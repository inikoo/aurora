{include file='header.tpl'} 
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="parent_key" value="{$store->id}" />
<input type="hidden" id="parent" value="store" />
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='contacts_navigation.tpl'} 
		<div class="branch">

			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php?view=orders" id="branch_type_orders" ">&#8704; {t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> &rarr;{t}Pending Orders{/t} ({$store->get('Store Code')})</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons">
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title no_buttons">{t}Pending Orders{/t} <span class="id">{$store->get('Store Code')}</span> </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px;display:none">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='pending_orders'}selected{/if}" id="pending_orders"> <span> {t}Pending Orders{/t}</span></span></li>
		</ul>
		<div class="tabs_base">
	</div>
	</div>
	<div style="padding:0 20px">
		<div style="padding:15px 0 30px 0;{if !($block_view=='pending_orders')  }display:none{/if}" id="pending_orders_block">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}Pending Orders{/t} 
				</span> 
					<div class="elements_chooser">
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Packed}selected{/if} label_Packed" id="elements_Packed" table_type="Packed">{t}Packed{/t} (<span id="elements_Packed_number"><img style="width:12.9px" src="art/loading.gif"/></span>)</span> 
						<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InWarehouse}selected{/if} label_InWarehouse" id="elements_InWarehouse" table_type="InWarehouse">{t}In Warehouse{/t} (<span id="elements_InWarehouse_number"><img style="width:12.9px" src="art/loading.gif"/></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.SubmittedbyCustomer}selected{/if} label_SubmittedbyCustomer" id="elements_SubmittedbyCustomer" table_type="SubmittedbyCustomer">{t}Submitted by Customer{/t} (<span id="elements_SubmittedbyCustomer_number"><img style="width:12.9px" src="art/loading.gif"/></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcess}selected{/if} label_InProcess" id="elements_InProcess" table_type="InProcess">{t}In Process{/t} (<span id="elements_InProcess_number"><img style="width:12.9px" src="art/loading.gif"/></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcessbyCustomer}selected{/if} label_InProcessbyCustomer" id="elements_InProcessbyCustomer" table_type="InProcessbyCustomer">{t}In Website{/t} (<span id="elements_InProcessbyCustomer_number"><img style="width:12.9px" src="art/loading.gif"/></span>)</span> 
					</div>
				
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
				<div id="table1" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_cancel" style="padding:15px 20px 5px 10px;width:275px">
	<input type="hidden" id="cancel_order_key" value="">
	<div id="cancel_msg">
	</div>
	<table class="edit" style="width:100%">
		<tr class="title">
			<td colspan="2">{t}Cancel Order{/t}</td>
		</tr>
		<tr >
			<td colspan="2" style="font-size:90%;color:#777" id="cancel_order_number_label"></td>
		</tr>
		<tr class="space5">
			<td colspan="2">{t}Reasons for cancellation{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="cancel_input" onkeyup="change_reason_cancel(event,this,'cancel')"></textarea> </td>
		</tr>
		<tr id="cancel_buttons">
			<td colspan="2"> 
			<div class="buttons">
				<button onclick="save_cancel()" id="cancel_save" class="positive disabled">{t}Cancel Order{/t}</button> <button class="negative" onclick="dialog_cancel.hide()">{t}Close{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px;display:none" id="cancel_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> {t}Processig Request{/t} </td>
		</tr>
	</table>
</div>

{include file='footer.tpl'} 