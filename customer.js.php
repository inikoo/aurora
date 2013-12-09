<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Customer.php');
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


//var dialog_export;
var dialog_make_order;
var dialog_quick_edit_Customer_Main_Contact_Name;
var dialog_quick_edit_Customer_Tax_Number;
var dialog_quick_edit_Customer_Registration_Number;

var dialog_quick_edit_Customer_Name;
var dialog_quick_edit_Customer_Main_Email;
var dialog_quick_edit_Customer_Main_Address;
var dialog_quick_edit_Customer_Main_Telephone;
var dialog_quick_edit_Customer_Main_Mobile;
var dialog_quick_edit_Customer_Website;
var dialog_quick_edit_Customer_Main_FAX;
var dialog_orders_in_process_found;
var customer_key=<?php echo $_REQUEST['customer_key']  ?>;
var customer_type="<?php echo $_REQUEST['customer_type']  ?>";
var dialog_edit_note;
var list_of_dialogs;


<?php
$customer=new Customer($_REQUEST['customer_key']);
print "var customer_id='".$customer->id."';";
print "var store_id='".$customer->data['Customer Store Key']."';";


foreach($customer->get_other_mobiles_data() as $key=>$value)
	printf('var dialog_quick_edit_Customer_Mobile%d;', $key);
foreach($customer->get_other_faxes_data() as $key=>$value)
	printf('var dialog_quick_edit_Customer_FAX%d;', $key);

foreach($customer->get_other_emails_data() as $key=>$value)
	printf('var dialog_quick_edit_Customer_Email%d;', $key);

foreach($customer->get_other_telephones_data() as $key=>$value)
	printf('var dialog_quick_edit_Customer_Telephone%d;', $key);

$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}


?>



var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";
var validate_scope_data=
{
    'customer_quick':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	,'email':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
		,'registration_number':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Registration_Number','validation':false}

	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}
	,'web':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Website','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Website')?>'}]}
<?php

foreach($customer->get_other_emails_data()  as $email_key=>$email  ){
printf(",'email%d':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Email%d','validation':[{'regexp':regexp_valid_email,'invalid_msg':'%s'}]}",
$email_key,
$email_key,
_('Invalid Email')
);
}
foreach($customer->get_other_telephones_data()  as $telephone_key=>$telephone  ){
printf(",'telephone%d':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Telephone%d','validation':[{'regexp':regex_valid_tel,'invalid_msg':'%s'}]}",
$telephone_key,
$telephone_key,
_('Invalid Telephone')
);
}
foreach($customer->get_other_faxes_data()  as $telephone_key=>$telephone  ){
printf(",'fax%d':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_FAX%d','validation':[{'regexp':regex_valid_tel,'invalid_msg':'%s'}]}",
$telephone_key,
$telephone_key,
_('Invalid Fax')
);
}
foreach($customer->get_other_mobiles_data()  as $telephone_key=>$telephone  ){
printf(",'mobile%d':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Mobile%d','validation':[{'regexp':regex_valid_tel,'invalid_msg':'%s'}]}",
$telephone_key,
$telephone_key,
_('Invalid Mobile')
);
}


?>




	//,'other_email':{'ar':'find','ar_request':'ar_contacts.php?tipo=email_in_other_customer&customer_key='+customer_id+'&store_key='+store_id+'&query=','changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
//,'other_telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_Telephone','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$",'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	//,'other_mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
	//,'other_fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}
  //	,'registration_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Registration_Number','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Company Registration Number')?>'}]}


  },
  'billing_quick':{
  	//'fiscal_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Fiscal_Name','ar':false,'validation':[{'regexp':"[a-zA-Z]+",'invalid_msg':'<?php echo _('Invalid Fiscal Name')?>'}]}
  	'tax_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Tax_Number','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Tax Number')?>'}]}

  }

};



var validate_scope_metadata={
'customer_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}
,'billing_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}

};

function make_order(){

  
    
    var data={
	'courier':Dom.get('make_order_courier').value,
	'special_instructions':Dom.get('make_order_special_instructions').value,
	'payment_method':Dom.get('make_order_payment_method').value,
	'gold_reward':Dom.get('gold_reward').value,
	'offer':Dom.get('offer').value,
    };
    //alert('customer_csv.php?id='+customer_id+'&data='+encodeURIComponent(YAHOO.lang.JSON.stringify(data)))

// var value=new Object()
  //      for (i in data)
    //        value[i]=my_encodeURIComponent(data[i]);

        var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data));

//alert('customer_csv.php?id='+customer_id+'&data='+json_value);
  //return;
	  window.open('customer_csv.php?id='+customer_key+'&data='+json_value,'Download');
    close_dialog('make_order');

}

function showdetails(o){

 

    var history_id=o.getAttribute('hid');
    var details=o.getAttribute('d');
    tr=Dom.getAncestorByTagName(o,'tr');
    row_index=tr.rowIndex+1;
    var table=Dom.getAncestorByTagName(o,'table');
    //alert(o);
    if(details=='no'){
	row_class=tr.getAttribute('class');

	var request="ar_history.php?tipo=history_details&id="+history_id;
	//alert(request)	
YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			var x=table.insertRow(row_index);
			x.setAttribute('class',row_class);
			x.setAttribute('id','chd'+history_id);

			var c1=x.insertCell(0);
			var c2=x.insertCell(1);
			var c3=x.insertCell(2);
			var c4=x.insertCell(3);
			x.setAttribute('style','padding:10px 0 ;border-top:none')
			c1.innerHTML="";
			c2.innerHTML="";
			c3.setAttribute('style','padding:10px;');
c4.innerHTML="";

			c3.setAttribute('colspan',3);
			c3.innerHTML=r.details;
			Dom.get('ch'+history_id).src='art/icons/showed.png';
			Dom.get('ch'+history_id).setAttribute('d','yes');

			
		    }
		       
		}
	    });   
    }else{
	Dom.get('ch'+history_id).src='art/icons/closed.png';
	Dom.get('ch'+history_id).setAttribute('d','no');
	table.deleteRow(row_index);

    }
     
	
}




 
Event.addListener(window, "load", function() {
	    tables = new function() {
		    
		    var tableid=0; 
		    var tableDivEL="table"+tableid;  
		    
		    
		    var myRowFormatter = function(elTr, oRecord) {		   
				if (oRecord.getData('type') =='Orders') {
					Dom.addClass(elTr, 'customer_history_orders');
				}else if (oRecord.getData('type') =='Notes') {
					Dom.addClass(elTr, 'customer_history_notes');
				}else if (oRecord.getData('type') =='Changes') {
					Dom.addClass(elTr, 'customer_history_changes');
				}
				return true;
			}; 
		    
		    
		this.prepare_note = function(elLiner, oRecord, oColumn, oData) {
          
            if(oRecord.getData("strikethrough")=="Yes") { 
            Dom.setStyle(elLiner,'text-decoration','line-through');
            Dom.setStyle(elLiner,'color','#777');

            }
            elLiner.innerHTML=oData
        };
        		    
		    var ColumnDefs = [
  					{key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				       ,{key:"type", label:"", width:0,sortable:false,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:70}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:500}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'dialog',object:'delete_note'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'customer_history'}

					   ];
		request="ar_history.php?tipo=customer_history&parent=customer&parent_key="+customer_key+"&sf=0&tid="+tableid
		//alert(request)
		    this.dataSource0  = new YAHOO.util.DataSource(request);
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
                  fields: ["note","date","time","handle","delete","can_delete" ,"delete_type","key","edit","type","strikethrough"]};
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['history']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table0.filter={key:'<?php echo$_SESSION['state']['customer']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['history']['f_value']?>'};

	        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table0.subscribe("cellClickEvent", onCellClick);            
			this.table0.table_id=tableid;
     		this.table0.subscribe("renderEvent", myrenderEvent);



	    //   Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    //Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)









    var tableid=1; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"subject", label:"<?php echo _('Family')?>",className:"aleft",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   				      ,{key:"description", label:"<?php echo _('Description')?>",className:"aleft",width:270,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ,{key:"orders", label:"<?php echo _('Orders')?>",className:"aright",width:60}

				      ,{key:"ordered", label:"<?php echo _('Ordered')?>",className:"aright",width:60}
				      ,{key:"dispatched", label:"<?php echo _('Dispatched')?>", className:"aright",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   ];
		
		    this.dataSource1  = new YAHOO.util.DataSource("ar_contacts.php?tipo=assets_dispatched_to_customer&customer_key="+customer_key+"&tid="+tableid);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.table_id=tableid;
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
		fields: ["subject","ordered","dispatched","orders","description" ]};
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['assets']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['assets']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['assets']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table1.filter={key:'<?php echo$_SESSION['state']['customer']['assets']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['assets']['f_value']?>'};

	   this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);



// ------------------------------------- orders start -------------------------------

 var tableid=2; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		   
		    var ColumnDefs =  [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"last_update", label:"<?php echo _('Last Updated')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"current_state",label:"<?php echo _('Current State')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
                                        {key:"order_date", label:"<?php echo _('Order Date')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      {key:"total_amount", label:"<?php echo _('Total')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 
					
					 ];
		//alert("ar_contacts.php?tipo=customer_orders&customer_key="+customer_key+"&sf=0&tid="+tableid);
		    this.dataSource2  = new YAHOO.util.DataSource("ar_contacts.php?tipo=customer_orders&customer_key="+customer_key+"&sf=0&tid="+tableid);
		    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.table_id=tableid;
	    this.dataSource2.responseSchema = {
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
		
		fields: ["public_id","last_update","current_state","order_date","total_amount"]};
			  
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['customer']['orders']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['orders']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table2.filter={key:'<?php echo$_SESSION['state']['customer']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['orders']['f_value']?>'};
this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);
	   

	   
	   //login stat table
	   
	   
	       

	    var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			      // {key:"user", label:"<?php echo _('User')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      {key:"ip", label:"<?php echo _('IP Address')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"login_date", label:"<?php echo _('Login Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"logout_date", label:"<?php echo _('Logout Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}	
			];
			       
	    this.dataSource3 = new YAHOO.util.DataSource("ar_users.php?tipo=customer_user_login_history&tableid=3&user_key="+customer_key);
		//alert("ar_users.php?tipo=customer_user_login_history&tableid=3&user_key="+customer_key)
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
		
		
		fields: ["user","ip","login_date","logout_date"]};


	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:50,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['staff_user']['login_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['staff_user']['login_history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'<?php echo$_SESSION['state']['staff_user']['login_history']['f_field']?>',value:'<?php echo$_SESSION['state']['staff_user']['login_history']['f_value']?>'};
	   this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);
	   
	
// -------------------------------------orders end ----------------------------------




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
     


	    this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table100.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //



	
		};
    });


function close_dialog_orders_in_process_found(){
	dialog_orders_in_process_found.hide()

}

function force_take_order(){

	dialog_orders_in_process_found.hide()
Dom.get('take_order_img').src='art/loading.gif'

location.href='order.php?new=1&customer_key='+customer_key; 
}

function take_order(){

Dom.get('take_order_img').src='art/loading.gif'


  	var request='ar_contacts.php?tipo=number_orders_in_process&customer_key='+Dom.get('customer_key').value
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);


		
		if(r.state=='200'){   
		
		if(r.orders_in_process>0){
			Dom.get('take_order_img').src='art/icons/add.png'
		region1 = Dom.getRegion('take_order'); 
    region2 = Dom.getRegion('dialog_orders_in_process_found'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_orders_in_process_found', pos);
		
Dom.get('orders_in_process_found_orders_list').innerHTML=r.orders_list
Dom.get('orders_in_process_found_msg').innerHTML=r.msg

	dialog_orders_in_process_found.show()

		}else{
    location.href='order.php?new=1&customer_key='+customer_key; 
	}
	
	}

		},failure:function(o){
		    
		}
	    
	    });
               








}

function post_comment_update_other(r){
	if(r.scope=='email'){
		//alert('comment_update_other_email')
		//alert(r.key);
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			eval('dialog_quick_edit_Customer_Email'+r.scope_key).hide();
			Dom.get('Customer_Email'+r.scope_key+'_msg').innerHTML='';
			Dom.get('Customer_Email'+r.scope_key+'_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_Email'+r.scope_key+'_msg').innerHTML='comment_failure';
		}
		
	}
	if(r.scope=='telephone'){
		//alert('comment_update tele');
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			eval('dialog_quick_edit_Customer_Telephone'+r.scope_key).hide();
			Dom.get('Customer_Telephone'+r.scope_key+'_msg').innerHTML='';
			Dom.get('Customer_Telephone'+r.scope_key+'_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_Telephone'+r.scope_key+'_msg').innerHTML='comment_failure';
		}
		
	}	
	if(r.scope=='mobile'){
		//alert('comment_update mob');
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			eval('dialog_quick_edit_Customer_Mobile'+r.scope_key).hide();
			Dom.get('Customer_Mobile'+r.scope_key+'_msg').innerHTML='';
			Dom.get('Customer_Mobile'+r.scope_key+'_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_Mobile'+r.scope_key+'_msg').innerHTML='comment_failure';
		}
		
	}
	if(r.scope=='fax'){
		//alert('comment_update fax');
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			eval('dialog_quick_edit_Customer_FAX'+r.scope_key).hide();
			Dom.get('Customer_FAX'+r.scope_key+'_msg').innerHTML='';
			Dom.get('Customer_FAX'+r.scope_key+'_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_FAX'+r.scope_key+'_msg').innerHTML='comment_failure';
		}
		
	}

}

function post_comment_update(r){
	if(r.scope=='email'){
		//alert('comment_update')

		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			dialog_quick_edit_Customer_Main_Email.hide();
			Dom.get('Customer_Main_Email_msg').innerHTML='';
			//alert(r.newvalue)
			Dom.get('Customer_Main_Email_comment').setAttribute('ovalue',r.newvalue);
			
		}
		else{
			Dom.get('Customer_Main_Email_msg').innerHTML='comment_failure';
		}
		
	}
	if(r.scope=='telephone'){
		//alert('comment_update tele');
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			dialog_quick_edit_Customer_Main_Email.hide();
			Dom.get('Customer_Main_Telephone_msg').innerHTML='';
			Dom.get('Customer_Main_Telephone_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_Main_Telephone_msg').innerHTML='comment_failure';
		}
		
	}	
	if(r.scope=='mobile'){
		//alert('comment_update mob');
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			dialog_quick_edit_Customer_Main_Mobile.hide();
			Dom.get('Customer_Main_Mobile_msg').innerHTML='';
			Dom.get('Customer_Main_Mobile_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_Main_Mobile_msg').innerHTML='comment_failure';
		}
		
	}
	if(r.scope=='fax'){
		//alert('comment_update fax');
		Dom.get(r.key).innerHTML=r.newvalue;
		if(r.state==200){
			dialog_quick_edit_Customer_Main_FAX.hide();
			Dom.get('Customer_Main_FAX_msg').innerHTML='';
			Dom.get('Customer_Main_FAX_comment').setAttribute('ovalue',r.newvalue);
		}
		else{
			Dom.get('Customer_Main_FAX_msg').innerHTML='comment_failure';
		}
		
	}

}

