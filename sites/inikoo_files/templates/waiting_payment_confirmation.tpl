<div style="padding:20px">
	<div id="waiting_container">
		<div style="float:left">
			<img style="width:100px" src="art/loading_big.gif"> 
		</div>
		<div style="margin-left:50px;float:left">
			<h2>
				Order {$order->get('Order Public ID')}
			</h2>
			<h3>
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:-1px"> {$order->get('order customer name')}, {$customer->get('Customer Main Contact Name')}, <span class="id">C{$customer->get_formated_id()}</span> 
			</h3>
			<h3>
				{t}Total{/t}: {$order->get('Balance Total Amount')} 
			</h3>
			
			<h1 style="margin-top:20px">
				{t}Waiting for payment to complete{/t}
			</h1>
			
			
			<table id="pending_payment_confirmations">
			<tr class="title">
			<td>{t}Payment ID{/t}</td><td>{t}{t}Service Provider{/t}{/t}</td><td>{t}Date{/t}</td><td></td><td></td>
			</tr>
			{foreach from=$order->get_payment_objects('Pending',true,true) item=payment}
			<tr class="payment" payment_key="{$payment->get('Payment Key')}">
			<td >{$payment->get('Payment Key')}</td>
			<td>{$payment->payment_service_provider->get('Payment Service Provider Name')}</td>
			<td id="payment_date_{$payment->get('Payment Key')}">{$payment->get('Created Date')}</td>
			<td id="payment_date_interval_{$payment->get('Payment Key')}">{$payment->get_formated_time_lapse('Created Date')}</td>
			<td><div class="buttons"><button class="negative" onClick="cancel_payment({$payment->get('Payment Key')})">{t}Set as cancelled{/t}</button></div></td>
			
			
			</tr>
			{/foreach}
			</table>
			
		</div>
		<div style="clear:both">
		</div>
	</div>
</div>
