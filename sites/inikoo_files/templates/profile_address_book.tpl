<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />

<div class="top_page_menu" style="padding:0px 20px 5px 20px">
<div class="buttons" style="float:left">
<button  onclick="window.location='profile.php?view=change_password'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Change Password{/t}</button>
<button  class="selected" onclick="window.location='profile.php?view=address_book'" ><img src="art/icons/chart_organisation.png" alt=""> {t}Address Book{/t}</button>
<button  onclick="window.location='profile.php?view=orders'" ><img src="art/icons/table.png" alt=""> {t}Orders{/t}</button>
<button  onclick="window.location='profile.php?view=contact'" ><img src="art/icons/chart_pie.png" alt=""> {t}My Account{/t}</button>


</div>


<div style="clear:both">

</div>
</div>



       
<div id="address_book_block" >
///////



<table class="edit" border=1 style="clear:both;margin-bottom:40px;width:100%">
<tr><td>{t}Contact Address{/t}</td><td>{t}Billing Address{/t}</td><td>{t}Delivery Address{/t}</td></tr>
<tr>
<td>{$page->customer->get('Customer Main XHTML Address')}</td>
<td>{if $page->customer->get('Customer Billing Address Key')==$page->customer->get('Customer Main Address Key')}{t}Same as Contact Address{/t}{elseif $page->customer->get('Customer Billing Address Key')==$page->customer->get('Customer Delivery Address Key')}{t}Same as Delivery Address{/t}{else}{$page->customer->display_billing_address('xhtml')}{/if}</td>
<td>{if $page->customer->get('Customer Main Delivery Address Key')==$page->customer->get('Customer Main Address Key')}{t}Same as Contact Address{/t}{elseif $page->customer->get('Customer Billing Address Key')==$page->customer->get('Customer Delivery Address Key')}{t}Same as Billing Address{/t}{else}{$page->customer->display_delivery_address('xhtml')}{/if}</td>
</tr>
<tr><td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address'><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>

</td>
<td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=add_address&type=billing_'><img src="art/icons/chart_pie.png" alt=""> {t}Add{/t}</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address'><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>

</td>
<td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=add_address&type=delivery_'><img src="art/icons/chart_pie.png" alt=""> {t}Add{/t}</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address'><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>

</td>
</tr>
<tr>
<td>
</td>
<td><table>

{foreach from=$page->customer->get_billing_address_objects()  item=address key=key }
{if $page->customer->get('Customer Billing Address Key')!=$address->id}
<tr><td>{$address->display('xhtml')}</td></tr>
<tr><td>


<div class="buttons" style="float:left">
<button onClick="change_main_address({$address->id},{literal}{{/literal}type:'billing',prefix:'billing_',Subject:'Customer',subject_key:{$page->customer->id}{literal}}{/literal})""><img src="art/icons/chart_pie.png" alt=""> {t}Set as Main{/t}</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address'><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>


<div class="buttons" style="float:left">
<button class="negative" onClick="delete_address({$address->id},{literal}{{/literal}type:'billing',prefix:'billing_',Subject:'Customer',subject_key:{$page->customer->id}{literal}}{/literal})"><img src="art/icons/chart_pie.png" alt=""> {t}Remove{/t}</button>
</div>


</td></tr>
{/if}
{/foreach}

</table></td>





<td><table>

{foreach from=$page->customer->get_delivery_address_objects()  item=address key=key }
{if $page->customer->get('Customer Main Delivery Address Key')!=$address->id}
<tr><td>{$address->display('xhtml')}</td></tr>
<tr><td>

<div class="buttons" style="float:left">
<button onClick="change_main_address({$address->id},{literal}{{/literal}type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:{$page->customer->id}{literal}}{/literal})"><img src="art/icons/chart_pie.png" alt=""> {t}Set as Main{/t}</button>
</div>



<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address'><img src="art/icons/chart_pie.png" alt=""> {t}Edit{/t}</button>
</div>



<div class="buttons" style="float:left">
<button class="negative" onClick="delete_address({$address->id},{literal}{{/literal}type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:{$page->customer->id}{literal}}{/literal})"><img src="art/icons/chart_pie.png" alt=""> {t}Remove{/t}</button>
</div>


</td></tr>
{/if}
{/foreach}


</table></td>
</tr>
</table>
///////////////////
</div>     

