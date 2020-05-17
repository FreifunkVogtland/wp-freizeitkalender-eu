<?php

namespace freizeitkalender\eu;

defined('ABSPATH') or die('');

?>
<div id="freizeitkalender-eu">
    <div class="freizeitkalender-eu-menu">
        <div v-on:click="set_prev_month()" title="Vorheriger Monat"><i class="material-icons">chevron_left</i></div>
        <div v-on:click="set_current_month()" title="Aktueller Monat"><i class="material-icons">calendar_today</i><span class="freizeitkalender-eu-ml-5">{{get_selected_date_for_display()}}</span></div>
        <div v-on:click="set_next_month()" title="Nächster Monat"><i class="material-icons">chevron_right</i></div>
    </div>
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
        <div v-for="(kalender_event, index) in kalender_event_list" class="freizeitkalender-eu-event">
            <hr v-if="kalender_event.is_new_date" class="freizeitkalender-eu-event-new-date" v-bind:data-content="kalender_event.datebegin"/>
            <div class="freizeitkalender-eu-event-icon" v-bind:title="kalender_event.event_category">
                <i class="material-icons">{{kalender_event.icon_class}}</i>
            </div>
            <div>
                <h2>{{ kalender_event.title }}</h2>
                <h3>{{ kalender_event.subtitle }}</h3>
            </div>
            <div class="freizeitkalender-eu-event-detail">
                {{ kalender_event.formatteddate }}<br/>
                {{ kalender_event.location.name }}<br/>
                {{ kalender_event.location.address }}, {{ kalender_event.location.zipcode }}, {{ kalender_event.location.city }}
                <div v-if="kalender_event.is_description_loaded === false" class="freizeitkalender-eu-event-description" v-on:click="load_kalender_event_description(index)"><a class="freizeitkalender-eu-more-link">Weiterlesen<span class="meta-nav"> →</span></a></div>
                <div v-else class="freizeitkalender-eu-event-description">{{ kalender_event.description }}</div>
            </div>
        </div>
    </div>
</div>
<div class="freizeitkalender-eu-info">
    <div class="freizeitkalender-eu-info-text">
        <p>Die Zentrale Veranstaltungsdatenbank Vogtlandkreis wurde gefördert aus Mitteln der Gemeinschaftsaufgabe „Verbesserung der regionalen Wirtschaftsstruktur“ durch den Freistaat Sachsen.</p>
        <p><strong>© Landratsamt Vogtlandkreis</strong></p>
    </div>
    <div class="freizeitkalender-eu-info-picture">
        <a href="https://www.freizeitkalender.eu" target="_blank"><img src="<?= PluginService::getPluginUrl() ?>/img/freizeitkalender-logo.png" alt="Freizeitkalender"/></a>    
    </div>
    <div class="freizeitkalender-eu-info-picture">
        <a href="https://www.vogtlandkreis.de/" target="_blank"><img src="<?= PluginService::getPluginUrl() ?>/img/vogtlandkreis-claim.png" alt="Vogtlandkreis"/></a>    
    </div>
</div>
