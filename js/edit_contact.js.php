<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
include_once('../classes/Contact.php');
$contact_id=$_SESSION['state']['contact']['id'];
$contact=new contact($contact_id);
$main_telephone=$contact->get_main_telephone_data();
$main_fax=$contact->get_main_fax_data();
$main_mobile=$contact->get_main_mobile_data();
$main_address=$contact->get_main_address_data();

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

var Country_List=[<?=$country_list?>];





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

	var form=new YAHOO.inputEx.Form( { 
		fields: [ 

			 {type:'group',inputParams:{legend:'Email',name:'email_group',fields:[ 
											 {type:'combine',inputParams: {label: 'Email',
												     fields:[
													     {type:'email', inputParams: { name: 'email',description: 'Email address',value:'<?=$contact->data['Contact Main Plain Email']?>',showMsg: true,regexp: inputEx.regexps.email}}, 
													     {type:'uneditable', inputParams: {value:'Delete',name: 'delete_email'}}
													     ],separators: [false," "]
												     }}

											 ]}} ,

			 {type:'group',inputParams:{legend:'Name',name:'group2',fields:[ 
											
											{type: 'select', inputParams: {label: 'Title', name: 'title', selectValues: [<?=$salutation?>]  , value:'<?=$contact->data['Contact Salutation']?>'}},
											{inputParams: {label: 'Firstname', name: 'firstname',  value:'<?=$contact->data['Contact First Name']?>',typeInvite: '' } }, 
											{inputParams: {label: 'Lastname', name: 'lastname', value:'<?=$contact->data['Contact Surname']?>' ,typeInvite: '',} }, 
											 ]}} ,

			 {type:'group',inputParams:{legend:'Address',name:'group3',fields:[ 
											   {type:'autocomplete'
												   ,inputParams: {
												   label: 'Country'
												       , name: 'country'
												       ,  value:'GBR'
												       ,datasource: CountryDS, 
											       }
											   }
											   ,{inputParams: {label: 'Internal', name: 'line1',  value:'<?=$main_address['Internal']?>',typeInvite: '' } }
											   ,{inputParams: {label: 'Building', name: 'line2',  value:'<?=$main_address['Building']?>',typeInvite: '' } }
											   ,{inputParams: {label: 'Street', name: 'line3',  value:'<?=$main_address['Street']?>',typeInvite: '' } }
											   ,{inputParams: {label: 'Town', name: 'town',  value:'<?=$main_address['Town']?>',typeInvite: 'City Name' } }
											   ,{inputParams: {label: 'Postcode', name: 'postcode',  value:'<?=$main_address['Postal Code']?>',typeInvite: '',size: 5 } }
											    ]}} ,

			 { type:'group',inputParams:{legend:'Telephones',name:'group4',fields:[ 

												    
												    {type:'combine',inputParams: {label: 'Work Tel',description: 'Contact Work Telephone',
														fields: [
															 { inputParams: {name: 'wtel_icode', typeInvite: 'intl',size: 3, value: '<?=$main_telephone['Telecom Country Telephone Code']?>'} },
															 { inputParams: {name: 'wtel_acode', typeInvite: '0',size: 1 ,value: '<?=$main_telephone['Telecom National Access Code']?>'} },
															 { inputParams: {name: 'wtel_ncode', typeInvite: 'local',size: 4 ,value: '<?=$main_telephone['Telecom Area Code']?>'} },
															 { inputParams: {name: 'wtel_number', typeInvite: 'number',size:6, value: '<?=$main_telephone['Telecom Number']?>'} },
															 { inputParams: {name: 'wtel_ext', typeInvite: '',size: 2 ,value: '<?=$main_telephone['Telecom Extension']?>'} },
				],
														separators: ["+"," (",")","-"," Ext "]} },
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
	
	xform=form.getFieldByName('email_group').getFieldByName('email.group');
	alert(xform);
	    



	
    });
			


