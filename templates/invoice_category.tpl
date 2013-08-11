{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='contacts_navigation.tpl'} 
		<input type="hidden" id="category_key" value="{$category->id}" />
		<input type="hidden" id="state_type" value="{$state_type}" />
		<input type="hidden" id="invoices_view" value="{$invoices_view}" />
		<input type="hidden" id="parent" value="category" />
		<input type="hidden" id="parent_key" value="{$category->id}" />
		<input type="hidden" id="show_subjects" value="{$show_subjects}" />
		<input type="hidden" id="show_subcategories" value="{$show_subcategories}" />
				<input type="hidden" id="link_extra_argument" value="&id={$category->id}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="to" value="{$to}" />
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; 
			{if $root_category_store_key}
			{if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Invoices{/t}</a> &rarr; {/if}<a href="invoices.php?store={$store->id}">{t}Invoices{/t} ({$store->get('Store Code')})</a> &rarr;<a href="invoice_categories.php?&store_key={$store->id}"> {t}Categories{/t} </a> &rarr; 
			{else}
			<a href="orders_server.php">&#8704; {t}Invoices{/t}</a> &rarr; <a href="invoice_categories.php?&store_key=0"> {t}Categories{/t} </a> &rarr;
			{/if}
			{$category->get('Category XHTML Branch Tree')} </span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if}  <span class="main_title">{$category->get_icon()} {$category->get('Category Label')} <span class="id">({$category->get('Category Code')})</span>  </span> 
			</div>
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button onclick="window.location='edit_invoice_category.php?id={$category->id}'"> <img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t} </button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		{include file='calendar_splinter.tpl'} 
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span> </li>
		<li style="{if !$show_subcategories}display:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t} ({$category->get('Number Children')})</span></span> </li>
		<li style="{if !$show_subjects}display:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Invoices{/t} ({$category->get('Number Subjects')})</span></span> </li>
		<li style="{if !$show_subjects_data}display:none{/if};display:none"> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span> </li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}Changelog{/t}</span></span> </li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px;display:none;">
	</div>
	<div id="block_subcategories" style="{if $block_view!='subcategories'}display:none;{/if}clear:bzoth;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;margin-bottom:20px">
			<span class="clean_table_title"> {t}Subcategories{/t} </span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
	</div>
	<div id="block_subjects" style="{if $block_view!='subjects'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="children_table" class="data_table">
			<span class="clean_table_title with_elements"> {t}Invoice list{/t} <span>{$period}</span><img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"> </span> 
			{if $category->get('Category Deep')>1} 
			<div id="table_type" class="table_type">
					<div style="font-size:90%">
						<img style="float:right;margin-left:15px;cursor:pointer;position:relative;bottom:-7px;right:3px" id="invoice_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
						<div id="invoice_type_chooser" style="{if $elements_invoice_elements_type!='type'}display:none{/if}">
							<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Invoice}selected{/if} label_elements_type_Invoice" id="elements_invoice_type_Invoice" table_type="Invoice">{t}Invoices{/t} (<span id="elements_invoice_type_Invoice_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Refund}selected{/if} label_elements_type_Refund" id="elements_invoice_type_Refund" table_type="Refund">{t}Refunds{/t} (<span id="elements_invoice_type_Refund_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="invoice_payment_chooser" style="{if $elements_invoice_elements_type!='payment'}display:none{/if}">
							<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Yes}selected{/if} label_elements_payment_Yes" id="elements_invoice_payment_Yes" table_type="Yes">{t}Paid{/t} (<span id="elements_invoice_payment_Yes_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Partially}selected{/if} label_elements_payment_Partially" id="elements_invoice_payment_Partially" table_type="Partially">{t}Partially Paid{/t} (<span id="elements_invoice_payment_Partially_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.No}selected{/if} label_elements_payment_No" id="elements_invoice_payment_No" table_type="No">{t}Waiting Payment{/t} (<span id="elements_invoice_payment_No_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
					</div>
				</div>
			{/if}	
			<div class="table_top_bar"  style="margin-bottom:15px">
			</div>
		
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
	</div>
	<div id="block_overview" style="{if $block_view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="sales_info" style="{if !$show_subjects_data}display:none{/if}">
			<div style="margin-top:20px;width:900px">
				
				<div style="margin-top:20px">
					<div style="width:200px;float:left;margin-left:0px;">
						<table style="clear:both" class="show_info_product">
							
							<tbody  >
								<tr>
									<td> {t}Invoices{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,"Invoices")} </td>
								</tr>
								<tr >
									<td> {t}Refunds{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,'Acc Profit')} </td>
								</tr>
								<tr style="display:none">
									<td> {t}Margin{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,'Acc Margin')} </td>
								</tr>
								<tr style="display:none">
									<td> {t}GMROI{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,'Acc GMROI')} </td>
								</tr>
							</tbody>
							
						</table>
					</div>
					<div style="float:left;margin-left:20px">
						<table style="width:200px;clear:both" class="show_info_product">
							{foreach from=$period_tags item=period } 
							<tbody id="info2_{$period.key}" style="{if $category_period!=$period.key}display:none{/if}">
								{if $category->get_period($period.db,'Acc No Supplied')!=0} 
								<tr>
									<td> {t}Required{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,'Acc Required')} </td>
								</tr>
								<tr style="display:none">
									<td> {t}No Supplied{/t}: </td>
									<td class="aright error"> {$category->get_period($period.db,'Acc No Supplied')} </td>
								</tr>
								{/if} 
								<tr>
									<td> {t}Sold{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,'Acc Sold')} </td>
								</tr>
								{if $category->get_period($period.db,'Acc Given')!=0} 
								<tr>
									<td> {t}Given for free{/t}: </td>
									<td class="aright"> {$category->get_period($period.db,'Acc Given')} </td>
								</tr>
								{/if} {if $category->get_period($period.db,'Acc Given')!=0} 
								<tr>
									<td> {t}Broken{/t}: </td>
									<td class="aright"> {$category->get('Total Acc Broken')} </td>
								</tr>
								{/if} {if $category->get_period($period.db,'Acc Given')!=0} 
								<tr>
									<td> {t}Lost{/t}: </td>
									<td class="aright"> { $category->get_period($period.db,'Acc Lost')} </td>
								</tr>
								{/if} 
							</tbody>
							{/foreach} 
						</table>
					</div>
				</div>
				<div id="sales_plots" style="clear:both;{if $category->get_period('Total','Acc Sold Amount')==0}display:none{/if}">
					<ul class="tabs" id="chooser_ul" style="margin-top:25px">
						<li> <span class="item {if $plot_tipo=='store'}selected{/if}" onclick="change_plot(this)" id="plot_store" tipo="store"> <span> {t}Invoices Sales{/t} </span> </span> </li>
						{* 
						<li> <span class="item {if $plot_tipo=='top_deinvoicements'}selected{/if}" id="plot_top_deinvoicements" onclick="change_plot(this)" tipo="top_deinvoicements"> <span> {t}Top Products{/t} </span> </span> </li>
						<li> <span class="item {if $plot_tipo=='pie'}selected{/if}" onclick="change_plot(this)" id="plot_pie" tipo="pie" forecast="{$plot_data.pie.forecast}" interval="{$plot_data.pie.interval}"> <span> {t}Products{/t} </span> </span> </li>
						*} 
					</ul>
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 
					<div id="sales_plot" style="clear:both;border:1px solid #ccc">
						<div id="single_data_set">
							<strong> You need to upgrade your Flash Player </strong> 
						</div>
					</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=invoice_category_sales&category_key={$category->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("sales_plot");
		// ]]>
	</script> 
					<div style="clear:both">
					</div>
				</div>
			</div>
		</div>
		{if $category->get('Category Deep')==1} 
		
		<span id="table_title" class="clean_table_title with_elements">{t}Categories break-thought{/t} </span> 







		<div id="table_type" class="table_type">
			<div style="font-size:90%">
						<img style="float:right;margin-left:15px;cursor:pointer;position:relative;bottom:-7px;right:3px" id="invoice_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
						<div id="invoice_type_chooser" style="{if $elements_invoice_elements_type!='type'}display:none{/if}">
							<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Invoice}selected{/if} label_elements_type_Invoice" id="elements_invoice_type_Invoice" table_type="Invoice">{t}Invoices{/t} (<span id="elements_invoice_type_Invoice_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_type.Refund}selected{/if} label_elements_type_Refund" id="elements_invoice_type_Refund" table_type="Refund">{t}Refunds{/t} (<span id="elements_invoice_type_Refund_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="invoice_payment_chooser" style="{if $elements_invoice_elements_type!='payment'}display:none{/if}">
							<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Yes}selected{/if} label_elements_payment_Yes" id="elements_invoice_payment_Yes" table_type="Yes">{t}Paid{/t} (<span id="elements_invoice_payment_Yes_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.Partially}selected{/if} label_elements_payment_Partially" id="elements_invoice_payment_Partially" table_type="Partially">{t}Partially Paid{/t} (<span id="elements_invoice_payment_Partially_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_invoice_payment.No}selected{/if} label_elements_payment_No" id="elements_invoice_payment_No" table_type="No">{t}Waiting Payment{/t} (<span id="elements_invoice_payment_No_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
					</div>
		</div>
				
		<div class="table_top_bar" style="margin-bottom:0px">
		</div>
		<div id="plot_referral_1" style="float:left;">
			<strong> You need to upgrade your Flash Player </strong> 
		</div>
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "350", "300", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=category&category_key={$category->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS"); 
			
		// you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_referral_1");
		// ]]>
	</script> 
		<div style="float:left" id="plot_referral_2">
			<strong> You need to upgrade your Flash Player </strong> 
		</div>
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "550", "550", "8", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=category_subjects&category_key={$category->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");
		so.addVariable("loading_settings", "LOADING SETTINGS");  // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_referral_2");
		// ]]>
	</script>
	{/if} 
	</div>
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title"> {t}Changelog{/t} </span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="invoice_type_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Changes}selected{/if} label_invoice_Changes" id="elements_Changes" table_type="Changes">{t}Changes{/t} (<span id="elements_Changes_number">{$history_elements_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_invoice_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
			</div>
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable">
		</div>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_change_invoices_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Group invoices by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="invoices_element_chooser_type" style="float:none;margin:0px auto;min-width:120px" onclick="change_invoices_element_chooser('type')" class="{if $elements_invoice_elements_type=='type'}selected{/if}"> Type</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="invoices_element_chooser_payment" style="float:none;margin:0px auto;min-width:120px" onclick="change_invoices_element_chooser('payment')" class="{if $elements_invoice_elements_type=='payment'}selected{/if}"> Payment</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 