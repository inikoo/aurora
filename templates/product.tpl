{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >
  
<div id="sub_header">
{if $next.id>0}<span class="nav2 onright"><a href="product.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="product.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
<span class="nav2 onright"><a href="family.php?id={$family_id}">&uarr; {$product->get('Product Family Name')}</a></span>
<span class="nav2 onright"><a href="department.php?id={$department_id}">&uarr;&uarr; {$product->get('Product Main Department Name')}</a></span>
 <span class="nav2 onleft"><a  href="departments.php">{t}Departments{/t}</a></span>
  <span class="nav2 onleft"><a href="families.php">{t}Families{/t}</a></span>
  <span class="nav2 onleft"><a href="products.php">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
  <span class="nav2 onleft"><a href="parts.php">{t}Parts{/t}</a></span>

</div>

<div  id="doc3" style="clear:both;" class="yui-g yui-t4" >
  <div id="yui-main"> 
    <div class="yui-b">
      

      
      <div  class="yui-gd" style="clear:both;padding:0;width:100%">
	<div class="yui-u first" >
	  <div id="photo_container" style="margin-top:10px">
	    <div style="border:1px solid #ddd;padding-top:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
	      <span style="font-size:150%;font-weight:800">{$product->get('Product Code')}</span>
	      <div id="imagediv"   style="border:1px solid #ddd;width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer;xbackground:red;margin: 0 0 10px 9px">
		<img src="{ if $num_images>0}{$images[$data.principal_image].med}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
	      </div>
	    </div>
	    <div style="width:160px;margin:auto;padding-top:5px"  >
	      {foreach from=$images item=image  name=foo}
	      {if $image.principal==0}
	      <img  style="float:left;border:1px solid#ccc;padding:2px;margin:2px" src="{$image.tb}"  /></td>
{/if}
{/foreach}
</div>
	  
            
	    
	  </div>
	  
	  
	</div>

	
	<div class="yui-u">
	  <h2>{$product->get('Product Name')} [{$product->get('Product ID')}]</h2>
	  <div class="yui-b"  style="width:100%;">
	    <div class="yui-g" style="width:100%;font-size:90%"   >
              <div class="yui-u first">
		<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr >
		      <td><span id="web_status"   style="cursor:pointer">{$web_status}</span> 
			    <img id="web_status_error" onclick="sincronize_all()" style="{if !$web_status_error}visibility:hidden;{/if}vertical-align:top;position:relative;bottom:2px;cursor:pointer"src="art/icons/exclamation.png" title="{$web_status_error_title}"/></td><td  class="aright">
			 <img id="no_sincro_pages" title="{$data.nosincro_pages_why}" onclick="manual_check()" style="{if $data.sincro_pages==1}visibility:hidden;{/if}vertical-align:top;position:relative;bottom:2px;cursor:pointer" src="art/icons/page_error.png"/> 
			 <img id="no_sincro_db" title="{$data.nosincro_db_why}" onclick="sincronizar()" src="art/icons/database_error.png" style="{if $data.sincro_db==1}visibility:hidden;{/if}vertical-align:top;position:relative;bottom:2px;cursor:pointer"/>  
			 <span  id="online"  >{if $num_links>0}{t}Online{/t} ({$fnum_links}) {else}{t}Offline{/t}{/if}</span></td>
		     </tr>
		     <tr style="border-bottom:1px solid #5f84ae;">
		       <td colspan=2><span id="edit_web_messages"></span></td>
		     </tr>
		</table>
		<table    class="show_info_product">
		    <tr>
		      <td>{t}Sell Price{/t}:</td><td  class="price aright">{$product->get('formated price')}{if $product->get('Product Units per Case')>1} <span style="font-weight:400;color:#555">({$unit_price} {t}each{/t})</span>{/if}</td>
		    </tr>
		    <tr {if $product->get('Product Unitary RRP')==''}style="display:none"{/if} >
		      <td>{t}RRP{/t}:</td><td  class="aright">{$product->get('formated unitary rrp')} {if $product->get('product Units per Case')>1}{t}each{/t}{/if}</td>
		    </tr>
		    
		    <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$product->get('formated product for sale since date')} </td>
		      {if $edit} <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>{/if}
		    </tr>
		    <tr><td id="outall_label" title="Total Sales">{t}TS{/t}:</td><td class="aright" >{$product->get('Formated Product Total Quantity Invoiced')} {t}Outers{/t}
		    </td></tr>
		    <tr><td id="awoutall_label" title="Average Weelky Sales" >{t}AWS{/t}:</td><td class="aright" >{$awtsoall} {t}Outers/w{/t}</td></tr>
		    <tr><td id="awoutq_label" title="Average Weelky Sales (Last 12 weeks)">{t}AWSQ{/t}:<td class="aright" >{$awtsoq} {t}Outers/w{/t}</td></tr>
		</table>
		<table    class="show_info_product" >
		  {if $view_suppliers}
		  {if $suppliers>0}
		  {foreach from=$suppliers item=supplier key=supplier_id }
		  <tr>
		    <td>{t}Supplier{/t}:</td>
		    <td><a href="supplier.php?id={$supplier_id}">{$supplier.name}</a></td>
		  </tr>
		  <tr><td>{t}Cost per{/t} :</td><td>{$supplier.formated_price}</td></tr>
		  {/foreach}
		  {else}
		  <tr><td colspan=2 style="color:brown;font-weight:bold;cursor:pointer">{t}No supplier set{/t}</td></tr>
		  {/if}
		  {/if}
		  
		</table>
	    </div>
              <div class="yui-u">

		{if $data.sale_status=='discontinued'}
		<table  style="margin:0;padding:5px 10px;border-top:1px solid #574017;width:100%;background:#deceb2"  >
		  <tr><td style="font-weight:800;font-size:160%;text-align:center">{t}Discontinued{/t}</td></tr>
		</table>
		{/if}
		{if $data.sale_status=='tobediscontinued'}
		<table  style="margin:0;padding:5px 10px;border-top:1px solid #574017;width:100%;background:#deceb2"  >
		  <tr><td style="font-weight:800;font-size:160%;text-align:center">{t}Discontinued{/t}</td></tr>
		</table>
		{/if}
		{if $data.sale_status=='nosale'}
		<table  style="margin:0;padding:5px 10px;border-top:1px solid #c7cbe0;width:100%;background:#deceb2"  >
		  <tr><td style="font-weight:800;font-size:160%;text-align:center">{t}Not for Sale{/t}</td></tr>
		</table>
		{/if}

		<table   class="show_info_product" >
		  <tr>
		    <td>{t}Available{/t}:<td class="stock aright" id="stock">{$product->get('Product Availability')}</td>
		  </tr>
		  <table class="locations " style="float:right"  >
		    {if $product->get('Number of Parts')>0}
		    <tr><td>{t}Parts{/t}:</td><td class="aleft">
			{foreach from=$product->parts item=part name=foo }
			<tr><td>{t}SKU{/t} {$part.sku}</td><td style="padding-left:10px"> ()</td></tr>
			{/foreach}
		      </table>
		    <td>
		      {/if}
		      {if $nextbuy>0   }<tr><td rowspan="2">{t}Next shipment{/t}:</td><td>{$data.next_buy}</td></tr><tr><td class="noborder">{$data.nextbuy_when}</td>{/if}
		    </tr>
		  </table>
		  
		  <table  class="show_info_product">
		    <tr ><td>{t}Unit Weight{/t}:</td><td class="aright">{$product->get('Formated Weight')}</td></tr>
		    <tr><td>{t}Unit Dimensions{/t}:</td><td class="aright">{$data.dimension}</td></tr>
		    <tr ><td>{t}Outer Weight{/t}:</td><td class="aright">{$data.oweight}{t}Kg{/t}</td></tr>
		    <tr><td>{t}Outer Dimensions{/t}:</td><td class="aright">{$data.odimension}</td></tr>

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

     <div class="search_box" >
       <span class="search_title" style="padding-right:15px">{t}Product Code{/t}:</span> <br><input size="8" class="text search" id="product_search" value="" name="search"/><img align="absbottom" id="product_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
       <span  class="product_search_msg"   id="product_search_msg"    ></span> <span  class="search_sugestion"   id="product_search_sugestion"    ></span>
       <br/>
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
      <tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?id={$product->get('Product Most Recent Key')}">Edit Product</a></td></tr>

      
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
	      <td><img id="product_month_sales" class="{if $plot_tipo=='product_month_sales'}selected{else}opaque{/if}" src="art/icons/chart_line.png" title="{t}Sales per month{/t}"/></td>
	      <td><img id="product_quarter_sales"   class="{if $plot_tipo=='product_quarter_sales'}selected{else}opaque{/if}" src="art/icons/chart_line.png" title="{t}Sales per quarter{/t}"/></td>
	      <td><img id="product_year_sales"   class="{if $plot_tipo=='product_year_sales'}selected{else}opaque{/if}" src="art/icons/chart_line.png" title="{t}Sales per year{/t}"/></td>
	      
	      <td></td>
	    </tr>
	<tr>
	  <td class="left"><img src="art/icons/basket.png" title="{t}Outers Sold{/t}"/></td>
	  <td><img  id="product_week_outers"  class="{if $plot_tipo=='product_week_outers'}selected{else}opaque{/if}"  src="art/icons/chart_line.png" title="{t}Outers sold per month{/t}"/></td>
	  <td><img  id="product_month_outers"  class="{if $plot_tipo=='product_month_outers'}selected{else}opaque{/if}"  src="art/icons/chart_line.png" title="{t}Outers sold per month{/t}"/></td>
	  <td><img  id="product_quarter_outers"  class="{if $plot_tipo=='product_quarter_outers'}selected{else}opaque{/if}"  src="art/icons/chart_line.png" title="{t}Outers sold  per quarter{/t}"/></td>
	  <td><img   id="product_year_outers" class="{if $plot_tipo=='product_year_outers'}selected{else}opaque{/if}"  src="art/icons/chart_line.png" title="{t}Outers sold  per year{/t}"/></td>
	  <td></td>
	</tr>
	    <tr style="display:none">
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
	  <tr><td>  <input type="text" size="2" style="vertical-align:bottom;text-align:right" id="plot_months" value="{$plot_data.months}"/> <span style="cursor:pointer" onclick="change_plot_months(this)">months</span></td><tr> 
	  <tr class="title"><td>Y-Axis Range</td><tr> 
	  <tr><td>All <input id="plot_radio_1"  type="radio" onchange="change_plot_sigma(this)" name="y_range" {if !$plot_data.max_sigma}checked="cheked"{/if} value="0"></td><tr> 
	  <tr><td>&sigma; <input id="plot_radio_2" type="radio" name="y_range"  onchange="change_plot_sigma(this)"  {if $plot_data.max_sigma}checked="cheked"{/if} value="1"></td><tr> 

	</table>
      </div>
      
    </div>
    <div id="xplot0" class="product_plot"  style="height:300px;{if $view_plot!=0};display:none{/if}" >
	   <iframe id="the_plot" src ="plot.php?tipo={$plot_tipo}" frameborder=0 height="100%" scrolling="no" width="100%"></iframe>
	 </div>
	 


      </div>
      

  {if $view_orders} 
  <div  id="block_orders" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Orders with this Product{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"> <span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span>  </div></div>
      <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	</div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  {/if}
  
  {if $view_customers} 
  <div  id="block_customers" class="data_table" style="{if $display.customers==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Customer who order this Product{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info1" class="clean_table_info"> <span id="rtext1"></span> <span class="rtext_rpp" id="rtext_rpp1"></span> <span class="filter_msg"  id="filter_msg1"></span>  </div></div>
      <div class="clean_table_filter"  id="clean_table_filter1"><div class="clean_table_info"><span id="filter_name1">{$filter_name1}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value0}" size=10/><div id='f_container1'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
    </div>
    <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>
  {/if}

  

</div>
</div>
<div id="web_status_menu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">

      {foreach from=$web_status_menu key=status_id item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_web_status('{$status_id}')"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

</div>{include file='footer.tpl'}

