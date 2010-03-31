{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}


{include file='calendar_splinter.tpl'}




<h1 style="clear:left">{$title}</h1>


<table class="report_sales1" id="report_sales_invoices">
<tr style="border-bottom:1px solid #ccc;margin-bottom:5px"><td colspan=7>
<div  style="margin-bottom:5px">
<span class="state_details" style="margin-right:20px">{t}Profit{/t}</span>
<span class="state_details selected">{t}Invoices{/t}</span>
</div>
</td></tr>
<tr><td>{t}Store{/t}</td><td></td><td>{t}Invoices{/t}</td><td>{t}Net Sales{/t}</td><td></td><td></td><td>{t}Tax{/t}</td></tr>
{foreach from=$store_data   item=data }
<tr class="geo"><td class="label"> {$data.store}</td><td style="text-align:left">{$data.substore}</td><td>{$data.invoices}</td><td>{$data.net}</td><td>{$data.per_eq_net}</td><td>{$data.sub_per_eq_net}</td><td>{$data.tax}</td></tr>
{/foreach}
</table>


<table class="report_sales1"id="report_sales_profit" >
<tr style="border-bottom:1px solid #ccc;margin-bottom:5px"><td colspan=7>
<div  style="margin-bottom:5px">
<span class="state_details selected" style="margin-right:20px">{t}Profit{/t}</span>
<span class="state_details ">{t}Invoices{/t}</span>
</div>
</td></tr>
<tr><td>{t}Store{/t}</td><td></td><td>{t}Revenue{/t}</td><td>{t}Profit{/t}</td><td>{t}Margin{/t}</td><td></td><td></td></tr>
{foreach from=$store_data_profit   item=data }
<tr class="geo"><td class="label"> {$data.store}</td><td style="text-align:left">{$data.substore}</td><td>{$data.net}</td><td>{$data.profit}</td><td>{$data.margin}</td></tr>
{/foreach}
</table>



<div id="plot" class="top_bar" style="position:relative;left:-20px;clear:both;padding:0;margin:0;{if !$display_plot}display:none{/if}">
<div display="none" id="plot_info" keys="{$store_keys}"  invoice_category_keys="{$invoice_category_keys}"   ></div>
      <ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px "  >
	<li>
	  <span class="item {if $plot_tipo=='per_store'}selected{/if}" onClick="change_plot(this)" id="plot_per_store" tipo="par_store" category="{$plot_data.per_store.category}" period="{$plot_data.per_store.period}" >
	    <span>Invoices per Store</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='per_category'}selected{/if}"  id="plot_per_category" onClick="change_plot(this)" tipo="per_category" category="{$plot_data.per_category.category}" period="{$plot_data.per_category.period}" name=""  >
	    <span>{t}Invoices per Category{/t}</span>
	  </span>
	</li>

      </ul> 
      
      <ul id="plot_options" class="tabs" style="{if $plot_tipo=='pie'}display:none{/if};position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	<li><span class="item"> <span id="plot_category"  category="{$plot_category}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_category}</span></span></li>
	<li><span class="item"> <span id="plot_period"   period="{$plot_period}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_period}</span></span></li>
    	
      </ul> 

      <div style="clear:both;margin:0 20px;padding:0 20px ;border-bottom:1px solid #999">
      </div>
      
      <div  id="plot_div" class="product_plot"  style="width:865px;xheight:325px;">
	<iframe id="the_plot" src ="{$plot_page}?{$plot_args}" frameborder=0 height="325" scrolling="no" width="{if $plot_tipo=='pie'}500px{else}100%{/if}"></iframe>
      </div>
      

   
    
  </div>


</div>

{include file='footer.tpl'}

