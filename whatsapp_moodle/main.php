<?php
namespace local_whatsapp_moodle\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

class main implements renderable, templatable {
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->defaultmessage = get_config('local_whatsapp_moodle', 'defaultmessage');
        return $data;
    }
}
