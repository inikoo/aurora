<?php
include_once('common.php');

?>
  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;


var dialog_export;
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	     //START OF THE TABLE =========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	
		    var CustomersColumnDefs = [ 
				       {key:"id", label:"",hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"contact", label:"<?php echo _('Contact')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"email", label:"<?php echo _('Email')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						  ,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'email_campaign_recipient'}
						];


	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_marketing.php?tipo=mailing_list&type=list&email_campaign_key="+Dom.get('email_campaign_key').value);
	  
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
			 'id','contact','email','delete'
			 
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['email_campaign']['mailing_list']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['email_campaign']['mailing_list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['email_campaign']['mailing_list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    

	    this.table0.filter={key:'<?php echo$_SESSION['state']['email_campaign']['mailing_list']['f_field']?>',value:'<?php echo$_SESSION['state']['email_campaign']['mailing_list']['f_value']?>'};

	
	};
    });

function close_dialog(tipo){
    switch(tipo){
case('export'):
 dialog_export.hide();
 break;
    }
};


 function init(){
 
 
  init_search('marketing_store');


  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'customers');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'customers'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
dialog_export = new YAHOO.widget.Dialog("dialog_export", {context:["export_data","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_export.render();
Event.addListener("export_data", "click", dialog_export.show,dialog_export , true);
 
 
 


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 



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



