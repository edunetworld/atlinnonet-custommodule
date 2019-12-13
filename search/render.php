
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

/* @package core_theme
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:18-01-2018
 * @Description: Custom JavaScript Code
*/

class search_render extends plugin_renderer_base {

	public $userid;
		
	public function __construct() {
		global $DB, $PAGE, $CFG,$USER;
		$this->userid = $USER->id;
	}
	
	public function displayresult($values){
		$content = '<div class="project">
		'.$values["userpic"].'
		<div><h5>'.$values["title"].'</h5></div>
		<p>'.$values["createdate"].'</p>
		<p>'.$values["school"].'</p>
		<div class="pcontent">		
		<div class="mdetail">'.$values['detail'].'</div>
		</div>
		</div>
		';
		
		return $content;
	}
	
	//Display Details of a Single User
	//@Param Object user
	public function display_userprofile_old($user){
		global $USER,$CFG,$DB;
		$content = '';
		$usrimg = get_userprofilepic($user,100);
		$userrole = get_atalrolenamebyid($user->msn);
		$conditions = ($userrole=="mentor") ? " AND ue.status=1 " : '';
		if($user->deleted==1){
			//This User is deleted from portal, but its entry stays in DB (moodle)
			//As moodle keeps deleted user for ForumPost & other refrences
			$content.='<div class="profilesearch">
			<div class="profile">
			<div class="pic">'.$usrimg.'</div>
			<div class="details">
				<h5>'.ucwords($user->firstname).' '.ucwords($user->lastname).'</h5>
				'.ucwords($user->city).'
			</div>
			</div>
			<div class="clearb"></div><br><br>This User account is deactivated
			</div>';
			return $content;
		}
		$projects = get_myactiveprojects($user->id,$conditions);
		$loggedIn_userrole = get_atalrolenamebyid($USER->msn);
		$data = '';		
		if($userrole=='student'){
			$projectcnt = get_userprojectcount($user->id,$user->msn);
			$data = '<p><h7>Active Innovations: </h7>'.$projectcnt.'</p>';
			$data.= '<p><h7>School: </h7>'.get_userschoolname($user->id).'</p>';
		} elseif($userrole=='mentor'){
			$projectcnt = get_userprojectcount($user->id,$user->msn);
			$data = '<p><h7>Active Innovations: </h7>'.$projectcnt.'</p>';
			if(!empty($user->city)){
				$data.= '<p><h7>City: </h7>'.$user->city.'</p>';
			}
			if(!empty($user->lastnamephonetic)){
				$data.= '<p><h7>Education: </h7>'.$user->lastnamephonetic.'</p>';
			}
			if(!empty($user->email)){				
				//$emaildata = ($loggedIn_userrole == "student")? "Not Specified" : $user->email;
				$emaildata = ( $user->email)? $user->email : "Not Specified" ;
				$data.= '<p><h7>Email Address: </h7>'.$emaildata.'</p>';
			}
			if(!empty($user->institution)){
				$data.= '<p><h7>Institution: </h7>'.$user->institution.'</p>';
			}
			if(!empty($user->department)){
				$data.= '<p><h7>Area of Specialization: </h7>'.$user->department.'</p>';
			}
			if(!empty($user->url)){
				$data.= '<p><h7>LinkedIn URL: </h7>'.$user->url.'</p>';
			}
			if(!empty($user->fburl)){
				$data.= '<p><h7>FaceBook URL: </h7>'.$user->fburl.'</p>';
			}
			if(!empty($user->description)){
				$data.= '<p><h7>Professional Summery: </h7>'.$user->description.'</p>';
			}
			if(!empty($user->possibleareaofinterven)){
				$data.= '<p><h7>Possible Area of Intervention: </h7>'.$user->possibleareaofinterven.'</p>';
			}
			$schools = get_assginedschool($user->id);
			if($schools)
			{
				foreach ($schools as $value)
				{
					$data.= '<p><h7>Schools Assigned: </h7>'.$value->schoolname.', '.$value->city.'</p>';
				}
			}
			//echo "<pre>";
			//print_r($schools);
			//echo "</pre>";
		} else{
			if($userrole != 'admin')
				$data.= '<p><h7>School: </h7>'.get_userschoolname($user->id).'</p>';
		}
		$contact = (!empty($user->phone1))? $user->phone1 : 'Not Specified';
		/* if($loggedIn_userrole =="student"){
			$contact = "Not Specified";
		} */
		$data.= '<p><h7>Contact: </h7>'.$contact.'</p>';
		if($userrole != 'admin'){
			if(count($projects)>0){
				$data.= '<p><h7>Innovations: </h7></p>';
				$data.='<ul>';
				foreach($projects as $key=>$values){
					$detailpagelink = $CFG->wwwroot.'/search/profile.php?key='.encryptdecrypt_userid( $values->createdbyuserid,"en");
					$createdusername = $DB->get_record('user',array('id'=>$values->createdbyuserid),'firstname,lastname');
					if($createdusername)
						$name = $createdusername->firstname.' '.$createdusername->lastname;
					$name ='<a href="'.$detailpagelink.'">'.$name.'</a>';
					$data.="<li>$values->project ($values->school), Created By : ".$name."</li>";
				}
				$data.='</ul>';
			}
		}
		$content.='<div class="profilesearch">
			<div class="profile">
				<div class="pic">'.$usrimg.'</div>
				<div class="details">
					<h5>'.ucwords($user->firstname).' '.ucwords($user->lastname).'</h5>
					'.ucwords($user->city).'
				</div>
			</div>
			<div class="clearb"></div>
			<div class="details">
				<p><h7>Name: </h7>'.ucwords($user->firstname).' '.ucwords($user->lastname).'</p>';
			/* if($loggedIn_userrole!="student"){
				//Dont show Email/Contact details to Students
				$content.='<p><h7>Email: </h7>'.$user->email.'</p>';
			} */
			$content.='<p><h7>Email: </h7>'.$user->email.'</p>';
			$content.='<p><h7>Role: </h7>'.ucwords($userrole).'</p>
				'.$data.'
			</div>
		</div>';
		return $content;
	}
	public function display_userprofile($user){
		global $USER,$CFG,$DB;
		$content = '';
		$usrimg = get_userprofilepic($user,100);
		$userrole = get_atalrolenamebyid($user->msn);
		$conditions = ($userrole=="mentor") ? " AND ue.status=1 " : '';
		$schools = get_assginedschool($user->id);
		$contact = (!empty($user->phone1))? $user->phone1 : 'Not Specified';
		if($user->deleted==1){
			//This User is deleted from portal, but its entry stays in DB (moodle)
			//As moodle keeps deleted user for ForumPost & other refrences
			$content.='<div class="profilesearch">
			<div class="profile">
			<div class="pic">'.$usrimg.'</div>
			<div class="details">
				<h5>'.ucwords($user->firstname).' '.ucwords($user->lastname).'</h5>
				'.ucwords($user->city).'
			</div>
			</div>
			<div class="clearb"></div><br><br>This User account is deactivated
			</div>';
			return $content;
		}
		$content.=' <div class="row">
                            
                    <!--left panel start-->
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          
                            <div class="card card-block">
                              <div class="row">
                                  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                  
                                    <div class="pic">'.$usrimg.'</div> 
                                      
                                     <div class="details" style="padding-top: 10px;"><h5><strong>'.ucwords($user->firstname).' '.ucwords($user->lastname).'</strong></h5>'.ucwords($user->city).'</div> 
                                      
                                  </div></div>
                            </div> 
                            <div class="card card-block assigned-schools">
                              
                                <h3>Schools Assigned</h3><br>
                                
                                <div class="assigned-schools-scroll">
                                   <div class="row">                              
                                       <div class="col-md-12 col-sm-12 col-xs-12">
                                       <ul>';
								if($schools)
								{
									foreach ($schools as $value)
									{
									$content.='<li><strong>'.$value->schoolname.'</strong><br>'.$value->city.'</li>';
									}
								}
                    $content.='</ul></div></div></div></div></div>
                    <!--left panel end--> 
      
                    <!--right panel start-->
                        <div  class="col-md-8 col-sm-8 col-xs-12 profile-rightpanel">
                             <div class="card card-block">
                            <h3>Personal Details</h3>
                             <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Name</strong> </div>                                 
                                 <div class="col-md-8 col-sm-8 col-xs-12">'.ucwords($user->firstname).' '.ucwords($user->lastname).'</div>
                             </div>
                                 
                            <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Email</strong> </div>                                 
                                 <div class="col-md-8 col-sm-8 col-xs-12">'.$user->email.'</div>
                             </div> 
                                 
                               <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Contact</strong> </div>                                 
                                   <div class="col-md-8 col-sm-8 col-xs-12">'.$contact.'</div>
                             </div>
                                 
                                <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12" ><strong>City</strong> </div>  
                                    <div class="col-md-8 col-sm-8 col-xs-12">'.$value->city.'</div>
                             </div> 
                                 
                               <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12" ><strong>Role</strong> </div>  
                                    <div class="col-md-8 col-sm-8 col-xs-12">'.ucwords($userrole).'</div>
                             </div>  

                            </div>  

                            <!--block 2-->
                             <div class="card card-block">
                            <h3>Professional Details</h3>
                               <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Education</strong></div> <div class="col-md-8 col-sm-8 col-xs-12">';
								$content.= ($user->lastnamephonetic)?:'Not Specified';
								$content.='</div></div>';
                                 
                        $content.='<div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Institution</strong></div> <div class="col-md-8 col-sm-8 col-xs-12">';
						$content.= ($user->institution)?:'Not Specified';
							$content.='</div></div>';
                        $content.='<div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Area of Specialization</strong></div> <div class="col-md-8 col-sm-8 col-xs-12">';
						$content.= ($user->department)?:'Not Specified';						
						$content.='</div></div>';
                        $content.='<div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>LinkedIn URL</strong></div>  <div class="col-md-8 col-sm-8 col-xs-12">';
						$content.= ($user->url)?:'Not Specified';
								 
						$content.='</div></div>';    
						$content.='<div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>FaceBook URL</strong></div> 
                                 <div class="col-md-8 col-sm-8 col-xs-12">';
						$content.= ($user->fburl)?:'Not Specified';
								 
						$content.='</div></div>';
						$content.='<div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Possible Area of Intervention</strong></div> <div class="col-md-8 col-sm-8 col-xs-12">';
						$content.= ($user->possibleareaofinterven)?:'Not Specified';	 
						$content.='</div></div>';   						
                        $content.='<div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-12"><strong>Professional Summery </strong></div>                                 
                                 <div class="col-md-8 col-sm-8 col-xs-12">
                                      <div class="professional-scroll">'; 
						if(!empty($user->description)){
							$content.= $user->description;
						}			  
						$content.='</div></div>';

						$content.='</div></div></div>
						<!--right panel end--> 
						</div>';
		return $content;
	}
	//Display Details of a Single Project
	//@Param Object project , Object enrolusers, String Projectimage
	public function display_projectdetail($project,$enrolusers,$image){
		global $USER,$CFG;
		$content = '';
		$endate = ($project->endate>0)?date("j M Y", $project->endate):'NA';
		$status = $this->resultprojectstatus($project);
		if(count($enrolusers)>0){
			$data.= '<p><span class="smallheading">Enroled Users: </span></p>';
			$data.='<ul>';
			foreach($enrolusers as $key=>$values){
				$detailpagelink = $CFG->wwwroot.'/search/profile.php?key='.encryptdecrypt_userid( $values->id,"en");
				$name = $values->firstname.' '.$values->lastname;
				$name ='<a href="'.$detailpagelink.'">'.$name.'</a>';
				$userrole = get_atalrolenamebyid($values->msn);
				$data.="<li>$name ($userrole)</li>";
			}
			$data.='</ul>';
		}
		$curr_userrole = get_atalrolenamebyid($USER->msn);
		/* $backlink = $CFG->wwwroot.'../project/assign.php';
		if($curr_userrole == 'mentor')
			$backlink = $CFG->wwwroot.'../project/mentorassign.php'; */
		$content.='<div">
		<h1>
		  <a class="btn btn-primary pull-right goBack">Back</a>
		</h1>
		</div>';
		$detailpagelink = $CFG->wwwroot.'/search/profile.php?key='.encryptdecrypt_userid( $project->userid,"en");
		$createdby = $project->firstname.' '.$project->lastname;
		$content.='<div class="profilesearch">
			<div class="profile">
				<div class="pic"><img width="200px" height="200px" src="'.$image.'"></div>
			</div>
			<div class="clearb"></div>
			<div class="details">
				<h5><span class="plink">'.ucwords($project->fullname).'</span></h5>
			</div>
			<div class="details">
				<p><span class="smallheading">School: </span>&nbsp;&nbsp;'.ucwords($project->school).'</p>
				<p><span class="smallheading">Name: </span>&nbsp;&nbsp;'.ucwords($project->fullname).'</p>
				<p><span class="smallheading">Shortname:</span>&nbsp;&nbsp;'.$project->shortname.'</p>
				<p><span class="smallheading">City:</span>&nbsp;&nbsp;'.$project->city.'</p>
				<p><span class="smallheading">Created On:</span>&nbsp;&nbsp;'.$project->createdate.'</p>
				<p><span class="smallheading">Created By:</span>&nbsp;&nbsp;<a href="'.$detailpagelink.'">'.$createdby.'</a></p>
				<p><span class="smallheading">End Date:</span>&nbsp;&nbsp;'.$endate.'</p>
				<p><span class="smallheading">Status:</span>&nbsp;&nbsp;'.$status.'</p>
				<p><span class="smallheading">Summary:</span>&nbsp;&nbsp;'.$project->summary.'</p>
				'.$data.'
			</div>
		</div>';
		return $content;
	}
	
