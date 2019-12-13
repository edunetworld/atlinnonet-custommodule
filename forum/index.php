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
 * @CreatedOn:14-12-2017
 
*/


require_once('../config.php');

require_login(null, false);

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

require_once($CFG->libdir.'/filelib.php');
require_once($CFG->dirroot . '/forum/lib.php');
require_once($CFG->dirroot . '/forum/locallib.php');

$url = new moodle_url('/forum/index.php');

$PAGE->set_url($url);

$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('standard');

$strmessages = "Forum";
$PAGE->set_title("{$SITE->fullname}");
$PAGE->set_heading("Forum");

$SESSION->studentpostforapproval = 0;
$SESSION->misusepostlist = 0;

// Now the page contents.
echo $OUTPUT->header();
echo $OUTPUT->heading("Forum");

if(get_atalrolenamebyid($USER->msn)=='incharge'){
	$output = showforumfeedforincharge();
} elseif(get_atalrolenamebyid($USER->msn)=='admin'){
	$output = showforumfeedforadmin();
} else{
	$output = showforumfeed();
}
echo $output;
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
	//$('#list-category-btn').click(function(){
	$('#category-listing').change(function(){
		var category = $(this).val();
		//var category = $('#category-listing').val();
		var request = $.ajax({
		  url: "ajax.php",
		  method: "POST",
		  data: { id : 1,mode:'movetopage',category:category},
		  dataType: "html",
		  beforeSend: function() {
				$("#loaderDiv").show();
			},
		});
		request.done(function( msg ) {		
			$( "#forumdata" ).html(msg);
			$("#loaderDiv").hide();
		});
		request.fail(function( jqXHR, textStatus ) {
		  alert( "Request failed: " + textStatus );
		}); 
	});
});
</script>