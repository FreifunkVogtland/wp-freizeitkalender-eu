<?php

namespace freizeitkalender\eu;

/**
 * Enum für alle Optionen in der Datenbank
 */
class OptionEnum
{
    /**
     * @var string
     */
    private $value;

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
    private const MESSAGE_BUCKET = 'message-bucket';

    /**
     * OptionEnum constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return OptionEnum
     */
    public static function FILTER_ID(): OptionEnum
    {
        return new self(self::FILTER_ID);
    }

    /**
     * @return OptionEnum
     */
    public static function LIMIT(): OptionEnum
    {
        return new self(self::LIMIT);
    }

    /**
     * @return OptionEnum
     */
    public static function MESSAGE_BUCKET(): OptionEnum
    {
        return new self(self::MESSAGE_BUCKET);
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
     * @param string $value
     * @return OptionEnum
     */
    public static function create(string $value): OptionEnum
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
     * @return string[]
     */
    public static function getConstList(): array
    {
        $constList['FILTER_ID'] = self::FILTER_ID;
        $constList['LIMIT'] = self::LIMIT;
        $constList['MESSAGE_BUCKET'] = self::MESSAGE_BUCKET;

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
