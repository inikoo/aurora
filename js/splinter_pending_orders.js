var Dom   = YAHOO.util.Dom;

function change_pending_orders_store(parent_key){

Dom.removeClass(Dom.getElementsByClassName('option','span','pending_orders_store_chooser'),'selected')
if(!parent_key){
	var parent='none'
	Dom.addClass('pending_orders_all_stores','selected')
}else{
	var parent='store'
		Dom.addClass('pending_orders_store_'+parent_key,'selected')

}

 get_pending_orders_in_basket_data(parent,parent_key)
 get_pending_orders_in_process_data(parent,parent_key)
 get_pending_orders_in_warehouse_data(parent,parent_key)
 get_pending_orders_packed_data(parent,parent_key)

}

function get_pending_orders_in_basket_data(parent,parent_key) {
    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_pending_orders_in_basket_data&parent=' +parent+ '&parent_key=' +parent_key
//   alert(ar_file+'?'+request)
   YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

        //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                 for (x in r.data) {

                        if (Dom.get(x) != undefined) {
                            Dom.get(x).innerHTML = r.data[x];
                        }
                    }
            }
        },
        failure: function(o) {
           // alert(o.statusText);
        },
        scope: this
    }, request

    );
}

function get_pending_orders_in_process_data(parent,parent_key) {
    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_pending_orders_in_process_data&parent=' +parent+ '&parent_key=' +parent_key
   YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                 for (x in r.data) {
                        if (Dom.get(x) != undefined) {
                            Dom.get(x).innerHTML = r.data[x];
                        }
                    }
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}

function get_pending_orders_in_warehouse_data(parent,parent_key) {
    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_pending_orders_in_warehouse_data&parent=' +parent+ '&parent_key=' +parent_key
   YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                 for (x in r.data) {
                        if (Dom.get(x) != undefined) {
                            Dom.get(x).innerHTML = r.data[x];
                        }
                    }
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}

function get_pending_orders_packed_data(parent,parent_key) {
    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_pending_orders_packed_data&parent=' +parent+ '&parent_key=' +parent_key
   YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                 for (x in r.data) {
                        if (Dom.get(x) != undefined) {
                            Dom.get(x).innerHTML = r.data[x];
                        }
                    }
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}

function init() {
 get_pending_orders_in_basket_data(Dom.get('parent').value ,Dom.get('parent_key').value )
 get_pending_orders_in_process_data(Dom.get('parent').value ,Dom.get('parent_key').value )
 get_pending_orders_in_warehouse_data(Dom.get('parent').value ,Dom.get('parent_key').value )
 get_pending_orders_packed_data(Dom.get('parent').value ,Dom.get('parent_key').value )
}

YAHOO.util.Event.onDOMReady(init);