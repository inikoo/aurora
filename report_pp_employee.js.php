<?php include_once('common.php');?>




function change_block() {
     ids = ['overview', 'packed', 'picked'];
     block_ids = ['block_overview', 'block_packed', 'block_picked'];
     Dom.setStyle(block_ids, 'display', 'none');
     Dom.setStyle('block_' + this.id, 'display', '');
     Dom.removeClass(ids, 'selected');
     Dom.addClass(this, 'selected');
     YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=report_pp-employee_view&value=' + this.id, {});
 }


function post_change_period_actions(period, from, to) {

    request = '&from=' + from + '&to=' + to;

    table_id = 1
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  
    Dom.get('rtext1').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp1').innerHTML = '';
  



}
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    
	     var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"id", label:"<?php echo _('Number')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      	,{key:"type", label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      ,{key:"state", label:"<?php echo _('Status')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ];
	    //?tipo=customers&tid=0"
	   
	    request="ar_reports.php?tipo=picked_dns&parent=employee&parent_key="+Dom.get('employee_key').value+"&tableid="+tableid
	   
	    this.dataSource0 = new YAHOO.util.DataSource(request);
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
			 "id","date","customer","type","state","weight","parcels","items"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,sortedBy : {
							    Key: "<?php echo $_SESSION['state']['report_pp']['picked_dns']['order']?>",
							     dir: "<?php echo $_SESSION['state']['report_pp']['picked_dns']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'',value:''};



	       var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"id", label:"<?php echo _('Number')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      	,{key:"type", label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      ,{key:"state", label:"<?php echo _('Status')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ];
	    //?tipo=customers&tid=0"
	   
	    request="ar_reports.php?tipo=packed_dns&parent=employee&parent_key="+Dom.get('employee_key').value+"&tableid="+tableid
	   
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
			 "id","date","customer","type","state","weight","parcels","items"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,sortedBy : {
							    Key: "<?php echo $_SESSION['state']['report_pp']['packed_dns']['order']?>",
							     dir: "<?php echo $_SESSION['state']['report_pp']['packed_dns']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'',value:''};





    };
  });


function init() {

     ids = ['overview', 'packed', 'picked'];
     Event.addListener(ids, "click", change_block);
}

 YAHOO.util.Event.onDOMReady(init);