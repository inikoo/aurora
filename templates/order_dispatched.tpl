{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="order_key" value="{$order->id}" />
	{include file='orders_navigation.tpl'} 
<div  class="branch"> 
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  
		{if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if}
		<a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> &rarr;
		{$order->get('Order Public ID')} ({$order->get('Current Dispatch State')})</span> 
	</div>
	
	<div class="top_page_menu" style="border:none">
	
		 <div class="buttons" style="float:left">
		  <span class="main_title">{t}Dispatched Order{/t} <span class="id">{$order->get('Order Public ID')}</span></span>
    </div>
		<div class="buttons">
					{*}<a style="height:14px" href="order.pdf.php?id={$order->id}" target="_blank"><img style="width:40px;height:12px" src="art/pdf.gif" alt=""></a> {*}
			<button style="{if $order->get_number_post_order_transactions()}display:none;{/if}" onclick="window.location='new_post_order.php?id={$order->id}'"><img src="art/icons/page_white_edit.png" alt=""> {t}Create Post Dispatch Operations{/t}</button> 

		</div>
		<div style="clear:both">
		</div>
	</div>
	

	<div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 5px 0 10px 0">
		<div style="border:0px solid #ddd;width:380px;float:left">
			
			<h2 style="padding:0">
				{$order->get('Order Customer Name')} <a class="id" href="customer.php?id={$order->get("order customer key")}">{$customer->get_formated_id()}</a>
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 30px 0 0px;color:#444">
				<span style="font-weight:500;color:#000"><b>{$order->get('Order Customer Contact Name')}</b><br />
				{$customer->get('Customer Main XHTML Address')}
				</span>
			</div>
			<div style="float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444">
				<span style="font-weight:500;color:#000">{t}Shipped to{/t}</span>:<br />
				{$order->get('Order XHTML Ship Tos')}
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="border:0px solid #ddd;width:265px;float:right">
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr>
					<td class="aright">{t}Total Ordered (N){/t}</td>
					<td width="100" class="aright">{$order->get('Total Net Amount')}</td>
				</tr>
				{if $order->get('Order Out of Stock Net Amount')!=0 } 
				<tr>
					<td class="aright">{t}Out of Stock (N){/t}</td>
					<td width="100" class="aright">-{$order->get('Out of Stock Net Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td colspan="2" style="font-size:70%;border-top:1px solid #ccc;border-bottom:1px solid #eee">{t}Invoiced Amounts{/t}</td>
				</tr>
				<tr>
					<td class="aright">{t}Items (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Items Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Shipping (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
				</tr>
				{if $order->get('Order Invoiced Charges Amount')!=0} 
				<tr>
					<td class="aright">{t}Charges (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
				</tr>
				{/if} {if $order->get('Order Invoiced Refund Net Amount')!=0} 
				<tr>
					<td class="aright"><i>{t}Refunds (N){/t}</i></td>
					<td width="100" class="aright">{$order->get('Invoiced Refund Net Amount')}</td>
				</tr>
				{/if} 
				{if $order->get('Order Invoiced Total Net Adjust Amount')!=0} 
				<tr class="adjust" style="color:red">
					<td class="aright">{t}Adjusts (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Net Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr style="border-top:1px solid #bbb">
					<td class="aright">{t}Total (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Net Amount')}</td>
				</tr>
				{if $order->get('Order Invoiced Refund Tax Amount')!=0} 
				<tr>
					<td class="aright"><i>{t}Refunds (Tax){/t}</i></td>
					<td width="100" class="aright">{$order->get('Invoiced Refund Tax Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Tax{/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Tax Amount')}</td>
				</tr>
				{if $order->get('Order Invoiced Total Tax Adjust Amount')!=0} 
				<tr class="adjust" style="color:red">
					<td class="aright">{t}Tax Adjusts{/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Tax Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Total{/t}</td>
					<td width="100" class="aright"><b>{$order->get('Invoiced Total Amount')}</b></td>
				</tr>
				
				
				
				
				
			</table>
		</div>
		<div style="border:0px solid red;width:265px;float:right">
			{if isset($note)}
			<div class="notes">
				{$note}
			</div>
			{/if} 
			<table border="0" style="border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding-right:0px;margin-right:30px;float:right">
				<tr>
					<td>{t}Order Date{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				<tr>
					<td>{t}Invoices{/t}:</td>
					<td class="aright">{$order->get('Order XHTML Invoices')}</td>
				</tr>
				<tr>
					<td>{t}Delivery Notes{/t}:</td>
					<td class="aright">{$order->get('Order XHTML Delivery Notes')}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	
	{*}
	<div id="msg_dispatched_post_transactions" style="{if !$order->get_number_post_order_transactions()}display:none;{/if}border:1px solid #fd4646;padding:5px 10px;background:#ff6969;color:#fff;xtext-align:center;text-weight:800">
		{t}This order has some post transactions{/t} <span onclick="show_dispatched_post_transactions()" style="font-size:90%;cursor:pointer">({t}Show details){/t}</span> 
	</div>
{*}

	<div style="{if !$order->get_number_post_order_transactions()}display:none;{/if}border:1px solid #fd7777;border-bottom:1px solid #fd7777;padding:5px 5px 10px 5px;background-color:#F5D0DF;" id="dispatched_post_transactions">
		
		<div class="buttons small">
			<button class="negative" onclick="window.location='new_post_order.php?id={$order->id}'"><img src="art/icons/page_white_edit.png" alt=""> {t}Post Dispatch Operations{/t}</button> 

		</div>
		<h2 style="margin-left:5px">
			{t}Post-Order Transactions{/t}
			
			
			
		</h2>
		<div id="table1" class="dtable btable" style="margin-bottom:0;font-size:80%">
		</div>
	</div>
	<h2>
		{t}Ordered Items{/t}
	</h2>
	<div id="table0" class="dtable btable" style="margin-bottom:0;font-size:80%">
	</div>
</div>

</div>
</div>
</div>
{include file='footer.tpl'} 