 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;


suggest_country=false;

var data={
    'email':''
    , 'password':''
    ,'customer_type':''
    ,'customer_type_other':''
    ,'customer_is_company':''
    ,'contact_name':''
    ,'company_name':''
    ,'tax_number':''

    ,'tel':''
    ,'country':''
    ,'country_d1':''
    ,'country_d2':''
    ,'postal_code':''
    ,'town':''
    ,'town_d1':''
    ,'town_d2':''
    ,'street':''
    ,'building':''
    ,'internal':''
    ,'newsletter':''
    ,'emarketing':''
    ,'catalogue':''
}

function validate_email(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(address) == false) {
      return false;
   }else
       return true;

}

// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 6;

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}
function trim(s)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not a whitespace, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (c != " ") returnString += c;
    }
    return returnString;
}
function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function checkInternationalPhone(strPhone){
var bracket=3
strPhone=trim(strPhone)
if(strPhone.indexOf("+")>1) return false
if(strPhone.indexOf("-")!=-1)bracket=bracket+1
if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false
var brchr=strPhone.indexOf("(")
if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false
if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false
s=stripCharsInBag(strPhone,validWorldPhoneChars);
return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}




function submit_email(){
    
    var store_key=Dom.get('get_email').getAttribute('store_key');
    var email=Dom.get('email').value;
    if(email==''){
	Dom.get('email_instructions').innerHTML=Dom.get('email_error_msg_1').innerHTML;
	Dom.addClass(['email','email_label'],'error');

    }else if(!validate_email(email)){
	msg_id=1+Math.floor(Math.random()*2);
	
	Dom.get('email_instructions').innerHTML=Dom.get('email_not_valid_msg_'+msg_id).innerHTML;
	Dom.addClass(['email','email_label'],'error');
		

    }else if(email!=Dom.get('email_confirmation').value){
	Dom.get('email_confirmation').setAttribute('confirmed','yes');

	if(Dom.get('email_confirmation').value==''){
	    Dom.get('email_instructions').innerHTML=Dom.get('email_not_confirmed').innerHTML;
	
	}else{
	    Dom.get('email_instructions').innerHTML=Dom.get('email_error_confirmed').innerHTML;


	}
	Dom.addClass(['email_confirmation','email_confirmation_label'],'error');

    }else{
    
     Dom.setStyle('submit_email','display','none');
			    Dom.setStyle('get_customer_type','display','');
			    //Dom.setStyle('get_password','display','');
			    Dom.get('email_confirmation').setAttribute('confirmed','yes');
		    	 Dom.get('email_instructions').innerHTML=Dom.get('email_ok').innerHTML;

			    data.email=email;
	

    }
    
   
}



function customer_type_selected(){
    
    Dom.removeClass('customer_type_options','error');
    
    Dom.get('password').setAttribute('confirmed','yes');
    if(!check_password())
	return;


    
    Dom.setStyle('customer_type_extra_info','display','none');
    Dom.get('other_type').value='';
    Dom.get('confirmation_trade_only').checked = false;
    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_ok_msg').innerHTML;



    old_value=data.customer_type;

    data.customer_type=this.value;
    
    

    


    if(data.customer_type=='wholesaler' || data.customer_type=='big_shop'|| data.customer_type=='small_shop' ){
	Dom.setStyle('submit_customer_type','display','none');
	company_choosen();
	


    }else{
	Dom.setStyle('person_choosen','display','none');
	Dom.setStyle('company_choosen','display','none');
	Dom.setStyle('submit_details','display','none');
	Dom.setStyle('submit_customer_type','display','none');
	Dom.setStyle('company_or_person','display','');

    }





}


function password_changed(){

    if(Dom.get('password').getAttribute('confirmed')=='yes'){
	check_password();
    }
}



function email_confirmation_changed(){
    if(this.getAttribute('confirmed')=='yes'){

	if(this.value==Dom.get('email').value){
	    Dom.removeClass(['email_confirmation','email_confirmation_label'],'error');
	    Dom.get('email_instructions').innerHTML=Dom.get('email_ok').innerHTML;

	}else{
	    	if(this.value==''){
	    Dom.get('email_instructions').innerHTML=Dom.get('email_not_confirmed').innerHTML;
	
	}else{
	    Dom.get('email_instructions').innerHTML=Dom.get('email_error_confirmed').innerHTML;


	}
	Dom.addClass(['email_confirmation','email_confirmation_label'],'error');

	}

    }

}

function customer_type_other_selected(){
    Dom.removeClass('customer_type_options','error');
	    
    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_other_msg').innerHTML;
	
    Dom.setStyle('customer_type_extra_info','display','');
}

function submit_customer_type(){
    var error_label=false;

    Dom.get('password').setAttribute('confirmed','yes');
	

    //if(!check_password())
	//error_label=true;

  

    data.customer_type='';
    var options=Dom.getElementsByClassName('radio', 'input', 'customer_type_options');
    for (i in options){
	if(options[i].checked){
	    data.customer_type=options[i].value;
	    
	    continue;
	}
    }

    if(!data.customer_type){
	Dom.addClass('customer_type_options','error');
	Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_other_msg').innerHTML;
	return
    }
    

   
    if(Dom.get('customer_type_other').checked){

	if(Dom.get('other_type').value==''){
	    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_other_msg').innerHTML;
	    Dom.addClass(['other_type_label','other_type'],'error');
	    error_label=true;
	}
	if(!Dom.get('confirmation_trade_only').checked){
	    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_not_confirmed_msg').innerHTML;
	    Dom.addClass('confirmation_trade_only_msg','error');

	    error_label=true;
	}
	
    
    if(error_label)
	return;
    data.customer_type='other';
    Dom.setStyle('submit_customer_type','display','none');
    Dom.setStyle('company_or_person','display','');
    
    }else{
	if(error_label)
	return;
   if(data.customer_type=='wholesaler' || data.customer_type=='big_shop'|| data.customer_type=='small_shop' ){
	Dom.setStyle('submit_customer_type','display','none');
	company_choosen();
	


    }else{
	Dom.setStyle('person_choosen','display','none');
	Dom.setStyle('company_choosen','display','none');
	Dom.setStyle('submit_details','display','none');
	Dom.setStyle('submit_customer_type','display','none');
	Dom.setStyle('company_or_person','display','');

    }

    }


    



}

function other_type_changed(){
if(Dom.get('other_type').value==''){
    Dom.addClass(['other_type_label','other_type'],'error');
    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_not_confirmed_msg').innerHTML;

}else{
    Dom.removeClass(['other_type_label','other_type'],'error');
    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_other_msg').innerHTML;

}

}

function confirmation_trade_only_changed(){
if(Dom.get('confirmation_trade_only').checked){
 Dom.removeClass('confirmation_trade_only_msg','error');
       Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_other_msg').innerHTML;

}else{
    Dom.addClass('confirmation_trade_only_msg','error');
    Dom.get('customer_type_instructions').innerHTML=Dom.get('customer_type_not_confirmed_msg2').innerHTML;
	    
}

}

function telephone_changed(){
    tel_dom=Dom.get('telephone')
    tel=tel_dom.value;
    
    if(tel=='')
       tel_dom.setAttribute('check','no');
    check=tel_dom.getAttribute('check');
	

    if(!checkInternationalPhone(tel) && check=='yes'){

	Dom.addClass(['telephone_label','telephone'],'error');
	return false;
    }else{

	Dom.removeClass(['telephone_label','telephone'],'error');
	return true;
	
    }
}


function person_choosen(){
    data.customer_is_company=false;
    Dom.get('person').checked=true;
    Dom.setStyle('person_choosen','display','');
    Dom.setStyle('company_choosen','display','none');
    Dom.setStyle('submit_details','display','');

}
function company_choosen(){
    data.customer_is_company=true;
    Dom.get('company').checked=true;
    Dom.setStyle('company_choosen','display','');
    Dom.setStyle('person_choosen','display','none');
    Dom.setStyle('submit_details','display','');
    Dom.setStyle('company_or_person','display','none');

}



function submit_details(){
 var error_tag=false;
 var no_company_name=false;

    if(Dom.get('person').checked){
	if(Dom.get('person_contact').value==''){
	    
	    Dom.addClass(['person_contact_label','person_contact'],'error');
	    error_tag=true;
	    Dom.get('customer_details_instructions').innerHTML=Dom.get('customer_details_msg2').innerHTML;

	}
	    Dom.get('customer_details_instructions').innerHTML='';
	
	


    }else{
	if(Dom.get('company_name').value==''){
	    Dom.addClass(['company_name_label','company_name'],'error');
	    error_tag=true;
	    no_company_name=true;
	    Dom.get('customer_details_instructions').innerHTML=Dom.get('customer_details_msg1').innerHTML;
		
	    
	}
	if(Dom.get('company_contact').value==''){
	     Dom.addClass(['company_contact_label','company_contact'],'error');
	     error_tag=true;
	     
	     if(no_company_name){
		 Dom.get('customer_details_instructions').innerHTML=Dom.get('customer_details_msg3').innerHTML;
	    
	     }else{
		 Dom.get('customer_details_instructions').innerHTML=Dom.get('customer_details_msg2').innerHTML;

	     }



	}

	

    }
    
    if(!error_tag){
	Dom.get('customer_details_instructions').innerHTML='';
	Dom.setStyle('submit_details','display','none');
	Dom.setStyle('get_optional_details','display','');
    }


}

