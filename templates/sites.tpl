{include file='header.tpl'} 
<div id="bd" class="no_padding">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Websites{/t}</span> 
		</div>
		
			<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title no_buttons">{t}Websites{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
	
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='sites'}selected{/if}" id="sites"> <span> {t}Websites{/t}</span></span></li>
		<li> <span class="item {if $block_view=='pages'}selected{/if}" id="pages"> <span> {t}Pages{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	
	
	
	<div style="padding:0 20px">
		<div id="block_sites" style="{if $block_view!='sites'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<span class="clean_table_title">{t}Website List{/t}</span> 
			<div class="table_top_bar">
			</div>
			<div class="clusters">
					<div id="table_view_menu1" >
						<div class="buttons small left cluster">
							<button class="table_option {if $sites_view=='general'}selected{/if}" id="sites_general">{t}Info{/t}</button> 
							<button class="table_option {if $sites_view=='products'}selected{/if}" id="sites_products">{t}Products{/t}</button> 
							<button style="display:none" class="table_option {if $sites_view=='users'}selected{/if}" id="sites_users" >{t}Users{/t}</button> 
							<button class="table_option {if $sites_view=='email_reminders'}selected{/if}" id="sites_email_reminders" >{t}Email Reminders{/t}</button> 
						</div>
					
					</div>
					
					<div style="clear:both">
					</div>
				</div>
			
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1 } 
			<div id="table1" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
		<div id="block_pages" style="{if $block_view!='pages'}display:none;{/if}clear:both;margin:20px 0 40px 0">

			<span class="clean_table_title">{t}Pages{/t}</span> 
			<div class="elements_chooser">
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.System}selected{/if} label_page_type" id="page_section_elements_System">{t}System{/t} (<span id="page_section_elements_System_number">{$page_section_elements_number.System}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.Info}selected{/if} label_page_type" id="page_section_elements_Info">{t}Info{/t} (<span id="page_section_elements_Info_number">{$page_section_elements_number.Info}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.Department}selected{/if} label_page_type" id="page_section_elements_Department">{t}Departments{/t} (<span id="page_section_elements_Department_number">{$page_section_elements_number.Department}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.Family}selected{/if} label_page_type" id="page_section_elements_Family">{t}Families{/t} (<span id="page_section_elements_Family_number">{$page_section_elements_number.Family}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.Product}selected{/if} label_page_type" id="page_section_elements_Product">{t}Products{/t} (<span id="page_section_elements_Product_number">{$page_section_elements_number.Product}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.FamilyCategory}selected{/if} label_page_type" id="page_section_elements_FamilyCategory">{t}Family Categories{/t} (<span id="page_section_elements_FamilyCategory_number">{$page_section_elements_number.FamilyCategory}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $page_section_elements.ProductCategory}selected{/if} label_page_type" id="page_section_elements_ProductCategory">{t}Product Categories{/t} (<span id="page_section_elements_ProductCategory_number">{$page_section_elements_number.ProductCategory}</span>)</span> 
			</div>
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="table_option {if $pages_view=='general'}selected{/if}" id="page_general">{t}Overview{/t}</button> <button class="table_option {if $pages_view=='visitors'}selected{/if}" id="page_visitors">{t}Visits{/t}</button> 
				</div>
				<div id="page_period_options" class="buttons small left cluster" style="display:{if $pages_view!='visitors' }none{/if};">
					<button class="table_option {if $page_period=='all'}selected{/if}" period="all" id="page_period_all">{t}All{/t}</button> <button class="table_option {if $page_period=='three_year'}selected{/if}" period="three_year" id="page_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $page_period=='year'}selected{/if}" period="year" id="page_period_year">{t}1Yr{/t}</button> <button class="table_option {if $page_period=='yeartoday'}selected{/if}" period="yeartoday" id="page_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $page_period=='six_month'}selected{/if}" period="six_month" id="page_period_six_month">{t}6M{/t}</button> <button class="table_option {if $page_period=='quarter'}selected{/if}" period="quarter" id="page_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $page_period=='month'}selected{/if}" period="month" id="page_period_month">{t}1M{/t}</button> <button class="table_option {if $page_period=='ten_day'}selected{/if}" period="ten_day" id="page_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $page_period=='week'}selected{/if}" period="week" id="page_period_week">{t}1W{/t}</button> <button class="table_option {if $page_period=='day'}selected{/if}" period="day" id="page_period_day">{t}1D{/t}</button> <button class="table_option {if $page_period=='hour'}selected{/if}" period="hour" id="page_period_hour">{t}1h{/t}</button> 
				</div>
			</div>
			<div class="buttons small clusters">
				<button class="selected" id="change_pages_table_type">{$pages_table_type_label}</button> 
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0 } 
			<div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $pages_table_type!='thumbnails'}display:none{/if}">
			</div>
			<div id="table0" class="data_table_container dtable btable" style="{if $pages_table_type=='thumbnails'}display:none{/if};font-size:85%">
			</div>
		




		</div>
	</div>
</div>
<div id="change_pages_table_type_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}View items as{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		{foreach from=$pages_table_type_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_table_type('pages','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
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

{include file='footer.tpl'} 
