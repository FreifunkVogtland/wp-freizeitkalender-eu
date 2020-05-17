<?php

namespace freizeitkalender\eu;

/**
 * Plugin Name:  Freizeitkalender Eu Plugin
 * Plugin URI: https://www.freizeitkalender.eu/
 * Description: Plugin, um den Vogtländischen Freizeitkalender einbinden zu können.
 * Version: 2.1.0a
 * Author: Philipp Grafe
 * Author URI:
 * License: GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  freizeitkalender-eu
 */

defined('ABSPATH') or die('');

foreach (glob(plugin_dir_path(__FILE__) . 'inc/*.php') as $file) {
    include_once $file;
}

PluginService::init();
