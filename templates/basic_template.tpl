{include file='header.tpl'}
<div id="bd" >


      <h2 style="clear:both">{t}Basic Template Preview{/t} </h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
<div id="campaign_div">{$msg}</div>

{literal}
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
{/literal}


	<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor='#99CC00' >
		<tr>
			<td valign="top" align="center">
			<table width="550" cellpadding="0" cellspacing="0">
				<tr>
				<td style="background-color:#FFCC66;border-top:0px solid #000000;border-bottom:1px solid #FFFFFF;text-align:center;" align="center">
				<span style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">Email not displaying correctly? 
				<a href="*|ARCHIVE|*" style="font-size:10px;color:#996600;line-height:200%;font-family:verdana;text-decoration:none;">View it in your 					browser.</a>
				</span>
				</td>
				</tr>
 
				<tr>
				<td style="background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;">
				<center><a href="">
				<IMG id=editableImg1 SRC="img/logo_header.jpg" BORDER="0" title="Your Company"  alt="Your Company" align="center"></a></center></td>
				</tr>
			</table>

			<table width="550" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
				<td bgcolor="#FFFFFF" valign="top" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">
			<p>
			<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">
			Genetically Mutated Bananas: Finally</	span><br>
			<span style="font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;">
			Quisque dignissim dictum ante</span><br>
			Lorem ipsum dolor sit amet, <a href="#">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. 
			Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, 				consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. 
			<a href="#">Clickitus heritus.</a>
			</p>

			<p>
			<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">
			Tools: Putting Those Opposable Thumbs To Use!</span><br>
			<span style="font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;">
			Quisque dignissim dictum ante</span><br>

			Lorem ipsum dolor sit amet, <a href="#">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. 
			Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, 				consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. 
			<a href="#">Clickitus heritus.</a>
			</p>

			<p>
			<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">
			How-to: Build Your Own Coconut Car In 30 Days</span><br>
			<span style="font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;">
			Quisque dignissim dictum ante</span><br>
			Lorem ipsum dolor sit amet, <a href="#">consectetuer adipiscing elit</a>. Sed at erat. Phasellus condimentum. 
			Nullam sed magna. Donec quis tellus in neque congue porttitor. Proin sit amet ligula id leo porta rutrum. Lorem ipsum dolor sit amet, 				consectetuer adipiscing elit. In suscipit, pede a rutrum malesuada, lacus massa euismod neque, a hendrerit justo ante at eros. 
			<a href="#">Clickitus heritus.</a>
			</p>
				</td>
				</tr>

				<tr>
				<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;" valign="top">
<span style="font-size:10px;color:#996600;line-height:100%;font-family:verdana;">
*|LIST:DESCRIPTION|* <br />
<br />
<a href="*|UNSUB|*">Unsubscribe</a> *|EMAIL|* from this list.<br />

<br />
Our mailing address is:<br />
*|LIST:ADDRESS|*<br />
<br />
Our telephone:<br />
*|LIST:PHONE|*<br />
<br />
Copyright (C) 2007 *|LIST:COMPANY|* All rights reserved.<br />
<br />
<a href="*|FORWARD|*">Forward</a> this email to a friend
 
</span>
				</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
   

</div> 


</div>

{include file='footer.tpl'}
