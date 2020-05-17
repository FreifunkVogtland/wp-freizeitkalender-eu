<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace freizeitkalender\eu;

use DateTime;
use DOMDocument;
use DOMElement;
use WP_REST_Request;

/**
 * Description of CalendarService
 *
 * @author philipp
 */
class CalendarService
{

    /**
     * CURL Request auf freizeitkalender.eu XML Schnittstelle
     *
     * @param string $url
     * @return DOMDocument
     */
    private static function makeCurlRequest(string $url): DOMDocument
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $doc = new DOMDocument();
        $doc->loadXML($output);

        return $doc;
    }

    /**
     * @param DOMDocument $domDocument
     * @return bool
     */
    private static function isValidDomDocument(DOMDocument $domDocument): bool
    {
        return ($domDocument->getElementsByTagName('out')->length !== 0);
    }

    /**
     * @param DOMDocument $domDocument
     * @return string
     */
    private static function getDomErrorMessage(DOMDocument $domDocument): string
    {
        $domElementError = $domDocument->getElementsByTagName('error')->item(0);
        if ($domElementError instanceof DOMElement) {
            $domElementMessage = $domElementError->getElementsByTagName('message')->item(0);
            if ($domElementMessage instanceof DOMElement) {
                return $domElementMessage->nodeValue;
            }
        }
        return '';
    }

    /**
     * @param string $eventCategory
     * @return string
     */
    private static function getIconClass(string $eventCategory): string
    {
        switch ($eventCategory) {
            case 'Party und Tanz':
                return 'calendar_today';
            case 'Bühne':
            case 'Konzert':
                return 'music_note';
            case 'Film':
                return 'movie';
            case 'Sport und Aktiv':
                return 'sports_soccer';
            case 'Wissen und Bildung':
                return 'school';
            case 'Ausstellung und Messe':
                return 'account_balance';
            case 'Fest und Markt':
                return 'store_front';
            default:
                return 'event';
        }
    }

    /**
     * @param DOMElement $event_entry
     * @return array
     */
    private static function getEventEntry(DOMElement $event_entry): array
    {
        $event_title = $event_entry->getElementsByTagName('title')->item(0)->nodeValue;
        $event_id = $event_entry->getElementsByTagName('id')->item(0)->nodeValue;
        $event_category = $event_entry->getElementsByTagName('categories')->item(0)->nodeValue;
        $icon_class = self::getIconClass($event_category);

        $location = $event_entry->getElementsByTagName('location')->item(0);
        $event = [
            'id' => $event_id,
            'title' => $event_title,
            'subtitle' => $event_entry->getElementsByTagName('subtitle')->item(0)->nodeValue,
            'datebegin' => $event_entry->getElementsByTagName('datebegin')->item(0)->nodeValue,
            'timebegin' => $event_entry->getElementsByTagName('timebegin')->item(0)->nodeValue,
            'formatteddate' => $event_entry->getElementsByTagName('formatteddate')->item(0)->nodeValue,
            'icon_class' => $icon_class,
            'event_category' => $event_category,
            'external_url' => 'https://www.freizeitkalender.eu/out/show/id/' . $event_id,
            'location' => [],
        ];
        if ($location instanceof DOMElement) {
            $event['location'] = [
                'id' => $location->getElementsByTagName('id')->item(0)->nodeValue,
                'name' => $location->getElementsByTagName('name')->item(0)->nodeValue,
                'address' => $location->getElementsByTagName('address')->item(0)->nodeValue,
                'city' => $location->getElementsByTagName('city')->item(0)->nodeValue,
                'zipcode' => $location->getElementsByTagName('zipcode')->item(0)->nodeValue,
            ];
        }

        $event['datebegin'] = implode('.', array_reverse(explode('-', $event['datebegin'])));
        $event['description'] = '';
        $event['is_description_loaded'] = false;

        return $event;
    }

    /**
     * Liste an Events holen
     *
     * @param WP_REST_Request $rest_request
     * @return array
     */
    public static function getEventList(WP_REST_Request $rest_request): array
    {
        $filterId = self::loadFilterId();
        $year_month_string = (string)$rest_request->get_param('year_month');
        if ($year_month_string === '') {
            $year_month_string = (new DateTime())->format('Y-m');
        }
        $limit = (int)$rest_request->get_param('limit');
        if ($limit === 1) {
            $limit = self::loadLimit();
            $doc = self::makeCurlRequest('https://www.freizeitkalender.eu/out/xml/filter/' . $filterId . '/limit/' . $limit);
        } else {
            $doc = self::makeCurlRequest('https://www.freizeitkalender.eu/out/xml/filter/' . $filterId . '/month/' . $year_month_string);
        }

        $event_list = [];
        $previousDate = '';
        while (($event_entry = $doc->getElementsByTagName('event')->item(0)) !== null) {
            if (!$event_entry instanceof DOMElement) {
                $event_entry->parentNode->removeChild($event_entry);
                continue;
            }
            $eventEntryData = self::getEventEntry($event_entry);
            $eventDateBegin = $eventEntryData['datebegin'];
            if ($eventDateBegin !== $previousDate) {
                $isNewDate = true;
                $previousDate = $eventDateBegin;
            } else {
                $isNewDate = false;
            }
            $eventEntryData['is_new_date'] = $isNewDate;
            $event_list[] = $eventEntryData;
            $event_entry->parentNode->removeChild($event_entry);
        }

        return $event_list;
    }

    /**
     * Details zu einem Event holen
     *
     * @param WP_REST_Request $rest_request
     * @return array
     */
    public static function getEventDetail(WP_REST_Request $rest_request): array
    {
        $event_detail = [
            'description' => 'Fehler beim Laden der Beschreibung'
        ];
        $event_id = (int)$rest_request->get_param('event_id');
        if (!is_int($event_id) || $event_id <= 0) {
            return $event_detail;
        }
        $filterId = self::loadFilterId();
        $doc = self::makeCurlRequest('https://www.freizeitkalender.eu/out/xml/filter/' . $filterId . '/event/' . urlencode((string)$event_id));
        $event_entry = $doc->getElementsByTagName('description')->item(0);
        if ($event_entry instanceof DOMElement) {
            $event_detail = [
                'description' => $event_entry->nodeValue,
            ];
        }

        return $event_detail;
    }

    /**
     * Filter Informationen holen für Validierung
     *
     * @param int $filterId
     * @return CalendarFilterInfo
     */
    public static function getFilterInfo(int $filterId): CalendarFilterInfo
    {
        $calendarFilterInfo = new CalendarFilterInfo();
        $domDocument = self::makeCurlRequest('https://www.freizeitkalender.eu/out/xml/filter/' . $filterId . '/limit/1');
        if (!self::isValidDomDocument($domDocument)) {
            $calendarFilterInfo->setMessageItem(ErrorLevelEnum::ERROR(), 'Die Filter ID scheint nicht gültig zu sein.');
            return $calendarFilterInfo;
        }

        $errorMessage = self::getDomErrorMessage($domDocument);
        if ($errorMessage !== '') {
            $calendarFilterInfo->setMessageItem(ErrorLevelEnum::ERROR(), $errorMessage);
            return $calendarFilterInfo;
        }

        $domElementFilter = $domDocument->getElementsByTagName('filter')->item(0);
        $domElementWhere = $domDocument->getElementsByTagName('where')->item(0);

        $calendarFilterInfo->setMessageItem(ErrorLevelEnum::WARNING(), 'Filter information could not be found!');
        if ($domElementFilter instanceof DOMElement && $domElementWhere instanceof DOMElement) {
            $filterName = $domElementFilter->getElementsByTagName('name')->item(0)->nodeValue;
            $calendarFilterInfo->setFilterName($filterName);

            $eventCount = (int)$domElementWhere->getElementsByTagName('numberofevents')->item(0)->nodeValue;
            $calendarFilterInfo->setEventCount($eventCount);
            $calendarFilterInfo->setMessageItem(ErrorLevelEnum::SUCCESS(), 'Filter information successfully loaded.');
        }

        return $calendarFilterInfo;
    }

    /**
     * @return int
     */
    public static function loadFilterId(): int
    {
        return (int)OptionService::getOption(OptionEnum::FILTER_ID(), '4');
    }

    /**
     * @return int
     */
    public static function loadLimit(): int
    {
        return (int)OptionService::getOption(OptionEnum::LIMIT(), '5');
    }

    /**
     * @param int $filterId
     */
    public static function saveFilterId(int $filterId): void
    {
        OptionService::updateOption(OptionEnum::FILTER_ID(), (string)$filterId);
    }

    /**
     * @param int $limit
     */
    public static function saveLimit(int $limit): void
    {
        OptionService::updateOption(OptionEnum::LIMIT(), (string)$limit);
    }

    /**
     * Service initialisierem
     */
    public static function init(): void
    {
        add_action('rest_api_init', static function () {
            register_rest_route('freizeitkalender-eu/v2', '/event-list/', array(
                'methods' => 'GET',
                'callback' => [__CLASS__, 'getEventList'],
            ));
            register_rest_route('freizeitkalender-eu/v2', '/event-list-by-month/(?P<year_month>(20|21)\d{2}-(0[1-9]|1[012]))', array(
                'methods' => 'GET',
                'callback' => [__CLASS__, 'getEventList'],
                'args' => array(
                    'year_month'
                ),
            ));
            register_rest_route('freizeitkalender-eu/v2', '/event-list-by-limit/(?P<limit>\d+$)', array(
                'methods' => 'GET',
                'callback' => [__CLASS__, 'getEventList'],
                'args' => array(
                    'year_month'
                ),
            ));
            register_rest_route('freizeitkalender-eu/v2', '/event-detail/(?P<event_id>\d+$)', array(
                'methods' => 'GET',
                'callback' => [__CLASS__, 'getEventDetail'],
                'args' => array(
                    'event_id'
                ),
            ));
        });
    }

}