	//To get Project Status, @params: Course Object
	function resultprojectstatus($values){
		$status = "";
		//Status: active , complete, unapprove, reject
		if($values->startdate>0 && $values->visible==0){
			$status = "Unapprove";
		} else if($values->startdate==0 && $values->visible==0){
			$status = "Rejected";
		} else if($values->enddate>0){
			$status = "Complete";
		} else{
			$status = "Active";
		}
		return $status;
	}
	
	function renderforum($post_array)
	{
		$parent = "";
		$replies = "";
		foreach($post_array as $key=>$values){
			foreach($values as $k=>$v){
				if($v->type=="parent"){
					$parent.= '<div class="forumpost clearfix lastpost firstpost starter" role="region" aria-label="forum-collaboration">
					<div class="row header clearfix">
					<div class="left picture">'.$v->userlink.'</div>
					<div class="topic firstpost starter">
					<div class="subject" role="heading" aria-level="2">'.$v->values->subject.'</div>
					<div class="atlclearfix">
					<div class="author" role="heading" aria-level="2">by
					<a href="'.$v->profileurl.'">'.$v->uname.'</a> - '.$v->values->createddate.'&nbsp;&nbsp;<span class="smallheading">'.$v->category.'</span>
					</div>';
					$parent.='</div>
					</div>
					</div>
					<div class="row maincontent clearfix">
					<div class="left"><div class="grouppictures">&nbsp;</div>
					</div>
					<div class="no-overflow">
					<div class="content">
					<div class="posting fullpost">'.$v->values->message.'
					<div class="attachedimages">'.$v->post_image.'</div>
					</div>
					</div>
					</div>
					</div>
					<div class="row side">
					</div>
					' ;	
				} else{
					$replies.= '<div class="reply">
					<div class="row header clearfix">
					<div class="left picture">'.$v->userlink.'</div>
					<div class="topic firstpost starter">
					<div class="subject" role="heading" aria-level="2">'.$v->values->subject.'</div>
					<div class="atlclearfix">
					<div class="author" role="heading" aria-level="2">by
					<a href="'.$v->profileurl.'">'.$v->uname.'</a> - '.$v->values->createddate.'
					</div>';
					$replies.='</div>
					</div>
					</div>
					<div class="row maincontent clearfix">
					<div class="left"><div class="grouppictures">&nbsp;</div>
					</div>
					<div class="no-overflow">
					<div class="content">
					<div class="posting fullpost">'.$v->values->message.'
					<div class="attachedimages">'.$v->post_image.'</div>
					</div>
					</div>
					</div>
					</div>
					<div class="row side">
					</div>
					</div><br>' ;				
				}
			}
		}
		$content = $parent.'<div style="margin-left:4%;">'.$replies.'</div></div>';
		return $content;
	}
}

?>
