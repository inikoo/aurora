  <div id="Editor_audit" style="position:fixed;top:-200px;width:250px">
  <div style="display:none" class="hd">s</div>
  <div class="bd dt-editor">
  
    <table>
      <input type="hidden" id="audit_location_key" value=""/>
      <input type="hidden" id="audit_sku" value=""/>
      <tr><td>{t}Quantity{/t}:</td><td><input style="text-align:right;width:4em" type="text" id="qty_audit" /></td></tr>
     <tr><td>{t}Notes{/t}:</td><td><input type="text" id="note_audit" /></td></tr>
    </table>
    <div class="yui-dt-button">
      <button onclick="save_audit();" class="yui-dt-default">{t}Save{/t}</button>
      <button onclick="close_audit_dialog()" >{t}Cancel{/t}</button>
    </div>
    
  </div>
</div>


 <div id="Editor_add_stock" style="position:fixed;top:-200px;width:250px">
  <div style="display:none" class="hd">s</div>
  <div class="bd dt-editor">
  
    <table>
      <input type="hidden" id="add_stock_location_key" value=""/>
      <input type="hidden" id="add_stock_sku" value=""/>
      <tr><td>{t}Quantity{/t}:</td><td><input style="text-align:right;width:4em" type="text" id="qty_add_stock" /></td></tr>
     <tr><td>{t}Notes{/t}:</td><td><input type="text" id="note_add_stock" /></td></tr>
    </table>
    <div class="yui-dt-button">
      <button onclick="save_add_stock();" class="yui-dt-default">{t}Save{/t}</button>
      <button onclick="close_add_stock_dialog()" >{t}Cancel{/t}</button>
    </div>
    
  </div>
</div>



<div id="Editor_lost_items" style="position:fixed;top:-200px">
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

<div id="Editor_move_items" style="position:fixed;top:-200px">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0>
          
         
          
	    <input type="hidden" id="move_sku" value=0 >
	    <input type="hidden" id="move_record_index" value=0 >
	    <input type="hidden" id="move_other_location_key" value=0 >
	    <input type="hidden" id="move_this_location_key" value="{$location->id}" >

	    <tr><td colspan="3">{t}Move{/t} <span id="move_sku_formated"></span></td></tr>
	    <tr>
	    <td id="this_location"  style="width:80px;text-align:right;padding-right:10px;"></td>
	    <td id="flow"  style="width:40px;text-align:center" onClick="change_move_flow()" flow="right"><img src="art/icons/arrow_right.png" /></td>
	    <td id="other_location" style="width:80px">
			<div id="location_move_to" style="width:80px;margin-left:2px">
			  <input id="location_move_to_input" type="text" style="width:80px;">
			  <div id="location_move_to_container" ></div>
			</div>
			<div id="location_move_from" style="margin-left:2px;display:none">
			  <input id="location_move_from_input" type="text"  value="">
			  <div id="location_move_from_container"></div>
			</div>


	      </td>
	    </tr>
	    <tr>
	      <td style="text-align:right;padding-right:10px;cursor:pointer" ovalue=""  id="move_stock_left" onclick="move_stock_right()"></td>
	      <td><input value='' style="width:45px;text-align:center" id="move_qty"  onkeyup="move_qty_changed()"   /></td>
	      <td style="padding-left:10px;cursor:pointer" id="move_stock_right"  ovalue="" onclick="move_stock_left()"></td>
	    </tr>
	  </table>
	  <div class="buttons">
	    <button onclick="save_move_items();" class="positive">{t}Save{/t}</button>
	    <button onclick="close_move_dialog()"  class="negative" >{t}Cancel{/t}</button>
	  </div>
    </div>
</div>


<div id="Editor_add_location" style="position:fixed;left:-1000px;width:200px;padding:20px 10px;height:140px">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0 style="width:100%;" >
          
         
          
	    <input type="hidden" id="add_location_sku" value=0 >
	     <input type="hidden" id="add_location_key" value=0 >

	    <tr><td>{t}Add Location{/t}</tr>
	    <tr style="height:30px">
	    
	    <td id="other_location" >
			
			<div id="add_location" >
			  <input id="add_location_input" type="text"  value="" style="width:100%" >
			  <div id="add_location_container"></div>
			</div>


	      </td>
	    </tr>
	    
	    <tr>
	    <td>
	     <div class="buttons">
	    <button style="display:none" onclick="save_add_location();" class="positive">{t}Save{/t}</button>
	    <button class="negative" onclick="close_add_location_dialog()" >{t}Cancel{/t}</button>
	  </div>
	    </td>
	    </tr>
	     <tr style="height:200px">
	     
	     </tr>
	   
	  </table>
	 
    </div>
</div>