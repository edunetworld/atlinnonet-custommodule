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
 * @CreatedOn:21-11-2018
*/
//require_once('../nitiadmin/lib.php');
require_once('../external/commonrender.php');

/*
 * Class to constrct HTML structure for NitiAdmin Administration Pages
*/

class MentorRender extends CommonRender {
	public $userid;
	public $DB;
	public $usermsn;
	public $recordsperpage;
	public function __construct() {
	    global $DB, $PAGE, $CFG,$USER;
	    $this->userid = $USER->id;
	    $this->DB = $DB;
	    $this->usermsn = $USER->msn;
	    $this->CFG = $CFG;
		$this->recordsperpage=25;
	}
	public function getMentorSessionsHtml($id='')
	{
		$html='';
		$condition='';
		if($id)
			$condition='and mu.id='.$id;
		$html.=$this->renderLoaderContent();
		$total_sessions = mentor_sessioncount($condition);
		$html.="<div><div><div> <h2>My Sessions</h2></div></div>";
		$total_session = mentor_sessioncount($condition);	
		$month = date("m");
		$mcondition = "and month(FROM_UNIXTIME(msr.dateofsession))=".$month;
		if($id)
			$mcondition = "and mu.id=$id and month(FROM_UNIXTIME(msr.dateofsession))=".$month;
		$total_session_month = mentor_sessioncount($mcondition);
		//$html.="<div class='row' style='margin-top:3%;'><div class='col-md-4 col-sm-4 col-xs-12'> <h5 style='color:#585555;'>$total_session Sessions Till Date  </h5></div><div class='col-md-4 col-sm-4 col-xs-12'> <h5 style='color:#585555;'>$total_session_month Sessions This Month </h5></div><div class='col-md-4 col-sm-4 col-xs-12'> <a class='btn btn-primary reportbtn' href='mentorsession.php'> Report Session </a></div></div>";
		$html.='<div class="row reportdetails"  >
            <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h4>'.$total_session.'</h4>

                <p> Sessions Till Date</p>
              </div>
              <div class="icon">                
				<i class="fa fa-university"></i>
              </div>
             
            </div>
          </div>
                           
           <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h4><strong>'.$total_session_month.'</strong></h4>

                <p>Sessions This Month</p>
              </div>
              <div class="icon">                
				<i class="fa fa-calendar"></i>
              </div>
             
            </div>
          </div>
                           
        <div class="col-md-4 col-sm-4 col-xs-12">           
            <div class="small-box bg-danger">
              <div class="inner">
                <h4>Report Session</h4>

                <p><a href="mentorsession.php">Click here&raquo;</a></p>
              </div>
              <div class="icon">                
				<i class="fa fa-edit"></i>
              </div>
             
            </div>
          </div>
          </div> ';
		$html.=$this->getSessionListContent($id);
		$html.="</div>";
		return $html;
	}
	public function getSessionListContent($id)
	{
		$content='';
		$condition='';
		if($id)
			$condition='and mu.id='.$id;
		$condition.=' ORDER By id DESC';
		$sessionlist = get_allmysession(0,0,$condition);
		$newSessionlist = array ();
		foreach($sessionlist as $session)
		{
			$newObj = new StdClass();
			$month = explode("-",$session->dateofsession_date);
			$newSessionlist[$month[2]][$month[1]][] = $session;
		}
		$content.="<div style='margin-top:3%;' class='table-container clearfix'>";
		if(count($newSessionlist)>0){
			foreach($newSessionlist as $sessionlist)
			{
				foreach($sessionlist as $session){
					$content.="<p style='color:red'>".date('F Y ', strtotime($session[0]->dateofsession_date))."</p>";
					$content.="<div class='card'> <table class='table datatable'>";
					foreach($session as $list){
						$date = date('d F ',$list->dateofsession);
						$content.="<tr>";
						$start = format_timeforReport($list->starttime);
						$end = format_timeforReport($list->endtime);
						$content.='<td style="width:20%;">
                                  <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-6"><p><i class="fa fa-calendar" aria-hidden="true"></i> '.$date.' </p></div>
                                      <div class="col-md-12 col-sm-12 col-xs-6"><p> <i class="fa fa-clock-o" aria-hidden="true"></i><i class="fa fa-clock"></i> '.$start.'&nbsp;&nbsp;'.$end.'</p></div>
                                </div>
                                </td>';
						$content.='<td style="width:70%">
                                     <div class="row">
                                         <div class="col-sm-12 col-md-12 session-tbl-bor">
                                         <div class="row"><div class="col-md-12 col-sm-12 col-xs-12"> <p> <i class="fa fa-university"></i> <strong>'.$list->schoolname.'</strong> </p> </div>
                                      <div class="col-md-12 col-sm-12 col-xs-12"> <p>'.substr($list->details,0,100).'</p> </div>
                                </div>
                                    
                                </div></div>
                                </td>';
						//$content.="<td style='width:15%;'>";
						//$content.='<div class="row"><div class="col-md-12 col-sm-12 col-xs-4"><p>'.$date.'</p></div><div class="col-md-12 col-sm-12 col-xs-4"><p>'.$start.'</p></div><div class="col-md-12 col-sm-12 col-xs-4"><p>'.$end.'</p></div></div></td>';
						//$content.="<td style='width:35%'>".$list->schoolname."</td>";
						//$content.="<td style='width:40%'>".substr($list->details,0,100)." .</td>";
						//$content.="<td style='width:15%;text-align:center;'><a href='/myession.php'><i aria-hidden='true' title='next' aria-label='next' style='display: inline;padding: //5px;color:#1177d1;' class='icon fa fa-fw fa-arrow-right'></i></a></td>";
						$content.="<td style='width:10%;'>";
						$content.="<a href='".$this->CFG->wwwroot."/mentor/sessiondetail.php?key=".encryptdecrypt_userid($list->id,"en")."'><i class='fa fa-lg fa-eye' title='ViewDetails'></i></a>";
						$content.="<a href='".$this->CFG->wwwroot."/mentor/mentorsession.php?key=".encryptdecrypt_userid($list->id,"en")."' style='margin-left:10%;'><i class='fa fa-lg fa-edit' title='Edit'></i></a>";
						$content.="</td>";
						$content.="</tr>";
					}
					$content.="</table></div>";
				}
			}
		}
		else
			$content.="<p>No Sessions Found! </p>";
		$content.="</div>";
		return $content;
	}	
	
