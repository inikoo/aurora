<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikoo
include_once('common.php');

if(!isset($_REQUEST['subject']) or !isset($_REQUEST['subject_key'])) exit();
if(!in_array($_REQUEST['subject'],array('customer','supplier')))exit();
$subject_type=$_REQUEST['subject'];
if($subject_type=='customer'){
include_once('class.Customer.php');
$subject=new Customer($_REQUEST['subject_key']);
}else{
include_once('class.Supplier.php');
$subject=new Supplier($_REQUEST['subject_key']);

}

?>



var dialog_quick_edit_Customer_Name;
var dialog_quick_edit_Customer_Main_Email;
var dialog_quick_edit_Customer_Main_Address;
var dialog_quick_edit_Customer_Main_Telephone;
var dialog_quick_edit_Customer_Main_Mobile;
var dialog_quick_edit_Customer_Website;
var dialog_quick_edit_Customer_Main_FAX;


var list_of_dialogs=[
"dialog_quick_edit_Subject_Name",
"dialog_quick_edit_Subject_Main_Address",
"dialog_quick_edit_Subject_Tax_Number",
"dialog_quick_edit_Subject_Registration_Number",

"dialog_quick_edit_Subject_Main_Contact_Name",
"dialog_quick_edit_Subject_Main_Email",
"dialog_quick_edit_Subject_Main_Telephone",
"dialog_quick_edit_Subject_Main_Mobile",
"dialog_quick_edit_Subject_Website",
"dialog_quick_edit_Subject_Main_FAX"
<?php
foreach($subject->get_other_emails_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Subject_Email%d\"",$key);
foreach($subject->get_other_telephones_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Subject_Telephone%d\"",$key);
foreach($subject->get_other_mobiles_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Subject_Mobile%d\"",$key);
foreach($subject->get_other_faxes_data() as $key=>$value)
	printf(",\"dialog_quick_edit_Subject_FAX%d\"",$key);
?>

];


var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";
var validate_scope_data=
{
    'subject_quick':{
//	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Subject_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Name')?>'}]}
//	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Name')?>'}]}
//	,'email':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Main_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
//	,'registration_number':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Registration_Number','validation':false}
	'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Main_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
//	,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
//	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Main_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}
//	,'web':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Website','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Website')?>'}]}
<?php

foreach($subject->get_other_emails_data()  as $email_key=>$email  ){
printf(",'email%d':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Email%d','validation':[{'regexp':regexp_valid_email,'invalid_msg':'%s'}]}",
$email_key,
$email_key,
_('Invalid Email')
);
}
foreach($subject->get_other_telephones_data()  as $telephone_key=>$telephone  ){
printf(",'telephone%d':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Telephone%d','validation':[{'regexp':regex_valid_tel,'invalid_msg':'%s'}]}",
$telephone_key,
$telephone_key,
_('Invalid Telephone')
);
}
foreach($subject->get_other_faxes_data()  as $telephone_key=>$telephone  ){
printf(",'fax%d':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_FAX%d','validation':[{'regexp':regex_valid_tel,'invalid_msg':'%s'}]}",
$telephone_key,
$telephone_key,
_('Invalid Fax')
);
}
foreach($subject->get_other_mobiles_data()  as $telephone_key=>$telephone  ){
printf(",'mobile%d':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Mobile%d','validation':[{'regexp':regex_valid_tel,'invalid_msg':'%s'}]}",
$telephone_key,
$telephone_key,
_('Invalid Mobile')
);
}


?>



  },
  'billing_quick':{
  	//'fiscal_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Subject_Fiscal_Name','ar':false,'validation':[{'regexp':"[a-zA-Z]+",'invalid_msg':'<?php echo _('Invalid Fiscal Name')?>'}]}
  	'tax_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Subject_Tax_Number','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Tax Number')?>'}]}

  }

};



var validate_scope_metadata={
'subject_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'subject_key','key':<?php echo $subject->id?>}
,'billing_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'subject_key','key':<?php echo $subject->id?>}

};

function hide_all_dialogs(){
dialog_quick_edit_Subject_Main_Telephone.hide()
}

function validate_subject_telephone(query){
    validate_general('subject_quick','telephone',unescape(query));
    if(query==''){
        validate_scope_data.subject_quick.telephone.validated=true;
	    validate_scope('subject_quick'); 
	    Dom.get(validate_scope_data.subject_quick.telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
    }
}

function validate_subject_telephone_comment(query){
	if(Dom.get('Subject_Main_Telephone_comment').getAttribute('ovalue')!=query){
		validate_scope_data.subject_quick.telephone.changed=true;
	}else{
		validate_scope_data.subject_quick.telephone.changed=false;
	}
	//alert(validate_scope_data.subject_quick.telephone.changed)
}



function dialog_quick_edit_Subject_Main_Telephone_(){
	Dom.get('Subject_Main_Telephone').value=Dom.get('Subject_Main_Telephone').getAttribute('ovalue');
	Dom.get('Subject_Main_Telephone_comment').value=Dom.get('Subject_Main_Telephone_comment').getAttribute('ovalue');
	hide_all_dialogs();
	dialog_quick_edit_Subject_Main_Telephone.show();
}

function save_quick_edit_other_telephone(telephone_key){
    save_edit_general_bulk('subject_quick');
}
function save_quick_edit_telephone(){
    save_edit_general_bulk('subject_quick');
}

function init_subject_quick(){
dialog_quick_edit_Subject_Main_Telephone = new YAHOO.widget.Dialog("dialog_quick_edit_Subject_Main_Telephone", {context:["quick_edit_main_telephone","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Subject_Main_Telephone.render();
Event.addListener('quick_edit_main_telephone', "click", dialog_quick_edit_Subject_Main_Telephone_);

Event.addListener('save_quick_edit_telephone', "click", save_quick_edit_telephone, true);
Event.addListener('close_quick_edit_telephone', "click", dialog_quick_edit_Subject_Main_Telephone.hide,dialog_quick_edit_Subject_Main_Telephone , true);

 var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Telephone","Customer_Main_Telephone_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;


}
YAHOO.util.Event.onDOMReady(init_subject_quick);
