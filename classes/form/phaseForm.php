<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
class phaseForm extends moodleform
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

        $mform->addElement('hidden','cm_id');
        $mform->setType('cm_id',PARAM_INT);
       

        $phase_types = array();
        $phase_types[''] = "Select Phase Type";
        $phase_types['mcq'] = "MCQ";
        $phase_types['informative'] = "INFORMATIVE";
   

        $mform->addElement('select', 'phase_type', "Phase Type", $phase_types); // Add elements to your form
        $mform->setDefault('phase_type', ''); // $choices = array();
        $mform->addRule('phase_type', "Required", 'required', null, 'server');

        $mform->addElement("editor","description_editor", "Description",null,$editoroption);
        $mform->setType('editor', PARAM_RAW);

        $is_first_phase = array(
            '1' => "YES",
            '0' => "NO",
        );

        $mform->addElement('select','is_first',"is First Phase",$is_first_phase);
        $mform->setDefault('is_first','0');
        $mform->addRule('is_first', "Required" ,'required',null,'server');

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
