<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [ 
				       {key:"id_a", label:"<?php echo _('ID')?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name_a", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"id_b", label:"<?php echo$customers_ids[0]?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name_b", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"correlation", label:"<?php echo _('Correlation')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"action", label:"", width:20,sortable:false}

				       
					 ];
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_correlation&sf=0&where=");
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
			 'id_a',
			 'name_a',
			 'id_b',
			 'name_b',
			 'correlation','action'

			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['correlations']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['correlations']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['correlations']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);

		    
		    
	    

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['correlations']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['correlations']['f_value']?>'};


	
	};
    });




function change_block(){
ids=['population','geo','data','orders','customers','correlations'];
block_ids=['block_population','block_geo','block_data','block_orders','block_customers','block_correlations'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-stats_view&value='+this.id ,{});
}


 function init() {

     init_search('customers_store');

     Event.addListener(['population', 'geo', 'data', 'orders', 'customers', 'correlations'], "click", change_block);

     Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
     var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS.queryMatchContains = true;
     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
     oAutoComp.minQueryLength = 0;
     var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);

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
