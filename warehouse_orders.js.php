<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');


?>

var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;
var assign_picker_dialog;
var pick_it_dialog;
var pick_assigned_dialog;

var assign_packer_dialog;
var pack_it_dialog;
var pack_assigned_dialog;



function select_staff_from_list(oArgs){
	
//alert(Dom.get('staff_list_parent_dialog').value);
var staff_alias=tables.table2.getRecord(oArgs.target).getData('code');
var staff_key=tables.table2.getRecord(oArgs.target).getData('key');
//`alert(staff_alias + ':' + staff_key )




switch(Dom.get('staff_list_parent_dialog').value){
case 'pick_it':
	Dom.get('pick_it_Staff_Name').value=staff_alias;
	Dom.get('pick_it_staff_key').value=staff_key;

	Dom.setStyle('pick_it_pin_tr','visibility','visible');
	Dom.get("pick_it_pin_alias").innerHTML=staff_alias;
	Dom.get('pick_it_password').focus();
	break;
case 'pack_it':
	Dom.get('pack_it_Staff_Name').value=staff_alias;
	Dom.get('pack_it_staff_key').value=staff_key;

	Dom.setStyle('pack_it_pin_tr','visibility','visible');
	Dom.get("pack_it_pin_alias").innerHTML=staff_alias;
	Dom.get('pack_it_password').focus();
	break;
case 'assign_picker':
	Dom.get('Assign_Picker_Staff_Name').value=staff_alias;
	Dom.get('assign_picker_staff_key').value=staff_key;
	Dom.get('assign_picker_sup_password').focus();
	break;
case 'assign_packer':
	Dom.get('Assign_packer_Staff_Name').value=staff_alias;
	Dom.get('assign_packer_staff_key').value=staff_key;
	Dom.get('assign_packer_sup_password').focus();
	break;

}






dialog_other_staff.hide();
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   	 {key:"state", label:"<?php echo _('State')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				   {key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				  
				  {key:"points", label:"<?php echo _('Size')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				    //   {key:"weight", label:"<?php echo _('Weight')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      // {key:"picks", label:"<?php echo _('Picks')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      {key:"operations", label:"<?php echo _('Actions')?>", width:350,hidden:(Dom.get('method').value=='Inikoo'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"see_link", label:"",sortable:false,hidden:(Dom.get('method').value!='Inikoo'?false:true),className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				];
		//alert("ar_edit_orders.php?tipo=ready_to_pick_orders");
		request="ar_edit_orders.php?tipo=warehouse_orders&sf=0&parent=warehouse&parent_key="+Dom.get('warehouse_key').value
	   
	    this.dataSource0 = new YAHOO.util.DataSource(request);
		//alert("ar_edit_orders.php?tipo=ready_to_pick_orders");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id","public_id",
			 "weight","picks",
			 "customer",
			 "date","picker","packer","status","operations","see_link","points","state"
			
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['warehouse_orders']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['warehouse_orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['warehouse_orders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['orders']['warehouse_orders']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['warehouse_orders']['f_value']?>'};

	       this.table0.table_id=tableid;
     //this.table0.subscribe("renderEvent", myrenderEvent);
	    



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
    
    
    function get_warehouse_orders_numbers(from, to) {
        var ar_file = 'ar_orders.php';
        var request = 'tipo=number_warehouse_orders_in_interval&parent=warehouse&parent_key=' + Dom.get('warehouse_key').value + '&from=' + from + '&to=' + to;
        //  alert(ar_file+'?'+request)
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {

                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    for (i in r.elements_numbers) {
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


var already_clicked_elements_click = false
function change_elements() {
el=this;
var elements_type='';
    if (already_clicked_elements_click) {
        already_clicked_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_elements_dblclick(el, elements_type)
    } else {
        already_clicked_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_elements_click = false; // reset when it happens
            change_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_elements_click(el,elements_type) {

 ids = ['elements_ready_to_ship', 'elements_done', 'elements_picking_and_packing', 'elements_ready_to_restock', 'elements_ready_to_pack', 'elements_ready_to_pick'];


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

    table_id = 0;
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

function change_elements_dblclick(el,elements_type) {

 ids = ['elements_ready_to_ship', 'elements_done', 'elements_picking_and_packing', 'elements_ready_to_restock', 'elements_ready_to_pack', 'elements_ready_to_pick'];


    
         Dom.removeClass(ids, 'selected')

     Dom.addClass(el, 'selected')

    table_id = 0;
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


function init() {



get_warehouse_orders_numbers('','')

    init_search('orders_warehouse');
    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);


    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;



    assign_picker_dialog = new YAHOO.widget.Dialog("assign_picker_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    })
    assign_picker_dialog.render();
    pick_assigned_dialog = new YAHOO.widget.Dialog("pick_assigned_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    pick_assigned_dialog.render();
    pick_it_dialog = new YAHOO.widget.Dialog("pick_it_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    pick_it_dialog.render();

    assign_packer_dialog = new YAHOO.widget.Dialog("assign_packer_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    assign_packer_dialog.render();
    pack_assigned_dialog = new YAHOO.widget.Dialog("pack_assigned_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    pack_assigned_dialog.render();
    pack_it_dialog = new YAHOO.widget.Dialog("pack_it_dialog", {
        visible: false,
        close: false,
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


 ids = ['elements_ready_to_ship', 'elements_done', 'elements_picking_and_packing', 'elements_ready_to_restock', 'elements_ready_to_pack', 'elements_ready_to_pick'];
     Event.addListener(ids, "click", change_elements);


    //Event.addListener("other_staff", "click", show_other_staff);
}

YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
YAHOO.util.Event.onContentReady("rppmenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
    YAHOO.util.Event.addListener("rtext_rpp0", "click", oMenu.show, null, oMenu);

});
