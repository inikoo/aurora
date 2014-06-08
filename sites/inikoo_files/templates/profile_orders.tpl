<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key" value="{$page->customer->id}" />


<input type="hidden" id="rrp" value="{$rrp}" />
<input type="hidden" id="_order" value="{$_order}" />
<input type="hidden" id="_order_dir" value="{$_order_dir}" />

<input type="hidden" id="label_id" value="{t}Order ID{/t}" />
<input type="hidden" id="label_state" value="{t}Order State{/t}" />
<input type="hidden" id="label_date" value="{t}Order Date"{/t}" />
<input type="hidden" id="label_total" value="{t}Total{/t}" />



{include file='profile_header.tpl' select='orders'} 
<div id="dialog_orders" style="padding:0 20px 40px 20px">
	<h2 style="float:left">
		{t}Orders{/t} 
	</h2>
	<div class="table_top_bar space">
	</div>
	{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=true } 
	<div id="table0" class="data_table_container dtable btable ">
	</div>
</div>