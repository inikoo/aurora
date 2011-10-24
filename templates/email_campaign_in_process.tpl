{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<input type="hidden" id="email_content_key" value="{$current_content_key}"/>
<input type="hidden" id="store_id" value="{$email_campaign->get('Email Campaign Store Key')}">
<input type="hidden" id="email_campaign_key" value="{$email_campaign->get('Email Campaign Key')}">


<div> 
  <span class="branch">{if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}&block_view=email">{$store->get('Store Code')} {t}Marketing{/t} ({t}Email Campaigns{/t})</a> &rarr; {$email_campaign->get('Email Campaign Name')}</span>
</div>



<div style="clear:left;margin:0 0px">
<h1>{t}Email Campaign{/t}: <span id="h1_email_campaign_name">{$email_campaign->get('Email Campaign Name')}</span></h1>


<div class="margin-bottom:10px" >
<div  style="padding:10px;float:left" id="edit_email_campaign_msg">
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objetive">{t}Invalid Campaign Objetive{/t}</span>
<span style="display:none" id="invalid_email_campaign_recipients">{t}Please add recipients{/t}</span>
<span style="display:none" id="invalid_email_campaign_scope">{t}Invalid Scope Syntax{/t}</span>
<span style="display:none" id="invalid_email_campaign_subjects">{t}Please add email subject{/t}</span>
<span style="display:none" id="invalid_email_campaign_contents">{t}Email content is empty{/t}</span>
</div>
</div>

<div class="buttons" style="width:100%;">
	<button   class="{if !$email_campaign->ready_to_send()}disabled{/if} positive"  id="send_email_campaign">{t}Send{/t}</button>
		<button  class="{if !$email_campaign->ready_to_send()}disabled{/if}"   id="preview_email_campaign" >{t}Preview{/t}</button>

			<button style="margin-left:20px" id="delete_email_campaign" class="negative">{t}Delete{/t}</button>

	
	
	
<button style="visibility:hidden" id="save_edit_email_campaign" class="positive">{t}Save{/t}</button>
	<button style="visibility:hidden" id="reset_edit_email_campaign" >{t}Reset{/t}</button>

	<div style="clear:both"></div>
</div>





<table class="edit" style="clear:both;width:100%;margin-top:10px" border=0  >

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

<tr >
<td class="label" >{t}Campaign Name{/t}:</td>
   <td  style="text-align:left;">
     <div   >
       <input style="text-align:left;width:100%" id="email_campaign_name" value="{$email_campaign->get('Email Campaign Name')|escape}" ovalue="{$email_campaign->get('Email Campaign Name')|escape}" >
       <div id="email_campaign_name_Container"  ></div>
     </div>
   </td>
   <td id="email_campaign_name_msg" class="edit_td_alert"></td>
 </tr>
<tbody >

<tr><td  class="label" >{t}Campaign Objetive{/t}:</td>
   <td  style="text-align:left;">
      <div>
       <input style="text-align:left;width:100%" id="email_campaign_objetive" value="{$email_campaign->get('Email Campaign Objective')|escape}" ovalue="{$email_campaign->get('Email Campaign Objective')|escape}" >
       <div id="email_campaign_objetive_Container"  >
       </div>
     </div>
   </td>
   <td id="email_campaign_objetive_msg" class="edit_td_alert"></td>
 </tr>
 
<tr>

<tr class="last" style="height:10px"><td colspan=3></td></tr>


<td  class="label" >{t}Recipients{/t}:</td>
<td ><span id="recipients_preview">{$email_campaign->get('Email Campaign Recipients Preview')}</span></td>
<td>
    <div class="buttons" >

		<button id="add_email_address_manually" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Custom{/t}</button>
	    <button   id="add_email_address_from_customer_list" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}From List{/t}</button>
	        		<a id="add_email_address_manually" href="email_campaign_mailing_list.php?id={$email_campaign->id}">{t}Edit{/t}</a>

	    </div>
    <div id="recipients_preview_msg" style="visibility:hidden;position:relative;left:-10px;padding:5px 0 0 0;border:1px solid #ccc;font-size:80%">
    
    </div>

