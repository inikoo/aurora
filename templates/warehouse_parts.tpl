{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Inventory{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='part_configuration.php'"><img src="art/icons/cog.png" alt=""> {t}Configuration{/t}</button> {/if} 
							<button onclick="window.location='warehouse_orders.php?id={$warehouse->id}'"><img src="art/icons/basket_put.png" alt=""> {t}Pick Orders{/t}</button> <button onclick="window.location='parts_movements.php?id={$warehouse->id}'"><img src="art/icons/arrow_switch.png" alt=""> {t}Part Movements{/t}</button> <button onclick="window.location='parts_stats.php?warehouse={$warehouse->id}'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button onclick="window.location='parts_lists.php?warehouse={$warehouse->id}'"><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button> <button onclick="window.location='part_categories.php?id=0&warehouse_id={$warehouse->id}'"><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button> 

			</div>
			<div class="buttons" style="float:left">
						<span style="font-size:140%;position:relative;bottom:-2.5px">	<span class="id">{$warehouse->get('Warehouse Name')}</span> {t}Inventory{/t} <span style="font-style:italic">({t}Parts{/t})</span> </span>

			</div>
			<div style="clear:both">
			</div>
		</div>
		
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $view=='parts'}selected{/if}" id="parts"> <span> {t}Parts{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $view=='movements'}selected{/if}" id="movements"> <span> {t}Movements{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $view=='stats'}selected{/if}" id="stats"> <span> {t}Stats{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_parts" style="{if $view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;">
			<span class="clean_table_title">{t}Parts{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
			<div id="table_type" class="table_type">
				<div style="font-size:90%" id="transaction_chooser">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NotKeeping}selected{/if} label_part_NotKeeping" id="elements_NotKeeping" table_type="NotKeeping">{t}NotKeeping{/t} (<span id="elements_orders_number">{$elements_number.NotKeeping}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_part_Discontinued" id="elements_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_orders_number">{$elements_number.Discontinued}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.LastStock}selected{/if} label_part_LastStock" id="elements_LastStock" table_type="LastStock">{t}LastStock{/t} (<span id="elements_orders_number">{$elements_number.LastStock}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Keeping}selected{/if} label_part_Keeping" id="elements_Keeping" table_type="Keeping">{t}Keeping{/t} (<span id="elements_orders_number">{$elements_number.Keeping}</span>)</span> 
				</div>
			</div>
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $parts_view=='general'}selected{/if}" id="parts_general" name="general">{t}Description{/t}</button> <button class="{if $parts_view=='stock'}selected{/if}" id="parts_stock" name="stock">{t}Stock{/t}</button> <button class="{if $parts_view=='locations'}selected{/if}" id="parts_locations" name="locations">{t}Locations{/t}</button> <button class="{if $parts_view=='sales'}selected{/if}" id="parts_sales" name="sales">{t}Sales{/t}</button> <button class="{if $parts_view=='forecast'}selected{/if}" id="parts_forecast" name="forecast">{t}Forecast{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="period_options" style="{if $parts_view=='general' or $parts_view=='locations' };display:none{/if}">
					<button class="{if $parts_period=='all'}selected{/if}" period="all" id="parts_period_all">{t}All{/t}</button> <button class="{if $parts_period=='three_year'}selected{/if}" period="three_year" id="parts_period_three_year">{t}3Y{/t}</button> <button class="{if $parts_period=='year'}selected{/if}" period="year" id="parts_period_year">{t}1Yr{/t}</button> <button class="{if $parts_period=='six_month'}selected{/if}" period="six_month" id="parts_period_six_month">{t}6M{/t}</button> <button class="{if $parts_period=='quarter'}selected{/if}" period="quarter" id="parts_period_quarter">{t}1Qtr{/t}</button> <button class="{if $parts_period=='month'}selected{/if}" period="month" id="parts_period_month">{t}1M{/t}</button> <button class="{if $parts_period=='ten_day'}selected{/if}" period="ten_day" id="parts_period_ten_day">{t}10D{/t}</button> <button class="{if $parts_period=='week'}selected{/if}" period="week" id="parts_period_week">{t}1W{/t}</button> <button class="{if $parts_period=='yeartoday'}selected{/if}" period="yeartoday" id="parts_period_yeartoday">{t}YTD{/t}</button> <button class="{if $parts_period=='monthtoday'}selected{/if}" period="monthtoday" id="parts_period_monthtoday">{t}MTD{/t}</button> <button class="{if $parts_period=='weektoday'}selected{/if}" period="weektoday" id="parts_period_weektoday">{t}WTD{/t}</button> <button class="{if $parts_period=='today'}selected{/if}" period="today" id="parts_period_today">{t}Today{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="avg_options" style="{if $parts_view!='sales' };display:none{/if};display:none">
					<button class="{if $parts_avg=='totals'}selected{/if}" avg="totals" id="avg_totals">{t}Totals{/t}</button> <button class="{if $parts_avg=='month'}selected{/if}" avg="month" id="avg_month">{t}M AVG{/t}</button> <button class="{if $parts_avg=='week'}selected{/if}" avg="week" id="avg_week">{t}W AVG{/t}</button> <button class="{if $parts_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="avg_month_eff">{t}M EAVG{/t}</button> <button class="{if $parts_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="avg_week_eff">{t}W EAVG{/t}</button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable " style="font-size:90%">
			</div>
		</div>
	</div>
	<div id="block_movements" style="{if $view!='movements'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	</div>
	<div id="block_stats" style="{if $view!='stats'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
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
{include file='footer.tpl'} 