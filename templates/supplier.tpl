{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0px 20px;">
 {include file='suppliers_navigation.tpl'}
 <div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
  <h1>{t}Supplier{/t}: {$supplier->get('Supplier Name')} <span style="color:SteelBlue">({$supplier->get('Supplier Code')})</span></h1>
   <table style="width:500px" >
	
	<tr>
	  <td valign="top">{$company->get('Company Main XHTML Address')}</td>
	  <td  valign="top">
	    <table border=0 style="padding:0">
	      <tr><td colspan=2>{$company->get('Company Main Contact Name')}</td ></tr>
	      <tr><td colspan=2>{$company->get('Company Main XHTML Email')}</td ></tr>
	      <tr><td colspan=2>{$company->get('Company Main XHTML Telephone')}</td ></tr>
	      <tr><td colspan=2>{$company->get('Company Main XHTML FAX')}</td ></tr>

	      
	      
	    
	     </table>
	   </td>
	 </tr>
	 
       </table>

  
  </div>
  
 </div>
 
   <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $block_view=='products'}selected{/if}"  id="products">  <span> {t}Supplier Products{/t}</span></span></li>
    <li> <span class="item {if $block_view=='purchase_orders'}selected{/if}"  id="purchase_orders">  <span> {t}Purchase Orders{/t}</span></span></li>

	
 </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
      
      
      <div style="padding:0px 20px;">
      
      <div id="block_products" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
    <h2 style="font-size:150%;">{t}Supplier Details{/t}</h2>
	
	<div style="clear:both">
	<div style="width:300px;float:left">
  <table    class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$supplier->get('Supplier Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$supplier->get('Supplier Name')}</td>
    </tr>
  <tr >
      <td>{t}Location{/t}:</td><td>{$supplier->get('Supplier Main Location')}</td>
    </tr>
    <tr >
      <td>{t}Email{/t}:</td><td>{$supplier->get('Supplier Main XHTML Email')}</td>
    </tr> 
    
</table>
 
</div>
	<div style="width:300px;margin-left:10px;float:left">
	 	

		<table    class="show_info_product"  >
		  <tr>
		    <td>{t}Total Sales{/t}:</td><td class="aright">{$supplier->get('Total Parts Sold Amount')} </td>
		  </tr>
		  <tr>
		    <td>{t}Total Profit{/t}:</td><td class="aright">{$supplier->get('Total Parts Profit')} </td>
		  </tr>
		  <tr>
		    <td>{t}Stock Value{/t}:</td><td class="aright">{$supplier->get('Stock Value')} </td>
		  </tr>
		  
		</table>
		
</div>

	<div style="width:280px;margin-left:10px;float:left">
	 	<table    class="show_info_product"     >
		  <tr>
		    <td>{t}Items available{/t}:</td><td class="aright">{$supplier->get('Supplier Active Supplier Products')} </td>
		  </tr>
		  <tr>
		    <td>{t}Items no longer available{/t}:</td><td class="aright">{$supplier->get('Supplier Discontinued Supplier Products')} </td>

		  </tr>
		</table>

	
		
</div>


<div style="{if !$show_details}display:none;{/if};clear:both"  id="plot"></div>
</div>
   </div>
      
     
 <div id="block_products" style="{if $block_view!='purchase_orders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
   




   
  </div>
  
  
  
  
  
  
  
  <div  id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
    <div class="data_table" >
      <span class="clean_table_title">{t}Supplier Products{/t}</span>
      <div id="list_options0">
        <span  style="float:right;margin-left:20px" class="table_type state_details"><a style="text-decoration:none" href="import_csv.php?subject=supplier_products&subject_key={$supplier_id}">{t}Import (CSV){/t}</a></span>
       <span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="supplier" >{t}Export (CSV){/t}</span>
        <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

	  <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
	    <tr>
	      <td {if $products_view=='product_general'}class="selected"{/if} id="product_general" >{t}Summary{/t}</td>
	      <td {if $products_view=='product_stock'}class="selected"{/if}  id="product_stock"  >{t}Stock{/t}</td>
	      <td {if $products_view=='product_sales'}class="selected"{/if}  id="product_sales"  >{t}Sales{/t}</td>
	       <td {if $products_view=='product_forecast'}class="selected"{/if}  id="product_forecast"  >{t}Forecast{/t}</td>
	    </tr>
	  </table>
	   <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options_mini" >
	     <tr>
	       <td {if $products_period=='all'}class="selected"{/if} id="product_period_all" >{t}All{/t}</td>
	       <td {if $products_period=='year'}class="selected"{/if}  id="product_period_year"  >{t}1Yr{/t}</td>
	       <td {if $products_period=='quarter'}class="selected"{/if}  id="product_period_quarter"  >{t}1Qtr{/t}</td>
	       <td {if $products_period=='month'}class="selected"{/if}  id="product_period_month"  >{t}1M{/t}</td>
	       <td {if $products_period=='week'}class="selected"{/if}  id="product_period_week"  >{t}1W{/t}</td>
	     </tr>
	   </table>

      </div>

    
	   {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
	   <div  id="table0"   class="data_table_container dtable btable "> </div>
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
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu2" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
 
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="supplier-table-csv_export" export_options=$csv_export_options }

{include file='footer.tpl'}

