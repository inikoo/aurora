{include file='header.tpl'}
<div id="bd" >


<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a> &rarr; <a  href="reports.php">{t}Reports{/t}</a> &rarr; {t}Sales{/t}</span>
</div>


<h1 style="clear:left;width:700px;">{$title}</h1>

{if $total_invoices>0}
<p style="width:450px">
<b>{$total_invoices}</b> <img id="invoices" style="vertical-align:middle;cursor:pointer" title="{t}Display orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/> orders has been invoiced during this period ({$days}), which amounts to a net total of <b>{$total_net}</b>.
</p>
<table class="report_sales1">
<tr><td></td><td>{t}Invoices{/t}</td><td>{t}Net Sales{/t}</td><td>{t}Tax{/t}</td></tr>
<tr class="first">
<td class="label">{$home}</td><td>{$invoices_home} <img id="invoices_home" style="vertical-align:middle;cursor:pointer" title="{t}Display home orders invoiced in this period{/t}"src="art/icons/magnify-clip.png" /></td>
<td>{$net_home}</td>
<td>{$tax_home}</td>
</tr>

<tr class="first"><td class="label">{t}Exports{/t}</td><td>{$invoices_nohome} <img id="invoices_nohome" style="vertical-align:middle;cursor:pointer" title="{t}Display export orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$net_nohome}</td><td>{$tax_nohome}</td></tr>
{if invoices_partner!=0}
<tr class="partners"><td>Partners</td><td class="label">{$invoices_p} <img id="invoices_partner" style="vertical-align:middle;cursor:pointer" title="{t}Display pertner's orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$net_p}</td><td>{$tax_p}</td></tr>
{/if}
{if $invoices_unk!=0}
<tr class="first"><td class="label">{t}Unknown{/t}</td><td>{$invoices_unk} <img id="invoices_unknown" style="vertical-align:middle;cursor:pointer" title="{t}Display export orders invoiced in this period{/t}" src="art/icons/magnify-clip.png"/></td><td>{$net_unk}</td><td>{$tax_unk}</td></tr>
{/if}
<tr class="total" ><td>Total</td><td>{$total_invoices} <img id="invoices_total" style="vertical-align:middle;cursor:pointer" title="{t}Display orders invoiced in this period{/t}"src="art/icons/magnify-clip.png"/></td><td>{$total_net}</td><td>{$total_tax}</td></tr>


</table>

<h2>Tax Break Down</h2>

<table class="report_sales1">
<tr class="title"><td class="label">{t}Taxable Invoices{/t}</td></tr>
{foreach from=$taxable   item=data key=rate}
<tr class="geo"><td class="label">{$rate} {t}rate{/t}</td><td>{$data.invoices}</td><td>{$data.sales}</td><td>{$data.tax}</td></tr>
{/foreach}
{foreach from=$error_taxable   item=data key=rate}
<tr class="geo"><td class="label"><img src="art/icons/error.png"/> {$rate} {t}rate{/t}</td><td>{$data.invoices}</td><td>{$data.sales}</td><td>{$data.tax}</td></tr>
{/foreach}
<tr class="total"><td class="label">{t}Total{/t}</td><td>{$invoices_taxeable_all}</td><td>{$net_taxable_all}</td><td>{$tax_taxable_all}</td></tr>
<tr class="title"><td class="label">{t}Tax Free Invoices{/t}</td></tr>
{foreach from=$notaxable   item=data key=rate}
<tr class="geo"><td class="label">{t}0% rate{/t}</td><td>{$data.invoices}</td><td>{$data.sales}</td><td>{$data.tax}</td></tr>
{/foreach}
{foreach from=$error_notaxable   item=data key=rate}
<tr class="geo"><td class="label"><img src="art/icons/error.png"/> {t}Non zero rate{/t}</td><td>{$data.invoices}</td><td>{$data.sales}</td><td>{$data.tax}</td></tr>
{/foreach}
<tr class="total"><td class="label">{t}Total{/t}</td><td>{$invoices_notaxeable_all}</td><td>{$net_notaxable_all}</td><td>{$tax_notaxable_all}</td></tr>
</table>

<h2>Money Balance</h2>
<p>{t}Itemised net sales balances{/t}:</p>

<table class="report_sales1 compact">
<tr><td></td><td>{t}Orders{/t}</td><td>{t}Products{/t}</td><td>{t}Shipping{/t}</td><td>{t}Charges{/t}</td><td>{t}Unknown{/t}</td><td>{t}Total Net{/t}</td></tr>


<tr class="first">
  <td class="label">{t}Invoices{/t}</td>
  <td>{$balance.invoices.orders}</td>
  <td>{$balance.invoices.products}</td>
  <td>{$balance.invoices.shipping}</td>
  <td>{$balance.invoices.charges}</td>
  <td>{$balance.invoices.unk}</td>
  <td>{$balance.invoices.net}</td>
</tr>
{if $balance.replacements.orders!=0}

<tr >
  <td class="label">{t}Replt.{/t}</td>
  <td>{$balance.replacements.orders}</td>
  <td>{$balance.replacements.products}</td>
  <td>{$balance.replacements.shipping}</td>
  <td>{$balance.replacements.charges}</td>
<td></td>

  <td>{$balance.replacements.net}</td>
</tr>
{/if}
{if $balance.shortage.orders!=0}

<tr >
<td class="label">{t}Shortges{/t}</td>
  <td>{$balance.shortage.orders}</td>
  <td>{$balance.shortage.products}</td>
  <td>{$balance.shortage.shipping}</td>
  <td>{$balance.shortage.charges}</td>
<td></td>
  <td>{$balance.shortage.net}</td>
</tr>
{/if}
{if $balance.followup.orders!=0}
<tr >
<td class="label">{t}Follows{/t}</td>
  <td>{$balance.followup.orders}</td>
  <td>{$balance.followup.products}</td>
  <td>{$balance.followup.shipping}</td>
  <td>{$balance.followup.charges}</td>
<td></td>
  <td>{$balance.followup.net}</td>
</tr>
{/if}
{if $balance.samples.orders!=0}
<tr >
<td class="label">{t}Samples{/t}</td>
  <td>{$balance.samples.orders}</td>
  <td>{$balance.samples.products}</td>
  <td>{$balance.samples.shipping}</td>
  <td>{$balance.samples.charges}</td>
<td></td>
  <td>{$balance.samples.net}</td>
</tr>
{/if}
{if $balance.donation.orders!=0}
<tr >

<td class="label">{t}Donations{/t}</td>
  <td>{$balance.donation.orders}</td>
  <td>{$balance.donation.products}</td>
  <td>{$balance.donation.shipping}</td>
  <td>{$balance.donation.charges}</td>
  <td>{$balance.donation.net}</td>
</tr>
{/if}
{if $balance.invoices_negative.orders!=0}
<tr>
  <td class="label"><img src="art/icons/error.png"/> {t}Neg{/t}</td>
  <td>{$balance.invoices_negative.orders}</td>
  <td>{$balance.invoices_negative.products}</td>
  <td>{$balance.invoices_negative.shipping}</td>
  <td>{$balance.invoices_negative.charges}</td>
<td>{$balance.invoices_negative.unk}</td>
  <td>{$balance.invoices_negative.net}</td>
</tr>
{/if}
{if $balance.invoices_zero.orders!=0}
<tr class="geo">
 <td class="label"><img src="art/icons/error.png"/> {t}Zero{/t}</td>
  <td>{$balance.invoices_zero.orders}</td>
  <td>{$balance.invoices_zero.products}</td>
  <td>{$balance.invoices_zero.shipping}</td>
  <td>{$balance.invoices_zero.charges}</td>
 <td>{$balance.invoices_zero.net}</td>
