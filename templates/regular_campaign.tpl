{include file='header.tpl'}
{literal}
<script src="http://www.google.com/jsapi?key=ABQIAAAA1z2R5qvV2le8XlHGBMQW_BT2p0lhzy-giP_0zK-c010Lfxp0GhRrBN2wCdM7TBNC0YRkYDixL7chxg" type="text/javascript">
</script>

{/literal}
<script type="text/javascript">
google.load("jquery", "1"); 

</script>
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

 <br><br>
<div style="clear:left;margin:0 0px">
    <h1 style="text-transform: capitalize; width:200px; float:left;">{t}Campaign Builder{/t}</h1>
    <div class="campaign_cancel"> <a href="#">cancel &amp; exit</a> </div>
</div>
<br><br><hr style="color:#DDDDDD"><br><br><br>

	<table border=0 width="900px">
	<form action="campaign_use_segment.php" method="post">
	<tr>	
		<td colspan=2>which list would you like to send this campaign to? <span style="padding-left:480px;">
	<a href="#"><img src="art/next-bottom.gif"></a></span></td>
		</tr>
		
		{section name=value loop=$value}
		<tr bgcolor="#f1ede0">
		<td width="600">
		<div id="container">
		{literal}
		<script type="text/javascript">
		
			function showSlidingDiv(m){
			var id='#slidingDiv'+m;
			$(id).animate({"height": "toggle"}, { duration: 1000 }); 
			}
	
		</script>
		
		{/literal}
		<input type="radio" name="rdb" id="rdb" value="{$value[value].$list_key}"> &nbsp; 
		{$value[value].$list_name} ({$value[value].$recipient} recipients) 
		<div id="slidingDiv{$value[value].$list_key}" style="display: none; height:150px; padding:20px;">
		
			
			<input type="hidden" value="0" id="theValue" />
		<p><a href="javascript:;" onclick="addEvent({$value[value].$list_key});"><span style="font-size:10px; color:#CC66OD;">Add Condition</span></a></p>
			<div id="myDiv{$value[value].$list_key}" style="font-size:10px; color:#CC66OD;"> </div>
			
			
		
		<br>
		<br>
		
		
		</div>
		</div>
		</td>
		<td>
		<img src="art/icons/bullet_go.png" />
		<a href="#" onClick="showSlidingDiv({$value[value].$list_key}); return false;">
		<span style="color:#7080b1">Send to Segment</span> </a>&nbsp;&nbsp;&nbsp;
		<input type="submit" name="entire_list" value="Send to Entire List"  style="background-color:#7080b1; color:#fff">
		
	
		
		<span style="float:right"><br><br><a href="campaign_use_segment.php?getID={$value[value].$list_key}" name="use_segment"><img src="art/button1.png" /></a>&nbsp;&nbsp;<a href="" name="cancel"><img src="art/button2.png" /></a></span>

		</td>
		</tr>
			
		</div>
		
		{/section}
		
</form>	
	</table>	
	</div>
</div>
</div>

{include file='footer.tpl'}
