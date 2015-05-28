

function get_history_numbers() {

    var ar_file = 'ar_assets.php';
    var request = 'tipo=get_history_numbers&subject='+Dom.get('subject').value+'&subject_key=' + Dom.get('subject_key').value;

    //   alert(ar_file+'?'+request)
    Dom.get('elements_history_Changes_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_history_Notes_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_history_Attachments_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {

                for (i in r.elements_numbers) {
                    if (Dom.get('elements_history_' + i + '_number') != undefined) Dom.get('elements_history_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );

}

function change_product_view(e, data) {
    tipo = this.id;



    if (tipo == 'product_general') tipo = 'general';
    else if (tipo == 'product_sales') tipo = 'sales';
    else if (tipo == 'product_stock') tipo = 'stock';
    else if (tipo == 'product_parts') tipo = 'parts';
    else if (tipo == 'product_categories') tipo = 'categories';
    else if (tipo == 'product_timeline') tipo = 'timeline';
    else if (tipo == 'product_properties') tipo = 'properties';
    else if (tipo == 'product_reorder') tipo = 'reorder';

    var table = tables['table' + data.table_id];
    table.hideColumn('smallname');
    table.hideColumn('name');
    table.hideColumn('stock');
    table.hideColumn('stock_value');
    table.hideColumn('sales');
    table.hideColumn('profit');
    table.hideColumn('sold');
    table.hideColumn('margin');
    table.hideColumn('state');
    table.hideColumn('web');
    table.hideColumn('parts');
    table.hideColumn('supplied');
    table.hideColumn('formated_record_type');
    table.hideColumn('family');
    table.hideColumn('dept');
    table.hideColumn('expcode');
    table.hideColumn('delta_sales');
    table.hideColumn('stock_state');
    table.hideColumn('gmroi');
    table.hideColumn('stock_forecast');
    table.hideColumn('price');
    table.hideColumn('from');
    table.hideColumn('last_update');
    table.hideColumn('to');

    table.hideColumn('package_type');
    table.hideColumn('package_weight');
    table.hideColumn('package_dimension');
    table.hideColumn('package_volume');
    table.hideColumn('unit_weight');
    table.hideColumn('unit_dimension');




    table.hideColumn('1m_avg_sold_over_1y');
    table.hideColumn('days_available_over_1y');
    table.hideColumn('percentage_available_1y');

    Dom.setStyle(['change_products_display_mode', 'product_period_options', 'product_avg_options'], 'display', 'none')


    if (tipo == 'sales') {
        table.showColumn('sold');
        table.showColumn('sales');
        //table.showColumn('profit');
        //  table.showColumn('margin');
        table.showColumn('delta_sales');

        Dom.setStyle(['change_products_display_mode', 'product_period_options', 'product_avg_options'], 'display', '')

        table.showColumn('smallname');
    } else if (tipo == 'general') {
        table.showColumn('name');
        table.showColumn('web');
        table.showColumn('stock');
        table.showColumn('formated_record_type');
        table.showColumn('price');

        Dom.get('product_period_options').style.display = 'none';
        Dom.get('product_avg_options').style.display = 'none';
    } else if (tipo == 'stock') {
        table.showColumn('formated_record_type');
        table.showColumn('stock');
        table.showColumn('stock_value');
        table.showColumn('smallname');
        table.showColumn('state');
        table.showColumn('web');
        Dom.setStyle(['product_period_options', 'product_avg_options'], 'display', 'none')

    } else if (tipo == 'parts') {
        table.showColumn('parts');
        table.showColumn('supplied');
        table.showColumn('gmroi');
        table.showColumn('smallname');




    } else if (tipo == 'cats') {

        table.showColumn('family');
        table.showColumn('dept');
        table.showColumn('expcode');
        Dom.setStyle(['product_period_options', 'product_avg_options'], 'display', 'none')

    } else if (tipo == 'timeline') {

        Dom.get('product_period_options').style.display = 'none';
        Dom.get('product_avg_options').style.display = 'none';
        table.showColumn('from');
        table.showColumn('last_update');
        table.showColumn('to');

    } else if (tipo == 'properties') {
        Dom.setStyle(['product_period_options', 'product_avg_options'], 'display', 'none')

        table.showColumn('package_type');
        table.showColumn('package_weight');
        table.showColumn('package_dimension');
        table.showColumn('package_volume');
        table.showColumn('unit_weight');
        table.showColumn('unit_dimension');
    } else if (tipo == 'reorder') {

        table.showColumn('smallname');
        table.showColumn('stock');
        table.showColumn('1m_avg_sold_over_1y');
        table.showColumn('days_available_over_1y');
        table.showColumn('percentage_available_1y');
    }

    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-products-view&value=' + escape(tipo), {});

}




function change_family_view(e, data) {

    var table = tables['table' + data.table_id];
    var tipo = this.id;
    if (tipo == 'family_general') tipo = 'general';
    else if (tipo == 'family_sales') tipo = 'sales';
    else if (tipo == 'family_stock') tipo = 'stock';
    else if (tipo == 'family_timeline') tipo = 'timeline';

    table.hideColumn('department');

    table.hideColumn('stock_value');
    table.hideColumn('stock_error');
    table.hideColumn('outofstock');
    table.hideColumn('active');
    table.hideColumn('sales');
    table.hideColumn('profit');
    table.hideColumn('surplus');
    table.hideColumn('optimal');
    table.hideColumn('low');
    table.hideColumn('critcal');
    table.hideColumn('name');
    table.hideColumn('delta_sales');
    table.hideColumn('from');
    table.hideColumn('last_update');
    table.hideColumn('to');

    Dom.setStyle(['change_families_display_mode', 'family_period_options', 'family_avg_options'], 'display', 'none')


    if (tipo == 'sales') {
        table.showColumn('department');

        table.showColumn('profit');
        table.showColumn('sales');
        table.showColumn('name');
        table.showColumn('delta_sales');

        Dom.setStyle(['change_families_display_mode', 'family_period_options', 'family_avg_options'], 'display', '')


    } else if (tipo == 'general') {
        table.showColumn('department');

        table.showColumn('name');
        table.showColumn('active');

        Dom.get('family_period_options').style.display = 'none';
        Dom.get('family_avg_options').style.display = 'none';
    } else if (tipo == 'stock') {
        table.showColumn('department');

        table.showColumn('stock_error');
        table.showColumn('outofstock');
        table.showColumn('surplus');
        table.showColumn('optimal');
        table.showColumn('low');
        table.showColumn('critcal');

        Dom.get('family_' + 'period_options').style.display = 'none';


        Dom.get('family_' + 'avg_options').style.display = 'none';
    } else if (tipo == 'timeline') {

        Dom.get('family_period_options').style.display = 'none';
        Dom.get('family_avg_options').style.display = 'none';
        table.showColumn('from');
        table.showColumn('last_update');
        table.showColumn('to');

    }


    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-families-view&value=' + escape(tipo), {});
    Dom.get('families_view').value = tipo

}




function change_department_view(e, data) {

    var table = tables['table' + data.table_id];
    var tipo = this.id;
    if (tipo == 'department_general') tipo = 'general';
    else if (tipo == 'department_sales') tipo = 'sales';
    else if (tipo == 'department_stock') tipo = 'stock';
    else if (tipo == 'department_timeline') tipo = 'timeline';



    table.hideColumn('awp_p');
    table.hideColumn('name');

    table.hideColumn('awp_p');
    table.hideColumn('aws_p');
    table.hideColumn('active');
    table.hideColumn('todo');
    table.hideColumn('discontinued');

    table.hideColumn('families');
    table.hideColumn('sales');
    table.hideColumn('profit');
    //    table.hideColumn('stock_value');
    table.hideColumn('stock_error');
    table.hideColumn('outofstock');
    table.hideColumn('surplus');
    table.hideColumn('optimal');
    table.hideColumn('low');
    table.hideColumn('critical');
    table.hideColumn('delta_sales');
    table.hideColumn('from');
    table.hideColumn('last_update');
    table.hideColumn('to');


    Dom.setStyle(['change_departments_display_mode', 'department_period_options', 'department_avg_options'], 'display', 'none')

    if (tipo == 'sales') {
        table.showColumn('name');

        //table.showColumn('awp_p');
        //table.showColumn('aws_p');
        table.showColumn('sales');
        table.showColumn('delta_sales');
        table.showColumn('profit');
        Dom.setStyle(['change_departments_display_mode', 'department_period_options', 'department_avg_options'], 'display', '')

    } else if (tipo == 'general') {
        table.showColumn('name');

        Dom.get('department_period_options').style.display = 'none';
        Dom.get('department_avg_options').style.display = 'none';
        table.showColumn('active');
        table.showColumn('todo');
        table.showColumn('families');
        table.showColumn('discontinued');

    } else if (tipo == 'stock') {
        table.showColumn('name');

        Dom.get('department_period_options').style.display = 'none';
        Dom.get('department_avg_options').style.display = 'none';

        table.showColumn('surplus');
        table.showColumn('optimal');
        table.showColumn('low');
        table.showColumn('critical');
        table.showColumn('stock_error');
        table.showColumn('outofstock');
    } else if (tipo == 'timeline') {

        Dom.get('department_period_options').style.display = 'none';
        Dom.get('department_avg_options').style.display = 'none';
        table.showColumn('from');
        table.showColumn('last_update');
        table.showColumn('to');

    }

    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-departments-view&value=' + escape(tipo), {
        success: function(o) {}
    });


}

function change_timeline_group(table_id, subject, mode, label) {

    Dom.removeClass(Dom.getElementsByClassName('timeline_group', 'button', subject + '_timeline_group_options'), 'selected');;
    Dom.addClass(subject + '_timeline_group_' + mode, 'selected');
    var request = '&timeline_group=' + mode;
    dialog_sales_history_timeline_group.hide();

    Dom.get('change_' + subject + '_timeline_group').innerHTML = ' &#x21b6 ' + label;
    var request = '&timeline_group=' + mode;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_dialog_sales_history_timeline_group() {
    region1 = Dom.getRegion('change_sales_history_timeline_group');
    region2 = Dom.getRegion('dialog_sales_history_timeline_group');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_sales_history_timeline_group', pos);
    dialog_sales_history_timeline_group.show();
}
