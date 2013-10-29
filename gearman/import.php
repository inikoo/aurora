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

	$imported_record->update(array('Imported Records State'=>'InProcess','Imported Records Start Date'=>gmdate("Y-m-d H:i:s")));

	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$imported_record->data['Imported Waiting Records'],
		$fork_key
	);
	mysql_query($sql);


	$map=preg_split('/,/',$imported_record->data['Imported Records Options Map']);
	$base_data=get_base_data($imported_record->data['Imported Records Subject']);

	if ($imported_record->data['Imported Records Subject']=='customers') {
		include 'class.Customer.php';
		$list_scope='Customer';
		include 'edit_customers_functions.php';
		$list_table_name='Customer';
		$list_table_subject_label='Customer Key';
	}


	$list_name=$imported_record->data['Imported Records File Name'];

	$list_name_counter='';
	if (list_name_taken($list_name,$imported_record->data['Imported Records Parent Key'])) {
		$list_name_counter=2;
		while (list_name_taken($list_name.' '.$list_name_counter,$imported_record->data['Imported Records Parent Key'])) {

			$list_name_counter++;

		}

	}
	$list_name=_trim($list_name.' '.$list_name_counter);

	$list_sql=sprintf("insert into `List Dimension` (
	`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Use Type`,`List Metadata`,`List Creation Date`,`List Number Items`)
	values (%s,%d,%s,%s,%s,%s,NOW(),%d)",
		prepare_mysql($list_scope),
		$imported_record->data['Imported Records Parent Key'],
		prepare_mysql($list_name),
		prepare_mysql('Static'),

		prepare_mysql('ImportedRecords'),
		prepare_mysql('imported records '.$imported_record->id),
		0

	);
	//print $list_sql;

	mysql_query($list_sql);
	$list_key=mysql_insert_id();


	$imported_record->update(array('Imported Records Subject List Key'=>$list_key,'Imported Records Subject List Name'=>$list_name));


	$sql=sprintf("update `Fork Dimension` set `Fork Operations No Changed`=%d  where `Fork Key`=%d ",
		$imported_record->data['Imported Ignored Records'],
		$fork_key
	);
	mysql_query($sql);


	$sql=sprintf("select `Imported Record Data`,`Imported Record Key` from `Imported Record` where `Imported Record Import State`='Waiting' and `Imported Record Parent Key`=%d ",
		$fork_data['imported_records_key']
	);
	//print $sql;
	$contador=0;
	$number_errors=0;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {


		$sql=sprintf("select `Fork State` from `Fork Dimension` where `Fork Key`=%d  ",$fork_key);
		$res_check_if_cancelled=mysql_query($sql);
		if ($row_check_if_cancelled=mysql_fetch_assoc($res_check_if_cancelled)) {

			if($row_check_if_cancelled['Fork State']=='Cancelled'){
			$sql=sprintf("update `Fork Dimension` set `Fork Finished Date`=NOW(),`Fork Cancelled Date`=NOW(),`Fork Result`=%s `Fork Operations Cancelled`=(`Fork Operations Total Operations`-`Fork Operations Done`-`Fork Operations No Changed`-`Fork Operations Errors`) where `Fork Key`=%d ",
				prepare_mysql('imported cancelled'),
				$fork_key
			);
			mysql_query($sql);
			$imported_record->cancel();
			return false;
			}
		}

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
			$sql=sprintf("update `Imported Record` set `Imported Record Date`=%s, `Imported Record Import State`='Imported' ,`Imported Record Subject Key`=%d,`Imported Record XHTML Note`=%s,`Imported Record Note`=%s where `Imported Record Key`=%d",
				prepare_mysql(gmdate("Y-m-d H:i:s")),
				$response['subject_key'],
				prepare_mysql($response['note']),
				prepare_mysql(strip_tags($response['note'])),

				$row['Imported Record Key']


			);
			mysql_query($sql);



			$sql=sprintf("insert into `List %s Bridge` (`List Key`,`%s`) values (%d,%d)",
				$list_table_name,
				$list_table_subject_label,
				$list_key,
				$response['subject_key']
			);
			mysql_query($sql);


			//print "$sql\n";

		}
		else {
			$sql=sprintf("update `Imported Record` set `Imported Record Date`=%s,`Imported Record Import State`='Error',`Imported Record XHTML Note`=%s,`Imported Record Note`=%s where `Imported Record Key`=%d",
				prepare_mysql(gmdate("Y-m-d H:i:s")),
				prepare_mysql($response['note']),
				prepare_mysql(strip_tags($response['note'])),

				$row['Imported Record Key']
			);
			mysql_query($sql);
			//print "$sql\n";
			$number_errors++;

		}
		$imported_record->update_records_numbers();
		$sql=sprintf("update `Fork Dimension` set `Fork Operations Done`=%d ,`Fork Operations Errors`=%d where `Fork Key`=%d ",
			$contador-$number_errors,
			$number_errors,
			$fork_key
		);
		mysql_query($sql);


	}


	$imported_record->update(array('Imported Records State'=>'Finished','Imported Records Finish Date'=>gmdate("Y-m-d H:i:s")));

	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%s where `Fork Key`=%d ",
		$contador,
		prepare_mysql('imported'),
		$fork_key
	);
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
		if (array_key_exists('customer_key', $response) and $response['customer_key']) {
			$response['subject_key']=$response['customer_key'];

		}

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


function list_name_taken($list_name,$parent_key) {

	$sql=sprintf("select `List Key` from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d ",
		prepare_mysql($list_name),
		$parent_key
	);

	$result=mysql_query($sql);
	$num_results= mysql_num_rows($result);



	return $num_results;

}

?>
