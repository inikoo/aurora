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

<div class="buttons" >
	<button   class="{if !$email_campaign->ready_to_send()}disabled{/if} positive"  id="send_email_campaign">{t}Send{/t}</button>
		<button  class="{if !$email_campaign->ready_to_send()}disabled{/if}"   id="preview_email_campaign" >{t}Preview{/t}</button>
			<button style="margin-left:20px" id="delete_email_campaign" class="negative">{t}Delete{/t}</button>



	
</div>
<div style="float:left">
<span class="main_title">{t}Email Campaign{/t}: <span class="id" id="h1_email_campaign_name">{$email_campaign->get('Email Campaign Name')}</span></span>

</div>
<div style="clear:both"></div>

</div>


<div class="margin-bottom:0px" >
<div  style="display:none;padding:10px;float:left" id="edit_email_campaign_msg">
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objective">{t}Invalid Campaign Objective{/t}</span>
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


<tr >


<tr>
<td><h2>{t}Mailing List{/t}:</h2></td>
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

<td colspan=3><div style="padding:10px;border:1px solid #ccc;" id="recipients_preview">{$email_campaign->get('Email Campaign Recipients Preview')}</div></td>

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


<tr id="add_objective_tr" >
<td   >
<h2>{t}Objectives{/t}:</h2>
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
{include file='build_email_splinter.tpl'}





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
<input type="hidden" id="objective_key" value=""/>
<input type="hidden" id="objective_term" value=""/>
<input type="hidden" id="objective_time_limit_in_seconds" value=""/>

 <table>
<tbody>
<tr><td>
{t}Objective term{/t}:
</td>
</tr>

<tr><td>
<div class="buttons left" id="objective_terms">
<button class="objective_term" id="objective_term_Order">{t}Order{/t}</button>
<button class="objective_term" id="objective_term_Buy">{t}Buy{/t}</button>
<button class="objective_term" id="objective_term_Visit">{t}Visit{/t}</button>
<button class="objective_term" id="objective_term_Use">{t}Use{/t}</button>

</div>
</td></tr>
<tr><td>
{t}Goal completed if objective accomplished within this interval since email was send{/t}.
</td>
</tr>
<tr><td>
<input id="objective_time_limit" value="" style="width:100%" />
</td>
</tr>
<tr id="objective_time_wrong_interval_tr" style="display:none" >
<td style="color:#d12f19;font-size:80%;font-style:italic">
{t}Invalid interval, try 5 days or 1 week{/t}
</td>
</tr>
<tr id="objective_time_parsed_interval_tr" style="visibility:hidden">
<td  style="color:#666;font-size:80%;font-style:italic">
<span id="objective_time_parsed_interval"></span>
</td>
</tr>


</tbody>

 <tr><td colspan=2>
  <div class="buttons" style="margin-top:0px">
<button class="positive"  id="save_edit_objective"  >{t}Save{/t}</button>
<button  id="close_edit_objective" class="negative" >{t}Cancel{/t}</button><br/>

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
   <div  id="table0"   class="data_table_container dtable btable"> </div>
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
            <div  id="table5"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
<div id="dialog_family_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Family List{/t}</span>
            {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6}
            <div  id="table6"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
<div id="dialog_product_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Product List{/t}</span>
            {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7}
            <div  id="table7"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>
<div id="dialog_offer_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Offer List{/t}</span>
            {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8}
            <div  id="table8"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>

{include file='build_email_dialogs.tpl'}
{include file='footer.tpl'}




