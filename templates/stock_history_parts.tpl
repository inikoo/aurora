{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" value="{$warehouse->id}" id="warehouse_id" />
				<input type="hidden" value="{$warehouse->id}" id="warehouse_key" />

		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Inventory{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				</div>
			<div class="buttons" style="float:left">
				<span class="main_title"> <span class="id">{$warehouse->get('Warehouse Name')}</span> {t}Inventory{/t} <span style="font-style:italic">({t}Parts{/t})</span> </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
			<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span></li>

		<li> <span class="item {if $block_view=='parts'}selected{/if}" id="parts"> <span> {t}Parts{/t}</span></span></li>
		<li> <span class="item {if $block_view=='movements'}selected{/if}" id="movements"> <span> {t}Movements{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_parts" style="{if $block_view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;">
			<span class="clean_table_title">{t}Parts{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
		
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $parts_view=='general'}selected{/if}" id="parts_general" name="general">{t}Description{/t}</button> <button class="{if $parts_view=='stock'}selected{/if}" id="parts_stock" name="stock">{t}Stock{/t}</button> <button class="{if $parts_view=='locations'}selected{/if}" id="parts_locations" name="locations">{t}Locations{/t}</button> <button class="{if $parts_view=='sales'}selected{/if}" id="parts_sales" name="sales">{t}Sales{/t}</button> <button class="{if $parts_view=='forecast'}selected{/if}" id="parts_forecast" name="forecast">{t}Forecast{/t}</button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
	</div>
	<div id="block_movements" style="{if $block_view!='movements'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		
			<span class="clean_table_title">{t}Part Movements{/t}</span>
			<div id="table_type" class="table_type">
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='all_transactions'}selected{/if}" id="restrictions_all_transactions" table_type="all_transactions">{t}All{/t} (<span id="transactions_all_transactions"></span><img id="transactions_all_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='oip_transactions'}selected{/if}" id="restrictions_oip_transactions" table_type="oip_transactions">{t}OIP{/t} (<span id="transactions_oip_transactions"></span><img id="transactions_oip_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='out_transactions'}selected{/if}" id="restrictions_out_transactions" table_type="out_transactions">{t}Out{/t} (<span id="transactions_out_transactions"></span><img id="transactions_out_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='in_transactions'}selected{/if}" id="restrictions_in_transactions" table_type="in_transactions">{t}In{/t} (<span id="transactions_in_transactions"></span><img id="transactions_in_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='audit_transactions'}selected{/if}" id="restrictions_audit_transactions" table_type="audit_transactions">{t}Audits{/t} (<span id="transactions_audit_transactions"></span><img id="transactions_audit_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='move_transactions'}selected{/if}" id="restrictions_move_transactions" table_type="move_transactions">{t}Movements{/t} (<span id="transactions_move_transactions"></span><img id="transactions_move_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
			</div>
		</div>
			<div class="table_top_bar">
				</div>
				
				<div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">
			
				<div style="position:relative;left:18px;margin-top:10px">
					<span id="clear_intervalt" style="font-size:80%;color:#777;cursor:pointer;{if $to_transactions=='' and $from_transactions=='' }display:none{/if}">{t}clear{/t}</span> {t}Interval{/t}: 
					<input id="v_calpop1t" type="text" class="text" size="11" maxlength="10" name="from" value="{$from_transactions}" />
					<img style="height:14px;bottom:1px;left:-19px;" id="calpop1t" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <span class="calpop" style="margin-left:4px">&rarr;</span> 
					<input class="calpop" id="v_calpop2t" size="11" maxlength="10" type="text" name="to" value="{$to_transactions}" />
					<img style="height:14px;bottom:1px;left:-37px;" id="calpop2t" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" id="submit_intervalt" alt="{t}Go{/t}" /> 
				</div>
			
			<div id="cal1tContainer" style="position:absolute;display:none; z-index:2;;right:70px">
			</div>
			<div style="position:relative;right:-58px">
				<div id="cal2tContainer" style="display:none; z-index:2;position:absolute">
				</div>
			</div>
		</div>
				
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
	
	<div id="block_overview" style="{if $block_view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	
	
	
	
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a></li>
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

{include file='footer.tpl'} 