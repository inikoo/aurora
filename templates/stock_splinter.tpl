  <div id="Editor_audit" xstyle="visibility:hidden">
  <div style="display:none" class="hd">s</div>
  <div class="bd dt-editor">
  
    <table>
      <input type="hidden" id="lost_record_index" value=""/>
      <input type="hidden" id="lost_sku" value=""/>
      <tr><td>{t}Audit Quantity{/t}:</td><td><input style="text-align:right;width:4em" type="text" id="qty_audit" /></td></tr>
     
    </table>
    <div class="yui-dt-button">
      <button onclick="save_audit();" class="yui-dt-default">{t}Save{/t}</button>
      <button onclick="close_audit_dialog()" >{t}Cancel{/t}</button>
    </div>
    
  </div>
</div>


<div id="Editor_lost_items" xstyle="visibility:hidden">
  <div style="display:none" class="hd">s</div>
  <div class="bd dt-editor">
  
    <table>
      <input type="hidden" id="lost_record_index" value=""/>
      <input type="hidden" id="lost_sku" value=""/>
      <input type="hidden" id="lost_location_key" value=""/>
      <tr><td>{t}Quantity Lost{/t}:</td><td><input style="text-align:right;width:4em" type="text" id="qty_lost" /> {t}max{/t} <span onclick="set_all_lost()" id="lost_max_value" style="cursor:pointer"></span></td></tr>
      <tr><td>{t}Why?{/t}:</td><td><input type="text" id="lost_why" /></td></tr>
      <tr><td>{t}Action{/t}:</td><td><input type="text" id="lost_action" /></td></tr>
    </table>
    <div class="yui-dt-button">
      <button onclick="save_lost_items();" class="yui-dt-default">{t}Save{/t}</button>
      <button onclick="close_lost_dialog()" >{t}Cancel{/t}</button>
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
	    <tr><td id="this_location"  style="xwidth:180px;text-align:right;padding-right:10px;"></td>
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
	    <button onclick="close_move_dialog()" >{t}Cancel{/t}</button>
	  </div>
    </div>
</div>