{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0px 20px;">



<div class="top_page_menu">
<div class="buttons" style="float:left">
<button  onclick="window.location='client.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button>
<button  class="selected" onclick="window.location='address_book.php'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  onclick="window.location='orders.php'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button   onclick="window.location='profile.php'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>

</div>


<div style="clear:both"></div>
</div>


    <h2 class="client" style="text-align:left">{$customer->get('Customer Name')} <span style="color:SteelBlue">{$id}</span></h2> 


<table class="edit" border=1 style="clear:both;margin-bottom:40px;width:100%">
<tr><td>{t}Contact Address{/t}</td><td>{t}Billing Address{/t}</td><td>{t}Delivery Address{/t}</td></tr>
<tr>
<td>{$customer->get('Customer Main XHTML Address')}</td>
<td>{if $customer->get('Customer Billing Address Key')==$customer->get('Customer Main Address Key')}{t}Same as Contact Address{/t}{elseif $customer->get('Customer Billing Address Key')==$customer->get('Customer Delivery Address Key')}{t}Same as Delivery Address{/t}{else}{$customer->display_billing_address('xhtml')}{/if}</td>
<td>{if $customer->get('Customer Main Delivery Address Key')==$customer->get('Customer Main Address Key')}{t}Same as Contact Address{/t}{elseif $customer->get('Customer Billing Address Key')==$customer->get('Customer Delivery Address Key')}{t}Same as Billing Address{/t}{else}{$customer->display_delivery_address('xhtml')}{/if}</td>
</tr>
<tr><td>
<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>
</form>
</td>
<td>
<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Add{/t}</button>
</div>
</form>
<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>
</form>
</td>
<td>
<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Add{/t}</button>
</div>
</form>
<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>
</form>
</td>
</tr>
<tr>
<td>
</td>
<td><table>

{foreach from=$customer->get_billing_address_objects()  item=address key=key }
{if $customer->get('Customer Billing Address Key')!=$address->id}
<tr><td>{$address->display('xhtml')}</td></tr>
<tr><td>
<form action="update_details.php" method="POST" style="float:left">
<input type="hidden" value="site_edit_customer" name="tipo">
<input type="hidden" value="set_address_main" name="submit" id="submit">
<input type="hidden" value="{$address->id}" name="value" id="value">
<input type="hidden" value="billing" name="key" id="key">
<input type="hidden" value="Customer" name="subject" id="subject">
<input type="hidden" value="{$customer->get('Customer Key')}" name="subject_key" id="subject_key">

<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Set as Main{/t}</button>
</div>
</form>

<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>
</form>

<form action="update_details.php" method="POST" style="float:left">
<input type="hidden" value="site_edit_customer" name="tipo">
<input type="hidden" value="delete_address" name="submit" id="submit">
<input type="hidden" value="{$address->id}" name="value" id="value">
<input type="hidden" value="billing" name="key" id="key">
<input type="hidden" value="Customer" name="subject" id="subject">
<input type="hidden" value="{$customer->get('Customer Key')}" name="subject_key" id="subject_key">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button class="negative" type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Remove{/t}</button>
</div>
</form>

</td></tr>
{/if}
{/foreach}

</table></td>





<td><table>

{foreach from=$customer->get_delivery_address_objects()  item=address key=key }
{if $customer->get('Customer Main Delivery Address Key')!=$address->id}
<tr><td>{$address->display('xhtml')}</td></tr>
<tr><td>
<form action="update_details.php" method="POST" style="float:left">
<input type="hidden" value="site_edit_customer" name="tipo">
<input type="hidden" value="set_address_main" name="submit" id="submit">
<input type="hidden" value="{$address->id}" name="value" id="value">
<input type="hidden" value="Delivery" name="key" id="key">
<input type="hidden" value="Customer" name="subject" id="subject">
<input type="hidden" value="{$customer->get('Customer Key')}" name="subject_key" id="subject_key">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Set as Main{/t}</button>
</div>
</form>

<form action="" method="POST" style="float:left">
<input type="hidden" value="" name="" id="">
<div class="buttons" style="float:left">
<button type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>
</form>

<form action="update_details.php" method="POST" style="float:left">
<input type="hidden" value="site_edit_customer" name="tipo">
<input type="hidden" value="delete_address" name="submit" id="submit">
<input type="hidden" value="{$address->id}" name="value" id="value">
<input type="hidden" value="Delivery" name="key" id="key">
<input type="hidden" value="Customer" name="subject" id="subject">
<input type="hidden" value="{$customer->get('Customer Key')}" name="subject_key" id="subject_key">
<div class="buttons" style="float:left">
<button class="negative" type="submit"><img src="art/icons/chart_pie.png" alt=""> {t}Remove{/t}</button>
</div>
</form>

</td></tr>
{/if}
{/foreach}


</table></td>
</tr>
</table>
</div>


  

</div> 

<div>
{include file='footer.tpl'}

