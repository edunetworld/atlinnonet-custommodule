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
 * @CreatedOn:18-07-2018
*/

class techticket_render extends plugin_renderer_base {
	public $userid;
	public $DB;
	public $usermsn;
	public $recordsperpage;
	public $status;
	public $userrole;
	public $cfg;
	public function __construct($statuslist) {
	    global $USER,$CFG;
	    $this->userid = $USER->id;
		$this->recordsperpage=20;
		$this->status = $statuslist;
		$this->userrole = get_atalrolenamebyid($USER->msn);
		$this->cfg = $CFG;
	}
	
	public function show_searchbox_old(){
		$statusdata = $this->status;
		$option = "<option value=''>Filter By Status</option><option value='0'>All</option>";
		foreach($statusdata as $status){
			$option.='<option value="'.$status->id.'">'.ucwords($status->name).'</option>';
	    }
		$catoption = "";
		$category = get_ticketcategory();
		foreach($category as $key=>$values){
			$catoption.='<option value="'.$key.'">'.$values.'</option>';
	    }
		$content ='<div class="overlay">
		<div id="atlloaderimage" style="display:none;"></div>
		</div>';
		$filterdropdown = '<select class="form-control" id="filter-dropdown" data-info="filterbystatus">
		'.$option.'
		</select>';
		$categorydropdown = "";
		if($this->userrole!="admin"){
			//Create New Ticket button
			$content.='<div class="pull-right"><a href="'.$this->cfg->wwwroot.'/ticket/create.php"><input class="btn btn-primary" value="Create New" type="button"></a></div>';			
		} else{
			$categorydropdown = '<select class="form-control" id="filter-dropdowntwo" data-info="filterbycategory">
			'.$catoption.'
			</select>';
		}
		$content.='<div style="clear:both;"></div>';
		$content.='<div class="pagefilters">
		<div class="filterbox">
			<input id="searchvalue" placeholder="Search by Ticket Title" class="form-control" type="textbox">
		</div>
		<div class="filterbtn">
			<input id="listsearchbtn" class="btn btn-primary" value="Search" type="button">
		</div>
		<div class="filterbtn">
			<input id="listresetbtn" class="btn btn-primary" value="Reset" type="button">
		</div>
		<div class="pull-right" style="margin-left:4px;">
			'.$filterdropdown.'
		</div>
		<div class="pull-right">
			'.$categorydropdown.'
		</div>
		';
		
		$content.= "";
		$content.="</div>";
		
		return $content;
	}
	public function show_searchbox(){
		$statusdata = $this->status;
		$option = "<option value=''>Filter By Status</option><option value='0'>All</option>";
		foreach($statusdata as $status){
			$option.='<option value="'.$status->id.'">'.ucwords($status->name).'</option>';
	    }
		$catoption = "";
		$category = get_ticketcategory();
		foreach($category as $key=>$values){
			$catoption.='<option value="'.$key.'">'.$values.'</option>';
	    }
		$content='';
		$content.='<div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">                    
			<div class="filterbox"><input id="searchvalue" placeholder="Search by Ticket Title" class="form-control" type="textbox"></div></div>   
			<div class="col-md-8 col-sm-8 col-xs-12"> 
            <div class="row tk-btn-box">        
            <div class="col-md-2 col-xs-4"><input id="listsearchbtn" class="btn btn-primary form-control" value="Search" type="button"> </div>             
            <div class="col-md-2 col-xs-4"><input id="listresetbtn" class="btn btn-primary form-control" value="Reset" type="button"> </div>';
		if($this->userrole!="admin"){	
			$content.='<div class="col-md-3 col-xs-4"><a href="'.$this->cfg->wwwroot.'/ticket/create.php" class="btn btn-primary form-control" style="text-align: center !important;">Create</a> </div>';
		}
		else
		{
			$content.='<div class="col-md-5 col-xs-12"><select class="form-control" id="filter-dropdowntwo" data-info="filterbycategory">'.$catoption.'</select></div>';
		}
		$content.='<div class="col-md-5 col-xs-12">
            <select class="form-control" id="filter-dropdown" data-info="filterbystatus">'.$option.'
			</select></div>               
            </div></div></div>';
		
		return $content;
	}
	public function get_tablerows($list,$page,$recordsperpage){
		$content ="<div><table cellpadding='5' class='table-striped datatable ticketsdetails' width='100%'>
		<thead> <tr>
		<th scope='col' width='5%'>S.No</th>
		<th scope='col' width='10%'>Ticket No</th>
		<th scope='col' width='25%'>Title</th>
		<th scope='col' width='15%'>Created By</th>
		<th scope='col' width='20%'>Created On</th>
		<th scope='col' width='10%'>Status</th>";
		$content.= ($this->userrole=="admin")?"<th>Category</th>":"<th scope='col' width='25%'>Latest Comments</th> </tr></thead>";	
		if(count($list)>0){
			$i=($page==1)?1:($page*$recordsperpage-$recordsperpage)+1;
			foreach($list as $ticket){
				if($this->userrole=="admin"){
					$data = get_categoryname($ticket->category);
				} else{
					$data = (!empty($ticket->latest_comment))? substr($ticket->latest_comment, 0, 40)." .." : "";
				}
				$content.='<tr>';
				$content.="<td data-label='S.No : &nbsp;'>".$i."</td>";
				$content.='<td data-label="Ticket No : &nbsp;"><a href="'.$this->cfg->wwwroot.'/ticket/detail.php?id='.$ticket->id.'">Atltk0'.$ticket->id.'</a></td>';
				$content.="<td data-label='Title : &nbsp;'>".$ticket->name."</td>";
				$content.="<td data-label='Created By : &nbsp;'>".$ticket->firstname." ".$ticket->lastname."</td>";
				$content.="<td data-label='Created On : &nbsp;'>".$ticket->createddate."</td>";
				$content.="<td data-label='Status : &nbsp;'>".$ticket->status."</td>";
				$content.="<td data-label='Latest Comments : &nbsp;'>".$data."</td>";
				$content.='</tr>';
				$i++;
			}
		} else{
			$content.="<tr><td colspan='7' align='center'>No Records Found!</td></tr>";
		}
		$content.='</table></div>';
		return $content;
	}
	
	public function showcomments_old($data,$list){		
		$content ='<div class="myreply">
		<div class="myreplya">
			<textarea id="ticket-reply" class="myreplybox" rows="1" placeholder="Write your Reply" maxlength="255"></textarea>
		</div>
		<div class="myreplyb"><a data-id="'.$data->id.'" class="ticketreply" href="javascript:void(0);">Reply</a></div>
		</div>';
		if($data->statusid>3){
			$content=""; //can't reply to closed,invalid,deferred tickets
		}
		$style = (count($list)>0)?"display:block;":"display:none;";
		$content.='<div id="ticketreplies" class="replylist" role="region" aria-label="forum-collaboration" style='.$style.'>	
		<div class="postreply collapse in" aria-expanded="true">';
		if(count($list)>0){
			//<div class="forumpost clearfix lastpost firstpost starter" role="region" aria-label="forum-collaboration">			
			foreach($list as $reply){
			$userobject = (object) array('id'=>$reply->userid,'auth'=>$reply->auth,'username'=>$reply->username,
			'firstname'=>$reply->firstname,'lastname'=>$reply->lastname,'picture'=>$reply->picture);
			$userlink = userpicbyobject($userobject);
			$uname = $reply->firstname." ".$reply->lastname;
			$content.='
				<div class="reply">			
					<div class="topic firstpost starter">
					<div class="subject" role="heading" aria-level="2">'.$reply->reply.'</div>
					<div class="atlclearfix">
					<div class="author" role="heading" aria-level="2">by '.$uname.' '.$userlink.' - '.$reply->createdate.'
					</div>
					</div>
					</div>			
				</div>';			
			}
			$content.='</div>';			
		}
		$content.='<div>';
		return $content;
	}
	
