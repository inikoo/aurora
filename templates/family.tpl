{include file='header.tpl'}
<div id="bd" >
  {if $modify}<span class="nav2 onright"><a href="family.php?edit=1"  >{t}Edit{/t}</a></span>{/if}

{if $next.id>0}<span class="nav2 onright"><a href="family.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
<span class="nav2 onright" ><a href="department.php?id={$department->id}" title="{$department->get('Product Department Name')}">&uarr; {t}Up{/t}</a></span>
{if $prev.id>0}<span class="nav2 onright" ><a href="family.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}

<span class="nav2 onleft"><a href="store.php">{$store->get('Store Code')}</a></span>
<span class="nav2 onleft"><a href="families.php?store_key={$store->id}">{$store->get('Store Code')} {t}Families{/t}</a></span>
<span class="nav2 onleft"><a href="products.php?store_key={$store->id}">{$store->get('Store Code')} {t}Products{/t}</a></span>
<span class="nav2 onleft"><a href="categories.php?store_key={$store->id}">{$store->get('Store Code')} {t}Categories{/t}</a></span>



  <div class="search_box" >

   <span class="search_title">{t}Product Code{/t}:</span> <input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
     <span  class="search_msg"   id="product_search_msg"    ></span> <span  class="product_search_sugestion"   id="search_sugestion"    ></span>
     <br/>
    <span  class="state_details" state="{$show_details}"  id="show_details"  atitle="{if $show_details}{t}show details{/t}{else}{t}hide details{/t}{/if}"  >{if $show_details}{t}hide details{/t}{else}{t}show details{/t}{/if}</span>
  
  </div>
  

 <div style="clear:left;">
   <span class="branch" ><a  href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; <a  href="department.php?id={$department->id}">{$department->get('Product Department Code')}</a> &rarr; {$family->get('Product Family Name')}  ({$family->get('Product Family Code')})</span>
  
</div>
  
<div id="family_info" style="margin:10px 0;padding:0">

<h2 style="margin:0;padding:0">Family Information</h2>
<div style="width:350px;float:left">
  <table    class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$family->get('Product Family Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$family->get('Product Family Name')}</td>
    </tr>
    <tr >
      <td>{t}Description{/t}:</td><td>{$family->get('Product Family Special Characteristic')}</td>
    </tr>
   
    <tr >
      <td>{t}Similar{/t}:</td><td>{$family->get('Similar Families')}</td>
    </tr>
    <tr >
      <td>{t}Categories{/t}:</td><td>{$family->get('Categories')}</td>
    </tr>
     <tr >
      <td>{t}Web Page{/t}:</td><td>{$family->get('Web Page Links')}</td>
    </tr>

  </table>
</div>
<div style="width:300px;float:left;margin-left:20px">
  <table    class="show_info_product">
      <tr >
<td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$department_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td> 

   </tr>

       <tbody id="info_all" style="{if $department_period!='all'}display:none{/if}">
	 <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$family->get('Total Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$family->get('Total Invoices')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$family->get('Total Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$family->get('Total Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$family->get('Total Quantity Delivered')}</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="{if $department_period!='year'}display:none{/if}">
      	<tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$family->get('1 Year Acc Customers')}</td>
	</tr>
		<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$family->get('1 Year Acc Invoices')}</td>
	</tr>

	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$family->get('1 Year Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$family->get('1 Year Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$family->get('1 Year Acc Quantity Delivered')}</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="{if $department_period!='quarter'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$family->get('1 Quarter Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$family->get('1 Quarter Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$family->get('1 Quarter Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$family->get('1 Quarter Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$family->get('1 Quarter Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="{if $department_period!='month'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$family->get('1 Month Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$family->get('1 Month Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$family->get('1 Month Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$family->get('1 Month Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$family->get('1 Month Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="{if $department_period!='week'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$family->get('1 Week Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$family->get('1 Week Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$family->get('1 Week Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$family->get('1 Week Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$family->get('1 Week Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>

 </table>
</div>

</div>

   <div class="data_table"  style="clear:both">
     <span id="table_title" class="clean_table_title">{t}Products{/t}</span>
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

     
     <span   style="float:right;margin-left:80px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
     
     <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
       <tr><td  {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  {if $view_stock}<td {if $view=='stock'}class="selected"{/if}  id="stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $view=='sales'}class="selected"{/if}  id="sales"  >{t}Sales{/t}</td>{/if}
	  <td  {if $view=='parts'}class="selected"{/if}  id="parts"  >{t}Parts{/t}</td>
	  <td  {if $view=='cats'}class="selected"{/if}  id="cats"  >{t}Groups{/t}</td>

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
       <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td {if $avg=='totals'}class="selected"{/if} avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td {if $avg=='month'}class="selected"{/if}  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td {if $avg=='week'}class="selected"{/if}  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td {if $avg=='month_eff'}class="selected"{/if} style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td {if $avg=='week_eff'}class="selected"{/if} style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
  </div>


</div> 


<div id="rppmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="info_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
      {foreach from=$info_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_info_period('{$menu.period}','{$menu.title}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}
