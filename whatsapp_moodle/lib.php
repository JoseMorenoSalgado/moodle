<?php
defined('MOODLE_INTERNAL') || die();

function local_whatsapp_moodle_before_footer() {
    global $PAGE;

    $countrycode = get_config('local_whatsapp_moodle', 'countrycode');
    $phonenumber = get_config('local_whatsapp_moodle', 'phonenumber');
    $defaultmessage = get_config('local_whatsapp_moodle', 'defaultmessage');

    if (empty($countrycode) || empty($phonenumber)) {
        return;
    }

    $message = urlencode($defaultmessage);
    $whatsappurl = "https://wa.me/{$countrycode}{$phonenumber}?text={$message}";

    echo <<<HTML
    <div id="whatsapp-button" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        <a href="{$whatsappurl}" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="Contact us on WhatsApp" style="width: 60px; height: 60px;">
        </a>
    </div>
    HTML;
}

function local_whatsapp_moodle_extend_navigation(global_navigation $nav) {
    // No need to add navigation items as the button will be available globally
}
