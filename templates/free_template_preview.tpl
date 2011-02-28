{include file='header.tpl'}
<div id="bd" >


      <h2 style="clear:both">{t}Free Template Preview{/t} </h2>
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
<span style="font-size:20px;color:#000000;">{$template_sub}</span><br><br>
{$template_body}
<form  action="send_mail.php" name="free_template" method="post">
<input type="hidden" name="templateSub" value="{$template_sub}">
<input type="hidden" name="templatebody" value="{$template_body}
<input type="submit" name="send" id="send" value="Send">
</form>
</div> 


</div>

{include file='footer.tpl'}
