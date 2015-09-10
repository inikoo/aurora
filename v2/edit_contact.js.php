<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');




//$edit_block='personal';
//if(isset($_REQUEST['edit'])){
//$valid_edit_blocks=array('personal','work','pictures','others');
//if(in_array($_REQUEST['edit'],$valid_edit_blocks))
//    $edit_block=$_REQUEST['edit'];
//}
$salutation="''";
$sql="select `Salutation` from kbase.`Salutation Dimension` where `Language Code`='en'";
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






if( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ){
    $contact_key=$_SESSION['state']['contact']['id'];
}else
    $contact_key=$_REQUEST['id'];
$scope_key=$contact_key;
$scope='contact';
if( isset($_REQUEST['scope'])    ){
    $scope=$_REQUEST['scope'];
}
if( isset($_REQUEST['scope_key'])    ){
    $scope=$_REQUEST['scope_key'];
}

print "var contact_key=$contact_key;";

$contact=new Contact($contact_key);





//$contact_id=$_SESSION['state']['contact']['id'];
//$contact=new contact($contact_id);

//$edit_block='personal';
//if(isset($_REQUEST['edit'])){
//$valid_edit_blocks=array('personal','work','pictures','others');
//if(in_array($_REQUEST['edit'],$valid_edit_blocks))
//    $edit_block=$_REQUEST['edit'];
//}


$addresses=$contact->get_addresses();


$address_data="\n";
$address_data.=sprintf('0:{"key":0,"country":"","country_code":"UNK","country_d1":"","country_d2":"","town":"","postal_code":"","town_d1":"","town_d2":"","fuzzy":"","street":"","building":"","internal":"","type":["Office"],"description":"","function":["Contact"] } ' );
 $address_data.="\n";
foreach($addresses as $index=>$address){
    $address->set_scope($scope,$scope_key);
    
    


    $type="[";
    foreach($address->get('Type') as $_type){
	$type.=prepare_mysql($_type,false).",";
    }
    $type.="]";
    $type=preg_replace('/,]$/',']',$type);
    
    $function="[";
    foreach($address->get('Function') as $value){
	$function.=prepare_mysql($value,false).",";
    }
    $function.="]";
    $function=preg_replace('/,]$/',']',$function);
    


  $address_data.="\n".sprintf(',%d:{"key":%d,"country":%s,"country_code":%s,"country_d1":%s,"country_d2":%s,"town":%s,"postal_code":%s,"town_d1":%s,"town_d2":%s,"fuzzy":%s,"street":%s,"building":%s,"internal":%s,"type":%s,"description":%s,"function":%s} ',
			
			 $address->id
			 ,$address->id
			 ,prepare_mysql($address->data['Address Country Name'],false)
			 ,prepare_mysql($address->data['Address Country Code'],false)
			 ,prepare_mysql($address->data['Address Country First Division'],false)
			 ,prepare_mysql($address->data['Address Country Second Division'],false)
			 ,prepare_mysql($address->data['Address Town'],false)
			 ,prepare_mysql($address->data['Address Postal Code'],false)
			 ,prepare_mysql($address->data['Address Town First Division'],false)
			 ,prepare_mysql($address->data['Address Town Second Division'],false)
			 ,prepare_mysql($address->data['Address Fuzzy'],false)
			 ,prepare_mysql($address->display('street',false),false)
			 ,prepare_mysql($address->data['Address Building'],false)
			 ,prepare_mysql($address->data['Address Internal'],false)
			 ,$type
			 ,prepare_mysql($address->data['Address Description'],false)
			 ,$function

			 );
  $address_data.="\n";

}





?>
    
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var Subject='Contact';
var Subject_Key=contact_key;


var Address_Data={<?php echo$address_data?>};




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
  //  var ids = ["personal","pictures","work","other"]; 
   // YAHOO.util.Event.addListener(ids, "click", change_block);
    




} 
YAHOO.util.Event.onDOMReady(init);