<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />

{include file='profile_header.tpl' select='address_book'} 



       
<div id="address_book_block" >




<table class="edit" border=1 style="clear:both;margin-bottom:40px;width:100%">
<tr ><td style="width:33%">{t}Contact Address{/t}</td><td style="width:33%">{t}Billing Address{/t}</td><td style="width:33%">{t}Delivery Address{/t}</td></tr>
<tr>
<td>{$page->customer->get('Customer Main XHTML Address')}</td>
<td>{if $page->customer->get('Customer Billing Address Key')==$page->customer->get('Customer Main Address Key')}{t}Same as Contact Address{/t}{elseif $page->customer->get('Customer Billing Address Key')==$page->customer->get('Customer Delivery Address Key')}{t}Same as Delivery Address{/t}{else}{$page->customer->display_billing_address('xhtml')}{/if}</td>
<td>{if $page->customer->get('Customer Main Delivery Address Key')==$page->customer->get('Customer Main Address Key')}{t}Same as Contact Address{/t}{elseif $page->customer->get('Customer Billing Address Key')==$page->customer->get('Customer Delivery Address Key')}{t}Same as Billing Address{/t}{else}{$page->customer->display_delivery_address('xhtml')}{/if}</td>
</tr>
<tr><td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=contact_&index={$page->customer->get('Customer Main Address Key')}'><img src="art/icons/edit.gif" alt=""> {t}Edit{/t}</button>
</div>

</td>
<td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=add_address&type=billing_'><img src="art/icons/add.png" alt=""> {t}Add{/t}</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=billing_&index={$page->customer->get('Customer Billing Address Key')}'><img src="art/icons/edit.gif" alt=""> {t}Edit{/t}</button>
</div>

</td>
<td>

<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=add_address&type=delivery_'><img src="art/icons/add.png" alt=""> {t}Add{/t}</button>
</div>


<div class="buttons" style="float:left">
<button onClick=window.location='profile.php?view=edit_address&type=delivery_&index={$page->customer->get('Customer Main Delivery Address Key')}'><img src="art/icons/edit.gif" alt=""> {t}Edit{/t}</button>
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
<button onClick=window.location='profile.php?view=edit_address&type=billing_&index={$address->id}'><img src="art/icons/edit.gif" alt=""> {t}Edit{/t}</button>
</div>


<div class="buttons" style="float:left">
<button class="negative" onClick="delete_address({$address->id},{literal}{{/literal}type:'billing',prefix:'billing_',Subject:'Customer',subject_key:{$page->customer->id}{literal}}{/literal})"><img src="art/icons/cross.png" alt=""> {t}Remove{/t}</button>
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
<button onClick=window.location='profile.php?view=edit_address&type=delivery_&index={$address->id}'><img src="art/icons/edit.gif" alt=""> {t}Edit{/t}</button>
</div>



<div class="buttons" style="float:left">
<button class="negative" onClick="delete_address({$address->id},{literal}{{/literal}type:'Delivery',prefix:'delivery_',Subject:'Customer',subject_key:{$page->customer->id}{literal}}{/literal})"><img src="art/icons/cross.png" alt=""> {t}Remove{/t}</button>
</div>


</td></tr>
{/if}
{/foreach}


</table></td>
</tr>
</table>



</div>     

