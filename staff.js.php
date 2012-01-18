<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_note;


function change_block(){
ids=['history','details', 'working_hours'];
block_ids=['block_working_hours','block_history','block_details'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');

Dom.removeClass(ids,'selected');

Dom.addClass(this,'selected');
//alert('ar_sessions.php?tipo=update&keys=customer-view&value='+this.id)
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=staff-view&value='+this.id ,{});
}


 
YAHOO.util.Event.addListener(window, "load", function() {
	    tables = new function() {
		    
		    var tableid=0; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>",className:"aleft",width:150,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"objeto", label:"<?php echo _('Details')?>", className:"aleft",width:270,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				//    ,{key:"description", label:"<?php echo _('Description')?>",className:"aleft",width:170,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      ,{key:"note", label:"<?php echo _('Notes')?>",className:"aleft",width:150}
					   ];
		
		    this.dataSource0  = new YAHOO.util.DataSource("ar_history.php?tipo=staff_history&tid="+tableid+"&parent_key="+Dom.get('staff_key').value);
		    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
		//fields: ["date","subject","description","location" ]};
//fields: ["note","date","time","description","objeto" ]};
fields: ["note","date","time","objeto" ]};
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['staff_history']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['staff_history']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['staff_history']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table0.filter={key:'<?php echo$_SESSION['state']['staff_history']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['staff_history']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)


// ------------------------------------------------working hours table starts here --------------------------------
   var tableid=1; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"id", label:"<?php echo _('Staff Key')?>",className:"aleft",hidden:true,width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"day", label:"<?php echo _('Day')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   ,{key:"start_time", label:"<?php echo _('Start Time')?>",className:"aleft",width:160}
				      ,{key:"finish_time", label:"<?php echo _('Finish Time')?>",className:"aleft",width:160}
				      ,{key:"total_breaks_time", label:"<?php echo _('Total Breaks Time')?>", className:"aleft",width:160,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"hours_worked", label:"<?php echo _('Hours Worked')?>", className:"aleft",width:180,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   ];
		
		
		request="ar_staff.php?tipo=staff_working_hours&tid="+tableid+"&id="+Dom.get('staff_key').value;
		
		    this.dataSource1  = new YAHOO.util.DataSource(request);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.table_id=tableid;
	    this.dataSource1.responseSchema = {
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
		fields: ["id","finish_time","total_breaks_time","start_time","day","hours_worked" ]};
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['staff_history']['working_hours']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['staff_history']['working_hours']['order']?>",
									 dir: "<?php echo$_SESSION['state']['staff_history']['working_hours']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table1.filter={key:'<?php echo$_SESSION['state']['staff_history']['working_hours']['f_field']?>',value:'<?php echo$_SESSION['state']['staff_history']['working_hours']['f_value']?>'};

	    


	    





   
	
	};
    });


function init(){







 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 

ids=['history','details', 'working_hours'];
 YAHOO.util.Event.addListener(ids, "click", change_block);
 



 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });







