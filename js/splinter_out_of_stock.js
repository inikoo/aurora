/*
	Autor: Raul Perusquia <raul@inikoo.com>
	Copyright (c) 2009, Inikoo
	Version 2.0
	Created: 25 November 2013 11:14:41 GMT
*/

var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
sales_tables = new Object();
var period_ids = ['mtd', 'ytd', 'wtd', '1w', '10d', '1m', '1q', '1y', '3y', 'last_m', 'last_w', 'yesterday', 'today', '6m'];



function getDocHeight() {
    var D = document;
    return Math.max(
    Math.max(D.body.scrollHeight, D.documentElement.scrollHeight), Math.max(D.body.offsetHeight, D.documentElement.offsetHeight), Math.max(D.body.clientHeight, D.documentElement.clientHeight));
}


function myrenderEvent() {

    parent.Dom.setStyle('block_' + Dom.get('block_key').value, 'height', getDocHeight() + 'px')


}


//function set_title(period) {
//    Dom.get('period_title').innerHTML = Dom.get('period_title_' + period).value;
//}





function get_out_of_stock_data(from, to) {

    var request = 'ar_reports.php?tipo=out_of_stock_data&from=' +from + '&to=' +to
    //alert(request)	 
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('number_out_of_stock_parts').innerHTML = r.number_out_of_stock_parts;
                Dom.get('number_out_of_stock_transactions').innerHTML = r.number_out_of_stock_transactions;
            } else {

            }
        }
    });


}


function get_out_of_stock_customer_data(from, to) {

    var request = 'ar_reports.php?tipo=out_of_stock_customer_data&from=' +from + '&to=' +to
    
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('number_out_of_stock_customers').innerHTML = r.number_out_of_stock_customers;
            } else {

            }
        }
    });
}

function get_out_of_stock_order_data(from, to) {

    var request = 'ar_reports.php?tipo=out_of_stock_order_data&from=' +from + '&to=' +to
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('number_out_of_stock_orders').innerHTML = r.number_out_of_stock_orders;
            } else {

            }
        }
    });
}


function get_out_of_stock_lost_revenue_data(from, to) {

    var request = 'ar_reports.php?tipo=out_of_stock_lost_revenue_data&from=' +from + '&to=' +to
    // alert(request)	 
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('lost_revenue').innerHTML = r.lost_revenue;
            } else {

            }
        }
    });


}

function sales_init(){

//set_title(Dom.get('period').value)
 Event.addListener(period_ids, "click",change_period);


Dom.get('number_out_of_stock_orders').innerHTML='<img src="art/loading.gif" style="height:14px">'
  Dom.get('number_out_of_stock_parts').innerHTML ='<img src="art/loading.gif" style="height:14px">'
                Dom.get('number_out_of_stock_transactions').innerHTML = '<img src="art/loading.gif" style="height:14px">'
                Dom.get('lost_revenue').innerHTML ='<img src="art/loading.gif" style="height:14px">'
              Dom.get('number_out_of_stock_orders').innerHTML = '<img src="art/loading.gif" style="height:14px">'
                Dom.get('number_out_of_stock_customers').innerHTML = '<img src="art/loading.gif" style="height:14px">'




   get_out_of_stock_data(Dom.get('from').value,Dom.get('to').value)
    get_out_of_stock_customer_data(Dom.get('from').value,Dom.get('to').value);
    get_out_of_stock_lost_revenue_data(Dom.get('from').value,Dom.get('to').value);
    get_out_of_stock_order_data(Dom.get('from').value,Dom.get('to').value);
    
   



    
}
Event.onDOMReady(sales_init);




function change_period() {

    Dom.removeClass(period_ids, 'selected');
    Dom.addClass(this, 'selected');

    period = this.id;

 



  
   
   var request = 'ar_sessions.php?tipo=change_period&period=' + period + '&parent=spinter_out_of_stock'+'&from=&to=';
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
         // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
				  Dom.get('period_label').innerHTML = r.period_label
				//  alert(r.from+'  '+r.to)
get_out_of_stock_data(r.from,r.to)
    get_out_of_stock_customer_data(r.from,r.to);
    get_out_of_stock_lost_revenue_data(r.from,r.to);
    get_out_of_stock_order_data(r.from,r.to);
    
    
    Dom.get('link_parts').href='report_out_of_stock.php?period='+period+'&block=parts';
    Dom.get('link_transactions').href='report_out_of_stock.php?period='+period+'&block=transactions';
    Dom.get('link_orders').href='report_out_of_stock.php?period='+period+'&block=orders';
    Dom.get('link_customers').href='report_out_of_stock.php?period='+period+'&block=customers';
    Dom.get('link_revenue').href='report_out_of_stock.php?period='+period+'&block=revenue';

    

            } else{
            
            }
            
        }

    });




   
}



