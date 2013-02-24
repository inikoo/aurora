<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.PartLocation.php';
require_once 'class.Category.php';

require_once 'ar_edit_common.php';
if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {

case('get_edit_selected_parts_wait_info'):
	$data=prepare_values($_REQUEST,array(
			'fork_key'=>array('type'=>'key')

		));
	get_edit_selected_parts_wait_info($data);
	break;

case('edit_part_custom_field'):
case('edit_part_unit'):
case('edit_part'):
case('edit_part_description'):
case('edit_part_health_and_safety'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string'),
			'sku'=>array('type'=>'key'),
		));
	edit_part($data);


	break;

case('edit_parts'):

	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'subject_source_checked_type'=>array('type'=>'string'),
			'subject_source_checked_subjects'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'value'=>array('type'=>'string'),

		));
	edit_parts($data);


	break;

case('create_part'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));

	create_part($data);
	break;
}

function edit_part($data) {
	global $editor;
	//print_r($data);

	$part=new Part($data['sku']);
	$part->editor=$editor;


	if (!$part->sku) {
		$response= array('state'=>400,'msg'=>'part not found');
		echo json_encode($response);
		exit;
	}

	$key_dic=array(

	);



	if (array_key_exists($data['key'],$key_dic))
		$key=$key_dic[$data['key']];
	else
		$key=$data['key'];


	$the_new_value=_trim($data['newvalue']);

	if (preg_match('/^custom_field_part/i',$key)) {
		$custom_id=preg_replace('/^custom_field_/','',$key);
		$part->update_custom_fields($key, $the_new_value);
	} else {

		//print "$key $the_new_value";
		$part->update(array($key=>$the_new_value));
	}


	if ($part->updated) {



		$response= array('state'=>200,'action'=>'updated','newvalue'=>$part->new_value,'key'=>$data['okey']);

	} else {

		$response= array('state'=>400,'msg'=>$part->msg,'key'=>$data['okey']);
	}
	echo json_encode($response);
	exit;


}

function create_part($data) {

	$_part=$data['values'];


	$part_data['Part Most Recent']='Yes';

	$sp=new SupplierProduct($data['parent_key']);

	$supplier=new Supplier($sp->get('Supplier Key'));


	//print_r($sp);exit;

	$part_data['Part XHTML Currently Supplied By']=sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code'));
	$part_data['Part XHTML Currently Used In']=sprintf('<a href="product.php?id=%d">%s</a>',$sp->id,$sp->get('Product Code'));



	//$part_data['editor']=$editor;


	$part_data=array(
		//                  'editor'=>$editor,
		'Part Most Recent'=>'Yes',
		'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')),
		//                 'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')),
		'Part Unit Description'=>strip_tags(preg_replace('/\(.*\)\s*$/i','',$_part['Part Unit Description'])),

		'part valid from'=>gmdate("Y-m-d H:i:s"),
		'part valid to'=>gmdate("Y-m-d H:i:s"),
		'Part Gross Weight'=>$_part['Part Gross Weight']
	);

	//print_r($part_data);exit;

	$part=new Part('new',$part_data);
	if ($part->new) {
		print $part->msg;
	}



	$spp_header=array(
		'Supplier Product Part Type'=>'Simple',
		'Supplier Product Part Most Recent'=>'Yes',
		'Supplier Product Part Valid From'=>gmdate("Y-m-d H:i:s"),
		'Supplier Product Part Valid To'=>gmdate("Y-m-d H:i:s"),
		'Supplier Product Part In Use'=>'Yes'
	);

	$spp_list=array(
		array(
			'Part SKU'=>$part->data['Part SKU'],
			'Supplier Product Units Per Part'=>1,
			'Supplier Product Part Type'=>'Simple'
		)
	);



	$sp->new_current_part_list($spp_header,$spp_list);


	$response= array('state'=>200,'action'=>'created_','object_key'=>$part->id,'msg'=>$part->msg);
	echo json_encode($response);


	/*
    $part_list[]=array(
                     'Part SKU'=>$part->get('Part SKU'),
                     'Parts Per Product'=>1,
                     'Product Part Type'=>'Simple'
                 );



    $product->new_current_part_list(array(),$part_list)  ;

    $supplier_product->update_sold_as();
    $supplier_product->update_store_as();
    $product->update_parts();
    $part->update_used_in();
    $part->update_supplied_by();
    $product->update_cost_supplier();
*/

}

function get_edit_selected_parts_wait_info($data) {

	$fork_key=$data['fork_key'];
	$sql=sprintf("select `Fork Scheduled Date`,`Fork Start Date`,`Fork State`,`Fork Operations Done`,`Fork Operations No Changed`,`Fork Operations Errors`,`Fork Operations Total Operations` from `Fork Dimension` where `Fork Key`=%d ",
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
				'msg'=>$msg

			);
		echo json_encode($response);

	}else {
		$response= array(
			'state'=>400,

		);
		echo json_encode($response);

	}

}

function edit_parts($data) {


	switch ($data['parent']) {
	case 'category':
		$f_value=$_SESSION['state']['part_categories']['edit_parts']['f_value'];
		$f_field=$_SESSION['state']['part_categories']['edit_parts']['f_field'];
		break;
	case 'warehouse':
		$f_value=$_SESSION['state']['warehouse']['edit_parts']['f_value'];
		$f_field=$_SESSION['state']['warehouse']['edit_parts']['f_field'];
		break;
	}

	$edit_part_data=array(
		'parent'=>$data['parent'],
		'parent_key'=>$data['parent_key'],
		'subject_source_checked_type'=>$data['subject_source_checked_type'],
		'subject_source_checked_subjects'=>$data['subject_source_checked_subjects'],
		'key'=>$data['key'],
		'value'=>$data['value'],
		'f_value'=>$f_value,
		'f_field'=>$f_field,
		'tipo'=>'edit_parts'
	);


	$sql=sprintf("insert into `Fork Dimension`  (`Fork Process Data`) values (%s)  ",
		prepare_mysql(serialize($edit_part_data))

	);
	//print $sql;
	mysql_query($sql);
	$fork_key=mysql_insert_id();



	//$path=preg_replace('/\/ar_edit_parts.php/','',$_SERVER['SCRIPT_FILENAME']);
	//$exec_command="/opt/local/bin/php $path/fork_edit_parts.php > /dev/null 2>/dev/null &";
	//$r=system( $exec_command );



	$client= new GearmanClient();
	$client->addServer();
	$msg=$client->doBackground("edit_parts", $fork_key);


	$response= array(
		'state'=>200,'fork_key'=>$fork_key,'msg'=>$msg
	);
	echo json_encode($response);

}

?>
