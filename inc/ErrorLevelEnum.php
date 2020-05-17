<?php

namespace freizeitkalender\eu;

/**
 * Enum für alle Optionen in der Datenbank
 */
class ErrorLevelEnum
{

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private const SUCCESS = 'success';

    /**
     * @var string
     */
    private const INFO = 'info';

    /**
     * @var string
     */
    private const WARNING = 'warning';

    /**
     * @var string
     */
    private const ERROR = 'error';

    /**
     * MessageEnum constructor.
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return ErrorLevelEnum
     */
    public static function SUCCESS(): ErrorLevelEnum
    {
        return new self(self::SUCCESS);
    }

    /**
     * @return ErrorLevelEnum
     */
    public static function INFO(): ErrorLevelEnum
    {
        return new self(self::INFO);
    }

    /**
     * @return ErrorLevelEnum
     */
    public static function WARNING(): ErrorLevelEnum
    {
        return new self(self::WARNING);
    }

    /**
     * @return ErrorLevelEnum
     */
    public static function ERROR(): ErrorLevelEnum
    {
        return new self(self::ERROR);
    }

    /**
     * @param string $value
     * @return ErrorLevelEnum
     */
    public static function create(string $value): ErrorLevelEnum
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
        $constList['SUCCESS'] = self::SUCCESS;
        $constList['INFO'] = self::INFO;
        $constList['WARNING'] = self::WARNING;
        $constList['ERROR'] = self::ERROR;

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