<div style="{if $order->data['Order Invoiced']=='Yes'}display:none{/if}">
				<table border="0" class="info_block">
						<tr {if $order->
						get('Order Out of Stock Net Amount')==0 }style="display:none"{/if} id="tr_order_items_out_of_stock" > 
						<td class="aright">{t}Out of stock{/t}</td>
						<td width="100" class="aright"><span id="order_items_out_of_stock">{$order->get('Out of Stock Net Amount')}</span></td>
					</tr>
					
					<tr {if $order->
						get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross" > 
						<td class="aright">{t}Items Gross{/t}</td>
						<td width="100" class="aright" id="order_items_gross">{$order->get('Items Gross Amount After No Shipped')}</td>
					</tr>
					<tr {if $order->
						get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
						<td class="aright">{t}Discounts{/t}</td>
						<td width="100" class="aright">-<span id="order_items_discount">{$order->get('Items Discount Amount')}</span></td>
					</tr>
				
					<tr>
						<td class="aright">{t}Items Net{/t}</td>
						<td width="100" class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td>
					</tr>

                    <tr {if $order->get('Order Deal Amount Off')==0 }style="display:none"{/if} id="tr_order_amount_off" > 
						<td class="aright">{t}Ammount Off{/t}</td>
						<td width="100" class="aright"><span id="order_items_discount">{$order->get('Deal Amount Off')}</span></td>
					</tr>
					
					<tr id="tr_order_credits" {if $order->
						get('Order Net Credited Amount')==0}style="display:none"{/if}> 
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_credits" /> {t}Credits{/t}</td>
						<td width="100" class="aright" id="order_credits">{$order->get('Net Credited Amount')}</td>
					</tr>
					<tr id="tr_order_items_charges">
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_items_charges" /> {t}Charges{/t}</td>
						<td id="order_charges" width="100" class="aright">{$order->get('Charges Net Amount')}</td>
					</tr>
					<tr id="tr_order_shipping">
						<td class="aright"> <img style="{if $order->get('Order Shipping Method')=='On Demand'}visibility:visible{else}visibility:hidden{/if};cursor:pointer" src="art/icons/edit.gif" id="edit_button_shipping" /> {t}Shipping{/t}</td>
						<td id="order_shipping" width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
					</tr>
					<tr {if $order->get('Order Insurance Net Amount')==0 }style="display:none"{/if} id="tr_order_insurance" > 
						<td class="aright"> {t}Insurance{/t}</td>
						<td id="order_insurance" width="100" class="aright">{$order->get('Insurance Net Amount')}</td>
					</tr>
					<tr style="border-top:1px solid #777">
						<td class="aright">{t}Net{/t}</td>
						<td id="order_net" width="100" class="aright">{$order->get('Balance Net Amount')}</td>
					</tr>
					<tr id="tr_order_tax" style="border-bottom:1px solid #777">
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_tax" /> <span id="tax_info">{$order->get_formated_tax_info_with_operations()}</span></td>
						<td id="order_tax" width="100" class="aright">{$order->get('Balance Tax Amount')}</td>
					</tr>
					<tr style="border-bottom:1px solid #777">
						<td class="aright">{t}Total{/t}</td>
						<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Balance Total Amount')}</td>
					</tr>
					<tr id="tr_order_total_paid" style="border-top:1px solid #777;">
						<td class="aright"><img id="order_paid_info" style="height:14px;vertical-align:-1.5px" src="art/icons/information.png" title="{$order->get('Order Current XHTML Payment State')}"> {t}Paid{/t}</td>
						<td id="order_total_paid" width="100" class="aright">{$order->get('Payments Amount')}</td>
					</tr>
					<tr id="tr_order_total_to_pay" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"> 
						<div class="buttons small left" >
							<button style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="show_add_payment" amount="{$order->get('Order To Pay Amount')}" onclick="add_payment('order','{$order->id}')"><img src="art/icons/add.png"> {t}Payment{/t}</button> 
						</div>
						<span style="{if $order->get('Order To Pay Amount')>0}display:none{/if}" id="to_refund_label">{t}To Refund{/t}</span> 
						<span style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="to_pay_label">{t}To Pay{/t}</span></td>
						<td id="order_total_to_pay" width="100" class="aright" style="font-weight:800">{$order->get('To Pay Amount')}</td>
					</tr>
				</table>
				<div class="buttons small" style="display:none;{if $has_credit}display:none;{/if}clear:both;margin:0px;padding-top:10px">
					<button id="add_credit" style="margin:0px;">{t}Add debit/credit{/t}</button> 
				</div>
			</div>
			<div style="{if $order->data['Order Invoiced']=='No'}display:none{/if}">
				<table border="0" class="info_block">
					<tr>
						<td class="aright">{t}Total Ordered{/t}</td>
						<td width="100" class="aright">{$order->get('Total Net Amount')}</td>
					</tr>
					{if $order->get('Order Out of Stock Net Amount')!=0 } 
					<tr>
						<td class="aright">{t}Out of Stock{/t}</td>
						<td width="100" class="aright">{$order->get('Out of Stock Net Amount')}</td>
					</tr>
					{/if} 
					<tr style="font-size:70%;border-top:1px solid #ccc;border-bottom:1px solid #eee;">
						<td style="text-align:right">{t}Invoiced Amounts{/t}</td>
						<td></td>
					</tr>
					<tr>
						<td class="aright">{t}Items{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Items Amount')}</td>
					</tr>
					<tr>
						<td class="aright">{t}Shipping{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
					</tr>
					{if $order->get('Order Invoiced Charges Amount')!=0} 
					<tr>
						<td class="aright">{t}Charges{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
					</tr>
					{/if} 
					<tr {if $order->
						get('Order Invoiced Insurance Amount')==0 }style="display:none"{/if} > 
						<td class="aright"> {t}Insurance{/t}</td>
						<td id="order_insurance" width="100" class="aright">{$order->get('Invoiced Insurance Amount')}</td>
					</tr>
					{if $order->get('Order Invoiced Refund Net Amount')!=0} 
					<tr>
						<td class="aright"><i>{t}Refunds (N){/t}</i></td>
						<td width="100" class="aright">{$order->get('Invoiced Refund Net Amount')}</td>
					</tr>
					{/if} {if $order->get('Order Invoiced Total Net Adjust Amount')!=0} 
					<tr class="adjust" style="color:red">
						<td class="aright">{t}Adjusts (N){/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Total Net Adjust Amount')}</td>
					</tr>
					{/if} 
					<tr style="border-top:1px solid #bbb">
						<td class="aright">{t}Total Net{/t}</td>
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
					<tr id="tr_order_total_paid_invoiced" style="border-top:1px solid #777;">
						<td class="aright"><img id="order_paid_info_invoiced" src="art/icons/information.png" style="height:14px;vertical-align:-1.5px" title="{$order->get('Order Current XHTML Payment State')}"> {t}Paid{/t}</td>
						<td id="order_total_paid_invoiced" width="100" class="aright">{$order->get('Payments Amount')}</td>
					</tr>
					<tr id="tr_order_total_to_pay_invoiced" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"> 
						
						<span style="{if $order->get('Order To Pay Amount')>0}display:none{/if}" id="to_refund_label_invoiced">{t}To Refund{/t}</span> 
						<span style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="to_pay_label_invoiced">{t}To Pay{/t}</span></td>
						<td id="order_total_to_pay_invoiced" width="100" class="aright" style="font-weight:800">{$order->get('To Pay Amount')}</td>
					</tr>
				</table>
			</div>