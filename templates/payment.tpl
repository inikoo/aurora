{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 

		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="stores.php"> &#8704; {t}Stores{/t}</a> &rarr; {$store->get('Store Name')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_store.php?id={$store->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Store{/t}</button> {/if} <button style="display:none" onclick="window.location='store_stats.php?store={$store->id}'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button style="display:none" onclick="window.location='store_deals.php?store={$store->id}'"><img src="art/icons/money.png" alt=""> {t}Offers{/t}</button> <button style="display:none" onclick="window.location='products_lists.php?store={$store->id}'"><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button> <button id="choose_categories"><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button> {if $store->get('Store Websites')} <button style="display:none" onclick="window.location='sites.php?store={$store->id}'"><img src="art/icons/world.png" alt=""> {if $store->get('Store Websites')>1}{t}Websites{/t}{else}{t}Website{/t}{/if}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title"> <img src="art/icons/payment.png" style="height:18px;position:relative;bottom:2px" /> {$store->get('Store Name')} ({$store->get('Store Code')}) </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Overview{/t}</span></span></li>
		<li> <span class="item {if $block_view=='changelog'}selected{/if}" id="changelog"> <span> {t}History{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div style="padding:0 0px">
	
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
			
			<div style="margin-bottom:20px">
				<h2 style="margin:0;padding:0">
					{t}Store Information{/t} 
				</h2>
				<div style="width:350px;float:left">
					<table class="show_info_product">
						<tr>
							<td>{t}Code{/t}:</td>
							<td class="price">{$store->get('Store Code')}</td>
						</tr>
						<tr>
							<td>{t}Name{/t}:</td>
							<td>{$store->get('Store Name')}</td>
						</tr>
						
					</table>
				</div>
				<div style="width:200px;float:left;margin-left:20px">
					<table class="show_info_product">
						<tr>
							<td>{t}Departments{/t}:</td>
							<td class="number"> 
							<div>
								{$store->get('Departments')} 
							</div>
							</td>
						</tr>
						<tr>
							<td>{t}Families{/t}:</td>
							<td class="number"> 
							<div>
								{$store->get('Families')} 
							</div>
							</td>
						</tr>
						<tr>
							<td>{t}Products{/t}:</td>
							<td class="number"> 
							<div>
								{$store->get('For Public Sale Products')} 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div style="clear:both;">
				</div>
			</div>
			
		</div>

		<div id="block_changelog" style="{if $block_view!='changelog'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}History{/t}
				
				
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				
				<div id="table0" class="data_table_container dtable btable with_total" style="font-size:85%">
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
{include file='footer.tpl'} 