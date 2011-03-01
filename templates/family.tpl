{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
 {include file='assets_navigation.tpl'}
 
 <div class="branch"> 
 <span ><a  href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a  href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Name')}  ({$family->get('Product Family Code')})</span>
 </div>

  <div id="no_details_title" style="clear:right;">
    <h1>{t}Family{/t}: {$family->get('Product Family Name')} ({$family->get('Product Family Code')})</h1>
  </div>

</div>
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
    <li> <span class="item {if $block_view=='categories'}selected{/if}"  id="categories">  <span> {t}Categories{/t}</span></span></li>
    <li> <span class="item {if $block_view=='products'}selected{/if}" id="products"  ><span>  {t}Products{/t}</span></span></li>
    <li> <span class="item {if $block_view=='deals'}selected{/if}"  id="deals">  <span> {t}Offers{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">
  
<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">



 <div id="photo_container" style="margin-top:0px;float:left">
	    <div style="border:1px solid #ddd;padding-stop:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
	     
	      <div id="imagediv"   style="border:1px solid #ddd;width:{$div_img_width}px;height:{$div_img_height}px;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
		<img src="{ if $num_images>0}{$images[0].small_url}{else}art/nopic.png{/if}"  style="vertical-align:middle;display:block;" width="{$img_width}px" valign="center" border=1  id="image"   alt="{t}Image{/t}"/>
	      </div>
	    </div>
	    
	    { if $num_images>1}
	    <div style="width:160px;margin:auto;padding-top:5px"  >
	      {foreach from=$images item=image  name=foo}
	      {if $image.is_principal==0}
	      <img  style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}"  title="" alt="" />
	      {/if}
	      {/foreach}
	    </div>
	    {/if}
	    
	    
	  </div>
<h2 style="margin:20px 0 0 0 ;padding:0">Family Information</h2>
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

<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">

<div class="data_table"  style="margin-top:10px;clear:both">
     <span id="table_title" class="clean_table_title">{t}Products{/t}</span>
	
<span  id="export_csv0" style="float:right;margin-left:20px"  class="table_type state_details" tipo="products_in_family" >{t}Export (CSV){/t}</span>
<a style="float:right;margin-left:20px"  class="table_type state_details"  href="export_products_xml.php" >{t}Export (XML){/t}</a>
     <div id="table_type">
     <span id="table_type_list" style="float:right" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     </div>
     
     

     
    <div id="list_options0"> 
      <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
      <span   style="float:right;margin-left:20px" class="state_details" state="{$show_percentages}"  id="show_percentages"  atitle="{if $show_percentages}{t}Normal Mode{/t}{else}{t}Comparison Mode{/t}{/if}"  >{if $show_percentages}{t}Comparison Mode{/t}{else}{t}Normal Mode{/t}{/if}</span>     
      <span   style="float:right;margin-left:80px" class="state_details" state="{$show_only}"  id="show_only"    >{$show_only_label}</span>   



    <table style="float:left;margin:0 0 5px 0px ;padding:0"  class="options" >
       <tr><td  class="option {if $product_view=='general'}selected{/if}" id="product_general" >{t}General{/t}</td>
	  {if $view_stock}<td class="option {if $product_view=='stock'}selected{/if}"  id="product_stock"  >{t}Stock{/t}</td>{/if}
	  {if $view_sales}<td  class="option {if $product_view=='sales'}selected{/if}"  id="product_sales"  >{t}Sales{/t}</td>{/if}
	  <td  class="option {if $product_view=='parts'}selected{/if}"  id="product_parts"  >{t}Parts{/t}</td>
	  <td  class="option {if $product_view=='cats'}selected{/if}"  id="product_cats"  >{t}Groups{/t}</td>

	</tr>
      </table>
    <table id="period_options" style="float:left;margin:0 0 0 20px ;padding:0{if $view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td  class="option {if $product_period=='all'}selected{/if}" period="all"  id="period_all" >{t}All{/t}</td>
	  <td class="option {if $product_period=='year'}selected{/if}"  period="year"  id="period_year"  >{t}1Yr{/t}</td>
	  <td  class="option {if $product_period=='quarter'}selected{/if}"  period="quarter"  id="period_quarter"  >{t}1Qtr{/t}</td>
	  <td class="option {if $product_period=='month'}selected{/if}"  period="month"  id="period_month"  >{t}1M{/t}</td>
	  <td  class="option {if $product_period=='week'}selected{/if}" period="week"  id="period_week"  >{t}1W{/t}</td>
	</tr>
      </table>
    <table  id="avg_options" style="float:left;margin:0 0 0 20px ;padding:0class="option {if $product_view!='sales' };display:none{/if}"  class="options_mini" >
	<tr>
	  <td class="option {if $product_avg=='totals'}selected{/if}" avg="totals"  id="avg_totals" >{t}Totals{/t}</td>
	  <td class="option {if $product_avg=='month'}selected{/if}"  avg="month"  id="avg_month"  >{t}M AVG{/t}</td>
	  <td class="option {if $product_avg=='week'}selected{/if}"  avg="week"  id="avg_week"  >{t}W AVG{/t}</td>
	  <td class="option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff"  id="avg_month_eff"  >{t}M EAVG{/t}</td>
	  <td class="option {if $product_avg=='week_eff'}selected{/if}" style="display:none"  avg="week_eff"  id="avg_week_eff"  >{t}W EAVG{/t}</td>
	</tr>
      </table>
    </div>
   
     {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

	 <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
       </div>
    <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='thumbnails'}display:none{/if}"></div>
    <div  id="table0"   class="data_table_container dtable btable "  style="{if $table_type=='thumbnails'}display:none{/if}"   > </div>
  
</div>
<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_categories" style="{if $block_view!='categories'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>

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
{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="family-table-csv_export" export_options=$csv_export_options }
{include file='footer.tpl'}
