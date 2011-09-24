{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" style="padding:0px">

<div style="padding:0 20px">
 {include file='suppliers_navigation.tpl'}
  <div class="branch"> 
  <span  ><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a  href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a></span>
  </div>
  <h1><span class="id">{$supplier_product->get('Supplier Product Code')}</span> {$supplier_product->get('Supplier Product Name')} </h1>
  

<div class="" id="block_info"  style="width:920px">


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
              <div class="" style="width:280px;float:left;margin-right:20px">
        <table    class="show_info_product" >
		  <tr><td>{t}Supplier{/t}:</td><td class="aright"><a href="supplier.php?id={$supplier_product->get('Supplier Key')}">{$supplier_product->get('Supplier Code')}</a></td></tr>
		   <tr><td>{t}Code{/t}:</td><td class="aright">{$supplier_product->get('Supplier Product Code')}</td></tr>  
		   <tr><td>{t}Name{/t}:</td><td class="aright">{$supplier_product->get('Supplier Product Name')}</td></tr>  
		   <tr><td>{t}Unit{/t}:</td><td class="aright">{$supplier_product->get('Units')}</td></tr>  

		</table>
		<div style="text-align:right">{t}Sold by unit{/t}</div>
		<table    class="show_info_product">
		    <tr>
		      <td>{if $supplier_product->get('Supplier Product Units Per Case')==1 }{t}Cost{/t}{else}{t}Unit Cost{/t}{/if}:</td><td  class="price aright">{$supplier_product->get_formated_price_per_unit()}</td>
		    </tr>
		    <tbody style="{if $supplier_product->get('Supplier Product Units Per Case')==1 }display:none{/if}">
		    <tr>
		      <td>{t}Unit per Case{/t}:</td><td  class="aright">{$supplier_product->get('Units Per Case')}</td>
		    </tr>
		   <tr>
		      <td>{t}Cost per Case{/t}:</td><td  class="aright">{$supplier_product->get_formated_price_per_case()}</td>
		    </tr>
		   </tbody> 
		    
		</table>




		 

	 



		
		
		
	      </div>
              <div class="" style="width:360px;float:left;margin-right:0">

		

	
		{$product_chain_diagram}
		
		<table class="show_info_product" style="{if $supplier_product->get('Product Record Type')=='Historic'}display:none{/if};float:right;width:100%"  >
		 <tr><td>{t}Parts{/t}:</td></tr>
		 {foreach from=$supplier_product->get_parts() item=part_data}
		  <tr>
		    <td>1&rarr;{$part_data.Parts_Per_Supplier_Product_Unit}</td>
		    <td><a href="part.php?sku={$part_data.part->sku}">{$part_data.part->get_sku()}</a></td>
		    <td>{$part_data.part->get('Current Stock')}</td>
		    <td>{$part_data.part->get('Part XHTML Available For Forecast')}</td>
		  </tr>
		  {/foreach}
		</table>
		

		  
		 
		
              </div>
	    </div>
	  </div>
	</div>
      
      </div>
      <div style="clear:both"></div>
      
      
    
     

    </div> 

</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>
     <li> <span class="item {if $block_view=='sales'}selected{/if}"  id="sales">  <span> {t}Sales{/t}</span></span></li>
     <li> <span class="item {if $block_view=='stock'}selected{/if}"  id="stock">  <span> {t}Stock{/t}</span></span></li>
   <li> <span class="item {if $block_view=='purchase_orders'}selected{/if}"  id="purchase_orders"  >  <span> {t}Purchase Orders{/t}</span></span></li>
    <li> <span class="item {if $block_view=='timeline'}selected{/if}"  id="timeline">  <span> {t}History{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">

<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">

    <div class="" style="width:280px;float:left;margin-right:20px">

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
</div>

</div>
<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_stock" style="{if $block_view!='stock'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_timeline" style="{if $block_view!='timeline'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_purchase_orders" style="{if $block_view!='purchase_orders'}display:none;{/if}clear:both;margin:10px 0 40px 0" >
    <span id="table_title" class="clean_table_title">{t}Purchase Orders with this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>


</div>

</div>{include file='footer.tpl'}
