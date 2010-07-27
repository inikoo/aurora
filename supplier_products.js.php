<?php include_once('common.php');?>
    
    var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				//  {key:"id", label:"<?php echo _('Id')?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  {key:"supplier", label:"<?php echo _('Supplier')?>",  width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  
	  ,{key:"code", label:"<?php echo _('Code')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"description", label:"<?php echo _('Description')?>",<?php echo($_SESSION['state']['supplier_products']['view']=='general'?'':'hidden:true,')?>width:380, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"used_in", label:"<?php echo _('Used In')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 // ,{key:"cost", label:"<?php echo _('Cost')?>",<?php echo($_SESSION['state']['supplier_products']['view']=='product_sales' or ($_SESSION['state']['supplier_products']['view']=='general')   ?'':'hidden:true,')?> width:35,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"required", label:"<?php echo _('Required')?>",<?php echo($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"provided", label:"<?php echo _('Used')?>",<?php echo($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];

		this.dataSource0 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=supplier_products&tableid="+tableid);

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
			    totalRecords: "resultset.total_records"
			},
			
			fields: [
				 "description","id","code","name","cost","used_in","profit","allcost","used","required","provided","lost","broken","supplier"
				 ]};
	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?php echo$_SESSION['state']['supplier_products']['table']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								    Key: "<?php echo$_SESSION['state']['supplier_products']['table']['order']?>",
								     dir: "<?php echo$_SESSION['state']['supplier_products']['table']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier_products']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_products']['table']['f_value']?>'};
		this.table0.view='<?php echo$_SESSION['state']['supplier_products']['view']?>';

	    }});


var product_change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;

	if(table.view!=tipo){
	    table.hideColumn('cost');
	    table.hideColumn('required');
	    table.hideColumn('provided');
	    table.hideColumn('profit');
	    table.hideColumn('name');
	    table.hideColumn('supplier');

	    
	    
	    if(tipo=='product_sales'){
		table.showColumn('cost');
		table.showColumn('provided');
		table.showColumn('required');
		table.showColumn('profit');


	    }
	    else if(tipo=='product_general'){
		table.showColumn('supplier');
	    }
	    if(tipo=='product_stock'){
		
		
	    }
	    
	    
	    

	    Dom.get(table.view).className="";
	    Dom.get(tipo).className="selected";

	    table.view=tipo;

	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-products-view&value=' + escape(tipo) );
	    
	}
 }





function init(){

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms,{table_id:0});
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
    oAutoComp.minQueryLength = 0; 


    ids=['product_general','product_sales','product_stock','product_forecast'];
    YAHOO.util.Event.addListener(ids, "click",product_change_view)

  var change_view2 = function (e){

      block=this.getAttribute('block');
      state=this.getAttribute('state');
      new_title=this.getAttribute('atitle');
      old_title=this.getAttribute('title');
      
      this.setAttribute('title',new_title);
      this.setAttribute('atitle',old_title);

      if(state==1){
	  Dom.get('block_'+block).style.display='none';
	  this.setAttribute('state',0);

	  YAHOO.util.Dom.setStyle('but_logo_'+block, 'opacity', .2);
	  YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-display-'+block+'&value=0');
      }else{

	  Dom.get('block_'+block).style.display='';
	  this.setAttribute('state',1);
	  YAHOO.util.Dom.setStyle('but_logo_'+block, 'opacity', 1);
	  YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-display-'+block+'&value=1');
	  
	 }


     }

    
    var ids = ["change_view_details","change_view_products","change_view_po","change_view_history"]; 
    Event.addListener(ids,"click",change_view2);
};

YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });