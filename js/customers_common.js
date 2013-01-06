function change_elements(e,type){


ids=['elements_'+type+'_lost','elements_'+type+'_losing','elements_'+type+'_active'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=0;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}

function change_view_customers(e, table_id) {

    var tipo = this.id;


    if (tipo == 'customers_general') tipo = 'general';
    else if (tipo == 'customers_contact') tipo = 'contact';
    else if (tipo == 'customers_address') tipo = 'address';
    else if (tipo == 'customers_balance') tipo = 'balance';
    else if (tipo == 'customers_weblog') tipo = 'weblog';
    else if (tipo == 'customers_rank') tipo = 'rank';
    else if (tipo == 'customers_rank') tipo = 'rank';
    else if (tipo == 'customers_other_value') tipo = 'other_value';


    var table = tables['table' + table_id];




    Dom.removeClass(customer_views_ids, 'selected')
    Dom.addClass(this, 'selected')

    table.hideColumn('location');
    table.hideColumn('last_order');
    table.hideColumn('orders');
    table.hideColumn('other_value');

    table.hideColumn('email');
    table.hideColumn('telephone');
    table.hideColumn('contact_name');

    table.hideColumn('address');
    table.hideColumn('billing_address');
    table.hideColumn('delivery_address');
    table.hideColumn('contact_since');

    table.hideColumn('total_payments');
    table.hideColumn('net_balance');
    table.hideColumn('total_refunds');
    table.hideColumn('total_profit');

    table.hideColumn('balance');
    table.hideColumn('top_orders');
    table.hideColumn('top_invoices');
    table.hideColumn('top_balance');
    table.hideColumn('top_profits');
    table.hideColumn('activity');

    table.hideColumn('logins');
    table.hideColumn('failed_logins');
    table.hideColumn('requests');

    if (tipo == 'general') {
        table.showColumn('name');
        table.showColumn('location');
        table.showColumn('last_order');
        table.showColumn('orders');
        table.showColumn('activity');
        table.showColumn('contact_since');

       
    } else if (tipo == 'contact') {
        table.showColumn('name');
        table.showColumn('contact_name');
        table.showColumn('email');
        table.showColumn('telephone');

    } else if (tipo == 'address') {
        table.showColumn('address');
        table.showColumn('billing_address');
        table.showColumn('delivery_address');

    } else if (tipo == 'balance') {
        table.showColumn('name');
        table.showColumn('net_balance');
        table.showColumn('total_refunds');
        table.showColumn('total_payments');
        table.showColumn('total_profit');

        table.showColumn('balance');

    } else if (tipo == 'rank') {
        table.showColumn('name');
        table.showColumn('top_orders');
        table.showColumn('top_invoices');
        table.showColumn('top_balance');
        table.showColumn('top_profits');

    } else if (tipo == 'weblog') {
        table.showColumn('name');

        table.showColumn('logins');
        table.showColumn('failed_logins');
        table.showColumn('requests');

    }else if (tipo == 'other_value') {
     table.showColumn('name');
        table.showColumn('location');
        table.showColumn('other_value');

}
    

change_customers_view_save(tipo)
}


function change_customers_view_save(tipo){
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customers-table-view&value=' + escape(tipo), {});

}