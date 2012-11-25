{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='contacts_navigation.tpl'} 
		<input type="hidden" id="category_key" value="{$category->id}" />
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customer_categories.php?store={$store->id}&id=0">{t}Categories{/t}</a> &rarr; {$category->get('Category XHTML Branch Tree')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{*} {if isset($parent_category)} <button onclick="window.location='customer_categories.php?store_id={$store->id}&id={$parent_category->id}'"><img src="art/icons/arrow_up.png" alt=""> {$parent_category->get('Category Code')}</button> {/if} <button onclick="window.location='customer_categories.php?store={$store->id}&id=0'"><img src="art/icons/house.png" alt=""> {t}Customers Categories{/t}</button> {*} <span class="main_title">{t}Category{/t}: {$category->get('Category Label')}</span> 
			</div>
			<div class="buttons" style="float:right">
				<button onclick="window.location='edit_customer_category.php?id={$category->id}'"><img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t}</button> <button id="new_category"><img src="art/icons/add.png" alt=""> {t}Add Subcategory{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
			<li> <span class="item {if $block_view=='subcategories_charts'}selected{/if}" id="subcategories_charts"> <span> {t}Overview{/t}</span></span></li>

		<li style="{if $category->get('Category Children')==0 and  $category->get('Category Number Subjects')>0}display:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t}</span></span></li>
		<li style="{if $category->get('Category Number Subjects')==0 and  $category->get('Category Children')>0 }display:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Customers{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}History{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_subcategories" style="{if $block_view!='subcategories'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;margin-bottom:20px">
			<span class="clean_table_title">Subcategories</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
	<div id="block_subjects" style="{if $block_view!='subjects'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="children_table" class="data_table">
		
		
						<span class="clean_table_title">{t}Customers in this category{/t} <img id="export0" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"></span> 
					<div class="table_top_bar">
				</div>
				<div class="clusters" >
					<div class="buttons small left cluster">
						<button class="table_option {if $view=='general'}selected{/if}" id="general">{t}General{/t}</button> 
						<button class="table_option {if $view=='contact'}selected{/if}" id="contact">{t}Contact{/t}</button> 
						<button class="table_option {if $view=='address'}selected{/if}" id="address">{t}Address{/t}</button> 
						<button class="table_option {if $view=='balance'}selected{/if}" id="balance">{t}Balance{/t}</button> 
						<button class="table_option {if $view=='rank'}selected{/if}" id="rank">{t}Ranking{/t}</button>
						<button class="table_option {if $view=='weblog'}selected{/if}"  id="weblog"  >{t}WebLog{/t}</button>

					</div>
					<div style="clear:both">
					</div>
				</div>
	
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
	<div id="block_subcategories_charts" style="{if $block_view!='subcategories_charts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

{if $category->get('Category Deep')==1}
		<div style="float:left" id="plot_referral_1">
			<strong>You need to upgrade your Flash Player</strong> 
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
			<strong>You need to upgrade your Flash Player</strong> 
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
		<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable">
		</div>
	</div>
	
	
	
	
</div>
{include file='new_category_splinter.tpl'}
{include file='footer.tpl'} 