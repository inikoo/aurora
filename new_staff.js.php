<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');


$salutation="''";
$sql="select `Salutation` from kbase.`Salutation Dimension` where `Language Code`='en'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $salutation.=',"'.$row['Salutation'].'"';
}
mysql_free_result($result);
$sql="select `Country Key`,`Country Name`,`Country Code`,`Country 2 Alpha Code`,`Country Postal Code Regex` from kbase.`Country Dimension`";
$result=mysql_query($sql);
$country_list='';

while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $country_list.=',{"id":"'.$row['Country Key'].'","name":"'.$row['Country Name'].'","code":"'.$row['Country Code'].'","code2a":"'.$row['Country 2 Alpha Code'].'","postal_regex":"'.addslashes($row['Country Postal Code Regex']).'"}  ';
}
mysql_free_result($result);
$country_list=preg_replace('/^\,/','',$country_list);

$scope='contact';

if(isset($_REQUEST['scope']) and preg_match('/supplier|customer/',$_REQUEST['scope']))
    $scope=$_REQUEST['scope'];
$action_after_create=$_SESSION['state'][$scope]['action_after_create'];


print "var scope='$scope';";
print "var action_after_create='$action_after_create';";

?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var can_add_contact=false;
var postal_regex=new RegExp('.?');

var contact_data={
    "Contact Name":"" 
    ,"Contact Main Plain Email":""
    ,"Contact Main XHTML Telephone":""
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

var contact_found_email=false;

var contact_found=false;
var contact_found_key=0;

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





var Country_List=[<?php echo$country_list?>];

var Address_Keys=["key","country","country_code","country_d1","country_d2","town","postal_code","town_d1","town_d2","fuzzy","street","building","internal","description"];
var Address_Meta_Keys=["type","function"];


var Current_Address_Index=0;

var changes_details=0;
var changes_address=0;

var saved_details=0;
var error_details=0;
var values=new Object;


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




function print_data(){
    var data='';
    for(x in contact_data)
	data+=" "+x+": "+contact_data[x]+"<br/>";
    Dom.get("results").innerHTML=data;
}



function get_contact_data(){
        contact_data['Contact Name']=Dom.get('Contact_Name').value;
	contact_data['Contact Main XHTML Telephone']=Dom.get('Telephone').value;
	contact_data['Contact Mobile']=Dom.get('Mobile').value;


	if(validate_data.email.validated==true)
	    contact_data['Contact Main Plain Email']=Dom.get('Email').value;
	else
	    contact_data['Contact Main Plain Email']="";
}

function get_address_data(){

    contact_data['Contact Address Line 1']=Dom.get('address_internal').value;
    contact_data['Contact Address Line 2']=Dom.get('address_building').value;

    contact_data['Contact Address Line 3']=Dom.get('address_street').value;
    contact_data['Contact Address Town']=Dom.get('address_town').value;
      
    contact_data['Contact Address Town Second Division']=Dom.get('address_town_d2').value;
    contact_data['Contact Address Town First Division']=Dom.get('address_town_d1').value;
    contact_data['Contact Address Postal Code']=Dom.get('address_postal_code').value;
       
    contact_data['Contact Address Country Code']=Dom.get('address_country_code').value;
    contact_data['Contact Address Country First Division']=Dom.get('address_country_d1').value;
    contact_data['Contact Address Country Second Division']=Dom.get('address_country_d2').value;
    contact_data['Contact Address Country Third Division']=Dom.get('address_country_d3').value;
    contact_data['Contact Address Country Forth Division']=Dom.get('address_country_d4').value;    
    contact_data['Contact Address Country Fifth Division']=Dom.get('address_country_d5').value;
}

function pick_it(key){
    get_data();
    var json_value = YAHOO.lang.JSON.stringify(contact_data); 
    
    window.location='edit_'+scope+'.php?id='+key+'&data=';

}

function get_data(){
    get_contact_data();
    get_address_data();
}


var save_new_contact=function(e){
    if(!can_add_contact){
	return;
    }

    get_data();

 scope='staff';   
    var json_value = YAHOO.lang.JSON.stringify(contact_data); 
  //  var request='ar_edit_contacts.php?tipo=new_'+scope+'&subject=Staff&subject_key='+'&values=' + encodeURIComponent(json_value); 
var request='ar_edit_contacts.php?tipo=new_staff'+'&values=' + encodeURIComponent(json_value); 
    alert(request);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    if(action_after_create=='add_another'){


		    }else{
		       // window.location=scope+'.php?id='+r.contact_key;
			window.location=scope+'.php?id='+r.staff_key;
		    }
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}
    

var find_contact=function(){
   

    var json_value = YAHOO.lang.JSON.stringify(contact_data); 
	    
    var request='ar_contacts.php?tipo=find_contact&values=' + encodeURIComponent(json_value); 
  // alert(request) ;

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var old_contact_found=contact_found;
		var old_contact_found_email=contact_found_email;

		if(r.action=='found_email'){
		    contact_found=true;
		    contact_found_email=true;
		    contact_found_key=r.found_key;
		    display_form_state();
		    //alert(contact_found+' '+contact_found_email);
		     update_save_button();
		}else if(r.action=='found'){
		    contact_found=true;
		    contact_found_email=false;
		    contact_found_key=r.found_key;
		    display_form_state();
		     update_save_button();
		}else if(r.action=='found_candidates'){
		    contact_found=false; contact_found_email=false;
		    contact_found_key=0;
		     display_form_state();
		      update_save_button();
		}else{
		    contact_found=false; contact_found_email=false;
		    contact_found_key=0; 
		       display_form_state();
		        update_save_button();
		}
		//if(old_contact_found!=contact_found || old_contact_found_email!=contact_found_email){
		//    update_save_button();
		//	}
		//var old_contact_found=contact_found;
		//var old_contact_found_email=contact_found_email;


		Dom.get("results").innerHTML='';
		var count=0;
		
		for(x in r.candidates_data){
		    
		    Dom.get("results").innerHTML+='<div style="width:100%;xborder:1px solid red;"><div style="xborder:1px solid blue;width:200px;margin:0px 0px 10px 0;float:left;margin-left:100px" class="contact_display">'+r.candidates_data[x]['card']+'</div> <div style="xborder:1px solid green;margin-left:350px;;margin-top:5px"><div id="score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']+'" >'+r.candidates_data[x]['score']+'</div> <span onclick="pick_it('+r.candidates_data[x]['key']+')"  class="state_details" style="margin:10px 0;float:left"><?php echo _('Choose This')?></span><div style="clear:both"></div><div style="clear:both"> </div>';
		    
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
     

    function update_save_button(){
		//alert(contact_found);
	if(contact_found==true){

	    Dom.get('save_new_contact').style.display='none';
	    
	    if(contact_found_email==true){
		Dom.get('email_found_dialog').style.display='';
		Dom.get('contact_found_dialog').style.display='none';

	    }else{
		Dom.get('contact_found_dialog').style.display='';
		Dom.get('email_found_dialog').style.display='none';

	    }
	    
	}else{
	    Dom.get('save_new_contact').style.display='';
	    Dom.get('contact_found_dialog').style.display='none';
	    Dom.get('email_found_dialog').style.display='none';

	}
	
    }


    function validate_form(){

	display_form_state();
      
	var valid_form=true;
	for (item in validate_data ){
	    	                    // alert(item+' '+validate_data[item].required+' '+validate_data[item].validated)
	    if(validate_data[item].required==true && validate_data[item].validated==false){
		valid_form=false;
		
	    }
	    if(validate_data[item].inputed==true && validate_data[item].validated==false){
		valid_form=false;
	    }
	}

	var validate_group_id=1;
	var min_valid_items=1;
	var valid_items_in_group=0;
	for (item in validate_data ){
	   
	    if(validate_data[item].group==validate_group_id){
		
		if( validate_data[item].validated==true && validate_data[item].inputed==true ){
		    
		    valid_items_in_group++;
		}
	    }

	}
	                       //  alert(validate_data.email.validated+' '+validate_data.email.inputed)
	if(valid_items_in_group<min_valid_items){
	    //valid_form=false;
	}

	
	if(valid_form==true){
	    
	    Dom.removeClass('save_new_contact','disabled');
	    can_add_contact=true;
	}else{
	    can_add_contact=false;
	    Dom.addClass('save_new_contact','disabled');
	}
	
    }

function display_form_state(){
    
    return;
    
    if(contact_found==true){
	Dom.get('mark_contact_found').style.display='';
    }else{
	Dom.get('mark_contact_found').style.display='none';

    }
    
    for (i in validate_data){
	// alert(i+'_valid')
	if(validate_data[i].validated==true)
	    Dom.get(i+'_valid').innerHTML="<img src='art/icons/accept.png'>";
	else{
	    
	    //Dom.get(i+'_valid').innerHTML="<img src='art/icons/cross.png'>";
	}

	if(validate_data[i].inputed==true){
	    
	    Dom.get(i+'_inputed').innerHTML="<img src='art/icons/accept.png'>";
	  


	}else{
	    //Dom.get(i+'_inputed').innerHTML="";
	}
	

    }
}







function  validate_contact_name(query) {
    item='contact_name';
    var validator=new RegExp(validate_data[item].regexp,"i");
    if(validator.test(query)){
	validate_data[item].validated=true;
    }else{
	validate_data[item].validated=false;
    }


    get_contact_data();
    find_contact();
    validate_form();
};
function contact_name_inputed(){

    var item='contact_name';
    var value=Dom.get('Contact_Name').value.replace(/\s+/,"");
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;
    display_form_state();
}    



function  validate_telephone(original_query) {
    var tr=Dom.get('telephone_mould');
    var o=Dom.get('Telephone');
    value=original_query.replace(/[^\d]/g,"");
    //  alert(query)
    var item='telephone';

    if(original_query==''){
	validate_data[item].inputed=false;
	validate_data[item].validated=true;
	Dom.removeClass(tr,'no_validated');
	return;
    }
    


    var validator=new RegExp(validate_data[item].regexp,"i");
    
    if(validate_data[item].inputed==true){
	if(validator.test(value)){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	    validate_data[item].validated=false;
	}
    }else{
	if(validator.test(value) ){
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    validate_data[item].validated=false;

	}
	

    }
    get_contact_data();
    find_contact();
    validate_form();


};
function telephone_inputed(){
    var item='telephone';
    var value=Dom.get('Telephone').value.replace(/\s+/,"");
   // alert(value)
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;

    display_form_state();
    
    //validate_postal_code(postal_code);
    
}    



function  address_changed(query) {
  
    get_address_data();
    find_contact();
    //print_data();
};


function postal_code_inputed(){
    var postal_code=Dom.get('address_postal_code').value.replace(/\s+/,"");
    if(postal_code=='')
	validate_data.postal_code.inputed=false;
    else
	validate_data.postal_code.inputed=true;

    

}

function validate_postal_code(){
    var postal_code=Dom.get('address_postal_code').value.replace(/\s+/,"");
    var o=Dom.get("address_postal_code");
    var tr=Dom.get('tr_address_postal_code');
   // alert(postal_regex+' '+postal_code)
    var item='postal_code';

    var valid=postal_regex.test(postal_code);

    if(validate_data.postal_code.inputed==true){
	if(valid){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    //alert('hard no valid');
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	    validate_data[item].validated=false;

	}

    }else{
	
	Dom.removeClass(o,'no_validated');
	if(valid){
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    // alert('no valid');
	    validate_data[item].validated=false;
	    Dom.removeClass(tr,'validated');
	}


    }

 get_contact_data();
    validate_form();

    find_contact();
}

function email_inputed(){
    var item='email';
    var value=Dom.get('Email').value.replace(/\s+/,"");
    var tr=Dom.get('email_mould')
    if(value=='')
	validate_data[item].inputed=false;
    else{
	validate_data[item].inputed=true;

		    
	if(validate_data.email.validated==true){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	}else{
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	}
	
    }
    display_form_state();
    
}

function  validate_email(email) {
    var email=unescape(email);
    var o=Dom.get("Email");
    var tr=Dom.get('email_mould');

    if(email==''){
	validate_data['email'].inputed=false;
	validate_data.email.validated=true;
	Dom.removeClass(tr,'no_validated');
	return;
    }



    if(validate_data.email.inputed==true){
	if(isValidEmail(email)){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	    validate_data.email.validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	    validate_data.email.validated=false;
	}
    }else{
	
	Dom.removeClass(o,'no_validated');
	if(isValidEmail(email) ){
	    Dom.addClass(tr,'validated');
	    validate_data.email.validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    
	    validate_data.email.validated=false;
	    //alert('x '+validate_data.email.validated);
	}


    }

    get_contact_data();
    validate_form();

    find_contact();
    


};




    function init(){
    
      
 
	YAHOO.util.Event.addListener('Telephone', "blur",telephone_inputed);

	YAHOO.util.Event.addListener('Email', "blur",email_inputed);
	YAHOO.util.Event.addListener('address_postal_code', "blur",postal_code_inputed);

	YAHOO.util.Event.addListener('Contact_Name', "blur",contact_name_inputed);



	var ids = ["address_description","address_country_d1","address_country_d2","address_town"
		   ,"address_town_d2","address_town_d1","address_postal_code","address_street","address_internal","address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change_when_creating);
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change_when_creating);
	
	//TODO: event when paste with the middle mouse (peroblem in  linux only)
  

	YAHOO.util.Event.addListener('save_new_contact', "click",save_new_contact);
	
	
	

	

	
	
	
	
	if(suggest_d1){
	var Countries_d1_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d1_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d1_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d1_DS.maxCacheEntries = 10;
	var Countries_d1_AC = new YAHOO.widget.AutoComplete("address_country_d1", "address_country_d1_container", Countries_d1_DS); 
	Countries_d1_AC.generateRequest = function(sQuery) 
	    {
		return "?tipo=country_d1&country_2acode="+Dom.get('address_country_2acode').value+"&query=" + sQuery ;
	    };
 	var Country_d1_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d1_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d1_AC.itemSelectEvent.subscribe(Country_d1_selected); 
	
	}
	if(suggest_d2){
	var Countries_d2_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d2_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d2_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d2_DS.maxCacheEntries = 10;
	var Countries_d2_AC = new YAHOO.widget.AutoComplete("address_country_d2", "address_country_d2_container", Countries_d2_DS); 
	Countries_d2_AC.generateRequest = function(sQuery) 
	    {
		var request="?tipo=country_d2&country_2acode="+Dom.get('address_country_2acode').value
	        +"&country_d1_code="+Dom.get('address_country_d1_code').value
	        +"&query=" + sQuery ;
		//	alert(request)
		return request;
	    };
 	var Country_d2_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d2_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d2_AC.itemSelectEvent.subscribe(Country_d2_selected); 	
	}
	if(suggest_d3){
	var Countries_d3_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d3_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d3_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d3_DS.maxCacheEntries = 10;
	var Countries_d3_AC = new YAHOO.widget.AutoComplete("address_country_d3", "address_country_d3_container", Countries_d3_DS); 
	Countries_d3_AC.generateRequest = function(sQuery) 
	    {
		return "?tipo=country_d3&country_2acode="+Dom.get('address_country_2acode').value
		+"&country_d1_code="+Dom.get('address_country_d1_code').value
		+"&country_d2_code="+Dom.get('address_country_d2_code').value
		+"&query=" + sQuery ;
	    };
 	var Country_d3_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d3_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d3_AC.itemSelectEvent.subscribe(Country_d3_selected); 		
	}
	if(suggest_town){

	var Town_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Town_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Town_DS.responseSchema = {resultsList : "data",fields : ["name"]};
	Town_DS.maxCacheEntries = 10;
	var Town_AC = new YAHOO.widget.AutoComplete("address_town", "address_town_container", Town_DS); 
	Town_AC.generateRequest = function(sQuery) 
	    {
		var request= "?tipo=town&country_2acode="+Dom.get('address_country_2acode').value
		+"&country_d1_code="+Dom.get('address_country_d1_code').value
		+"&country_d2_code="+Dom.get('address_country_d2_code').value	      
		+"&country_d3_code="+Dom.get('address_country_d3_code').value	 

		+"&query=" + sQuery ;
	        alert(request);
		return request;
	    };
 	var Country_1d_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d1_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	//  Town_AC.itemSelectEvent.subscribe(Country_1d_selected); 	
	
	}
	
	
	if(suggest_country){
	
	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = function(oResultData, sQuery, sResultMatch) {
	    var query = sQuery.toLowerCase(),
	    name = oResultData.name,
	    code = oResultData.code,
	    query = sQuery.toLowerCase(),
	    nameMatchIndex = name.toLowerCase().indexOf(query),
	    codeMatchIndex = code.toLowerCase().indexOf(query),
	    displayname, displaycode;
	    if(nameMatchIndex > -1) {
		displayname = highlightMatch(name, query, nameMatchIndex);
	    }
	    else {
		displayname = name;
	    }

	    if(codeMatchIndex > -1) {
		displaycode = highlightMatch(code, query, codeMatchIndex);
	    }
	    else {
		displaycode = code;
	    }
	    return displayname + " (" + displaycode + ")";
	};

	// Helper function for the formatter
	var highlightMatch = function(full, snippet, matchindex) {
	    return full.substring(0, matchindex) + 
	    "<span class='match'>" + 
	    full.substr(matchindex, snippet.length) + 
	    "</span>" +
	    full.substring(matchindex + snippet.length);
	};

   
	var onCountrySelected = function(sType, aArgs) {
	    var myAC = aArgs[0]; // reference back to the AC instance
	    var elLI = aArgs[1]; // reference to the selected LI element
	    var oData = aArgs[2]; // object literal of selected item's result data
        
	    // update hidden form field with the selected item's ID
	    Dom.get("address_country_code").value = oData.code;
	    Dom.get("address_country_2acode").value = oData.code2a;

	    postal_regex=new RegExp(oData.postal_regex,"i");

	    myAC.getInputEl().value = oData.name + " (" + oData.code + ") ";

	    update_address_labels(oData.code);

	};
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
	}
 




	var contact_name_oACDS = new YAHOO.util.FunctionDataSource(validate_contact_name);
	contact_name_oACDS.queryMatchContains = true;
	var contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Contact_Name","Contact_Name_Container", contact_name_oACDS);
	contact_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.75;



	var email_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email);
	email_name_oACDS.queryMatchContains = true;
	var email_name_oAutoComp = new YAHOO.widget.AutoComplete("Email","Email_Container", email_name_oACDS);
	email_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.75;

	

	
	var telephone_name_oACDS = new YAHOO.util.FunctionDataSource(validate_telephone);
	telephone_name_oACDS.queryMatchContains = true;
	var telephone_name_oAutoComp = new YAHOO.widget.AutoComplete("Telephone","Telephone_Container", telephone_name_oACDS);
	telephone_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.55;


	var town_name_oACDS = new YAHOO.util.FunctionDataSource(address_changed);
	town_name_oACDS.queryMatchContains = true;
	var town_name_oAutoComp = new YAHOO.widget.AutoComplete("address_town","address_town_container", town_name_oACDS);
	town_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.55;

	var postal_code_name_oACDS = new YAHOO.util.FunctionDataSource(validate_postal_code);
	postal_code_name_oACDS.queryMatchContains = true;
	var postal_code_name_oAutoComp = new YAHOO.widget.AutoComplete("address_postal_code","address_postal_code_container", postal_code_name_oACDS);
	postal_code_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.55;

    } 
YAHOO.util.Event.onDOMReady(init);
