<?php
function mb_unserialize($serial_str) {
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
	return unserialize($out);
}
function parse_payment_method($method) {


	$method=_trim($method);
	//  print "$method\n";
	if ($method=='' or $method=='0')
		return 0;
	if (preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
		return 'Credit Card';

	//  print "$method\n";
	if (preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
		return 'Check';
	if (preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
		return 'Other';
	if (preg_match('/^(cash|casg|casn)$/i',$method))
		return 'Cash';
	if (preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
		return 'Paypal';
	if (preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
		return 'Bank Transfer';
	if (preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
		return 'Other';
	if (preg_match('/^(postal order)$/i',$method))
		return 'Other';
	if (preg_match('/^(Moneybookers)$/i',$method))
		return 'Other';


	return 'Unknown';

}
function filter_header_old($data) {
	foreach ($data as $key=>$value) {
		$data[$key]=_trim($value);
	}

	if (preg_match('/\d{2}-\d{2}-\d{2}/',$data['notes2']))
		$data['notes2']='';
	return $data;
}
function guess_tel($raw_tel,$country_id='',$city_id='') {
	if ($raw_tel=='')
		return false;
	$is_mobile=2; // 2 unknown 1 yes 0 no
	$icode='';
	$ncode='';
	$number='';
	$ext='';
	// fisrt try to see if it has an extension;
	$tel_ext=preg_split('/ext|#/i',$raw_tel);

	if (count($tel_ext)==2) {
		$ext=preg_replace('/[^0-9]/','',$tel_ext[1]);
	}

	$number=$tel_ext[0];
	// if (*) founf the numbers at the left could be  icodes and the number iside could be the ncode ane the number ate the rigth the numbner

	//  if(preg_match('/\(.*\)/i',$number,$possible_ncode)){
	//     $possible_ncode=preg_replace('/\(|\)/','', $possible_ncode[0]);
	//     if(preg_match('/^0*$/', $possible_ncode)){
	//       // forget it
	//     }else{
	//       $ncode=$possible_ncode;
	//     }
	//     $number_parts=preg_split('/\(.*\)/i',$number);
	//     $icode=$number_parts[0];
	//     $number=$number_parts[1];

	//   }



	//   // remove the internatinal code if found
	//   if($country_code=get_icode($country_id))
	//     $icode_match="/^\+?$country_code\s*/";
	//   else
	//     $icode_match='/^\+\d{1,3}\s+/';

	//   if(preg_match($icode_match,$number,$a_ncode)){
	//     $icode= $a_ncode[0];
	//     $number=str_replace($icode,'',$number);
	//   }
	//   //$number=preg_replace('/\[\d*\]/','',$number);

	$icode=preg_replace('/[^0-9]/','',$icode);
	$ncode=preg_replace('/[^0-9]/','',$ncode);
	$number=preg_replace('/[^0-9]/','',$number);
	$ext=preg_replace('/[^0-9]/','',$ext);

	if ($icode=get_icode($country_id)) {
		$regex_icode="/^0{0,2}$icode/";
		//    print "$regex_icode  xxxxxxxxxxxxx\n";
		$number=preg_replace($regex_icode,'',$number);
	}


	// country expcific

	switch ($country_id) {

	case(30)://UK
		if (preg_match('/^0845/',$number)) {
			$icode='';
			$ncode='0845';
			$number=preg_replace('/^0845/','',$number);
		}
		$number=preg_replace('/^0/','',$number);
		if (preg_match('/^7/',$number))
			$is_mobile=1;
		else
			$is_mobile=0;
		break;
	case(75)://Ireland
		if (preg_match('/^0?8(2|3|5|6|7|8|9)/',$number))
			$is_mobile=1;
		else
			$is_mobile=0;
		break;
	case(47)://Spain
	case(165)://France
		if (preg_match('/^0?6/',$number))
			$is_mobile=1;
		else
			$is_mobile=0;
		break;
	}


	$number=preg_replace('/[^\d]/','',$number);
	$telecom_data=array('icode'=>$icode,'ncode'=>$ncode,'number'=>$number,'ext'=>$ext,'is_mobile'=>$is_mobile);
	//print "$raw_tel\n";
	//print_r($telecom_data);
	// if(!($country_id==30 ))
	//  exit;
	return $telecom_data;

}
function get_icode($country_id) {
	$db =& MDB2::singleton();
	$sql=sprintf("select `Country Telephone Code` as tel_code from kbase.`Country Dimension`  where `Country Key`=%d",$country_id);
	$res=mysql_query($sql);
	if ($row=$res->fetchRow()) {
		if ($row['tel_code']!='')
			return $row['tel_code'];
	}else
		return '';
}
function country_id($address_id,$default=0) {
	$db =& MDB2::singleton();
	$sql=sprintf("select country_id from address_atom where address_id=%d ",$address_id);
	$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		return $row['country_id'];
	}else
		return $default;


}
function is_street($string) {
	if ($string=='')
		return false;

	$string=_trim($string);
	// if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

	if (preg_match('/\s+rd\.?\s*$|\s+road\s*$|^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))
		return true;
	if (preg_match('/[a-z\-\#\,]{1,}\s*\d/i',$string))
		return true;

	if (preg_match('/\d.*[a-z]{1,}/i',$string))
		return true;



	return false;
}
function is_internal($string) {
	if ($string=='')
		return false;
	// if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

	if (preg_match('/lot\s*(n-)?\s*\d|suite\s*\d|shop\s*\d|apt\s*\d/i',$string))
		return true;
	else
		return false;
}
function get_address_data($address_id) {
	$db =& MDB2::singleton();
	$sql=sprintf("select internal_address,building_address, street_address,town_d2,town_d1 ,country_d2,country_d1,town,postcode,country from address where id=%d",$address_id);
	//print "$sql\n";
	$res=mysql_query($sql);
	if (!$address_data=$res->fetchRow())
		return false;
	$sql=sprintf("select address_id,town_d2_id,town_d1_id,country_d2_id,country_d1_id,town_id,country_id from address_atom where address_id=%d",$address_id);
	//print "$sql\n";

	$res=mysql_query($sql);
	if (!$address_data_atom=$res->fetchRow())
		return false;

	//  print "===========>\n";
	//print_r($address_data);
	// print "_==========>\n";
	return array_merge($address_data, $address_data_atom);

}
function insert_address($address_data) {
	$db =& MDB2::singleton();

	// print_r($address_data);

	//$pc=addslashes($address_data['postcode']);
	$internal_address=($address_data['internal_address']!=''?'"'.addslashes(trim(mb_ucwords($address_data['internal_address']))).'"':'null');
	$building_address=($address_data['building_address']!=''?'"'.addslashes(mb_ucwords($address_data['building_address'])).'"':'null');
	$street_address=($address_data['street_address']!=''?'"'.addslashes(mb_ucwords($address_data['street_address'])).'"':'null');


	$town_id=$address_data['town_id'];
	$country_d1_id=$address_data['country_d1_id'];
	$country_d2_id=$address_data['country_d2_id'];
	$town_d2_id=$address_data['town_d2_id'];
	$town_d1_id=$address_data['town_d1_id'];

	if ($address_data['town']!='')
		$town='"'.addslashes(mb_ucwords($address_data['town'])).'"';
	else {
		$town= 'null';
		if ($address_data['town_id']==0)
			$town_id='null';
	}

	if ($address_data['country_d1']!='')
		$country_d1='"'.addslashes(mb_ucwords($address_data['country_d1'])).'"';
	else {
		$country_d1= 'null';
		if ($address_data['country_d1_id']==0)
			$country_d1_id='null';
	}



	if ($address_data['country_d2']!='')
		$country_d2='"'.addslashes(mb_ucwords($address_data['country_d2'])).'"';
	else {
		$country_d2= 'null';
		if ($address_data['country_d2_id']==0)
			$country_d2_id='null';
	}
	if ($address_data['town_d2']!='')
		$town_d2='"'.addslashes(mb_ucwords($address_data['town_d2'])).'"';
	else {
		$town_d2= 'null';
		if ($address_data['town_d2_id']==0)
			$town_d2_id='null';
	}
	if ($address_data['town_d1']!='')
		$town_d1='"'.addslashes(mb_ucwords($address_data['town_d1'])).'"';
	else {
		$town_d1= 'null';
		if ($address_data['town_d1_id']==0)
			$town_d1_id='null';
	}

	$postcode=($address_data['postcode']!=''?'"'.addslashes($address_data['postcode']).'"':'null');
	$country=mb_ucwords($address_data['country']);
	$country_id=$address_data['country_id'];



	$sql=sprintf("insert into address (internal_address,building_address,street_address,town_d2,town_d1,town,country_d2,postcode,country_d1,country) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,'%s')",
		$internal_address,$building_address,$street_address,$town_d2,$town_d1,$town,$country_d2,$postcode,$country_d1,$country
	);
	//mysql_query($sql);
	//$address_id = $db->lastInsertID();

	mysql_query($sql);
	$address_id=mysql_insert_id();

	$sql=sprintf("insert into address_atom (address_id,town_d2_id,town_d1_id,town_id,country_d2_id,country_d1_id,country_id) values (%d,%s,%s,%s,%s,%s,%d)",
		$address_id,$town_d2_id,$town_d1_id,$town_id,$country_d2_id,$country_d1_id,$country_id
	);
	//print "$sql\n";
	//mysql_query($sql);
	mysql_query($sql);



	return $address_id;

}
function get_address_metadata($address_id) {
	$db =& MDB2::singleton();
	global $_contact_tipo;
	global $_address_tipo;
	$sql=sprintf("select contact_id,contact.tipo as c_tipo,address2contact.tipo as address_tipo  from address2contact left join contact on (contact_id=contact.id) where address_id=%d",$address_id);
	// print "$sql\n";
	$res=mysql_query($sql);
	$metadata=array();
	while ($row=$res->fetchRow()) {

		$metadata[]=array(
			'contact_tipo'=>$_contact_tipo[$row['c_tipo']],
			'address_tipo'=>$_address_tipo[$row['address_tipo']],
			'contact_id'=>$row['contact_id'] ,
			'contact_tipo_id'=>$row['c_tipo'],
			'address_tipo_id'=>$row['address_tipo'],
		);
		return $metadata;


	}
	return $metadata;
}
function update_address($address_id,$address_data,$date_index='',$note='') {
	$db =& MDB2::singleton();


	$address_keys=array('postcode','internal_address','building_address','street_address','town_d2','town_d1','country_d2','country_d1','country');
	$address_atom_keys=array('town_d2_id','town_d1_id','country_d2_id','country_d1_id','country_id');


	$old_values=get_address_data($address_id);
	$array_metadata=get_address_metadata($address_id);

	// print_r($old_values);
	//print_r($address_data);
	$update_sql='';
	$values=array();
	foreach ($address_keys as $key) {
		//print $old_values[$key]."z".$address_data[$key]."zz\n";
		if (strcmp($old_values[$key],$address_data[$key])) {

			$values[]=array('old'=>$old_values[$key],'new'=>$address_data[$key]);
			$array_history_sql[]="insert into history_item (history_id,columna,old_value,new_value) values (%d,'$key',%s,%s)";
			$update_sql.=" $key=".prepare_mysql($address_data[$key])." ,";

		}
	}
	//print_r($values);


	if (count($values)>0) {
		$update_sql=preg_replace('/,$/','',$update_sql);
		$sql=sprintf("update address  set %s where id=%d",$update_sql,$address_id);
		//  print "$sql\n";
		//mysql_query($sql);
		mysql_query($sql);
		foreach ($array_metadata as $metadata) {

			$recipient_id=$metadata['contact_id'];
			//     if(is_numeric($customer_id= get_customer_from_contact($recipient_id))){
			//  $recipient_id=$customer_id;
			//  $sujeto='Customer';
			//       }else
			//  $sujeto='Contact';



			$sujeto='Contact';




			$sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('UPD','%s',%d,'%s',%d,%s)",$sujeto,$recipient_id,$metadata['address_tipo'],$address_id,prepare_mysql_date($date_index));
			//       print "$sql\n";
			// mysql_query($sql);
			//$history_id=$db->lastInsertID();
			mysql_query($sql);
			$history_id=mysql_insert_id();
			if ($metadata['address_tipo']<4) {
				foreach ($array_history_sql as $key=>$history_sql) {
					$sql=sprintf($history_sql,$history_id,prepare_mysql($values[$key]['old']),prepare_mysql($values[$key]['new']));
					//print "$sql\n";
					// mysql_query($sql);
					mysql_query($sql);
				}
			}
		}
	}



	//  $internal_address=($address_data['internal_address']!=''?'"'.addslashes(trim($address_data['internal_address'])).'"':'null');
	//  $building_address=($address_data['building_address']!=''?'"'.addslashes($address_data['building_address']).'"':'null');
	//  $street_address=($address_data['street_address']!=''?'"'.addslashes($address_data['street_address']).'"':'null');


	// if($address_data['town']!='')
	//    $town='"'.addslashes($address_data['town']).'"';
	//  else{$town= 'null';if($town_id==0)$town_id='null';}

	//  if($address_data['town_d1']!='')
	//    $town_d1='"'.addslashes($address_data['town_d1']).'"';
	//  else{$town_d1= 'null';if($town_d1_id==0)$town_d1_id='null';}

	//  if($address_data['country_d2']!='')
	//    $country_d2='"'.addslashes($address_data['country_d2']).'"';
	//  else{$country_d2= 'null';if($country_d2_id==0)$country_d2_id='null';}


	// if($address_data['country_d1']!='')
	//    $country_d1='"'.addslashes($address_data['country_d1']).'"';
	//  else{$country_d1= 'null';if($country_d1_id==0)$country_d1_id='null';}


	//  if($address_data['town_d2']!='')
	//    $town_d2='"'.addslashes($address_data['town_d2']).'"';
	//  else{$town_d2= 'null';if($town_d2_id==0)$town_d2_id='null';}




	//   $country=$address_data['country'];

	//  // Check what is different and act accordinly
	//  $old_address_data=get_address_data($address_id);

	//  $update=array();
	//  if($old_address_data['country']!=$country)$update['country']=true;
	//  if($old_address_data['country_d1']!=$country_d1)$update['country_d1']=true;
	//  if($old_address_data['country_d2']!=$country_d2)$update['country_d2']=true;
	//  if($old_address_data['town']!=$town)$update['town']=true;
	//  if($old_address_data['town_d2']!=$town_d2)$update['town_d2']=true;
	//  if($old_address_data['town_d1']!=$town_d1)$update['division']=true;
	//  if($old_address_data['postcode']!=$country_d1)$update['division']=true;
	//  if($old_address_data['street_address']!=$street_address)$update['street_address']=true;
	//  if($old_address_data['build_address']!=$build_address)$update['build_address']=true;
	//  if($old_address_data['internal_address']!=$internal_address)$update['internal_address']=true;





	//  $sql=sprintf("update  address set internal_address=%s,building_address=%s,street_address=%s,town=%s,town_d2=%s,town_d1=%s,country_d2=%s,country_d1=%s,postcode=%s,country=%d where id=%d",
	//        $internal_address,$building_address,$street_address,$town,$town_d2,$town_d1,$country_d2,$country_d1,$postcode,$country,$address_id
	//        );
	//  mysql_query($sql);
	//  $sql=sprintf("update  address_atom set town_id=%s,town_d2_id=%s,town_d1_id=%s,country_d2=%s,country_d1_id=%s,country_id=%d where address_id=%d",$town_id,$town_d2_id,$town_d1_id,$country_d2_id,$country_d1_id,$country_id,$address_id);
	//  mysql_query($sql);

	//  if(count($update)>0){
	//    $note=($note==''?'null':$note);
	//    $sql=sprintf("insert into history (tipo,sujeto,objeto,date,note) values (2,'contact','address','%s',%s)",$date,$ntre);
	//      print "$sql\n";
	//     mysql_query($sql);
	//     $history_id = $db->lastInsertID();
	//  }

	//  foreach($update as  $key => $value){
	//     $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s','%s','%s')"
	//    ,$history_id,$old_address_data[$key],$$key);
	//     print "$sql\n";
	//     mysql_query($sql);
	//  }

	// exit("update address\n");
}
function associate_address($address_id,$contact_id,$tipo,$description='',$date_index='',$history=true) {

	// print "$address_id  ++\n";

	global $_contact_tipo;
	global $_address_tipo;
	$db =& MDB2::singleton();

	$sql=sprintf("insert into address2contact  (address_id,contact_id,tipo,description) values (%d,%d,%d,%s)",$address_id,$contact_id,$tipo,prepare_mysql($description));
	// mysql_query($sql);
	mysql_query($sql);

	if ($history) {

		if ($date_index=='')
			exit("no date on hisroty associate address\n");
		//rint "xxxx $contact_id "   ;
		$contact_data=get_contact_data($contact_id);
		if (!$contact_data) {
			print "Notice: error no contact data in associate address $contact_id\n";
			exit;
		}
		$contact_tipo=$_contact_tipo[$contact_data['tipo']];


		//     if(is_numeric($customer_id= get_customer_from_contact($contact_id))){
		//  $recipient_id=$customer_id;
		//  $sujeto='Customer';
		//     }else{
		//  $sujeto='Contact';
		//  $recipient_id=$contact_id;
		//     }

		$sujeto='Contact';
		$recipient_id=$contact_id;

		$sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','%s',%d,'%s',%d,%s)",$sujeto,$recipient_id,$_address_tipo[$tipo],$address_id,prepare_mysql_date($date_index));
		// print "$sql\n";
		//mysql_query($sql);
		//$history_id=$db->lastInsertID();
		mysql_query($sql);
		$history_id=mysql_insert_id();


		$address=display_full_address($address_id);
		$sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,$_address_tipo[$tipo],prepare_mysql($address));
		// print "$sql\n";
		// mysql_query($sql);
		mysql_query($sql);
		// exit("address add\n");
	}


	return true;

}
function get_principal_address($tipo,$contact_id) {
	$db =& MDB2::singleton();

	if ($tipo=='del_address' or $tipo=='bill_address') {
		$customer_id=get_customer_from_contact($contact_id);
		if ($tipo=='bill_address')
			$objeto='main_bill_address';
		elseif ($tipo=='del_address')
			$objeto='main_del_address';
		$sql=sprintf("select %s as principal from customer where id=%d",$objeto,$customer_id);
		$res=mysql_query($sql);
		if ($row=$res->fetchRow()) {
			return $row['principal'];
		}
	}elseif ($tipo=='main_address' or $tipo=='shop_address') {
		$sql=sprintf("select main_address as principal from contact where id=%d",$contact_id);
		$res=mysql_query($sql);
		if ($row=$res->fetchRow()) {
			return $row['principal'];
		}

	}

	return false;

}
function set_principal_address($recipient_id,$tipo,$address_id,$date_index='',$history=true,$update=false) {
	$db =& MDB2::singleton();
	global $_address_tipo;
	global $_contact_tipo;
	global $myconf;

	// check if it actually changed
	if ($tipo==2)
		$princial_address=get_principal_address('bill_address',$recipient_id);
	elseif ($tipo==3)
		$princial_address=get_principal_address('del_address',$recipient_id);
	elseif ($tipo==1)
		$princial_address=get_principal_address('main_address',$recipient_id);

	//  print "$address_id= "
	if ($address_id==$princial_address)
		return;


	$tipo_history=($update?'CHG':'NEW');

	//  print "$tipo    $recipient_id  $address_id history  $history  \n";

	if ($address_id=='') {
		print "warning no address_id trying to set principal address\n";

	}

	if ($tipo==2 or $tipo==3) {
		$col=($tipo==2?'main_bill_address':'main_del_address');
		$col2=($tipo==2?'Main Billing Address':'Main Delivery Address');

		$customer_id=get_customer_from_contact($recipient_id);
		if (!$customer_id) {
			exit("$recipient_id  $col Error nop customer id where trying to update del or bill address\n");
		}

		$sql=sprintf("select %s from customer where id=%d",$col,$customer_id);
		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$old_address_id=$row[$col];
			if ($old_address_id=='')
				$change=false;
			else
				$change=true;
			if ($old_address_id!=$address_id) {

				$contact_data=get_customer_data($customer_id);
				$old_data=$contact_data[$col];

				$sql=sprintf("update customer set %s=%d where id=%d",$col,$address_id,$customer_id);
				mysql_query($sql);
				if ($change and $history) {
					$sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('%s','Customer',%d,%s,%d,%s)",$tipo_history,$customer_id,prepare_mysql($col2),$address_id,prepare_mysql_date($date_index));

					mysql_query($sql);
					$history_id=mysql_insert_id();


					$sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,%s,%s,%s)",$history_id,prepare_mysql($col2),prepare_mysql($old_data),$address_id);

					// mysql_query($sql);
					mysql_query($sql);
				}
			}
		}
	}else if ($tipo==1) {
			$col='main_address';
			$col2='Main Address';

			$contact_data=get_contact_data($recipient_id);
			$old_data=$contact_data[$col];

			$sql=sprintf("update contact set %s=%d where id=%d",$col,$address_id,$recipient_id);
			//  mysql_query($sql);
			mysql_query($sql);
			if ($history) {

				//     if(is_numeric($customer_id= get_customer_from_contact($recipient_id))){
				//  $recipient_id=$customer_id;
				//  $sujeto='Customer';
				//       }else
				//  $sujeto='Contact';
				$sujeto='Contact';

				$sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values (%s,%s,%d,%s,%d,%s)",prepare_mysql($tipo_history),prepare_mysql($sujeto),$recipient_id,prepare_mysql($col2),$address_id,prepare_mysql_date($date_index));
				//  print "qqqqqqqqq $sql\n";
				//mysql_query($sql);
				//$history_id=$db->lastInsertID();
				mysql_query($sql);
				$history_id=mysql_insert_id();



				$sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,%s,%s,%s)",$history_id,prepare_mysql($col2),prepare_mysql($old_data),$address_id);
				//print "qqqqqqqqq $sql\n";
				//mysql_query($sql);
				mysql_query($sql);
			}
		}




	//  exit("associate xxxxxxxxxsaddress\n");

}
function insert_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$file_date) {
	$db =& MDB2::singleton();




	$sql=sprintf("insert into orden_file (order_id,filename,checksum,checksum_header,checksum_products,date) values (%d,'%s','%s','%s','%s','%s')",$order_id,$filename,$checksum,$checksum_header,$checksum_products,date("Y-m-d H:i:s",strtotime('@'.$file_date)));
	// print "$sql\n";

	// mysql_query($sql);
	mysql_query($sql);
}
function update_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$file_date) {
	$db =& MDB2::singleton();
	$sql=sprintf("update orden_file set order_id=%d ,checksum='%s',checksum_header='%s',checksum_products='%s',date='%s' where filename=%s",$order_id,$checksum,$checksum_header,$checksum_products,date("Y-m-d H:i:s",strtotime('@'.$file_date)),prepare_mysql($filename));
	//    print "$sql\n";

	// mysql_query($sql);
	mysql_query($sql);
}
function get_payment_method($method) {


	$method=_trim($method);
	//  print "$method\n";
	if ($method=='' or $method=='0')
		return 0;
	if (preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
		return 2;

	//  print "$method\n";
	if (preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
		return 4;
	if (preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
		return 5;
	if (preg_match('/^(cash|casg|casn)$/i',$method))
		return 1;
	if (preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
		return 6;
	if (preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
		return 3;
	if (preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
		return 7;
	if (preg_match('/^(postal order)$/i',$method))
		return 8;
	if (preg_match('/^(Moneybookers)$/i',$method))
		return 9;

	print "Warning: unnkown pay method $method \n";
	return 0;

}
function read_products($raw_product_data,$y_map) {

	if (isset($y_map['no_reorder']) and $y_map['no_reorder'])
		$re_order=false;
	else
		$re_order=true;

	if (isset($y_map['no_price_bonus']) and $y_map['no_price_bonus'])
		$no_price_bonus=true;
	else
		$no_price_bonus=false;


	$transactions=array();
	foreach ($raw_product_data as $raw_data) {
		foreach ($y_map as $key=>$value) {
			$_data=$raw_data[$value];
			if (preg_match('/order|reorder|bonus/',$key))
				if ($_data=='')$_data=0;

				if (!$re_order and ($key=='reorder' or $key=='rrp')  )
					$_data=0;

				if ($no_price_bonus) {
					if ($key=='order' and $transaction['price']==0)
						$_data=0;
					if ($key=='bonus' and $transaction['price']==0)
						$_data=$_data+ $raw_data[$y_map['order']]  ;


				}
			if ($key=='supplier_product_code' and $raw_data[$y_map['supplier_code']]=='AW'   ) {
				$_data=$raw_data[$y_map['code']];
			}

			$transaction[$key]=$_data;
		}


		if ($transaction['units']==1 or $transaction['units']=='')
			$transaction['name']=$transaction['description'];
		else
			$transaction['name']=trim($transaction['units'].'x '.$transaction['description']);


		$transaction['fob']=$raw_data['fob'];
		$transactions[]=$transaction;
	}
	// print_r($transactions);
	return $transactions;
}
function set_transactions($transactions,$order_id,$tipo_order,$parent_order_id,$date_index,$record_out_stock=true,$tax_code='S') {
	$db =& MDB2::singleton();


	$date_index=str_replace("'",'',$date_index);


	$my_total_net=0;
	$my_total_rrp=0;
	$my_total_items_order=0;
	$my_total_items_reorder=0;
	$my_total_items_bonus=0;
	$my_total_items_free=0;
	$my_total_items_dispatched=0;
	$value_outstoke=0;
	$credit_value=0;

	//   print_r($transactions);
	foreach ($transactions as $transaction) {


		if ($transaction['fob'])
			$promotion_id=1;
		else
			$promotion_id='NULL';


		if ($transaction['order']=='')$transaction['order']=0;
		if ($transaction['reorder']=='')$transaction['reorder']=0;
		if ($transaction['bonus']=='')$transaction['bonus']=0;
		if ($transaction['discount']=='')$transaction['discount']=0;

		$my_items_to_charge=$transaction['order']-$transaction['reorder'];
		$my_items_to_charge_value=$my_items_to_charge*($transaction['price'] * (1-$transaction['discount']));
		//  print_r($transaction);
		$my_items_to_dispach=$my_items_to_charge+$transaction['bonus'];
		if (preg_match('/credit/i',$transaction['code'])) {
			//     $transaction['credit']=-abs( $transaction['credit']);
			$credit_parent=$transaction['description'];
			$my_items_to_charge_value=$transaction['credit'];
		}

		$my_total_rrp+=$my_items_to_charge*($transaction['rrp']*$transaction['units']);
		$my_total_net+=$my_items_to_charge_value;
		//   print $transaction['code']." caca $my_total_net =$my_items_to_charge_value \n ";
		$my_total_items_order+=$transaction['order'];
		$my_total_items_reorder=$transaction['reorder'];
		$my_total_items_bonus+=$transaction['bonus'];
		$my_total_items_dispatched+=$my_items_to_dispach;

		if ($transaction['discount']==1)
			$my_total_items_free+=$my_total_items_dispatched;
		$tipo_t=1;
		if ($transaction['discount']==1)
			$tipo_t=2;


		// Credits




		if (preg_match('/credit/i',$transaction['code'])) {
			$parent_id='';
			$parent='xxxx';
			$tipo=0;
			$parent_note=$transaction['description'];

			if (preg_match('/^Credit owed for order no\.:/i',$parent_note)) {
				$tipo=1;
				if (preg_match('/\d{4,5}/',$parent_note,$thismatch)) {
					$parent=$thismatch[0];
				}

			}


			$sql=sprintf("select id from orden where public_id=%d",$parent);
			//  print "$parent_note $sql\n";
			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$parent_id=$row['id'];
			}
			global $tax_rate;
			$tax_factor=$tax_rate;

			$credit_value_net=-$transaction['price'];
			if ($tax_code=='S')
				$credit_value_tax=$tax_factor*$credit_value_net;
			else
				$credit_value_tax=0;

			$tipo=2;// Debit done
			$parent_note=preg_replace('/^Credit owed for order no..$/i','',$parent_note);
			//  if(is_numeric($parent_id)){




			$sql=sprintf("insert into debit (tipo,order_affected_id,order_original_id,note,value_net,value_tax,date_done,tax_code) value (%d,%d,%s,%s ,'%.2f','%.2f',%s,%s)"
				,$tipo
				,$order_id
				,prepare_mysql($parent_id)
				,prepare_mysql($parent_note)
				,$credit_value_net
				,0
				,prepare_mysql_date($date_index)
				,prepare_mysql($tax_code));
			// print "$sql\n";
			mysql_query($sql);// mysql_query($sql);


		}



		//$sql=sprintf("update orden set debits='%.2f' where id=%d",$credit_value,$order_id);
		//mysql_query($sql);//mysql_query($sql);



		// do a todo_debit
		// $sql=sprintf("insert into todo_debit (tipo,order_affected_id,note,value,date_creation,date_done) value (%d,%d,%s,%.2f,%s)",$tipo,$order_id,prepare_mysql($parent_note),$credit_value,$date_index);
		// print "$sql\n";
		// mysql_query($sql);// mysql_query($sql);


		//      }


		//}

		$is_cash_promo=false;
		if (preg_match('/Promo$/i',$transaction['code']) and ($transaction['price']*$transaction['order']-$transaction['reorder']+$transaction['bonus'])<0) {
			$sql=sprintf("insert into debit (tipo,order_affected_id,order_original_id,note,value_net,value_tax,date_done,tax_code) value (%d,%d,%s,%s ,'%.2f','%.2f',%s,%s)"
				,6
				,$order_id
				,'NULL'
				,prepare_mysql($transaction['code'])
				,$transaction['price']*$transaction['order']-$transaction['reorder']+$transaction['bonus']
				,0
				,prepare_mysql_date($date_index)
				,prepare_mysql($tax_code));
			//print "$sql\n";
			$is_cash_promo=true;
			mysql_query($sql);// mysql_query($sql);
		}


		//print $transaction['code']."\n";
		if ($tipo_order==6 or $tipo_order==7 or $tipo_order==8) {
			if (is_numeric($parent_order_id))
				$original_order=$parent_order_id;
			else
				$original_order=0;
		}else
			$original_order='NULL';


		if (preg_match('/^PI-/i',$transaction['code']))
			$sql=sprintf("select id from product where description='%s' and code='%s'",addslashes($transaction['description']),addslashes($transaction['code']));
		else
			$sql=sprintf("select id from product where code='%s'",addslashes($transaction['code']));


		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			// Found Product
			$product_id=$row['id'];

			//      if(!is_numeric($order_id) or $order_id<1)
			// exit('Error order id can no be this');


			$sql=sprintf("insert into transaction (promotion_id,tipo,order_id,product_id,ordered,dispatched,discount,charge,tax_code,original_order_id) value (%s,%d,%d,%d,%.2f,%.2f,%.3f,%.2f,%s,%s)",$promotion_id,$tipo_t,$order_id,$product_id,$transaction['order'],$my_items_to_dispach,$transaction['discount'],$my_items_to_charge_value,prepare_mysql($tax_code),$original_order);

			//print "x $sql\n";
			//exit;
			mysql_query($sql);

			if ($transaction['reorder']>0 and $record_out_stock) {
				$value_outstoke=$value_outstoke+($transaction['reorder'] * ($transaction['price'] * (1-$transaction['discount'])));
			}

			if ($transaction['reorder']>0) {
				$sql=sprintf("insert into outofstock (order_id,product_id,qty,status) value (%d,%d,%.2f,%s)",$order_id,$product_id,$transaction['reorder'],($record_out_stock?1:2));
				mysql_query($sql);

			}

			if ($transaction['bonus']>0  or $transaction['discount']==1) {
				$qty=$transaction['bonus'];
				if ($transaction['discount']==1)
					$qty+=$my_items_to_charge;
				$sql=sprintf("insert into bonus (order_id,product_id,qty,promotion) value (%d,%d,%.2f,%d)",$order_id,$product_id,$qty,$promotion_id);
				//  print "$sql\n";
				mysql_query($sql);
			}
		}else {

			if (!preg_match('/credit/i',$transaction['code'])   and !$is_cash_promo) {

				$sql=sprintf("insert into todo_transaction (promotion_id,code,description,order_id,ordered,reorder,bonus,price,discount,tax_code,original_order_id) value (%s,'%s','%s',%d,  %.2f,%.2f,%.2f,%.2f,%.2f,%s,%s)",$promotion_id,addslashes($transaction['code']),addslashes($transaction['description']),$order_id,$transaction['order'],$transaction['reorder'],$transaction['bonus'],$transaction['price'],$transaction['discount'],prepare_mysql($tax_code),$original_order);
				//  print "x $sql\n";
				mysql_query($sql);
			}


		}








	}

	$sql=sprintf("update orden set outofstock='%.2f' where id=%d",$value_outstoke,$order_id);
	mysql_query($sql);







	// Blamce the originals

	if (is_numeric($parent_order_id)) {
		$debit_value_net=0;
		$debit_value_tax=0;
		$sql=sprintf("select value_net,value_tax from debit where order_original_id=%d",$parent_order_id);
		// print "$sql\n";
		$result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$debit_value_net+=$row['value_net'];
			$debit_value_tax+=$row['value_tax'];
		}
		$sql=sprintf("select total,net,tax from orden where id=%d",$parent_order_id);

		$result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$balance_net=$row['net']+$debit_value_net;
			$balance_tax=$row['tax']+$debit_value_tax;
			$balance_total=$row['total']+$debit_value_net+$debit_value_tax;

			$sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$parent_order_id);
			mysql_query($sql);//mysql_query($sql);
		}

	}

	// Balance this one


	// money due to cash promotions

	$debit_value_net=0;
	$debit_value_tax=0;

	$sql=sprintf("select value_net,value_tax from debit where (tipo!=6 and tipo!=5) and order_affected_id=%d",$order_id);
	$result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$debit_value_net-=$row['value_net'];
		$debit_value_tax-=$row['value_tax'];
	}
	$sql=sprintf("select total,net,tax from orden where id=%d",$order_id);

	$result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {


		$balance_net=$row['net']+$debit_value_net;
		$balance_tax=$row['tax']+$debit_value_tax;
		$balance_total=$row['total']+$debit_value_net+$debit_value_tax;

		$sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$order_id);

		//    print "$sql\n";
		mysql_query($sql);//mysql_query($sql);
	}






}
function setup_contact($act_data,$header_data,$date_index) {
	$co='';
	$header_data['country_d2']='';
	$header_data['country']='';
	$header_data['country_d1']='';

	$new_customer=false;



	$this_is_order_number=$header_data['history'];
	if (!is_numeric($this_is_order_number)) {
		//    print "Warning history not numeric\n";
		$this_is_order_number=1;

	}

	//  print_r($header_data);
	//  print_r($act_data);

	if (preg_match('/cash sale/i',$header_data['trade_name'])) {

		if ($header_data['address1']=='' and$header_data['address2']=='' and $header_data['address3']=='' and $header_data['city']=='' and $header_data['postcode']==''  and isset($act_data['contact'])
		) {



			$staff_name=$act_data['contact'];

			//$staff=new Staff('alias',$staff_name);
			//$staff_id=$staff->id;
			$staff_id=get_user_id($staff_name,'' , '',false);
			if (count($staff_id)==1 and $staff_id[0]!=0 ) {
				print "Staff $staff_name  sale\n";
				$header_data['address1']=$act_data['contact'];
			}
			unset($act_data);

		}


		$staff_name=$header_data['address1'];
		$staff_id=get_user_id($staff_name);

		//    $staff=new Staff('alias',$staff_name);
		//$staff_id=$staff->id;

		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			print "Staff sale\n";
			unset($act_data);
		}
	}


	$skip_del_address=false;
	$mob_data=false;
	$tel_data=false;
	$fax_date=false;
	$email_data=false;

	if (isset($header_data['phone'])  and $header_data['phone']=='0'  )
		$header_data['phone']='';
	if (isset($header_data['postcode'])  and $header_data['postcode']=='0'  )
		$header_data['postcode']='';

	if (!isset($act_data) or count($act_data)==0) {


		$email='';
		$tel=$header_data['phone'];
		if (preg_match('/[a-z0-9\.\-]+\@[a-z0-9\.\-]+/',$header_data['phone'],$match)) {
			$email=$match[0];
			$tel=preg_replace("/$email/",'',$header_data['phone']);
		}
		$country='Spain';
		$postalcode=$header_data['postcode'];
		if (preg_match('/^Espa.a \d+$/i',$header_data['postcode'])) {
			$tmp=preg_split('/\s/',$header_data['postcode']);
			$country=$tmp[0];
			$postalcode=$tmp[1];
		}



		print "order without act\n";
		$skip_del_address=true;
		$act_data['name']=$header_data['trade_name'];
		$act_data['contact']=$header_data['customer_contact'];
		$act_data['a1']=$header_data['address1'];
		$act_data['a2']=$header_data['address2'];
		$act_data['postcode']=$postalcode;
		$act_data['country_d2']='';
		$act_data['a3']='';
		$act_data['town']=$header_data['city'];
		$act_data['tel']=$tel;
		$act_data['fax']='';
		$act_data['mob']='';
		$act_data['source']='';
		$act_data['act']='';
		$act_data['email']=$email;
		$act_data['country']=$country;

		$act_data['town_d1']='';
		$act_data['town_d2']='';



		//print_r($header_data);
		//exit;

		if (preg_match('/sale - Philip|staff|staff order|cash sale|staff sale|cash - sale/i',$header_data['trade_name']) or
			preg_match('/staff|staff sale|cash sale/i',$header_data['city']) or
			preg_match('/staff|staff sale|cash sale/i',$header_data['address1']) or
			preg_match('/staff|staff sale|cash sale/i',$header_data['address2']) or
			preg_match('/staff|staff sale|cash sale/i',$header_data['address3']) or
			preg_match('/^staff$|staff sale/i',$header_data['notes']) or

			preg_match('/staff|staff sale|cash sale/i',$header_data['postcode'])) {
			//print "cash\n";
			// Chash tipe try to get staff name
			if ($header_data['address1']=='Al & Bev')
				$header_data['address1']='Bev';

			$regex='/staff orders?|staff|sales?|cash|\-|:|Mark postage to France/i';

			$header_data['city']=_trim(preg_replace($regex,'',$header_data['city']));
			$header_data['postcode']=_trim(preg_replace($regex,'',$header_data['postcode']));
			$header_data['trade_name']=_trim(preg_replace($regex,'',$header_data['trade_name']));
			$header_data['address1']=_trim(preg_replace($regex,'',$header_data['address1']));
			$header_data['address2']=_trim(preg_replace($regex,'',$header_data['address2']));
			$header_data['address3']=_trim(preg_replace($regex,'',$header_data['address3']));
			$header_data['customer_contact']=_trim(   preg_replace($regex,'',$header_data['customer_contact'])      );
			$header_data['phone']=_trim(preg_replace($regex,'',$header_data['phone']));


			if ($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']!='' and $header_data['customer_contact']=='' )
				$header_data['address1']=$header_data['city'];
			if ($header_data['address1']=='' and $header_data['postcode']!='' and $header_data['city']==''   and $header_data['customer_contact']=='' )
				$header_data['address1']=$header_data['postcode'];
			if ($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']==''  and $header_data['customer_contact']!=''  )
				$header_data['address1']=$header_data['customer_contact'];
			if ($header_data['address1']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']!='')
				$header_data['address1']=$header_data['trade_name'];
			if ($header_data['address1']=='' and $header_data['address2']!='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
				$header_data['address1']=$header_data['address2'];
			if ($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']!='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
				$header_data['address1']=$header_data['address3'];
			if ($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['notes']!=''       and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='') {
				// Unkown

				$header_data['address1']=$header_data['notes'];
			}


			if ($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='') {
				// Unkown
				// Create unknowen customer
				// $customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
				//return array(false,$customer_id,false,false,false,true,$co);
			}




			if ($header_data['address1']!='') {
				$staff_name=$header_data['address1'];

				$staff_id=get_user_id($staff_name);

				// $staff=new Staff('alias',$staff_name);

				// print "$staff_name\n";
				//   print_r($staff_id);
				//   exit;
				if (count($staff_id)==1 and $staff_id[0]!=0 ) {


					$staff_id=$staff_id[0];
					$staff=new Staff('id',$staff_id);
					//    $staff_data=get_staff_data($staff_id);
					//    //print_r($staff_data);

					$act_data['name']='Ancient Winsdom Staff';
					$act_data['contact']=$staff->data['Staff Name'];
					//    // print_r(get_contact_data($contact_id));
					//    // exit;
					//    if(!$staff_data['customer_id']){
					//      $customer_id = insert_customer($contact_id,array(9,1,2,3,7,10),$date_index,($this_is_order_number==1?true:false));
					//      $new_customer=true;
					//    }else{
					//      $customer_id = $staff_data['customer_id'];
					//      $new_customer=false;
					//    }
					//    return array($contact_id,$customer_id,false,false,false,$new_customer,$co);




				}else {
					// print $staff_name;
					if (preg_match('/|maureen|church|Parcel Force Driver|sarah|Money in Petty|church|Parcel Force Driver|craig|malcol|Joanne/i',$staff_name)) {
						// $customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
						// return array(false,$customer_id,false,false,false,true,$co);


					}







				}

			}


		}





		// Try to fix it
		if (!isset($header_data['order_num']))
			exit("NO num_inv \n");


	}








	$different_delivery_address=false;




	if (!$skip_del_address) {


		if ($header_data['postcode']=='07760 Ciutadella') {
			$header_data['town']='Ciutadella';
			$header_data['postcode']='07760';
		}

		if (!(
				_trim(strtolower($act_data['a1']))==_trim(strtolower($header_data['address1'])) and
				_trim(strtolower($act_data['a2']))==_trim(strtolower($header_data['address2'])) and
				_trim(strtolower($act_data['town']))==_trim(strtolower($header_data['city'])) and
				(

					_trim(strtolower($act_data['postcode']))==_trim(strtolower($header_data['postcode'])) or
					_trim(strtolower($act_data['country']).' '.strtolower($act_data['postcode']))==_trim(strtolower($header_data['postcode']))
				)

			)

		)
			$different_delivery_address=true;


		if ($different_delivery_address) {
			// print "cacacacacacacacacacaca";
		}
		//print "xxxxxxxxxxxxxxxxxxxxx";

		//    if($different_delivery_address and $act_data['town']!=''){
		//       if(strtolower($act_data['a1'])==strtolower($header_data['address1']) and  strtolower($act_data['a2'])==strtolower($header_data['address2'])  and preg_match('/'.$act_data['town'].'/i',strtolower($header_data['city'])))
		//  $different_delivery_address=false;
		//     }
		// check if a country is a valid country and if it is not assume uk


		if (strtolower($header_data['postcode'])== strtolower($act_data['country']) and $act_data['country']!='') {
			if (strtolower($header_data['city'])==strtolower($act_data['postcode'].' '.$act_data['town']))
				$different_delivery_address=false;
		}












		$sql=sprintf("select `Country Key` as id from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($header_data['country']),prepare_mysql($header_data['country']));
		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if (!$row = mysql_fetch_array($result, MYSQL_ASSOC))
			$header_data['country']=$act_data['country'];




	}




	if (preg_match('/South Afrika$|South Africa$/i',$header_data['postcode'])) {
		$header_data['country']='South Africa';
		$header_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$header_data['postcode']));
	}


	if ($header_data['country']=='') {
		$header_data['country']='España';
	}

	if ($act_data['country']=='') {
		$act_data['country']='España';
	}


	$header_data['postcode']=_trim( $header_data['postcode']);
	$header_data['trade_name']=preg_replace('/\\\"/i',' ',$header_data['trade_name']);





	$extra_contact=false;
	if ($act_data['contact']!='') {

		$_contact=$act_data['contact'];
		$split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
		if (count($split_names)==2) {
			$split_names1=preg_split('/\s+/i',$split_names[0]);
			$split_names2=preg_split('/\s+/i',$split_names[1]);
			if (count($split_names1)==1 and count($split_names2)==2 ) {
				$name1=$split_names1[0].' '.$split_names2[1];
				$name2=$split_names[1];
			}else {
				$name1=$split_names[0];
				$name2=$split_names[1];
			}
			$act_data['contact']=$name1;
			$extra_contact=$name2;
			if ($_contact==$act_data['name']) {
				$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
			}

		}
		$there_is_contact=true;
	}else {
		$there_is_contact=false;
		//   if(!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb|Amanatur S L/i',$act_data['name']))
		//   $act_data['contact']=$act_data['name'];
		//if(!preg_match('/^(pompas)$/i',$act_data['name']))
		//  $act_data['contact']=$act_data['name'];



	}


	$act_data=act_transformations($act_data);
	$act_data=ci_act_transformations($act_data);


	//  print $act_data['contact']." >>> $extra_contact   \n ";

	if ($act_data['name']!=$act_data['contact'] )
		$tipo_customer='Company';
	else {
		$tipo_customer='Person';
	}









	$address_raw_data=get_address_raw();




	$address_raw_data['address1']=$act_data['a1'];
	$address_raw_data['address2']=$act_data['a2'];
	$address_raw_data['address3']=$act_data['a3'];
	$address_raw_data['town']=$act_data['town'];
	$address_raw_data['town_d1']=$act_data['town_d1'];
	$address_raw_data['town_d2']=$act_data['town_d2'];
	$address_raw_data['country_d2']=$act_data['country_d2'];
	$address_raw_data['postcode']=$act_data['postcode'];
	$address_raw_data['country']=$act_data['country'];
	if (isset($act_data['country_d1']))
		$address_raw_data['country_d1']=$act_data['country_d1'];





	$shop_address_data=$address_raw_data;

	$extra_id1=$act_data['act'];
	$extra_id2=$shop_address_data['postcode'];

	//  print "$different_delivery_address xxx";
	if ($different_delivery_address) {

		if (preg_match('/^c\/o/i',$header_data['address1'])) {
			$co=$header_data['address1'];
			$header_data['address1']='';
		}
		if (preg_match('/^c\/o/i',$header_data['address2'])) {
			$co=$header_data['address2'];
			$header_data['address2']='';
		}
		if (preg_match('/^c\/o/i',$header_data['address3'])) {
			$co=$header_data['address3'];
			$header_data['address3']='';
		}

		$address_raw_data_del=get_address_raw();
		$address_raw_data_del['address1']=$header_data['address1'];
		$address_raw_data_del['address2']=$header_data['address2'];
		$address_raw_data_del['address3']=$header_data['address3'];
		$address_raw_data_del['town']=$header_data['city'];
		$address_raw_data_del['postcode']=$header_data['postcode'];
		$address_raw_data_del['country_d2']=$header_data['country_d2'];
		$address_raw_data_del['country_d1']=$header_data['country_d1'];
		$address_raw_data_del['country']=$header_data['country'];

		//    print_r($header_data);exit;


		//print_r($address_raw_data_del);
		$del_address_data=$address_raw_data_del;


		$different_del_address=true;



		// print_r($shop_address_data);print_r($del_address_data);exit;

		$a_diff=array_diff_assoc($del_address_data,$shop_address_data);

		if (isset($a_diff['country_d1_id']))
			unset($a_diff['country_d1_id']);
		if (isset($a_diff['country_d2_id']))
			unset($a_diff['country_d2_id']);
		//   print"***";

		//print array_key_exists('postcode',$a_diff)."\n";

		foreach ($a_diff as $key=>$value) {
			//print $del_address_data[$key]."** \n";
			if (strtolower($del_address_data[$key])==strtolower($shop_address_data[$key]))
				unset($a_diff[$key]);
		}
		if (count($a_diff)==0) {
			$different_del_address=false;
			print "Same address\n";
		}

		//  print_r($del_address_data);
		// print_r($shop_address_data);



		// print_r($shop_address_data);
		//exit;
		if (preg_match('/ireland/i',$shop_address_data['country'])) {
			if (count($a_diff)==1 and array_key_exists('postcode',$a_diff))
				$different_del_address=false;
			if (count($a_diff)==1 and array_key_exists('country_d2',$a_diff))
				$different_del_address=false;
			if (count($a_diff)==2 and array_key_exists('country_d2',$a_diff)
				and array_key_exists('postcode',$a_diff))
				$different_del_address=false;

		}


		if (count($a_diff)==2) {

			if (array_key_exists('postcode',$a_diff) and array_key_exists('country_d2',$a_diff)
				and ($shop_address_data['country']==''


				)) {
				// print "PC of the del address taken (a)\n";
				$different_del_address=false;
				$shop_address_data['postcode']=$del_address_data['postcode'];
			}
		}













		if (count($a_diff)==1) {

			if (array_key_exists('postcode',$a_diff)
				and ($shop_address_data['country']==''
				)) {
				// print "PC of the del address taken\n";
				$different_del_address=false;
				$shop_address_data['postcode']=$del_address_data['postcode'];
			}
			elseif (array_key_exists('country_d2',$a_diff)
				or array_key_exists('country_d1',$a_diff)
			) {
				//print "D2 x of the del address taken\n";
				$different_del_address=false;

			}

		}
		//    print_r($shop_address_data);
		//print_r($del_address_data);
		//print "xca";
		//exit;
	}else {
		$del_address_data=$shop_address_data;
		$different_del_address=false;
	}





	//  $country_id=$shop_address_data['country_id'];


	$act_data['tel']=preg_replace('/\[\d*\]/','',$act_data['tel']);
	$act_data['tel']=preg_replace('/\(/','',$act_data['tel']);
	$act_data['tel']=preg_replace('/\)/','',$act_data['tel']);
	$act_data['fax']=preg_replace('/\[\d*\]/','',$act_data['fax']);
	$act_data['fax']=preg_replace('/\(/','',$act_data['fax']);
	$act_data['fax']=preg_replace('/\)/','',$act_data['fax']);
	$act_data['mob']=preg_replace('/\[\d*\]/','',$act_data['mob']);
	$act_data['mob']=preg_replace('/\(/','',$act_data['mob']);
	$act_data['mob']=preg_replace('/\)/','',$act_data['mob']);

	$email_data=guess_email($act_data['email']);

	// print_r($email_data);
	//print "$tipo_customer\n";
	//print_r($act_data);

	global $myconf;
	$shop_address_data['default_country_id']=$myconf['country_id'];

	if (isset($act_data['act']))
		$customer_data['Customer Old ID']=$act_data['act'];
	else
		$customer_data['Customer Old ID']='';




	$customer_data['type']=$tipo_customer;
	$customer_data['contact_name']=$act_data['contact'];
	$customer_data['company_name']=$act_data['name'];
	$customer_data['email']=$email_data['email'];
	$customer_data['telephone']=_trim($act_data['tel']);
	$customer_data['fax']=$act_data['fax'];
	$customer_data['mobile']=$act_data['mob'];
	$customer_data['address_data']=$shop_address_data;
	$customer_data['address_data']['type']='3line';

	$customer_data['address_data']=$shop_address_data;
	$customer_data['address_data']['type']='3line';
	$customer_data['address_data']['name']=$act_data['contact'];
	$customer_data['address_data']['company']=$act_data['name'];
	$customer_data['address_data']['telephone']=_trim($act_data['tel']);



	$customer_data['has_shipping']=true;
	if ($customer_data['has_shipping']) {
		$del_address_data['default_country_id']=$myconf['country_id'];
		$customer_data['shipping_data']=$del_address_data;

		$customer_data['shipping_data']['name']=mb_ucwords($header_data['customer_contact']);
		$customer_data['shipping_data']['company']=mb_ucwords($header_data['trade_name']);


		$_tel=preg_split('/ /',$header_data['phone']);
		$email=$_tel[count($_tel)-1];
		if (preg_match('/@/i',$email)) {

			$email=preg_replace('/\/com$/','.com',$email);
			$email=preg_replace('/\//','',$email);


			$tel=_trim(preg_replace('/'.$email.'/','',$header_data['phone']));

			$email=_trim($email);
		}else {
			$email='';
			$tel=$header_data['phone'];

		}
		$tel=_trim(preg_replace('/^\s*\[\s*1\s*\]\s*/','',$tel));
		// print "***** $tel\n";
		$customer_data['shipping_data']['telephone']=$tel;
		$customer_data['shipping_data']['email']=$email;
		$customer_data['shipping_data']['type']='3line';

	}

	//  $customer_data['other_id']=$act_data['act'];

	// print_r($customer_data);
	//exit;
	//print_r($act_data);
	//$customer_data['address_data']=

	if (isset($act_data['act']))
		$customer_data['Customer Old ID']=$act_data['act'];
	else
		$customer_data['Customer Old ID']='';
	//   print "+++++++++++++++\n";


	if ($customer_data['email']=='jordisubiranaballesteros@hotmail.com' and $customer_data['contact_name']!='Iolanda Catarina Martinez Angelico') {
		// print_r($customer_data);
		$customer_data['email']='';
	}

	return $customer_data;





}
function read_header($raw_header_data,$map_act,$y_map,$map,$convert_encoding=true) {



	//$new_mem=memory_get_usage(true);
	//    print"x$new_mem x ";

	$act_data=array();
	$header_data=array();
	//first read the act part

	$raw_act_data=array_shift($raw_header_data);
	//print "========\n";
	// print_r($raw_header_data);
	
	//print_r($map['total_topay']);
	
	foreach($raw_header_data as $_key=>$_value){
		if(is_array($_value)){
		foreach($_value as $_key2=>$_value2){
		if($_value2=='A pagar' and $_key2==$map['total_topay']['col']-2 ){
		//print "$_key $_key2 $_value2 \n";
		$map['total_topay']['row']=$_key;
		}
		}
		}
	}
	
	//print_r($map);
	
	if ($raw_act_data) {

		foreach ($raw_act_data as $key=>$col) {
			if ($convert_encoding)
				$cols[$key]=mb_convert_encoding($col, "UTF-8", "ISO-8859-1");
			else
				$cols[$key]=$col;
		}

		$act_data['customer_id_from_inikoo']=0;
		if ($cols[65]=='inikoo')
			$act_data['customer_id_from_inikoo']=1;


		//     print_r($cols);
		//exit;
		$act_data['name']=mb_ucwords($cols[$map_act['name']]);
		$act_data['contact']=mb_ucwords($cols[$map_act['contact']]);
		if ($act_data['name']=='' and $act_data['contact']!='') // Fix only contact
			$act_data['name']=$act_data['contact'];
		$act_data['first_name']=mb_ucwords($cols[$map_act['first_name']]);
		$act_data['a1']=mb_ucwords($cols[$map_act['a1']]);
		$act_data['a2']=mb_ucwords($cols[$map_act['a2']]);
		$act_data['a3']=mb_ucwords($cols[$map_act['a3']]);
		$act_data['town']=mb_ucwords($cols[$map_act['town']]);
		$act_data['country_d2']=mb_ucwords($cols[$map_act['country_d2']]);
		$act_data['postcode']=$cols[$map_act['postcode']];

		$act_data['country']=mb_ucwords($cols[$map_act['country']]);
		$act_data['tel']=$cols[$map_act['tel']];
		$act_data['fax']=$cols[$map_act['fax']];
		$act_data['mob']=$cols[$map_act['mob']];
		$act_data['source']=$cols[$map_act['source']];
		$act_data['act']=$cols[$map_act['act']];
		$act_data['email']=$cols[count($cols)-1];
		$act_data['country_d1']='';
		//  if($act_data['a1']==0)$act_data['a1']='';
		//if($act_data['a2']==0)$act_data['a2']='';
		//if($act_data['a3']==0)$act_data['a3']='';



	}

	// print $raw_header_data[9][5]." $map\n";
	//  print_r($map);

	//print_r($raw_header_data);






	foreach ($map as $key=>$map_data) {
		if ($map_data) {
			// print "$key  \n";
			//      print_r($map_data);
			// print "**** $key ".$map_data['row']." ".$map_data['col']."\n";



			$_data=$raw_header_data[$map_data['row']][$map_data['col']];
			if ($convert_encoding)
				$_data=mb_convert_encoding($_data, "UTF-8", "ISO-8859-1");


			if (isset($map_data['tipo']))
				$tipo=$map_data['tipo'];
			else
				$tipo='';
			switch ($tipo) {
			case('name'):
				$_data=_trim($_data);
				if ($_data=='0')$_data='';
				$header[$key]=$_data;

				break;
			case('name'):
				$_data=_trim($_data);
				if ($_data=='0')$_data='';
				$header[$key]=mb_ucwords($_data);

				break;
			case('date'):

				$header[$key]=date("Y-m-d",mktime(0, 0, 0, 1 , $_data-1, 1900));
				break;
			default:
				$header[$key]=$_data;
				break;
			}
		}else
			$header[$key]='';
	}

	if ($header['feedback']=='SinBinBoth') {
		$header['feedback']=1;
	}elseif ($header['feedback']=='SinBinPick') {
		$header['feedback']=2;
	}elseif ($header['feedback']=='SinBinPack') {
		$header['feedback']=3;
	}else
		$header['feedback']=0;


	$new_mem=memory_get_usage(true);
	// print"x$new_mem x ";


	return array($act_data,$header);

}
function read_records($handle_csv,$y_map,$number_header_rows) {



	$first_order_bonus=false;

	$re_order=true;
	if (isset($y_map['no_reorder']) and $y_map['no_reorder'] )
		$re_order=false;

	$header=array(false);
	$products=array();
	$act=false;
	$row=0;
	while (($cols = fgetcsv($handle_csv))!== false) {

		if ($row<$number_header_rows) {// is a header data
			$header[]=$cols;
		}else {
			//      i
			//    if(isset($cols[3])){
			//  if(preg_match('/wsl-1513/i',$cols[3])  ){
			//    print_r($cols);
			//  print $y_map['bonus']."\n ";
			//  }
			//       }
			// print count($cols)."\n";




			if (count($cols)<$y_map['discount'])
				continue;



			if (preg_match('/regalo de bienvenida/i',$cols[$y_map['description']]))
				$first_order_bonus=true;

			//  if($cols[$y_map['code']]=='Pack-29')
			// print $y_map['bonus'];

			if (
				(



					$cols[$y_map['code']]!=''
					and (is_numeric($cols[$y_map['credit']]) or $cols[$y_map['discount']]==1   )
					and $cols[$y_map['description']]!=''
					and (is_numeric($cols[$y_map['price']]) or $cols[$y_map['price']]==''  )
					and (  ( is_numeric($cols[$y_map['order']])   and  $cols[$y_map['order']]!=0   )
						or ( is_numeric($cols[$y_map['reorder']])   and  $cols[$y_map['reorder']]!=0   and $re_order   )
						or ( is_numeric($cols[$y_map['bonus']])   and  $cols[$y_map['bonus']]!=0   ) )
				)or (preg_match('/credit/i',$cols[$y_map['code']])   and  $cols[$y_map['price']]!='' and  $cols[$y_map['price']]!=0  )
			) {





				//  if($cols['units']==1 or $cols['units']='')
				//    $cols['name']=$cols['description'];
				//  else
				//    $cols['name']=$cols['units'].'x '.$cols['description'];

				$cols['fob']=$first_order_bonus;
				$products[]=$cols;
			}else if (preg_match('/^public\d*$|^nic$/i',$cols[0])  )
					$header[0]=$cols;

		}
		$row++;
	}
	// print_r($products);
	// exit;
	return array($header,$products);

}

function get_customer_msg($data) {


	$data['customer_msg']='';
	if (preg_match('/^(EXPORT TO GERMANY|catalogue|DO NOT SEND WINE-SEND ALTERNATIVE|PLEASE HOLD UNTIL Bag-01 IN STOCK|corner of Marine Parade and Graystone Road|Friday \d{1,2}pm|NO WINE\!|Give to Kara|open 10 am to 5 pm|entrance from.*Street|del tue or thu|If Not In Leave In Cupboard By Door Please|if noone in leave with neighbour or in garage|closed on Wednesdays|Shop open 10am-5pm. Closed Wednesdays.|Leave at rear if out|no wine\!?|Look 4 Multi-Storey Carpark|Not open untill? \d{1,2}.\d{1,2}(AM|PM))$/i',_trim($data['notes2']))
		or preg_match('/difficult to find|URGENT|if out, leave|catalogue - please|Phone with |pls phone |Ensure |Opp car showroom|save until|Next to Hairdresser|Contract if |del after|Monday . 9am|opening hours|Mon,Weds, Thurs 9am - 2pm; Fri 9am - 4pm;|if out leave by the side of the green recycle bin|Del before 3PM|carefully|pls pack|pls pick|9am sharp|email cust on|if any|if cust|notify if|call |access via|contact cust|give wine|call on |pls pick today|can only del|Check order CAREFULLY|CHECK CARRIAGE|contact cust if out of stock|drink so give something else as bonus|WEDNESDAY|DESP TODAY AND PACK CAREFULLY|please pack bath bombs very|If closed with|call if|IF ITEMS OUT OF STOCK CONTACT CUSTOMER|Tuesday|No Substitution please|Thursday|friday|can be left|deluvery |please |closed on|Subs OK|NO WINE alternative gift please 1 box of SG|if out can be left |Please call if|contact cust if something out of stock|if out put|Alternative gift to WINE|Add Catalogu|Call if out of stock|Call if if out of stock|Leave outside|Closed between|Not before|Let (her|me|him) know|oppocite|opposite|Behind|Must go out on|Deliver before|if not there|nobody|Leave in|Deliver|If no-one|Leave at|Deliver on|closed at|Please ring customer before delivery |Delivery Between|nobody|porch |close |Open |Shop open|Shop closed|if out Deliver|Leave at|if not there|next door|delivery before|deliver to|in shed|leave around|leave with|leave on|garage|shop|if noone|if not|despatch|dispatch/i',$data['notes2'])
	) {
		$data['customer_msg']=$data['notes2'];
		$data['notes2']='';

	}
	if (preg_match('/^(EXPORT TO GERMANY|catalogue|DO NOT SEND WINE-SEND ALTERNATIVE|PLEASE HOLD UNTIL Bag-01 IN STOCK|corner of Marine Parade and Graystone Road|Friday \d{1,2}pm|NO WINE\!|Give to Kara|open 10 am to 5 pm|entrance from.*Street|del tue or thu|If Not In Leave In Cupboard By Door Please|if noone in leave with neighbour or in garage|closed on Wednesdays|Shop open 10am-5pm. Closed Wednesdays.|Leave at rear if out|no wine\!?|Look 4 Multi-Storey Carpark|Not open untill? \d{1,2}.\d{1,2}(AM|PM))$/i',_trim($data['notes']))
		or preg_match('/difficult to find|URGENT|if out, leave|catalogue - please|Phone with |pls phone |Ensure |save until|Next to Hairdresser|Contract if more than 2 boxes|del after|Monday . 9am|opening hours|Mon,Weds, Thurs 9am - 2pm; Fri 9am - 4pm;|if out leave by the side of the green recycle bin|Del before 3PM|carefully|pls pack|pls pick|9am sharp|email cust on|if any|if cust|notify if|call |access via|contact cust|give wine|call on |pls pick today|can only del|Check order CAREFULLY|CHECK CARRIAGE|contact cust if out of stock|drink so give something else as bonus|WEDNESDAY|DESP TODAY AND PACK CAREFULLY|please pack bath bombs very|If closed with|call if|IF ITEMS OUT OF STOCK CONTACT CUSTOMER|Tuesday|No Substitution please|Thursday|friday|can be left|deluvery |please |closed on|Subs OK|NO WINE alternative gift please 1 box of SG|if out can be left |Please call if|contact cust if something out of stock|if out put|Alternative gift to WINE|Add Catalogu|Call if out of stock|Call if if out of stock|Leave outside|Closed between|Not before|Let (her|me|him) know|oppocite|opposite|Behind|Must go out on|Deliver before|if not there|nobody|Leave in|Deliver|If no-one|Leave at|Deliver on|closed at|Please ring customer before delivery |Delivery Between|nobody|porch |close |Open |Shop open|Shop closed|if out Deliver|Leave at|if not there|next door|delivery before|deliver to|in shed|leave around|leave with|leave on|garage|shop|if noone|if not|despatch|dispatch/i',$data['notes'])
	) {
		$data['customer_msg'].=' '.$data['notes'];
		$data['notes']='';

	}


	return $data;
}

function is_to_be_collected($data) {

	if (preg_match('/^(local *|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|Collect .*|Collection.*|to be collected|to collect|collected|customer to collect|to be collect by cust|to be collected.*|will collec.*|to collect.*|to collect today)$/i',_trim($data['notes']))) {


		$data['shipper_code']='NA';
		$data['collection']='Yes';

		if (preg_match('/^(local|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|to be collected|to collect|collected|customer to collect|to be collect by cust)$/i',_trim($data['notes']))) {
			$data['notes']='';
		}

	}

	if (preg_match('/^(local *|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|Collect .*|Collection.*|to be collected|to collect|collected|customer to collect|to be collect by cust|to be collected.*|will collec.*|to collect.*|to collect today)$/i',_trim($data['notes']))) {

		$data['shipper_code']='NA';
		$data['collection']='Yes';

		if (preg_match('/^(local|collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|to be collected|to collect|collected|customer to collect|to be collect by cust)$/i',_trim($data['notes2']))) {
			$data['notes2']='';
		}


	}

	// print_r($data);
	return $data;

}
function is_showroom($data) {
	if (preg_match('/^(showrooms?|Showrooom)$/i',_trim($data['notes']))) {
		$data['showroom']='Yes';
		$data['notes']='';
		$data['shipper_code']='NA';
		$data['collection']='Yes';

	}
	if (preg_match('/^(showrooms?|Showrooom)$/i',_trim($data['notes2']))) {
		$data['showroom']='Yes';
		$data['notes2']='';
		$data['shipper_code']='NA';
		$data['collection']='Yes';
	}
	return $data;
}
function is_staff_sale($data) {
	$data['staff sale key']=0;

	if (preg_match('/cash sale/i',$data['trade_name'])  or preg_match('/cash sale/i',$data['notes'])) {
		if ($data['shipping']==0) {
			$data['shipper_code']='NA';
			$data['collection']='Yes';
		}



		$tmp=preg_replace('/^Staff Sales?\s*\-?\s*/i','',$data['customer_contact']);
		// exit("x:".$tmp."\n");
		$staff_id=get_user_id($tmp);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
			$data['shipper_code']='NA';
			$data['collection']='Yes';
			$data['staff sale']='Yes';

			if (preg_match('/'.$tmp.'/i',$data['notes']))
				$data['notes']='';
			if (preg_match('/'.$tmp.'/i',$data['notes2']))
				$data['notes2']='';

			if (preg_match('/^cash sale$/i',$data['notes2']))
				$data['notes2']='';
			if (preg_match('/^cash sale$/i',$data['notes']))
				$data['notes']='';
		}



		if ($data['staff sale key']==0) {
			$tmp=preg_replace('/Staff Sales?\s*\-?\s*/i','',$data['notes']);
			$staff_id=get_user_id($tmp);
			if (count($staff_id)==1 and $staff_id[0]!=0 ) {
				$data['staff sale key']=$staff_id[0];
				$data['shipper_code']='NA';
				$data['collection']='Yes';
				$data['staff sale']='Yes';
				$data['notes']='';
				if (preg_match('/^cash sale$/i',$data['notes2']))
					$data['notes2']='';
				if (preg_match('/^cash sale$/i',$data['notes']))
					$data['notes']='';
				if (preg_match('/'.$tmp.'/i',$data['notes2']))
					$data['notes2']='';
			}
		}


		if ($data['staff sale key']==0) {
			$tmp=preg_replace('/Staff Sales?\s*\-?\s*/i','',$data['notes2']);
			$staff_id=get_user_id($tmp);
			if (count($staff_id)==1 and $staff_id[0]!=0 ) {
				$data['staff sale key']=$staff_id[0];
				$data['shipper_code']='NA';
				$data['collection']='Yes';
				$data['staff sale']='Yes';
				$data['notes2']='';
				if (preg_match('/^cash sale$/',$data['notes2']))
					$data['notes2']='';
				if (preg_match('/^cash sale$/',$data['notes']))
					$data['notes']='';

			}
		}


	}


	if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['trade_name']))) {

		$data['staff sale']='Yes';
		$data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['trade_name']);
		$staff_id=get_user_id($data['staff sale name']);
		$data['staff sale key']=$staff_id[0];

		$data['shipper_code']='NA';
		$data['collection']='Yes';

	}



	if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['notes']))) {

		$data['staff sale']='Yes';
		$data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['notes']);
		$staff_id=get_user_id($data['staff sale name']);
		$data['staff sale key']=$staff_id[0];
		$data['notes']='';
		$data['shipper_code']='NA';
		$data['collection']='Yes';

	}
	if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['notes2']))) {

		$data['staff sale']='Yes';
		$data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['notes2']);
		$staff_id=get_user_id($data['staff sale name']);
		$data['staff sale key']=$staff_id[0];
		$data['notes2']='';
		$data['shipper_code']='NA';
		$data['collection']='Yes';

	}
	if (preg_match('/^(staff sale|staff)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['staff sale']='Yes';
		$data['shipper_code']='NA';
		$data['collection']='Yes';


		$staff_id=get_user_id($data['customer_contact']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}


	}

	if (preg_match('/^staff sales?\-?\s+\-?\s*[a-z]*/i',_trim($data['postcode']))) {

		$data['staff sale']='Yes';
		$data['staff sale name']=preg_replace('/^staff sales?\-?\s+\-?\s*/i','',$data['postcode']);
		$staff_id=get_user_id($data['staff sale name']);
		$data['staff sale key']=$staff_id[0];

		$data['shipper_code']='NA';
		$data['collection']='Yes';

	}







	if (preg_match('/^(staff sale|staff)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['staff sale']='Yes';
		$data['shipper_code']='NA';
		$data['collection']='Yes';
		$data['staff sale key']=0;

		$staff_id=get_user_id($data['customer_contact']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['address1']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['address2']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}

	}


	if (preg_match('/^staff sales?$/i',_trim($data['trade_name']))) {
		$data['staff sale']='Yes';
		$data['shipper_code']='NA';
		$data['collection']='Yes';


		$staff_id=get_user_id($data['address1']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['address2']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['customer_contact']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}




	}


	if (preg_match('/^staff sales?$/i',_trim($data['customer_contact']))) {
		$data['staff sale']='Yes';
		$data['shipper_code']='NA';
		$data['collection']='Yes';
		$staff_id=get_user_id($data['address1']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['address2']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['customer_contact']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
	}

	//print_r($data);exit;

	if (preg_match('/^staff sales?|Ancient Winsdom Staff$/i',_trim($data['postcode']))) {
		$data['staff sale']='Yes';
		$data['shipper_code']='NA';
		$data['collection']='Yes';
		$staff_id=get_user_id($data['address1']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['address2']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
		$staff_id=get_user_id($data['customer_contact']);
		if (count($staff_id)==1 and $staff_id[0]!=0 ) {
			$data['staff sale key']=$staff_id[0];
		}
	}

	//print_r($data);exit;




	//print_r($data);exit;

	return $data;

}
function get_tax_number($data) {
	global $myconf;
	$data['tax_number']='';
	$note='';
	$tax_number='';
	if (!isset($data['dn_country_code']) or !$data['dn_country_code'] or $data['dn_country_code']=='0')
		$data['dn_country_code']='';
	//print $data['dn_country_code']."xxx";
	if (
		(in_array(strtoupper($data['dn_country_code']),$myconf['tax_conditional0_2acode'])  and ($data['tax1']==0 or $data['tax1']=='' or !$data['tax1'])  and $data['notes2']!='' )
		or ($data['dn_country_code']=='' and ($data['tax1']==0 or $data['tax1']=='' or !$data['tax1']) and $data['notes2']!=''  )
	) {



		if (preg_match('/CUSTOMER VAT 75732 Company : 680602=4840/i',$data['notes2'])) {

			$note='';
			$tax_number='680602-4840';

		}else {


			$tax_number=$data['notes2'];
			$regex='/ - do not change shipping cost - fix price.| \, 15\/30|if oos inform customer, no incense for alt. gift|, CARRIAGE BETWEEN .* AND 110|If oos, send email\!|deliver after 10:30 am|Contact Customer for payment details \!\!\!|Check order CAREFULLY|CHARGE BEFORE PICKING|Please phone 087 652 5769 before delivery\!|Deliveries accepted Tue - Sat 1000-1700 Contac Nic|Daughter: Cristina Viana|if oos inform customer, no more bath sets|pls contact cust if any probs with paym|no carriage, |Please contact customer for out of stock items.|See customer.s note regarding SSC.|delivery to Ireland - |Deliveries accepted Tue to Sat 10 \- 17\:30|deliver after 10am|Delivery after 10.00 am| P 24\/06 via frans mass|see note of 5th FEB|see note of 09\/06\/20009|always quote customer| --- Shipping FOC promotion|Kaym_Whelan@yahoo.ie| - checked see note of 19\/05\!/i';

			if (preg_match($regex, $tax_number,$match)) {

				$note=$match[0];
				$tax_number=preg_replace($regex,'',$tax_number);
			}
		}
		//print "OTN: $tax_number\n";
		// print "tax number: $tax_number\n";
		$tax_number=_trim($tax_number);
		// print "$tax_number\n";
		$tax_number=preg_replace('/tax id\s*:?\s*-?\s*/i','',$tax_number);
		$tax_number=preg_replace('/V\.a\.t\. N.*:\s*-?\s*/i','',$tax_number);

		$tax_number=preg_replace('/VAT NO\s*-\s*/i','',$tax_number);
		$tax_number=preg_replace('/^VAT No\.\:\s*/i','',$tax_number);
		$tax_number=preg_replace('/^vat no\s*(\.|:)?\s*/i','',$tax_number);
		$tax_number=preg_replace('/^vat\s*(\:|\-)?\s*/i','',$tax_number);
		$tax_number=preg_replace('/^vat\s*reg\*(\:|\-)?\s*/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*Checked and Valid$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*valid and checked$/i','',$tax_number);
		$tax_number=preg_replace('/tax\s*:?\s*/i','',$tax_number);

		$tax_number=preg_replace('/\-?\s*ok$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*checked$/i','',$tax_number);
		$tax_number=preg_replace('/\s*ckecked$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*checked\s+valid\.?$/i','',$tax_number);
		$tax_number=preg_replace('/\s*\-?\s*valid$/i','',$tax_number);
		$tax_number=preg_replace('/\s*\-?\s*verified$/i','',$tax_number);
		$tax_number=preg_replace('/\s*\-?\s*Checked\s*\!{0,5}$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*\(checked\)$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*\(check ok\)$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*valid\s*\(HM\)$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*checked by customs$/i','',$tax_number);

		if (preg_match('/EL137399039 checkedEL-137399039/i',$tax_number))
			$tax_number='EL137399039';
		if (preg_match('/PT:503958271, validPT-503958271/i',$tax_number))
			$tax_number='PT-503958271';
		if (preg_match('/NL060484305B02 validNL060484305B02 valid/i',$tax_number))
			$tax_number='NL060484305B02';
		if (preg_match('/^IE : 3756781C$/i',$tax_number))
			$tax_number='IE3756781C';

		$tax_number=_trim($tax_number);
		// print "TN: $tax_number\n";
		if (
			preg_match('/^[a-z]{1,2}\s*\-?\s*[a-z0-9]{8,12}\s*$/i',$tax_number)
			or preg_match('/^[a-z]{0,2}\s*\d{6,16}\s*[a-z]\.?\d{0,10}$/i',$tax_number)
			or preg_match('/^\d{3} \d{4}\-?\d/i',$tax_number)
			or preg_match('/[a-z]-\d{6,10}-[a-z]/i',$tax_number)
			or preg_match('/[a-z]{2}\s*\d{3}\.\d{3}\.\d{3}/i',$tax_number)
			or preg_match('/\d{3}.\d{3,4}.\d{3,4}/i',$tax_number)
			or preg_match('/680602-4840/i',$tax_number)
			or preg_match('/[a-z]{2}\s*\d{2,4}\s*\d{2,3}\s*\d{2,4}\s*[a-z]?\d{2,4}/i',$tax_number)
			or preg_match('/NL 8132 54 097 B01/i',$tax_number)
			or preg_match('/n-\d{8} S/i',$tax_number)
			or preg_match('/tf 2134041/i',$tax_number)




		) {
			$tax_number=preg_replace('/\s/','',$tax_number);
			if (!($tax_number[2]=='-'  or $tax_number[1]=='-')) {

				if (preg_match('/^[a-z]{2}\d/i',$tax_number)) {
					$t1=substr($tax_number,0,2);
					$t2=substr($tax_number,2);
					$tax_number=$t1.'-'.$t2;
				}elseif (preg_match('/^[a-z]\d/i',$tax_number)) {
					$t1=substr($tax_number,0,1);
					$t2=substr($tax_number,1);
					$tax_number=$t1.'-'.$t2;
				}


			}
			$data['tax_number']=$tax_number;
			$data['notes2']=$note;
			// print "$tax_number\n";
			// return $tax_number;
		}elseif (preg_match('/^\d{7,12}$/i',$tax_number)) {
			// print "$tax_number\n";
			// return $tax_number;
			$data['tax_number']=$tax_number;
			$data['notes2']=$note;
		}
	}elseif (preg_match('/^vat\s\d{11}$/i',_trim($data['notes2']))) {
		$data['tax_number']=$data['notes2'];
		$data['notes2']='';
	}elseif (preg_match('/SA VAT NO 9116\/677\/16\/3/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^SA VAT NO /','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^tax : tf \d{7}/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^tax : /','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^tax id \d{5,}/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^tax id /','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^(Customer)?\s*tax id\s*:?\s*[a-z]?\d{5,}[a-z]?/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^(Customer)?\s*tax id\s*:?\s*/','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^tax : tf 2134041?/i',_trim($data['notes2']))) {
		$data['tax_number']='tf 2134041';
		$data['notes2']='';
	}elseif (preg_match('/^Tax 85 467 757 063?/i',_trim($data['notes2']))) {
		$data['tax_number']='85467757063';
		$data['notes2']='';
	}elseif (preg_match('/^EL 046982660 valid?/i',_trim($data['notes2']))) {
		$data['tax_number']='EL-046982660';
		$data['notes2']='';
	}elseif (preg_match('/^EL-377 187 83?/i',_trim($data['notes2']))) {
		$data['tax_number']='EL-37718783';
		$data['notes2']='';
	}elseif (preg_match('/^FI1622254-8 checked by customs?/i',_trim($data['notes2']))) {
		$data['tax_number']='FI-1622254-8';
		$data['notes2']='';
	}elseif (preg_match('/^IE-7251185?/i',_trim($data['notes2']))) {
		$data['tax_number']='IE-7251185';
		$data['notes2']='';
	}elseif (preg_match('/^SE556670-257601$/i',_trim($data['notes2']))) {
		$data['tax_number']='SE556670-257601';
		$data['notes2']='';
	}elseif (preg_match('/^IE5493347N$/i',_trim($data['notes2']))) {
		$data['tax_number']='IE5493347N';
		$data['notes2']='';
	}elseif (preg_match('/^ES-B92544691$/i',_trim($data['notes2']))) {
		$data['tax_number']='ES-B92544691';
		$data['notes2']='';
	}

	return $data;

}
?>
