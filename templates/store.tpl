{include file='header.tpl'}
<div id="bd" >
{include file='assets_navigation.tpl'}
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Store: {$store->get('Store Name')} ({$store->get('Store Code')})</h1>
  </div>
<div id="info" style="clear:left;margin:20px 0 10px 0;padding:0;{if !$show_details}display:none;{/if}">

<h2 style="margin:0;padding:0">{t}Store Information{/t}</h2>
<div style="width:350px;float:left">
  <table    class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$store->get('Store Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$store->get('Store Name')}</td>
    </tr>
 <tr >
      <td>{t}Web Page{/t}:</td><td>{$store->get('Web Page Links')}</td>
    </tr>
</table>
  <table    class="show_info_product">

   <tr>
	    <td>{t}Departments{/t}:</td><td class="number"><div >{$store->get('Departments')}</div></td>
	  </tr>
    <tr>
	    <td>{t}Families{/t}:</td><td class="number"><div >{$store->get('Families')}</div></td>
	  </tr>
	  <tr>
	    <td>{t}Products{/t}:</td><td class="number"><div>{$store->get('For Public Sale Products')}</div></td>
	  </tr>
  </table>
</div>
<div style="width:15em;float:left;margin-left:20px">
  <table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$stores_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $stores_period!='all'}display:none{/if}">
	 <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$store->get('Total Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$store->get('Total Invoices')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$store->get('Total Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$store->get('Total Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$store->get('Total Quantity Delivered')}</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="{if $stores_period!='year'}display:none{/if}">
      	<tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$store->get('1 Year Acc Customers')}</td>
	</tr>
		<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$store->get('1 Year Acc Invoices')}</td>
	</tr>

	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$store->get('1 Year Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$store->get('1 Year Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$store->get('1 Year Acc Quantity Delivered')}</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="{if $stores_period!='quarter'}display:none{/if}"  >
         <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$store->get('1 Quarter Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$store->get('1 Quarter Acc Invoices')}</td>
	    </tr>
      
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$store->get('1 Quarter Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$store->get('1 Quarter Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$store->get('1 Quarter Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="{if $stores_period!='month'}display:none{/if}"  >
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$store->get('1 Month Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$store->get('1 Month Acc Invoices')}</td>
	    </tr>
       
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$store->get('1 Month Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$store->get('1 Month Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$store->get('1 Month Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="{if $stores_period!='week'}display:none{/if}"  >
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$store->get('1 Week Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$store->get('1 Week Acc Invoices')}</td>
	    </tr>
       
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$store->get('1 Week Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$store->get('1 Week Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$store->get('1 Week Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
 </table>
</div>

</div>
<div id="plot" class="top_bar" style="clear:both;padding:0;margin:0;{if !$show_details}display:none;{/if}">
{include file='plot_splinter.tpl'}
</div>     
     
<div class="data_table" style="clear:both;">
    <span   class="clean_table_title">{t}Departments{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    <span   style="float:right;margin-left:80px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
    <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
      <tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}Summary{/t}</td>
	{if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t} {$view}</td>{/if}
	{if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
      </tr>
    </table>
    <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	  <tr>
	    
	    <td  {if $period=='all'}class="selected"{/if} period="all"  id="period_all" >{t}All{/t}</td>
	   <td {if $period=='year'}class="selected"{/if}  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	    <td  {if $period=='quarter'}class="selected"{/if}  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	    <td {if $period=='month'}class="selected"{/if}  period="month"  id="period_month"  >{t}1M{/t}</td>
	    <td  {if $period=='week'}class="selected"{/if} period="week"  id="period_week"  >{t}1W{/t}</td>
	  </tr>
      </table>
	<table  id="avg_options" style="float:left;margin:0 0 0 25px ;padding:0 {if $view!='sales'};display:none{/if}"  class="options_mini" >
	  <tr>
	    <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	    <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	    <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	    
	  </tr>
       </table>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
<div  id="table0"   class="data_table_container dtable btable with_total"> </div>
</div>

 

</div>
<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="info_period_menu" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
      {foreach from=$info_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_info_period('{$menu.period}','{$menu.title}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="stores-table-csv_export" export_options=$csv_export_options }
{include file='footer.tpl'}
