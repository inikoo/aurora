<?php
include_once('class.Customer.php');
include_once('class.Telecom.php');
include_once('common.php');

function check_duplicates($customer, $telecom_type='Telephone', $customer_type='Customer'){
	$telecom_numbers=array();
	switch($telecom_type){
		case 'Telephone':
			if($customer->data["Customer Main Telephone Key"]){
				$telecom= new Telecom($customer->data["Customer Main Telephone Key"]);
				$telecom_numbers[$telecom->id]=$telecom->data['Telecom Plain Number'];
			}
		break;
		case 'Mobile':
			if($customer->data["Customer Main Mobile Key"]){
				$telecom= new Telecom($customer->data["Customer Main Mobile Key"]);
				$telecom_numbers[$telecom->id]=$telecom->data['Telecom Plain Number'];
			}
		break;
		case 'FAX':
			if($customer->data["Customer Main FAX Key"]){
				$telecom= new Telecom($customer->data["Customer Main FAX Key"]);
				$telecom_numbers[$telecom->id]=$telecom->data['Telecom Plain Number'];
			}
		break;
		case 'other_telephone':
			foreach($customer->get_other_telephones_data() as $key=>$value){
				$telecom_numbers[$key]=$value['number'];
			}
		break;
		case 'other_mobile':
			foreach($customer->get_other_mobiles_data() as $key=>$value){
				$telecom_numbers[$key]=$value['number'];
			}
		break;
		case 'other_fax':
			foreach($customer->get_other_faxes_data() as $key=>$value){
				$telecom_numbers[$key]=$value['number'];
			}
		break;
		
	}
/*
	$telecom_types=array('Telephone','FAX','Mobile');
	$telecom_numbers=array();
	foreach($telecom_types as $telecom_type){
		$telecom= new Telecom($customer->data["Customer Main $telecom_type Key"]);
		$telecom_numbers[$telecom_type][$telecom->id]=$telecom->data['Telecom Plain Number'];
	}
	
	foreach($customer->get_other_telephones_data() as $key=>$value){
		$telecom_numbers['Telephone'][$key]=$value['number'];
	}
	foreach($customer->get_other_mobiles_data() as $key=>$value){
		$telecom_numbers['Mobile'][$key]=$value['number'];
	}
	foreach($customer->get_other_faxes_data() as $key=>$value){
		$telecom_numbers['FAX'][$key]=$value['number'];
	}
*/	
	
	
	$warning_message_keys=array();
	//foreach($telecom_numbers as $telecom_number){
	foreach($telecom_numbers as $key=>$value){
		$sql=sprintf("select * from `Telecom Dimension` where `Telecom Plain Number`=%s and `Telecom Key`!=%d", prepare_mysql($value), $key);
		//print $sql;
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result)){
			$warning_message_keys[]=array('key'=>$key, 'val'=>$row['Telecom Key']);
		}
		
		//print_r( $warning_message_keys);
	}
	
	
	//}
	//print_r($customer->get_other_telephones_data());
	//print_r($warning_message_keys);
	//print 'xx';
	//$main_telephone_warning=false;
	foreach($warning_message_keys as $key=>$val){
		$main_telephone_warning[$val['key']]=false;
	}
	//print_r($main_telephone_warning);
	if($warning_message_keys){
		foreach($warning_message_keys as $key=>$warning_message_key){
			$sql=sprintf("select `Subject Key` from `Telecom Bridge` where `Telecom Key`=%d and `Subject Type`=%s", $warning_message_key['val'], prepare_mysql($customer_type));
			//print $sql;
			$result=mysql_query($sql);
			if($row=mysql_fetch_array($result)){
				$subject= new Customer($row['Subject Key']);
				$store=new Store($subject->data['Customer Store Key']);
				$main_telephone_warning[$warning_message_key['key']].=sprintf(", %s (%s) <a href=\"customer.php?id=%d\">%s</a> %s",_('Customer'),$store->data['Store Code'],$subject->id, $subject->get_formated_id(),$subject->data['Customer Name']);
			}
		}
		
	/*	
		if($main_telephone_warning){
		foreach($main_telephone_warning as $key=>$msg){
		
			$main_telephone_warning[$key]=preg_replace('/^, /','',$main_telephone_warning[$key]);
			$main_telephone_warning[$key]='<img style="cursor:pointer" title="Other Customers/Supplier has this telephone" src="art/icons/error.png" alt="warning"/> '.$main_telephone_warning[$key];
		}
		}
		*/
		//print_r($main_telephone_warning);
		return $main_telephone_warning;
	}
}



//print_r(check_duplicates(new Customer('70801')));


?>