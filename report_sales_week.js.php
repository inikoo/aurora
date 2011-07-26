<?php
include_once('common.php');


?>
   var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;


var dialog_link;

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
	    				       {key:"date", label:"<?php echo _('Date')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
	    				       ,{key:"day_of_week", label:"<?php echo _('Day')?>", width:30,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ,{key:"id", label:"<?php echo _('Public ID')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				          
				       ,{key:"items", label:"<?php echo _('Items')?>", width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  		,{key:"shipping", label:"<?php echo _('Shipping')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  		,{key:"net", label:"<?php echo _('Total Net')?>", width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ,{key:"state", label:"<?php echo _('Status')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=invoices&tableid=1&saveto=report_sales_week");
	     this.dataSource1.table_id=tableid;
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
			 "id","day_of_week","net","shipping","items","tax",
			 "state",
			 "customer",
			 "date",
			 "date",
			 "total_amount","orders","dns"
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['invoices']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['orders']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['invoices']['f_value']?>'};


	
	};
    });



function submit_report(){

date=Dom.get('date').value;
if(date==''){
return;
}

location.href='report_sales_week.php?store='+Dom.get('store_key').value+'&date='+date;

}


function last_week(){
location.href='report_sales_week.php?store='+Dom.get('store_key').value+'&tipo=last_week';

}

function this_week(){
location.href='report_sales_week.php?store='+Dom.get('store_key').value+'&tipo=this_week';

}

 function init(){

dialog_link = new YAHOO.widget.Dialog("dialog_other_date", {context:["other_date","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_link.render();

Event.addListener("other_date", "click", dialog_link.show,dialog_link , true);

YAHOO.util.Event.addListener('submit_report', "click",submit_report);

Event.addListener("last_week", "click", last_week , true);
Event.addListener("this_week", "click", this_week , true);
YAHOO.util.Event.addListener('export_csv1', "click",download_csv,'report_sales_week_invoices');

 
}

YAHOO.util.Event.onDOMReady(init);
