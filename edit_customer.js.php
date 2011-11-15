<?php
	
    include_once('common.php');
    include_once('class.Customer.php');

    if (!isset($_REQUEST['id'])) {
    exit;
	
	
	
}

$customer=new Customer($_REQUEST['id']);

print "var forgot_count='".$_REQUEST['forgot_count']."';";
print "var register_count='".$_REQUEST['register_count']."';";
print "var customer_id='".$customer->id."';";
print "var store_id='".$customer->data['Customer Store Key']."';";


$tax_number_regex="^((AT)?U[0-9]{8}|(BE)?0?[0-9]{9}|(BG)?[0-9]{9,10}|(CY)?[0-9]{8}L|(CZ)?[0-9]{8,10}|(DE)?[0-9]{9}|(DK)?[0-9]{8}|(EE)?[0-9]{9}|(EL|GR)?[0-9]{9}|(ES)?[0-9A-Z][0-9]{7}[0-9A-Z]|(FI)?[0-9]{8}|(FR)?[0-9A-Z]{2}[0-9]{9}|(GB)?([0-9]{9}([0-9]{3})?|[A-Z]{2}[0-9]{3})|(HU)?[0-9]{8}|(IE)?[0-9]S[0-9]{5}L|(IT)?[0-9]{11}|(LT)?([0-9]{9}|[0-9]{12})|(LU)?[0-9]{8}|(LV)?[0-9]{11}|(MT)?[0-9]{8}|(NL)?[0-9]{9}B[0-9]{2}|(PL)?[0-9]{10}|(PT)?[0-9]{9}|(RO)?[0-9]{2,10}|(SE)?[0-9]{12}|(SI)?[0-9]{8}|(SK)?[0-9]{10})$";

$send_post_type='Letter';
$send_post_status='Cancelled';
$sql=sprintf("select * from `Customers Send Post`   where  `Customer Key`=%d  " ,
$_REQUEST['id']);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
if($row['Send Post Status']=='To Send')
	$send_post_status='To Send';
if($row['Post Type']!='Letter')
	$send_post_type='Catalogue';
}


print "var emails={";
$count=0;
foreach($customer->get_other_emails_data()  as $email_key=>$email  ){
printf("%s%d:{email:'%s'}",($count?',':''),$email_key,$email['email']);
$count++;
}

print "};";

//show case 		
$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}


$show_case=Array();
$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){

	foreach($custom_field as $key=>$value){
		$show_case[$value]=Array('value'=>$row[$key], 'lable'=>$key);
	}
}
?>



var send_post_status='<?php echo $send_post_status;?>';
var send_post_type='<?php echo $send_post_type;?>';
var Dom   = YAHOO.util.Dom;
var editing='<?php echo $_SESSION['state']['customer']['edit']?>';
var dialog_other_field_label;
var dialog_comment;
var dialog_set_password_main;
var dialog_set_password_;
//  	,'tax_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Tax_Number','validation':[{'regexp':"<?php echo $tax_number_regex?>",'invalid_msg':'<?php echo _('Invalid Tax Number')?>'}]}

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

foreach($show_case  as $custom_key=>$custom_value){
printf(",'custom_field_customer_%s':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_%s'}",
$custom_value['lable'],
$custom_value['lable']
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





YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





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






//"[ext\d\(\)\[\]\-\s]+"
var validate_scope_metadata={
'customer':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}
,'billing_data':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}

};




function change_block(e){
   var ids = ["details","company","delivery","categories","communications","merge", "password", "billing"]; 
    var block_ids = ["d_details","d_company","d_delivery","d_categories","d_communications","d_merge", "d_password", "d_billing"]; 

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value='+this.id ,{});
}

function change_to_delivery_block(){
 var ids = ["details","company","delivery","categories","communications", "password", "billing"]; 
    var block_ids = ["d_details","d_company","d_delivery","d_categories","d_communications", "d_password", "d_billing"]; 


Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_delivery','display','');
Dom.removeClass(ids,'selected');
Dom.addClass('delivery','selected');

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value=delivery' ,{});

}

function forget_password(o, email){

  var pos = Dom.getXY(o);
  
  pos[0]=pos[0]+500


    Dom.setXY('password_msg', pos);
	var store_key=Dom.get('store_key').value;
    var site_key=1;//Dom.get('site_key').value;
//email=this.getAttribute('email')
var url ='http://'+ window.location.host + window.location.pathname;
var request='ar_edit_contacts.php?tipo=forgot_password&customer_key=' + customer_id +'&store_key='+store_key + '&url='+url + '&email='+email + '&site_key='+site_key
Dom.get('password_msg').innerHTML='Sending';
Dom.get('password_msg').style.display='';
	            alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{

	            success:function(o){
					
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				Dom.get('password_msg').innerHTML="Email Sent"
				Dom.get('password_msg').style.display='';
				window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;

            }
			else{
				Dom.get('password_msg').innerHTML=r.msg;
				Dom.get('password_msg').style.display='';
			}
   			}
    });
}

function validate_customer_tax_number(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('billing_data','tax_number',unescape(query));

 if(original_query==''){
    
     validate_scope_data.billing_data.tax_number.validated=true;
     validate_scope('billing_data'); 
 }

}



function validate_customer_tax_number(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('billing_data','tax_number',unescape(query));

 if(original_query==''){
    
     validate_scope_data.billing_data.tax_number.validated=true;
     validate_scope('billing_data'); 
 }

}

