<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');

$scope='company';
$action_after_create='continue';
if(isset($_REQUEST['scope']) and preg_match('/supplier|customer|corporation/',$_REQUEST['scope']))
    $scope=$_REQUEST['scope'];
$store_key=0;
if($scope=='customer'){
    $store_key=$_REQUEST['store_key'];
}
if($scope!='corporation')    
$action_after_create=$_SESSION['state'][$scope]['action_after_create'];
print "var scope='$scope';\n";
print "var store_key='$store_key';\n";
print "var action_after_create='$action_after_create';\n";
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var can_add_subject=false;

<?php if($scope=='company'){?>
var subject_data={
    "Company Name":""
    ,"Company Main Contact Name":""
    ,"Company Main Plain Email":""
    ,"Company Main Plain Telephone":""
    ,"Company Address Line 1":""
    ,"Company Address Line 2":""
    ,"Company Address Line 3":""
    ,"Company Address Town":""
    ,"Company Address Postal Code":""
    ,"Company Address Country Name":""
    ,"Company Address Country Code":""
    ,"Company Address Town Second Division":""
    ,"Company Address Town First Division":""
    ,"Company Address Country First Division":""
    ,"Company Address Country Second Division":""
    ,"Company Address Country Third Division":""
    ,"Company Address Country Forth Division":""
    ,"Company Address Country Fifth Division":""
    
};  
<?php }?>
var suggest_country=true;
var suggest_d1=true;
var suggest_d2=true;
var suggest_d3=false;
var suggest_d4=false;
var suggest_d4=false;
var suggest_town=false;
var contact_with_same_email=0;
var subject_found_email=false;
var subject_found=false;
var subject_found_key=0;
var validate_data={'postal_code':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address'}
		   ,'town':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z]+"}
		   ,'street':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z\\d]+"}
		   ,'building':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z\\d]+"}
		   ,'internal':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z\\d]+"}

		   ,'country':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address'}
		   ,'address':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item'}
		   ,'email':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item'}
		   ,'telephone':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item','regexp':"\\d{4,}"}
		   ,'company_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':true,'group':0,'type':'item'}
		   ,'contact_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item'}
};
var Subject='Company';
var Subject_Key=0;
var Current_Address_Index=0;
var changes_details=0;
var changes_address=0;
var saved_details=0;
var error_details=0;
var values=new Object;



function update_category(o){
var parent_category_key=o.getAttribute('cat_key');
var category_key=o.options[o.selectedIndex].value;

subject_data['Cat'+parent_category_key]=category_key;


}


function save_new_company(e){
   
    if(!can_add_subject){

	return;
    }

 Dom.setStyle("creating_message",'display','');
	  Dom.setStyle(["save_new_Company","cancel_add_Company"],'display','none');

    get_data();

    if(scope=='supplier'){
        var ar_file='ar_edit_suppliers.php';
    }else
        var ar_file='ar_edit_contacts.php';
   
  // for (x in subject_data){
//alert(x+' '+subject_data[x])
//}


   
    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
    var request=ar_file+'?tipo=new_'+scope+'&delete_email='+subject_found_email+'&values=' + encodeURIComponent(json_value); 
  //  alert(request);return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		//return;
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    if(action_after_create=='add_another'){


		    }else{
		        if(scope=='corporation'){
		           window.location='edit_company_areas.php?edit=new_company_areas';

		        }else if(scope=='supplier'){
		           window.location='edit_supplier.php?id='+r.supplier_key;

		        }else if(scope=='customer'){
		           window.location='customer.php?id='+r.customer_key;

		        }
		        
		        else{
		        window.location='company.php?id='+r.company_key;
		        }
		    }
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}
function validate_company_name (query) {
    
    var validator=new RegExp(validate_data.company_name.regexp,"i");

    if(validator.test(query)){
	validate_data.company_name.validated=true;
    }else{
	validate_data.company_name.validated=false;
    }
    
    get_subject_data();
    find_subject();
    validate_form();

};
function name_inputed(){
    var item='company_name';
    var value=Dom.get('Company_Name').value.replace(/\s+/,"");
    //  alert(value)
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;

    display_form_state();
    
    //validate_postal_code(postal_code);
    
}    
function cancel_new_company(){
if(scope=='customer')
window.location='customers.php'

else
window.location='companies.php?edit=1';
}
function get_subject_data(){
    subject_data[Subject+' Name']=Dom.get('Company_Name').value;
    
}
function get_contact_data(){
    subject_data[Subject+' Main Contact Name']=Dom.get('Contact_Name').value;
	subject_data[Subject+' Main Plain Telephone']=Dom.get('Telephone').value;
subject_data[Subject+' Main Plain Email']=Dom.get('Email').value;

}
function init(){
    
	YAHOO.util.Event.addListener(['save_new_'+Subject,'save_when_founded','force_new'], "click",save_new_company);
      	YAHOO.util.Event.addListener(['cancel_add_'+Subject], "click",cancel_new_company);
	YAHOO.util.Event.addListener('Company_Name', "blur",name_inputed);

	var company_name_oACDS = new YAHOO.util.FunctionDataSource(validate_company_name);
	company_name_oACDS.queryMatchContains = true;
	var company_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Name","Company_Name_Container", company_name_oACDS);
	company_name_oAutoComp.minQueryLength = 0; 
	company_name_oAutoComp.queryDelay = 0.75;
    } 
YAHOO.util.Event.onDOMReady(init);