</td>
</tr>
<tr class="last" style="height:10px"><td colspan=3></td></tr>


<tr id="add_objetive_tr" >
<td  class="label" >{t}Objetives{/t}:</td>
<td colspan=2>


<div class="buttons" >
	
	<button  id="department"><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Department{/t}</button>
	<button id="family" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Family{/t}</button>
		<button id="product"><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Product{/t}</button>
	<button id="offer"><img src="art/icons/add.png" alt="{t}Add{/t}"/>  {t}Offer{/t}</button>

      </div>

</td>



</tr>



<tr>
<td ></td>
<td colspan=2>

<div style="width:100%>
{include file='table_splinter.tpl' table_id=9 filter_name=$filter_name9 filter_value=$filter_value9 no_filter=1}
<div  id="table9"   class="data_table_container dtable btable" style="font-size:80%"> </div>
</div>
</td>
</tr>
<tr class="last" style="height:10px"><td colspan=3></td></tr>

<input type="hidden" value="{$current_content_key}" id="current_email_contact_key">

<tr   >

<td  class="label" >{t}Subject{/t}:</td>
   <td  style="text-align:left;">
     <div   >
       <input style="text-align:left;width:100%" id="email_campaign_subject" value='{$email_campaign->get_subject($current_content_key)|escape}' ovalue="{$email_campaign->get_subject($current_content_key)|escape}" >
       <div id="email_campaign_subject_Container"  ></div>
     </div>
   </td>
   <td id="email_campaign_subject_msg" class="edit_td_alert"></td>
 </tr>

<tr>
<tr class="last" style="height:15px"><td colspan=3></td></tr>


<tr  style="height:40px">
<td  class="label" >{t}Type of Email{/t}:</td>
<td colspan=2 >

<div class="buttons left">
<button  id="select_text_email" class="{if $email_campaign->get('Email Campaign Content Type')=='Plain'}selected{/if}" ><img src="art/icons/script.png" alt=""/> {t}Text Email{/t}</button>
<button  id="select_html_from_template_email" class="{if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}selected{/if}" ><img src="art/icons/layout.png" alt=""/> {t}Template Email{/t}{if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}<img class="selected" src="art/icons/accept.png"/>{/if}</button>
<button  id="select_html_email" class="{if $email_campaign->get('Email Campaign Content Type')=='HTML'}selected{/if}" ><img src="art/icons/html.png" alt=""/> {t}HTML Email{/t}</button>

</div>

</td>
</tr>
</tbody>


<tbody id="text_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='Plain'}display:none{/if}">






<tr id="tr_content" style="{if $current_content_key!=$current_content_key}display:none{/if}">
<td class="label" ><div id="email_campaign_content_text_msg" class="edit_td_alert"></div>
    <div id="html_email_editor_msg" class="edit_td_alert"></div></td>
   <td  colspan=2 style="text-align:">
   <div  style="top:00px;width:600px;margin:0px;height:260px" >                                                     
   <textarea style="width:100%;height:250px;background-image:url(art/text_email_guide.png);" id="email_campaign_content_text" ovalue="{$email_campaign->get_content_text($current_content_key)|escape}">{$email_campaign->get_content_text($current_content_key)|escape}</textarea>
    <br>
    <div id="email_campaign_content_text_Container"  ></div>
     </div>
   
   </td>
</tr>


</tbody>
<tbody id="html_email_from_template_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML Template'}display:none{/if}">

<tr id="change_template_buttons">
<td></td>
<td colspan=2>
<div class="buttons left">
<button id="change_template_layout" ><img  src="art/icons/images.png" alt=""/> {t}Template Layout{/t}</button>
<button id="change_template_color_scheme" ><img src="art/icons/color_swatch.png" alt=""/> {t}Color Scheme{/t}</button>
<button id="change_template_header_image"><img  src="art/icons/layout_header.png" alt=""/> {t}Header Image{/t}</button>



</div>
</td>
</tr>

