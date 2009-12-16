{include file='header.tpl'}
<div id="bd" >
<div id="sub_header">
{if $next}<span class="nav2 onright"><a href="report_sales_main.php?{$next.url}">{$next.title}</a></span>{/if}
{if $prev}<span class="nav2 onright" ><a href="report_sales_main.php?{$prev.url}">{$prev.title}</a></span>{/if}
{if $up}<span class="nav2 onright" style="margin-left:20px"><a href="report_sales_main.php?{$up.url}">{$up.title}</a></span>{/if}

<span class="nav2"><a href="reports.php">{t}Sales Reports{/t}</a></span>
<span class="nav2"><a href="assets_department.php?id={$department_id}">{$department}</a></span>
<span class="nav2"><a href="assets_family.php?id={$family_id}"></a></span>
</div>
     




<h1 style="clear:left">{$title}</h1>
<table class="report_sales1" border=0>
<tr class="title"><td></td><td style="padding-right:0px">{t}Invoices{/t}</td><td></td><td style="padding-right:0px">{t}Customers{/t}</td><td></td>
<td style="padding-right:0px">{t}Net Sales{/t}</td><td></td></tr>
{foreach from=$store_data   item=data name=foo }
<tr {if $smarty.foreach.foo.last}class="last"{else}class="geo"{/if}><td class="label"> {$data.store}{$data.substore}</td>
<td style="padding-right:0px;padding-left:20px">{$data.invoices}</td>
<td>{$data.per_invoices}{$data.sub_per_invoices}</td>
<td style="padding-right:0px;padding-left:20px">{$data.customers}</td>
<td>{$data.per_customers}{$data.sub_per_customers}</td>
<td style="padding-right:0px;padding-left:20px">{$data.net}</td>
<td>{$data.per_eq_net}{$data.sub_per_eq_net}</td>
<td class="space" style="width:20px"></td>
<td id="compare_invoices"  style="background:{$data.compare_invoices_color}" class="compare">{$data.compare_invoices}</td>
<td id="compare_customers" style="background:{$data.compare_customers_color}" class="compare">{$data.compare_customers}</td>
<td id="compare_net" style="background:{$data.compare_net_color}" class="compare">{$data.compare_net}</td>
{/foreach}
<tr class="space" style="height:15px;"><td><td></td></td><td colspan="4" style="text-align:center"></td><td></td></tr>
<tr style="border:none;font-size:90%"><td></td><td colspan="6" style="text-align:center">{t}Last Week Sales Activity{/t}</td></tr>
</table>


</div>

{include file='footer.tpl'}

