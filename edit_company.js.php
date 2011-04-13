<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');




//$edit_block='personal';
//if(isset($_REQUEST['edit'])){
//$valid_edit_blocks=array('personal','work','pictures','others');
//if(in_array($_REQUEST['edit'],$valid_edit_blocks))
//    $edit_block=$_REQUEST['edit'];
//}
$salutation="''";
$sql="select `Salutation` from kbase.`Salutation Dimension` where `Language Code`='en'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $salutation.=',"'.$row['Salutation'].'"';
}
mysql_free_result($result);





if( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ){
    $company_key=$_SESSION['state']['company']['id'];
}else
    $company_key=$_REQUEST['id'];
$scope_key=$company_key;
$scope='company';
if( isset($_REQUEST['scope'])    ){
    $scope=$_REQUEST['scope'];
}
if( isset($_REQUEST['scope_key'])    ){
    $scope=$_REQUEST['scope_key'];
}

print "var company_key=$company_key;";

$company=new Company($company_key);

$contacts=$company->get_contacts();
$contact_data="\n";
$contact_data.=sprintf('0:{"Contact_Key":0,"Contact_Name":"","Name_Data":{"Contact_Salutation":"","Contact_First_Name":"","Contact_Surname":"" ,"Contact_Suffix":""   },"Contact_Gender":"","Contact_Profession":"","Contact_Title":"","Emails":{},"Addresses":[]} ' );
$contact_data.="\n";
foreach($contacts as $contact){
  
  $mobiles=$contact->get_mobiles();
  $number_of_mobiles=count($mobiles);
  $mobile_data='';
  foreach($mobiles as $mobile){
    $scope_related_mobile_type='Work Mobile';
    //if(isset($mobile->data['Telecom Type'][$scope_related_mobile_type])){
	$mobile_data.=sprintf(',%d:{"Mobile_Key":%d,"Mobile":"%s","Country_Code":"%s","National_Access_Code":"%s","Number":%s,"Telecom_Is_Main":"%s","Telecom Type Description":"%s"}'
			      ,$mobile->id
			      ,$mobile->id
			      ,addslashes($mobile->display())
			      ,addslashes($mobile->data['Telecom Country Telephone Code'])
			      ,addslashes($mobile->data['Telecom National Access Code'])
			      ,addslashes($mobile->data['Telecom Number'])
			      ,$mobile->data['Mobile Is Main']
			      ,$mobile->data['Telecom Type']
			      );
      }
  //}
  $mobile_data=preg_replace('/^,/','',$mobile_data);

  $emails=$contact->get_emails();
  $number_of_emails=count($emails);
  $email_data='';
  
 // print_r($emails);
  
  foreach($emails as $email){
    $email_data.=sprintf(',%d:{"Email_Key":%d,"Email":"%s","Email_Contact_Name":"%s","Email_Description":"%s","Email_Is_Main":"%s"}'
			 ,$email->id
			 ,$email->id
			 ,$email->data['Email']
			 ,addslashes($email->data['Email Contact Name'])
			 ,addslashes('')
			 ,$email->data['Email Is Main']
			 );
  }
  $email_data=preg_replace('/^,/','',$email_data);
  $contact_addresses=$contact->get_addresses();
  $contact_address_data='';
  $scope_related_telephone_type='Work Telephone';
  $scope_related_fax_type='Office Fax';
  $scope_related_address_type='Work';

   foreach($contact_addresses as $contact_address){
if(isset($contact_address->data['Address Type'][$scope_related_address_type]) ){


alert("get_telephones no longer exists");// $tels=$contact->get_telephones($contact_address->id);
  $number_of_telephones=count($tels);

    $tels_data='';
    

    foreach($tels as $tel){

      if(isset($tel->data['Telecom Type'][$scope_related_telephone_type])){
	
	$tels_data.=sprintf(',%d:{"Telephone_Key":%d,"Telephone":"%s","Country_Code":"%s","National_Access_Code":"%s","Area_Code":"%s","Number":"%s","Extension":"%s","Telecom_Is_Main":"%s","Telecom Type Description":"%s"}'
			    ,$tel->id
			    ,$tel->id
			    ,addslashes($tel->display())
			    ,addslashes($tel->data['Telecom Country Telephone Code'])
			    ,addslashes($tel->data['Telecom National Access Code'])
			    ,addslashes($tel->data['Telecom Area Code'])
			    ,addslashes($tel->data['Telecom Number'])
			    ,addslashes($tel->data['Telecom Extension'])
			    ,$tel->data['Telecom Is Main'][$scope_related_telephone_type]
			    ,$tel->data['Telecom Type'][$scope_related_telephone_type]
			    );
      }
    }
    $tels_data=preg_replace('/^,/','',$tels_data);
      
    $faxes=$contact->get_faxes($contact_address->id);
    
     
     $faxes_data='';
     $number_of_faxes=count($faxes);
    foreach($faxes as $fax){
    if(isset($fax->data['Telecom Type'][$scope_related_fax_type]) ){
      $faxes_data.=sprintf(',%d:{"Fax_Key":%d,"Fax":"%s","Country_Code":"%s","National_Access_Code":"%s","Area_Code":"%s","Number":"%s","Telecom_Is_Main":"%s","Telecom Type Description":"%s"}'
			  ,$fax->id
			  ,$fax->id
			  ,addslashes($fax->display())
		        ,addslashes($fax->data['Telecom Country Telephone Code'])
			    ,addslashes($fax->data['Telecom National Access Code'])
			    ,addslashes($fax->data['Telecom Area Code'])
			    ,addslashes($fax->data['Telecom Number'])
			    ,$fax->data['Telecom Is Main'][$scope_related_fax_type]
			  ,$fax->data['Telecom Type'][$scope_related_fax_type]
			  );
    }
    }
    $faxes_data=preg_replace('/^,/','',$faxes_data);

    $contact_address_data.=sprintf(',%d:{"Address_Key":%d,"Address":"%s","Address_Mini":"%s","Address_Is_Main":"%s","Telephones":{%s},"Number_Of_Telephones":%d,"Faxes":{%s},"Number_Of_Faxes":%d}'
				   ,$contact_address->id
				   ,$contact_address->id
				   ,addslashes($contact_address->display('xhtml'))
				   ,addslashes($contact_address->display('mini'))
				   ,addslashes($contact_address->data['Address Is Main'][$scope_related_address_type]) 
				   ,$tels_data
                    ,$number_of_telephones
				   ,$faxes_data
				   ,$number_of_faxes
				   );

  }
  $contact_address_data=preg_replace('/^,/','',$contact_address_data);
  $contact_data.=sprintf(',%d:{"Contact_Key":%d,"Contact_Name":"%s" '
			 ,$contact->id
			 ,$contact->id
			 ,$contact->data['Contact Name']
			 );
  $contact_data.="\n";
  
  $contact_data.=sprintf(',"Name_Data":{"Contact_Salutation":"%s","Contact_First_Name":"%s","Contact_Surname":"%s" ,"Contact_Suffix":"%s"} '
			 ,$contact->data['Contact Salutation']
			 ,$contact->data['Contact First Name']
			 ,$contact->data['Contact Surname']
			 ,$contact->data['Contact Suffix']
			 );
  $contact_data.="\n";
  $contact_data.=sprintf(',"Contact_Gender":"%s","Contact_Profession":"%s","Contact_Title":"%s" '
			 ,$contact->data['Contact Gender']
			 ,$contact->data['Contact Profession']
			 ,$contact->data['Contact Title']
			 );
  $contact_data.="\n";
    $contact_data.=sprintf(',"Mobiles":{%s},"Number_Of_Mobiles":%d,"Emails":{%s},"Number_Of_Emails":%d,"Addresses":{%s}} '
			   ,$mobile_data
			   ,$number_of_mobiles
			   ,$email_data
			   ,$number_of_emails
			   ,$contact_address_data
			 );
  $contact_data.="\n";
  
}
}

