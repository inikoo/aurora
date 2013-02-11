<?php
include_once('common.php');
print "var umbral=".$_REQUEST['umbral'].";";
print "var year=".$_REQUEST['year'].";";

?>
   var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;


function change_plot(o){
  
    
    Dom.get('the_plot').src = 'plot.php?tipo='+o.id;
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-plot&value='+o.id);
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"<?php echo$customers_ids[0]?>",width:60,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				       ,{key:"location", label:"<?php echo _('Location')?>",sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"invoices", label:"<?php echo _('Invoices')?>",sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"net", label:"<?php echo _('Net')?>",sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
      ,{key:"tax1", label:"<?php echo _('IVA')?>",sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
      ,{key:"tax2", label:"<?php echo _('IE')?>",sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

,{key:"total", label:"<?php echo _('Total')?>",sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=ES_1&umbral="+umbral+'&year='+year);
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
			 'id',
			 'name',
			
			 'location',
			 'total','invoices','tax1','tax2','net'
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : 1000,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "total",
									 dir: "desc"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['customers']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });



function submit_report(){
var limite=Dom.get('limite').value;
var year=Dom.get('year').value;
location.href='report_tax_ES1.php?umbral='+limite+'&year='+year;

}


 function init(){


YAHOO.util.Event.addListener('submit_report', "click",submit_report);


 
}

YAHOO.util.Event.onDOMReady(init);
