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
 * @CreatedOn:23-07-2019
 * @Description: Event List Page
*/

require_once('../config.php');

require_once($CFG->libdir.'/filelib.php');

$PAGE->set_url('/page/eventlist.php');
$show_form_status=false;

$PAGE->set_pagelayout('standard');
$strmessages = "Event Overview";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("Event Overview");
echo $OUTPUT->header();
if (isloggedin()) {
	$userrole = get_atalrolenamebyid($USER->msn);
	if($userrole=="mentor"){
		$URL= '<a class="nav-link" href="'.$CFG->wwwroot.'/atalfeatures/roleofmentor.php" title="Login">Dashboard</a>';
	}
	else
		$URL= '<a class="nav-link" href="'.$CFG->wwwroot.'/my" title="Login">Dashboard</a>';
}
else
	$URL= '<a class="nav-link" href="'.$CFG->wwwroot.'" title="Login"><i class="fa fa-sign-in fa-lg"></i> Login</a>';
echo '<header role="banner" class="pos-f-t navbar navbar-full bg-faded navbar-static-top moodle-has-zindex">
        
            <div class="container-fluid navbar-nav">
                <ul class="navbar-nav hidden-md-down" style="margin-right:2px;">
                    <!-- page_heading_menu -->
                    
                </ul>
        
                <a href="'.$CFG->wwwroot.'" class="navbar-brand has-logo
                        ">
                        <span class="logo hidden-xs-down">
                            <img src="'.$OUTPUT->pix_url('aimlogonew', 'theme').'">
                        </span>
                </a>
                <div class="navbar-brand atalnav-brand"><span class="atlhead">ATL InnoNet</span><br><span class="atlshead">Innovation Networking and Mentoring Platform</span></div>		
        		
                <ul class="navbar-nav hidden-md-down navbar-custom-menu">
                    <!-- custom_menu -->
                    <li class="nav-item">'.$URL.'</li>
                    <!-- page_heading_menu -->
                    
                </ul>
        
        		<div>
        		</div>
        		
                <!-- search_box -->
                <span class="hidden-md-down">
                  
                </span>
            </div>
        </header>';    

$ei = $_GET['ei'];
//echo "Event Overview Content will go here";
$event = get_event($ei);
//echo $OUTPUT->footer();
/*
 [3] => Array
        (
            [eventid] => 1
            [name] => ATL Innonet Launch
            [description] => 24th April'18
            [parentid] => 0
            [eventimage] => 
        )
*/
?>
<!--Blog start-->
  <div class="container-fluid">
    
    <div class="row p-b-2">
            <div class="col-sm-12 col-md-8"> <h1> <?php echo $event->name ?></h1> 
			<div class="text-info"><i>Posted Date: <?php echo $event->postedon ?></i></div>
			
			</div>
          
    </div>

<div class="blogs">
    <div class="row">
	
	<div class="col-sm-12 col-md-4"> <img src="<?php echo $event->eventimage ?>" border="0" class="img-responsive"></div>
	
	<div class="col-sm-12 col-md-8">
	
	<h5 style="line-height:24px;"><span class="text-primary"><b>Event Date:</b></span>  <?php echo $event->eventdate ?><br></h5>
	
	
	<?php echo $event->description ?> 

	</div>
    </div>

  </div>
  
 </div></div>
  </div></div></div></div></div>
 <!--footer platform start-->
<footer class="main-footer" style="margin-top:30px; clear: both;position:absolute;width:100%">
	<div class="container-fluid">
		<div class="col-xs-12 col-sm-12 col-md-2"> 
        <div class="ibmlogo">
Powered By<br /><img src="<?php echo $OUTPUT->pix_url('ibm-logo', 'theme'); ?>" class="img-responsive"></div> </div>

			<div class="col-xs-12 col-sm-12 col-md-10"></div>
</div>
</footer>	
<!--Blog end-->


