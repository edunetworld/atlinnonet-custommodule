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
 * Ticketing System Create New Ticket Page
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:24-07-2018
*/
require_once('../config.php');

require_login(null, false);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
$id = optional_param('id', 0, PARAM_INT);

require_once('lib.php');
require_once('edit_form.php');

$userrole = get_atalrolenamebyid($USER->msn);
$statuslist = get_allstatus();

// First create the form.
$args = array(
    'ticketid' => $id,
	'category'=> get_ticketcategory()
);
$frmobject = new ticketform(null, $args);
$heading = ($id==0)?"Create Ticket":"Edit a Ticket";

if ($frmobject->is_cancelled()) {
    // The form has been cancelled, take them back to what ever the return to is.
    redirect($CFG->wwwroot.'/ticket/create.php');
} else if ($data = $frmobject->get_data()) {
	if ($data){
		$usrmsg = "update";
		$cm = new stdClass();
		foreach ($data as $key => $value) {
			$cm->$key = $value;
		}
		$cm->createdby = $USER->id;
		$cm->timemodified = time();
		$cm->statusid = 1;
		$cm->assigned_to = getnitiadminid();
		if($data->id==0){
			$cm->timecreated = time();
			$cm->latest_comment = NULL; //$cm->description ;
			$usrmsg = "add";
			$cm->id = $DB->insert_record('tech_ticket', $cm);
			if($cm->id>0){
				ticket_sentmail($cm);
			}
		} else{
			$cm->id = $data->id;
			$DB->update_record('tech_ticket', $cm);
		}
		if(!empty($cm->id)){
			$SESSION->ticketflag = $usrmsg;
			redirect($CFG->wwwroot.'/ticket');
		}
	}
}

$PAGE->set_url('/ticket/create.php');
//Heading
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('standard');
$strmessages = "Ticketing System";
$PAGE->set_title("{$SITE->shortname}");
$PAGE->set_heading($strmessages);

echo $OUTPUT->header();

echo '<div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><h1>
<a class="btn btn-primary pull-right goBack backdek">Back</a>
</h1></div></div>                       
            <div class="row">
            
            <div class="col-md-11 col-sm-11 col-xs-10"><h2>
     Create a Ticket</h2></div>
                
        <div class="col-md-1 col-sm-1 col-xs-2"><h1> 
<a class="pull-right goBack"><i class="fa fa-chevron-circle-left arrowback"></i></a>
</h1></div> </div>';

//echo $OUTPUT->heading($heading);

$content = '';
$content.= html_writer::start_tag('div', array('class' => 'createproject'));
$content.= $frmobject->render();
$content.= html_writer::end_tag('div');
echo $content;
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
