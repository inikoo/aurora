{include file='header.tpl'}
<div id="bd" >


      <h2 style="clear:both">{t}Newsletter1 Template Preview{/t} </h2>
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


	
<table cellpadding="10" cellspacing="0" bgcolor='#99CC00'style="min-width:600px;" >
<tr>
<td valign="top" align="center">

<table width="600" cellpadding="0" cellspacing="0">


<tr>
<td align="left" valign="middle" style="background-color:#FFFFFF;border-top:0px solid #333333;border-bottom:10px solid #FFFFFF;"><center><h1>{$header}</h1></center></td>
</tr>


</table>

<table width="600" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
<tr>

<td width="200" valign="top" style="background-color:#FFFFFF;border-right:1px dashed #CCCCCC;text-align:left;">
<span style="font-size:11px;font-weight:normal;color:#999999;font-family:arial;line-height:150%;">

<span style="font-size:15px;font-weight:bold;color:#222222;font-family:arial;line-height:150%;"></span>

{$block3}



</span>
</td>


<td bgcolor="#FFFFFF" valign="top" width="400" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">

<p>
<span style="font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;">{$contenttitle}</span><br></p>
<p style="width:450px;">
{$block1}
</p>
<p class="basic_template_img"><img height="107" width="350" src="{$image1}"></p>


<p style="width:450px;">

{$block2}
</p>
<p class="basic_template_img"><img height="107" width="450" src="{$image2}"></p>
</td>


</tr>

<tr>
<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;" valign="top" colspan="2">
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
