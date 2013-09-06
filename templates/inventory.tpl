{include file='header.tpl'} 
<div id="bd" style="padding:0px 0px 20px 0px">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" value="{$warehouse->id}" id="warehouse_id" />
		<input type="hidden" value="{$warehouse->id}" id="warehouse_key" />
		<input type="hidden" value="{$warehouse->id}" id="parent_key" />
		<input type="hidden" value="warehouse" id="parent" />
		<input type="hidden" id="parts_table_id" value="2" />
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Inventory{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} 
				<button style="height:25px;width:27px" onclick="window.location='edit_inventory.php?id={$warehouse->id}'"><img style="position:relative;width:18px;height:18px;top:-2px" src="art/icons/cog.png" alt="{t}Configuration{/t}" title="{t}Configuration{/t}"></button> 
				<button onclick="window.location='edit_parts.php?id={$warehouse->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Parts{/t}</button> 
				
				{/if} 
				<button onclick="window.location='warehouse_orders.php?id={$warehouse->id}'"><img src="art/icons/basket_put.png" alt=""> {t}Pick Orders{/t}</button> <button style="display:none" onclick="window.location='parts_movements.php?id={$warehouse->id}'"><img src="art/icons/arrow_switch.png" alt=""> {t}Part Movements{/t}</button> <button style="display:none" onclick="window.location='parts_stats.php?warehouse={$warehouse->id}'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button onclick="window.location='parts_lists.php?warehouse_id={$warehouse->id}'"><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button> <button onclick="window.location='part_categories.php?&warehouse_id={$warehouse->id}'"><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title"><img src="art/icons/warehouse.png" style="height:18px;position:relative;bottom:2px" /> <span class="id">{$warehouse->get('Warehouse Name')}</span> {t}Inventory{/t} <span style="font-style:italic">({t}Parts{/t})</span> </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='parts'}selected{/if}" id="parts"> <span> {t}Parts{/t}</span></span></li>
		<li> <span class="item {if $block_view=='movements'}selected{/if}" id="movements"> <span> {t}Movements{/t}</span></span></li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}Stock History{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_parts" style="{if $block_view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;">
			<span class="clean_table_title">{t}Parts{/t} 
			<img class="export_data_link" id="export_parts" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
			<div class="elements_chooser">
				<img class="menu" id="part_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="part_use_chooser" style="{if $elements_part_elements_type!='use'}display:none{/if}">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_use.NotInUse}selected{/if} label_part_NotInUse" id="elements_NotInUse" table_type="NotInUse">{t}Not In Use{/t} (<span id="elements_NotInUse_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_use.InUse}selected{/if} label_part_InUse" id="elements_InUse" table_type="InUse">{t}In Use{/t} (<span id="elements_InUse_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="part_state_chooser" style="{if $elements_part_elements_type!='state'}display:none{/if}">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.NotKeeping}selected{/if} label_part_NotKeeping" id="elements_NotKeeping" table_type="NotKeeping">{t}NotKeeping{/t} (<span id="elements_NotKeeping_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Discontinued}selected{/if} label_part_Discontinued" id="elements_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.LastStock}selected{/if} label_part_LastStock" id="elements_LastStock" table_type="LastStock">{t}LastStock{/t} (<span id="elements_LastStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_state.Keeping}selected{/if} label_part_Keeping" id="elements_Keeping" table_type="Keeping">{t}Keeping{/t} (<span id="elements_Keeping_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="part_stock_state_chooser" style="{if $elements_part_elements_type!='stock_state'}display:none{/if}">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock_state.Error}selected{/if} label_part_Error" id="elements_Error" table_type="Error">{t}Error{/t} (<span id="elements_Error_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock_state.OutofStock}selected{/if} label_part_OutofStock" id="elements_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock_state.VeryLow}selected{/if} label_part_VeryLow" id="elements_VeryLow" table_type="VeryLow">{t}Very Low{/t} (<span id="elements_VeryLow_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock_state.Low}selected{/if} label_part_Low" id="elements_Low" table_type="Low">{t}Low{/t} (<span id="elements_Low_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock_state.Normal}selected{/if} label_part_Normal" id="elements_Normal" table_type="Normal">{t}Ok{/t} (<span id="elements_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:30px" class=" table_type transaction_type state_details {if $elements_stock_state.Excess}selected{/if} label_part_Excess" id="elements_Excess" table_type="Excess">{t}Excess{/t} (<span id="elements_Excess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details  label_part_NotInUse">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_use.NotInUse}selected{/if} label_part_NotInUse" id2="elements_NotInUse" id="elements_NotInUse_bis" table_type="NotInUse" title="{t}Not In Use{/t}">{t}NiU{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details ">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_use.InUse}selected{/if} label_part_InUse" id2="elements_InUse" id="elements_InUse_bis" table_type="InUse" title="{t}In Use{/t}">{t}iU{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details  label_part_NotInUse">[</span> 
				</div>
			</div>
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $parts_view=='general'}selected{/if}" id="parts_general" name="general">{t}Description{/t}</button> <button class="{if $parts_view=='stock'}selected{/if}" id="parts_stock" name="stock">{t}Stock{/t}</button> <button class="{if $parts_view=='locations'}selected{/if}" id="parts_locations" name="locations">{t}Locations{/t}</button> <button class="{if $parts_view=='sales'}selected{/if}" id="parts_sales" name="sales">{t}Sales{/t}</button> <button class="{if $parts_view=='forecast'}selected{/if}" id="parts_forecast" name="forecast">P/F</button> 
				</div>
				<div class="buttons small left cluster" id="part_period_options" style="{if $parts_view=='general' or $parts_view=='locations' };display:none{/if}">
					<button class="{if $parts_period=='all'}selected{/if}" period="all" id="parts_period_all">{t}All{/t}</button> <button style="margin-left:4px" class="{if $parts_period=='yeartoday'}selected{/if}" period="yeartoday" id="parts_period_yeartoday">{t}YTD{/t}</button> <button class="{if $parts_period=='monthtoday'}selected{/if}" period="monthtoday" id="parts_period_monthtoday">{t}MTD{/t}</button> <button class="{if $parts_period=='weektoday'}selected{/if}" period="weektoday" id="parts_period_weektoday">{t}WTD{/t}</button> <button class="{if $parts_period=='today'}selected{/if}" period="today" id="parts_period_today">{t}Today{/t}</button> <button style="margin-left:4px" class="{if $parts_period=='yesterday'}selected{/if}" period="yesterday" id="parts_period_yesterday">{t}YD{/t}</button> <button class="{if $parts_period=='last_w'}selected{/if}" period="last_w" id="parts_period_last_w">{t}LW{/t}</button> <button class="{if $parts_period=='last_m'}selected{/if}" period="last_m" id="parts_period_last_m">{t}LM{/t}</button> <button style="margin-left:4px" class="{if $parts_period=='three_year'}selected{/if}" period="three_year" id="parts_period_three_year">{t}3Y{/t}</button> <button class="{if $parts_period=='year'}selected{/if}" period="year" id="parts_period_year">{t}1Yr{/t}</button> <button class="{if $parts_period=='six_month'}selected{/if}" period="six_month" id="parts_period_six_month">{t}6M{/t}</button> <button class="{if $parts_period=='quarter'}selected{/if}" period="quarter" id="parts_period_quarter">{t}1Qtr{/t}</button> <button class="{if $parts_period=='month'}selected{/if}" period="month" id="parts_period_month">{t}1M{/t}</button> <button class="{if $parts_period=='ten_day'}selected{/if}" period="ten_day" id="parts_period_ten_day">{t}10D{/t}</button> <button class="{if $parts_period=='week'}selected{/if}" period="week" id="parts_period_week">{t}1W{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="avg_options" style="{if $parts_view!='sales' };display:none{/if};display:none">
					<button class="{if $parts_avg=='totals'}selected{/if}" avg="totals" id="avg_totals">{t}Totals{/t}</button> <button class="{if $parts_avg=='month'}selected{/if}" avg="month" id="avg_month">{t}M AVG{/t}</button> <button class="{if $parts_avg=='week'}selected{/if}" avg="week" id="avg_week">{t}W AVG{/t}</button> <button class="{if $parts_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="avg_month_eff">{t}M EAVG{/t}</button> <button class="{if $parts_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="avg_week_eff">{t}W EAVG{/t}</button> 
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
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='all_transactions'}selected{/if}" id="restrictions_all_transactions" table_type="all_transactions">{t}All{/t} (<span id="transactions_all_transactions"></span><img id="transactions_all_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='oip_transactions'}selected{/if}" id="restrictions_oip_transactions" table_type="oip_transactions">{t}OIP{/t} (<span id="transactions_oip_transactions"></span><img id="transactions_oip_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='out_transactions'}selected{/if}" id="restrictions_out_transactions" table_type="out_transactions">{t}Out{/t} (<span id="transactions_out_transactions"></span><img id="transactions_out_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='in_transactions'}selected{/if}" id="restrictions_in_transactions" table_type="in_transactions">{t}In{/t} (<span id="transactions_in_transactions"></span><img id="transactions_in_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='audit_transactions'}selected{/if}" id="restrictions_audit_transactions" table_type="audit_transactions">{t}Audits{/t} (<span id="transactions_audit_transactions"></span><img id="transactions_audit_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='move_transactions'}selected{/if}" id="restrictions_move_transactions" table_type="move_transactions">{t}Movements{/t} (<span id="transactions_move_transactions"></span><img id="transactions_move_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
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
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="stock_history_plot_subblock">
			<span class="clean_table_title">{t}Stock History Chart{/t} <img class="display_table_chooser" id="hide_stock_history_chart" alt="{t}hide{/t}" title="{t}Hide Chart{/t}" style="{if !$show_stock_history_chart}display:none;{/if}" src="art/icons/hide_button.png" /> <img class="display_table_chooser" id="show_stock_history_chart" alt="{t}show{/t}" title="{t}Show Chart{/t}" style="{if $show_stock_history_chart}display:none;{/if}" src="art/icons/show_button.png" /> </span> 
			<div id="stock_history_plot_subblock_part" style="{if !$show_stock_history_chart}display:none;{/if}">
				<div class="buttons small">
					<button id="change_plot">&#x21b6 <span id="change_plot_label_value" style="{if $stock_history_chart_output!='value'}display:none{/if}">{t}Value at Cost{/t}</span> <span id="change_plot_label_end_day_value" style="{if $stock_history_chart_output!='end_day_value'}display:none{/if}">{t}Cost Value (end day){/t}</span> <span id="change_plot_label_commercial_value" style="{if $stock_history_chart_output!='commercial_value'}display:none{/if}">{t}Commercial Value{/t}</span> </button> 
				</div>
				<div id="stock_history_plot">
					<strong>You need to upgrade your Flash Player</strong> 
				</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "part_history_plot_object", "930", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
				so.addVariable("chart_id", "part_history_plot_object");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&output={$stock_history_chart_output}&parent=warehouse&parent_key={$warehouse->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("stock_history_plot");
		// ]]>
	</script> <script>

var flashMovie;

function reloadSettings(file) {
  flashMovie.reloadSettings(file);
}

	function amChartInited(chart_id){

  flashMovie = document.getElementById(chart_id);
  
  }
	</script> 
			</div>
		</div>
		<div id="stock_history_table_subblock">
			<span class="clean_table_title" style="clear:both;margin-top:20px">{t}Stock History{/t} 
			<div id="stock_history_type" style="display:inline;color:#aaa">
				<span id="stock_history_type_day" table_type="day" style="margin-left:10px;font-size:80%;" class="table_type state_details {if $stock_history_type=='day'}selected{/if}">{t}Daily{/t}</span> <span id="stock_history_type_week" table_type="week" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $stock_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span id="stock_history_type_month" table_type="month" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $stock_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> 
			</div>
			</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:0px">
			</div>
			<div style="float:right;margin-top:0px;padding:0px;font-size:90%;position:relative;top:-7px">
				<div style="position:relative;left:18px;margin-top:10px">
					<span id="clear_interval" style="font-size:80%;color:#777;cursor:pointer;{if $to=='' and $from=='' }display:none{/if}">{t}clear{/t}</span> {t}Interval{/t}: 
					<input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}" />
					<img style="height:14px;bottom:1px;left:-19px;" id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <span class="calpop" style="margin-left:4px">&rarr;</span> 
					<input class="calpop" id="v_calpop2" size="11" maxlength="10" type="text" name="to" value="{$to}" />
					<img style="height:14px;bottom:1px;left:-37px;" id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <img style="position:relative;right:26px;cursor:pointer;height:15px" align="absbottom" src="art/icons/application_go.png" id="submit_interval" alt="{t}Go{/t}" /> 
				</div>
				<div id="cal1Container" style="position:absolute;display:none; z-index:2">
				</div>
				<div style="position:relative;right:-80px">
					<div id="cal2Container" style="display:none; z-index:2;position:absolute">
					</div>
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
			<div id="table0" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
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
{if $block_view!='parts'} 
<div id="change_plot_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Choose chart{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('value')"> {t}Value at Cost{/t}</button> <button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('end_day_value')"> {t}Cost Value (end day){/t}</button> <button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('commercial_value')"> {t}Commercial Value{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
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
{/if} 
{include file='export_splinter.tpl' id='parts' export_fields=$export_parts_fields map=$export_parts_map is_map_default={$export_parts_map_is_default}}

<div id="dialog_change_parts_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Group parts by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="parts_element_chooser_use" style="float:none;margin:0px auto;min-width:120px" onclick="change_parts_element_chooser('use')" class="{if $elements_part_elements_type=='use'}selected{/if}"> {t}State{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="parts_element_chooser_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_parts_element_chooser('state')" class="{if $elements_part_elements_type=='state'}selected{/if}"> {t}State/Availability{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="parts_element_chooser_stock_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_parts_element_chooser('stock_state')" class="{if $elements_part_elements_type=='stock_state'}selected{/if}"> {t}Stock Level{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 