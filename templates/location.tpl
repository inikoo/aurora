{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >
<div id="sub_header">
  {if $next.id>0}<span class="nav2 onright"><a href="location.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
  {if $prev.id>0}<span class="nav2 onright" ><a href="location.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
  <span class="nav2 onleft"><a href="warehouse.php">{t}Location index{/t}</a></span>
</div>

<div class="search_box" >
  <span class="search_title" style="padding-right:15px">{t}Location{/t}:</span> <br><input size="8" class="text search" id="location_search" value="" name="search"/><img align="absbottom" id="location_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
  <span  class="search_msg"   id="location_search_msg"    ></span> <span  class="search_sugestion"   id="location_search_sugestion"    ></span>
  <br/>
  <a style="font-weight:800;color:#777;cursor:pointer" href="edit_location.php?id={$id}">{t}Edit Location{/t}</a>
</div>

<div style="padding:20px">
  <div id="location_map" style="float:left;border:1px solid #ccc;height:160px;width:360px;margin-top:10px;margin-right:20px">
  </div>
  <div style="padding-left:20px">
    <h1>{$data.name}</h1>
    <table>
      <tr><td>{t}Used for{/t}:</td><td>{$data.used_for}</td></tr>
      <tr><td>{t}Max Capacity{/t}:</td><td>{$data.dim.max_vol}</td></tr>
      <tr><td>{t}Max Weight{/t}:</td><td>{$data.dim.max_weight}{t}Kg{/t}</td></tr>
    </table>
  </div>

</div>


<div id="the_table1" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  
  <div style="">
    <div style="float:right;padding:0;margin:0">
      <table class="options" style="float:right;padding:0;margin:0">
	<tr>
	  <td  style="{if $num_products<1 or !$has_stock}visibility:hidden;{/if}" id="move_stock">Move Stock</td>
	  <td  style="{if !$has_stock}visibility:hidden;{/if}" id="damaged_stock">Set Stock as Damaged</td>
	  <td  style="{if $num_products==0}visibility:hidden;{/if}" id="change_stock">Audit</td>
	  <td  id="add_product">Add Product</td>
	  
	</tr>
      </table>
      
      
      <div id="manage_stock" style="display:none;clear:both;margin:0 0 20px 5px">
	<div id="manage_stock_messages" ></div>
	<div id="manage_stock_locations" style="width:100px;display:none;margin-bottom:30px;margin-left:2px">
	  <input id="new_location_input" type="text">
	  <div id="new_location_container"></div>
	</div>
	<div id="manage_stock_products" style="width:100px;xdisplay:none;margin-bottom:30px;margin-left:2px;">
	  <input id="new_product_input" type="text">
	  <div id="new_product_container">
	    
	  </div>
	</div>
	
	<div id="manage_stock_engine"></div>
      </div>
    </div>
    <h2>{t}Products{/t}</h2>
  </div>
  
  <div id="product_messages" style="clear:both"></div>
  
  
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

<div id="the_table0" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption0" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div id="clean_table_filter0" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>

      

   
    




{include file='footer.tpl'}

