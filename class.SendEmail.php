<?php

include_once('class.DB_Table.php');
require_once("external_libs/mail/email_message.php");
require_once("external_libs/mail/smtp_message.php");
require_once("external_libs/mail/smtp.php");
/* Uncomment when using SASL authentication mechanisms */
require("external_libs/mail/sasl.php");


class SendEmail extends DB_Table{

	function SendEmail(){
	
		
	}
	
	function smtp($type, $data){
		
		
		$this->message_object=new smtp_message_class;
		$this->message_object->localhost="localhost";   /* This computer address */
		$this->message_object->smtp_host="localhost";   /* SMTP server address */
		$this->message_object->smtp_direct_delivery=0;  /* Deliver directly to the recipients destination SMTP server */
		$this->message_object->smtp_exclude_address=""; /* In directly deliver mode, the DNS may return the IP of a sub-domain of the default domain for domains that do not exist. If that is your case, set this variable with that sub-domain address. */
		/*
		 * If you use the direct delivery mode and the GetMXRR is not functional, you need to use a replacement function.
		 */
		/*
		$_NAMESERVERS=array();
		include("rrcompat.php");
		$this->message_object->smtp_getmxrr="_getmxrr";
		*/
		$this->message_object->smtp_user="";            /* authentication user name */
		$this->message_object->smtp_realm="";           /* authentication realm or Windows domain when using NTLM authentication */
		$this->message_object->smtp_workstation="";     /* authentication workstation name when using NTLM authentication */
		$this->message_object->smtp_password="";        /* authentication password */
		$this->message_object->smtp_pop3_auth_host="";  /* if you need POP3 authetntication before SMTP delivery, specify the host name here. The smtp_user and smtp_password above should set to the POP3 user and password */
		$this->message_object->smtp_debug=0;            /* Output dialog with SMTP server */
		$this->message_object->smtp_html_debug=1;       /* If smtp_debug is 1, set this to 1 to output debug information in HTML */
		
		
		$this->to='';
		$this->subject='';
		$this->message='';
		$this->additional_headers='';
		$this->additional_parameters='';
		
		
		
		
		switch($type){
			case 'plain':
			$sql=sprintf("select * from `Email Credentials Dimension` where `Email Credentials Key`=%d", $data['email_credentials_key']);
			$result=mysql_query($sql);
			if($row=mysql_fetch_array($result)){
				$this->from=$row['Email Address'];
				$this->to=$data['to'];
				$this->subject=$data['subject'];
				$this->message=$data['body'];
				$this->additional_headers=sprintf("From: %s\nBcc: %s",$row['Email Address'],$data['bcc']);
				$this->additional_headers=sprintf("From: %s",$row['Email Address']);
				
				
				//$additional_headers=$row['Email Address'];
				$this->additional_parameters="-f ".$row['Email Address'];
				
				$this->message_object->smtp_debug=0;           
				$this->message_object->smtp_html_debug=1; 
				$this->message_object->localhost="localhost";   
				$this->message_object->smtp_host=$row['Outgoing Mail Sever'];  
				$this->message_object->smtp_direct_delivery=0; 
				$this->message_object->smtp_exclude_address=""; 
				$this->message_object->smtp_user=$row['Login'];  
				$this->message_object->smtp_realm="";       
				$this->message_object->smtp_workstation="";    
				$this->message_object->smtp_password=$row['Password'];  
				$this->message_object->smtp_pop3_auth_host="";  
				$this->message_object->smtp_port=465;
				$this->message_object->smtp_ssl=1;
		//new		
				$subject='subject';
				$to_name='to name';
				$from_name='from name';

				$this->html_message="<html>
				<head>
				<title>$subject</title>
				<style type=\"text/css\"><!--
				body { color: black ; font-family: arial, helvetica, sans-serif ; background-color: #A3C5CC }
				A:link, A:visited, A:active { text-decoration: underline }
				--></style>
				</head>
				<body>
				<table width=\"100%\">
				<tr>
				<td>
				<center><h1>$subject</h1></center>
				<hr>
				<P>Hello ".strtok($to_name," ").",<br><br>
				This message is just to let you know that the <a href=\"http://www.phpclasses.org/mimemessage\">MIME E-mail message composing and sending PHP class</a> is working as expected.<br><br>
				Thank you,<br>
				$from_name</p>
				</td>
				</tr>
				</table>
				</body>
				</html>";
				$this->message_object->CreateQuotedPrintableHTMLPart($this->html_message,"",$html_part);
				
					$text_message="This is an HTML message. Please use an HTML capable mail program to read this message.";
					$this->message_object->CreateQuotedPrintableTextPart($this->message_object->WrapText($text_message),"",$text_part);
					
					
										$alternative_parts=array(
						$text_part,
						$html_part
					);
					$this->message_object->AddAlternativeMultipart($alternative_parts);
			//new	
				return true;
			}
			else{
				return false;
			}
			break;
		}
	
	}

	
	function smtp_mail($to,$subject,$message,$additional_headers="",$additional_parameters="")
	{

		return($this->message_object->Mail($to,$subject,$message,$additional_headers,$additional_parameters));
	}
	
	function send(){
		if($this->smtp_mail($this->to,$this->subject,$this->html_message,$this->additional_headers,$this->additional_parameters))
			$response=array('state'=>200,'msg'=>'ok');
		else
			$response=array('state'=>400,'msg'=>$this->message_object->error);
			//$response=array('state'=>400,'msg'=>'error--');
		return $response;
	}
		
	function retry(){
		$success=0;
		$fail=0;
		$result='';
		$sql=sprintf("select * from `Email Queue Dimension` where `Status`='No' and `Type`='Plain'");
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result)){
			$data=array(
				'subject'=>	$row['Subject'],
				'body'=>$row['Plain'],
				'email_credentials_key'=>$row['Email Credentials Key'],
				'to'=>$row['To'],
				'bcc'=>$row['BCC']
			);
			$this->smtp('plain', $data);
			$result=$this->send();
			
			if($result['msg']=='ok'){
				$sql=sprintf("update `Email Queue Dimension` set `Status`='Yes' where `Key`=%d", $row['Key']);
				if(mysql_query($sql))
					$success++;
			}
			else
				$fail++;
				
		}
		$response=sprintf("%d emails sent. %d has failed", $success, $fail);
		return $response;
	}


  
}

?>