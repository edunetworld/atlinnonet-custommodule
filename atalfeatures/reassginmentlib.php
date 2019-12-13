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

/*
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:23-08-2018
 
*/
function get_reassignment_form_old($last_re)
{
	global $USER,$DB;
	$disabled = "";
	//$last_re=1;
	if($last_re)
		$disabled = "disabled";
	//echo $disabled;
	$action = $CFG->wwwroot.'atalfeatures/selectschool.php';
	$state = stateForMoodleForm();
	$user_detail = $DB->get_record('user',array('id'=>$USER->id),'id,city,aim');
	$city = $user_detail->city;
	$stateid = $user_detail->aim;
	$max_mentor = getCustomSettings('mentor_maxschool');
	$max_mentor = $max_mentor->atl_value;
	$cityid = getCityIdbyCityname($city,$stateid);
	$schdata = getschool_bycity_mentorchoice($cityid);
	$assigned_schooldata = get_assginedschool($USER->id);
	// Remove the Already Assigned School From Available School List
	$result = array_column($assigned_schooldata, 'schoolid');
	foreach($result as $key=>$value)
	{
		foreach($schdata as $skey=>$svalue)
		{
			if($svalue->id==$value)
				unset($schdata[$skey]);
		}
	}
	$html="";
	$html.='<div style="margin-top: 25px;"><span class="red">*Note: Please read the below instructions</span>
	<p>
	<ul>
	<li>Mentor can either add a new school or replace an existing school from their assigned schools</li>
	<li>Only those schools which do not have sufficient amount of mentors will be displayed in the "Available Schools" section</li>
	<li>Use Add/Remove buttons to edit the assigned schools</li>
	<li>Before submitting ensure that only the schools you want to mentor are included in "Assigned Schools" section</li>
	<li><span style=" background-color: yellow;font-weight: bold;
    text-decoration: underline;">Once submitted, mentors will not be allowed to change their assigned schools for 2 months.</span></li>
	<li>Upon submission, school and mentor will receive email notification(s) with relevant details</li>
	</ul></p>
	</div>';
	$html.='<form autocomplete="off" action="" method="post" accept-charset="utf-8" id="mform1" class="mform">
	<div style="display: none;"><input name="max_mentor" type="hidden" value="'.$max_mentor.'" />
	<input name="city" type="hidden" value="'.$cityid.'" />
	<input name="schoolid" type="hidden"/>
	<input name="removedschoolid" type="hidden" />	
	</div>
<br>
	<div class="form-group row  fitem" >
    <div class="col-sm-12 col-md-1">
        <span class="pull-xs-right text-nowrap">
        </span>
        <label class="col-form-label d-inline " for="id_state">
            <strong>State</strong>
        </label>
    </div>
    <div class="col-sm-12 col-md-3 form-inline felement" data-fieldtype="select">
        <select class="custom-select" name="state" id="id_state" style="width:100%">';
		foreach ($state  as $key => $value) {
			if($key==$stateid)
				$html.='<option selected="selected" value="'.$key.'">'.$value.'</option>';
			else
         		$html.='<option  value="'.$key.'">'.$value.'</option>';
         } 
       $html.='</select>
        <div class="form-control-feedback" id="id_error_state"  style="display: none;">
            
        </div>
    </div>
</div><div class="form-group row  fitem" >
    <div class="col-sm-12 col-md-1">
        <span class="pull-xs-right text-nowrap">
        </span>
        <label class="col-form-label d-inline " for="id_cityid">
            <strong>City</strong>
        </label>
    </div>
    <div class="col-sm-12 col-md-3 form-inline felement" data-fieldtype="select">
        <select class="custom-select" name="cityid" id="id_cityid" style="width:100%" >';

        $html.='</select>
        <div class="form-control-feedback" id="id_error_cityid"  style="display: none;">
            
        </div>
    </div>
	</div><table id="reassign-container">
   	 <tbody><tr>
      <td id="assignedschool_cell">
          <label for="assginedschool"><strong>Assigned Schools</strong></label>
          <div id="assginedschool_container">
		<select name="assginedschool[]" id="assginedschool" multiple="multiple" size="5" class="form-control" style="width:400px">';
		if(count($assigned_schooldata)>0){
			foreach($assigned_schooldata as $key=>$values){
		$html.='<option value="'.$values->schoolid.'">'.$values->schoolname.' - '.$values->city.'</option>';
			}
		} 
	$html.='</select>
		</div>
      </td>
      <td id="buttonscell" style="padding:25px 10px 0px 10px">
              <input name="add" id="add" type="button" value="◄ &nbsp;Add" title="Add" class="btn btn-secondary form-control" style="margin-bottom:10px;"><br>
              <input name="remove" id="remove" type="button" value="Remove&nbsp; ►" title="Remove" class="btn btn-secondary form-control">
      </td>
      <td id="availableschool_cell">
          <label for="school"><strong>Available Schools</strong></label>
          <div id="availbleschool_wrapper">
		<select name="school[]" id="id_school" multiple="multiple" size="5" class="form-control" style="width:400px;">';
		if(count($schdata)>0){
			foreach($schdata as $key=>$values){
				$html.='<option value="'.$values->id.'">'.$values->schoolname.' - '.$values->cityname.'</option>';
				$i++;
			}
		}

	$html.='</select>
		</div>

      </td>
    </tr>
    <tr id="removedschool_row" style="display:none;">
	<td colspan="2"></td>
	
    <td>
	<br>
          <label for="school"><strong>Removed Schools</strong></label>
          <div id="removedschool_wrapper">
		<select name="removed_school[]" id="removed_school" multiple="multiple" size="5" class="form-control" style="width:400px;"></select>
		<br>
		<span class="add-back"><a title="Add Back to Assigned School" class="btn btn-secondary" style="width:108px; height:34px;"> Add Back  </a></span>
		</div>

      </td>
    </tr>
  </tbody></table><div class="form-group row  fitem femptylabel  " >
   
    <div class="col-md-12 form-inline felement text-center" data-fieldtype="submit">
    <input type="submit" class="btn btn-primary " name="submitbutton" id="id_submitbutton" value="Submit your Choice" type="submit" style="margin:30px 0px 0px -41px;" '.$disabled.'>
        <div class="form-control-feedback" id="id_error_submitbutton"  style="display: none;">
            
        </div>
    </div>
</div>

</form>';
return $html;
}
function check_last_ressignment()
{
	global $USER,$DB;
	$status = 1;
	$result = $DB->get_record_sql("SELECT * FROM {schoolassign_history} where userid=$USER->id order by id asc limit 0,1");
	if($result)
	{
		$current_timestamp=strtotime("now"); // Current Timestamp
		//$current_date = date("Y m d H:i:s",$current_timestamp);
		$latest = ($result->assigned_on)?$result->assigned_on:$result->removed_on;
		//Difference in seconds
		$difference = abs(($current_timestamp)-($latest));
		$days = intval(intval($difference) / (3600*24)); // Convert to days 
		//$days=62;
		// Disable if last Ressignment period is less than 60 days 
		$regap = getCustomSettings('reassignment_gap');
		$gap = ($regap->atl_value)??60;
		$status = ($days<=$gap)?1:0;
	}
	else
		$status = 0;
	//$status=0;
	return $status;
}
function reassignment_mail($sid)
{
	global $USER,$DB,$CFG;
	$from = 'mentorindia-aim@gov.in';
	$userdb = $DB->get_record('user',array('id'=>$USER->id));
	$sql = "select * from mdl_user u join mdl_school s on u.username=s.atl_id where msn=3 and deleted=0 and s.id=$sid";	
	$school_data = $DB->get_record_sql($sql);
	//echo "<pre>";
	//print_r($school_data);die;
	$useremail->id = $school_data->id;
	$useremail->email = $school_data->email;
	//$useremail->email = "jothi@edunetworld.com";
	$CFG->bccmails = array($userdb->email);
	//$CFG->bccmails = array("jothi1105@gmail.com");
	
	$subject = "New Mentor Assigned to your ATL";
	$mailbody = construct_mailbody($school_data,'assign');
	//echo $mailbody;die;
	email_to_user($useremail, $from,$subject,$mailbody);
}
function construct_mailbody($schooldata,$status)
{
	global $USER,$DB;
	if($status=="assign"){
	$result = get_atal_statebystateid($schooldata->aim);
	$state = $result->name;
	$result_city = get_atal_citybycityid($schooldata->cityid);
	if($result_city)
		$city = $result_city[$schooldata->cityid]->name;
	else
		$city = '';
	$userdb = $DB->get_record('user',array('id'=>$USER->id));
	$html="<p>Dear School,</p>
	
	<p>Greetings from Team AIM!
	This is to inform you that <strong>".$userdb->firstname." ".$userdb->lastname." </strong>has been assigned as a Mentor for Change for your school’s Atal Tinkering Lab. Your new Mentor of Change will be shortly contacting you via telephone and/or mail to get started on their mentoring session. We request you to facilitate and enable the Mentor of Change in their task and work collaboratively with them to strengthening mentoring facilities in your school.</p>

	<p>The Mentor of Change has been cced in this mail, and additionally we are sharing all the contact details to start the engagement:</p>";
		$html.= "<p><table style='border:1px solid black'>";
		$html.= "<tr><td style='border:1px solid black'>ATL UID</td><td style='border:1px solid black'>".$schooldata->atl_id."</td></tr>";
		$html.= "<tr><td style='border:1px solid black'>School Name</td><td style='border:1px solid black'>".$schooldata->name."</td></tr>";
		$html.= "<tr><td style='border:1px solid black'>City</td><td style='border:1px solid black'>".$city."</td></tr>";
		$html.= "<tr><td style='border:1px solid black'>State</td><td style='border:1px solid black'>".$state."</td></tr>";
		$html.= "<tr><td style='border:1px solid black'>ATL Incharge Number</td><td style='border:1px solid black'>".$schooldata->phone." </td></tr>";
		$html.= "<tr><td style='border:1px solid black'>ATL Incharge EmailId</td><td style='border:1px solid black'>".$schooldata->email."</td></tr>";
		$html.= "<tr><td style='border:1px solid black'>Mentor Name</td><td style='border:1px solid black'>".$userdb->firstname." ".$userdb->lastname."</td></tr>";
		$html.= "<tr><td style='border: 1px solid black'>Email ID</td><td style='border:1px solid black'>".$userdb->email."</td></tr>";
		$html.= "<tr><td style='border:1px solid black'>Phone Number</td><td style='border:1px solid black'>".$userdb->phone1."</td></tr></table></p>";
		$html.="<p>Please reach out to us at mentorindia-aim@gov.in if you have any queries or concerns. </p><p>Thank You,<br>AIM</p>";
		
	}
	else
	{
		$html="<p>Dear School,</p><p>Greetings from Team AIM!</p>";
		$html.="<p>This is to inform you that MoC<strong> ".$userdb->firstname." ".$userdb->lastname." (".$userdb->email.") </strong> has been reassigned to a different school, and would now not be associated with your school’s Atal Tinkering Lab. We are working on finding a new mentor suitable to the requirements of your lab, in the mean time if you have any concerns or request you may write to us at mentorindia-aim@gov.in. </p>";
		$html.="<p>Thank you,<br>AIM</p>";
	}
	//echo $html;die;
	return $html;
}
function removal_mail($sid)
{
	global $USER,$DB,$CFG;
	$from = 'mentorindia-aim@gov.in';
	$userdb = $DB->get_record('user',array('id'=>$USER->id));
	$sql = "select * from mdl_user u join mdl_school s on u.username=s.atl_id where msn=3 and deleted=0 and s.id=$sid";	
	$school_data = $DB->get_record_sql($sql);
	$useremail->id = $school_data->id;
	$useremail->email = $school_data->email;
	//$useremail->email = "jothi@edunetworld.com";
	$CFG->bccmails = array($userdb->email);
	//$CFG->bccmails = array("jothi1105@gmail.com");
	$subject = "Change in Mentor assigned to your ATL";
	$mailbody = construct_mailbody($school_data,'remove');
	email_to_user($useremail, $from,$subject,$mailbody);
}
function get_reassignment_form($last_re)
{
	global $USER,$DB;
	$disabled = "";
	//$last_re=1;
	if($last_re)
		$disabled = "disabled";
	//echo $disabled;
	$action = $CFG->wwwroot.'atalfeatures/selectschool.php';
	$state = stateForMoodleForm();
	$user_detail = $DB->get_record('user',array('id'=>$USER->id),'id,city,aim');
	$city = $user_detail->city;
	$stateid = $user_detail->aim;
	$max_mentor = getCustomSettings('mentor_maxschool');
	$max_mentor = $max_mentor->atl_value;
	$cityid = getCityIdbyCityname($city,$stateid);
	$schdata = getschool_bycity_mentorchoice($cityid);
	$assigned_schooldata = get_assginedschool($USER->id);
	// Remove the Already Assigned School From Available School List
	$result = array_column($assigned_schooldata, 'schoolid');
	foreach($result as $key=>$value)
	{
		foreach($schdata as $skey=>$svalue)
		{
			if($svalue->id==$value)
				unset($schdata[$skey]);
		}
	}
	$html="";
	$html.='<div style="margin-top: 25px;"><span class="red">*Note: Please read the below instructions</span>
	<p>
	<ul>
	<li>Mentor can either add a new school or replace an existing school from their assigned schools</li>
	<li>Only those schools which do not have sufficient amount of mentors will be displayed in the "Available Schools" section</li>
	<li>Use Add/Remove buttons to edit the assigned schools</li>
	<li>Before submitting ensure that only the schools you want to mentor are included in "Assigned Schools" section</li>
	<li><span style=" background-color: yellow;font-weight: bold;
    text-decoration: underline;">Once submitted, mentors will not be allowed to change their assigned schools for 2 months.</span></li>
	<li>Upon submission, school and mentor will receive email notification(s) with relevant details</li>
	</ul></p>
	</div>';
	$html.='<form autocomplete="off" action="" method="post" accept-charset="utf-8" id="mform1" class="mform">';
	$html.='<div class="row form-group">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <input name="max_mentor" type="hidden" value="'.$max_mentor.'" />
		<input name="city" type="hidden" value="'.$cityid.'" />
		<input name="schoolid" type="hidden"/>
		<input name="removedschoolid" type="hidden" />	
        </div>
    </div>';
	$html.='<div class="row form-group">
      <div class="col-md-1 col-sm-1 col-xs-12">
         <label class="col-form-label d-inline " for="id_state"><strong>State</strong></label>
      </div><div class="col-md-3 col-sm-3 col-xs-12">
         <select class="custom-select" name="state" id="id_state" style="width:100%">';
	 foreach ($state  as $key => $value) {
		if($key==$stateid)
			$html.='<option selected="selected" value="'.$key.'">'.$value.'</option>';
		else
			$html.='<option  value="'.$key.'">'.$value.'</option>';
	 } 
	$html.='</select>
        <div class="form-control-feedback" id="id_error_state"  style="display: none;"></div>
          </div></div>';
	$html.='<div class="row">
		<div class="col-md-1 col-sm-1 col-xs-12">
          <label class="col-form-label d-inline form-group" for="id_cityid"><strong>City</strong></label>
        </div>
          
        <div class="col-md-3 col-sm-3 col-xs-12">
        <select class="custom-select" name="cityid" id="id_cityid" style="width:100%"></select>
        <div class="form-control-feedback" id="id_error_cityid"  style="display: none;"></div>
          </div>  
      </div>';
	$html.='<div class="row">
             
      <div class="col-md-5 col-sm-5 col-xs-12">
          
          <label for="assginedschool" class="form-group"><strong>Assigned Schools</strong></label>
          <div id="assginedschool_container">
		<select name="assginedschool[]" id="assginedschool" multiple="multiple" size="5" class="form-control">';
	if(count($assigned_schooldata)>0){
			foreach($assigned_schooldata as $key=>$values){
		$html.='<option value="'.$values->schoolid.'">'.$values->schoolname.' - '.$values->city.'</option>';
			}
		}	
	$html.='</select></div></div>
	<div class="col-md-2 col-sm-2 col-xs-12">
      
              <div class="add-remove-box">
              <span class="add-remove-arrow-lap">
               <input name="add" id="add" type="button" value="◄ &nbsp;Add" title="Add" class="btn btn-secondary form-control" style="margin-bottom:10px;"><br>
              <input name="remove" id="remove" type="button" value="Remove&nbsp; ►" title="Remove" class="btn btn-secondary form-control">
              </span>
              
             <span  class="add-remove-arrow-mob">
               <input name="add" id="add" type="button" value="Add" title="Add" class="btn btn-secondary form-control" style="margin-bottom:10px;"><br>
              <input name="remove" id="remove" type="button" value="Remove" title="Remove" class="btn btn-secondary form-control">
              </span>
              </div> 
          </div>      
        <div class="col-md-5 col-sm-5 col-xs-12">
        
            <label for="school" class="form-group"><strong>Available Schools</strong></label>
            <div id="availbleschool_wrapper">
		<select name="school[]" id="id_school" multiple="multiple" size="5" class="form-control">';
		if(count($schdata)>0){
			foreach($schdata as $key=>$values){
				$html.='<option value="'.$values->id.'">'.$values->schoolname.' - '.$values->cityname.'</option>';
				$i++;
			}
		}	
	$html.='</select></div></div> </div>';
	$html.='<div class="row">     
      <div class="col-md-5 col-sm-5 col-xs-12"></div>                
          <div class="col-md-2 col-sm-2 col-xs-12"></div>       
        <div class="col-md-5 col-sm-5 col-xs-12" id="removedschool_row" style="display:none;">       
            <label for="school" class="form-group"><strong>Removed Schools</strong></label>
           <div id="removedschool_wrapper">
		<select name="removed_school[]" id="removed_school" multiple="multiple" size="5" class="form-control"></select>
		<br>
        <div class="row"><div class="col-md-5 col-sm-5 col-xs-12">
		<span class="add-back"><a title="Add Back to Assigned School" class="btn btn-secondary" style="height:34px; width: 100%"> Add Back  </a></span>
        </div>  </div>       
		</div> 
        </div></div>';
	$html.='<div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center" >
          <div class="col-md-12 form-inline felement text-center" data-fieldtype="submit">
		<input type="submit" class="btn btn-primary " name="submitbutton" id="id_submitbutton" value="Submit your Choice" type="submit" style="margin-top:30px;" '.$disabled.'>
		</div></div></div></form>';
	return $html;
}
?>