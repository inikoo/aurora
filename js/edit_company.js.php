<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
include_once('../classes/Contact.php');
$contact_id=$_SESSION['state']['contact']['id'];
$contact=new contact($contact_id);
//$main_telephone=$contact->get_main_telephone_data();
//$main_fax=$contact->get_main_fax_data();
//$main_mobile=$contact->get_main_mobile_data();
//$main_address=$contact->get_main_address_data();


$edit_block='personal';
if(isset($_REQUEST['edit'])){
$valid_edit_blocks=array('personal','work','pictures','others');
if(in_array($_REQUEST['edit'],$valid_edit_blocks))
    $edit_block=$_REQUEST['edit'];
}
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


if( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ){
    $company_key=$_SESSION['state']['company']['id'];
}else
    $company_key=$_REQUEST['id'];
print "var company_key=$company_key;";

?>
    
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var Country_List=[<?=$country_list?>];


var current_salutation='salutation<?=$contact->get('Salutation Key')?>';
var current_block='<?=$edit_block?>';
var old_salutation=current_salutation;


var changes_details=0;
var saved_details=0;
var error_details=0;


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


function update_full_address(){
    var full_address=Dom.get(current_salutation).innerHTML+' '+Dom.get("v_first_name").value+' '+Dom.get("v_surname").value;
    Dom.get("full_name").value=full_address;
    calculate_num_changed_in_personal()
}


function update_salutation(o){
    if(Dom.hasClass(o, 'selected'))
	return;
    Dom.removeClass(current_salutation, 'selected');
    Dom.addClass(o, 'selected');
    current_salutation=o.id;
    calculate_num_changed_in_personal()
    update_full_address();

}

function calculate_num_changed_in_personal(){
    var changes=0;
    if(current_salutation!=old_salutation)
	changes++;
    
    var first_name=Dom.get("v_first_name");
    if(first_name.getAttribute('ovalue')!=first_name.value)
	changes++;
    
    var surname=Dom.get("v_surname");
    if(surname.getAttribute('ovalue')!=surname.value)
	changes++;
    
    Dom.get("personal_num_changes").innerHTML=changes;

}
var change_block = function(e){
    if(Dom.hasClass(this, 'selected'))
	return;
    Dom.removeClass(current_block, 'selected');
    Dom.addClass(this, 'selected');
    Dom.setStyle('d_'+current_block, 'display','none');
    Dom.setStyle('d_'+this.id, 'display','');

    current_block=this.id;
    
};



var save_details=function(e){
    var items = ["name","fiscal_name","tax_number","registration_number"];
    var table='company';
    save_details=0;
    for ( var i in items )
	{
	    var key=items[i];
	    var value=Dom.get(items[i]).value;
	    var request='ar_edit_contacts.php?tipo=edit_'+escape(table)+'&key=' + key + '&value=' + escape(value)+'&id='+company_key; 
	   
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    success:function(o) {
			//alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.action=='updatedd'){
			    Dom.get(items[i]).value=r.value;
			    Dom.get(items[i]).getAttribute('ovalue')=Dom.get(items[i]).value;
			    save_details++;
			}else if(r.action=='error'){
			    alert(r.msg);
			}
			    

			
		    }
		});

	} 
    
}
var cancel_save_details=function(e){
    var items = ["name","fiscal_name","tax_number","registration_number"];
    for ( var i in items )
	{
	    Dom.get(items[i]).value=Dom.get(items[i]).getAttribute('ovalue');
	} 
    
    Dom.get('details_messages').innerHTML='';
    Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', 'none'); 
}

var update_details=function(e){
    var changes=0;
    
    var items = ["name","fiscal_name","tax_number","registration_number"];
    for ( var i in items )
	{
	    if(Dom.get(items[i]).value!=Dom.get(items[i]).getAttribute('ovalue'))
		changes++; 
	} 

    
    if(changes==0){
	Dom.get('details_messages').innerHTML='';
	Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', 'none'); 
    }else if (changes==1){
	Dom.get('details_messages').innerHTML=changes+'<?=' '._('change')?>';
	Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', ''); 
    }else{
	Dom.get('details_messages').innerHTML=changes+'<?=' '._('changes')?>';
	Dom.setStyle(['save_details_button', 'cancel_save_details_button'], 'display', ''); 
    }


}


function init(){

    //   var ids = ["personal","pictures","work","other"]; 
    //	YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener('save_details_button', "click",save_details );

    YAHOO.util.Event.addListener('cancel_save_details_button', "click",cancel_save_details );
    var ids = ["name","fiscal_name","tax_number","registration_number"]; 

    YAHOO.util.Event.addListener(ids, "keyup", update_details);

} 
YAHOO.util.Event.onDOMReady(init);