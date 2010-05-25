{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
{include file='assets_navigation.tpl'}

<div style="clear:left;"> 
  <span class="branch" ><a  href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a  href="department.php?id={$product->get('Product Main Department Key')}">{$product->get('Product Main Department Name')}</a> &rarr; <a  href="family.php?id={$product->get('Product Family Key')}">{$product->get('Product Family Name')}</a></span>
</div>

<div class="search_box" >
  
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
    <tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?pid={$product->pid}">Edit Product</a></td></tr>
  </table>
  
  
    </div>

<div class="" id="block_info"  style="width:790px">
      

      
      <div   style="clear:left;padding:0;width:100%">

	  <div id="photo_container" style="margin-top:10px;float:left">
	    <div style="border:1px solid #ddd;padding-top:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
	      <span style="font-size:150%;font-weight:800">{$product->get('Product Code')}</span>
	      <div id="imagediv"   style="border:1px solid #ddd;width:{$div_img_width}px;height:{$div_img_height}px;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 0 0 10px 9px;vertical-align:middle">
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
	  

	<div style="float:left;margin-left:20px">
	  <h2>{$product->get('Product Name')} [{$product->get('Product ID')}]</h2>
	  <div class=""  style="width:100%;">
	    <div class="" style="width:100%;font-size:90%"   >
              <div class="" style="width:250px;float:left;margin-right:20px">
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
		      <td>{t}Sell Price{/t}:</td><td  class="price aright">{$product->get_formated_price()}</td>
		    </tr>
		    <tr {if $product->get('Product RRP')==''}style="display:none"{/if} >
		      <td>{t}RRP{/t}:</td><td  class="aright">{$product->get('RRP Per Unit')} {t}each{/t}</td>
		    </tr>
		    
		    <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$product->get('For Sale Since Date')} </td>
		      {if $edit} <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>{/if}
		    </tr>
		  
		</table>

	  <table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$family_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $family_period!='all'}display:none{/if}">
	 <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$product->get('Total Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$product->get('Total Invoices')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$product->get('Total Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$product->get('Total Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$product->get('Total Quantity Delivered')}</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="{if $family_period!='year'}display:none{/if}">
      	<tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$product->get('1 Year Acc Customers')}</td>
	</tr>
		<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$product->get('1 Year Acc Invoices')}</td>
	</tr>

	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$product->get('1 Year Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$product->get('1 Year Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$product->get('1 Year Acc Quantity Delivered')}</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="{if $family_period!='quarter'}display:none{/if}"  >
         <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$product->get('1 Quarter Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$product->get('1 Quarter Acc Invoices')}</td>
	    </tr>
      
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$product->get('1 Quarter Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$product->get('1 Quarter Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$product->get('1 Quarter Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="{if $family_period!='month'}display:none{/if}"  >
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$product->get('1 Month Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$product->get('1 Month Acc Invoices')}</td>
	    </tr>
       
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$product->get('1 Month Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$product->get('1 Month Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$product->get('1 Month Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="{if $family_period!='week'}display:none{/if}"  >
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$product->get('1 Week Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$product->get('1 Week Acc Invoices')}</td>
	    </tr>
       
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$product->get('1 Week Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$product->get('1 Week Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$product->get('1 Week Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
 </table>



		{if $view_suppliers}
		<table    class="show_info_product" >
		  <tr><td>{t}Suppliers{/t}:</td><td class="aright">{$product->get('Product XHTML Supplied By')}</td></tr>
		</table>
		{/if}
	      </div>
              <div class="" style="width:250px;float:left">

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
		  
		    {if $product->get('Product Next Supplier Shipment')   }<tr><td rowspan="2" style="font-size:75%">{$product->get('Product Next Supplier Shipment')}</td></tr>{/if}
		   
		</table>
		
		
		<table class="show_info_product" style="float:right;width:100%"  >
		  <tr><td>{t}Parts{/t}:</td><td class="aright">{$product->get('Product XHTML Parts')}</td></tr>
		  <tr>
		    <td>{t}Locations{/t}:</td><td class="aleft">
		      {foreach from=$product->parts_location item=part name=foo }
		      <tr><td>{t}SKU{/t} <a href="part.php?sku={$part.sku}">{$part.sku}</a></td><td style="padding-left:10px"> {$part.location_code}({$part.stock})</td></tr>
		      {/foreach}
		</table>
		

		  
		  <table  class="show_info_product">
		    <tr ><td>{t}Unit Weight{/t}:</td><td class="aright">{$product->get('Formated Weight')}</td></tr>
		    {if $data.dimension!=''}
		    <tr><td>{t}Unit Dimensions{/t}:</td><td class="aright">{$data.dimension}</td></tr>
		    {/if}
		     {if $data.oweight!=''}
		    <tr ><td>{t}Outer Weight{/t}:</td><td class="aright">{$data.oweight}{t}Kg{/t}</td></tr>
		    {/if}
		    {if $data.odimension!=''}
		    <tr><td>{t}Outer Dimensions{/t}:</td><td class="aright">{$data.odimension}</td></tr>
		    {/if}
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

  


  
 




<div  id="block_timeline" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Product Code Timeline{/t}</span>
    {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3}
    <div  id="table3"   class="data_table_container dtable btable "> </div>
  </div>
<div>


  <div   id="block_plot" style="clear:both;{if $display.plot==0}display:none{/if};margin-top:20px"  >
{include file='plot_splinter.tpl'}
    
     
</div>





 
      

  {if $view_orders} 
  <div  id="block_orders" class="data_table" style="{if $display.orders==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Orders with this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  {/if}
  
  {if $view_customers} 
  <div  id="block_customers" class="data_table" style="{if $display.customers==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Customer who order this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
  <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>
  {/if}


  <div  id="block_history" class="data_table" style="{if $display.history==0}display:none;{/if}clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Product History{/t}</span>
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
    <div  id="table2"   class="data_table_container dtable btable "> </div>
  </div>


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

