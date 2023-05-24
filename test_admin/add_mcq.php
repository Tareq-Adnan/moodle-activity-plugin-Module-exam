<?php
/**
 * Prints an instance of mod_test.
 *
 * @package     mod_test
 * @copyright   2023@copyright
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


 global $DB, $PAGE, $OUTPUT;
require(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../lib.php');
require_once ($CFG->dirroot . '/mod/test/classes/form/question.php');
require_once ($CFG->dirroot . '/mod/test/classes/form/option.php');

$phase_id=optional_param("phase_id", 0, PARAM_INT);
$cm_id=optional_param("courseModule_id", 0, PARAM_INT);
$question_id=optional_param('qid',0,PARAM_INT);
$eid=optional_param('eid', null, PARAM_INT);
$addOp=optional_param('addOp',0,PARAM_INT);
$vid=optional_param('vid',0,PARAM_INT);
$editQuestionOption=optional_param('editoption',0,PARAM_INT);
$deleteQuestion=optional_param('deleteQuestion', 0, PARAM_INT);
$delOptionid=optional_param('delOptionid', 0, PARAM_INT);

$PAGE->set_context(\context_system::instance());
$PAGE->set_url(new moodle_url('/mod/test/test_admin/add_mcq.php'));
$PAGE->set_title("Add Questions");

$url=[
    'phase_id'=>$phase_id,
    'cm_id'=>$cm_id,
    'qid'=>$question_id
];
$form =new question(new moodle_url('/mod/test/test_admin/add_mcq.php',$url));

if($form->is_cancelled()){
    $link=$PAGE->url;
   redirect(new moodle_url($link."?phase_id=".$phase_id));
}else if($formData=$form->get_data()){

    $data=new stdClass();
    $data->phase_id=$phase_id;
    $data->question_title=$formData->question_title;
    if($formData->id){
        $data->id=$formData->id;
        $data->timetimemodified=time();
        $DB->update_record('exam_question', $data);
        redirect(new moodle_url($PAGE->url."?phase_id=".$phase_id));
    }else{
        $data->timecreated=time();
        $DB->insert_record('exam_question', $data);
        redirect(new moodle_url($PAGE->url."?phase_id=".$phase_id));
    }

}
if($eid){
    $form->set_data($DB->get_record('exam_question',['id'=>$eid]));
}
if($deleteQuestion!=0){
    $DB->delete_records('question_options', ['question_id'=>$deleteQuestion]);
    $DB->delete_records('exam_question',['id'=>$deleteQuestion]);
}else if($delOptionid){
    $DB->delete_records('question_options', ['id'=>$delOptionid]);
    redirect(new moodle_url($PAGE->url."?phase_id=".$phase_id));
}

// $question=$DB->get_records('exam_question');
$option=$DB->get_records('question_options');

if($vid!=0){
    $sql="SELECT * FROM {question_options} WHERE question_id = :id";
    $option=$DB->get_records_sql($sql,['id'=>$vid]);
}else{
    $option=[];
}


$sql="SELECT * FROM {exam_question} WHERE phase_id = :id";
$question=$DB->get_records_sql($sql,['id'=>$phase_id]);

$data=[
    'question'=>array_values($question),
    'addOption'=>new moodle_url('/mod/test/test_admin/add_mcq_option.php'),
    'option'=>array_values($option),
    "p_id"=>$phase_id,
    'vid'=>$vid,
    'addOp'=>$addOp,
];


echo $OUTPUT->header();
echo "<h1>Add Questions</h1><br><hr>";
$form->display();
echo "<hr>";
echo $OUTPUT->render_from_template('mod_test/question', $data);

if($addOp!=0 || $editQuestionOption ){
    echo "<hr>";
    
    $url=[
        'addOp'=>$addOp,
        'phase_id'=>$phase_id
    ];
    $addForm=new option(new moodle_url('/mod/test/test_admin/add_mcq.php',$url));

    if($editQuestionOption){
        $addForm->set_data($DB->get_record('question_options',['id'=>$editQuestionOption]));
    }

    if($addForm->is_cancelled()){
        redirect(new moodle_url($PAGE->url."?phase_id=".$phase_id));
    }
    else if($formData=$addForm->get_data()){
        $data=new stdClass();
        if($editQuestionOption){
            $data->question_id=$vid;
        }else{
            $data->question_id=$addOp;
        }
       
        $data->option_title=$formData->option_title;
        $data->is_valid=$formData->is_valid;

        if($formData->id !=""){
           
            $data->id=$formData->id;
            $data->timemodified=time();
            $DB->update_record('question_options', $data);
            redirect(new moodle_url($PAGE->url."?phase_id=".$phase_id),"updated");
        }else{
           
            $data->timecreated=time();
            $DB->insert_record('question_options', $data);
            redirect(new moodle_url($PAGE->url."?phase_id=".$phase_id),"Inserted");
        }

    }

    $addForm->display(); 
}





echo $OUTPUT->footer();