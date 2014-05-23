var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function update_basket(total, items, code) {

    Dom.get('total').innerHTML = total
    Dom.get('number_items').innerHTML = items



    if (Dom.get('waiting_' + code) != undefined) {
        Dom.setStyle('waiting_' + code, 'visibility', 'hidden')

    }

}



function order_product_from_button(code) {


    qty = Dom.get('qty_' + code).value
    if (qty == '') {
        //  Dom.setStyle('error_no_qty_'+code,'visibility','visible')
        Dom.get('qty_' + code).focus()
        return
    }


    Dom.setStyle('waiting_' + code, 'visibility', 'visible')

    var return_url = encodeURIComponent(Dom.get('selfurl').value)
    Dom.get('total').innerHTML = '<img src="art/loading.gif" style="width:14px;position:relative;top:2px"/>'
    Dom.get('number_items').innerHTML = '<img src="art/loading.gif" style="width:14px;position:relative;top:2px"/>'

    request = Dom.get('checkout_order_button_url').value + '&scode=' + Dom.get('product_code_' + code).value + '&product=' + Dom.get('product_description_' + code).value + '&price=' + Dom.get('price_' + code).value + '&return=' + return_url + '&nocart&sd=' + code + '&qty=' + qty
    //alert(request)
    Dom.get('basket_iframe').contentDocument.location = request;

}



function init_basket() {

    //alert(Dom.get('request').value)
    Dom.get('basket_iframe').contentDocument.location = Dom.get('request').value;



}
Event.onDOMReady(init_basket);
