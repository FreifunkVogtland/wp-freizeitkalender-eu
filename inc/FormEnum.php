<?php

namespace freizeitkalender\eu;

/**
 * Enum für alle Optionen in der Datenbank
 */
class FormEnum
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private const NONCE_FIELD = 'nonce-field';

    /**
     * @var string
     */
    private const FILTER_ID = 'filter-id';

    /**
     * @var string
     */
    private const LIMIT = 'limit';

    /**
     * @var string
     */
    private const _WP_HTTP_REFERER = '_wp_http_referer';

    /**
     * FormEnum constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return FormEnum
     */
    public static function NONCE_FIELD(): FormEnum
    {
        return new self(self::NONCE_FIELD);
    }

    /**
     * @return FormEnum
     */
    public static function FILTER_ID(): FormEnum
    {
        return new self(self::FILTER_ID);
    }

    /**
     * @return FormEnum
     */
    public static function LIMIT(): FormEnum
    {
        return new self(self::LIMIT);
    }

    /**
     * @return FormEnum
     */
    public static function _WP_HTTP_REFERER(): FormEnum
    {
        return new self(self::_WP_HTTP_REFERER);
    }

    /**
     * @param string $value
     * @return FormEnum|null
     */
    public static function create(string $value): FormEnum
    {
        $cleanValue = PluginService::stripShortname($value);
        foreach (self::getConstList() as $_const => $_value) {
            if ($cleanValue === $_value) {
                return self::$_const();
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if (mb_strpos($this->value, '_') === 0) {
            return $this->value;
        }
        return PluginService::addShortname($this->value);
    }

    /**
     * @return string[]
     */
    public static function getConstList(): array
    {
        $constList['NONCE_FIELD'] = self::NONCE_FIELD;
        $constList['FILTER_ID'] = self::FILTER_ID;
        $constList['LIMIT'] = self::LIMIT;
        $constList['_WP_HTTP_REFERER'] = self::_WP_HTTP_REFERER;

        return $constList;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValidValue(string $value): bool
    {
        $cleanValue = PluginService::stripShortname($value);

        return in_array($cleanValue, self::getConstList(), true);
    }
}