function submit(){

    Dom.get('telephone').setAttribute('check','yes');

    
    if(data.customer_is_company){
	data.contact_name=Dom.get('company_contact').value;

    }else{
	data.contact_name=Dom.get('person_contact').value;
    }

    data.company_name=Dom.get('company_name').value;
    data.tax_number=Dom.get('company_tax_number').value;

   
    valid_tel=telephone_changed();
    if(!valid_tel){
	Dom.setStyle('final_tel_error_msg','display','');
	Dom.setStyle('final_msg','display','none');

	return;
    }else{
	Dom.setStyle('final_msg','display','');
	Dom.setStyle('final_tel_error_msg','display','none');
    }
    data.password=sha256_digest((Dom.get('password').value));

    data.tel=Dom.get('telephone').value;
    data.country=Dom.get('address_country').value;
    data.country_d1=Dom.get('address_country_d1').value;
    data.country_d2=Dom.get('address_country_d2').value;
    data.postal_code=Dom.get('address_postal_code').value;
    data.town=Dom.get('address_town').value;
    data.town_d1=Dom.get('address_town_d1').value;
    data.town_d2=Dom.get('address_town_d2').value;
    data.street=Dom.get('address_street').value;
    data.building=Dom.get('address_building').value;
    data.internal=Dom.get('address_internal').value;

    data.newsletter=Dom.get('newsletter').checked;
    data.emarketing=Dom.get('emarketing').checked;
    data.catalogue=Dom.get('catalogue').checked;

    var jsonStr = YAHOO.lang.JSON.stringify(data);
    
    Dom.setStyle('submit','display','none');
    Dom.setStyle('wait','display','');

    
    var request='ar_register.php?tipo=register_customer&values='+jsonStr;
    //    alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.action=='found'){

			  Dom.get('registered_email').innerHTML=email;
			    Dom.setStyle('get_email','display','none');
			    Dom.setStyle('found_email','display','');
		      }else if(r.action=='created'){
			  previous_page='index.php';
			  window.location=previous_page+'?wellcome';
			  
		      }
		      
		      

		    }else{
			window.location='register.php?we';
		    }
			

		}
	    
	    });





}

function name_changed(){
    if(this.value==''){
	Dom.addClass(this,'error');
	Dom.addClass(this.id+'_label','error');

    }else{
	Dom.removeClass(this,'error');
	Dom.removeClass(this.id+'_label','error');
    }

    if(this.id=='company_contact'){
	Dom.get('person_contact').value=this.value;
    }else if(this.id=='person_contact'){
	Dom.get('company_contact').value=this.value;
    }


}

function send_password_reminder(){

}
function check_password(){

}


function init(){
   
    
    Event.addListener('submit_email', "click",submit_email);
    
    Event.addListener('customer_type_other', "click",customer_type_other_selected);
    ids=['customer_type_wholesaler','customer_type_big_shop','customer_type_small_shop','customer_type_internet','customer_type_market','customer_type_special'];
    Event.addListener(ids, "click",customer_type_selected);
    Event.addListener('other_type', "keyup",other_type_changed);
    Event.addListener('confirmation_trade_only', "click",confirmation_trade_only_changed);
    Event.addListener('submit_customer_type', "click",submit_customer_type);

    Event.addListener('company', "click",company_choosen);
    Event.addListener('person', "click",person_choosen);
    Event.addListener(['company_name','company_contact','person_contact'],"keyup",name_changed);
  
    Event.addListener('telephone',"keyup",telephone_changed);
    Event.addListener('email_confirmation',"keyup",email_confirmation_changed);
    Event.addListener(['password','password_confirmation'],"keyup",password_changed);



    Event.addListener('submit_details', "click",submit_details);

    Event.addListener('submit', "click",submit);
    
    Event.addListener('password_reminder', "click",send_password_reminder);

	var ids = ["address_description","address_country_d1","address_country_d2","address_town"
		   ,"address_town_d2","address_town_d1","address_postal_code","address_street","address_internal","address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change_when_creating);
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change_when_creating);


   
	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex","postcode_help"]}

 
	var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
    var highlightMatch = countries_highlightMatch;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);

	var Countries_d1_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d1_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d1_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d1_DS.maxCacheEntries = 10;
	var Countries_d1_AC = new YAHOO.widget.AutoComplete("address_country_d1", "address_country_d1_container", Countries_d1_DS); 
	Countries_d1_AC.generateRequest = function(sQuery) 
	    {
	  // alert("?tipo=country_d1&country_2acode="+Dom.get('address_country_2acode').value+"&query=" + sQuery )
		return "?tipo=country_d1&country_2acode="+Dom.get('address_country_2acode').value+"&query=" + sQuery ;
	    };
 	var Country_d1_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d1_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d1_AC.itemSelectEvent.subscribe(Country_d1_selected); 

    
   
}
Event.onDOMReady(init);
