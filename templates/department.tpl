{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">
 {include file='assets_navigation.tpl'}
 <div style="clear:left;"> 
 <span class="branch" ><a  href="store.php?id={$store->id}">{$store->get('Store Name')}</a>&rarr; {$department->get('Product Department Name')}</span>
 </div>

    <h1>Department: {$department->get('Product Department Name')} ({$department->get('Product Department Code')})</h1>


</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $block_view=='categories'}selected{/if}"  style="display:none" id="categories">  <span> {t}Categories{/t}</span></span></li>
    <li> <span class="item {if $block_view=='families'}selected{/if}"  id="families">  <span> {t}Families{/t}</span></span></li>
    <li> <span class="item {if $block_view=='products'}selected{/if}" id="products"  ><span>  {t}Products{/t}</span></span></li>
    <li> <span class="item {if $block_view=='deals'}selected{/if}"  id="deals">  <span> {t}Offers{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">


<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">



<h2 style="margin:0;padding:0">{t}Department Information{/t}:</h2>
<div style="width:350px;float:left">
  <table  class="show_info_product">

    <tr >
      <td>{t}Code{/t}:</td><td class="price">{$department->get('Product Department Code')}</td>
    </tr>
    <tr >
      <td>{t}Name{/t}:</td><td>{$department->get('Product Department Name')}</td>
    </tr>
   </table>
    <table    class="show_info_product">
    <tr>
	    <td>{t}Families{/t}:</td><td class="number"><div>{$department->get('Families')}</div></td>
	  </tr>
	  <tr>
	    <td>{t}Products{/t}:</td><td class="number"><div>{$department->get('For Sale Products')}</div></td>
	  </tr>
 
     <tr >
      <td>{t}Web Page{/t}:</td><td>{$department->get('Web Page Links')}</td>
    </tr>

  </table>
</div>
<div style="width:15em;float:left;margin-left:20px">

<table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$store_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $store_period!='all'}display:none{/if}">
	 <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('Total Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$department->get('Total Invoices')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('Total Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('Total Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('Total Quantity Delivered')}</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="{if $store_period!='year'}display:none{/if}">
      	<tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Year Acc Customers')}</td>
	</tr>
		<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$department->get('1 Year Acc Invoices')}</td>
	</tr>

	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Year Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Year Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Year Acc Quantity Delivered')}</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="{if $store_period!='quarter'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$department->get('1 Quarter Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Quarter Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Quarter Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Quarter Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Quarter Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="{if $store_period!='month'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$department->get('1 Month Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Month Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Month Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Month Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Month Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="{if $store_period!='week'}display:none{/if}"  >
        <tr >
	     <td>{t}Orders{/t}:</td><td class="aright">{$department->get('1 Week Acc Invoices')}</td>
	    </tr>
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$department->get('1 Week Acc Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$department->get('1 Week Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$department->get('1 Week Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$department->get('1 Week Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
 </table>
</div>



<div  id="plots" style="clear:both">
<ul class="tabs" id="chooser_ul" style="margin-top:25px">
    <li>
	  <span class="item {if $plot_tipo=='store'}selected{/if}" onClick="change_plot(this)" id="plot_store" tipo="store"    >
	    <span>{t}Department Sales{/t}</span>
	  </span>
	</li>
{*
	<li>
	  <span class="item {if $plot_tipo=='top_departments'}selected{/if}"  id="plot_top_departments" onClick="change_plot(this)" tipo="top_departments"  >
	    <span>{t}Top Families{/t}</span>
	  </span>
	</li>
*}
	<li>
	  <span class="item {if $plot_tipo=='pie'}selected{/if}" onClick="change_plot(this)" id="plot_pie" tipo="pie"     forecast="{$plot_data.pie.forecast}" interval="{$plot_data.pie.interval}"  >
	    <span>{t}Families{/t}</span>
	  </span>
	</li>

  </ul>
  
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script>

<div id="plot" style="clear:both;border:1px solid #ccc" >
	<div id="single_data_set"  >
		<strong>You need to upgrade your Flash Player</strong>
	</div>
</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=department_sales&department_key={$department->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot");
		// ]]>
	</script>
  
  
  <div style="clear:both"></div>
</div>

   
</div>
<div id="block_families" style="{if $block_view!='families'}display:none;{/if}clear:both;margin:10px 0 40px 0">

<div class="data_table" style="clear:both;">
  <span id="table_title" class="clean_table_title">{t}Families{/t} 
   <img id="export_csv0"   tipo="families_in_department" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif">

  
  </span>
   
     
   
 
  <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >

            
                        <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_family_products_discontinued"  id="elements_discontinued" table_type="discontinued"   >{t}Discontinued{/t} (<span id="elements_discontinued_number">{$elements_number.Discontinued}</span>)</span>
                        <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Discontinuing}selected{/if} label_family_products_discontinued"  id="elements_discontinuing" table_type="discontinuing"   >{t}Discontinuing{/t} (<span id="elements_discontinuing_number">{$elements_number.Discontinuing}</span>)</span>

                <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Normal}selected{/if} label_family_products_normal"  id="elements_normal" table_type="normal"   >{t}For Sale{/t} (<span id="elements_notes_number">{$elements_number.Normal}</span>)</span>
                <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.InProcess}selected{/if} label_family_products_inprocess"  id="elements_inprocess" table_type="inprocess"   >{t}In Process{/t} (<span id="elements_notes_number">{$elements_number.InProcess}</span>)</span>

        </div>
     </div>
 
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   <div id="table_type">
     <span   style="float:right;margin-left:40px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>

     <span id="table_type_list" style="float:right" class=" state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class=" state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     </div>
     
      <div id="list_options0"> 

  
 


  <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
    <tr>
        <td  class="option {if $family_view=='general'}selected{/if}" id="family_general" >{t}General{/t}</td>
        <td class="option {if $family_view=='stock'}selected{/if}"  id="family_stock"  {if !$view_stock}style="display:none"{/if} >{t}Stock{/t}</td>
      <td  class="option {if $family_view=='sales'}selected{/if}"  id="family_sales" {if !$view_sales}style="display:none"{/if} >{t}Sales{/t}</td>
    </tr>
  </table>
  
  <table id="family_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $family_view!='sales' };display:none{/if}"  class="options_mini" >
    <tr>
      
      <td class="option {if $family_period=='all'}selected{/if}" period="all"  id="family_period_all" >{t}All{/t} </td>
      <td class="option {if $family_period=='year'}selected{/if}"  period="year"  id="family_period_year"  >{t}1Yr{/t}</td>
      <td class="option  {if $family_period=='quarter'}selected{/if}"  period="quarter"  id="family_period_quarter"  >{t}1Qtr{/t}</td>
      <td class="option {if $family_period=='month'}selected{/if}"  period="month"  id="family_period_month"  >{t}1M{/t}</td>
      <td class="option  {if $family_period=='week'}selected{/if}" period="week"  id="family_period_week"  >{t}1W{/t}</td>
    </tr>
  </table>
	
  <table  id="family_avg_options" style="float:left;margin:0 0 0 25px ;padding:0 {if $family_view!='sales'};display:none{/if}"  class="options_mini" >
    <tr>
      <td class="option {if $family_avg=='totals'}selected{/if}" avg="totals"  id="family_avg_totals" >{t}Totals{/t}</td>
      <td class="option {if $family_avg=='month'}selected{/if}"  avg="month"  id="family_avg_month"  >{t}M AVG{/t}</td>
      <td class="option {if $family_avg=='week'}selected{/if}"  avg="week"  id="family_avg_week"  >{t}W AVG{/t}</td>
      
    </tr>
  </table>
  </div>

 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
      
  <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='thumbnails'}display:none{/if}"></div>
 <div  id="table0"  style="{if $table_type=='thumbnails'}display:none{/if}"  class="data_table_container dtable btable with_total"> </div>

