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

function part_myrenderEvent() {

    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }
    get_part_elements_numbers()

}

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
    table.hideColumn('stock_days');
    table.hideColumn('stock_state');
        table.hideColumn('next_shipment');

   // table.hideColumn('reference');

    table.hideColumn('description_small');

    if (tipo == 'general') {
        // Dom.setStyle(['part_period_options','avg_options'],'display','none')
        Dom.setStyle(['part_period_options'], 'display', 'none')

        table.showColumn('description');
        table.showColumn('reference');
        table.showColumn('used_in');

    } else if (tipo == 'stock') {
        table.showColumn('description_small');

        //Dom.setStyle(['part_period_options','avg_options'],'display','none')
        Dom.setStyle(['part_period_options'], 'display', 'none')


        table.showColumn('stock');
        table.showColumn('stock_value');

        table.showColumn('stock_days');
        table.showColumn('stock_state');
        table.showColumn('next_shipment');

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


var already_clicked_parts_elements_use_click=false;
function change_parts_elements_use(e, table_id) {
    var el = this
  
        if (already_clicked_parts_elements_use_click)
        {
            already_clicked_parts_elements_use_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_parts_elements_use_dblclick(el, table_id)
        }
        else
        {
            already_clicked_parts_elements_use_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_parts_elements_use_click=false; // reset when it happens
                 change_parts_elements_use_click(el, table_id)
            },300); // <-- dblclick tolerance here
        }
        return false;
}



function change_parts_elements_use_click(el, table_id) {

    ids = ['elements_InUse', 'elements_NotInUse'];

    if (Dom.hasClass(el, 'selected')) {
        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')
            Dom.removeClass(el.id + '_bis', 'selected')
            Dom.removeClass(el.id + '_tris', 'selected')

        }

    } else {
        Dom.addClass(el, 'selected')
        Dom.addClass(el.id + '_bis', 'selected')
        Dom.addClass(el.id + '_tris', 'selected')

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
    //alert(request);
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



function change_parts_elements_use_dblclick(el, table_id) {

    ids = ['elements_InUse', 'elements_NotInUse','elements_InUse_bis', 'elements_NotInUse_bis'];

  	Dom.removeClass(ids, 'selected')
Dom.addClass(el, 'selected')
        Dom.addClass(el.id + '_bis', 'selected')
           Dom.addClass(el.id + '_tris', 'selected')


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
    //alert(request);
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}




var already_clicked_parts_elements_use_bis_click=false;
function change_parts_elements_use_bis(e, table_id) {
    var el = this
  
        if (already_clicked_parts_elements_use_bis_click)
        {
            already_clicked_parts_elements_use_bis_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_parts_elements_use_bis_dblclick(el, table_id)
        }
        else
        {
            already_clicked_parts_elements_use_bis_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_parts_elements_use_bis_click=false; // reset when it happens
                 change_parts_elements_use_bis_click(el, table_id)
            },300); // <-- dblclick tolerance here
        }
        return false;
}

