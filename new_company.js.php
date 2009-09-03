<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');


$salutation="''";
$sql="select `Salutation` from `Salutation Dimension` where `Language Key`=1";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $salutation.=',"'.$row['Salutation'].'"';
}
mysql_free_result($result);
$sql="select `Country Key`,`Country Name`,`Country Code`,`Country 2 Alpha Code` from kbase.`Country Dimension`";
$result=mysql_query($sql);
$country_list='';

while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $country_list.=',{"id":"'.$row['Country Key'].'","name":"'.$row['Country Name'].'","code":"'.$row['Country Code'].'","code2a":"'.$row['Country 2 Alpha Code'].'"}  ';
}
mysql_free_result($result);
$country_list=preg_replace('/^\,/','',$country_list);





?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
  

var company_data={
    "Company Name":""
    ,"Company Main Contact Name":""
    ,"Company Main Plain Email":""
    ,"Company Main Telephone":""
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
var suggest_country=false;
var suggest_d1=false;
var suggest_d2=false;
var suggest_d3=false;
var suggest_d4=false;
var suggest_d4=false;
var suggest_town=false;



var Subject='Company';
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
    for(x in company_data)
	data+=" "+x+": "+company_data[x]+"<br/>";
    Dom.get("results").innerHTML=data;
}


function get_company_data(){
    company_data['Company Name']=Dom.get('Company_Name').value;
    
}
function get_contact_data(){
        company_data['Company Main Contact Name']=Dom.get('Contact_Name').value;
	company_data['Company Main Telephone']=Dom.get('Telephone').value;


	if(Dom.get("Email").getAttribute('valid')==1)
	    company_data['Company Main Plain Email']=Dom.get('Email').value;
	else
	    company_data['Company Main Plain Email']="";
}

function get_adddress_data(){
    company_data['Company Address Line 1']=Dom.get('address_internal').value;
    company_data['Company Address Line 2']=Dom.get('address_building').value;
    company_data['Company Address Line 3']=Dom.get('address_street').value;
    company_data['Company Address Town']=Dom.get('address_town').value;
    company_data['Company Address Town Second Division']=Dom.get('address_town_d2').value;
    company_data['Company Address Town First Division']=Dom.get('address_town_d1').value;
    company_data['Company Address Postal Code']=Dom.get('address_postal_code').value;
    company_data['Company Address Country Code']=Dom.get('address_country_code').value;
    company_data['Company Address Country First Division']=Dom.get('address_country_d1').value;
    company_data['Company Address Country Second Division']=Dom.get('address_country_d2').value;
    company_data['Company Address Country Third Division']=Dom.get('address_country_d3').value;
    company_data['Company Address Country Forth Division']=Dom.get('address_country_d4').value;
    company_data['Company Address Country Fifth Division']=Dom.get('address_country_d5').value;
}



function get_data(){
    get_company_data();
    get_contact_data();
    get_adddress_data();
}


var save_new_company=function(e){
   
    get_data();

    var json_value = YAHOO.lang.JSON.stringify(company_data); 
	    
    var request='ar_edit_contacts.php?tipo=new_company&values=' + encodeURIComponent(json_value); 
	    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='updated'){
		    Dom.get(items[i]).value=r.value;
		    Dom.get(items[i]).getAttribute('ovalue')=r.newvalue;
		    save_details++;
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}
    

