function show_dialog_change_offer_element_chooser() {
    region1 = Dom.getRegion('offer_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_offer_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_offer_element_chooser', pos);
    dialog_change_offer_element_chooser.show()
}

function change_offers_element_chooser(elements_type) {

    Dom.setStyle(['offer_section_chooser', 'offer_flags_chooser', 'offer_state_chooser'], 'display', 'none')
    Dom.setStyle('offer_' + elements_type + '_chooser', 'display', '')
    Dom.removeClass(['offers_element_chooser_section', 'offers_element_chooser_flags', 'offers_element_chooser_state', ], 'selected')
    Dom.addClass('offers_element_chooser_' + elements_type, 'selected')
    dialog_change_offer_element_chooser.hide()

    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

var alreadyclicked_campaign = false;

function change_elements_campaign(e, data) {
    var el = this

    if (alreadyclicked_campaign) {
        alreadyclicked_campaign = false; // reset
        clearTimeout(alreadyclicked_campaignTimeout); // prevent this from happening
        change_elements_campaign_dblclick(el, data)
    } else {
        alreadyclicked_campaign = true;
        alreadyclicked_campaignTimeout = setTimeout(function() {
            alreadyclicked_campaign = false; // reset when it happens
            change_elements_campaign_click(el, data)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}


function change_elements_campaign_click(o, data) {

    ids = ['campaign_elements_Waiting', 'campaign_elements_Active', 'campaign_elements_Suspended', 'campaign_elements_Finish']

    if (Dom.hasClass(o, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(o, 'selected')
        }

    } else {
        Dom.addClass(o, 'selected')

    }

    table_id = data.table_id;
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

function change_elements_campaign_dblclick(o, data) {


    ids = ['campaign_elements_Waiting', 'campaign_elements_Active', 'campaign_elements_Suspended', 'campaign_elements_Finish']




    Dom.removeClass(ids, 'selected')
    Dom.addClass(o, 'selected')

    table_id = data.table_id;
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


var alreadyclicked_deal_component = false;

function change_elements_deal_component(e, data) {
    var el = this

    if (alreadyclicked_deal_component) {
        alreadyclicked_deal_component = false; // reset
        clearTimeout(alreadyclicked_deal_componentTimeout); // prevent this from happening
        change_elements_deal_component_dblclick(el, data)
    } else {
        alreadyclicked_deal_component = true;
        alreadyclicked_deal_componentTimeout = setTimeout(function() {
            alreadyclicked_deal_component = false; // reset when it happens
            change_elements_deal_component_click(el, data)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}


function change_elements_deal_component_click(o, data) {

    ids = ['deal_component_status_elements_Waiting', 'deal_component_status_elements_Active', 'deal_component_status_elements_Suspended', 'deal_component_status_elements_Finish']

    if (Dom.hasClass(o, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(o, 'selected')
        }

    } else {
        Dom.addClass(o, 'selected')

    }

    table_id = data.table_id;
   
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
//alert(request)

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_elements_deal_component_dblclick(o, data) {


    ids = ['deal_component_status_elements_Waiting', 'deal_component_status_elements_Active', 'deal_component_status_elements_Suspended', 'deal_component_status_elements_Finish']




    Dom.removeClass(ids, 'selected')
    Dom.addClass(o, 'selected')

    table_id = data.table_id;
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



var alreadyclicked_offer = false;

function change_elements_offer(e, data) {
    var el = this

    if (alreadyclicked_offer) {
        alreadyclicked_offer = false; // reset
        clearTimeout(alreadyclicked_offerTimeout); // prevent this from happening
        change_elements_offer_dblclick(el, data)
    } else {
        alreadyclicked_offer = true;
        alreadyclicked_offerTimeout = setTimeout(function() {
            alreadyclicked_offer = false; // reset when it happens
            change_elements_offer_click(el, data)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}


function change_elements_offer_click(o, data) {

    if (data.elements_type == 'status') ids = ['offer_status_elements_Waiting', 'offer_status_elements_Active', 'offer_status_elements_Suspended', 'offer_status_elements_Finish']

    if (Dom.hasClass(o, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(o, 'selected')
        }

    } else {
        Dom.addClass(o, 'selected')

    }

    table_id = data.table_id;
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

function change_elements_offer_dblclick(o, data) {


    if (data.elements_type == 'status') ids = ['offer_status_elements_Waiting', 'offer_status_elements_Active', 'offer_status_elements_Suspended', 'offer_status_elements_Finish']

    Dom.removeClass(ids, 'selected')
    Dom.addClass(o, 'selected')

    table_id = data.table_id;
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



function get_offers_elements_numbers() {
    var ar_file = 'ar_deals.php';
    var request = 'tipo=get_offer_elements_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value

    //Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                for (i in r.elements_numbers) {


                    for (j in r.elements_numbers[i]) {

                        if (Dom.get('offer_' + i + '_elements_' + j + '_number') != undefined) Dom.get('offer_' + i + '_elements_' + j + '_number').innerHTML = r.elements_numbers[i][j]
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


function get_campaign_elements_numbers() {
    var ar_file = 'ar_deals.php';
    var request = 'tipo=get_campaign_elements_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    if (Dom.get('campaign_elements_' + i + '_number') != undefined) Dom.get('campaign_elements_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}

function get_deal_component_elements_numbers() {
    var ar_file = 'ar_deals.php';
    var request = 'tipo=get_deal_component_elements_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value
   
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
           
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    if (Dom.get('deal_component_status_elements_' + i + '_number') != undefined) Dom.get('deal_component_status_elements_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}


function offers_myrenderEvent() {


    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }

    get_offers_elements_numbers()

}

function campaigns_myrenderEvent() {

    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }

    get_campaign_elements_numbers()

}
