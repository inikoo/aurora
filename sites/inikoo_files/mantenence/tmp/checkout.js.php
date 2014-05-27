<?php exit(); ?>
var order_key="<?php echo $_REQUEST['order_key']?>"

var data={ 
    "firstName":''
    ,"lastName":''
    ,"company":''
	,"address1":''
	,"address2":''
	,"city":''
	,"state":''
	,"zip":''
	,"country":''
	,"phone":''
	,"CCNo":''
	,"CVV2":''
	,"CCType":''
	,"CCExpiresMonth":''
	,"CCExpiresYear":''
	,"contactEmail":''
	,"notes":''
    };


function init(){

Event.addListener("payment_option", "click", payment_option);
Event.addListener("Submit_CC", "click", Submit_CC);
Event.addListener("Save_CC", "click", Save_CC);




}

function Save_CC(){
	alert('save');
}

function Submit_CC(){
	data['firstName']=Dom.get('firstName').value;
	data['lastName']=Dom.get('lastName').value;
	data['company']=Dom.get('company').value;
	data['address1']=Dom.get('address1').value;
	data['address2']=Dom.get('address2').value;
	data['city']=Dom.get('city').value;
	data['state']=Dom.get('state').value;
	data['zip']=Dom.get('zip').value;
	data['country']=Dom.get('country').value;
	data['phone']=Dom.get('phone').value;
	data['CCNo']=Dom.get('CCNo').value;
	data['CVV2']=Dom.get('CVV2').value;
	data['CCType']=Dom.get('CCType').value;
	data['CCExpiresMonth']=Dom.get('CCExpiresMonth').value;
	data['CCExpiresYear']=Dom.get('CCExpiresYear').value;
	data['contactEmail']=Dom.get('contactEmail').value;
	data['notes']=Dom.get('notes').value;


	var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
	var ar_file=path+'inikoo_files/ar_edit_orders.php';
	request='tipo=cc_payment&json_values='+json_value;
//alert(request)
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						alert('ok');
							
				
					    } else {
						alert(r.L_LONGMESSAGE0);
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