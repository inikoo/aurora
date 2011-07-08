<?php

//Ensures no one loads page and does simple spam check.
if(isset($_POST['name']) && empty($_POST['spam_check']))
{
	//Include our email validator for later use 
	require 'email-validator.php';
	$validator = new EmailAddressValidator();
	
	//Declare our $errors variable we will be using later to store any errors.
	$errors = array();
	
	//Setup our basic variables
	$input_name = strip_tags($_POST['name']);
	$input_email = strip_tags($_POST['email']);
	$input_message = strip_tags($_POST['message']);
	
	//We'll check and see if any of the required fields are empty.
	//We use an array to store the required fields.
	$required = array('Name field' => 'name', 'Email field' => 'email', 'Message field' => 'message');
	
	//Loops through each required $_POST value 
	//Checks to ensure it is not empty.
	foreach($required as $key=>$value)
	{
		if(isset($_POST[$value]) && $_POST[$value] !== '') 
		{
			continue;
		}
		else {
			$errors[] = $key . ' cannot be left blank.';
		}
	}
	
	//Make sure the email is valid. 
    if (!$validator->check_email_address($input_email)) {
           $errors[] = 'Email address is invalid.';
    }
	
	//Now check to see if there are any errors 
	if(empty($errors))
	{
		
		//No errors, send mail using conditional to ensure it was sent.
		if(mail('themedemo@pixnami.com', "Message from $input_name", $input_message, "From: $input_email"))
		{
			echo 'Your email has been sent.';
		}
		else 
		{
			echo 'There was a problem sending your email.';
		}
		
	}
	else 
	{
		
		//Errors were found, output all errors to the user.
		echo implode('<br />', $errors);
		
	}
}
else
{
	die('Direct access to this page is not allowed.');
}
