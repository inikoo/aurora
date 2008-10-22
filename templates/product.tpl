{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >
  
<div id="sub_header">
{if $next.id>0}<span class="nav2 onright"><a href="product.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="product.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
<span class="nav2 onright"><a href="family.php?id={$family_id}">&uarr; {$family}</a></span>
<span class="nav2 onright"><a href="department.php?id={$department_id}">&uarr;&uarr; {$department}</a></span>
<span class="nav2 on left"><a href="departments.php">{t}Departments{/t}</a></span>
<span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
<span class="nav2 onleft"><a href="products.php">{t}Product index{/t}</a></span>
</div>

<div id="doc3" style="clear:both;" class="yui-g yui-t4" >
  <div id="yui-main"> 
    <div class="yui-b">
      
      
      <div  class="yui-gd" style="clear:both;padding:0;width:100%">
	<div class="yui-u first" >
	  <div id="photo_container">
	    <div style="border:1px solid #ddd;padding-top:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
	      <span style="font-size:150%;font-weight:800">{$code}</span>
	      <div id="imagediv"  pic_id="{$images[0].id}"  style="border:1px solid #ddd;width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer;xbackground:red;margin: 0 0 10px 9px">
		<img src="{ if $images[0]}{$image_dir}{$images[0].src}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
	      </div>
	      
	      <div class="image_caption" id="caption" {if $images!=1}style="display:none"{/if}>{$images[0].caption}</div>
	      <table class="other_images " id="otherimages"   xstyle="display:none"   >
		{foreach from=$images item=image  name=foo}
		{if $smarty.foreach.foo.first}<tr>{else}
	          <td  id="oim_{$smarty.foreach.foo.iteration}" pic_id="{$image.id}"  ><img  src="{$image_dir}{$image.src}"  /></td>
	          {/if}
	          {if $smarty.foreach.foo.last}</tr>{/if}
	        {/foreach}
	      </table>
	    </div>
            
	    
	  </div>
	  
	  
	</div>


	<div class="yui-u">
	  <h1>{$units}x {$description} [{$product_id}]</h1>
	  <div class="yui-b"  style="width:100%;">
	    <div class="yui-g" style="width:100%;font-size:90%"   >
              <div class="yui-u first">
		<table    class="show_info_product" >
		  <td class="aright">
		    <tr>
		      <td>{t}Sell Price{/t}:</td><td  class="price aright">{$price}{if $units>1} <span style="font-weight:400;color:#555">({$unit_price} {t}each{/t})</span>{/if}</td>
		    </tr>
		    <tr>
		      <td>{t}RRP{/t}:</td><td  class="aright">{$rrp} {if $units>1}{t}each{/t}{/if}</td>
		    </tr>
		    
		    <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$first_date} ({$weeks_since}{t}w{/t})</td>
		      {if $edit} <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>{/if}
		    </tr>
		    <tr><td id="outall_label" title="Total Sales">{t}TS{/t}:</td><td class="aright" >{$outall} {t}Outers{/t}
		    </td></tr>
		    <tr><td id="awoutall_label" title="Average Weelky Sales" >{t}AWS{/t}:</td><td class="aright" >{$awoutall} {t}Outers/w{/t}</td></tr>
		    <tr><td id="awoutq_label" title="Average Weelky Sales (Last 12 weeks)">{t}AWSQ{/t}:<td class="aright" >{$awoutq} {t}Outers/w{/t}</td></tr>
		</table>
		<table    class="show_info_product" >
		  {if $view_suppliers}
		  {if $suppliers>0}
		  {foreach from=$suppliers_name item=supplier key=supplier_id }
		  <tr><td>{t}Supplier{/t}:</td><td><a href="supplier.php?id={$supplier_id}">{$supplier}</a></td></tr>
		  
		  <tr><td>{t}Unit Cost Price{/t}:</td><td>{$suppliers_price[$supplier_id]}</td></tr>
		  {/foreach}
		  {else}
		  <tr><td colspan=2 style="color:brown;font-weight:bold;cursor:pointer">{t}No supplier set{/t}</td></tr>
		  {/if}
		  {/if}
		  
		</table>
	    </div>
              <div class="yui-u">

		  <table   class="show_info_product" {if $edit}class="edit"{/if}>
		    <tr>
		      <td>{t}Stock{/t}:<br>{$stock_units}</td><td class="stock aright" id="stock">{$stock}</td>
		    </tr>
		     <tr>
		      <td>{t}Available{/t}:</td><td class="aright">{$available}</td>
		    </tr>
		    {if $locations}
		    <tr><td>{t}Location{/t}:</td><td class="aright">
			<table class="locations " style="float:right"  >
			{foreach from=$locations item=location name=foo }
			<tr><td>{$location.icon} </td><td> {$location.name}</td><td style="padding-left:10px"> ({$location.stock})</td></tr>
			{/foreach}
			</table>
		      <td>
		    {/if}
		    {if $nextbuy>0   }<tr><td rowspan="2">{t}Next shipment{/t}:</td><td>{$next_buy}</td></tr><tr><td class="noborder">{$nextbuy_when}</td>{/if}
		    </tr>
		  </table>
		  
		  <table  class="show_info_product">
		    <tr ><td>{t}Unit Weight{/t}:</td><td id="uw">{$uw}</td></tr>
		    <tr><td>{t}Unit Dimensions{/t}:</td><td>{$udim}</td></tr>
		    <tr ><td>{t}Outer Weight{/t}:</td></tr>
		    <tr><td>{t}Outer Dimensions{/t}:</td><td>{$odim}</td></tr>
		    <tr><td>{t}Colour{/t}:</td><td>{$color}</td></tr>
		  </table>
		  <table  class="show_info_product">
		    <tr>
		      <td>{t}Categories{/t}:</td>
		      <td>{$categories}</td>
		    </tr>
		    <tr>
		      <td>{t}Material{/t}:</td>
		      <td>{$materials}</td>
		    </tr>
		    <tr>
		      <td>{t}Ingredients{/t}:</td>
		      <td>{$ingredients}</td>
		    </tr>
		  </table>
		
              </div>
	    </div>
	  </div>
	</div>
      </div>
      
      




    </div> 
  </div>
  <div class="yui-b">
    <div  style="float:right;margin-top:10px;text-align:right">
      {include file='product_search.tpl'}
    </div>	 
    
    <table border=0 cellpadding="2" style="float:right;margin-top:20px;" class="view_options">
      <tr style="border-bottom:1px solid #ddd">
	
	<th><img src="art/icons/information.png" title="{t}Product Details{/t}"/></th>
	{if $view_orders}
	<th><img src="art/icons/chart_line.png" title="{t}Charts{/t}"/></th>
	<th><img  src="art/icons/cart.png" title="{t}Orders{/t}"/></th>
	{/if}
	<th><img src="art/icons/user_green.png" title="{t}Customers{/t}"/></th>
	<th><img src="art/icons/package.png" title="{t}Stock{/t}"/></th>
      </tr>
      <tr style="height:18px;border-bottom:1px solid #ddd">
	<td  id="change_view_details" 
	     {if $display.details==0}title="{t}Show Product Details{/t}" atitle="{t}Hide Product Details{/t}"{else}atitle="Hide Product Details"  title="{t}Hide Product Details{/t}"{/if} >
	  <img {if $hide.details==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_details"  /></td>
	{if $view_orders}
	<td  id="change_view_plot" state="{$display.plot}" block="plot"  
	     {if $display.plot==0} title="{t}Show Charts{/t}" atitle="{t}Hide Charts{/t}"{else} atitle="{t}Show Charts{/t}" title="{t}Hide Charts{/t}"{/if} >
	  <img {if $display.plot==0}style="opacity:0.2"{/if} src="art/icons/tick.png"  id="but_logo_plot"  /></td>
	
	<td  state="{$display.orders}" block="orders"  id="change_view_orders" 
	     {if $display.orders==0}title="{t}Show Orders{/t}" atitle="{t}Hide Orders{/t}" {else} atitle="{t}Show Orders{/t}" title="{t}Hide Orders{/t}" {/if} >
	  <img {if $display.orders==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_orders"   /></td>
	{/if}
	<td  state="{$display.customers}" block="customers"   id="change_view_customers" {if $display.customers==0}title="{t}Show Customers who have ordered this product{/t}" atitle="{t}Hide Customers who have ordered this product{/t}"{else}atitle="{t}Show Customers who have ordered this product{/t}" title="{t}Hide Customers who have ordered this product{/t}"{/if} ><img {if $display.customers==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_customers"   /></td>
	<td   state="{$display.stock_history}" block="stock_history"  id="change_view_stock_history" {if $display.stock_history==0}title="{t}Show Stock History{/t}" atitle="{t}Hide Stock History{/t}"{else}atitle="{t}Show Stock History{/t}" title="{t}Hide Stock History{/t}"{/if} ><img {if $display.stock_history==0}style="opacity:0.2"{/if} src="art/icons/tick.png"    id="but_logo_stock_history"   /></td>
	
      </tr>
      <tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?id={$product_id}">Edit Product</a></td></tr>
      <tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?id={$product_id}">Set Stock</a></td></tr>
      
    </table>
    
    
  </div> 
  
  


  <div id="block_plot" style="clear:both;{if $display.plot==0}display:none{/if}">
    <div id="plot_options" class="plot_options" xstyle="float:right;width:130px">
      <table border=0 class="plot_menu" style="margin-top:30px">
	<tr class="top">
	  <td class="left"></td>
	  <td ><img src="art/icons/calendar_view_week.png" title="{t}Weekly{/t}"/></td>
	  <td><img src="art/icons/calendar_view_month.png" title="{t}Monthy{/t}"/></td>
	  <td><img src="art/icons/calendar_view_quarter.png" title="{t}Quarterly{/t}"/></td>
	  <td><img src="art/icons/calendar_view_year.png" title="{t}Yearly{/t}"/></td>
	    <tr>
	      <td class="left"><img src="art/icons/money.png" title="{t}Net Sales{/t}"/></td>
	      <td><img id="product_week_sales"   class="{if $plot_tipo=='product_week_sales'}selected{else}opaque{/if}" src="art/icons/chart_line.png" title="{t}Sales per week{/t}"/></td>
	      <td><img id="product_month_sales" class="{if $plot_tipo=='product_month_sales'}selected{else}opaque{/if}" src="art/icons/chart_bar.png" title="{t}Sales per month{/t}"/></td>
	      <td><img id="product_quarter_sales"   class="{if $plot_tipo=='product_quarter_sales'}selected{else}opaque{/if}" src="art/icons/chart_bar.png" title="{t}Sales per quarter{/t}"/></td>
	      <td><img id="product_year_sales"   class="{if $plot_tipo=='product_year_sales'}selected{else}opaque{/if}" src="art/icons/chart_line.png" title="{t}Sales per year{/t}"/></td>
	      
	      <td></td>
	    </tr>
	<tr>
	  <td class="left"><img src="art/icons/basket.png" title="{t}Outers Sold{/t}"/></td>
	  <td><img  id="product_week_outers"  class="{if $plot_tipo=='product_week_outers'}selected{else}opaque{/if}"  src="art/icons/chart_line.png" title="{t}Outers sold per month{/t}"/></td>
	  <td><img  id="product_week_outers"  class="{if $plot_tipo=='product_month_outers'}selected{else}opaque{/if}"  src="art/icons/chart_bar.png" title="{t}Outers sold per month{/t}"/></td>
	  <td><img  id="product_week_outers"  class="{if $plot_tipo=='product_quarter_outers'}selected{else}opaque{/if}"  src="art/icons/chart_bar.png" title="{t}Outers sold  per quarter{/t}"/></td>
	  <td><img   id="product_week_outers" class="{if $plot_tipo=='product_year_outers'}selected{else}opaque{/if}"  src="art/icons/chart_line.png" title="{t}Outers sold  per year{/t}"/></td>
	  <td></td>
	</tr>
	    <tr>
	      <td class="left"><img src="art/icons/package.png" title="{t}Stock{/t}"/></td>
	      <td><img id="product_stock_history"  class="{if $plot_tipo=='product_stock_history'}selected{else}opaque{/if}"      src="art/icons/chart_line.png" title="{t}Stock History{/t}"/></td>
	      <td></td>
	      <td></td>
	      <td></td>
	    </tr>
      </table>
      <div class="other_options">
	<table >
	  <tr class="title"><td>Dates</td><tr> 
	  <tr><td >Show last</td><tr> 
	  <tr><td>  <input type="text" size="2" style="vertical-align:bottom"/> <span style="">months</span></td><tr> 
	  <tr class="title"><td>Y-Axis Range</td><tr> 
	  <tr><td>All <input type="radio" name="y_range" checked="cheked" value="all"></td><tr> 
	  <tr><td>&sigma; <input type="radio" name="y_range" value="sigma"></td><tr> 

	</table>
      </div>
      
    </div>
    <div id="xplot0" class="product_plot"  style="height:300px;{if $view_plot!=0};display:none{/if}" >
	   <iframe id="the_plot" src ="plot.php?tipo={$plot_tipo}" frameborder=0 height="100%" scrolling="no" width="100%"></iframe>
	 </div>
	 


      </div>
      

      {if $view_orders} 
      <div  id="block_orders" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 20px">
	<span id="table_title" class="clean_table_title">{t}{$table_title_orders}{/t}</span>
	<div  class="clean_table_caption"  style="clear:both;">
	  <div style="float:left;"><div id="table_info0" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg0"></span></div></div>
	  <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator">{t}Showing all orders{/t}</span></div></div>
	</div>
	<div  id="table0"   class="data_table_container dtable btable "> </div>
      </div>
{/if}
      
      {if $view_customers} 
<div  id="block_customers" class="data_table" style="{if $display.customers==0}display:none;{/if}clear:both;margin:25px 20px">
  <span id="table_title" class="clean_table_title">{t}{$table_title_customers}{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator">{t}Showing all customers{/t}</span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>
{/if}
{if $view_stock}
<div  id="block_stock_history" class="data_table" style="{if $display.stock_history==0}display:none;{/if}clear:both;margin:25px 20px">
  <span id="table_title" class="clean_table_title">{t}{$table_title_stock}{/t}</span>
  <div  class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info2" class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg2"></span></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator">{t}Showing all records{/t}</span></div></div>
  </div>
  <div  id="table2"   class="data_table_container dtable btable "> </div>
</div>
{/if}


</div>
</div>

</div>{include file='footer.tpl'}

