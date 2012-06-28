{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		<input type="hidden" id="family_key" value="{$family->id}" />
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} 
				{if $modify}<button onclick="edit_family()"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Family{/t}</button>{/if} 
			</div>
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if}
<span class="main_title">{t}Family{/t}: {$family->get('Product Family Name')} <span class="id">({$family->get('Product Family Code')})</span></span>
			</div>
			<div style="clear:both">
			</div>
		</div>
		
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $block_view=='categories'}selected{/if}" id="categories"> <span> {t}Categories{/t}</span></span></li>
		<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
		<li> <span class="item {if $block_view=='deals'}selected{/if}" id="deals"> <span> {t}Offers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='web'}selected{/if}" id="web"> <span> {t}Web Pages{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	
			
	<div style="padding:10px 20px">
	
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div style="width:900px;float:left;margin-left:20px">
					<div class="clusters">
					<div class="buttons small left cluster">
					<button class="{if $products_period=='all'}selected{/if}" period="all" id="products_period_all" style="padding-left:7px;padding-right:7px">{t}All{/t}</button>
				</div>
				<div class="buttons small left cluster">				<tr>
					<button class="{if $products_period=='yeartoday'}selected{/if}" period="yeartoday" id="products_period_yeartoday">{t}YTD{/t}</button>
					<button class="{if $products_period=='monthtoday'}selected{/if}" period="monthtoday" id="products_period_monthtoday">{t}MTD{/t}</button>
					<button class="{if $products_period=='weektoday'}selected{/if}" period="weektoday" id="products_period_weektoday">{t}WTD{/t}</button>
					<button class="{if $products_period=='today'}selected{/if}" period="today" id="products_period_today">{t}Today{/t}</button>
					</div>
					
						<div class="buttons small left cluster">				<tr>
					<button class="{if $products_period=='yesterday'}selected{/if}" period="yesterday" id="products_period_yesterday">{t}Yesterday{/t}</button>
					<button class="{if $products_period=='last_w'}selected{/if}" period="last_w" id="products_period_last_w">{t}Last Week{/t}</button>
					<button class="{if $products_period=='last_m'}selected{/if}" period="last_m" id="products_period_last_m">{t}Last Month{/t}</button>
					</div>
					
					<div class="buttons small left cluster">				<tr>
					<button class="{if $products_period=='three_year'}selected{/if}" period="three_year" id="products_period_three_year">{t}3Y{/t}</button>
					<button class="{if $products_period=='year'}selected{/if}" period="year" id="products_period_year">{t}1Yr{/t}</button>
					<button class="{if $products_period=='six_month'}selected{/if}" period="six_month" id="products_period_six_month">{t}6M{/t}</button>
					<button class="{if $products_period=='quarter'}selected{/if}" period="quarter" id="products_period_quarter">{t}1Qtr{/t}</button>
					<button class="{if $products_period=='month'}selected{/if}" period="month" id="products_period_month">{t}1M{/t}</button>
					<button class="{if $products_period=='ten_day'}selected{/if}" period="ten_day" id="products_period_ten_day">{t}10D{/t}</button>
					<button class="{if $products_period=='week'}selected{/if}" period="week" id="products_period_week">{t}1W{/t}</button>
				
				</div>

			<div style="clear:both"></div>
	
	</div>
					
			<div style="margin-top:20px">
				<table class="show_info_product" style="float:left;width:250px">
			
			
			
					<tr style="display:none">
						<td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$products_period_title}</span></span> <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<" title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer" src="art/icons/next.png" alt=">" tite="next" /></td>
					</tr>
					{foreach from=$period_tags item=period }
					<tbody id="info_{$period.key}" style="{if $products_period!=$period.key}display:none{/if}">
					
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class=" aright">{$family->get_period({$period.db},'Acc Invoiced Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class=" aright">{$family->get_period({$period.db},'Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Outers{/t}:</td>
							<td class="aright">{$family->get_period({$period.db},'Acc Quantity Delivered')}</td>
						</tr>
					</tbody>
				{/foreach}
				</table>
				<table class="show_info_product" style="float:left;width:250px;margin-left:20px">
			
			
			
					
					{foreach from=$period_tags item=period }
					<tbody id="info2_{$period.key}" style="{if $products_period!=$period.key}display:none{/if}">
						<tr>
							<td>{t}Invoices{/t}:</td>
							<td class="aright">{$family->get_period({$period.db},'Acc Invoices')}</td>
						</tr>
						<tr>
							<td>{t}Customers{/t}:</td>
							<td class=" aright">{$family->get_period({$period.db},'Acc Customers')}</td>
						</tr>
						
					</tbody>
				{/foreach}
				</table>
			</div>
			</div>
			<div id="plots" style="clear:both">
				<ul class="tabs" id="chooser_ul" style="margin-top:25px">
					<li> <span class="item {if $plot_tipo=='store'}selected{/if}" onclick="change_plot(this)" id="plot_store" tipo="store"> <span>{t}Family Sales{/t}</span> </span> </li>
					{* 
					<li> <span class="item {if $plot_tipo=='top_departments'}selected{/if}" id="plot_top_departments" onclick="change_plot(this)" tipo="top_departments"> <span>{t}Top Products{/t}</span> </span> </li>
					*} 
					<li> <span class="item {if $plot_tipo=='pie'}selected{/if}" onclick="change_plot(this)" id="plot_pie" tipo="pie" forecast="{$plot_data.pie.forecast}" interval="{$plot_data.pie.interval}"> <span>{t}Products{/t}</span> </span> </li>
				</ul>
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 
				<div id="plot" style="clear:both;border:1px solid #ccc">
					<div id="single_data_set">
						<strong>You need to upgrade your Flash Player</strong> 
					</div>
				</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=family_sales&family_key={$family->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot");
		// ]]>
	</script> 
				<div style="clear:both">
				</div>
			</div>
		</div>
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div id="photo_container" style="margin-top:0px;float:left">
				<div style="border:1px solid #ddd;padding-stop:0;width:220px;text-align:center;margin:0 10px 0 0px">
					<div id="imagediv" style="border:1px solid #ddd;width:190px;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
						<img src="{$family->get('Product Family Main Image')}" style="vertical-align:middle;display:block;" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px">
					{foreach from=$family->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} <img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" title="" alt="" /> {/if} {/foreach} 
				</div>
			</div>
			<h2 style="margin:20px 0 0 0 ;padding:0">
				Family Information 
			</h2>
			<div style="width:350px;float:left">
				<table class="show_info_product">
					<tr>
						<td>{t}Code{/t}:</td>
						<td class="price">{$family->get('Product Family Code')}</td>
					</tr>
					<tr>
						<td>{t}Name{/t}:</td>
						<td>{$family->get('Product Family Name')}</td>
					</tr>
					<tr>
						<td>{t}Record Type{/t}:</td>
						<td>{$family->get('Product Family Record Type')}</td>
					</tr>
					<tr>
						<td>{t}Similar{/t}:</td>
						<td>{$family->get('Similar Families')}</td>
					</tr>
					<tr>
						<td>{t}Categories{/t}:</td>
						<td>{$family->get('Categories')}</td>
					</tr>
					<tr>
						<td>{t}Web Page{/t}:</td>
						<td>{$family->get('Web Page Links')}</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="block_web" style="{if $block_view!='web'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Pages{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
			<div id="table4" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin-top:10px;clear:both">
				<span id="table_title" class="clean_table_title">{t}Products{/t} <img id="export_csv0" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
				<div id="table_type" class="table_type">
					<div style="font-size:90%" id="transaction_chooser">
						<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Historic}selected{/if} label_family_products_changes" id="elements_historic" table_type="historic">{t}Historic{/t} (<span id="elements_historic_number">{$elements_number.Historic}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_family_products_discontinued" id="elements_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_discontinued_number">{$elements_number.Discontinued}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Private}selected{/if} label_family_products_private" id="elements_private" table_type="private">{t}Private Sale{/t} (<span id="elements_private_number">{$elements_number.Private}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NoSale}selected{/if} label_family_products_nosale" id="elements_nosale" table_type="nosale">{t}Not for Sale{/t} (<span id="elements_nosale_number">{$elements_number.NoSale}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Sale}selected{/if} label_family_products_sale" id="elements_sale" table_type="sale">{t}Public Sale{/t} (<span id="elements_notes_number">{$elements_number.Sale}</span>)</span> 
					</div>
				</div>
				<div class="table_top_bar">
				</div>
				<div class="buttons small clusters">
					<button class="selected" id="change_products_display_mode">{$display_products_mode_label}</button> <button class="selected" id="change_products_table_type">{$products_table_type_label}</button> 
				
				</div>
				<div class="clusters" id="table_view_menu0"  style="{if $products_table_type=='thumbnails'}display:none{/if}"  >
					<div class="buttons small left cluster">
						<button class="table_option {if $product_view=='general'}selected{/if}" id="product_general">{t}Overview{/t}</button> <button class="table_option {if $product_view=='stock'}selected{/if}" id="product_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $product_view=='sales'}selected{/if}" id="product_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> <button class="table_option {if $product_view=='parts'}selected{/if}" id="product_parts" {if !$view_sales}style="display:none" {/if}>{t}Parts{/t}</button> <button class="table_option {if $product_view=='cats'}selected{/if}" id="product_cats" {if !$view_sales}style="display:none" {/if}>{t}Groups{/t}</button> 
					</div>
					<div id="product_period_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_period=='all'}selected{/if}" period="all" id="product_period_all">{t}All{/t}</button> <button class="table_option {if $product_period=='three_year'}selected{/if}" period="three_year" id="product_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $product_period=='year'}selected{/if}" period="year" id="product_period_year">{t}1Yr{/t}</button> <button class="table_option {if $product_period=='yeartoday'}selected{/if}" period="yeartoday" id="product_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $product_period=='six_month'}selected{/if}" period="six_month" id="product_period_six_month">{t}6M{/t}</button> <button class="table_option {if $product_period=='quarter'}selected{/if}" period="quarter" id="product_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $product_period=='month'}selected{/if}" period="month" id="product_period_month">{t}1M{/t}</button> <button class="table_option {if $product_period=='ten_day'}selected{/if}" period="ten_day" id="product_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $product_period=='week'}selected{/if}" period="week" id="product_period_week">{t}1W{/t}</button> 
					</div>
					<div id="product_avg_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_avg=='totals'}selected{/if}" avg="totals" id="product_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $product_avg=='month'}selected{/if}" avg="month" id="product_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $product_avg=='week'}selected{/if}" avg="week" id="product_avg_week">{t}W AVG{/t}</button> <button class="table_option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="product_avg_month_eff">{t}M EAVG{/t}</button> <button class="table_option {if $product_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="product_avg_week_eff">{t}W EAVG{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $products_table_type!='thumbnails'}display:none{/if}">
				</div>
				<div id="table0" class="data_table_container dtable btable with_total " style="{if $products_table_type=='thumbnails'}display:none{/if};font-size:90%">
				</div>
			</div>
			<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			</div>
			<div id="block_categories" style="{if $block_view!='categories'}display:none;{/if}clear:both;margin:10px 0 40px 0">
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
<div id="info_period_menu" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
			{foreach from=$info_period_menu item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_info_period('{$menu.period}','{$menu.title}')"> {$menu.label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="change_products_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Display Mode Options{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
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
<div id="change_products_table_type_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}View items as{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		{foreach from=$products_table_type_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_table_type('products','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="family-table-csv_export" export_options=$csv_export_options } {include file='footer.tpl'} 