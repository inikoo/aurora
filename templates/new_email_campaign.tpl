{include file='header.tpl'}
<div id="bd" >
{include file='marketing_navigation.tpl'}
<div style="clear:left;margin:0 0px">
    <h1 style="margin-bottom:20px">{t}New Email Campaign{/t}</h1>

<div class="top_row" >

<table class="edit" style="margin-top:10px">
<tr><td class="label">{t}Campaign Name:{/t}</td><td><input style="width:300px" id="email_campaign_name" type="text" value=""></td></tr>
<tr><td class="label">{t}Campaign Objetive:{/t}</td><td><textarea style="width:300px"  id="email_campaign_objetive"  ></textarea></td></tr>
</table>

<button id="cancel_new_email_campaign">{t}Cancel{/t}</button>
<button id="save_new_email_campaign">{t}Create{/t}</button>

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
