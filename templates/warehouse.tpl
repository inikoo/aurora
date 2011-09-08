{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">
{include file='locations_navigation.tpl'}

 <div style="clear:left;margin:0 0px">
    <h1>{t}Warehouse{/t}: {$warehouse->get('Warehouse Name')} ({$warehouse->get('Warehouse Code')})</h1>
  </div>

</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='parts'}selected{/if}"  style="display:none" id="parts">  <span> {t}Parts{/t}</span></span></li>

    <li> <span class="item {if $view=='locations'}selected{/if}"  id="locations">  <span> {t}Locations{/t}</span></span></li>
    <li> <span class="item {if $view=='areas'}selected{/if}"  id="areas">  <span> {t}Areas{/t}</span></span></li>
    <li> <span class="item {if $view=='shelfs'}selected{/if}"  id="shelfs">  <span> {t}Shelfs{/t}</span></span></li>
    <li> <span class="item {if $view=='map'}selected{/if}" id="map"  ><span>  {t}Map{/t}</span></span></li>
     <li> <span class="item {if $view=='movements'}selected{/if}"  id="movements">  <span> {t}Movements{/t}</span></span></li>
 <li> <span class="item {if $view=='stats'}selected{/if}"  id="stats">  <span> {t}Stats{/t}</span></span></li>
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
<div id="block_parts" style="{if $view!='parts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<div class="data_table" style="clear:both;">
    <span class="clean_table_title">{t}Parts{/t}</span>

<span  id="export_csv2" style="float:right;margin-left:20px"  class="table_type state_details" tipo="parts" >{t}Export (CSV){/t}</span>
 <div style="clear:both;margin:0 0px;padding:0 0px ;border-bottom:1px solid #999"></div>
 <table style="float:left;margin:0 0 0 0px ;padding:0" class="options" >
	<tr>
	  <td  {if $parts_view=='general'}class="selected"{/if} id="parts_general"  name="general" >{t}Description{/t}</td>
	  <td {if $parts_view=='stock'}class="selected"{/if}  id="parts_stock" name="stock"  >{t}Stock{/t}</td>
	  <td  {if $parts_view=='sales'}class="selected"{/if}  id="parts_sales"  name="sales" >{t}Sales{/t}</td>
	  	  <td  {if $parts_view=='forecast'}class="selected"{/if}  id="parts_forecast"name="forecast"   >{t}Forecast{/t}</td>

	</tr>
      </table>
       <table  id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $parts_view=='general' };display:none{/if}"  class="options_mini" >
	<tr>


	  <td  {if $parts_period=='all'}class="selected"{/if} period="all"  id="parts_period_all" >{t}All{/t}</td>
	  <td  {if $parts_period=='three_year'}class="selected"{/if}"  period="three_year"  id="parts_period_three_year"  >{t}3Y{/t}</td>
	  <td  {if $parts_period=='year'}class="selected"{/if}  period="year"  id="parts_period_year"  >{t}1Yr{/t}</td>
	  <td  {if $parts_period=='yeartoday'}class="selected"{/if}"  period="yeartoday"  id="parts_period_yeartoday"  >{t}YTD{/t}</td>	
	  <td  {if $parts_period=='six_month'}class="selected"{/if}"  period="six_month"  id="parts_period_six_month"  >{t}6M{/t}</td>
	  <td  {if $parts_period=='quarter'}class="selected"{/if}  period="quarter"  id="parts_period_quarter"  >{t}1Qtr{/t}</td>
	  <td  {if $parts_period=='month'}class="selected"{/if}  period="month"  id="parts_period_month"  >{t}1M{/t}</td>
	  <td  {if $parts_period=='ten_day'}selected{/if}"  period="ten_day"  id="parts_period_ten_day"  >{t}10D{/t}</td>
	  <td  {if $parts_period=='week'}class="selected"{/if} period="week"  id="parts_period_week"  >{t}1W{/t}</td>



	</tr>
      </table>
       <table id="avg_options"  style="float:left;margin:0 0 0 20px ;padding:0{if $parts_view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $parts_avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $parts_avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $parts_avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $parts_avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $parts_avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
       </table>
       
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
  <div  id="table2"   class="data_table_container dtable btable "> </div>
       </div>
</div>

<div id="block_locations" style="{if $view!='locations'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
 <div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
    <span class="clean_table_title">{t}Locations{/t}</span>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
</div>

<div id="block_areas" style="{if $view!='areas'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
 <div id="the_table1" class="data_table" style="margin:0px 0px;clear:both">
    <span class="clean_table_title">{t}Warehouse Areas{/t}</span>
{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>
</div>
      
<div id="block_map" style="{if $view!='map'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
  <div   style="border:1px solid #ccc;text-align:left;margin:0px;padding:20px;height:270px;width:600px;margin: 0 0 10px 0;float:left">
    <img   src="_warehouse.png" name="printable_map" />
    </div>
  </div>
</div>

<div id="block_shelfs" style="{if $view!='shelfs'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_movements" style="{if $view!='movements'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
</div>
<div id="block_stats" style="{if $view!='stats'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
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

<div id="rppmenu1" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>







{include file='footer.tpl'}
