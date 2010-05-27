<?php
    include_once('common.php');?>
    var Dom   = YAHOO.util.Dom;
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    

	    
	    var SuppliersColumnDefs = [
				       {key:"id", label:"<?php echo _('Id')?>",  width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"code", label:"<?php echo _('Code')?>",width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['suppliers']['view']!='general'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      
				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['suppliers']['view']!='contact'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tel",<?php echo($_SESSION['state']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Tel')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     
				        ,{key:"pending_pos", <?php echo($_SESSION['state']['suppliers']['view']!='general'?'hidden:true,':'')?> label:"<?php echo _('P POs')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"for_sale", <?php echo($_SESSION['state']['suppliers']['view']!='products'?'hidden:true,':'')?> label:"<?php echo _('For Sale')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"tobedicontinued", <?php echo($_SESSION['state']['suppliers']['view']!='products'?'hidden:true,':'')?> label:"<?php echo _('To be disc')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"discontinued",<?php echo($_SESSION['state']['suppliers']['view']!='products'?'hidden:true,':'')?>  label:"<?php echo _('Disc')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"nosale", <?php echo($_SESSION['state']['suppliers']['view']!='products'?'hidden:true,':'')?> label:"<?php echo _('Not for sale')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"stock_value",<?php echo($_SESSION['state']['suppliers']['view']!='money'?'hidden:true,':'')?>  label:"<?php echo _('Stock Value')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"high",<?php echo($_SESSION['state']['suppliers']['view']!='product_availability'?'hidden:true,':'')?>  label:"<?php echo _('High')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"normal",<?php echo($_SESSION['state']['suppliers']['view']!='product_availability'?'hidden:true,':'')?>  label:"<?php echo _('Normal')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"low", <?php echo($_SESSION['state']['suppliers']['view']!='product_availability'?'hidden:true,':'')?> label:"<?php echo _('Low')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"crical", <?php echo($_SESSION['state']['suppliers']['view']!='product_availability'?'hidden:true,':'')?> label:"<?php echo _('Critical')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"outofstock", <?php echo($_SESSION['state']['suppliers']['view']!='product_availability'?'hidden:true,':'')?> label:"<?php echo _('Out of Stock')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"profit", <?php echo($_SESSION['state']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	 ,{key:"profit_after_storing", <?php echo($_SESSION['state']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('PaS')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"cost", <?php echo($_SESSION['state']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Cost')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 
				       ];

	      this.dataSource0 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=suppliers");
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
			 "id"
			 ,"name"
			 ,"code"
			 ,"forsale"
			 ,"outofstock"
			 ,"low","location","email","profit",'profit_after_storing','cost',"pending_pos"
	 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['suppliers']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['suppliers']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['suppliers']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['suppliers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['suppliers']['table']['f_value']?>'};
	    this.table0.view='<?php echo$_SESSION['state']['suppliers']['view']?>';
	    



	};
    });


 var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;

	table.hideColumn('location');
	table.hideColumn('email');
	table.hideColumn('for_sale');
	table.hideColumn('tobediscontinued');
	table.hideColumn('nosale');
	table.hideColumn('high');
	table.hideColumn('normal');
	table.hideColumn('low');
	table.hideColumn('critical');
	table.hideColumn('outofstock');
	table.hideColumn('profit');
	table.hideColumn('profit_after_storing');
	table.hideColumn('cost');
	if(tipo=='general'){
	    table.showColumn('name');
	    table.showColumn('location');
	    table.showColumn('email');
	}else if(tipo=='stock'){
	    table.showColumn('high');
	    table.showColumn('normal');
	    table.showColumn('low');
	    table.showColumn('critical');
	    table.showColumn('outofstock');


	}else if(tipo=='sales'){
	    table.showColumn('profit');
	    table.showColumn('profit_after_storing');
	    table.showColumn('cost');

	}else if(tipo=='products'){
	    table.showColumn('for_sale');
	    table.showColumn('tobediscontinued');
	    table.showColumn('nosale');
	}
	

	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";
	table.view=tipo
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-view&value=' + escape(tipo) );
    }


function init(){
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

    
    ids=['general','sales','stock','products'];
    YAHOO.util.Event.addListener(ids, "click",change_view)
YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);


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


