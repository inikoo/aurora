<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var dialog_add_email_address;
var dialog_add_email_address_from_list;
var validate_scope_data;
var validate_scope_metadata;


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	     //START OF THE TABLE =========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
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
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_lists&store_id="+Dom.get('store_id').value);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",  rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		fields: ["name","customer_list_key","creation_date","customers","customer_list_type","add_to_email_campaign_action"]};
		

	  this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['list']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	   // this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};

	
	};
    });


function cancel_edit_email_campaign(){
location.href='marketing.php';
}

function save_edit_email_campaign(){

if(Dom.hasClass(this,'disabled')){
alert("fill the fields");
return;
}


var store_key=Dom.get('store_id').value;


var email_campaign_name=Dom.get('email_campaign_name').value;
var email_campaign_objetive=Dom.get('email_campaign_objetive').value;

var request='ar_edit_marketing.php?tipo=create_email_marketing&store_key='+encodeURIComponent(store_key)+'&name='+encodeURIComponent(email_campaign_name)+'&objective='+encodeURIComponent(email_campaign_objetive);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

            location.href="new_email_campaign.php?id="+r.email_campaign_key;
		   
		}else{
		    Dom.get('new_store_messages').innerHTML='<span class="error">'+r.msg+'</span>';
		}
	    }
	    });
}


function validate_email_campaign_name(query){
 validate_general('email_campaign','name',unescape(query));
}
function validate_email_campaign_objetive(query){
 validate_general('email_campaign','objetive',unescape(query));
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
		Dom.get('recipients_preview').innerHTML=r.recipients_preview;
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
alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

            location.href="new_email_campaign.php?id="+r.email_campaign_key;
		   dialog_add_email_address_from_list.hide();
		}else{
		    if(r.msg!=undefined)
		        Dom.addclass('delete_email_campaign','error')
		        Dom.get('delete_email_campaign').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });






}

function text_email(){
Dom.addClass('select_text_email','selected');
Dom.removeClass('select_html_email','selected');

Dom.setStyle('text_email_fields','display','')

}


function delete_email_campaign(){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=delete_email_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key);
//alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

            location.href="marketing.php";
		}else{
		    if(r.msg!=undefined)
		        Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });
}

function init(){

 validate_scope_data={
 'email_campaign':{
	'name':{'dbname':'Email Campaign Name',
	        'changed':false,
	        'validated':true,
	        'required':true,
	        'group':1,
	        'type':'item',
	        'name':'email_campaign_name',
	        
	        'ar':'find','ar_request':'ar_marketing.php?tipo=is_email_campaign_name&store_key='+Dom.get('store_id').value+'&query=',
	        'validation':[{'regexp':"[a-z\\d]+",
	        'invalid_msg':Dom.get('invalid_email_campaign_name').innerHTML}]},
	'objetive':{
	            'dbname':'Email Campaign Objective',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_objetive',
	            'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_objetive').innerHTML}]
	            }


  },
'add_email_address_manually':{
  	'email_address':{'dbname':'Email Address','changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'add_email_address','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
  	,'email_contact_name':{'dbname':'Email Contact Name','changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'add_email_contact_name','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}

  }
};


 validate_scope_metadata={
'email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'store_key','key':Dom.get('store_id').value}
,'add_email_address_manually':{'type':'new','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}

};

 
    dialog_add_email_address = new YAHOO.widget.Dialog("dialog_add_email_address", {context:["add_email_address_manually","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    dialog_add_email_address.render();
    Event.addListener("add_email_address_manually", "click", dialog_add_email_address.show,dialog_add_email_address , true);

  dialog_add_email_address_from_list = new YAHOO.widget.Dialog("dialog_add_email_address_from_list", {context:["add_email_address_from_customer_list","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_add_email_address_from_list.render();
    Event.addListener("add_email_address_from_customer_list", "click", dialog_add_email_address_from_list.show,dialog_add_email_address_from_list , true);


    var email_campaign_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_name);
    email_campaign_name_oACDS.queryMatchContains = true;
    var email_campaign_name_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_name","email_campaign_name_Container", email_campaign_name_oACDS);
    email_campaign_name_oAutoComp.minQueryLength = 0; 
    email_campaign_name_oAutoComp.queryDelay = 0.1;
    
    var email_campaign_objetive_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_objetive);
    email_campaign_objetive_oACDS.queryMatchContains = true;
    var email_campaign_objetive_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_objetive","email_campaign_objetive_Container", email_campaign_objetive_oACDS);
    email_campaign_objetive_oAutoComp.minQueryLength = 0; 
    email_campaign_objetive_oAutoComp.queryDelay = 0.1;
    
    var add_email_address_oACDS = new YAHOO.util.FunctionDataSource(validate_add_email_address_manually);
    add_email_address_oACDS.queryMatchContains = true;
    var add_email_address_oAutoComp = new YAHOO.widget.AutoComplete("add_email_address","add_email_address_Container", add_email_address_oACDS);
    add_email_address_oAutoComp.minQueryLength = 0; 
    add_email_address_oAutoComp.queryDelay = 0.1;
    
    
    Event.addListener("save_new_add_email_address_manually", "click", save_add_email_address_manually);
    Event.addListener("cancel_new_add_email_address_manually", "click", close_dialog_add_email_address);
    Event.addListener("delete_email_campaign", "click", delete_email_campaign);


   // Event.addListener('reset_new_email_campaign', "click", cancel_new_email_campaign);
   // Event.addListener('save_new_email_campaign', "click", save_new_email_campaign);

}

Event.onDOMReady(init);