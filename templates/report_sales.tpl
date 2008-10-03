{include file='header.tpl'}
<div id="bd" >

     

<div class="report_chosser2" >
<h1>{$title}</h1>

<ul>
<li id="report_sales_year"><a href="report_sales.php?tipo=y&y={$year}"> <img src="art/icons/calendar_view_year.png"> Anual Report ({$year})</a></li>
<li id="report_sales_year"><a href="report_sales.php?tipo=m&y={$year}&m={$month}"> <img src="art/icons/calendar_view_month.png"> Monthly Report ({$month_name})</a></li>
<li id="report_sales_year"><a href="report_sales.php?tipo=w&y={$year}&w={$week}"> <img src="art/icons/calendar_view_week.png"> Weekly Report (Week {$week} {$year})</a></li>
<li id="report_sales_year"><a href="report_sales.php?tipo=f&from={$from}&to={$to}"> <img src="art/icons/date.png"> Custom Report</a></li>

</ul>

	</div> 





<div style="padding:10px 0px;xborder:1px solid red;clear:both;text-align:center" >
<h3>{t}Net sales per month{/t}</h3>
<div id="main_sales_plot" style="height:350px">{t}Please note: The YUI Charts Control requires Flash Player 9.0.45 or higher. The latest version of Flash Player is available at the{/t} <a href="http://www.adobe.com/go/getflashplayer">{t}Adobe Flash Player Download Center{/t}</a>.</div>

</div>



  </div>
</div> 
{include file='footer.tpl'}

