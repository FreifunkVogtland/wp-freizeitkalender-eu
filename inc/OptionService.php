<?php

namespace freizeitkalender\eu;

class OptionService
{
    /**
     * Holt einen Wert aus der DB
     * @param OptionEnum $optionEnum
     * @param string $defaultValue
     * @return string
     */
    public static function getOption(OptionEnum $optionEnum, string $defaultValue): string
    {
        return get_option($optionEnum->getValue(), $defaultValue);
    }

    /**
     * Speichert einen Wert in der DB
     *
     * @param OptionEnum $optionEnum
     * @param string $value
     */
    public static function updateOption(OptionEnum $optionEnum, string $value): void
    {
        update_option($optionEnum->getValue(), $value);
    }
}