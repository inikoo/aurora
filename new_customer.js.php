<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');

$scope='customer';
$action_after_create='continue';

$store_key=$_REQUEST['store_key'];

print "var scope='$scope';\n";
print "var store_key='$store_key';\n";
print "var action_after_create='$action_after_create';\n";

$custom_values_data =  array();
$sql = sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer' and `Custom Field In New Subject`='Yes'");
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$custom_values_data[] = array('field_name'=>$row['Custom Field Name'], 'default'=>$row['Default Value']);
}

//print_r ($custom_values_data);	
//$custom_values_data=array( array('field_name'=>'mobile4323', 'default'=>''));
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var can_add_subject=false;

var subject_data={
    "Customer Name":""
    ,"Customer Main Contact Name":""
    ,"Customer Tax Number":""
    ,"Customer Registration Number":""
    ,"Customer Main Plain Email":""
    ,"Customer Main Plain Telephone":""
    ,"Customer Main Plain FAX":""
    ,"Customer Main Plain Mobile":""
    ,"Customer Address Line 1":""
    ,"Customer Address Line 2":""
    ,"Customer Address Line 3":""
    ,"Customer Address Town":""
    ,"Customer Address Postal Code":""
    ,"Customer Address Country Name":""
    ,"Customer Address Country Code":""
    ,"Customer Address Town Second Division":""
    ,"Customer Address Town First Division":""
    ,"Customer Address Country First Division":""
    ,"Customer Address Country Second Division":""
    ,"Customer Address Country Third Division":""
    ,"Customer Address Country Forth Division":""
    ,"Customer Address Country Fifth Division":""
	<?php
	foreach($custom_values_data as $data_x)
		echo ",\"".$data_x['field_name']."\":\"".$data_x['default']."\"";
	?>
    
};  
var suggest_country=true;
var suggest_d1=true;
var suggest_d2=true;
var suggest_d3=true;
var suggest_d4=false;
var suggest_d4=false;
var suggest_town=true;
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
		   ,'telephone':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item','regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$"}
		   ,'company_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':true,'group':0,'type':'item'}
		   ,'contact_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item'}
};
var Subject='Customer';
var Subject_Key=0;
var Current_Address_Index=0;
var changes_details=0;
var changes_address=0;
var saved_details=0;
var error_details=0;
var values=new Object;

subject_found_email_other_store=false;

function update_category(o){
    var parent_category_key=o.getAttribute('cat_key');
    var category_key=o.options[o.selectedIndex].value;
    subject_data['Cat'+parent_category_key]=category_key;
}

function get_custom_data(){
<?php
	foreach($custom_values_data as $dom_data){
?>
	subject_data['<?php echo $dom_data['field_name']?>']=Dom.get('<?php echo $dom_data['field_name']?>').value;
<?php
	}
?>
}

function save_new_customer(e){
   
    if(!can_add_subject){
	return;
    }

 Dom.setStyle("creating_message",'display','');
Dom.setStyle(["save_new_Customer","cancel_add_Customer"],'display','none');

    get_data();

 
        var ar_file='ar_edit_contacts.php';
   
  


   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(subject_data));
   //alert(json_value);
    //var json_value = YAHOO.lang.JSON.stringify(subject_data); 
    var request=ar_file+'?tipo=new_'+scope+'&delete_email='+subject_found_email+'&values=' + json_value; 
	//alert(request);
  //alert(request);return;

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    if(action_after_create=='add_another'){


		    }else{
		       	window.location='customer.php?r=nc&id='+r.customer_key;

		    }
		    
		}else{
		    alert(r.msg);
		}
			    

			
	    }
	});

}


function customer_is_a_person(){
Dom.get('Customer_Type').value='Person'
Dom.get('Company_Tax_Number').value='';
Dom.get('Company_Registration_Number').value='';
validate_data.company_name.validated=true;
Dom.setStyle('company_section','display','none');
Dom.setStyle('set_as_company','display','');

subject_data['Customer Tax Number']=Dom.get('Company_Tax_Number').value;
subject_data['Company_Registration_Number']=Dom.get('Company_Registration_Number').value;

validate_form();
}
function customer_is_a_company(){
Dom.get('Customer_Type').value='Company'
Dom.setStyle('company_section','display','');
Dom.setStyle('set_as_company','display','none');

validate_company_name(Dom.get('Company_Name').value);
validate_form();
}

function validate_company_name (query) {



if(Dom.get('Customer_Type').value=='Person'){

validate_data.company_name.validated=true;

}else{


    var validator=new RegExp(validate_data.company_name.regexp,"i");

    if(validator.test(query)){
	validate_data.company_name.validated=true;
    }else{
	validate_data.company_name.validated=false;
    }
 }   
    get_subject_data();
    find_subject();
    validate_form();

};
function name_inputed_to_be_deleted(){

    var item='company_name';
    var value=Dom.get('Company_Name').value.replace(/\s+/,"");
   //   alert(value)
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
        subject_data[Subject+' Tax Number']=Dom.get('Company_Tax_Number').value;
        subject_data[Subject+' Registration Number']=Dom.get('Company_Registration_Number').value;

}
function get_contact_data(){
    subject_data[Subject+' Main Contact Name']=Dom.get('Contact_Name').value;
	subject_data[Subject+' Main Plain Telephone']=Dom.get('Telephone').value;
	subject_data[Subject+' Main Plain FAX']=Dom.get('FAX').value;
	subject_data[Subject+' Main Plain Mobile']=Dom.get('Mobile').value;
subject_data[Subject+' Main Plain Email']=Dom.get('Email').value;

}


function update_save_button(){
	//	alert(subject_found);
	validate_form();
	
	//Dom.get('email_found_key')
	
	if(subject_found==true && valid_form){

	    Dom.get('save_new_'+Subject).style.display='none';
	    
	    if(subject_found_email_other_store==true){
		
		Dom.get('email_found_dialog').style.display='none';
		Dom.get(Subject+'_found_dialog').style.display='none';
        Dom.get('email_found_other_store_dialog').style.display='';
        
	    }
	    else if(subject_found_email==true){
		Dom.get('email_found_dialog').style.display='';
		Dom.get(Subject+'_found_dialog').style.display='none';
         Dom.get('email_found_other_store_dialog').style.display='none';
	    }else{
		Dom.get(Subject+'_found_dialog').style.display='';
		Dom.get('email_found_dialog').style.display='none';
         Dom.get('email_found_other_store_dialog').style.display='none';
	    }
	    
	}else{
	
	    Dom.get('save_new_'+Subject).style.display='';
	    Dom.get(Subject+'_found_dialog').style.display='none';
	    Dom.get('email_found_dialog').style.display='none';
	     Dom.get('email_found_other_store_dialog').style.display='none';

	}
	
    }

function clone_founded(){
clone_customer(Dom.get('found_email_other_store_customer_key').value);
}


function clone_customer(customer_id){

Dom.setStyle("creating_message",'display','');
	  Dom.setStyle(["save_new_Customer","cancel_add_Customer","email_found_other_store_dialog"],'display','none');

var json_value_scope = YAHOO.lang.JSON.stringify({scope:scope,store_key:store_key}); 
  var request='ar_edit_contacts.php?tipo=clone_customer&customer_key=' + customer_id+'&scope=' + my_encodeURIComponent(json_value_scope); 
  
  
       YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		           window.location='customer.php?r=nc&id='+r.customer_key;
		    
		}else{
		    alert(r.msg);
		}
			    

			
	    }
	});
 

}

function find_subject(){
    get_data();

    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
var json_value_scope = YAHOO.lang.JSON.stringify({scope:scope,store_key:store_key}); 


    var request='ar_contacts.php?tipo=show_posible_customer_matches&values=' + my_encodeURIComponent(json_value)+'&scope=' + my_encodeURIComponent(json_value_scope); 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var old_subject_found=subject_found;
		var old_subject_found_email=subject_found_email;
Dom.get('found_email_other_store_customer_key').value=0;
		if(r.action=='found_email'){
		
	
		
		    subject_found=true;
		    subject_found_email=true;
		    subject_found_key=r.found_key;
		    display_form_state();
		    contact_with_same_email=r.found_key;
		    
		    		    Dom.get('email_founded_name').innerHTML=r.found_name;

		    //alert(subject_found+' '+subject_found_email);
		     update_save_button();
		}else if(r.action=='found_email_other_store'){
		
	subject_found_email_other_store=true;
		
		    subject_found=true;
		    subject_found_key=r.found_key;
		    display_form_state();
		    contact_with_same_email=r.found_key;
		    Dom.get('found_email_other_store_customer_key').value=r.found_key;
		    Dom.get('email_founded_name').innerHTML=r.found_name;

		    //alert(subject_found+' '+subject_found_email);
		     update_save_button();
		}else if(r.action=='found'){
		    subject_found=true;
		    subject_found_email=false;
		    subject_found_key=r.found_key;
		    subject_found_name=r.found_name;
		    Dom.get('founded_name').innerHTML=r.found_name;
		    display_form_state();
		     update_save_button();
		}else if(r.action=='found_candidates'){
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0;
		     display_form_state();
		      update_save_button();
		}else{
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0; 
		       display_form_state();
		        update_save_button();
		        
		}
		//if(old_subject_found!=subject_found || old_subject_found_email!=subject_found_email){
		//    update_save_button();
		//	}
		//var old_subject_found=subject_found;
		//var old_subject_found_email=subject_found_email;


		Dom.get("results").innerHTML='';
		var count=0;
		
		for(x in r.candidates_data){
		    
		    Dom.get("results").innerHTML+='<div style="width:100%;"><div style="width:270px;margin:0px 0px 10px 0;float:left;margin-left:10px" class="contact_display">'+r.candidates_data[x]['card']+'</div> <div style="xborder:1px solid green;margin-left:300px;;margin-top:5px"><div id="score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']+'" >'+r.candidates_data[x]['score']+'</div><div style="font-size:80%">'+r.candidates_data[x]['link']+'</div>  <div style="clear:both"></div><div style="clear:both"> </div>';
		    
		    var found_img='';
		    // alert(r.candidates_data[x]['found']);return;
		    if(r.candidates_data[x]['found']==1)
			found_img='<img src="art/icons/award_star_gold_1.png"/>';
		    
		    Dom.get('score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']).innerHTML=star_rating(r.candidates_data[x]['score'],200).innerHTML+found_img+'<span style="font-size:80%;margin-left:0px"> Score ('+Math.round(r.candidates_data[x]['score'])+')</span>';
		    
		    
		    
		    //	    if(count % 2 || count==0)
		    //	Dom.get("results").innerHTML+='<tr>'+td;
		    //else
		    //	Dom.get("results").innerHTML+=td+'</tr>';
		}
		//	Dom.get("results").innerHTML+='</table>';
		
	    }
	});

}

function change_allow(o,key,value){

Dom.get(key).value=value;
Dom.removeClass(Dom.getElementsByClassName('option', 'span', o.parentNode ),'selected');
Dom.addClass(o,'selected');


}

function init(){
    
	YAHOO.util.Event.addListener(['save_new_'+Subject,'save_when_founded','force_new'], "click",save_new_customer);
      	YAHOO.util.Event.addListener(['cancel_add_'+Subject], "click",cancel_new_company);
	//YAHOO.util.Event.addListener('Company_Name', "blur",name_inputed);

	var company_name_oACDS = new YAHOO.util.FunctionDataSource(validate_company_name);
	company_name_oACDS.queryMatchContains = true;
	var company_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Name","Company_Name_Container", company_name_oACDS);
	company_name_oAutoComp.minQueryLength = 0; 
	company_name_oAutoComp.queryDelay = 0.75;
    } 
YAHOO.util.Event.onDOMReady(init);
