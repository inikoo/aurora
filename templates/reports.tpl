{include file='header.tpl'}
<div id="bd" >
  <div class="report_chosser" >
    <ul>
      <li id="report_sales"><a href="report_sales.php"> <img src="art/icons/money.png"> Sales</a></li>
      <li id="geo_sales" ><img src="art/icons/world.png"> Geo-Sales</li>
      <li><img src="art/icons/user.png"> Customers</li>
      <li><img src="art/icons/clock.png"> Dispaching Times</li>
      <li><img src="art/icons/cog.png"> Productivity</li>
      <li><img src="art/icons/brick.png"> Stock</li>
    </ul>
    
  </div> 
  
  <div  id="plot_options"  style="clear:both;margin-top:20px">
    <div class="report_chosser2" >
      <h2>{t}Sales Reports{/t}</h2>
      <ul>
	<li ><a href="report_sales.php?tipo=y&y={$year}"> <img src="art/icons/calendar_view_year.png"> Anual ({$year})</a>
	</li>
	<li ><a href="report_sales.php?tipo=m&y={$year}&m={$month}"> <img src="art/icons/calendar_view_month.png"> Monthly ({$month_name})</a></li>
	<li ><a href="report_sales.php?tipo=w&y={$year}&w={$week}"> <img src="art/icons/calendar_view_week.png"> Weekly({$week} {$year})</a></li>
	<li style="cursor:default"><img src="art/icons/date.png"> Custom Dates <span style="position:relative;left:0px;"><input id="v_calpop1" type="text" class="text" size="11" maxlength="10" name="from" value="{$from}"/><img   id="calpop1" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> <span class="calpop">&rarr;</span> <input   class="calpop" id="v_calpop2" size="11" maxlength="10"   type="text" class="text" size="8" name="to" value="{$to}"/><img   id="calpop2" class="calpop_to" src="art/icons/calendar_view_month.png" align="absbottom" alt=""   /> 
	      <img style="position:relative;right:26px;cursor:pointer" align="absbottom" src="art/icons/application_go.png"  alt="{t}Go{/t}" id="go_free_report"/>
	    </span>
	  <div id="cal1Container" style="position:absolute;display:none; z-index:2"></div>
	  <div style="position:relative;right:-120px"><div id="cal2Container" style="display:none; z-index:2;position:absolute"></div></div>
	    
	  
	</li>
      </ul>
      
    </div>
  </div> 

  <div id="front_plot" style="clear:both;">
    <div class="plot_options">
      <h3>Historic Monthly Net Sales</h3>
      <table style="font-size:87%">
	<tr><td>Group by month <input style="position:relative;top:2px" type="checkbox" id="net_sales_gmonth" name="net_sales_gmonth" value="net_sales_gmonth"><td></tr>
      </table>
    </div>
    <div id="plot_div" class="product_plot"  style="width:810px;height:300px;">
      <iframe id="the_plot" src ="plot.php?tipo={$plot_tipo}" frameborder=0 height="100%" scrolling="no" width="100%"></iframe>
      <div style="clear:both"></div>
    </div>
  </div>
  
</div>

{include file='footer.tpl'}

