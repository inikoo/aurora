<?php
include_once('common.php');
include_once('class.Supplier.php');

?>
   var supplier_key='<?php echo$_SESSION['state']['supplier']['id']?>';
var dialog_quick_edit_Customer_Main_Contact_Name;
var dialog_quick_edit_Customer_Tax_Number;
var dialog_quick_edit_Customer_Main_Contact_Name;
var dialog_quick_edit_Customer_Main_Email;
var dialog_quick_edit_Customer_Main_Address;
var dialog_quick_edit_Customer_Main_Telephone;
var dialog_quick_edit_Customer_Main_Mobile;
var dialog_quick_edit_Customer_Main_FAX;
var list_of_dialogs;

    var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var create_new_po=function(){
    var request='ar_orders.php?tipo=create_po';
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//			alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    window.location.href='porder.php?id='+r.id;
		}
	    }
	});    
    
};
    

YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		    
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  
			  {key:"supplier", label:"<?php echo _('Supplier')?>", hidden:true, width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	              ,{key:"code", label:"<?php echo _('Code')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"description", label:"<?php echo _('Description')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='general'?'':'hidden:true,')?>width:380, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"used_in", label:"<?php echo _('Used In')?>", width:310,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"stock", label:"<?php echo _('Stock')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='stock'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"weeks_until_out_of_stock", label:"<?php echo _('W Until OO')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='stock'?'':'hidden:true,')?> width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"required", label:"<?php echo _('Required')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"dispatched", label:"<?php echo _('Dispatched')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 	,{key:"sold", label:"<?php echo _('Sold')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 	,{key:"sales", label:"<?php echo _('Sales')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ,{key:"profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='profit'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ,{key:"margin", label:"<?php echo _('Margin')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='profit'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];
		
		this.dataSource0 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=supplier_products&parent=supplier&parent_key="+Dom.get('supplier_id').value+"&tableid="+tableid);
	
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
			      "description","id","code","name","cost","used_in","profit","allcost","used","required","provided","lost","broken","supplier",
				 "dispatched","sold","sales","weeks_until_out_of_stock","stock","margin"
			     ]};
		
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['supplier']['supplier_products']['nr']?>,containers : 'paginator0', 
								     pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								     previousPageLinkLabel : "<",
								     nextPageLinkLabel : ">",
								     firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								     ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								 })
							     
							     ,sortedBy : {
								 key: "<?php echo$_SESSION['state']['supplier']['supplier_products']['order']?>",
								 dir: "<?php echo$_SESSION['state']['supplier']['supplier_products']['order_dir']?>"
							     }
							     ,dynamicData : true
							     
							 }
							 );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		
		this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
		
		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier']['supplier_products']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['supplier_products']['f_value']?>'};
		this.table0.view='<?php echo$_SESSION['state']['supplier']['supplier_products']['view']?>';
		
		var tableid=1; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		var SuppliersColumnDefs = [
					   {key:"id", label:"<?php echo _('Id')?>",  width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"date", label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"status", label:"<?php echo _('Type')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"items", label:"<?php echo _('Items')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"total", label:"<?php echo _('Total')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ];
		
		this.dataSource1 = new YAHOO.util.DataSource("ar_porders.php?tipo=purchase_orders&parent=supplier&parent_key="+supplier_key+"&tableid=1");
		//	alert("ar_porders.php?tipo=purchase_orders&parent=supplier&parent_key="+supplier_key+"tableid=1")
	this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource1.connXhrMode = "queueRequests";
		this.dataSource1.responseSchema = {
		    resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id"
			 ,"status"
			 ,"date"
			 ,"items"
			 ,"total"

	 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['porders']['table']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['porders']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['porders']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);
	    
	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['porders']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['porders']['table']['f_value']?>'};
	
	
	
	
		    var tableid=1;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      ,{key:"locations", label:"<?php echo _('Locations')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"quantity", label:"<?php echo _('Qty')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"value", label:"<?php echo _('Value')?>", width:60,sortable:false,className:"aleft"}
				      
				      ,{key:"sold_qty", label:"<?php echo _('Sold')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"in_qty", label:"<?php echo _('In')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"lost_qty", label:"<?php echo _('Lost')?>", width:60,sortable:false,className:"aright"}

				      ];

		 
		    
		    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=part_stock_history&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource1.connXhrMode = "queueRequests";
		    this.dataSource1.responseSchema = {
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
				 "date","locations","quantity","value","sold_qty","in_qty","lost_qty"

				 ]};

	    
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource1, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({alwaysVisible:false,
									 rowsPerPage:<?php echo$_SESSION['state']['part']['stock_history']['nr']?>,containers : 'paginator1', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['stock_history']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['stock_history']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;


	
	
	
	
	var tableid=3; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		var SuppliersColumnDefs = [
					   {key:"id", label:"<?php echo _('Id')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"date", label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"status", label:"<?php echo _('Type')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"items", label:"<?php echo _('Items')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   //,{key:"total", label:"<?php echo _('Total')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ];
		
		this.dataSource3 = new YAHOO.util.DataSource("ar_porders.php?tipo=delivery_notes&parent=supplier&parent_key="+supplier_key+"&tableid=3");
	this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource3.connXhrMode = "queueRequests";
		this.dataSource3.responseSchema = {
		    resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id"
			 ,"status"
			 ,"date"
			 ,"items"

	 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource3, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['supplier_dns']['table']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_dns']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_dns']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);
	    
	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['supplier_dns']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_dns']['table']['f_value']?>'};





  var tableid=100; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			];
			       
	    this.dataSource100 = new YAHOO.util.DataSource("ar_regions.php?tipo=country_list&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource100.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource100.connXhrMode = "queueRequests";
	    	    this.dataSource100.table_id=tableid;

	    this.dataSource100.responseSchema = {
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
			 "name","flag",'code','population','gnp','wregion','code3a','code2a','plain_name','postal_regex','postcode_help'
			 ]};


	    this.table100 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource100
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator100', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info100'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table100.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table100.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table100.subscribe("cellClickEvent", this.table100.onEventShowCellEditor);
this.table100.prefix='';
 this.table100.subscribe("rowMouseoverEvent", this.table100.onEventHighlightRow);
       this.table100.subscribe("rowMouseoutEvent", this.table100.onEventUnhighlightRow);
      this.table100.subscribe("rowClickEvent", select_country_from_list);
     
