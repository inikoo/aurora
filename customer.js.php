<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Customer.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_note;
var dialog_new_sticky_note;
var dialog_sticky_note;
var dialog_export;
var dialog_link;
var customer_key=<?php echo $_REQUEST['customer_key']  ?>;
var dialog_edit_note;
<?php
$customer=new Customer($_REQUEST['customer_key']);
print "var customer_id='".$customer->id."';";
print "var store_id='".$customer->data['Customer Store Key']."';";

$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}


?>
var onCellClick = function(oArgs) {
    var target = oArgs.target,
                 column = this.getColumn(target),
                          record = this.getRecord(target);

    var recordIndex = this.getRecordIndex(record);
    ar_file='ar_edit_contacts.php';

    switch (column.action) {
    case('edit'):
           Dom.get('edit_note_history_key').value=record.getData('key');

        Dom.get('edit_note_input').value=record.getData('note');
                
         Dom.get('record_index').value=recordIndex;

     
  var y=(Dom.getY(target))-0
    var x=(Dom.getX(target))-200
 Dom.setX('dialog_edit_note', x)
    Dom.setY('dialog_edit_note', y)
dialog_edit_note.show();
    
    break;
    case 'delete':
        if (record.getData('delete')!='') {

            if(record.getData('can_delete')){

            var delete_type=record.getData('delete_type');
            if (confirm('Are you sure, you want to '+delete_type+' this row?')) {
               



  
                YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record), {
                success: function (o) {
                   //  alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200 && r.action=='deleted') {

                            this.deleteRow(target);


                        } else if (r.state == 200 && r.action=='discontinued') {

                            var data = record.getData();
                            data['delete']=r.delete;
                            data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex,data);



                        } else {
                            alert(r.msg);
                        }
                    },
failure: function (o) {
                        alert(o.statusText);
                    },
scope:this
                }
                );
            }
            }else{
            
            
      
               
            
            if(record.getData('strikethrough')=='Yes')
            var action='unstrikethrough_';
            else
             var action='strikethrough_';

        
        
            YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo='+action+column.object + myBuildUrl(this,record), {
                success: function (o) {
                  //   alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        
                          var data = record.getData();
                            data['strikethrough']=r.strikethrough;
                    data['delete']=r.delete;
                    
                    //data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex,data);
                        
                    
                    },
failure: function (o) {
                        alert(o.statusText);
                    },
scope:this
                }
                );
            
            }
            
            
            
        }
        break;

    default:

        this.onEventShowCellEditor(oArgs);
        break;
    }
};

var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";
var validate_scope_data=
{
    'customer':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	,'email':{'ar':'find','ar_request':'ar_contacts.php?tipo=email_in_other_customer&customer_key='+customer_id+'&store_key='+store_id+'&query=','changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}

<?php

foreach($customer->get_other_emails_data()  as $email_key=>$email  ){
printf(",'email%d':{'ar':'find','ar_request':'ar_contacts.php?tipo=email_in_other_customer&customer_key='+customer_id+'&store_key='+store_id+'&query=','changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Email%d','validation':[{'regexp':regexp_valid_email,'invalid_msg':'%s'}]}",
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




	,'other_email':{'ar':'find','ar_request':'ar_contacts.php?tipo=email_in_other_customer&customer_key='+customer_id+'&store_key='+store_id+'&query=','changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
,'other_telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_Telephone','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$",'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	,'other_mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
	,'other_fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Other_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}
  	,'registration_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Registration_Number','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Company Registration Number')?>'}]}


  },
  'billing_data':{
  	'fiscal_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Fiscal_Name','ar':false,'validation':[{'regexp':"[a-zA-Z]+",'invalid_msg':'<?php echo _('Invalid Fiscal Name')?>'}]}
  	,'tax_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Tax_Number','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Tax Number')?>'}]}

  }

};



var validate_scope_metadata={
'customer':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}
,'billing_data':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}

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
			x.setAttribute('style','padding:10px 0 ;border-top:none')
			c1.innerHTML="";
			c2.innerHTML="";
			c3.setAttribute('style','padding:10px 0 ;');


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

