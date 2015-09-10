<?php

include_once('common.php');$sql="select `Country Key`,`Country Name`,`Country Code`,`Country 2 Alpha Code` from kbase.`Country Dimension`";
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

var Country_List=[<?php echo$country_list?>];

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

var match_country = function(sQuery) {
        // Case insensitive matching
        var query = sQuery.toLowerCase(),
            contact,
            i=0,
            l=Country_List.length,
            matches = [];
        
        // Match against each name of each contact
        for(; i<l; i++) {
            contact = Country_List[i];
            if((contact.name.toLowerCase().indexOf(query) > -1) ||
	       (contact.code.toLowerCase().indexOf(query) > -1))  {
                matches[matches.length] = contact;
            }
        }

        return matches;
    };

var todo_after_select_country= function(){
    return;
}

  var onCountrySelected = function(sType, aArgs) {
	    var myAC = aArgs[0]; // reference back to the AC instance
	    var elLI = aArgs[1]; // reference to the selected LI element
	    var oData = aArgs[2]; // object literal of selected item's result data
        
	    // update hidden form field with the selected item's ID
	    Dom.get("address_country_code").value = oData.code;
	    Dom.get("address_country_2acode").value = oData.code2a;

	    myAC.getInputEl().value = oData.name + " (" + oData.code + ") ";
	    
	    todo_after_select_country(oData.code);
	    
	};

var country_formatResult = function(oResultData, sQuery, sResultMatch) {
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
  var highlightMatch = function(full, snippet, matchindex) {
	 return full.substring(0, matchindex) + 
	 "<span class='match'>" + 
	 full.substr(matchindex, snippet.length) + 
	 "</span>" +
	    full.substring(matchindex + snippet.length);
     };