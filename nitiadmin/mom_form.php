<?php
/*
Copyright (C) 2019  IBM Corporation 
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details at 
http://www.gnu.org/licenses/gpl-3.0.html
*/
/* 
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:28-08-2018
 * @Description: Mentor of Month Form
*/
defined('MOODLE_INTERNAL') || die();


//moodleform is defined in formslib.php
require_once($CFG->dirroot.'/lib/formslib.php');

class MentorofMonthForm extends moodleform {

    /**
     * Define the form.
     */
    public function definition() {
        global $CFG, $USER, $OUTPUT,$DB;
		$month = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
		$year = range(date('Y'), date('Y')+10);
		$year = array_combine($year,$year);
        $mform = $this->_form; // Don't forget the underscore!
		
		$mform->addElement('text', 'mentor_name', 'Mentor Name', 'size="50",maxlength="100"');	
		$mform->addRule('mentor_name', get_string('required'), 'required', null, 'client');
		$mform->addElement('text', 'mentor_email', 'Mentor Email', 'size="50",maxlength="100"');	
		$mform->addRule('mentor_email', get_string('required'), 'required', null, 'client');
		
		//Month
		$mform->addElement('select', 'month', 'Month', $month);
		$mform->addRule('month', get_string('required'), 'required', null, 'client');
		//Month
		$mform->addElement('select', 'year', 'Year', $year);
		$mform->addRule('year', get_string('required'), 'required', null, 'client');


        $this->add_action_buttons(false, 'Submit');       
    }

    /**
     * Validate the form data.
    *
	*/
    function validation($data, $files) {
        global $DB;
       $errors = parent::validation($data, $files);
	   if(($data['mentor_email']))
	   {
			$result = $DB->get_record("user",array('email'=>$data['mentor_email'],'msn'=>4));
			if(!$result)
				$errors['mentor_email'] = "Mentor Email Not Exists!";
	   }
        return $errors;
    }
}
