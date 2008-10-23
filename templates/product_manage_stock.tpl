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
	      <tr class="title"><td colspan="3">{t}Operations{/t}</td></tr>
	      <tr><td id="move_stock">Move Stock</td><td>Stock Damaged</td><td>New Location</td></tr>
	    </table>
	    <table class="options" style="float:left;margin-bottom:20px">
	      <tr class="title"> <td colspan="2">{t}Fix Errors{/t}</td></tr>
	      <tr ><td>Change Stock Qty</td><td>Change Location</td></tr>
	</table>
	<div id="manage_stock_desktop" style="display:none" >
	  <div id="manage_stock_messages"></div>
	  <div id="manage_stock_engine"></div>
	  <div style="clear:both"></div>
	</div>
	<table class="edit_location" style="clear:both">
	  <tr><td>{t}Generally used for{/t}</td><td>{t}Picking Priority{/t}</td><td>{t}Location{/t}</td><td >{t}Stock{/t}</td><td ></td> </tr>
	  {foreach  from=$locations item=location name=foo }
	  <tr ><td>{$location.tipo} </td><td   ><span   rank=$location.picking_rank  onOclick="rank_up()"   style="cursor:pointer;{if $location.picking_rank==1}display:none;{/if}">&uarr;</span><span  onClick="rank_down()"   style="cursor:pointer;{if $physical_locations==$location.picking_rank}display:none;{/if}" >&darr;</span>  {$location.picking_rank} <img style="height:14px;vertical-align:top" src="art/icons/basket.png"/></td><td id="loc_name{$location.location_id}"> {$location.name}</td><td ><input   id="loc_stock{$location.location_id}" class="aright" size="5" type="text" value='{$location.stock}'/></td><td><img src="art/icons/cross.png" /></td></tr>
	  {/foreach}
	   <tr class="totals"><td  class="aright" >{t}Total Stock{/t}:</td><td></td><td></td><td >{$stock}</td><td></td> </tr>
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
      




</div>
</div>

</div>{include file='footer.tpl'}

