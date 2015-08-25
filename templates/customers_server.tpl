{include file='header.tpl'} 
<input type="hidden" id="field_labels" value="{$field_labels}"/>
<input type="hidden" id="state_data" value="{$state_data}"/>

<div id="bd" class="no_padding">



 {include file='navigation.tpl' _content=$content} 	

	<div style="padding:0px">
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item {if $type=='contacts_with_orders'}selected{/if}" id="contacts_with_orders"> <span> {t}Contacts with Orders{/t}</span></span></li>
			<li> <span class="item {if $type=='all_contacts'}selected{/if}" id="all_contacts"> <span> {t}All Contacts{/t}</span></span></li>
		</ul>
		<div class="tabs_base">
		</div>
	</div>
	<div style="padding:0 20px">
		<div id="block_contacts_with_orders" style="clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Customers per Store{/t} <img style="display:none" id="export_csv0" tipo="customers_per_store" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1} 
			<div id="table0" class="data_table_container dtable btable with_total">
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
{include file='footer.tpl'} 