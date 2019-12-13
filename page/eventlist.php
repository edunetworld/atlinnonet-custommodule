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

//echo "Event Overview Content will go here";
$events = get_eventslist();
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
<!--post start-->
<div class="allevents">

<div class="row p-b-1 p-l-1">
            <div class="col-sm-12 col-md-12"> <h1> Events</h1> </div>
</div>

<div class="row">
 	<div class="col-md-12">
            
                <div class="blog-carousel">
                    <!--Start single events-->
					<?php
					foreach($events as $event){ ?>
                    <div class="col-md-3">
                    <div class="single-blog-post">
                        <div class="img-holder">
                            <img src="<?php echo $event['eventimage'] ?>" alt="" class="img-responsive">
                            <div class="overlay-style-one">
                                <div class="box">
                                </div>
                            </div>
                        </div>
                        <div class="text-holder">
                            <ul class="meta-info">
                                <li style="text-align:left; display:block;"><a href="#"><i class="fa fa-calendar"></i> <?php echo $event['eventdate'] ?>
                            </ul>
                            <a href="#">
<h3 class="blog-title" style="text-align:left; display:block;">
<a target="_blank" href="event.php?ei=<?php echo $event['eventid'] ?>"><?php echo $event['name'] ?></a></h3>
                            </a> 
                              <div class="text">
                                <p><?php echo $event['trimmed_des'] ?></p>
                            </div>
                            <div class="bottom clearfix">
                                <div class="readmore pull-left">
                                    <a target="_blank" href="event.php?ei=<?php echo $event['eventid'] ?>">Read More <i class="fa fa-arrow-right"></i></a>    
                                </div>
                                <div class="comment pull-right">
                                  <!--  <p><span class="flaticon-multimedia"></span> Comments:15</p>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php } ?> <!--End Foreach-->
                    <!--End single events-->
				
                </div>
                
            </div>		
</div></div>
<!--post end-->
    </div>
<!--Events end-->
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
<script type="text/javascript">
require(['jquery'], function($) {
});
</script>

