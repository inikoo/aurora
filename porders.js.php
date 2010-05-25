<?php
include_once('common.php');

?>
var view='<?php echo$_SESSION['state']['orders']['view']?>'

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Order ID')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"last_date", label:"<?php echo _('Last Updated')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       //   {key:"date", label:"<?php echo _('Ordered at')?>", width:145,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				       {key:"customer",label:"<?php echo _('Customer')?>", width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"state", label:"<?php echo _('Status')?>", width:240,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"total_amount", label:"<?php echo _('Total')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      
					 ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=orders");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id",
			 "state",
			 "customer",
			 "date",
			 "last_date",
			 "total_amount"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['orders']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['table']['f_value']?>'};

	    
	};
    });




function init(){

    
var Dom   = YAHOO.util.Dom;


//  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
//  oACDS.queryMatchContains = true;
//  var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
//  oAutoComp.minQueryLength = 0; 

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 

 cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal2.update=updateCal;
 cal2.id=2;
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 
 cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id=1;
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 
 YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
 YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
	


 var change_interval = function(e){
     from=Dom.get("v_calpop1").value;
     to=Dom.get("v_calpop2").value;
     var table=tables.table0;
     var datasource=tables.dataSource0;
     var request='&sf=0&from=' +from+'&to='+to;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     
 }
 
 YAHOO.util.Event.addListener("submit_interval", "click", change_interval);


	var change_view = function (e){

	    new_view=this.id

	    if(new_view!=view){
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=orders-view&value='+escape(new_view));
		this.className='selected';
		Dom.get(view).className='';

		Dom.get('details_'+view).style.display='none';
		Dom.get('details_'+new_view).style.display='';
		
		
		view=new_view;


		var table=tables.table0;
		var datasource=tables.dataSource0;
		var request='&sf=0&view='+view;
		datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
	    }
	    

	}



	var ids=['all','invoices','in_process',"cancelled"]
	YAHOO.util.Event.addListener(ids, "click", change_view);


}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });



