{include file='header.tpl'}
<div id="bd" > 
<span class="nav2 onleft"><a href="part_categories.php?id=0">{t}Parts Categories{/t}</a></span>
{include file='locations_navigation.tpl'}

  


<div class="data_table" style="clear:both;">
    <span class="clean_table_title">{t}Parts{/t}</span>

<span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="parts" >{t}Export (CSV){/t}</span>
 <div style="clear:both;margin:0 0px;padding:0 0px ;border-bottom:1px solid #999"></div>
 <table style="float:left;margin:0 0 0 0px ;padding:0" class="options" >
	<tr>
	  <td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>
       <table  id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view=='general' };display:none{/if}"  class="options_mini" >
	<tr>


	  <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	  <td  {if period=='three_year'}selected{/if}"  period="three_year"  id="period_three_year"  >{t}3Y{/t}</td>
	  <td  {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  {if period=='yeartoday'}selected{/if}"  period="yeartoday"  id="period_yeartoday"  >{t}YTD{/t}</td>	
	  <td  {if period=='six_month'}selected{/if}"  period="six_month"  id="period_six_month"  >{t}6M{/t}</td>
	  <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td  {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  {if period=='ten_day'}selected{/if}"  period="ten_day"  id="period_ten_day"  >{t}10D{/t}</td>
	  <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>

  <td  {if $parts_period=='yeartoday'}class="selected"{/if}"  period="yeartoday"  id="parts_period_yeartoday"  >{t}YTD{/t}</td>	
	  <td  {if $parts_period=='monthtoday'}class="selected"{/if}"  period="monthtoday"  id="parts_period_monthtoday"  >{t}MTD{/t}</td>	
	  <td  {if $parts_period=='weektoday'}class="selected"{/if}"  period="weektoday"  id="parts_period_weektoday"  >{t}WTD{/t}</td>	
	  <td  {if $parts_period=='today'}class="selected"{/if}"  period="today"  id="parts_period_today"  >{t}Today{/t}</td>	


	</tr>
	
	
	
	
      </table>
       <table id="avg_options"  style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
       </table>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}

	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
  
  
    <div  id="table0"   class="data_table_container dtable btable " style="font-size:85%"> </div>
  </div>
 


</div> 

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
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
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="parts-table-csv_export" export_options=$csv_export_options }

{include file='footer.tpl'}
