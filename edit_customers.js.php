<?php
include_once('common.php');
?>
   var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;

var dialog_delete_all;
var interval_instance;

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
	                    {key:"customer_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
			    ,{key:"go", label:"", width:20,action:"none"}

			    ,{key:"name", label:"<?php echo _('Customer Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer_field'}
			    
//			    ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"telephone", label:"<?php echo _('Telephone')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       
//				       ,{key:"address", label:"<?php echo _('Main Address')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"ship_town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"ship_postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"ship_region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       ,{key:"ship_country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
//				       
				       

					 ];
	    //?tipo=customers&tid=0"
	    
	    request="ar_edit_contacts.php?tipo=edit_customers"
	    if(Dom.get('list_key').value>0){
	    request=request+'&list_key='+Dom.get('list_key').value;
	    }
	    
	    this.dataSource0 = new YAHOO.util.DataSource(request);
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
			 'go',
			 'name',
			
			 'email',
			 'telephone',
			 'contact_name'
			 ,"address","town","postcode","region","country"
			 ,"ship_address","ship_town","ship_postcode","ship_region","ship_country","customer_key"
			
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['edit_table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['edit_table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['edit_table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['table']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['edit_table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['edit_table']['f_value']?>'};

	      //YAHOO.util.Event.addListener('f_input', "keyup",FilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });

function init(){
 init_search('customers_store');

 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
   

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 
 
 
 
dialog_delete_all = new YAHOO.widget.Dialog("dialog_delete_all", {context:["delete_all","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_delete_all.render();
Event.addListener('delete_all', "click", dialog_delete_all.show,dialog_delete_all , true);
Event.addListener('close_delete_all', "click", dialog_delete_all.hide,dialog_delete_all , true);
Event.addListener('save_delete_all', "click", save_delete_all);


}

function save_delete_all(){

if(Dom.get('list_key').value){
var request='ar_edit_contacts.php?tipo=delete_all_customers_in_list&list_key='+Dom.get('list_key').value+'&store_key='+Dom.get('store_key').value
}else{
var request='ar_edit_contacts.php?tipo=delete_all_customers_in_store&store_key='+Dom.get('store_key').value
}

Dom.setStyle('delete_all_tbody','display','none');
Dom.setStyle('deleting_all','display','');

table_id=0
 var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];





        var myCallback = {
            success: table.onDataReturnInitializeTable,
            
            scope:table
          
        };
        interval_instance=datasource.setInterval(10000, null, myCallback);


	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
//		alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		 
		    
		    if (r.state==200) {
                Dom.setStyle('delete_all_tbody','display','');
Dom.setStyle('deleting_all','display','none');
table_id=0
 var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.clearInterval ( interval_instance ) 
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      


                dialog_delete_all.hide();
                
                
		    }else
			Dom.get('delete_all_msg').innerHTML=r.msg;
	
		}
	    });        
	



}


YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 });
