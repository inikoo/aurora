{include file='header.tpl'} 
<div id="bd" style="padding:0px">
<input type="hidden" id="supplier_key" value="{$supplier->id}"/>
	<div style="padding:0px 20px;">
		{include file='suppliers_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
			<span class="main_title">
			<span id="customer_name_heading" style="padding:2px 7px;border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_name_edit','visibility','hidden')"> <span id="customer_name">{$supplier->get('Supplier Name')}</span> <span class="id">({$supplier->get('Supplier Code')})</span> <img onmouseover="Dom.setStyle('customer_name_heading','border-color','#ccc')" onmouseout="Dom.setStyle('customer_name_heading','border-color','#fff')" id="quick_edit_name_edit" style="cursor:pointer;visibility:hidden;padding-bottom:3px" src="art/icons/edit.gif"></span>
		</span>
			</div>
			<div class="buttons">
				<button onclick="window.location='edit_supplier.php?id={$supplier->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Supplier{/t}</button> <button onclick="window.location='new_supplier_product.php?supplier_key={$supplier->id}'"><img src="art/icons/add.png" alt=""> {t}Add Supplier Product{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
		<table style="width:500px">
			<tr>
				<td id="main_address_td" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_address','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_address','visibility','hidden')"> <img onmouseover="Dom.setStyle('main_address_td','border-color','#ccc')" onmouseout="Dom.setStyle('main_address_td','border-color','#fff')" id="quick_edit_main_address" style="float:right;cursor:pointer;visibility:hidden" src="art/icons/edit.gif"> 
				<div id="main_address">
					{$company->get('Company Main XHTML Address')}
				</div>
				</td>
				<td valign="top"> 
				<table border="0" style="padding:0">
					<tr id="main_contact_name_tr" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_contact_name_edit','visibility','hidden')">
						<td id="main_contact_name" colspan="2" class="aright">{$company->get('Company Main Contact Name')}</td>
						<td><img alt="{t}Name{/t}" title="{t}Name{/t}" src="art/icons/user_suit.png" /></td>
						<td><img onmouseover="Dom.setStyle('main_contact_name_tr','border-color','#ccc')" onmouseout="Dom.setStyle('main_contact_name_tr','border-color','#fff')" id="quick_edit_main_contact_name_edit" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
					</tr>
					<tr id="main_email_tr" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_email','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_email','visibility','hidden')">
						<td id="main_email" colspan="2" class="aright">{$company->get('Company Main XHTML Email')}</td>
						<td><img alt="{t}Email{/t}" title="{t}Email{/t}" src="art/icons/email.png" /></td>
						<td><img onmouseover="Dom.setStyle('main_email_tr','border-color','#ccc')" onmouseout="Dom.setStyle('main_email_tr','border-color','#fff')" id="quick_edit_email" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
					</tr>
					<tr id="main_telephone_tr" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_telephone','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_telephone','visibility','hidden')">
						<td id="main_telephone" colspan="2" class="aright">{$company->get('Company Main XHTML Telephone')}</td>
						<td><img alt="{t}Main Telephone{/t}" title="{t}Main Telephone{/t}" src="art/icons/telephone.png" /></td>
						<td><img onmouseover="Dom.setStyle('main_telephone_tr','border-color','#ccc')" onmouseout="Dom.setStyle('main_telephone_tr','border-color','#fff')" id="quick_edit_main_telephone" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
					</tr>
					<tr id="main_fax_tr" style="border:1px dotted #fff" onmouseover="Dom.setStyle('quick_edit_main_fax','visibility','visible')" onmouseout="Dom.setStyle('quick_edit_main_fax','visibility','hidden')">
						<td id="main_fax" colspan="2" class="aright">{$company->get('Company Main XHTML FAX')}</td>
						<td><img alt="{t}Fax{/t}" title="{t}Fax{/t}" src="art/icons/printer.png" /></td>
						<td><img onmouseover="Dom.setStyle('main_fax_tr','border-color','#ccc')" onmouseout="Dom.setStyle('main_fax_tr','border-color','#fff')" id="quick_edit_main_fax" style="cursor:pointer;visibility:hidden" src="art/icons/edit.gif"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
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
	<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
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
						
							<button class="table_option {if $supplier_products_view=='general'}selected{/if}" id="supplier_products_general">{t}General{/t}</button>
							<button class="table_option {if $supplier_products_view=='stock'}selected{/if}" id="supplier_products_stock">{t}Parts Stock{/t}</button>
							<button class="table_option {if $supplier_products_view=='sales'}selected{/if}" id="supplier_products_sales">{t}Parts Sales{/t}</button>
							<button class="table_option {if $supplier_products_view=='profit'}selected{/if}" id="supplier_products_profit">{t}Profit{/t}</button>
					</div>
					<div class="buttons small left cluster" id="supplier_products_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $supplier_products_view!='sales'};display:none{/if}">
						
							<button class="table_option {if $supplier_products_period=='all'}selected{/if}" period="all" id="supplier_products_period_all">{t}All{/t}</button>
							<button class="table_option {if $supplier_products_period=='three_year'}selected{/if}" period="three_year" id="supplier_products_period_three_year">{t}3Y{/t}</button>
							<button class="table_option {if $supplier_products_period=='year'}selected{/if}" period="year" id="supplier_products_period_year">{t}1Yr{/t}</button>
							<button class="table_option {if $supplier_products_period=='six_month'}selected{/if}" period="six_month" id="supplier_products_period_six_month">{t}6M{/t}</button>
							<button class="table_option {if $supplier_products_period=='quarter'}selected{/if}" period="quarter" id="supplier_products_period_quarter">{t}1Qtr{/t}</button>
							<button class="table_option {if $supplier_products_period=='month'}selected{/if}" period="month" id="supplier_products_period_month">{t}1M{/t}</button>
							<button class="table_option {if $supplier_products_period=='ten_day'}selected{/if}" period="ten_day" id="supplier_products_period_ten_day">{t}10D{/t}</button>
							<button class="table_option {if $supplier_products_period=='week'}selected{/if}" period="week" id="supplier_products_period_week">{t}1W{/t}</button>
							
							<button style="margin-left:10px" class="table_option {if $supplier_products_period=='yeartoday'}selected{/if}" period="yeartoday" id="supplier_products_period_yeartoday">{t}YTD{/t}</button>
							<button class="table_option {if $supplier_products_period=='monthtoday'}selected{/if}" period="monthtoday" id="supplier_products_period_monthtoday">{t}MTD{/t}</button>
							<button class="table_option {if $supplier_products_period=='weektoday'}selected{/if}" period="weektoday" id="supplier_products_period_weektoday">{t}WTD{/t}</button>
						
					</div>
					<div id="supplier_products_avg_options" style="display:none;float:left;margin:0 0 0 20px ;padding:0 {if $supplier_products_view!='sales'};display:none{/if}" class="options_mini">
						
							<button class="table_option {if $supplier_products_avg=='totals'}selected{/if}" avg="totals" id="supplier_products_avg_totals">{t}Totals{/t}</button>
							<button class="table_option {if $supplier_products_avg=='month'}selected{/if}" avg="month" id="supplier_products_avg_month">{t}M AVG{/t}</button>
							<button class="table_option {if $supplier_products_avg=='week'}selected{/if}" avg="week" id="supplier_products_avg_week">{t}W AVG{/t}</button>
					
					</div>
					</div>
					
					
					{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
					<div id="table0" class="data_table_container dtable btable " style="font-size:90%">
					</div>
				</div>
			</div>
		</div>
		<div id="block_purchases" style="{if $block_view!='purchases'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		
		
		
		<span class="clean_table_title">{t}Purchases Chart{/t} <img id="hide_purchase_history_chart" alt="{t}hide{/t}" title="{t}Hide Chart{/t}" style="{if !$show_purchase_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle;position:relative;bottom:1px" src="art/icons/hide_button.png" /> <img id="show_purchase_history_chart" alt="{t}show{/t}" title="{t}Show Chart{/t}" style="{if $show_purchase_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle" src="art/icons/show_button.png" /> </span> 
	<div  class="buttons small"><button id="change_plot">&#x21b6 <span id="change_plot_label_value" style="{if $purchase_history_chart_output!='stock'}display:none{/if}">{t}Stock{/t}</span><span id="change_plot_label_stock" style="{if $purchase_history_chart_output!='value'}display:none{/if}">{t}Value{/t}</span></button></div>
	<div id="purchase_history_plot" style="{if !$show_purchase_history_chart}display:none;{/if}">
			<br/><br/>
			<strong>You need to upgrade your Flash Player</strong> 
		</div>
		<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 

<script type="text/javascript">
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
		<div id="table2" style="font-size:85%" class="data_table_container dtable btable ">
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
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="supplier-table-csv_export" export_options=$csv_export_options } {include file='footer.tpl'} 
<div id="dialog_quick_edit_Customer_Main_Contact_Name" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Customer Name:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Main_Contact_Name" value="{$company->get('Company Main Contact Name')}" ovalue="{$company->get('Company Main Contact Name')}" valid="0"> 
				<div id="Customer_Main_Contact_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Main_Contact_Name_msg"></span> <button class="positive" id="save_quick_edit_main_contact_name">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_main_contact_name">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Main_Email" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Contact Email:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Customer_Main_Email" value="{$company->get('Company Main Plain Email')}" ovalue="{$company->get('Company Main Plain Email')}" valid="0"> 
				<div id="Customer_Main_Email_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Main_Email_msg"></span> <button class="positive" id="save_quick_edit_email">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_email">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Main_Telephone" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Telephone:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Customer_Main_Telephone" value="{$company->get('Company Main XHTML Telephone')}" ovalue="{$company->get('Company Main XHTML Telephone')}" valid="0"> 
				<div id="Customer_Main_Telephone_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Main_Telephone_msg"></span> <button class="positive" id="save_quick_edit_telephone">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_telephone">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Main_FAX" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Fax:{/t}</td>
			<td> 
			<div style="width:200px">
				<input type="text" id="Customer_Main_FAX" value="{$company->get('Company Main XHTML FAX')}" ovalue="{$company->get('Company Main XHTML FAX')}" valid="0"> 
				<div id="Customer_Main_FAX_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Main_FAX_msg"></span> <button class="positive" id="save_quick_edit_fax">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_fax">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Name" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Customer Name:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Name" value="{$supplier->get('Supplier Name')}" ovalue="{$supplier->get('Supplier Name')}" valid="0"> 
				<div id="Customer_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Name_msg"></span> <button class="positive" id="save_quick_edit_name">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_name">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Main_Address" style="float:left;xborder:1px solid #ddd;width:430px;margin-right:20px;padding-bottom:50px">
	<table border="0" style="margin:10px; width:100%">
		{include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true show_components=true} 
	</table>
	<div style="display:none" id='contact_current_address'>
	</div>
	<div style="display:none" id='contact_address_display{$supplier->get("Supplier Main Address Key")}'>
	</div>
</div>
<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
