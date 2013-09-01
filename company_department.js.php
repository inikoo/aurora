<?php
include_once('common.php')?>

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; 
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"id", label:"<?php echo _('Id')?>",  width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				        ,{key:"alias", label:"<?php echo _('Alias')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Name')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"position", label:"<?php echo _('Position')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //  ,{key:"area", label:"<?php echo _('Area')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //  ,{key:"department", label:"<?php echo _('Department')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    this.dataSource0 = new YAHOO.util.DataSource("ar_staff.php?tipo=staff&sf=0&parent=department&parent_key="+Dom.get('company_department_key').value);
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
		
		fields: ["id","alias","name","area","department","position"]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['hr']['staff']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['hr']['staff']['order']?>",
									 dir: "<?php echo$_SESSION['state']['hr']['staff']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								);
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
		    
	    
	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['hr']['staff']['f_field']?>',value:'<?php echo$_SESSION['state']['hr']['staff']['f_value']?>'};



	    var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>",  width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Title')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       				       ,{key:"department", label:"<?php echo _('Department')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"employees", label:"<?php echo _('Employees')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ];
	    this.dataSource3 = new YAHOO.util.DataSource("ar_staff.php?tipo=company_positions&tableid=1");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
		
		
		fields: ["code","name","employees","department"]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['hr']['positions']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['hr']['positions']['order']?>",
									 dir: "<?php echo$_SESSION['state']['hr']['positions']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    
	    this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);
		    
	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['hr']['positions']['f_field']?>',value:'<?php echo$_SESSION['state']['hr']['positions']['f_value']?>'};


	
	};
    });




 function init(){
 var Dom   = YAHOO.util.Dom;


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 

 var ids=['all','staff','exstaff'];
 YAHOO.util.Event.addListener(ids, "click", change_view);
 



 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });


var change_view = function (e){

    new_view=this.id

    if(new_view!=view){
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=hr-view&value='+escape(new_view));
	this.className='selected';
	Dom.get(view).className='';
	
	view=new_view;
	
	
	var table=tables.table0;
	var datasource=tables.dataSource0;
	var request='&sf=0&view='+view;
	datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
    }
    

	}