	/*public function show_sessionDetails($data,$school){
		$starttime = format_timeforReport($data->starttime);
		$endtime = format_timeforReport($data->endtime);
		$html='<div">
		<h1>
		  <a class="btn btn-primary pull-right goBack">Back</a>
		</h1>
		</div>';
		$html.="<table><tr><td width='60%' valign='top'><ul class='profilesearch' style='list-style-type:none;'>";
		$html .="<div class='details'></div>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>School:</h7>&nbsp;<font color='#daa520'>".$school->name."</font></p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Address:</h7>&nbsp;".$school->address."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Date Of Session:</h7>&nbsp;".date("d-m-Y",$data->dateofsession)."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Start Time:</h7>&nbsp;".$starttime."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>End Time:</h7>&nbsp;".$endtime."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Session Type:</h7>&nbsp;".getSessionType($data->sessiontype)."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Total Hrs:</h7>&nbsp;".$data->totaltime."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Total Number Of Students:</h7>&nbsp;".$data->totalstudents."</p></li>";
		if(!empty($data->functiondetails))
			$html.="<li class='details' style='padding-top:1%;'><p><h7>Function Detail:</h7>&nbsp;".$data->functiondetails."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Session Detail:</h7>&nbsp;".$data->details."</p></li>";
		$html.="<li class='details' style='padding-top:1%;'><p><h7>Session Reported On:</h7>&nbsp;".date("d-m-Y",$data->timecreated)."</p></li>";
		$html.="</ul></td>";
		$html.="</tr></table>";
		
		//$html = $this->getSessionList_html($data,$school);
		return $html;
	}*/
	public function show_sessionDetails($data,$school)
	{
		$starttime = format_timeforReport($data->starttime);
		$endtime = format_timeforReport($data->endtime);
		$html='<div class="row">                
			<div class="col-md-9 col-sm-9 col-xs-12">
			<div class="row"> 
			<div class="col-md-12 col-sm-12 col-xs-12">
			<h3><i class="fa fa-university"></i> <strong>School:</strong> '.$school->name.' </h3> <br></div></div> 
			<div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12"><strong>Address:</strong> '.$school->address.' <br><br></div></div>  
			<div class="row"> 
			<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="sessionnum">
            <div class="row">
			<div class="col-md-3 col-sm-3 col-xs-6 text-center">
            <i class="fa fa-2x fa-calendar"></i>  <br>
            <strong>Date Of Session</strong><br>
            <h5> '.date("d-m-Y",$data->dateofsession).' </h5></div> 
			<div class="col-md-3 col-sm-3 col-xs-6 text-center">
            <i class="fa fa-2x fa-clock-o"></i>  <br>
            <strong>Time</strong><br>
            <h5> '.$starttime.' - '.$endtime.'</h5></div>
			<div class="col-md-3 col-sm-3 col-xs-6 text-center">
            <i class="fa fa-2x fa-users"></i>  <br>
            <strong>Students</strong><br>
           <h5> '.$data->totalstudents.'</h5></div>                       
            <div class="col-md-3 col-sm-3 col-xs-6 text-center">
            <i class="fa fa-2x fa-hourglass-end"></i>  <br>
           <strong>Total Hrs</strong><br>
            <h5> '.$data->totaltime.'</h5></div></div></div></div></div>  
			<div class="row"> 
          <div class="col-md-12 col-sm-12 col-xs-12"><strong>Session Type:</strong> '.getSessionType($data->sessiontype).'<br><br></div></div>';
	
		$html.='<div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12 text-justify"><strong>Session Detail:  </strong> '.$data->details.'<br><br></div></div>';
			if(!empty($data->functiondetails))
            $html.='<div class="row"><div class="col-md-12 col-sm-12 col-xs-12 text-justify"><strong>Session Detail:  </strong> '.$data->functiondetails.'<br><br></div></div>'; 			
            $html.='<div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12"><strong>Session Reported On:  </strong>'.date("d-M-Y",$data->timecreated).'</div></div> 
			</div>
            <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="sessionpicture">
            <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-12"></div>
            <div class="col-md-10 col-sm-10 col-xs-12">
            <div class="row">
            <!--picture -->
            <div class="col-md-12 col-sm-12 col-xs-12">
			<strong>Session Pictures:</strong><br>';
            $html.=$this->get_sessionpic_url();
			$html.='</div></div></div></div></div> </div></div>';
	return $html;
	}
	public function get_sessionpic_url()
	{
		global $CFG,$USER;
		$output='';
		$key = optional_param('key', '', PARAM_ALPHANUM);
		$id = encryptdecrypt_userid($key,"de");
		$context = context_user::instance($USER->id, MUST_EXIST);
		$fs = get_file_storage();
		$files = $fs->get_area_files($context->id, 'mentorsession_file', 'files_1', $id, "filename", false);
		$i=1;
		foreach ($files as $file) {
		$path = '/' . $context->id . '/mentorsession_file/files_1/' .$file->get_itemid() . $file->get_filepath() . $file->get_filename();
		$url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, false);
		$output.= '<a href="#" data-toggle="modal" data-target="#spic'.$i.'">  <img src="'.$url.'" 
			border="0" class="img-responsive"></a><br>
            <!---popup modal start--->
                            <div class="modal fade" id="spic'.$i.'" role="dialog">
							<div class="modal-dialog">
							  <div class="modal-content">
								<div class="modal-header">
								  <button type="button" class="close" data-dismiss="modal">&times;</button>
								  <h4 class="modal-title"></h4>
								</div>
								<div class="modal-body">
								  <img src="'.$url.'" border="0" class="img-responsive">
								</div></div></div></div>
		<!----popup modal end--->';
		$i++;
		}
		return $output;
	}
}
