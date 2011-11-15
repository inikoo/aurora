{include file='header.tpl'}
<div id="bd" >
<input value="{$email_campaign->id}" id="email_campaign_key" type="hidden"  />
{include file='marketing_navigation.tpl'}
<div class="branch"> 
  <span >{if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}&block_view=email">{$store->get('Store Code')} {t}Marketing{/t} ({t}Email Campaigns{/t})</a> &rarr; <a href="email_campaign.php?id={$email_campaign->id}">{$email_campaign->get('Email Campaign Name')}</a> &rarr; {t}Mailing List{/t}</span>
</div>


<div class="top_page_menu">
    <div class="buttons" style="float:left">
        <button  onclick="window.location='email_campaign.php?id={$email_campaign->id}'" ><img src="art/icons/door_out.png" alt=""> {t}Return to Email Campaign{/t}</button>
    </div>
    <div class="buttons" style="float:right">
     		<button id="add_email_address_manually" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Individual Email Address{/t}</button>
		<button id="add_email_address_manually" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Import from File{/t}</button>
	    <button   id="add_email_address_from_customer_list" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Import from Customer List{/t}</button>

       </div>
    <div style="clear:both"></div>
</div>
 


 <div id="the_table" class="data_table" style="clear:both;margin-top:15px">
      <span class="clean_table_title">{t}Mailing List{/t}</span>
 
  <div class="table_top_bar" style="margin-bottom:15px" ></div> 

{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
 </div>

</div>

<div id="dialog_export">
	
  
  
  
  <div id="filtermenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
	{foreach from=$filter_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
  <div id="rppmenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
	{foreach from=$paginator_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
<div id="dialog_add_email_address" style="padding:20px 10px 10px 10px">
  <div id="note_msg"></div>
  <div style="display:none" id="new_add_email_address_manually_invalid_msg">{t}Fill the form properly{/t}</div>
  <table style="padding:10px;margin:10px" border=0 >
 
  
   
   <tr >
   <td  class="label" >{t}Email Address{/t}:</td>
   <td  style="text-align:left;;width:250px">
     <div  >
       <input style="text-align:left" id="add_email_address" value="" ovalue="" >
       <div id="add_email_address_Container"  ></div>
     </div>
   </td>
   <td id="add_email_address_msg" class="edit_td_alert"></td>
 </tr>
    <tr >
    <td class="label" >{t}Contact Name{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="add_email_contact_name" value="" ovalue="" >
       <div id="add_email_contact_name_Container"  ></div>
     </div>
   </td>
   <td id="add_email_contact_name_msg" class="edit_td_alert"></td>
 </tr>
 <tr><td colspan="3" class="error" style="display:none" id="new_add_email_address_manually_dialog_msg"></td></tr>
    <tr >
 
   <td colspan="2">
   <div class="buttons" >
   	<button  class="disabled positive"  id="save_new_add_email_address_manually" >{t}Add{/t}</button>

   	<button  id="cancel_new_add_email_address_manually" class="negative">{t}Cancel{/t}</button>
</div>
    </td>
    </tr>
</table>
</div>

<div id="dialog_add_email_address_from_list">

<div class="splinter_cell" style="padding:30px 15px 10px 0;border:none">

<div id="the_table" class="data_table" >
 <span class="clean_table_title">{t}Customer Lists{/t}</span>
 <div class="home_splinter_options" style="position:relative;top:-5px">
 <a class="state_details" href="new_customers_list.php?store={$email_campaign->get('Email Campaign Store Key')}&gbt=ecip&gbtk={$email_campaign->id}" style="float:right;margin-left:5px">{t}Create List{/t}</a>
 </div>
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1}
   <div  id="table1"   class="data_table_container dtable btable "> </div>
 </div>
 <div id="add_email_address_from_customer_list_msg" class="error"></div>

 </div>
 </div>

  {include file='footer.tpl'}
