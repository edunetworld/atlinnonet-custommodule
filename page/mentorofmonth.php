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
 * @CreatedOn:30-08-2019
 * @Description: Mentor of Month Page
*/

require_once('../config.php');

require_once($CFG->libdir.'/filelib.php');


$PAGE->set_url('/page/mentorofmonth.php');
$show_form_status=false;

$PAGE->set_pagelayout('standard');
$strmessages = "Event Overview";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("Event Overview");
?>
<link rel="stylesheet" href="js/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="js/owlcarousel/assets/owl.theme.default.min.css">
<script src="js/owlcarousel/jquery.min.js"></script>
    <script src="js/owlcarousel/owl.carousel.js"></script>
<?php
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
	$URL= '<a class="nav-link" href="'.$CFG->wwwroot.'/login/index.php" title="Login">Login</a>';
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
?>  

<?php 
$USER = new StdClass();
$moms = get_mentorofmonth();
///echo "<pre>";
//print_r($moms);die;
//$USER->id=4977;
?>
<?php //echo new moodle_url('/user/pix.php/'.$USER->id.'/f1.jpg')?>
<!--mentor of the month start-->
<div class="container-fluid">  
<div class="row">
            <div class="col-sm-12 col-md-8"> <h1> Mentor of the month</h1> </div>
          
    </div>
<div class="mentor-of-the-month">
<?php	
	foreach($moms as $year=>$months){
	foreach($months as $month=>$mentors)
	{
		$m = date('F', mktime(0, 0, 0, $month, 10)).'\''.$year;
		echo '<p><div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12"> <h2>'.$m.'</h2> </div>
		</div></p>';
?>    
<!--Carousel start-->
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="owl-carousel owl-theme">
           <!--item start-->
		<?php foreach($mentors as $key=>$value): ?>
            <div class="item">

		<?php $url = '/user/pix.php/'.$value->userid.'/f1.jpg'; ?>
			 <img src="<?php echo new moodle_url($url); ?>" alt="" >
		  <strong><?php echo $value->mentor_name ?></strong>
		<?php 
		echo $value->schoolname;
		echo ($value->schoolcity)?(', '.$value->schoolcity):'';
		?>
            </div>
		<?php endforeach; ?>
            <!--item end-->
    </div></div></div>
<?php 
	}
}
?>
<!--Carousel end-->

<!--mentor of the month end-->
  </div>
  
</div></div></div></div></div>
</div>
<!--footer platform start-->
<footer class="main-footer" style="margin-top:30px; clear: both;position:absolute;width:100%">
	<div class="container-fluid">
		<div class="col-xs-12 col-sm-12 col-md-2"> 
Powered By<img src="<?php echo $OUTPUT->pix_url('ibm-logo', 'theme'); ?>" width="60px"> </div>

			<div class="col-xs-12 col-sm-12 col-md-10"></div>
</div>
</footer>	
<script>
$(document).ready(function() {
	
  $('.owl-carousel').owlCarousel({
	loop: true,
	margin: 10,
	responsiveClass: true,
	responsive: {
	  0: {
		items: 1,
		nav: true
	  },
	  600: {
		items: 5,
		nav: false
	  },
	  1000: {
		items: 5,
		nav: false,
		loop: false,
		margin: 20
	  }
	}
  })
})
</script>	