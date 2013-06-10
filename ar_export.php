<?php
require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];

$export_method='gearman';//or 'gearman';


switch ($tipo) {

case('get_wait_info'):
	$data=prepare_values($_REQUEST,array(
			'fork_key'=>array('type'=>'key'),
			'table'=>array('type'=>'string')
		));
	get_wait_info($data);
	break;
case('export'):

	$data=prepare_values($_REQUEST,array(
			'table'=>array('type'=>'string'),
			'output'=>array('type'=>'enum','valid values regex'=>'/csv|xls/i'),
			'fields'=>array('type'=>'string'),
			'date'=>array('type'=>'string','optional'=>true)

		));
	export($data);
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}


function export($data) {
	global $inikoo_account_code,$fork_encrypt_key,$export_method;
	
	$user=$data['user'];
	list ($sql_count,$sql_data,$fetch_type)=get_sql_query($_REQUEST);
	
	
	
	$edit_part_data=array(
		'table'=>$data['table'],
		'output'=>$data['output'],
		'user_key'=>$user->id,
		'sql_count'=>$sql_count,
		'sql_data'=>$sql_data,
		'fetch_type'=>$fetch_type,
		
	);

	$token=substr(str_shuffle(md5(time()).rand().str_shuffle('qwertyuiopasdfghjjklmnbvcxzQWERTYUIOPKJHGFDSAZXCVBNM1234567890') ),0,64);
	$sql=sprintf("insert into `Fork Dimension`  (`Fork Process Data`,`Fork Token`) values (%s,%s)  ",
		prepare_mysql(serialize($edit_part_data)),
		prepare_mysql( $token)
	);

	$salt=md5(rand());

	mysql_query($sql);
	$fork_key=mysql_insert_id();

	$encrypt_key=$fork_encrypt_key.$salt;

	$secret_data=serialize(
		array('token'=>$token,'fork_key'=>$fork_key)
	);

	$encrypted_data=AESEncryptCtr(base64_encode($secret_data),$encrypt_key,256);

	$fork_metadata=serialize(array('code'=>addslashes($inikoo_account_code),'salt'=>$salt,'data'=>$secret_data,'endata'=>$encrypted_data));


	if($export_method=='gearman'){

	$client= new GearmanClient();
	$client->addServer('127.0.0.1');
	$msg=$client->doBackground("export", $fork_metadata);

	}else{



	}


	$response= array(
		'state'=>200,'fork_key'=>$fork_key,'msg'=>$msg,'table'=>$data['table']
	);
	echo json_encode($response);

}

function get_wait_info($data) {

	$fork_key=$data['fork_key'];
	$sql=sprintf("select `Fork Result`,`Fork Scheduled Date`,`Fork Start Date`,`Fork State`,`Fork Operations Done`,`Fork Operations No Changed`,`Fork Operations Errors`,`Fork Operations Total Operations` from `Fork Dimension` where `Fork Key`=%d ",
		$fork_key);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		if ($row['Fork State']=='In Process')
			$msg=number($row['Fork Operations Done']+$row['Fork Operations Errors']+$row['Fork Operations No Changed']).'/'.$row['Fork Operations Total Operations'];
		elseif ($row['Fork State']=='Queued')
			$msg=_('Queued');
		else
			$msg='';
		$response= array(
			'state'=>200,
			'fork_key'=>$fork_key,
			'fork_state'=>$row['Fork State'],
			'done'=>$row['Fork Operations Done'],
			'no_changed'=>$row['Fork Operations No Changed'],
			'errors'=>$row['Fork Operations Errors'],
			'total'=>$row['Fork Operations Total Operations'],
			'result'=>$row['Fork Result'],
			'msg'=>$msg,
			'progress'=>sprintf('%s/%s (%s)',number($row['Fork Operations Done']),number($row['Fork Operations Total Operations']),percentage($row['Fork Operations Done'],$row['Fork Operations Total Operations'])),
			'table'=>$data['table']

		);
		echo json_encode($response);

	}else {
		$response= array(
			'state'=>400,

		);
		echo json_encode($response);

	}

}



