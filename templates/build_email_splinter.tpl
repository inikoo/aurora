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

<tr id="tr_content" style="border-top:1px solid #eee;">
<td class="label" ><div id="email_campaign_content_text_msg" class="edit_td_alert"></div>
    <div id="html_email_editor_msg" class="edit_td_alert"></div></td>
   <td  colspan=2 style="text-align:">
   <div  style="top:00px;width:600px;margin:0px;height:260px;margin-top:5px" >                                                     
   <textarea style="width:100%;height:250px;background-image:url(art/text_email_guide.png);" id="email_campaign_content_text" ovalue="{$email_campaign->get_content_text($email_campaign->get_first_content_key())|escape}">{$email_campaign->get_content_text($email_campaign->get_first_content_key())|escape}</textarea>
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
<button {if $email_campaign->get_template_type($email_campaign->get_first_content_key())!='Postcard'}style="display:none"{/if}   class="change_template_buttons" id="change_postcard"><img  src="art/icons/postcard.png" alt=""/> {t}Postcards{/t}</button>



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
<button style="float:none;margin:5px auto" id="change_template_layout_basic"  {if $email_campaign->get_template_type($email_campaign->get_first_content_key())=='Basic'}class="selected"{/if}  > {t}Basic{/t}<img id="selected_template_layout_basic" style="{if $email_campaign->get_template_type($email_campaign->get_first_content_key())!='Basic'}display:none{/if}" class="selected" src="art/icons/accept.png"/></button>
</div>


<div style="margin-left:15px;float:left;text-align:center" class="buttons left">
<img src="art/right_column.gif" alt="{t}Right Column{/t}" title="{t}Right Column{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_right_column" {if $email_campaign->get_template_type($email_campaign->get_first_content_key())=='Right Column'}class="selected"{/if}> {t}Right Column{/t}<img id="selected_template_layout_right_column" style="{if $email_campaign->get_template_type($email_campaign->get_first_content_key())!='Right Column'}display:none{/if}" class="selected" src="art/icons/accept.png"/></button>
</div>


<div style="margin-left:15px;float:left;text-align:center" class="buttons left">
<img src="art/left_column.gif" alt="{t}Left Column{/t}" title="{t}Left Column{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_left_column" {if $email_campaign->get_template_type($email_campaign->get_first_content_key())=='Left Column'}class="selected"{/if}> {t}Left Column{/t}<img id="selected_template_layout_left_column" style="{if $email_campaign->get_template_type($email_campaign->get_first_content_key())!='Left Column'}display:none{/if}" class="selected" src="art/icons/accept.png"/></button>
</div>

<div style="margin-left:15px;float:left;text-align:center" class="buttons left">

<img  src="art/postcard.gif" alt="{t}Postcard{/t}" title="{t}Postcard{/t}" />
<br/>
<button style="float:none;margin:5px auto" id="change_template_layout_postcard" {if $email_campaign->get_template_type($email_campaign->get_first_content_key())=='Postcard'}class="selected"{/if}> {t}Postcard{/t}<img id="selected_template_layout_postcard" {if $email_campaign->get_template_type($email_campaign->get_first_content_key())!='Postcard'}style="display:none"{/if} class="selected" src="art/icons/accept.png"/></button>
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

<iframe onLoad="changeHeight(this);" id="color_scheme_template_email_iframe" src="email_template.php?email_campaign_key={$email_campaign->id}&email_content_key={$email_campaign->get_first_content_key()}" frameborder=0 style="width:700px;height:100px;float:right" >
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

<iframe onLoad="changeHeight(this);" id="template_email_iframe" src="email_template.php?edit=1&email_campaign_key={$email_campaign->id}&email_content_key={$email_campaign->get_first_content_key()}" frameborder=0 style="width:700px;height:100px" >
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

<textarea id="html_email_editor" ovalue="{$email_campaign->get_content_html($email_campaign->get_first_content_key())|escape}" rows="20" cols="75">{$email_campaign->get_content_html($email_campaign->get_first_content_key())|escape}</textarea>
</form>
</td>
</tr>

</tbody>
