var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


Event.addListener(window, "load", function() {
    tables = new function() {

        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [

            {
            key: "pid",
            label: "Product ID",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }

            , {
            key: "code",
            label: Dom.get('label_code').value,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "description",
            label: Dom.get('label_description').value,
            width: 530,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "quantity",
            label: Dom.get('label_quantity').value,
            width: 50,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'new_order'
        }
            //  ,{key:"gross",label:Dom.get('label_gross').value,  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
                    // ,{key:"discount",label:Dom.get('label_discount').value,  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
                    ,
        {
            key: "to_charge",
            label: Dom.get('label_net').value,
            width: 85,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }
        ];

        var request = "ar_orders.php?tipo=transactions&parent=order_in_process_by_customer&parent_key=" + Dom.get('order_key').value + "&tableid=0"
        // alert(request)
        this.dataSource0 = new YAHOO.util.DataSource(request);
        this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource0.connXhrMode = "queueRequests";
        this.dataSource0.responseSchema = {
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

            fields: [
                "code"
                , "description"
                , "quantity"
                , "discount"
                , "to_charge", "gross", "tariff_code", "created", "last_updated", "pid"
                // "promotion_id",
                            ]
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource0, {
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 500,
                containers: 'paginator0',
                pageReportTemplate: '(Page {currentPage} of {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: "code",
                dir: ""
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table0.subscribe("cellClickEvent", myonCellClick);

        this.table0.request = request;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);


        this.table0.filter = {
            key: '',
            value: ''
        };





    };
});

function cancel_order() {

Dom.setStyle('wait_cancel','display','')
Dom.setStyle(['cancel_order','close_cancel_order_dialog'],'display','none')


    Event.removeListener('cancel_order', "mouseout", hide_cancel_order_info);
    Dom.get('cancel_order_img').src = 'art/loading.gif';
    var value = encodeURIComponent('Cancelled by customer');
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=cancel&note=' + value + '&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //        alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.href = 'basket.php?cancelled=1';

            } else {
                alert('EC23' + r.msg)
                Dom.setStyle('wait_cancel','display','none')
Dom.setStyle(['cancel_order','close_cancel_order_dialog'],'display','')
            }
        },
        failure: function(o) {
            alert(o.statusText);

        },
        scope: this
    }, request

    );


}

function show_cancel_order_info() {
    Dom.setStyle('cancel_order_info', 'display', '')
}

function hide_cancel_order_info() {
    Dom.setStyle('cancel_order_info', 'display', 'none')

}


function show_edit_button(e, data) {

}

function hide_edit_button(e, data) {


}

function get_tax_info() {

    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_tax_info&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //  alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {



                Dom.get('tax_info').innerHTML = r.tax_info
                for (x in r.data) {

                    Dom.get(x).innerHTML = r.data[x];
                }


            } else {
                alert(r.state)
            }
        },
        failure: function(o) {


        },
        scope: this
    }, request

    );


}


function post_item_updated_actions(branch, r) {

    if (r.key == 'tax_number') {
        dialog_set_tax.hide();
        show_dialog_check_tax_number(r.newvalue);
        get_tax_info();


    }

}


function back_to_shop() {
    location.href = "page.php?id=" + Dom.get('last_basket_page_key').value

}

function validate_customer_tax_number(query) {
    validate_general('customer_quick', 'tax_number', unescape(query));
}


function close_quick_edit_tax_number() {
    dialog_set_tax.hide();
}

function save_quick_edit_tax_number() {
    save_edit_general_bulk('customer_quick');
}

function show_set_tax_number_dialog() {

    region1 = Dom.getRegion('set_tax_number');
    region2 = Dom.getRegion('dialog_set_tax');
    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_set_tax', pos);
    dialog_set_tax.show();

}

function show_cancel_order_dialog() {

    region1 = Dom.getRegion('show_cancel_order_dialog');
    region2 = Dom.getRegion('dialog_confirm_cancel');
    var pos = [region1.right - region2.width-5, region1.top]
    Dom.setXY('dialog_confirm_cancel', pos);
    dialog_confirm_cancel.show();

}



function close_cancel_order_dialog() {
    dialog_confirm_cancel.hide()
}

function init_basket() {



    validate_scope_data = {
        'customer_quick': {
            'tax_number': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'Customer_Tax_Number',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': 'Invalid Tax Number'
                }]
            }

        }
    };



    validate_scope_metadata = {
        'customer_quick': {
            'type': 'edit',
            'ar_file': 'ar_edit_contacts.php',
            'key_name': 'customer_key',
            'key': Dom.get('customer_key').value
        }
    };


    var customer_tax_number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_tax_number);
    customer_tax_number_oACDS.queryMatchContains = true;
    var customer_tax_number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Tax_Number", "Customer_Tax_Number_Container", customer_tax_number_oACDS);
    customer_tax_number_oAutoComp.minQueryLength = 0;
    customer_tax_number_oAutoComp.queryDelay = 0.1;


    Event.addListener('cancel_order', "click", cancel_order);
    Event.addListener('show_cancel_order_dialog', "click", show_cancel_order_dialog);
    Event.addListener('close_cancel_order_dialog', "click", close_cancel_order_dialog);

    Event.addListener('show_cancel_order_dialog', "mouseover", show_cancel_order_info);
    Event.addListener('show_cancel_order_dialog', "mouseout", hide_cancel_order_info);

    dialog_confirm_cancel = new YAHOO.widget.Dialog("dialog_confirm_cancel", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    dialog_confirm_cancel.render();


    dialog_set_tax = new YAHOO.widget.Dialog("dialog_set_tax", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    dialog_set_tax.render();

}

YAHOO.util.Event.onDOMReady(init_basket);