function get_sql_query($data) {
	//print_r($data);

	switch ($data['table']) {
	case 'customers':
		return customers_sql_query($data);
		break;
	case 'part_stock_historic':
		return part_stock_historic_sql_query($data);
		break;		
	default:
		return false;
	}
}

function part_stock_historic_sql_query($data) {

	$fetch_type='simple';
	$group='';
	$wheref='';
	switch ($data['parent']) {
	case 'none':
	$where=sprintf("where  `Date`=%s  ",prepare_mysql($data['date']));
		$table='`Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)';
		break;
	case 'warehouse':

	$where=sprintf("where `Warehouse key`=%d and `Date`=%s  ",$data['parent_key'],prepare_mysql($data['date']));
		$table='`Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)';


		break;

	default;
		$where.='false';
	}
		$sql_count="select count(Distinct P.`Part SKU`) as num from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref";

$data['fields']=preg_replace('/value_at_end_day/','sum(`Value At Day Cost`) as value_at_end_day',$data['fields']);
$data['fields']=preg_replace('/locations/','count(DISTINCT `Location Key`) as locations',$data['fields']);
$data['fields']=preg_replace('/value_at_cost/','sum(`Value At Cost`) as value_at_cost',$data['fields']);
$data['fields']=preg_replace('/stock/','sum(`Quantity On Hand`) as stock',$data['fields']);
$data['fields']=preg_replace('/commercial_value/','sum(`Value Commercial`) as commercial_value',$data['fields']);

//print $data['fields'];
	$sql_data="select sum(`Quantity On Hand`) as stock,sum(`Quantity Open`) as stock_open,sum(`Value At Cost`) as value_at_cost,,sum(`Value Commercial`) as commercial_value from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref group by ISF.`Part SKU`   order by ISF.`Part SKU` ";

	$sql_data=sprintf("select %s from `Inventory Spanshot Fact` ISF left join `Part Dimension` P on  (P.`Part SKU`=ISF.`Part SKU`)  $where $wheref group by ISF.`Part SKU`   order by ISF.`Part SKU` ",
	addslashes($data['fields'])
	);
/*
	$sql_data=sprintf("select %s from %s %s %s",
		addslashes($data['fields']),
		$table,
		$where,
		$group
	);*/
	//print $sql_data;
//exit;
	return array($sql_count,$sql_data,$fetch_type);
}

function customers_sql_query($data) {

	$fetch_type='simple';
	$group='';
	$where=' where true ';
	switch ($data['parent']) {
	case 'store':
		$where.=sprintf(' and `Customer Store Key`=%d',$data['parent_key']);
		$table='`Customer Dimension` C';
		break;
	case 'list':

		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$data['parent_key']);

		$res=mysql_query($sql);
		if ($customer_list_data=mysql_fetch_assoc($res)) {
			$awhere=false;
			if ($customer_list_data['List Type']=='Static') {
				$table='`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
				$where.=sprintf(' and `List Key`=%d ',$data['parent_key']);

			} else {

				$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
				$tmp=preg_replace('/\\\\\"/','"',$tmp);
				$tmp=preg_replace('/\'/',"\'",$tmp);

				$raw_data=json_decode($tmp, true);

				$raw_data['store_key']=$customer_list_data['List Parent Key'];
				include_once 'list_functions_customer.php';
				list($where,$table,$group)=customers_awhere($raw_data);
			}

		} else {
			return;
		}


		break;

	default;
		$where.='false';
	}
	$sql_count=sprintf("select count(Distinct C.`Customer Key`) as num from %s %s ",$table,$where);
	$sql_data=sprintf("select %s from %s %s %s",
		addslashes($data['fields']),
		$table,
		$where,
		$group
	);
	//print $sql_data;

	return array($sql_count,$sql_data,$fetch_type);
}
