{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>


<div id="bd" >
  
<div id="sub_header">
{if $next.id>0}<span class="nav2 onright"><a href="location.php?id={$next.id}">{$next.code} &rarr; </a></span>{/if}
{if $prev.id>0}<span class="nav2 onright" ><a href="location.php?id={$prev.id}">&larr; {$prev.code}</a></span>{/if}
<span class="nav2 onleft"><a href="locations.php">{t}Location index{/t}</a></span>
</div>

<div  id="doc3" style="clear:both;" class="yui-g yui-t4" >
  <div id="yui-main"> 
    <div class="yui-b">
      
      
      <div  class="yui-gd" style="clear:both;padding:10px 0;width:100%;">
	
	<div class="yui-u first"  >
	  <div id="map_container" style="border:1px solid #ccc;height:160px">
	    
	  </div>
	</div>

	
	<div class="yui-u">
	  <h1>{$data.name}</h1>
	  <table>
	    <tr><td>{t}Used for{/t}:</td><td>{$data.used_for}</td></tr>
	    <tr><td>{t}Max Capacity{/t}:</td><td>{$data.dim.max_vol}</td></tr>
	    <tr><td>{t}Max Weight{/t}:</td><td>{$data.dim.max_weight}{t}Kg{/t}</td></tr>
	  </table>
	</div>
      </div>
	  </div>
      <div id="the_table1" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
	
	<div style="">

	  <div style="float:right;padding:0;margin:0">
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="change_stock">Audit</td>
	      <td  id="move_stock">Move Stock</td>
	      <td  id="damaged_stock">Set Stock as Damaged</td>
	    </tr>
	  </table>
	  <div id="manage_stock" style="display:none;clear:both;margin:0 0 20px 5px">
	    <div id="manage_stock_messages" ></div>
	    <div id="manage_stock_locations" style="width:100px;display:none;margin-bottom:30px;margin-left:2px"><input id="new_location_input" type="text"><div id="new_location_container"></div></div>
	    <div id="manage_stock_products" style="width:100px;display:none;margin-bottom:30px;margin-left:2px;"><input id="new_product_input" type="text"><div id="new_product_container"></div></div>
	     
	    <div id="manage_stock_engine"></div>
	  </div>
	  </div>
	  <h2>{t}Products{/t}</h2>
	</div>

	<div id="product_messages" style="clear:both">hola</div>


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
      
      

   
    
  </div>
  <div class="yui-b">
    <div  style="float:right;margin-top:10px;text-align:right">
      {t}Location Name{/t}
<form  id="location_search_form" action="locations.php" method="GET" >
<input size="8" class="text search" id="prod_search" value="" name="search"/><img onclick="document.getElementById('location_search_form').submit()"align="absbottom" id="submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search">
 </form>
    </div>	 
    

    
    
  </div> 
  
  </div>
</div>





{include file='footer.tpl'}

