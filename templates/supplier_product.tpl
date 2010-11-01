{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
 {include file='suppliers_navigation.tpl'}
  <div class="branch"> 
  <span  ><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a  href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a></span>
  </div>
<div id="no_details_title" style="clear:right;{if $show_details}display:none;{/if}">
    <h1>{t}Product{/t}: [{$supplier_product->get('Supplier Product Code')}] {$supplier_product->get('Supplier Product Name')}</h1>
</div>
<div class="" id="block_info"  style="width:890px">
      <div   style="clear:left;padding:0;width:100%">

	  <div id="photo_container" style="margin-top:10px;float:left">
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
	  

	<div style="float:left;margin-left:20px">
	
	  <div class=""  style="width:100%;">
	    <div class="" style="width:100%;font-size:90%"   >
              <div class="" style="width:350px;float:left;margin-right:20px">

		<table    class="show_info_product">
		    <tr>
		      <td>{t}Unit Price{/t}:</td><td  class="price aright">{$supplier_product->get_formated_price_per_unit()}</td>
		    </tr>
		    <tr {if $supplier_product->get('Product RRP')==''}style="display:none"{/if} >
		      <td>{t}RRP{/t}:</td><td  class="aright">{$supplier_product->get('RRP Per Unit')} {t}each{/t}</td>
		    </tr>
		    
		    <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$supplier_product->get('For Sale Since Date')} </td>
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
	  <td>{t}Customers{/t}:</td><td class="aright">{$supplier_product->get('Total Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$supplier_product->get('Total Invoices')}</td>
	</tr>
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$supplier_product->get('Total Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$supplier_product->get('Total Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$supplier_product->get('Total Quantity Delivered')}</td>
	</tr>


      </tbody>

      <tbody id="info_year"  style="{if $family_period!='year'}display:none{/if}">
      	<tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$supplier_product->get('1 Year Acc Customers')}</td>
	</tr>
		<tr >
	  <td>{t}Invoices{/t}:</td><td class="aright">{$supplier_product->get('1 Year Acc Invoices')}</td>
	</tr>

	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$supplier_product->get('1 Year Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$supplier_product->get('1 Year Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$supplier_product->get('1 Year Acc Quantity Delivered')}</td>
	</tr>

      </tbody>
        <tbody id="info_quarter" style="{if $family_period!='quarter'}display:none{/if}"  >
         <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$supplier_product->get('1 Quarter Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$supplier_product->get('1 Quarter Acc Invoices')}</td>
	    </tr>
      
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$supplier_product->get('1 Quarter Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$supplier_product->get('1 Quarter Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$supplier_product->get('1 Quarter Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
        <tbody id="info_month" style="{if $family_period!='month'}display:none{/if}"  >
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$supplier_product->get('1 Month Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$supplier_product->get('1 Month Acc Invoices')}</td>
	    </tr>
       
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$supplier_product->get('1 Month Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$supplier_product->get('1 Month Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$supplier_product->get('1 Month Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
       <tbody id="info_week" style="{if $family_period!='week'}display:none{/if}"  >
        <tr >
	  <td>{t}Customers{/t}:</td><td class="aright">{$supplier_product->get('1 Week Acc Customers')}</td>
	</tr>
       <tr >
	     <td>{t}Invoices{/t}:</td><td class="aright">{$supplier_product->get('1 Week Acc Invoices')}</td>
	    </tr>
       
	<tr >
	  <td>{t}Sales{/t}:</td><td class=" aright">{$supplier_product->get('1 Week Acc Invoiced Amount')}</td>
	</tr>
	<tr >
	  <td>{t}Profit{/t}:</td><td class=" aright">{$supplier_product->get('1 Week Acc Profit')}</td>
	</tr>
	<tr >
	  <td>{t}Outers{/t}:</td><td class="aright">{$supplier_product->get('1 Week Acc Quantity Delivered')}</td>
	</tr>	
      </tbody>
 </table>



		{if $view_suppliers}
		<table    class="show_info_product" >
		  <tr><td>{t}Suppliers{/t}:</td><td class="aright">{$supplier_product->get('Product XHTML Supplied By')}</td></tr>
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

		<table   class="show_info_product" style="{if $supplier_product->get('Product Record Type')=='Historic'}display:none{/if}">
		  <tr>
		    <td>{t}Available{/t}:<td class="stock aright" id="stock">{$supplier_product->get('Product Availability')}</td>
		  </tr>
		  
		    {if $supplier_product->get('Product Next Supplier Shipment')   }<tr><td rowspan="2" style="font-size:75%">{$supplier_product->get('Product Next Supplier Shipment')}</td></tr>{/if}
		   
		</table>
		
		
		<table class="show_info_product" style="{if $supplier_product->get('Product Record Type')=='Historic'}display:none{/if};float:right;width:100%"  >
		  <tr><td>{t}Parts{/t}:</td><td class="aright">{$supplier_product->get('Product XHTML Parts')}</td></tr>
		  <tr>
		    <td>{t}Locations{/t}:</td><td class="aleft">
		      {foreach from=$supplier_product->get_part_locations(true) item=part_location name=foo }
		      <tr><td><a href="part.php?sku={$part.sku}">{$part_location.PartFormatedSKU}</a></td><td style="padding-left:10px"> <a href="location.php?id={$part_location.LocationKey}"><b>{$part_location.LocationCode}</b></a> ({$part_location.QuantityOnHand})</td></tr>
		      {/foreach}
		</table>
		

		  
		  <table  class="show_info_product">
		    <tr ><td>{t}Unit Weight{/t}:</td><td class="aright">{$supplier_product->get('Formated Weight')}</td></tr>
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

</div>{include file='footer.tpl'}
