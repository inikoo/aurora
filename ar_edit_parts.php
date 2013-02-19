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
	$response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {

case('get_edit_selected_parts_wait_info'):
	$data=prepare_values($_REQUEST,array(
			'edit_pid'=>array('type'=>'numeric')

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

	if (isset($_SESSION['state']['multi_edit_data'][$data['edit_pid']])) {
		$response= array(
			'state'=>251,
			'msg'=>number($_SESSION['state']['multi_edit_data'][$data['edit_pid']]['done']).'/'.number($_SESSION['state']['multi_edit_data'][$data['edit_pid']]['total']),

			'edit_pid'=>$data['edit_pid']
		);
		echo json_encode($response);
	}else {
		$response= array(
			'state'=>251,'edit_pid'=>$data['edit_pid'],'msg'=>date('U')
		);
		echo json_encode($response);
	}


}
/*
function edit_parts($data) {



$edit_part_data=array(
'parent'=>data['parent'],
			'parent_key'=>data['parent'],
			'subject_source_checked_type'=>data['parent'],
			'subject_source_checked_subjects'=>data['parent'],
			'key'=>data['parent'],
			'value'=>data['parent'],

);


		$sql=sprintf("insert into `Fork Dimension` set (`Fork Process Data`)  ",
			prepare_mysql(serialize($edit_part_data)),
			$data['fork_key']
		);


	$number_parts=0;
	$number_parts_updated=0;
	$number_parts_no_change=0;
	$number_parts_errors=0;




	if ($data['subject_source_checked_type']=='unchecked') {

		$subject_source_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
		$estimated_number_parts=count($subject_source_checked_subjects);

		$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork State=%s",
			prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>0))),
			$data['fork_key']
		);
		mysql_query($sql);

		foreach ($subject_source_checked_subjects as $subject_key) {
			$part= new Part($subject_key);
			if ($part->sku) {
				$number_parts++;
				$part->update(array($data['key']=>$data['value']));
				if ($part->error) {
					$number_parts_errors++;
				}elseif ($part->updated) {
					$number_parts_updated++;
				}else {
					$number_parts_no_change++;
				}

				$sql=sprintf("update `Fork Dimension` set `Fork State=%s where `Fork Key`",
					prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>$number_parts))),
					$data['fork_key']

				);
				mysql_query($sql);
			}
		}
	}
	else {

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


		$wheref='';
		if ($f_field=='used_in' and $f_value!='')
			$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='description' and $f_value!='')
			$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='supplied_by' and $f_value!='')
			$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='sku' and $f_value!='')
			$wheref.=" and  P.`Part SKU` ='".addslashes($f_value)."'";


		switch ($data['parent']) {
		case 'category':

			$sql=sprintf("select `Subject Key` as `Part SKU` from `Category Bridge` B left join `Part Dimension` P on (`Part SKU`=`Subject Key`)  where `Subject`='Part' and `Category Key`=%d %s",$data['parent_key'],$wheref);

			break;
		case 'warehouse':
			$sql=sprintf("select B.`Part SKU` from `Part Warehouse Bridge`  from `Category Bridge` B left join `Part Dimension` P on (`Part SKU`=`Subject Key`) where `Warehouse Key`=%d %s",$data['parent_key'],$wheref);
			break;
		}

		$res=mysql_query($sql);
		$no_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);

		$estimated_number_parts = mysql_num_rows($res)-count($no_checked_subjects);
		$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork State=%s",
			prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>0))),
			$data['fork_key']
		);
		mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			if (!in_array($row['Part SKU'],$no_checked_subjects)) {
				$part= new Part($row['Part SKU']);
				if ($part->sku) {
					$number_parts++;
					$part->update(array($data['key']=>$data['value']));
					if ($part->error) {
						$number_parts_errors++;
					}elseif ($part->updated) {
						$number_parts_updated++;
					}else {
						$number_parts_no_change++;
					}
					$sql=sprintf("update `Fork Dimension` set `Fork State=%s where `Fork Key`",
						prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>$number_parts))),
						$data['fork_key']

					);
					mysql_query($sql);

				}
			}

		}


	}


}
*/
?>
