<?php
	include('common.php');

if(isset($_REQUEST['submit'])){


	$mail_list = array();

	$mail_list = isset($_REQUEST['mail_list'])?$_REQUEST['mail_list']:'';

	/*if($mail_list == '')
	{
		$_SESSION['msg'] = 'Please choose the mail ID';
		header('location:campaign_list.php');
		exit();
	}*/
	
	//fetch radio button value for choosing a template
	
	$template = isset($_REQUEST['template'])?$_REQUEST['template']:'';

	
	if($template == 1)
	{
		$structure = "<html>
	<body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor=\"#99CC00\" >


	<STYLE>
	 .headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
	 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
	 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
	 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
	 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
	 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
	 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
	 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
	 a { color:#FF6600; color:#FF6600; color:#FF6600; }
	</STYLE>



	<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" class=\"backgroundTable\" bgcolor=\"#99CC00\" >
		<tr>
			<td valign=\"top\" align=\"center\">
			<table width=\"550\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
				<td style=\"background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;\" align=\"center\">
				<span style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">Email not displaying correctly? 
				<a href=\"*|ARCHIVE|*\" style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">View it in your 					browser.</a>
				</span>
				</td>
				</tr>
 
				<tr>
				<td style=\"background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;\">
				<center><a href=\"\">
				<IMG id=editableImg1 SRC=\"img/logo_header.jpg\" BORDER=\"0\" title=\"Your Company\"  alt=\"Your Company\" align=\"center\"></a></center></td>
				</tr>
			</table>

			<table width=\"550\" cellpadding=\"20\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">
				<tr>
				<td bgcolor=\"#FFFFFF\" valign=\"top\" style=\"font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;\">
			<p>
			<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">
			Genetically Mutated Bananas: Finally</	span><br>
			<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">
			Quisque dignissim dictum ante</span><br>
			Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. 
			Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, 				consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. 
			<a href=\"#\">Clickitus heritus.</a>
			</p>

			<p>
			<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">
			Tools: Putting Those Opposable Thumbs To Use!</span><br>
			<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">
			Quisque dignissim dictum ante</span><br>

			Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. 
			Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, 				consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. 
			<a href=\"#\">Clickitus heritus.</a>
			</p>

			<p>
			<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">
			How-to: Build Your Own Coconut Car In 30 Days</span><br>
			<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">
			Quisque dignissim dictum ante</span><br>
			Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. 
			Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, 				consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. 
			<a href=\"#\">Clickitus heritus.</a>
			</p>
				</td>
				</tr>

				<tr>
				<td style=\"background-color:#FFFFCC;border-top:10px solid #FFFFFF;\" valign=\"top\">
<span style=\"font-size:10px;color:#996600;line-height:100%;font-family:verdana;\">
*|LIST:DESCRIPTION|* <br />
<br />
<a href=\"*|UNSUB|*\">Unsubscribe</a> *|EMAIL|* from this list.<br />

<br />
Our mailing address is:<br />
*|LIST:ADDRESS|*<br />
<br />
Our telephone:<br />
*|LIST:PHONE|*<br />
<br />
Copyright (C) 2007 *|LIST:COMPANY|* All rights reserved.<br />
<br />
<a href=\"*|FORWARD|*\">Forward</a> this email to a friend
 
</span>
				</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</body>
</html>";
		
	}
/***************************************************************************************************************************************/
	if($template == 2)
	{
		$structure = "<html>
<body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor=\"#99CC00\" >

<STYLE>
 .headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
 td { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
 .sideColumn { background-color:#FFFFFF; border-right:1px dashed #CCCCCC; text-align:left; }
 .sideColumnText { font-size:11px; font-weight:normal; color:#999999; font-family:arial; line-height:150%; }
 .sideColumnTitle { font-size:15px; font-weight:bold; color:#333333; font-family:arial; line-height:150%; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
 a { color:#FF6600; color:#FF6600; color:#FF6600; }
</STYLE>



<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" bgcolor='#99CC00' >
<tr>
<td valign=\"top\" align=\"center\">

<table width=\"600\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td style=\"background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;\" align=\"center\"><span style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">Email not displaying correctly? <a href=\"*|ARCHIVE|*\" style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">View it in your browser.</a></span></td>

</tr>

<tr>
<td align=\"left\" valign=\"middle\" style=\"background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;\"><center><a href=\"\"><IMG id=editableImg1 SRC=\"img/logo_2column.jpg\" BORDER=\"0\" title=\"Your Company\"  alt=\"Your Company\" align=\"center\"></a></center></td>
</tr>


</table>

<table width=\"600\" cellpadding=\"20\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">
<tr>

<td width=\"200\" valign=\"top\" style=\"background-color:#FFFFFF;border-right:1px dashed #CCCCCC;text-align:left;\">
<span style=\"font-size:11px;font-weight:normal;color:#999999;font-family:arial;line-height:150%;\">

<span style=\"font-size:15px;font-weight:bold;color:#333333;font-family:arial;line-height:150%;\">Grooming Tips:</span><br>

Tick picking novice? Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum.

<br>
<br>

<span style=\"font-size:15px;font-weight:bold;color:#333333;font-family:arial;line-height:150%;\">Waterhole Dangers</span><br>
Watery oasis, or crocodilian cesspool from hell? As the dry season approaches, lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum.

</span>
</td>


<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"400\" style=\"font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;\">

<p>
<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Why Are Tigers So Mean?</span><br>
<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">by Frederick Von Chimpenheimer</span><br>
Recent psychological studies and brain scans have shown that tigers have naturally violent tendencies toward chimpanzees. \"There's sort of a banana-envy thing going on, which makes the average tiger very self-conscious around chimpanzees\" says Dr. Chimpfried Brown. Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. <a href=\"#\">Clickitus heritus.</a>
</p>



<p>
<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Plantains vs. Bananas</span><br>

<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">by Francine Chimperson</span><br>
Throwing a party? There's no better way to anger your dinner guests than by accidentally serving them plantains instead of bananas. Avoid the poo flinging with these handy guidelines. Plantains look like over ripe bananas, and must be cooked before eaten. Bananas are softer, and don't cook very well (they get mushy). In suscipit, pede a rutrum malesuada, lacus massa euismod neque <a href=\"#\">Clickitus heritus.</a>
</p>

</td>


</tr>

<tr>
<td style=\"background-color:#FFFFCC;border-top:10px solid #FFFFFF;\" valign=\"top\" colspan=\"2\">
<span style=\"font-size:10px;color:#996600;line-height:100%;font-family:verdana;\">
*|LIST:DESCRIPTION|* <br />

<br />
<a href=\"*|UNSUB|*\">Unsubscribe</a> *|EMAIL|* from this list.<br />
<br />
Our mailing address is:<br />
*|LIST:ADDRESS|*<br />
<br />
Our telephone:<br />
*|LIST:PHONE|*<br />
<br />
Copyright (C) 2007 *|LIST:COMPANY|* All rights reserved.<br />

<br />
<a href=\"*|FORWARD|*\">Forward</a> this email to a friend
  
</span>
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>";

	}
/********************************************************************************************************************************************/
	if($template == 3)
	{

		$structure = "<html>
<body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor='#99CC00' >

<STYLE>
 .headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
 td { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
 .sideColumn { background-color:#FFFFFF; border-left:1px dashed #CCCCCC; text-align:left; }
 .sideColumnText { font-size:11px; font-weight:normal; color:#999999; font-family:arial; line-height:150%; }
 .sideColumnTitle { font-size:15px; font-weight:bold; color:#333333; font-family:arial; line-height:150%; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
 a { color:#FF6600; color:#FF6600; color:#FF6600; }
</STYLE>



<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" bgcolor='#fdf4d7' >
<tr>
<td valign=\"top\" align=\"center\">

<table width=\"600\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td style=\"background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;\" align=\"center\"><span style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">Email not displaying correctly? <a href=\"*|ARCHIVE|*\" style=\"font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;\">View it in your browser.</a></span></td>

</tr>

<tr>
<td align=\"left\" valign=\"middle\" style=\"background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;\"><center><a href=\"\"><IMG id=editableImg1 SRC=\"img/email_header_UK_tris.jpg\" BORDER=\"0\" title=\"Your Company\"  alt=\"Your Company\" align=\"center\"></a></center></td>
</tr>


</table>

<table width=\"600\" cellpadding=\"20\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">
<tr>

<td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"400\" style=\"font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;\">

<p>
<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">New Oil Burners</span><br>

<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">Directly from India</span><br>
Wow, they are beautiful, and cheap.
<br/><br/>
<img src=\"http://www.ancientwisdom.biz/pics/ob-189.jpg\" width=150/>
<img src=\"http://www.ancientwisdom.biz/pics/ob-188.jpg\" width=150/>
</p>



<p>
<span style=\"font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;\">Plantains vs. Bananas</span><br>
<span style=\"font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;\">by Francine Chimperson</span><br>
Throwing a party? There's no better way to anger your dinner guests than by accidentally serving them plantains instead of bananas. Avoid the poo flinging with these handy guidelines. Plantains look like over ripe bananas, and must be cooked before eaten. Bananas are softer, and don't cook very well (they get mushy). In suscipit, pede a rutrum malesuada, lacus massa euismod neque <a href=\"#\">Clickitus heritus.</a>
</p>

</td>


<td width=\"200\" valign=\"top\" style=\"background-color:#FFFFFF;border-left:1px dashed #CCCCCC;text-align:left;\">
<span style=\"font-size:11px;font-weight:normal;color:#999999;font-family:arial;line-height:150%;\">

<span style=\"font-size:15px;font-weight:bold;color:#333333;font-family:arial;line-height:150%;\">Grooming Tips:</span><br>
Tick picking novice? Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum.
<img src=\"http://www.ancientwisdom.biz/pics/ob-189.jpg\" width=50/>


<br>
<br>

<span style=\"font-size:15px;font-weight:bold;color:#333333;font-family:arial;line-height:150%;\">Waterhole Dangers</span><br>

Watery oasis, or crocodilian cesspool from hell? As the dry season approaches, lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum.

</span>
</td>


</tr>


<tr>
<td style=\"background-color:#FFFFCC;border-top:10px solid #FFFFFF;\" valign=\"top\" colspan=\"2\">
<span style=\"font-size:10px;color:#996600;line-height:100%;font-family:verdana;\">
*|LIST:DESCRIPTION|* <br />
<br />
<a href=\"*|UNSUB|*\">Unsubscribe</a> *|EMAIL|* from this list.<br />

<br />
Our mailing address is:<br />
*|LIST:ADDRESS|*<br />
<br />
Our telephone:<br />
*|LIST:PHONE|*<br />
<br />
Copyright (C) 2007 *|LIST:COMPANY|* All rights reserved.<br />
<br />
<a href=\"*|FORWARD|*\">Forward</a> this email to a friend
  

</span>
</td>
</tr>


</table>
</td>
</tr>
</table>
</body>
</html>";


	}

/*****************************************************************************************************************************************/

	if($template == 4)
	{

		$structure = "<html>
<body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor='#66CC00' >

<STYLE>
 .headerTop { background-color:#66CC00; border-top:0px solid #000000; border-bottom:0px solid #FFCC66; text-align:right; }
 .adminText { font-size:10px; color:#FFFFCC; line-height:200%; font-family:verdana; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #FFFFFF; border-bottom:0px solid #333333; }
 .title { font-size:22px; font-weight:bold; color:#336600; font-family:arial; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
 td { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#333333; line-height:100%; font-family:verdana; }
 a { color:#FF0000; color:#FF6600; color:#FF6600; }
</STYLE>



<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" class=\"backgroundTable\" bgcolor='#66CC00' >
<tr>
<td valign=\"top\" align=\"center\">

<table width=\"550\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td style=\"background-color:#66CC00;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;\" align=\"center\"><span style=\"font-size:10px;color:#FFFFCC;line-height:200%;font-family:verdana;text-decoration:none;\">Email not displaying correctly? <a href=\"*|ARCHIVE|*\" style=\"font-size:10px;color:#FFFFCC;line-height:200%;font-family:verdana;text-decoration:none;\">View it in your browser.</a></span></td>

</tr>
 
<tr>
<td style=\"background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;\"><center><a href=\"\"><IMG id=editableImg1 SRC=\"img/postcard_logo.gif\" BORDER=\"0\" title=\"Your Company\"  alt=\"Your Company\" align=\"center\"></a></center></td>
</tr>

<tr>
<td style=\"background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;\"><center><a href=\"#\"><img src=\"img/postcard_splash.jpg\" width=\"550\" height=\"300\" border=\"0\" alt=\"Lorem ipsum\"></a></center></td>
</tr>
</table>

<table width=\"550\" cellpadding=\"20\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">
<tr>
<td bgcolor=\"#FFFFFF\" valign=\"top\" style=\"font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;\">

<p>
<span style=\"font-size:22px;font-weight:bold;color:#336600;font-family:arial;line-height:110%;\">The Big Banana Bonanza</span><br>

Lorem ipsum dolor sit amet, <a href=\"#\">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. <a href=\"#\">Clickitus heritus.</a>
</p>




</td>
</tr>


<tr>
<td style=\"background-color:#FFFFCC;border-top:10px solid #FFFFFF;\" valign=\"top\">
<span style=\"font-size:10px;color:#333333;line-height:100%;font-family:verdana;\">

*|LIST:DESCRIPTION|* <br />
<br />
<a href=\"*|UNSUB|*\">Unsubscribe</a> *|EMAIL|* from this list.<br />
<br />
Our mailing address is:<br />
*|LIST:ADDRESS|*<br />
<br />
Our telephone:<br />
*|LIST:PHONE|*<br />

<br />
Copyright (C) 2007 *|LIST:COMPANY|* All rights reserved.<br />
<br />
<a href=\"*|FORWARD|*\">Forward</a> this email to a friend
  
</span>
</td>
</tr>


</table>


</td>
</tr>

</table>


</body>
</html>";

	}


/************************************************************************************************************************************/



	foreach($mail_list as $key=>$mail)
	{	
		
		$sql = "select * from `Email Campaign Dimension` where `Email Campaign Key` = '".$mail."'";
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		
		$name = $row['Email Campaign Name'];
	
		$objective = $row['Email Campaign Objective'];

		$content = $row['Email Campaign Content'];		

		//pick up the customer mail to send
		$runSql = "select `Customer Key` from `Email Campaign Mailing List` where `Email Campaign Key` = '".$mail."'";
		$runQuery = mysql_query($runSql);
		$fetchRow = mysql_fetch_assoc($runQuery);

		//print_r($fetchRow); die();
		
		//fetch the customer base from Customer List Customer Bridge
		$exeSql = "select `Customer Key` from `Customer List Customer Bridge` where `Customer List Key` = '".$fetchRow['Customer Key']."'";
		$exeQuery = mysql_query($exeSql);
		

		while($exeRow = mysql_fetch_assoc($exeQuery))
		{
			$printSql = "select `Customer Main Plain Email` from `Customer Dimension` where `Customer Key` = '".$exeRow['Customer Key']."'";
			$resultSet = mysql_query($printSql);
			$execute = mysql_fetch_assoc($resultSet);

			
			$to = $execute['Customer Main Plain Email'];
			$from = "rulovico@gmail.com";
			$subject = "Hello! This is HTML email";
			
		
			//begin of HTML message
			$message = <<<EOF
			$structure
EOF;
			//end of message
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";

			//options to send to cc+bcc
			//$headers .= "Cc: [email]maa@p-i-s.cXom[/email]";
			//$headers .= "Bcc: [email]email@maaking.cXom[/email]";

			// now lets send the email.
			mail($to, $subject, $message, $headers);

			



		}
			$_SESSION['msg'] = "Message has been sent....!"; 
			header('location:campaign_list.php');
	}

}
	
?>