var find_company=function(){
   

    var json_value = YAHOO.lang.JSON.stringify(company_data); 
	    
    var request='ar_contacts.php?tipo=find_company&values=' + encodeURIComponent(json_value); 
    //  alert(request) ;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='found'){
		    
		}else if(r.action=='found_candidates'){
		    
		}
		Dom.get("results").innerHTML='';
		var count=0;
		
		for(x in r.candidates_data){
		    
		    Dom.get("results").innerHTML+='<div style="width:100%;xborder:1px solid red"><div style="width:200px;margin:5px 0px;float:left;margin-right:15px" class="contact_display">'+r.candidates_data[x]['card']+'</div> <div style="xborder:1px solid blue;margin-left:230px;;margin-top:5px"><div id="score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']+'" >'+r.candidates_data[x]['score']+'</div> <span class="button edit" style="margin:10px 0;float:left"><?php echo _('Choose This')?></span><div style="clear:both"></div><div style="clear:both"> </div>';
		    
		    var found_img='';
		    // alert(r.candidates_data[x]['found']);return;
		    if(r.candidates_data[x]['found']==1)
			found_img='<img src="art/icons/award_star_gold_1.png"/>';
		    
		    Dom.get('score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']).innerHTML=star_rating(r.candidates_data[x]['score'],200).innerHTML+found_img+'<span style="font-size:80%;margin-left:10px">('+Math.round(r.candidates_data[x]['score'])+')</span>';
		    
		    
		    
		    //	    if(count % 2 || count==0)
		    //	Dom.get("results").innerHTML+='<tr>'+td;
		    //else
		    //	Dom.get("results").innerHTML+=td+'</tr>';
		}
		//	Dom.get("results").innerHTML+='</table>';
		
	    }
	});

}
     


function company_name_changed (query) {
    get_company_data();
    // print_data();
    find_company();
			   //alert(query)
};
function  contact_name_changed2(query) {
   
    get_contact_data();
    //print_data();
    find_company();
};
function  email_changed(email) {
    email=unescape(email);
    o=Dom.get("Email");
    //alert(email)
    if(isValidEmail(email)){
	o.setAttribute('valid',1);
	Dom.removeClass(o,'invalid');
	get_contact_data();
	find_company();
    }else{
	if(o.getAttribute('valid')==1){
	    get_contact_data();
	    find_company(); 
	}
	    
	o.setAttribute('valid',0);
	Dom.addClass(o,'invalid');
    }
  

};
function  telephone_changed(query) {
  

    get_contact_data();
    //print_data()
    find_company();
};



    function init(){
    
	//   var ids = ["personal","pictures","work","other"]; 
	//	YAHOO.util.Event.addListener(ids, "click", change_block);
	// YAHOO.util.Event.addListener('save_details_button', "click",save_details );

	//YAHOO.util.Event.addListener('cancel_save_details_button', "click",cancel_save_details );
	//YAHOO.util.Event.addListener('add_address_button', "click",edit_address,false );


	//var ids = ["name","fiscal_name","tax_number","registration_number"]; 
	//YAHOO.util.Event.addListener(ids, "keyup", update_details);
 

	var ids = ["address_description","address_country_d1","address_country_d2","address_town"
		   ,"address_town_d2","address_town_d1","address_postal_code","address_street","address_internal","address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change_when_creating);
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change_when_creating);
	//TODO: event when paste with the middle mouse (peroblem in  linux only)
  

	YAHOO.util.Event.addListener('save_new_company', "click",save_new_company);
	
	
	

	
	
	
	
	
	
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
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
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
      
	    myAC.getInputEl().value = oData.name + " (" + oData.code + ") ";

	    update_address_labels(oData.code);

	};
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
	}
 

	var company_name_oACDS = new YAHOO.util.FunctionDataSource(company_name_changed);
	company_name_oACDS.queryMatchContains = true;
	var company_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Name","Company_Name_Container", company_name_oACDS);
	company_name_oAutoComp.minQueryLength = 0; 
	company_name_oAutoComp.queryDelay = 0.75;

	var contact_name_oACDS = new YAHOO.util.FunctionDataSource(contact_name_changed2);
	contact_name_oACDS.queryMatchContains = true;
	var contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Contact_Name","Contact_Name_Container", contact_name_oACDS);
	contact_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.75;

	var email_name_oACDS = new YAHOO.util.FunctionDataSource(email_changed);
	email_name_oACDS.queryMatchContains = true;
	var email_name_oAutoComp = new YAHOO.widget.AutoComplete("Email","Email_Container", email_name_oACDS);
	email_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.75;
	var telephone_name_oACDS = new YAHOO.util.FunctionDataSource(telephone_changed);
	telephone_name_oACDS.queryMatchContains = true;
	var telephone_name_oAutoComp = new YAHOO.widget.AutoComplete("Telephone","Telephone_Container", telephone_name_oACDS);
	telephone_name_oAutoComp.minQueryLength = 0; 
	contact_name_oAutoComp.queryDelay = 0.55;

    } 
YAHOO.util.Event.onDOMReady(init);