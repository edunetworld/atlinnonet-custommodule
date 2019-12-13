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
 * @Description: Student OverView Page
*/

//http://localhost/ATLInnonet/blog/index.php?userid=3  - blog view
//http://localhost/ATLInnonet/blog/edit.php?action=add&userid=3  - blog entry
require_once('../config.php');

require_once('../blog/renderer.php');
require_once($CFG->libdir.'/filelib.php');


$PAGE->set_url('/page/student.php');
$show_form_status=false;

$PAGE->set_pagelayout('standard');
$strmessages = "Mentor Overview";
$PAGE->set_title("{$SITE->shortname}: $strmessages");
$PAGE->set_heading("Student Overview");
//echo $OUTPUT->header();

echo "Student Overview Content will go here";
echo '<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FAIMToInnovate&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';

//echo '<a class="twitter-timeline" href="https://twitter.com/AIMtoInnovate?ref_src=twsrc%5Etfw">Tweets by AIMtoInnovate</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
echo '<a class="twitter-timeline" data-width="400" data-height="500" data-theme="dark" data-link-color="#F5F8FA" href="https://twitter.com/AIMtoInnovate?ref_src=twsrc%5Etfw">Tweets by AIMtoInnovate</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
//echo $OUTPUT->footer();
?>



<script type="text/javascript">
require(['jquery'], function($) {
});
</script>

