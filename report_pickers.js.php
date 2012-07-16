<?php include_once('common.php');?>
var link="report_pp.php";
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    //START OF THE TABLE=========================================================================================================================
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       //     {key:"tipo", label:"<?php echo _('Type')?>", width:30,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       {key:"alias", label:"<?php echo _('Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				       ,{key:"orders", label:"<?php echo _('Orders')?>", width:65,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      				       ,{key:"p_orders", label:"", width:30,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"units", label:"<?php echo _('Units')?>", width:65,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      	,{key:"p_units", label:"", width:30,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ,{key:"weight", label:"<?php echo _('Weight')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     				       ,{key:"p_weight", label:"", width:40,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ,{key:"errors", label:"<?php echo _('Errors')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"epo", label:"<?php echo _('OwErr')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				       //    ,{key:"hours", label:"<?php echo _('Hours')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"uph", label:"<?php echo _('Units/h')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=pickers_report");
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
			 "tipo","alias","orders","units","weight","errors","hours","uph","epo","p_orders","p_units","p_weight"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,sortedBy : {
							    Key: "<?php echo $_SESSION['state']['report_pp']['pickers']['order']?>",
							     dir: "<?php echo $_SESSION['state']['report_pp']['pickers']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'',value:''};







    };
  });