$addresses=$company->get_addresses(1);
$address_data="\n";
$address_data.=sprintf('0:{"key":0,"country":"","country_code":"UNK","country_d1":"","country_d2":"","town":"","postal_code":"","town_d1":"","town_d2":"","fuzzy":"","street":"","building":"","internal":"","type":["Office"],"description":"","function":["Contact"] } ' );
$address_data.="\n";
foreach($addresses as $index=>$address){
    $address->set_scope($scope,$scope_key);
    
    


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
    


  $address_data.="\n".sprintf(',%d:{"key":%d,"country":%s,"country_code":%s,"country_d1":%s,"country_d2":%s,"town":%s,"postal_code":%s,"town_d1":%s,"town_d2":%s,"fuzzy":%s,"street":%s,"building":%s,"internal":%s,"type":%s,"description":%s,"function":%s} ',
			
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


?>

var Subject='Company';
var Subject_Key=company_key;


var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var Contact_Data={<?php echo$contact_data?>};


//var Address_Data={<?php  $address_data?>};
//var Address_Keys=["key","country","country_code","country_d1","country_d2","town","postal_code","town_d1","town_d2","fuzzy","street","building","internal","description"];
//var Address_Meta_Keys=["type","function"];

var current_salutation='salutation<?php echo$contact->get('Salutation Key')?>';

var old_salutation=current_salutation;
var Current_Address_Index=0;

var changes_details=0;
var changes_address=0;

var saved_details=0;
var error_details=0;



var CountryDS = new YAHOO.widget.DS_JSFunction(function (sQuery) {
	if (!sQuery || sQuery.length == 0) return false;
	var query = sQuery.toLowerCase();
	var aResults = [];
	
	code_match='';
	if(query.length==3){
	    for(var i = 0 ; i < Country_List.length ; i++) {
		var desc = Country_List[i].c.toLowerCase();
		if( query==desc  ) {
		    aResults.push([Country_List[i].n, Country_List[i]]);
		    code_match=Country_List[i].c;
		    break;
		}
	    }
	    

	}


	patt1 = new RegExp("^"+query); 
	
	for(var i = 0 ; i < Country_List.length ; i++) {
	    var desc = Country_List[i].n.toLowerCase();
	    if( desc.match(patt1) ) {
		if(code_match!= Country_List[i].c )
		    aResults.push([Country_List[i].n, Country_List[i]]);
		
	    }
	}
	return aResults;
    });
CountryDS.maxCacheEntries = 100;



var save_details=function(e){
    var items = ["name","fiscal_name","tax_number","registration_number"];
    var table='company';
    save_details=0;
    for ( var i in items )
	{
	    var key=items[i];
	    var value=Dom.get(key).value;
	    var request='ar_edit_contacts.php?tipo=edit_'+table+'&key=' + key + '&newvalue=' + encodeURIComponent(value)+'&id='+company_key; 
	//   alert(request);
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    success:function(o) {
			//alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.action=='updated'){
			    Dom.get(r.key).value=r.newvalue;
			    Dom.get(r.key).setAttribute('ovalue',r.newvalue);
			    save_details++;
			}else if(r.action=='error'){
			    alert(r.msg);
			}
			    
update_details()
			
		    }
		});

	} 
    
}
var cancel_save_details=function(e){
    var items = ["name","fiscal_name","tax_number","registration_number"];
    for ( var i in items )
	{
	    Dom.get(items[i]).value=Dom.get(items[i]).getAttribute('ovalue');
	} 
    
    Dom.get('details_messages').innerHTML='';
    Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', 'none'); 
}

