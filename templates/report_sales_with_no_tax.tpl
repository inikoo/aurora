{include file='header.tpl'}
<div id="bd" >
{include file='reports_navigation.tpl'}


{include file='calendar_splinter.tpl'}




<h1 style="clear:left">{t}Sales without tax{/t} ({$period})</h1>

<div style="clear:left;width:560px;margin-top:20px" >
  <table class="options" >
    
    <td  {if $currency_type=='original'}class="selected"{/if} id="original"  >{t}Original Currency{/t}</td>
    <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Corporate Currency{/t}</td>
    <td {if $currency_type=='hm_revenue_and_customs'}class="selected"{/if}  id="hm_revenue_and_customs"  >{t}HM Revenue & Customs{/t}</td>

  </table>
  {t}Amount Totals in{/t}:
</div>


<div class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Customers{/t}</span>
      <div id="table_type">
         <a  style="float:right"  class="table_type state_details"  href="report_sales_with_no_tax_customers_csv.php" >{t}Export (CSV){/t}</a>

     </div>

    <div style="clear:both;margin:0 0 10px 0;padding:0 20px ;border-bottom:1px solid #999"></div>
    
        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
    <div  id="table1"   class="data_table_container dtable btable with_total"></div>
  </div>


<div class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Invoices{/t}</span>
   
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:0px solid #999"></div>
    
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
  </div>





 
</div>
<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


{include file='footer.tpl'}

