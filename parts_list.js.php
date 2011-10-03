<?php
include_once('common.php');
$static_list_id=$_REQUEST['id'];
?>
  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var view='<?php echo$_SESSION['state']['hr']['view']?>'
var dialog_export;
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	     //START OF THE TABLE =========================================================================================================================

	   var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 

					 
			      {key:"sku", label:"<?php echo _('Code')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"description", label:"<?php echo _('Description')?>",width:100,<?php echo($_SESSION['state']['store']['products']['view']!='general'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"used_in", label:"<?php echo _('used_in')?>",width:80,<?php echo($_SESSION['state']['store']['products']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"supplied_by", label:"<?php echo _('supplied_by')?>",width:80,<?php echo(($_SESSION['state']['store']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"stock", label:"<?php echo _('stock')?>",width:20,<?php echo(($_SESSION['state']['store']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      
			      ,{key:"sold", label:"<?php echo _('Sold')?>",width:20,<?php echo($_SESSION['state']['store']['products']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"available_for", label:"<?php echo _('available_for')?>",width:80,<?php echo($_SESSION['state']['store']['products']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"stock_value", label:"<?php echo _('stock_value')?>",width:50,<?php echo($_SESSION['state']['store']['products']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     // ,{key:"margin", label:"<?php echo _('Margin')?>",width:100,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     // ,{key:"stock", label:"<?php echo _('Available')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' or $_SESSION['state']['store']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    //  ,{key:"parts", label:"<?php echo _('Parts')?>",width:200,<?php echo($_SESSION['state']['store']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:200,<?php echo($_SESSION['state']['store']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			   //   ,{key:"family", label:"<?php echo _('Family')?>",width:120,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      //,{key:"dept", label:"<?php echo _('Main Department')?>",width:300,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"expcode", label:"<?php echo _('TC(UK)')?>",width:200,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      

			       ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=parts&type=list&where=&list_key=<?php echo $static_list_id;?>");
		alert("ar_assets.php?tipo=parts&type=list&where=&list_key=<?php echo $static_list_id;?>");
		//alert("ar_assets.php?tipo=parts&parent=store&tableid=0");
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
		
		fields: [
			 'sku','description', 'used_in', 'supplied_by', 'stock', 'sold', 'available_for', 'stock_value'
			 ]};

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['store']['products']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['parts']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['parts']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

	
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
 
 
  init_search('customers_store');


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



