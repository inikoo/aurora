{include file='header.tpl'}
<div id="bd" >
{include file='locations_navigation.tpl'}

<div class="branch"> 
  <span>{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; <a href="warehouse_orders.php?id={$warehouse->id}">{t}Pending Orders{/t}</a> &rarr; {$delivery_note->get('Delivery Note ID')}</span>
</div>


<div id="top_page_menu" class="top_page_menu">

    <div class="buttons" style="float:left">
        <button  onclick="window.location='customers.php?store={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Customers{/t}</button>
    </div>
    <div class="buttons" style="float:right">
    
      <a  style="height:14px"  href="order_pick_aid.pdf.php?&id={$delivery_note->id}" target="_blank"><img style="width:40px;height:12px" src="art/pdf.gif" alt=""></a>
 <a  href="order_pick_aid.php?id={$delivery_note->id}&refresh" target="_blank">{t}Update Locations{/t}</a>
    
   
   </div>
    <div style="clear:both"></div>
</div>


<input value="{$delivery_note->id}" id="dn_key" type="hidden"/>
  <div id="control_panel" style="clear:both;margin-top:15px" >


    <div  style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0;xheight:15em">

       <div style="xborder:1px solid #ddd;width:350px;float:left"> 
        <h1 style="padding:0 0 10px 0">{t}Picking for Delivery Note{/t} {$delivery_note->get('Delivery Note ID')}</h1>
        <h2 style="padding:0">{$delivery_note->get('Delivery Note Customer Name')} ({$customer->get_formated_id()}) {$delivery_note->get('Delivery Note Country 2 Alpha Code')}</h2>
      
	<div style="clear:both"></div>
       </div>

  
      <div style="border:0px solid #ddd;width:330px;float:right;">
          
	 <table  style="xdisplay:none;width:100%;xborder-top:1px solid #333;xborder-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px" >

        <tbody id="resend" style="xdisplay:none">
		        <tr><td  class="aright" >{t}Picker{/t}:</td><td id="assigned_picker" key="{$delivery_note->get('Delivery Note Assigned Picker Key')}"  class="aright">{$delivery_note->get('Delivery Note Assigned Picker Alias')}</td></tr>
	        <tr><td  class="aright" >{t}Transactions{/t}:</td><td  class="aright"><span id="number_picked_transactions">{$number_picked_transactions}</span>/<span id="number_transactions">{$number_transactions}</span> <span style="margin-left:10px" id="percentage_picked">{$delivery_note->get('Faction Picked')}</span></td></tr>
        </tbody>

	   
	   

	   
	 </table>
       </div>

    
       <div style="clear:both"></div>
      </div>
      
      
      
      
      
    <div class="data_table"  style="clear:both">
	<span id="table_title" class="clean_table_title">{t}Items{/t}</span>
	<div id="table_type" style="display:none">
	  <span id="set_pending_as_picked" style="float:right;color:brown" class="table_type state_details ">{t}Set pending as Picked{/t}</span>
	 
	</div>
<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0" style="font-size:80%"  class="data_table_container dtable btable "> </div>
</div>


</div>




  </div>
</div>
</div>

<div id="no_dispatchable_editor_dialog" style="width:200px" xstyle="position:fixed;top:-200px">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
    
    <div style="display:none;margin-top:20px" id="todo_error_msg">
    <p>
    {t}Error, the sum of out of stock and not found units are greater than the number of not picked units{/t}
    </p>
    </div>
    
    
          <table border=0 style="margin:0">
          
         
          
	    <input type="hidden" id="todo_itf_key" value=0 >
	    <input type="hidden" id="todo_units" value=0 >
	    <input type="hidden" id="required_units" value=0 >
	    <input type="hidden" id="picked_units" value=0 >

	    <tr><td colspan="4">{t}Pending{/t}: <span id="formated_todo_units"></span></td></tr>
	    <tr style="display:none">
	    <td></td><td></td>
	    <td><span id="to_assign_todo_units" style="width:20px;"></span></td><td>{t}Unspecified{/t}</td>
	    </tr>
	    <tr>
	    <td style="cursor:pointer" onClick="add_no_dispatchable('out_of_stock_units')">+</td>
	    <td style="cursor:pointer" onClick="remove_no_dispatchable('out_of_stock_units')">-</td>
	    <td><input id="out_of_stock_units" type="text" style="width:20px;"></td><td>{t}Out of Stock{/t}</td>
	      </tr>
	    <tr>
	    <td style="cursor:pointer" onClick="add_no_dispatchable('not_found_units')">+</td>
	    <td style="cursor:pointer" onClick="remove_no_dispatchable('not_found_units')">-</td>
	    <td><input id="not_found_units" type="text" style="width:20px;"></td><td>{t}Not Found{/t}</td>
	    </tr>
	    <tr>
	    <td style="cursor:pointer" onClick="add_no_dispatchable('no_picked_other_units')">+</td>
	    <td style="cursor:pointer" onClick="remove_no_dispatchable('no_picked_other_units')">-</td>
	    <td><input id="no_picked_other_units" type="text" style="width:20px;"></td><td>{t}Other Reason{/t}</td>
	    </tr>	   
	
	  </table>
	  <div class="yui-dt-button" style="margin-top:0px">
	    <button onclick="save_no_dispatchable();" class="yui-dt-default">{t}Save{/t}</button>
	    <button style="display:none" onclick="close_no_dispatchable_dialog()" >{t}Cancel{/t}</button>
	  </div>
    </div>
</div>


{include file='footer.tpl'}
