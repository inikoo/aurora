{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<input type="hidden" id="category_key" value="0" />
	<div style="padding:0 20px">
		{include file='contacts_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Categories{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Customer Categories Home{/t}</span>
			</div>
			<div class="buttons" style="float:right">
				<button onclick="window.location='edit_customer_categories.php?store_id={$store->id}&id=0'"><img src="art/icons/table_edit.png" alt=""> {t}Edit Categories{/t}</button> <button id="new_category"><img src="art/icons/add.png" alt=""> {t}Main Category{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Categories{/t}</span></span></li>
		{*} 
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}History{/t}</span></span></li>
		{*} 
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_subcategories" style="{if $block_view!='subcategories'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}Main Categories{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable ">
		</div>
	</div>
	{*} 
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable ">
		</div>
	</div>
	{*} 
</div>
{include file='new_category_splinter.tpl'} 
{include file='footer.tpl'} 