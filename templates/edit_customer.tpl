{include file='header.tpl'}
<div id="bd" >
 {include file='contacts_navigation.tpl'}
<input type="hidden" value="{$customer->id}" id="customer_key"/>

 <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Edit Customer{/t}: <span id="title_name">{$customer->get('Customer Name')}</span></h1>
  </div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Customer Details{/t}</span></span></li>
 {if $customer_type=='Company'}
    <li> <span class="item {if $edit=='company'}selected{/if}" style="display:none"  id="company">  <span> {t}Company Details{/t}</span></span></li>
 {/if}
 <li> <span class="item {if $edit=='delivery'}selected{/if}"  id="delivery">  <span> {t}Delivery Options{/t}</span></span></li>
    <li> <span class="item {if $edit=='categories'}selected{/if}"  id="categories">  <span> {t}Categories{/t}</span></span></li>
    <li> <span class="item {if $edit=='communications'}selected{/if}"  id="communications">  <span> {t}Communications{/t}</span></span></li>

  </ul>
  
 <div class="tabbed_container" > 
   
   
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
 <td class="label" style="width:200px">{t}Send Marketing Emails{/t}:</td>
 <td>
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
   <span class="{if $customer->get('Customer Send Postal Marketing')=='Yes'}selected{/if}" onclick="save_comunications('Customer Send Postal Marketing','Yes')" id="Customer Send Postal Marketing_Yes">{t}Yes{/t}</span> <span class="{if $customer->get('Customer Send Postal Marketing')=='No'}selected{/if}" onclick="save_comunications('Customer Send Postal Marketing','No')" id="Customer Send Postal Marketing_No">{t}No{/t}</span>
   </div>
 </td>
 </tr>
 
 
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
 
 <td class="label">{t}{$cat.name}{/t}:</td>
 <td>
   {foreach from=$cat.teeth item=cat2 key=cat2_id name=foo2}
   <div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}"   class="options" style="margin:5px 0">
    <table border=0 >
    
     {foreach from=$cat2.elements item=cat3 key=cat3_id name=foo3}
    
        {if $cat3.mod5==0}<tr>{/if}
            <td style="text-align:center;padding:5px 5px; border:none">   
                <span  style=""  class="catbox {if $cat3.selected}selected{/if}" 
                    value="{$cat3.selected}" 
                    ovalue="{$cat3.selected}" 
                    onclick="save_radio(this)" 
                    cat_id="{$cat3_id}" 
                    id="cat{$cat3_id}" 
                    parent="{$cat3.parent}" 
                    position="{$cat3.position}" 
                    default="{$cat3.default}"  >{$cat3.name}
                </span>
            </td>
        {if $cat3.mod5==4}</tr>{/if}
     {/foreach}
     </table>
    </div>
   {/foreach}
 </td>   
</tr>
{/foreach}


</table>
</div>
  
<div  class="edit_block" style="{if $edit!="delivery"}display:none{/if};min-height:260px"  id="d_delivery">
 {include file='edit_delivery_address_splinter.tpl'}

</div>


   <div  class="edit_block" style="{if $edit!="details"}display:none{/if};"  id="d_details">
  
       <div class="general_options" style="float:right">
	        <span  style="margin-right:10px;visibility:hidden"  id="save_edit_customer" class="state_details">{t}Save{/t}</span>
	        <span style="margin-right:10px;visibility:hidden" id="reset_edit_customer" class="state_details">{t}Reset{/t}</span>
      </div>
  
   <table class="edit" border=0 style="clear:both;margin-bottom:40px">
 <tr style="display:none"><td class="label" style="width:12em">{t}Type{/t}:</td>
	       <td style="width:19em"> 
		 <div class="options" style="margin:5px 0" id="shelf_type_type_container">
		   <input type="hidden" value="{$shelf_default_type}" ovalue="{$shelf_default_type}" id="shelf_type_type"  >
		  <span class="radio{if $customer_type=='Company'} selected{/if}"  id="radio_shelf_type_{$customer_type}" radio_value="{$customer_type}">{t}Company{/t}</span> 
		    <span class="radio{if $customer_type=='Person'} selected{/if}"  id="radio_shelf_type_{$customer_type}" radio_value="{$customer_type}">{t}Person{/t}</span> 

		 </div>

<tr class=""><td style="" class="label">{t}Tax Number{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Customer_Tax_Number" value="{$customer->get('Customer Tax Number')}" ovalue="{$customer->get('Customer Tax Number')}" valid="0">
       <div id="Customer_Tax_Number_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Tax_Number_msg" class="edit_td_alert"></td>
 </tr>

		 {if $customer_type=='Company'}
 </td></tr>
 <tr class="first"><td style="" class="label">{t}Company Name{/t}:</td>
   <td  style="text-align:left;width:12em">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Customer_Name" value="{$customer->get('Customer Name')}" ovalue="{$customer->get('Customer Name')}" valid="0">
       <div id="Customer_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Name_msg"  class="edit_td_alert"></td>
 </tr>
 {/if}
 <tr class=""><td style=";width:12em" class="label" >{t}Contact Name{/t}:</td>
   <td  style="text-align:left;width:18em;">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Customer_Main_Contact_Name" value="{$customer->get('Customer Main Contact Name')}" ovalue="{$customer->get('Customer Main Contact Name')}" valid="0">
       <div id="Customer_Main_Contact_Name_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Main_Contact_Name_msg" class="edit_td_alert"></td>
 </tr>
 <tr class=""><td style="" class="label">{t}Contact Email{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Customer_Main_Email" value="{$customer->get('Customer Main Plain Email')}" ovalue="{$customer->get('Customer Main Plain Email')}" valid="0">
       <div id="Customer_Main_Email_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Main_Email_msg" class="edit_td_alert"></td>
 </tr>
 <tr class=""><td style="" class="label">{t}Contact Telephone{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Customer_Main_Telephone" value="{$customer->get('Customer Main XHTML Telephone')}" ovalue="{$customer->get('Customer Main XHTML Telephone')}" valid="0">
       <div id="Customer_Main_Telephone_Container" style="" ></div>
     </div>
   </td>
   <td id="Customer_Main_Telephone_msg" class="edit_td_alert"></td>
 </tr>




     </table>

   <div id="customer_contact_address" style="float:left;xborder:1px solid #ddd;width:400px;margin-right:40px;min-height:300px">
     <div style="border-bottom:1px solid #777;margin-bottom:5px">
       {t}Contact Address{/t}:
     </div>
     <table>
       {include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true  }
     </table>
     <div style="display:none" id='contact_current_address' ></div>
     <div style="display:none" id='contact_address_display{$customer->get("Customer Main Address Key")}' ></div>
   </div>

 <div id="customer_billing_address" style="float:left;xborder:1px solid #ddd;width:400px;margin-bottom:20px;">
     <div style="border-bottom:1px solid #777;margin-bottom:7px">
     
       {t}Billing Information{/t}:<span class="state_details" style="float:right;display:none" address_key="" id="billing_cancel_edit_address">{t}Cancel{/t}</span>
     
     </div>
     
     
       <table border=0>
	 {if $customer->get('Customer Type')=='Company'}
     <tr><td class="lavel">{t}Fiscal Name{/t}:</td>
        <td  style="text-align:left;width:18em;">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="Customer_Fiscal_Name" value="{$customer->get('Customer Fiscal Name')}" ovalue="{$customer->get('Customer Fiscal Name')}" valid="0">
       <div id="Customer_Fiscal_Name_Container" style="" ></div>
     </div>
   </td>
   </tr><tr> <td id="Customer_Fiscal_Name_msg" class="edit_td_alert"></td>
   <td><span  style="margin-right:10px;visibility:hidden"  id="save_edit_billing_data" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_billing_data" class="state_details">{t}Reset{/t}</span></td>
   
   
  
     
     </tr>
{/if}

      
      
     </table>
     
       
       <div id="billing_address" style="margin-bottom:10px">
            {if ($customer->get('Customer Billing Address Link')=='Contact')   }

   <span style="font-weight:600">{t}Billing Address Same as contact address{/t}</span> 
   {else}
   {$customer->billing_address_xhtml()}
   {/if}
   
</div>
       <span id="show_edit_billing_address"  address_key="{$customer->get('Customer Billing Address Key')}" class="state_details">{t}Set up different address{/t}</span>
       
       
   </div>
 
 <div id="customer_delivery_address" style="float:left;xborder:1px solid #ddd;width:400px;">
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

{include file='footer.tpl'}
