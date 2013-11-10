{include file='header.tpl'}
<div id="bd" >

  <div class="top_navigation">
  <span class="nav2 onleft link {if $tipo=='sales'}selected{/if}" id="sales">{t}Sales by date{/t}</span>
  <span class="nav2 onleft link {if $tipo=='geosales'}selected{/if}" id="geosales" >{t}Sales by location{/t}</span>
  </div>


  <div class="chooser" style="xdisplay:none">
    <ul>
      <li {if $tipo=='sales'}class="selected"{/if} id="salesx"><img src="art/icons/money.png"> Sales</li>
      <li style="display:none"{if $tipo=='geosales'}class="selected"{/if} id="geosalesx" ><img src="art/icons/world.png"> Geo-Sales</li>
      <li style="display:none"{if $tipo=='customers'}class="selected"{/if} id="customers"><img src="art/icons/user.png"> Customers</li>
      <li style="display:none"{if $tipo=='products'}class="selected"{/if} id="products"><img src="art/icons/brick.png"> Products</li>
      <li style="display:none"{if $tipo=='times'}class="selected"{/if} id="times"><img src="art/icons/clock.png"> Dispatching Times</li>
      <li style="display:none"{if $tipo=='prod'}class="selected"{/if} id="prod"><img src="art/icons/cog.png"> Productivity</li>
      <li {if $tipo=='stock'}class="selected"{/if} id="stock"><img src="art/icons/brick.png"> Stock</li>
    </ul>
    
  </div> 
  
  <div {if $tipo!='sales'}style="display:none"{/if}  id="header_sales"  class="report_header" >
    <h2 >{t}Sales Reports{/t}</h2>
    <ul>
      <li ><a href="report_sales_main.php?tipo=y&y={$year}"> <img src="art/icons/calendar_view_year.png"> Anual ({$year})</a></li>
      <li ><a href="report_sales_main.php?tipo=m&y={$year}&m={$month}"> <img src="art/icons/calendar_view_month.png"> Monthly ({$month_name})</a></li>
      <li ><a href="report_sales_main.php?tipo=w&y={$year}&w={$week}"> <img src="art/icons/calendar_view_week.png"> Weekly({$week} {$year})</a></li>
      <li ><a href="report_sales_main.php?tipo=d&y={$year}&m={$month}&d={$day}"> <img src="art/icons/calendar_view_day.png"> {t}Today{/t}</a></li>
      <li style="cursor:default"><img src="art/icons/date.png"> Custom Dates <span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	  <img style="position:relative;right:26px;cursor:pointer" align="absbottom" src="art/icons/application_go.png"  alt="{t}Go{/t}" id="go_free_report"/>
	</span>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </li>
      </ul>
  </div>
  
  <div  {if $tipo!='geosales'}style="display:none"{/if}  id="header_geosales"  class="report_header" >
      <h2 >{t}Sales Reports by Location{/t}</h2>

      
  </div>
 <div  {if $tipo!='products'}style="display:none"{/if}  id="header_products"  class="report_header" >
      <h2 >{t}Product Analisis{/t}</h2>
      <ul>
	<li><a href="report_products_2080.php">{t}Product Activity{/t}</a></li>
      </ul>
      
  </div>


  <div {if $tipo!='customers'}style="display:none"{/if}  id="header_customers"  class="report_header" ></div>
  <div {if $tipo!='times'}style="display:none"{/if}  id="header_times"  class="report_header" ></div>
  <div {if $tipo!='prod'}style="display:none"{/if}  id="header_prod"  class="report_header" >
      <h2 >{t}Pickers and Packers Report{/t}</h2>
    <ul>
      <li ><a href="report_pp.php?tipo=today"> <img src="art/icons/calendar_view_day.png" > {t}Today{/t} {$today}</a></li>
      <li ><a href="report_pp.php?tipo=w&y={$year}&w={$week}"> <img src="art/icons/calendar_view_week.png"> {t}Weekly{/t} ({$week} {$year})</a></li>
      <li ><a href="report_pp.php?tipo=m&y={$year}&m={$month}"> <img src="art/icons/calendar_view_month.png"> {t}Monthly{/t} ({$month_name})</a></li>
      <li style="cursor:default"><img src="art/icons/date.png"> Custom Dates <span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	  <img style="position:relative;right:26px;cursor:pointer" align="absbottom" src="art/icons/application_go.png"  alt="{t}Go{/t}" id="go_free_report"/>
	</span>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </li>
      </ul>
  </div>


  <div {if $tipo!='stock'}style="display:none"{/if}  id="header_stock"  class="report_header" >
 <h2 >{t}Out of stock Report{/t}</h2>
    <ul>
      <li ><a href="report_outofstock.php?tipo=today"> <img src="art/icons/calendar_view_day.png" > {t}Today{/t} {$today}</a></li>
      <li ><a href="report_outofstock.php?tipo=w&y={$year}&w={$week}"> <img src="art/icons/calendar_view_week.png"> {t}Weekly{/t} ({$week} {$year})</a></li>
      <li ><a href="report_outofstock.php?tipo=m&y={$year}&m={$month}"> <img src="art/icons/calendar_view_month.png"> {t}Monthly{/t} ({$month_name})</a></li>
      <li style="cursor:default"><img src="art/icons/date.png"> Custom Dates <span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	  <img style="position:relative;right:26px;cursor:pointer" align="absbottom" src="art/icons/application_go.png"  alt="{t}Go{/t}" id="go_outofstock"/>
	</span>
	<div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	<div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
      </li>
      </ul>
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

    <div {if $tipo!='geosales'}style="display:none"{/if} id="plot_options_geosales" class="plot_options">
    </div>
    <div {if $tipo!='customers'}style="display:none"{/if} id="plot_options_customer" class="plot_options">
    </div>
    <div {if $tipo!='times'}style="display:none"{/if} id="plot_options_times" class="plot_options">
    </div>
    <div {if $tipo!='prod'}style="display:none"{/if} id="plot_options_prod" class="plot_options">
    </div>
    
    
    <div  id="plot_div" class="product_plot"  style="width:930px;xheight:325px;">
      

<iframe style="position:relative;left:-20px" id="the_plot" src ="plot.php?tipo={$plot_tipo}" frameborder=0 height="310" scrolling="no" width="100%"></iframe>
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

  <div id="map" style="padding: 0px 20px;display:{if $tipo!='geosales'}none{/if};">

      <table border=0 style="float:right">
	<tr>
	  <td><span id="world" class="but {if $region=='world'}selected{/if}">{t}World{/t}</span></td>
	  <td><span id="north_america" class="but {if $region=='north_america'}selected{/if}">{t}N America{/t}</span></td>
	  <td><span id="south_america" class="but {if $region=='south_america'}selected{/if}">{t}S America{/t}</span></td>
	  <td><span id="europe" class="but {if $region=='europe'}selected{/if}">{t}Europe{/t}</span></td>
	  <td><span id="asia" class="but {if $region=='asia'}selected{/if}">{t}Asia{/t}</span></td>
	  <td><span id="africa" class="but {if $region=='africa'}selected{/if}">{t}Africa{/t}</span></td>
	  <td><span id="oceania" class="but {if $region=='africa'}selected{/if}">{t}Oceania{/t}</span></td>
	</tr>
	
      </table>
      <img id="map_image" src="{$map_url}">
  </div>


  
</div>

{include file='footer.tpl'}

