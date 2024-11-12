<?php
require_once('../../config.php');
require_login();

$defaultmessage = get_config('local_whatsapp_moodle', 'defaultmessage');
$countrycode = get_config('local_whatsapp_moodle', 'countrycode');
$phonenumber = get_config('local_whatsapp_moodle', 'phonenumber');
$message = optional_param('message', $defaultmessage, PARAM_TEXT);

if (empty($countrycode) || empty($phonenumber)) {
    print_error('whatsapp:configerror', 'local_whatsapp_moodle');
}

$whatsappurl = "https://wa.me/{$countrycode}{$phonenumber}?text=" . urlencode($message);

redirect($whatsappurl);
