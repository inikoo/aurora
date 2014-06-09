var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function cancel_payment(payment_key) {

//alert("caca")
        var request = 'ar_edit_payments.php?tipo=cancel_payment&payment_key=' + payment_key+"&order_key="+Dom.get('order_key').value
      //   alert(request)
        YAHOO.util.Connect.asyncRequest('GET', request, {
            success: function(o) {
          //   alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);



                if (r.state == 200) {
                    // Dom.get('basket_total').innerHTML = r.data.order_total
                    if (r.pending_payments == 0) {
                    
                  //  alert(r.order_dispatch_status)
                    
                        if(r.order_dispatch_status=='In Process by Customer'){
                        	location.href='checkout.php'
                        }else if(r.order_dispatch_status=='Submitted by Customer') {
                        	location.href='thanks.php?id='+Dom.get('order_key').value
                        }else  if(r.order_dispatch_status!='Waiting for Payment Confirmation'){
                            location.href='order.php?id='+Dom.get('order_key').value+'&info='+r.order_dispatch_status

                        }
                        
                        
                    } else {

                        if (r.status != 'Pending') {
                            Dom.setStyle('payment_' + r.payment_key, 'display', 'none')
                            Dom.removeClass('payment_' + r.payment_key, 'payment')

                        } else {
                            Dom.get('payment_date_interval_' + r.payment_key).innerHTML = r.created_time_interval

                        }

                    }


                } else if (r.state == 201) {
                    location.reload();

                }



            },
            failure: function(o) {
                alert("ERRROR: E1 "+o.statusText);
            },
            scope: this
        });



}




function update_payment_table() {

    items = Dom.getElementsByClassName('payment', 'tr', 'pending_payment_confirmations')


    var i;

    for (i = 0; i < items.length; ++i) {
        payment_key = items[i].getAttribute('payment_key')

        var request = 'ar_payments.php?tipo=get_payment_status&payment_key=' + payment_key
        // alert(request)
        YAHOO.util.Connect.asyncRequest('GET', request, {
            success: function(o) {
                // alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);



                if (r.state == 200) {
                    // Dom.get('basket_total').innerHTML = r.data.order_total
                    if (r.pending_payments == 0) {
                        location.href = Don.get('redirect').value
                    } else {

                        if (r.status != 'Pending') {
                            Dom.setStyle('payment_' + r.payment_key, 'display', 'none')
                            Dom.removeClass('payment_' + r.payment_key, 'payment')

                        } else {
                            Dom.get('payment_date_interval_' + r.payment_key).innerHTML = r.created_time_interval

                        }

                    }


                } else if (r.state == 201) {
                    location.reload();

                }



            },
            failure: function(o) {
                alert(o.statusText);
            },
            scope: this
        });


    }

}


function init() {

  loop=setInterval(update_payment_table, 5000);
   

}

YAHOO.util.Event.onDOMReady(init);
