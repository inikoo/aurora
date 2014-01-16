{include file='header.tpl'} 
<div id="bd">
	{include file='top_search_splinter.tpl'} 
	<input type="hidden" id="method" value="{$warehouse->get('Warehouse Picking Aid Type')}">
		<input type="hidden" id="warehouse_key" value="{$warehouse->id}">

	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr;
		{if $user->get('User Type')!='Warehouse'}
			{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="inventory.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr;{/if} {t}Pending Orders{/t}</span> 
	</div>
	<div id="orders_table" class="data_table" style="clear:both;margin-top:23px">
		<span class="clean_table_title">{t}Pending Orders{/t} <img id="export_csv0" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
		<div  class="elements_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.ReadytoShip}selected{/if} label_dn_state_ready_to_ship" id="elements_ready_to_ship" table_type="ready_to_ship">{t}Ready to Ship{/t} (<span id="elements_ready_to_ship_number">{$elements_number.ReadytoShip}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Done}selected{/if} label_dn_state_done" id="elements_done" table_type="done">{t}Done{/t} (<span id="elements_done_number">{$elements_number.Done}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.PickingAndPacking}selected{/if} label_dn_state_picking_and_packing" id="elements_picking_and_packing" table_type="picking_and_packing">{t}Picking/Packing{/t} (<span id="elements_notes_number">{$elements_number.PickingAndPacking}</span>)</span> 
				<span style="float:right;margin-left:20px;{if !$elements_number.ReadytoRestock}display:none{/if}" class=" table_type transaction_type state_details {if $elements.ReadytoRestock}selected{/if} label_dn_state_ready_to_restock" id="elements_ready_to_restock" table_type="ready_to_restock">{t}Ready to Restock{/t} (<span id="elements_notes_number">{$elements_number.ReadytoRestock}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.ReadytoPack}selected{/if} label_dn_state_ready_to_pack" id="elements_ready_to_pack" table_type="ready_to_pack">{t}Ready to Pack{/t} (<span id="elements_notes_number">{$elements_number.ReadytoPack}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.ReadytoPick}selected{/if} label_dn_state_ready_to_pick" id="elements_ready_to_pick" table_type="ready_to_pick">{t}Ready to Pick{/t} (<span id="elements_notes_number">{$elements_number.ReadytoPick}</span>)</span> 
			
		</div>
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
		</div>
		<table style="float:left;margin:0 0 0 0px ;padding:0;height:15px;" class="options">
			<tr>
			</tr>
		</table>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
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
{include file='assign_picker_packer_splinter.tpl'} 
{include file='footer.tpl'} 