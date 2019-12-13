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
 * @CreatedOn:11-09-19
 * @Description: Mentor Overview Page
*/

require_once('../config.php');

require_once($CFG->libdir.'/filelib.php');


$PAGE->set_url('/page/mentor.php');
$show_form_status=false;

//$PAGE->set_pagelayout('standard');
$strmessages = "Mentor Overview";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("Mentor Overview");
?>
		
<link rel="stylesheet" href="js/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="js/owlcarousel/assets/owl.theme.default.min.css">
<script src="js/owlcarousel/jquery.min.js"></script>
    <script src="js/owlcarousel/owl.carousel.js"></script>
    
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
	$URL= '<a class="nav-link" href="'.$CFG->wwwroot.'/login/index.php" title="Login"><i class="fa fa-sign-in fa-lg"></i> Login</a>';
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
$moms = get_mentorofmonth(0,0,0,6);
//echo new moodle_url('/user/pix.php/4977/f1.jpg');
//echo "<pre>";
//print_r($moms);die;

?>

<!--banner start -->


<div class="container-fluid mentor-of-the-month">

        <div class="row">
          <div>
            
        <!--craousel start-->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
        <img src="images/banner1.jpg" alt="Los Angeles" style="width:100%;">
      </div>

      <div class="item">
        <img src="images/banner2.jpg" alt="Chicago" style="width:100%;">
      </div>
    
      <div class="item">
        <img src="images/banner3.jpg" alt="New york" style="width:100%;">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

        <!--craousel end-->    

          </div>
           
        </div>


    </div>

<!--banner end-->

<!--4 tab section start-->

<div id="gray-bg" class="fourtab-container">
<div class="container-fluid">

<div class="row">

<div class="col-md-3">
	<a href="<?php echo $CFG->wwwroot.'/page/eventlist.php' ?>" style="text-decoration: none;"><div class="fourtab eventtab"><div class="darkshadow">
	<h5>Events</h5>
	<p>Know about mentorship events</p>
	</div></div></a>	
</div>

<!-- <div class="col-md-3">
	<a href="<?php //echo $CFG->wwwroot ?>" style="text-decoration: none;"><div class="fourtab blogtab">
	<h5>Blogs</h5>
	<p>Lorem Ipsum is dummy text</p>
	</div></a>	
</div> -->
<div class="col-md-3">
	<a href="<?php echo $CFG->wwwroot.'/mentorguides/Orientation Brochure-v2.pdf' ?>" style="text-decoration: none;"><div class="fourtab blogtab"><div class="darkshadow">
	<h5>Orientation Brochure</h5>
	<p>Download the Orientation brochure</p>
        </div></div></a>	
</div>
<div class="col-md-3">
	<a href="<?php echo $CFG->wwwroot.'/page/peers.php' ?>" style="text-decoration: none;"><div class="fourtab peertab"><div class="darkshadow">
	<h5>Know your peer</h5>
	<p>Know more about fellow mentors</p>
        </div></div></a>	
</div>

<div class="col-md-3">
	<a href="<?php echo $CFG->wwwroot ?>" style="text-decoration: none;"><div class="fourtab logintab"><div class="darkshadow">
	
    </div></div></a>	
</div>

</div>

</div></div>

<!--4 tab section end--->



<!--mentor of the mpnth and tutorials section start-->

<div id="gray-bg">
<div class="container-fluid">

<div class="row">


<!--left panel start-->
<div class="col-md-8">


	<div class="whitebg mentoroverview">

        <div class="row"><div class="col-md-12">
           <h1> Mentor India </h1> 
          <p class="text-justify">Mentor India is a strategic nation building initiative to engage leaders who can guide and mentor students in over 3000 Atal Tinkering Labs that Atal Innovation Mission has established across India. Mentors of Change are proactive leaders who voluntarily give their time and expertise to guide and enable the students to experience, learn and practice future skills such as design and computational thinking. Mentors of Change are instrumental in making the tinkering labs as successful platforms where students can take advice from current industry leaders and bring that knowledge to practice. </p><br>  
        </div></div>
        
        
		<div class="row"><div class="col-md-12"><h1><a href="<?php echo $CFG->wwwroot.'/page/mentorofmonth.php' ?>" target="_blank" style="color:#1fbeca;" > Mentor of the month </a></h1></div></div>
	
<!--Carousel start-->
<div class="row">
	<div class="col-md-12">

		<div class="owl-carousel owl-theme">

					    <!--item start-->
						
		<?php 
		foreach($moms as $year=>$months){
		foreach($months as $month=>$mentors){
		foreach($mentors as $key=>$value){
			$url = '/user/pix.php/'.$value->userid.'/f1.jpg';
		?>
					  <div class="item">
		<a href="#" target="_blank"> 
			<img src="<?php echo new moodle_url($url); ?>" alt="" class="img-responsive" >

	<strong><?php echo $value->mentor_name ?></strong>
	<?php 	
		echo $value->schoolname;
		echo ($value->schoolcity)?(', '.$value->schoolcity):'';
	?></a>
					  </div>
		<?php }}} ?>
					  <!--item end-->
				 </div>
		</div>
</div>

<!--Carousel end-->
		


	</div>
</div>
<!--left panel end-->



<!--right panel start-->
<div class="col-md-4">
	<div class="row"><div class="col-md-12">

	<div class="bluebg">
	<div class="row"><div class="col-md-12"><h1>Tutorials</h1></div></div>

<div class="row">
	<div class="col-md-5"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_1.ppsx' ?>"><img src="<?php echo $OUTPUT->image_url('module_1', 'theme')?>" class="img-responsive" border="0"></a></div>
	<div class="col-md-7"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_1.ppsx' ?>">Tutorial-1 : Getting the Mindset Right</a></div>
</div>

<div class="row">
	<div class="col-md-5"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_2.ppsx' ?>"><img src="<?php echo $OUTPUT->image_url('module_2', 'theme')?>" class="img-responsive" border="0"></a></div>
	<div class="col-md-7"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_2.ppsx' ?>">Tutorial-2 : Being a Consistent Acheiver</a></div>
</div>

<div class="row">
	<div class="col-md-5"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_3.ppsx' ?>"><img src="<?php echo $OUTPUT->image_url('module_3', 'theme')?>" class="img-responsive" border="0"></a></div>
	<div class="col-md-7"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_3.ppsx' ?>">Tutorial-3 : Being an Inspiring Leader</a></div>
</div>
<div class="row">
	<div class="col-md-5"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_4.ppsx' ?>"><img src="<?php echo $OUTPUT->image_url('module_4', 'theme')?>" class="img-responsive" border="0"></a></div>
	<div class="col-md-7"><a href="<?php echo $CFG->wwwroot.'/mentorguides/MTT_Module_4.ppsx' ?>">Tutorial-4 : A Mentor's Manifesto</a></div>
</div>


	</div>
</div></div>
</div>
<!--right panel end-->



</div>




    
    

</div></div>
</div></div></div></div></div></div>

<!--mentor of the mpnth and tutorials section end-->

<!--footer platform start-->
<footer class="main-footer" style="margin-top:30px; clear: both;position:absolute;width:100%">
	<div class="container-fluid">
		<div class="col-xs-12 col-sm-12 col-md-2"> 
<div class="ibmlogo">
Powered By<br /><img src="<?php echo $OUTPUT->pix_url('ibm-logo', 'theme'); ?>" class="img-responsive"></div> </div>

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
