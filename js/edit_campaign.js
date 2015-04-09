var Dom = YAHOO.util.Dom;

var validate_scope_data;
var validate_scope_metadata;


function change_block(e) {

    var ids = ["description", "state","deals"];
    var block_ids = ["d_description", "d_state","d_deals"];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('d_' + this.id, 'display', '');

    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=campaign-edit_block_view&value=' + this.id, {});

}


YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;

    tables = new function() {

  

        tableid = 2; 
        tableDivEL = "table" + tableid;
        var productsColumnDefs = [
        {
            key: "key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }, 
        {
            key: "state",
            label: "",
            width: 10,
            sortable: false
        },

        {
            key: "code",
            label: labels.Code,
            width: 110,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, 
        {
            key: "term_allowances_label",
            label: labels.Description,
            width: 340,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
            },
            
           {
            key: "edit_status",
            label: "",
            width: 150,
            sortable: true,
            className: "aleft",
            
            }  
            
        ];

        request = "ar_edit_deals.php?tipo=deals&parent=campaign&parent_key=" + Dom.get('campaign_key').value + '$sf=0&tableid=' + tableid;
     //   alert(request)
        this.dataSource2 = new YAHOO.util.DataSource(request);
        this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource2.connXhrMode = "queueRequests";
        this.dataSource2.responseSchema = {
            resultsList: "resultset.data",
            metaFields: {
                rowsPerPage: "resultset.records_perpage",
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records"

            },

            fields: [ "key", "state", "code", "term_allowances_label","edit_status"]
        };


        this.table2 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs, this.dataSource2, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.edit_offers.nr,
                containers: 'paginator1',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state.edit_offers.order,
                dir: state.edit_offers.order_dir
            },
            dynamicData: true

        }

        );

        this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table2.request = request;
        this.table2.table_id = tableid;
        this.table2.subscribe("renderEvent", myrenderEvent);
        this.table2.getDataSource().sendRequest(null, {
            success: function(request, response, payload) {
                if (response.results.length == 0) {
                    //   get_part_elements_numbers()
                } else {
                    // this.onDataReturnInitializeTable(request, response, payload);
                }
            },
            scope: this.table2,
            argument: this.table2.getState()
        });
        this.table2.filter = {
            key: state.edit_offers.f_field,
            value: state.edit_offers.f_value
        };
    };
});



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


    };
});

function date_changed() {

    if (this.id == 'v_calpop1') {

        //Dom.get('state_from').value='Date';
       //validate_scope_data.campaign_state.state_from.changed=true
       validate_general('campaign_state', 'from', this.value);


   } else if (this.id == 'v_calpop2') {
    //Dom.get('state_to').value='Date';
    //validate_scope_data.campaign_state.state_to.changed=true
    validate_general('campaign_state', 'to', this.value);


}
}

function handleSelect(type, args, obj) {

    var dates = args[0];
    var date = dates[0];
    var year = date[0],
    month = date[1],
    day = date[2];


    if (month < 10) month = '0' + month;
    if (day < 10) day = '0' + day;
    var txtDate1 = document.getElementById("v_calpop" + this.id);
    txtDate1.value = day + "-" + month + "-" + year;
    this.hide();

    if (this.id == 1) {
      //Dom.get('state_from').value='Date';
      //validate_scope_data.campaign_state.state_from.changed=true
      validate_general('campaign_state', 'from', txtDate1.value);
      
  } else if (this.id == 2) {
    //Dom.get('state_to').value='Date';
    //validate_scope_data.campaign_state.state_to.changed=true
    validate_general('campaign_state', 'to', txtDate1.value);

}
}


function validate_campaign_code(query) {

    validate_general('campaign_description', 'code', unescape(query));
}

function validate_campaign_name(query) {

    validate_general('campaign_description', 'name', unescape(query));
}

function validate_campaign_description(query) {
    validate_general('campaign_description', 'description', unescape(query));
}

function save_description() {
    save_edit_general('campaign_description');
}

function reset_description() {
    reset_edit_general('campaign_description');
}

function save_state() {
    save_edit_general_bulk('campaign_state');
}

function reset_state() {
    reset_edit_general('campaign_state');
}

function cancel_delete_campaign() {
    dialog_delete_campaign.hide();
}


