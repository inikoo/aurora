{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0px 20px;">
 {include file='suppliers_navigation.tpl'}
 <div class="branch"> 
  <span  ><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span>
  </div>
 <div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
  <h1>{$supplier->get('Supplier Name')} <span style="color:SteelBlue">({$supplier->get('Supplier Code')})</span></h1>
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
      
      <div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
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
      
     
 <div id="block_purchase_orders" style="{if $block_view!='purchase_orders'}display:none;{/if}clear:both;margin:10px 0 40px 0">
   




   
  </div>
  
  
  
  
  
  
  
  <div  id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
    <div class="data_table" >
      <span class="clean_table_title">{t}Supplier Products{/t} <img id="export_csv1"   tipo="supplier_products" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
      <div id="list_options0">
             <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>

     <table  style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td class="option {if $supplier_products_view=='general'}selected{/if}" id="supplier_products_general" >{t}General{/t}</td>
	  <td class="option {if $supplier_products_view=='stock'}selected{/if}"  id="supplier_products_stock"  >{t}Parts Stock{/t}</td>
	  <td class="option {if $supplier_products_view=='sales'}selected{/if}"  id="supplier_products_sales"  >{t}Parts Sales{/t}</td>
	  	  <td class="option {if $supplier_products_view=='profit'}selected{/if}"  id="supplier_products_profit"  >{t}Profit{/t}</td>

	</tr>
      </table>
     <table id="supplier_products_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $supplier_products_view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $supplier_products_period=='all'}selected{/if}" period="all"  id="supplier_products_period_all" >{t}All{/t}</td>
	  <td class="option {if $supplier_products_period=='three_year'}selected{/if}"  period="three_year"  id="supplier_products_period_three_year"  >{t}3Y{/t}</td>
	  <td class="option {if $supplier_products_period=='year'}selected{/if}"  period="year"  id="supplier_products_period_year"  >{t}1Yr{/t}</td>
	  <td class="option {if $supplier_products_period=='six_month'}selected{/if}"  period="six_month"  id="supplier_products_period_six_month"  >{t}6M{/t}</td>
	  <td class="option {if $supplier_products_period=='quarter'}selected{/if}"  period="quarter"  id="supplier_products_period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $supplier_products_period=='month'}selected{/if}"  period="month"  id="supplier_products_period_month"  >{t}1M{/t}</td>
	  <td class="option {if $supplier_products_period=='ten_day'}selected{/if}"  period="ten_day"  id="supplier_products_period_ten_day"  >{t}10D{/t}</td>
	  <td class="option {if $supplier_products_period=='week'}selected{/if}" period="week"  id="supplier_products_period_week"  >{t}1W{/t}</td>
	
	  <td style="visibility:hidden"></td>
	  	  <td  class="option {if $supplier_products_period=='yeartoday'}selected{/if}"  period="yeartoday"  id="supplier_products_period_yeartoday"  >{t}YTD{/t}</td>	
	  	  <td  class="option {if $supplier_products_period=='monthtoday'}selected{/if}"  period="monthtoday"  id="supplier_products_period_monthtoday"  >{t}MTD{/t}</td>	
	  	  <td  class="option {if $supplier_products_period=='weektoday'}selected{/if}"  period="weektoday"  id="supplier_products_period_weektoday"  >{t}WTD{/t}</td>	

	
	</tr>
      </table>
     <table  id="supplier_products_avg_options" style="display:none;float:left;margin:0 0 0 20px ;padding:0 {if $supplier_products_view!='sales'};display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $supplier_products_avg=='totals'}selected{/if}" avg="totals"  id="supplier_products_avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $supplier_products_avg=='month'}selected{/if}"  avg="month"  id="supplier_products_avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $supplier_products_avg=='week'}selected{/if}"  avg="week"  id="supplier_products_avg_week"  >{t}W AVG{/t}</td>

	</tr>
      </table>


    
	   {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
	   <div  id="table0"   class="data_table_container dtable btable " style="font-size:90%"> </div>
	 </div>
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

