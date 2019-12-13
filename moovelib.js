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
 * @CreatedOn:15-01-2018
 * @Description: Custom JavaScript Code
*/

//variable defination

var class_watson = "Main JS";


//JS Functions ...

//Global Search Box at Header.

var searchelement =  document.getElementById('sitesearch');
if (typeof(searchelement) != 'undefined' && searchelement != null) {
	document.getElementById("sitesearch").addEventListener("click",function(e) {	
	var searchdata =  (document.getElementById('searchdata').value).trim();
		if(searchdata!='')
			performsearch();
	});
}
if (typeof(searchelement) != 'undefined' && searchelement != null) {
	document.getElementById('searchdata').addEventListener("keyup",function(e) {	
	var searchdata =  (document.getElementById('searchdata').value).trim();
		var key = e.which || e.keyCode;
		if (key === 13) { // 13 is enter
		if(searchdata!='')
			performsearch();
		}
	});
}
function performsearch()
{
	var path = document.getElementById("spath").value;
	var data = document.getElementById("searchdata").value;	
	data = encodeURI(data);
	path = path+'/search/?search='+data;
	window.location.href = path;
}

//Show Loader indicator functions
function showatlloader(){
	document.getElementById("atlbox2").style.display = "block";
	document.getElementById("atlloader").style.display = "block";
}

//Hide Loader indicator functions
function hideatlloader(){
	document.getElementById("atlbox2").style.display = "none";
	document.getElementById("atlloader").style.display = "none";
}

//Function to set Project Status: active , complete, unapprove, reject
function setprojectstatus(e){
	var projectid = e.getAttribute('data-id');
	var title = e.getAttribute('data-project');
	var flag = e.getAttribute('data-flag');
	var btnvalue = e.getAttribute('value');
	var myparent = e.parentElement.parentElement.parentElement;
	var mode = "";
	var msg = "";
	var rejectmsg = "";
	if(flag=="a" && btnvalue=="Approve"){
		mode = "approve";
		msg = "Sure Want to  Approve  "+title+" ?";
	} else if(flag=="r" && btnvalue=="Reject"){
		mode = "reject";
		msg = "Sure Want to  Reject  "+title+" ?";
	} else if(flag=="d" && btnvalue=="Delete"){
		mode = "deleteproject";
		msg = "Sure Want to Delete  "+title+" ?";
	} else if(flag=="c" && btnvalue=="Complete"){
		mode = "completeproject";
		msg = "Sure Want to Mark it Complete  "+title+" ?";
	} else{
		mode = "";
	}	
	var r = confirm(msg);
	if(r==true){
		if(mode=="reject"){
			rejectmsg = prompt("Please enter reason for rejection", "Innovation Rejected");
			if (rejectmsg == null) {
				return false;
			}
		}
		if(mode=="deleteproject"){
			rejectmsg = prompt("Please enter reason for Delete", "Innovation Deleted");
			if (rejectmsg == null) {
				return false;
			}
		}
		showatlloader();
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var myObj = JSON.parse(this.responseText);
				if(myObj.success==1){
					hideatlloader();
					if(myparent.getAttribute('class')=='project'){
						myparent.style.display = "none";
					}
				} else{					
					hideatlloader();
					alert(myObj.msg);
				}
			}
		};
		xmlhttp.open("GET", "ajax.php?mode="+mode+"&id="+projectid+"&msg="+rejectmsg, true);
		xmlhttp.send();		
	}	
}

//Function to set Mentor Status Accept or Reject for a Project
function setmentorstatus(e){
	var projectid = e.getAttribute('data-id');
	var title = e.getAttribute('data-project');
	var flag = e.getAttribute('data-flag');
	var btnvalue = e.getAttribute('value');
	var myparent = e.parentElement.parentElement.parentElement;
	var mode = "";
	var msg = "";
	var rejectmsg = "r";
	if(flag=="a" && btnvalue=="Accept"){
		mode = "approve";
		msg = "Sure Want to  Accept  "+title+" ?";
	} else if(flag=="r" && btnvalue=="Reject"){
		mode = "reject";
		msg = "Sure Want to  Reject  "+title+" ?";
	} else{
		mode = "";
	}	
	var r = confirm(msg);
	if(r==true){		
		showatlloader();
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var myObj = JSON.parse(this.responseText);
				if(myObj.success==1){
					hideatlloader();
					if(myparent.getAttribute('class')=='project'){
						myparent.style.display = "none";
					}
				} else{ hideatlloader(); }
			}
		};
		xmlhttp.open("GET", "ajax.php?mode=mentoraccept&flag="+mode+"&id="+projectid+"&msg="+rejectmsg, true);
		xmlhttp.send();		
	}	
}

//This function gets called from Assign mentor Page. (Watson Suggest Mentor)
function showwatsonmentor(projectid){
	var xmlhttp = new XMLHttpRequest();
	var divid = "watsonmentorsuggest";
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				//do noting
				document.getElementById(divid).innerHTML = "No Mentor Found";
			} else{
				//success..	
				if(myObj.replyhtml!=""){
					document.getElementById(divid).innerHTML = "";
					var node = document.createElement("DIV");
					node.className = "watuserprofile";
					var newhtml = decodeURIComponent(myObj.replyhtml);
					newhtml = newhtml.replace(/\+/g, ' ');
					node.innerHTML = newhtml;
					document.getElementById(divid).appendChild(node);
				} else{
					document.getElementById(divid).innerHTML = "No Mentor Found";
				}
			}
		}
	};
	xmlhttp.open("GET", "ajax.php?id="+projectid+"&mode=suggestmentor", true);
	xmlhttp.send();
	
}