</tr>{/if}
<tr  style="display:none"  >
 <td class="label">{t}Subtotal{/t}</td>
  <td>{$balance.subtotal.orders}</td>
  <td>{$balance.subtotal.products}</td>
  <td>{$balance.subtotal.shipping}</td>
  <td>{$balance.subtotal.charges}</td>
<td>{$balance.subtotal.unk}</td>
  <td>{$balance.total.net}</td>
</tr>
{if $balance.refund.orders!=0}
<tr class="geo">
 <td class="label">{t}Refunds{/t}</td>
  <td>{$balance.refund.orders}</td>
  <td>{$balance.refund.products}</td>
  <td>{$balance.refund.shipping}</td>
  <td>{$balance.refund.charges}</td>
<td>{$balance.refund.unk}</td>
 <td>{$balance.refund.net}</td>
</tr>
{/if}
<tr >
 <td class="label">{t}Credits{/t}</td>
  <td>{$balance.credits.orders}</td>
  <td>{$balance.credits.products}</td>
  <td>{$balance.credits.shipping}</td>
  <td>{$balance.credits.charges}</td>
<td>{$balance.credits.unk}</td>
  <td>{$balance.credits.net}</td>
</tr>
<tr >
 <td class="label">{t}Total{/t}</td>
  <td>{$balance.total.orders}</td>
  <td>{$balance.total.products}</td>
  <td>{$balance.total.shipping}</td>
  <td>{$balance.total.charges}</td>
<td>{$balance.total.unk}</td>
  <td>{$balance.total.net_balance}</td>
</tr>


</table>
<p>{t}Itemised total balances{/t}:</p>
<table class="report_sales1 compact">
<tr><td></td><td>{t}Orders{/t}</td><td>{t}Net{/t}</td><td>{t}Tax{/t}</td><td>{t}Net (c){/t}</td><td>{t}Tax (c){/t}</td><td>{t}Total{/t}</td></tr>


<tr class="first">
  <td class="label">{t}Invoices{/t}</td>
  <td>{$balance.invoices.orders}</td>
  <td>{$balance.invoices.net}</td>
  <td>{$balance.invoices.tax}</td>
  <td>{$balance.invoices.credit_net}</td>
  <td>{$balance.invoices.credit_tax}</td>
  <td>{$balance.invoices.total}</td>
</tr>
{if $balance.replacements.orders!=0}

<tr >
  <td class="label">{t}Replt.{/t}</td>
  <td>{$balance.replacements.orders}</td>
  <td>{$balance.replacements.products}</td>
  <td>{$balance.replacements.shipping}</td>
  <td>{$balance.replacements.charges}</td>
  <td>{$balance.replacements.credit_net}</td>
  <td>{$balance.replacements.net_charged}</td>
  <td>{$balance.replacements.tax}</td>
  <td>{$balance.replacements.credit_tax}</td>
  <td>{$balance.replacements.total}</td>
</tr>
{/if}
{if $balance.shortage.orders!=0}

<tr >
<td class="label">{t}Shortges{/t}</td>
  <td>{$balance.shortage.orders}</td>
  <td>{$balance.shortage.products}</td>
  <td>{$balance.shortage.shipping}</td>
  <td>{$balance.shortage.charges}</td>
  <td>{$balance.shortage.credit_net}</td>
  <td>{$balance.shortage.net_charged}</td>
  <td>{$balance.shortage.tax}</td>
  <td>{$balance.shortage.credit_tax}</td>
  <td>{$balance.shortage.total}</td>
</tr>
{/if}
{if $balance.followup.orders!=0}
<tr >
<td class="label">{t}Follows{/t}</td>
  <td>{$balance.followup.orders}</td>
  <td>{$balance.followup.products}</td>
  <td>{$balance.followup.shipping}</td>
  <td>{$balance.followup.charges}</td>
  <td>{$balance.followup.credit_net}</td>
  <td>{$balance.followup.net_charged}</td>
  <td>{$balance.followup.tax}</td>
  <td>{$balance.followup.credit_tax}</td>
  <td>{$balance.followup.total}</td>