function save_delete_campaign() {

    var request = 'ar_edit_deals.php?tipo=delete_campaign&id=' + Dom.get('campaign_key').value
    Dom.setStyle('deleting', 'display', '');
    Dom.setStyle('delete_campaign_buttons', 'display', 'none');

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
//alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'marketing.php?store=' + Dom.get('store_key').value;
            } else {
                Dom.setStyle('deleting', 'display', 'none');
                Dom.setStyle('delete_campaign_buttons', 'display', '');
                Dom.get('delete_campaign_msg').innerHTML = r.msg
            }
        }
    });
}

function show_dialog_delete_campaign() {

    region1 = Dom.getRegion('delete_campaign');
    region2 = Dom.getRegion('dialog_delete_campaign');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_delete_campaign', pos);
    dialog_delete_campaign.show();
}

function finish(){
 Dom.removeClass(['finish','permanent'],'selected')
 Dom.addClass('finish','selected')
 Dom.setStyle(['v_calpop2','calpop2'],'display','none')
 Dom.setStyle(['change_valid_to'],'display','')


 Dom.get('state_to').value='Finish'


 validate_scope_data.campaign_state.state_to.changed=true

validate_scope('campaign_state');


}

function change_valid_to(){
 Dom.removeClass(['finish','permanent'],'selected')
 Dom.setStyle(['v_calpop2','calpop2'],'display','')
 Dom.setStyle(['change_valid_to'],'display','none')


 validate_scope_data.campaign_state.to.reqired=true

 validate_scope('campaign_state');

}

function set_as_permament(){


 Dom.removeClass(['finish','permanent'],'selected')
 Dom.addClass('permanent','selected')
 Dom.setStyle(['v_calpop2','calpop2'],'display','none')
 Dom.setStyle(['change_valid_to'],'display','')
 Dom.get('state_to').value='Permanent';

 if(Dom.get('state_to').getAttribute('ovalue')=='Permanent'){
    validate_scope_data.campaign_state.state_to.changed=false
    validate_scope_data.campaign_state.to.changed=false
    Dom.get('v_calpop2').value='';

}else{
 validate_scope_data.campaign_state.state_to.changed=true
}
validate_scope('campaign_state');


}

function start_now(){


 //Dom.removeClass(['finish','permanent'],'selected')
 Dom.addClass('start_now','selected')
 Dom.setStyle(['v_calpop1','calpop1'],'display','none')
// Dom.setStyle(['change_valid_to'],'display','')
 Dom.get('state_from').value='Start';

 if(Dom.get('state_from').getAttribute('ovalue')=='Start'){
    validate_scope_data.campaign_state.state_from.changed=false
    validate_scope_data.state_from.to.changed=false
    Dom.get('v_calpop1').value='';

}else{
 validate_scope_data.campaign_state.state_from.changed=true
}
validate_scope('campaign_state');


}


function post_save_actions(r){
	
	if(r.key=='code'){
		Dom.get('title_deal_code_bis').innerHTML=r.newvalue
				Dom.get('title_deal_code').innerHTML=r.newvalue

	}

}

function post_item_updated_actions(branch, r){
	location.reload(); 
}

function new_deal() {
    location.href = "new_deal.php?parent=campaign&parent_key=" + Dom.get('campaign_key').value;
}


function edit_deal_state(deal_key, state) {
    var request = 'ar_edit_deals.php?tipo=update_deal_status&value=' + state + '&deal_key=' + deal_key

    //alert(request)  
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {

            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

				Dom.get('deal_state_edit_'+r.key).innerHTML=r.button_edit_status
				Dom.get('deal_state_'+r.key).innerHTML=r.status_icon

            } else {

            }
           

        }
    });
}







