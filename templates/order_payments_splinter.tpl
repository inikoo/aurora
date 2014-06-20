	{if $order->get_number_payments()>0}
	<div style="padding: 10px 10px 15px 10px;font-size:85%;;margin-bottom:10px;border:1px solid #ccc;margin-top:20px;">
			<table class="edit" id="pending_payment_confirmations" border="0" style="margin-top:0px;padding-top:0px;width:100%;">
				<tr>
					<td colspan="6"> <span id="table_title_items" class="clean_table_title" ">{t}Payments{/t}</span> </td>
				</tr>
				<tr class="title">
					<td>{t}Payment ID{/t}</td>
					<td>{t}Service Provider{/t}</td>
					<td>{t}Date{/t}</td>
					<td>{t}Status{/t}</td>
					<td>{t}Amount{/t}</td>
					<td>{t}Notes{/t}</td>
				</tr>
				{foreach from=$invoice->get_payment_objects('',true,true) item=payment} 
				<tr id="payment_{$payment->get('Payment Key')}" class="payment" payment_key="{$payment->get('Payment Key')}">
					<td>{$payment->get('Payment Key')}</td>
					<td>{$payment->payment_service_provider->get('Payment Service Provider Name')}</td>
					<td id="payment_date_{$payment->get('Payment Key')}">{$payment->get('Created Date')}</td>
					<td>{$payment->get('Payment Transaction Status')}</td>
					<td id="payment_amount_{$payment->get('Payment Key')}">{$payment->get('Amount')}</td>
					<td style="width:300px"> 
					<div class="buttons small" style="{if $payment->get('Payment Transaction Status')!='Pending'}display:none{/if}">
						<button class="negative" onclick="cancel_payment({$payment->get('Payment Key')})">{t}Set as cancelled{/t}</button> <button class="positive" onclick="confirm_payment({$payment->get('Payment Key')})">{t}Set as completed{/t}</button> 
					</div>
					</td>
				</tr>
				{/foreach} 
			</table>
		</div>
{/if}