</tr>
{/if}
{if $balance.samples.orders!=0}
<tr >
<td class="label">{t}Samples{/t}</td>
  <td>{$balance.samples.orders}</td>
  <td>{$balance.samples.products}</td>
  <td>{$balance.samples.shipping}</td>
  <td>{$balance.samples.charges}</td>
  <td>{$balance.samples.credit_net}</td>
  <td>{$balance.samples.net_charged}</td>
  <td>{$balance.samples.tax}</td>
  <td>{$balance.samples.credit_tax}</td>
  <td>{$balance.samples.total}</td>
</tr>
{/if}
{if $balance.donation.orders!=0}
<tr >

<td class="label">{t}Donations{/t}</td>
  <td>{$balance.donation.orders}</td>
  <td>{$balance.donation.products}</td>
  <td>{$balance.donation.shipping}</td>
  <td>{$balance.donation.charges}</td>
  <td>{$balance.donation.credit_net}</td>
  <td>{$balance.donation.net_charged}</td>
  <td>{$balance.donation.tax}</td>
  <td>{$balance.donation.credit_tax}</td>
  <td>{$balance.donation.total}</td>
</tr>
{/if}
{if $balance.invoices_negative.orders!=0}
<tr>
  <td class="label"><img src="art/icons/error.png"/> {t}Neg{/t}</td>
  <td>{$balance.invoices_negative.orders}</td>
  <td>{$balance.invoices_negative.products}</td>
  <td>{$balance.invoices_negative.shipping}</td>
  <td>{$balance.invoices_negative.charges}</td>

  <td>{$balance.invoices_negative.net}</td>
  <td>{$balance.invoices_negative.tax}</td>
    <td>{$balance.invoices_negative.credit_net}</td>
  <td>{$balance.invoices_negative.credit_tax}</td>
  <td>{$balance.invoices_negative.total}</td>
</tr>
{/if}
{if $balance.invoices_zero.orders!=0}
<tr class="geo">
 <td class="label"><img src="art/icons/error.png"/> {t}Zero{/t}</td>
  <td>{$balance.invoices_zero.orders}</td>
  <td>{$balance.invoices_zero.products}</td>
  <td>{$balance.invoices_zero.shipping}</td>
  <td>{$balance.invoices_zero.charges}</td>
 <td>{$balance.invoices_zero.net}</td>
 <td>{$balance.invoices_zero.tax}</td>
 <td>{$balance.invoices_zero.credit_net}</td>
  <td>{$balance.invoices_zero.credit_tax}</td>
  <td>{$balance.invoices_zero.total}</td>
</tr>{/if}
{if $balance.refund.orders!=0}
<tr>
<td  class="label">Refunds</td>
  <td>{$balance.refund.orders}</td>
 <td></td>
 <td></td>
<td>{$balance.refund.credit_net}</td>
<td>{$balance.refund.credit_tax}</td>
<td>{$balance.refund.total}</td>
</tr>
{/if}
{if $balance.refund_error.orders!=0}
<td  class="label"><img src="art/icons/error.png"/> Refunds</td>
  <td>{$balance.refund_error.orders}</td>
  <td></td>
  <td></td>
  <td></td>
 <td></td>
 <td></td>
 <td>{$balance.refund_error.credit_net}</td>
  <td>{$balance.refund_error.credit_tax}</td>
  <td>{$balance.refund_error.total}</td>
</tr>
{/if}
<tr >
 <td class="label">{t}Total{/t}</td>
  <td>{$balance.total.orders}</td>
  <td>{$balance.total.net}</td>
  <td>{$balance.total.tax}</td>
  <td>{$balance.total.credit_net}</td>
  <td>{$balance.total.credit_tax}</td>
  <td>{$balance.total.total}</td>


<table>


