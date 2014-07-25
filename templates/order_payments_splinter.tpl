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
			<input type="hidden" id="payment_amount_{$payment->id}" value="{$payment->get('Payment Amount')}"  />
			<input type="hidden" id="payment_refunded_amount_{$payment->id}" value="{$payment->get('Payment Refund')}"  />
			<input type="hidden" id="payment_max_refund_amount_{$payment->id}" value="{$payment->get('Max Payment to Refund')}"  />
			<input type="hidden" id="payment_online_refund_{$payment->id}" value="{$payment->payment_account->get('Payment Account Online Refund')}"  />

			</td>
			<td>{$payment->payment_service_provider->get('Payment Service Provider Name')} ({$payment->get('Method')})</td>
			<td><span id="payment_date_{$payment->get('Payment Key')}">{$payment->get('Created Date')}</span></td>
			<td><span id="payment_status_{$payment->get('Payment Key')}">{$payment->get('Payment Transaction Status')}</span></td>
			<td style="text-align:right"><span id="payment_amount_{$payment->get('Payment Key')}">{$payment->get('Amount')}</span></td>
			<td><span id="payment_reference_{$payment->get('Payment Key')}">{if $payment->get('Payment Type')!='Payment'}{$payment->get_parent_info()}{/if}{if $payment->get('Payment Transaction ID')!='' and $payment->get('Payment Type')!='Payment'}, {/if}{$payment->get('Payment Transaction ID')}</span></td>
			<td style="width:200px"> 
			<div class="buttons small left">
				<button style="{if !( $payment->get('Payment Transaction Status')=='Pending')}display:none{/if}" class="negative" onclick="cancel_payment({$payment->get('Payment Key')})">{t}Set as cancelled{/t}</button> 
				<button style="{if !( $payment->get('Payment Transaction Status')=='Pending')}display:none{/if}" class="positive" onclick="complete_payment({$payment->get('Payment Key')})">{t}Set as completed{/t}</button> 
				<button id="add_refund_{$payment->id}" class="{if  $order->get('Order To Pay Amount')<0}positive{/if}" style="{if  $payment->get('Payment Type')!='Payment' or  $payment->get('Payment Method')=='Account' or $payment->get('Payment Transaction Status')!='Completed'}display:none{/if}" onclick="refund_payment({$payment->get('Payment Key')})"><img style="height:12.5px;width:12.5px" src="art/icons/add.png"> {t}Refund{/t}</button> 
				<button id="add_credit_{$payment->id}" class="{if  $order->get('Order To Pay Amount')<0}positive{/if}" style="{if  $payment->get('Payment Type')!='Payment' or  $payment->get('Payment Method')=='Account' or $payment->get('Payment Transaction Status')!='Completed'}display:none{/if}" onclick="credit_payment({$payment->get('Payment Key')})"><img style="height:12.5px;width:12.5px"  src="art/icons/add.png"> {t}Credit{/t}</button> 

				<button class="positive" style="{if  !( $payment->get('Payment Balance')!=0  and $order->get_invoices_to_pay_abs_amount()!=0)  }display:none{/if}" onclick="pay_invoice({$payment->get('Payment Key')})">{t}Pay Invoice{/t}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
{/if}