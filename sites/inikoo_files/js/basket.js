var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;


function order_product_from_list_changed(product_pid){
alert("a")
}

function order_product_from_list(product_pid){
qty=Dom.get('qty'+product_pid).value


Dom.get('list_button_img'+product_pid).src='art/loading.gif';
request='ar_basket.php?tipo=edit_order_transaction&pid='+product_pid+'&qty='+qty
//alert(request)
 YAHOO.util.Connect.asyncRequest(
                    'GET',
                request, {
                success: function (o) {
                //alert(o.responseText)
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                          
                          if(r.state==200){
                          Dom.get('basket_total').innerHTML=r.data.order_total
							Dom.get('list_button_img'+product_pid).src='art/icons/basket_add.png';
                          }else{
                         
                          
                          }
                          
                     
                      
                    },
                    failure: function (o) {
                        alert(o.statusText);
                    },
                scope:this
                }
                );

}


function init_basket() {

}
Event.onDOMReady(init_basket);