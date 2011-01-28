


function validate_form()
{
	var check=validate();
	if(check==false)
	{
	
		alert('check your entry');
		
	}
}
function validate()
{
	if(document.getElementById("list_name").value=="")
	{
  		//alert("First name must be filled out");
		return false;
	}

	if(document.getElementById("default_name").value=="")
	{
		//alert("Second name must be filled out");
		return false;
	}

	var emailID=document.getElementById("default_email").value;
	if ((emailID.value==null)||(emailID.value==""))
	{
		alert("Please Enter your Email ID")
		emailID.focus()
		return false
	}
	if (echeck(emailID.value)==false)
	{
		emailID.value=""
		emailID.focus()
		return false
	}


	//return true
}

function echeck(str) 
{

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
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


 
$('#create_new_list').click(function() {
     $('#list_or_group').slideDown('slow', function() {
    // Animation complete.
  });
});
 
