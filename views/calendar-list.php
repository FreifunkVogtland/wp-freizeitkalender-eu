<?php

namespace freizeitkalender\eu;

defined('ABSPATH') or die('');

?>
<div id="freizeitkalender-eu-list">
    <div class="freizeitkalender-eu-liste">
        <div v-if="kalender_event_list.length === 0 && !kalender_event_list_loading" class="freizeitkalender-eu-info-none">
            Leider keine Termine diesen Monat. Schauen Sie mal im Freizeitkalender nach, ob es noch andere Veranstaltungen gibt.
        </div>
        <div v-if="kalender_event_list_loading" class="freizeitkalender-eu-info-loading">
            <section class="freizeitkalender-eu-loading-circle-section" title="https://steemit.com/deutsch/@snackaholic/css-tutorial-wie-man-eine-ladeanimation-erstellt">
                <div class="freizeitkalender-eu-loading-circle0 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle1 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle2 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle3 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle4 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle5 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle6 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle7 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle8 freizeitkalender-eu-loading-circle"></div>
                <div class="freizeitkalender-eu-loading-circle9 freizeitkalender-eu-loading-circle"></div>
            </section>
        </div>
        <div v-for="(kalender_event, index) in kalender_event_list">
            <p>
                <a v-bind:href="kalender_event.external_url" target="_blank" title="Weitere Details">{{ kalender_event.title }}</a><br/>
                {{ kalender_event.formatteddate }} bei {{ kalender_event.location.name }} in {{ kalender_event.location.address }}, {{ kalender_event.location.zipcode }}, {{ kalender_event.location.city }}
            </p>
        </div>
    </div>
</div>
<div class="freizeitkalender-eu-info">
    <div class="freizeitkalender-eu-info-text">
        <p><a href="https://www.freizeitkalender.eu" target="_blank" title="Freizeitkalender - Die Zentrale Veranstaltungsdatenbank Vogtlandkreis wurde gefördert aus Mitteln der Gemeinschaftsaufgabe „Verbesserung der regionalen Wirtschaftsstruktur“ durch den Freistaat Sachsen. - © Landratsamt Vogtlandkreis"><img src="<?= PluginService::getPluginUrl() ?>/img/freizeitkalender-logo.png" alt="Freizeitkalender"/></a></p>
        <p><a href="https://www.vogtlandkreis.de/" target="_blank" title="Landratsamt"><img src="<?= PluginService::getPluginUrl() ?>/img/vogtlandkreis-claim.png" alt="Vogtlandkreis"/></a></p>
    </div>
</div>
