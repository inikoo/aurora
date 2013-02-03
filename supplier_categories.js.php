<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikooo
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;



function change_block(){
ids=['subcategories','history'];
block_ids=['block_subcategories','block_history'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier_categories-root_block_view&value='+this.id ,{});
}


function update_supplier_category_history_elements() {

    var ar_file = 'ar_history.php';
    var request = 'tipo=get_supplier_category_history_elements&parent=warehouse&parent_key=' + Dom.get('warehouse_key').value;
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



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				
				    {key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"label", label:"<?php echo _('Label')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"children", label:"<?php echo _('Subcategories')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"subjects", label:"<?php echo _('Parts')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					,{key:"percentage_assigned", label:"<?php echo _('Assigned')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				
				     ];
request="ar_categories.php?tipo=main_categories&parent=supplier_categories&tableid=1&parent_key=0";
//alert(request)
	    this.dataSource1 = new YAHOO.util.DataSource(request);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			"code","label","children","subjects","percentage_assigned"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['supplier_categories']['main_categories']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_categories']['main_categories']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_categories']['main_categories']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);

	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['main_categories']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['main_categories']['f_value']?>'};

		

 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
				       ];
		request="ar_history.php?tipo=supplier_categories&parent=none&parent_key=0&tableid=2";
		
	    this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		  
		 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'handle','date','tipo','objeto','details','note','time'
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['supplier_categories']['history']['nr']?>,containers : 'paginator2', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['supplier_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['supplier_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

		this.table2.table_id=tableid;
     	this.table2.subscribe("renderEvent", myrenderEvent);

		    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['history']['f_value']?>'};







	};
    });




function change_history_elements(e, table_id) {
    ids = ['elements_Changes', 'elements_Assign'];
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




 function init() {
     ids = ['subcategories', 'history'];
     Event.addListener(ids, "click", change_block);


     init_search('suppliers');


     var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS1.queryMatchContains = true;
     oACDS1.table_id = 1;

     var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
     oAutoComp1.minQueryLength = 0;

     var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS2.queryMatchContains = true;
     oACDS2.table_id = 2;

     var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
     oAutoComp2.minQueryLength = 0;


     Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
     Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
     Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
     Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);



     ids = ['elements_Changes', 'elements_Assign'];
     Event.addListener(ids, "click", change_history_elements, 2);


 }

 YAHOO.util.Event.onDOMReady(init);




 YAHOO.util.Event.onContentReady("rppmenu1", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
         trigger: "rtext_rpp1"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });

 YAHOO.util.Event.onContentReady("filtermenu1", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
         trigger: "filter_name1"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);

 });
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


