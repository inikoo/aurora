<div style="width:450px">
	<table border="0" class="info_block">
		<tr>
			<td>{t}Created{/t}:</td>
			<td class="aright">{$order->get('Created Date')}</td>
		</tr>
		{if $order->get('Order Current Dispatch State')=='In Process by Customer' } 
		<tr>
			<td>{t}Last updated{/t}:</td>
			<td class="aright">{$order->get('Last Updated Date')}</td>
		</tr>
		<tr style="border-top:1px solid #ccc">
			<td>{t}On website{/t}:</td>
			<td class="aright">{$order->get('Interval Last Updated Date')}</td>
		</tr>
		{elseif $order->get('Order Current Dispatch State')=='Waiting for Payment Confirmation'} 
		<tr>
			<td>{t}Submit Payment{/t}:</td>
			<td class="aright">{$order->get('Checkout Submitted Payment Date')}</td>
		</tr>
		{else} 
		<tr>
			<td>{t}Last updated{/t}:</td>
			<td class="aright">{$order->get('Last Updated Date')}</td>
			<td class="aright">{$order->get('Interval Last Updated Date')}</td>
		</tr>
		{/if} 
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
