<?php
include_once('common.php');

?>


var category_show_options=[{label:"<?php echo _('Yes')?>", value:"Yes"}, {label:"<?php echo _('No')?>", value:"No"}];
var category_show_name={'Yes':'Yes','No':'No'};

var dialog_delete_category_from_list;

function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);

    var recordIndex = datatable.getRecordIndex(record);

    switch (column.object) {
    case 'delete_category':


       // Dom.get('objective_time_limit').value = record.getData('temporal_formated_metadata');
        Dom.get('delete_from_list_category_key').value = record.getData('id');
 Dom.get('delete_from_list_category_code').innerHTML = record.getData('code');


	region1 = Dom.getRegion(target); 
    region2 = Dom.getRegion('dialog_delete_category_from_list'); 
	var pos =[region1.right-region2.width,region1.top]
	Dom.setXY('dialog_delete_category_from_list', pos);



       
     
        dialog_delete_category_from_list.show();

        break;


    }

}

YAHOO.util.Event.addListener(window, "load", function() {



    tables = new function() {
  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'subcategory' }
					,{key:"label", label:"<?php echo _('Label')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'subcategory' }
					,{key:"delete", label:"", width:100,sortable:false,className:"aleft",action:'dialog_delete',object:'delete_category'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				    ,{key:"branch_type",label:'',width:80,}
				     ];



	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_categories.php?tipo=edit_supplier_category_list&parent=none&parent_key=0");
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
			 'id','code','delete','delete_type','go','label','branch_type'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['supplier_categories']['edit_categories']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['supplier_categories']['edit_categories']['order']?>",
							     dir: "<?php echo$_SESSION['state']['supplier_categories']['edit_categories']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	  
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);


	    this.table0.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['edit_categories']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['edit_categories']['f_value']?>'};

		
 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
				       ];
		request="ar_history.php?tipo=supplier_categories&parent=none&parent_key=0&tableid=1";
	   
	   this.dataSource1 = new YAHOO.util.DataSource(request);

	   this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 "key"
			 ,"date"
			 ,'time','handle','note'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['supplier_categories']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['supplier_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['supplier_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['history']['f_value']?>'};



	};
    });




function show_history(){
Dom.setStyle(['show_history',''],'display','none')
Dom.setStyle(['hide_history','history_table'],'display','')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_categories-show_history&value=1', {});

}

function hide_history(){
Dom.setStyle(['show_history',''],'display','')
Dom.setStyle(['hide_history','history_table'],'display','none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_categories-show_history&value=0', {});

}


function cancel_new_category() {
    Dom.get('new_category_code').value = '';
    Dom.setStyle('new_category_no_name_msg', 'display', 'none')

    dialog_new_category.hide();

}

function show_new_category_dialog() {

    Dom.setStyle('category_form_chooser', 'display', '')
    Dom.setStyle(['custom_category_form', 'simple_category_form', 'new_category_save_buttons', 'new_category_save_buttons', 'new_category_show_options'], 'display', 'none')
    dialog_new_category.show();
    Dom.get("new_category_code").value='';
    Dom.get("new_category_label").value='';
    Dom.get("new_category_max_deep").value=2;
    
}


function post_create_actions() {
 var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function save_new_category() {

    var code = Dom.get("new_category_code").value;
    var label = Dom.get("new_category_label").value;

    var store_key = Dom.get("new_category_store_key").value;
    var warehouse_key = Dom.get("new_category_warehouse_key").value;

    var allow_other = Dom.get("new_category_allow_other").value;
    var multiplicity = Dom.get("new_category_multiplicity").value;
    var max_deep = Dom.get("new_category_max_deep").value;
    var show_registration = Dom.get('new_category_show_registration').value
    var show_profile = Dom.get('new_category_show_profile').value
    var show_ui = Dom.get('new_category_show_ui').value

    var subject = Dom.get("new_category_subject").value;

    if (code == '') {
        Dom.setStyle('new_category_no_code_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_no_code_msg', 'display', 'none')

    }

    if (label == '') {
        Dom.setStyle('new_category_no_label_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_no_label_msg', 'display', 'none')

    }

    if (!(!isNaN(parseInt(max_deep)) && (parseFloat(max_deep) == parseInt(max_deep))) || max_deep < 2) {
        Dom.setStyle('new_category_wrong_max_deep_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_wrong_max_deep_msg', 'display', 'none')

    }


    var ar_file = 'ar_edit_categories.php';
    var request = 'tipo=new_main_category&subject=' + subject + '&code=' + code + '&label=' + label + '&store_key=' + store_key + '&warehouse_key=' + warehouse_key + '&allow_other=' + allow_other + '&multiplicity=' + multiplicity + '&max_deep=' + max_deep + '&show_registration=' + show_registration + '&show_profile=' + show_profile + '&show_ui=' + show_ui;

   
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
update_branch_type_elements()
                post_create_actions()
                cancel_new_category()

            } else {
                Dom.setStyle('new_category_msg', 'display', '')

                Dom.get('new_category_msg_text').innerHTML = r.msg
            }

        },
        failure: function(o) {
            alert(o.statusText);

        },
        scope: this
    }, request

    );
}


