<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo

function fork_import($job) {
	include 'class.User.php';

	include 'class.ImportedRecords.php';

	if (!$_data=get_fork_data($job)) {
		print "error reading fork data\n";
		return;

	}

	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];
	$inikoo_account_code=$_data['inikoo_account_code'];

	$user=new User('id',$fork_data['user_key']);

	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Alias'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);

	$imported_record=new ImportedRecords('id',$fork_data['imported_records_key']);

$imported_record->update(array('Imported Records State'=>'InProcess'));

	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$imported_record->data['Imported Waiting Records'],
		$fork_key
	);
	mysql_query($sql);


	$map=preg_split('/,/',$imported_record->data['Imported Records Options Map']);
	$base_data=get_base_data($imported_record->data['Imported Records Subject']);

	if ($imported_record->data['Imported Records Subject']=='customers') {
		include 'class.Customer.php';

		include 'edit_customers_functions.php';
	}


	$sql=sprintf("select `Imported Record Data`,`Imported Record Key` from `Imported Record` where `Imported Record Import State`='Waiting' and `Imported Record Parent Key`=%d ",
		$fork_data['imported_records_key']
	);
	//print $sql;
	$contador=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$contador++;
		$sql=sprintf("update `Imported Record` set `Imported Record Import State`='Importing' where `Imported Record Key`=%d",
			$row['Imported Record Key']
		);
		mysql_query($sql);
		$record_data=$base_data;

		foreach (json_decode($row['Imported Record Data'],true) as $key=>$value) {
			$record_data[$map[$key]]=$value;
		}

		$response=create_record($imported_record->data['Imported Records Subject'],$imported_record->data['Imported Records Parent Key'],$record_data,$editor);


		if ($response['state']==200 and $response['action']=='created') {
			$sql=sprintf("update `Imported Record` set `Imported Record Import State`='Imported' ,`Subject Key`=%d where `Imported Record Key`=%d",
				 $response['customer_key'],
				$row['Imported Record Key']
			);
			mysql_query($sql);

		}else {
			$sql=sprintf("update `Imported Record` set `Imported Record Import State`='Error' where `Imported Record Key`=%d",
				$row['Imported Record Key']
			);
			mysql_query($sql);


		}
		$imported_record->update_records_numbers();
		$sql=sprintf("update `Fork Dimension` set `Fork Operations Done`=%d  where `Fork Key`=%d ",
			$contador,
			$fork_key
		);
		mysql_query($sql);


	}


$imported_record->update(array('Imported Records State'=>'Finished'));

	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%s where `Fork Key`=%d ",
		$contador,
		'imported',
		$fork_key
	);
	//print $sql;
	mysql_query($sql);

	return false;

}

function create_record($subject,$parent_key,$data,$editor) {
	switch ($subject) {
	case'customers':

		$data['Customer Store Key']=$parent_key;


		if (  !( $data['Customer Type']=='Person' or  $data['Customer Type']=='Company')    ) {
			list($data['Customer Type'] ,$data['Customer Company Name'],$data['Customer Main Contact Name'])=parse_company_person($data['Customer Company Name'],$data['Customer Main Contact Name']);
		}

		if ($data['Customer Type']=='Company')
			$data['Customer Name']=$data['Customer Company Name'];
		else
			$data['Customer Name']=$data['Customer Main Contact Name'];




		if ($data['Customer Address Country 2 Alpha Code']!='') {
			$country=new Country('2alpha',$data['Customer Address Country 2 Alpha Code']);
			$data['Customer Address Country Code']=$country->data['Country Code'];
			unset($country);
		}
		elseif ($data['Customer Address Country Code']!='') {
			$country=new Country('code',$data['Customer Address Country Code']);
			$data['Customer Address Country Code']=$country->data['Country Code'];
			unset($country);
		}
		elseif ($data['Customer Address Country Name']!='') {
			$country=new Country('code',$data['Customer Address Country Name']);
			$data['Customer Address Country Code']=$country->data['Country Code'];
			unset($country);
		}
		else {
			$data['Customer Address Country Code']='UNK';
		}


		$editor['Date']=gmdate('Y-m-d H:i:s');
		$data['editor']=$editor;

		$response=add_customer($data) ;

		return $response;

		break;
	}
}


function get_base_data($subject) {
	$base_data=array();
	switch ($subject) {
	case'customers':
		$base_data=array(
			"Customer Store Key"=>"",
			"Customer Name"=>"",
			"Customer Type"=>"",
			"Customer Main Contact Name"=>"",
			"Customer Tax Number"=>"",
			"Customer Registration Number"=>"",
			"Customer Main Plain Email"=>"",
			"Customer Main Plain Telephone"=>"",
			"Customer Main Plain FAX"=>"",
			"Customer Main Plain Mobile"=>"",
			"Customer Address Line 1"=>"",
			"Customer Address Line 2"=>"",
			"Customer Address Line 3"=>"",
			"Customer Address Town"=>"",
			"Customer Address Postal Code"=>"",
			"Customer Address Country Name"=>"",
			"Customer Address Country Code"=>"",
			"Customer Address Country 2 Alpha Code"=>"",
			"Customer Address Town Second Division"=>"",
			"Customer Address Town First Division"=>"",
			"Customer Address Country First Division"=>"",
			"Customer Address Country Second Division"=>"",
			"Customer Address Country Third Division"=>"",
			"Customer Address Country Forth Division"=>"",
			"Customer Address Country Fifth Division"=>"");


		$sql = sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer' and `Custom Field In New Subject`='Yes'");
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$base_data[$row['Custom Field Name']] = $row['Default Value'];
		}


		break;
	}
	return $base_data;

}

?>
