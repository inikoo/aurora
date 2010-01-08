{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}



  
  <div {if $tipo!='sales'}style="display:none"{/if}  id="header_sales"  class="report_header" >
    <h2 >{t}Sales Reports{/t}</h2>
<div  style='background: url("art/option_block_left.png");;width:16px;height:67px;float:left'></div>
<div style='background: url("art/option_block_center.png");width:560px;padding:0 20px;;height:67px;color:#fff;float:left '>  
  <ul style="margin-top:5px" >
      <li ><a style="color:#fff" href="report_sales_main.php?tipo=y&y={$year}"> <img src="art/icons/calendar_view_year.png"> Anual ({$year})</a></li>
      <li ><a style="color:#fff" href="report_sales_main.php?tipo=m&y={$year}&m={$month}"> <img src="art/icons/calendar_view_month.png"> Monthly ({$month_name})</a></li>
      <li ><a style="color:#fff" href="report_sales_main.php?tipo=w&y={$year}&w={$week}"> <img src="art/icons/calendar_view_week.png"> Weekly({$week} {$year})</a></li>
      <li ><a  style="color:#fff" href="report_sales_main.php?tipo=d&y={$year}&m={$month}&d={$day}"> <img src="art/icons/calendar_view_day.png"> {t}Today{/t}</a></li>
      <li style="cursor:default"><img src="art/icons/date.png"> Custom Dates <span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	  <img style="position:relative;right:26px;cursor:pointer" align="absbottom" src="art/icons/application_go.png"  alt="{t}Go{/t}" id="go_free_report"/>
	</span>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </li>
      </ul>
<div style="clear:both"></div>
</div> 
<div  style='background: url("art/option_block_right.png");;width:16px;height:67px;float:left'></div>
 </div>
  
 


 


 
  
  
  <div {if $tipo=='prod' or $tipo=='geosales' }style="display:none"{/if}  id="front_plot" style="clear:both;xborder:1px solid green;xbackground:red" >
    
    <div {if $tipo!='sales' or $plot_tipo=='timeplot_sales'}style="display:none"{/if} style="display:none" id="plot_options_sales" class="plot_options" >
      
      <table style="margin-top:30px;font-size:87%" border=0>
	<tr style="height:2em"><td><span  id="net_sales_month"  class="but {if $plot_tipo=='total_sales_groupby_month' or $plot_tipo=='total_sales_month'}selected{/if}">Net Sales</span></td></tr>
	
	<tr style="height:2em"><td style="margin:10px 0"><span id="net_diff1y_sales_month" class="but {if $plot_tipo=='net_diff1y_sales_month' or $plot_tipo=='net_diff1y_sales_month_per'}selected{/if}" >Growth</span></td></tr>
	<tr style="height:2em"><td style="margin:10px 0"><span id="timeplot_sales" class="but {if $plot_tipo=='timeplot_sales'}selected{/if}" >Time Series</span></td></tr>
	<tr style="height:2em"><td></td></tr>
	<tr id="tr_net_sales_gmonth" style="display:{if $plot_tipo=='total_sales_groupby_month' or $plot_tipo=='total_sales_month'}block{else}none{/if}"><td>Group by month <input style="position:relative;top:2px" {if $plot_tipo=='total_sales_groupby_month'}checked="checked"{/if}type="checkbox" id="net_sales_gmonth" name="net_sales_gmonth" value="net_sales_gmonth"><td></tr>
	
	
	
	<tr style="display:{if $plot_tipo=='net_diff1y_sales_month' or $plot_tipo=='net_diff1y_sales_month_per'}{else}none{/if}" id="tr_net_diff1y_sales_month"><td>{t}Net difference{/t} <input style="position:relative;top:2px" {if $plot_tipo=='net_diff1y_sales_month'}checked="checked"{/if} type="radio" id="net_diff1y_sales_month_net" name="net_diff1y_sales_month" value="net_sales_gmonth"><td></tr>
	<tr style="display:{if $plot_tipo=='net_diff1y_sales_month' or $plot_tipo=='net_diff1y_sales_month_per'}{else}none{/if}" id="tr_net_diff1y_sales_month_per"><td>{t}Percentage{/t} <input style="position:relative;top:2px" {if $plot_tipo=='net_diff1y_sales_month_per'}checked="checked"{/if} type="radio" id="net_diff1y_sales_month_per" name="net_diff1y_sales_month" value="net_sales_gmonth"><td></tr>
      </table>
    </div>
    <div {if $tipo!='stock'}style="display:none"{/if} id="plot_options_stock" class="plot_options">
      
      <table style="font-size:87%">
      </table>
    </div>

    
    
    
    <div  id="plot_div" class="plot"  >
      

      <iframe class="plot_iframe"  id="the_plot" src="plot.php?tipo={$plot_tipo}" frameborder=0 height="310" scrolling="no" width="100%"></iframe>
      <span style="position:relative;left:70px;">{$plot_title[$plot_tipo]}</span>
      <div id="plot_options_sales_bis" style="margin-left:30px;{if $plot_tipo!='timeplot_sales'}display:none{/if};display:none">
	<table>
	  <tr>
	    <td><span  id="net_sales_month_bis"  class="but">Net Sales</span></td>
	    <td><span  id="net_diff1y_sales_month_bis"  class="but">Growth</span></td>
	    
	    <td style="margin:10px 0"><span  class="but selected" >Time Seriesx</span></td>
	  </tr>
	</table>
      </div>
      <div style="clear:both"></div>
    </div>
    
  </div>

 

  
</div>

{include file='footer.tpl'}

