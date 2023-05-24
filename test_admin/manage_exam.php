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
global $DB, $PAGE, $OUTPUT;

$cm_id = optional_param('courseModule_id',0, PARAM_INT);
$phase_id = optional_param('phase_id',0, PARAM_INT);
$delid=optional_param('delid', 0, PARAM_INT);

$PAGE->set_context(\context_system::instance());
$PAGE->set_url(new moodle_url('/mod/test/test_admin/manage_exam.php'));
$PAGE->set_title("Test Phase Management");

if($delid!=0){
    $DB->delete_records('exam_phases', ['id'=>$delid]);
}

$sql="SELECT * FROM {exam_phases} WHERE phase_id = :id";
$data=$DB->get_records_sql($sql,['id'=>$phase_id]);
echo $OUTPUT->header();

$data = [
    'addPhase' => new moodle_url("/mod/test/test_admin/add_phase.php"),
    'cm_id'=>$cm_id,
    'phase_id'=>$phase_id,
    'info'=>array_values($data),
    'pageLink'=>new moodle_url('/mod/test/test_admin/manage_exam.php'),
    'showQues'=>new moodle_url('/mod/test/test_admin/add_mcq.php'),
    
];
echo $OUTPUT->render_from_template("mod_test/exam_phase", $data);
echo $OUTPUT->footer();