function save(tipo){
   
    switch(tipo){
    case('edit_note'):
    
        YAHOO.util.Connect.asyncRequest(
                    'GET',
                ar_file+'?tipo=customer_edit_note&customer_key='+customer_key+'&note_key='+Dom.get('edit_note_history_key').value+'&note='+my_encodeURIComponent(Dom.get('edit_note_input').value)+'&date='+Dom.get('edit_note_date').getAttribute('value')+'&record_index='+Dom.get('record_index').value, {
                success: function (o) {
               // alert(o.responseText)
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                          
                          if(r.state==200){
                          	var table=tables['table0'];
                       
                          	   record = table.getRecord(r.record_index);
                          	 
                          	  var data = record.getData();
                          data['note']=r.newvalue;
                          table.updateRow(r.record_index,data);
                          
                                                    close_dialog('edit_note');;

                          }else{
                          Dom.get('edit_note_msg').innerHTML=r.msg;
                          
                          }
                          
                     
                      
                    },
                    failure: function (o) {
                        alert(o.statusText);
                    },
                scope:this
                }
                );
    
    
    break;
    case('note'):
        var value=my_encodeURIComponent(Dom.get(tipo+"_input").value);
        var note_type=my_encodeURIComponent(Dom.get("note_type").getAttribute('value'));

	var request="ar_edit_contacts.php?tipo=customer_add_note&customer_key="+customer_key+"&note="+value+"&details=&note_type="+note_type;

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			close_dialog(tipo)
			var table=tables['table0'];
			var datasource=tables['dataSource0'];
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
	    });        
	

	break;
	 case('sticky_note'):
	  case('new_sticky_note'):

  //  var value=my_encodeURIComponent(Dom.get(tipo+"_input").value);


 var data_to_update=new Object;
 data_to_update[tipo]={'okey':tipo,'value':Dom.get(tipo+"_input").value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_key


	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
		    
		    Dom.get('sticky_note_content').innerHTML=r.newvalue;
			
			close_dialog(r.key);

            if(r.newvalue==''){
                Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','none');
                Dom.setStyle('new_sticky_note_tr','display','');

            }else{
                           

             Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','');
                Dom.setStyle('new_sticky_note_tr','display','none');
            }

            var table=tables['table0'];
			var datasource=tables['dataSource0'];
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
		}
	    });        
	

	

	break;	
	
   case('link'):
   var value='';
   if(Dom.get("link_note").value!='')
    value=Dom.get("link_note").value+'; ';
   alert(Dom.get("link_file").html);
    	value=value+Dom.get("link_file").value+' <a href="file://'+Dom.get("link_file").value+'">link</a>';

   var value=encodeURIComponent(value);
	var request="ar_edit_contacts.php?tipo=edit_customer&key=Note&customer_key="+customer_key+"&newvalue="+value;
	alert(request);
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
	    });        
  
    
    break;
    }
    
    
    
    
};

function change(e,o,tipo){
    switch(tipo){
    case('note'):
	if(o.value!=''){
	    enable_save(tipo);
/*
	    if(window.event)
		key = window.event.keyCode; //IE
	    else
		key = e.which; //firefox     
	    
	    if (key == 13)
		save(tipo);
*/

	}else
	    disable_save(tipo);
	break;
    case('new_sticky_note'):
        if(o.value!=''){
	    enable_save(tipo);
	    }else{
	    disable_save(tipo);
	    }
	    
    break;
    }
};


function enable_save(tipo){
    switch(tipo){
    case('note'):
	Dom.get(tipo+'_save').style.visibility='visible';
	break;
	 case('new_sticky_note'):
	Dom.get(tipo+'_save').style.visibility='visible';
	break;
    }
};

function disable_save(tipo){
    switch(tipo){
    case('note'):
	Dom.get(tipo+'_save').style.visibility='hidden';
	break;
	case('new_sticky_note'):
	Dom.get(tipo+'_save').style.visibility='hidden';
	break;
    }
};