var update_details=function(e){
    var changes=0;
    
    var items = ["name","fiscal_name","tax_number","registration_number"];
    for ( var i in items )
	{
	    if(Dom.get(items[i]).value!=Dom.get(items[i]).getAttribute('ovalue'))
		changes++; 
	} 

    
    if(changes==0){
	Dom.get('details_messages').innerHTML='';
	Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', 'none'); 
    }else if (changes==1){
	Dom.get('details_messages').innerHTML=changes+'<?php echo' '._('change')?>';
	Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', ''); 
    }else{
	Dom.get('details_messages').innerHTML=changes+'<?php echo' '._('changes')?>';
	Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', ''); 
    }


};


    





function initx(){
    // Commented because duplicate save adresss  saving whan try to edit an address
    
    
    //   var ids = ["personal","pictures","work","other"]; 
    //	YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener('save_details_button', "click",save_details );

    YAHOO.util.Event.addListener('cancel_save_details_button', "click",cancel_save_details );
    YAHOO.util.Event.addListener('add_address_button', "click",edit_address,false );
    YAHOO.util.Event.addListener('add_contact_button', "click",edit_contact,false );


    var ids = ["name","fiscal_name","tax_number","registration_number"]; 
    YAHOO.util.Event.addListener(ids, "keyup", update_details);
    
    
    edit_address(1,'contact_');
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
    
  

 

} 
//YAHOO.util.Event.onDOMReady(init);