function change_parts_elements_use_bis_click(el, table_id) {


    Dom.get(['elements_Error_number', 'elements_Excess_number', 'elements_Normal_number', 'elements_Low_number', 'elements_VeryLow_number', 'elements_OutofStock_number']).innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';

    ids = ['elements_InUse_bis', 'elements_NotInUse_bis'];

    //ids = ['elements_Keeping', 'elements_NotKeeping', 'elements_Discontinued', 'elements_LastStock'];
    if (Dom.hasClass(el, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')
            Dom.removeClass(el.getAttribute('id2'), 'selected')
            Dom.removeClass(el.getAttribute('id3'), 'selected')


        }

    } else {
        Dom.addClass(el, 'selected')
        Dom.addClass(el.getAttribute('id2'), 'selected')
        Dom.addClass(el.getAttribute('id3'), 'selected')

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

function change_parts_elements_use_bis_dblclick(el, table_id) {


    Dom.get(['elements_Error_number', 'elements_Excess_number', 'elements_Normal_number', 'elements_Low_number', 'elements_VeryLow_number', 'elements_OutofStock_number']).innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';

    ids = ['elements_InUse_bis', 'elements_NotInUse_bis','elements_InUse', 'elements_NotInUse','elements_InUse_tris', 'elements_NotInUse_tris'];
            Dom.removeClass(ids, 'selected')
            Dom.addClass(el, 'selected')
            Dom.addClass(el.getAttribute('id2'), 'selected')
            Dom.addClass(el.getAttribute('id3'), 'selected')


    ids = ['elements_InUse_bis', 'elements_NotInUse_bis'];


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


var already_clicked_parts_elements_use_tris_click=false;
function change_parts_elements_use_tris(e, table_id) {
    var el = this
  
        if (already_clicked_parts_elements_use_tris_click)
        {
            already_clicked_parts_elements_use_tris_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_parts_elements_use_tris_dblclick(el, table_id)
        }
        else
        {
            already_clicked_parts_elements_use_tris_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_parts_elements_use_tris_click=false; // reset when it happens
                 change_parts_elements_use_tris_click(el, table_id)
            },300); // <-- dblclick tolerance here
        }
        return false;
}

function change_parts_elements_use_tris_click(el, table_id) {


    Dom.get(['elements_None_number', 'elements_Set_number', 'elements_Overdue_number']).innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';




    ids = ['elements_InUse_tris', 'elements_NotInUse_tris'];

    if (Dom.hasClass(el, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')
            Dom.removeClass(el.getAttribute('id2'), 'selected')
            Dom.removeClass(el.getAttribute('id3'), 'selected')


        }

    } else {
        Dom.addClass(el, 'selected')
        Dom.addClass(el.getAttribute('id2'), 'selected')
        Dom.addClass(el.getAttribute('id3'), 'selected')

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
    
   // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_parts_elements_use_tris_dblclick(el, table_id) {


    Dom.get(['elements_None_number', 'elements_Set_number', 'elements_Overdue_number']).innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';

    ids = ['elements_InUse_bis', 'elements_NotInUse_bis','elements_InUse_tris', 'elements_NotInUse_tris','elements_InUse', 'elements_NotInUse'];
            Dom.removeClass(ids, 'selected')
            Dom.addClass(el, 'selected')
            Dom.addClass(el.getAttribute('id2'), 'selected')
            Dom.addClass(el.getAttribute('id3'), 'selected')


    ids = ['elements_InUse_tris', 'elements_NotInUse_tris'];


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

var already_clicked_parts_elements_stock_state_click=false;
function change_parts_elements_stock_state(e, table_id) {
    var el = this
  
        if (already_clicked_parts_elements_stock_state_click)
        {
            already_clicked_parts_elements_stock_state_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_parts_elements_stock_state_dblclick(el, table_id)
        }
        else
        {
            already_clicked_parts_elements_stock_state_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_parts_elements_stock_state_click=false; // reset when it happens
                 change_parts_elements_stock_state_click(el, table_id)
            },300); // <-- dblclick tolerance here
        }
        return false;
}


function change_parts_elements_stock_state_click(el, table_id) {



    ids = ['elements_Error', 'elements_Excess', 'elements_Normal', 'elements_Low', 'elements_VeryLow', 'elements_OutofStock'];

    //ids = ['elements_Keeping', 'elements_NotKeeping', 'elements_Discontinued', 'elements_LastStock'];
    if (Dom.hasClass(el, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')


        }

    } else {
        Dom.addClass(el, 'selected')

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
function change_parts_elements_stock_state_dblclick(el, table_id) {



    ids = ['elements_Error', 'elements_Excess', 'elements_Normal', 'elements_Low', 'elements_VeryLow', 'elements_OutofStock'];
            Dom.removeClass(ids, 'selected')
            Dom.addClass(el, 'selected')

 


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

var already_clicked_parts_elements_state_click=false;
function change_parts_elements_state(e, table_id) {
    var el = this
  
        if (already_clicked_parts_elements_state_click)
        {
            already_clicked_parts_elements_state_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_parts_elements_state_dblclick(el, table_id)
        }
        else
        {
            already_clicked_parts_elements_state_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_parts_elements_state_click=false; // reset when it happens
                 change_parts_elements_state_click(el, table_id)
            },200); // <-- dblclick tolerance here
        }
        return false;
}


function change_parts_elements_state_click(el, table_id) {
    ids = ['elements_Keeping', 'elements_NotKeeping', 'elements_Discontinued', 'elements_LastStock'];
    if (Dom.hasClass(el, 'selected')) {
        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }
        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')
        }
    } else {
        Dom.addClass(el, 'selected')
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

function change_parts_elements_state_dblclick(el, table_id) {
    ids = ['elements_Keeping', 'elements_NotKeeping', 'elements_Discontinued', 'elements_LastStock'];
      Dom.removeClass(ids, 'selected')
      
        Dom.addClass(el, 'selected')
      

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








var already_clicked_parts_elements_next_shipment_click=false;
function change_parts_elements_next_shipment(e, table_id) {
    var el = this
  
        if (already_clicked_parts_elements_next_shipment_click)
        {
            already_clicked_parts_elements_next_shipment_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_parts_elements_next_shipment_dblclick(el, table_id)
        }
        else
        {
            already_clicked_parts_elements_next_shipment_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_parts_elements_next_shipment_click=false; // reset when it happens
                 change_parts_elements_next_shipment_click(el, table_id)
            },200); // <-- dblclick tolerance here
        }
        return false;
}


function change_parts_elements_next_shipment_click(el, table_id) {
    ids = ['elements_None', 'elements_Set', 'elements_Overdue'];
    if (Dom.hasClass(el, 'selected')) {
        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }
        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')
        }
    } else {
        Dom.addClass(el, 'selected')
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

function change_parts_elements_next_shipment_dblclick(el, table_id) {
    ids = ['elements_None', 'elements_Set', 'elements_Overdue'];
      Dom.removeClass(ids, 'selected')
      
        Dom.addClass(el, 'selected')
      

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




function change_parts_element_chooser(elements_type) {

    Dom.setStyle(['part_use_chooser', 'part_state_chooser', 'part_stock_state_chooser','part_next_shipment_chooser'], 'display', 'none')
    Dom.setStyle('part_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['parts_element_chooser_use', 'parts_element_chooser_state', 'parts_element_chooser_stock_state','parts_element_chooser_next_shipment'], 'selected')
    Dom.addClass('parts_element_chooser_' + elements_type, 'selected')
    dialog_change_parts_element_chooser.hide()


    var table = tables['table' + Dom.get('parts_table_id').value];
    var datasource = tables['dataSource' + Dom.get('parts_table_id').value];

    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_dialog_change_parts_element_chooser() {
    region1 = Dom.getRegion('part_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_parts_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_parts_element_chooser', pos);
    dialog_change_parts_element_chooser.show()
}

function get_part_elements_numbers() {
    var ar_file = 'ar_parts.php';
    var request = 'tipo=get_part_elements_numbers&parent=' + Dom.get('parent').value + '&parent_key=' + Dom.get('parent_key').value
   // alert(request)
    //Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

        //    alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    //alert('elements_'+ i +'_number '+'  '+Dom.get('elements_'+ i +'_number')+'  '+r.elements_numbers[i])
                    Dom.get('elements_' + i + '_number').innerHTML = r.elements_numbers[i]
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

function init_parts() {
    // get_part_elements_numbers()
    dialog_change_parts_element_chooser = new YAHOO.widget.Dialog("dialog_change_parts_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_parts_element_chooser.render();
    Event.addListener("part_element_chooser_menu_button", "click", show_dialog_change_parts_element_chooser);


    ids = ['elements_InUse', 'elements_NotInUse'];
    Event.addListener(ids, "click", change_parts_elements_use, Dom.get('parts_table_id').value);
    ids = ['elements_InUse_bis', 'elements_NotInUse_bis'];
    Event.addListener(ids, "click", change_parts_elements_use_bis, Dom.get('parts_table_id').value);
   ids = ['elements_InUse_tris', 'elements_NotInUse_tris'];
    Event.addListener(ids, "click", change_parts_elements_use_tris, Dom.get('parts_table_id').value);

    ids = ['elements_Keeping', 'elements_NotKeeping', 'elements_Discontinued', 'elements_LastStock'];
    Event.addListener(ids, "click", change_parts_elements_state, Dom.get('parts_table_id').value);

    ids = ['elements_Error', 'elements_Excess', 'elements_Normal', 'elements_Low', 'elements_VeryLow', 'elements_OutofStock'];
    Event.addListener(ids, "click", change_parts_elements_stock_state, Dom.get('parts_table_id').value);

  ids = ['elements_None', 'elements_Set', 'elements_Overdue'];
    Event.addListener(ids, "click", change_parts_elements_next_shipment, Dom.get('parts_table_id').value);

    var ids = ['parts_general', 'parts_stock', 'parts_sales', 'parts_forecast', 'parts_locations'];
    YAHOO.util.Event.addListener(ids, "click", change_parts_view, Dom.get('parts_table_id').value);

    YAHOO.util.Event.addListener(parts_period_ids, "click", change_parts_period, Dom.get('parts_table_id').value);
    ids = ['parts_avg_totals', 'parts_avg_month', 'parts_avg_week', "parts_avg_month_eff", "parts_avg_week_eff"];
    YAHOO.util.Event.addListener(ids, "click", change_parts_avg, Dom.get('parts_table_id').value);



    Event.addListener('clean_table_filter_show2', "click", show_filter, Dom.get('parts_table_id').value);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, Dom.get('parts_table_id').value);


    var oACDS2x = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2x.queryMatchContains = true;
    oACDS2x.table_id = Dom.get('parts_table_id').value;
    var oAutoComp2x = new YAHOO.widget.AutoComplete("f_input" + Dom.get('parts_table_id').value, "f_container" + Dom.get('parts_table_id').value, oACDS2x);
    oAutoComp2x.minQueryLength = 0;
    
}

YAHOO.util.Event.onDOMReady(init_parts);
