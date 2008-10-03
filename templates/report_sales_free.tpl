{include file='header.tpl'}
<div id="bd" >
<div id="sub_header">
{if $next}<span class="nav2 onright"><a href="report_sales.php?{$next.url}">{$next.title}</a></span>{/if}
{if $prev}<span class="nav2 onright" ><a href="report_sales.php?{$prev.url}">{$prev.title}</a></span>{/if}
{if $up}<span class="nav2 onright" style="margin-left:20px"><a href="report_sales.php?{$up.url}">{$up.title}</a></span>{/if}
<span class="nav2 onright"><a href="assets_index.php">{t}Product index{/t}</a></span>
<span class="nav2"><a href="reports.php">{t}Sales Reports{/t}</a></span>
<span class="nav2"><a href="assets_department.php?id={$department_id}">{$department}</a></span>
<span class="nav2"><a href="assets_family.php?id={$family_id}"></a></span>
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

<h1>{$title}</h1>

{if $total_invoices>0}
<p style="width:450px">
<b>{$total_invoices}</b> orders has been invoiced during this period ({$days}), which amounts to a net total of <b>{$total_net}</b>.{if $todo_orders>0}{/if } 
</p>
<table class="report_sales1">
<tr><td></td><td>{t}Invoices{/t}</td><td>{t}Net Sales{/t}</td><td>{t}Tax{/t}</td><td>{t}Refunds{/t}</td><td>{t}Customers{/t}</td></tr>
<tr class="first"><td class="label">{$home}</td><td>{$invoices_home}</td><td>{$net_home}</td><td>{$tax_home}</td><td>{$refund_home}</td></tr>
<tr class="first"><td class="label">{t}Exports{/t}</td><td>{$total_invoices_nohome}</td><td>{$total_net_nohome}</td><td>{$total_tax_nohome}</td><td>{$refund_nohome}</td></tr>

<tr class="partners"><td>Partners</td><td class="label">{$invoices_p}</td><td>{$net_p}</td><td>{$tax_p}</td><td>{$refund_p}</td></tr>
<tr class="total" ><td>Total</td><td>{$total_invoices}</td><td>{$total_net}</td><td>{$total_tax}</td><td>{$refund}</td></tr>
</table>
<p style="width:375px">
We received <b>{$orders_total}</b> orders on {if $tipo=='m'}{$period}{/if},   <b>{$per_orders_invoices}</b> has been invoiced. The average process time was {$orders_ptime}. 
</p>
<table class="report_sales1">
<tr><td></td><td>Number</td><td>Net Value</td><td></tr>
<tr class="first"><td class="label">{t}Invoices{/t}</td><td>{$orders_invoices}</td><td>{$orders_invoices_net}</td></tr>
<tr class="geo"><td class="label">{t}Follows{/t}</td><td>{$orders_follows}</td><td>{$orders_follows_net}</td></tr>
<tr class="geo"><td class="label">{t}Cancellations{/t}</td><td>{$orders_cancelled}</td><td>{$orders_cancelled_net}</td></tr>
{if $orders_donations>0}<tr class="geo  {if  $orders_others==0}last{/if}"><td class="label">{t}Donations{/t}</td><td>{$orders_donations}</td><td>{$orders_donation_net}</td></tr>{/if}
{if $orders_others>0}<tr class="geo  last"><td class="label last">{t}Others{/t}</td><td>{$orders_others}</td><td>{$orders_others_net}</td></tr>{/if}

<tr class="geo"><td class="label">{t}To do{/t}</td><td>{$orders_todo}</td><td>{$orders_todo_net}</td></tr>


<tr class="total" ><td>Total</td><td>{$orders_total}</td><td>{$orders_total_net}</td></tr>
</table>


<h2>Partners</h2>
<p>
The {$per_partner_sales} of the sales are due to our partners
</p>
<h2>Exports</h2>
<table  class="report_export_countries" style="width:550px">
<tr class="tlabels"><td class="country">Country</td><td>Orders</td><td>Sales</td><td>Taxes</td><td></td></tr>
{foreach name=outer item=countries from=$export_countries}
  <tr>
 <td class="country">{$countries.country}</td><td>{$countries.orders}</td><td>{$countries.net}</td><td>{$countries.tax}</td><td>{$countries.share}</td><td>{if $countries.eu}<img src="art/flags/eu.gif">{/if}</td>

</tr>
{/foreach}
</table>

<p style="width:350px">
The export sales represent   {$per_export} ({$per_export_nop} without partners) of our sale s. Excluding our partners, the principal export destination  is {$export_country1} with a {$per_export_country1} share in the total export sales  follow by  {$export_country2} ({$per_export_country2}) and {$export_country3} ({$per_export_country3}).
<table class="report_sales1" style="width:350px">
<tr><td></td><td>Orders</td><td>Net Sales</td><td>Tax</td></tr>
<tr class="geo"><td class="label">{$extended_home_nohome}</td><td>{$invoices_extended_home_nohome}</td><td>{$net_extended_home_nohome}</td><td>{$tax_extended_home_nohome}</td></tr>
<tr class="geo" ><td class="label"> {$region}<td>{$invoices_region_nohome}</td><td>{$net_region_nohome}</td><td>{$tax_region_nohome}</td></tr>
<tr class="geo"><td class="label">{$region2}</td><td>{$invoices_region2_nohome}</td><td>{$net_region2_nohome}</td><td>{$tax_region2_nohome}</td></tr>
<tr class="outside last " ><td class="label">{$outside}</td><td>{$invoices_outside}</td><td>{$net_outside}</td><td>{$tax_outside}</td></tr>
<tr class="subtotal"  ><td>Subtotal</td><td>{$invoices_nohome}</td><td>{$net_nohome}</td><td>{$tax_nohome}</td></tr>
<tr class="partners"><td>Partners</td><td>{$invoices_p_nohome}</td><td>{$net_p_nohome}</td><td>{$tax_p_nohome}</td></tr>
<tr class="total" ><td>Total</td><td>{$total_invoices_nohome}</td><td>{$total_net_nohome}</td><td>{$total_tax_nohome}</td></tr>

<tr class="org"><td class="label">{$org}</td><td>{$invoices_org_nohome}</td><td>{$net_org_nohome}</td><td>{$tax_org_nohome}</td></tr>
</table>



<p style="width:500px;clear:both">
With respect last year there is <b>{$text_diff_sales}</b> on the sales {$text_diff_orders_link} <b>{$text_diff_orders}</b> orders.
</p>


{else}
{if $todo_orders>0}
{$todo_orders} orders are waiting to be dispatched, which amounts to a total of  {$to_be_net}.
{else}
{t}No orders has been placed in this period{/t}.
{/if}
{/if}




	</div> 








  </div>
</div> 
{include file='footer.tpl'}

