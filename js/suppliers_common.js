var suppliers_period_ids = [
    'suppliers_period_all',
    'suppliers_period_yeartoday',
    'suppliers_period_monthtoday',
    'suppliers_period_weektoday',
    'suppliers_period_today',
    'suppliers_period_yesterday',
    'suppliers_period_last_w',
    'suppliers_period_last_m',
    'suppliers_period_three_year',
    'suppliers_period_year',
    'suppliers_period_six_month',
    'suppliers_period_quarter',
    'suppliers_period_month',
    'suppliers_period_ten_day',
    'suppliers_period_week'
    ];

var supplier_products_period_ids = [
    'supplier_products_period_all',
    'supplier_products_period_yeartoday',
    'supplier_products_period_monthtoday',
    'supplier_products_period_weektoday',
    'supplier_products_period_today',
    'supplier_products_period_yesterday',
    'supplier_products_period_last_w',
    'supplier_products_period_last_m',
    'supplier_products_period_three_year',
    'supplier_products_period_year',
    'supplier_products_period_six_month',
    'supplier_products_period_quarter',
    'supplier_products_period_month',
    'supplier_products_period_ten_day',
    'supplier_products_period_week'
    ];



function change_suppliers_view(e, data) {
    var table = tables['table' + data.table_id];
    var tipo = this.id;
    if (tipo == 'suppliers_general') tipo = 'general';
    else if (tipo == 'suppliers_sales') tipo = 'sales';
    else if (tipo == 'suppliers_profit') tipo = 'profit';

    else if (tipo == 'suppliers_stock') tipo = 'stock';
    else if (tipo == 'suppliers_products') tipo = 'products';
    else if (tipo == 'suppliers_contact') tipo = 'contact';



    if (tipo == 'profit' || tipo == 'sales') {
        Dom.setStyle('suppliers_period_options', 'display', '');
    } else {
        Dom.setStyle('suppliers_period_options', 'display', 'none');
    }

    table.hideColumn('id');

    table.hideColumn('name');
    table.hideColumn('contact');
    table.hideColumn('email');
    table.hideColumn('location');
    table.hideColumn('tel');
    table.hideColumn('pending_pos');
    table.hideColumn('for_sale');

    table.hideColumn('discontinued');
    table.hideColumn('stock_value');
    table.hideColumn('high');
    table.hideColumn('normal');
    table.hideColumn('low');
    table.hideColumn('critical');
    table.hideColumn('outofstock');
    table.hideColumn('sales');
    table.hideColumn('profit');
    table.hideColumn('profit_after_storing');
    table.hideColumn('cost');
    table.hideColumn('margin');
    table.hideColumn('products');

    table.showColumn('code');


    if (tipo == 'general') {
        table.showColumn('name');
        table.showColumn('location');
        table.showColumn('for_sale');
        table.showColumn('pending_pos');
        table.showColumn('products');


    } else if (tipo == 'stock') {
        table.showColumn('high');
        table.showColumn('normal');
        table.showColumn('low');
        table.showColumn('critical');
        table.showColumn('outofstock');
    } else if (tipo == 'contact') {
        table.showColumn('email');
        table.showColumn('tel');
        table.showColumn('name');
        table.showColumn('contact');

    } else if (tipo == 'profit') {
        table.showColumn('profit');
        table.showColumn('profit_after_storing');
        table.showColumn('cost');
        table.showColumn('margin');

    } else if (tipo == 'sales') {
        table.showColumn('sales');
        table.showColumn('sold');
        table.showColumn('required');
        table.showColumn('name');

    } else if (tipo == 'products') {

        table.showColumn('for_sale');
        table.showColumn('name');
        table.showColumn('discontinued');
        table.showColumn('products');

    }

    Dom.removeClass(['suppliers_general', 'suppliers_products', 'suppliers_stock', 'suppliers_sales', 'suppliers_contact', 'suppliers_profit'], 'selected')
    Dom.addClass(this, 'selected')
change_suppliers_view_save(tipo)

}

function change_suppliers_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=suppliers-suppliers-view&value=' + escape(tipo), {});

}
function change_supplier_products_view(e, data) {

    tipo = this.id;
    if (tipo == 'supplier_products_general') tipo = 'general';
    else if (tipo == 'supplier_products_sales') tipo = 'sales';
    else if (tipo == 'supplier_products_stock') tipo = 'stock';
    else if (tipo == 'supplier_products_profit') tipo = 'profit';
  
  
  
  var table = tables['table' + data.table_id];









    table.hideColumn('description');
    table.hideColumn('used_in');
    table.hideColumn('stock');
    table.hideColumn('weeks_until_out_of_stock');
    table.hideColumn('required');
    table.hideColumn('dispatched');
    table.hideColumn('sold');
    table.hideColumn('sales');
    table.hideColumn('profit');
    table.hideColumn('margin');





    if (tipo == 'sales') {
        table.showColumn('required');
        table.showColumn('provided');
        table.showColumn('dispatched');
        table.showColumn('sold');
        table.showColumn('sales');
        table.showColumn('used_in');

        Dom.get('supplier_products_period_options').style.display = '';
        // Dom.get('supplier_products_avg_options').style.display='';
    } else if (tipo == 'general') {

        Dom.get('supplier_products_period_options').style.display = 'none';
        // Dom.get('supplier_products_avg_options').style.display='none';
        table.showColumn('description');
        table.showColumn('used_in');

    } else if (tipo == 'stock') {

        table.showColumn('stock');
        table.showColumn('weeks_until_out_of_stock');
        table.showColumn('used_in');
        Dom.get('supplier_products_period_options').style.display = 'none';
        // Dom.get('supplier_products_avg_options').style.display='none';
    } else if (tipo == 'profit') {
        table.showColumn('margin');

        table.showColumn('profit');
        table.showColumn('used_in');

        Dom.get('supplier_products_period_options').style.display = '';
        // Dom.get('supplier_products_avg_options').style.display='';
    }

    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-supplier_products-view&value=' + escape(tipo), {});


}

function change_suppliers_period(e, table_id) {
    tipo = this.id;

    Dom.removeClass(suppliers_period_ids, "selected")
    Dom.addClass(this, "selected")

    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '&period=' + this.getAttribute('period');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_suppliers_avg(e, table_id) {

    //  alert(avg);
    tipo = this.id;
    Dom.get(avg).className = "";
    Dom.get(tipo).className = "selected";
    avg = tipo;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '&avg=' + this.getAttribute('avg');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}