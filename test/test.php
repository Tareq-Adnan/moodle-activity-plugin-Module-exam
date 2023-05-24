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

require(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
require_once($CFG->dirroot . '/mod/test/classes/form/question.php');
require_once($CFG->dirroot . '/mod/test/classes/form/examForm.php');
global $DB, $PAGE, $OUTPUT;

// Course module id.
$cm_id = optional_param('courseModule_id', 0, PARAM_INT);
$phase_id = optional_param('phase_id', 0, PARAM_INT);
$_SESSION['phase_id']=$phase_id;
// // Activity instance id.
$t = optional_param('t', 0, PARAM_INT);
$PAGE->set_context(\context_system::instance());
$PAGE->set_url('/mod/test/classes/test.php');
$PAGE->set_title("Initial Test");


if($data=$DB->get_record("exam_phases",['cm_id'=>$cm_id]))
{}else{
    $data=new stdClass();
    $data->description="";
    $data->phase_id="";
    $data->cm_id="";
}




echo $OUTPUT->header();

$Display = [
    'editTest' => 'Edit Test',
    'editTest_url' => new moodle_url('/mod/test/classes/form/inputEdit.php'),
    'score' => 'Test Score',
    'score_url' => new moodle_url('/mod/test/test/testScore.php'),
    'attemptT0Test' => 'Attempt Test',
    'attempt_url' => new moodle_url('/mod/test/test/test.php'),
    'testData'=>$data->description,
    'next'=>$data->phase_id,
    'cm_id'=>$data->cm_id,
    
];

$quesData=$DB->get_records('exam_question');
$option=$DB->get_records('question_options');


$ques=[
    'question'=>array_values($quesData),
   
];
$form=new examForm();
 if($phase_id!= 0){
   
   // if(getPhaseId($phase_id)==$phase_id){
        $form->display();
 //   }
}else{
    if($cm_id==$data->cm_id){
        echo $OUTPUT->render_from_template('mod_test/test', $Display);
    }else{
        echo "<h1 class='text-center'>No Question are Set</h1>";
    }
   
}



echo $OUTPUT->footer();