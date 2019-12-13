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
 * @CreatedOn:05-09-19
 * @Description: List all the Peers (Mentors)
*/

require_once('../config.php');

require_once($CFG->libdir.'/filelib.php');
require_once('render.php');


$PAGE->set_url('/page/peers.php');
$show_form_status=false;

$PAGE->set_pagelayout('standard');
$strmessages = "Know your Peers";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("Know your Peers");
?>
<link rel="stylesheet" href="js/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="js/owlcarousel/assets/owl.theme.default.min.css">
<script src="js/owlcarousel/jquery.min.js"></script>
    <script src="js/owlcarousel/owl.carousel.js"></script>
<?php
echo $OUTPUT->header();
$commonpage = new ExternalPageRenderer();
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
?>  
<div class="container-fluid">
    
    <div class="row">
            <div class="col-sm-12 col-md-8"> <h1> Know your peer</h1> </div>
          
    </div>
<?php echo $commonpage->get_filters(); ?>
<?php echo $commonpage->render_peers(); ?>

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
<script>
$(document).ready(function() {
	
$('#page-page-peers #id_state').change(function()
{
		var e2 = document.getElementById("id_city");		
		var  stateid = $('#id_state').val();
		if(stateid=='select_state')
			return false;
		var request = $.ajax({
		  url: "../atalfeatures/ajax.php",
		  method: "POST",
		  data: { id : stateid,mode:'getcityvalue'},
		  dataType: "html",
		  beforeSend: function() {
				//$(".overlay").show();
				//$("#atlloaderimage").show();
			},
		});
		request.done(function( msg ) {
			e2.options.length = 0;
			var myObj = JSON.parse(msg);
			var coptions = document.createElement("option");			
			coptions.value = 0;
			coptions.text = "Select District";
			e2.appendChild(coptions);
			if(myObj.success==1){			
				var data = myObj.replyhtml;			
				if(data.length>0){			
					for(var i = 0; i < data.length; i++) {
						var soption = data[i];
						var cityoptions = document.createElement("option");
						for(var j = 0; j < soption.length; j++) {
							if(j==0){
								cityoptions.value = soption[j];
							} else{
								cityoptions.text = soption[j];
							}
						}
						e2.appendChild(cityoptions);
					}
					//$(".overlay").hide();
					//$("#atlloaderimage").hide();
				}
			}			
		});
		request.fail(function( jqXHR, textStatus ) {
		  alert( "Request failed: " + textStatus );
		}); 	
});
$('#page-page-peers #id_city').change(function()
{
	$("#schoolid").html('<option value="">Select School</option>');
	var city = $("#id_city option:selected").html();
	var state = $("#id_state").val();
	var mode='peer-by-city';
		var data = {'id' : 1,'mode':mode,'city':city,'state':state};
		var request = $.ajax({
		   url: "ajax.php",
		  method: "POST",
		  data:  data,
		  dataType: "html",
		  beforeSend: function() {
				$(".overlay").show();
				$("#atlloaderimage").show();
			},
			success:  function( msg ) {
				$('#peers-list').html('');
				$('#peers-list').html(msg);
				//console.log(msg);
				$(".overlay").hide();
				$("#atlloaderimage").hide();
			}
		});
		// Load Schools Dropdown
		var mode = 'school-by-city';
		var city = $("#id_city option:selected").val();
		var data1 = {'mode':mode,'city':city,'state':state};
		$.ajax({
			url: "ajax.php",
			method: "POST",
			data:  data1,
			dataType: "html",
			success:  function( msg ) {
				$("#schoolid").append(msg);
			}
		});
});
// School Change Event

$('#page-page-peers #schoolid').change(function()
{
	
	var school = $("#schoolid option:selected").val();
	if(school==0)
		return false;
	var state = $("#id_state").val();
	var city = $("#id_city option:selected").html();
	var mode='peer-by-school';
		var data = {'id' : 1,'mode':mode,'city':city,'state':state,'school':school};
		var request = $.ajax({
		   url: "ajax.php",
		  method: "POST",
		  data:  data,
		  dataType: "html",
		  beforeSend: function() {
				$(".overlay").show();
				$("#atlloaderimage").show();
			},
			success:  function( msg ) {
				$('#peers-list').html('');
				$('#peers-list').html(msg);
				//console.log(msg);
				$(".overlay").hide();
				$("#atlloaderimage").hide();
			}
		});
});

$("body").on("click", ".movetopage-peer", function(event){
		//var val = $('#filter-dropdown').val();	
		//if(typeof(val) === 'undefined')
		//	val='mentorlist';
		var city = $("#id_city option:selected").html();
		var state = $("#id_state").val();
		var mode='movetopage-peer';
		if(city!='Select District')
			var data = {'id' : this.id,'mode':mode,'city':city,'state':state};
		else
			var data = {'id' : this.id,'mode':mode};
		
		
		var request = $.ajax({
		   url: "ajax.php",
		  method: "POST",
		  data:  data,
		  dataType: "html",
		  beforeSend: function() {
				$(".overlay").show();
				$("#atlloaderimage").show();
			},
			success:  function( msg ) {
				$('#peers-list').html('');
				$('#peers-list').html(msg);
				//console.log(msg);
				$(".overlay").hide();
				$("#atlloaderimage").hide();
			}
		});
	});
});
</script>	

