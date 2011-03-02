{include file='header.tpl'}
<div id="bd" >
    <h2 style="clear:both">{t}Postcard Template Preview{/t} </h2>
<div style="border:1px solid #ccc;padding:50px;width:690px">
<div id="campaign_div">{$msg}</div>

{literal}
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
{/literal}

<form action="send_mail.php" name="newsletter2_form" id="newsletter2_form" method="POST">
<table width="550" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor='#66CC00' >
<tr>
<td valign="top" align="center">

<table width="550" cellpadding="0" cellspacing="0">

 
<tr>
<td style="background-color:#FFFFFF;border-top:0px solid #FFFFFF;border-bottom:0px solid #333333;"><center><a href=""><IMG id=editableImg1 SRC="img/postcard_logo.gif" BORDER="0" title=""  alt="" align="center"></a><h1>{$header}</h1></center></td>
</tr>


</table>

<table width="550" cellpadding="20" cellspacing="0" bgcolor="#FFFFFF">
<tr>
<td bgcolor="#FFFFFF" valign="top" style="font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;">
<p class="basic_template_img"><img height="220" width="500" src="{$image1}"></p>
<p>
<span style="font-size:22px;font-weight:bold;color:#336600;font-family:arial;line-height:110%;"></span><br>

{$block1}
</p>




</td>
</tr>


<tr>
<td style="background-color:#FFFFCC;border-top:10px solid #FFFFFF;" valign="top">
<span style="font-size:10px;color:#333333;line-height:100%;font-family:verdana;">

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

  <input type="submit" name="send_mail" value="Send Mail">
	<input type="hidden" name="template" value="postcard">

   
</form>
</div> 


</div>

{include file='footer.tpl'}
