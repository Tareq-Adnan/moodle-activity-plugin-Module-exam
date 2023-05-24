<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_test.
 *
 * @package     mod_test
 * @copyright   2023@copyright
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
global $DB,$PAGE,$OUTPUT;

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$t = optional_param('t', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('test', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('test', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('test', array('id' => $t), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('test', $moduleinstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);


// $event = \mod_test\event\course_module_viewed::create(
//     array(
//         'objectid' => $moduleinstance->id,
//         'context' => $modulecontext
//     )
// );

$PAGE->set_url('/mod/test/view.php', array('id' => $cm->id));
$PAGE->set_title("Test Activity");
$PAGE->set_heading("Activity heading");
// $event->add_record_snapshot('course', $course);
// $event->add_record_snapshot('test', $moduleinstance);
// $event->trigger();

echo $OUTPUT->header();
$Display=[
    'editTest'=> 'Edit Test',
    'editTest_url'=>new moodle_url('/mod/test/test_admin/manage_exam.php'),
    'score'=> 'Test Score',
    'score_url'=>new moodle_url('/mod/test/test/testScore.php'),
    'attemptT0Test'=> 'Attempt Test',
    'attempt_url'=>new moodle_url('/mod/test/test/test.php'),
    'cm_id'=>$cm->id,
    'phase_id'=>$moduleinstance->id,
];
echo $OUTPUT->render_from_template('mod_test/index', $Display);

echo $OUTPUT->footer();