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
					
					$this->from_name=getenv("USERNAME");
					$this->from_address=$row['Email Address'];                                              $this->sender_line=__LINE__;

					$this->reply_name=$this->from_name;
					$this->reply_address=$this->from_address;
					$this->reply_address=$this->from_address;
					$this->error_delivery_name=$this->from_name;
					$this->error_delivery_address=$this->from_address;
					$this->to_name=$data['to'];
					$this->to_address=$data['to'];                                                $this->recipient_line=__LINE__;
					$this->subject=$data['subject'];
					$this->message=$data['plain'];

					if(strlen($this->from_address)==0)
						die("Please set the messages sender address in line ".$this->sender_line." of the script ".basename(__FILE__)."\n");
					if(strlen($this->to_address)==0)
						die("Please set the messages recipient address in line ".$this->recipient_line." of the script ".basename(__FILE__)."\n");

					//$this->message_object=new smtp_message_class;

					/* This computer address */
					$this->message_object->localhost="localhost";

					/* SMTP server address, probably your ISP address,
					 * or smtp.gmail.com for Gmail
					 * or smtp.live.com for Hotmail */
					$this->message_object->smtp_host=$row['Outgoing Mail Sever'];  

					/* SMTP server port, usually 25 but can be 465 for Gmail */
					$this->message_object->smtp_port=465;

					/* Use SSL to connect to the SMTP server. Gmail requires SSL */
					$this->message_object->smtp_ssl=1;

					/* Use TLS after connecting to the SMTP server. Hotmail requires TLS */
					$this->message_object->smtp_start_tls=0;

					/* Change this variable if you need to connect to SMTP server via an HTTP proxy */
					$this->message_object->smtp_http_proxy_host_name='';
					/* Change this variable if you need to connect to SMTP server via an HTTP proxy */
					$this->message_object->smtp_http_proxy_host_port=3128;

					/* Change this variable if you need to connect to SMTP server via an SOCKS server */
					$this->message_object->smtp_socks_host_name = '';
					/* Change this variable if you need to connect to SMTP server via an SOCKS server */
					$this->message_object->smtp_socks_host_port = 1080;
					/* Change this variable if you need to connect to SMTP server via an SOCKS server */
					$this->message_object->smtp_socks_version = '5';


					/* Deliver directly to the recipients destination SMTP server */
					$this->message_object->smtp_direct_delivery=0;

					/* In directly deliver mode, the DNS may return the IP of a sub-domain of
					 * the default domain for domains that do not exist. If that is your
					 * case, set this variable with that sub-domain address. */
					$this->message_object->smtp_exclude_address="";

					/* If you use the direct delivery mode and the GetMXRR is not functional,
					 * you need to use a replacement function. */
					/*
					$_NAMESERVERS=array();
					include("rrcompat.php");
					$message_object->smtp_getmxrr="_getmxrr";
					*/

					/* authentication user name */
					$this->message_object->smtp_user=$row['Login'];  

					/* authentication password */
					$this->message_object->smtp_password=$row['Password'];  

					/* if you need POP3 authetntication before SMTP delivery,
					 * specify the host name here. The smtp_user and smtp_password above
					 * should set to the POP3 user and password*/
					$this->message_object->smtp_pop3_auth_host="";

					/* authentication realm or Windows domain when using NTLM authentication */
					$this->message_object->smtp_realm="";

					/* authentication workstation name when using NTLM authentication */
					$this->message_object->smtp_workstation="";

					/* force the use of a specific authentication mechanism */
					$this->message_object->smtp_authentication_mechanism="";

					/* Output dialog with SMTP server */
					$this->message_object->smtp_debug=0;

					/* if smtp_debug is 1,
					 * set this to 1 to make the debug output appear in HTML */
					$this->message_object->smtp_html_debug=1;

					/* If you use the SetBulkMail function to send messages to many users,
					 * change this value if your SMTP server does not accept sending
					 * so many messages within the same SMTP connection */
					$this->message_object->maximum_bulk_deliveries=100;

					$this->message_object->SetEncodedEmailHeader("To",$this->to_address,$this->to_name);
					$this->message_object->SetEncodedEmailHeader("From",$this->from_address,$this->from_name);
					$this->message_object->SetEncodedEmailHeader("Reply-To",$this->reply_address,$this->reply_name);
					$this->message_object->SetHeader("Return-Path",$this->error_delivery_address);
					$this->message_object->SetEncodedEmailHeader("Errors-To",$this->error_delivery_address,$this->error_delivery_name);
					
					
				/*	
					$message_object->SetEncodedHeader("Subject",$subject);
					$message_object->AddQuotedPrintableTextPart($message_object->WrapText($message));
					
				*/

				/*
				 *  Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
				 *  If you are using Windows, you need to use the smtp_message_class to set the return-path address.
				 */
					if(defined("PHP_OS")
					&& strcmp(substr(PHP_OS,0,3),"WIN"))
						$this->message_object->SetHeader("Return-Path",$this->error_delivery_address);

					$this->message_object->SetEncodedHeader("Subject",$this->subject);


				/*
				 *  It is strongly recommended that when you send HTML messages,
				 *  also provide an alternative text version of HTML page,
				 *  even if it is just to say that the message is in HTML,
				 *  because more and more people tend to delete HTML only
				 *  messages assuming that HTML messages are spam.
				 */
					$this->text_message=$this->message;
					$this->message_object->CreateQuotedPrintableTextPart($this->message_object->WrapText($this->text_message),"",$this->text_part);

				/*
				 *  Multiple alternative parts are gathered in multipart/alternative parts.
				 *  It is important that the fanciest part, in this case the HTML part,
				 *  is specified as the last part because that is the way that HTML capable
				 *  mail programs will show that part and not the text version part.
				 */
					$this->alternative_parts=array(
						$this->text_part
					);
					$this->message_object->AddAlternativeMultipart($this->alternative_parts);
					
					
					//Attachements
					$text_attachment=array();
					$image_attachment=array();
					foreach($data['attachement'] as $value){
						if($value['attachement_type']=='Text'){
							$text_attachment[]=array('Data'=>$value['Data']
												   ,'Name'=>$value['Name']
												   ,'Content-Type'=>$value['Content-Type']
												   ,'Disposition'=>$value['Disposition']
												   );
						}
						
						else if($value['attachement_type']=='Image'){
							$image_attachment[]=array('FileName'=>$value['FileName']
												   ,'Content-Type'=>$value['Content-Type']
												   ,'Disposition'=>$value['Disposition']
												   );
						}
						
					}
					

					foreach($text_attachment as $single_text)
						$this->message_object->AddFilePart($single_text);

					foreach($image_attachment as $single_image)
						$this->message_object->AddFilePart($single_image);					
					

					
					
					return true;
				}
				else
					return false;
			
			
			case 'html':
			/*
				$data=array(
					'subject'=>	'',
					'plain'=>'',
					'html'=>'',
					'email_credentials_key'=>'',
					'to'=>'',
					'bcc'=>''
				);
			*/
				$sql=sprintf("select * from `Email Credentials Dimension` where `Email Credentials Key`=%d", $data['email_credentials_key']);
				$result=mysql_query($sql);
				if($row=mysql_fetch_array($result)){
					
					$this->from_name=getenv("USERNAME");
					$this->from_address=$row['Email Address'];                                              $this->sender_line=__LINE__;

					$this->reply_name=$this->from_name;
					$this->reply_address=$this->from_address;
					$this->reply_address=$this->from_address;
					$this->error_delivery_name=$this->from_name;
					$this->error_delivery_address=$this->from_address;
					$this->to_name=$data['to'];
					$this->to_address=$data['to'];                                                $this->recipient_line=__LINE__;
					$this->subject=$data['subject'];
					$this->message=$data['plain'];

					if(strlen($this->from_address)==0)
						die("Please set the messages sender address in line ".$this->sender_line." of the script ".basename(__FILE__)."\n");
					if(strlen($this->to_address)==0)
						die("Please set the messages recipient address in line ".$this->recipient_line." of the script ".basename(__FILE__)."\n");

					//$this->message_object=new smtp_message_class;

					/* This computer address */
					$this->message_object->localhost="localhost";

					/* SMTP server address, probably your ISP address,
					 * or smtp.gmail.com for Gmail
					 * or smtp.live.com for Hotmail */
					$this->message_object->smtp_host=$row['Outgoing Mail Sever'];  

					/* SMTP server port, usually 25 but can be 465 for Gmail */
					$this->message_object->smtp_port=465;

					/* Use SSL to connect to the SMTP server. Gmail requires SSL */
					$this->message_object->smtp_ssl=1;

					/* Use TLS after connecting to the SMTP server. Hotmail requires TLS */
					$this->message_object->smtp_start_tls=0;

					/* Change this variable if you need to connect to SMTP server via an HTTP proxy */
					$this->message_object->smtp_http_proxy_host_name='';
					/* Change this variable if you need to connect to SMTP server via an HTTP proxy */
					$this->message_object->smtp_http_proxy_host_port=3128;

					/* Change this variable if you need to connect to SMTP server via an SOCKS server */
					$this->message_object->smtp_socks_host_name = '';
					/* Change this variable if you need to connect to SMTP server via an SOCKS server */
					$this->message_object->smtp_socks_host_port = 1080;
					/* Change this variable if you need to connect to SMTP server via an SOCKS server */
					$this->message_object->smtp_socks_version = '5';


					/* Deliver directly to the recipients destination SMTP server */
					$this->message_object->smtp_direct_delivery=0;

					/* In directly deliver mode, the DNS may return the IP of a sub-domain of
					 * the default domain for domains that do not exist. If that is your
					 * case, set this variable with that sub-domain address. */
					$this->message_object->smtp_exclude_address="";

					/* If you use the direct delivery mode and the GetMXRR is not functional,
					 * you need to use a replacement function. */
					/*
					$_NAMESERVERS=array();
					include("rrcompat.php");
					$message_object->smtp_getmxrr="_getmxrr";
					*/

					/* authentication user name */
					$this->message_object->smtp_user=$row['Login'];  

					/* authentication password */
					$this->message_object->smtp_password=$row['Password'];  

					/* if you need POP3 authetntication before SMTP delivery,
					 * specify the host name here. The smtp_user and smtp_password above
					 * should set to the POP3 user and password*/
					$this->message_object->smtp_pop3_auth_host="";

					/* authentication realm or Windows domain when using NTLM authentication */
					$this->message_object->smtp_realm="";

					/* authentication workstation name when using NTLM authentication */
					$this->message_object->smtp_workstation="";

					/* force the use of a specific authentication mechanism */
					$this->message_object->smtp_authentication_mechanism="";

					/* Output dialog with SMTP server */
					$this->message_object->smtp_debug=0;

					/* if smtp_debug is 1,
					 * set this to 1 to make the debug output appear in HTML */
					$this->message_object->smtp_html_debug=1;

					/* If you use the SetBulkMail function to send messages to many users,
					 * change this value if your SMTP server does not accept sending
					 * so many messages within the same SMTP connection */
					$this->message_object->maximum_bulk_deliveries=100;

					$this->message_object->SetEncodedEmailHeader("To",$this->to_address,$this->to_name);
					$this->message_object->SetEncodedEmailHeader("From",$this->from_address,$this->from_name);
					$this->message_object->SetEncodedEmailHeader("Reply-To",$this->reply_address,$this->reply_name);
					$this->message_object->SetHeader("Return-Path",$this->error_delivery_address);
					$this->message_object->SetEncodedEmailHeader("Errors-To",$this->error_delivery_address,$this->error_delivery_name);
					
					
				/*	
					$message_object->SetEncodedHeader("Subject",$subject);
					$message_object->AddQuotedPrintableTextPart($message_object->WrapText($message));
					
				*/

				/*
				 *  Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
				 *  If you are using Windows, you need to use the smtp_message_class to set the return-path address.
				 */
					if(defined("PHP_OS")
					&& strcmp(substr(PHP_OS,0,3),"WIN"))
						$this->message_object->SetHeader("Return-Path",$this->error_delivery_address);

					$this->message_object->SetEncodedHeader("Subject",$this->subject);

					if(isset($data['html']) and $data['html'])
						$html_msg=$data['html'];
					else
						$html_msg='';
						
					$this->html_message=$html_msg;
					$this->message_object->CreateQuotedPrintableHTMLPart($this->html_message,"",$this->html_part);

				/*
				 *  It is strongly recommended that when you send HTML messages,
				 *  also provide an alternative text version of HTML page,
				 *  even if it is just to say that the message is in HTML,
				 *  because more and more people tend to delete HTML only
				 *  messages assuming that HTML messages are spam.
				 */
					$this->text_message=$this->message;
					$this->message_object->CreateQuotedPrintableTextPart($this->message_object->WrapText($this->text_message),"",$this->text_part);

				/*
				 *  Multiple alternative parts are gathered in multipart/alternative parts.
				 *  It is important that the fanciest part, in this case the HTML part,
				 *  is specified as the last part because that is the way that HTML capable
				 *  mail programs will show that part and not the text version part.
				 */
					$this->alternative_parts=array(
						$this->text_part,
						$this->html_part
					);
					$this->message_object->AddAlternativeMultipart($this->alternative_parts);
					
					
					//Attachements
					$text_attachment=array();
					$image_attachment=array();
					foreach($data['attachement'] as $value){
						if($value['attachement_type']=='Text'){
							$text_attachment[]=array('Data'=>$value['Data']
												   ,'Name'=>$value['Name']
												   ,'Content-Type'=>$value['Content-Type']
												   ,'Disposition'=>$value['Disposition']
												   );
						}
						
						else if($value['attachement_type']=='Image'){
							$image_attachment[]=array('FileName'=>$value['FileName']
												   ,'Content-Type'=>$value['Content-Type']
												   ,'Disposition'=>$value['Disposition']
												   );
						}
						
					}
					

					foreach($text_attachment as $single_text)
						$this->message_object->AddFilePart($single_text);

					foreach($image_attachment as $single_image)
						$this->message_object->AddFilePart($single_image);		
						
					return true;
				}
				else
					return false;
			
			break;
		}
	
	}

	
	function smtp_mail($to,$subject,$message,$additional_headers="",$additional_parameters=""){

		return($this->message_object->Mail($to,$subject,$message,$additional_headers,$additional_parameters));
		//return($this->message_object->Send());//($to,$subject,$message,$additional_headers,$additional_parameters));
	}
	
	function send(){
	
		$error=$this->message_object->Send();
		for($recipient=0,Reset($this->message_object->invalid_recipients);$recipient<count($this->message_object->invalid_recipients);Next($this->message_object->invalid_recipients),$recipient++)
			$response= "Invalid recipient: ".Key($this->message_object->invalid_recipients)." Error: ".$this->message_object->invalid_recipients[Key($this->message_object->invalid_recipients)]."\n";
		if(strcmp($error,""))
			$response=  array('state'=>400,'msg'=>$error);
		else
			$response=  array('state'=>200,'msg'=>'ok');

		echo $response;
		return $response;
	}
	
	
	function retry($type){
		$success=0;
		$fail=0;
		$result='';
		
		$files=array();
		switch($type){
		case 'plain':
			$sql=sprintf("select * from `Email Queue Dimension` where `Status`='No' and `Type`='Plain'");


			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
				$sql=sprintf("select * from `Email Queue Attachement Dimension` where `Email Queue Key` = %d", $row['Email Queue Key']);
				$res=mysql_query($sql);
				while($r=mysql_fetch_array($res)){
					$files[]=array('Data'=>$r['Data'],
									'Name'=>$r['Name'],
									'Content-Type'=>$r['Content-Type'],
									'Disposition'=>$r['Disposition'],
									'FileName'=>$r['FileName'],
									'attachement_type'=>$r['Type']
								);
				}
				
				
				$data=array(
					'subject'=>	$row['Subject'],
					'plain'=>$row['Plain'],
					'email_credentials_key'=>$row['Email Credentials Key'],
					'to'=>$row['To'],
					'bcc'=>$row['BCC'],
					'attachement'=>$files
				);
				
				$this->smtp('plain', $data);
				$res=$this->send();
				
				if($res['msg']=='ok'){
					$sql=sprintf("update `Email Queue Dimension` set `Status`='Yes' where `Email Queue Key`=%d", $row['Email Queue Key']);
					if(mysql_query($sql))
						$success++;
				}
				else
					$fail++;
					
			}
			
			$response=sprintf("%d emails sent. %d has failed", $success, $fail);
			break;
		
		case 'html':
			$sql=sprintf("select * from `Email Queue Dimension` where `Status`='No' and `Type`='HTML'");
			//$sql=sprintf("select * from `Email Queue Dimension` ");
			//print $sql;
		//	print $sql;
			$result=mysql_query($sql);
			//print "$result";
			while($row=mysql_fetch_assoc($result)){
				//print mysql_error();
				
				$sql=sprintf("select * from `Email Queue Attachement Dimension` where `Email Queue Key` = %d", $row['Email Queue Key']);
				$res=mysql_query($sql);
				while($r=mysql_fetch_array($res)){
					$files[]=array('Data'=>$r['Data'],
									'Name'=>$r['Name'],
									'Content-Type'=>$r['Content-Type'],
									'Disposition'=>$r['Disposition'],
									'FileName'=>$r['FileName'],
									'attachement_type'=>$r['Type']
								);
				}
				
				$data=array(
					'subject'=>	$row['Subject'],
					'plain'=>$row['Plain'],
					'html'=>$row['HTML'],
					'email_credentials_key'=>$row['Email Credentials Key'],
					'to'=>$row['To'],
					'bcc'=>$row['BCC'],
					'attachement'=>$files
				);
				$this->smtp('html', $data);
				$res=$this->send();
				
				if($res['msg']=='ok'){
					$sql=sprintf("update `Email Queue Dimension` set `Status`='Yes' where `Email Queue Key`=%d", $row['Email Queue Key']);
					if(mysql_query($sql))
						$success++;
				}
				else
					$fail++;
					
			}
			$response=sprintf("%d emails sent. %d has failed", $success, $fail);
		break;
		}
		
		return $response;
	}

	function store_in_queue($result, $files=false, $data){
		if(preg_match('/^could not resolve the host domain/',$result['msg'])){
			if(isset($data['html']) && $data['html']){
				$html_msg=$data['html'];
			}
			else
				$html_msg=null;
			
			$sql=sprintf("insert into `Email Queue Dimension` (`To`, `Type`, `Subject`, `Plain`, `HTML`, `Email Credentials Key`, `BCC`) values (%s, %s, %s, %s, %s, %d, %s)	"
			,prepare_mysql($data['to'])
			,prepare_mysql($data['type'])
			,prepare_mysql($data['subject'])
			,prepare_mysql($data['plain'])
			,prepare_mysql($html_msg)
			,$data['email_credentials_key']
			,prepare_mysql($data['bcc'])
			);
		
			//print $sql;
			$stat=mysql_query($sql);
			
			$email_queue_key=mysql_insert_id();
			
			if(isset($files)){
				foreach($files as $value){
					if(isset($value['Data']) && $value['Data']){
						$data_temp=$value['Data'];
					}
					else
						$data_temp=null;
						
					if(isset($value['FileName']) && $value['FileName']){
						$file_name=$value['FileName'];
					}
					else
						$file_name=null;	
						
					if(isset($value['Name']) && $value['Name']){
						$name=$value['Name'];
					}
					else
						$name=null;			
						
					$sql=sprintf("insert into `Email Queue Attachement Dimension` (`Email Queue Key`, `Data`, `FileName`, `Name`, `Content-Type`, `Disposition`, `Type`) values (%d, %s, %s, %s, %s, %s, %s)"
					,$email_queue_key
					,prepare_mysql($data_temp)
					,prepare_mysql($file_name)
					,prepare_mysql($name)
					,prepare_mysql($value['Content-Type'])
					,prepare_mysql($value['Disposition'])
					,prepare_mysql($value['attachement_type'])
					);
					
					//print prepare_mysql($file_name);
					$stat = $stat & mysql_query($sql);
				}
				
			}
			
			if($stat)
				$result=array('state'=>400,'msg'=>_('Message will send shortly'));
			else
				$result=array('state'=>400,'msg'=>'Error: Message could not be sent');
			
		}
		return $result;
	}
  
}

?>
