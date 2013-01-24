{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<input type="hidden" id="supplier_key" value="{$supplier->id}" />
		<input type="hidden" id="link_extra_argument" value="&id={$supplier->id}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="to" value="{$to}" />
	<div style="padding:0px 20px;">
		{include file='suppliers_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title"> <span id="customer_name_heading" style="padding:2px 7px;border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_name_edit','visibility','hidden')"> <span id="customer_name">{$supplier->get('Supplier Name')}</span> <span class="id">({$supplier->get('Supplier Code')})</span> <img onmouseover="Dom.setStyle('customer_name_heading','border-color','#ccc')" onmouseout="Dom.setStyle('customer_name_heading','border-color','#fff')" id="quick_edit_name_edit" style="cursor:pointer;visibility:hidden;padding-bottom:3px" src="art/icons/edit.gif"></span> </span> 
			</div>
			<div class="buttons">
				<button onclick="window.location='edit_supplier.php?id={$supplier->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Supplier{/t}</button> <button onclick="window.location='new_supplier_product.php?supplier_key={$supplier->id}'"><img src="art/icons/add.png" alt=""> {t}Add Supplier Product{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>		
		<div style="width:520px;float:left;padding-top:0px">
		<table id="supplier_data" border="0" style="width:100%;border-collapse: collapse;">
				<tr>
					<td colspan="2"> 
					<div style="border:0px solid red;float:left;margin-right:20px">
						{if $supplier->get_image_src()} <img id="avatar" src="{$supplier->get_image_src()}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="art/avatar_company.png" style="cursor:pointer;"> {/if} 
					</div>
					<h1 style="padding-bottom:0px;width:300px">
						<span id="supplier_name_heading" style="padding:2px 7px;padding-left:0;border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_name_edit','visibility','hidden')"><span id="supplier_name">{$supplier->get('Supplier Name')}</span> <img onmouseover="Dom.addClass('supplier_name_heading','edit_over')" onmouseout="Dom.removeClass('supplier_name_heading','edit_over')" id="quick_edit_name_edit" style="cursor:pointer;visibility:hidden;padding-bottom:3px" src="art/icons/edit.gif"></span> 
					</h1>
					<table class="supplier_show_data">
						{if $supplier->get('Supplier Main Contact Key')} 
						<tr id="main_contact_name_tr" onmouseover="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','hidden')">
							<td id="main_contact_name" colspan="2" class="aright">{if $supplier->get('Supplier Main Contact Name')==''}<span class="add_missing_value">{t}add contact name{/t}</span>{/if}{$supplier->get('Supplier Main Contact Name')}</td>
							<td><img alt="{t}Name{/t}" title="{t}Name{/t}" src="art/icons/user_suit.png" /></td>
							<td><img onmouseover="Dom.addClass('main_contact_name_tr','edit_over')" onmouseout="Dom.removeClass('main_contact_name_tr','edit_over')" id="quick_edit_main_contact_name_edit" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} 
					</table>
					</td>
					<td> </td>
				</tr>
				<tr>
					{if $supplier->get('Supplier Main Address Key')} 
					
					<td id="main_address_td" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_address','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_address','visibility','hidden')"> <img onmouseover="Dom.addClass('main_address_td','edit_over')" onmouseout="Dom.removeClass('main_address_td','edit_over')" id="quick_edit_main_address" style="float:right;cursor:pointer;visibility:hidden" src="art/icons/edit.gif"> 
					<div id="main_address">
						{if $supplier_address_fuzzy_type=='All'}<span class="add_missing_value">{t}add supplier address{/t}</span>{else}{$supplier->get('Supplier Main XHTML Address')}{/if}
					</div>
					<div style="margin-top:3px;{if $supplier_address_fuzzy_type=='All'}display:none{/if}" class="buttons small left">
						<button onclick="window.open('suppliers_address_label.pdf.php?type=supplier&id={$supplier->id}&label=99012')"><img style="height:12px" src="art/icons/printer.png" alt=""> {t}Label{/t}</button>
					</div>
					</td>
					
					{/if} 
					<td valign="top"> 
					<table class="supplier_show_data">
					
						<tr id="main_email_tr" onmouseover="Dom.setStyle('quick_edit_email','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_email','visibility','hidden')">
							<td id="main_email" colspan="2" class="aright">{if $supplier->get_main_email_user_key()}<a href="site_user.php?id={$supplier->get_main_email_user_key()}"><img src="art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>{/if} {if $supplier->get('Supplier Main XHTML Telephone')=='' and $supplier->get('Supplier Main XHTML Email')==''}<span class="add_missing_value">{t}add email{/t}</span>{/if} {$supplier->get('Supplier Main XHTML Email')}</td>
							<td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="art/icons/email.png" /></td>
							<td id="email_label{$supplier->get('Supplier Main Email Key')}" style="color:#777;font-size:80%">{$supplier->get_principal_email_comment()} <img onmouseover="Dom.addClass('main_email_tr','edit_over')" onmouseout="Dom.removeClass('main_email_tr','edit_over')" id="quick_edit_email" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						 {foreach from=$supplier->get_other_emails_data() item=other_email key=key} 
						<tr id="other_email_tr" onmouseover="Dom.setStyle('quick_edit_other_email{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_email{$key}','visibility','hidden')">
							<td id="email{$key}" colspan="2" class="aright">{if $other_email.user_key}<a href="site_user.php?id={$other_email.user_key}"><img src="art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>{/if} {$other_email.xhtml}</td>
							<td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="art/icons/email.png" /></td>
							<td id="email_label{$key}" style="color:#777;font-size:80%">{$other_email.label} <img onmouseover="Dom.addClass('other_email_tr','edit_over')" onmouseout="Dom.removeClass('other_email_tr','edit_over')" id="quick_edit_other_email{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach}
						
						<tr id="main_telephone_tr" onmouseover="Dom.setStyle('quick_edit_main_telephone','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_telephone','visibility','hidden')">
							<td id="main_telephone" colspan="2" class="aright" style="{if $supplier->get('Supplier Main XHTML Mobile') and $supplier->get('Supplier Preferred Contact Number')=='Telephone'}font-weight:800{/if}">{if $supplier->get('Supplier Main XHTML Telephone')=='' and $supplier->get('Supplier Main XHTML Email')==''}<span class="add_missing_value">{t}add telephone{/t}</span>{/if} {$supplier->get('Supplier Main XHTML Telephone')}</td>
							<td><img alt="{t}Main Telephone{/t}" title="{t}Main Telephone{/t}" src="art/icons/telephone.png" /></td>
							<td id="telephone_label{$supplier->get('Supplier Main Telephone Key')}" style="color:#777;font-size:80%">{$supplier->get_principal_telecom_comment('Telephone')} <img onmouseover="Dom.addClass('main_telephone_tr','edit_over')" onmouseout="Dom.removeClass('main_telephone_tr','edit_over')" id="quick_edit_main_telephone" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						
						{foreach from=$supplier->get_other_telephones_data() item=other_tel key=key} 
						<tr id="other_telephone_tr" onmouseover="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_telephone{$key}','visibility','hidden')">
							<td id="telephone{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
							<td><img alt="{t}Telephone{/t}" title="{t}Telephone{/t}" src="art/icons/telephone.png" /></td>
							<td id="telephone_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_telephone_tr','edit_over')" onmouseout="Dom.removeClass('other_telephone_tr','edit_over')" id="quick_edit_other_telephone{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach} 
						{if $supplier->get('Supplier Main FAX Key')} 
						<tr id="main_fax_tr" onmouseover="Dom.setStyle('quick_edit_main_fax','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_fax','visibility','hidden')">
							<td id="main_fax" colspan="2" class="aright">{$supplier->get('Supplier Main XHTML FAX')}</td>
							<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="art/icons/printer.png" /></td>
							<td id="fax_label{$supplier->get('Supplier Main FAX Key')}" style="color:#777;font-size:80%">{$supplier->get_principal_telecom_comment('FAX')} <img onmouseover="Dom.addClass('main_fax_tr','edit_over')" onmouseout="Dom.removeClass('main_fax_tr','edit_over')" id="quick_edit_main_fax" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} {foreach from=$supplier->get_other_faxes_data() item=other_tel key=key} 
						<tr id="other_fax_tr" onmouseover="Dom.setStyle('quick_edit_other_fax{$key}','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_other_fax{$key}','visibility','hidden')">
							<td id="fax{$key}" colspan="2" class="aright">{$other_tel.xhtml}</td>
							<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="art/icons/printer.png" /></td>
							<td id="fax_label{$key}" style="color:#777;font-size:80%">{$other_tel.label} <img onmouseover="Dom.addClass('other_fax_tr','edit_over')" onmouseout="Dom.removeClass('other_fax_tr','edit_over')" id="quick_edit_other_fax{$key}" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/foreach} {if $supplier->get('Supplier Website')} 
						<tr id="website_tr" onmouseover="Dom.setStyle('quick_edit_website','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_website','visibility','hidden')">
							<td id="website" colspan="2" class="aright">{$supplier->get('Supplier Website')}</td>
							<td><img alt="{t}Fax{/t}" title="{t}Website{/t}" src="art/icons/world.png" /></td>
							<td id="website_label{$supplier->get('Supplier Main FAX Key')}" style="color:#777;font-size:80%"><img onmouseover="Dom.addClass('website_tr','edit_over')" onmouseout="Dom.removeClass('website_tr','edit_over')" id="quick_edit_website" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
						</tr>
						{/if} 
		
					</table>
					</td>
				</tr>
				
			</table>
		</div>
		
	
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}History{/t}</span></span></li>
		<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Part Sales{/t}</span></span></li>
		<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"> <span> {t}Supplier Products{/t}</span></span></li>
		<li> <span class="item {if $block_view=='purchases'}selected{/if}" id="purchases"> <span> {t}Purchases{/t}</span></span></li>
		<li> <span class="item {if $block_view=='purchase_orders'}selected{/if}" id="purchase_orders"> <span> {t}Purchase Orders{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0px 20px;">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div style="clear:both">
				<div style="width:300px;float:left">
					<table class="show_info_product">
						<tr>
							<td>{t}Code{/t}:</td>
							<td class="price">{$supplier->get('Supplier Code')}</td>
						</tr>
						<tr>
							<td>{t}Name{/t}:</td>
							<td>{$supplier->get('Supplier Name')}</td>
						</tr>
						<tr>
							<td>{t}Location{/t}:</td>
							<td>{$supplier->get('Supplier Main Location')}</td>
						</tr>
						<tr>
							<td>{t}Email{/t}:</td>
							<td>{$supplier->get('Supplier Main XHTML Email')}</td>
						</tr>
					</table>
				</div>
				<div style="width:300px;margin-left:10px;float:left">
					<table class="show_info_product">
						<tr>
							<td>{t}Total Sales{/t}:</td>
							<td class="aright">{$supplier->get('Total Acc Parts Sold Amount')} </td>
						</tr>
						<tr>
							<td>{t}Total Profit{/t}:</td>
							<td class="aright">{$supplier->get('Total Acc Parts Profit')} </td>
						</tr>
						<tr>
							<td>{t}Stock Value{/t}:</td>
							<td class="aright">{$supplier->get('Stock Value')} </td>
						</tr>
					</table>
				</div>
				<div style="width:280px;margin-left:10px;float:left">
					<table class="show_info_product">
						<tr>
							<td>{t}Items available{/t}:</td>
							<td class="aright">{$supplier->get('Supplier Active Supplier Products')} </td>
						</tr>
						<tr>
							<td>{t}Items no longer available{/t}:</td>
							<td class="aright">{$supplier->get('Supplier Discontinued Supplier Products')} </td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:10px 0 40px 0">

{include file='calendar_splinter.tpl'} 
		<div style="margin-top:20px;width:900px">
			<span><img src="art/icons/clock_16.png" style="height:12px;position:relative;bottom:2px"> {$period}</span> 
			<div style="margin-top:0px">
				<div style="width:200px;float:left;margin-left:0px;">
					<table style="clear:both" class="show_info_product">
						<tbody>
							<tr>
								<td>{t}Sales{/t}:</td>
								<td class="aright" id="sales_amount"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Profit{/t}:</td>
								<td class="aright" id="profits"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Margin{/t}:</td>
								<td class="aright" id="margin"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}GMROI{/t}:</td>
								<td class="aright" id="gmroi"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="float:left;margin-left:20px">
					<table style="width:200px;clear:both" class="show_info_product">
						<tbody id="no_supplied_tbody" style="display:none">
							<tr>
								<td>{t}Required{/t}:</td>
								<td class="aright" id="required"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Out of Stock{/t}:</td>
								<td class="aright error" id="out_of_stock"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Not Found{/t}:</td>
								<td class="aright error" id="not_found"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>{t}Sold{/t}:</td>
								<td class="aright" id="sold"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr id="given_tr" style="display:none">
								<td>{t}Given for free{/t}:</td>
								<td class="aright"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr id="broken_tr" style="display:none">
								<td>{t}Broken{/t}:</td>
								<td class="aright" id="broken"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr id="lost_tr" style="display:none">
								<td>{t}Lost{/t}:</td>
								<td class="aright" id="lost"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="clear:both;">
			</div>
		</div>
		
		
		<div id="sales_sub_blocks" style="clear:both;">
			<ul class="tabs" id="chooser_ul" style="margin-top:10px">
				<li> <span class="item {if $sales_sub_block_tipo=='plot_supplier_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_supplier_sales" tipo="store"> <span>{t}Sales Chart{/t}</span> </span> </li>
				<li> <span class="item {if $sales_sub_block_tipo=='supplier_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="supplier_sales_timeseries" tipo="store"> <span>{t}Part Sales History{/t}</span> </span> </li>
				<li> <span class="item {if $sales_sub_block_tipo=='supplier_product_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="supplier_product_sales" tipo="list" forecast="" interval=""> <span>{t}Products Sales{/t}</span> </span> </li>
			</ul>
			<div id="sub_block_plot_supplier_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_supplier_sales'}display:none{/if}">


