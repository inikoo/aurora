 {if $order->get_number_payments()>0} 
<div style="padding: 0px 10px 15px 10px;font-size:85%;;margin-bottom:10px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;margin-top:20px;">
	<table class="edit" id="pending_payment_confirmations" border="0" style="margin-top:0px;padding-top:0px;width:100%;">
		<tr>
			<td colspan="7"> <span id="table_title_items" class="clean_table_title" style="font-size:140%">{t}Payments{/t}</span> </td>
		</tr>
		<tr class="title">
			<td>{t}Payment ID{/t}</td>
			<td>{t}Method{/t}</td>
			<td>{t}Date{/t}</td>
			<td>{t}Status{/t}</td>
			<td style="text-align:right">{t}Amount{/t}</td>
			<td>{t}Reference{/t}</td>
			<td>{t}Operations{/t}</td>
		</tr>
		{foreach from=$order->get_payment_objects('',true,true) item=payment} 
		<tr id="payment_{$payment->get('Payment Key')}" class="payment" payment_key="{$payment->get('Payment Key')}">
			<td>{$payment->get('Payment Key')} 
			<input type="hidden" id="payment_amount_{$payment->id}" value="{$payment->get('Payment Amount')}" />
			<input type="hidden" id="payment_refunded_amount_{$payment->id}" value="{$payment->get('Payment Refund')}" />
			<input type="hidden" id="payment_max_refund_amount_{$payment->id}" value="{$payment->get('Max Payment to Refund')}" />
			<input type="hidden" id="payment_online_refund_{$payment->id}" value="{$payment->payment_account->get('Payment Account Online Refund')}" />
			</td>
			<td>{$payment->payment_service_provider->get('Payment Service Provider Name')} ({$payment->get('Method')})</td>
			<td><span id="payment_date_{$payment->get('Payment Key')}">{$payment->get('Created Date')}</span></td>
			<td><span id="payment_status_{$payment->get('Payment Key')}">{$payment->get('Payment Transaction Status')}</span></td>
			<td style="text-align:right"><span id="payment_amount_{$payment->get('Payment Key')}">{$payment->get('Amount')}</span></td>
			<td><span id="payment_reference_{$payment->get('Payment Key')}">{if $payment->get('Payment Type')!='Payment'}{$payment->get_parent_info()}{/if}{if $payment->get('Payment Transaction ID')!='' and $payment->get('Payment Type')!='Payment'}, {/if}{$payment->get('Payment Transaction ID')}</span></td>
			<td style="width:200px"> 
			<div class="buttons small left">
			{if $payment->get('Payment Transaction Status')=='Cancelled' or $payment->get('Payment Transaction Status')=='Declined' or $payment->get('Payment Transaction Status')=='Errors'}
				{$payment->get('Payment Transaction Status Info')}
			{else}
				<button style="{if !( $payment->get('Payment Transaction Status')=='Pending')}display:none{/if}" class="negative" onclick="cancel_pending_payment({$payment->get('Payment Key')})">{t}Set as cancelled{/t}</button> 
				<button id="complete_payment_{$payment->id}" style="{if !( $payment->get('Payment Transaction Status')=='Pending')}display:none{/if}" class="positive" onclick="show_complete_payment_dialog({$payment->get('Payment Key')})">{t}Set as completed{/t}</button> 
				
				{if  $payment->get('Payment Type')=='Payment' and  $payment->get('Payment Method')!='Account' and $payment->get('Payment Transaction Status')=='Completed'  }
				
					{if  ($order->get('Order To Pay Amount')- $order->get_to_refund_amount())<0}
						<button style="margin-bottom:5px"  id="add_refund_{$payment->id}" class="{if  $order->get('Order To Pay Amount')<0}positive{/if}"  onclick="refund_payment({$payment->get('Payment Key')})"><img style="height:12.5px;width:12.5px" src="art/icons/add.png"> {t}Refund{/t}</button> 
						<button style="margin-bottom:5px" id="add_credit_{$payment->id}" class="{if  $order->get('Order To Pay Amount')<0}positive{/if}" onclick="credit_payment({$payment->get('Payment Key')})"><img style="height:12.5px;width:12.5px" src="art/icons/add.png"> {t}Credit{/t}</button> 
					{else}
											<button style="margin-bottom:5px"  id="add_refund_{$payment->id}" class="{if  $order->get('Order To Pay Amount')<0}positive{/if}"  onclick="refund_payment({$payment->get('Payment Key')})"><img style="height:12.5px;width:12.5px" src="art/icons/add.png"> {t}Refund{/t}</button> 

					{/if}
					{if $order->get_to_refund_amount()<0 }
						{foreach from=$invoices_data item=invoice} 
						{if  $invoice.to_pay<0}
						<button  style="margin-bottom:5px" id="pay_refund_{$payment->id}"  onclick="pay_refund({$invoice.key},{$payment->get('Payment Key')},{$invoice.to_pay})">{t}Refund{/t} {$invoice.number}</button> 
						<button  style="margin-bottom:5px"  id="pay_refund_to_customer_account_{$payment->id}"  onclick="pay_refund_to_customer_account({$invoice.key},{$payment->get('Payment Key')},{$invoice.to_pay})">{t}Credit{/t} {$invoice.number}</button> 
						{/if}
						{/foreach}
					{/if}
				{/if}
				<button id="cancel_payment_{$payment->id}" style="{if !( $payment->get('Payment Transaction Status')=='Completed' and $payment->get('Payment Submit Type')=='Manual')}display:none{/if}" class="negative" onclick="show_cancel_payment_dialog({$payment->get('Payment Key')})">{t}Cancel{/t}</button>
			{/if}
				
				
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
{/if}

{*}
									<button class="positive" style="{if  !( $payment->get('Payment Balance')!=0  and $order->get_invoices_to_pay_abs_amount()!=0)  }display:none{/if}" onclick="pay_invoice({$payment->get('Payment Key')})">{t}Pay Invoice{/t}</button> 
								<button class="positive" style="{if  !( $payment->get('Payment Balance')!=0  and $order->get_invoices_to_pay_abs_amount()!=0)  }display:none{/if}" onclick="pay_invoice({$payment->get('Payment Key')})">{t}Pay Refund{/t}</button> 

{*}
