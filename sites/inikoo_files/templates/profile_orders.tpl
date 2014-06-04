<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key" value="{$page->customer->id}" />
{include file='profile_header.tpl' select='orders'} 
<div id="dialog_orders" class="xdialog_inikoo logged">
	
	<h2 style="padding:20px 0 0px 20px">
		{t}Orders{/t}
	</h2>
	<div style="padding:20px">
		{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=true } 
		<div id="table0" class="data_table_container dtable btable ">
		</div>
	
</div>
