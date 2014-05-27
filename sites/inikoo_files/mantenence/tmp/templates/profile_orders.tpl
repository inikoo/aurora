<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />

{include file='profile_header.tpl' select='orders'}  


       
<div id="orders_block" style="padding:0px 20px">

<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>


<div>
<h2>{t}Orders{/t}</h2>
     {include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=true }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>

<div>
 {include file=$order_template}
</div>

</div>