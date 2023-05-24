<?php


$cm_id = optional_param('cm_id', 0, PARAM_INT);
if ($cm_id) {
    $cm = get_coursemodule_from_id('management', $cm_id, 0, false, MUST_EXIST);
    $cmid_url_param = "cm_id={$cm_id}";
    $context = context_module::instance($cm->id);
} else {
    $context = null;
    $cmid_url_param = "";
}