function validate_customer_registration_number(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('customer','registration_number',unescape(query));

 if(original_query==''){
    
     validate_scope_data.customer.registration_number.validated=true;
     validate_scope('customer'); 
 }

}




function validate_customer_email(query){
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



function validate_customer_new_other_email(query){

 validate_general('customer','other_email',unescape(query));
if(query==''){
    validate_scope_data.customer.other_email.validated=true;
	validate_scope('customer'); 
    Dom.get(validate_scope_data.customer.other_email.name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
}

}


function validate_customer_new_other_telephone(query){
 validate_general('customer','other_telephone',unescape(query));
if(query==''){
    validate_scope_data.customer.other_telephone.validated=true;
	validate_scope('customer'); 
    Dom.get(validate_scope_data.customer.other_telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
}
}

function validate_customer_new_other_fax(query){

 validate_general('customer','other_fax',unescape(query));
if(query==''){
    validate_scope_data.customer.other_fax.validated=true;
	validate_scope('customer'); 
    Dom.get(validate_scope_data.customer.other_fax.name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
}
}

function validate_customer_new_other_mobile(query){
 validate_general('customer','other_mobile',unescape(query));
if(query==''){
    validate_scope_data.customer.other_mobile.validated=true;
	validate_scope('customer'); 
    Dom.get(validate_scope_data.customer.other_mobile.name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
}
}

function validate_customer_name(query){
 validate_general('customer','name',unescape(query));
}
function validate_customer_fiscal_name(query){
 validate_general('billing_data','fiscal_name',unescape(query));
}

function validate_customer_telephone(query){
    validate_general('customer','telephone',unescape(query));
    if(query==''){
        validate_scope_data.customer.telephone.validated=true;
	    validate_scope('customer');
		if(Dom.get('Customer_Main_Telephone').getAttribute('ovalue'))
	    Dom.get(validate_scope_data.customer.telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
    }
}

function validate_customer_mobile(query){
    validate_general('customer','mobile',unescape(query));
    if(query==''){
        validate_scope_data.customer.mobile.validated=true;
	    validate_scope('customer'); 
		if(Dom.get('Customer_Main_Mobile').getAttribute('ovalue'))
	    Dom.get(validate_scope_data.customer.mobile.name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
    }
}

function submit_change_password_(){
Dom.setStyle('dialog_set_password_','display','');
user_key=Dom.get('user_key_in_change_password_form').value;
var error=false;


	if(  Dom.get('change_password_password1_').value=='' &&  Dom.get('change_password_password1_').value==Dom.get('change_password_password2_').value ){
		Dom.addClass(['change_password_password1_','change_password_password2_'],'error');
		error=true;
		Dom.setStyle('change_password_error_no_password_','display','')

	}else{
	Dom.removeClass(['change_password_password1_','change_password_password2_'],'error');
	Dom.setStyle('change_password_error_no_password_','display','none')

	}



	if(!error){
		if( Dom.get('change_password_password1_').value!=Dom.get('change_password_password2_').value ){
			Dom.addClass(['change_password_password1_','change_password_password2_'],'error');
			if(!error)
				Dom.setStyle('change_password_error_password_not_march_','display','')
				error=true;

		}else{
			Dom.removeClass(['change_password_password1_','change_password_password2_'],'error');
			Dom.setStyle('change_password_error_password_not_march_','display','none')

		}
	}
	if(!error){
		if(!error &&   Dom.get('change_password_password1_').value.length<6){
			Dom.addClass(['change_password_password1_'],'error');

			if(!error)
				Dom.setStyle('change_password_error_password_too_short_','display','')

			
			error=true;
		}else{
			Dom.removeClass(['change_password_password1_'],'error');
			Dom.setStyle('change_password_error_password_too_short_','display','none')

		}
	}



	if(!error){
	change_password_(user_key)
	Dom.get('change_password_password1_').value='';
	Dom.get('change_password_password2_').value='';
	}
}

function submit_change_password(){
Dom.setStyle('dialog_set_password_main','display','');
var error=false;


	if(  Dom.get('change_password_password1').value=='' &&  Dom.get('change_password_password1').value==Dom.get('change_password_password2').value ){
		Dom.addClass(['change_password_password1','change_password_password2'],'error');
		error=true;
		Dom.setStyle('change_password_error_no_password','display','')

	}else{
	Dom.removeClass(['change_password_password1','change_password_password2'],'error');
	Dom.setStyle('change_password_error_no_password','display','none')

	}



	if(!error){
		if( Dom.get('change_password_password1').value!=Dom.get('change_password_password2').value ){
			Dom.addClass(['change_password_password1','change_password_password2'],'error');
			if(!error)
				Dom.setStyle('change_password_error_password_not_march','display','')
				error=true;

		}else{
			Dom.removeClass(['change_password_password1','change_password_password2'],'error');
			Dom.setStyle('change_password_error_password_not_march','display','none')

		}
	}
	if(!error){
		if(!error &&   Dom.get('change_password_password1').value.length<6){
			Dom.addClass(['change_password_password1'],'error');

			if(!error)
				Dom.setStyle('change_password_error_password_too_short','display','')

			
			error=true;
		}else{
			Dom.removeClass(['change_password_password1'],'error');
			Dom.setStyle('change_password_error_password_too_short','display','none')

		}
	}



	if(!error)
	change_password()
}

function change_password_(user_key){
Dom.setStyle('dialog_set_password_','display','none');
//alert('change');//return;
//user_key=Dom.get(o).getAttribute('user');

    //var user_key=Dom.get('user_key_'+user).value;
	//alert(user_key);return;
	

    //var store_key=Dom.get('store_key').value;
    //var site_key=Dom.get('site_key').value;
	
	//ep1=AESEncryptCtr(sha256_digest(Dom.get('change_password_password1').value),Dom.get('epwcp1').value,256);
	
	ep1=sha256_digest(Dom.get('change_password_password1_').value);
  //   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
    ep2=Dom.get('epwcp2').value;
	
//alert(ep1)	
	
//	njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
    //njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
//var url ='http://'+ window.location.host + window.location.pathname;

var data={'user_key':user_key,'ep1':ep1, 'ep2':ep2}

  var json_value = encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request=' ar_edit_users.php?tipo=change_passwd&user_id='+user_key+'&ep1='+ep1+'&ep2='+ep2;
//alert(request);//return;
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){

             


		        if(r.result=='ok'){
				//alert('Password changed!');
                //Dom.setStyle('change_password_ok','display','');
                
				window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;
    		    }else{
		      //  Dom.setStyle('change_password_ok','display','none');
                //Dom.setStyle('change_password_form','display','');
		        }
		    }else{
		        //  Dom.setStyle('change_password_ok','display','none');
               // Dom.setStyle('change_password_form','display','');
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });



}

function change_password(o){
//alert('change');//return;
    var user_key=Dom.get('user_key').value;
	

    //var store_key=Dom.get('store_key').value;
    //var site_key=Dom.get('site_key').value;
	
	//ep1=AESEncryptCtr(sha256_digest(Dom.get('change_password_password1').value),Dom.get('epwcp1').value,256);
	ep1=sha256_digest(Dom.get('change_password_password1').value);
  //   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
    ep2=Dom.get('epwcp2').value;
	
//alert(ep1)	
	
//	njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
    //njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
//var url ='http://'+ window.location.host + window.location.pathname;

var data={'user_key':user_key,'ep1':ep1, 'ep2':ep2}

  var json_value = encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request=' ar_edit_users.php?tipo=change_passwd&user_id='+user_key+'&ep1='+ep1+'&ep2='+ep2;
alert(request);//return;
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){

             


		        if(r.result=='ok'){
				alert('Password changed!');
                //Dom.setStyle('change_password_ok','display','');
                Dom.setStyle('dialog_set_password_main','display','none');
    		    }else{
		      //  Dom.setStyle('change_password_ok','display','none');
                //Dom.setStyle('change_password_form','display','');
		        }
		    }else{
		        //  Dom.setStyle('change_password_ok','display','none');
               // Dom.setStyle('change_password_form','display','');
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });



}


<?php

foreach($show_case  as $custom_key=>$custom_value){
printf("function validate_customer_%s(query){\nvalidate_general('customer','custom_field_customer_%s',unescape(query));\nif(query=='')\n{validate_scope('customer');\nDom.get('Customer_%s_msg').innerHTML='This operation will remove the %s';\n}}\n"

, $custom_value['lable']
, $custom_value['lable']
, $custom_value['lable']
, $custom_key

);
}

?>

function validate_customer_fax(query){
    validate_general('customer','fax',unescape(query));
    if(query==''){
        validate_scope_data.customer.fax.validated=true;
	    validate_scope('customer'); 
		if(Dom.get('Customer_Main_FAX').getAttribute('ovalue'))
	    Dom.get(validate_scope_data.customer.fax.name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
    }
}



function validate_customer_main_contact_name(query){
 validate_general('customer','contact',unescape(query));
}

function save_edit_billing_data(){
    save_edit_general_bulk('billing_data');
}

function reset_edit_billing_data(){
    reset_edit_general('billing_data')
}

function save_edit_customer(){
    save_edit_general_bulk('customer');
}

function reset_edit_customer(){
    reset_edit_general('customer')
}


function save_comunications_send_post(key,value){
var request='ar_edit_contacts.php?tipo=edit_customer_send_post&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	            alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	            alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
                                 if(r.key=='Post Type')
					{			 
                                         if (r.newvalue=='Letter' || r.newvalue=='Catalogue') {
                          			 Dom.removeClass([r.key+'_Catalogue',r.key+'_Letter'],'selected');
                                        	 Dom.addClass(r.key+'_'+r.newvalue,'selected');
                                        	 }else {
                                        	 alert(r.msg)
                                        	 }
                                         }
				 if(r.key=='Send Post Status')
					{			 
                                         if (r.newvalue=='To Send' || r.newvalue=='Cancelled') {
                          			 Dom.removeClass([r.key+'_Cancelled',r.key+'_To Send'],'selected');
                                        	 Dom.addClass(r.key+'_'+r.newvalue,'selected');
                                        	 }else {
                                        	 alert(r.msg)
                                        	 }
                                         }
                                  }
   			}
    });
}



function save_comunications(key,value){

 var data_to_update=new Object;
 data_to_update[key]={'okey':key,'value':value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
				var ra =  YAHOO.lang.JSON.parse(o.responseText);
				  for (x in ra){
               r=ra[x]
				if(r.state==200){
			
  
 
            if (r.newvalue=='No' || r.newvalue=='Yes') {
                           Dom.removeClass([r.key+'_No',r.key+'_Yes'],'selected');

               Dom.addClass(r.key+'_'+r.newvalue,'selected');

            }else{
                alert(r.msg)
            }
            }
        }
    }
    });

}

function save_checkout(o) {

var category_key=o.getAttribute('cat_id')
var subject='Customer';
var subject_key=Dom.get('customer_key').value;
if(Dom.hasClass(o,'selected'))
    var operation_type='disassociate_subject_to_category';
else
    var operation_type='associate_subject_to_category';

var request='ar_edit_categories.php?tipo='+operation_type+'&category_key=' + category_key+ '&subject=' + subject +'&subject_key=' + subject_key +"&cat_id="+o.id
		
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				 
            if (r.action=='deleted') {
                Dom.removeClass(r.cat_id,'selected');

            }else if(r.action=='added'){
                            Dom.addClass(r.cat_id,'selected');

            }else{
                alert(r.msg)
            }
        }
    }
    });



}

