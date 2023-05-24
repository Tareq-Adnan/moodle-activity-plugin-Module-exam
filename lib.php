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
 * Library of interface functions and constants.
 *
 * @package     mod_test
 * @copyright   2023@copyright
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function test_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_test into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_test_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function test_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('test', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_test in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_test_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function test_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('test', $moduleinstance);
}

/**
 * Removes an instance of the mod_test from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function test_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('test', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('test', array('id' => $id));

    return true;
}
function management_editor()
{
    return array("subdirs" => true, "maxfiles" => -1, "maxbytes" => 0);
}

function getNewSerial(){
    global $DB;
    $current_last_serial_sql = "SELECT max(serial) as max_serial FROM {exam_phases};";
    $phase_max_serial = $DB->get_record_sql($current_last_serial_sql)->max_serial;

    return $phase_max_serial  ? $phase_max_serial + 1 : 1;
}

function test_insert_phase($formData, $context)
{
    global $DB;
    $record = new stdClass;
    $record->phase_id = $formData->phase_id;
    $record->phase_type = $formData->phase_type;
    $record->description = "";
    $record->serial = getNewSerial();
    $record->is_first = $formData->is_first;
    $record->timecreated = time();
    $record->timemodified = time();
    $record->id = $DB->insert_record('exam_phases', $record);

    if (!empty($formData->description_editor)) {
        $record->description_editor = $formData->description_editor;
        $record = file_postupdate_standard_editor($record, 'description', management_editor(), $context, 'mod_test', 'phase_description', $record->id);
    }
    $DB->update_record('exam_phases', $record);
    return $record->id;
}

function getPhaseId($phase_id){
    global $DB;
    $sql="SELECT DISTINCT phase_id FROM {exam_question} WHERE phase_id = :id";
    $question=$DB->get_record_sql($sql,['id'=>$phase_id]);
    
    if($question){
        $pid=get_object_vars($question)['phase_id'];
    }else{
        $pid="";
    }

    return $pid;
       
}