<tr style="display:none" id="change_template_layout_tr">
<td></td>
<td colspan=2>
<div class="buttons">
<button id="close_change_template_layout" ><img src="art/icons/arrow_left.png" alt=""/> {t}Go back to Edit Email{/t}</button>

</div>



<p>
{t}Choose which template layout you want to use{/t}. 
</p>
<div style="padding:10px 0">

<div style="float:left;text-align:center" class="buttons left">
<img  src="art/basic.gif" alt="{t}Basic{/t}" title="{t}Basic{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_basic"  {if $current_template_type=='Basic'}class="selected"{/if}  > {t}Basic{/t}<img id="selected_template_layout_basic" style="{if $current_template_type!='Basic'}display:none{/if}" class="selected" src="art/icons/accept.png"/></button>
</div>


<div style="margin-left:15px;float:left;text-align:center" class="buttons left">
<img src="art/right_column.gif" alt="{t}Right Column{/t}" title="{t}Right Column{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_right_column" {if $current_template_type=='Right Column'}class="selected"{/if}> {t}Right Column{/t}<img id="selected_template_layout_right_column" style="{if $current_template_type!='Right Column'}display:none{/if}" class="selected" src="art/icons/accept.png"/></button>
</div>


<div style="margin-left:15px;float:left;text-align:center" class="buttons left">
<img src="art/left_column.gif" alt="{t}Left Column{/t}" title="{t}Left Column{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_left_column" {if $current_template_type=='Left Column'}class="selected"{/if}> {t}Left Column{/t}<img id="selected_template_layout_left_column" style="{if $current_template_type!='Left Column'}display:none{/if}" class="selected" src="art/icons/accept.png"/></button>
</div>

<div style="margin-left:15px;float:left;text-align:center" class="buttons left">

<img  src="art/postcard.gif" alt="{t}Postcard{/t}" title="{t}Postcard{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_postcard" {if $current_template_type=='Postcard'}class="selected"{/if}> {t}Postcard{/t}<img id="selected_template_layout_postcard" {if $current_template_type!='Postcard'}style="display:none"{/if} class="selected" src="art/icons/accept.png"/></button>
</div>

</div>

</td>
</tr>




<tr style="xdisplay:none" id="change_template_color_scheme_tr">
<td></td>
<td colspan=2>
<div class="buttons">
<button id="close_change_template_color_scheme" ><img src="art/icons/arrow_left.png" alt=""/> {t}Go back to Edit Email{/t}</button>

</div>
<p>
{t}Choose color scheme for your email{/t}. 
</p>

<table  id="color_schemes" class="color_scheme" border=1 style="width:100%">
{foreach from=$color_schemes item=color_scheme }
<tr class="color_scheme" id="color_scheme_tr_{$color_scheme.Email_Template_Color_Scheme_Key}">
<td style="padding:2px 0;width:120px">{$color_scheme.Email_Template_Color_Scheme_Name}</td>
<td>

<span id="color_scheme_Background_Body_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Background_Body};" alt="{$color_scheme.Background_Body}" title="{t}Background{/t}"></span>
<span id="color_scheme_Background_Header_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Background_Header};" alt="{$color_scheme.Background_Header}" title="{t}Background Header{/t}"></span>
<span id="color_scheme_Text_Header_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="display:none;background-color:#{$color_scheme.Text_Header};" alt="{$color_scheme.Text_Header}" title="{t}Text Header{/t}"></span>
<span id="color_scheme_Link_Header_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="display:none;background-color:#{$color_scheme.Link_Header};" alt="{$color_scheme.Link_Header}" title="{t}Links Header{/t}"></span>

<span id="color_scheme_Background_Container_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Background_Container};" alt="{$color_scheme.Background_Container}" title="{t}Background Container{/t}"></span>
<span id="color_scheme_H1_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.H1};" alt="{$color_scheme.H1}" title="{t}Titles{/t}"></span>
<span id="color_scheme_H2_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.H2};" alt="{$color_scheme.H2}" title="{t}Subtitles{/t}"></span>
<span id="color_scheme_Text_Container_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Text_Container};" alt="{$color_scheme.Text_Container}" title="{t}Text{/t}"></span>
<span id="color_scheme_Link_Container_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Link_Container};" alt="{$color_scheme.Link_Container}" title="{t}Links{/t}"></span>

