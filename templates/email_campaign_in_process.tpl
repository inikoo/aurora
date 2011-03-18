{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<div style="clear:left;margin:0 0px">
<h1>{t}Email Campaign{/t}: {$email_campaign->get('Email Campaign Name')}</h1>


<div class="top_row" >
<div class="general_options" style="padding:10px;float:right">
	<span  style="margin-right:10px;" class="disabled"  id="send_email_campaign" class="state_details">{t}Send{/t}</span>
		<span  style="margin-right:10px;" class="disabled"  id="preview_email_campaign" class="state_details">{t}Preview{/t}</span>
	<span style="margin-right:10px;display:none" id="save_edit_email_campaign" class="state_details">{t}Save{/t}</span>

	<span style="margin-right:10px;" id="save_and_exit_edit_email_campaign" class="state_details">{t}Continue Later{/t}</span>
		<span style="margin-right:10px;" id="delete_email_campaign" class="state_details">{t}Delete{/t}</span>


</div>

<table class="edit" style="margin-top:10px"  >
<input type="hidden" id="store_id" value="{$email_campaign->get('Email Campaign Store Key')}">
<input type="hidden" id="email_campaign_key" value="{$email_campaign->get('Email Campaign Key')}">
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objetive">{t}Invalid Campaign Objetive{/t}</span>
<span style="display:none" id="invalid_email_campaign_recipients">{t}Please add recipients{/t}</span>

<tr class="top">
<td class="label">{t}Store:{/t}</td>
<td>
{$store->get('Store Name')}

</td>
</tr>


<tr class=""><td style=";width:160px" class="label" >{t}Campaign Name{/t}:</td>
   <td  style="text-align:left;">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="email_campaign_name" value="{$email_campaign->get('Email Campaign Name')}" ovalue="{$email_campaign->get('Email Campaign Name')}" >
       <div id="email_campaign_name_Container" style="" ></div>
     </div>
   </td>
   <td style="width:300px"id="email_campaign_name_msg" class="edit_td_alert"></td>
 </tr>

<tr class="last"><td style="" class="label" >{t}Campaign Objetive{/t}:</td>
   <td  style="text-align:left;">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="email_campaign_objetive" value="{$email_campaign->get('Email Campaign Objective')}" ovalue="{$email_campaign->get('Email Campaign Objective')}" >
       <div id="email_campaign_objetive_Container" style="" ></div>
     </div>
   </td>
   <td id="email_campaign_objetive_msg" class="edit_td_alert"></td>
 </tr>
 
<tr class="last">
<td style="" class="label" >{t}Recipients{/t}:</td>
<td ><span id="recipients_preview">{$email_campaign->get('Email Campaign Recipients Preview')}</span></td>
<td>
<div class="general_options" >
		<span style="margin-left:0px;;float:none" id="add_email_address_manually" class="state_details">{t}Add manually{/t}</span>

	<span  style="margin-left:20px;float:none"  id="add_email_address_from_customer_list" class="state_details">{t}Add from Customer List{/t}</span>
	
      </div>

</td>
</tr>
<tr class="last" style="height:40px">
<td style="" class="label" >{t}Advertising Object{/t}:</td>
<td ><span id="number_of_recipients">{$email_campaign->get('Email Campaign')}</span></td>
<td>
<div class="general_options" >
	
	<span  style="margin-left:0px;;float:none"   id="save_edit_email_campaign" class="state_details">{t}Department{/t}</span>
	<span style="margin-left:20px;float:none" id="reset_edit_email_campaign" class="state_details">{t}Family{/t}</span>
		<span style="margin-left:20px;float:none" id="reset_edit_email_campaign" class="state_details">{t}Product{/t}</span>
	<span style="margin-left:20px;float:none" id="reset_edit_email_campaign" class="state_details">{t}Offer{/t}</span>

      </div>

</td>
</tr>
<tr class="" style="height:40px">
<td style="" class="label" >{t}Type of Email{/t}:</td>
<td colspan=2 ><div style="margin-top:2px;font-size:100%">
<span  style="margin-right:10px;"  id="select_text_email" class="small_button" onClick="text_email()">{t}Text Email{/t}</span>
<span style="" id="select_html_email" class="small_button" onClick="html_email()">{t}HTML Email{/t}</span>
</div>

</td>
</tr>
<tbody id="text_email_fields" style="display:none">
<tr class=""  >

<td style="" class="label" >{t}Subject{/t}:</td>
   <td  style="text-align:left;">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="email_campaign_subject" value="{$email_campaign->get('Email Campaign Objective')}" ovalue="{$email_campaign->get('Email Campaign Objective')}" >
       <div id="email_campaign_objetive_Container" style="" ></div>
     </div>
   </td>
   <td id="email_campaign_objetive_msg" class="edit_td_alert"></td>
 </tr>

<td style="" class="label" >{t}Content{/t}:</td>
   <td  colspan=2 style="text-align:left;">
   <textarea style="width:600px;height:250px;background-image:url(art/text_email_guide.png);"></textarea>
   </td>
 
 </tr>
</tbody>
</table>



</div>

</div>

<div id="create_email_list_block" style="display:none">

<div id="staff_column" class="col">
<h2>{t}Products{/t}</h2>
<div style="font-size:80%">
<span id="add_product" class="state_details" style="margin-right:10px">{t}Add Product{/t}</span>
<span id="add_family" class="state_details" style="margin-right:10px">{t}Add Family{/t}</span>
<span id="add_department" class="state_details">{t}Add Department{/t}</span>



</div>

</div>	
<div id="suppliers_column" class="col">
<h2>{t}Offers{/t}</h2>

</div>
<div id="customers_column" class="col" style="margin-right:0px">
<h2>{t}Customers{/t}</h2>

</div>
</div>

</div>

</div>

<div id="dialog_add_email_address">
  <div id="note_msg"></div>
  <div style="display:none" id="new_add_email_address_manually_invalid_msg">{t}Fill the form properly{/t}</div>
  <table style="padding:10px;margin:10px" border=0 >
 
  
   
   <tr class="">
   <td  class="label" >{t}Email Address{/t}:</td>
   <td  style="text-align:left">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left" id="add_email_address" value="" ovalue="" >
       <div id="add_email_address_Container" style="" ></div>
     </div>
   </td>
   <td id="add_email_address_msg" class="edit_td_alert"></td>
 </tr>
    <tr class="">
    <td class="label" >{t}Contact Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;" id="add_email_contact_name" value="" ovalue="" >
       <div id="add_email_contact_name_Container" style="" ></div>
     </div>
   </td>
   <td id="add_email_contact_name_msg" class="edit_td_alert"></td>
 </tr>
 <tr><td colspan="3" class="error" style="display:none" id="new_add_email_address_manually_dialog_msg"></td></tr>
    <tr class="buttons" style="font-size:100%">
   <td></td>
   <td colspan="2">
   <div class="general_options" style="padding:10px;float:right">
	<span  style="margin-right:10px;" class="disabled"  id="save_new_add_email_address_manually" class="state_details">{t}Add{/t}</span>
	<span style="margin-right:10px;" id="cancel_new_add_email_address_manually" class="state_details">{t}Cancel{/t}</span>
</div>
    </td>
    </tr>
</table>
</div>



{include file='footer.tpl'}



<div id="dialog_add_email_address_from_list">

<div class="splinter_cell" style="padding:30px 15px 10px 0;border:none">

<div id="the_table" class="data_table" >
 <span class="clean_table_title">Customer Lists</span>
 <div class="home_splinter_options" style="position:relative;top:-5px">
 <a class="state_details" href="new_customers_list.php?store={$email_campaign->get('Email Campaign Store Key')}&gbt=ecip&gbtk={$email_campaign->id}" style="float:right;margin-left:5px">{t}Create List{/t}</a>
 </div>
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name filter_value=$filter_value no_filter=1}
   <div  id="table0"   class="data_table_container dtable btable "> </div>
 </div>
 <div id="add_email_address_from_customer_list_msg" class="error"></div>

 </div>
 </div>