this.table100.table_id=tableid;
     this.table100.subscribe("renderEvent", myrenderEvent);

	    this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table100.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //



	    }});
  var orders_change_view=function(e){
	
	var tipo=this.id;
switch(tipo){
case('dns'):
Dom.get('block_pos').style.display='none';
Dom.get('block_invoices').style.display='none';
Dom.get('block_dns').style.display='';
	    Dom.removeClass('pos',"selected");
	    Dom.removeClass('invoices',"selected");
	    Dom.addClass('dns',"selected");
break;
case('pos'):
Dom.get('block_pos').style.display='';
Dom.get('block_invoices').style.display='none';
Dom.get('block_dns').style.display='none';
	    Dom.removeClass('dns',"selected");
	    Dom.removeClass('invoices',"selected");
	    Dom.addClass('pos',"selected");
break;
case('invoices'):
Dom.get('block_pos').style.display='none';
Dom.get('block_invoices').style.display='';
Dom.get('block_dns').style.display='none';
	    Dom.removeClass('pos',"selected");
	    Dom.removeClass('dns',"selected");
	    Dom.addClass('invoices',"selected");
break;
}
	

	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-orders_view&value=' + escape(tipo),{},null );
	    
	
 }

  var product_change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;

	if(table.view!=tipo){
	    table.hideColumn('cost');
	    table.hideColumn('required');
	    table.hideColumn('provided');
	    table.hideColumn('profit');
	    table.hideColumn('name');
	    table.hideColumn('tuos');
	    table.hideColumn('usld');
	    table.hideColumn('stock');
	    table.hideColumn('sales');

	    
	    
	    if(tipo=='product_sales'){
		table.showColumn('cost');
		table.showColumn('provided');
		table.showColumn('required');
		table.showColumn('profit');
		table.showColumn('sales');


	    }
	    else if(tipo=='product_general'){
	    table.showColumn('name');
		
	    }else if(tipo=='product_stock'){
	    table.showColumn('usld');
			    table.showColumn('stock');
			    table.showColumn('name');

	    }else if(tipo=='product_forecast'){
		    table.showColumn('tuos');
	    table.showColumn('usld');
		
	    }
	    
	    
	    

	    Dom.get(table.view).className="";
	    Dom.get(tipo).className="selected";

	    table.view=tipo;
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-products-view&value=' + escape(tipo),{} );
	    
	}
 }


   function change_block(){
ids=["details","products","purchase_orders","purchases"];
block_ids=["block_details","block_products","block_purchase_orders","block_purchases"];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-block_view&value='+this.id ,{});
}

