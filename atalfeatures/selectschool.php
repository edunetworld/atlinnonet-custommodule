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
 * @Description: School Choice by Mentor
*/

require_once('../config.php');
require_once('render.php');
require_once(__DIR__ .'/selectschool_form.php');
require_once('lib.php');
require_once('reassginmentlib.php');
require_login(null, false);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
require_once($CFG->libdir.'/filelib.php');
$show_form_status = false;
$userrole = get_atalrolenamebyid($USER->msn);
$id = optional_param('id', 0, PARAM_INT);    // User id; -1 if creating new school.
$PAGE->set_url('/atalfeatures/selectschool.php');

//Heading
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('standard');
$strmessages = "Choose your School";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("Choose your School");

echo $OUTPUT->header();
// Now the page contents.
if (isset($_POST['submitbutton'])) { 
   $from = 'mentorindia-aim@gov.in';
   $newdata =new stdClass();

	$newdata->userid = $USER->id;
	//$newdata->status =1;
	//$schoolid_arr = explode(',',$_POST['schoolid']);
	$schoolid_arr = array_map('intval', explode(',', $_POST['schoolid']));
	if(count($schoolid_arr) >= 1)
	{
		$new_array = $schoolid_arr;
		"select schoolid from {user_school} where userid=".$USER->id." and role =".
		$current = $DB->get_records_sql("select schoolid from {user_school} where userid=$USER->id and role='mentor'")
		;
		$old_array = array_keys($current);
		$assigned = array_diff($new_array,$old_array);
		$removed = array_diff($old_array,$new_array);
		if($removed )
		{
			$remove_history = new StdClass();
			$remove_history->userid = $USER->id;
			foreach($removed as $value)
			{
				$useremail = new StdClass();	
				$useremail->deleted =0;
				$remove_history->schoolid = $value;
				$remove_history->removed_on = time();
				$DB->insert_record('schoolassign_history', $remove_history);
				removal_mail($value);
			}
		}
		if($assigned)
		{
			$useremail = new StdClass();	
			$useremail->deleted =0;
				
			$assign_history = new StdClass();
			$assign_history->userid = $USER->id;
			foreach($assigned as $value)
			{				
				$assign_history->schoolid = $value;
				$assign_history->assigned_on = time();
				$DB->insert_record('schoolassign_history', $assign_history);
				reassignment_mail($value);
			}
		}

		$DB->delete_records('user_school', array('userid' =>$USER->id,'role'=>'mentor'));
		foreach($schoolid_arr as $value)
		{
			$newdata->schoolid =$value;
			$newdata->role ='mentor';
			$DB->insert_record('user_school', $newdata);
		}
	}
	$show_form_status = true;
}
if($show_form_status)
{
	echo '<div class="alert alert-success"><strong>You have been Assigned to the school of Your Choice ! Happy Mentoring ! </strong><button class="close" type="button" data-dismiss="alert">×</button></a></div>';
}
echo '<div class="row"><div class="col-sm-12 col-md-12">
 <h2>Reassign School</h2>
  </div></div>';
$last_re = check_last_ressignment();
echo get_reassignment_form($last_re);
//QA
/*
$nps='<div id="sg-nps" class="sg-black">
<script type="text/javascript">(function(d,e,j,h,f,c,b){d.SurveyGizmoBeacon=f;d[f]=d[f]||function(){(d[f].q=d[f].q||[]).push(arguments)};c=e.createElement(j),b=e.getElementsByTagName(j)[0];c.async=1;c.src=h;b.parentNode.insertBefore(c,b)})(window,document,"script","//d2bnxibecyz4h5.cloudfront.net/runtimejs/intercept/intercept.js","sg_beacon");sg_beacon("init","MzI0MTk0LTg5YmNhNjcyZDQ2NTRmMGFiMDVjODJiMmFjOTEyOTg4M2I4NDZlMWVjMjgyYjQyMzRl");
</script></div>';
*/
//Prod
$nps='<div id="sg-nps" class="sg-black"><script type="text/javascript">
(function(d,e,j,h,f,c,b){d.SurveyGizmoBeacon=f;d[f]=d[f]||function(){(d[f].q=d[f].q||[]).push(arguments)};c=e.createElement(j),b=e.getElementsByTagName(j)[0];c.async=1;c.src=h;b.parentNode.insertBefore(c,b)})(window,document,"script","//d2bnxibecyz4h5.cloudfront.net/runtimejs/intercept/intercept.js","sg_beacon");
sg_beacon("init","MzI0MTk0LTIxOTI0NGIyOTdhMzQ2M2I5NDRlMGQ0OTdiM2NkNTQ1M2RlM2IwMWUxMjNlMTI3YjM4");
</script></div>';
echo $nps;
echo $OUTPUT->footer();
?>
<script type="text/javascript">

