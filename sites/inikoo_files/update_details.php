<?php

include_once('ar_edit_contacts.php');



if($_REQUEST['submit'] == 'contact_details')
	update_contact_details();
elseif($_REQUEST['submit'] == 'set_address_main'){
	update_main_address($_REQUEST);
	header( "Location: address_book.php" ) ;
}
elseif($_REQUEST['submit'] == 'delete_address'){
	delete_address();
	header( "Location: address_book.php" ) ;
}
	
else
	print 'other';



	
	
function update_contact_details(){
	
$data=array();	
foreach($_REQUEST as $key=>$value){
	$data[$key]=array('value'=>$value, 'okey'=>$key);
}

$_REQUEST['values']=$data;

$out=edit_customer($_REQUEST);
print_r($out);
$warning_msg='';
foreach($out as $item){
	if($item['state']!=200){
		if(preg_match('/^Email could not be updated/', $item['msg']))
			$item['msg']='Email already Taken';
		$warning_msg.=$item['msg']."\n";
	}
}

header( "Location: client.php?warning_msg=$warning_msg" ) ;

}	
	
?>