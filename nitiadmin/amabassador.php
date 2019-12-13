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
 * amabassador Report Page
 * @CreatedBy:ATL Dev (IBM)
 * @CreatedOn:20-06-2018
*/

require_once('../config.php');
require_once('render.php');
require_once('lib.php');
require_login(null, false);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
require_once($CFG->libdir.'/filelib.php');
$userrole = get_atalrolenamebyid($USER->msn);
/*
if($userrole != 'admin')
	redirect($CFG->wwwroot);
*/
$PAGE->set_url('/nitiadmin/amabassador.php');

//Heading
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('standard');
$strmessages = "NITI Administration : Amabassador List Report";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("NITI Administration : Amabassador List Report");

echo $OUTPUT->header();
echo '<div class="card-block"<h1>
	<a class="btn btn-primary pull-right goBack">Back</a>
	</h1></div>';
echo $OUTPUT->heading(" Regional Amabassador List");
$content = '';
$sql = "SELECT r.*,u.email,u.firstname,u.lastname FROM `mdl_regional_ambassador` r join mdl_user u on u.id=r.userid ";
$mentors = $DB->get_records_sql($sql);
?>
<table class='table bordered-table-report' style="width:100%;">
<th>S.No</th>
<th>Mentor Email</th>
<th>Mentor Firstname</th>
<th>Mentor Lastname</th>
<th>What makes the right Candidate</th>
<th>Addional initiative taken on Ground</th>
<th>Addressing the Challenges</th>
<?php
$i=1;
foreach($mentors as $keys=>$values)
{ ?>
	<tr>
	<td><?php echo $i ?></td>
	<td><?php echo $values->email ?></td>
	<td><?php echo $values->firstname ?></td>
	<td><?php echo $values->lastname ?></td>
	<td style="word-break:break-all;"><?php echo $values->q1 ?></td>
	<td style="word-break:break-all;"><?php echo $values->q2 ?></td>
	<td style="word-break:break-all;"><?php echo $values->q3 ?></td>
	</tr>
<?php $i++;
} 
?>
</table>
<?php
echo $content;
echo $OUTPUT->footer();
?>