function show_edit_main_contact_name(){
Dom.get('Customer_Main_Contact_Name').value=Dom.get('Customer_Main_Contact_Name').getAttribute('ovalue')
hide_all_dialogs();
dialog_quick_edit_Customer_Name.show();
}


function show_edit_tax(){
hide_all_dialogs();
Dom.get('Customer_Tax_Number').value=Dom.get('Customer_Tax_Number').getAttribute('ovalue')
dialog_quick_edit_Customer_Tax_Number.show();
}

function show_edit_name(){

hide_all_dialogs();

Dom.get('Customer_Main_Contact_Name').value=Dom.get('Customer_Main_Contact_Name').getAttribute('ovalue')

dialog_quick_edit_Customer_Main_Contact_Name.show();
//dialog_quick_edit_Customer_Main_Email.show();
}

function dialog_quick_edit_Customer_Main_Email_(){
	Dom.get('Customer_Main_Email').value=Dom.get('Customer_Main_Email').getAttribute('ovalue');
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Email.show();
}
function dialog_quick_edit_Customer_Main_Address_(){

	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Address.show();
}
function dialog_quick_edit_Customer_Main_Telephone_(){
	Dom.get('Customer_Main_Telephone').value=Dom.get('Customer_Main_Telephone').getAttribute('ovalue');
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Telephone.show();
}

function dialog_quick_edit_Customer_Main_FAX_(){
	Dom.get('Customer_Main_FAX').value=Dom.get('Customer_Main_FAX').getAttribute('ovalue');
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_FAX.show();
}

function hide_all_dialogs(){
	for(x in list_of_dialogs)
		eval(list_of_dialogs[x]).hide();
		//alert(list_of_dialogs[x])
}

function save_quick_edit_main_contact_name(){

save_edit_general('supplier_quick');
}


function save_quick_edit_tax_number(){
	save_edit_general('billing_quick');
}

