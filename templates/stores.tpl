{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<input type="hidden" id="subject" value="none"> 
	<input type="hidden" id="subject_key" value="1"> 
	<input type="hidden" id="products_table_id" value="3"> 
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; &#8704; {t}Stores{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				<button style="{if !$user->can_edit('account')}display:none{/if}" onclick="window.location='edit_stores.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Stores{/t}</button>
				<button style="display:none" onclick="window.location='stores_stats.php'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Stores{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li style="display:none"> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
			<li> <span class="item {if $block_view=='stores'}selected{/if}" id="stores"> <span> {t}Stores{/t}</span></span></li>
			<li> <span class="item {if $block_view=='departments'}selected{/if}" id="departments"> <span> {t}Departments{/t}</span></span></li>
			<li> <span class="item {if $block_view=='families'}selected{/if}" id="families"> <span> {t}Families{/t}</span></span></li>
			<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
			<li> <span class="item {if $block_view=='deals'}selected{/if}" style="display:none" id="deals"> <span> {t}Offers{/t}</span></span></li>
			<li> <span class="item {if $block_view=='sites'}selected{/if}" id="sites"> <span> {t}Websites{/t}</span></span></li>
		</ul>
		<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_stores" style="{if $block_view!='stores'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Stores{/t} 
				<img class="export_data_link" id="export_csv0" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="table_top_bar">
				</div>
				
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $view=='general'}selected{/if}" id="general">{t}Summary{/t}</button> <button class="table_option {if $view=='stock'}selected{/if}" id="stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $view=='sales'}selected{/if}" id="sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> 
						<div style="clear:both">
						</div>
					</div>
					<div id="stores_period_options" class="buttons small left cluster" style="{if $view!='sales' };display:none{/if}">
						<button class="table_option {if $period=='all'}selected{/if}" period="all" id="period_all">{t}All{/t}</button> <button class="table_option {if $period=='three_year'}selected{/if}" period="three_year" id="period_three_year">{t}3Y{/t}</button> <button class="table_option {if $period=='year'}selected{/if}" period="year" id="period_year">{t}1Yr{/t}</button> <button class="table_option {if $period=='yeartoday'}selected{/if}" period="yeartoday" id="period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $period=='six_month'}selected{/if}" period="six_month" id="period_six_month">{t}6M{/t}</button> <button class="table_option {if $period=='quarter'}selected{/if}" period="quarter" id="period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $period=='month'}selected{/if}" period="month" id="period_month">{t}1M{/t}</button> <button class="table_option {if $period=='ten_day'}selected{/if}" period="ten_day" id="period_ten_day">{t}10D{/t}</button> <button class="table_option {if $period=='week'}selected{/if}" period="week" id="period_week">{t}1W{/t}</button> 
						<div style="clear:both">
						</div>
					</div>
					<div id="stores_avg_options" class="buttons small left cluster" style="display:{if $view!='sales' }none{else}block{/if};">
						<button class="table_option {if $avg=='totals'}selected{/if}" avg="totals" id="avg_totals">{t}Totals{/t}</button> <button class="table_option {if $avg=='month'}selected{/if}" avg="month" id="avg_month">{t}M AVG{/t}</button> <button class="table_option {if $avg=='week'}selected{/if}" avg="week" id="avg_week">{t}W AVG{/t}</button> 
						<div style="clear:both">
						</div>
					</div>
					<div id="stores_currency_mode" class="buttons small cluster group"  style="display:{if $view!='sales' }none{else}block{/if};">
						<button  id="change_stores_display_mode">&#x21b6 {$display_stores_mode_label}</button> 
					</div>
					
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable with_total" style="font-size:85%">
				</div>
			</div>
		</div>
		<div id="block_departments" style="{if $block_view!='departments'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}Departments{/t} 
				<img class="export_data_link" id="export_csv1" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="table_top_bar">
				</div>
				
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $department_view=='general'}selected{/if}" id="department_general">{t}Overview{/t}</button> <button class="table_option {if $department_view=='stock'}selected{/if}" id="department_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $department_view=='sales'}selected{/if}" id="department_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> 
					</div>
					<div id="department_period_options" class="buttons small left cluster" style="display:{if $department_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $department_period=='all'}selected{/if}" period="all" id="department_period_all">{t}All{/t}</button> <button class="table_option {if $department_period=='three_year'}selected{/if}" period="three_year" id="department_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $department_period=='year'}selected{/if}" period="year" id="department_period_year">{t}1Yr{/t}</button> <button class="table_option {if $department_period=='yeartoday'}selected{/if}" period="yeartoday" id="department_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $department_period=='six_month'}selected{/if}" period="six_month" id="department_period_six_month">{t}6M{/t}</button> <button class="table_option {if $department_period=='quarter'}selected{/if}" period="quarter" id="department_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $department_period=='month'}selected{/if}" period="month" id="department_period_month">{t}1M{/t}</button> <button class="table_option {if $department_period=='ten_day'}selected{/if}" period="ten_day" id="department_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $department_period=='week'}selected{/if}" period="week" id="department_period_week">{t}1W{/t}</button> 
					</div>
					<div id="department_avg_options" class="buttons small left cluster" style="display:{if $department_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $department_avg=='totals'}selected{/if}" avg="totals" id="department_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $department_avg=='month'}selected{/if}" avg="month" id="department_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $department_avg=='week'}selected{/if}" avg="week" id="department_avg_week">{t}W AVG{/t}</button> 
					</div>
					<div class="buttons small cluster group">
					<button  id="change_departments_display_mode">&#x21b6 {$display_departments_mode_label}</button> 
				</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name0 filter_value=$filter_value1 } 
				<div id="table1" class="data_table_container dtable btable with_total" style="font-size:85%">
				</div>
			</div>
		</div>
		<div id="block_families" style="{if $block_view!='families'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin:0px;clear:both">
				<span class="clean_table_title">{t}Families{/t} 
				<img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif">
				</span> 
			
					<div  class="elements_chooser">
						<span style="float:right;margin-left:20px;" class="{if $elements_family.NoSale}selected{/if} label_family_products_nosale" id="elements_family_nosale" table_type="nosale">{t}No Sale{/t} (<span id="elements_family_nosale_number">{$elements_family_number.NoSale}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinued}selected{/if} label_family_products_discontinued" id="elements_family_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_family_discontinued_number">{$elements_family_number.Discontinued}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinuing}selected{/if} label_family_products_discontinued" id="elements_family_discontinuing" table_type="discontinuing">{t}Discontinuing{/t} (<span id="elements_family_discontinuing_number">{$elements_family_number.Discontinuing}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.Normal}selected{/if} label_family_products_normal" id="elements_family_normal" table_type="normal">{t}For Sale{/t} (<span id="elements_family_notes_number">{$elements_family_number.Normal}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.InProcess}selected{/if} label_family_products_inprocess" id="elements_family_inprocess" table_type="inprocess">{t}In Process{/t} (<span id="elements_family_notes_number">{$elements_family_number.InProcess}</span>)</span> 
					<div style="clear:both">
					</div>
					</div>
				
				<div class="table_top_bar">
				</div>
				
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $family_view=='general'}selected{/if}" id="family_general">{t}Overview{/t}</button> <button class="table_option {if $family_view=='stock'}selected{/if}" id="family_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $family_view=='sales'}selected{/if}" id="family_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> 
					</div>
					<div id="family_period_options" class="buttons small left cluster" style="display:{if $family_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $family_period=='all'}selected{/if}" period="all" id="family_period_all">{t}All{/t}</button> <button class="table_option {if $family_period=='three_year'}selected{/if}" period="three_year" id="family_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $family_period=='year'}selected{/if}" period="year" id="family_period_year">{t}1Yr{/t}</button> <button class="table_option {if $family_period=='yeartoday'}selected{/if}" period="yeartoday" id="family_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $family_period=='six_month'}selected{/if}" period="six_month" id="family_period_six_month">{t}6M{/t}</button> <button class="table_option {if $family_period=='quarter'}selected{/if}" period="quarter" id="family_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $family_period=='month'}selected{/if}" period="month" id="family_period_month">{t}1M{/t}</button> <button class="table_option {if $family_period=='ten_day'}selected{/if}" period="ten_day" id="family_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $family_period=='week'}selected{/if}" period="week" id="family_period_week">{t}1W{/t}</button> 
					</div>
					<div id="family_avg_options" class="buttons small left cluster" style="display:{if $family_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $family_avg=='totals'}selected{/if}" avg="totals" id="family_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $family_avg=='month'}selected{/if}" avg="month" id="family_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $family_avg=='week'}selected{/if}" avg="week" id="family_avg_week">{t}W AVG{/t}</button> 
					</div>
					<div class="buttons small cluster group">
					<button  id="change_departments_display_mode">&#x21b6 {$display_families_mode_label}</button> 
				</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
				<div id="table2" class="data_table_container dtable btable with_total" style="font-size:85%">
				</div>
			</div>
		</div>
		<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin:0px;clear:both;">
				<span class="clean_table_title with_elements_chooser">{t}Products{/t} <img id="export_csv3" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
				<div id="table_type" class="elements_chooser">
					
						<img class="menu" id="product_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
						<div id="product_type_chooser" style="{if $elements_product_elements_type!='type'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Historic}selected{/if} label_product_Historic" id="elements_type_Historic" table_type="Historic">{t}Historic{/t} (<span id="elements_type_Historic_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Discontinued}selected{/if} label_product_Discontinued" id="elements_type_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_type_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Private}selected{/if} label_product_Private" id="elements_type_Private" table_type="Private">{t}Private{/t} (<span id="elements_type_Private_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.NoSale}selected{/if} label_product_NoSale" id="elements_type_NoSale" table_type="NoSale">{t}No Sale{/t} (<span id="elements_type_NoSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Sale}selected{/if} label_product_Sale" id="elements_type_Sale" table_type="Sale">{t}Sale{/t} (<span id="elements_type_Sale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="product_web_chooser" style="{if $elements_product_elements_type!='web'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.ForSale}selected{/if} label_product_ForSale" id="elements_web_ForSale" table_type="ForSale">{t}Online{/t} (<span id="elements_web_ForSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.OutofStock}selected{/if} label_product_OutofStock" id="elements_web_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_web_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.Discontinued}selected{/if} label_product_Discontinued" id="elements_web_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_web_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.Offline}selected{/if} label_product_Offline" id="elements_web_Offline" table_type="Offline">{t}Offline{/t} (<span id="elements_web_Offline_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="product_stock_chooser" style="{if $elements_product_elements_type!='stock'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Error}selected{/if} label_product_Error" id="elements_stock_Error" table_type="Error">{t}Error{/t} (<span id="elements_stock_Error_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.OutofStock}selected{/if} label_product_OutofStock" id="elements_stock_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_stock_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.VeryLow}selected{/if} label_product_VeryLow" id="elements_stock_VeryLow" table_type="VeryLow">{t}Very Low{/t} (<span id="elements_stock_VeryLow_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Low}selected{/if} label_product_Low" id="elements_stock_Low" table_type="Low">{t}Low{/t} (<span id="elements_stock_Low_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Normal}selected{/if} label_product_Normal" id="elements_stock_Normal" table_type="Normal">{t}Normal{/t} (<span id="elements_stock_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Excess}selected{/if} label_product_Excess" id="elements_stock_Excess" table_type="Excess">{t}Excess{/t} (<span id="elements_stock_Excess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='InWeb'}selected{/if}" id="elements_stock_aux_InWeb" table_type="InWeb" title="{t}InWeb Products{/t}">{t}In Web{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='ForSale'}selected{/if}" id="elements_stock_aux_ForSale" table_type="ForSale" title="{t}ForSale Products{/t}">{t}For Sale{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='All'}selected{/if}" id="elements_stock_aux_All" table_type="All" title="{t}All Products{/t}">{t}All{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 
						</div>
						<div style="clear:both">
					</div>
					
				</div>
				<div class="table_top_bar">
				</div>
				
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $product_view=='general'}selected{/if}" id="product_general">{t}Overview{/t}</button> 
						<button class="table_option {if $product_view=='timeline'}selected{/if}" id="product_timeline">{t}Timeline{/t}</button>

						<button class="table_option {if $product_view=='stock'}selected{/if}" id="product_stock" style="{if !$view_stock}display:none{/if}">{t}Stock{/t}</button> 
						<button class="table_option {if $product_view=='sales'}selected{/if}" id="product_sales" style="{if !$view_sales}display:none{/if}">{t}Sales{/t}</button>
						<button class="table_option {if $product_view=='parts'}selected{/if}" id="product_parts" style="{if !$view_sales}display:none{/if}">{t}Parts{/t}</button> 
						<button class="table_option {if $product_view=='properties'}selected{/if}" id="product_properties">{t}Properties{/t}</button> 
						<button class="table_option {if $product_view=='cats'}selected{/if}" id="product_cats" style="display:none;{if !$view_sales}display:none{/if}">{t}Groups{/t}</button> 
					</div>
					<div id="product_period_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_period=='all'}selected{/if}" period="all" id="product_period_all">{t}All{/t}</button> <button class="table_option {if $product_period=='three_year'}selected{/if}" period="three_year" id="product_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $product_period=='year'}selected{/if}" period="year" id="product_period_year">{t}1Yr{/t}</button> <button class="table_option {if $product_period=='yeartoday'}selected{/if}" period="yeartoday" id="product_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $product_period=='six_month'}selected{/if}" period="six_month" id="product_period_six_month">{t}6M{/t}</button> <button class="table_option {if $product_period=='quarter'}selected{/if}" period="quarter" id="product_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $product_period=='month'}selected{/if}" period="month" id="product_period_month">{t}1M{/t}</button> <button class="table_option {if $product_period=='ten_day'}selected{/if}" period="ten_day" id="product_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $product_period=='week'}selected{/if}" period="week" id="product_period_week">{t}1W{/t}</button> 
					</div>
					<div id="product_avg_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_avg=='totals'}selected{/if}" avg="totals" id="product_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $product_avg=='month'}selected{/if}" avg="month" id="product_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $product_avg=='week'}selected{/if}" avg="week" id="product_avg_week">{t}W AVG{/t}</button> <button class="table_option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="product_avg_month_eff">{t}M EAVG{/t}</button> <button class="table_option {if $product_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="product_avg_week_eff">{t}W EAVG{/t}</button> 
					</div>
					<div class="buttons small cluster group">
					<button id="change_departments_display_mode">&#x21b6 {$display_products_mode_label}</button> 
				</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 } 
				<div id="table3" class="data_table_container dtable btable" style="font-size:85%">
				</div>
			</div>
		</div>
		<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:20px 0 40px 0">
		</div>
		<div id="block_sites" style="{if $block_view!='sites'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<div class="data_table" style="clear:both;margin-top:25px">
				<span class="clean_table_title">{t}Web Sites{/t}</span> 
				<div id="table_type">
					<span id="table_type_list" style="float:right" class="table_type state_details {if $sites_table_type=='list'}selected{/if}">{t}List{/t}</span> <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $sites_table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span> 
				</div>
				<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
				</div>
				{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name1 filter_value=$filter_value4 no_filter=4 } 
				<div id="table4" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
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
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
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
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
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
<div id="rppmenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},3)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="change_stores_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		
		{foreach from=$stores_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons small">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('stores','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div id="change_families_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">

		{foreach from=$families_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('families','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div id="change_products_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">

		{foreach from=$products_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('products','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div id="change_departments_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">

		{foreach from=$departments_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons small">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('departments','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
{include file='assert_elements_splinter.tpl'} {include file='footer.tpl'} 