</div>
</div>
<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
  <div class="data_table" style="margin:0px;clear:both">
    <span class="clean_table_title">{t}Products{/t}</span>
<span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="products" >{t}Export (CSV){/t}</span>
<a style="float:right;margin-left:20px"  class="table_type state_details"  href="export_xml.php" >{t}Export (XML){/t}</a>

     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
    
    <span   style="float:right;margin-left:80px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>
    
   <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
       <tr><td  class="option {if $product_view=='general'}selected{/if}" id="product_general" >{t}General{/t}</td>
	 <td class="option {if $product_view=='stock'}selected{/if}"  {if !$view_stock}style="display:none"{/if} id="product_stock"  >{t}Stock{/t}</td>
	  <td  class="option {if $product_view=='sales'}selected{/if}" {if !$view_sales}style="display:none"{/if} id="product_sales"  >{t}Sales{/t}</td>
	  <td  class="option {if $product_view=='parts'}selected{/if}"  id="product_parts"  >{t}Parts{/t}</td>
	  <td  class="option {if $product_view=='cats'}selected{/if}" style="display:none"  id="product_cats"  >{t}Groups{/t}</td>

	</tr>
      </table>
	
    <table id="product_period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $product_view!='sales' };display:none{/if}"  class="options_mini" >
	  <tr>
	    
	    <td class="option {if $product_period=='all'}selected{/if}" period="all"  id="product_period_all" >{t}All{/t}</td>
	    <td class="option {if $product_period=='year'}selected{/if}"  period="year"  id="product_period_year"  >{t}1Yr{/t}</td>
	    <td class="option {if $product_period=='quarter'}selected{/if}"  period="quarter"  id="product_period_quarter"  >{t}1Qtr{/t}</td>
	    <td class="option {if $product_period=='month'}selected{/if}"  period="month"  id="product_period_month"  >{t}1M{/t}</td>
	    <td class="option {if $product_period=='week'}selected{/if}" period="week"  id="product_period_week"  >{t}1W{/t}</td>
	  </tr>
      </table>

       <table  id="product_avg_options" style="float:left;margin:0 0 0 20px ;padding:0{if $product_view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $product_avg=='totals'}selected{/if}" avg="totals"  id="product_avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $product_avg=='month'}selected{/if}"  avg="month"  id="product_avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $product_avg=='week'}selected{/if}"  avg="week"  id="product_avg_week"  >{t}W AVG{/t}</td>
	  <td class="option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff"  id="product_avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td class="option {if $product_avg=='week_eff'}selected{/if}" style="display:none"  avg="week_eff"  id="product_avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>



        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name2 filter_value=$filter_value1  }

    <div  id="table1"   class="data_table_container dtable btable with_total "> </div>
  </div>
</div>
<div id="block_categories" style="{if $block_view!='categories'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>

</div>
</div> 

<div id="plot_period_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Plot frequency{/t}:</li>
      {foreach from=$plot_period_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_period('{$menu.period}')"> {$menu.label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="plot_category_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Plot Type{/t}:</li>
      {foreach from=$plot_category_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_plot_category('{$menu.category}')"> {$menu.label}</a></li>
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
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
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
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="family-table-csv_export" export_options=$csv_export_options }
{include file='footer.tpl'}

