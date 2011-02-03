
function edit(key,dir,dirname)
{
	//var key;
	
	
document.getElementById(dir).innerHTML = "<img src='art/icons/folder_add.png' / ><span><input type='textbox' id='txtFolder' name='txtFolder' value=\'"+dirname+"\' style='width:85px' onBlur=\"textChange(\'"+key+"\',\'"+dirname+"\');\"></span>";
	//document.getElementById('txtFolder').value = ;
	
}
function textChange(p,r)
{	
	var p;
	var r;
	r = document.getElementById('txtFolder').value; 

	location.href='marketing_campaign.php?t='+p+'&n='+r;

}
function del(del_key)
{
	
	var del_key;
	location.href='marketing_campaign.php?del='+del_key;
}

function delFolder()
{
	var selObj;

	selObj = document.getElementById('select_folder').options[document.getElementById('select_folder').selectedIndex].value;
	var answer = confirm('Are you sure to delete this data ?');

	if (answer){
		alert("Succefully Deleted")
		location.href='marketing_campaign.php?did='+selObj;
	}
	else{
		alert("Thanks for sticking around!")
	}

}


