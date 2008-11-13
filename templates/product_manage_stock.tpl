{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >
  
  <div id="sub_header">
    {if $next.id>0}<span class="nav2 onright"><a href="product_manage_stock.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
    {if $prev.id>0}<span class="nav2 onright" ><a href="product_manage_stock.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
    <span class="nav2 onright"><a href="family.php?id={$family_id}">&uarr; {$family}</a></span>
    <span class="nav2 onright"><a href="department.php?id={$department_id}">&uarr;&uarr; {$department}</a></span>
    <span class="nav2 on left"><a href="departments.php">{t}Departments{/t}</a></span>
    <span class="nav2 onleft"><a href="categories.php">{t}Categories{/t}</a></span>
    <span class="nav2 onleft"><a href="products.php">{t}Product index{/t}</a></span>
  </div>
  
  

  <div  style="clear:left;padding:10px 20px;width:220px;float:left">
    <table>
      <tr><td style="vertical-align:bottom;font-size:120%;font-weight:800">{$data.code}</td><td style="vertical-align:middle;color:#777;padding-left:10px"><b>{$units}</b> {t}units per outer{/t}</td></tr>
      {foreach from=$same_products item=product  name=foo}
      {if $smarty.foreach.foo.first}
      <tr><td colspan="2">{t}Linked Products{/t}</td></tr>
      {/if}
      <tr><td style="vertical-align:bottom;font-size:120%;font-weight:800">{$product.code}</td><td style="vertical-align:middle;color:#777;padding-left:10px"><b>{$product.f_units}</b> {t}units per outer{/t}</td></tr>
      {/foreach}
    </table>
    <div class="yui-u first" >
      <div id="photo_container">
	<div style="border:1px solid #ddd;padding-top:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
	  <div id="imagediv"  pic_id="{$images[0].id}"  style="border:1px solid #ddd;width:200px;height:140px;padding:0px 0;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px">
	    <img src="{ if $images[0]}{$image_dir}{$images[0].src}{else}art/nopic.png{/if}"     id="image"   alt="{t}Image{/t}"/>
	  </div>
	</div>
      </div>
    </div>
  </div>
  

  <div  id="manage_stock" class="manage_stock" style=";width:680px;float:left">
    
    <div class="search_box" style="clear:none;float:right;width:120px" >
      <span class="search_title">{t}Product Code{/t}:</span> <input size="8" class="text search" id="prod_search" value="" name="search"/><img align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
      <span  class="search_msg"   id="search_msg"    ></span> <span  class="search_sugestion"   id="search_sugestion"    ></span>
      <br/>
      <span>{t}Return to product pages{/t}:</span><br/>
      <a href="product.php?id={$data.id}">{$data.code}</a>
      {foreach from=$same_products item=product key=sproduct_id name=foo}
       <br/><a href="product.php?id={$sproduct_id}">{$product.code}</a>
      {/foreach}
      
    </div>

    <table class="options" style="float:left">
      <tr class="title">
	<td colspan="3"  {if $locations.has_unknown}style="display:none"{/if}  >{t}Operations{/t}</td>
      </tr>
      <tr>
	<td  {if $locations.num_physical lt 2 }style="display:none"{/if} id="move_stock">Move Stock</td>
	<td  {if $locations.num_physical_with_stock==0}style="display:none"{/if}   id="damaged_stock">Stock Damaged</td>
	<td id="new_location"  {if $locations.has_unknown}style="display:none"{/if}  >Assign Location</td>
      </tr>
       <tr>
	<td id="link_product" onClick="{if $locations.has_link}unlink(this){else}link(this){/if}" >{if $locations.has_link}Unlink Product{else}Link Product{/if}</td>
	<td style="visibility:hidden"></td>
	<td style="visibility:hidden"></td>
       </tr>
    </table>
    <table class="options" style="clear:left;float:left;margin-bottom:20px">
      <tr class="title"> 
	<td colspan="2"  {if !$locations.has_unknown and  !$locations.has_physical}style="display:none"{/if}    >{t}Fix Errors{/t}</td></tr>
      <tr >
	<td id="change_stock"  {if !$locations.has_physical}style="display:none"{/if}>Change Stock Qty</td>
	<td id="change_location" {if !$locations.has_physical}style="display:none"{/if}>Correct Name</td>
	<td id="identify_location" {if !$locations.has_unknown}style="display:none"{/if}>Identify Location</td>
      </tr>
    </table>
    <div id="manage_stock_desktop" style="display:none;width:450px" >
      <div id="manage_stock_close"><img src="art/close.png" style="opacity:.5;position:relative;bottom:5px;left:10px;float:right;cursor:pointer" title="{t}{/t}" onclick="clear_actions();"/></div>
      <div id="manage_stock_messages"></div>
      <div id="manage_stock_products" style="margin:0 0 20px 0;width:100px;display:none"><input id="new_product_input" type="text"><div id="new_product_container"></div></div>
      <div id="manage_stock_locations" style="margin:0 0 20px 0;width:100px;display:none"><input id="new_location_input" type="text"><div id="new_location_container"></div></div>
      <div id="manage_stock_engine"></div>
      <div style="clear:both">
      </div>
    </div>
    <table class="edit_location" id="location_table"  style="clear:both">
      <tr><td>{t}Location{/t}</td><td >{t}Type{/t}</td><td></td><td  style="text-align:right">{t}Units{/t}</td><td  style="text-align:right">{t}Outers{/t}</td><td ></td> </tr>
      {foreach  from=$locations.data item=location name=foo }
      <tr  id="row_{$location.location_id}"   pl_id="{$location.id}"  >
	<td id="loc_name{$location.location_id}" class="aleft"  > {$location.name}</td>
	<td id="loc_tipo{$location.location_id}" >{$location.tipo}</td>
	    <td style="text-align:right"  id="loc_pick_info{$location.location_id}" >
	      <span id="loc_picking_up{$location.location_id}"  rank={$location.picking_rank}  onClick="rank_up({$location.location_id})"   style="cursor:pointer;{if  $location.picking_rank<2 or !$location.picking_rank}display:none;{/if}">&uarr;</span> 
	      <span id="loc_picking_tipo{$location.location_id}" >{$location.picking_tipo}</span>  
	      <img  id="loc_picking_img{$location.location_id}"  can_pick="{if $location.can_pick }1{else}0{/if}"   onclick="swap_picking({$location.location_id})" src="{if $location.can_pick }art/icons/basket.png{else}art/icons/basket_delete.png{/if}" style="position:relative;bottom:1px;vertical-align:bottom;cursor:pointer;{if !$location.is_physical}display:none{/if}"/> 
	    </td>
	    <td  style="text-align:right"><span   id="loc_stock_units{$location.location_id}"    >{$location.stock_units}</span></td>
	    <td  style="text-align:right"><span   id="loc_stock_outers{$location.location_id}"    >{$location.stock_outers}</span></td>
	    <td><img  onclick="desassociate_loc({$location.location_id})"   id="loc_del{$location.location_id}"  can_del="{if $location.has_stock}1{else}0{/if}"   title="{t}Free the location{/t}" style="cursor:pointer;{if $location.has_stock and $location.location_id!=1}display:none{/if}"  src="art/icons/cross.png" /></td></tr>
	  {/foreach}
	   <tr class="totals"><td  >{t}Total Stock{/t}:</td><td COLSPAN="3" id="total_stock_units" style="text-align:right" >{$locations.stock_units}</td><td id="total_stock_outers" style="text-align:right">{$locations.stock_outers}</td><td></td> </tr>
    </table>
  </div>
  

  <div id="the_table" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
    <span class="clean_table_title">{t}History{/t}</span>
    <div  id="clean_table_caption0" class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0">{$table_info}</span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div id="clean_table_filter0" class="clean_table_filter" style="display:none">
	<div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>

     
 

</div>




{include file='footer.tpl'}

