

      <div display="none" id="plot_info" keys="{$store->id}"    from="{$plot_interval}" to="{$plot_forecast}"   category="{$plot_category}" period="{$plot_period}"    ></div>
      <ul id="plot_chooser" class="tabs" style="margin-left:20px;padding:0 0px "  >

	{if $page=='part'}
		<li>
		  <span class="item {if $plot_tipo=='part_stock_history'}selected{/if}" onClick="change_plot(this)" id="plot_part_stock_history" tipo="part_stock_history"    >
		    <span>{$part->get_sku()} {t}Stock History{/t}</span>
		  </span>
		</li>
	<li>
	  <span class="item {if $plot_tipo=='part_outs'}selected{/if}"  id="plot_part_outs" onClick="change_plot(this)" tipo="part_outs"  >
	    <span>{t}Stock Outs{/t}</span>
	  </span>
	</li>
	<li>
	{/if}
	{if $page=='store'}
	<li>
	  <span class="item {if $plot_tipo=='store'}selected{/if}" onClick="change_plot(this)" id="plot_store" tipo="store"    >
	    <span>{$store->get('Store Code')} {t}Store{/t}</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='top_departments'}selected{/if}"  id="plot_top_departments" onClick="change_plot(this)" tipo="top_departments"  >
	    <span>{t}Top Departments{/t}</span>
	  </span>
	</li>
	<li>
	  <span class="item {if $plot_tipo=='pie'}selected{/if}" onClick="change_plot(this)" id="plot_pie" tipo="pie"     forecast="{$plot_data.pie.forecast}" interval="{$plot_data.pie.interval}"  >
	    <span>{t}Department's Pie{/t}</span>
	  </span>
	</li>
     
      {/if}
      {if $page=='product'}

	<li>
	  <span class="item {if $plot_tipo=='product'}selected{/if}" onClick="change_plot(this)" id="plot_product" tipo="product"    >
	    <span>{$product->get('Product Code')} {t}Sales{/t}</span>
	  </span>
	</li>
      	<li>
	  <span class="item {if $plot_tipo=='parts'}selected{/if}" onClick="change_plot(this)" id="plot_parts" tipo="parts"    >
	    <span>{t}Stock History{/t}</span>
	  </span>
	</li>

      {/if}
       </ul> 


      <ul id="plot_options" class="tabs" style="position:relative;top:.6em;float:right;margin:0 20px;padding:0 20px;font-size:90% "  >
	<li><span class="item"> <span id="plot_category"  category="{$plot_category}" >{$plot_formated_category}</span></span></li>
	<li {if $plot_tipo=='pie'}style="display:none"{/if}><span class="item"> <span id="plot_period"   period="{$plot_period}" >{$plot_formated_period}</span></span></li>
	<li {if $plot_tipo=='pie'}style="display:none"{/if}><span class="item"> <span id="plot_interval">{$plot_interval_label}</span> <span></li>
	<li {if $plot_tipo!='pie'}style="display:none"{/if}><span class="item"> <span id="pie_interval">{$pie_interval_label}</span> </span></li>
	
      </ul> 

      <div style="clear:both;border-bottom:1px solid #999">
      </div>
       {if $page=='product' or $page=='store' or $page=='department' or $page=='family' }

      <div id="pie_options"  style="display:none;{if $plot_tipo!='pie'}display:none;{/if}border:1px solid #ddd;float:right;margin:20px 0px;margin-right:40px;width:300px;padding:10px">
	<table id="pie_category_options" style="float:none;margin-bottom:10px;margin-left:30px"  class="options_mini" >
	  <tr>
	    <td  {if $plot_data.pie.category=='sales'}class="selected"{/if} period="sales"  id="pie_category_sales" >{t}Sales{/t}</td>
	    <td {if $plot_data.pie.category=='profit'}class="selected"{/if}  period="profit"  id="pie_category_profit"  >{t}Profit{/t}</td>
	  </tr>
	</table>
	<table id="pie_period_options" style="float:none;margin-bottom:20px;margin-left:30px"  class="options_mini" >
	  <tr>
	    <td  {if $plot_data.pie.period=='all'}class="selected"{/if} period="all"  id="pie_period_all" onclick="change_plot_period('all')" >{t}All{/t}</td>
	    <td {if $plot_data.pie.period=='y'}class="selected"{/if}  period="year"  id="pie_period_year" onclick="change_plot_period('y')"  >{t}Year{/t}</td>
	    <td  {if $plot_data.pie.period=='q'}class="selected"{/if}  period="quarter"  id="pie_period_quarter" onclick="change_plot_period('q')"  >{t}Quarter{/t}</td>
	    <td {if $plot_data.pie.period =='m'}class="selected"{/if}  period="month"  id="pie_period_month" onclick="change_plot_period('m')"  >{t}Month{/t}</td>
	    <td  {if $plot_data.pie.period=='w'}class="selected"{/if} period="week"  id="pie_period_week" onclick="change_plot_period('w')"  >{t}Week{/t}</td>
	  </tr>
	</table>
	<div style="font-size:90%;margin-left:30px">
	  <span>{$plot_formated_period}</span>: <input class="text" type="text" value="{$plot_formated_date}" style="width:6em"/> <img style="display:none" src="art/icons/chart_pie.png" alt="{t}update{/t}"/>
	</div>
      </div>
{/if}      

      <div  id="plot_div" class="product_plot"  style="width:940px;height:425px;position:relative;left:-10px">
	<iframe id="the_plot" src ="{$plot_page}?{$plot_args}" frameborder="0" height="425" scrolling="no" width="100%"></iframe>
	
      </div>
     
<div style="position:relative;left:-10000px">

   <div id="plot_period_menu" class="yuimenu">
  <div class="bd">
  <h3>{t}Plot frequency{/t}:</h3>
    <ul class="first-of-type">
      {foreach from=$plot_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_period('{$menu.period}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="plot_category_menu" class="yuimenu" >
  <div class="bd">
  <h3>Plot Type</h3>
    <ul class="first-of-type">
      {foreach from=$plot_category_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_category('{$menu.category}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="plot_interval_menu" class="yuimenu">
  <div class="bd">
  <h3>{t}Plot Interval{/t}:</h3>
    <ul class="first-of-type">
      {foreach from=$plot_interval_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_interval({$menu.value})"> {$menu.label}</a></li>
      {/foreach}
       <h3>{t}Plot Forecast{/t}:</h3>
      {foreach from=$plot_forecast_interval_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_forecast_interval({$menu.value})"> {$menu.label}</a></li>
      {/foreach}
      
    </ul>
  </div>
</div>
<div id="pie_interval_menu" class="yuimenu" >
  <div class="bd">
  <h3>{t}Pie Interval{/t}:</h3>
    <ul class="first-of-type">
      {foreach from=$pie_interval_menu key=menu_key item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_pie_interval('{$menu_key}')"> {$menu.label}</a></li>
      {/foreach}
       
      
    </ul>
  </div>
</div>
</div>