YAHOO.util.Event.onContentReady("dialog_new_category", function() {
    dialog_new_category = new YAHOO.widget.Dialog("dialog_new_category", {
        context: ["new_category", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });

    dialog_new_category.render();

    Event.addListener("new_category", "click", show_new_category_dialog, true);
    Event.addListener("new_category_cancel", "click", cancel_new_category, true);
    Event.addListener("new_category_save", "click", save_new_category, true);
    
    
    
    

});

function set_allow_other(value) {
    Dom.removeClass(['set_allow_other_Yes', 'set_allow_other_No'], 'selected')
    Dom.addClass('set_allow_other_' + value, 'selected')
    Dom.get('new_category_allow_other').value = value
}

function set_multiplicity(value) {
    Dom.removeClass(['set_multiplicity_Yes', 'set_multiplicity_No'], 'selected')
    Dom.addClass('set_multiplicity_' + value, 'selected')
    Dom.get('new_category_multiplicity').value = value
}

function set_show_registration(value) {
    Dom.removeClass(['set_show_registration_Yes', 'set_show_registration_No'], 'selected')
    Dom.addClass('set_show_registration_' + value, 'selected')
    Dom.get('new_category_show_registration').value = value
}

function set_show_profile(value) {
    Dom.removeClass(['set_show_profile_Yes', 'set_show_profile_No'], 'selected')
    Dom.addClass('set_show_profile_' + value, 'selected')
    Dom.get('new_category_show_profile').value = value
}
function set_show_ui(value) {
    Dom.removeClass(['set_show_ui_Yes', 'set_show_ui_No'], 'selected')
    Dom.addClass('set_show_ui_' + value, 'selected')
    Dom.get('new_category_show_ui').value = value
}


function show_simple_category_form() {
    Dom.setStyle('category_form_chooser', 'display', 'none')
    Dom.setStyle(['simple_category_form', 'new_category_save_buttons','new_category_show_options'], 'display', '')

    Dom.get('new_category_code').focus();

}

function show_custom_category_form() {
    Dom.setStyle('category_form_chooser', 'display', 'none')
    Dom.setStyle(['custom_category_form', 'new_category_save_buttons', 'simple_category_form','new_category_show_options'], 'display', '')
    Dom.get('new_category_code').focus();

}






function change_category_elements(e, table_id) {
    ids = ['elements_Root', 'elements_Node', 'elements_Head'];
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

function change_history_elements(e, table_id) {
    ids = ['elements_Change', 'elements_Assign'];
   // alert("caca")
    
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
//alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function update_branch_type_elements() {

    var ar_file = 'ar_categories.php';
    var request = 'tipo=get_branch_type_elements&subject=' + Dom.get('category_subject').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            for (key in r.elements_number) {
                Dom.get('elements_' + key + '_number').innerHTML = r.elements_number[key]
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}

function update_supplier_category_history_elements() {

    var ar_file = 'ar_history.php';
    var request = 'tipo=get_supplier_category_history_elements&parent=none&parent_key=0';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            for (key in r.elements_number) {
                Dom.get('elements_' + key + '_number').innerHTML = r.elements_number[key]
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}


function save_delete_category_from_list(){
  var request = 'ar_edit_categories.php?tipo=delete_category&category_key=' + Dom.get('delete_from_list_category_key').value
    Dom.setStyle('deleting_from_list', 'display', '');
    Dom.setStyle('delete_category_buttons_from_list', 'display', 'none');

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
          
           var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    update_branch_type_elements()
          
dialog_delete_category_from_list.hide()

            } else {
                Dom.setStyle('deleting_from_list', 'display', 'none');
                Dom.setStyle('delete_category_buttons_from_list', 'display', '');
                Dom.get('delete_category_msg_from_list').innerHTML = r.msg
            }
        }
    });
}

function cancel_delete_category_from_list(){
dialog_delete_category_from_list.hide()

}


function init() {

    init_search('suppliers');
    ids = ['elements_Node', 'elements_Root', 'elements_Head'];
    Event.addListener(ids, "click", change_category_elements, 0);
    
     ids = ['elements_Change', 'elements_Assign'];
    Event.addListener(ids, "click", change_history_elements, 1);
    

 dialog_delete_category_from_list = new YAHOO.widget.Dialog("dialog_delete_category_from_list", {
       
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });

    dialog_delete_category_from_list.render();
    
    
        var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS0.queryMatchContains = true;
     oACDS0.table_id = 0;
     var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS0);
     oAutoComp0.minQueryLength = 0;
    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS1.queryMatchContains = true;
     oACDS1.table_id = 1;
     var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
     oAutoComp1.minQueryLength = 0;




     Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
     Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
     Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    


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
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});


YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu1", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});

