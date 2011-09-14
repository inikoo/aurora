{include file='header.tpl'}
<div id="bd" >
 {include file='contacts_navigation.tpl'}
<input type="hidden" value="{$customer->id}" id="customer_key"/>

 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1><span style="color:SteelBlue">{$id}</span>, <span id="title_name">{$customer->get('Customer Name')}</span></h1>
  </div>

<div style="padding:10px;background-color:#FAF8CC;width:300px;{if $recent_merges==''}display:none{/if}">{$recent_merges}</div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Customer Details{/t}</span></span></li>
 {if $customer_type=='Company'}
    <li> <span class="item {if $edit=='company'}selected{/if}" style="display:none"  id="company">  <span> {t}Company Details{/t}</span></span></li>
 {/if}
 <li> <span class="item {if $edit=='delivery'}selected{/if}"  id="delivery">  <span> {t}Delivery Options{/t}</span></span></li>
    <li> <span class="item {if $edit=='categories'}selected{/if}"  id="categories">  <span> {t}Categories{/t}</span></span></li>
    <li> <span class="item {if $edit=='communications'}selected{/if}"  id="communications">  <span> {t}Communications{/t}</span></span></li>
    <li> <span class="item {if $edit=='merge'}selected{/if}"  id="merge">  <span> {t}Merge{/t}</span></span></li>
	{if $site_customer}
	<li> <span class="item {if $edit=='password'}selected{/if}"  id="password">  <span> {t}User Site{/t}</span></span></li>
	{/if}
  </ul>
  
 <div class="tabbed_container" > 
 <div  class="edit_block" style="{if $edit!="password"}display:none{/if};min-height:260px"  id="d_password">
 


   <table class="edit" border=0  style="width:700px">
    <tr class="title"><td colspan=5>{t}Reset Password{/t}</td></tr>
   <tr>
   <td style="width:200px">{t}Send an Email: ({$customer->get('Customer Main Plain Email')}){/t}</td>
   <td style="width:200px">
   
   
   <div>
       <input type="button" class="button" id="forget_password" value="Send"/>
   
   </div>
   
   </td>
  
   </tr>
   </table>
 </div>
 
 
   <div  class="edit_block" style="{if $edit!="merge"}display:none{/if};min-height:260px"  id="d_merge">
   
   <table class="edit" border=0  style="width:700px">
   <tr>
   <td style="width:200px">{t}Merge with: (Customer ID){/t}</td>
   <td style="width:200px">
   
   
   <div>
       <input style="text-align:left;width:100%" id="customer_b_id" value="" ovalue="" >
       <div id="customer_b_id_Container" style="" ></div>
   </div>
   
   </td>
   <td style="width:300px"><a id="go_merge" href="" class="state_details" style="display:none">{t}Go{/t}</a><span id="merge_msg" class="error" style="display:none"></span></td>
   </tr>
   </table>
   
   </div>
   
