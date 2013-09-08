{include file='header.tpl'} 
<div id="bd">
	{include file='orders_navigation.tpl'} 
	<input type="hidden" id="invoice_list_key" value="{$invoice_list_key}" />
	<input type="hidden" id="parent" value="list" />
	<input type="hidden" id="parent_key" value="{$invoice_list_key}" />
<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1} <a href="orders_server.php?view=dn" id="branch_type_dn" style="{if $block_view!='dn'}display:none{/if}">&#8704; {t}Delivery Notes{/t}</a> <a href="orders_server.php?view=invoices" id="branch_type_invoices" style="{if $block_view!='invoices'}display:none{/if}">&#8704; {t}Invoices{/t}</a> <a href="orders_server.php?view=orders" id="branch_type_orders" style="{if $block_view!='orders'}display:none{/if}">&#8704; {t}Orders{/t}</a> &rarr; {/if} 
			<a href="orders.php?store={$store->id}&view={$block_view}">
			<span id="branch_type2_dn" style="{if $block_view!='dn'}display:none{/if}">{t}Delivery Notes{/t}</span> 
			<span id="branch_type2_invoices" style="{if $block_view!='invoices'}display:none{/if}">{t}Invoices{/t}</span> 
			<span id="branch_type2_orders" style="{if $block_view!='orders'}display:none{/if}">{t}Orders{/t}</span> 
			({$store->get('Store Code')})</a> &rarr; {t}Lists{/t}</span> 
		</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Invoices List{/t}: <span class="id">{$invoice_list_name}</span></span> 
		</div>
		<div class="buttons">
			{if $modify}<button onclick="window.location='edit_invoices.php?list_key={$invoice_list_key}'"><img src="art/icons/table_edit.png" alt="" /> {t}Edit Invoices in List{/t}</button>{/if} <button onclick="window.location='invoices_address_label.pdf.php?label=l7159&scope=list&id={$invoice_list_key}'"><img src="art/icons/printer.png" alt="" /> {t}Print{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="the_table" class="data_table" style="clear:both;margin-top:10px">
					<span class="clean_table_title">{t}Invoices{/t} 
				<img id="export_invoices" tipo="stores" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export table{/t}" alt="{t}Export table{/t}" src="art/icons/export_csv.gif"></span> 
			
					<div class="elements_chooser">
						<img class="menu" id="invoice_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
						<div id="invoice_type_chooser" style="{if $elements_invoice_elements_type!='type'}display:none{/if}">
							<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Invoice}selected{/if} label_elements_type_Invoice" id="elements_invoice_type_Invoice" table_type="Invoice">{t}Invoices{/t} (<span id="elements_invoice_type_Invoice_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Refund}selected{/if} label_elements_type_Refund" id="elements_invoice_type_Refund" table_type="Refund">{t}Refunds{/t} (<span id="elements_invoice_type_Refund_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="invoice_payment_chooser" style="{if $elements_invoice_elements_type!='payment'}display:none{/if}">
							<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Yes}selected{/if} label_elements_payment_Yes" id="elements_invoice_payment_Yes" table_type="Yes">{t}Paid{/t} (<span id="elements_invoice_payment_Yes_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Partially}selected{/if} label_elements_payment_Partially" id="elements_invoice_payment_Partially" table_type="Partially">{t}Partially Paid{/t} (<span id="elements_invoice_payment_Partially_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.No}selected{/if} label_elements_payment_No" id="elements_invoice_payment_No" table_type="No">{t}Waiting Payment{/t} (<span id="elements_invoice_payment_No_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
					</div>
				
				<div class="table_top_bar space" >
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
				</div>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'}