//This function gets called from Assign mentor To School Page. (Watson Suggest Mentor)
function showwatsonmentor_forschool(schoolid){
	var xmlhttp = new XMLHttpRequest();
	var divid = "watsonmentorsuggest";
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				//do noting
				document.getElementById(divid).innerHTML = "No Mentor Found";
			} else{
				//success..	
				if(myObj.replyhtml!=""){
					document.getElementById(divid).innerHTML = "";
					var node = document.createElement("DIV");
					node.className = "watuserprofile";
					var newhtml = decodeURIComponent(myObj.replyhtml);
					newhtml = newhtml.replace(/\+/g, ' ');
					node.innerHTML = newhtml;
					document.getElementById(divid).appendChild(node);
				} else{
					document.getElementById(divid).innerHTML = "No Mentor Found";
				}
			}
		}
	};
	xmlhttp.open("GET", "ajax.php?id="+schoolid+"&mode=suggestmentorschool", true);
	xmlhttp.send();
	
}

//This function is use to assign Watson Suggest Mentors into a Project
function assign_suggestmentor(e){
	var xmlhttp = new XMLHttpRequest();
	var mentorid = e.getAttribute('data-url');
	var projectid = document.getElementById("pid").value;
	projectid = genraterandomnum1(projectid);
	var divid = "";
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				mycloseFunction();
			} else{
				//success..
				mycloseFunction();	
				divid = "enm"+document.getElementById("pid").value;
				var node = document.createElement("DIV");
				node.className = "userprofile";
				node.innerHTML = myObj.replyhtml;
				document.getElementById(divid).appendChild(node);
			}
		}
	};
	xmlhttp.open("GET", "ajax.php?id="+projectid+"&key="+mentorid+"&mode=enrolmentor", true);
	xmlhttp.send();	
}

//This function is use to assign Watson Suggest Mentors into a School
function assign_suggestmentorschool(e){
	var xmlhttp = new XMLHttpRequest();
	var mentorid = e.getAttribute('data-url');
	var schoolid = document.getElementById("sid").value;	
	var divid = "";
	schoolid = genraterandomnum1(schoolid);
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				mycloseFunction();
			} else{
				//success..
				mycloseFunction();
				divid = "ems"+document.getElementById("sid").value;
				var node = document.createElement("DIV");
				node.className = "userprofile";
				node.innerHTML = myObj.replyhtml;
				document.getElementById(divid).appendChild(node);
			}
		}
	};
	xmlhttp.open("GET", "ajax.php?id="+schoolid+"&uid1="+mentorid+"&mode=assign", true);
	xmlhttp.send();	
}

//geraterandomnumber function to be used in project page, enrol user to project.
var genraterandomnum1 = function(id) {
	var idd = Number(id) + Number(1);
	var randam = Math.floor(Math.random() * 666) + 1;
	randam = "3E4"+randam+"A"+idd+"A"+id;
	return randam;
}

//function to delete Post, when user clicks on delete icon, then it opens an Confirm poupbox
function postdelete(element){
	var postid = element.getAttribute("data-id");
	document.getElementById("deletemsg").textContent = "Processing ...";
	document.getElementById("deletemsg").style.display = "none";
	document.getElementById("atlbox2").classList.remove("hide");
	document.getElementById("atlboxdel").classList.remove("hide");
	document.getElementById("atlboxdel").style.display = "block";
	document.getElementById("delpostid").value = postid;			
}

//Ajax call for delete post, which get call when user click on delete btn in delete popup
function deleteforumpost(path){
	document.getElementById("deletemsg").style.display = "block";
	var xmlhttp = new XMLHttpRequest();
	var id = document.getElementById("delpostid").value;
	var pid = genraterandomnum1(id);
	var elemid = "p"+id;
	var urlpath = document.getElementById("liburlpath").value;
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				document.getElementById("deletemsg").textContent = "cannot able to Delete, Error occurs !" ;
			} else{
				mycloseFunction();
				document.getElementById("deletemsg").textContent = "Success!";
				document.getElementById(elemid).style.display = "none";
			}
		}
	};
	if(path==''){
		xmlhttp.open("GET", "ajax.php?id="+pid+"&mode=del", true);
	} else{
		xmlhttp.open("GET", urlpath+"?id="+pid+"&mode=del", true);
	}
	xmlhttp.send();
}

function closenewjspopup(){
	var searchproj =  document.getElementById('newprojectpoup');
	var searchnew =  document.getElementById('atlnewpopupbox');
	document.getElementById("atlbox2").classList.add("hide");
	document.getElementById("atlbox2").style.display = "none";
	if (typeof(searchnew) != 'undefined' && searchnew != null) {
		document.getElementById("atlnewpopupbox").classList.add("hide");
		document.getElementById("atlnewpopupbox").style.display = "none";
	}
	if (typeof(searchproj) != 'undefined' && searchproj != null) {
		document.getElementById("newprojectpoup").classList.add("hide");
		document.getElementById("newprojectpoup").style.display = "none";
	}
}

//Ajax call for delete post, which get call when user click on delete btn in delete popup
function approveforumpost(){
	document.getElementById("approvemsg").style.display = "block";
	var xmlhttp = new XMLHttpRequest();
	var id = document.getElementById("approvepostid").value;
	var pid = genraterandomnum1(id);
	var elemid = "p"+id;
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				document.getElementById("approvemsg").textContent = "cannot able to Approve, Error occurs !" ;
			} else{
				closenewjspopup();
				document.getElementById("approvemsg").textContent = "Success!";
				document.getElementById(elemid).style.display = "none";
			}
		}
	};
	xmlhttp.open("GET", "ajax.php?id="+pid+"&mode=approve", true);
	xmlhttp.send();
}