function post_item_updated_actions(branch,r){

//alert(branch+' '+r.key)



if(r.key=='registration_number'){
		Dom.get('Customer_Registration_Number').value=r.newvalue;
		Dom.get('Customer_Registration_Number').setAttribute('ovalue',r.newvalue);
		Dom.get('Customer_Registration_Number_msg').innerHTML='';
		Dom.get('registration_number').innerHTML=r.newvalue;
	}
	else if(r.key=='tax_number'){
		Dom.get('Customer_Tax_Number').value=r.newvalue;
		Dom.get('Customer_Tax_Number').setAttribute('ovalue',r.newvalue);
		Dom.get('Customer_Tax_Number_msg').innerHTML='';
		Dom.get('tax').innerHTML=r.newvalue;
		Dom.get('check_tax_number').src='art/icons/taxation.png';
	}
	else if(r.key=='contact'){
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
	}else if(r.key=='name'){
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
	}else{
	
		window.location.reload();
	}
	
	
	
//	if(branch=='customer_quick' || branch=='billing_quick'){
eval('dialog_quick_edit_'+validate_scope_data[branch][r.key].name).hide();
//}
	
	/*
	else if(r.key=='telephone'){
	
	
	
	//	change_comment('telephone', <?php echo (($customer->get('Customer Main Telephone Key'))?$customer->get('Customer Main Telephone Key'):'0')?>);
	//	save_comment();
		//if(r.state!=200) return;
		
		//dialog_quick_edit_Customer_Main_Telephone.hide();
		//Dom.get('main_telephone').innerHTML=r.newvalue;
		//Dom.get('Customer_Main_Telephone').innerHTML=r.newvalue;
		//Dom.get('contact_telephone_id').innerHTML=r.newvalue;
		D//om.get('Customer_Main_Telephone_msg').innerHTML='';
		
	}
	
	
	
	
	
	if(r.key=='mobile'){
		//alert('in_mob')
		change_comment('mobile', <?php echo (($customer->get('Customer Main Mobile Key'))?$customer->get('Customer Main Mobile Key'):'0')?>);
		save_comment();
		if(r.state!=200) return;
		
		dialog_quick_edit_Customer_Main_Mobile.hide();
		Dom.get('main_mobile').innerHTML=r.newvalue;
		//Dom.get('Customer_Mobile').innerHTML=r.newvalue;
		Dom.get('Customer_Main_Mobile_msg').innerHTML='';
	}
	else if(r.key=='email'){
		//alert('in_email')
		change_comment('email', <?php echo (($customer->get('Customer Main Email Key'))?$customer->get('Customer Main Email Key'):'0')?>);
		save_comment();
		//Dom.get('main_email_comment').innerHTML=r.newvalue;
		if(r.state!=200) return;
		dialog_quick_edit_Customer_Main_Email.hide();
		Dom.get('main_email').innerHTML='<a href="mailto:'+r.newvalue+'">'+r.newvalue+'</a>';
		//Dom.get('Customer_Main_Email').innerHTML=r.newvalue;
		//Dom.get('contact_email_id').innerHTML='<a href="mailto:'+r.newvalue+'">'+r.newvalue+'</a>';
		Dom.get('Customer_Main_Email_msg').innerHTML='';
		
	}
	else if(r.key.match(/email/gi)){
		var email_id=r.key.split('email');
		
		//alert('other_email: '+email_id[1]);
		change_other_comment('email', email_id[1]);
		save_comment_other();
		if(r.state!=200) return;
	
		
		Dom.get(r.key).innerHTML='<a href="mailto:'+r.newvalue+'">'+r.newvalue+'</a>';
		//Dom.get('Customer_Email'+email_id[1]).innerHTML=r.newvalue;
		eval('dialog_quick_edit_Customer_Email'+email_id[1]).hide();
		Dom.get('Customer_Email'+email_id[1]+'_msg').innerHTML='';
	}
	else if(r.key=='telephone'){
		change_comment('telephone', <?php echo (($customer->get('Customer Main Telephone Key'))?$customer->get('Customer Main Telephone Key'):'0')?>);
		save_comment();
		if(r.state!=200) return;
		
		dialog_quick_edit_Customer_Main_Telephone.hide();
		Dom.get('main_telephone').innerHTML=r.newvalue;
		//Dom.get('Customer_Main_Telephone').innerHTML=r.newvalue;
		//Dom.get('contact_telephone_id').innerHTML=r.newvalue;
		Dom.get('Customer_Main_Telephone_msg').innerHTML='';
		
	}
	else if(r.key=='fax'){
		//alert('in fax')
		change_comment('fax', <?php echo (($customer->get('Customer Main FAX Key'))?$customer->get('Customer Main FAX Key'):'0')?>);
		save_comment();
		if(r.state!=200) return;
		
		dialog_quick_edit_Customer_Main_FAX.hide();
		Dom.get('main_fax').innerHTML=r.newvalue;
		//Dom.get('Customer_Main_FAX').innerHTML=r.newvalue;
		//Dom.get('contact_fax_id').innerHTML=r.newvalue;
		Dom.get('Customer_Main_FAX_msg').innerHTML='';
		
	}
	else if(r.key.match(/telephone/gi)){

		var telephone_id=r.key.split('telephone');
		
		change_other_comment('telephone', telephone_id[1]);
		save_comment_other();
		if(r.state!=200) return;
		
		Dom.get(r.key).innerHTML=r.newvalue;
		//Dom.get('Customer_Telephone'+telephone_id[1]).innerHTML=r.newvalue;
		eval('dialog_quick_edit_Customer_Telephone'+telephone_id[1]).hide();
		Dom.get('Customer_Telephone'+telephone_id[1]+'_msg').innerHTML='';
		
	}
	else if(r.key.match(/fax/gi)){

		var fax_id=r.key.split('fax');
		
		change_other_comment('fax', fax_id[1]);
		save_comment_other();
		if(r.state!=200) return;
		
		Dom.get(r.key).innerHTML=r.newvalue;
		//Dom.get('Customer_FAX'+fax_id[1]).innerHTML=r.newvalue;
		eval('dialog_quick_edit_Customer_FAX'+fax_id[1]).hide();
		Dom.get('Customer_FAX'+fax_id[1]+'_msg').innerHTML='';
		
	}
	else if(r.key.match(/mobile/gi)){
		var mobile_id=r.key.split('mobile');
		change_other_comment('mobile', mobile_id[1]);
		save_comment_other();
		if(r.state!=200) return;
		

		Dom.get(r.key).innerHTML=r.newvalue;
		//Dom.get('Customer_Mobile'+mobile_id[1]).innerHTML=r.newvalue;
		eval('dialog_quick_edit_Customer_Mobile'+mobile_id[1]).hide();
		Dom.get('Customer_Mobile'+mobile_id[1]+'_msg').innerHTML='';
		
	}
	else if(branch=='address'){
		dialog_quick_edit_Customer_Main_Address.hide();
		Dom.get('main_address').innerHTML=r.xhtml_address;
		//if(r.is_main_delivery=='Yes')
		//	Dom.get('main_delivery_address').innerHTML=r.xhtml_address;
	}
	//else
		//alert('non');
		
		
		*/
		
}

function post_edit_address(){
window.location.reload();
}

function save_quick_edit_name(){
	//alert('validate name');
	//validate_customer_name();
    save_edit_general_bulk('customer_quick');
	//Dom.setStyle('dialog_quick_edit_'+field_name,'display','none')
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function save_comment(){

var tipo=Dom.get('comment_scope').value+'_label'+Dom.get('comment_scope_key').value;

 var data_to_update=new Object;
 data_to_update[tipo]={'okey':tipo,'value':Dom.get('comment').value}
 //data_to_update[tipo]={'okey':tipo,'value':'test val'}
//alert('new val:' +Dom.get("comment").value)
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id
//alert(request);

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
				//alert('ok');
				post_comment_update(r);
		    }else{}
				//alert('not');
				//post_comment_update(r);
		}
		}
	    });        
	

}

function save_comment_other(){

var tipo=Dom.get('comment_scope').value+'_label'+Dom.get('comment_scope_key').value;

 var data_to_update=new Object;
 data_to_update[tipo]={'okey':tipo,'value':Dom.get('comment').value}
 //data_to_update[tipo]={'okey':tipo,'value':'test val'}
//alert('new val:' +Dom.get("comment").value)
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id
//alert(request);

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
				//alert('ok');
				post_comment_update_other(r);
		    }else{}
				//alert('not');
				//post_comment_update_other(r);
		}
		}
	    });        
	

}

function change_other_comment(type,key){
 //alert('type:'+type+' key:'+key)
 Dom.get('comment_scope_key').value=key;
 Dom.get('comment_scope').value=type;

 if(type=='email'){
    //Dom.get('comment').value=Dom.get(['Customer_Email'+key+'_comment']).value;
	Dom.get('comment').value=Dom.get('Customer_Email'+key+'_comment').value;
	//Dom.get('comment').value=Dom.get('Customer_Email46524_comment').value;
 }else if(type=='telephone'){
    Dom.get('comment').value=Dom.get('Customer_Telephone'+key+'_comment').value;
 }else if(type=='mobile'){
    Dom.get('comment').value=Dom.get('Customer_Mobile'+key+'_comment').value;
 }else if(type=='fax'){
    Dom.get('comment').value=Dom.get('Customer_FAX'+key+'_comment').value;
 }

 //alert('comment value: '+ Dom.get('comment').value);
}

function change_comment(type,key){
 //alert('type:'+type+' key:'+key)
 Dom.get('comment_scope_key').value=key;
 Dom.get('comment_scope').value=type;

 if(type=='email'){
    Dom.get('comment').value=Dom.get('Customer_Main_Email_comment').value;
 }else if(type=='telephone'){
    Dom.get('comment').value=Dom.get('Customer_Main_Telephone_comment').value;
 }else if(type=='mobile'){
    Dom.get('comment').value=Dom.get('Customer_Main_Mobile_comment').value;
 }else if(type=='fax'){
    Dom.get('comment').value=Dom.get('Customer_Main_FAX_comment').value;
 }

 //alert('comment value: '+ Dom.get('comment').value);
}

function save_quick_edit_main_contact_name(){

save_edit_general_bulk('customer_quick');
}


function save_quick_edit_tax_number(){



	save_edit_general_bulk('billing_quick');
}

function save_quick_edit_registration_number(){

	save_edit_general_bulk('customer_quick');
}

