<?php

require_once("$CFG->libdir/formslib.php");

class examForm extends moodleform{

    

    public function definition(){
        global $CFG,$OUTPUT,$DB;

       $form = $this->_form;
       $sql="SELECT * FROM {exam_question} WHERE phase_id= :id";
       $question=$DB->get_records_sql($sql,['id'=>$_SESSION['phase_id']]);
      if($phaseType=$DB->get_record('exam_phases',['phase_id'=>$_SESSION['phase_id']])){}
      else{
        $phaseType=new stdClass();
        $phaseType->phase_type="";
        
      }
      // $phaseType=$DB->get_record('exam_phases',['phase_id'=>$_SESSION['phase_id']]);
       $option=$DB->get_records("question_options");

        if($phaseType->phase_type=="mcq"){
            foreach($question as $q){
                $form->addElement('html',  '<div class="card p-2 my-2">');
                $form->addElement('html',  '<h5>'.$q->question_title."</h5>");
                 foreach($option as $op){
                     if($q->id ==$op->question_id){
                         $form->addElement('html',  '<div class="d-inline-flex mx-2">');
                         $form->addElement('radio', $q->question_title , $op->option_title);
                         $form->setDefault($q->question_title, "");
         
                         $form->addElement('html',  '</div>');
                     }
                 }
                 $form->addElement('html',  '</div>');
                }
        }else{
            $form->addElement('textarea',  'introduction');
        }
       

       $this->add_action_buttons();
       
      
      

    }
}