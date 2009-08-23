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

var Subject='Company';
var Subject_Key=0;


var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;



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









function get_data(){
    values['Company Name']=Dom.get('Company_Name').value;
	values['Company Main Contact Name']=Dom.get('Contact_Name').value;
	values['Company Main Telephone']=Dom.get('Telephone').value;
	values['Company Main Plain Email']=Dom.get('Email').value;
    values['Company Address Line 1']=Dom.get('address_internal').value;
	values['Company Address Line 1']=Dom.get('address_building').value;
	values['Company Address Line 1']=Dom.get('address_street').value;
	values['Company Address Town']=Dom.get('address_town').value;
    values['Address Town Secondary Division']=Dom.get('address_town_d2').value;
	values['Address Town Primary Division']=Dom.get('address_town_d1').value;
	values['Company Address Postal Code']=Dom.get('address_postal_code').value;
	values['Company Address Country Code']=Dom.get('address_country_code').value;
    values['Company Address Country Primary Division']=Dom.get('address_country_d1').value;
    values['Company Address Country Secondary Division']=Dom.get('address_country_d2').value;

}


var save_new_company=function(e){
   
get_data();

	var json_value = YAHOO.lang.JSON.stringify(values); 
	    
	    var request='ar_edit_contacts.php?tipo=new_company&values=' + encodeURIComponent(json_value); 
	    
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    success:function(o) {
			alert(o.responseText);
			return;
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
	
	var Countries_d2_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
    Countries_d2_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d2_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
    Countries_d2_DS.maxCacheEntries = 10;
    var Countries_d2_AC = new YAHOO.widget.AutoComplete("address_country_d2", "address_country_d2_container", Countries_d2_DS); 
	Countries_d2_AC.generateRequest = function(sQuery) 
	{
	    return "?tipo=country_d2&country_2acode="+Dom.get('address_country_2acode').value
	        +"&country_d1_code="+Dom.get('address_country_d1_code').value
	        +"&query=" + sQuery ;
 	};
 	var Country_d2_selected = function(sType, aArgs) {
        var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
        Dom.get("address_country_d2_code").value = oData[1];
        myAC.getInputEl().value = oData[0] ;
    };
    Countries_d2_AC.itemSelectEvent.subscribe(Country_d2_selected); 	
	
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
	
	var Town_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
    Town_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Town_DS.responseSchema = {resultsList : "data",fields : ["name"]};
    Town_DS.maxCacheEntries = 10;
    var Town_AC = new YAHOO.widget.AutoComplete("address_town", "address_town_container", Town_DS); 
	Town_AC.generateRequest = function(sQuery) 
	{
	    return "?tipo=town&country_2acode="+Dom.get('address_country_2acode').value
	            +"&country_2acode="+Dom.get('address_country_d1_code').value
	            +"&country_d1_code="+Dom.get('address_country_d1_code').value
	            +"&country_d2_code="+Dom.get('address_country_d2_code').value	      
	            +"&country_d3_code="+Dom.get('address_country_d3_code').value	 
	            +"&query=" + sQuery ;
 	};
 	var Country_1d_selected = function(sType, aArgs) {
        var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
        Dom.get("address_country_d1_code").value = oData[1];
        myAC.getInputEl().value = oData[0] ;
    };
  //  Town_AC.itemSelectEvent.subscribe(Country_1d_selected); 	
	
	
	
	
	
	
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
YAHOO.util.Event.onDOMReady(init);