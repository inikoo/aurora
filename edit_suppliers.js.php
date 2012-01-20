<?php
    include_once('common.php');?>
    
    var Dom   = YAHOO.util.Dom;



var validate_scope_data={'code':{'inputed':false,'validated':false,'required':true,'group':1,'type':'item','regexp':"[a-z\\d]+"}};


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		var tableid=0; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;



		var SuppliersColumnDefs = [
					   {key:"supplier_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
					   ,{key:"go", label:"", width:20,action:"none"}
					   ,{key:"id", label:"", width:20,action:"none",hidden:true}
					   ,{key:"code", label:"<?php echo _('Code')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['suppliers']['edit_suppliers']['view']!='general'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier'}
					   ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['suppliers']['edit_suppliers']['view']!='contact'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['suppliers']['edit_suppliers']['view']!='contact'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"tel",<?php echo($_SESSION['state']['suppliers']['edit_suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Tel')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				       
					   ];

		this.dataSource0 = new YAHOO.util.DataSource("ar_edit_suppliers.php?tipo=edit_suppliers");
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
			     ,"low","location","email","profit",'profit_after_storing','cost','supplier_key','go'
			     ]};

		this.table0 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
							 this.dataSource0, {draggableColumns:true,
									    renderLoopSize: 50,generateRequest : myRequestBuilder
									    ,paginator : new YAHOO.widget.Paginator({
										    rowsPerPage    : <?php echo$_SESSION['state']['suppliers']['edit_suppliers']['nr']?>,containers : 'paginator', 
										    pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
										    previousPageLinkLabel : "<",
										    nextPageLinkLabel : ">",
										    firstPageLinkLabel :"<<",
										    lastPageLinkLabel :">>"
										    ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
										})
								     
									    ,sortedBy : {
								 key: "<?php echo$_SESSION['state']['suppliers']['edit_suppliers']['order']?>",
								 dir: "<?php echo$_SESSION['state']['suppliers']['edit_suppliers']['order_dir']?>"
							     }
									    ,dynamicData : true

							 }
							 );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.filter={key:'<?php echo$_SESSION['state']['suppliers']['edit_suppliers']['f_field']?>',value:'<?php echo$_SESSION['state']['suppliers']['edit_suppliers']['f_value']?>'};
		this.table0.view='<?php echo$_SESSION['state']['suppliers']['edit_suppliers']['view']?>';
	    
		this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table0.subscribe("cellClickEvent", onCellClick);


	    };});


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
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-view&value=' + escape(tipo),{} );
};
function change_block(e){
    
	



	Dom.get('d_suppliers').style.display='none';
	Dom.get('d_new').style.display='none'
	    Dom.get('d_'+this.id).style.display='';

	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-edit&value='+this.id ,{});
	




}


function validate_supplier_code(){

    var code=Dom.get('Supplier_Code');
    
    var validator=new RegExp(validate_scope_data.code.regexp,"i");
    if(validator.test(query)){
	validate_scope_data.company_name.validated=true;
    }else{
	validate_scope_data.code.validated=false;
    }
    

    if(code==''){
	

    }else{
	
	var request='ar_supplier.php?tipo=find_supplier_code&code='; 


    }

}



    function init(){
	var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
	oACDS.queryMatchContains = true;
	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
	oAutoComp.minQueryLength = 0; 
	
	
	ids=['general','sales','stock','products'];
	YAHOO.util.Event.addListener(ids, "click",change_view);

	var ids = ["suppliers"]; 
	YAHOO.util.Event.addListener(ids, "click", change_block);

    }
YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});


YAHOO.util.Event.onContentReady("filtermenu1", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});
