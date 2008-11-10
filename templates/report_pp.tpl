{include file='header.tpl'}
<div id="bd" >
<div id="sub_header">
{if $next}<span class="nav2 onright"><a href="report_pp.php?{$next.url}">{$next.title}</a></span>{/if}
{if $prev}<span class="nav2 onright" ><a href="report_pp.php?{$prev.url}">{$prev.title}</a></span>{/if}
{if $up}<span class="nav2 onright" style="margin-left:20px"><a href="report_pp.php?{$up.url}">{$up.title}</a></span>{/if}

<span class="nav2"><a href="reports.php">{t}Productivity Reports{/t}</a></span>
</div>
     



<div class="cal_menu" >
<span>{$tipo_title}</span> <span id="period">{$period}</span>
{if $tipo=='y'}
<table  class="calendar_year">
<tr>
<td><a href="report_sales.php?tipo=m&y={$period}&m=1">{$m[0]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=2">{$m[1]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=3">{$m[2]}</a></td>
</tr><tr>
<td><a href="report_sales.php?tipo=m&y={$period}&m=4">{$m[3]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=5">{$m[4]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=6">{$m[5]}</a></td>
</tr><tr>
<td><a href="report_sales.php?tipo=m&y={$period}&m=7">{$m[6]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=8">{$m[7]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=9">{$m[8]}</a></td>
</tr><tr>
<td><a href="report_sales.php?tipo=m&y={$period}&m=10">{$m[9]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=11">{$m[10]}</a></td>
<td><a href="report_sales.php?tipo=m&y={$period}&m=12">{$m[11]}</a></td>
</tr>
</table>
{/if}


</div>

<h1 style="clear:left">{$title}</h1>


 <div class="data_table" style="clear:both;margin:0px 20px">
	<span id="table_title" class="clean_table_title">{t}Pickers Report{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info"> <span class="filter_msg"  id="filter_msg0"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

<div class="data_table" style="clear:both;margin:0px 20px">
	<span id="table_title" class="clean_table_title">{t}Packers Report{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info1" class="clean_table_info"> <span class="filter_msg"  id="filter_msg1"></span></div></div>

	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
	</div>
	<div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>

</div>
{include file='footer.tpl'}