function save_quick_edit_email(){
    save_edit_general('supplier_quick');

}
function save_quick_edit_telephone(){
	//alert('telephone');
	//validate_customer_telephone();
    save_edit_general('supplier_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_mobile(){
	//alert('mobile');
	//validate_customer_mobile();
    save_edit_general('supplier_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_fax(){
	//alert('fax');
	//validate_customer_fax();
    save_edit_general('supplier_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function save_quick_edit_name(){
	//alert('validate name');
	//validate_customer_name();
    save_edit_general('supplier_quick');
	//Dom.setStyle('dialog_quick_edit_'+field_name,'display','none')
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}



function post_item_updated_actions(branch,r){


if(branch=='supplier_quick'){
eval('dialog_quick_edit_'+validate_scope_data[branch][r.key].name).hide();
}
	if(r.key=='tax_number'){
		Dom.get('Customer_Tax_Number').value=r.newvalue;
		Dom.get('Customer_Tax_Number').setAttribute('ovalue',r.newvalue);
		Dom.get('Customer_Tax_Number_msg').innerHTML='';
		Dom.get('main_name').innerHTML=r.newvalue;
	}
	if(r.key=='contact'){
		dialog_quick_edit_Customer_Main_Contact_Name.hide();
		Dom.get('main_contact_name').innerHTML=r.newvalue;
		Dom.get('Customer_Main_Contact_Name').value=r.newvalue;
		Dom.get('Customer_Main_Contact_Name').setAttribute('ovalue',r.newvalue);
		Dom.get('Customer_Main_Contact_Name_msg').innerHTML='';
		if(customer_type=='Person'){
			Dom.get('customer_name').innerHTML=r.newvalue;
		    Dom.get('Customer_Name').value=r.newvalue;
		    Dom.get('Customer_Name').setAttribute('ovalue',r.newvalue);

		    Dom.get('Customer_Name_msg').innerHTML='';
			
		}	
	}
	if(r.key=='name'){
		dialog_quick_edit_Customer_Name.hide();
		Dom.get('customer_name').innerHTML=r.newvalue;
		Dom.get('Customer_Name').value=r.newvalue;
		Dom.get('Customer_Name').setAttribute('ovalue',r.newvalue);

		Dom.get('Customer_Name_msg').innerHTML='';
		
		if(customer_type=='Person'){
		Dom.get('main_contact_name').innerHTML=r.newvalue;
		Dom.get('Customer_Main_Contact_Name').value=r.newvalue;
		Dom.get('Customer_Main_Contact_Name').setAttribute('ovalue',r.newvalue);
		Dom.get('Customer_Main_Contact_Name_msg').innerHTML='';

			
		}	
	}
	else if(r.key=='email'){
		if(r.state!=200) return;
		dialog_quick_edit_Customer_Main_Email.hide();
		Dom.get('main_email').innerHTML='<a href="mailto:'+r.newvalue+'">'+r.newvalue+'</a>';
		//Dom.get('Customer_Main_Email').innerHTML=r.newvalue;
		//Dom.get('contact_email_id').innerHTML='<a href="mailto:'+r.newvalue+'">'+r.newvalue+'</a>';
		Dom.get('Customer_Main_Email_msg').innerHTML='';
		
	}
	else if(r.key=='telephone'){
		if(r.state!=200) return;
		
		dialog_quick_edit_Customer_Main_Telephone.hide();
		Dom.get('main_telephone').innerHTML=r.newvalue;
		//Dom.get('Customer_Main_Telephone').innerHTML=r.newvalue;
		//Dom.get('contact_telephone_id').innerHTML=r.newvalue;
		Dom.get('Customer_Main_Telephone_msg').innerHTML='';
		
	}
	else if(r.key=='fax'){
		if(r.state!=200) return;
		
		dialog_quick_edit_Customer_Main_FAX.hide();
		Dom.get('main_fax').innerHTML=r.newvalue;
		//Dom.get('Customer_Main_FAX').innerHTML=r.newvalue;
		//Dom.get('contact_fax_id').innerHTML=r.newvalue;
		Dom.get('Customer_Main_FAX_msg').innerHTML='';
		
	}
	else if(branch=='address'){
		dialog_quick_edit_Customer_Main_Address.hide();
		Dom.get('main_address').innerHTML=r.xhtml_address;
		//if(r.is_main_delivery=='Yes')
		//	Dom.get('main_delivery_address').innerHTML=r.xhtml_address;
	}
	//else
		//alert('non');

}


var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";
var validate_scope_data=
{
    'supplier_quick':{
	'name':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	,'email':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
	
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}
}};



var validate_scope_metadata={
'supplier_quick':{'type':'edit','ar_file':'ar_edit_suppliers.php','key_name':'supplier_key','key':<?php echo $_SESSION['state']['supplier']['id']?>}

};

function init(){
  init_search('supplier_products_supplier');
  
  list_of_dialogs=[
"dialog_quick_edit_Customer_Name", 
"dialog_quick_edit_Customer_Main_Contact_Name",
"dialog_quick_edit_Customer_Main_Address",
"dialog_quick_edit_Customer_Tax_Number",
"dialog_quick_edit_Customer_Main_Contact_Name",
"dialog_quick_edit_Customer_Main_Email",
"dialog_quick_edit_Customer_Main_Telephone",
"dialog_quick_edit_Customer_Main_Mobile",
"dialog_quick_edit_Customer_Main_FAX"
];
  
  
  
  
 YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'supplier');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'supplier'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms,{table_id:0});
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 
 
 ids=['pos','dns','invoices'];
    YAHOO.util.Event.addListener(ids, "click",orders_change_view)

    ids=['product_general','product_sales','product_stock','product_forecast'];
    YAHOO.util.Event.addListener(ids, "click",product_change_view)
    
    


    
ids=["details","products","purchase_orders","purchases"];
    Event.addListener(ids,"click",change_block);


  ids=['supplier_products_general','supplier_products_sales','supplier_products_stock','supplier_products_profit'];
 YAHOO.util.Event.addListener(ids, "click",change_supplier_products_view,{'table_id':0,'parent':'supplier'})


 ids=['supplier_products_period_all','supplier_products_period_year','supplier_products_period_quarter','supplier_products_period_month','supplier_products_period_week',
 'supplier_products_period_six_month','supplier_products_period_three_year','supplier_products_period_ten_day','supplier_products_period_month','supplier_products_period_week',
 'supplier_products_period_yeartoday','supplier_products_period_monthtoday','supplier_products_period_weektoday'
 
 ];

 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'supplier_products'});



