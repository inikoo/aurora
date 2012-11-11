<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;

var validate_scope_data;
var validate_scope_metadata;


    



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {






	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

                                        ,{key:"name", label:"<?php echo _('Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"description", label:"<?php echo _('Description')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"used", label:"<?php echo _('Used')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=campaigns&store_id="+Dom.get('store_id').value);
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
		
		fields: ["name","key","description","duration","used"]};
		

	  this.table0 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['store_offers']['campaigns']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['store_offers']['campaigns']['order']?>",
									 dir: "<?php echo $_SESSION['state']['store_offers']['campaigns']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	  
	    this.table0.filter={key:'<?php echo $_SESSION['state']['store_offers']['campaigns']['f_field']?>',value:'<?php echo $_SESSION['state']['store_offers']['campaigns']['f_value']?>'};
	    
	    
	    
	    
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

                                        ,{key:"name", label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"description", label:"<?php echo _('Description')?>", width:320,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"used", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=deals&parent=store_with_children&parent_key="+Dom.get('store_id').value+'&tableid=1');
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
		
		fields: ["name","key","description","duration","used"]};
		

	  this.table1 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource1
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['store_offers']['offers']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['store_offers']['offers']['order']?>",
									 dir: "<?php echo $_SESSION['state']['store_offers']['offers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

	  
	    this.table1.filter={key:'<?php echo $_SESSION['state']['store_offers']['offers']['f_field']?>',value:'<?php echo $_SESSION['state']['store_offers']['offers']['f_value']?>'};
	    

	
	};
    });



function validate_deal_code(query){
alert(query)
 validate_general('deal','code',unescape(query));
}

function validate_deal_name(query){
 validate_general('deal','name',unescape(query));
}
function validate_deal_description(query){
 validate_general('deal','description',unescape(query));
}



function init(){
var validate_scope_data=
{
    'deal':{
	'description':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Deal_Description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Offer Description')?>'}]},
	'name':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Offer Name')?>'}]},
	'code':{'ar':'find','ar_request':'ar_assets.php?tipo=code_in_other_deal&deal_key='+Dom.get('deal_key').value+'&store_key='+Dom.get('store_key').value+'&query=','changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Deal_Code','validation':false}
}  
};
var validate_scope_metadata={
'deal':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'deal_key','key':Dom.get('deal_key').value}

};
 var deal_code_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_code);
    deal_code_oACDS.queryMatchContains = true;
    var deal_code_oAutoComp = new YAHOO.widget.AutoComplete("deal_code","deal_code_Container", deal_code_oACDS);
    deal_code_oAutoComp.minQueryLength = 0; 
    deal_code_oAutoComp.queryDelay = 0.1;
    
     var deal_name_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_name);
    deal_name_oACDS.queryMatchContains = true;
    var deal_name_oAutoComp = new YAHOO.widget.AutoComplete("deal_name","deal_name_Container", deal_name_oACDS);
    deal_name_oAutoComp.minQueryLength = 0; 
    deal_name_oAutoComp.queryDelay = 0.1;
    
    
     var deal_description_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_description);
    deal_description_oACDS.queryMatchContains = true;
    var deal_description_oAutoComp = new YAHOO.widget.AutoComplete("Deal_Description","Deal_Description_Container", deal_description_oACDS);
    deal_description_oAutoComp.minQueryLength = 0; 
    deal_description_oAutoComp.queryDelay = 0.1;




init_search('products_store');





}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });



