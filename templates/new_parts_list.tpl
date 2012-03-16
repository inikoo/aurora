{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
		<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="parts_lists.php?warehouse_id={$warehouse->id}">{t}Parts Lists{/t}</a> &rarr; {t}New Parts List{/t}</span> 
	</div>
	<h2 style="clear:both">
		{t}New Parts List{/t} ({$warehouse->get('Warehouse Name')})
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:870px">
		<input type="hidden" id="warehouse_key" value="{$warehouse->id}"> <span id="error_no_name" style="display:none">{t}Please specify a name{/t}.</span> 
		<table>
		
				<tr>
					<td colspan="2"><b>{t}Part Properties{/t}</b></td>
				</tr>
				<tr>
					<td>{t}created between{/t}:</td>
					<td> 
					<input id="v_calpop3" type="text" class="text" size="11" maxlength="10" name="from" value="" />
					<img id="part_created_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <span class="calpop">&rarr;</span> 
					<input id="v_calpop4" class="calpop" size="11" maxlength="10" type="text" class="text" size="8" name="to" value="" />
					<img id="part_created_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> 
					<div id="part_created_from_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<div id="part_created_to_Container" style="display:none; z-index:2;position:absolute">
					</div>
					</td>
				</tr>
				
				<tr>
					<td>{t}Tarrif Code{/t}:</td>
					<td> 
					<input id="tariff_code" style="width:100px;float:left;margin-right:10px;" />
					<div style="margin-left:10px;display:inline" class="buttons small left">
					<button id="tariff_code_invalid">{t}Invalid Tariff Codes{/t}</button>
					</div>
					
					</td>
				</tr>
				
				<tr>
					<td colspan="2"><b>{t}Dispatched parts:{/t}</b></td>
				</tr>
				
				<tr>
					<td>{t}created between{/t}:</td>
					<td> 
					<input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="" />
					<img id="part_dispatched_from" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> <span class="calpop">&rarr;</span> 
					<input id="v_calpop2" class="calpop" size="11" maxlength="10" type="text" class="text" size="8" name="to" value="" />
					<img id="part_dispatched_to" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt="" /> 
					<div id="part_dispatched_from_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<div id="part_dispatched_to_Container" style="display:none; z-index:2;position:absolute">
					</div>
					</td>
				</tr>
				
				<tr>
					<td>{t}shipped to{/t}:</td>
					<td> 
					<input id="geo_constraints" style="width:500px" />
					<div class="general_options">
						<span id="country" class="state_details">{t}Country{/t}</span> 
					</div>
					</td>
				</tr>
				
			</table>
		
	</table>
</div>
<div style="padding:20px;width:890px;xtext-align:right">
	<div id="save_dialog" style="width:600px;float:left;visibility:hidden">
		<div id="the_div" style="xdisplay:none;">
			{t}Enter list name{/t} : 
			<input type="text" name="list_name" id="list_name"> &nbsp;&nbsp;{t}Select List Type{/t} : 
			<input type="radio" name="type" checked="checked" id="static" value="Static">&nbsp;{t}Static{/t} &nbsp;&nbsp;
			<input type="radio" name="type" id="dynamic" value="Dynamic">&nbsp;{t}Dynamic{/t} 
		</div>
		<div id="save_list_msg">
		</div>
	</div>
	<div class="buttons">
		<button style="display:none;margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="save_list">{t}Save List{/t}</button> <button style="display:none;margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="modify_search">{t}Redo List{/t}</button> <button style="margin-left:20px;border:1px solid #ccc;padding:4px 5px;cursor:pointer" id="submit_search">{t}Create List{/t}</button> 
	</div>
</div>
<div style="padding:30px 40px;display:none" id="searching">
	{t}Search in progress{/t} <img style="margin-left:20px;position:relative;top:5px " src="art/progressbar.gif" /> 
</div>
<div id="the_table" class="data_table" style="margin-top:20px;clear:both;display:none">

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

	{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
	<div id="table0" class="data_table_container dtable btable ">
	</div>
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
<div class="search_box" style="margin-top:30px;font-size:90%;display:none" id="the_search_box">
	<table>
		<tr>
			<td colspan="" style="text-align:right;border-bottom:1px solid #ccc">Search over:</td>
		</tr>
		<tr>
			<td style="text-align:right">{t}All Customers{/t}</td>
			<td>
			<input checked="checked" name="geo_group" id="geo_group_all" value="all" type="radio"></td>
		</tr>
		<tr>
			<td style="text-align:right">{$home} {t}Customers{/t}</td>
			<td>
			<input name="geo_group" id="geo_group_home" value="home" type="radio"></td>
		</tr>
		<tr>
			<td style="text-align:right">{t}Foreign Customers{/t}</td>
			<td>
			<input name="geo_group" id="geo_group_nohome" value="nohome" type="radio"></td>
		</tr>
		<tr>
			<td colspan="" style="text-align:right;border-bottom:1px solid #ccc;height:30px;vertical-align:bottom">Only Customers:</td>
		</tr>
		<tr>
			<td style="text-align:right">{t}with Email{/t}</td>
			<td>
			<input id="with_email" type="checkbox"></td>
		</tr>
		<tr>
			<td style="text-align:right">{t}with Telephone{/t}</td>
			<td>
			<input id="with_tel" type="checkbox"></td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 
<div id="dialog_wregion_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}World Regions{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
			<div id="table1" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_country_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_postal_code_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Postal Code List{/t}</span> {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
			<div id="table3" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_city_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Cities{/t}</span> {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4} 
			<div id="table4" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_department_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Department List{/t}</span> {include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5} 
			<div id="table5" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_family_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Family List{/t}</span> {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6} 
			<div id="table6" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_product_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Product List{/t}</span> {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7} 
			<div id="table7" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
<div id="dialog_category_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Category List{/t}</span> {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8} 
			<div id="table8" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
