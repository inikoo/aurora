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




