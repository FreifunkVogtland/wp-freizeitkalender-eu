<?php

namespace freizeitkalender\eu;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

foreach (glob(plugin_dir_path(__FILE__) . 'inc/*.php') as $file) {
    include_once $file;
}

foreach (OptionEnum::getConstList() as $optionValue) {
    $optionEnum = OptionEnum::create($optionValue);
    if (mb_strpos($optionEnum->getValue(), PluginService::getShortname()) === 0) {
        delete_option($optionEnum->getValue());
    }
}


