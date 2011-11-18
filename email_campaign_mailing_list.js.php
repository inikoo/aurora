<?php
include_once('common.php');

?>
  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var dialog_add_email_address;
var dialog_add_email_address_from_list;

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
 this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
		    
	   this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table0.subscribe("cellClickEvent", onCellClick);      	    

	    this.table0.filter={key:'<?php echo$_SESSION['state']['email_campaign']['mailing_list']['f_field']?>',value:'<?php echo$_SESSION['state']['email_campaign']['mailing_list']['f_value']?>'};

	
	
	
	
	
	
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
                                        {key:"name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"creation_date", label:"<?php echo _('List Created')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					//,{key:"no_of_customer", label:"<?php echo _('No. Of Customer')?>",  width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"customer_list_type", label:"<?php echo _('List Type')?>",  width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"add_to_email_campaign_action", label:"", width:50,sortable:false,className:"right"}
                  //                     ,{key:"customer_list_key", label:"<?php echo _('Create Campaign')?>", width:155,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ];
	    //?tipo=customers&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_lists&store_id="+Dom.get('store_id').value+'&tableid=1');
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
		
		fields: ["name","customer_list_key","creation_date","customers","customer_list_type","add_to_email_campaign_action"]};
		

	  this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource1
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['list']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
 this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);
	    
	   // this.table1.subscribe("dataReturnEvent", data_returned);  


	    this.table1.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};

	
	
	
	
	
	
	
	
	
	
	
	
	
	
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
  
  
   validate_scope_data={

 'add_email_address_manually':{
  	'email_address':{'dbname':'Email Address','changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'add_email_address','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]},
  
  'email_contact_name':{'dbname':'Email Contact Name','changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'add_email_contact_name','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
   }
   

  
  
  }
validate_scope_metadata={

'add_email_address_manually':{'type':'new','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}

};

  

    dialog_add_email_address = new YAHOO.widget.Dialog("dialog_add_email_address", {context:["add_email_address_manually","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_add_email_address.render();
    Event.addListener("add_email_address_manually", "click", dialog_add_email_address.show,dialog_add_email_address , true);

  dialog_add_email_address_from_list = new YAHOO.widget.Dialog("dialog_add_email_address_from_list", {context:["add_email_address_from_customer_list","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_add_email_address_from_list.render();
    Event.addListener("add_email_address_from_customer_list", "click", dialog_add_email_address_from_list.show,dialog_add_email_address_from_list , true);

 
  var add_email_address_oACDS = new YAHOO.util.FunctionDataSource(validate_add_email_address_manually);
    add_email_address_oACDS.queryMatchContains = true;
    var add_email_address_oAutoComp = new YAHOO.widget.AutoComplete("add_email_address","add_email_address_Container", add_email_address_oACDS);
    add_email_address_oAutoComp.minQueryLength = 0; 
    add_email_address_oAutoComp.queryDelay = 0.1;
    
       Event.addListener("save_new_add_email_address_manually", "click", save_add_email_address_manually);
    Event.addListener("cancel_new_add_email_address_manually", "click", close_dialog_add_email_address);

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 



 }
 
 
 function validate_add_email_address_manually(query){
 validate_general('add_email_address_manually','email_address',unescape(query));
}

function save_add_email_address_manually(){
save_new_general('add_email_address_manually');
}

function post_new_create_actions(branch,r){
switch ( branch ) {
	case 'add_email_address_manually':
table_id=0
      var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  		
		
		//check_if_ready_to_send();
		close_dialog_add_email_address();
		break;
	
	
	
	default:
		
}
}


function close_dialog_add_email_address(){
cancel_new_general('add_email_address_manually')
dialog_add_email_address.hide();
}

function add_to_email_campaign(list_key){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=add_emails_from_list&email_campaign_key='+encodeURIComponent(email_campaign_key)+'&list_key='+encodeURIComponent(list_key);
//alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
Dom.get('recipients_preview').innerHTML=r.recipients_preview;
Dom.get('email_campaign_number_recipients').value=r.number_recipients;
		validate_general('full_email_campaign','email_recipients',r.number_recipients);
		check_if_ready_to_send();
		Dom.setStyle('recipients_preview_msg','visibility','visible')
		Dom.get('recipients_preview_msg').innerHTML=r.msg;
		
		   dialog_add_email_address_from_list.hide();
		}else{
		    if(r.msg!=undefined)
		        Dom.addClass('delete_email_campaign','error')
		        Dom.get('delete_email_campaign').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });






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



YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });
