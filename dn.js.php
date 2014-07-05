<?php
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

var dialog_pick_it;
var dialog_pack_it;

var assign_picker_dialog;
var pick_it_dialog;
var pick_assigned_dialog;

var assign_packer_dialog;
var pack_it_dialog;
var pack_assigned_dialog;


YAHOO.util.Event.addListener(window, "load", function() {
   tables = new function() {


		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

		show_if_dispatched=(Dom.get('dn_state').value=='Dispatched'?false:true)

	    var OrdersColumnDefs = [
				     {key:"part", label:"<?php echo _('Part')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ,{key:"description", label:"<?php echo _('Description')?>",width:400,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     
				     ,{key:"quantity",label:"<?php echo _('Ordered')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"given",label:"<?php echo _('Given')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"picked",label:"<?php echo _('Picked')?>", hidden:!show_if_dispatched, width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"packed",label:"<?php echo _('Packed')?>", hidden:!show_if_dispatched, width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"dispatched",label:"<?php echo _('Dispatched')?>", hidden:show_if_dispatched,  width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"notes",label:"<?php echo _('Notes')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},action:'edit_object',object:'pending_transactions'}

				  
					 ];

	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_in_process_in_dn&tid=0");
	    this.OrdersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.OrdersDataSource.connXhrMode = "queueRequests";
	    this.OrdersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "part","description","quantity","picked","packed","notes","dispatched","given"
			 ]};
	    this.OrdersDataTable = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
								   this.OrdersDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	 
	    
	    this.OrdersDataTable.id=tableid;
	    this.OrdersDataTable.editmode=false;


var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Alias')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
//alert("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name','key'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_staff_from_list);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};	    
	};
    });


function undo_dispatch(){


        Dom.get('undo_dispatch_icon').src='art/loading.gif'
        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=delivery_note_undo_dispatch&dn_key=' + Dom.get('dn_key').value;
       // alert(ar_file+'?'+request)
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
            //  alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                  location.reload(); 
                } else {
                   
                }
            },
            failure: function(o) {
               // alert(o.statusText);

            },
            scope: this
        }, request

        );


}

function show_dn_details(){
Dom.setStyle('dn_details_panel','display','')
Dom.setStyle('show_dn_details','display','none')

}

function hide_dn_details(){
Dom.setStyle('dn_details_panel','display','none')
Dom.setStyle('show_dn_details','display','')
}


function init() {


    init_search('orders_store');


    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0;

    Event.addListener("pick_it_", "click", pick_it_);
    Event.addListener("process_dn_packing", "click", show_process_dn_packing_dialog);

    assign_picker_dialog = new YAHOO.widget.Dialog("assign_picker_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    assign_picker_dialog.render();

    dialog_pick_it = new YAHOO.widget.Dialog("dialog_pick_it", {
        context: ["pick_it_", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_pick_it.render();

    Event.addListener("close_dialog_pick_it", "click", dialog_pick_it.hide, dialog_pick_it, true);
    dialog_pack_it = new YAHOO.widget.Dialog("dialog_pack_it", {
        context: ["process_dn_packing", "tr", "br"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_pack_it.render();
    Event.addListener("close_dialog_pack_it", "click", dialog_pack_it.hide, dialog_pack_it, true);

    pick_assigned_dialog = new YAHOO.widget.Dialog("pick_assigned_dialog", {
        context: ["pack_it", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    pick_assigned_dialog.render();
    pick_it_dialog = new YAHOO.widget.Dialog("pick_it_dialog", {
        context: ["pack_it", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    pick_it_dialog.render();

    assign_packer_dialog = new YAHOO.widget.Dialog("assign_packer_dialog", {
        context: ["pack_it", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    assign_packer_dialog.render();
    pack_assigned_dialog = new YAHOO.widget.Dialog("pack_assigned_dialog", {
        context: ["pack_it", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    pack_assigned_dialog.render();
    pack_it_dialog = new YAHOO.widget.Dialog("pack_it_dialog", {
        context: ["process_dn_packing", "tr", "br"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    pack_it_dialog.render();
    dialog_other_staff = new YAHOO.widget.Dialog("dialog_other_staff", {
        context: ["other_staff", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_other_staff.render();

    Event.addListener("create_invoice", "click", create_invoice);


    Event.addListener("show_dn_details", "click", show_dn_details);
    Event.addListener("hide_dn_details", "click", hide_dn_details);

}

function create_invoice() {


    var dn_key = Dom.get('dn_key').value;


    var request = 'ar_edit_orders.php?tipo=create_invoice&dn_key=' + escape(dn_key);
    //  alert(request); //return;
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //		alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'invoice.php?id=' + r.invoice_key;

            } else {
                alert(r.msg)

            }
        }
    });

}

function pick_it_() {

    state = Dom.get('dn_state').value;
    if (Dom.get('dn_picker_key').value) {
        window.location = 'order_pick_aid.php?id=' + Dom.get('dn_key').value;
    } else {
        dialog_pick_it.show()
    }
}

function pick_it() {
    window.location = 'order_pick_aid.php?id=' + Dom.get('dn_key').value;

}

function show_process_dn_packing_dialog() {


    dialog_pack_it.show()

}

function pack_itx() {
    dialog_pack_it.hide()

    pack_it_dialog.show();
}

function pack_it(o, dn_key) {
    dialog_pack_it.hide()

    pack_it_dialog.show();
    Dom.get('pack_it_Staff_Name').focus();
    Dom.get('pack_it_dn_key').value = dn_key;
    Dom.get('staff_list_parent_dialog').value = 'pack_it';

}

YAHOO.util.Event.onDOMReady(init);
