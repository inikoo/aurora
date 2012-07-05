var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;


function order_product_from_list(product_pid){
qty=Dom.get('qty'+product_pid).value



request='ar_basket.php?tipo=edit_order_transaction&pid='+product_pid+'&qty='+qty
alert(request)
 YAHOO.util.Connect.asyncRequest(
                    'GET',
                request, {
                success: function (o) {
                alert(o.responseText)
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                          
                          if(r.state==200){
                          

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