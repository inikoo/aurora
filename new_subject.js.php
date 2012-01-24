 var valid_form=false;
function find_subject(){
    get_data();

    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
var json_value_scope = YAHOO.lang.JSON.stringify({scope:scope,store_key:store_key}); 

if(scope=='customer'){
the_tipo='show_posible_customer_matches';
}else{
the_tipo='find_'+Subject
}


    var request='ar_contacts.php?tipo='+the_tipo+'&values=' + my_encodeURIComponent(json_value)+'&scope=' + my_encodeURIComponent(json_value_scope); 
  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var old_subject_found=subject_found;
		var old_subject_found_email=subject_found_email;

		if(r.action=='found_email'){
		    subject_found=true;
		    subject_found_email=true;
		    subject_found_key=r.found_key;
		    
		    contact_with_same_email=r.found_key;
		    //alert(subject_found+' '+subject_found_email);
		     update_save_button();
		}else if(r.action=='found'){
		    subject_found=true;
		    subject_found_email=false;
		    subject_found_key=r.found_key;
		    
		     update_save_button();
		}else if(r.action=='found_candidates'){
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0;
		     
		      update_save_button();
		}else{
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0; 
		       
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




function get_address_data(){

    subject_data[Subject+' Address Line 1']=Dom.get('address_internal').value;
    subject_data[Subject+' Address Line 2']=Dom.get('address_building').value;

    subject_data[Subject+' Address Line 3']=Dom.get('address_street').value;
    subject_data[Subject+' Address Town']=Dom.get('address_town').value;
      
    subject_data[Subject+' Address Town Second Division']=Dom.get('address_town_d2').value;
    subject_data[Subject+' Address Town First Division']=Dom.get('address_town_d1').value;
    subject_data[Subject+' Address Postal Code']=Dom.get('address_postal_code').value;
       
    subject_data[Subject+' Address Country Code']=Dom.get('address_country_code').value;
    subject_data[Subject+' Address Country First Division']=Dom.get('address_country_d1').value;
    subject_data[Subject+' Address Country Second Division']=Dom.get('address_country_d2').value;
    subject_data[Subject+' Address Country Third Division']=Dom.get('address_country_d3').value;
    subject_data[Subject+' Address Country Forth Division']=Dom.get('address_country_d4').value;
    
    subject_data[Subject+' Address Country Fifth Division']=Dom.get('address_country_d5').value;


}


function update_save_button(){
	//	alert(subject_found);
	validate_form();
	
	if(subject_found==true && valid_form){

	    Dom.get('save_new_'+Subject).style.display='none';
	    
	    if(subject_found_email==true){
		Dom.get('email_found_dialog').style.display='';
		Dom.get(Subject+'_found_dialog').style.display='none';

	    }else{
		Dom.get(Subject+'_found_dialog').style.display='';
		Dom.get('email_found_dialog').style.display='none';

	    }
	    
	}else{
	
	    Dom.get('save_new_'+Subject).style.display='';
	    Dom.get(Subject+'_found_dialog').style.display='none';
	    Dom.get('email_found_dialog').style.display='none';

	}
	
    }
function validate_form(){

	
      
	 valid_form=true;
	for (item in validate_data ){
	    if(validate_data[item].required==true && validate_data[item].validated==false){
		valid_form=false;
		
			    //	    alert(item+' '+validate_data[item].required+' '+validate_data[item].validated)

	    }
	    if(validate_data[item].inputed==true && validate_data[item].validated==false){
	//	valid_form=false;
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
	//	alert(validate_data.email.validated+' '+validate_data.email.inputed)
	if(valid_items_in_group<min_valid_items){
	    //valid_form=false;
	}

	
	if(valid_form==true){
	    
	    Dom.removeClass('save_new_'+Subject,'disabled');
	    can_add_subject=true;
	}else{
	    can_add_subject=false;
	    Dom.addClass('save_new_'+Subject,'disabled');
	}
	
    }
function display_form_state(){
    
    
   
}



function validate_contact_name(query) {
    item='contact_name';
    var validator=new RegExp(validate_data[item].regexp,"i");
    if(validator.test(query)){
	validate_data[item].validated=true;
    }else{
	validate_data[item].validated=false;
    }
    get_contact_data();
    find_subject();
    validate_form();
};
function contact_name_inputed(){
    var item='contact_name';
    var value=Dom.get('Contact_Name').value.replace(/\s+/,"");
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;
    
    
}    
function validate_telephone(original_query) {
    
    var tr=Dom.get('telephone_mould');
    var o=Dom.get('Telephone');
    value=original_query.replace(/[^\d]/g,"");
    //  alert(query)
    var item='telephone';

    if(original_query==''){
	validate_data[item].inputed=false;
	validate_data[item].validated=true;
	//Dom.removeClass(tr,'no_validated');
	//Dom.removeClass(tr,'validated');

	return;
    }
    else
	validate_data[item].inputed=true;


    var validator=new RegExp(validate_data[item].regexp,"i");
    
    if(validate_data[item].inputed==true){
	if(validator.test(value)){
	    //Dom.removeClass(tr,'no_validated');
	    //Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    //Dom.removeClass(tr,'validated');
	    //Dom.addClass(tr,'no_validated');
	    validate_data[item].validated=false;
	}
    }else{
	if(validator.test(value) ){
	    //Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    //Dom.removeClass(tr,'validated');
	    
	    validate_data[item].validated=false;

	}
	

    }
    get_contact_data();
    find_subject();
    validate_form();


};
function telephone_inputed(){
    var item='telephone';
    var value=Dom.get('Telephone').value.replace(/\s+/,"");
    //  alert(value)
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;

    
    
    //validate_postal_code(postal_code);
    
}    
function edit_founded(){
if(scope=='customer')
    location.href='customer.php?edit='+subject_found_key;

else
    location.href='edit_'+scope+'.php?id='+subject_found_key;

}




function address_changed(query) {
  
    get_address_data();
    find_subject();
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
  
    var item='postal_code';
    var valid=postal_regex.test(postal_code);

    if(postal_code!=''){
	validate_data.postal_code.inputed=true;
    }else{
	validate_data.postal_code.inputed=true;
		
    }

Dom.get('address_postal_code_warning').setAttribute('title',postcode_help);
    if(validate_data.postal_code.inputed==true){
	if(valid){
	   // Dom.removeClass(tr,'no_validated');
	  //  Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	    
	    Dom.get('address_postal_code_warning').style.visibility='hidden';
	}else{
	    //alert('hard no valid');
	   // Dom.removeClass(tr,'validated');
	  //  Dom.addClass(tr,'no_validated');
	    validate_data[item].validated=false;
	    	    Dom.get('address_postal_code_warning').style.visibility='visible';

	}

    }else{
	
	Dom.removeClass(o,'no_validated');
	if(valid){
	  //  Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	    	    Dom.get('address_postal_code_warning').style.visibility='hidden';

	}else{
	    // alert('no valid');
	    validate_data[item].validated=false;
	 //   Dom.removeClass(tr,'validated');
	    	    Dom.get('address_postal_code_warning').style.visibility='visible';

	}


    }

 get_contact_data();
    validate_form();

    find_subject();
}
function email_inputed(){
return;
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
    
    
}
function validate_email_address(email) {
 
    

    var email=unescape(email);
    var o=Dom.get("Email");
    var tr=Dom.get('email_mould');
    var item='email';
    if(email==''){
	validate_data['email'].inputed=false;
	validate_data.email.validated=true;
	//Dom.removeClass(tr,'no_validated');
	//Dom.removeClass(tr,'validated');
	    	    	    Dom.get('email_warning').style.visibility='hidden';

	return;
    }else
	validate_data.email.inputed=true;

    
    // alert(email+' '+isValidEmail(email))

    if(validate_data.email.inputed==true){
	if(isValidEmail(email)){
	    //Dom.removeClass(tr,'no_validated');
	    //Dom.addClass(tr,'validated');
	    validate_data.email.validated=true;
	    	    	    Dom.get('email_warning').style.visibility='hidden';

	}else{
	   // Dom.removeClass(tr,'validated');
	   // Dom.addClass(tr,'no_validated');
	    	    Dom.get('email_warning').style.visibility='visible';

	    
	    validate_data.email.validated=false;
	}
    }else{
	Dom.get('email_warning').style.visibility='hidden';

	//Dom.removeClass(o,'no_validated');
	
	if(isValidEmail(email) ){
	Dom.get('email_warning').style.visibility='hidden';

	//Dom.addClass(tr,'validated');
	    validate_data.email.validated=true;
	}else{
	  //  Dom.removeClass(tr,'validated');
	    	    	    	  //  Dom.get('email_code_warning').style.visibility='hidden';

	    validate_data.email.validated=false;
	    //alert('x '+validate_data.email.validated);
	}


    }
    get_contact_data();
    validate_form();

    find_subject();
    


};





function print_data(){
    var data='';
    for(x in subject_data)
	data+=" "+x+": "+subject_data[x]+"<br/>";
    Dom.get("results").innerHTML=data;
}
function get_data(){
    get_subject_data();
    get_contact_data();
    get_address_data();
   
    get_scope_data();
	get_custom_data();
}

function get_custom_data(){
}


function get_scope_data(){
if(scope=='supplier'){
    subject_data['Supplier Code']=Dom.get('Supplier_Code').value;

}
if(scope=='customer'){
    // alert(Dom.get('Store_Key'))
    subject_data['Customer Store Key']=Dom.get('Store_Key').value;
    subject_data['Customer Type']=Dom.get('Customer_Type').value;
    
  
    
    
    subject_data['Customer Send Newsletter']=Dom.get('allow_newsletter').value;
    subject_data['Customer Send Email Marketing']=Dom.get('allow_marketing_email').value;
    subject_data['Customer Send Postal Marketing']=Dom.get('allow_marketing_postal').value;
    subject_data['Recargo Equivalencia']=Dom.get('re').value;

}



}



function init(){
    
 
	YAHOO.util.Event.addListener('Telephone', "blur",telephone_inputed);

	YAHOO.util.Event.addListener('Email', "blur",email_inputed);
	YAHOO.util.Event.addListener('address_postal_code', "blur",postal_code_inputed);
	YAHOO.util.Event.addListener('Contact_Name', "blur",contact_name_inputed);

/*
	var ids = ["address_description","address_country_d1","address_country_d2","address_town"
		   ,"address_town_d2","address_town_d1","address_postal_code","address_street","address_internal","address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change_when_creating);
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change_when_creating);
  */
	if(suggest_country){

	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex","postcode_help"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
		Countries_AC.prefix = ''; 

	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
    var highlightMatch = countries_highlightMatch;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);

	}



	
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
	Town_AC.autoHighlight = false;
	Town_AC.generateRequest = function(sQuery) 
	    {

		var request= "?tipo=town&country_2acode="+Dom.get('address_country_2acode').value
	//	+"&country_d1_code="+Dom.get('address_country_d1_code').value
//		+"&country_d2_code="+Dom.get('address_country_d2_code').value	      
//		+"&country_d3_code="+Dom.get('address_country_d3_code').value	 

		+"&query=" + sQuery ;
	  // alert(request);
		return request;
	    };
 	var Country_1d_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d1_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	//  Town_AC.itemSelectEvent.subscribe(Country_1d_selected); 	

   

	}
	


 


	if(scope=='supplier'){
	    
	    var supplier_code_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_code);
	    supplier_code_oACDS.queryMatchContains = true;
	    var supplier_code_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Code","Supplier_Code_Container", supplier_code_oACDS);
	    supplier_code_oAutoComp.minQueryLength = 0; 
	    supplier_code_oAutoComp.queryDelay = 0.75;
	    
	}


	var contact_name_oACDS = new YAHOO.util.FunctionDataSource(validate_contact_name);
	contact_name_oACDS.queryMatchContains = true;
	var contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Contact_Name","Contact_Name_Container", contact_name_oACDS);
	contact_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.75;

	var email_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email_address);
	email_name_oACDS.queryMatchContains = true;
	var email_name_oAutoComp = new YAHOO.widget.AutoComplete("Email","Email_Container", email_name_oACDS);
	email_name_oAutoComp.minQueryLength = 0; 
	email_name_oAutoComp.queryDelay = 0.75;

	

	
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



