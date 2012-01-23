
function save_use_calculated_shipping(){
var ar_file='ar_edit_orders.php'; 
    	var request='tipo=use_calculated_shipping&order_key='+order_key;

alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
					
					for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					        Dom.get('order_shipping_method').innerHTML=r.order_shipping_method;
					 Dom.get('shipping_amount').value=r.shipping_amount
						}
						reset_set_shipping()
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  

}

function save_set_shipping(){
value=Dom.get("shipping_amount").value;
var ar_file='ar_edit_orders.php'; 
    	var request='tipo=set_order_shipping&value='+value+'&order_key='+order_key;

//alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
					
					for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					           Dom.get('order_shipping_method').innerHTML=r.order_shipping_method;
					            Dom.get('shipping_amount').value=r.shipping_amount
						}
						reset_set_shipping()
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  

}

function reset_set_shipping(){
dialog_edit_shipping.hide();
}





function close_edit_delivery_address_dialog(){
edit_delivery_address.hide();
}

function change_delivery_address(){

 var y=(Dom.getY('control_panel'))
    var x=(Dom.getX('control_panel'))
Dom.setX('edit_delivery_address_splinter_dialog',x);
Dom.setY('edit_delivery_address_splinter_dialog',y+0);
 edit_delivery_address.show();
}


function post_change_main_delivery_address(){
 
    var ar_file='ar_edit_orders.php';
    request='tipo=update_ship_to_key&order_key='+order_key+'&ship_to_key=0';
	
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					 
					   var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						if(r.result=='updated'){
						edit_delivery_address.hide()
					Dom.get('delivery_address').innerHTML=r.new_value;
						     Dom.setStyle('tr_order_shipping','display','');
						    Dom.setStyle('shipping_address','display','');
						    Dom.setStyle('for_collection','display','none');
						}
					    } else {
						alert(r.msg);
						//	callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    // callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
}
function change_shipping_type(){


new_value=this.getAttribute('value');
var ar_file='ar_edit_orders.php';
	request='tipo=edit_new_order_shipping_type&id='+order_key+'&key=collection&newvalue='+new_value;
	//alert(request);return;
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						if(r.result=='updated'){
						    if(r.new_value=='Yes'){
						    Dom.setStyle('tr_order_shipping','display','none');
						    Dom.setStyle('shipping_address','display','none');
						    Dom.setStyle('for_collection','display','');

						    
						    }else{
						     Dom.setStyle('tr_order_shipping','display','');
						    Dom.setStyle('shipping_address','display','');
						    Dom.setStyle('for_collection','display','none');
						    
						    }
						    
						
						}
						    
						
					    } else {
						alert(r.msg);
						//	callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    // callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
	

}






function validate_discount_percentage(o){

percentage=parseFloat(o.value);
if(!isNaN(percentage) && percentage>=0 && percentage<=100){
Dom.removeClass('change_discount_save','disabled')

}else{
Dom.addClass('change_discount_save','disabled')

}


}




function change_discount(o,transaction_key,record_key,value){
	region1 = Dom.getRegion(o); 
    region2 = Dom.getRegion('change_staff_discount'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_staff_discount', pos);
	change_staff_discount.show()
	
		Dom.get('change_discount_transaction_key').value=transaction_key;
     Dom.get('change_discount_record_key').value= record_key;
Dom.get('change_discount_value').value=value;
	Dom.get('change_discount_value').focus();
}
function cancel_change_discount(){

	Dom.get('change_discount_value').value='';
	

	Dom.addClass('change_discount_save','disabled');
	change_staff_discount.hide();

    }


function save_change_discount(){
if(!Dom.hasClass('change_discount_save','disabled')){

Dom.setStyle('change_staff_discount_waiting','display','')
Dom.setStyle('change_staff_discount_buttons','display','none')


var ar_file='ar_edit_orders.php'; 
    	var request='tipo=update_percentage_discount&order_transaction_key='+Dom.get('change_discount_transaction_key').value+'&percentage='+Dom.get('change_discount_value').value+'&order_key='+Dom.get('order_key').value;

//alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
					
					
						
						
						var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					
						  datatable=tables['table0'];


		    record=datatable.getRecord(Dom.get("change_discount_record_key").value);
					
					for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					      Dom.get('ordered_products_number').value=r.data['ordered_products_number'];
                 		if(r.discounts){
						    Dom.get('tr_order_items_gross').style.display='';
						}
						else{
						    Dom.get('tr_order_items_gross').style.display='none';

						}

		           if(r.charges){
						    Dom.get('tr_order_items_charges').style.display='';

						}
						else{
						    Dom.get('tr_order_items_charges').style.display='none';

						}
						

						datatable.updateCell(record,'quantity',r.quantity);
						datatable.updateCell(record,'to_charge',r.to_charge);
						datatable.updateCell(record,'description',r.description);
					
change_staff_discount.hide();
Dom.setStyle('change_staff_discount_waiting','display','none')
Dom.setStyle('change_staff_discount_buttons','display','')

						
						
						
						
						
						}
						
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  


}
}

function init_common_order_not_dispatched(){
Event.addListener("tr_order_shipping", "mouseover", show_edit_button,{'name':'shipping'});
Event.addListener("tr_order_shipping", "mouseout", hide_edit_button,{'name':'shipping'});
Event.addListener("use_calculate_shipping", "click", save_use_calculated_shipping);
edit_delivery_address = new YAHOO.widget.Dialog("edit_delivery_address_splinter_dialog", 
			{ 
			    visible : false,close:true,
			    underlay: "none",draggable:false
			    
			} );
 edit_delivery_address.render();
YAHOO.util.Event.addListener("change_delivery_address", "click",change_delivery_address);

 change_staff_discount = new YAHOO.widget.Dialog("change_staff_discount", { visible : false,close:true,underlay: "none",draggable:false} );
 change_staff_discount.render();
 
YAHOO.util.Event.addListener(["set_for_collection","set_for_shipping"], "click",change_shipping_type);


  dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {context:["cancel","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_cancel.render();
  YAHOO.util.Event.addListener("cancel", "click",open_cancel_dialog );

dialog_edit_shipping = new YAHOO.widget.Dialog("dialog_edit_shipping", {context:["edit_button_shipping","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_edit_shipping.render();
Event.addListener("edit_button_shipping", "click", dialog_edit_shipping.show,dialog_edit_shipping , true);

Event.addListener("save_set_shipping", "click", save_set_shipping);
Event.addListener("reset_set_shipping", "click", reset_set_shipping);


Event.addListener("change_discount_save", "click", save_change_discount);
Event.addListener("change_discount_cancel", "click", cancel_change_discount);

}

YAHOO.util.Event.onDOMReady(init_common_order_not_dispatched);