<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class question extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        // $editoroptions = $this->_customdata['editoroptions'] ?? null;
        $editoroption = array("subdirs"=>1,"maxfiles" => -1);

        $mform = $this->_form; // Don't forget the underscore!


        $mform->addElement('hidden','id');
        $mform->setType('id',PARAM_INT);

        $mform->addElement('text',  'question_title', "Question Title");
        $mform->setType('question_title',PARAM_RAW);
        $mform->addRule('question_title', "Required Fields", 'required', null, 'server');

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
