<div style="width:450px">
	<table border="0" class="info_block">
		<tr>
			<td>{t}Created{/t}:</td>
			<td class="aright">{$order->get('Created Date')}</td>
			<td></td>
		</tr>
		<tr style="{if $order->get('Order Submitted by Customer Date')==''}display:none{/if}">
			<td>{t}Submitted{/t}:</td>
			<td class="aright">{$order->get('Submitted by Customer Date')}</td>
			<td class="aright">{$order->get('Submitted by Customer Interval')}</td>
		</tr>		
        <tr style="{if $order->get('Order Send to Warehouse Date')==''}display:none{/if}">
			<td>{t}Send to Warehouse{/t}:</td>
			<td class="aright">{$order->get('Send to Warehouse Date')}</td>
			<td class="aright">{$order->get('Send to Warehouse Interval')}</td>

		</tr>		
		<tr style="{if $order->get('Order Packed Done Date')==''}display:none{/if}">
			<td>{t}Packed{/t}:</td>
			<td class="aright">{$order->get('Packed Done Date')}</td>
			<td class="aright">{$order->get('Packed Done Interval')}</td>
		</tr>		
		<tr style="{if $order->get('Order Dispatched Date')==''}display:none{/if}">
			<td>{t}Dispatched{/t}:</td>
			<td class="aright">{$order->get('Dispatched Date')}</td>
			<td class="aright">{$order->get('Dispatched Interval')}</td>
		</tr>
		<tr style="{if $order->get('Order Suspended Date')==''}display:none{/if}">
			<td>{t}Suspended{/t}:</td>
			<td colspan=2 class="aright">{$order->get('Order Suspended Date')}</td>
		</tr>		
		<tr style="{if $order->get('Order Cancelled Date')==''}display:none{/if}">
			<td>{t}Cancelled{/t}:</td>
			<td colspan=2 class="aright">{$order->get('Order Cancelled Date')}</td>
		</tr>
		<tr style="{if $order->get('Order Post Transactions Dispatched Date')==''}display:none{/if}">
			<td>{t}Replacements Dispatched{/t}:</td>
			<td colspan=2 class="aright">{$order->get('Post Transactions Dispatched Date')}</td>
		</tr>		
		
		
	</table>
	<table border="0" class="info_block">
		<tr>
			<td>{t}Customer Fiscal Name{/t}:</td>
			<td class="aright">{$order->get('Order Customer Fiscal Name')}</td>
		</tr>
		<tr>
			<td>{t}Tax Number{/t}:</td>
			<td class="aright" id="update_order_tax_number_value">{$order->get('Order Tax Number')}</td>
			<td class="aright"><img id="update_order_tax_number" xonClick="show_set_tax_number_dialog_from_details()" style="cursor:pointer" src="art/icons/edit.gif"></td>
			{*}Use sema method as for change the tax number when is othe rEU state{*}
		</tr>
		<tr>
			<td>{t}Customer Name{/t}:</td>
			<td class="aright">{$order->get('Order Customer Name')}</td>
		</tr>
		<tr>
			<td>{t}Contact Name{/t}:</td>
			<td class="aright">{$order->get('Order Customer Contact Name')}</td>
		</tr>
		<tr>
			<td>{t}Telephone{/t}:</td>
			<td class="aright">{$order->get('Order Telephone')}</td>
		</tr>
		<tr>
			<td>{t}Email{/t}:</td>
			<td class="aright">{$order->get('Order Email')}</td>
		</tr>
	</table>
	<table border="0" class="info_block">
		<tr>
			<td>{t}Tax Code{/t}:</td>
			<td class="aright">{$order->get('Order Tax Code')} {$order->get('Tax Rate')} </td>
		</tr>
		<tr>
			<td>{t}Tax Info{/t}:</td>
			<td class="aright">{$order->get('Order Tax Name')}</td>
		</tr>
	</table>
	<table border="0" class="info_block">
		<tr>
			<td>{t}Weight {/t}:</td>
			<td class="aright">{$order->get('Weight')}</td>
		</tr>
	</table>
</div>
