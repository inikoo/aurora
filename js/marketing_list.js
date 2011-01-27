


function validate_form()
{
	

	if(document.getElementById("list_name").value=="")
  		alert("First name must be filled out");
	else if(document.getElementById("default_name").value=="")
		alert("Second name must be filled out");
  
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
 
