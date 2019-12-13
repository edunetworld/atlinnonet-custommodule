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
 * @Description: Mentor of Month Page for NITI Admin
*/
require_once('../config.php');
require_once('render.php');
include_once(__DIR__ .'/mom_form.php');
require_login(null, false);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
require_once($CFG->libdir.'/filelib.php');
$userrole = get_atalrolenamebyid($USER->msn);
$PAGE->set_url('/nitiadmin/mentormonth.php');
$show_form_status=false;
//Heading
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('standard');
$strmessages = "NITI Administration : Mentor of Month ";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("NITI Administration : Mentor of Month");
echo $OUTPUT->header();
$formobj = new MentorofMonthForm();
if ($formobj->is_cancelled()) {
    // The form has been cancelled, take them back to what ever the return to is.
	// redirect($returnurl);
} else if ($data = $formobj->get_data()) {
			try
			{
				$status = false;
				$mentordetail = $DB->get_record("user",array('email'=>$data->mentor_email,'msn'=>4),'id');
				if($mentordetail)
					$data->userid = $mentordetail->id;
				$result = $DB->insert_record("mentormonth",$data);
				if($result)
					$status = true;
				if($status)
					$show_form_status=true;
			}
			catch(Exception $e)
			{
				echo $e->getMessage();die;
			}
		} 
$alert_box='';
if($show_form_status)
{
	$alert_box='<div class="alert alert-success">
	<strong>Added Successfully! </strong><button class="close" type="button" data-dismiss="alert">×</button></a>
	</div>';
}
echo $alert_box;
echo $formobj->render();
if($_POST['proceed']=='delete')
{
	$data = new StdClass();
	$id = $_POST['id'];
	$DB->delete_records('mentormonth', array('id'=>$id));	
}
$moms =$DB->get_records("mentormonth");
echo "<p><h4>Mentor of Month </h4></p>";
echo "<div class='card-block'>
<table class='table table-stripped'>
<th> Mentor Name </th>
<th> Mentor Email </th>
<th> Month </th>
<th> Year </th>";
foreach($moms as $key=>$value)
{
	echo "<tr>";
	echo "<td>".$value->mentor_name."</td>";
	echo "<td>".$value->mentor_email."</td>";
	echo "<td>".date('F', mktime(0, 0, 0, $value->month, 10))."</td>";; 
	echo "<td>".$value->year."</td>";
	echo "<td><form action='mentormonth.php' method='post'><input type='hidden' name='proceed' value='delete'><input type='hidden' name='id' value='".$value->id."'> <input type='submit' value='Delete'></form></td>";
	echo "</tr>";
}
echo "</table>";
echo $OUTPUT->footer();
?>