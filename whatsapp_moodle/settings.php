<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_whatsapp_moodle', get_string('pluginname', 'local_whatsapp_moodle'));

    $settings->add(new admin_setting_configtext('local_whatsapp_moodle/defaultmessage', get_string('defaultmessage', 'local_whatsapp_moodle'), '', 'Hola, necesito más información'));
    $settings->add(new admin_setting_configtext('local_whatsapp_moodle/countrycode', get_string('countrycode', 'local_whatsapp_moodle'), '', ''));
    $settings->add(new admin_setting_configtext('local_whatsapp_moodle/phonenumber', get_string('phonenumber', 'local_whatsapp_moodle'), '', ''));

    $ADMIN->add('localplugins', $settings);
}
