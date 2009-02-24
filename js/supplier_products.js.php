<?include_once('../common.php');?>
    
    var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  {key:"id", label:"<?=_('Id')?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"code", label:"<?=_('Code')?>",  width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"name", label:"<?=_('Name')?>",<?=($_SESSION['state']['supplier_products']['view']=='product_general'?'':'hidden:true,')?>width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"usedin", label:"<?=_('Used In')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"cost", label:"<?=_('Cost')?>",<?=($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:35,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"required", label:"<?=_('Required')?>",<?=($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"provided", label:"<?=_('Used')?>",<?=($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"profit", label:"<?=_('Profit')?>",<?=($_SESSION['state']['supplier_products']['view']=='product_sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];

		this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=all_supplier_products&tableid="+tableid);

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
				 "id","code","name","cost","usedin","profit","allcost","used","required","provided","lost","broken"
				 ]};
	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?=$_SESSION['state']['supplier_products']['table']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								     key: "<?=$_SESSION['state']['supplier_products']['table']['order']?>",
								     dir: "<?=$_SESSION['state']['supplier_products']['table']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.filter={key:'<?=$_SESSION['state']['supplier_products']['table']['f_field']?>',value:'<?=$_SESSION['state']['supplier_products']['table']['f_value']?>'};
		this.table0.view='<?=$_SESSION['state']['supplier_products']['view']?>';

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

	    
	    
	    if(tipo=='product_sales'){
		table.showColumn('cost');
		table.showColumn('provided');
		table.showColumn('required');
		table.showColumn('profit');


	    }
	    else if(tipo=='product_general'){
		
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
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
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



YAHOO.util.Event.onContentReady("rppmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["rtext_rpp0","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp0", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });