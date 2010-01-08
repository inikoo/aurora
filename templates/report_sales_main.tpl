{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}




<div class="cal_menu" style="width:150px" >
      {if $up}<a class="prev" href="report_sales_main.php?{$up.url}" ><img src="art/icons/up.png" alt="&uarr;" title="{$up.title}"  /></a>{/if}

<span>{$tipo_title}</span> <span id="period">{$period}</span>
      {if $prev}<a class="prev" href="report_sales_main.php?{$prev.url}" ><img src="art/icons/previous.png" alt="<" title="{$prev.title}"  /></a>{/if}
      {if $next}<a class="next" href="report_sales_main.php?{$next.url}" ><img src="art/icons/next.png" alt=">" title="{$next.title}"  /></a>{/if}


{if $tipo=='y'}
<table  class="calendar_year">
<tr>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=1">{$m[0]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=2">{$m[1]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=3">{$m[2]}</a></td>
</tr><tr>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=4">{$m[3]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=5">{$m[4]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=6">{$m[5]}</a></td>
</tr><tr>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=7">{$m[6]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=8">{$m[7]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=9">{$m[8]}</a></td>
</tr><tr>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=10">{$m[9]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=11">{$m[10]}</a></td>
<td><a href="report_sales_main.php?tipo=m&y={$period}&m=12">{$m[11]}</a></td>
</tr>
</table>
{/if}
{if $tipo=='w' or $tipo=='m' or $tipo=='d'}
<table  class="calendar_year">
<tr class="top">
<td>w</td>
<td>M</td>
<td>T</td>
<td>W</td>
<td>T</td>
<td>F</td>
<td>S</td>
<td>D</td>
</tr>
{foreach from=$w item=week}
<tr class="day">
<td><a href="report_sales_main.php?tipo=w&y={$week.year}&w={$week.number}">{$week.number}</a></td>
<td ><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_mon}&d={$week.mon}">{$week.mon}</a></td>
<td><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_tue}&d={$week.tue}">{$week.tue}</a></td>
<td><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_wed}&d={$week.wed}">{$week.wed}</a></td>
<td><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_thu}&d={$week.thu}">{$week.thu}</a></td>
<td><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_fri}&d={$week.fri}">{$week.fri}</a></td>
<td><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_sat}&d={$week.sat}">{$week.sat}</a></td>
<td><a href="report_sales_main.php?tipo=d&y={$week.year}&m={$week.m_sun}&d={$week.sun}">{$week.sun}</a></td>
</tr>
{/foreach}
</table>

{/if}
<div class="custom_dates" >
<span id="show_custom_dates">{t}Custom Dates{/t}</span>
<div id="custom_dates_form" style="margin-top:5px">
{t}From{/t}:<span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/>
 <img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /><br/>
 <span >{t}To:{/t}</span> <input   id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/>
 <img   id="calpop2" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /><br/>
 
 <span  class="state_details" id="go_free_report" style="margin-right:16px;position:relative;top:2px"/>Get Report</span>
	</span>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>

</div>
</div>
</div>

<h1 style="clear:left">{$title}</h1>
<table class="report_sales1">
<tr><td>{t}Store{/t}</td><td></td><td>{t}Invoices{/t}</td><td>{t}Net Sales{/t}</td><td></td><td></td><td>{t}Tax{/t}</td></tr>
{foreach from=$store_data   item=data }
<tr class="geo"><td class="label"> {$data.store}</td><td style="text-align:left">{$data.substore}</td><td>{$data.invoices}</td><td>{$data.net}</td><td>{$data.per_eq_net}</td><td>{$data.sub_per_eq_net}</td><td>{$data.tax}</td></tr>
{/foreach}
</table>

<div id="plot" class="top_bar" style="position:relative;left:-20px;clear:both;padding:0;margin:0;">
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

