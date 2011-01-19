{include file="$head_template"}
<body class="yui-skin-sam kaktus">
  <div id="container" >
    {include file="templates/checkout_header.en_GB.tpl"}
   
      <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1>{t}Edit Customer{/t}: <span id="title_name">{$customer->get('Customer Name')}</span></h1>
  </div>


 <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Customer Details{/t}</span></span></li>
 {if $customer_type=='Company'}
    <li> <span class="item {if $edit=='company'}selected{/if}" style="display:none"  id="company">  <span> {t}Company Details{/t}</span></span></li>
 {/if}
 <li> <span class="item {if $edit=='delivery'}selected{/if}"  id="delivery">  <span> {t}Delivery Options{/t}</span></span></li>
    <li> <span class="item {if $edit=='marketing'}selected{/if}"  id="marketing">  <span> {t}Marketing Data{/t}</span></span></li>

  </ul>



      
     <div class="order_edit" style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 0px 0">

<div class="order_edit_block" >


</div>





								<!--table 1 starts -->

 <table class="edit" border=1 style="clear:both;margin-bottom:40px">
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

									<!--table 1 ends -->






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




<div class="order_edit_block" style="margin-left:20px">






</div>


     

          

       
       
       <div style="clear:both"></div>
      </div>
	
	
 
    
	<div style="clear:both;height:20px"></div>
	<div style="border:0px solid #ddd;width:210px;float:right">
	 
       </div>
	
	<div class="data_table"  style="clear:both;margin-bottom:40px">
	

	<div id="table_type">





	
     

     
    <div id="list_options0"> 
      

 
  </div>


    
	
	
    {include  file="$footer_template"}
 </body>
