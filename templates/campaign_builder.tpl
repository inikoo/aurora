{include file='header.tpl'}

{literal}
<script>
$(document).ready(function(){
$("#showr").click(function () {
  $("#display_part:eq(0)").show("fast", function () {
    /* use callee so don't have to name the function */
    $(this).next("#display_part").show("fast", arguments.callee);
  });
});
$("#hidr").click(function () {
  $("#display_part").hide("fast");
});
});
</script>
{/literal}

<div id="bd" >

 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Campaign Builder{/t}</span><span style="padding-left:30px;">{$msg}</span>
	<div class="general_options">
		<span onclick="window.location.href='new_campaign.php'">Create Campaign</span>
	</div>
	<div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid rgb(153, 153, 153);"></div>
         <span style="font-size:11px;">{$campaign_size} records<span>
     <div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid #4682b4;"></div>
      <table width="913">
           <tr style="border-bottom:1px #4682b4 solid;"><td class="campaign_header">Name</td><td class="campaign_header">Maximum Emails</td><td class="campaign_header">Campaign Objective</td><td class="campaign_header">Status</td>
           
	   </tr>
<form action="check_template.php" method="POST" name="myForm" id="myForm" onsubmit="return SelectUrl()">
{section name="i" loop="$campaign"}
	
    <tr bgcolor="{cycle values=#eeeeee,#d0d0d0}"> {* CHANGE HERE *}
  	

      <td align='center'><input type="checkbox" id="mail_{$campaign[i].$key}" name="check_email[]" value="{$campaign[i].$key}">{$campaign[i].$name}</td><td align='center'>{$campaign[i].$emails}</td><td align='center'>{$campaign[i].$obj}</td><td align='center'>{$campaign[i].$status}</td>
	
		
    </tr>
	
{/section} 

       
	   <tr><td></td>
		<td></td>
		<td></td>
		<td></td>
	  </tr>


     </table>

	
	
{if !isset($no_record) }

 <span style="font-size:11px; color:#445695; font-weight:500;">(Please select the default email template or create it)</span><br><br>
	<b id="showr">Select Template &nbsp;&nbsp;</b>
 	<b id="hidr">Cancel</b>
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
