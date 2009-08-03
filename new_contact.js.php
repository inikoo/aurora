<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
$salutation="''";
$sql="select `Salutation` from `Salutation Dimension` where `Language Key`=1";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $salutation.=',"'.$row['Salutation'].'"';
}
$sql="select `Country Name`,`Country Code` from `Country Dimension`";
$result=mysql_query($sql);
$country_list='';
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $country_list.=',{"n":"'.$row['Country Name'].'","c":"'.$row['Country Code'].'"}  ';
}
$country_list=preg_replace('/^\,/','',$country_list);


?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var Country_List=[<?php echo$country_list?>];


var get_country= function(q){
    var postData='tipo=country&q='+q;
    var sUrl='ar_address.php';
    var handleSuccess = function(o){

	var r =  YAHOO.lang.JSON.parse(o.responseText);
	return r;
    }
    var handleFailure = function(o){
       

    }
    var callback =
    {
	success:handleSuccess,
	failure: handleFailure,
	argument: ['foo','bar']
    };


    var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData); 
    
}




	// Country_list DataSource using a JSFunction
			// Country_list.posts is set by the http://feeds.delicious.com/feeds/json/neyric?count=100 script included in the page
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




YAHOO.util.Event.addListener(window, "load", function(){

	new YAHOO.inputEx.Form( { 
		fields: [ 

			 {type:'group',inputParams:{legend:'Email',name:'group1',fields:[ 
											 												    {type:'email', inputParams: {label: 'Email', name: 'email',description: 'Email address',typeInvite: 'email',showMsg: true,regexp: inputEx.regexps.email}}, 
										
											 ]}} ,

			 {type:'group',inputParams:{legend:'Name',name:'group2',fields:[ 
											
											{type: 'select', inputParams: {label: 'Title', name: 'title', selectValues: [<?php echo$salutation?>] } },
											{inputParams: {label: 'Firstname', name: 'firstname',  value:'',typeInvite: 'eg John' } }, 
											{inputParams: {label: 'Lastname', name: 'lastname', value:'' ,typeInvite: '',} }, 
											 ]}} ,

			 {type:'group',inputParams:{legend:'Address',name:'group3',fields:[ 
											   {type:'autocomplete'
												   ,inputParams: {
												   label: 'Country'
												       , name: 'country'
												       ,  value:''
												       ,typeInvite: 'eg UK or GBR' 
												       ,datasource: CountryDS, 
											       }
											   }
											   ,{inputParams: {label: 'Internal', name: 'line1',  value:'',typeInvite: 'Flat 3' } }
											   ,{inputParams: {label: 'Building', name: 'line2',  value:'',typeInvite: 'The Big Building' } }
											   ,{inputParams: {label: 'Street', name: 'line3',  value:'',typeInvite: '43 Hello Road' } }
											   ,{inputParams: {label: 'Town', name: 'town',  value:'',typeInvite: 'City Name' } }
											   ,{inputParams: {label: 'Postcode', name: 'postcode',  value:'',typeInvite: '',size: 5 } }
											    ]}} ,

			 { type:'group',inputParams:{legend:'Telephones',name:'group4',fields:[ 

												    
												    {type:'combine',inputParams: {label: 'Work Tel',description: 'Contact Work Telephone',
														fields: [
															 { inputParams: {name: 'wtel_icode', typeInvite: 'iac',size: 3} },
															 { inputParams: {name: 'wtel_ncode', typeInvite: 'lac',size: 4} },
															 { inputParams: {name: 'wtel_number', typeInvite: 'number'} },
															 { inputParams: {name: 'wtel_ext', typeInvite: 'ext',size: 2} },
				],
														separators: ["+"," ",false," Ext "]} },
												    {type:'combine',inputParams: {label: 'Work Fax',description: 'Contact Work Fax',
														fields: [
															 { inputParams: {name: 'wfax_icode', typeInvite: 'iac',size: 3} },
															 { inputParams: {name: 'wfax_ncode', typeInvite: 'lac',size: 4} },
															 { inputParams: {name: 'wfax_number', typeInvite: 'number'} },
															 ],
														separators: ["+"," ",false]} },
												     {type:'combine',inputParams: {label: 'Mobile',description: 'Mobile Phone',
														fields: [
															 { inputParams: {name: 'mob_icode', typeInvite: 'iac',size: 3} },
															 { inputParams: {name: 'mob_number', typeInvite: 'number'} },
															 ],
														separators: ["+"," "]} },
  
												    {type:'combine',inputParams: {label: 'Home Tel',description: 'Contact Home Telephone',
														fields: [
															 { inputParams: {name: 'htel_icode', typeInvite: 'iac',size: 3} },
															 { inputParams: {name: 'htel_ncode', typeInvite: 'lac',size: 4} },
															 { inputParams: {name: 'htel_number', typeInvite: 'number'} },

				],
														separators: ["+"," ",false]} },
												     ]}} 	 
			  ], 
		    buttons: [{type: 'submit', value: 'Change'}], 	
		    parentEl: 'container1' 
		    });
	
	
	
    });
			