dialog_quick_edit_Customer_Main_Contact_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Contact_Name", {context:["quick_edit_main_contact_name_edit","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Contact_Name.render();

dialog_quick_edit_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Name", {context:["quick_edit_name_edit","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Name.render();
/*
dialog_quick_edit_Customer_Main_Contact_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Contact_Name", {context:["quick_edit_main_contact_name_edit","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Contact_Name.render();
*/
dialog_quick_edit_Customer_Tax_Number = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Tax_Number", {context:["quick_edit_tax","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Tax_Number.render();


dialog_quick_edit_Customer_Main_Email = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Email", {context:["quick_edit_email","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Email.render();
dialog_quick_edit_Customer_Main_Address = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Address", {context:["main_address","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Address.render();
dialog_quick_edit_Customer_Main_Telephone = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Telephone", {context:["quick_edit_main_telephone","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Telephone.render();
dialog_quick_edit_Customer_Main_Mobile = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Mobile", {context:["quick_edit_main_mobile","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Mobile.render();
dialog_quick_edit_Customer_Main_FAX = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_FAX", {context:["quick_edit_main_fax","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_FAX.render();


Event.addListener('quick_edit_main_contact_name_edit', "click", show_edit_name);
Event.addListener('quick_edit_name_edit', "click", show_edit_main_contact_name);
Event.addListener('quick_edit_tax', "click", show_edit_tax);
Event.addListener('quick_edit_email', "click", dialog_quick_edit_Customer_Main_Email_);
Event.addListener('quick_edit_main_address', "click", dialog_quick_edit_Customer_Main_Address_);
Event.addListener('quick_edit_main_telephone', "click", dialog_quick_edit_Customer_Main_Telephone_);
Event.addListener('quick_edit_main_fax', "click", dialog_quick_edit_Customer_Main_FAX_);


Event.addListener('save_quick_edit_name', "click", save_quick_edit_main_contact_name, true);
Event.addListener('close_quick_edit_name', "click", dialog_quick_edit_Customer_Name.hide,dialog_quick_edit_Customer_Name , true);
//save_quick_edit_name

Event.addListener('save_quick_edit_tax_number', "click", save_quick_edit_tax_number, true);                                                                                 
Event.addListener('close_quick_edit_tax_number', "click", dialog_quick_edit_Customer_Tax_Number.hide,dialog_quick_edit_Customer_Tax_Number , true);
    
Event.addListener('save_quick_edit_main_contact_name', "click", save_quick_edit_main_contact_name, true);
Event.addListener('close_quick_edit_main_contact_name', "click", dialog_quick_edit_Customer_Main_Contact_Name.hide,dialog_quick_edit_Customer_Main_Contact_Name , true);

Event.addListener('save_quick_edit_email', "click", save_quick_edit_email, true);
Event.addListener('close_quick_edit_email', "click", dialog_quick_edit_Customer_Main_Email.hide,dialog_quick_edit_Customer_Main_Email , true);

Event.addListener('save_quick_edit_telephone', "click", save_quick_edit_telephone, true);
Event.addListener('close_quick_edit_telephone', "click", dialog_quick_edit_Customer_Main_Telephone.hide,dialog_quick_edit_Customer_Main_Telephone , true);

