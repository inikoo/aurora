<?php
    include_once('common.php');
        include_once('class.Customer.php');

    $customer=new Customer($_SESSION['state']['customer']['id']);
    
print "var customer_id='".$_SESSION['state']['customer']['id']."';";


$tax_number_regex="^((AT)?U[0-9]{8}|(BE)?0?[0-9]{9}|(BG)?[0-9]{9,10}|(CY)?[0-9]{8}L|(CZ)?[0-9]{8,10}|(DE)?[0-9]{9}|(DK)?[0-9]{8}|(EE)?[0-9]{9}|(EL|GR)?[0-9]{9}|(ES)?[0-9A-Z][0-9]{7}[0-9A-Z]|(FI)?[0-9]{8}|(FR)?[0-9A-Z]{2}[0-9]{9}|(GB)?([0-9]{9}([0-9]{3})?|[A-Z]{2}[0-9]{3})|(HU)?[0-9]{8}|(IE)?[0-9]S[0-9]{5}L|(IT)?[0-9]{11}|(LT)?([0-9]{9}|[0-9]{12})|(LU)?[0-9]{8}|(LV)?[0-9]{11}|(MT)?[0-9]{8}|(NL)?[0-9]{9}B[0-9]{2}|(PL)?[0-9]{10}|(PT)?[0-9]{9}|(RO)?[0-9]{2,10}|(SE)?[0-9]{12}|(SI)?[0-9]{8}|(SK)?[0-9]{10})$";


$addresses=$customer->get_address_keys();
//$addresses[$customer->data['Customer Billing Address Key']]=$customer->data['Customer Billing Address Key'];
$address_data="\n";
//$address_data.=sprintf('0:{"key":0,"country":"","country_code":"UNK","country_d1":"","country_d2":"","town":"","postal_code":"","town_d1":"","town_d2":"","fuzzy":"","street":"","building":"","internal":"","type":["Office"],"description":"","function":["Contact"] } ' );
 $address_data.="\n";
foreach($addresses as $index){
   // $address->set_scope('Customer',$cust);
    
    $address=new Address($index);


    $type="[";
    foreach($address->get('Type') as $_type){
	$type.=prepare_mysql($_type,false).",";
    }
    $type.="]";
    $type=preg_replace('/,]$/',']',$type);
    
    $function="[";
    foreach($address->get('Function') as $value){
	$function.=prepare_mysql($value,false).",";
    }
    $function.="]";
    $function=preg_replace('/,]$/',']',$function);
    

  $address_data.="\n".sprintf('Address_Data[%d]={"key":%d,"country":%s,"country_code":%s,"country_d1":%s,"country_d2":%s,"town":%s,"postal_code":%s,"town_d1":%s,"town_d2":%s,"fuzzy":%s,"street":%s,"building":%s,"internal":%s,"type":%s,"description":%s,"function":%s}; ',
			
			 $address->id
			 ,$address->id
			 ,prepare_mysql($address->data['Address Country Name'],false)
			 ,prepare_mysql($address->data['Address Country Code'],false)
			 ,prepare_mysql($address->data['Address Country First Division'],false)
			 ,prepare_mysql($address->data['Address Country Second Division'],false)
			 ,prepare_mysql($address->data['Address Town'],false)
			 ,prepare_mysql($address->data['Address Postal Code'],false)
			 ,prepare_mysql($address->data['Address Town First Division'],false)
			 ,prepare_mysql($address->data['Address Town Second Division'],false)
			 ,prepare_mysql($address->data['Address Fuzzy'],false)
			 ,prepare_mysql($address->display('street',false),false)
			 ,prepare_mysql($address->data['Address Building'],false)
			 ,prepare_mysql($address->data['Address Internal'],false)
			 ,$type
			 ,prepare_mysql($address->data['Address Description'],false)
			 ,$function

			 );
  $address_data.="\n";




}
print $address_data;






?>
  
var Dom   = YAHOO.util.Dom;
var editing='<?php echo $_SESSION['state']['customer']['edit']?>';


var validate_scope_data=
{
    'customer':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	,'email':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Telephone','validation':[{'regexp':"[ext\\d\\(\\)\\[\\]\\-\\s]+",'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
  	,'tax_number':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Tax_Number','validation':[{'regexp':"<?php echo $tax_number_regex?>",'invalid_msg':'<?php echo _('Invalid Tax Number')?>'}]}

  },
  'billing_data':{
  	'fiscal_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Fiscal_Name','ar':false,'validation':[{'regexp':"[a-zA-Z]+",'invalid_msg':'<?php echo _('Invalid Fiscal Name')?>'}]}

  }
  
};

//"[ext\d\(\)\[\]\-\s]+"
var validate_scope_metadata={
'customer':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}
,'billing_data':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}

};

function validate_scope_old(){
    var changed=false;
    var errors=false;
    //alert(validate_scope_data['name'].changed+'v:'+validate_scope_data['name'].validated)
    
    
    for(item in validate_scope_data){
	
        if(validate_scope_data[item].changed==true)
            changed=true;
	if(validate_scope_data[item].validated==false)
            errors=true;
    }
    
    if(changed ){
	Dom.get('reset_edit_customer').style.visibility='visible';
	if(!errors)
	    Dom.get('save_edit_customer').style.visibility='visible';
	else
	    Dom.get('save_edit_customer').style.visibility='hidden';

    }else{
        Dom.get('save_edit_customer').style.visibility='hidden';
	Dom.get('reset_edit_customer').style.visibility='hidden';

    }
    
    
    
}


function change_block(e){
    if(this.id=='delivery2')
	id='delivery';
    else
	id=this.id;

     if(editing!=id){
	

	


	Dom.get('d_details').style.display='none';
	Dom.get('d_company').style.display='none';
	Dom.get('d_delivery').style.display='none';

	Dom.get('d_'+id).style.display='';

	//	alert(this.id);
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value='+id ,{});
	
	editing=id;
    }



}


function validate_customer_tax_number(query){
  original_query= query;
query=query.replace(/[^A-Z0-9]/i, "");
 //alert(query)
 validate_general('customer','tax_number',unescape(query));

 if(original_query==''){
    
     validate_scope_data.customer.tax_number.validated=true;
     validate_scope('customer'); 
 }

}


function validate_customer_email(query){

 validate_general('customer','email',unescape(query));
if(query==''){
validate_scope_data.customer.email.validated=true;
	validate_scope('customer'); 
	
Dom.get(validate_scope_data.customer.email.name+'_msg').innerHTML='<?php echo _('This operation will remove the email')?>';
	
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
}
function validate_customer_main_contact_name(query){
 validate_general('customer','contact',unescape(query));
}

function save_edit_billing_data(){
    save_edit_general('billing_data');
}
function reset_edit_billing_data(){
    reset_edit_general('billing_data')
}



function save_edit_customer(){
    save_edit_general('customer');
}
function reset_edit_customer(){
    reset_edit_general('customer')
}


function display_new_delivery_address(){
  
    edit_address(0,'delivery_');
}

function display_edit_billing_address(){
  billing_address_key=this.getAttribute('address_key');
  
    edit_address(billing_address_key,'billing_');
    Dom.get('billing_address').style.display='none';
    Dom.get('show_edit_billing_address').style.display='none';
}

function save_billing_address(e,options){
save_address(e,options);
Dom.get('billing_address').style.display='';
    Dom.get('show_edit_billing_address').style.display='';
}


function reset_billing_address(){
reset_address(false,'billing_');

Dom.get('billing_address').style.display='';
    Dom.get('show_edit_billing_address').style.display='';

}


function init(){
  

  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
    oAutoComp.minQueryLength = 0; 
    var ids = ["details","company","delivery","delivery2"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);

    
    YAHOO.util.Event.addListener('add_new_delivery_address', "click",display_new_delivery_address );
        YAHOO.util.Event.addListener('show_edit_billing_address', "click",display_edit_billing_address );

    
    YAHOO.util.Event.addListener('save_edit_customer', "click", save_edit_customer);
    YAHOO.util.Event.addListener('reset_edit_customer', "click", reset_edit_customer);
    
     YAHOO.util.Event.addListener('save_edit_billing_data', "click", save_edit_billing_data);
    YAHOO.util.Event.addListener('reset_edit_billing_data', "click", reset_edit_billing_data);
    

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


    var customer_telephone_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_telephone);
    customer_telephone_oACDS.queryMatchContains = true;
    var customer_telephone_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Main_Telephone","Customer_Main_Telephone_Container", customer_telephone_oACDS);
    customer_telephone_oAutoComp.minQueryLength = 0; 
    customer_telephone_oAutoComp.queryDelay = 0.1;
    
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

	<?php print sprintf("edit_address(%d,'contact_');",$customer->data['Customer Main Address Key']);?>
	var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	 
	YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,{prefix:'contact_',subject:'Customer',subject_key:customer_id,type:'contact'});
	YAHOO.util.Event.addListener('contact_reset_address_button', "click",reset_address,'contact_');
	
	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("contact_address_country", "contact_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='contact_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
	

	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("delivery_address_country", "delivery_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='delivery_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);

var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("billing_address_country", "billing_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='billing_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);

     var ids = ["delivery_address_description","delivery_address_country_d1","delivery_address_country_d2","delivery_address_town","delivery_address_town_d2","delivery_address_town_d1","delivery_address_postal_code","delivery_address_street","delivery_address_internal","delivery_address_building"]; 
	     YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'delivery_');
	     YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'delivery_');
	 
	 YAHOO.util.Event.addListener('delivery_save_address_button', "click",save_address,{prefix:'delivery_',subject:'Customer',subject_key:customer_id,type:'Delivery'});
	 YAHOO.util.Event.addListener('delivery_reset_address_button', "click",reset_address,'delivery_');


     var ids = ["billing_address_description","billing_address_country_d1","billing_address_country_d2","billing_address_town","billing_address_town_d2","billing_address_town_d1","billing_address_postal_code","billing_address_street","billing_address_internal","billing_address_building"]; 
	     YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'billing_');
	     YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'billing_');


 YAHOO.util.Event.addListener('billing_save_address_button', "click",save_billing_address,{prefix:'billing_',subject:'Customer',subject_key:customer_id,type:'Billing'});
	

	 YAHOO.util.Event.addListener('billing_reset_address_button', "click",reset_billing_address);


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

