<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2015 at 15:00:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/new_fork.php';




if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$_data=prepare_values($_REQUEST, array(
		'state'=>array('type'=>'json array'),
		'type'=>array('type'=>'string'),
		'ar_file'=>array('type'=>'string'),
		'tipo'=>array('type'=>'string'),
		'parameters'=>array('type'=>'json array'),
		'fields'=>array('type'=>'json array')
	));

$dont_save_table_state=true;
$_data['nr']=1000000;
$_data['page']=1;
include_once 'conf/export_fields.php';

if (!isset($export_fields[$_data['tipo']])) {
	$response=array('state'=>405, 'resp'=>'field set not found');
	echo json_encode($response);
	exit;
}

$field_set=$export_fields[$_data['tipo']];

include_once 'prepare_table/init.php';

$fields='';
foreach ($_data['fields'] as $field_key) {
	if (isset($field_set[$field_key]))
		$fields.=$field_set[$field_key]['name'].',';
}
$fields=preg_replace('/,$/', '', $fields);

$sql="select $fields from $table $where $wheref $where_type $group_by order by $order $order_direction ";

if ($_data['type']=='excel') {
	$output='xls';
}else {
	$output=$_data['type'];
}

$export_data=array(
	'table'=>$_data['tipo'],
	'output'=>$output,
	'user_key'=>$user->id,
	'sql_count'=>$sql_totals,
	'sql_data'=>$sql,
	'fetch_type'=>'simple'
);

//print_r($export_data);

list($fork_key, $msg)=new_fork('export', $export_data, $account->get('Account Code'), $db);


$response= array(
	'state'=>200, 'fork_key'=>$fork_key, 'msg'=>$msg, 'type'=>$_data['type'],'tipo'=>$_data['tipo']
);
echo json_encode($response);

?>
