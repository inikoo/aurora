var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function button_changed(product_pid) {
    if (Dom.get('but_qty' + product_pid).getAttribute('ovalue') != Dom.get('but_qty' + product_pid).value) {
        Dom.setStyle('order_button_' + product_pid, 'border-color', '#aaa')
    } else {
        Dom.setStyle('order_button_' + product_pid, 'border-color', '#fff')

    }

}

function order_product_from_list_changed(product_pid) {
    if (Dom.get('qty' + product_pid).getAttribute('ovalue') != Dom.get('qty' + product_pid).value) {
        Dom.setStyle('list_button' + product_pid, 'visibility', 'visible')
    } else {
        Dom.setStyle('list_button' + product_pid, 'visibility', 'hidden')
    }

}



function order_from_list(code, order_key) {

    items = Dom.getElementsByClassName('product_item', 'tr', 'list_' + code)
    request = '';

    var return_url = encodeURIComponent(Dom.get('selfurl').value)
    Dom.setStyle('waiting_' + code, 'display', '')
    Dom.setStyle('done_' + code, 'opacity', '1')

    var products_to_update = {};

    var i;

    for (i = 0; i < items.length; ++i) {
        counter = items[i].getAttribute('counter')

        if (Dom.get('qty_' + code + '_' + counter) != undefined && Dom.get('qty_' + code + '_' + counter).value != Dom.get('qty_' + code + '_' + counter).getAttribute('ovalue')) {

            qty = parseInt(Dom.get('qty_' + code + '_' + counter).value)

            if (isNaN(qty)) qty = 0




            product_id = Dom.get('product_' + code + '_' + counter).value

            products_to_update[product_id] = qty;

            
        }

    }

    transactions_data = YAHOO.lang.JSON.stringify(products_to_update)

    request = 'ar_basket.php?tipo=edit_multiple_order_transactios&transactions_data=' + transactions_data + '&order_key=' + order_key
    //alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                Dom.get('basket_total').innerHTML = r.data.order_total
                Dom.get('number_items').innerHTML = r.data.ordered_products_number

                Dom.setStyle('waiting_' + code, 'display', 'none')
                Dom.setStyle('done_' + code, 'display', '')

                Dom.setStyle('list_order_button_submit_' + code, 'border-color', '#fff')


                var removeElement = function() {
                        var el = this.getEl();
                        Dom.setStyle(el, 'display', 'none')
                    }

                var myAnim = new YAHOO.util.Anim('done_' + code, {
                    opacity: {
                        from: 1,
                        to: 0
                    },


                }, 4, YAHOO.util.Easing.easeOut);
                myAnim.onComplete.subscribe(removeElement);
                myAnim.animate();


                for (i in r.updated_transactions) {
                    qty = r.updated_transactions[i]['qty'];
                    if (qty == 0) qty = ''
                    Dom.get('qty_' + code + '_' + i).value = qty
                    Dom.get('qty_' + code + '_' + i).setAttribute('ovalue',qty) 
                }


            } else {


            }



        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });







}


function order_product_from_list_old(product_pid) {
    qty = Dom.get('qty' + product_pid).value

    if (qty <= 0 || qty == '') {
        qty = 0
    }

    Dom.get('list_button_img' + product_pid).src = 'art/loading.gif';
    request = 'ar_basket.php?tipo=edit_order_transaction&pid=' + product_pid + '&qty=' + qty
    //alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                Dom.get('basket_total').innerHTML = r.data.order_total
                Dom.get('list_button_img' + product_pid).src = 'art/icons/basket_add.png';
                if (r.quantity == 0) r.quantity = '';
                Dom.get('qty' + r.product_pid).setAttribute('ovalue', r.quantity)
                Dom.get('qty' + r.product_pid).value = r.quantity

                order_product_from_list_changed(r.product_pid)
            } else {


            }



        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });

}

function order_product_from_button(product_pid, order_key) {


    //form_id='order_button_'+product_pid;
    if (Dom.get('but_qty' + product_pid).getAttribute('ovalue') == Dom.get('but_qty' + product_pid).value) return;


    qty = Dom.get('but_qty' + product_pid).value

    if (qty <= 0 || qty == '') {
        qty = 0
    }

    // Dom.setStyle('but_button' + form_id, 'visibility', 'hidden')
    Dom.setStyle('waiting_' + product_pid, 'display', '')
    Dom.setStyle('done_' + product_pid, 'display', 'none')
    Dom.setStyle('done_' + product_pid, 'opacity', 1)



    request = 'ar_basket.php?tipo=edit_order_transaction&pid=' + product_pid + '&qty=' + qty + '&order_key=' + order_key
    //alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                Dom.get('basket_total').innerHTML = r.data.order_total
                Dom.get('number_items').innerHTML = r.data.ordered_products_number

                Dom.setStyle('waiting_' + r.product_pid, 'display', 'none')
                Dom.setStyle('done_' + r.product_pid, 'display', '')
                Dom.setStyle('order_button_' + r.product_pid, 'border-color', '#fff')



                var removeElement = function() {
                        var el = this.getEl();
                        Dom.setStyle(el, 'display', 'none')
                    }

                var myAnim = new YAHOO.util.Anim('done_' + r.product_pid, {
                    opacity: {
                        from: 1,
                        to: 0
                    },


                }, 4, YAHOO.util.Easing.easeOut);
                myAnim.onComplete.subscribe(removeElement);
                myAnim.animate();

                if (r.quantity == 0) r.quantity = '';


                Dom.get('but_qty' + r.product_pid).setAttribute('ovalue', r.quantity)
                Dom.get('but_qty' + r.product_pid).value = r.quantity




                //order_product_from_list_changed(r.product_pid)
            } else {


            }



        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });

}

function init_basket() {

}
Event.onDOMReady(init_basket);
