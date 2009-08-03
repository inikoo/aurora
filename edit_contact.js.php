<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
include_once('../class.Contact.php');
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


?>
    
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var Country_List=[<?php echo$country_list?>];


var current_salutation='salutation<?php echo$contact->get('Salutation Key')?>';
var current_block='<?php echo$edit_block?>';
var old_salutation=current_salutation;




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
    
}







function init(){
    var ids = ["personal","pictures","work","other"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    




} 
YAHOO.util.Event.onDOMReady(init);