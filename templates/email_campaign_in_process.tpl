{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<div style="clear:left;margin:0 0px">
<h1>{t}Email Campaign{/t}: <span id="h1_email_campaign_name">{$email_campaign->get('Email Campaign Name')}</span></h1>


<div class="top_row" >
<div  style="padding:10px;float:left" id="edit_email_campaign_msg">
</div>
<div class="general_options" style="padding:10px;float:right">
	<span  style="margin-right:10px;" class="disabled"  id="send_email_campaign" class="state_details">{t}Send{/t}</span>
		<span  style="margin-right:10px;" class="disabled"  id="preview_email_campaign" class="state_details">{t}Preview{/t}</span>
	
	<span style="margin-right:10px;" id="save_and_exit_edit_email_campaign" class="state_details">{t}Continue Later{/t}</span>
		<span style="margin-right:10px;" id="delete_email_campaign" class="state_details">{t}Delete{/t}</span>

<span style="margin-right:20px;visibility:hidden" id="save_edit_email_campaign" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_email_campaign" class="state_details">{t}Reset{/t}</span>
</div>

<table class="edit" style="clear:both;width:100%" border=0  >
<input type="hidden" id="store_id" value="{$email_campaign->get('Email Campaign Store Key')}">
<input type="hidden" id="email_campaign_key" value="{$email_campaign->get('Email Campaign Key')}">
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objetive">{t}Invalid Campaign Objetive{/t}</span>
<span style="display:none" id="invalid_email_campaign_recipients">{t}Please add recipients{/t}</span>
<span style="display:none" id="invalid_email_campaign_scope">{t}Invalid Scope Syntax{/t}</span>
<span style="display:none" id="invalid_email_campaign_subjects">{t}Please add email subject{/t}</span>
<span style="display:none" id="invalid_email_campaign_contents">{t}Email content is empty{/t}</span>

<tr class="top">
<td class="label">{t}Store:{/t}</td>
<td>
{$store->get('Store Name')}

</td>
</tr>
<input id="email_campaign_number_recipients" type='hidden' value="{$email_campaign->get('Number of Emails')}" ovalue="{$email_campaign->get('Number of Emails')}"/>
<input id="email_campaign_subjects" type='hidden' value='{$email_campaign->get('Email Campaign Subjects')|escape}' ovalue='{$email_campaign->get('Email Campaign Subjects')|escape}'/>
<input id="email_campaign_contents" type='hidden' value='{$email_campaign->get('Email Campaign Contents')|escape}' ovalue='{$email_campaign->get('Email Campaign Contents')|escape}'/>

<tr class=""><td style=";width:160px" class="label" >{t}Campaign Name{/t}:</td>
   <td  style="text-align:left;">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;width:420px" id="email_campaign_name" value="{$email_campaign->get('Email Campaign Name')|escape}" ovalue="{$email_campaign->get('Email Campaign Name')|escape}" >
       <div id="email_campaign_name_Container" style="" ></div>
     </div>
   </td>
   <td style="width:300px"id="email_campaign_name_msg" class="edit_td_alert"></td>
 </tr>

<tr class="last"><td style="" class="label" >{t}Campaign Objetive{/t}:</td>
   <td  style="text-align:left;">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;width:420px" id="email_campaign_objetive" value="{$email_campaign->get('Email Campaign Objective')|escape}" ovalue="{$email_campaign->get('Email Campaign Objective')|escape}" >
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
    <div id="recipients_preview_msg" style="visibility:hidden;position:relative;left:-10px;padding:5px 0 0 0;border:1px solid #ccc;font-size:80%">
    
    <div>

</td>
</tr>
<tr class="last" style="height:40px">
<td style="" class="label" >{t}Advertising Object{/t}:</td>
<td >

     <div  style="position:relative;top:00px" >
       <input style="text-align:left;;width:420px" id="email_campaign_scope" value="{$email_campaign->get('Email Campaign Scope')|escape}" ovalue="{$email_campaign->get('Email Campaign Scope')|escape}" >
       <div id="email_campaign_scope_Container" style="" ></div>
     </div>
 
   <div style="width:300px"id="email_campaign_scope_msg" class="edit_td_alert"></div
</td>
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
<span  style="margin-right:10px;"  id="select_text_email" class="small_button {if $email_campaign->get('Email Campaign Content Type')=='Plain'}selected{/if}" >{t}Text Email{/t}</span>
<span style="margin-right:10px;" id="select_html_from_template_email" class="small_button {if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}selected{/if}" >{t}Template Email{/t}</span>
<span style="" id="select_html_email" class="small_button {if $email_campaign->get('Email Campaign Content Type')=='HTML'}selected{/if}" >{t}HTML Email{/t}</span>

</div>

</td>
</tr>

<tbody id="text_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='Plain'}display:none{/if}">
<tr class=""  >

<td style="" class="label" >{t}Subject{/t}:</td>
   <td  style="text-align:left;">
     <div  style="position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="email_campaign_subject" value='{$email_campaign->get_subject()|escape}' ovalue="{$email_campaign->get_subject()|escape}" >
       <div id="email_campaign_subject_Container" style="" ></div>
     </div>
   </td>
   <td id="email_campaign_subject_msg" class="edit_td_alert"></td>
 </tr>
<tr>
<td style="" class="label" >{t}Content{/t}:</td>
   <td  colspan=2 style="text-align:left;">
   <div  style="position:relative;top:00px" >                                                     
   <textarea style="width:600px;height:250px;background-image:url(art/text_email_guide.png);" id="email_campaign_content_text" ovalue="{$email_campaign->get_content_text()|escape}">{$email_campaign->get_content_text()|escape}</textarea>
    <div id="email_campaign_content_text_Container" style="" ></div>
     </div>
    <div id="email_campaign_content_text_msg" class="edit_td_alert"></div>
   </td>
 
 </tr>
</tbody>
<tbody id="html_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML Template'}display:none{/if}">

<tr><td>{t}Template{/t}</td><td>
<div class="general_options" >

<span style="float:none;margin:0px">{t}Choose Template{/t}</span>
</div>
</td>
</tr>

<tr>
<td></td>
<td>

<iframe onLoad="changeHeight(this);" id="template_email_iframe" src="email_template.php?email_campaign_key={$email_campaign->id}&email_content_key={$email_campaign->get_first_content_key()}" frameborder=0 width=700 >
<p>Your browser does not support iframes.</p>
</iframe>

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
 
 
 <div id="dialog_preview_text_email" style="padding:10px 20px">

<table border=0 style="width:600px">
<tr style="xborder-bottom:1px solid #ccc;"><td style="width:60px"><span style=";margin-left:5px"><b>{t}Subject{/t}:</b></span></td><td>{$email_campaign->get_subject()}</td></tr>
<tr><td colspan=2><div style="min-height: 200px;border:1px solid #ccc;padding:5px">{$email_campaign->get_content_text()}</div></td></tr>
</table>


 </div>

 
