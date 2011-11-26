{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<input type="hidden" id="email_content_key" value="{$current_content_key}"/>
<input type="hidden" id="store_id" value="{$email_campaign->get('Email Campaign Store Key')}">
<input type="hidden" id="store_key" value="{$email_campaign->get('Email Campaign Store Key')}">

<input type="hidden" id="email_campaign_key" value="{$email_campaign->get('Email Campaign Key')}">


<div class="branch"> 
  <span>{if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}&block_view=email">{$store->get('Store Code')} {t}Marketing{/t} ({t}Email Campaigns{/t})</a> &rarr; {$email_campaign->get('Email Campaign Name')}</span>
</div>


<div class="top_page_menu">

<div class="buttons" style="width:100%">
	<button   class="{if !$email_campaign->ready_to_send()}disabled{/if} positive"  id="send_email_campaign">{t}Send{/t}</button>
		<button  class="{if !$email_campaign->ready_to_send()}disabled{/if}"   id="preview_email_campaign" >{t}Preview{/t}</button>
			<button style="margin-left:20px" id="delete_email_campaign" class="negative">{t}Delete{/t}</button>



	
</div>
<div style="clear:both"></div>
</div>

<h1>{t}Email Campaign{/t}: <span id="h1_email_campaign_name">{$email_campaign->get('Email Campaign Name')}</span></h1>

<div class="margin-bottom:0px" >
<div  style="display:none;padding:10px;float:left" id="edit_email_campaign_msg">
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objetive">{t}Invalid Campaign Objetive{/t}</span>
<span style="display:none" id="invalid_email_campaign_recipients">{t}Please add recipients{/t}</span>
<span style="display:none" id="invalid_email_campaign_scope">{t}Invalid Scope Syntax{/t}</span>
<span style="display:none" id="invalid_email_campaign_subjects">{t}Please add email subject{/t}</span>
<span style="display:none" id="invalid_email_campaign_contents">{t}Email content is empty{/t}</span>
</div>
</div>




<table class="edit" style="clear:both;width:100%;margin-top:10px" border=0  >



<input id="email_campaign_number_recipients" type='hidden' value="{$email_campaign->get('Number of Emails')}" ovalue="{$email_campaign->get('Number of Emails')}"/>
<input id="email_campaign_subjects" type='hidden' value='{$email_campaign->get('Email Campaign Subjects')|escape}' ovalue='{$email_campaign->get('Email Campaign Subjects')|escape}'/>
<input id="email_campaign_contents" type='hidden' value='{$email_campaign->get('Email Campaign Contents')|escape}' ovalue='{$email_campaign->get('Email Campaign Contents')|escape}'/>


<tbody >


<tr class="top">


<tr>
<td><h2>{t}Mailing List{/t}</h2></td>
<td  colspan=2  >
    <div class="buttons" >
    	    <button   id="add_email_address_from_customer_list" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Import from Customer List{/t}</button>

		<button id="add_email_address_manually" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Individual Email Address{/t}</button>
		<button id="import_email_address" ><img src="art/icons/add.png" alt="{t}Add{/t}"/> {t}Import from File{/t}</button>
	     <a id="add_email_address_manually" href="email_campaign_mailing_list.php?id={$email_campaign->id}">{t}Edit Mailing List{/t}</a>

	    </div>
    <div id="recipients_preview_msg" style="visibility:hidden;position:relative;left:-10px;padding:5px 0 0 0;border:1px solid #ccc;font-size:80%">
    
    </div>

</td>
</tr>


<tr>

<td colspan=3><span id="recipients_preview">{$email_campaign->get('Email Campaign Recipients Preview')}</span></td>

</tr>

 <tr class="last" style="height:20px"><td colspan=3></td></tr>
 <tr style="height:10px"><td colspan=3></td></tr>

 <tr   >

<td   ><h2 style="padding:0;margin:0">{t}Subject{/t}: </h2>	      
</td>
   <td  style="text-align:left;width:400px">
     <div   >
       <input style="text-align:left;width:370px" id="email_campaign_subject" value='{$email_campaign->get_subject($current_content_key)|escape}' ovalue="{$email_campaign->get_subject($current_content_key)|escape}" >
       <div id="email_campaign_subject_Container"  ></div>
     </div>
   </td>
   <td>
  <div style="float:left;width:180px" id="email_campaign_subject_msg" class="edit_td_alert"></div>
   
   <div class="buttons" style="float:right;width:160px;position:relative;top:-5px">
     <button style="visibility:hidden" id="save_edit_email_campaign" class="positive">{t}Save{/t}</button>
	    <button style="visibility:hidden" id="reset_edit_email_campaign" >{t}Reset{/t}</button>
    </div>
   
   
   
   </td>
 </tr>
 
 
 



<tr class="last" style="height:10px"><td colspan=3></td></tr>


<tr id="add_objetive_tr" >
<td   >
<h2>{t}Objetives{/t}:</h2>
</td>
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
<td colspan=3>

<div style="width:100%>
{include file='table_splinter.tpl' table_id=9 filter_name=$filter_name9 filter_value=$filter_value9 no_filter=1}
<div  id="table9"   class="data_table_container dtable btable" style="font-size:80%"> </div>
</div>
</td>
</tr>
<tr class="last" style="height:10px"><td colspan=3></td></tr>

<input type="hidden" value="{$current_content_key}" id="current_email_contact_key">



<tr>



</tbody>


<tbody id="text_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='Plain'}display:none{/if}">


<tr>
<td colspan=2  ><h2>{t}Plain Email{/t}</h2></td>
<td>
<div class="buttons" id="change_template_buttons" style="padding-top:2px">
<button  id="change_type1" >{t}Change Email Type{/t}</button>
<button style="visibility:hidden" id="save_edit_email_content_text" class="positive">{t}Save{/t}</button>
<button style="visibility:hidden" id="reset_edit_email_content_text" >{t}Reset{/t}</button>

</div>
</td>
</tr>

<tr id="tr_content" style="border-top:1px solid #eee;{if $current_content_key!=$current_content_key}display:none{/if}">
<td class="label" ><div id="email_campaign_content_text_msg" class="edit_td_alert"></div>
    <div id="html_email_editor_msg" class="edit_td_alert"></div></td>
   <td  colspan=2 style="text-align:">
   <div  style="top:00px;width:600px;margin:0px;height:260px;margin-top:5px" >                                                     
   <textarea style="width:100%;height:250px;background-image:url(art/text_email_guide.png);" id="email_campaign_content_text" ovalue="{$email_campaign->get_content_text($current_content_key)|escape}">{$email_campaign->get_content_text($current_content_key)|escape}</textarea>
    <br>
    <div id="email_campaign_content_text_Container"  ></div>
     </div>
   
   </td>
</tr>


</tbody>

<tbody id="html_email_from_template_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML Template'}display:none{/if}">

<tr>
<td colspan=2  ><h2>{t}Template Email{/t}</h2></td>
<td>
<div class="buttons" id="change_template_buttons" style="padding-top:2px">
<button  id="change_type2" >{t}Change Email Type{/t}</button>
</div>
</td>
</tr>

<tr id="change_template_buttons_tr">

<td colspan=3 style="border-bottom:1px solid #eee;border-top:1px solid #ccc;">
<div class="buttons left" id="change_template_buttons">
<button class="selected change_template_buttons" id="change_template_content" ><img  src="art/icons/email_edit.png" alt=""/> {t}Edit Content{/t}</button>

<button class="change_template_buttons" id="change_template_layout" ><img  src="art/icons/images.png" alt=""/> {t}Template Layout{/t}</button>
<button class="change_template_buttons" id="change_template_color_scheme" ><img src="art/icons/color_swatch.png" alt=""/> {t}Color Scheme{/t}</button>
<button class="change_template_buttons" id="change_template_header_image"><img  src="art/icons/header.png" alt=""/> {t}Header Image{/t}</button>
<button {if $current_template_type!='Postcard'}style="display:none"{/if}   class="change_template_buttons" id="change_postcard"><img  src="art/icons/postcard.png" alt=""/> {t}Postcards{/t}</button>



</div>
</td>
</tr>



<tr style="display:none" id="change_postcard_tr">
<td colspan=3>
<div class="buttons">
<button  id="new_postcard" ><img src="art/icons/add.png" alt="{t}New Postcard{/t}" title="{t}New Postcard{/t}"/> {t}Postcard{/t}</button>

</div>

<div id="color_schemes" class="data_table"  style="margin-top:10px;clear:both">
<span id="table_title" class="clean_table_title">{t}Postcards{/t}</span> 
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

{include file='table_splinter.tpl' table_id=12 filter_name=$filter_name12 filter_value=$filter_value12 no_filter=1}
<div  id="table12"   class="data_table_container dtable btable" style="font-size:80%"> </div>
</div>

</td>
</tr>


<tr style="display:none" id="change_template_header_image_tr">
<td colspan=3>
<div class="buttons">
<button  id="new_template_header_image" ><img src="art/icons/add.png" alt="{t}New Header Image{/t}" title="{t}New Header Image{/t}"/> {t}Header Image{/t}</button>

</div>

<div id="color_schemes" class="data_table"  style="margin-top:10px;clear:both">
<span id="table_title" class="clean_table_title">{t}Header Images{/t}</span> 
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

{include file='table_splinter.tpl' table_id=11 filter_name=$filter_name11 filter_value=$filter_value11 no_filter=1}
<div  id="table11"   class="data_table_container dtable btable" style="font-size:80%"> </div>
</div>

</td>
</tr>




<tr style="display:none" id="change_template_layout_tr">
<td></td>
<td colspan=2>




<p style="display:none">
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


<tr style="display:none" id="change_template_header_image_tr">
<td colspan=3>
<div class="buttons">
<button  id="new_template_header_image" ><img src="art/icons/add.png" alt="{t}New Header Image{/t}" title="{t}New Header Image{/t}"/> {t}Header Image{/t}</button>

</div>

<div id="color_schemes" class="data_table"  style="margin-top:10px;clear:both">
<span id="table_title" class="clean_table_title">{t}Header Images{/t}</span> 
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

{include file='table_splinter.tpl' table_id=11 filter_name=$filter_name11 filter_value=$filter_value11 no_filter=1}
<div  id="table11"   class="data_table_container dtable btable" style="font-size:80%"> </div>
</div>

</td>
</tr>
<tr style="display:none" id="change_template_color_scheme_tr">

<td colspan=3>

<div class="buttons">
<button  id="new_color_scheme" ><img src="art/icons/add.png" alt="{t}New Color Schema{/t}" title="{t}New Color Schema{/t}"/> {t}Color Scheme{/t}</button>
<button style="display:none" id="close_color_scheme_view_details" ><img src="art/icons/text_list_bullets.png" alt="{t}Color Scheme List{/t}"  title="{t}Color Scheme List{/t}" /> {t}Color Scheme List{/t}</button>

</div>

<div id="color_schemes" class="data_table"  style="margin-top:10px;clear:both">
<span id="table_title" class="clean_table_title">{t}Color Schemes{/t}</span> 
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

{include file='table_splinter.tpl' table_id=10 filter_name=$filter_name10 filter_value=$filter_value10 no_filter=1}
<div  id="table10"   class="data_table_container dtable btable" style="font-size:80%"> </div>
</div>

<table id="color_scheme_details" class="color_scheme" border=0 style="width:100%;display:none">
<tr><td  style="padding:5px 0" colspan=3>
<h2 style="width:100%;padding-left:10px "id="color_scheme_details_name"></h2>
</td></tr>





<tr>
  <input type="hidden" id="color_edit_scheme_key" value=""/>

<td style="padding:0px" colspan="3">

<iframe onLoad="changeHeight(this);" id="color_scheme_template_email_iframe" src="email_template.php?email_campaign_key={$email_campaign->id}&email_content_key={$current_content_key}" frameborder=0 style="width:700px;height:100px;float:right" >
<p>{t}Your browser does not support iframes{/t}.</p>
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

<tbody>
<tr style="height:30px"><td></td></tr>
<tr>
<td>
<div class="buttons left">
<button class="positive"  id="color_scheme_use_this" onClick="save_select_color_scheme_from_button()" >{t}Use Scheme{/t}</button><br/>
<button style="margin-top:10px" id="reset_default_color_scheme_values" >{t}Original Colours{/t}</button><br/>
<button style="margin-top:10px" id="delete_scheme" class="negative" >{t}Delete Scheme{/t}</button><br/>

</div>
</td>
</tr>

</table>
</td>
</tr>

</table>


</td>

</tr>







<tr  id="template_editor_tr">
<td></td>
<td colspan=2>

<iframe onLoad="changeHeight(this);" id="template_email_iframe" src="email_template.php?edit=1&email_campaign_key={$email_campaign->id}&email_content_key={$current_content_key}" frameborder=0 style="width:700px;height:100px" >
<p>Your browser does not support iframes.</p>
</iframe>

</td>
</tr>



</tbody>

<tbody id="html_email_fields" style="{if $email_campaign->get('Email Campaign Content Type')!='HTML'}display:none{/if}">

<tr>
<td colspan=2  ><h2>{t}HTML Email{/t}</h2></td>
<td>
<div class="buttons" id="change_template_buttons" style="padding-top:2px">
<button  id="change_type3" >{t}Change Email Type{/t}</button>
<button style="visibility:hidden" id="save_edit_email_content_html" class="positive">{t}Save{/t}</button>
<button style="visibility:hidden" id="reset_edit_email_content_html" >{t}Reset{/t}</button>

</div>
</td>
</tr>

<tr style="border-top:1px solid #eee;">
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
<div id="dialog_edit_objective" style="padding:20px 10px 5px 10px;width:320px">
<input type="hidden" id="objetive_key" value=""/>
<input type="hidden" id="objetive_term" value=""/>
<input type="hidden" id="objetive_time_limit_in_seconds" value=""/>

 <table>
<tbody>
<tr><td>
{t}Objetive term{/t}:
</td>
</tr>

<tr><td>
<div class="buttons left" id="objetive_terms">
<button class="objetive_term" id="objetive_term_Order">{t}Order{/t}</button>
<button class="objetive_term" id="objetive_term_Buy">{t}Buy{/t}</button>
<button class="objetive_term" id="objetive_term_Visit">{t}Visit{/t}</button>
<button class="objetive_term" id="objetive_term_Use">{t}Use{/t}</button>

</div>
</td></tr>
<tr><td>
{t}Goal completed if objective accomplished within this interval since email was send{/t}.
</td>
</tr>
<tr><td>
<input id="objetive_time_limit" value="" style="width:100%" />
</td>
</tr>
<tr id="objetive_time_wrong_interval_tr" style="display:none" >
<td style="color:#d12f19;font-size:80%;font-style:italic">
{t}Invalid interval, try 5 days or 1 week{/t}
</td>
</tr>
<tr id="objetive_time_parsed_interval_tr" style="visibility:hidden">
<td  style="color:#666;font-size:80%;font-style:italic">
<span id="objetive_time_parsed_interval"></span>
</td>
</tr>


</tbody>

 <tr><td colspan=2>
  <div class="buttons" style="margin-top:0px">
<button class="positive"  id="save_edit_objetive"  >{t}Save{/t}</button>
<button  id="close_edit_objetive" class="negative" >{t}Cancel{/t}</button><br/>

</div>
  </td></tr>

    </table>
 



</div>
<div id="dialog_upload_postcard" style="padding:20px 10px 10px 10px;width:320px">

 <table>
  <form enctype="multipart/form-data" method="post" id="upload_postcard_form">
<input type="hidden" name="store_key" value="{$store->id}" />
 <tr><td>{t}Image{/t}:</td><td><input id="upload_postcard_file" style="border:1px solid #ddd;" type="file" name="image"/></td></tr>
  <tr><td>{t}Name{/t}:</td><td><input id="upload_postcard_name" style="border:1px solid #ddd;width:100%" name="name"/></td></tr>
  </form>
 <tr><td colspan=2>
  <div class="buttons">
<button class="positive"  id="upload_postcard"  >{t}Upload{/t}</button>
<button  id="cancel_upload_postcard" class="negative" >{t}Cancel{/t}</button><br/>

</div>
  </td></tr>

    </table>
 



</div>
<div id="dialog_change_email_type" style="padding:20px 10px 10px 10px;width:420px">

 <table  border=0 style="margin:auto">
<tr>
<td>
<div class="warning" style="padding:10px"><b>{t}Warning{/t}</b> {t}The email layout maybe be lost when changing the email type{/t}.</div>

</td>
</tr>


 <tr  style="height:40px">



<td >

<div class="buttons left">
<button  id="select_text_email" class="{if $email_campaign->get('Email Campaign Content Type')=='Plain'}selected{/if}" ><img src="art/icons/script.png" alt=""/> {t}Text Email{/t}</button>
<button  id="select_html_from_template_email" class="{if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}selected{/if}" ><img src="art/icons/layout.png" alt=""/> {t}Template Email{/t}{if $email_campaign->get('Email Campaign Content Type')=='HTML Template'}<img class="selected" src="art/icons/accept.png"/>{/if}</button>
<button  id="select_html_email" class="{if $email_campaign->get('Email Campaign Content Type')=='HTML'}selected{/if}" ><img src="art/icons/html.png" alt=""/> {t}HTML Email{/t}</button>
</div>

</td>
</tr>

    </table>
</div>
<div id="dialog_upload_header_image" style="padding:20px 10px 10px 10px;width:320px">

 <table>
  <form enctype="multipart/form-data" method="post" id="upload_header_image_form">
<input type="hidden" name="store_key" value="{$store->id}" />
 <tr><td>{t}Image{/t}:</td><td><input id="upload_header_image_file" style="border:1px solid #ddd;" type="file" name="image"/></td></tr>
  <tr><td>{t}Name{/t}:</td><td><input id="upload_header_image_name" style="border:1px solid #ddd;width:100%" name="name"/></td></tr>

  </form>
 <tr><td colspan=2>
  <div class="buttons">
<button class="positive"  id="upload_header_image"  >{t}Upload{/t}</button>
<button  id="cancel_upload_header_image" class="negative" >{t}Cancel{/t}</button><br/>
</div>
  </td></tr>
    </table>
</div>
<div id="dialog_send_email_campaign"  style="padding:20px 10px 10px 10px;width:280px">
    <table style="padding:10px;margin:10px" border=0 >
        <tbody id="dialog_send_email_campaign_choose_when1">
            <tr>
                <td>
                    <div class="buttons">
                        <button  onclick="send_now()" class="positive" >{t}Send Now{/t}</button>
                        <button onclick="choose_time()" >{t}Send Later (set when){/t}</button>
                    </div>
                </td>
            </tr>
        </tbody>
        <tbody id="other_time_form" style="xdisplay:none">
            <tr>
                <td>When you want to send the emails</td>
            </tr>
            <tr>
                <td> <input id="end_email_campaign_datetime" style="width:100%"></td>
            </tr>
            <tr>
                <td id="time_tag"></td>
            </tr>
            <tr>
                <td>
                    <div class="buttons">
                        <button  class="disabled"  onclick="send_other_time()" >{t}Send{/t}</button>
                        <button  class="negative"  id="cancel_set_sending_time" >{t}Cancel{/t}</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div id="dialog_add_email_address_from_list">

<div class="splinter_cell" style="width:600px;padding:5px 15px 10px 0;border:none">

<div class="buttons">
 <button  onclick="window.location='new_customers_list.php?store={$email_campaign->get('Email Campaign Store Key')}&gbt=ecip&gbtk={$email_campaign->id}'" ><img src="art/icons/add.png" alt=""> {t}Create List{/t}</button>
</div>


<div  class="data_table" style="clear:both" >
 <span class="clean_table_title">{t}Customer Lists{/t}</span>

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
<div id="dialog_edit_color" style="padding-right:10px;width:360px;height:230px">
 <input type="hidden" id="color_edit_element" value=""/>

  <div style="position:relative;top:200px" class="buttons">
    <button id="save_color" class="positive">{t}Save{/t}</button>
  <button id="close_edit_color_dialog" class="negative">{t}Cancel{/t}</button>

 
 </div>
 <div id="edit_color" style="margin-top:20px;padding-top:20px;"></div>

</div>

{include file='footer.tpl'}




