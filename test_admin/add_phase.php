<?php
/**
 * Prints an instance of mod_test.
 *
 * @package     mod_test
 * @copyright   2023@copyright
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
require_once($CFG->dirroot . '/mod/test/classes/form/phaseForm.php');


global $DB, $PAGE, $OUTPUT;

$PAGE->set_context(\context_system::instance());
$PAGE->set_title("Test Phase Management");
$context=context_system::instance();

$phase_id = optional_param('phase_id', 0, PARAM_INT);
$cm_id = optional_param('courseModule_id',0, PARAM_INT);

$eid = optional_param('eid', 0, PARAM_INT);
$PAGE->set_url(new moodle_url('/mod/test/test_admin/add_phase.php'));

$url = array(
    "phase_id" => $phase_id,
    "courseModule_id" => $cm_id,
);


$form = new phaseForm(new moodle_url('/mod/test/test_admin/add_phase.php', $url));

if ($eid) {
    $phase = $DB->get_record("exam_phases", ["id" => $eid]);

} else {
    $phase = new stdClass();
    $phase->id = null;
}

if ($eid) {
    $formData = file_prepare_standard_editor($phase, 'description', management_editor(), $context, 'mod_test', 'phase_description', $phase->id);
    $form->set_data($formData);
}

if ($form->is_cancelled()) {
    redirect("/mod/test/test_admin/manage_exam.php", "Form Cancelled!");

} else if ($formData = $form->get_data()) {

    $formData->phase_id = $phase_id;
    $formData->cm_id=$cm_id;

    $record = new stdClass();
    $record->cm_id= $formData->cm_id;
    $record->phase_id = $phase_id;
    $record->phase_type = $formData->phase_type;
    $record->description = "";
    $record->is_first = $formData->is_first;

    if ($formData->id) {
        $record->timemodified = time();
        $record->id = $formData->id;
        if (!empty($formData->description_editor)) {
            $record->description_editor = $formData->description_editor;
            $record = file_postupdate_standard_editor($record, 'description', management_editor(), $context, 'mod_test', 'phase_description', $record->id);
            
        }
        $DB->update_record('exam_phases', $record);
        redirect("/mod/test/test_admin/manage_exam.php?phase_id=".$phase_id, "Data updated!");
    } else {
        $record->timecreated = time();
        $record->serial = getNewSerial();
        $record->id = $DB->insert_record('exam_phases', $record);

        if (!empty($formData->description_editor)) {
            $record->description_editor = $formData->description_editor;
            $record = file_postupdate_standard_editor($record, 'description', management_editor(), $context, 'mod_test', 'phase_description', $record->id);
           
        }
        $DB->update_record('exam_phases', $record);
        redirect("/mod/test/test_admin/add_mcq.php?courseModule_id=" . $cm_id, "Add Questions");
        
    }
}

if ($eid != 0) {

    $form->set_data($toform);
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();