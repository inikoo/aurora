{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" id="warehouse_key" value="{$warehouse->id}" />
	<input type="hidden" id="list_key" value="{$part_list_id}" />
	<input type="hidden" id="parent" value="list" />
	<input type="hidden" id="parent_key" value="{$part_list_id}" />
	<input type="hidden" id="parts_table_id" value="0" />

	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if} <a href="inventory.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; <a href="parts_lists.php?warehouse_id={$warehouse->id}">{t}Parts Lists{/t}</a> &rarr; {$part_list_name} </span> 
	</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:15px">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Parts List{/t}: <span class="id">{$part_list_name}</span></span> 
		</div>
		<div class="buttons">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="data_table" style="clear:both;">
		<span class="clean_table_title">{t}Parts{/t} <img class="export_data_link" id="export_parts" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
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
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
		<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
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
				<button id="parts_element_chooser_use" style="float:none;margin:0px auto;min-width:120px" onclick="change_parts_element_chooser('use')" class="{if $elements_part_elements_type=='use'}selected{/if}"> State</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="parts_element_chooser_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_parts_element_chooser('state')" class="{if $elements_part_elements_type=='state'}selected{/if}"> State/Availability</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="parts_element_chooser_stock_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_parts_element_chooser('stock_state')" class="{if $elements_part_elements_type=='stock_state'}selected{/if}"> Stock Level</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='export_splinter.tpl' id='parts' export_fields=$export_parts_fields map=$export_parts_map is_map_default={$export_parts_map_is_default}}

{include file='footer.tpl'} 