<?php

namespace freizeitkalender\eu;

defined('ABSPATH') or die('');

$calenderFilterInfo = CalendarService::getFilterInfo(CalendarService::loadFilterId());

?>

<div class="wrap">

    <?= MessageService::renderMessage() ?>

    <h1><?= esc_html(get_admin_page_title()) ?></h1>

    <form method="post" action="<?= esc_html(admin_url('admin-post.php')) ?>">

        <div id="universal-message-container">
            <h2>Kalender Einstellungen</h2>

            <div class="options">
                <?= FormService::renderFilterIdInput('Filter ID für den Kalender') ?>
                <?= FormService::renderLimitInput('Limit für Listenansicht') ?>
                <p>
                    Filtername: <?= esc_html($calenderFilterInfo->getFilterName()) ?>
                    <br/>
                    Anstehende Veranstaltungen: <?= esc_html($calenderFilterInfo->getEventCount()) ?>
                </p>
            </div>
            <?= FormService::renderWpHiddenFields() ?>
            <?= get_submit_button() ?>
        </div>
    </form>

    <p>Den Kalender gibt es in zwei Varianten. Einmal in der Detailansicht für Kategorien, Seiten oder Beiträge mit dem Shortcode [<?= PluginService::SHORTCODE_DETAIL ?>]</p>
    <p>Für Widgets in Sidebars kann eine Listenansicht genommen werden. Shortcode [<?= PluginService::SHORTCODE_LIST ?>]</p>

</div><!-- .wrap -->