//To remove/unassign user from a project
function unenroluser(e){
	var r = confirm("Sure Want To Remove this user from Project ?");
	if (r === false) {
		return false;
	}
	var xmlhttp = new XMLHttpRequest();
	var userid = e.getAttribute('data-user');
	var projectid = e.getAttribute('data-project');
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==1){
				//Success, hide userprofile in DOM
				var x = e.parentElement.parentElement;
				if(x.getAttribute('class')=="userprofile"){
					x.style.display = "none";
				}
			}
		}
	};
	xmlhttp.open("GET", "ajax.php?id="+projectid+"&uid="+userid+"&mode=unenroll", true);
	xmlhttp.send();	
}

//function to report missue
function postmisuse(element){
	var postid = element.getAttribute("data-id");
	document.getElementById("misusemsg").textContent = "Processing ...";
	document.getElementById("misusemsg").style.display = "none";
	document.getElementById("atlbox2").classList.remove("hide");
	document.getElementById("atlboxmisuse").classList.remove("hide");
	document.getElementById("atlboxmisuse").style.display = "block";
	document.getElementById("mispostid").value = postid;
}

//Ajax call for delete post, which get call when user click on delete btn in delete popup
function reportpostmisuse(path){
	document.getElementById("misusemsg").style.display = "block";
	var xmlhttp = new XMLHttpRequest();
	var id = document.getElementById("mispostid").value;
	var pid = genraterandomnum1(id);
	var urlpath = document.getElementById("liburlpath").value;
	var type = document.getElementById("misusetype").value;
	var aid = "#misa"+id;
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==0){
				document.getElementById("misusemsg").textContent = "cannot Report, Error occurs !" ;
			} else{
				mycloseFunction();
				document.getElementById("misusemsg").textContent = "Success!";				
				$(aid).parent('div').removeClass("atlmisuse"); //remove black flag
				$(aid).parent('div').addClass("atlmisusered"); //show red flag
			}
		}
	};
	if(path==''){
		xmlhttp.open("GET", "ajax.php?id="+pid+"&mode=reportspam&type="+type, true);
	} else{
		xmlhttp.open("GET", urlpath+"?id="+pid+"&mode=reportspam&type="+type, true);
	}
	xmlhttp.send();
}

//Function to Remove Mentor from a School
function removementorschool(element){
	var r = confirm("Sure want to remove this mentor from school ?");
	if (r === false) {
		return false;
	}
	var xmlhttp = new XMLHttpRequest();
	var schoolid = element.getAttribute("data-sid");
	var userid = element.getAttribute("data-user");
	document.getElementById("atlbox2").style.display = "block";
	document.getElementById("atlloader").style.display = "block";
	var myparent = element.parentElement.parentElement;
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var myObj = JSON.parse(this.responseText);
			if(myObj.success==1){
				myparent.style.display="none";
			}
			document.getElementById("atlbox2").style.display = "none";
			document.getElementById("atlloader").style.display = "none";
		}
	};
	xmlhttp.open("GET", "ajaxnew.php?mode=mentorschool&sid="+schoolid+"&id="+userid, true);
	xmlhttp.send();
}

//Binding of events to JS Functions ...

require(['jquery'], function($) {
	$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
	});
	$(document).ready(function(){
		$("body").on("click", ".goBack", function(event){
			window.history.back();
		});
	});


	//To Hide Miscellaneous Box in User Profile Page.
	var bodyid = $('body').attr('id');
	if(bodyid=="page-user-profile"){	
		$(".profile_tree section:nth-child(2)").hide();
		$(".profile_tree section:nth-child(3)").hide();
	}
	
	var loadcityvalue = function() {
		var e2 = document.getElementById("id_cityid");		
		var  stateid = $('#id_state').val();
		if(stateid=='select_state')
			return false;
		var request = $.ajax({
		  url: "ajax.php",
		  method: "POST",
		  data: { id : stateid,mode:'getcityvalue' },
		  dataType: "html",
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
				}
			}
			var city = $("input[name=city]").val();
			if(city!=0 && typeof city!='undefined')
				$('#id_cityid').find('option[value='+city+']').attr("selected",true);			
		});
		request.fail(function( jqXHR, textStatus ) {
		  alert( "Request failed: " + textStatus );
		}); 	
	}
	
	//Create School. .. Loading City value Based on State value
	$('#page-create-createschool #id_state,#page-site-index #id_state,#page-atalfeatures-editmentor #id_state,#page-atalfeatures-creatementor #id_state,#filter-mentor #id_state,#filter-school #id_state,#page-atalfeatures-selectschool #id_state').change(loadcityvalue);
	//Populating CityValue in Create School Page for Edit school info.
	if ( $( "#page-create-createschool #id_state,#page-atalfeatures-editmentor #id_state,#page-atalfeatures-creatementor #id_state,#page-atalfeatures-selectschool #id_state,#page-atalfeatures-selectschool #id_state" ).length ) {
		if($('#id_state').val()!='Select State' && $('#id_state')!='undefined')	{
			loadcityvalue();
		}
	}
	
	$('#page-create-createschool #id_cityid,#page-atalfeatures-editmentor #id_cityid,#page-atalfeatures-creatementor #id_cityid').change(function(){
		var cityid = $('#id_cityid option:selected').val();
		if(cityid==""){
			cityid = "";
		}
		document.getElementsByName("city")[0].value = cityid;
	});
	
	//Forum Replies.
	$("body").on("click", ".sentreply", function(event){
		var filename = "ajax.php";
		if($('#liburlpath').length)	{
			if($('#liburlpath').val()!==''){
				filename = $('#liburlpath').val();
			}
		}		
		var discussionid = this.getAttribute("data-id");
		//Save My Reply ..
		var textareaid = "area"+discussionid;
		var textarea1 = document.getElementById(textareaid).value;
		if(textarea1==""){
			alert("Please Enter your Reply");
			return false;
		}
		var errorreplydiv = "#myerrmsg"+discussionid;
		$(errorreplydiv).html("");
		var cleartextarea = false;
		var did = genraterandomnum1(discussionid);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var myObj = JSON.parse(this.responseText);
				if(myObj.success==1){
					document.getElementById(textareaid).value = "";
					var replydiv = "myrep"+discussionid;
					var data1 = document.getElementById(replydiv).innerHTML;
					data1 = data1 + myObj.replyhtml;
					//append the reply msg below previous one;
					//$(replydiv).append(data1);
					document.getElementById(replydiv).innerHTML = data1;
					cleartextarea = true;
				} else{
					if(myObj.msg=='badwords'){
						$(errorreplydiv).html(myObj.replyhtml);
						$(errorreplydiv).show();
					}
				}
			}
		};
		xmlhttp.open("POST", filename , true);
		xmlhttp.setRequestHeader("Content-type", "application/json");
		var data = JSON.stringify({"disid": did, "reply": textarea1});
		xmlhttp.send(data);		
		if(cleartextarea){
			document.getElementById(textareaid).value = "";
		}
	});
	
	//To Hide AdditinalNames, Interest, Optional Box in User Profile Edit Page.
	if(bodyid=="page-user-edit"){	
		$("#id_moodle_additional_names").hide();
		$("#id_moodle_interests").hide();
		$("#id_moodle_optional").hide();
		if($("#atlglobaluserrole").val()!="mentor"){
			$("#id_category_1").hide(); //Govt PhotoId Optional field
		}
	}

