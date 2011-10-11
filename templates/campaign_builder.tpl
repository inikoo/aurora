{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

{include file='marketing_navigation.tpl'}




 
<div style="clear:left;margin:0 0px">
    <h1>{t}Marketing{/t}</h1>
</div>

</div>
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item selected{if $view=='Emarketing'}selected{/if}" id="metrics"  ><span>  {t}Emarketing{/t}</span></span></li>
    <li> <span class="item {if $view=='newsletter'}selected{/if}"  id="newsletter">  <span> {t}eNewsletters{/t}</span></span></li>
    <li> <span class="item {if $view=='email'}selected{/if}"  id="email">  <span> {t}Email Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web_internal'}selected{/if}"  id="web_internal">  <span> {t}Site Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='web'}selected{/if}"  id="web">  <span> {t}Internet Campaigns{/t}</span></span></li>
    <li> <span class="item {if $view=='other'}selected{/if}"  id="other">  <span> {t}Other Media Campaigns{/t}</span></span></li>
</ul>
 <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>


<div id="block_metrics" style="{if $view!='Emarketing'}display:block;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


<h2>Emarketing</h2>
<div class="table_top_bar"></div>
<table class="options" style="float: left; margin: 0pt 0pt 0pt 0px; padding: 0pt;">
	<tbody><tr><td id="create_list"><a href="#">Create List</a></td>
        <td id="view_list"><a href="customers_lists.php">View List</a></td>
	  <td id="create_campaign"><a href="#" onclick="checkListTable()">Create Campaign</a></td>	  
          <td id="view_campaign" class="campaign_tab"><a style="color:#ffffff;" href="campaign_builder.php">View Campaign</a></td>	</tr>
      </tbody></table>

<div class="data_table" style="clear:both"><br>
   <span class="clean_table_title">{t}Campaign Builder{/t}</span><span style="padding-left:30px;">{$msg}</span>
	<div class="general_options">
		<span onclick="window.location.href='new_campaign.php'">Create Campaign</span>
	</div>
	<div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid rgb(153, 153, 153);"></div>
         <span style="font-size:11px;">{$campaign_size} records<span>
     <div style="clear: both; margin: 0pt 0px; padding: 0pt 20px; border-bottom: 1px solid #4682b4;"></div>
<form action="check_template.php" method="POST" name="myForm" id="myForm" onsubmit="return SelectUrl()">
      <table width="913">
           <tr style="border-bottom:1px #4682b4 solid;"><td class="campaign_header" style="padding-left:20px;">Name</td><td class="campaign_header">Maximum Emails</td><td class="campaign_header">Campaign Objective</td><td class="campaign_header">Status</td>
           
	   </tr>

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

<div>
	<table>
		<tr><td>Campaign Email id(add email Manually) </td> <td>
		<input type="hidden" value="0" id="theValue" />
		<input type="text" name="email[]" id="email" size="30"> <img onclick="addElement();" src="art/icons/add.png">

		<div id="myDiv"> </div> </td>
		</tr>	
	</table>
</div>

 <span style="font-size:11px; color:#445695; font-weight:500;">(Please select the default email template or create it)</span><br><br>
	<b id="showr">Select Template &nbsp;&nbsp;</b>
 	


 	<div id="display_part">
        		<br>
  	        	<input type="radio" id="template1" name="template" value="1">Free Template
			<br><br>
                        <input type="radio" id= "template2" name="template" value="2">Template Email
                       
	<div><br><input type="submit" name="submit" class="Emarketing_button" value="Continue"></div>
        </div>
{/if}
	

</form>	
 

  </div>





</div>
<div id="block_newsletter" style="{if $view!='newsletter'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_email" style="{if $view!='email'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">


  <span   class="clean_table_title" >{t}Email Campaigns{/t}</span>


  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    
   
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
<div  id="table0"   class="data_table_container dtable btable"> </div>


</div>
<div id="block_web_internal" style="{if $view!='web_internal'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_web" style="{if $view!='web'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_other" style="{if $view!='other'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
</div>

{include file='footer.tpl'}

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
