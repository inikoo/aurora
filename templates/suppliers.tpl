{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='suppliers_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Suppliers{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='new_supplier.php'"><img src="art/icons/add.png" alt=""> {t}Add Supplier{/t}</button> <button onclick="window.location='edit_suppliers.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Suppliers{/t}</button> {/if} <button onclick="window.location='suppliers_stats.php?'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button onclick="window.location='suppliers_lists.php?'"><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button> <button onclick="window.location='supplier_categories.php?id=0'"><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Suppliers{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $block_view=='suppliers'}selected{/if}" id="suppliers"> <span> {t}Suppliers{/t}</span></span></li>
			<li> <span class="item {if $block_view=='sproducts'}selected{/if}" id="sproducts"> <span> {t}Supplier Products{/t}</span></span></li>
			<li> <span class="item {if $block_view=='porders'}selected{/if}" id="porders"> <span> {t}Purchase Orders{/t}</span></span></li>
			<li> <span class="item {if $block_view=='sinvoices'}selected{/if}" id="sinvoices"> <span> {t}Supplier Invoices{/t}</span></span></li>
			<li> <span class="item {if $block_view=='idn'}selected{/if}" id="idn"> <span> {t}Incoming Delivery Notes{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_suppliers" style="{if $block_view!='suppliers'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Suppliers List{/t} <img id="export_csv0" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $suppliers_view=='general'}selected{/if}" id="suppliers_general">{t}General{/t}</button> <button class="{if $suppliers_view=='contact'}selected{/if}" id="suppliers_contact">{t}Contact{/t}</button> 
						<button class="{if $suppliers_view=='products'}selected{/if}" id="suppliers_products">{t}Products{/t}</button> 
						<button style="{if !$view_stock}display:none{/if}" class="{if $suppliers_view=='stock'}selected{/if}" id="suppliers_stock">{t}Stock{/t}</button> 
						<button style="{if !$view_sales}display:none{/if}" class="{if $suppliers_view=='sales'}selected{/if}" id="suppliers_sales">{t}Sales{/t}</button> 
						<button style="{if !$view_sales}display:none{/if}" class="option {if $suppliers_view=='sales_year'}selected{/if}" id="suppliers_sales_year">{t}Sales/Year{/t}</button> 

						<button style="{if !$view_sales}display:none{/if}" class="{if $suppliers_view=='profit'}selected{/if}" id="suppliers_profit">{t}Profit{/t}</button> 
					</div>
					<div class="buttons small left cluster" id="suppliers_period_options" style="{if $suppliers_view!='sales' and  $suppliers_view!='profit'};display:none{/if}">
						<button class="table_option {if $suppliers_period=='all'}selected{/if}" period="all" id="suppliers_period_all">{t}All{/t}</button> <button style="margin-left:4px" class="table_option {if $suppliers_period=='yeartoday'}selected{/if}" period="yeartoday" id="suppliers_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $suppliers_period=='monthtoday'}selected{/if}" period="monthtoday" id="suppliers_period_monthtoday">{t}MTD{/t}</button> <button class="table_option {if $suppliers_period=='weektoday'}selected{/if}" period="weektoday" id="suppliers_period_weektoday">{t}WTD{/t}</button> <button class="table_option {if $suppliers_period=='today'}selected{/if}" period="today" id="suppliers_period_today">{t}Today{/t}</button> <button style="margin-left:4px" class="table_option {if $suppliers_period=='yesterday'}selected{/if}" period="yesterday" id="suppliers_period_yesterday">{t}YD{/t}</button> <button class="table_option {if $suppliers_period=='last_w'}selected{/if}" period="last_w" id="suppliers_period_last_w">{t}LW{/t}</button> <button class="table_option {if $suppliers_period=='last_m'}selected{/if}" period="last_m" id="suppliers_period_last_m">{t}LM{/t}</button> <button style="margin-left:4px" class="table_option {if $suppliers_period=='three_year'}selected{/if}" period="three_year" id="suppliers_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $suppliers_period=='year'}selected{/if}" period="year" id="suppliers_period_year">{t}1Yr{/t}</button> <button class="table_option {if $suppliers_period=='six_month'}selected{/if}" period="six_month" id="suppliers_period_six_month">{t}6M{/t}</button> <button class="table_option {if $suppliers_period=='quarter'}selected{/if}" period="quarter" id="suppliers_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $suppliers_period=='month'}selected{/if}" period="month" id="suppliers_period_month">{t}1M{/t}</button> <button class="table_option {if $suppliers_period=='ten_day'}selected{/if}" period="ten_day" id="suppliers_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $suppliers_period=='week'}selected{/if}" period="week" id="suppliers_period_week">{t}1W{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
				</div>
			</div>
		</div>
		<div id="block_porders" style="{if $block_view!='porders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title" style="margin-right:5px">{t}Purchase Orders{/t} </span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
		<div id="block_sinvoices" style="{if $block_view!='sinvoices'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_idn" style="{if $block_view!='idn'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_sproducts" style="{if $block_view!='sproducts'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}Supplier Products{/t} <img id="export_csv1" tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="option {if $supplier_products_view=='general'}selected{/if}" id="supplier_products_general">{t}General{/t}</button> <button style="{if !$view_stock}display:none{/if}" class="option {if $supplier_products_view=='stock'}selected{/if}" id="supplier_products_stock">{t}Stock{/t}</button> 
						<button style="{if !$view_sales}display:none{/if}" class="option {if $supplier_products_view=='sales'}selected{/if}" id="supplier_products_sales">{t}Sales{/t}</button> 
						<button style="{if !$view_sales}display:none{/if}" class="option {if $supplier_products_view=='sales_year'}selected{/if}" id="supplier_products_sales_year">{t}Sales/Year{/t}</button> 
						
						<button style="{if !$view_sales}display:none{/if}" class="option {if $supplier_products_view=='profit'}selected{/if}" id="supplier_products_profit">{t}Profit{/t}</button> 
					</div>
					<div class="buttons small left cluster" id="supplier_products_period_options" style="{if $supplier_products_view!='sales' and  $supplier_products_view!='profit'};display:none{/if}">
						<button class="table_option {if $supplier_products_period=='all'}selected{/if}" period="all" id="supplier_products_period_all">{t}All{/t}</button> <button style="margin-left:4px" class="table_option {if $supplier_products_period=='yeartoday'}selected{/if}" period="yeartoday" id="supplier_products_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $supplier_products_period=='monthtoday'}selected{/if}" period="monthtoday" id="supplier_products_period_monthtoday">{t}MTD{/t}</button> <button class="table_option {if $supplier_products_period=='weektoday'}selected{/if}" period="weektoday" id="supplier_products_period_weektoday">{t}WTD{/t}</button> <button class="table_option {if $supplier_products_period=='today'}selected{/if}" period="today" id="supplier_products_period_today">{t}Today{/t}</button> <button style="margin-left:4px" class="table_option {if $supplier_products_period=='yesterday'}selected{/if}" period="yesterday" id="supplier_products_period_yesterday">{t}YD{/t}</button> <button class="table_option {if $supplier_products_period=='last_w'}selected{/if}" period="last_w" id="supplier_products_period_last_w">{t}LW{/t}</button> <button class="table_option {if $supplier_products_period=='last_m'}selected{/if}" period="last_m" id="supplier_products_period_last_m">{t}LM{/t}</button> <button style="margin-left:4px" class="table_option {if $supplier_products_period=='three_year'}selected{/if}" period="three_year" id="supplier_products_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $supplier_products_period=='year'}selected{/if}" period="year" id="supplier_products_period_year">{t}1Yr{/t}</button> <button class="table_option {if $supplier_products_period=='six_month'}selected{/if}" period="six_month" id="supplier_products_period_six_month">{t}6M{/t}</button> <button class="table_option {if $supplier_products_period=='quarter'}selected{/if}" period="quarter" id="supplier_products_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $supplier_products_period=='month'}selected{/if}" period="month" id="supplier_products_period_month">{t}1M{/t}</button> <button class="table_option {if $supplier_products_period=='ten_day'}selected{/if}" period="ten_day" id="supplier_products_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $supplier_products_period=='week'}selected{/if}" period="week" id="supplier_products_period_week">{t}1W{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
				<div id="table1" class="data_table_container dtable btable" style="font-size:90%">
				</div>
			</div>
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
{include file='footer.tpl'} 