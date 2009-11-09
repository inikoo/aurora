
{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >
  
<div id="sub_header">
{if $next.id>0}<span class="nav2 onright"><a href="part.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="part.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}

 <span class="nav2 onleft"><a  href="departments.php">{t}Departments{/t}</a></span>
  <span class="nav2 onleft"><a href="families.php">{t}Families{/t}</a></span>
  <span class="nav2 onleft"><a href="products.php">{t}Products{/t}</a></span>
  <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
  <span class="nav2 onleft"><a  href="parts.php">{t}Parts{/t}</a></span>

</div>

<div  id="doc3" style="clear:both;" class="yui-g yui-t4" >
  <div id="yui-main"> 
    <div class="yui-b">
      <div  class="yui-gd" style="clear:both;padding:0;width:100%">
	<div class="yui-u first" >
	  <div id="photo_container" style="margin-top:10px">
	    <div style="border:1px solid #ddd;padding-top:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
	      <span style="font-size:150%;font-weight:800">{t}Part SKU{/t} {$part->get('Part SKU')}</span>
	      <div id="imagediv"   style="border:1px solid #ddd;width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer;xbackground:red;margin: 0 0 10px 9px">
		<img src="{ if $num_images>0}{$images[$data.principal_image].med}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
	      </div>
	    </div>
	    <div style="width:160px;margin:auto;padding-top:5px"  >
	      {foreach from=$images item=image  name=foo}
	      {if $image.principal==0} <img  style="float:left;border:1px solid#ccc;padding:2px;margin:2px" src="{$image.tb}"  /></td>{/if}
              {/foreach}
</div>
	  
            
	    
	  </div>
	  
	  
	</div>

	
	<div class="yui-u">
	  <h2>{$part->get('Part XHTML Description')}</h2>
	  <h2>{t}Sold as{/t}: {$part->get('Part XHTML Currently Used In')}</h2>
	  <div class="yui-b"  style="width:100%;">
	    <div class="yui-g" style="width:100%;font-size:90%"   >
              <div class="yui-u first">
		<table    class="show_info_product">
		  <td class="aright">
		    
		     <tr><td>{t}Status{/t}:</td><td>{$part->get('Part Status')}</td></tr>
		      <tr><td>{t}Keeping from{/t}:</td><td>{$part->get('Valid From')}</td></tr>
		     <tr><td>{t}Supplied by{/t}:</td><td>{$part->get('Part XHTML Currently Supplied By')}</td></tr>
		     <tr><td>{t}Cost{/t}:</td><td>{$part->get('Cost')}</td></tr>
		</table>
		 <table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$parts_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $parts_period!='all'}display:none{/if}">
	<tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
     </tbody>
      <tbody id="info_year"  style="{if $parts_period!='year'}display:none{/if}">
      <tr<td>{t}Sales{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Profit{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}GMROI{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      

      </tbody>
        <tbody id="info_quarter" style="{if $parts_period!='quarter'}display:none{/if}"  >
       <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
      </tbody>
        <tbody id="info_month" style="{if $parts_period!='month'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
       <tbody id="info_week" style="{if $parts_period!='week'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
 </table>
		
		
		<table    class="show_info_product">

		</table>
		<table    class="show_info_product" >
		  
		</table>
	      </div>
              <div class="yui-u">

		
		<table   class="show_info_product" >
		  <tr>
		    <td>{t}Stock{/t}:<br>{$stock_units}</td><td class="stock aright" id="stock">{$part->get('Part Current Stock')}</td>
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
		    {if $nextbuy>0   }<tr><td rowspan="2">{t}Next shipment{/t}:</td><td>{$data.next_buy}</td></tr><tr><td class="noborder">{$data.nextbuy_when}</td>{/if}
		    </tr>
		  </table>
		   <table    class="show_info_product">
      <tr >
      <td colspan="2" class="aright" style="padding-right:10px"> <span class="product_info_sales_options" id="info_period"><span id="info_title">{$parts_period_title}</span></span>
      <img id="info_previous" class="previous_button" style="cursor:pointer" src="art/icons/previous.png" alt="<"  title="previous" /> <img id="info_next" class="next_button" style="cursor:pointer"  src="art/icons/next.png" alt=">" tite="next"/></td>
    </tr>
       <tbody id="info_all" style="{if $parts_period!='all'}display:none{/if}">
	<tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
     </tbody>
      <tbody id="info_year"  style="{if $parts_period!='year'}display:none{/if}">
      <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>


      </tbody>
        <tbody id="info_quarter" style="{if $parts_period!='quarter'}display:none{/if}"  >
       <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
      </tbody>
        <tbody id="info_month" style="{if $parts_period!='month'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
       <tbody id="info_week" style="{if $parts_period!='week'}display:none{/if}"  >
         <tr<td>{t}Required{/t}:</td><td class="aright">{$part->get('1 Year Acc Required')}</td></tr>
      <tr<td>{t}Provided{/t}:</td><td class="aright">{$part->get('1 Year Acc Provided')}</td></tr>
      <tr><td>{t}Acquired{/t}:</td><td class="aright">{$part->get('1 Year Acc Adquired')}</td></tr>
      <tr><td>{t}Sold{/t}:</td><td class="aright">{$part->get('1 Year Sold')}</td></tr>
      <tr><td>{t}Given{/t}:</td><td class="aright">{$part->get('1 Year Given')}</td></tr>
	  <tr><td>{t}Broken{/t}:</td><td class="aright">{$part->get('1 Year Broken')}</td></tr>
      <tr><td>{t}Lost{/t}:</td><td class="aright">{$part->get('1 Year Lost')}</td></tr>
	  
      </tbody>
 </table>
		  <table  class="show_info_product">


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
      <tr><td  colspan="5" style="text-align:center"><a href="edit_product.php?id={$data.id}">Edit Product</a></td></tr>
      <tr><td  colspan="5" style="text-align:center"><a href="product_manage_stock.php?id={$data.id}">Manage Stock</a></td></tr>
      
    </table>
    
    
  </div> 
  
  
 <div style="clear:both"></div>

 

      

{if $view_stock}
 <div  id="block_orders" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Part Stock History{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"> <span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span>  </div></div>
      <div class="clean_table_filter"  id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	</div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
 <div  id="block_orders" class="data_table" style="clear:both;margin:25px 0px">
    <span id="table_title" class="clean_table_title">{t}Part Stock Transactions{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info1" class="clean_table_info"> <span id="rtext1"></span> <span class="rtext_rpp" id="rtext_rpp1"></span> <span class="filter_msg"  id="filter_msg1"></span>  </div></div>
      <div class="clean_table_filter"  id="clean_table_filter1"><div class="clean_table_info"><span id="filter_name1">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value1}" size=10/><div id='f_container1'></div></div></div>
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