function save_category(o) {

var parent_category_key=o.getAttribute('cat_key');
var category_key=o.options[o.selectedIndex].value;
var subject='Customer';
var subject_key=Dom.get('customer_key').value;

//if(Dom.hasClass(o,'selected'))
//    var operation_type='disassociate_subject_to_category_radio';
//else


if(category_key==''){
var request='ar_edit_categories.php?tipo=disassociate_subject_from_all_sub_categories&category_key=' + parent_category_key+ '&subject=' + subject +'&subject_key=' + subject_key 

}else{
var request='ar_edit_categories.php?tipo=associate_subject_to_category_radio&category_key=' + category_key+ '&subject=' + subject +'&subject_key=' + subject_key +"&parent_category_key="+parent_category_key+"&cat_id="+o.id


}


	//alert(request);
	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
			//alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				}


        
    }
                                                                 });



}
 
			



function save_billing_address(e,options){
save_address(e,options);

        Dom.setStyle(['billing_address','show_edit_billing_address'],'display','')

    Dom.setStyle(['set_contact_address_as_billing','new_billing_address_table'],'display','none')
}


function reset_billing_address(){
reset_address(false,'billing_');

Dom.get('billing_address').style.display='';
    Dom.get('show_edit_billing_address').style.display='';

}

function back_to_take_order(){

    location.href='order.php?id=+id'; 


}

function save_convert_to_company(){
if(Dom.hasClass('save_convert_to_company','disabled')){
return;
}

var request='ar_edit_contacts.php?tipo=convert_customer_to_company&company_name=' + encodeURIComponent(Dom.get('New_Company_Name').value) +'&customer_key=' + customer_id
	           
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           // alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
        location.href='edit_customer.php?id='+customer_id;
                                  }else{
                                  Dom.get('New_Company_Name_msg').innerHTML=r.msg
                                  }
   			}
    });


}
function cancel_convert_to_company(){
Dom.setStyle(['New_Company_Name_tr','save_convert_to_company','cancel_convert_to_company'],'display','none');
Dom.setStyle('convert_to_company','display','');
Dom.get('New_Company_Name').value='';
}
function convert_to_company(){
Dom.setStyle(['New_Company_Name_tr','save_convert_to_company','cancel_convert_to_company'],'display','');
Dom.setStyle('convert_to_company','display','none');
Dom.get('New_Company_Name').focus();
}



function save_convert_to_person(){
if(Dom.hasClass('save_convert_to_person','disabled')){
return;
}

var request='ar_edit_contacts.php?tipo=convert_customer_to_person&customer_key=' + customer_id
	           
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           //alert(o.responseText);	
			    var r =  YAHOO.lang.JSON.parse(o.responseText);
			    if(r.state==200){
                    location.href='edit_customer.php?id='+customer_id;
                }else{
                    Dom.get('convert_to_person_info').innerHTML=r.msg;
                }
   			}
    });


}
function cancel_convert_to_person(){
Dom.setStyle(['convert_to_person_info','save_convert_to_person','cancel_convert_to_person'],'display','none');
Dom.setStyle('convert_to_person','display','');
}
function convert_to_person(){
Dom.setStyle(['convert_to_person_info','save_convert_to_person','cancel_convert_to_person'],'display','');
Dom.setStyle('convert_to_person','display','none');
}




function validate_new_company_name(query){

  var validator=new RegExp('/[a-z0-9]/',"i");
    if (!validator.test(query)) {
        Dom.removeClass('save_convert_to_company','disabled')

    } else {
   Dom.addClass('save_convert_to_company','disabled')
   
    }
    
}

function delete_customer(){
Dom.setStyle(['save_delete_customer','cancel_delete_customer','delete_customer_warning'],'display','');
Dom.setStyle('delete_customer','display','none');
}
function cancel_delete_customer(){
Dom.setStyle(['save_delete_customer','cancel_delete_customer','delete_customer_warning'],'display','none');
Dom.setStyle('delete_customer','display','');
}

function save_delete_customer(){


var request='ar_edit_contacts.php?tipo=delete_customer&customer_key=' + customer_id
	           
	           Dom.setStyle('deleting','display','');
	           	           Dom.setStyle(['save_delete_customer','cancel_delete_customer'],'display','none');

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
        location.href='customer.php?id='+customer_id;
                                  }else{
                                   Dom.setStyle('deleting','display','none');
                                  Dom.get('delete_customer_msg').innerHTML=r.msg
                                  }
   			}
    });


}


function merge(query){
var request='ar_contacts.php?tipo=can_merge_customer&customer_key='+Dom.get('customer_key').value+'&customer_to_merge_id='+query
	         
	       Dom.get('go_merge').href='';
	          Dom.setStyle(['go_merge','merge_msg'],'display','none');
	       //    	           Dom.setStyle(['save_delete_customer','cancel_delete_customer'],'display','none');

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	         //  alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			Dom.get('merge_msg').innerHTML=r.msg;
				          Dom.setStyle(['merge_msg'],'display','');

			if(r.state==200){
                    if(r.action=='ok'){
                    Dom.setStyle(['merge_msg'],'display','none');
                    Dom.get('go_merge').href='customer_split_view.php?p=a_edit&id_a='+Dom.get('customer_key').value+'&id_b='+r.id;
                    Dom.setStyle(['go_merge'],'display','');
            
                    }
                                  }
   			}
    });

}

function post_change_main_delivery_address(){}



function display_new_billing_address(){
    Dom.setStyle(['show_new_billing_address','billing_address'],'display','none')
    Dom.setStyle('new_billing_address_table','display','')
}

/*
function display_edit_billing_address(){
address_id=Dom.get('show_edit_billing_address').getAttribute('address_key');
    edit_address(address_id,'billing_')
    Dom.setStyle(['new_billing_address_table','set_contact_address_as_billing'],'display','')
    Dom.setStyle(['show_edit_billing_address','billing_address','billing_tr_address_type','billing_tr_address_function'],'display','none')
}
*/
function hide_billing_address_form(){
address_prefix='billing_';
 if (Dom.get(address_prefix+'address_key').value==0) {
hide_new_billing_address()
}else{
hide_edit_billing_address()
}

}

function hide_edit_billing_address(){
    reset_address(false,'billing_')
    
    Dom.setStyle(['billing_address','show_edit_billing_address'],'display','')

    Dom.setStyle(['new_billing_address_table','set_contact_address_as_billing'],'display','none')
}



function hide_new_billing_address(){
    reset_address(false,'billing_')
    
    Dom.setStyle(['billing_address','show_new_billing_address'],'display','')

    Dom.setStyle('new_billing_address_table','display','none')
}

function set_contact_address_as_billing(){

var request='ar_edit_contacts.php?tipo=set_contact_address_as_billing&customer_key=' + customer_id

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	    	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
       Dom.get('billing_address').innerHTML=r.xhtml_billing_address;
            Dom.setStyle(['new_billing_address_table','set_contact_address_as_billing','show_edit_billing_address'],'display','none')
            Dom.setStyle(['show_new_billing_address','billing_address'],'display','')
            Dom.get('show_edit_billing_address').setAttribute('address_key',0)
reset_address(false,'billing_')
                                  }else{
                                 
                                 
                                  }
   			}
    });

}


//change_comment


function change_comment(o,type,key){

 var pos = Dom.getXY(o);
 

 Dom.get('comment_scope_key').value=key;
 Dom.get('comment_scope').value=type;




 if(type=='email'){


    Dom.get('comment').value=Dom.get('comment_email').value;
 }else if(type=='telephone'){
    Dom.get('comment').value=Dom.get('comment_telephone').value;
 }else if(type=='mobile'){
    Dom.get('comment').value=Dom.get('comment_mobile').value;
 }else if(type=='fax'){
    Dom.get('comment').value=Dom.get('comment_fax').value;
 }

  dialog_comment.show();
  Dom.setXY('dialog_comment', pos);
  Dom.get("comment").focus();
}


function change_other_field_label(o,type,key){

 var pos = Dom.getXY(o);
 


 Dom.get('other_field_label_scope_key').value=key;
 Dom.get('other_field_label_scope').value=type;

 if(type=='email'){
    Dom.get('other_field_label_scope_name').innerHTML='<?php echo _('Email')?>';
 }else if(type=='telephone'){
    Dom.get('other_field_label_scope_name').innerHTML='<?php echo _('Telephone')?>';
 }else if(type=='mobile'){
    Dom.get('other_field_label_scope_name').innerHTML='<?php echo _('Mobile')?>';
 }else if(type=='fax'){
    Dom.get('other_field_label_scope_name').innerHTML='<?php echo _('Fax')?>';
 }

  Dom.get("other_field_label").value='';
  dialog_other_field_label.show();
  Dom.setXY('dialog_other_field_label', pos);
  Dom.get("other_field_label").focus();
}


function save_preferred(o,value){


if(Dom.hasClass(o,'selected'))
    return;
 var data_to_update=new Object;
 data_to_update['preferred_contact_number']={'okey':'preferred_contact_number','value':value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
		    
			Dom.removeClass(['Customer_Preferred_Contact_Number_Mobile','Customer_Preferred_Contact_Number_Telephone'],'selected');
					Dom.addClass('Customer_Preferred_Contact_Number_'+r.newvalue,'selected');


          

           
         
   
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
		}
	    });        
}

function save_comment(){

//alert(Dom.get('comment_scope').value);return;

var tipo=Dom.get('comment_scope').value+'_label'+Dom.get('comment_scope_key').value;
//alert(tipo);
 var data_to_update=new Object;
 data_to_update[tipo]={'okey':tipo,'value':Dom.get("comment").value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id
//alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
		    
			
			dialog_comment.hide()


  
  

 if(r.scope=='email'){
Dom.get('comment_email').value=r.newvalue;
                }else if(r.scope=='telephone'){
Dom.get('comment_telephone').value=r.newvalue;
                }else if(r.scope=='mobile'){
Dom.get('comment_mobile').value=r.newvalue;
                }else if(r.scope=='fax'){
Dom.get('comment_fax').value=r.newvalue;
                }



            if(r.newvalue==''){
              
Dom.get('comment_icon_'+r.scope).src='art/icons/comment.gif';
            }else{
                 
           Dom.get('comment_icon_'+r.scope).src='art/icons/comment_filled.gif';

            }
   
			
		    }else
			Dom.get('comment_msg').innerHTML=r.msg;
		}
		}
	    });        
	

}


function save_other_field_label(){



var tipo=Dom.get('other_field_label_scope').value+'_label'+Dom.get('other_field_label_scope_key').value;
//alert(tipo)
 var data_to_update=new Object;
 data_to_update[tipo]={'okey':tipo,'value':Dom.get("other_field_label").value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_contacts.php?tipo=edit_customer&values='+ jsonificated_values+"&customer_key="+customer_id

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
		    
			
			dialog_other_field_label.hide()

            if(r.newvalue==''){
                if(r.scope=='email'){
                    label='<?php echo _('Other Email')?>';
                }else if(r.scope=='telephone'){
                    label='<?php echo _('Other Telephone')?>';
                }else if(r.scope=='mobile'){
                    label='<?php echo _('Other Mobile')?>';
                }else if(r.scope=='fax'){
                    label='<?php echo _('Other Fax')?>';
                }else{
                    label='error';
                }
                
                Dom.get('tr_other_'+r.scope+'_label'+r.scope_key).innerHTML=label;

            }else{
                      if(r.scope=='email'){
                    label='<?php echo _('Email')?>';
                }else if(r.scope=='telephone'){
                    label='<?php echo _('Telephone')?>';
                }else if(r.scope=='mobile'){
                    label='<?php echo _('Mobile')?>';
                }else if(r.scope=='fax'){
                    label='<?php echo _('Fax')?>';
                }else{
                    label='error';
                }      
                           
                Dom.get('tr_other_'+r.scope+'_label'+r.scope_key).innerHTML=r.newvalue+' ('+label+')';

           
            }
   
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
		}
	    });        
	

}

function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;

if(r.action=='other_email_added' || r.action=='other_telephone_added'  || r.action=='other_fax_added' || r.action=='other_mobile_added' ){
setTimeout("location.reload(true)", 100);
}else if(r.action=='other_email_deleted' || r.action=='other_telephone_deleted'|| r.action=='other_fax_deleted'|| r.action=='other_mobile_deleted'){

Dom.setStyle('tr_other_email'+r.email_key,'display','none')
}



if(Dom.get('Customer_Main_Telephone').value=='' || Dom.get('Customer_Main_Mobile').value=='' ){
    Dom.setStyle('tr_Customer_Preferred_Contact_Number','display','none');
}else{
 Dom.setStyle('tr_Customer_Preferred_Contact_Number','display','');
}


}


function display_add_other_email(){
Dom.setStyle('display_add_other_email','display','none');
Dom.setStyle('tr_add_other_email','display','');
}

function display_add_other_telephone(){
Dom.setStyle('display_add_other_telephone','display','none');
Dom.setStyle('tr_add_other_telephone','display','');
}
function display_add_other_fax(){
Dom.setStyle('display_add_other_fax','display','none');
Dom.setStyle('tr_add_other_fax','display','');
}
function display_add_other_mobile(){
Dom.setStyle('display_add_other_mobile','display','none');
Dom.setStyle('tr_add_other_mobile','display','');
}

function register_email(o){
	  var pos = Dom.getXY(o);
  
  pos[0]=pos[0]+300


    Dom.setXY('register_msg', pos);
	
	email=Dom.get(o).getAttribute('email');
	//alert(email);
	var url ='http://'+ window.location.host + window.location.pathname;
password='xxxxxxxxxxxxxx';

	data['Email']=Dom.get(o).getAttribute('email');
	data['customer_id']=customer_id;
	data['store_id']=store_id;
	data['password']=password;
	data['send_email']=true;
	data['url']=url;

	var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
  
  
  
var request='ar_edit_users.php?tipo=create_user&values=' + json_value 
Dom.get('register_msg').innerHTML="Registering in the system"
				Dom.get('register_msg').style.display='';
	            alert(request);	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
				
	            success:function(o){
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state=='200'){
					Dom.get('register_msg').style.display='none';
					window.location ='http://'+ window.location.host + window.location.pathname+'?id='+customer_id;

				
				}


   			}
    });
	


	
}


function show_change_password_dialog(o,user_key){
Dom.get('user_key_in_change_password_form').value=user_key;


  var pos = Dom.getXY(o);
  
  pos[0]=pos[0]-300
 Dom.get('change_password_password1_').focus();

    Dom.setXY('dialog_set_password_', pos);



dialog_set_password_.show();
//submit_change_password_(user_key);


}

function init(){




  init_search('customers_store');


 
dialog_other_field_label = new YAHOO.widget.Dialog("dialog_other_field_label", {visible : false,close:true,underlay: "none",draggable:false});
dialog_other_field_label.render();


dialog_comment = new YAHOO.widget.Dialog("dialog_comment", {visible : false,close:true,underlay: "none",draggable:false});
dialog_comment.render();

    Event.addListener("display_add_other_email", "click", display_add_other_email , true);

    Event.addListener("display_add_other_telephone", "click", display_add_other_telephone , true);
    Event.addListener("display_add_other_mobile", "click", display_add_other_mobile , true);
    Event.addListener("display_add_other_fax", "click", display_add_other_fax , true);
	
<?php 
	echo 'var ids = ["forget_password_main"';
	for($i=0; $i<$_REQUEST['forgot_count']; $i++){
		echo ', "forget_password_'.$i.'"';
	}
	echo '];';
	

?>
	Event.addListener(ids, "click", forget_password );
	/*
<?php 

	echo 'var ids_show = [';
	for($i=0; $i<$_REQUEST['register_count']; $i++){
		echo ' "register_'.$i.'",';
	}
	echo '];';
	

?>

	//var ids_show = ["show_register_block_0", ""];

	Event.addListener(ids_show, "click", register , true);
	*/
	
 var customer_merge_oACDS = new YAHOO.util.FunctionDataSource(merge);
    customer_merge_oACDS.queryMatchContains = true;
    var customer_merge_oAutoComp = new YAHOO.widget.AutoComplete("customer_b_id","customer_b_id_Container", customer_merge_oACDS);
    customer_merge_oAutoComp.minQueryLength = 0; 
    customer_merge_oAutoComp.queryDelay = 0.2;

Dom.addClass('Send Post Status'+'_'+send_post_status,'selected');
Dom.addClass('Post Type'+'_'+send_post_type,'selected');
  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
    oAutoComp.minQueryLength = 0; 
    var ids = ["details","company","delivery","categories","communications","merge", "password", "billing"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener( "delivery2",  "click",change_to_delivery_block);
    
    
    Event.addListener("back_to_take_order", "click", back_to_take_order , true);
    
        YAHOO.util.Event.addListener('show_edit_billing_address', "click",display_edit_billing_address );
        YAHOO.util.Event.addListener('show_new_billing_address', "click",display_new_billing_address );

    
    YAHOO.util.Event.addListener('save_edit_customer', "click", save_edit_customer);
    YAHOO.util.Event.addListener('reset_edit_customer', "click", reset_edit_customer);
    
     YAHOO.util.Event.addListener('save_edit_billing_data', "click", save_edit_billing_data);
    YAHOO.util.Event.addListener('reset_edit_billing_data', "click", reset_edit_billing_data);
  
    
        YAHOO.util.Event.addListener('delete_customer', "click", delete_customer);
        YAHOO.util.Event.addListener('cancel_delete_customer', "click", cancel_delete_customer);
        YAHOO.util.Event.addListener('save_delete_customer', "click", save_delete_customer);

    YAHOO.util.Event.addListener('convert_to_company', "click", convert_to_company);
    YAHOO.util.Event.addListener('cancel_convert_to_company', "click", cancel_convert_to_company);
    YAHOO.util.Event.addListener('save_convert_to_company', "click", save_convert_to_company);

 YAHOO.util.Event.addListener('convert_to_person', "click", convert_to_person);
    YAHOO.util.Event.addListener('cancel_convert_to_person', "click", cancel_convert_to_person);
    YAHOO.util.Event.addListener('save_convert_to_person', "click", save_convert_to_person);


  var new_company_name_oACDS = new YAHOO.util.FunctionDataSource(validate_new_company_name);
    new_company_name_oACDS.queryMatchContains = true;
    var new_company_name_oAutoComp = new YAHOO.widget.AutoComplete("New_Company_Name","New_Company_Name_Container", new_company_name_oACDS);
    new_company_name_oAutoComp.minQueryLength = 0; 
    new_company_name_oAutoComp.queryDelay = 0.1;


   var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_name);
    customer_name_oACDS.queryMatchContains = true;
    var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Name","Customer_Name_Container", customer_name_oACDS);
    customer_name_oAutoComp.minQueryLength = 0; 
    customer_name_oAutoComp.queryDelay = 0.1;

     var customer_fiscal_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fiscal_name);
    customer_fiscal_name_oACDS.queryMatchContains = true;
    var customer_fiscal_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Fiscal_Name","Customer_Fiscal_Name_Container", customer_fiscal_name_oACDS);
    customer_fiscal_name_oAutoComp.minQueryLength = 0; 
    customer_fiscal_name_oAutoComp.queryDelay = 0.1;
    
    
    var customer_email_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email);
    customer_email_oACDS.queryMatchContains = true;
    var customer_email_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Email","Customer_Main_Email_Container", customer_email_oACDS);
    customer_email_oAutoComp.minQueryLength = 0; 
    customer_email_oAutoComp.queryDelay = 0.1;

