<?php

namespace freizeitkalender\eu;

/**
 * Grundlegende Einstellungen für das Plugin
 */
class PluginService
{
    /**
     * @var string
     */
    public const PLUGIN_VERSION = '2.1.0a';

    /**
     * @var string
     */
    public const SHORTCODE_DETAIL = 'freizeitkalender-eu';

    /**
     * @var string
     */
    public const SHORTCODE_LIST = 'freizeitkalender-eu-list';

    /**
     * Init all
     */
    public static function init(): void
    {
        FormService::init();
        add_action('admin_menu', [__CLASS__, 'addAdminMenu']);

        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
        add_shortcode(self::SHORTCODE_DETAIL, [__CLASS__, 'getDetailHtml']);
        add_shortcode(self::SHORTCODE_LIST, [__CLASS__, 'getListHtml']);
        CalendarService::init();
    }

    /**
     * Local oder Live?
     * @return bool
     */
    private static function isProductionServer(): bool
    {
        $urlPartList = parse_url(home_url());
        $hostPartList = explode('.', $urlPartList['host']);

        return end($hostPartList) === 'de';
    }

    /**
     * Adminmenu einbinden
     */
    public static function addAdminMenu()
    {
        // https://code.tutsplus.com/series/creating-custom-wordpress-administration-pages--cms-1062
        // https://wordpress.org/support/article/roles-and-capabilities/ 'manage_options'
        add_options_page(self::getName() . ' Settings', self::getName(), 'manage_options', self::getShortname() . 'admin-page.php', [__CLASS__, 'addAdminView'], 4);
    }

    /**
     * Adminseite einbinden
     */
    public static function addAdminView(): void
    {
        include_once self::getPluginPath() . 'views/admin-page.php';
    }

    /**
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'Freizeit Kalender Eu';
    }

    /**
     *
     * @return string
     */
    public static function getShortname(): string
    {
        return 'fzkldeu-';
    }

    /**
     *
     * @return string
     */
    public static function getPluginPath(): string
    {
        return plugin_dir_path(__DIR__);
    }

    /**
     *
     * @return string
     */
    public static function getPluginUrl(): string
    {
        return plugins_url('', __DIR__);
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public static function stripShortname(string $value): string
    {
        if (mb_strpos($value, self::getShortname()) === 0) {
            return mb_substr($value, mb_strlen(self::getShortname()));
        }

        return $value;
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public static function addShortname(string $value): string
    {
        return self::getShortname() . $value;
    }

    #region Kalender

    /**
     * CSS und JS laden
     */
    public static function enqueue_scripts()
    {
        $cache_buster = self::PLUGIN_VERSION;
        // Register Material Design Font
        wp_register_style('google-material-icons', plugins_url('material-icons-font-20200325/css/baseline.css', __FILE__), [], $cache_buster);

        // Register own style
        wp_register_style('freizeitkalender-eu-style', plugins_url('../css/freizeitkalender-eu.css', __FILE__), ['google-material-icons'], $cache_buster);

        // Enqueue style
        wp_enqueue_style('freizeitkalender-eu-style');

        // Register Vue.js
        if (self::isProductionServer()) {
            wp_register_script('vue-js', plugins_url('vue-2.6.11/vue.min.js', __FILE__), [], $cache_buster);
        } else {
            wp_register_script('vue-js', plugins_url('vue-2.6.11/vue.js', __FILE__), [], $cache_buster);
        }

        // Register own script with depency vue-js
        wp_register_script('freizeitkalender-eu-script', plugins_url('../js/freizeitkalender-eu.js', __FILE__), ['vue-js'], $cache_buster);

        // Enqueue the script
        wp_enqueue_script('freizeitkalender-eu-script');

        // Register own script with depency vue-js
        wp_register_script('freizeitkalender-eu-list-script', plugins_url('../js/freizeitkalender-eu-list.js', __FILE__), ['freizeitkalender-eu-script'], $cache_buster);

        // Enqueue the script
        wp_enqueue_script('freizeitkalender-eu-list-script');
    }

    /**
     * Nötiges HTML ausgeben
     *
     * @return string
     */
    public static function getDetailHtml(): string
    {
        ob_start();
        include_once self::getPluginPath() . '/views/calendar-full.php';
        return ob_get_clean();
    }

    /**
     * Nötiges HTML ausgeben
     *
     * @return string
     */
    public static function getListHtml(): string
    {
        ob_start();
        include_once self::getPluginPath() . '/views/calendar-list.php';
        return ob_get_clean();
    }

    #endregion

}