<script type="text/javascript">
		// <![CDATA[
		
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=supplier_sales&supplier_key={$supplier->id}"));
		so.addVariable("preloader_color", "#999999");
		
		so.write("sub_block_plot_supplier_sales");
		// ]]>
	</script> 
				<div style="clear:both">
				</div>
			</div>
			<div id="sub_block_supplier_sales_timeseries" style="padding:20px;min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='supplier_sales_timeseries'}display:none{/if}">
				<span class="clean_table_title">{t}Part Sales History{/t}</span> 
				<div>
					<span tipo='year' id="part_sales_history_type_year" style="float:right" class="table_type state_details {if $part_sales_history_type=='year'}selected{/if}">{t}Yearly{/t}</span> <span tipo='month' id="part_sales_history_type_month" style="float:right;margin-right:10px" class="table_type state_details {if $part_sales_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> <span tipo='week' id="part_sales_history_type_week" style="float:right;margin-right:10px" class="table_type state_details {if $part_sales_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span tipo='day' id="part_sales_history_type_day" style="float:right;margin-right:10px" class="table_type state_details {if $part_sales_history_type=='day'}selected{/if}">{t}Daily{/t}</span> 
				</div>
				<div  class="table_top_bar"  style="margin-bottom:10px">
				</div>
				{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
				<div id="table4" style="font-size:85%" class="data_table_container dtable btable">
				</div>
			</div>
			
			</div>
		</div>


	
		<div id="block_purchase_orders" style="{if $block_view!='purchase_orders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table">
				<span class="clean_table_title">{t}Supplier Products{/t} </span> 
				<div id="list_options0">
					<div class="table_top_bar">
					</div>
					<div class="clusters">
						<div class="buttons small left cluster">
							<button class="table_option {if $supplier_products_view=='general'}selected{/if}" id="supplier_products_general">{t}General{/t}</button> <button class="table_option {if $supplier_products_view=='stock'}selected{/if}" id="supplier_products_stock">{t}Parts Stock{/t}</button> <button class="table_option {if $supplier_products_view=='sales'}selected{/if}" id="supplier_products_sales">{t}Parts Sales{/t}</button> <button class="table_option {if $supplier_products_view=='profit'}selected{/if}" id="supplier_products_profit">{t}Profit{/t}</button> 
						</div>
						<div class="buttons small left cluster" id="supplier_products_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $supplier_products_view!='sales'};display:none{/if}">
							<button class="table_option {if $supplier_products_period=='all'}selected{/if}" period="all" id="supplier_products_period_all">{t}All{/t}</button> <button class="table_option {if $supplier_products_period=='three_year'}selected{/if}" period="three_year" id="supplier_products_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $supplier_products_period=='year'}selected{/if}" period="year" id="supplier_products_period_year">{t}1Yr{/t}</button> <button class="table_option {if $supplier_products_period=='six_month'}selected{/if}" period="six_month" id="supplier_products_period_six_month">{t}6M{/t}</button> <button class="table_option {if $supplier_products_period=='quarter'}selected{/if}" period="quarter" id="supplier_products_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $supplier_products_period=='month'}selected{/if}" period="month" id="supplier_products_period_month">{t}1M{/t}</button> <button class="table_option {if $supplier_products_period=='ten_day'}selected{/if}" period="ten_day" id="supplier_products_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $supplier_products_period=='week'}selected{/if}" period="week" id="supplier_products_period_week">{t}1W{/t}</button> <button style="margin-left:10px" class="table_option {if $supplier_products_period=='yeartoday'}selected{/if}" period="yeartoday" id="supplier_products_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $supplier_products_period=='monthtoday'}selected{/if}" period="monthtoday" id="supplier_products_period_monthtoday">{t}MTD{/t}</button> <button class="table_option {if $supplier_products_period=='weektoday'}selected{/if}" period="weektoday" id="supplier_products_period_weektoday">{t}WTD{/t}</button> 
						</div>
						<div id="supplier_products_avg_options" style="display:none;float:left;margin:0 0 0 20px ;padding:0 {if $supplier_products_view!='sales'};display:none{/if}" class="options_mini">
							<button class="table_option {if $supplier_products_avg=='totals'}selected{/if}" avg="totals" id="supplier_products_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $supplier_products_avg=='month'}selected{/if}" avg="month" id="supplier_products_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $supplier_products_avg=='week'}selected{/if}" avg="week" id="supplier_products_avg_week">{t}W AVG{/t}</button> 
						</div>
					</div>
					{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
					<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
					</div>
				</div>
			</div>
		</div>
		<div id="block_purchases" style="{if $block_view!='purchases'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Purchases Chart{/t} <img id="hide_purchase_history_chart" alt="{t}hide{/t}" title="{t}Hide Chart{/t}" style="{if !$show_purchase_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle;position:relative;bottom:1px" src="art/icons/hide_button.png" /> <img id="show_purchase_history_chart" alt="{t}show{/t}" title="{t}Show Chart{/t}" style="{if $show_purchase_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle" src="art/icons/show_button.png" /> </span> 
			<div class="buttons small">
				<button id="change_plot">&#x21b6 <span id="change_plot_label_value" style="{if $purchase_history_chart_output!='stock'}display:none{/if}">{t}Stock{/t}</span><span id="change_plot_label_stock" style="{if $purchase_history_chart_output!='value'}display:none{/if}">{t}Value{/t}</span></button>
			</div>
			<div id="purchase_history_plot" style="{if !$show_purchase_history_chart}display:none;{/if}">
				<br />
				<br />
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "930", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=part_purchase_history&output={$purchase_history_chart_output}&parent=supplier&parent_key={$supplier->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("purchase_history_plot");
		// ]]>
	</script> <span class="clean_table_title" style="clear:both;margin-top:20px">{t}Purchase History{/t} 
			<div id="purchase_history_type" style="display:inline;color:#aaa">
				<span id="purchase_history_type_day" table_type="day" style="margin-left:10px;font-size:80%;" class="table_type state_details {if $purchase_history_type=='day'}selected{/if}">{t}Daily{/t}</span> <span id="purchase_history_type_week" table_type="week" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $purchase_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span id="purchase_history_type_month" table_type="month" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $purchase_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> 
			</div>
			</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name='' filter_value='' no_filter=1 } 
			<div id="table2" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:10px 0 40px 0">
				<span class="clean_table_title">{t}History/Notes{/t}</span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Changes}selected{/if} label_customer_history_changes" id="elements_changes" table_type="changes">{t}Changes History{/t} (<span id="elements_changes_number">{$elements_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Orders}selected{/if} label_customer_history_orders" id="elements_orders" table_type="orders">{t}Order History{/t} (<span id="elements_orders_number">{$elements_number.Orders}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Notes}selected{/if} label_customer_history_notes" id="elements_notes" table_type="notes">{t}Staff Notes{/t} (<span id="elements_notes_number">{$elements_number.Notes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Attachments}selected{/if} label_customer_history_attachments" id="elements_attachments" table_type="attachments">{t}Attachments{/t} (<span id="elements_notes_number">{$elements_number.Attachments}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Emails}selected{/if} label_customer_history_emails" id="elements_emails" table_type="emails">{t}Emails{/t} (<span id="elements_emails_number">{$elements_number.Emails}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.WebLog}selected{/if} label_customer_history_weblog" id="elements_weblog" table_type="weblog">{t}WebLog{/t} (<span id="elements_weblog_number">{$elements_number.WebLog}</span>)</span> 
			</div>
		</div>
		<div class="table_top_bar">
		</div>
		{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 } 
		<div id="table4" class="data_table_container dtable btable">
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
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>

{include file='splinter_edit_subject_quick.tpl' subject=$supplier subject_tag='Supplier'} 

{include file='footer.tpl'} 