<div  class="edit_block" style="{if $edit!="communications"}display:none{/if};min-height:260px"  id="d_communications">
    
    
    
    
<table class="edit">
 <tr class="title"><td colspan=5>{t}Emails{/t}</td></tr>
 
 <tr>
 <td class="label" style="width:200px">{t}Send Newsletter{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Customer Send Newsletter')=='Yes'}selected{/if}" onclick="save_comunications('Customer Send Newsletter','Yes')" id="Customer Send Newsletter_Yes">{t}Yes{/t}</span> <span class="{if $customer->get('Customer Send Newsletter')=='No'}selected{/if}" onclick="save_comunications('Customer Send Newsletter','No')" id="Customer Send Newsletter_No">{t}No{/t}</span>
   </div>
 </td>
 </tr>
  <tr>
 <td class="label" style="width:200px">{t}Send Marketing Emails{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Customer Send Email Marketing')=='Yes'}selected{/if}" onclick="save_comunications('Customer Send Email Marketing','Yes')" id="Customer Send Email Marketing_Yes">{t}Yes{/t}</span> <span class="{if $customer->get('Customer Send Email Marketing')=='No'}selected{/if}" onclick="save_comunications('Customer Send Email Marketing','No')" id="Customer Send Email Marketing_No">{t}No{/t}</span>
   </div>
 </td>
 </tr>
 
  <tr class="title"><td colspan=5>{t}Post{/t}</td></tr>
 

  <tr>
 <td class="label" style="width:200px">{t}Send Marketing Post{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Customer Send Postal Marketing')=='Yes'}selected{/if}" onclick="save_comunications('Customer Send Postal Marketing','Yes')" id="Customer Send Postal Marketing_Yes">{t}Yes{/t}</span> <span class="{if $customer->get('Customer Send Postal Marketing')=='No'}selected{/if}" onclick="save_comunications('Customer Send Postal Marketing','No')" id="Customer Send Postal Marketing_No">{t}No{/t}</span><br/><br/>
   </div>
 </td>
 </tr>


<tbody id="add_to_post_cue" style="display:none">

  <tr class="title"><td colspan=5>{t}Send Post {/t}</td></tr>
 <tr>
 <td class="label" style="width:200px">{t}Add Customer To Send Post{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Send Post Status')=='To Send'}selected{/if}" onclick="save_comunications_send_post('Send Post Status','To Send')" id="Send Post Status_To Send">{t}Yes{/t}</span> <span class="{if $customer->get('Send Post Status')=='Cancelled'}selected{/if}" onclick="save_comunications_send_post('Send Post Status','Cancelled')" id="Send Post Status_Cancelled">{t}No{/t}</span>
   </div>
 </td>
 </tr>
<tr>
 <td class="label" style="width:200px">{t}Post Type{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Post Type')=='Letter'}selected{/if}" onclick="save_comunications_send_post('Post Type','Letter')" id="Post Type_Letter">{t}Letter{/t}</span> <span class="{if $customer->get('Post Type')=='Catalogue'}selected{/if}" onclick="save_comunications_send_post('Post Type','Catalogue')" id="Post Type_Catalogue">{t}Catalogue{/t}</span>
   </div>
 </td>
 </tr>
 </tbody>
 
 
{*} 
 {foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat.name}{/t}:</td>
 <td>
   {foreach from=$cat.teeth item=cat2 key=cat2_id name=foo2}
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
     {foreach from=$cat2.elements item=cat3 key=cat3_id name=foo3}
     <span  class="catbox {if $cat3.selected}selected{/if}" value="{$cat3.selected}" ovalue="{$cat3.selected}" onclick="save_radio(this)" cat_id="{$cat3_id}" id="cat{$cat3_id}" parent="{$cat3.parent}" position="{$cat3.position}" default="{$cat3.default}"  >{$cat3.name}</span>
     {/foreach}
    </div>
   {/foreach}
 </td>   
</tr>
{/foreach}
{*}

</table>
</div>
<div  class="edit_block" style="{if $edit!="categories"}display:none{/if};min-height:260px"  id="d_categories">
<table class="edit">
 <tr class="title"><td colspan=5>{t}Categories{/t}</td></tr>
 
 {foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat->get('Category Name')}{/t}:</td>
 <td>
  <select id="cat{$cat_key}" cat_key="{$cat_key}"  onChange="save_category(this)">
    {foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2  }
        {if $smarty.foreach.foo2.first}
        <option {if $categories_value[$cat_key]=='' }selected="selected"{/if} value="">{t}Unknown{/t}</option>
        {/if}
        <option {if $categories_value[$cat_key]==$sub_cat_key }selected="selected"{/if} value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Name')}</option>
    {/foreach}
  </select>
  
 </td>   
</tr>
{/foreach}


</table>
</div>
<div  class="edit_block" style="{if $edit!="delivery"}display:none{/if};min-height:260px"  id="d_delivery">
 {include file='edit_delivery_address_splinter.tpl'}

</div>
<div  class="edit_block" style="{if $edit!="details"}display:none{/if};"  id="d_details">
  
      

   <table class="edit" border=0 style="clear:both;margin-bottom:40px;width:100%">
<tr>
<td></td>
<td style="text-align:right;color:#777;font-size:90%">
<div id="delete_customer_warning" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none">
<h2>{t}Delete Customer{/t}</h2>
<p>
{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t}
</p>
<p id="delete_customer_msg"></p>
<span id="cancel_delete_customer"  style="cursor:pointer;display:none;font-weight:800" >{t}No i dont want to delete it{/t}</span>
<span id="save_delete_customer"  style="cursor:pointer;display:none;margin-left:20px;">{t}Yes, delete it!{/t}</span>
<p id="deleting" style="display:none;">{t}Deleting customer, wait please{/t}</p>
</div>
<span id="delete_customer" class="state_details" style="{if $customer->get('Customer With Orders')=='Yes'}display:none{/if}">{t}Delete Customer{/t}</span>

</td>
<td>
 <div class="general_options" style="float:right">
	        <span  style="margin-right:10px;visibility:hidden"  id="save_edit_customer" class="state_details">{t}Save{/t}</span>
	        <span style="margin-right:10px;visibility:hidden" id="reset_edit_customer" class="state_details">{t}Reset{/t}</span>
      </div>
</td>
</tr>

<tr>
<td style="width:150px"></td>
<td style="text-align:right;color:#777;font-size:90%;width:300px">
<div  id="convert_to_person_info" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none;margin-bottom:5px">
<p>
{t}This operation will delete the company{/t}
</p>
<div style="color:#999">
<span id="cancel_convert_to_person" class="state_details" style="display:none" >{t}Cancel{/t}</span>
<span id="save_convert_to_person" class="state_details" style="display:none;margin-left:10px;color:#777">{t}Do it!{/t}</span>
</div>
</div>
<span id="convert_to_person" class="state_details" style="{if $customer_type!='Company'}display:none{/if}">{t}Convert to Person{/t}</span>
</td>
</tr>


<tr>
<td></td>
<td style="text-align:right;color:#777;font-size:90%">
<span id="convert_to_company" class="state_details" style="{if $customer_type=='Company'}display:none{/if}">{t}Convert to Company{/t}</span>
<span id="cancel_convert_to_company" class="state_details" style="display:none" >{t}Cancel{/t}</span>
<span id="save_convert_to_company" class="disabled state_details" style="display:none;margin-left:10px;;color:#777;">{t}Save Conversion to Company{/t}</span>
</td>
</tr>
   
  <tr id="New_Company_Name_tr"  style="display:none" class="first">
  <td style=""  class="label">{t}Company Name{/t}:</td>
   <td  style="text-align:left;">
     <div  >
       <input style="text-align:left;width:100%" id="New_Company_Name" value="" ovalue="" valid="0">
       <div id="New_Company_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="New_Company_Name_msg"  class="edit_td_alert"></td>
 </tr> 
 
 
 
   
 <tr style="display:none"><td class="label">{t}Type{/t}:</td>
	       <td > 
		 <div class="options" style="margin:5px 0" id="shelf_type_type_container">
		   <input type="hidden" value="{$shelf_default_type}" ovalue="{$shelf_default_type}" id="shelf_type_type"  >
		  <span class="radio{if $customer_type=='Company'} selected{/if}"  id="radio_shelf_type_{$customer_type}" radio_value="{$customer_type}">{t}Company{/t}</span> 
		    <span class="radio{if $customer_type=='Person'} selected{/if}"  id="radio_shelf_type_{$customer_type}" radio_value="{$customer_type}">{t}Person{/t}</span> 

		 </div>



		 
 
 <tr {if $customer_type!='Company'}style="display:none"{/if} class="first"><td style="" class="label">{t}Company Name{/t}:</td>
   <td  style="text-align:left;">
     <div  >
       <input style="text-align:left;width:100%" id="Customer_Name" value="{$customer->get('Customer Name')}" ovalue="{$customer->get('Customer Name')}" valid="0">
       <div id="Customer_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Name_msg"  class="edit_td_alert"></td>
 </tr>
 
  <tr  class="first"><td style="" class="label">{if $customer_type=='Company'}{t}Registration Number{/t}{else}{t}Identification Number{/t}{/if}:</td>
   <td  style="text-align:left;">
     <div  >
       <input style="text-align:left;width:100%" id="Customer_Registration_Number" value="{$customer->get('Customer Registration Number')}" ovalue="{$customer->get('Customer Registration Number')}" valid="0">
       <div id="Customer_Registration_Number_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Registration_Number_msg"  class="edit_td_alert"></td>
 </tr>
 
 
 

 <tr class=""><td style="" class="label" >{t}Contact Name{/t}:</td>
   <td  style="text-align:left;">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Contact_Name" value="{$customer->get('Customer Main Contact Name')}" ovalue="{$customer->get('Customer Main Contact Name')}" valid="0">
       <div id="Customer_Main_Contact_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Main_Contact_Name_msg" class="edit_td_alert"></td>
 </tr>


 
 <tr class=""><td style="" class="label"><img   id="comment_icon_email" src="{if $customer->get_principal_email_comment()==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main Email Key')==''}display:none{/if}" onClick="change_comment(this,'email',{$customer->get('Customer Main Email Key')})"> {t}Contact Email{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Email" value="{$customer->get('Customer Main Plain Email')}" ovalue="{$customer->get('Customer Main Plain Email')}" valid="0">
       <div id="Customer_Main_Email_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="display_add_other_email" class="state_details" style="font-size:80%;color:#777;">{t}Add other Email{/t}</span>
   <span id="Customer_Main_Email_msg" class="edit_td_alert">{$main_email_warning}</span>
   </td>
 </tr>



 {foreach from=$customer->get_other_emails_data() key=other_email_key item=other_email }
  <tr class="" id="tr_other_email{$other_email_key}"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'email',{$other_email_key})">  <span id="tr_other_email_label{$other_email_key}">{if $other_email.label==''}{t}Other Email{/t}{else}{$other_email.label} (Email){/if}:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Email{$other_email_key}" value="{$other_email.email}" ovalue="{$other_email.email}" valid="0">
       <div id="Customer_Email{$other_email_key}_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_Email{$other_email_key}_msg" class="edit_td_alert"></span>
   </td>
 </tr>
{/foreach}


 <tr id="tr_add_other_email" class="" style="display:none"><td style="" class="label">{t}Other Email{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_Email" value="" ovalue="" valid="0">
       <div id="Customer_Other_Email_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_Email_msg" class="edit_td_alert"></td>
 </tr>




 <tr class=""><td style="" class="label"><img  id="comment_icon_telephone" src="{if $customer->get_principal_telecom_comment('Telephone')==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main Telephone Key')==''}display:none{/if}" onClick="change_comment(this,'telephone',{$customer->get('Customer Main Telephone Key')})"> {t}Contact Telephone{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Telephone" value="{$customer->get('Customer Main XHTML Telephone')}" ovalue="{$customer->get('Customer Main XHTML Telephone')}" valid="0">
       <div id="Customer_Main_Telephone_Container" style="" ></div>
     </div>
   </td>
   <td>
    <span id="display_add_other_telephone" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main XHTML Telephone')==''}display:none{/if}">{t}Add other Telephone{/t}</span>
    <span id="Customer_Main_Telephone_msg" class="edit_td_alert">{$main_telephone_warning}</span>
   </td>
 </tr>
 
 
 
  {foreach from=$customer->get_other_telephones_data() key=other_telephone_key item=other_telephone }
 <tr class="" id="tr_other_telephone{$other_telephone_key}"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'telephone',{$other_telephone_key})">  <span id="tr_other_telephone_label{$other_telephone_key}">{if $other_telephone.label==''}{t}Other Telephone{/t}{else}{$other_telephone.label} (Telephone){/if}:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Telephone{$other_telephone_key}" value="{$other_telephone.xhtml}" ovalue="{$other_telephone.xhtml}" valid="0">
       <div id="Customer_Telephone{$other_telephone_key}_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_Telephone{$other_telephone_key}_msg" class="edit_td_alert"></span>
   </td>
 </tr>
{/foreach}

 <tr id="tr_add_other_telephone" class="" style="display:none"><td style="" class="label">{t}Other Telephone{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_Telephone" value="" ovalue="" valid="0">
       <div id="Customer_Other_Telephone_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_Telephone_msg" class="edit_td_alert"></td>
 </tr>

 
 
 
  <tr class=""><td style="" class="label"><img  id="comment_icon_mobile" src="{if $customer->get_principal_telecom_comment('Mobile')==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main Mobile Key')==''}display:none{/if}" onClick="change_comment(this,'mobile',{$customer->get('Customer Main Mobile Key')})"> {t}Contact Mobile{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_Mobile" value="{$customer->get('Customer Main XHTML Mobile')}" ovalue="{$customer->get('Customer Main XHTML Mobile')}" valid="0">
       <div id="Customer_Main_Mobile_Container" style="" ></div>
     </div>
   </td>
  
   
      <td>
    <span id="display_add_other_mobile" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main XHTML Mobile')==''}display:none{/if}">{t}Add other Mobile{/t}</span>
    <span id="Customer_Main_Mobile_msg" class="edit_td_alert">{$main_mobile_warning}</span>
   </td>
   
 </tr>
 
 
 
   {foreach from=$customer->get_other_mobiles_data() key=other_mobile_key item=other_mobile }
 <tr class="" id="tr_other_mobile{$other_mobile_key}"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'mobile',{$other_mobile_key})">  <span id="tr_other_mobile_label{$other_mobile_key}">{if $other_mobile.label==''}{t}Other Mobile{/t}{else}{$other_mobile.label} (Mobile){/if}:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Mobile{$other_mobile_key}" value="{$other_mobile.number}" ovalue="{$other_mobile.number}" valid="0">
       <div id="Customer_Mobile{$other_mobile_key}_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_Mobile{$other_mobile_key}_msg" class="edit_td_alert"></span>
   </td>
 </tr>
{/foreach}

 <tr id="tr_add_other_mobile" class="" style="display:none"><td style="" class="label">{t}Other Mobile{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_Mobile" value="" ovalue="" valid="0">
       <div id="Customer_Other_Mobile_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_Mobile_msg" class="edit_td_alert"></td>
 </tr>
 
 
<tr class=""><td style="" class="label"><img  id="comment_icon_fax" src="{if $customer->get_principal_telecom_comment('FAX')==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main FAX Key')==''}display:none{/if}" onClick="change_comment(this,'fax',{$customer->get('Customer Main FAX Key')})"> {t}Contact Fax{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Main_FAX" value="{$customer->get('Customer Main XHTML FAX')}" ovalue="{$customer->get('Customer Main XHTML FAX')}" valid="0">
       <div id="Customer_Main_FAX_Container" style="" ></div>
     </div>
   </td>
   <td>
<span id="display_add_other_fax" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main XHTML FAX')==''}display:none{/if}">{t}Add other Fax{/t}</span>
    <span id="Customer_Main_FAX_msg" class="edit_td_alert">{$main_fax_warning}</span>
   </td>
 </tr>

 {foreach from=$customer->get_other_faxes_data() key=other_fax_key item=other_fax }
 <tr class="" id="tr_other_fax{$other_fax_key}"><td style="" class="label"><img  src="art/icons/edit.gif" style="cursor:pointer" onClick="change_other_field_label(this,'fax',{$other_fax_key})">  <span id="tr_other_fax_label{$other_fax_key}">{if $other_fax.label==''}{t}Other Fax{/t}{else}{$other_fax.label} (Fax){/if}:<span></td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_FAX{$other_fax_key}" value="{$other_fax.number}" ovalue="{$other_fax.number}" valid="0">
       <div id="Customer_FAX{$other_fax_key}_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_FAX{$other_fax_key}_msg" class="edit_td_alert"></span>
   </td>
 </tr>
{/foreach}

 <tr id="tr_add_other_fax" class="" style="display:none"><td style="" class="label">{t}Other Fax{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Other_FAX" value="" ovalue="" valid="0">
       <div id="Customer_Other_FAX_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Other_FAX_msg" class="edit_td_alert"></td>
 </tr>
 
 {*Edit custom fields*}
 
 {foreach from=$show_case key=custom_field_key item=custom_field_value }
 <tr class="" id="tr_{$custom_field_value.lable}"><td style="" class="label">{$custom_field_key}:</td>
   <td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Customer_{$custom_field_value.lable}" value="{$custom_field_value.value}" ovalue="{$custom_field_value.value}" valid="0">
       <div id="Customer_{$custom_field_value.lable}_Container" style="" ></div>
     </div>
   </td>
   <td>
   <span id="Customer_{$custom_field_value.lable}_msg" class="edit_td_alert"></span>
   </td>
 </tr>
{/foreach}


 <tr id="tr_Customer_Preferred_Contact_Number"   style="{if $customer->get('Customer Main XHTML Mobile')=='' or $customer->get('Customer Main XHTML Telephone')==''   }display:none{/if}" >
 <td class="label" style="width:200px">{t}Preferred contact number{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Customer Preferred Contact Number')=='Telephone'}selected{/if}" onclick="save_preferred(this,'Telephone')" id="Customer_Preferred_Contact_Number_Telephone">{t}Telephone{/t}</span> <span class="{if $customer->get('Customer Preferred Contact Number')=='Mobile'}selected{/if}" onclick="save_preferred(this,'Mobile')" id="Customer_Preferred_Contact_Number_Mobile">{t}Mobile{/t}</span>
   </div>
 </td>
 </tr>


     </table>

   <div id="customer_contact_address" style="float:left;xborder:1px solid #ddd;width:430px;margin-right:20px;min-height:300px">
     <div style="border-bottom:1px solid #777;margin-bottom:5px">
       {t}Contact Address{/t}:
     </div>
     <table border=0 style="width:100%">
       {include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true  show_components=true}
     </table>
     <div style="display:none" id='contact_current_address' ></div>
     <div style="display:none" id='contact_address_display{$customer->get("Customer Main Address Key")}' ></div>
   </div>

 <div id="customer_billing_address" style="float:left;xborder:1px solid #ddd;width:400px;margin-bottom:20px;">
     <div style="border-bottom:1px solid #777;margin-bottom:7px">
     
       {t}Billing Information{/t}:<span class="state_details" style="float:right;display:none" address_key="" id="billing_cancel_edit_address">{t}Cancel{/t}</span>
     
     </div>
     
     
       <table border=0>
       
       
       <tr class="">
 <td  class="label">{t}Tax Number{/t}:</td>
   <td  style="text-align:left;width:280px">
     <div   >
       <input style="text-align:left;width:100%" id="Customer_Tax_Number" value="{$customer->get('Customer Tax Number')}" ovalue="{$customer->get('Customer Tax Number')}" valid="0">
       <div id="Customer_Tax_Number_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Tax_Number_msg" style="" class="edit_td_alert"></td>
 </tr>
       
       
	
     <tr  style="{if $customer->get('Customer Type')!='Company'}display:none{/if}"><td class="lavel">{t}Fiscal Name{/t}:</td>
        <td style="text-align:left;">
     <div   >
       <input style="text-align:left;" id="Customer_Fiscal_Name" value="{$customer->get('Customer Fiscal Name')}" ovalue="{$customer->get('Customer Fiscal Name')}" valid="0">
       <div id="Customer_Fiscal_Name_Container" style="" ></div>
     </div>
   </td>
   </tr><tr> <td id="Customer_Fiscal_Name_msg" class="edit_td_alert"></td>
   <td><span  style="margin-right:10px;visibility:hidden"  id="save_edit_billing_data" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_billing_data" class="state_details">{t}Reset{/t}</span></td>
   
   
  
     
     </tr>


{if $hq_country=='ES'}
<tr>
<td>RE:</td>
<td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
      <span class="{if $customer->get('Recargo Equivalencia')=='Yes'}selected{/if}" onclick="save_comunications('Recargo Equivalencia','Yes')" id="Recargo Equivalencia_Yes">{t}Yes{/t}</span> <span class="{if $customer->get('Recargo Equivalencia')=='No'}selected{/if}" onclick="save_comunications('Recargo Equivalencia','No')" id="Recargo Equivalencia_No">{t}No{/t}</span><br/><br/>
   </div>
<td>
</tr>
{else}
<tr style="display:none">
<td>{t}Tax Code{/t}:</td><td>
<select id="tax_code">
  {foreach from=$tax_codes item=sub_cat key=sub_cat_key name=foo2  }
    
        <option {if $customer->get('Customer Tax Category Code')==$sub_cat.code }selected="selected"{/if} value="{$sub_cat.code}">{$sub_cat.name}</option>
    {/foreach}
</select></td>
</tr>
{/if}
      
      
     </table>
     
       
       <div id="billing_address_block" style="margin-bottom:10px">
       <table style="width:100%" border=0>
       <tr style="border-bottom:1px solid #777">
       <td>{t}Billing Address{/t}:</td>
       <td class="aright">
       <span style="font-size:90%;display:none" id="set_contact_address_as_billing"  class="edit aright state_details">{t}Use contact address{/t}</span>

       <span style="font-size:90%;{if ($customer->get('Customer Billing Address Link')!='Contact')}display:none{/if}" id="show_new_billing_address"  same_as_contact="{if ($customer->get('Customer Billing Address Link')=='Contact')}1{else}0{/if}"  class="edit aright state_details">{t}Set up different address{/t}</span>
       <span style="font-size:90%;{if ($customer->get('Customer Billing Address Link')=='Contact')}display:none{/if}" id="show_edit_billing_address"  same_as_contact="{if ($customer->get('Customer Billing Address Link')=='Contact')}1{else}0{/if}"  address_key="{$customer->get('Customer Billing Address Key')}" class="edit aright state_details">{t}Edit address{/t}</span>

       </td>
       </tr>
       <tr >
       <td colspan=2 id="billing_address">
        
            {if ($customer->get('Customer Billing Address Link')=='Contact')}
   <span style="font-weight:600">{t}Same as contact address{/t}</span> 
   {else}
   {$customer->billing_address_xhtml()}
   {/if}
   
       </td>
       </tr>
      
      
      
       </table> 
      
        <table id="new_billing_address_table" border=0 style="width:100%;display:none">
       {include file='edit_address_splinter.tpl' close_if_reset=true address_identifier='billing_' address_type='Shop' show_tel=false show_contact=false  address_function='Billing'  hide_type=true hide_description=true show_form=false  show_components=false }
     </table>
      
</div>
       
       
   </div>
 
 <div id="customer_delivery_address" style="display:none;float:left;xborder:1px solid #ddd;width:400px;">
     <div style="border-bottom:1px solid #777;margin-bottom:5px">
       {t}Delivery Address{/t}:<span class="state_details" style="float:right;display:none" address_key="" id="billing_cancel_edit_address">{t}Cancel{/t}</span>
     </div>
     
     <div id="delivery_current_address_bis" style="margin-bottom:10px">
     {if ($customer->get('Customer Delivery Address Link')=='Contact') or ( $customer->get('Customer Delivery Address Link')=='Billing'  and  ($customer->get('Customer Main Address Key')==$customer->get('Customer Billing Address Key'))   )   }
     
     <span style="font-weight:600">{t}Same as contact address{/t}</span> 

     
     {elseif $customer->get('Customer Delivery Address Link')=='Billing'}
     
     <span style="font-weight:600">{t}Same as billing address{/t}</span> 

     
     {else}
     {$customer->delivery_address_xhtml()}
    
     
     {/if}
     <div id="billing_address_display{$customer->get('Customer Billing Address Key')}" style="display:none"></div>
      </div>
    <span id="delivery2" class="state_details">Set up different address</span>

    

   </div>



<div style="clear:both"></div>


   </div>
   
 {if $customer_type=='Company'}
   <div  class="edit_block" style="{if $edit!="company"}display:none{/if}"  id="d_company">
      <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;display:none"  id="save_new_customer" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;display:none" id="close_add_customer" class="state_details">{t}Reset{/t}</span>
	
      </div>


      <div id="new_customer_messages" class="messages_block"></div>

      


     
	  
       {include file='edit_company_splinter.tpl'}

     
   </div>
{else}
<div  class="edit_block" style="display:none"  id="d_company"></div>
{/if}
  
   
</div>

</div>

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

<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Country List{/t}</span>
            
            {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100}
            <div  id="table100"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>


<div id="dialog_other_field_label">
  <div id="other_field_label_msg"></div>
    <input type="hidden" value="" id="other_field_label_scope"/>

  <input type="hidden" value="" id="other_field_label_scope_key"/>
  <table style="padding:20px;margin:20px 10px 10px 5px" >
 
    <tr><td colspan=2>
	<input  id="other_field_label" value=""  /> (<span id="other_field_label_scope_name"></span>)
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%;">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button"    style="visibility:hidden;" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  style="display:block;margin-top:5px" onclick="save_other_field_label()" id="note_save"  class="unselectable_text button"   >{t}Save{/t}</span></td></tr>
</table>
</div>


<div id="dialog_comment">
  <div id="comment_msg"></div>
    <input type="hidden" value="" id="comment_scope"/>

  <input type="hidden" value="" id="comment_scope_key"/>
  
  
    <input type="hidden" value="{$customer->get_principal_telecom_comment('Telephone')}" id="comment_telephone"/>
        <input type="hidden" value="{$customer->get_principal_telecom_comment('FAX')}" id="comment_fax"/>
    <input type="hidden" value="{$customer->get_principal_telecom_comment('Mobile')}" id="comment_mobile"/>
    <input type="hidden" value="{$customer->get_principal_email_comment()}" id="comment_email"/>

  <table style="padding:20px;margin:20px 10px 10px 5px" >
 <tr><td>{t}Comment{/t}:</td></tr>
    <tr><td colspan=2>
	<input  id="comment" value=""  /> 
      </td>
    <tr>
    <tr class="buttons" style="font-size:100%;">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button"    style="visibility:hidden;" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  style="display:block;margin-top:5px" onclick="save_comment()" id="comment_save"  class="unselectable_text button"   >{t}Save{/t}</span></td></tr>
</table>
</div>

{include file='footer.tpl'}
