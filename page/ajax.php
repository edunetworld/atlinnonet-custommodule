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
 * Page ajax
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:10-09-2019
*/

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/render.php');

$id = $_REQUEST['id'];
$mode = $_REQUEST['mode'];
$html = '';
$outcome = new stdClass();
$outcome->success = 0;
$outcome->msg = "Error occurs";
$outcome->replyhtml = '';
$renderobj = new ExternalPageRenderer();
if($mode=='movetopage-peer')
{
	if(isset($_REQUEST['city']))
	{
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$result = $renderobj->get_mentoritem($id,$city,$state);
	}
	else	
	{
		//echo "Here";die;
		$result = $renderobj->get_mentoritem($id);
	}
	echo $result;
}

if($mode=='peer-by-city')
{
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$result = $renderobj->get_mentoritem(1,$city,$state);
	echo $result;
}
if($mode=='school-by-city')
{
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$result = $renderobj->get_SchoolsbyCity($city,$state);
	echo $result;
}
if($mode=='peer-by-school')
{
	$city = $_REQUEST['city'];
	$state = $_REQUEST['state'];
	$state = $_REQUEST['state'];
	$school = $_REQUEST['school'];
	$result = $renderobj->get_mentoritem(1,$city,$state,$school);
	echo $result;
}
?>