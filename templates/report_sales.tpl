{include file='header.tpl'}
<div id="bd" >
<div id="sub_header">
{if $next}<span class="nav2 onright"><a href="report_sales.php?{$next.url}">{$next.title}</a></span>{/if}
{if $prev}<span class="nav2 onright" ><a href="report_sales.php?{$prev.url}">{$prev.title}</a></span>{/if}
{if $up}<span class="nav2 onright" style="margin-left:20px"><a href="report_sales.php?{$up.url}">{$up.title}</a></span>{/if}

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

<h1 style="clear:left">{$title}</h1>

{if $total_invoices>0}
<p style="width:450px">
<b>{$total_invoices}</b> <img id="invoices" style="vertical-align:middle;cursor:pointer" title="{t}Display orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/> orders has been invoiced during this period ({$days}), which amounts to a net total of <b>{$total_net}</b>.{if $todo_orders>0}{/if } 
</p>
<table class="report_sales1">
<tr><td></td><td>{t}Invoices{/t}</td><td>{t}Net Sales{/t}</td><td>{t}Tax{/t}</td><td>{t}Refunds{/t}</td><td>{t}Customers{/t}</td></tr>
<tr class="first"><td class="label">{$home}</td><td>{$invoices_home} <img id="invoices_home" style="vertical-align:middle;cursor:pointer" title="{t}Display home orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$net_home}</td><td>{$tax_home}</td><td>{$refund_home}</td></tr>
<tr class="first"><td class="label">{t}Exports{/t}</td><td>{$invoices_nohome} <img id="invoices_nohome" style="vertical-align:middle;cursor:pointer" title="{t}Display export orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$net_nohome}</td><td>{$tax_nohome}</td><td>{$refund_nohome}</td></tr>

<tr class="partners"><td>Partners</td><td class="label">{$invoices_p} <img id="invoices_partner" style="vertical-align:middle;cursor:pointer" title="{t}Display pertner's orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$net_p}</td><td>{$tax_p}</td><td>{$refund_p}</td></tr>
<tr class="total" ><td>Total</td><td>{$total_invoices} <img id="invoices_total" style="vertical-align:middle;cursor:pointer" title="{t}Display orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$total_net}</td><td>{$total_tax}</td><td>{$refund}</td></tr>
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

<h2>Comparision Prevous Year</h2>
<table border=0>
<tr>
<td><img src="{if $diff_sales<0}art/down.png{else}art/up.png{/if}"/></td>
<td><b>{t}Net Sales{/t}</b><br>{$per_diff_sales}</td>
<td style="padding-left:30px"><img src="{if $diff_invoices<0}art/down.png{else}art/up.png{/if}"/></td>
<td><b>{t}Orders Invoiced{/t}</b><br>{$per_diff_invoices}</td>
<td style="padding-left:30px"><img src="up.png"/></td>
<td><b>{t}Orders Received{/t}</b><br>33.33%</td>
</tr>
</table>
<p style="width:500px;clear:both">
With respect last year there is <b>{$text_diff_sales}</b> on the sales {$text_diff_invoices_link} <b>{$text_diff_invoices}</b> orders invoiced.
</p>




<h2>Partners</h2>
<p>
The {$per_partner_sales} of the sales are due to our partners
</p>
<h2>Exports</h2>
<table  class="report_export_countries" style="width:550px">
<tr class="tlabels"><td class="country">Country</td><td>Orders</td><td>Sales</td><td>Taxes</td><td></td></tr>
{foreach name=outer item=countries from=$export_countries}
  <tr>
 <td class="country">{$countries.country}</td><td>{$countries.orders} <img onClick="show_invoices_country({$countries.id},'{$countries.name}')" style="vertical-align:middle;cursor:pointer" title="{t}Display {$countries.name} orders{/t}"src="art/icons/magnify-clip.png"/></td><td>{$countries.net}</td><td>{$countries.tax}</td><td>{$countries.share}</td><td>{if $countries.eu}<img src="art/flags/eu.gif">{/if}</td>

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


<div id="orders1">
  <div id="orders1_hd" class="hd">&nbsp;</div>
  <div class="bd">
     <div class="data_table" style="margin:5px 20px;">
    <span class="clean_table_title" id="clean_table_title0"></span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{t}Number{/t}</span>: <input style="border-bottom:none" id='f_input0' value="" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  </div>
  <div class="ft"></div>
</div>



{include file='footer.tpl'}