<span id="color_scheme_Background_Footer_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Background_Footer};" alt="{$color_scheme.Background_Footer}" title="{t}Footer{/t}"></span>
<span id="color_scheme_Text_Footer_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="background-color:#{$color_scheme.Text_Footer};" alt="{$color_scheme.Text_Footer}" title="{t}Text Footer{/t}"></span>
<span id="color_scheme_Link_Footer_{$color_scheme.Email_Template_Color_Scheme_Key}" class="swatch" style="display:none;background-color:#{$color_scheme.Link_Footer};" alt="{$color_scheme.Link_Footer}" title="{t}Links Footer{/t}"></span>

</td>
<td>
<div class="buttons">
<button  style="width:100px;{if $current_color_scheme!=$color_scheme.Email_Template_Color_Scheme_Key}display:none{/if}" id="color_scheme_in_use_{$color_scheme.Email_Template_Color_Scheme_Key}" class="selected">{t}Selected{/t}<img  class="selected" src="art/icons/accept.png"/></button>
<button style="width:100px;{if $current_color_scheme==$color_scheme.Email_Template_Color_Scheme_Key}display:none{/if}" id="color_scheme_use_this_{$color_scheme.Email_Template_Color_Scheme_Key}" onClick="color_scheme_use_this({$color_scheme.Email_Template_Color_Scheme_Key})" >{t}Use this{/t}</button>
<button id="color_scheme_view_details_{$color_scheme.Email_Template_Color_Scheme_Key}" onClick="color_scheme_view_details({$color_scheme.Email_Template_Color_Scheme_Key})">{t}View Details{/t}</button>
<button id="close_color_scheme_view_details_{$color_scheme.Email_Template_Color_Scheme_Key}" onClick="close_color_scheme_view_details({$color_scheme.Email_Template_Color_Scheme_Key})">Go Back</button>

</div>
</td>


</tr>
{/foreach}
<tr id="color_scheme_details">
  <input type="hidden" id="color_edit_scheme_key" value=""/>

<td style="padding:0px" colspan="3">

<iframe onLoad="changeHeight(this);" id="template_email_iframe" src="email_template.php?email_campaign_key={$email_campaign->id}&email_content_key={$current_content_key}" frameborder=0 style="width:600px;height:100px;float:right" >
<p>Your browser does not support iframes.</p>
</iframe>
<table style="width:150px;margin-top:10px;font-size:90%">
<tr><td>{t}Canvas{/t}</td></tr>
<tr><td><span id="color_scheme_Background_Body" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Background_Body};" alt="{$color_scheme.Background_Body}" title="{t}Canvas Background{/t}"></span> {t}Background{/t}</td></tr>
<tr style="height:10px"><td></td></tr>
<tr><td>{t}Header{/t}</td></tr>
<tr><td><span id="color_scheme_Background_Header" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Background_Header};" alt="{$color_scheme.Background_Header}" title="{t}Header Background{/t}"></span> {t}Background{/t}</td></tr>
<tr><td><span id="color_scheme_Text_Header" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Text_Header};" alt="{$color_scheme.Text_Header}" title="{t}Text Header{/t}"></span> {t}Text{/t}</td></tr>
<tr><td><span id="color_scheme_Link_Header" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Link_Header};" alt="{$color_scheme.Link_Header}" title="{t}Links Header{/t}"></span> {t}Links{/t}</td></tr>
<tr style="height:10px"><td></td></tr>
<tr><td>{t}Body{/t}</td></tr>
<tr><td><span id="color_scheme_Background_Container" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Background_Container};" alt="{$color_scheme.Background_Container}" title="{t}Body Background{/t}"></span> {t}Background{/t}</td></tr>
<tr><td><span id="color_scheme_H1" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.H1};" alt="{$color_scheme.H1}" title="{t}Text Container{/t}"></span> {t}Title{/t}</td></tr>
<tr><td><span id="color_scheme_H2" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.H2};" alt="{$color_scheme.H2}" title="{t}Links Container{/t}"></span> {t}Subtitle{/t}</td></tr>

