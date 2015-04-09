<table class="bonus_items" style="float:right" border="0">
	{foreach from=$order->get_deal_bonus_items() key=bonus_deal_component_key item=component_order_promotion_bonus }
	 {if $component_order_promotion_bonus.type=='choose_from_family'} 
	<tbody class="choose_from_family" id="bonus_options_{$bonus_deal_component_key}">
		{foreach from=$component_order_promotion_bonus.items item=order_promotion_bonus name=foo} 
		<tr>
		<td style="padding-right:30px">{if $smarty.foreach.foo.first}{$order_promotion_bonus.deal_info}{/if}</td>
			<td style="padding-right:20px">{$order_promotion_bonus.code}</td>
			<td>{$order_promotion_bonus.name}</td>
			<td> <img bonus_deal_component_key='{$bonus_deal_component_key}' id="order_promotion_bonus_checked_{$bonus_deal_component_key}_{$order_promotion_bonus.pid}" family_key="{$order_promotion_bonus.family_key}" product_key="{$order_promotion_bonus.product_key}" product_code="{$order_promotion_bonus.code}" pid="{$order_promotion_bonus.pid}" onclick="change_order_promotion_bonus(this,0)" style="{if !$order_promotion_bonus.selected}display:none{/if}" class="checkbox checkbox_checked" src="art/icons/checkbox_checked.png"> <img bonus_deal_component_key='{$bonus_deal_component_key}' id="order_promotion_bonus_unchecked_{$bonus_deal_component_key}_{$order_promotion_bonus.pid}" family_key="{$order_promotion_bonus.family_key}" product_key="{$order_promotion_bonus.product_key}" product_code="{$order_promotion_bonus.code}" pid="{$order_promotion_bonus.pid}" onclick="change_order_promotion_bonus(this,1)" style="{if $order_promotion_bonus.selected}display:none{/if}" class="checkbox checkbox_unchecked" src="art/icons/checkbox_unchecked.png"> </td>
		</tr>
		{/foreach} 
	</tbody>
	{elseif $component_order_promotion_bonus.type=='product'} 
	<tbody class="bonus_product" id="bonus_product_{$bonus_deal_component_key}">
		<tr>
			<td style="padding-right:30px">{$component_order_promotion_bonus.item.deal_info}</td>
			<td style="padding-right:20px">{$component_order_promotion_bonus.item.code}</td>
			<td>{$component_order_promotion_bonus.item.name}</td>
			<td></td>
		</tr>
	</tbody>
	{/if} {/foreach} 
</table>
<div style="clear:both;height:10px">
</div>
