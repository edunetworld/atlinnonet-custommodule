<?php
/*
Copyright (C) 2019  IBM Corporation 
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details at 
http://www.gnu.org/licenses/gpl-3.0.html
*/

/* @package core_project
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:09-10-2018
 * @Description: School Form to Mentor School of Choice
*/

defined('MOODLE_INTERNAL') || die();


//moodleform is defined in formslib.php
require_once($CFG->dirroot.'/lib/formslib.php');

class SelectSchoolForm extends moodleform {

    /**
     * Define the form.
     */
    public function definition() {
        global $CFG, $USER, $OUTPUT,$DB;
        $mform = $this->_form; // Don't forget the underscore!
        // Normal fields
		$state = $city = array();
		$max_mentor = getCustomSettings('mentor_maxschool');
		$max_mentor = $max_mentor->atl_value;
		$max_school = getCustomSettings('mentor_maxschool');
		$max_school = $max_school->atl_value;
		//if($USER->city)
		//	$city[$USER->city] = $USER->city;
		//else
		//$city['select_city'] = "Select City";

		$state = stateForMoodleForm();
		// Temp Hidden in Phase1
		$school = array(''=>'Select School');
		$assigned_school = array();
		$cityid = getCityIdbyCityname($USER->city,$USER->aim);
		$city[$cityid] = $USER->city;
		$schdata = getschool_bycity_mentorchoice($cityid);
		$assigned_schooldata = get_assginedschool($USER->id);
		$i=0;
		if(count($schdata)>0){
			foreach($schdata as $key=>$values){
				$school[$values->id] = $values->schoolname.' - '.$values->cityname;
				$i++;
			}
		} 
		if(count($assigned_schooldata)>0){
			foreach($assigned_schooldata as $key=>$values){
				$assigned_school[$values->schoolid] = $values->schoolname.' - '.$values->city;
				$i++;
			}
		}
		//$populate_school = $this->generate_html($assigned_school);
		$populate_school = $this->generate_html_1($assigned_school,$schdata);

		// Own state or different state
		/*$radioarray=array();
		$radioarray[] = $mform->createElement('radio', 'otherschool', '',' Yes ', 'yes');
		$radioarray[] = $mform->createElement('radio', 'otherschool', '', 'No', 'no');
		$mform->setDefault('otherschool', 'no');
		$mform->addGroup($radioarray, 'radioar', 'Choose School from other State', array(''), false); */
		
		//School State
		$mform->addElement('select', 'state', 'State', $state);
		//$mform->disabledIf('state','otherschool', 'noteq', 'yes');
		$mform->setDefault('state', $USER->aim);
		
		//School City
		$mform->addElement('select', 'cityid', 'City', $city);
		//$mform->disabledIf('cityid','otherschool', 'noteq', 'yes');
		//$mform->setDefault('cityid', $cityid);

		/*$mform->addElement('select', 'assigned_school', 'Assigned Schools', $assigned_school,'max-width="50%"'); // Temp Hidden in 
		$mform->getElement('assigned_school')->setMultiple(true);*/

		$mform->addElement('html',$populate_school);
		
		//$mform->addElement('select', 'school', '', $school,'max-width="50%"'); // Temp Hidden in Phase1
		//$mform->addRule('school', get_string('required'), 'required', null, 'client');
		///$mform->getElement('school')->setMultiple(true);

		$mform->addElement('hidden', 'max_mentor',$max_mentor);
		$mform->addElement('hidden', 'max_school',$max_school);
        $mform->addElement('hidden', 'city',$cityid);
		$mform->addElement('hidden', 'schoolid');
		$mform->addElement('hidden', 'removedschoolid');
		$this->add_action_buttons(false, 'Submit your Choice');       
    }

    /**
     * Validate the form data.
    *
	*/
    function validation($data, $files) {
        global $DB,$USER;
        $errors = parent::validation($data, $files);
        return $errors;
    }
    public function reset() {
        $this->_form->updateSubmission(null, null);
    }

    function generate_html($assigned_school)
    {
    	$html = '<div class="form-group row  fitem   " id="yui_3_17_2_1_1564569747626_83">
    	<div class="col-md-3">
        <span class="pull-xs-right text-nowrap">
        </span>
        <label class="col-form-label d-inline " for="id_assigned_school">
            Assigned Schools
        </label>
    	</div>
    	<div class="col-md-9 form-inline felement" ><table style="border:1px solid rgba(0,0,0,.15);max-height: 150px;
    	overflow-y: scroll;" id="assigned-schools" class="form-control">';
         foreach ($assigned_school  as $key => $value) {
         	$html.='<tr>';
         	$html.='<td>'.$value.'<a title="Remove the School" class="remove-school close" data-id="'.$key.'" data-value="'.$value.'" style="color:red;padding-left:3px;"> <span aria-hidden="true"> &times;</span> </a> </td>';
         	$html.='</tr>';
         	$html.='</tr>';

         }  
        $html.='</table></div>
        <div class="col-md-3">
        <span class="pull-xs-right text-nowrap">
        </span>
        <label class="col-form-label d-inline " for="id_assigned_school">
            
        </label>
    	</div>
    	<div class="col-md-9 form-inline felement" style="padding-top:10px;" >
        <table id="removed-schools" class="form-inline felement"
           >
        </table></div>
   	 	</div>
		</div>';
		return $html;
    }
    function generate_html_1($assigned_school,$schdata)
    {
    	$html='<table id="reassign-container">
   	 <tbody><tr>
      <td id="assignedschool_cell">
          <p><label for="assginedschool">Assigned Schools</label></p>
          <div id="assginedschool_container">
		<select name="assginedschool[]" id="assginedschool" multiple="multiple" size="10" class="form-control" style="width:400px;">';
	foreach ($assigned_school  as $key => $value) {
         	$html.='<option value="'.$key.'">'.$value.'</option>';
         }  
  	$html.='</select>
		</div>
      </td>
      <td id="buttonscell">
              <input name="add" id="add" type="button" value="◄&nbsp;Add" title="Add" class="btn btn-secondary"><br>
              <input name="remove" id="remove" type="button" value="Remove&nbsp;►" title="Remove" class="btn btn-secondary">
      </td>
      <td id="availableschool_cell">
          <p><label for="school">Available Schools</label></p>
          <div id="availbleschool_wrapper">
		<select name="school[]" id="id_school" multiple="multiple" size="10" class="form-control" style="width:400px;">';
		foreach ($schdata  as $key => $value) {
         	$html.='<option value="'.$value->id.'">'.$value->schoolname.' - '.$value->cityname.'</option>';
         } 	
		$html.='</select>
		</div>

      </td>
    </tr>
    <tr id="removedschool_row" style="display:none;">
    <td colspan="2">
          <p><label for="school">Removed Schools</label></p>
          <div id="removedschool_wrapper">
		<select name="removed_school[]" id="removed_school" multiple="multiple" size="5" class="form-control" style="width:400px;"></select>
		<span class="add-back"><a title="Add Back to Assigned School" class="btn btn-secondary"> Add Back  </a></span>
		</div>

      </td>
    </tr>
  </tbody></table>';
  	return $html;
    }
}
