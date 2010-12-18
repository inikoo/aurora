
<?php


include_once('common.php');
$family_key=sprintf("%d",$_REQUEST['key']);
print "var family_key=$family_key;";

?>

var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

  
function fast_change_item(tipo,product_id){

var qty=Dom.get('order_qty_'+product_id).value;
if(qty=='')
qty=0;
if(tipo=='remove'){
    qty=parseFloat(qty)-1;
}else{
    qty=parseFloat(qty)+1;
}

var ar_file='ar_edit_orders.php';
		request='tipo=edit_new_order&key=quantity&newvalue='+qty+'&pid='+ product_id;
//alert(request)
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					  //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						Dom.get('order_qty_'+product_id).value=r.quantity;
						if(r.quantity>0){
						Dom.setStyle('fast_remove'+product_id,'visibility','visible');
						}else{
						Dom.setStyle('fast_remove'+product_id,'visibility','hidden');

						}
						
						Dom.get('basket_items').innerHTML=r.data.amount_items;
						if(r.data.discounts==0)
						Dom.get('basket_discounts').innerHTML='';
						else
						Dom.get('basket_discounts').innerHTML="(-"+r.data.amount_discounts+")";
						Dom.get('basket_shipping_and_handing').innerHTML=r.data.amount_shipping_and_handing;
						Dom.get('basket_net').innerHTML=r.data.amount_total_net;
						Dom.get('basket_tax').innerHTML=r.data.amount_tax;
						Dom.get('basket_total').innerHTML=r.data.amount_total;


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

function remove_item(product_key){

}

function change_item(product_pid){
Dom.setStyle('order_button'+product_pid,'display','');
Dom.setStyle('undo_button'+product_pid,'display','');

Dom.setStyle('fast_remove'+product_pid,'display','none');
Dom.setStyle('fast_add'+product_pid,'display','none');


}

function undo_item(product_pid){
Dom.get('order_qty_'+product_pid).value=Dom.get('order_qty_'+product_pid).getAttribute('ovalue');
Dom.setStyle('order_button'+product_pid,'display','none');
Dom.setStyle('undo_button'+product_pid,'display','none');

Dom.setStyle('fast_remove'+product_pid,'display','');
Dom.setStyle('fast_add'+product_pid,'display','');

}

function order_item(product_id){
var qty=Dom.get('order_qty_'+product_id).value;


var ar_file='ar_edit_orders.php';
request='tipo=edit_new_order&key=quantity&newvalue='+qty+'&pid='+ product_id;

	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						Dom.get('order_qty_'+product_id).value=r.quantity;
						Dom.get('basket_items').innerHTML=r.data.amount_items;
						if(r.data.discounts==0)
						Dom.get('basket_discounts').innerHTML='';
						else
						Dom.get('basket_discounts').innerHTML="(-"+r.data.amount_discounts+")";
						Dom.get('basket_shipping_and_handing').innerHTML=r.data.amount_shipping_and_handing;
						Dom.get('basket_net').innerHTML=r.data.amount_total_net;
						Dom.get('basket_tax').innerHTML=r.data.amount_tax;
						Dom.get('basket_total').innerHTML=r.data.amount_total;
Dom.setStyle('order_button'+product_id,'display','none');
Dom.setStyle('undo_button'+product_id,'display','none');

Dom.setStyle('fast_remove'+product_id,'display','');
Dom.setStyle('fast_add'+product_id,'display','');


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




function checkout(){
    location.href="checkout.php";
}

function init(){
 // get_thumbnails({tipo:'products',parent:'family',parent_key:family_key});
ids=['table_type_thumbnails','table_type_list','table_type_manual'];
 YAHOO.util.Event.addListener(ids, "click",change_table_type,{table_id:0,parent:'family'});
 
  YAHOO.util.Event.addListener("checkout", "click",checkout);

 }




YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
YAHOO.util.Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });
 