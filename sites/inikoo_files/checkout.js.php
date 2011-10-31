var order_key="<?php echo $_REQUEST['order_key']?>"

function init(){

Event.addListener("payment_option", "click", payment_option);





}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "not radio obj";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "middle";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "not selected";
}

function payment_option(){

alert(getCheckedValue(Dom.get('payment_type')));
alert('rr');return;

	//order_key=Dom.get('order_key').value;

var ar_file=path+'inikoo_files/ar_edit_orders.php';
	request='tipo=update_order&order_key='+order_key;
//alert(request)
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						
						for(x in r.data){
							Dom.get('basket_total').innerHTML=r.data['order_total'];
							Dom.get('basket_items').innerHTML=r.data['ordered_products_number'];
							//alert('ok');
						    //Dom.get(x).innerHTML=r.data[x];
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


YAHOO.util.Event.onDOMReady(init);