{include file='header.tpl'}
<div id="bd" style="padding:0px">

<div style="padding:0 20px">

{include file='assets_navigation.tpl'}

<div style="clear:left;">
  <h1>{t}Suppliers{/t}</h1>
</div>
</div>


<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='suppliers'}selected{/if}"  id="suppliers">  <span> {t}Suppliers{/t}</span></span></li>
       <li> <span class="item {if $block_view=='sproducts'}selected{/if}"  id="sproducts">  <span> {t}Supplier Products{/t}</span></span></li>

   <li> <span class="item {if $block_view=='porders'}selected{/if}"  id="porders">  <span> {t}Purchase Orders{/t}</span></span></li>
        <li> <span class="item {if $block_view=='sinvoices'}selected{/if}"  id="sinvoices">  <span> {t}Supplier Invoices{/t}</span></span></li>
    <li> <span class="item {if $block_view=='idn'}selected{/if}"  id="idn">  <span> {t}Incoming Delivery Notes{/t}</span></span></li>


  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
</div>



<div style="padding:0 20px">

<div id="block_suppliers" style="{if $block_view!='suppliers'}display:none;{/if}clear:both;margin:10px 0 40px 0">
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}Suppliers List{/t}  <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr><td  {if $suppliers_view=='general'}class="selected"{/if} id="suppliers_general" >{t}General{/t}</td>
	  <td {if $suppliers_view=='products'}class="selected"{/if}  id="suppliers_products"  >{t}Products{/t}</td>
	  {if $view_stock}<td {if $suppliers_view=='stock'}class="selected"{/if}  id="suppliers_stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  {if $suppliers_view=='sales'}class="selected"{/if}  id="suppliers_sales"  >{t}Sales{/t}</td>{/if}
	</tr>
      </table>
  
 
     {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
</div>
<div id="block_porders" style="{if $block_view!='porders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
</div>
<div id="block_sinvoices" style="{if $block_view!='sinvoices'}display:none;{/if}clear:both;margin:10px 0 40px 0">
</div>
<div id="block_idn" style="{if $block_view!='idn'}display:none;{/if}clear:both;margin:10px 0 40px 0">
</div>
<div id="block_sproducts" style="{if $block_view!='sproducts'}display:none;{/if}clear:both;margin:10px 0 40px 0">
<div class="data_table" style="clear:both;">
    <span class="clean_table_title">{t}Supplier Products{/t} <img id="export_csv1"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
     <table  style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td class="option {if $supplier_products_view=='general'}selected{/if}" id="supplier_products_general" >{t}General{/t}</td>
	  <td class="option {if $supplier_products_view=='stock'}selected{/if}"  id="supplier_products_stock"  >{t}Stock{/t}</td>
	  <td class="option {if $supplier_products_view=='sales'}selected{/if}"  id="supplier_products_sales"  >{t}Sales{/t}</td>
	</tr>
      </table>
        <table id="supplier_products_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $supplier_products_view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>

	  <td class="option {if $supplier_products_period=='all'}selected{/if}" period="all"  id="supplier_products_period_all" >{t}All{/t}</td>
	  <td class="option {if $supplier_products_period=='year'}selected{/if}"  period="year"  id="supplier_products_period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $supplier_products_period=='quarter'}selected{/if}"  period="quarter"  id="supplier_products_period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $supplier_products_period=='month'}selected{/if}"  period="month"  id="supplier_products_period_month"  >{t}1M{/t}</td>
	  <td class="option {if $supplier_products_period=='week'}selected{/if}" period="week"  id="supplier_products_period_week"  >{t}1W{/t}</td>
	</tr>
      </table>


       <table  id="supplier_products_avg_options" style="float:left;margin:0 0 0 20px ;padding:0 {if $supplier_products_view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $supplier_products_avg=='totals'}selected{/if}" avg="totals"  id="supplier_products_avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $supplier_products_avg=='month'}selected{/if}"  avg="month"  id="supplier_products_avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $supplier_products_avg=='week'}selected{/if}"  avg="week"  id="supplier_products_avg_week"  >{t}W AVG{/t}</td>

	</tr>
      </table>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

      
  
    <div  id="table1"   class="data_table_container dtable btable"> </div>
  </div>
</div>

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
