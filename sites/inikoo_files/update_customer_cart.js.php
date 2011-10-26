<?php global $path;

if($_REQUEST['path']=='1')
	$_path="../";
elseif($_REQUEST['path']=='2')
	$_path="../../";
elseif($_REQUEST['path']=='3')
	$_path="../sites/";
?>


function init(){

ids=['order_single_product34107'];
Event.addListener(ids, "click", order_single_product);

}





YAHOO.util.Event.onDOMReady(init);

function order_single_product(pid){
if(isNaN(parseInt(Dom.get('qty'+pid).value))){
	alert('Invalid Qty'); return;
}

	
	Dom.get('loading'+pid).innerHTML='<img src="'+path+'inikoo_files/art/loading.gif"/>';
	var pid=Dom.get('pid'+pid).value;
	var order_key=Dom.get('order_id'+pid).value;

	var old_qty=Dom.get('old_qty'+pid).value;
	var new_qty=parseInt(Dom.get('qty'+pid).value) + parseInt(old_qty);
	//alert(pid+' ' + order_id+ ' ' + qty);
	//alert(new_qty);
	

	
 var ar_file=path+'inikoo_files/ar_edit_orders.php';
	request='tipo=edit_new_order&id='+order_key+'&key=quantity&newvalue='+new_qty+'&oldvalue=0&pid='+ pid;
	//request='tipo=edit_new_order&id='+order_key+'&key=quantity&newvalue=1&oldvalue=0&pid='+ pid;
alert(request)
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						for(x in r.data){
							Dom.get('old_qty'+pid).value=new_qty;
							Dom.get('loading'+pid).innerHTML='<img src="'+path+'inikoo_files/art/icons/accept.png"/>';
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