<h2>Orders Received</h2>
<p style="width:375px">
We received <b>{$orders_total}</b> orders on {if $tipo=='m'}{$period}{/if},   <b>{$per_orders_done}</b> has been done. The average process time was <b>{$dispatch_days}</b> days, (<b>{$dispatch_days_home}</b> days for {$_home} orders, <b>{$dispatch_days_nohome}</b> days for export ones). 
</p>


<table   class="report_sales1">
<tr><td></td><td style="text-align:right;padding:0">Number</td><td></td><td>Net Value</td><td></tr>
{foreach from=$orders_state key=k item=v name=foo}
<tr class="{if $smarty.foreach.foo.first}first{else}geo{/if}">
  <td>{$k}</td>
  <td  style="padding:0 4px 0 0;" >{$v.orders}</td>
  <td style="text-align:left;padding:0 0 0 0;">({$v.orders_percentage})</td>
   <td  style="padding:0 4px 0 0;" >{$v.net}</td>
  <td style="text-align:left;padding:0 0 0 0;">({$v.net_percentage})</td>
  

</tr>
{/foreach}
<tr class="total" ><td>Total</td><td style="text-align:right;padding:0 4px 0 0;">{$orders_total}</td><td></td><td>{$orders_total_net}</td></tr>
</table>

<h2>Orders Dispatched</h2>


<table   class="report_sales1">
<tr><td></td><td style="text-align:right;padding:0">Number</td><td></td><td>Weight</td><td></tr>
{foreach from=$dn key=k item=v name=foo}
<tr class="{if $smarty.foreach.foo.first}first{else}geo{/if}">
  <td>{$v.type}</td>
  <td  style="padding:0 4px 0 0;" >{$v.number}</td>
  <td style="text-align:left;padding:0 0 0 0;">({$v.number_per})</td>
  <td>{$v.weight}</td>
  <td style="text-align:left;padding:0 0 0 0;">({$v.weight_per})</td>
  
</tr>
{/foreach}
<tr class="total" ><td>Total</td><td style="text-align:right;padding:0 4px 0 0;">{$dn_total}</td><td></td><td>{$dn_total_weight}</td></tr>
</table>


<h2>Comparison  Previous Year</h2>
<table border=0>
<tr>
<td><img src="{if $diff_sales_change!="+"}art/down.png{else}art/up.png{/if}"/></td>
<td><b>{t}Net Sales{/t}</b><br>{$diff_sales_per}</td>
<td style="padding-left:30px"><img src="{if $diff_invoices_change!="+"}art/down.png{else}art/up.png{/if}"/></td>
<td><b>{t}Orders Invoiced{/t}</b><br>{$diff_invoices_per}</td>
{*}
<td style="padding-left:30px"><img src="{if $diff_orders_received!="+"}art/down.png{else}art/up.png{/if}"/></td>
<td><b>{t}Orders Received{/t}</b><br>{$diff_orders_received_per}</td>
{*}
</tr>
</table>
<p style="width:500px;clear:both">
With respect last year there is <b>{$text_diff_sales}</b> on the sales {$text_diff_invoices_link} <b>{$text_diff_invoices}</b> orders invoiced.
</p>
<table class="report_sales1">
<tr><td></td><td>&Delta; {t}Orders{/t}</td><td>%&Delta; {t}Orders{/t}</td><td>&Delta; {t}Value{/t}({$currency})</td><td>%&Delta; {t}Value{/t}</td></tr>

<tr class="first"><td class="label">{$_home} {t}Invoices{/t}</td><td>{$diff_invoices_home}</td><td>{$diff_invoices_home_per}</td><td>{$diff_sales_home}</td><td>{$diff_sales_home_per}</td></tr>
<tr class="first"><td class="label">{t}Export Invoices{/t}</td><td>{$diff_invoices_nohome}</td><td>{$diff_invoices_nohome_per}</td><td>{$diff_sales_nohome}</td><td>{$diff_sales_nohome_per}</td></tr>
<tr class="first"><td class="label">{t}Partner Invoices{/t}</td><td>{$diff_invoices_partners}</td><td>{$diff_invoices_partners_per}</td><td>{$diff_sales_partners}</td><td>{$diff_sales_partners_per}</td></tr>
<tr class="total"><td class="label">{t}All Invoices{/t}</td><td>{$diff_invoices}</td><td>{$diff_invoices_per}</td><td>{$diff_sales}</td><td>{$diff_sales_per}</td></tr>
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
 <td class="country">{$countries.country}</td><td>{$countries.orders} <img onClick="show_invoices_country('{$countries.2acode}','{$countries.name}')" style="vertical-align:middle;cursor:pointer" title="{t}Display {$countries.name} orders{/t}"src="art/icons/magnify-clip.png"/></td><td>{$countries.net}</td><td>{$countries.tax}</td><td>{$countries.share}</td><td>{if $countries.eu}<img src="art/flags/eu.gif">{/if}</td>

</tr>
{/foreach}
</table>

<p style="width:350px">
{if $invoices_data.invoices_p_nohome>0}
{$invoices_data.invoices_p_nohome}

The export sales represent   {$per_export} ({$per_export_nop} without partners) of our sales.Excluding our partners, the principal export destination  is {$export_country1} with a {$per_export_country1} share in the total export sales  follow by  {$export_country2} ({$per_export_country2}) and {$export_country3} ({$per_export_country3}).
{else}
The principal export destination  is {$export_country1} with a {$per_export_country1} share in the total export sales  follow by  {$export_country2} ({$per_export_country2}) and {$export_country3} ({$per_export_country3}).

{/if}
<table class="report_sales1" style="width:350px">
<tr><td></td><td>Orders</td><td>Net Sales</td><td>Tax</td></tr>
{if $invoices_extended_home_nohome!=0}
<tr class="geo"><td class="label">{$extended_home_nohome}</td><td>{$invoices_extended_home_nohome}</td><td>{$net_extended_home_nohome}</td><td>{$tax_extended_home_nohome}</td></tr>
{/if}
<tr class="geo" ><td class="label"> {$region}<td>{$invoices_region_nohome}</td><td>{$net_region_nohome}</td><td>{$tax_region_nohome}</td></tr>
<tr class="geo"><td class="label">{$region2}</td><td>{$invoices_region2_nohome_noregion}</td><td>{$net_region2_nohome_noregion}</td><td>{$tax_region2_nohome_noregion}</td></tr>
<tr class="outside last " ><td class="label">{$outside}</td><td>{$invoices_outside}</td><td>{$net_outside}</td><td>{$tax_outside}</td></tr>
{if $invoices_p_nohome!=0}
<tr class="subtotal"  ><td>Subtotal</td><td>{$invoices_nohome}</td><td>{$net_nohome}</td><td>{$tax_nohome}</td></tr>

<tr class="partners"><td>Partners</td><td>{$invoices_p_nohome}</td><td>{$net_p_nohome}</td><td>{$tax_p_nohome}</td></tr>
{/if}
<tr class="total" ><td>Total</td><td>{$total_invoices_nohome}</td><td>{$total_net_nohome}</td><td>{$total_tax_nohome}</td></tr>

<tr class="org"><td class="label">{$org}</td><td>{$invoices_org_nohome}</td><td>{$net_org_nohome}</td><td>{$tax_org_nohome}</td></tr>
</table>
{*}
{else}
{if $todo_orders>0}
{$todo_orders} orders are waiting to be dispatched, which amounts to a total of  {$to_be_net}.
{else}
{t}No orders has been placed in this period{/t}.
{/if}
{*}
{/if}











 </div>
</div> 
	</div> 
	
	<div id="orders1">
	<div class="data_table" style="clear:both;">
    <span   class="clean_table_title" id="clean_table_title0"></span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    
   
    
    
	
	


{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable"> </div>
  </div>
	</div>
	
{include file='footer.tpl'}

