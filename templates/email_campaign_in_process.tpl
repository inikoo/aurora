{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<div> 
  <span class="branch">{if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}&block_view=email">{$store->get('Store Code')} {t}Marketing{/t} ({t}Email Campaigns{/t})</a> &rarr; {$email_campaign->get('Email Campaign Name')}</span>
</div>



<div style="clear:left;margin:0 0px">
<h1>{t}Email Campaign{/t}: <span id="h1_email_campaign_name">{$email_campaign->get('Email Campaign Name')}</span></h1>


<div class="top_row" >
<div  style="padding:10px;float:left" id="edit_email_campaign_msg">
</div>
<div class="general_options" style="padding:10px;float:right">
	<span  style="margin-right:10px;" class="{if !$email_campaign->ready_to_send()}disabled{/if}"  id="send_email_campaign" class="state_details">{t}Send{/t}</span>
		<span  style="margin-right:10px;" class="{if !$email_campaign->ready_to_send()}disabled{/if}"   id="preview_email_campaign" class="state_details">{t}Preview{/t}</span>
	
	<span style="margin-right:10px;" id="save_and_exit_edit_email_campaign" class="state_details">{t}Continue Later{/t}</span>
		<span style="margin-right:10px;" id="delete_email_campaign" class="state_details">{t}Delete{/t}</span>

<span style="margin-right:20px;visibility:hidden" id="save_edit_email_campaign" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_email_campaign" class="state_details">{t}Reset{/t}</span>
</div>
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objetive">{t}Invalid Campaign Objetive{/t}</span>
<span style="display:none" id="invalid_email_campaign_recipients">{t}Please add recipients{/t}</span>
<span style="display:none" id="invalid_email_campaign_scope">{t}Invalid Scope Syntax{/t}</span>
<span style="display:none" id="invalid_email_campaign_subjects">{t}Please add email subject{/t}</span>
<span style="display:none" id="invalid_email_campaign_contents">{t}Email content is empty{/t}</span>

<table class="edit" style="clear:both;width:890px" border=0  >
<input type="hidden" id="store_id" value="{$email_campaign->get('Email Campaign Store Key')}">
<input type="hidden" id="email_campaign_key" value="{$email_campaign->get('Email Campaign Key')}">

<tr class="top">
<td style="width:130px" class="label">{t}Store:{/t}</td>
<td  >
{$store->get('Store Name')}
</td>
<td  style="width:290px">
</td>
</tr>

<input id="email_campaign_number_recipients" type='hidden' value="{$email_campaign->get('Number of Emails')}" ovalue="{$email_campaign->get('Number of Emails')}"/>
<input id="email_campaign_subjects" type='hidden' value='{$email_campaign->get('Email Campaign Subjects')|escape}' ovalue='{$email_campaign->get('Email Campaign Subjects')|escape}'/>
<input id="email_campaign_contents" type='hidden' value='{$email_campaign->get('Email Campaign Contents')|escape}' ovalue='{$email_campaign->get('Email Campaign Contents')|escape}'/>

<tr class="">
<td class="label" >{t}Campaign Name{/t}:</td>
   <td  style="text-align:left;">
     <div  style="" >
       <input style="text-align:left;width:100%" id="email_campaign_name" value="{$email_campaign->get('Email Campaign Name')|escape}" ovalue="{$email_campaign->get('Email Campaign Name')|escape}" >
       <div id="email_campaign_name_Container" style="" ></div>
     </div>
   </td>
   <td id="email_campaign_name_msg" class="edit_td_alert"></td>
 </tr>
<tbody style="">

<tr class="last"><td style="" class="label" >{t}Campaign Objetive{/t}:</td>
   <td  style="text-align:left;">
     <div  style="" >
       <input style="text-align:left;width:100%" id="email_campaign_objetive" value="{$email_campaign->get('Email Campaign Objective')|escape}" ovalue="{$email_campaign->get('Email Campaign Objective')|escape}" >
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
    
    </div>

</td>
</tr>
<tr class="last" style="height:40px">
<td style="" class="label" >{t}Advertising Object{/t}:</td>
<td >

     <div  style="" >
       <input style="text-align:left;width:100%" id="email_campaign_scope" value="{$email_campaign->get('Email Campaign Scope')|escape}" ovalue="{$email_campaign->get('Email Campaign Scope')|escape}" >
       <div id="email_campaign_scope_Container" style="" ></div>
     </div>
 
   <div id="email_campaign_scope_msg" class="edit_td_alert"></div>
</td>
<td>
<div class="general_options" >
	
	<span  style="margin-left:0px;;float:none"   id="department" class="state_details">{t}Department{/t}</span>
	<span style="margin-left:20px;float:none" id="family" class="state_details">{t}Family{/t}</span>
		<span style="margin-left:20px;float:none" id="product" class="state_details">{t}Product{/t}</span>
	<span style="margin-left:20px;float:none" id="offer" class="state_details">{t}Offer{/t}</span>

      </div>

</td>
</tr>

<input type="hidden" value="{$current_content_key}" id="current_email_contact_key">

<tr class=""  >

<td  class="label" >{t}Subject{/t}:</td>
   <td  style="text-align:left;">
     <div  style="" >
       <input style="text-align:left;width:100%" id="email_campaign_subject" value='{$email_campaign->get_subject($current_content_key)|escape}' ovalue="{$email_campaign->get_subject($current_content_key)|escape}" >
       <div id="email_campaign_subject_Container" style="" ></div>
     </div>
   </td>
   <td id="email_campaign_subject_msg" class="edit_td_alert"></td>
 </tr>

<tr>


<tr class="" style="height:40px">
<td style="" class="label" >{t}Type of Email{/t}:</td>
<td colspan=2 ><div style="margin-top:7px;font-size:100%">
<span  style="margin-right:10px;margin-left:0"  id="select_text_email" class="small_button {if $email_campaign->get('Email Campaign Content Type')=='Plain'}selected{/if}" >{t}Text Email{/t}</span>
<span style="margin-right:10px;" id="select_html_from_template_email" class="small_button {if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}selected{/if}" >{t}Template Email{/t}</span>
<span style="" id="select_html_email" class="small_button {if $email_campaign->get('Email Campaign Content Type')=='HTML'}selected{/if}" >{t}HTML Email{/t}</span>

</div>

</td>
</tr>
</tbody>


<tbody id="text_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='Plain'}display:none{/if}">






<tr id="tr_content" style="{if $current_content_key!=$current_content_key}display:none{/if}">
<td class="label" >{t}Content{/t}:<br/><div id="email_campaign_content_text_msg" class="edit_td_alert"></div>
    <div id="html_email_editor_msg" class="edit_td_alert"></div></td>
   <td  colspan=2 style="text-align:">
   <div  style="top:00px;width:600px;margin:0px;height:260px" >                                                     
   <textarea style="width:100%;height:250px;background-image:url(art/text_email_guide.png);" id="email_campaign_content_text" ovalue="{$email_campaign->get_content_text($current_content_key)|escape}">{$email_campaign->get_content_text($current_content_key)|escape}</textarea>
    <br>
    <div id="email_campaign_content_text_Container" style="" ></div>
     </div>
   
   </td>
</tr>


</tbody>
<tbody id="html_email_from_template_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML Template'}display:none{/if}">

<tr>
<td>{t}Template{/t}</td>
<td colspan=2>
<div class="general_options" >
<span    style="float:none;margin:0px">{t}Choose Template{/t}</span>
</div>
</td>
</tr>

<tr>
<td></td>
<td colspan="2">

<iframe onLoad="changeHeight(this);" id="template_email_iframe" src="email_template.php?edit=1&email_campaign_key={$email_campaign->id}&email_content_key={$email_campaign->get_first_content_key()}" frameborder=0 style="width:700px;height:100px" >
<p>Your browser does not support iframes.</p>
</iframe>

</td>
</tr>



</tbody>
<tbody id="html_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML'}display:none{/if}">

<tr>
<td></td>
<td colspan=2>

<textarea id="html_email_editor" ovalue="{$email_campaign->get_content_html($current_content_key)|escape}" rows="20" cols="75">{$email_campaign->get_content_html($current_content_key)|escape}</textarea>
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



<div id="dialog_send_email_campaign">
  
  <table style="padding:10px;margin:10px" border=0 >
 
  
   
   <tbody id="dialog_send_email_campaign_choose_when1">
  <tr> <td  class="label" colspan=2>{t}Send campaign{/t}</td>
</tr>
<tr>

   <td  onclick="send_now()" style="width:50%;cursor:pointer;text-align:center;border:1px solid #ccc;vertical-align:middle;padding:2px">
    {t}Now{/t} 
</td>

   <td onclick="choose_time()" style="cursor:pointer;text-align:center;border:1px solid #ccc;vertical-align:middle;padding:2px">
      {t}Choose time{/t} 
   </td>
 </tr>
 </tbody>
<tbody id="other_time_form" style="display:none">
<tr>
<td colspan=2>{t}Date{/t} {t}Time{/t}: <input id="end_email_campaign_datetime"></td>
</tr>
  <tr class="buttons" style="font-size:100%">
   <td><span  style="margin-left:0px;" style="display:none" id="time_tag"></span></td>
   <td colspan="2">
   <div class="general_options" style="padding:10px;float:right">
   
	<span  style="margin-right:0px;" class="disabled"  onclick="send_other_time()" class="state_details">{t}Send{/t}</span>
	
</div>
    </td>
</tbody>


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
<input type="hidden" value="1" id="preview_index">
<input type="hidden" value="{$email_campaign->get('Number of Emails')}" id="preview_number_of_views">

<div id="preview_navigation" style="{if $email_campaign->get('Number of Emails')<2}display:none;{/if}color:#777;text-align:right;margin-right:30px">
<span id="preview_formated_index">1</span>/<span id="preview_formated_number_of_views">{$email_campaign->get('Number of Emails')}</span>  <span style="margin-left:20px"id="previous_preview" class="state_details">{t}Previous{/t}</span> <span  style="margin-left:10px" id="next_preview" class="state_details">{t}Next{/t}</span>
</div>

<table border=0 style="width:700px">
<tr style="xborder-bottom:1px solid #ccc;"><td style="width:60px"><span  style=";margin-left:5px"><b>{t}To{/t}:</b></span></td><td id="preview_to"></td></tr>

<tr style="xborder-bottom:1px solid #ccc;"><td style="width:60px"><span style=";margin-left:5px"><b>{t}Subject{/t}:</b></span></td><td   id="preview_subject" ></td></tr>
<tr style="display:none" id="tr_preview_plain_body"><td colspan=2><div id="preview_plain_body" style="min-height: 200px;border:1px solid #ccc;padding:5px"></div></td></tr>
<tr style="display:none" id="tr_preview_template_body"><td colspan=2><iframe id="preview_html_body" onLoad="changeHeight(this);" src="email_template.php?email_campaign_key={$email_campaign->id}&email_content_key={$email_campaign->get_first_content_key()}" frameborder=0 style="width:700px;height:100px" ><p>Your browser does not support iframes.</p></iframe></td></tr>
<tr style="display:none" id="tr_preview_html_body"><td colspan=2><div id="preview_html_body" style="min-height: 200px;border:1px solid #ccc;padding:5px"></div></td></tr>

</table>


 </div>

 
  <div id="dialog_department_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Department List{/t}</span>
            {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5}
            <div  id="table5"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 

 
 
 <div id="dialog_family_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Family List{/t}</span>
            {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6}
            <div  id="table6"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_product_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Product List{/t}</span>
            {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7}
            <div  id="table7"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_offer_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Offer List{/t}</span>
            {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8}
            <div  id="table8"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
