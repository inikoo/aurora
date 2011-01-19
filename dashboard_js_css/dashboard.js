// JavaScript Document

function customise()
{
		alert("customise");
	if(document.getElementById(crm_chk).checked==true)
		document.getElementById(dashboard_right_now).visibility="none";
	if(document.getElementById(crm_chk).checked==false)
		document.getElementById(dashboard_right_now).visibility="block";
}