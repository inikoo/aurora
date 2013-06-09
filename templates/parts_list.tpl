{include file='header.tpl'}
<div id="bd" >

	{include file='locations_navigation.tpl'} 
	<input type="hidden" id="warehouse_key" value="{$warehouse->id}"/>
		<input type="hidden" id="list_key" value="{$part_list_id}"/>

	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}  <a href="inventory.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a>  &rarr; <a href="parts_lists.php?warehouse_id={$warehouse->id}">{t}Parts Lists{/t}</a> &rarr; {$part_list_name} </span> 
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
			<span class="clean_table_title">{t}Parts{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
		
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
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
  <div id="rppmenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
	{foreach from=$paginator_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  


  {include file='footer.tpl'}
