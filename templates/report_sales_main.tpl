{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}


{include file='calendar_splinter.tpl'}




<h1 style="clear:left">{$title}</h1>
<table class="report_sales1">
<tr><td>{t}Store{/t}</td><td></td><td>{t}Invoices{/t}</td><td>{t}Net Sales{/t}</td><td></td><td></td><td>{t}Tax{/t}</td></tr>
{foreach from=$store_data   item=data }
<tr class="geo"><td class="label"> {$data.store}</td><td style="text-align:left">{$data.substore}</td><td>{$data.invoices}</td><td>{$data.net}</td><td>{$data.per_eq_net}</td><td>{$data.sub_per_eq_net}</td><td>{$data.tax}</td></tr>
{/foreach}
</table>

<div id="plot" class="top_bar" style="position:relative;left:-20px;clear:both;padding:0;margin:0;{if !$display_plot}display:none{/if}">
<div display="none" id="plot_info" keys="{$store->id}" ></div>
      <ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px "  >
	<li>
	  <span class="item {if $plot_tipo=='store'}selected{/if}" onClick="change_plot(this)" id="plot_store" tipo="store" category="{$plot_data.store.category}" period="{$plot_data.store.period}" >
	    <span>Totals</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='top_departments'}selected{/if}"  id="plot_top_departments" onClick="change_plot(this)" tipo="top_departments" category="{$plot_data.top_departments.category}" period="{$plot_data.top_departments.period}" name=""  >
	    <span>{t}Growth{/t}</span>
	  </span>
	</li>

      </ul> 
      
      <ul id="plot_options" class="tabs" style="{if $plot_tipo=='pie'}display:none{/if};position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	<li><span class="item"> <span id="plot_category"  category="{$plot_category}" style="xborder:1px solid black;display:inline-block; vertical-align:middle">{$plot_formated_category}</span></span></li>

      </ul> 

      <div style="clear:both;margin:0 20px;padding:0 20px ;border-bottom:1px solid #999">
      </div>
      
      <div  id="plot_div" class="product_plot"  style="width:865px;xheight:325px;">
	<iframe id="the_plot" src ="{$plot_page}?{$plot_args}" frameborder=0 height="325" scrolling="no" width="{if $plot_tipo=='pie'}500px{else}100%{/if}"></iframe>
      </div>
      

   
    
  </div>


</div>

{include file='footer.tpl'}

