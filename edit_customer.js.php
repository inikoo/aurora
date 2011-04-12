<?php

    include_once('common.php');
    include_once('class.Customer.php');

    if (!isset($_REQUEST['id'])) {
    exit;
}

$customer=new Customer($_REQUEST['id']);
    
print "var customer_id='".$_REQUEST['id']."';";


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
?>
var send_post_status='<?php echo $send_post_status;?>';
var send_post_type='<?php echo $send_post_type;?>';
var Dom   = YAHOO.util.Dom;
var editing='<?php echo $_SESSION['state']['customer']['edit']?>';




var validate_scope_data=
{
    'customer':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	,'email':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Email','validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Telephone','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$",'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}

	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_FAX','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Fax')?>'}]}

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
   var ids = ["details","company","delivery","categories","communications"]; 
    var block_ids = ["d_details","d_company","d_delivery","d_categories","d_communications"]; 

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value='+this.id ,{});
	
   



}

function change_to_delivery_block(){
 var ids = ["details","company","delivery","categories","communications"]; 
    var block_ids = ["d_details","d_company","d_delivery","d_categories","d_communications"]; 


Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_delivery','display','');
Dom.removeClass(ids,'selected');
Dom.addClass('delivery','selected');

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value=delivery' ,{});

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
var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {

				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
			 
            if (r.newvalue=='No' || r.newvalue=='Yes') {
                           Dom.removeClass([r.key+'_No',r.key+'_Yes'],'selected');

               Dom.addClass(r.key+'_'+r.newvalue,'selected');

            }else{
                alert(r.msg)
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
			alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				}


        
    }
                                                                 });



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


function validate_new_company_name(query){

  var validator=new RegExp('/[a-z0-9]/',"i");
    if (!validator.test(query)) {
        Dom.removeClass('save_convert_to_company','disabled')

    } else {
   Dom.addClass('save_convert_to_company','disabled')
   
    }
    
}

function post_change_main_delivery_address(){}

function init(){
Dom.addClass('Send Post Status'+'_'+send_post_status,'selected');
Dom.addClass('Post Type'+'_'+send_post_type,'selected');
  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
    oAutoComp.minQueryLength = 0; 
    var ids = ["details","company","delivery","categories","communications"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener( "delivery2",  "click",change_to_delivery_block);
    
    
    Event.addListener("back_to_take_order", "click", back_to_take_order , true);
    
        YAHOO.util.Event.addListener('show_edit_billing_address', "click",display_edit_billing_address );

    
    YAHOO.util.Event.addListener('save_edit_customer', "click", save_edit_customer);
    YAHOO.util.Event.addListener('reset_edit_customer', "click", reset_edit_customer);
    
     YAHOO.util.Event.addListener('save_edit_billing_data', "click", save_edit_billing_data);
    YAHOO.util.Event.addListener('reset_edit_billing_data', "click", reset_edit_billing_data);
    
    YAHOO.util.Event.addListener('convert_to_company', "click", convert_to_company);
    YAHOO.util.Event.addListener('cancel_convert_to_company', "click", cancel_convert_to_company);
    YAHOO.util.Event.addListener('save_convert_to_company', "click", save_convert_to_company);


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

