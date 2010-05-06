<?php
    include_once('common.php');
        include_once('class.Customer.php');

    $customer=new Customer($_SESSION['state']['customer']['id']);
    
print "var customer_id='".$_SESSION['state']['customer']['id']."';";
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
    }
};

//"[ext\d\(\)\[\]\-\s]+"
var validate_scope_metadata={'customer':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':<?php echo $_SESSION['state']['customer']['id']?>}};

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
     if(editing!=this.id){
	

	


	Dom.get('d_details').style.display='none';
	Dom.get('d_company').style.display='none';
	Dom.get('d_delivery').style.display='none';

	Dom.get('d_'+this.id).style.display='';

	//	alert(this.id);
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value='+this.id ,{});
	
	editing=this.id;
    }



}





function validate_customer_email(query){
 validate_general('customer','email',unescape(query));
}
function validate_customer_name(query){
 validate_general('customer','name',unescape(query));
}
function validate_customer_telephone(query){
 validate_general('customer','telephone',unescape(query));
}
function validate_customer_main_contact_name(query){
 validate_general('customer','contact',unescape(query));
}




function save_edit_customer(){
    save_edit_general('customer');
}
function reset_edit_customer(){
    reset_edit_general('customer')
}


function init(){
  

  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
    oAutoComp.minQueryLength = 0; 
    var ids = ["details","company","delivery"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    
    YAHOO.util.Event.addListener('save_edit_customer', "click", save_edit_customer);
    YAHOO.util.Event.addListener('reset_edit_customer', "click", reset_edit_customer);

    var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_name);
    customer_name_oACDS.queryMatchContains = true;
    var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Name","Customer_Name_Container", customer_name_oACDS);
    customer_name_oAutoComp.minQueryLength = 0; 
    customer_name_oAutoComp.queryDelay = 0.1;

    
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




	<?php
	      print sprintf("edit_address(%d,'contact_');",$customer->data['Customer Main Address Key']);
	if($customer->data['Customer Main Address Key']!=$customer->data['Customer Billing Address Key']){
	    
	    print sprintf("alert('caca');edit_address(%d,'billing_');",$customer->data['Customer Billing Address Key']);

	}{


	}
	    ?>

	     var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	     YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	     YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	 
	 YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,'contact_');
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
	








}

YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("filtermenu", function () {
	var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("rppmenu", function () {
	var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });

