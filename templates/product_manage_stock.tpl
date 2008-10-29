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

<div  id="doc3" style="clear:both;" class="yui-g yui-t4" >
  <div id="yui-main"> 
    <div class="yui-b">
      
      
      <div  class="yui-gd" style="clear:both;padding:10px 0;width:100%">
	
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
	      {$units}x {$description} [{$product_id}]
	    </div>
	  </div>
	</div>


	<div class="yui-u">
	  <div  id="manage_stock" class="manage_stock" >
	    <table class="options" style="float:left">
	      <tr class="title">
		<td colspan="3"  {if $locations.has_unknown}style="display:none"{/if}  >{t}Operations{/t}</td>
	      </tr>
	      <tr>
		<td  {if $locations.num_physical lt 2 }style="display:none"{/if} id="move_stock">Move Stock</td>
		<td  {if $locations.num_physical_with_stock==0}style="display:none"{/if}   id="damaged_stock">Stock Damaged</td>
		<td id="new_location"  {if $locations.has_unknown}style="display:none"{/if}  >Assign Location</td></tr>
	    </table>
	    <table class="options" style="clear:both;float:left;margin-bottom:20px">
	      <tr class="title"> 
		<td colspan="2"  {if !$locations.has_unknown and  !$locations.has_physical}style="display:none"{/if}    >{t}Fix Errors{/t}</td></tr>
	      <tr >
		<td id="change_stock"  {if !$locations.has_physical}style="display:none"{/if}>Change Stock Qty</td>
		<td id="modify_location" {if !$locations.has_physical}style="display:none"{/if}>Modify Location</td>
		<td id="identify_location" {if !$locations.has_unknown}style="display:none"{/if}>Identify Location</td>
	      </tr>
	</table>
	    <div id="manage_stock_desktop" style="display:none" >
	      <div id="manage_stock_close"><img src="art/close.png" style="opacity:.5;position:relative;bottom:5px;left:10px;float:right;cursor:pointer" title="{t}{/t}" onclick="clear_actions();"/></div>
	      <div id="manage_stock_messages"></div>
	      <div id="manage_stock_locations" style="margin:0 0 20px 0;width:100px;display:none"><input id="new_location_input" type="text"><div id="new_location_container"></div></div>
	      <div id="manage_stock_engine"></div>
	      <div style="clear:both">
	      </div>
	    </div>
	<table class="edit_location" id="location_table"  style="clear:both">
	  <tr><td>{t}Location{/t}</td><td >{t}Type{/t}</td><td></td><td  style="text-align:right">{t}Stock{/t}</td><td ></td> </tr>
	  {foreach  from=$locations.data item=location name=foo }
	  <tr  id="row_{$location.location_id}"   pl_id="{$location.id}"  >
	    <td id="loc_name{$location.location_id}" class="aleft"  > {$location.name}</td>
	     <td id="loc_tipo{$location.location_id}" >{$location.tipo}</td>
	    <td style="text-align:right"  id="loc_pick_info{$location.location_id}" >
	      <span id="loc_picking_up{$location.location_id}"  rank={$location.picking_rank}  onClick="rank_up({$location.location_id})"   style="cursor:pointer;{if  $location.picking_rank<2 or !$location.picking_rank}display:none;{/if}">&uarr;</span> 
	      <span id="loc_picking_tipo{$location.location_id}" >{$location.picking_tipo}</span>  
	      <img  id="loc_picking_img{$location.location_id}"  can_pick="{if $location.can_pick }1{else}0{/if}"   onclick="swap_picking({$location.location_id})" src="{if $location.can_pick }art/icons/basket.png{else}art/icons/basket_delete.png{/if}" style="position:relative;bottom:1px;vertical-align:bottom;cursor:pointer;{if !$location.is_physical}display:none{/if}"/> 
	    </td>
	    <td  style="text-align:right"><span   id="loc_stock{$location.location_id}"    >{$location.stock}</span></td>
	    <td><img  onclick="desassociate_loc({$location.location_id})"   id="loc_del{$location.location_id}"  can_del="{if $location.has_stock}1{else}0{/if}"   title="{t}Free the location{/t}" style="cursor:pointer;{if $location.has_stock and $location.location_id!=1}display:none{/if}"  src="art/icons/cross.png" /></td></tr>
	  {/foreach}
	   <tr class="totals"><td  >{t}Total Stock{/t}:</td><td COLSPAN="3" id="total_stock" style="text-align:right" >{$stock}</td><td></td> </tr>
	</table>
      </div>
	</div>
      </div>
      
      




    </div> 
  </div>
  <div class="yui-b">
    <div  style="float:right;margin-top:10px;text-align:right">
      {include file='product_search.tpl'}
    </div>	 
    
    <table border=0 cellpadding="2" style="clear:both;float:right;margin-top:20px;" >
      <tr><td  colspan="5" style="text-align:center"><a href="product.php?id={$product_id}">{t}Exit{/t}</a> <img style="margin-left:5px;vertical-align:bottom" src="art/icons/door_out.png"/> </td></tr>
      
    </table>
    
    
  </div> 
  
  </div>
</div>




{include file='footer.tpl'}