function init() {

    session_data=YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels=session_data.label;

    validate_scope_data = {

        'campaign_state':{
            'state_from': {
                'changed': false,
                'validated': true,
                'required': false,
                'name': 'state_from',
                'validation': false,
                'ar': false
            },
            'state_to': {
                'changed': false,
                'validated': true,
                'required': true,
                'name': 'state_to',
                'validation': false,
                'ar': false
            },

            'from': {
                'changed': false,
                'validated': true,
                'required': (Dom.get('campaign_status').value=='Waiting'?false:true),
                'group': 1,
                'type': 'item',
                'name': 'v_calpop1',
                'ar': false,
                'dbname': 'Deal Campaign Valid From',
                'validation': [{'regexp': "\\d{2}-\\d{2}-\\d{4}",'invalid_msg': labels.Invalid_date}]
            },
            'to': {
                'changed': false,
                'validated': true,
                'required': (Dom.get('campaign_valid_to').value==''?false:true),
                'group': 1,
                'type': 'item',
                'name': 'v_calpop2',
                'ar': false,
                'dbname': 'Deal Campaign Valid To',
                'validation': [{'regexp': "\\d{2}-\\d{2}-\\d{4}",'invalid_msg': labels.Invalid_date}]
            }
        },



        'campaign_description': {
            'description': {
                'changed': false,
                'validated': true,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'campaign_description',
                'dbname': 'Deal Campaign Description',
                'ar': false,
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_description
                }]
            },
            'name': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_name',
                'dbname': 'Deal Campaign Name',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_name
                }]
            },
            'code': {
                'ar': 'find',
                'ar_request': 'ar_deals.php?tipo=is_campaign_code_in_store&store_key=' + Dom.get('store_key').value + '&query=',
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_code',
                'dbname': 'Deal Campaign Code',
                'validation': false
            }
        }
    };

    validate_scope_metadata = {
        'campaign_description': {
            'type': 'edit',
            'ar_file': 'ar_edit_deals.php',
            'key_name': 'campaign_key',
            'key': Dom.get('campaign_key').value
        },
        'campaign_state': {
            'type': 'edit',
            'ar_file': 'ar_edit_deals.php',
            'key_name': 'campaign_key',
            'key': Dom.get('campaign_key').value
        }

    };


    var campaign_code_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_code);
    campaign_code_oACDS.queryMatchContains = true;
    var campaign_code_oAutoComp = new YAHOO.widget.AutoComplete("campaign_code", "campaign_code_Container", campaign_code_oACDS);
    campaign_code_oAutoComp.minQueryLength = 0;
    campaign_code_oAutoComp.queryDelay = 0.1;

    var campaign_name_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_name);
    campaign_name_oACDS.queryMatchContains = true;
    var campaign_name_oAutoComp = new YAHOO.widget.AutoComplete("campaign_name", "campaign_name_Container", campaign_name_oACDS);
    campaign_name_oAutoComp.minQueryLength = 0;
    campaign_name_oAutoComp.queryDelay = 0.1;


    var campaign_description_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_description);
    campaign_description_oACDS.queryMatchContains = true;
    var campaign_description_oAutoComp = new YAHOO.widget.AutoComplete("campaign_description", "campaign_description_Container", campaign_description_oACDS);
    campaign_description_oAutoComp.minQueryLength = 0;
    campaign_description_oAutoComp.queryDelay = 0.1;




    init_search('marketing_store');

    var ids = ["description", "state","deals"];
    Event.addListener(ids, "click", change_block);

    Event.addListener('save_edit_campaign_description', "click", save_description);
    Event.addListener('reset_edit_campaign_description', "click", reset_description);
    Event.addListener('save_edit_campaign_state', "click", save_state);
    Event.addListener('reset_edit_campaign_state', "click", reset_state);




    Event.addListener('finish', "click", finish);
    Event.addListener('change_valid_to', "click", change_valid_to);
    Event.addListener('permanent', "click", set_as_permament);
    Event.addListener('start_now', "click", start_now);


    dialog_delete_campaign = new YAHOO.widget.Dialog("dialog_delete_campaign", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_delete_campaign.render();
    Event.addListener("delete_campaign", "click", show_dialog_delete_campaign);
    YAHOO.util.Event.addListener('cancel_delete_campaign', "click", cancel_delete_campaign);
    YAHOO.util.Event.addListener('save_delete_campaign', "click", save_delete_campaign);


    cal1 = new YAHOO.widget.Calendar("calpop1", "campaign_from_Container", {
        title: "Start:",
        mindate: new Date(),
        close: true
    });
    cal1.update = updateCal;
    cal1.id = '1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true);

    YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);

    cal2 = new YAHOO.widget.Calendar("calpop2", "campaign_to_Container", {
        title: "Until:",
        mindate: new Date(),
        close: true
    });
    cal2.update = updateCal;
    cal2.id = '2';
    cal2.render();
    cal2.update();
    cal2.selectEvent.subscribe(handleSelect, cal2, true);

    YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
    Event.addListener(['v_calpop1', 'v_calpop2'], "keyup", date_changed);


  

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;


    YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

}

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("rppmenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
        trigger: "rtext_rpp2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

