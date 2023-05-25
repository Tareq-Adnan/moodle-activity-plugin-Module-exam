<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class option extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!
        $mform->addElement('hidden','id');
        $mform->setType('id',PARAM_INT);

        $mform->addElement('text',  'option_title', "Option title");
        $mform->setType('option_title',PARAM_RAW);
        $mform->addRule('option_title', "Required Fields", 'required', null, 'server');

        $valid=[];
        $valid[1]="Valid";
        $valid[0]="inValid";

        $mform->addElement('select',  'is_valid',  "is Valid?", $valid);
        $mform->setType('is_valid',PARAM_RAW);
        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
