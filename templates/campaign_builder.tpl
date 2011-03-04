{include file='header.tpl'}



<div id="bd" >
<span class="nav2 onleft"><a href="#">{t}Create List{/t}</a></span>

<span class="nav2 onleft"><a href="customers_lists.php">{t}View List{/t}</a></span>
<span class="nav2 onleft"><a href="new_campaign.php">{t}Create Campaign{/t}</a></span>
<span class="nav2 onleft"><a href="campaign_builder.php">{t}View Campaign{/t}</a></span>

 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Campaign Builder{/t}</span><span style="padding-left:30px;">{$msg}</span>
	<div class="general_options">
		<span onclick="window.location.href='new_campaign.php'">Create Campaign</span>
	</div>
	<div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid rgb(153, 153, 153);"></div>
         <span style="font-size:11px;">{$campaign_size} records<span>
     <div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid #4682b4;"></div>
      <table width="913">
           <tr style="border-bottom:1px #4682b4 solid;"><td class="campaign_header" style="padding-left:20px;">Name</td><td class="campaign_header">Maximum Emails</td><td class="campaign_header">Campaign Objective</td><td class="campaign_header">Status</td>
           
	   </tr>
<form action="check_template.php" method="POST" name="myForm" id="myForm" onsubmit="return SelectUrl()">
{section name="i" loop="$campaign"}
	
    <tr bgcolor="{cycle values=#f2f2ff,#ffffff}"> {* CHANGE HERE *}
  	

      <td align='center' style="font: 13px/1.231 arial,helvetica,clean,sans-serif;color: #222222;font-weight:400;"><input  type="checkbox" id="mail_{$campaign[i].$key}" name="check_email[]" value="{$campaign[i].$key}">&nbsp;&nbsp;{$campaign[i].$name}</td>
<td align='center' style="font: 13px/1.231 arial,helvetica,clean,sans-serif;color: #222222;font-weight:400;">{$campaign[i].$emails}</td>
<td align='center' style="font: 13px/1.231 arial,helvetica,clean,sans-serif;color: #222222;font-weight:400;">{$campaign[i].$obj}</td>
<td align='center' style="font: 13px/1.231 arial,helvetica,clean,sans-serif;color: #222222;font-weight:400;">{$campaign[i].$status}</td>
	
		
    </tr>
	
{/section} 
       
	   


     </table>
<div style="clear: both; margin: 0pt 0px;margin-top:-7px; margin-bottom:10px; border-bottom: 1px solid #4682b4;"></div>
	
	
{if !isset($no_record) }

 <span style="font-size:11px; color:#445695; font-weight:500;">(Please select the default email template or create it)</span><br><br>
	<b id="showr">Select Template &nbsp;&nbsp;</b>
 	
 	<div id="display_part">
        		<br>
  	        	<input type="radio" id="template1" name="template" value="1">Free Template
			<br><br>
                        <input type="radio" id= "template2" name="template" value="2">Template Email
                       
	<div><br><input type="submit" name="submit" value="Continue"></div>
        </div>
{/if}
	

</form>	
 

  </div>

	


</div>

  
  {include file='footer.tpl'}
