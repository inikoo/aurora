var parts_period_ids = [
    'parts_period_all',
    'parts_period_yeartoday',
    'parts_period_monthtoday',
    'parts_period_weektoday',
    'parts_period_today',
    'parts_period_yesterday',
    'parts_period_last_w',
    'parts_period_last_m',
    'parts_period_three_year',
    'parts_period_year',
    'parts_period_six_month',
    'parts_period_quarter',
    'parts_period_month',
    'parts_period_ten_day',
    'parts_period_week'
    ];

function change_parts_view(e, table_id) {
    //alert(this.id)
    var tipo = this.getAttribute('name');
    //  alert(tipo)
    var table = tables['table' + table_id];

    Dom.removeClass(['parts_general', 'parts_stock', 'parts_sales', 'parts_forecast', 'parts_locations'], 'selected')

    Dom.addClass(this, 'selected')

    table.hideColumn('description');
    table.hideColumn('supplied_by');
    table.hideColumn('stock');
    table.hideColumn('stock_value');
    table.hideColumn('avg_stock');
    table.hideColumn('avg_stockvalue');
    table.hideColumn('keep_days');
    table.hideColumn('outstock_days');
    table.hideColumn('unknown_days');
    table.hideColumn('gmroi');
    table.hideColumn('sold');
    table.hideColumn('money_in');
    table.hideColumn('delta_sold');
    table.hideColumn('delta_money_in');
    table.hideColumn('profit_sold');
    table.hideColumn('margin');
    table.hideColumn('locations');
    table.hideColumn('used_in');

    table.hideColumn('description_small');

    if (tipo == 'general') {
        // Dom.setStyle(['part_period_options','avg_options'],'display','none')
        Dom.setStyle(['part_period_options'], 'display', 'none')

        table.showColumn('description');
        table.showColumn('supplied_by');
        table.showColumn('used_in');

    } else if (tipo == 'stock') {
        table.showColumn('description_small');

        //Dom.setStyle(['part_period_options','avg_options'],'display','none')
        Dom.setStyle(['part_period_options'], 'display', 'none')


        table.showColumn('stock');
        table.showColumn('stock_value');
        //   table.showColumn('avg_stock');
        //  table.showColumn('avg_stockvalue');
        //  table.showColumn('keep_days');
        //  table.showColumn('outstock_days');
        //  table.showColumn('unknown_days');

    } else if (tipo == 'sales') {
        table.showColumn('description_small');

        //Dom.setStyle(['part_period_options','avg_options'],'display','')
        Dom.setStyle(['part_period_options'], 'display', '')

        table.showColumn('sold');
        table.showColumn('money_in');
        table.showColumn('delta_sold');
        table.showColumn('delta_money_in');

    } else if (tipo == 'locations') {

        table.showColumn('description_small');

        // Dom.setStyle(['part_period_options','avg_options'],'display','')
        Dom.setStyle(['part_period_options'], 'display', 'none')
        table.showColumn('locations');
        table.showColumn('stock');
        table.showColumn('stock_value');

    } else if (tipo == 'forecast') {
        table.showColumn('description_small');
        table.showColumn('profit_sold');
        table.showColumn('margin');
        // Dom.setStyle(['part_period_options','avg_options'],'display','')
        Dom.setStyle(['part_period_options'], 'display', '')
        table.showColumn('gmroi');


    }
    change_parts_view_save(tipo)
}


function change_parts_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=warehouse-parts-view&value=' + escape(tipo), {});

}


function change_parts_period(e, table_id) {
    tipo = this.id;

    Dom.removeClass(parts_period_ids, "selected")
    Dom.addClass(this, "selected")

    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '&period=' + this.getAttribute('period');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_parts_avg(e, table_id) {

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


function change_parts_elements(e, table_id) {
    
    ids = ['elements_InUse', 'elements_NotInUse'];

    //ids = ['elements_Keeping', 'elements_NotKeeping', 'elements_Discontinued', 'elements_LastStock'];
    if (Dom.hasClass(this, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')

        }

    } else {
        Dom.addClass(this, 'selected')

    }


    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}
