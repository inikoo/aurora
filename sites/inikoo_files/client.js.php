

var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";
var validate_scope_data=
{
    'customer':{
	//'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	//,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	'email':{'ar':false,'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
	
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'fax','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}
	}};
	
var validate_scope_metadata={
'customer':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':''}

};	

function check_validated(branch){
var flag_var=true;
	for (items in validate_scope_data[branch]) {
        if (validate_scope_data[branch][items].validated==false)
			flag_var=false;
	}
	
	if(!flag_var){
		Dom.setStyle('submit','display','none');
	}
	else
		Dom.setStyle('submit','display','');
}

function validate_customer_telephone(query){
	validate_general('customer','telephone',unescape(query));
	if(query==''){
		validate_scope_data.customer.telephone.validated=true;
		validate_scope('customer');
		if(Dom.get('telephone').getAttribute('ovalue'))
		Dom.get(validate_scope_data.customer.telephone.name+'_msg').innerHTML='<?php echo _('This operation will remove the telephone')?>';
	}
	if(query==Dom.get('telephone').getAttribute('ovalue'))
		Dom.get(validate_scope_data.customer.telephone.name+'_msg').innerHTML='';
	
	check_validated('customer')
}
	
function validate_customer_mobile(query){
    validate_general('customer','mobile',unescape(query));
    if(query==''){
        validate_scope_data.customer.mobile.validated=true;
	    validate_scope('customer'); 
		if(Dom.get('mobile').getAttribute('ovalue'))
	    Dom.get(validate_scope_data.customer.mobile.name+'_msg').innerHTML='<?php echo _('This operation will remove the mobile')?>';
    }
		if(query==Dom.get('mobile').getAttribute('ovalue'))
		Dom.get(validate_scope_data.customer.mobile.name+'_msg').innerHTML='';
		
		check_validated('customer')
}

function validate_customer_fax(query){
    validate_general('customer','fax',unescape(query));
    if(query==''){
        validate_scope_data.customer.fax.validated=true;
	    validate_scope('customer'); 
		if(Dom.get('fax').getAttribute('ovalue'))
	    Dom.get(validate_scope_data.customer.fax.name+'_msg').innerHTML='<?php echo _('This operation will remove the fax')?>';
    }
			if(query==Dom.get('fax').getAttribute('ovalue'))
		Dom.get(validate_scope_data.customer.fax.name+'_msg').innerHTML='';
		
			check_validated('customer')
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
	
		check_validated('customer')
}


function init(){
    var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("telephone","telephone_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
	
	var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_mobile);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("mobile","mobile_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
	
	var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_fax);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("fax","fax_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
	
    var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_email);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("email","email_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
}

YAHOO.util.Event.onDOMReady(init);