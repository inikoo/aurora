var audit_dialog;
var lost_dialog;
var  move_dialog;

function set_all_lost(){
    Dom.get('qty_lost').value=Dom.get('lost_max_value').innerHTML;
    Dom.get('lost_why').focus();
}

function save_lost_items(){
    var data=new Object();
    data['qty']=Dom.get('qty_lost').value;
    data['why']=Dom.get('lost_why').value;
    data['action']=Dom.get('lost_action').value;
    
    data['location_key']=Dom.get('lost_location_key').value
    data['part_sku']=Dom.get('lost_sku').value;
location_key=Dom.get('lost_location_key').value;
sku=Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=lost_stock&values=' + encodeURIComponent(json_value); 

    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='ok'){
		    Dom.get('qty_lost').value='';
		    Dom.get('lost_why').value='';
		    Dom.get('lost_action').value='';

		    Editor_lost_items.hide();
		   
            		     <td class="quantity"  id="part_location_quantity_{$location.PartSKU}_{$location.LocationKey}" quantity="{$location.QuantityOnHand}"  >{$location.FormatedQuantityOnHand}</td>
Dom.get('part_location_quantity_'+sku+'_'+location_key).setAttribute('quantity',r.qty);
Dom.get('part_location_quantity_'+sku+'_'+location_key).innerHTML=r.qty;


            if(false){
		    
		     datatable=tables['table1'];
		    record=datatable.getRecord(Dom.get("lost_record_index").value);
		    datatable.updateCell(record,'qty',r.qty);
		    

		    if(r.qty==0){
			 datatable.updateCell(record,'delete',delete_label);
			 datatable.updateCell(record,'lost','');
			 
		     }else{
			 datatable.updateCell(record,'delete','');
			 datatable.updateCell(record,'lost',lost_label);

		     }							  
		     // alert(r.stock)
		     if(r.stock==0){
			 datatable.updateCell(record,'move','');
			 
		     }else{
			 datatable.updateCell(record,'move',move_label);
			 
		     }	
		    

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		    }
		    
		    
		    
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}

function audit(sku,location_key){

var pos = Dom.getXY('part_location_audit_'+sku+'_'+location_key);
audit_dialog.show();
Dom.setXY('Editor_audit', pos);
}


function lost(sku,location_key){

qty=Dom.get('part_location_quantity_'+sku+'_'+location_key).getAttribute('quantity');
Dom.get('lost_max_value').innerHTML=qty;
Dom.get('lost_sku').value=sku;
Dom.get('lost_location_key').value=location_key;

var pos = Dom.getXY('part_location_lost_items_'+sku+'_'+location_key);
lost_dialog.show();
Dom.setXY('Editor_lost_items', pos);
}

function move(sku,location_key){

var pos = Dom.getXY('part_location_move_items_'+sku+'_'+location_key);
move_dialog.show();
Dom.setXY('Editor_move_items', pos);
}



function save_audit(){


}


function close_audit_dialog(){
audit_dialog.hide();
}
function close_lost_dialog(){
Dom.get('qty_lost').value='';
lost_dialog.hide();
}

function close_move_dialog(){
move_dialog.hide();
}

function init(){

  audit_dialog = new YAHOO.widget.Dialog("Editor_audit", {  visible : false,close:false,underlay: "none",draggable:false});
  audit_dialog.render();
  lost_dialog = new YAHOO.widget.Dialog("Editor_lost_items", {  visible : false,close:false,underlay: "none",draggable:false});
  lost_dialog.render();
  move_dialog = new YAHOO.widget.Dialog("Editor_move_items", {  visible : false,close:false,underlay: "none",draggable:false});
  move_dialog.render();
 }

YAHOO.util.Event.onDOMReady(init);