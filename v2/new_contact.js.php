<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');




$scope='contact';
$action_after_create='continue';
if(isset($_REQUEST['scope']) and preg_match('/supplier|customer|corporation/',$_REQUEST['scope']))
    $scope=$_REQUEST['scope'];
$store_key=0;
if($scope=='customer'){
    $store_key=$_REQUEST['store_key'];
}


print "var scope='$scope';";
print "var store_key='$store_key';\n";

print "var action_after_create='$action_after_create';";

?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var can_add_subject=false;

var subject_data={
    "Customer Main Contact Name":"" 
     ,"Contact Name":"" 
    , "Customer Company Name":"" 
    ,"Customer Type":"Person"
    ,"Contact Main Plain Email":""
    ,"Contact Main Plain Telephone":""
        ,"Contact Main Plain Mobile":""

    ,"Contact Address Line 1":""
    ,"Contact Address Line 2":""
    ,"Contact Address Line 3":""
    ,"Contact Address Town":""
    ,"Contact Address Postal Code":""
    ,"Contact Address Country Name":""
    ,"Contact Address Country Code":""
    ,"Contact Address Town Second Division":""
    ,"Contact Address Town First Division":""
    ,"Contact Address Country First Division":""
    ,"Contact Address Country Second Division":""
    ,"Contact Address Country Third Division":""
    ,"Contact Address Country Forth Division":""
    ,"Contact Address Country Fifth Division":""
};  
var suggest_country=true;
var suggest_d1=true;
var suggest_d2=true;
var suggest_d3=false;
var suggest_d4=false;
var suggest_d4=false;
var suggest_town=false;

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
		   ,'contact_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item'}
};

var Subject='Contact';
var Subject_Key=0;
var Current_Address_Index=0;
var changes_details=0;
var changes_address=0;
var saved_details=0;
var error_details=0;
var values=new Object;



var save_new_contact=function(e){
 
  //  if(!can_add_subject){
	
//	return;
  //  }
	  
	  Dom.setStyle("creating_message",'display','');
	  Dom.setStyle(["save_new_Contact","cancel_add_Contact"],'display','none');


    get_data();

   
    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
  //alert(json_value);
    if(scope=='supplier'){
        var ar_file='ar_edit_suppliers.php';
    }else
        var ar_file='ar_edit_contacts.php';
    
   
   var request=ar_file+'?tipo=new_'+scope+'&delete_email='+subject_found_email+'&values=' + encodeURIComponent(json_value); 

   // alert(request);
   // var request='ar_edit_contacts.php?tipo=new_'+scope+'&values=' + encodeURIComponent(json_value); 
   
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		
		if(scope=='customer'){
		           window.location='customer.php?id='+r.customer_key;

		        }
		        
		        else{
		        window.location='company.php?id='+r.company_key;
		        }
		
		
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}
    var find_contact=function(){
   

    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
	    
    var request='ar_contacts.php?tipo=find_contact&values=' + encodeURIComponent(json_value); 
  alert(request) ;

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
			alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var old_subject_found=subject_found;
		var old_subject_found_email=subject_found_email;

		if(r.action=='found_email'){
		    subject_found=true;
		    subject_found_email=true;
		    subject_found_key=r.found_key;
		    display_form_state();
		    //alert(subject_found+' '+subject_found_email);
		     update_save_button();
		}else if(r.action=='found'){
		    subject_found=true;
		    subject_found_email=false;
		    subject_found_key=r.found_key;
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
		    
		    Dom.get("results").innerHTML+='<div style="width:100%;xborder:1px solid red;"><div style="xborder:1px solid blue;width:200px;margin:0px 0px 10px 0;float:left;margin-left:100px" class="subject_display">'+r.candidates_data[x]['card']+'</div> <div style="xborder:1px solid green;margin-left:350px;;margin-top:5px"><div id="score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']+'" >'+r.candidates_data[x]['score']+'</div> <span onclick="pick_it('+r.candidates_data[x]['key']+')"  class="state_details" style="margin:10px 0;float:left"><?php echo _('Choose This')?></span><div style="clear:both"></div><div style="clear:both"> </div>';
		    
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
      
    function get_subject_data(){
    subject_data[Subject+' Name']=Dom.get('Contact_Name').value;
    
}
   

function cancel_new_contact(){

if(scope=='customer')
window.location='customers.php'

else
window.location='contacts.php?edit=1';
}

function get_contact_data(){
    subject_data['Contact Name']=Dom.get('Contact_Name').value;

    subject_data['Customer Main Contact Name']=Dom.get('Contact_Name').value;
	subject_data[Subject+' Main Plain Telephone']=Dom.get('Telephone').value;
		subject_data[Subject+' Main Plain Mobile']=Dom.get('Telephone').value;

subject_data[Subject+' Main Plain Email']=Dom.get('Email').value;

}
function init(){
    
      	YAHOO.util.Event.addListener(['save_new_'+Subject,'save_when_founded','force_new'], "click",save_new_contact);
      	YAHOO.util.Event.addListener(['cancel_add_'+Subject], "click",cancel_new_contact);

 

    } 
YAHOO.util.Event.onDOMReady(init);
