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
  <a style="font-weight:800;color:#777;cursor:pointer" href="edit_location.php?id={$location->get('Location Key')}">{t}Edit Location{/t}</a>
</div>


  <div style="clear:left;margin-top:30px;padding-left:20px;width:20em">
    <table class="show_info_product">
      <tr><td>{t}Location{/t}:</td><td style="font-weight:800">{$location->get('Location Code')}</td></tr>
      <tr><td>{t}Used for{/t}:</td><td>{$location->get('Location Mainly Used For')}</td></tr>
      <tr><td>{t}Max Capacity{/t}:</td><td>{$location->get('Location Max Volume')}</td></tr>
      <tr><td>{t}Max Weight{/t}:</td><td>{$location->get('Location Max Weight')}</td></tr>
      <tr><td>{t}Max Slots{/t}:</td><td>{$location->get('Location Max Slots')}</td></tr>

    </table>


</div>


<div id="the_table1" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">

  <div style="">
    <div style="float:right;padding:0;margin:0">
      <table class="options" style="float:right;padding:0;margin:0">
	<tr>
	  <td  id="add_product">Add Part</td>
	  
	</tr>
      </table>
      
      
      <div id="manage_stock" style="display:none;clear:both;margin:0 0 20px 5px">
	<div id="manage_stock_messages" ></div>
	<div id="manage_stock_locations" style="width:100px;display:none;margin-bottom:30px;margin-left:2px">
	  <input id="new_location_input" type="text">
	  <div id="new_location_container"></div>
	</div>
	<div id="manage_stock_products" style="width:400px;xdisplay:none;margin-bottom:30px;margin-left:2px;">
	  <input id="new_product_input" type="text">
	  <div id="new_product_container">
	    
	  </div>
	</div>
	
	<div id="manage_stock_engine"></div>
      </div>
    </div>
    <h2>{t}Parts{/t}</h2>
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

<div id="Editor_lost_items" xstyle="visibility:hidden">
  <div style="display:none" class="hd">s</div>
  <div class="bd dt-editor">
  
    <table>
      <input type="hidden" id="lost_record_index" value=""/>
      <input type="hidden" id="lost_sku" value=""/>
      <tr><td>{t}Quantity Lost{/t}:</td><td><input style="text-align:right;width:4em" type="text" id="qty_lost" /> {t}max{/t} <span onclick="set_all_lost()" id="lost_max_value" style="cursor:pointer"></span></td></tr>
      <tr><td>{t}Why?{/t}:</td><td><input type="text" id="lost_why" /></td></tr>
      <tr><td>{t}Action{/t}:</td><td><input type="text" id="lost_action" /></td></tr>
    </table>
    <div class="yui-dt-button">
      <button onclick="save_lost_items();" class="yui-dt-default">{t}Save{/t}</button>
      <button onclick="Editor_lost_items.cfg.setProperty('visible',false);" >{t}Cancel{/t}</button>
    </div>
    
  </div>
</div>


<div id="Editor_move_items" xstyle="visibility:hidden">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0>
	    <input type="hidden" id="move_sku" value=0 >
	    <input type="hidden" id="move_record_index" value=0 >
	    <input type="hidden" id="other_location_key" value=0 >
	    <input type="hidden" id="this_location_key" value="{$location->id}" >

	    <tr><td colspan="3">{t}Move{/t} <span id="move_sku_formated"></span></td></tr>
	    <tr><td id="this_location"  style="xwidth:180px;text-align:right;padding-right:10px;">{$location->get('Location Code')}</td>
	      <td id="flow"  style="width:40px;text-align:center" onClick="change_move_flow()" flow="right"><img src="art/icons/arrow_right.png" /></td>
	      <td id="other_location" style="xwidth:180px">
			<div id="location_move_to" style="width:8em;xdisplay:none;xmargin-bottom:30px;margin-left:2px">
			  <input id="location_move_to_input" type="text">
			  <div id="location_move_to_container"></div>
			</div>
			<div id="location_move_from" style="width:8em;xdisplay:none;xmargin-bottom:30px;margin-left:2px;display:none">
			  <input id="location_move_from_input" type="text">
			  <div id="location_move_from_container"></div>
			</div>


	      </td>
	    </tr>
	    <tr>
	      <td style="width:8em;text-align:right;padding-right:10px;cursor:pointer" ovalue=""  id="move_stock_left" onclick="move_stock_right()"></td>
	      <td><input value='' style="width:40px;text-align:center" id="move_qty"  onkeyup="move_qty_changed()"   /></td>
	      <td style="padding-left:10px;cursor:pointer" id="move_stock_right"  ovalue="" onclick="move_stock_left()"></td>
	    </tr>
	  </table>
	  <div class="yui-dt-button">
	    <button onclick="save_move_items();" class="yui-dt-default">{t}Save{/t}</button>
	    <button onclick="Editor_move_items.cfg.setProperty('visible',false);" >{t}Cancel{/t}</button>
	  </div>
    </div>
</div>
