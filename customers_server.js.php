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
				       {key:"code", label:"<?php echo _('Code')?>",width:70,sortable:true,<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?>className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Store Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"contacts", label:"<?php echo _('Contacts')?>",<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				       ,{key:"new_contacts", label:"<?php echo _('New Contacts')?>",<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				       ,{key:"customers", label:"<?php echo _('Customers')?>",<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				       ,{key:"active", label:"<?php echo _('Active')?>",<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				       ,{key:"new", label:"<?php echo _('New')?>",<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				       ,{key:"lost", label:"<?php echo _('Lost')?>",<?php echo($_SESSION['state']['stores']['customers']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=customers_per_store&tableid="+tableid);
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 'code','name','contacts','active','new','lost','customers','new_contacts'
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     // sortedBy: {key:"<?php echo$_SESSION['tables']['customers_list'][0]?>", dir:"<?php echo$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['stores']['customers']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['customers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['customers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['stores']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['stores']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['customers']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });




 function init(){
// -----------------------------------------------------------------

YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'customers_per_store');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'customers_per_store'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);

// -----------------------------------------------------------------
 
 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 
  init_search('customers');
 

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 







  



YAHOO.util.Event.addListener('but_show_details', "click",show_details,'customers');
//var ids=['general','contact','address','ship_to_address','balance','rank'];
//YAHOO.util.Event.addListener(ids, "click",change_view);
//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);

var search_data={tipo:'customer_name',container:'customer'};
Event.addListener('customer_submit_search', "click",submit_search,search_data);
Event.addListener('customer_search', "keydown", submit_search_on_enter,search_data);

 
}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
