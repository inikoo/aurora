var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function update_payment_table(){

	    items = Dom.getElementsByClassName('payment', 'tr', 'pending_payment_confirmations')


    var i;

    for (i = 0; i < items.length; ++i) {
        payment_key = items[i].getAttribute('payment_key')
        
           request = 'ar_payments.php?tipo=get_payment_status&payment_key=' + payment_key
   //alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);



            if (r.state == 200) {
                Dom.get('basket_total').innerHTML = r.data.order_total
               

            } else {


            }



        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });

        
        }

}


function init(){

	//loop=setInterval(update_payment_table, 1000);
update_payment_table();

}

YAHOO.util.Event.onDOMReady(init);