//Delete Student From Student Lisitng Page
$("body").on("click", ".user-delete", function(event) {
	var content_mode = $(this).attr('data-content');
	var mode = "delete"+content_mode;
	 var content = "Are you Sure You Want to Delete the ";
	 //content+= ($(this).attr('data-content')).toUpperCase();
	 content+=content_mode+" ?";
	 
	var r = confirm(content);
	if (r == true) {
				var delete_id= true;
			} else {
				var delete_id= false;
			}
		if(delete_id){
					var id = $(this).attr('data-id');
					var data= {'id' : 1,'mode':mode,'delete_id':id};
					var alert_message = "Deleted Successfully!";
					triggerAjaxRequest(data,true,alert_message);
		} 
});
				//Delete Event From Event Lisitng Page
	$("body").on("click", ".showprojectcomments", function(event){
		var id = $(this).attr('data-id');
		var divid = "innovid"+$(this).attr('refid');
		var refstatus = $(this).attr('refstat');
		if(refstatus=="reject"){
			document.getElementById(divid).style.display = "none";
			//Remove Rejected project from Tab , after student read the comment;
		}
		$("#atlbox2").show();
		$('#projectmsgdiv').html("");
		$("#newprojectpoup").show();
		var request = $.ajax({
		url: "ajaxnew.php",
		method: "POST",
		data:  { 'id' : id,'mode':'getprojectcomments'},
		dataType: "html",
		beforeSend: function() {
				$('#projectmsgdiv').html("Please wait ..");
			},
		});
		request.done(function( msg ) {
			var myObj = JSON.parse(msg);
			$('#projectmsgdiv').html(myObj.replyhtml);			
		});
		request.fail(function( jqXHR, textStatus ) {
			$('#projectmsgdiv').html("No Notification Found !");		
		});
	});
	
	//Common Poupbox in project with close button
	$("body").on("click", ".newclosebtn", function(){
		closenewpopup();
	});
	
	function closenewpopup(){
		$("#atlbox2").hide(); //main background box
		$("#newprojectpoup").hide(); //common popupbox on atlbox2
		$("#atlnewpopupbox").hide(); //common popupbox on atlbox2
	}
	
	//Post Approve Button click
	$('#postapprovebtn').click(function(){
		approveforumpost();
	});
	
	//function to Approve Post, when Incharge clicks on Approve icon, then it opens an Confirm poupbox
	$("body").on("click", ".atlapprove", function(event){
		var postid = $(this).attr('data-id');
		$('#approvemsg').hide();
		$('#approvemsg').html("Processing ...");		
		$("#atlbox2").show();
		$('#atlnewpopupbox').show();
		$('#approvepostid').val(postid);
	});
	
	//Forum Bad word check from Forum page.
	$('#id_submitbutton').click(function(event){
		var flag = $("input[name=isforumflag]").val();
		if (typeof(flag) != 'undefined' && flag != null) {
			event.preventDefault();
			var name = $('#id_name').val();
			var msg = $('#id_message').val();			
			var path = "wordfilter.php";
			var frmlocation = $("input[name=forumlocation]").val();
			$('#id_error_name').html("");
			$('#id_error_message').html("");
			if(name==""){
				$('#id_error_name').html("Error, Please Enter Title !");
				$('#id_error_name').show();
				return false;
			}
			if(msg==""){
				$('#id_error_message').html("Error, Please Enter Description !");
				$('#id_error_message').show();
				return false;
			}
			if(flag=='y' && name!=""){		
				//Ajax call;
				var request = $.ajax({
				url: path,
				method: "POST",
				data:  { 'name' : name,'message' : msg,'mode':'wordfilter'},
				dataType: "html"
				});
				request.done(function( msg ) {
					var myObj = JSON.parse(msg);
					if(myObj.success==1){
						$('#id_error_message').html(myObj.replyhtml);
						$('#id_error_message').show();
					} else{
						$("#mform1").submit();
						//$(this).parents('form:first').submit();
					}
				});
				request.fail(function( jqXHR, textStatus ) {
					$('#id_error_message').html("Error, cannot Submit data !");
				});		
			}
		} else{
			var flag = $("input[name=isticketflag]").val();
			if (typeof(flag) != 'undefined' && flag != null) {
				$("#atlbox2").show();
				$("#atlloader").show();
			}
			var flag = $("input[name=isrptmentorsession]").val();
			if (typeof(flag) != 'undefined' && flag != null) {
				//Report Your Session By mentor, i.e session feedback form.
				setsessiontimeinform();
			}
		}
	});
	
	//Forum Bad word check from my dashboard page
	$('#id_saveforum').click(function(event){
		var flag = $("input[name=isforumflag]").val();
		if (typeof(flag) != 'undefined' && flag != null) {
			event.preventDefault();
			var name = $('#id_title').val();
			var msg = $('#id_detail').val();
			var path = "wordfilter.php";
			var frmlocation = $("input[name=forumlocation]").val();
			$('#id_error_title').html("");
			$('#id_error_detail').html("");
			if(name==""){
				$('#id_error_title').html("Error, Please Enter Title !");
				$('#id_error_title').show();
				return false;
			}
			if(msg==""){
				$('#id_error_detail').html("Error, Please Enter Description !");
				$('#id_error_detail').show();
				return false;
			}
			if(flag=='y' && name!=""){		
				var forumfilterpath = $("input[name=forumfilterpath]").val();
				if(forumfilterpath!="" || forumfilterpath != null){
					path = forumfilterpath;
				}
				//Ajax call;
				var request = $.ajax({
				url: path,
				method: "POST",
				data:  { 'name' : name,'message' : msg,'mode':'wordfilter'},
				dataType: "html"
				});
				request.done(function( msg ) {
					var myObj = JSON.parse(msg);
					if(myObj.success==1){
						$('#id_error_detail').html(myObj.replyhtml);
						$('#id_error_detail').show();
					} else{
						$("#mform2").submit();
						//$(this).parents('form:first').submit();
					}
				});
				request.fail(function( jqXHR, textStatus ) {
					$('#id_error_detail').html("Error, cannot Submit data !");
					$('#id_error_detail').show();
				});		
			}
		}
	});
	//View Replies & Hide Replies in Forum and Dashboard Page
	$("body").on("click", ".expandpost", function(event){
		var divid = $('#'+event.target.id).closest( "div" );
		var spanTextObj = $('#'+event.target.id).closest( "div" ).attr('aria-expanded');
		if(typeof spanTextObj === 'undefined' || spanTextObj=="false")
		{
			$('#'+event.target.id).closest("div").find('span').html('Hide Replies ');
			$('#'+event.target.id).closest("div").find('i').removeClass('fa-arrow-down');
			$('#'+event.target.id).closest("div").find('i').addClass('fa-arrow-up');
		}
		else
		{
			$('#'+event.target.id).closest( "div" ).find('span').html('View Replies');
			$('#'+event.target.id).closest("div").find('i').removeClass('fa-arrow-up');
			$('#'+event.target.id).closest("div").find('i').addClass('fa-arrow-down');
		}
	});
	
	//Show any Project msg to Atal Incharge ..such as Project Accept/Reject by Mentors
	$("body").on("click", ".showprojectmsgincharge", function(event){
		var id = $(this).attr('data-id');
		var divid = "innovid"+$(this).attr('refid');
		var refstatus = $(this).attr('refstat');		
		$("#atlbox2").show();
		$('#projectmsgdiv').html("");
		$("#newprojectpoup").show();
		var request = $.ajax({
		url: "ajaxnew.php",
		method: "POST",
		data:  { 'id' : id,'mode':'getprojectcomments'},
		dataType: "html",
		beforeSend: function() {
				$('#projectmsgdiv').html("Please wait ..");
			},
		});
		request.done(function( msg ) {
			var myObj = JSON.parse(msg);
			$('#projectmsgdiv').html(myObj.replyhtml);			
		});
		request.fail(function( jqXHR, textStatus ) {
			$('#projectmsgdiv').html("No Notification Found !");		
		});
	});	
	//Allow Only Characters/Space/Backspace&Tab in Name Field
	// Removed - #page-create-createschool #id_name,
	$( "#page-atalfeatures-creatementor #id_name,#page-atalfeatures-creatementor #id_lastname,#page-atalfeatures-creatementor #id_refree1_name,#page-atalfeatures-creatementor #id_refree2_name,#page-atalfeatures-editmentor  #id_refree1_name,#page-atalfeatures-editmentor #id_refree2_name,#page-atalfeatures-editmentor #id_lastname,#page-atalfeatures-editmentor #id_name,#page-create-createschool #id_principal_name,#page-create-createschool #id_firstname,#page-create-createschool #id_lastname,#page-project-createstudent #id_firstname,#page-project-createstudent #id_lastname" ).keypress(function( e ) {
		try {
			if (window.event) {
				var charCode = window.event.keyCode;
			}
			else if (e) {
				var charCode = e.which;
			}
			else { return true; }
			// Allows Only Characters/Space/Backspace/Tab/Delete
			if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode==8 ) || (charCode == 32) || (charCode == 9) || (charCode == 46))
				return true;
			else
				return false;
		}
		catch (err) {
			alert(err.Description);
		}
	});
	//Mentor Schedule
	$('#mentorscheduley').click(function(){
		var url = $(this).attr('data-url');
		var id = $(this).attr('data-id');
		//Ajax call;
		var request = $.ajax({
		url: url,
		method: "GET",
		data:  { 'mode':'mentorschedule','id' : id,'status' : 'y'},
		dataType: "html"
		});
		request.done(function( msg ) {
			$('#mn-statusbox').hide();
			$('#mn-statusbox2').hide();
		});
		request.fail(function( jqXHR, textStatus ) {			
			$('#mn-statusbox').hide();
			$('#mn-statusbox2').hide();
		});	
		
	});
	//Mentor Schedule
	$('#mentorschedulen').click(function(){
		var url = $(this).attr('data-url');
		var id = $(this).attr('data-id');
		//Ajax call;
		var request = $.ajax({
		url: url,
		method: "GET",
		data:  { 'mode':'mentorschedule','id' : id,'status' : 'n'},
		dataType: "html"
		});
		request.done(function( msg ) {
			$('#mn-statusbox').hide();
			$('#mn-statusbox2').hide();
		});
		request.fail(function( jqXHR, textStatus ) {			
			$('#mn-statusbox').hide();
			$('#mn-statusbox2').hide();
		});	
		
	});
	$("body").on("click", ".movetopage-mentor-report", function(event){
		var val = $('#filter-dropdown').val();	
		if(typeof(val) === 'undefined')
			val='mentorlist';
		var mode='movetopage-mentor';
		var data = {'id' : this.id,'mode':mode,'filterby':val};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", ".movetopage-school-report", function(event){
		var mode=$('#filter-mode').val();
		if(mode == 'filter-schoolactivity-atlid')
			mode = 'all-schoolactivity';
		else 
			var mode='allschool-list';
		var data = {'id' : this.id,'mode':mode,'filterby':'all'};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", ".movetopage-meeting-report", function(event){
		var dval = $('#filter-dropdown-ms-meeting').val();	
		var name=$('#id_schoolatlid').val();
		var mode='movetopage-meeting';
		var inputObj = {'val':dval,'name':name};
		console.log(inputObj);
		var data = {'id' :this.id,'mode':mode,'filterby':JSON.stringify(inputObj)};
		triggerAjaxRequest(data,false);
	});
	//filter mentor list
	
	//Common filter dropdowns: Ticket system	
	$('#filter-dropdown').change(function(){
		var val = $(this).val();
		var mode='filter-mentor';
		if($("#filter-mode").val()=='filter-schoolactivity-atlid')
			var mode='filter-schoolactivity';
		var filterdropdown_value = '0';
		var datainfo =  $(this).attr('data-info');
		if (typeof(datainfo) != 'undefined' && datainfo != null) {
			mode=datainfo;
		}
		var searchelemn =  document.getElementById('filter-dropdowntwo');
		if(typeof(searchelemn) != 'undefined' && searchelemn != null){
			filterdropdown_value = $('#filter-dropdowntwo').val();
		}
		var data = {'id' :1,'mode':mode,'filterby':val,'filterbytwo':filterdropdown_value};
		$('#id_schoolatlid').val('');
		$('#id_studentemail').val('');
		$('#id_mentoremail').val('');
		triggerAjaxRequest(data,false);
	});
	$('#filter-dropdowntwo').change(function(){
		var val = $(this).val();
		var mode='filter-mentor';
		var filterdropdownone_value = '0';
		var datainfo =  $(this).attr('data-info');
		if (typeof(datainfo) != 'undefined' && datainfo != null) {
			mode=datainfo;
		}
		var searchelemn =  document.getElementById('filter-dropdown');
		if(typeof(searchelemn) != 'undefined' && searchelemn != null){
			filterdropdownone_value = $('#filter-dropdown').val();
		}
		var data = {'id' :1,'mode':mode,'filterby':val,'filterbytwo':filterdropdownone_value};
		triggerAjaxRequest(data,false);
	});
	
	$('#filter-dropdown-ms-meeting,#filter-ms-meeting-month').change(function()
	{
		var mode = (this.id == "filter-dropdown-ms-meeting") ? "filter-ms-meeting-status" : "filter-ms-meeting-month";
		var val = $(this).val();
		var name=$('#id_schoolatlid').val();
		var checkdropdown = $('#filter-dropdown').val();	
		var inputObj = {'val':val,'name':name};
		console.log(inputObj);
		var data = {'id' :1,'mode':mode,'filterby':JSON.stringify(inputObj)};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", "#mentorreport-filter-name", function(event){
		var email=$('#id_mentoremail').val();
		var checkdropdown = $('#filter-dropdown').val();	
		if(!email)
			return false;
		var mode='filter-mentor-email';
		if(typeof(checkdropdown) === 'undefined')
			var mode='filter-mentorlist-email';
		var data = {'id' :1,'mode':mode,'filterby':email};
		$('#filter-dropdown').val($("option:first").val());
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", "#schoolreport-filter-atlid", function(event){
		var atlid=$('#id_schoolatlid').val();
		var mode=$('#filter-mode').val();
		if(!atlid)
			return false;
		var data = {'id' :1,'mode':mode,'filterby':atlid};
		$('#filter-dropdown').val($("option:first").val());
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", "#mentorreport-reset", function(event){
		var mode='filter-mentor';
		$('#id_mentoremail').val('');
		var filterby = $(this).attr('data-mode');
		var data = {'id' :1,'mode':mode,'filterby':filterby};
		$('#filter-dropdown').val($("option:first").val());
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", "#schoolreport-reset", function(event){
		var mode=$('#reset-mode').val();
		$('#id_schoolatlid').val('');
		if (typeof($('#filter-dropdown-ms-meeting')) != 'undefined' && typeof($('#filter-ms-meeting-month')) != 'undefined')
			$("#filter-dropdown-ms-meeting,#filter-ms-meeting-month").val($("option:first").val());
		var data = {'id' :1,'mode':mode,'filterby':'all'};
		$('#filter-dropdown').val($("option:first").val());
		triggerAjaxRequest(data,false);
	});
	
	//Common Pagination function
	$("body").on("click", ".movetopage", function(event){
		var filterelement_value = '';
		var id = '#forumdata';
		var searchelement =  document.getElementById('category-listing');
		if (typeof(searchelement) != 'undefined' && searchelement != null) {
			//Forum & Posts
			var category = $('#category-listing').val();
			var data = { 'id' : this.id,'mode':'movetopage','category':category};
		} else{
			//Pagination navigation logic starts..
			id = '';
			var val={notselected:'all'};
			var filterelemn =  document.getElementById('filter-dropdown');
			if(typeof(filterelemn) != 'undefined' && filterelemn != null){
				filterelement_value = $('#filter-dropdown').val();				
				val.dropdown1 = filterelement_value;
			}
			var searchelemn =  document.getElementById('searchvalue');
			if(typeof(searchelemn) != 'undefined' && searchelemn != null){
				filterelement_value = $('#searchvalue').val();
				val.searchbox1 = filterelement_value;
			}
			//var arr = {City:'Moscow', Age:25};
			val = JSON.stringify(val);
			var data = {'id':this.id,'mode':'movetopage','filters':val};
		}
		triggerAjaxRequest(data,false,'',id);
	});
	
	$("body").on("click", '.movetopage-mentor,#filter-mentor #searchby-filters', function(event) {
		var state_id = $('#id_state').val();
		var city_id = $('#id_cityid').val();
		var city = $('#id_cityid option:selected').text();
		var name =$('#id_name').val();
		if(this.id=='searchby-filters')
		{
			var id= 1;
			if(state_id=='' && city_id==0 && ($.trim(name))=='')
				return false;
		}
		else
			var id= this.id;
		if(city=='Select District')
			city=0;
		var data = { 'id' : id,'mode':'movetopage','state':state_id,'city':city,'name':name};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", ".movetopage-school,#filter-school #searchby-filters", function(event) {
		var state_id = $('#id_state').val();
		var city = $('#id_cityid').val();
		var name =$('#id_name').val();
		if(this.id=='searchby-filters')
		{
			var id= 1;
			if(state_id=='' && city==0 && ($.trim(name))=='')
				return false;
		}
		else
			var id= this.id;
		var data= { 'id' : id,'mode':'movetopage_school','state':state_id,'city':city,'name':name};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", "#studentreport-filter-email", function(event){
		var email=$('#id_studentemail').val();
		var school=$('#id_schoolfilter').val();
		if(school=='' && email=='')
			return false;
		if(email)
			var data = {'id' : 1,'mode': $('#id_studentemail').attr('data-info'),'filterby':email};
		else if(school)
			var data = {'id' : 1,'mode': $('#id_schoolfilter').attr('data-info'),'filterby':school};
		$('#filter-dropdown').val($("option:first").val());
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", "#studentreport-reset", function(event){
		$('#id_studentemail').val('');
		$('#id_schoolfilter').val('');
		var data = { 'id' : 1,'mode':$(this).attr('data-info'),'filterby':'all'};
		$('#filter-dropdown').val($("option:first").val());
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", ".movetopage-student-report", function(event){
		var mode='allstudent-list';
		var email=$('#id_studentemail').val();
		var school=$('#id_schoolfilter').val();
		if(school=='' && email=='')
			var data = { 'id' : this.id,'mode':mode,'filterby':'all'};
		if(school!='')
			var data = { 'id' : this.id,'mode':'filter-student-schooldetail','filterby':school};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", ".movetopage-student-acti-report", function(event){
		var mode='allstudent-acitivylist';
		var email=$('#id_studentemail').val();
		var school=$('#id_schoolfilter').val();
		if(school=='' && email=='')
			var data = { 'id' : this.id,'mode':mode,'filterby':'all'};
		if(school!='')
			var data = { 'id' : this.id,'mode':'filter-studentactivity-schooldetail','filterby':school};
		triggerAjaxRequest(data,false);
	});
	$("body").on("click", ".movetopage-session-report", function(event){
		var mode='all-sessionlist';
		var school=$('#id_schoolatlid').val();
		if(school=='')
			var data = { 'id' : this.id,'mode':mode,'filterby':'all'};
		if(school!='')
			var data = { 'id' : this.id,'mode':'filter-session-school-atlid','filterby':school};
		triggerAjaxRequest(data,false);
	});
	function triggerAjaxRequest(data,reload,alert_message='',id='',append=false){
		var request = $.ajax({
		  url: "ajax.php",
		  method: "POST",
		  data:  data,
		  dataType: "html",
		  beforeSend: function() {
				$(".overlay").show();
				$("#atlloaderimage").show();
			},
		});
		request.done(function( msg ) {
			if(reload)
			{
				if(msg=="success")
				{
					if(alert_message)
						alert(alert_message);
					location.reload(true);
				}
				else
				{
					alert("Failed! Try Again Later!");
				}
			}
			else
			{
				if(!id)
					reloadAjaxContent('#table-content-wrapper',msg);
				else
					reloadAjaxContent(id,msg,append);
			}
		});
		request.fail(function( jqXHR, textStatus ) {
		  alert( "Request failed: " + textStatus );
		});
	}
	function reloadAjaxContent(id,content,append=false)
	{
		if(append)
			$(id).append(content);
		else
			$(id).html(content);
		$(".overlay").hide();
		$("#atlloaderimage").hide();
	}
	
	//Common Search & filter dropdown
	$('#listsearchbtn').click(function(){
		var val = $('#searchvalue').val();		
		if(val!=""){
			var data = {'id':1,'mode':'searchfilter','filters':val};
			triggerAjaxRequest(data,false,'','');
		}
	});
	$("body").on("click", "#listresetbtn", function(event){
		var mode='resetfilters';
		var data = {'id' :1,'mode':mode,'filters':'resetfilter'};
		triggerAjaxRequest(data,false);
		resetfilterdropdowns();
	});
	
	//Tickets: Add new Reply to a ticket
	$('.ticketreply').click(function(){
		var replydata = $('#ticket-reply').val();
		var ticketid =  $(this).attr('data-id');
		$('#ticket-reply').val('');
		var data = {'id' :ticketid,'mode':'newreply','reply':replydata};
		triggerAjaxRequest(data,false,'','#ticketreplies',true);
		if(data !="" && replydata !=""){
			//var reply="<div><div><div>"+replydata+"</div><div><div>by You 1 min ago</div></div></div></div>";
			//console.log(replydata);
			//$("#ticketreplies").append(replydata);
			$("#ticketreplies").show();
		}
	});

	//Tickets: Change Ticket status
	$('#changestatus').change(function(){
		var val = $(this).val();
		var ticketid =  $(this).attr('data-id');
		var tid =  ticketid;
		var path =  $(this).attr('data-url');
		var othermsg = "";
		if(val==""){
			return false;
		}
		if(val=="delete"){
			//Delete Ticket
			var delmsgprompt = prompt("Enter the Reason for Delete, without it ticket will not delete", "");
			if (delmsgprompt == null || delmsgprompt == "") {
				var r = false;
				$(this).prop('selectedIndex',0);
				return false;
			} else {
				othermsg = delmsgprompt;
				var r = true;
			}
		} else{
			if(val!="0"){
				var r = confirm("Sure Want to Change Status !");
			}
		}
		if (r == true) {
			ticketid = Number(ticketid) + Number(3567);
			ticketid = "A"+ticketid+"A"+ticketid+78665;
			//Ajax call;
			var request = $.ajax({
			url: "ajax.php",
			method: "POST",
			data: {'id':ticketid,'status':val,'mode':'status','msg':othermsg},
			dataType: "html",
			beforeSend: function() {
				$(".overlay").show();
				$("#atlloaderimage").show();
				},
			});
			request.done(function(msg) {
				if(msg=="success"){
					if(val=="delete"){
						alert("Sucessfully Deleted");
						path = path + 'ticket';
						window.location.href = path;
					} else{
						//Refresh the page once Ticket Status changes.
						path = path + 'ticket/detail.php?id=' + tid;
						window.location.href = path;
					}
				} else{
					alert("Error occurs!");
				}
			});
			request.fail(function( jqXHR, textStatus,errorMessage ){
				alert( "Request failed: " + textStatus + errorMessage );
			});
		}
	});
	
	//Tickets: Show msg under Ticket Category in create ticket page
	$('#id_category').change(function(){
		$('#id_error_category').html('');
		$('#id_error_category').hide();
		var val = $(this).val();
		if(val==10){
			var msg = "No technical ATL InnoNet queries will be entertained under <b>Others</b> heading";
			$('#id_error_category').html(msg);
			$('#id_error_category').show();
		}
	});
	$('#change-category').change(function(){
		var val = $(this).val();
		var ticketid =  $(this).attr('data-id');
		var tid =  ticketid;
		//alert(ticketid);	
		var r = confirm("Are you sure you want to Redirect the Ticket?");
		if(r)
		{
		var data = { 'id' : ticketid,'mode':'changecategory','category':val};
		triggerAjaxRequest(data,true,'Updated successfully');
		}
		else
			return false;
	});
	
	//Common: Reset Filter dropdown
	function resetfilterdropdowns(){
		var searchelemn =  document.getElementById('filter-dropdowntwo');
		if(typeof(searchelemn) != 'undefined' && searchelemn != null){
			$('#filter-dropdowntwo').prop('selectedIndex',0);
		}
		var searchelemn =  document.getElementById('filter-dropdown');
		if(typeof(searchelemn) != 'undefined' && searchelemn != null){
			$('#filter-dropdown').prop('selectedIndex',0);
		}
		//Search Box, make it empty
		var searchelemn =  document.getElementById('searchvalue');
		if(typeof(searchelemn) != 'undefined' && searchelemn != null){
			$('#searchvalue').val('');
		}
	}
	
	//Mentor- Report your session:
	$('#id_sessiontype').change(function(){
		var val = $(this).val();
		if(val=='c'){
			$('#functiondetaildiv').show();
		} else{
			$('#functiondetaildiv').hide();
		}		
	});	
	//Mentor- Report your session: assign school function value
	$('#id_functiondetails').focusout(function(){
		$('#schoolfunction').val($(this).val());
	});
	//Mentor- Report your session:
	function setsessiontimeinform(){
		//arrange start & end time
		var starttime = $('#id_starttime1').val()+'-'+$('#id_starttime2').val()+'-'+$('#id_starttime3').val();
		var endtime = $('#id_endtime1').val()+'-'+$('#id_endtime2').val()+'-'+$('#id_endtime3').val();	
		$('#starttime').val(starttime);
		$('#endtime').val(endtime);
		$("#atlbox2").show();
		$("#atlloader").show();
	}

});