function save_quick_edit_email(){
	Dom.setStyle('save_quick_edit_email','display','none')
	Dom.setStyle('close_quick_edit_email','display','none')
		Dom.setStyle('Customer_Main_Email_wait','display','')

    save_edit_general_bulk('customer_quick');

}
function save_quick_edit_telephone(){
	//alert('telephone');
	//validate_customer_telephone();
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_mobile(){
	//alert('mobile');
	//validate_customer_mobile();
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_web(){

    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function save_quick_edit_fax(){
	//alert('fax');
	//validate_customer_fax();
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}


function save_quick_edit_other_email(email_key){


	//alert(query);
	//validate_customer_email_other(email_key);
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}


function save_quick_edit_other_telephone(telephone_key){

	//alert('other_telephone');
	//alert(query);
	//validate_customer_telephone_other(telephone_key);
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}


function save_quick_edit_other_mobile(mobile_key){

	//alert('other_mobile');
	//alert(query);
	//validate_customer_mobile_other(mobile_key);
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function validate_customer_mobile_other(query,id){
    id=id.scope.mobile_id;
    if(query==''){
        validate_scope_data.customer_quick['mobile'+id].validated=true;
        if(Dom.get(validate_scope_data.customer_quick['mobile'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer_quick['mobile'+id].changed=true;
        }else{
            validate_scope_data.customer_quick['mobile'+id].changed=false;
        }
        validate_scope('customer_quick'); 
        Dom.get(validate_scope_data.customer_quick['mobile'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
     
    }else{
        validate_general('customer_quick','mobile'+id,unescape(query));
    }
}

function save_quick_edit_other_fax(fax_key){

	//alert('other_fax');
	//alert(query);
	//validate_customer_fax_other(fax_key);
    save_edit_general_bulk('customer_quick');
	//window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function validate_customer_fax_other(query,id){
    id=id.scope.fax_id;
    if(query==''){
        validate_scope_data.customer_quick['fax'+id].validated=true;
        if(Dom.get(validate_scope_data.customer_quick['fax'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer_quick['fax'+id].changed=true;
        }else{
            validate_scope_data.customer_quick['fax'+id].changed=false;
        }
        validate_scope('customer_quick'); 
        Dom.get(validate_scope_data.customer_quick['fax'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
     
    }else{
        validate_general('customer_quick','fax'+id,unescape(query));
    }
}

function validate_customer_telephone_other(query,id){
    id=id.scope.telephone_id;
    if(query==''){
        validate_scope_data.customer_quick['telephone'+id].validated=true;
        if(Dom.get(validate_scope_data.customer_quick['telephone'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer_quick['telephone'+id].changed=true;
        }else{
            validate_scope_data.customer_quick['telephone'+id].changed=false;
        }
        validate_scope('customer_quick'); 
        Dom.get(validate_scope_data.customer_quick['telephone'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
     
    }else{
        validate_general('customer_quick','telephone'+id,unescape(query));
    }
}

function change_view(){
ids=['orders','history','products','details', 'login_stat'];
block_ids=['block_orders','block_history','block_products','block_details', 'block_login_stat'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');

Dom.removeClass(ids,'selected');

Dom.addClass(this,'selected');
//alert('ar_sessions.php?tipo=update&keys=customer-view&value='+this.id)
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-view&value='+this.id ,{});
}

var oMenu;



function change_elements(){

ids=['elements_changes','elements_orders','elements_notes','elements_attachments','elements_emails','elements_weblog'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=0;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}


function validate_customer_email_other(query,id){
//alert('query:'+query + ' id:'+id )



    if(query==''){
        validate_scope_data.customer_quick['email'+id].validated=true;
        if(Dom.get(validate_scope_data.customer_quick['email'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer_quick['email'+id].changed=true;
        }else{
            validate_scope_data.customer_quick['email'+id].changed=false;
        }
        validate_scope('customer_quick'); 
        Dom.get(validate_scope_data.customer_quick['email'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
     
    }else{
        validate_general('customer_quick','email'+id,unescape(query));
    }
}

function validate_customer_email_other_comment(query,id){
	id=id.scope.email_id;
	if(Dom.get(validate_scope_data.customer_quick['email'+id].name).getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick['email'+id].changed=true;
	}else{
		validate_scope_data.customer_quick['email'+id].changed=false;
	}
	//alert(validate_scope_data.customer_quick['email'+id].changed)
}


function validate_customer_telephone_other_comment(query,id){
	id=id.scope.telephone_id;
	if(Dom.get(validate_scope_data.customer_quick['telephone'+id].name).getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick['telephone'+id].changed=true;
	}else{
		validate_scope_data.customer_quick['telephone'+id].changed=false;
	}
	//alert(validate_scope_data.customer_quick['telephone'+id].changed)
}

function validate_customer_fax_other_comment(query,id){
	id=id.scope.fax_id;
	if(Dom.get(validate_scope_data.customer_quick['fax'+id].name).getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick['fax'+id].changed=true;
	}else{
		validate_scope_data.customer_quick['fax'+id].changed=false;
	}
	//alert(validate_scope_data.customer_quick['fax'+id].changed)
}

function validate_customer_mobile_other_comment(query,id){
	id=id.scope.mobile_id;
	if(Dom.get(validate_scope_data.customer_quick['mobile'+id].name).getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick['mobile'+id].changed=true;
	}else{
		validate_scope_data.customer_quick['mobile'+id].changed=false;
	}
	//alert(validate_scope_data.customer_quick['mobile'+id].changed)
}




function save_tax_details_match(e,value){

   Dom.setStyle('check_tax_number_wait','display','');
         Dom.setStyle('check_tax_number_buttons','display','none');
         
               Dom.setStyle('check_tax_number_result_tr','display','none');
    
    	   var request='ar_edit_contacts.php?tipo=update_tax_number_match&customer_key='+Dom.get('customer_key').value+'&value='+value


    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	Dom.setStyle(['submit_register','cancel_register'],'visibility','visible');

	//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
		     
		     	if(r.match){
		     	Dom.get('check_tax_number').src='art/icons/taxation_green.png';
		     	}else{
		     	Dom.get('check_tax_number').src='art/icons/taxation_yellow.png';
		     	
		     	}
		     
		     	dialog_check_tax_number.hide();
			}

		},failure:function(o){
		   
		}
	    
	    });
               
         
}

function request_catalogue(){

    
    	   var request='ar_edit_contacts.php?tipo=add_customer_send_post&customer_key='+Dom.get('customer_key').value+'&post_type=Catalogue'


    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {

		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
		     
		     
			}

		},failure:function(o){
		    
		}
	    
	    });


}


function show_dialog_check_tax_number(){

	region1 = Dom.getRegion('check_tax_number'); 
    region2 = Dom.getRegion('dialog_check_tax_number'); 
	var pos =[region1.right+5,region1.top]
	Dom.setXY('dialog_check_tax_number', pos);
	
	Dom.get('check_tax_number_result').innerHTML='';
		      Dom.setStyle('check_tax_number_result_tr','display','none');
		      		      Dom.setStyle('check_tax_number_buttons','display','none');
		      		      Dom.setStyle('check_tax_number_wait','display','');
	Dom.setStyle('save_tax_details_not_match','display','none')
					Dom.setStyle('save_tax_details_match','display','none')	      		      
		      		      Dom.setStyle('close_check_tax_number','display','none')	    
							      		        				Dom.setStyle('check_tax_number_name_tr','display','none')
						      		        				Dom.setStyle('check_tax_number_address_tr','display','none')

	
	dialog_check_tax_number.show()
	
	 Dom.get('check_tax_number_result').innerHTML='';
	
	   var request='ar_contacts.php?tipo=check_tax_number&customer_key='+Dom.get('customer_key').value

 
     
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	Dom.setStyle(['submit_register','cancel_register'],'visibility','visible');

		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		 Dom.get('check_tax_number_result').innerHTML=r.msg;
		      Dom.setStyle('check_tax_number_result_tr','display','');
		      		      Dom.setStyle('check_tax_number_buttons','display','');
		      		      Dom.setStyle('check_tax_number_wait','display','none');
		    if(r.state=='200'){
		     

				if(r.result.valid){
					Dom.get('check_tax_number').src='art/icons/taxation_green.png';
					
				
				}else{
				Dom.get('check_tax_number').src='art/icons/taxation_error.png';
				
				}
				
				if(r.result.name!= undefined || r.result.address!= undefined){
						      		        
						      		        if(r.result.name!= undefined){
						      		        				Dom.setStyle('check_tax_number_name_tr','display','')
						      		        				Dom.get('check_tax_number_name').innerHTML=r.result.name

						      		        }
						      		            if(r.result.address!= undefined){
						      		        				Dom.setStyle('check_tax_number_address_tr','display','')
						      		        				Dom.get('check_tax_number_address').innerHTML=r.result.address

						      		        }

				Dom.setStyle('save_tax_details_not_match','display','')
					Dom.setStyle('save_tax_details_match','display','')	      		     
				}else{
					
				  Dom.setStyle('close_check_tax_number','display','')	
				}
	
			        
			        
		    }else{
		  
		      Dom.setStyle('close_check_tax_number','display','')
		    }
		 
			

		},failure:function(o){
		    
		}
	    
	    });
	
	
}

function close_dialog_check_tax_number(){
	dialog_check_tax_number.hide()
}





function init(){

list_of_dialogs=["dialog_quick_edit_Customer_Name", 
"dialog_quick_edit_Customer_Name",
"dialog_quick_edit_Customer_Main_Address",
"dialog_quick_edit_Customer_Tax_Number",
"dialog_quick_edit_Customer_Registration_Number",

"dialog_quick_edit_Customer_Main_Contact_Name",
"dialog_quick_edit_Customer_Main_Email",
"dialog_quick_edit_Customer_Main_Telephone",
"dialog_quick_edit_Customer_Main_Mobile",
"dialog_quick_edit_Customer_Website",
"dialog_quick_edit_Customer_Main_FAX"
<?php
foreach($customer->get_other_emails_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Customer_Email%d\"",$key);
foreach($customer->get_other_telephones_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Customer_Telephone%d\"",$key);
foreach($customer->get_other_mobiles_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Customer_Mobile%d\"",$key);
foreach($customer->get_other_faxes_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Customer_FAX%d\"",$key);
?>

];


Event.addListener(['elements_changes','elements_orders','elements_notes','elements_attachments','elements_emails','elements_weblog'], "click",change_elements);


  init_search('customers_store');
Event.addListener(['orders','history','products','details', 'login_stat'], "click",change_view);


 Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 


    var alt_shortcuts = function(type, args, obj) {
	if(args[0]==78){
	    window.location=Dom.get("next").href;
	}else if(args[0]==80){
	    window.location=Dom.get("next").href;
	}

    }

    kpl1 = new YAHOO.util.KeyListener(document, { alt:true ,keys:[78,80] }, { fn:alt_shortcuts } );
    kpl1.enable();




dialog_check_tax_number = new YAHOO.widget.Dialog("dialog_check_tax_number", {visible : false,close:true,underlay: "none",draggable:false});
dialog_check_tax_number.render();
Event.addListener("check_tax_number", "click", show_dialog_check_tax_number);
Event.addListener(["close_check_tax_number"], "click", close_dialog_check_tax_number);
Event.addListener(["save_tax_details_not_match"], "click", save_tax_details_match,'No');
Event.addListener(["save_tax_details_match"], "click", save_tax_details_match,'Yes');



dialog_orders_in_process_found = new YAHOO.widget.Dialog("dialog_orders_in_process_found", {visible : false,close:true,underlay: "none",draggable:false});
dialog_orders_in_process_found.render();



dialog_make_order = new YAHOO.widget.Dialog("dialog_make_order", {context:["make_order","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_make_order.render();


//dialog_export = new YAHOO.widget.Dialog("dialog_export", {context:["export_data","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
//Event.addListener("export_data", "click", dialog_export.show,dialog_export , true);

Event.addListener("make_order", "click", dialog_make_order.show,dialog_make_order , true);


Event.addListener("take_order", "click", take_order , true);
//dialog_export.render();

	
	

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
  var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id=1;
var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 





if(Dom.get('modify').value == 1){ 


dialog_quick_edit_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Name", {context:["customer_name","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Name.render();

dialog_quick_edit_Customer_Main_Contact_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Contact_Name", {context:["quick_edit_main_contact_name_edit","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Contact_Name.render();

dialog_quick_edit_Customer_Tax_Number = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Tax_Number", {context:["quick_edit_tax","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Tax_Number.render();

dialog_quick_edit_Customer_Registration_Number = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Registration_Number", {context:["quick_edit_registration_number","tr","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Registration_Number.render();

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

dialog_quick_edit_Customer_Website = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Website", {context:["quick_edit_website","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Website.render();

<?php
	foreach($customer->get_other_emails_data() as $key=>$value){
		printf('dialog_quick_edit_Customer_Email%d = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Email%d", {context:["quick_edit_other_email%d","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});', $key, $key, $key);
		printf('dialog_quick_edit_Customer_Email%d.render();', $key);
}
?>

<?php
	foreach($customer->get_other_telephones_data() as $key=>$value){
		printf('dialog_quick_edit_Customer_Telephone%d = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Telephone%d", {context:["quick_edit_other_telephone%d","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});', $key, $key, $key);
		printf('dialog_quick_edit_Customer_Telephone%d.render();', $key);
}
?>

<?php
	foreach($customer->get_other_mobiles_data() as $key=>$value){
		printf('dialog_quick_edit_Customer_Mobile%d = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Mobile%d", {context:["quick_edit_other_mobile%d","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});', $key, $key, $key);
		printf('dialog_quick_edit_Customer_Mobile%d.render();', $key);
}
?>

<?php
	foreach($customer->get_other_faxes_data() as $key=>$value){
		printf('dialog_quick_edit_Customer_FAX%d = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_FAX%d", {context:["quick_edit_other_fax%d","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});', $key, $key, $key);
		printf('dialog_quick_edit_Customer_FAX%d.render();', $key);
}
?>


Event.addListener('quick_edit_main_contact_name_edit', "click", show_edit_main_contact_name);
Event.addListener('quick_edit_name_edit', "click", show_edit_name);

Event.addListener('quick_edit_tax', "click", show_edit_tax);
Event.addListener('quick_edit_registration_number', "click", show_edit_registration_number);

Event.addListener('quick_edit_email', "click", dialog_quick_edit_Customer_Main_Email_);
Event.addListener('quick_edit_main_address', "click", dialog_quick_edit_Customer_Main_Address_);
Event.addListener('quick_edit_main_telephone', "click", dialog_quick_edit_Customer_Main_Telephone_);
Event.addListener('quick_edit_main_mobile', "click", dialog_quick_edit_Customer_Main_Mobile_);
Event.addListener('quick_edit_main_fax', "click", dialog_quick_edit_Customer_Main_FAX_);
Event.addListener('quick_edit_website', "click", dialog_quick_edit_Customer_Website_);


<?php
	foreach($customer->get_other_emails_data() as $key=>$value){
	printf("Event.addListener('quick_edit_other_email%d', \"click\", dialog_quick_edit_Customer_Email%d_);", $key, $key);	
	printf("Event.addListener('close_quick_edit_email%d', \"click\", dialog_quick_edit_Customer_Email%d.hide,dialog_quick_edit_Customer_Email%d , true);", $key, $key, $key);	
	//Event.addListener('close_quick_edit_email', "click", dialog_quick_edit_Customer_Main_Email.hide,dialog_quick_edit_Customer_Main_Email , true);
	}

	foreach($customer->get_other_telephones_data() as $key=>$value){
	printf("Event.addListener('quick_edit_other_telephone%d', \"click\", dialog_quick_edit_Customer_Telephone%d_);", $key, $key);	
	printf("Event.addListener('close_quick_edit_telephone%d', \"click\", dialog_quick_edit_Customer_Telephone%d.hide,dialog_quick_edit_Customer_Telephone%d , true);", $key, $key, $key);	
	}


	foreach($customer->get_other_mobiles_data() as $key=>$value){
	printf("Event.addListener('quick_edit_other_mobile%d', \"click\", dialog_quick_edit_Customer_Mobile%d_);", $key, $key);	
	printf("Event.addListener('close_quick_edit_other_mobile%d', \"click\", dialog_quick_edit_Customer_Mobile%d.hide,dialog_quick_edit_Customer_Mobile%d , true);", $key, $key, $key);	
	}


	foreach($customer->get_other_faxes_data() as $key=>$value){
	printf("Event.addListener('quick_edit_other_fax%d', \"click\", dialog_quick_edit_Customer_FAX%d_);", $key, $key);	
	printf("Event.addListener('close_quick_edit_other_fax%d', \"click\", dialog_quick_edit_Customer_FAX%d.hide,dialog_quick_edit_Customer_FAX%d , true);", $key, $key, $key);	
	}
?>



	
/*
	<?php print sprintf("edit_address(%d,'contact_');",$customer->data['Customer Main Address Key']);?>
	var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	 
	YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,{prefix:'contact_',subject:'Customer',subject_key:customer_id,type:'contact'});
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
*/

Event.addListener('save_quick_edit_main_contact_name', "click", save_quick_edit_main_contact_name, true);
Event.addListener('close_quick_edit_main_contact_name', "click", dialog_quick_edit_Customer_Main_Contact_Name.hide,dialog_quick_edit_Customer_Main_Contact_Name , true);


Event.addListener('save_quick_edit_tax_number', "click", save_quick_edit_tax_number, true);
Event.addListener('close_quick_edit_tax_number', "click", dialog_quick_edit_Customer_Tax_Number.hide,dialog_quick_edit_Customer_Tax_Number , true);

Event.addListener('save_quick_edit_registration_number', "click", save_quick_edit_registration_number, true);
Event.addListener('close_quick_edit_registration_number', "click", dialog_quick_edit_Customer_Registration_Number.hide,dialog_quick_edit_Customer_Registration_Number , true);


Event.addListener('save_quick_edit_name', "click", save_quick_edit_name, true);
Event.addListener('close_quick_edit_name', "click", dialog_quick_edit_Customer_Name.hide,dialog_quick_edit_Customer_Name , true);

Event.addListener('save_quick_edit_email', "click", save_quick_edit_email, true);
Event.addListener('close_quick_edit_email', "click", dialog_quick_edit_Customer_Main_Email.hide,dialog_quick_edit_Customer_Main_Email , true);

Event.addListener('save_quick_edit_telephone', "click", save_quick_edit_telephone, true);
Event.addListener('close_quick_edit_telephone', "click", dialog_quick_edit_Customer_Main_Telephone.hide,dialog_quick_edit_Customer_Main_Telephone , true);

Event.addListener('save_quick_edit_mobile', "click", save_quick_edit_mobile, true);
Event.addListener('close_quick_edit_mobile', "click", dialog_quick_edit_Customer_Main_Mobile.hide,dialog_quick_edit_Customer_Main_Mobile , true);

Event.addListener('save_quick_edit_fax', "click", save_quick_edit_fax, true);
Event.addListener('close_quick_edit_fax', "click", dialog_quick_edit_Customer_Main_FAX.hide,dialog_quick_edit_Customer_Main_FAX , true);


Event.addListener('save_quick_edit_web', "click", save_quick_edit_web, true);
Event.addListener('close_quick_edit_web', "click", dialog_quick_edit_Customer_Website.hide,dialog_quick_edit_Customer_Website , true);

	var customer_email_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email);
    customer_email_oACDS.queryMatchContains = true;
    var customer_email_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Email","Customer_Main_Email_Container", customer_email_oACDS);
    customer_email_oAutoComp.minQueryLength = 0; 
    customer_email_oAutoComp.queryDelay = 0.1;
	
	var customer_email_oACDS = new YAHOO.util.FunctionDataSource(validate_email_comment);
    customer_email_oACDS.queryMatchContains = true;
    var customer_email_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Email_comment","Customer_Main_Email_comment_Container", customer_email_oACDS);
    customer_email_oAutoComp.minQueryLength = 0; 
    customer_email_oAutoComp.queryDelay = 0.1;

/*
<?php
printf("ids=[''");
foreach($customer->get_other_emails_data() as $key=>$value)
	printf(",'save_quick_edit_email%d'", $key);
printf("];");
?>
*/
//Event.addListener(ids, "click", save_quick_edit_other_email, true);

<?php
foreach($customer->get_other_emails_data()  as $email_key=>$email){
printf("var customer_email%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email_other);\ncustomer_email%d_oACDS.queryMatchContains = true;\nvar customer_email%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Email%d','Customer_Email%d_Container', customer_email%d_oACDS);\ncustomer_email%d_oAutoComp.minQueryLength = 0;\ncustomer_email%d_oAutoComp.queryDelay = 0.1;\ncustomer_email%d_oAutoComp.email_id =%d;",
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,$email_key,
$email_key
);

printf("var customer_email%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email_other_comment);\ncustomer_email%d_oACDS.queryMatchContains = true;\nvar customer_email%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Email%d_comment','Customer_Email%d_comment_Container', customer_email%d_oACDS);\ncustomer_email%d_oAutoComp.minQueryLength = 0;\ncustomer_email%d_oAutoComp.queryDelay = 0.1;\ncustomer_email%d_oAutoComp.email_id =%d;",
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,
$email_key,$email_key,
$email_key
);
}
?>

<?php
foreach($customer->get_other_telephones_data()  as $telephone_key=>$telephone  ){
printf("var customer_telephone%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone_other);\ncustomer_telephone%d_oACDS.queryMatchContains = true;\nvar customer_telephone%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Telephone%d','Customer_Telephone%d_Container', customer_telephone%d_oACDS);\ncustomer_telephone%d_oAutoComp.minQueryLength = 0;\ncustomer_telephone%d_oAutoComp.queryDelay = 0.1;;\ncustomer_telephone%d_oAutoComp.telephone_id =%d;",
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,$telephone_key,
$telephone_key
);

printf("var customer_telephone%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone_other_comment);\ncustomer_telephone%d_oACDS.queryMatchContains = true;\nvar customer_telephone%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Telephone%d_comment','Customer_Telephone%d_comment_Container', customer_telephone%d_oACDS);\ncustomer_telephone%d_oAutoComp.minQueryLength = 0;\ncustomer_telephone%d_oAutoComp.queryDelay = 0.1;;\ncustomer_telephone%d_oAutoComp.telephone_id =%d;",
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,
$telephone_key,$telephone_key,
$telephone_key
);
}
?>


<?php
foreach($customer->get_other_faxes_data()  as $fax_key=>$fax  ){
printf("var customer_fax%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax_other);\ncustomer_fax%d_oACDS.queryMatchContains = true;\nvar customer_fax%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_FAX%d','Customer_FAX%d_Container', customer_fax%d_oACDS);\ncustomer_fax%d_oAutoComp.minQueryLength = 0;\ncustomer_fax%d_oAutoComp.queryDelay = 0.1;;\ncustomer_fax%d_oAutoComp.fax_id =%d;",
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,$fax_key,
$fax_key
);

printf("var customer_fax%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax_other_comment);\ncustomer_fax%d_oACDS.queryMatchContains = true;\nvar customer_fax%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_FAX%d_comment','Customer_FAX%d_comment_Container', customer_fax%d_oACDS);\ncustomer_fax%d_oAutoComp.minQueryLength = 0;\ncustomer_fax%d_oAutoComp.queryDelay = 0.1;;\ncustomer_fax%d_oAutoComp.fax_id =%d;",
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,
$fax_key,$fax_key,
$fax_key
);
}
?>

<?php
foreach($customer->get_other_mobiles_data()  as $mobile_key=>$mobile  ){
printf("var customer_mobile%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_mobile_other);\ncustomer_mobile%d_oACDS.queryMatchContains = true;\nvar customer_mobile%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Mobile%d','Customer_Mobile%d_Container', customer_mobile%d_oACDS);\ncustomer_mobile%d_oAutoComp.minQueryLength = 0;\ncustomer_mobile%d_oAutoComp.queryDelay = 0.1;;\ncustomer_mobile%d_oAutoComp.mobile_id =%d;",
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,$mobile_key,
$mobile_key
);

printf("var customer_mobile%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_mobile_other_comment);\ncustomer_mobile%d_oACDS.queryMatchContains = true;\nvar customer_mobile%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Mobile%d_comment','Customer_Mobile%d_comment_Container', customer_mobile%d_oACDS);\ncustomer_mobile%d_oAutoComp.minQueryLength = 0;\ncustomer_mobile%d_oAutoComp.queryDelay = 0.1;;\ncustomer_mobile%d_oAutoComp.mobile_id =%d;",
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,
$mobile_key,$mobile_key,
$mobile_key
);

}
?>

	var customer_main_contact_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_main_contact_name);
    customer_main_contact_name_oACDS.queryMatchContains = true;
    var customer_main_contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Contact_Name","Customer_Main_Contact_Name_Container", customer_main_contact_name_oACDS);
    customer_main_contact_name_oAutoComp.minQueryLength = 0; 
    customer_main_contact_name_oAutoComp.queryDelay = 0.1;

	var customer_tax_number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_tax_number);
    customer_tax_number_oACDS.queryMatchContains = true;
    var customer_tax_number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Tax_Number","Customer_Tax_Number_Container", customer_tax_number_oACDS);
    customer_tax_number_oAutoComp.minQueryLength = 0; 
    customer_tax_number_oAutoComp.queryDelay = 0.1;
    
    
    	var customer_registration_number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_registration_number);
    customer_registration_number_oACDS.queryMatchContains = true;
    var customer_registration_number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Registration_Number","Customer_Registration_Number_Container", customer_registration_number_oACDS);
    customer_registration_number_oAutoComp.minQueryLength = 0; 
    customer_registration_number_oAutoComp.queryDelay = 0.1;
    
	
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

    var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone_comment);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Telephone_comment","Customer_Main_Telephone_comment_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
	
    var customer_mobile_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_mobile);
    customer_mobile_oACDS.queryMatchContains = true;
    var customer_mobile_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Mobile","Customer_Main_Mobile_Container", customer_mobile_oACDS);
    customer_mobile_oAutoComp.minQueryLength = 0; 
    customer_mobile_oAutoComp.queryDelay = 0.1;

    var customer_mobile_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_website);
    customer_mobile_oACDS.queryMatchContains = true;
    var customer_mobile_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Website","Customer_Website_Container", customer_mobile_oACDS);
    customer_mobile_oAutoComp.minQueryLength = 0; 
    customer_mobile_oAutoComp.queryDelay = 0.1;
	
	
    var customer_mobile_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_mobile_comment);
    customer_mobile_oACDS.queryMatchContains = true;
    var customer_mobile_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Mobile_comment","Customer_Main_Mobile_comment_Container", customer_mobile_oACDS);
    customer_mobile_oAutoComp.minQueryLength = 0; 
    customer_mobile_oAutoComp.queryDelay = 0.1;
	
	var customer_fax_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax);
    customer_fax_oACDS.queryMatchContains = true;
    var customer_fax_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_FAX","Customer_Main_FAX_Container", customer_fax_oACDS);
    customer_fax_oAutoComp.minQueryLength = 0; 
    customer_fax_oAutoComp.queryDelay = 0.1;

	
	var customer_fax_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax_comment);
    customer_fax_oACDS.queryMatchContains = true;
    var customer_fax_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_FAX_comment","Customer_Main_FAX_comment_Container", customer_fax_oACDS);
    customer_fax_oAutoComp.minQueryLength = 0; 
    customer_fax_oAutoComp.queryDelay = 0.1;
	
	<?php print sprintf("edit_address(%d,'contact_');",$customer->data['Customer Main Address Key']);?>
	
	var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	 
	YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,{prefix:'contact_',subject:'Customer',subject_key:customer_id,type:'contact'});
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

/*
var other_email_count=Dom.get('other_email_count').value;
for(i=0; i<other_email_count; i++)
	Event.addListener('save_quick_edit_other_email_'+i, "click", save_quick_edit_name, true);
*/


//End quick edit



}


}
YAHOO.util.Event.onDOMReady(init);

function hide_all_dialogs(){
	for(x in list_of_dialogs)
		eval(list_of_dialogs[x]).hide();
		
}

function show_edit_main_contact_name(){
Dom.get('Customer_Main_Contact_Name').value=Dom.get('Customer_Main_Contact_Name').getAttribute('ovalue')
hide_all_dialogs();
dialog_quick_edit_Customer_Main_Contact_Name.show();
}


function show_edit_tax(){
hide_all_dialogs();
Dom.get('Customer_Tax_Number').value=Dom.get('Customer_Tax_Number').getAttribute('ovalue')
dialog_quick_edit_Customer_Tax_Number.show();
}

function show_edit_registration_number(){
hide_all_dialogs();

Dom.get('Customer_Registration_Number').value=Dom.get('Customer_Registration_Number').getAttribute('ovalue')
dialog_quick_edit_Customer_Registration_Number.show();
}


function show_edit_name(){
hide_all_dialogs();
Dom.get('Customer_Name').value=Dom.get('Customer_Name').getAttribute('ovalue')
dialog_quick_edit_Customer_Name.show();
}

function dialog_quick_edit_Customer_Main_Email_(){
	Dom.get('Customer_Main_Email').value=Dom.get('Customer_Main_Email').getAttribute('ovalue');
	<?php if($customer->get_principal_email_comment()){ ?>
	Dom.get('Customer_Main_Email_comment').value=Dom.get('Customer_Main_Email_comment').getAttribute('ovalue');
	<?php }?>
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Email.show();
}
function dialog_quick_edit_Customer_Main_Address_(){

	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Address.show();
}
function dialog_quick_edit_Customer_Main_Telephone_(){
	Dom.get('Customer_Main_Telephone').value=Dom.get('Customer_Main_Telephone').getAttribute('ovalue');
	<?php if($customer->get_principal_telecom_comment('Telephone')) {?>
	Dom.get('Customer_Main_Telephone_comment').value=Dom.get('Customer_Main_Telephone_comment').getAttribute('ovalue');
	<?php }?>
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Telephone.show();
}
function dialog_quick_edit_Customer_Main_Mobile_(){
	Dom.get('Customer_Main_Mobile').value=Dom.get('Customer_Main_Mobile').getAttribute('ovalue');
	<?php if($customer->get_principal_telecom_comment('Mobile')) {?>
	Dom.get('Customer_Main_Mobile_comment').value=Dom.get('Customer_Main_Mobile_comment').getAttribute('ovalue');
	<?php }?>
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_Mobile.show();
}
function dialog_quick_edit_Customer_Website_(){
	Dom.get('Customer_Website').value=Dom.get('Customer_Website').getAttribute('ovalue');
	hide_all_dialogs();
	dialog_quick_edit_Customer_Website.show();
}

function dialog_quick_edit_Customer_Main_FAX_(){
	Dom.get('Customer_Main_FAX').value=Dom.get('Customer_Main_FAX').getAttribute('ovalue');
	<?php if($customer->get_principal_telecom_comment('FAX')) {?>
	Dom.get('Customer_Main_FAX_comment').value=Dom.get('Customer_Main_FAX_comment').getAttribute('ovalue');
	<?php }?>
	hide_all_dialogs();
	dialog_quick_edit_Customer_Main_FAX.show();
}
<?php
foreach($customer->get_other_emails_data() as $key=>$value){
	printf("function dialog_quick_edit_Customer_Email%d_(){hide_all_dialogs();Dom.get('Customer_Email%d').value=Dom.get('Customer_Email%d').getAttribute('ovalue');%sdialog_quick_edit_Customer_Email%d.show();}\n", $key, $key, $key, (($value['label'])?"Dom.get('Customer_Email".$key."_comment').value=Dom.get('Customer_Email".$key."_comment').getAttribute('ovalue');":''),$key);
}
foreach($customer->get_other_telephones_data() as $key=>$value){
	printf("function dialog_quick_edit_Customer_Telephone%d_(){hide_all_dialogs();Dom.get('Customer_Telephone%d').value=Dom.get('Customer_Telephone%d').getAttribute('ovalue');%sdialog_quick_edit_Customer_Telephone%d.show();}\n", $key, $key, $key, (($value['label'])?"Dom.get('Customer_Telephone".$key."_comment').value=Dom.get('Customer_Telephone".$key."_comment').getAttribute('ovalue');":''), $key);
}
foreach($customer->get_other_mobiles_data() as $key=>$value){
	printf("function dialog_quick_edit_Customer_Mobile%d_(){hide_all_dialogs();Dom.get('Customer_Mobile%d').value=Dom.get('Customer_Mobile%d').getAttribute('ovalue');%sdialog_quick_edit_Customer_Mobile%d.show();}\n", $key, $key, $key, (($value['label'])?"Dom.get('Customer_Mobile".$key."_comment').value=Dom.get('Customer_Mobile".$key."_comment').getAttribute('ovalue');":''), $key);
}
foreach($customer->get_other_faxes_data() as $key=>$value){
	printf("function dialog_quick_edit_Customer_FAX%d_(){hide_all_dialogs();Dom.get('Customer_FAX%d').value=Dom.get('Customer_FAX%d').getAttribute('ovalue');%sdialog_quick_edit_Customer_FAX%d.show();}\n", $key, $key, $key, (($value['label'])?"Dom.get('Customer_FAX".$key."_comment').value=Dom.get('Customer_FAX".$key."_comment').getAttribute('ovalue');":''), $key);
}
?>
function validate_customer_name(query){
 validate_general('customer_quick','name',unescape(query));
}

function validate_customer_website(query){
//alert('q: ' + query)
if(query==''){
    validate_scope_data.customer_quick.web.validated=true;
    
 if(Dom.get(validate_scope_data.customer_quick.web.name).getAttribute('ovalue')!=query){
     validate_scope_data.customer_quick.web.changed=true;
 }else{
    validate_scope_data.customer_quick.web.changed=false;
 }
    
	validate_scope('customer_quick'); 
    Dom.get(validate_scope_data.customer_quick.web.name+'_msg').innerHTML='<?php echo _('This operation will remove the website')?>';
}else{
validate_general('customer_quick','web',unescape(query));

}


}


function validate_customer_main_contact_name(query){
 validate_general('customer_quick','contact',unescape(query));
}

function validate_customer_tax_number(query){

 validate_general('billing_quick','tax_number',unescape(query));
}

function validate_customer_registration_number(query){
 validate_general('customer_quick','registration_number',unescape(query));
}

function validate_email_comment(query){
//alert(query)
 //if(Dom.get('Customer_Main_Email_comment').getAttribute('ovalue')!=query){
     validate_scope_data.customer_quick.email.changed=true;
 //}

}

function validate_customer_email(query){

if(query==''){
    validate_scope_data.customer_quick.email.validated=true;
    
 if(Dom.get(validate_scope_data.customer_quick.email.name).getAttribute('ovalue')!=query){
     validate_scope_data.customer_quick.email.changed=true;
 }else{
    validate_scope_data.customer_quick.email.changed=false;
 }
    
	validate_scope('customer_quick'); 
    Dom.get(validate_scope_data.customer_quick.email.name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
}else{
validate_general('customer_quick','email',unescape(query));

}


}

function validate_customer_telephone(query){
    validate_general('customer_quick','telephone',unescape(query));
    if(query==''){
        validate_scope_data.customer_quick.telephone.validated=true;
	    validate_scope('customer_quick'); 
	    Dom.get(validate_scope_data.customer_quick.telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
    }
}

function validate_customer_telephone_comment(query){
	if(Dom.get('Customer_Main_Telephone_comment').getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick.telephone.changed=true;
	}else{
		validate_scope_data.customer_quick.telephone.changed=false;
	}
	//alert(validate_scope_data.customer_quick.telephone.changed)
}


function validate_customer_mobile_comment(query){
	if(Dom.get('Customer_Main_Mobile_comment').getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick.mobile.changed=true;
	}else{
		validate_scope_data.customer_quick.mobile.changed=false;
	}
	//alert(validate_scope_data.customer_quick.mobile.changed)
}

function validate_customer_fax_comment(query){
	if(Dom.get('Customer_Main_FAX_comment').getAttribute('ovalue')!=query){
		validate_scope_data.customer_quick.fax.changed=true;
	}else{
		validate_scope_data.customer_quick.fax.changed=false;
	}
	//alert(validate_scope_data.customer_quick.fax.changed)
}

function validate_customer_mobile(query){
    validate_general('customer_quick','mobile',unescape(query));
    if(query==''){
        validate_scope_data.customer_quick.mobile.validated=true;
	    validate_scope('customer_quick'); 
	    Dom.get(validate_scope_data.customer_quick.mobile.name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
    }
}


function validate_customer_fax(query){
    validate_general('customer_quick','fax',unescape(query));
    if(query==''){
        validate_scope_data.customer_quick.fax.validated=true;
	    validate_scope('customer_quick'); 
	    Dom.get(validate_scope_data.customer_quick.fax.name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
    }
}


YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", rppmenu.show, null, rppmenu);


    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name2", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp2", "click", rppmenu.show, null, rppmenu);


    });
