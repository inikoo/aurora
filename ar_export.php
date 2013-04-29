<?php
require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];




switch ($tipo) {

case('get_wait_info'):
	$data=prepare_values($_REQUEST,array(
			'fork_key'=>array('type'=>'key')

		));
	get_wait_info($data);
	break;
case('export'):

	$data=prepare_values($_REQUEST,array(
			'table'=>array('type'=>'string'),
			'output'=>array('type'=>'enum','valid values regex'=>'/csv|xls/i')

		));
	export($data);
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);

}


function export($data) {

	$edit_part_data=array(
		'table'=>$data['table'],
		'output'=>$data['output'],
		'request'=>$_REQUEST
	);


	$sql=sprintf("insert into `Fork Dimension`  (`Fork Process Data`) values (%s)  ",
		prepare_mysql(serialize($edit_part_data))
	);

	mysql_query($sql);
	$fork_key=mysql_insert_id();

	$client= new GearmanClient();
	$client->addServer('127.0.0.1');
	$msg=$client->doBackground("export", $fork_key);


	$response= array(
		'state'=>200,'fork_key'=>$fork_key,'msg'=>$msg
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
			'progress'=>sprintf('%s/%s (%s)',number($row['Fork Operations Done']),number($row['Fork Operations Total Operations']),percentage($row['Fork Operations Done'],$row['Fork Operations Total Operations']))

		);
		echo json_encode($response);

	}else {
		$response= array(
			'state'=>400,

		);
		echo json_encode($response);

	}

}
