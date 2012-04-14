<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key"  value="{$page->customer->id}"/>
<input type="hidden" id="label_dispatched" value="{t}Dispatched{/t}" />
<input type="hidden" id="label_description" value="{t}Description{/t}" />
<input type="hidden" id="label_subject" value="{t}Family{/t}" />
<input type="hidden" id="label_orders" value="{t}Orders{/t}" />



{include file='profile_header.tpl' select='products'}  

<div id="dialog_orders"    class="dialog_inikoo logged"  >
<h2>{t}Products Ordered{/t}</h2>

<div style="border:1px solid #ccc;padding:20px;width:700px;float:left;margin-bottom:40px">
{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=true }
    <div  id="table0"   class="data_table_container dtable btable "> </div>

</div>


</div>



