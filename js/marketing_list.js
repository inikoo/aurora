function validate_form()
{
	var check=validate();
	if(check==false)
	{
		 window.scrollTo(0,0);
		document.getElementById('check_div').style.display = 'block';
		//alert('check your entry');
				
	}
	else
	{
		check=true;	
	}
	//alert(check);
	return check;
}
function validate()
{
	hide();
	if(document.getElementById("list_name").value=="")
	{
		document.getElementById('list_msg').style.display = 'block';
		return false;
	}

	if(document.getElementById("default_name").value=="")
	{
		document.getElementById('default_name_msg').style.display = 'block';
		return false;
	}


	var emailID=document.getElementById("default_email").value;
	
	if (emailID=='')
	{
		document.getElementById("email_msg").innerHTML = "";
		document.getElementById("email_msg").innerHTML = "Please enter a valid email ID";
		document.getElementById('email_msg').style.display = 'block';
		//alert("Please Enter your Email ID");
		document.getElementById("default_email").focus();
		
		return false;
	}
	if (echeck(emailID)==false)
	{
		emailID.value="";
		document.getElementById("default_email").focus();
		
		document.getElementById("email_msg").innerHTML = "";
		document.getElementById("email_msg").innerHTML = "Please enter a valid email ID";
		document.getElementById('email_msg').style.display = 'block';

		return false;
	}

	if(document.getElementById("default_subject").value=="")
	{
		//alert("default subject");
		document.getElementById("subject_msg").innerHTML = "";
		document.getElementById("subject_msg").innerHTML = "Please enter a value";
		document.getElementById('subject_msg').style.display = 'block';
  		return false;
		
	}

	if(document.getElementById("description").value=="")
	{
		//alert("description");
		document.getElementById("remind_msg").innerHTML = "";
		document.getElementById("remind_msg").innerHTML = "Please enter a value";
		document.getElementById('remind_msg').style.display = 'block';
  		return false;
		
	}


	//return true
}

function validate_form2()
{
	var check=validate2();
	if(check==false)
	{
		 window.scrollTo(0,0);
		document.getElementById('check_div').style.display = 'block';
		//alert('check your entry');
				
	}
	else
	{
		check=true;	
	}
	//alert(check);
	return check;
}
function validate2()
{
	hide();
	if(document.getElementById("group_title").value=="")
	{
		return false;
	}

	if(document.getElementById("group_name0").value=="")
	{
		
		return false;
	}


	
}

function echeck(str) 
{
	
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		  // alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		  // alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		   // alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		   // alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    //alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    //alert("Invalid E-mail ID")
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		   // alert("Invalid E-mail ID")
		    return false
		 }

 		 return true					
}




 function hide() 
{
	document.getElementById('list_msg').style.display = 'none';
	document.getElementById('default_name_msg').style.display = 'none';
	document.getElementById('email_msg').style.display = 'none';
	document.getElementById('subject_msg').style.display = 'none';
	document.getElementById('remind_msg').style.display = 'none';	
	document.getElementById('edit_contact_div').style.display = 'none';
	document.getElementById('edit_email_div').style.display = 'none';
	document.getElementById('check_div').style.display = 'none';
	
}

function show(div_id) 
{
	if(div_id=='list_msg')
	{
		document.getElementById('list_msg').style.display = 'block';
		document.getElementById('default_name_msg').style.display = 'none';
		document.getElementById('email_msg').style.display = 'none';
		document.getElementById('subject_msg').style.display = 'none';
		document.getElementById('remind_msg').style.display = 'none';
	}
	if(div_id=='default_name_msg')
	{
		document.getElementById('default_name_msg').style.display = 'block';
		document.getElementById('list_msg').style.display = 'none';
		document.getElementById('email_msg').style.display = 'none';
		document.getElementById('subject_msg').style.display = 'none';
		document.getElementById('remind_msg').style.display = 'none';
	}
	if(div_id=='email_msg')
	{
		document.getElementById('default_name_msg').style.display = 'none';
		document.getElementById('list_msg').style.display = 'none';
		document.getElementById('email_msg').style.display = 'block';
		document.getElementById('subject_msg').style.display = 'none';	
		document.getElementById('remind_msg').style.display = 'none';
	}
	if(div_id=='subject_msg')
	{
		document.getElementById('default_name_msg').style.display = 'none';
		document.getElementById('list_msg').style.display = 'none';
		document.getElementById('email_msg').style.display = 'none';
		document.getElementById('subject_msg').style.display = 'block';	
		document.getElementById('remind_msg').style.display = 'none';
	}
	if(div_id=='remind_msg')
	{
		document.getElementById('default_name_msg').style.display = 'none';
		document.getElementById('list_msg').style.display = 'none';
		document.getElementById('email_msg').style.display = 'none';
		document.getElementById('subject_msg').style.display = 'none';
		document.getElementById('remind_msg').style.display = 'block';	
	}
	
	
}

function edit_contact()
{
	//alert("hello");
	document.getElementById('contact_div').style.display = 'none';
	document.getElementById('edit_contact_div').style.display = 'block';	
}


function edit_email()
{
	document.getElementById('email_me_div').style.display = 'none';
	document.getElementById('edit_email_div').style.display = 'block';	
	
}	


function empty_text()
{
	document.getElementById('list_search').value='';
}


function CreateTextbox()
{
	var i=1;
	add_group.innerHTML = add_group.innerHTML +"<br> <input type=text name='group_text<script>+i</script>' class='av_text' style='width:650px;'/> ";
	add_group.innerHTML = add_group.innerHTML +"<div id='group_msg' class='invalid-error'>Example: 'Interested in ... or Food Preferences'.</div>"; 
}
 

function show_list()
{
	document.getElementById('list_or_group').style.display = 'none';
	document.getElementById('group_div').style.display = 'none';
	document.getElementById('new_list').style.display = 'block';
	document.getElementById('list_details_div').style.display = 'none';
}
function show_group()
{
	document.getElementById('list_or_group').style.display = 'none';
	document.getElementById('group_div').style.display = 'block';
	document.getElementById('new_list').style.display = 'none';
}
function create_group()
{       document.getElementById('new_list').style.display = 'none';
	document.getElementById('list_or_group').style.display = 'none';
	document.getElementById('group_div').style.display = 'block';
        document.getElementById('list_details_div').style.display = 'none';
	
	
}

function add_group_div(){
	
	
	if(document.getElementById('d3').style.display=='block')
	{
		document.getElementById('d4').style.display='block';
		document.getElementById('add_group_link').innerHTML='';
		exit;

	}

	if(document.getElementById('d2').style.display=='block')
	{
		document.getElementById('d3').style.display='block';
		exit;

	}

	if(document.getElementById('d1').style.display=='block')
	{
		document.getElementById('d2').style.display='block';
		exit;

	}

	if(document.getElementById('d0').style.display=='block')
	{
		document.getElementById('d1').style.display='block';
		exit;

	}

}