// Global SG configuration
window.SurveyGizmoBeacon = 'sg_beacon';
window.sg_beacon = window.sg_beacon || function() {
 (window.sg_beacon.q = window.sg_beacon.q || []).push(arguments);
};
 
// Insert intercept script into DOM
const npsScript = document.createElement('script');
const firstScript = document.getElementsByTagName('script')[0];
npsScript.async = 1;
npsScript.src = '//d2bnxibecyz4h5.cloudfront.net/runtimejs/intercept/intercept.js';
firstScript.parentNode.insertBefore(npsScript, firstScript);

// NPS options
//QA
//window.sg_beacon('init', 'MzI0MTk0LTg5YmNhNjcyZDQ2NTRmMGFiMDVjODJiMmFjOTEyOTg4M2I4NDZlMWVjMjgyYjQyMzRl');   
//Prod
window.sg_beacon('init', 'MzI0MTk0LTIxOTI0NGIyOTdhMzQ2M2I5NDRlMGQ0OTdiM2NkNTQ1M2RlM2IwMWUxMjNlMTI3YjM4');
// required
window.sg_beacon('data', 'siteName', 'ATL Innonet Platform');  // required
window.sg_beacon('data', 'pageUrl', window.location.href);  // required
window.sg_beacon('data', 'version', 'v2');   // required
window.sg_beacon('data', 'sglocale', 'en');   // optional. By default NPS widget will use language from browser's settings (if the language is not supported by the widget - English will be used). You can override the behavior if you provide locale code here (ex.: zh-cn, zh-tw, nl, en, fr-ca, ja ).
</script>
<script type="text/javascript">
require(['jquery'], function($) {

	$('#id_cityid').change(function()
	{
		var cityid = $(this).val();
		var data = {'id' :cityid,'mode':'loadschool-by-cityid-mchoice'};
		var selectField = $('#id_school');
		selectField.empty();
		//selectField.append($("<option />").val("").text("Select Atal Schools"));
		selectField.append($("<option />").val("load").text("Loading ... "));
		var request = $.ajax({
		  url: "ajax.php",
		  method: "POST",
		  data:  data,
		  dataType: "json",
		  success : function(replydata) {
				var opts = replydata.replyhtml;    
				if(opts.length==0)
				{
					var option  = '<option disabled value="">'+'No Schools Found'+'</option>';
					selectField.append(option);
				}    
				else
				{ 
					$.each(opts, function(i, item) {
						var itemtext = item.schoolname + ' - ' + item.cityname;
					  $('#id_school').append($("<option />").val(item.id).text(itemtext)); 
					});
				}
				//document.getElementsByName("city")[0].value = cityid;
				 $('#id_school option[value="load"]').remove();
			},
			faliure : function (replydata)
			{
				alert("Something has gone Wrong! Please try again later!");
			}
		});
	});
	$('#id_school').click(function()
	{
        var schools = [];
        $.each($("#id_school option:selected"), function(){            
            schools.push($(this).val());
        });
		document.getElementsByName("schoolid")[0].value = schools.join(", ");
	});
	/*
	$("body").on("click", ".remove-school", function(event){
	//$('.remove-school').click(function(){
		var id = $(this).attr('data-id');
		var value = $(this).attr('data-value');
		$(this).closest('tr').remove();
		$len = $("#assigned-schools tr").length;
		if($len == 0)
		$("#assigned-schools").append('<tr><td>No Schools</td></tr>');
		$option_html = '<tr><td>'+value+'<td><td> <a style="color:white;padding:2px;cursor:pointer;"class="add-school btn-success" data-id="'+id+'" data-value="'+value+'"> Add +  </a></td></tr>';
		//$('#id_school').append($option_html);
		$('#removed-schools').append($option_html);
		//$("input[name='removedschoolid']").val(id);
		//$("input[name='removedschoolid']").val().replace(',', "");
		if($("input[name='removedschoolid']").val() == '')
			$("input[name='removedschoolid']").val(id);
		else
		{
			var id_list = $("input[name='removedschoolid']").val();
			id_list+=','+id;
			$("input[name='removedschoolid']").val(id_list);
		}
	});
	$("body").on("click", ".add-school", function(event){
		var id = $(this).attr('data-id');
		var value = $(this).attr('data-value');
		$option_html = '<tr><td>'+value+'<a title="Remove the School" class="remove-school close" data-id="'+id+'" data-value="'+value+'" style="color:red;padding-left:3px;"> <span aria-hidden="true"> &times;</span> </a> </td></tr>';
		if($("#assigned-schools tr td").html()=="No Schools")
			$('#assigned-schools tr').remove();
		$('#assigned-schools').append($option_html);
		var removed_school = $("input[name='removedschoolid']").val();
		var rem_arr=removed_school.split(',');
		if(rem_arr.length>1)
		{
			$.each(rem_arr, function (index, value) {
				if(value == id)
					rem_arr.splice(rem_arr.indexOf(value), 1);
			});
			$new_removelist = rem_arr.join(',');
			$("input[name='removedschoolid']").val($new_removelist);
		}
		else
			$("input[name='removedschoolid']").val('');
		//str = removed_school.replace(id, "");
		
		$(this).closest('tr').remove();
	});*/
	$("body").on("click", "#add", function(event){
		var schools = [];
		// No Of Maximum School a Mentor can be assigned is "max_mentor"
		var max_mentor_toschool = $("input[name='max_mentor']").val();
		var assigned_len = $("#assginedschool option").length;
		if(assigned_len >=max_mentor_toschool)
    	{
    		alert("You have already assigned to " + max_mentor_toschool + " Schools. Please Remove any school from your Assigned School list to choose new School");
    		return false;
    	}
    	var new_schoollen = $("#id_school option:selected").length;
    	if((new_schoollen+assigned_len)>max_mentor_toschool)
    	{
    		alert("You Can choose only " + max_mentor_toschool + "Schools.");
    		return false;
    	}
        $.each($("#id_school option:selected"), function(){  
        	
        	$("#assginedschool").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
        });
        $("#id_school option:selected").remove();
	});
	$("body").on("click", "#remove", function(event){
		var schools = [];
		var removed_school = $("#assginedschool option:selected").length;
		if(removed_school==0)
			return false;
		$("#removedschool_row").show();
        $.each($("#assginedschool option:selected"), function(){   
        	$("#removed_school").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
        });
        $("#assginedschool option:selected").remove();

	});
	$("body").on("click", ".add-back", function(event){
		if($("#removed_school option:selected").length==0)
		{
			alert("Please Choose Any School to Add");
		}
		var schools = [];
		// No Of Maximum School a Mentor can be assigned is "max_mentor"
		var max_mentor_toschool = $("input[name='max_mentor']").val();
		var assigned_len = $("#assginedschool option").length;
		if(assigned_len >=max_mentor_toschool)
    	{
    		alert("You have already Choosed/Assigned to " + max_mentor_toschool + " Schools. Please Remove any school from your Assigned School list to choose new School");
    		return false;
    	}
        $.each($("#removed_school option:selected"), function(){  
        	$("#assginedschool").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
        });
        $("#removed_school option:selected").remove();
        if($("#removed_school option").length==0)
        	$("#removedschool_row").hide();

	});
	$("body").on("click", "#id_submitbutton", function(event){
		var schools_assigned= [];
		var schools_removed= [];
		var new_schoollen = $("#assginedschool option").length;
		if(new_schoollen ==0)
		{
			alert("Please Select Atleast One School");
			return false;
		}
		var r = confirm("Are you sure you want to Submit ?");
		if (r == true) {
		  $.each($("#assginedschool option"), function(){  
        	schools_assigned.push($(this).val());
        });
         $.each($("#removed_school option"), function(){  
        	schools_removed.push($(this).val());
        });
        document.getElementsByName("schoolid")[0].value = schools_assigned.join(", ");
        document.getElementsByName("removedschoolid")[0].value = schools_removed.join(", ");	
		} else {
		  return false;
		}
	});
	
});
</script>