<tr><td><span id="color_scheme_Text_Container" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Text_Container};" alt="{$color_scheme.Text_Container}" title="{t}Text Container{/t}"></span> {t}Text{/t}</td></tr>
<tr><td><span id="color_scheme_Link_Container" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Link_Container};" alt="{$color_scheme.Link_Container}" title="{t}Links Container{/t}"></span> {t}Links{/t}</td></tr>
<tr style="height:10px"><td></td></tr>
<tr><td>{t}Footer{/t}</td></tr>
<tr><td><span id="color_scheme_Background_Footer" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Background_Footer};" alt="{$color_scheme.Background_Footer}" title="{t}Footer Background{/t}"></span> {t}Background{/t}</td></tr>
<tr><td><span id="color_scheme_Text_Footer" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Text_Footer};" alt="{$color_scheme.Text_Footer}" title="{t}Text Footer{/t}"></span> {t}Text{/t}</td></tr>
<tr><td><span id="color_scheme_Link_Footer" class="swatch" style="cursor:pointer;background-color:#{$color_scheme.Link_Footer};" alt="{$color_scheme.Link_Footer}" title="{t}Links Footer{/t}"></span> {t}Links{/t}</td></tr>

</table>
</td>
</tr>

</table>


</td>

</tr>






<tr style="display:none" id="template_editor_tr">
<td></td>
<td >

<iframe onLoad="changeHeight(this);" id="template_email_iframe" src="email_template.php?edit=1&email_campaign_key={$email_campaign->id}&email_content_key={$current_content_key}" frameborder=0 style="width:700px;height:100px" >
<p>Your browser does not support iframes.</p>
</iframe>

</td>
</tr>

<tr id="change_template_color_scheme_tr"></tr>
<tr id="change_template_header_image_tr"></tr>


</tbody>
<tbody id="html_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML'}display:none{/if}">

<tr>
<td></td>
<td colspan=2>
  <form onsubmit="return false;">

<textarea id="html_email_editor" ovalue="{$email_campaign->get_content_html($current_content_key)|escape}" rows="20" cols="75">{$email_campaign->get_content_html($current_content_key)|escape}</textarea>
</form>
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

<div id="dialog_add_email_address" style="padding-top:10px">
  <div id="note_msg"></div>
  <div style="display:none" id="new_add_email_address_manually_invalid_msg">{t}Fill the form properly{/t}</div>
  <table style="padding:10px;margin:10px" border=0 >
 
  
   
   <tr >
   <td  class="label" >{t}Email Address{/t}:</td>
   <td  style="text-align:left">
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
 
   <td colspan="3">
   <div class="buttons" >
   	<button  class="disabled positive"  id="save_new_add_email_address_manually" >{t}Add{/t}</button>

   	<button  id="cancel_new_add_email_address_manually" class="negative">{t}Cancel{/t}</button>
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

 <div id="dialog_edit_color" style="padding-right:10px;width:360px;height:230px">
 <input type="hidden" id="color_edit_element" value=""/>

  <div style="position:relative;top:200px" class="buttons">
    <button id="save_color" class="positive">{t}Save{/t}</button>
  <button id="close_edit_color_dialog" class="negative">{t}Cancel{/t}</button>

 
 </div>
 <div id="edit_color" style="margin-top:20px;padding-top:20px;"></div>

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
<tr style="display:none" id="tr_preview_template_body"><td colspan=2><iframe id="preview_html_body" onLoad="changeHeight(this);" src="email_template.php?email_campaign_key={$email_campaign->id}&email_content_key={$current_content_key}" frameborder=0 style="width:700px;height:100px" ><p>Your browser does not support iframes.</p></iframe></td></tr>
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
 
 
