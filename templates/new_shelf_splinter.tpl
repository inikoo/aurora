  <div id="new_warehouse_shelf_block" style="float:left;padding:5px;border:1px solid #ddd;width:400px;margin-bottom:15px;display:none">
       <table class="edit" >
    	 <tr><td class="label">{t}Warehouse{/t}:</td><td><span style="font-weight:800">{$warehouse->get('Warehouse Name')}</span><input type="hidden" id="shelf_warehouse_key" ovalue="{$warehouse->id}" value="{$warehouse->id}"></td></tr>
	 
	 
	 <tr class="first"><td style="width:11em" class="label">Warehouse Area:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="shelf_warehouse_area" value="" ovalue="" >
	      <div id="shelf_warehouse_area_Container"  ></div>
	    </div>
	  </td>
	  <td id="shelf_warehouse_area_msg" class="edit_td_alert"><input type="hidden" value="{$warehouse->get('Warehouse Key')}" id="shelf_warehouse_area_key"></td>
	</tr>
	
	 <tr class="first"><td style="width:11em" class="label">Shelf Code:</td>
	  <td  style="text-align:left;width:19em">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="shelf_code" value="" ovalue="" >
	      <div id="shelf_code_Container"  ></div>
	    </div>
	  </td>
	  <td id="shelf_code_msg" class="edit_td_alert"></td>
	</tr>
	
	<tr class="first"><td  class="label">{t}Shelf Type{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="shelf_shelf_type" value="" ovalue="">
	      <div id="shelf_shelf_type_Container"  ></div>
	    </div>
	  </td>
	  	  <td id="shelf_shelf_type_msg" class="edit_td_alert"><input type="hidden" value="" id="shelf_shelf_type_key"></td>

	</tr>
	   
	    <tr id="tr_layout" style="display:none"><td class="label">{t}Layout{/t}:</td><td>{t}Columns{/t}:<input style="width:2em"  id="shelf_columns" ovalue=""  type="text"/> {t}Rows{/t}:<input style="width:2em" id="shelf_rows" ovalue=""  type="text"/></td></tr>

	   
       </table>    </div>  
    <div id="new_warehouse_shelf_messages" style="font-size:80%;float:left;padding:10px 15px;border:1px solid #ddd;width:200px;margin-bottom:15px;margin-left:10px;display:none">

     </div>