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
              <div class="" style="width:360px;float:left;margin-right:0">

		

	
		{$product_chain_diagram}
		<div>{t}Parts{/t}</div>
		<table class="show_info_product" style="{if $supplier_product->get('Product Record Type')=='Historic'}display:none{/if};float:right;width:100%"  >
		  {foreach from=$supplier_product->get_parts() item=part_data}
		  <tr>
		    <td>1&rarr;{$part_data.Parts_Per_Supplier_Product_Unit}</td>
		    <td>{$part_data.part->get_sku()}</td>
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
      <div  id="block_orders" class="data_table" style="clear:both;margin:35px 0px">
    <span id="table_title" class="clean_table_title">{t}Purchase Orders with this Product{/t}</span>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
     

    </div> 

</div>{include file='footer.tpl'}
