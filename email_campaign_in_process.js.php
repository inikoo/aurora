<?php
include_once('common.php');
//include_once('class.EmailCampaign.php');


//$email_campaign_key=$_REQUEST['email_campaign_key'];

 //$email_campaign=new EmailCampaign($email_campaign_key);

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var dialog_add_email_address;
var dialog_add_email_address_from_list;
var validate_scope_data;
var validate_scope_metadata;
var dialog_preview_text_email;
var dialog_send_email_campaign;

function select_department(oArgs){
    var product_ordered_or=Dom.get('email_campaign_scope').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'d('+tables.table5.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('email_campaign_scope').value=product_ordered_or;
    dialog_department_list.hide();
    hide_filter(true,5);
     validate_general('email_campaign','scope', Dom.get('email_campaign_scope').value);

}

function select_family(oArgs){
    var product_ordered_or=Dom.get('email_campaign_scope').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'f('+tables.table6.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('email_campaign_scope').value=product_ordered_or;
    dialog_family_list.hide();
    hide_filter(true,6)
}
function select_product(oArgs){
    var product_ordered_or=Dom.get('email_campaign_scope').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table7.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('email_campaign_scope').value=product_ordered_or;
    dialog_product_list.hide();
    hide_filter(true,7)
}
function select_offer(oArgs){
    var product_ordered_or=Dom.get('email_campaign_scope').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'o('+tables.table8.getRecord(oArgs.target).getData('id').replace(/<.*?>/g, '')+')';

    Dom.get('email_campaign_scope').value=product_ordered_or;
    dialog_product_list.hide();
    hide_filter(true,8)
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	     //START OF THE TABLE =========================================================================================================================
		var store_key=Dom.get('store_id').value;

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

	
	
	
	 var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				];
			       
	    this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code","name"
			 ]};

	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", select_department);
           
           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
	    //
// --------------------------------------Department table ends here----------------------------------------------------------


// --------------------------------------Family table starts here--------------------------------------------------------
   var tableid=6; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource6 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    	    this.dataSource6.table_id=tableid;

	    this.dataSource6.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name'
			 ]};

	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource6
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table6.subscribe("rowMouseoverEvent", this.table6.onEventHighlightRow);
       this.table6.subscribe("rowMouseoutEvent", this.table6.onEventUnhighlightRow);
      this.table6.subscribe("rowClickEvent", select_family);
        this.table6.table_id=tableid;
           this.table6.subscribe("renderEvent", myrenderEvent);


	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table6.filter={key:'code',value:''};


   var tableid=7; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  			];
			       
		this.dataSource7 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=product_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    	    this.dataSource7.table_id=tableid;

	    this.dataSource7.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code","name"
			 ]};

	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource7
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table7.subscribe("rowMouseoverEvent", this.table7.onEventHighlightRow);
       this.table7.subscribe("rowMouseoutEvent", this.table7.onEventUnhighlightRow);
      this.table7.subscribe("rowClickEvent", select_product);
     


	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table7.filter={key:'code',value:''};


	 var tableid=8; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
                    {key:"name", label:"<?php echo _('Name')?>",width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                  // ,{key:"description", label:"<?php echo _('Description')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  			];
			       
		this.dataSource8 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=deal_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource8.connXhrMode = "queueRequests";
	    	    this.dataSource8.table_id=tableid;

	    this.dataSource8.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "description","name","id"
			 ]};

	    this.table8 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource8
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator8', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table8.subscribe("rowMouseoverEvent", this.table8.onEventHighlightRow);
       this.table8.subscribe("rowMouseoutEvent", this.table8.onEventUnhighlightRow);
      this.table8.subscribe("rowClickEvent", select_offer);
     


	    this.table8.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table8.filter={key:'code',value:''};


	
	
	
	};
    });

function cancel_edit_email_campaign(){
location.href='marketing.php';
}


function validate_email_campaign_name(query){
 validate_general('email_campaign','name',unescape(query));
}

