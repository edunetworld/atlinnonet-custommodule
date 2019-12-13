<?php
/*
Copyright (C) 2019  IBM Corporation 
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details at 
http://www.gnu.org/licenses/gpl-3.0.html
*/

/* 
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:10-09-2019
 * @Description: Common External Pages Render functions
*/
require_once('../external/commonrender.php');

/*
 * Class to constrct HTML structure
*/

class ExternalPageRenderer extends CommonRender{
	
	public function __construct() {
	    global $DB, $PAGE, $CFG,$USER;
	    $this->userid = $USER->id;
	    $this->DB = $DB;
	    $this->CFG = $CFG;
		$this->recordsperpage=18;
	}
	public function render_peers()
	{
	//echo "<pre> Heer";
	//print_r($mentors);die;
	$html='<div class="mentor-of-the-month">
    <div class="row">';
	$html.= $this->renderLoaderContent();
	
	$html.='<!--Carousel start-->
	<div class="row peer" id="peers-list">';
	
	$html.= $this->get_mentoritem();
	$html.='</div>
			<!-- container-fluid Ends -->
			</div></div>';
	return $html;
	}
	public function get_mentoritem($page=1,$city='',$state=0,$school='')
	{
		$content = '';
		$start_from = ($page-1) * $this->recordsperpage; 
		if($school)
		{
			$total_mentors = get_MentorswithSchools(0,0,$state,$city,$school);
			$mentors=get_MentorswithSchools($start_from,$this->recordsperpage,$state,$city,$school);
		}
		elseif($city)
		{
		$total_mentors = get_MentorswithSchools(0,0,$state,$city);
		$mentors=get_MentorswithSchools($start_from,$this->recordsperpage,$state,$city);
		}
		else
		{
			$total_mentors = get_MentorswithSchools(0,0);
			$mentors = get_MentorswithSchools($start_from,$this->recordsperpage);
		}
		$total_mentor = count($total_mentors);
		$content.='<div style="padding:2%;"><h4><p>Total Mentors : '.$total_mentor.'</h4></p></div></br>';
		//echo "<pre>";
		//print_r($mentors);die;
		if(count($mentors)>0){
		foreach($mentors as $key=>$mentor):
		$content.='<div class="col-sm-12 col-md-2 peer-items">
			<!--item start-->
            <div class="item">
			<a href="#" target="_blank">';
		$url = '/user/pix.php/'.$mentor->id.'/f1.jpg';
		$content.='<img src="'.new moodle_url($url).'" class="img-responsive" alt="" >
		<strong>'.$mentor->firstname.' '.$mentor->lastname.'</strong>';
		$content.= $mentor->schoolname;
		//$content.=($mentor->city)?(', '.$mentor->city):'';
		$content.='</a>
            </div><!-- item end -->
			</div><!-- Carousel end -->';
		endforeach;
		}
		else
			$content.='<div class="col-sm-12 col-md-2 peer-items">No Mentors Found!</div>';
		$content.='<div class="pagination-wrapper" align="center" style="clear:both">';
		($total_mentor>1)?$total_pages =ceil(($total_mentor)/$this->recordsperpage):$total_pages =1;	
		$content.= $this->paginate_function($this->recordsperpage,$page,$total_mentor,$total_pages,'peer');
		$content.='</div>';	
		return $content;
	}
	public function get_filters()
	{
	$states = get_atal_allstates();
		
	$content='<div class="row  peer">
	<div class="col-sm-12 col-md-4"></div>
	<div class="col-sm-12 col-md-8 text-right">
	<div class="col-xs-12 col-sm-12 col-md-1 align-middle"> 
	<div class="form-group">
	<label style="padding-top:9px;">State:</label>
	</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-3">
	<div class="form-group">
      <select class="form-control" name="state" id="id_state">
	  <option disabled selected> Select State</option>';
	foreach($states as $state)
	{
		$content.='<option value="'.$state->id.'">'.$state->name.'</option>';
	}
    $content.='</select>
    </div>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-1 align-middle"> 
	<div class="form-group">
	<label style="padding-top:9px;">District:</label>
	</div>
	</div>
 	<div class="col-xs-12 col-sm-12 col-md-3">
	<div class="form-group">
    <select class="form-control" name="cityid" id="id_city">
        <option value="0">Select District</option>
    </select>
    </div>
 	</div>
	<div class="col-xs-12 col-sm-12 col-md-1 align-middle"> 
	<div class="form-group">
	<label style="padding-top:9px;">School:</label>
	</div>
	</div>
 	<div class="col-xs-12 col-sm-12 col-md-3">
	<div class="form-group">
    <select class="form-control" name="schoolid" id="schoolid">
        <option value="0">Select School</option>
    </select>
    </div>
 	</div>
	</div>
	</div>';
	return $content;
	}
	public function get_SchoolsbyCity($city,$state)
	{
		$result = get_SchoolbyCity($city,$state);
		$html_options = '';
		if($result){
		foreach($result as $school)
		{
			$html_options.="<option value='".$school->atl_id."'>".$school->name."</option>";
		}
		}
		return $html_options;
	}
	
}