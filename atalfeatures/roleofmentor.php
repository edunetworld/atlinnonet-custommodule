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
 * @CreatedOn:03-06-2018
*/


require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot . '/my/lib.php');

redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$reset  = optional_param('reset', null, PARAM_BOOL);

require_login();

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());
if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect(new moodle_url('/admin/index.php'));
}
$userrole = get_atalrolenamebyid($USER->msn);
$strmymoodle = get_string('myhome');
// Check the User Has Filled the Updated the Form
$firstlogin = false;
$user = $DB->get_record('user', array('id'=>$USER->id),'profilestatus');
if(!$user->profilestatus){
	$firstlogin = true;
}
if($firstlogin){
	if($userrole == 'mentor'){
		$url = $CFG->wwwroot.'/atalfeatures/editmentor.php?firstlogin=1&key='.encryptdecrypt_userid($USER->id,"en");
		redirect($url);
	}
}
$userid = $USER->id;  // Owner of the page
$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);
$pagetitle = $strmymoodle;

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

// Start setting up the page
$params = array();
$PAGE->set_context($context);
$PAGE->set_url('/atalfeatures/roleofmentor.php', $params);
$PAGE->set_pagelayout('roleofmentor');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
//$PAGE->set_title($pagetitle);
$PAGE->set_title("{$SITE->fullname}");
$PAGE->set_heading($header);

$SESSION->roleofmentorpage = 1;
// Toggle the editing state and switches
$USER->editing = $edit = 0;

echo $OUTPUT->header();

//echo $OUTPUT->custom_block_region('content');
?>
<?php 
//$exist = $DB->get_record('regional_ambassador',array('userid'=>$USER->id));
//if(!$exist): 
?>
<div>
<!--<a href='ambassadors.php'><img class="img-responsive" src="<?php//echo $OUTPUT->pix_url('amba-banner', 'theme'); ?>"> </a> <br /> -->
<div class="page-context-header card card-block" style="background-color: #d7dfe3;
    color: red;font-size:medium;">

<?php
//endif;
echo $OUTPUT->heading("Role of a Mentor");
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
$pdflink = get_atalvariables('roleofmentor');
$pdflink = $CFG->wwwroot.$pdflink;
$test = '<input type="hidden" id="id_frmcategory"><form id="mform2"></form>';

$content .= '<div class="rolementor-desktop"><object data="'.$pdflink.'" type="application/pdf" width="100%" height="580px"><img src="'.$OUTPUT->image_url('role-of-a-mentor', 'theme').'" width="100%" style="max-width:650px;"><p>Your web browser does not have a PDF plugin. Instead you can download the PDF File. Mobile testing <br><center> <a class="btn btn-primary" href="'.$pdflink.'" download>Click here!</a></center></p></object></div>'.$test;
    
$content .= '<div class="rolementor-mob"><img src="'.$OUTPUT->image_url('role-of-a-mentor', 'theme').'" width="100%" style="max-width:650px;"><p>Your web browser does not have a PDF plugin. Instead you can download the PDF File. Mobile testing <br><center> <a class="btn btn-primary" href="'.$pdflink.'" download>Click 
here!</a></center></p></div>'.$test; 


//$content.= '<br><embed src="'.$pdflink.'" width="100%" height="580px" />'.$test;
//$content.= "<iframe src=\"".$pdflink."\" width=\"100%\" width=\"580px%\"></iframe>".$test;


echo $content;


echo $OUTPUT->footer();

// Trigger dashboard has been viewed event.
//$eventparams = array('context' => $context);
//$event = \core\event\dashboard_viewed::create($eventparams);
//$event->trigger();

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


var slideIndex = 1;
//showSlides(slideIndex);

showSlides();  //Uncomment it to view Event Slide show in this Page

function showSlides() {
    var i;
    var slides = document.getElementsByClassName("customslider");
	var dots = document.getElementsByClassName("dot");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1} 
    slides[slideIndex-1].style.display = "block"; 
    setTimeout(showSlides, 5000); // Change image every 2 seconds
	  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
	  }
	  dots[slideIndex-1].className += " active";
}

function plusSlides(n) {
  showSlides_next(slideIndex += n);
}

function currentSlide(n) {
  showSlides_next(slideIndex = n);
}

function showSlides_next(n) {
  var i;
  var slides = document.getElementsByClassName("customslider");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
require(['jquery'], function($) {
	$(document).ready(function(){
        //$("#myModal").show();
    });
});
</script>