	public function detailpage_old($id,$statusdata,$replys,$data,$userrole){
		//	echo "<pre>";
		//print_r($data);die;
		//createdby	assigned_to
		global $CFG;
		$content = "";
		$option = "<option value='0'>Change Status</option>";
		$tckstatus = "";
		foreach($statusdata as $status){
			if($status->id==$data->statusid)
				$option.='<option value="'.$status->id.'" selected>'.ucwords($status->name).'</option>';
			else
				$option.='<option value="'.$status->id.'">'.ucwords($status->name).'</option>';
			if($status->id==$data->statusid){
				$tckstatus = ucwords($status->name);
			}
		}
		if($userrole=="admin"){
			if($tckstatus=="Open")
				$option.='<option value="delete">Delete</option>';
			$catoption = "";
			$category = get_ticketcategory();
			foreach($category as $key=>$values){
				if($key==$data->category)
					$catoption.='<option value="'.$key.'" selected>'.$values.'</option>';
				else
					$catoption.='<option value="'.$key.'">'.$values.'</option>';
			}
			$content.='<div class="pull-right"><select class="form-control" id="change-category" data-info="filterbycategory" data-id="'.$data->id.'">
			'.$catoption.'
			</select></div>';
			$content.='<div class="pull-right">
			<select class="form-control" id="changestatus" data-info="ticketstatus" data-url="'.$CFG->wwwroot.'/" data-id="'.$data->id.'">
			'.$option.'</select>';
			$content.='</div>
			<div class="clearb"></div>';
		}
		$content.= '<div class="ticketdetail">';
		$content.= '<div class="title">';
		$content.='<p><span class="tlabel">Title:</span><span> '.ucwords($data->name).'</span></p>';
		$content.='<p><span class="tlabel">Status:</span><span> '.$tckstatus.'</span></p>';
		$content.='<p><span class="tlabel">Detail:</span><span> '.$data->description.'</span></p>';
		$userobj = atal_getUserDetailsbyId($data->createdby); // Display User Details
		$content.="<p><span class='tlabel'>Created By:</span><span> <a href='".getuser_profilelink($data->createdby)."'>".$userobj->firstname." ".$userobj->lastname."</a></span></p>";			
		$content.= '</div>';		
		$content.= '<div class="title"><span class="tlabel">Comments:</span></div>';
		$content.= '</div>';
		$content.= '<div class="ticketdetail">';
		$content.= $this->showcomments($data,$replys);
		$content.= '</div>';
		return $content;
	}
	public function detailpage($id,$statusdata,$replys,$data,$userrole){
		global $CFG;
		$content = "";
		$option = "<option value='0'>Change Status</option>";
		$tckstatus = "";
		foreach($statusdata as $status){
			if($status->id==$data->statusid)
				$option.='<option value="'.$status->id.'" selected>'.ucwords($status->name).'</option>';
			else
				$option.='<option value="'.$status->id.'">'.ucwords($status->name).'</option>';
			if($status->id==$data->statusid){
				$tckstatus = ucwords($status->name);
			}
		}
		if($userrole=="admin"){
			if($tckstatus=="Open")
				$option.='<option value="delete">Delete</option>';
			$catoption = "";
			$category = get_ticketcategory();
			foreach($category as $key=>$values){
				if($key==$data->category)
					$catoption.='<option value="'.$key.'" selected>'.$values.'</option>';
				else
					$catoption.='<option value="'.$key.'">'.$values.'</option>';
			}
			$content.='<div class="pull-right"><select class="form-control" id="change-category" data-info="filterbycategory" data-id="'.$data->id.'">
			'.$catoption.'
			</select></div>';
			$content.='<div class="pull-right">
			<select class="form-control" id="changestatus" data-info="ticketstatus" data-url="'.$CFG->wwwroot.'/" data-id="'.$data->id.'">
			'.$option.'</select>';
			$content.='</div>
			<div class="clearb"></div>';
		}
		$userobj = atal_getUserDetailsbyId($data->createdby); // Display User Details
		$content.= '<div class="techdetails">
		<table border="0" width="100%" cellpadding="5">
		<tr>
		 <td width="15%"><span>Title:</span></td>
		 <td>'.ucwords($data->name).'</td>
		 </tr>
		 <tr>
		 <td><span>Detail:</span></td>
		 <td>'.$data->description.'</td>
		 </tr>
		 <tr>
		 <td><span>Created By:</span></td>
		 <td> <a href="'.getuser_profilelink($data->createdby).'">'.$userobj->firstname." ".$userobj->lastname.'</a></td>
		 </tr>
		 <tr>
		 <td><span>Status:</span></td>
		 <td> '.$tckstatus.'</td>
		 </tr></table></div> <br>';
		$content.= '<div class="ticketdetail">';
		$content.= $this->showcomments($data,$replys);
		$content.= '</div>';
		return $content;
	}
	public function showcomments($data,$list){		
		$content ='<div class="myreply">
		<div class="myreplya">
			<textarea id="ticket-reply" class="myreplybox" rows="3" placeholder="Write your Reply" maxlength="255"></textarea>
		</div>
		<div class="myreplyb"><a data-id="'.$data->id.'" class="ticketreply btn btn-primary" href="javascript:void(0);"> <i class="fa fa-reply fa-xs"></i> Reply</a>
		</div>
		</div>';
		if($data->statusid>3){
			$content=""; //can't reply to closed,invalid,deferred tickets
		}
		$style = (count($list)>0)?"display:block;":"display:none;";
		$content.='<div id="ticketreplies" role="region" aria-label="forum-collaboration" style='.$style.'>	
		<div class="postreply collapse in" aria-expanded="true">';
		if(count($list)>0){		
		foreach($list as $reply){
		$userobject = (object) array('id'=>$reply->userid,'auth'=>$reply->auth,'username'=>$reply->username,
		'firstname'=>$reply->firstname,'lastname'=>$reply->lastname,'picture'=>$reply->picture);
		$userlink = userpicbyobject($userobject);
		$uname = $reply->firstname." ".$reply->lastname;
		$content.='<div class="row">
                <div class="col-md-1 col-sm-1 col-xs-3"><a href="#">'.$userlink.'</a></div>  
        <div class="col-md-11 col-sm-11 col-xs-9">
            <div class="reply replylist">			
					<div class="topic firstpost starter">
		<div class="author" role="heading" aria-level="2">by <a href="#"><strong>'.$uname.'</strong></a> '.$reply->createdate.'</div>  
		<div class="atlclearfix">'.$reply->reply.'</div>
					</div>			
				</div>
        </div>   
		</div>'; 
		}
		}
		$content.='</div><div></div></div>';
		
		return $content;
	}
	public function replyAjaxContent($postid,$data)
	{
		global $USER;
		insert_newreply($postid,$data);
		$userobj = atal_getUserDetailsbyId($USER->id);
		//$userobject = (object) array('id'=>$USER->id,'username'=>$USER->username,'firstname'=>$USER->firstname,'lastname'=>$USER->lastname,'picture'=>$USER->picture);
		$userlink = userpicbyobject($userobj);
		$uname = $userobj->firstname." ".$userobj->lastname;
		$content='<div class="row">
                <div class="col-md-1 col-sm-1 col-xs-3"><a href="#">'.$userlink.'</a></div>  
        <div class="col-md-11 col-sm-11 col-xs-9">
            <div class="reply replylist">			
					<div class="topic firstpost starter">
		<div class="author" role="heading" aria-level="2">by <a href="#"><strong>'.$uname.'</strong></a>'.date('S F Y').'</div>  
		<div class="atlclearfix">'.$data.'</div>
					</div>			
				</div>
        </div>   
		</div>'; 
		return $content;
	}
}

?>