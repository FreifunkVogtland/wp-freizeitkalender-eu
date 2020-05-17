<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace freizeitkalender\eu;

/**
 * Description of IntValidator
 *
 * @author philipp
 */
class FilterIdValidator
{
    //put your code here

    /**
     *
     * @var int|null
     */
    private $filterId;

    /**
     * @var MessageBucket
     */
    private $messageBucket;

    /**
     *
     * @var bool
     */
    private $isValid = false;

    /**
     *
     * @param string $inputValue
     */
    public function __construct(string $inputValue)
    {
        $this->messageBucket = new MessageBucket();
        $filterId = (int)$inputValue;
        if ($filterId <= 0 || $filterId > 10000) {
            $this->getMessageBucket()->addMessageItem(ErrorLevelEnum::ERROR(), 'Ungültigen Wert eingeben. Er sollte zwischen 0 und 10000 liegen.');
            return;
        }
        $filterInfo = CalendarService::getFilterInfo($filterId);

        if ($filterInfo->getMessageItem()->getErrorLevelEnum()->getValue() === ErrorLevelEnum::ERROR()->getValue()) {
            $this->getMessageBucket()->addMessage(ErrorLevelEnum::ERROR(), 'Filter Informationen konnten nicht geladen werden.');
            $this->getMessageBucket()->addMessage(ErrorLevelEnum::WARNING(), $filterInfo->getMessageItem()->getMessage());
            if ($filterInfo->getMessageItem()->getMessage() === 'Zugriff für Filter nicht erlaubt') {
                $this->getMessageBucket()->addMessage(ErrorLevelEnum::INFO(), 'Stimmt das Passwort?');
            }
        } else {
            $eventCount = $filterInfo->getEventCount();
            $countInfo = $eventCount . ' Einträgen';
            if ($eventCount === 1) {
                $countInfo = '1 Eintrag';
            }
            $this->getMessageBucket()->addMessage(ErrorLevelEnum::SUCCESS(), 'Filter "' . $filterInfo->getFilterName() . '" mit ' . $countInfo);
        }

        if (!$this->getMessageBucket()->hasErrorMessage()) {
            $this->setIsValid(true);
            $this->setFilterId($filterId);
        }
    }

    /**
     *
     * @return int
     */
    public function getFilterId(): int
    {
        return $this->filterId;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $istValid
     */
    private function setIsValid(bool $istValid): void
    {
        $this->isValid = $istValid;
    }

    /**
     * @param int $filterId
     */
    private function setFilterId(int $filterId): void
    {
        $this->filterId = $filterId;
    }

    /**
     * @return MessageBucket
     */
    public function getMessageBucket(): MessageBucket
    {
        return $this->messageBucket;
    }

}