<?php
foreach($customer->get_other_emails_data()  as $email_key=>$email  ){
printf("var customer_email%d_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email_other);\ncustomer_email%d_oACDS.queryMatchContains = true;\nvar customer_email%d_oAutoComp = new YAHOO.widget.AutoComplete('Customer_Email%d','Customer_Email%d_Container', customer_email%d_oACDS);\ncustomer_email%d_oAutoComp.minQueryLength = 0;\ncustomer_email%d_oAutoComp.queryDelay = 0.1;;\ncustomer_email%d_oAutoComp.email_id =%d;",
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

<?php

foreach($show_case  as $custom_key=>$custom_value){
printf("var customer_%s_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_%s);\ncustomer_%s_oACDS.queryMatchContains = true;\nvar customer_%s_oAutoComp = new YAHOO.widget.AutoComplete('Customer_%s','Customer_%s_Container', customer_%s_oACDS);\ncustomer_%s_oAutoComp.minQueryLength = 0;\ncustomer_%s_oAutoComp.queryDelay = 0.1;",
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable'],
$custom_value['lable']
);
}

?>
   var customer_other_email_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_new_other_email);
    customer_other_email_oACDS.queryMatchContains = true;
    var customer_other_email_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Other_Email","Customer_Other_Email_Container", customer_other_email_oACDS);
    customer_other_email_oAutoComp.minQueryLength = 0; 
    customer_other_email_oAutoComp.queryDelay = 0.1;


 var customer_other_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_new_other_telephone);
    customer_other_telephone_oACDS.queryMatchContains = true;
    var customer_other_telephone_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Other_Telephone","Customer_Other_Telephone_Container", customer_other_telephone_oACDS);
    customer_other_telephone_oAutoComp.minQueryLength = 0; 
    customer_other_telephone_oAutoComp.queryDelay = 0.1;
    
     var customer_other_mobile_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_new_other_mobile);
    customer_other_mobile_oACDS.queryMatchContains = true;
    var customer_other_mobile_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Other_Mobile","Customer_Other_Mobile_Container", customer_other_mobile_oACDS);
    customer_other_mobile_oAutoComp.minQueryLength = 0; 
    customer_other_mobile_oAutoComp.queryDelay = 0.1;
    
     var customer_other_fax_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_new_other_fax);
    customer_other_fax_oACDS.queryMatchContains = true;
    var customer_other_fax_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Other_FAX","Customer_Other_FAX_Container", customer_other_fax_oACDS);
    customer_other_fax_oAutoComp.minQueryLength = 0; 
    customer_other_fax_oAutoComp.queryDelay = 0.1;

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
    
    
     var customer_main_contact_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_main_contact_name);
    customer_main_contact_name_oACDS.queryMatchContains = true;
    var customer_main_contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Contact_Name","Customer_Main_Contact_Name_Container", customer_main_contact_name_oACDS);
    customer_main_contact_name_oAutoComp.minQueryLength = 0; 
    customer_main_contact_name_oAutoComp.queryDelay = 0.1;
	


  var customer_Tax_Number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_tax_number);
    customer_Tax_Number_oACDS.queryMatchContains = true;
    var customer_Tax_Number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Tax_Number","Customer_Tax_Number_Container", customer_Tax_Number_oACDS);
    customer_Tax_Number_oAutoComp.minQueryLength = 0; 
    customer_Tax_Number_oAutoComp.queryDelay = 0.1;
    
    
     var customer_Registration_Number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_registration_number);
    customer_Registration_Number_oACDS.queryMatchContains = true;
    var customer_Registration_Number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Registration_Number","Customer_Registration_Number_Container", customer_Registration_Number_oACDS);
    customer_Registration_Number_oAutoComp.minQueryLength = 0; 
    customer_Registration_Number_oAutoComp.queryDelay = 0.1;
    

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
	


var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("billing_address_country", "billing_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='billing_';
    Countries_AC.prefix='billing_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);

 

     var ids = ["billing_address_description","billing_address_country_d1","billing_address_country_d2","billing_address_town","billing_address_town_d2","billing_address_town_d1","billing_address_postal_code","billing_address_street","billing_address_internal","billing_address_building"]; 
	     YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'billing_');
	     YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'billing_');

 YAHOO.util.Event.addListener('billing_save_address_button', "click",save_billing_address,{prefix:'billing_',subject:'Customer',subject_key:customer_id,type:'Billing'});
	

//	 YAHOO.util.Event.addListener('billing_reset_address_button', "click",reset_billing_address);
	YAHOO.util.Event.addListener('delivery_reset_address_button', "click",hide_new_delivery_address,'delivery_');
	YAHOO.util.Event.addListener('billing_reset_address_button', "click",hide_billing_address_form,'billing_');

    YAHOO.util.Event.addListener('set_contact_address_as_billing', "click", set_contact_address_as_billing);



 var customer_merge_oACDS = new YAHOO.util.FunctionDataSource(merge);
    customer_merge_oACDS.queryMatchContains = true;
    var customer_merge_oAutoComp = new YAHOO.widget.AutoComplete("customer_b_id","customer_b_id_Container", customer_merge_oACDS);
    customer_merge_oAutoComp.minQueryLength = 0; 
    customer_merge_oAutoComp.queryDelay = 0.1;

		dialog_set_password_main = new YAHOO.widget.Dialog("dialog_set_password_main", {context:["set_password_main","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_set_password_main.render();
	
	dialog_set_password_ = new YAHOO.widget.Dialog("dialog_set_password_", { visible : false,close:true,underlay: "none",draggable:false});
    dialog_set_password_.render();
/*	
	<?php
	for($i=0; $i<$_REQUEST['forgot_count']; $i++){
	printf("dialog_set_password_%s = new YAHOO.widget.Dialog(\"dialog_set_password_%s\", {context:[\"set_password_%s\",\"tr\",\"tl\"]  ,visible : false,close:true,underlay: \"none\",draggable:false});
    dialog_set_password_%s.render();
	", $i,$i, $i, $i);
	}
	?>

	/*
<?php
for($i=0; $i<$_REQUEST['forgot_count']; $i++){
printf("Event.addListener(\"set_password_%s\", \"click\", dialog_set_password_%s.show,dialog_set_password_%s , true);", $i, $i, $i);
}
?>	
*/
<?php 
	//echo 'var ids = ["set_password_main__"';
	for($i=0; $i<$_REQUEST['forgot_count']; $i++){
		//echo ', "set_password_'.$i.'"';
	
	//echo '];';
	//echo 'Event.addListener("set_password_'.$i.'", "click", dialog_set_password_.show,dialog_set_password_ ,'.$i.');';
	}
?>
//alert(ids)
Event.addListener("set_password_main", "click", dialog_set_password_main.show,dialog_set_password_main , true);

//Event.addListener(ids, "click", dialog_set_password_.show,dialog_set_password_ , true);

Event.addListener("submit_change_password", "click",submit_change_password);
Event.addListener("submit_change_password_", "click",submit_change_password_, true);
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
	YAHOO.util.Event.addListener("rtext_rpp0", "click",oMenu.show , null, oMenu);
	
	
	
	
});

