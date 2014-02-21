<?php
include_once 'common.php';


?>


function post_change_period_actions(r) {
period=r.period;
to=r.to;
from=r.from;


    request = '&from=' + from + '&to=' + to;

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  
  

    Dom.get('rtext0').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp0').innerHTML = '';
   

}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				    {key:"monthyear", label:"<?php echo _('Period')?>", width:30,sortable:false,className:"aleft"}
				    ,{key:"tariff_code", label:"<?php echo _('Comodity')?>",width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"value", label:"<?php echo _('Value')?>", width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"items", label:"<?php echo _('Items')?>", width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"bonus", label:"<?php echo _('Bonus')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"weight", label:"<?php echo _('Net Mass')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	,{key:"country_2alpha_code", label:"<?php echo _('Country')?>",width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"invoices", label:"<?php echo _('Invoices')?>", width:400,sortable:false,className:"aleft"}

				     ];

	//alert("ar_sites.php?tipo=pages&parent=site&tableid=0&parent_key="+Dom.get('site_key').value);
		request="ar_reports.php?tipo=intrastat&tableid=0";
	//alert(request)
	this.dataSource0 = new YAHOO.util.DataSource(request);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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

		fields: ['monthyear','tariff_code','value','weight','country_2alpha_code','invoices','bonus','items'
						 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
								       ,paginator : new YAHOO.widget.Paginator({

									      rowsPerPage:<?php echo$_SESSION['state']['report_intrastat']['nr']?>,containers : 'paginator0',
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })

								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_intrastat']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_intrastat']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table0.request=request;
  this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);



	    this.table0.filter={key:'<?php echo $_SESSION['state']['report_intrastat']['f_field']?>',value:'<?php echo$_SESSION['state']['report_intrastat']['f_value']?>'};






	};

    });

function init_rep_sales_main(){




}

YAHOO.util.Event.onDOMReady(init_rep_sales_main);
