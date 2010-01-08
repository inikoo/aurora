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
<td style="padding-right:0px">{t}Net Sales{/t}</td><td></td><td class="space"></td><td class="compare_title">&Delta;{t}invoices{/t}</td><td class="compare_title">&Delta;{t}customers{/t}</td><td class="compare_title">&Delta;{t}sales{/t}</td></tr>
{foreach from=$store_data   item=data name=foo }
<tr {if $smarty.foreach.foo.last}class="last"{else}class="geo"{/if}><td class="label"> {$data.store}{$data.substore}</td>
<td style="padding-right:0px;padding-left:20px">{$data.invoices}</td>
<td>{$data.per_invoices}{$data.sub_per_invoices}</td>
<td style="padding-right:0px;padding-left:20px">{$data.customers}</td>
<td>{$data.per_customers}{$data.sub_per_customers}</td>
<td style="padding-right:0px;padding-left:20px">{$data.net}</td>
<td>{$data.per_eq_net}{$data.sub_per_eq_net}</td>
<td class="space" style="width:15px;"></td>
<td id="compare_invoices"  style="background:{$data.compare_invoices_color}" class="compare">{$data.compare_invoices}</td>
<td id="compare_customers" style="background:{$data.compare_customers_color}" class="compare">{$data.compare_customers}</td>
<td id="compare_net" style="background:{$data.compare_net_color}" class="compare">{$data.compare_net}</td>
{/foreach}
<tr  style="height:15px;"><td></td>
<td colspan=6  >
<table class="bracket" style=" ;width:100%;height:100%;padding:0px" border=0>
<tr>
<td style="background:url('art/bracket_left.png') no-repeat bottom right;width:5px"></td></td><td  style="text-align:center;background:url('art/bracket_line.png') repeat-x bottom "></td><td  style="text-align:center;background:url('art/bracket_center.png')  no-repeat bottom;width:4px"></td><td  style="text-align:center;background:url('art/bracket_line.png') repeat-x bottom "></td><td style="background:url('art/bracket_right.png') no-repeat bottom left;width:5px"></td>
</tr>
</table>

</td>
<td></td>
<td colspan=3 >
<table class="bracket" style=" ;width:100%;height:100%;padding:0px" border=0>
</tr>

<tr>
<td style="background:url('art/bracket_left.png') no-repeat bottom right;width:5px"></td></td><td  style="text-align:center;background:url('art/bracket_line.png') repeat-x bottom "></td><td  style="text-align:center;background:url('art/bracket_center.png')  no-repeat bottom;width:4px"></td><td  style="text-align:center;background:url('art/bracket_line.png') repeat-x bottom "></td><td style="background:url('art/bracket_right.png') no-repeat bottom left;width:5px"></td>
</tr>
</table>
</td>
</tr>

<tr class="bracket_label" >
<td></td><td colspan="6" class="period_label">{$period_label}</td><td></td>
<td colspan="3" class="compare_label">{$compare_label}</td>
</tr>


<tr  style="height:8px;"><td></td>
<td></td>
<td colspan=5  >
<table class="bracket" style=" ;width:100%;height:100%;padding:0px" border=0>
<tr>
<td style="background:url('art/bracket_down_left.png') no-repeat bottom right;width:5px"></td></td><td  style="text-align:center;background:url('art/bracket_line.png') repeat-x bottom "></td><td  style="text-align:center;background:url('art/bracket_down_center.png')  no-repeat bottom;width:4px"></td><td  style="text-align:center;background:url('art/bracket_line.png') repeat-x bottom "></td><td style="background:url('art/bracket_down_right.png') no-repeat bottom left;width:5px"></td>
</tr>
</table>

</td>
<td></td>
<td colspan=3 >

</td>
</tr>

<tr class="title">
<td></td><td>{t}Prev Due{/t}</td><td colspan="5" rowspan="3">
<table style="width:100%" border=0>
<tr><td>R</td><td>C</td><td>iP</td><td>PP</td><td>R</td><td>D</td></tr>
{foreach from=$activity_data   item=data name=foo }
<tr class="geo"><td>{$data.received}</td><td>{$data.cancelled}</td><td>{$data.in_process}</td><td>{$data.in_warehouse}</td><td>{$data.ready}</td><td>{$data.dispached}</td>

</tr>

{/foreach}
</table>



</td>
</tr>

{foreach from=$store_data   item=data name=foo}
<tr {if $smarty.foreach.foo.last}class="last"{else}class="geo"{/if}><td class="label"> {$data.store}{$data.substore}</td></tr>
{/foreach}

</table>


</div>
<div id="period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
      {foreach from=$period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_period('{$menu.period}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
{include file='footer.tpl'}