Event.addListener('save_quick_edit_mobile', "click", save_quick_edit_mobile, true);
Event.addListener('close_quick_edit_mobile', "click", dialog_quick_edit_Customer_Main_Mobile.hide,dialog_quick_edit_Customer_Main_Mobile , true);

Event.addListener('save_quick_edit_fax', "click", save_quick_edit_fax, true);
Event.addListener('close_quick_edit_fax', "click", dialog_quick_edit_Customer_Main_FAX.hide,dialog_quick_edit_Customer_Main_FAX , true);


    var customer_main_contact_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_main_contact_name);
    customer_main_contact_name_oACDS.queryMatchContains = true;
    var customer_main_contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Contact_Name","Customer_Main_Contact_Name_Container", customer_main_contact_name_oACDS);
    customer_main_contact_name_oAutoComp.minQueryLength = 0; 
    customer_main_contact_name_oAutoComp.queryDelay = 0.1;

    var customer_email_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email);
    customer_email_oACDS.queryMatchContains = true;
    var customer_email_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Email","Customer_Main_Email_Container", customer_email_oACDS);
    customer_email_oAutoComp.minQueryLength = 0; 
    customer_email_oAutoComp.queryDelay = 0.1;
	
    var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_name);
    customer_name_oACDS.queryMatchContains = true;
    var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Name","Customer_Name_Container", customer_name_oACDS);
    customer_name_oAutoComp.minQueryLength = 0; 
    customer_name_oAutoComp.queryDelay = 0.1;
	
    var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Telephone","Customer_Main_Telephone_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;


    var customer_fax_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax);
    customer_fax_oACDS.queryMatchContains = true;
    var customer_fax_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_FAX","Customer_Main_FAX_Container", customer_fax_oACDS);
    customer_fax_oAutoComp.minQueryLength = 0; 
    customer_fax_oAutoComp.queryDelay = 0.1;



	<?php 


$supplier=new Supplier($_REQUEST['id']);
print "var supplier_id='".$supplier->id."';";

print sprintf("edit_address(%d,'contact_');",$supplier->data['Supplier Main Address Key']);?>
	
	var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	 
	YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,{prefix:'contact_',subject:'Supplier',subject_key:supplier_id,type:'contact'});
	//alert("caca")
	YAHOO.util.Event.addListener('contact_reset_address_button', "click",reset_address,'contact_');
	
	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("contact_address_country", "contact_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='contact_';
    Countries_AC.prefix='contact_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);



};

function validate_customer_name(query){
 validate_general('supplier_quick','name',unescape(query));
}

function validate_customer_main_contact_name(query){
 validate_general('supplier_quick','contact',unescape(query));
}

function validate_customer_tax_number(query){
 validate_general('billing_quick','tax_number',unescape(query));
}


function validate_customer_email(query){
//alert('q: ' + query)
if(query==''){
    validate_scope_data.supplier_quick.email.validated=true;
    
 if(Dom.get(validate_scope_data.supplier_quick.email.name).getAttribute('ovalue')!=query){
     validate_scope_data.supplier_quick.email.changed=true;
 }else{
    validate_scope_data.supplier_quick.email.changed=false;
 }
    
	validate_scope('supplier_quick'); 
    Dom.get(validate_scope_data.supplier_quick.email.name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
}else{
validate_general('supplier_quick','email',unescape(query));

}


}

function validate_customer_telephone(query){
    validate_general('supplier_quick','telephone',unescape(query));
    if(query==''){
        validate_scope_data.supplier_quick.telephone.validated=true;
	    validate_scope('supplier_quick'); 
	    Dom.get(validate_scope_data.supplier_quick.telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
    }
}

function validate_customer_fax(query){
    validate_general('supplier_quick','fax',unescape(query));
    if(query==''){
        validate_scope_data.supplier_quick.fax.validated=true;
	    validate_scope('supplier_quick'); 
	    Dom.get(validate_scope_data.supplier_quick.fax.name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
    }
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

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name2", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu2", { context:["filter_name2","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info2", "click", oMenu.show, null, oMenu);
    });
