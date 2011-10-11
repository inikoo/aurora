{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<div style="clear:left;margin:0 0px">
    <h1 style="margin-bottom:20px">{t}New Email Campaign{/t}</h1>

<div class="top_row" >
 <div class="general_options" style="padding:10px;float:right">
	
	<span  style="margin-right:10px;" class="disabled"  id="save_new_email_campaign" class="state_details">{t}Continue{/t}</span>
	<span style="margin-right:10px;" id="reset_new_email_campaign" class="state_details">{t}Cancel{/t}</span>
	      <span class="error" id="new_email_campaign_dialog_msg"></span>
           <span class="error" id="new_email_campaign_invalid_msg"></span>

      </div>
      <div class="error" id="new_email_campaign_dialog_msg"></div>
      
<table class="edit" style="margin-top:10px">


<input type="hidden" value={$store->id} id="store_id">
<span style="display:none" id="invalid_email_campaign_name">{t}Invalid Campaign Name{/t}</span>
<span style="display:none" id="invalid_email_campaign_objetive">{t}Invalid Campaign Objetive{/t}</span>

<tr ><td style=";width:12em" class="label" >{t}Campaign Name{/t}:</td>
   <td  style="text-align:left;width:18em;">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="email_campaign_name" value="" ovalue="" >
       <div id="email_campaign_name_Container"  ></div>
     </div>
   </td>
   <td id="email_campaign_name_msg" class="edit_td_alert"></td>
 </tr>

<tr ><td style=";width:12em" class="label" >{t}Campaign Objetive{/t}:</td>
   <td  style="text-align:left;width:18em;">
     <div  style="width:15em;position:relative;top:00px" >
       <input style="text-align:left;width:18em" id="email_campaign_objetive" value="" ovalue="" >
       <div id="email_campaign_objetive_Container"  ></div>
     </div>
   </td>
   <td id="email_campaign_objetive_msg" class="edit_td_alert"></td>
 </tr>


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
{include file='footer.tpl'}
