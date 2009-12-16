{include file='header.tpl'}
<div id="bd" >

  
<div class="block_list">
<h2>Sales</h2>
<div onClick="location.href='report_sales_server.php'">Sales by Period</div>
<div onClick="location.href='report_sales_server.php'">Sales by Location</div>

</div>

<div style="clear:both"class="block_list">
<h2>Activity/Performance</h2>
<div onClick="location.href='report_sales_activity.php'">{t}Sales Activity{/t}</div>
<div onClick="location.href='report_sales_server.php'">{t}Pickers/Packers{/t}</div>
<div onClick="location.href='report_sales_server.php'">{t}Out of Stock{/t}</div>

</div>


  </div> 
 

{include file='footer.tpl'}