function validate_email_campaign_objetive(query){
 validate_general('email_campaign','objetive',unescape(query));
}

function validate_email_campaign_scope(query){
 validate_general('email_campaign','scope',unescape(query));
}

function validate_email_campaign_subject(query){
validate_scope_metadata['email_campaign']['secondary_key']=Dom.get('current_email_contact_key');
 validate_general('email_campaign','subject',unescape(query));
}

function validate_email_campaign_content_text(query){

 validate_general('email_campaign','content_text',unescape(query));
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
		Dom.get('email_campaign_number_recipients').value=r.number_recipients;
		validate_general('full_email_campaign','email_recipients',r.number_recipients);
		
		if(r.ready_to_send){
		Dom.removeClass('preview_email_campaign','disabled');
				Dom.removeClass('send_email_campaign','disabled');

		}else{
			Dom.addClass('preview_email_campaign','disabled');
				Dom.addClass('send_email_campaign','disabled');
		
		}
		
		
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

function text_email(){

  


var email_campaign_key=Dom.get('email_campaign_key').value;
var request='ar_edit_marketing.php?tipo=select_plain_email_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key);

 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
            Dom.removeClass(['select_text_email','select_html_from_template_email','select_html_email'],'selected');
            Dom.setStyle(['text_email_fields','html_email_from_template_fields','html_email_fields'],'display','none')

            Dom.addClass('select_text_email','selected');
            Dom.setStyle('text_email_fields','display','')
            
		}else{
		    if(r.msg!=undefined)
		        Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
});



}

function set_html_from_template_email(){
var email_campaign_key=Dom.get('email_campaign_key').value;
var request='ar_edit_marketing.php?tipo=select_html_email_from_template_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key);
//alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

            Dom.removeClass(['select_text_email','select_html_from_template_email','select_html_email'],'selected');
            Dom.setStyle(['text_email_fields','html_email_from_template_fields','html_email_fields'],'display','none')

            Dom.addClass('select_html_from_template_email','selected');
            Dom.setStyle('html_email_from_template_fields','display','')
            
            changeHeight(Dom.get('template_email_iframe'))

		}else{
		    if(r.msg!=undefined)
		        Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
});

}

function html_email(){
var email_campaign_key=Dom.get('email_campaign_key').value;
var request='ar_edit_marketing.php?tipo=select_html_email_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key);
//alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

            Dom.removeClass(['select_text_email','select_html_from_template_email','select_html_email'],'selected');
            Dom.setStyle(['text_email_fields','html_email_from_template_fields','html_email_fields'],'display','none')

            Dom.addClass('select_html_email','selected');
            Dom.setStyle('html_email_fields','display','')

		}else{
		    if(r.msg!=undefined)
		        Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
});


}

function send_email_campaign(){

validate_scope('email_campaign');


Dom.setStyle('dialog_send_email_campaign_choose_when1','display','');
Dom.setStyle('other_time_form','display','none');

dialog_send_email_campaign.show();



}

function delete_email_campaign(){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=delete_email_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key);
//alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
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

function reset_edit_email_campaign(){
reset_edit_general('email_campaign');

}

function send_now(){
start_send(0)
}

function choose_time(){

Dom.setStyle('dialog_send_email_campaign_choose_when1','display','none');
Dom.setStyle('other_time_form','display','');

}

function send_other_time(){
user_input=Dom.get('end_email_campaign_datetime').value;
lag_seconds=Date.create(user_input).secondsFromNow();

if(isNaN(lag_seconds)){
	lag_seconds='Not Identified';
	Dom.get('time_tag').innerHTML=lag_seconds
	Dom.setStyle('time_tag','display','');
}
else{
	display_date=Date.create(user_input).format(Date.RFC1123);
	Dom.get('time_tag').innerHTML=display_date;
	//Dom.setStyle('time_tag','display','none');
	start_send(lag_seconds);
}
//alert(lag_seconds);return;


}


function start_send(lap_seconds){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=set_email_campaign_as_ready&email_campaign_key='+email_campaign_key+'&start_sending_in='+lap_seconds;
alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
//alert("email_campaign.php?id="+Dom.get('email_campaign_key').value);return;
            location.href="email_campaign.php?id="+Dom.get('email_campaign_key').value;
		}else{
		alert(r.msg)
		    //if(r.msg!=undefined)
		     //   Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });

}



function save_edit_email_campaign(){

EmailHTMLEditor.saveHTML();

save_edit_general('email_campaign');
}

function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;

switch ( branch ) {
	case 'email_campaign':
		switch ( key ) {
			case 'name':
				Dom.get('h1_email_campaign_name').innerHTML=newvalue;
				break;
			
			
		};
		break;
	
	
	
	
}

}

function changeHeight(iframe){
        try
        {
        
         
          var innerDoc = (iframe.contentDocument) ? iframe.contentDocument : iframe.contentWindow.document;
          
        
          if (innerDoc.body.offsetHeight) //ns6 syntax
          {
         // alert(innerDoc.body.offsetHeight)

            Dom.setStyle(iframe,'height',innerDoc.body.offsetHeight + 32  +'px');

             //iframe.height = innerDoc.body.offsetHeight + 32  +'px'; //Extra height FireFox
          }
          else if (iframe.Document && iframe.Document.body.scrollHeight) //ie5+ syntax
          {
                  Dom.setStyle(iframe,'height',iframe.Document.body.scrollHeight + 32  +'px');

          }else{
         
          Dom.setStyle(iframe,'height','700px');
            
          }
        }
        catch(err)
        {
          alert(err.message);
        }
      }



function preview_email_campaign(){
get_preview( Dom.get('preview_index').value ) 
dialog_preview_text_email.show()
}

function previous_preview(){
get_preview( parseInt(Dom.get('preview_index').value)-1 )

}

function next_preview(){
get_preview( parseInt(Dom.get('preview_index').value)+1 )
}


function get_preview( index ) {
	var email_campaign_key=Dom.get('email_campaign_key').value;
	var request='ar_marketing.php?tipo=preview_email_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key)+'&index='+index;

 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
             
                 
           Dom.setStyle(['tr_preview_plain_body','tr_preview_html_body','tr_preview_template_body'],'display','none') 
    
           
             Dom.get('preview_index').value=r.index;
             Dom.get('preview_formated_index').innerHTML=r.formated_index;
             Dom.get('preview_to').innerHTML=r.to;
             Dom.get('preview_subject').innerHTML=r.subject;
             
             if(r.type=='Plain'){
                Dom.setStyle('tr_preview_plain_body','display','')
                Dom.get('preview_plain_body').innerHTML=r.plain;

             }if(r.type=='HTML'){
                Dom.setStyle('tr_preview_plain_body','display','')
                              Dom.get('preview_plain_body').innerHTML=r.html;

             }else{
              Dom.setStyle('tr_preview_template_body','display','')
            Dom.get('preview_html_body').src=r.html_src;
              
             }
          
                
            
		}else{
		  
	    }
	    }
	    });
	
}

function html_editor_changed(){

validate_scope_data['email_campaign']['content_html_text']['changed']=true;
validate_scope('email_campaign');
}


function init(){

  init_search('marketing_store');

//changeHeight(Dom.get('template_email_iframe'))
//resizeFrame()

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
	        'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_name').innerHTML}]
	        },
	'objetive':{
	            'dbname':'Email Campaign Objective',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_objetive',
	            'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_objetive').innerHTML}]
	            },
	 'scope':{
	            'dbname':'Email Campaign Scope',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_scope',
	            'validation':[{'regexp':"^([a-z0-9\\-]+|(d|f|c)\\([a-z0-9\\-]+\\))(,([0-9a-z\\-]+|(d|f|c|o)\\([a-z0-9\\-\\_]+\\)))*$",'invalid_msg':Dom.get('invalid_email_campaign_scope').innerHTML}]
	            },  
	  'subject':{
	            'dbname':'Email Campaign Subject',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_subject',
	            'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_subjects').innerHTML}]
	            },             
	     'content_text':{
	            'dbname':'Email Campaign Content Text',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_content_text',
	            'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_contents').innerHTML}]
	            },               
	         'content_html_text':{
	            'dbname':'Email Campaign Content HTML',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'html_email_editor',
	            'validation':false
	            },            
	            
	   	 //           'validation':[{'regexp':"^(((d|f|c)\\()?[a-z0-9\\-\\)]+,?)+$",'invalid_msg':Dom.get('invalid_email_campaign_scope').innerHTML}]
         
	            
   },
 'add_email_address_manually':{
  	'email_address':{'dbname':'Email Address','changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'add_email_address','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]},
  	'email_contact_name':{'dbname':'Email Contact Name','changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'add_email_contact_name','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
   },
   
  'full_email_campaign':{
 'name':{'dbname':'Email Campaign Name',
	        'changed':false,
	        'validated':true,
	        'required':true,
	        'group':1,
	        'type':'item',
	        'name':'email_campaign_name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_name').innerHTML}]
	        },
	'objetive':{
	            'dbname':'Email Campaign Objective',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_objetive','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_objetive').innerHTML}]
	            },
 	   	'email_recipients':{
 	   	'changed':false,'validated':Dom.get('email_campaign_number_recipients').value>0?true:false,'required':true,'name':'email_campaign_number_recipients','validation':[{'numeric':"positive integer",'invalid_msg':Dom.get('invalid_email_campaign_recipients').innerHTML}]
 	   	},
 	   	'email_subjects':{
 	   	'changed':false,'validated':Dom.get('email_campaign_subjects').value!=''?true:false,'required':true,'name':'email_campaign_subjects','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_subjects').innerHTML}]
 	   	},
	'email_contents':{
 	   	'changed':false,'validated':Dom.get('email_campaign_contents').value!=''?true:false,'required':true,'name':'email_campaign_contents','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_contents').innerHTML}]
 	   	},
 },
 'preview_email_campaign':{
 	'email_subjects':{
 	   	'changed':false,'validated':Dom.get('email_campaign_subjects').value!=''?true:false,'required':true,'name':'email_campaign_subjects','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_subjects').innerHTML}]
 	   	},
	'email_contents':{
 	   	'changed':false,'validated':Dom.get('email_campaign_contents').value!=''?true:false,'required':true,'name':'email_campaign_contents','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_contents').innerHTML}]
 	   	}
 }
  
  
  }
validate_scope_metadata={
'email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value,'dynamic_second_key':'current_email_contact_key','second_key_name':'email_content_key'}
,'add_email_address_manually':{'type':'new','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}
,'full_email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}
,'preview_email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}

};


 

    dialog_add_email_address = new YAHOO.widget.Dialog("dialog_add_email_address", {context:["add_email_address_manually","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    dialog_add_email_address.render();
    Event.addListener("add_email_address_manually", "click", dialog_add_email_address.show,dialog_add_email_address , true);

  dialog_add_email_address_from_list = new YAHOO.widget.Dialog("dialog_add_email_address_from_list", {context:["add_email_address_from_customer_list","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_add_email_address_from_list.render();
    Event.addListener("add_email_address_from_customer_list", "click", dialog_add_email_address_from_list.show,dialog_add_email_address_from_list , true);

  dialog_preview_text_email = new YAHOO.widget.Dialog("dialog_preview_text_email", {context:["preview_email_campaign","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_preview_text_email.render();
    Event.addListener("preview_email_campaign", "click", preview_email_campaign);



  
   dialog_send_email_campaign = new YAHOO.widget.Dialog("dialog_send_email_campaign", {context:["send_email_campaign","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_send_email_campaign.render();
    Event.addListener("preview_email_campaign", "click", preview_email_campaign);
  
  
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
    
      var email_campaign_scope_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_scope);
    email_campaign_scope_oACDS.queryMatchContains = true;
    var email_campaign_scope_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_scope","email_campaign_scope_Container", email_campaign_scope_oACDS);
    email_campaign_scope_oAutoComp.minQueryLength = 0; 
    email_campaign_scope_oAutoComp.queryDelay = 0.1;
    
    var add_email_address_oACDS = new YAHOO.util.FunctionDataSource(validate_add_email_address_manually);
    add_email_address_oACDS.queryMatchContains = true;
    var add_email_address_oAutoComp = new YAHOO.widget.AutoComplete("add_email_address","add_email_address_Container", add_email_address_oACDS);
    add_email_address_oAutoComp.minQueryLength = 0; 
    add_email_address_oAutoComp.queryDelay = 0.1;
    
    var email_campaign_subject_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_subject);
    email_campaign_subject_oACDS.queryMatchContains = true;
    var email_campaign_subject_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_subject","email_campaign_subject_Container", email_campaign_subject_oACDS);
    email_campaign_subject_oAutoComp.minQueryLength = 0; 
    email_campaign_subject_oAutoComp.queryDelay = 0.1;
    
     var email_campaign_content_text_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_content_text);
    email_campaign_content_text_oACDS.queryMatchContains = true;
    var email_campaign_content_text_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_content_text","email_campaign_content_text_Container", email_campaign_content_text_oACDS);
    email_campaign_content_text_oAutoComp.minQueryLength = 0; 
    email_campaign_content_text_oAutoComp.queryDelay = 0.1;
   
    Event.addListener("save_new_add_email_address_manually", "click", save_add_email_address_manually);
    Event.addListener("cancel_new_add_email_address_manually", "click", close_dialog_add_email_address);
    Event.addListener("delete_email_campaign", "click", delete_email_campaign);
    Event.addListener("select_text_email", "click", text_email);
    Event.addListener("select_html_email", "click", html_email);

  Event.addListener("select_html_from_template_email", "click", set_html_from_template_email);


    Event.addListener("send_email_campaign", "click", send_email_campaign);
 

    Event.addListener('reset_edit_email_campaign', "click", reset_edit_email_campaign);
    Event.addListener('save_edit_email_campaign', "click", save_edit_email_campaign);
 
     dialog_department_list = new YAHOO.widget.Dialog("dialog_department_list", {context:["department","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_department_list.render();
    Event.addListener("department", "click", dialog_department_list.show,dialog_department_list , true);

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
    Event.addListener("family", "click", dialog_family_list.show,dialog_family_list , true);

    dialog_product_list = new YAHOO.widget.Dialog("dialog_product_list", {context:["product","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_product_list.render();
    Event.addListener("product", "click", dialog_product_list.show,dialog_product_list , true);
    
     dialog_offer_list = new YAHOO.widget.Dialog("dialog_offer_list", {context:["offer","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_offer_list.render();
    Event.addListener("offer", "click", dialog_offer_list.show,dialog_offer_list , true);
    
    
       var myConfig = {
        height: '300px',
        width: '600px',
        animate: true,
        dompath: true,
        focusAtStart: true
    };
    
    var state = 'off';
    
    
    
        EmailHTMLEditor = new YAHOO.widget.Editor('html_email_editor', myConfig);
    EmailHTMLEditor.on('toolbarLoaded', function() {
    
        var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
        
        this.toolbar.on('editcodeClick', function() {
        

        
            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';
                
                this.cleanHTML();
               
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
            
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
        
        this.on('editorKeyUp', html_editor_changed, this, true);
                this.on('editorDoubleClick', html_editor_changed, this, true);
                this.on('editorMouseDown', html_editor_changed, this, true);
                this.on('buttonClick', html_editor_changed, this, true);

        this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    }, EmailHTMLEditor, true);
    EmailHTMLEditor.render();
 
    
      Event.addListener("previous_preview", "click", previous_preview);
      Event.addListener("next_preview", "click", next_preview);



    

}

Event.onDOMReady(init);