function close_dialog(tipo){
    switch(tipo){
  //   case('long_note'):
// 	//Dom.get(tipo+"_input").value='';
// 	dialog_note.hide();

// 	break;
  case('attach'):

	Dom.get(tipo+"_note").value='';
	//	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_attach.hide();

	break;
      case('edit_note'):

	Dom.get("edit_note_input").value='';
	
	dialog_edit_note.hide();
break;
    case('note'):

	Dom.get(tipo+"_input").value='';
	Dom.get(tipo+'_save').style.visibility='hidden';
	
	dialog_note.hide();
break;
 case('sticky_note'):
	dialog_sticky_note.hide();
	 Dom.get('sticky_note_input').value=Dom.get('sticky_note_content').innerHTML;
	break;
case('new_sticky_note'):
	 Dom.get('sticky_note_input').value=Dom.get('sticky_note_content').innerHTML;

	Dom.get(tipo+"_input").value='';
	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_new_sticky_note.hide();

	break;
    
     case('link'):
    Dom.get("link_note").value='';
	Dom.get("link_file").value='';
	//Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_link.hide();

	break;
    
 case('export'):
 dialog_export.hide();
 break;
 case('make_order'):

     //	Dom.get(tipo+"_input").value='';
	//Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_make_order.hide();

	break;
    }


};

 
Event.addListener(window, "load", function() {
	    tables = new function() {
		    
		    var tableid=0; // Change if you have more the 1 table
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
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'customer_history'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'customer_history'}

					   ];
		
		    this.dataSource0  = new YAHOO.util.DataSource("ar_history.php?tipo=customer_history&customer_key="+customer_key+"&sf=0&tid="+tableid);
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
                  fields: ["note","date","time","handle","delete","can_delete" ,"delete_type","key","edit","type","strikethrough"]};
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table0.filter={key:'<?php echo$_SESSION['state']['customer']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['table']['f_value']?>'};

	        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table0.subscribe("cellClickEvent", onCellClick);            



	    //   Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)









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
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
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
		
		    this.dataSource2  = new YAHOO.util.DataSource("ar_contacts.php?tipo=customer_orders&customer_key="+customer_key+"&sf=0&tid="+tableid);
		    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.table_id=tableid;
	    this.dataSource2.responseSchema = {
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
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
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


function take_order(){
    location.href='order.php?new=1&customer_key='+customer_key; 


}


function save_quick_edit_name(){
	//alert('validate name');
	validate_customer_name();
    save_edit_general_bulk('customer');
	//Dom.setStyle('dialog_quick_edit_'+field_name,'display','none')
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function save_quick_edit_email(){
	//alert('validate email');
	//validate_customer_email();
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_telephone(){
	//alert('telephone');
	//validate_customer_telephone();
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_mobile(){
	//alert('mobile');
	//validate_customer_mobile();
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}
function save_quick_edit_fax(){
	//alert('fax');
	//validate_customer_fax();
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}


function save_quick_edit_other_email(email_key){


	//alert(query);
	//validate_customer_email_other(email_key);
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}


function save_quick_edit_other_telephone(telephone_key){

	//alert('other_telephone');
	//alert(query);
	//validate_customer_telephone_other(telephone_key);
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}


function save_quick_edit_other_mobile(mobile_key){

	//alert('other_mobile');
	//alert(query);
	//validate_customer_mobile_other(mobile_key);
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function validate_customer_mobile_other(query,id){
    id=id.scope.mobile_id;
    if(query==''){
        validate_scope_data.customer['mobile'+id].validated=true;
        if(Dom.get(validate_scope_data.customer['mobile'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer['mobile'+id].changed=true;
        }else{
            validate_scope_data.customer['mobile'+id].changed=false;
        }
        validate_scope('customer'); 
        Dom.get(validate_scope_data.customer['mobile'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
     
    }else{
        validate_general('customer','mobile'+id,unescape(query));
    }
}

function save_quick_edit_other_fax(fax_key){

	//alert('other_fax');
	//alert(query);
	//validate_customer_fax_other(fax_key);
    save_edit_general_bulk('customer');
	window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
}

function validate_customer_fax_other(query,id){
    id=id.scope.fax_id;
    if(query==''){
        validate_scope_data.customer['fax'+id].validated=true;
        if(Dom.get(validate_scope_data.customer['fax'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer['fax'+id].changed=true;
        }else{
            validate_scope_data.customer['fax'+id].changed=false;
        }
        validate_scope('customer'); 
        Dom.get(validate_scope_data.customer['fax'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
     
    }else{
        validate_general('customer','fax'+id,unescape(query));
    }
}

function validate_customer_telephone_other(query,id){
    id=id.scope.telephone_id;
    if(query==''){
        validate_scope_data.customer['telephone'+id].validated=true;
        if(Dom.get(validate_scope_data.customer['telephone'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer['telephone'+id].changed=true;
        }else{
            validate_scope_data.customer['telephone'+id].changed=false;
        }
        validate_scope('customer'); 
        Dom.get(validate_scope_data.customer['telephone'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
     
    }else{
        validate_general('customer','telephone'+id,unescape(query));
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

ids=['elements_changes','elements_orders','elements_notes','elements_attachments','elements_emails'];


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
/*
function validate_customer_email_other(id, query){
	
    if(query==''){
        validate_scope_data.customer['email'+id].validated=true;
        if(Dom.get(validate_scope_data.customer['email'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer['email'+id].changed=true;
        }else{
            validate_scope_data.customer['email'+id].changed=false;
        }
        validate_scope('customer'); 
        Dom.get(validate_scope_data.customer['email'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
     
    }else{
        validate_general('customer','email'+id,unescape(query));
    }
}
*/

function validate_customer_email_other(query,id){


    id=id.scope.email_id;

    if(query==''){
        validate_scope_data.customer['email'+id].validated=true;
        if(Dom.get(validate_scope_data.customer['email'+id].name).getAttribute('ovalue')!=query){
            validate_scope_data.customer['email'+id].changed=true;
        }else{
            validate_scope_data.customer['email'+id].changed=false;
        }
        validate_scope('customer'); 
        Dom.get(validate_scope_data.customer['email'+id].name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
     
    }else{
        validate_general('customer','email'+id,unescape(query));
    }
}

function init(){


Event.addListener(['elements_changes','elements_orders','elements_notes','elements_attachments','elements_emails'], "click",change_elements);


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



	//Details textarea editor ---------------------------------------------------------------------
	var texteditorConfig = {
	    height: '270px',
	    width: '750px',
	    dompath: true,
	    focusAtStart: true
	};     

 	editor = new YAHOO.widget.Editor('long_note_input', texteditorConfig);

	editor._defaultToolbar.buttonType = 'basic';
 	editor.render();

	//	editor.on('editorKeyUp',change_textarea,'details' );
	//-------------------------------------------------------------


dialog_note = new YAHOO.widget.Dialog("dialog_note", {context:["note","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_note.render();

dialog_edit_note = new YAHOO.widget.Dialog("dialog_edit_note", {visible : false,close:true,underlay: "none",draggable:false});
dialog_edit_note.render();


dialog_new_sticky_note = new YAHOO.widget.Dialog("dialog_new_sticky_note", {context:["new_sticky_note","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_new_sticky_note.render();

dialog_sticky_note = new YAHOO.widget.Dialog("dialog_sticky_note", {context:["sticky_note","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_sticky_note.render();

dialog_attach = new YAHOO.widget.Dialog("dialog_attach", {context:["attach","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_attach.render();

dialog_link = new YAHOO.widget.Dialog("dialog_link", {context:["link","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_link.render();

dialog_make_order = new YAHOO.widget.Dialog("dialog_make_order", {context:["make_order","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_make_order.render();

dialog_export = new YAHOO.widget.Dialog("dialog_export", {context:["export_data","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
Event.addListener("new_sticky_note", "click", dialog_new_sticky_note.show,dialog_new_sticky_note , true);
Event.addListener(["sticky_note",'sticky_note_bis'], "click", dialog_sticky_note.show,dialog_sticky_note , true);

Event.addListener("note", "click", dialog_note.show,dialog_note , true);
Event.addListener("attach", "click", dialog_attach.show,dialog_attach , true);
Event.addListener("link", "click", dialog_link.show,dialog_link , true);
Event.addListener("export_data", "click", dialog_export.show,dialog_export , true);

Event.addListener("make_order", "click", dialog_make_order.show,dialog_make_order , true);


Event.addListener("take_order", "click", take_order , true);
dialog_export.render();

 if(Dom.get('sticky_note_content').innerHTML==''){
 

                Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','none');
                Dom.setStyle('new_sticky_note_tr','display','');

            }else{

             Dom.setStyle('sticky_note_div','display','');
                Dom.setStyle('new_sticky_note_tr','display','none');
            }

			

	
			
			
/*
dialog_long_note = new YAHOO.widget.Dialog("dialog_long_note", {context:["customer_data","tl","tl"] ,visible : false,close:true,underlay: "none",draggable:false});
dialog_long_note.render();
Event.addListener("long_note", "click", dialog_long_note.show,dialog_long_note , true);

//Event.addListener("note", "click", dialog_note.hide,dialog_note , true);

*/

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
//Start Quick Edit
dialog_quick_edit_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Name", {context:["quick_edit_name","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Name.render();
dialog_quick_edit_Customer_Main_Email = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Email", {context:["quick_edit_email","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Email.render();
dialog_quick_edit_Customer_Main_Address = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Address", {context:["quick_edit_main_address","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Address.render();
dialog_quick_edit_Customer_Main_Telephone = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Telephone", {context:["quick_edit_main_telephone","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Telephone.render();
dialog_quick_edit_Customer_Main_Mobile = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Mobile", {context:["quick_edit_main_mobile","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Mobile.render();
dialog_quick_edit_Customer_Main_Fax = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Main_Fax", {context:["quick_edit_main_fax","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Main_Fax.render();
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


Event.addListener('quick_edit_name', "dblclick", dialog_quick_edit_Customer_Name.show,dialog_quick_edit_Customer_Name , true);
Event.addListener('quick_edit_email', "dblclick", dialog_quick_edit_Customer_Main_Email.show,dialog_quick_edit_Customer_Main_Email , true);
Event.addListener('quick_edit_main_address', "dblclick", dialog_quick_edit_Customer_Main_Address.show,dialog_quick_edit_Customer_Main_Address , true);
Event.addListener('quick_edit_main_telephone', "dblclick", dialog_quick_edit_Customer_Main_Telephone.show,dialog_quick_edit_Customer_Main_Telephone , true);
Event.addListener('quick_edit_main_mobile', "dblclick", dialog_quick_edit_Customer_Main_Mobile.show,dialog_quick_edit_Customer_Main_Mobile , true);
Event.addListener('quick_edit_main_fax', "dblclick", dialog_quick_edit_Customer_Main_Fax.show,dialog_quick_edit_Customer_Main_Fax , true);


<?php
	foreach($customer->get_other_emails_data() as $key=>$value)
	printf("Event.addListener('quick_edit_other_email%d', \"dblclick\", dialog_quick_edit_Customer_Email%d.show,dialog_quick_edit_Customer_Email%d , true);", $key, $key, $key);	
?>

<?php

	foreach($customer->get_other_telephones_data() as $key=>$value)
	printf("Event.addListener('quick_edit_other_telephone%d', \"dblclick\", dialog_quick_edit_Customer_Telephone%d.show,dialog_quick_edit_Customer_Telephone%d , true);", $key, $key, $key);	
?>

<?php

	foreach($customer->get_other_mobiles_data() as $key=>$value)
	printf("Event.addListener('quick_edit_other_mobile%d', \"dblclick\", dialog_quick_edit_Customer_Mobile%d.show,dialog_quick_edit_Customer_Mobile%d , true);", $key, $key, $key);	
?>

<?php

	foreach($customer->get_other_faxes_data() as $key=>$value)
	printf("Event.addListener('quick_edit_other_fax%d', \"dblclick\", dialog_quick_edit_Customer_FAX%d.show,dialog_quick_edit_Customer_FAX%d , true);", $key, $key, $key);	
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


Event.addListener('save_quick_edit_name', "click", save_quick_edit_name, true);
Event.addListener('save_quick_edit_email', "click", save_quick_edit_email, true);
Event.addListener('save_quick_edit_telephone', "click", save_quick_edit_telephone, true);
Event.addListener('save_quick_edit_mobile', "click", save_quick_edit_mobile, true);
Event.addListener('save_quick_edit_fax', "click", save_quick_edit_fax, true);

	var customer_email_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email);
    customer_email_oACDS.queryMatchContains = true;
    var customer_email_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Email","Customer_Main_Email_Container", customer_email_oACDS);
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
}
?>

    var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Telephone","Customer_Main_Telephone_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
	
    var customer_mobile_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_mobile);
    customer_mobile_oACDS.queryMatchContains = true;
    var customer_mobile_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Mobile","Customer_Main_Mobile_Container", customer_mobile_oACDS);
    customer_mobile_oAutoComp.minQueryLength = 0; 
    customer_mobile_oAutoComp.queryDelay = 0.1;
	
	var customer_fax_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax);
    customer_fax_oACDS.queryMatchContains = true;
    var customer_fax_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_FAX","Customer_Main_FAX_Container", customer_fax_oACDS);
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

function validate_customer_name(query){
//alert('query')
//alert(query)
 validate_general('customer','name',unescape(query));
}

function validate_customer_email(query){
//alert('q: ' + query)
if(query==''){
    validate_scope_data.customer.email.validated=true;
    
 if(Dom.get(validate_scope_data.customer.email.name).getAttribute('ovalue')!=query){
     validate_scope_data.customer.email.changed=true;
 }else{
    validate_scope_data.customer.email.changed=false;
 }
    
	validate_scope('customer'); 
    Dom.get(validate_scope_data.customer.email.name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
}else{
validate_general('customer','email',unescape(query));

}


}

function validate_customer_telephone(query){
    validate_general('customer','telephone',unescape(query));
    if(query==''){
        validate_scope_data.customer.telephone.validated=true;
	    validate_scope('customer'); 
	    Dom.get(validate_scope_data.customer.telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
    }
}

function validate_customer_mobile(query){
    validate_general('customer','mobile',unescape(query));
    if(query==''){
        validate_scope_data.customer.mobile.validated=true;
	    validate_scope('customer'); 
	    Dom.get(validate_scope_data.customer.mobile.name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
    }
}

function validate_customer_fax(query){
    validate_general('customer','fax',unescape(query));
    if(query==''){
        validate_scope_data.customer.fax.validated=true;
	    validate_scope('customer'); 
	    Dom.get(validate_scope_data.customer.fax.name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
    }
}

YAHOO.util.Event.onDOMReady(init);

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
