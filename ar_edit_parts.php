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
case('part_transactions'):
	list_part_transactions_for_edition();
	break;
case('delete_MSDS_file'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'sku'=>array('type'=>'key'),
		));


	delete_MSDS_attachment($data);
	break;
case('add_MSDS_file'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'sku'=>array('type'=>'key'),
		));
	$data['field']='Part MSDS Attachment Bridge Key';
	$data['caption']='';

	add_MSDS_attachment($data);
	break;
case('supplier_products_in_part'):
	list_supplier_products_in_part();
	break;
case('products_in_part'):
	list_products_in_part();
	break;
case('get_edit_selected_parts_wait_info'):
	$data=prepare_values($_REQUEST,array(
			'fork_key'=>array('type'=>'key')

		));
	get_edit_selected_parts_wait_info($data);
	break;
case('edit_part_properties'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),

			'sku'=>array('type'=>'key'),
		));

	edit_part_properties($data);
	break;
	break;
case('edit_part_custom_field'):
case('edit_part_unit'):
case('edit_part_status'):

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

default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);
}

function edit_part_properties($data) {
	global $editor;

	$values=$data['values'];
	$part=new Part($data['sku']);
	$part->editor=$editor;

	if (!$part->sku) {
		$response= array('state'=>400,'msg'=>'part not found');
		echo json_encode($response);
		exit;
	}
	$response=array();

	// print_r($values);

	foreach ($values as $key=>$_data) {
		$_data['key']=$key;
		$_data['newvalue']=$_data['value'];
		$response[]=part_process_edit($part,$_data);
	}
	echo json_encode($response);
	exit;

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


	$response=part_process_edit($part,$data);

	echo json_encode($response);
	exit;


}


function part_process_edit($part,$data) {

	$key_dic=array(
		'available_for_products_configuration'=>'Part Available for Products Configuration'
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

	if (!$part->error) {
		$response= array('state'=>200,'action'=>'updated','newvalue'=>$part->new_value,'key'=>$data['okey']);
		if ($data['okey']=='available_for_products_configuration') {
			include_once 'product_common_functions.php';
			$products=$part->get_current_products();
			$response['product_data']=get_product_web_state_labels($products);
		}
	} else {
		$response= array('state'=>400,'msg'=>$part->msg,'key'=>$data['okey']);
	}

	return $response;


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
		'Part Package Weight'=>$_part['Part Package Weight']
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
    $product->update_cost();
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

function list_products_in_part() {

	$conf=$_SESSION['state']['part']['products'];


	if (isset( $_REQUEST['sku']))
		$sku=$_REQUEST['sku'];
	else {
		exit("");
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$_SESSION['state']['part']['products']['order']=$order;
	$_SESSION['state']['part']['products']['order_dir']=$order_direction;
	$_SESSION['state']['part']['products']['nr']=$number_results;
	$_SESSION['state']['part']['products']['sf']=$start_from;
	$_SESSION['state']['part']['products']['where']=$where;
	$_SESSION['state']['part']['products']['f_field']=$f_field;
	$_SESSION['state']['part']['products']['f_value']=$f_value;


	if ($sku) {

		$filter_msg='';

		$wheref='';
		$where=sprintf("where `Part SKU`=%d ",$sku);;

		if ($f_field=='code' and $f_value!='')
			$wheref.=sprintf(" and `Product Code` like '%s%%'   ",addslashes($f_value));



		$sql="select count(*) as total from `Product Part List` L  left join `Product Part Dimension` PP on (L.`Product Part Key`=PP.`Product Part Key`) left join `Product Dimension` P on (P.`Product ID`=PP.`Product ID`)  $where $wheref";
		//  print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total=$row['total'];
		}
		mysql_free_result($result);

		if ($wheref=='') {
			$filtered=0;
			$total_records=$total;
		} else {
			$sql="select count(*) as total from  `Product Part List`   $where ";
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$total_records=$row['total'];
				$filtered=$total_records-$total;
			}
			mysql_free_result($result);

		}


		$rtext=number($total_records)." ".ngettext('product','products',$total_records);
		if ($total_records>$number_results)
			$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
		else
			$rtext_rpp=' ('._('Showing all').')';

		if ($total==0 and $filtered>0) {
			switch ($f_field) {
			case('code'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with this code ")." <b>".$f_value."*</b> ";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with description like ")." <b>".$f_value."*</b> ";
				break;
			}
		}
		elseif ($filtered>0) {
			switch ($f_field) {
			case('code'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with code like')." <b>".$f_value."*</b>";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with description like')." <b>".$f_value."*</b>";
				break;
			}
		}
		else
			$filter_msg='';
		/*  }else{//products parts for new product */

		/*      $total=count($_SESSION['state']['new_product']['parts']); */
		/*      $total_records=$total; */
		/*      $filtered=0; */
		/*    } */






		$_dir=$order_direction;
		$_order=$order;


		$order='`Part SKU`';


		$sql="select `Product Use Part H and S`,`Product Use Part Pictures`,`Product Use Part Properties`,`Product Use Part Tariff Data`,P.`Product ID`,`Product Web State`,`Product Web Configuration`,`Product Record Type`,`Product Sales Type`,`Parts Per Product`,`Part SKU`,`Product Part List Note`,`Product Code` ,`Store Code`,`Store Key` from `Product Part List` L  left join `Product Part Dimension` PP on (L.`Product Part Key`=PP.`Product Part Key`) left join `Product Dimension` P on (P.`Product ID`=PP.`Product ID`)left join `Store Dimension` S on (P.`Product Store Key`=S.`Store Key`) $where  $wheref  order by $order $order_direction limit $start_from,$number_results    ";
		//print $sql;
		$res = mysql_query($sql);
		$total=mysql_num_rows($res);
		$adata=array();
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC) ) {
			// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);




			switch ($row['Product Sales Type']) {
			case('Public Sale'):
				$sales_type=_('Public Sale');
				break;
			case('Private Sale'):
				$sales_type=_('Private Sale');
				break;
			case('Not for Sale'):
				$sales_type=_('Not For Sale');
				break;
			default:
				$sales_type=$row['Product Sales Type'];

			}


			switch ($row['Product Record Type']) {
			default:
				$record_type=$row['Product Record Type'];

			}


			switch ($row['Product Web Configuration']) {
			case('Online Force Out of Stock'):
				$formated_web_configuration=_('Force out of stock');
				break;
			case('Online Auto'):
				$formated_web_configuration=_('Auto');
				break;
			case('Offline'):
				$formated_web_configuration=_('Force Offline');
				break;
			case('Online Force For Sale'):
				$formated_web_configuration=_('Force Online');
				break;
			default:
				$formated_web_configuration=$row['Product Web Configuration'];
			}

			switch ($row['Product Web State']) {
			case('Out of Stock'):
				$web_state='<span class=="out_of_stock">['._('Out of Stock').']</span>';
				break;
			case('For Sale'):
				$web_state='';
				break;
			case('Discontinued'):
				$web_state=_('Discontinued');
			case('Offline'):
				$web_state=_('Offline');
			default:
				$web_state=$row['Product Web State'];


				break;


			}

			if ($row['Product Sales Type']!='Public Sale') {
				$web_configuration=$row['Product Sales Type'];
				switch ($row['Product Sales Type']) {
				case 'Private Sale':
					$formated_web_configuration=_('Private Sale');
					break;
				default:
					$formated_web_configuration=_('Not For Sale');
					break;
				}
			} else {

				$web_configuration=$row['Product Web Configuration'];
			}

			$code=sprintf("<a href='edit_product.php?pid=%d'>%s</a>",$row['Product ID'],$row['Product Code']);

			if (fmod($row['Parts Per Product'],1)==0) {
				$parts_per_product=sprintf("%d",$row['Parts Per Product']);
				$products_per_part='1';
			}elseif ($row['Parts Per Product']==0.5) {
				$parts_per_product=1;
				$products_per_part=2;
			}elseif ($row['Parts Per Product']==0.333333) {
				$parts_per_product=1;
				$products_per_part=2;
			}elseif ($row['Parts Per Product']==0.666667) {
				$parts_per_product=2;
				$products_per_part=3;
			}elseif ($row['Parts Per Product']==0.083333) {
				$parts_per_product=1;
				$products_per_part=12;
			}else {
				$parts_per_product=$row['Parts Per Product'];
				$products_per_part=1;
			}
			$relation=$parts_per_product.' &rarr; '.$products_per_part;
			$adata[]=array(

				'pid'=>$row['Product ID'],
				'sku'=>$row['Part SKU'],
				'relation'=>$relation,
				'code'=>$code,
				'store'=>$row['Store Code'],
				'notes'=>$row['Product Part List Note'],
				'sales_type'=>$sales_type,
				'record_type'=>$record_type,

				'web_configuration'=>$web_configuration,
				'formated_web_configuration'=>$formated_web_configuration,
				'state_info'=>$sales_type,
				'link_health_and_safety'=>$row['Product Use Part H and S'],
				'link_tariff'=>$row['Product Use Part Tariff Data'],
				'link_properties'=>$row['Product Use Part Properties'],
				'link_pictures'=>$row['Product Use Part Pictures']
				//'link_health_and_safety'=>($row['Product Use Part H and S']=='Yes'?'<img src="art/icons/link.png" alt="link">':'<img src="art/icons/link_break.png" alt="link">'),
				//'link_tariff'=>($row['Product Use Part Tariff Data']=='Yes'?'<img src="art/icons/link.png" alt="link">':'<img src="art/icons/link_break.png" alt="link">'),
				//'link_properties'=>($row['Product Use Part Properties']=='Yes'?'<img src="art/icons/link.png" alt="link">':'<img src="art/icons/link_break.png" alt="link">'),
				//'link_pictures'=>($row['Product Use Part Pictures']=='Yes'?'<img src="art/icons/link.png" alt="link">':'<img src="art/icons/link_break.png" alt="link">'),

			);
		}
		mysql_free_result($res);

	} else {
		$adata=array();
		if (isset($_SESSION['state']['new_product']['parts'])) {
			foreach ($_SESSION['state']['new_product']['parts'] as $values)
				$adata[]=$values;
		}
		$rtext=_('Choose or create a part');
		$rtext_rpp='';
		$total_records=count($adata);
		$filter_msg='';
		$_dir=$order_direction;
		$_order=$order;

		if ($total_records>0) {
			$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
		}

	}



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function list_supplier_products_in_part() {

	$conf=$_SESSION['state']['part']['supplier_products'];


	if (isset( $_REQUEST['sku']))
		$sku=$_REQUEST['sku'];
	else {
		exit("");
	}


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$_SESSION['state']['part']['supplier_products']['order']=$order;
	$_SESSION['state']['part']['supplier_products']['order_dir']=$order_direction;
	$_SESSION['state']['part']['supplier_products']['nr']=$number_results;
	$_SESSION['state']['part']['supplier_products']['sf']=$start_from;
	$_SESSION['state']['part']['supplier_products']['f_field']=$f_field;
	$_SESSION['state']['part']['supplier_products']['f_value']=$f_value;


	if ($sku) {

		$filter_msg='';

		$wheref='';
		$where=sprintf("where `Supplier Product Part Most Recent`='Yes' and  `Part SKU`=%d ",$sku);;

		if ($f_field=='code' and $f_value!='')
			$wheref.=sprintf(" and `Supplier Product Code` like '%s%%'   ",addslashes($f_value));



		$sql="select count(*) as total from `Supplier Product Part List`  L  left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`)  $where $wheref";
		//  print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total=$row['total'];
		}
		mysql_free_result($result);

		if ($wheref=='') {
			$filtered=0;
			$total_records=$total;
		} else {
			$sql="select count(*) as total from `Supplier Product Part List`  L  left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`)  $where ";
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$total_records=$row['total'];
				$filtered=$total_records-$total;
			}
			mysql_free_result($result);

		}


		$rtext=number($total_records)." ".ngettext('product','products',$total_records);
		if ($total_records>$number_results)
			$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
		else
			$rtext_rpp=' ('._('Showing all').')';

		if ($total==0 and $filtered>0) {
			switch ($f_field) {
			case('code'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with this code ")." <b>".$f_value."*</b> ";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with description like ")." <b>".$f_value."*</b> ";
				break;
			}
		}
		elseif ($filtered>0) {
			switch ($f_field) {
			case('code'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with code like')." <b>".$f_value."*</b>";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with description like')." <b>".$f_value."*</b>";
				break;
			}
		}
		else
			$filter_msg='';
		/*  }else{//products parts for new product */

		/*      $total=count($_SESSION['state']['new_product']['parts']); */
		/*      $total_records=$total; */
		/*      $filtered=0; */
		/*    } */






		$_dir=$order_direction;
		$_order=$order;


		$order='`Part SKU`';


		$sql="select `Supplier Product Part Most Recent`,`Supplier Product Part Valid To`,`Supplier Product Part Valid From`,P.`Supplier Product ID`,`Supplier Product Part List Key`,`Supplier Product Part In Use`,`Supplier Product Name`,`Supplier Product Units Per Part`,`Part SKU`,`Supplier Product Code` ,S.`Supplier Code`,S.`Supplier Key`
		from `Supplier Product Part List` L
		left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`)
		left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)
		left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`) $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
		// print $sql;
		$res = mysql_query($sql);
		$total=mysql_num_rows($res);
		$adata=array();
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC) ) {
			// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);

			if ($row['Supplier Product Part In Use']=='Yes') {
				$available_state='Available';
			} else {
				$available_state='No available';
			}

			$relation=$row['Supplier Product Units Per Part'].' &rarr; 1';
			$adata[]=array(
				'sppl_key'=>$row['Supplier Product Part List Key'],
				'sku'=>$row['Part SKU'],
				'relation'=>$relation,
				'code'=>'<a href="supplier_product.php?pid='.$row['Supplier Product ID'].'">'.$row['Supplier Product Code'].' ('.$row['Supplier Product ID'].')'.'</a>',
				'name'=>$row['Supplier Product Name'].'<br>'.$row['Supplier Product Part Valid From'].' &rarr; '.$row['Supplier Product Part Valid To'].' '.($row['Supplier Product Part Most Recent']=='Yes'?'*':''),
				'supplier'=>'<a href="supplier.php?id='.$row['Supplier Key'].'">'.$row['Supplier Code'].'</a>',
				'available'=>$row['Supplier Product Part In Use'],
				'available_state'=>$available_state
			);
		}
		mysql_free_result($res);

	} else {
		$adata=array();
		if (isset($_SESSION['state']['new_product']['parts'])) {
			foreach ($_SESSION['state']['new_product']['parts'] as $values)
				$adata[]=$values;
		}
		$rtext=_('Choose or create a part');
		$rtext_rpp='';
		$total_records=count($adata);
		$filter_msg='';
		$_dir=$order_direction;
		$_order=$order;

		if ($total_records>0) {
			$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
		}

	}



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function add_MSDS_attachment($data) {
	global $editor;

	$part=new Part($data['sku']);
	if (!$part->sku) {
		$msg= "no part found";
		$response= array('state'=>400,'msg'=>$msg);
		echo base64_encode(json_encode($response));
		exit;
	}


	$msg='';
	$error=false;


	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
		$postMax = ini_get('post_max_size'); //grab the size limits...
		$msg= "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
		$response= array('state'=>400,'msg'=>_('Files could not be attached').".<br>".$msg,'key'=>'attach');
		echo base64_encode(json_encode($response));
		exit;

	}

	foreach ($_FILES as $file_data) {

		$msg='';
		if ($file_data['size']==0) {
			$msg= "This file seems that is empty, have a look and try again"; // echo out error and solutions...
			$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;

		}

		if ($file_data['error']) {
			$msg=$file_data['error'];
			if ($file_data['error']==4) {
				$msg=' '._('please choose a file, and try again');

			}
			$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;
		}
		$_data=array(
			'file'=>$file_data['tmp_name']
		);

		$attach=new Attachment('find',$_data,'create');
		if ($attach->id) {

			$part->update_MSDS_attachment($attach,$file_data['name'],$data['caption']);

			$error=$part->error;

		}else {
			$error=true;
			$msg=$attach->msg;
		}


	}

	if ($error) {
		$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
	} else {
		$response= array('state'=>200,'newvalue'=>array('attach_key'=>$part->data['Part MSDS Attachment Bridge Key'],'attach_info'=>$part->data['Part MSDS Attachment XHTML Info']),'key'=>'attach','msg'=>$msg);
	}

	echo base64_encode(json_encode($response));
}

function delete_MSDS_attachment($data) {
	global $editor;

	$part=new Part($data['sku']);
	if (!$part->sku) {
		$msg= "no part found";
		$response= array('state'=>400,'msg'=>$msg);
		echo json_encode($response);
		exit;
	}



	$part->delete_MSDS_attachment();
	$msg=$part->msg;
	$error=$part->error;





	if ($error) {
		$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');



	} else {
		$response= array('state'=>200);

	}

	echo json_encode($response);
}

function list_part_transactions_for_edition() {


if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		exit("a");
	}


	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		exit("b");
	}

	if ($parent=='part') {
		$conf=$_SESSION['state']['part']['transactions'];
		$conf_base=$_SESSION['state']['part'];
		$conf_field='part';
	}elseif ($parent=='warehouse') {
		$conf=$_SESSION['state']['warehouse']['transactions'];
		$conf_base=$_SESSION['state']['warehouse'];
		$conf_field='warehouse';
	}elseif ($parent=='supplier_product') {
		$conf=$_SESSION['state']['supplier_product']['transactions'];
		$conf_base=$_SESSION['state']['supplier_product'];
		$conf_field='supplier_product';
	}else {
		exit("c");
	}

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state'][$conf_field]['from'];


	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$conf_field]['to'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	$elements=$conf['elements'];



	if (isset( $_REQUEST['transactions_type_elements_OIP'])) {
		$elements['OIP']=$_REQUEST['transactions_type_elements_OIP'];
	}
	if (isset( $_REQUEST['transactions_type_elements_Move'])) {
		$elements['Move']=$_REQUEST['transactions_type_elements_Move'];
	}
	if (isset( $_REQUEST['transactions_type_elements_In'])) {
		$elements['In']=$_REQUEST['transactions_type_elements_In'];
	}
	if (isset( $_REQUEST['transactions_type_elements_Out'])) {
		$elements['Out']=$_REQUEST['transactions_type_elements_Out'];
	}
	if (isset( $_REQUEST['transactions_type_elements_Audit'])) {
		$elements['Audit']=$_REQUEST['transactions_type_elements_Audit'];
	}


	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');



	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state'][$conf_field]['from'],$_SESSION['state'][$conf_field]['to']);
	} else {

		$_SESSION['state'][$conf_field]['from']=$date_interval['from'];
		$_SESSION['state'][$conf_field]['to']=$date_interval['to'];
	}


	//print_r($_SESSION['state'][$conf_field]['transactions']);

	$_SESSION['state'][$conf_field]['transactions']['order']=$order;
	$_SESSION['state'][$conf_field]['transactions']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_field]['transactions']['nr']=$number_results;
	$_SESSION['state'][$conf_field]['transactions']['sf']=$start_from;
	$_SESSION['state'][$conf_field]['transactions']['f_field']=$f_field;
	$_SESSION['state'][$conf_field]['transactions']['f_value']=$f_value;
	$_SESSION['state'][$conf_field]['transactions']['elements']=$elements;
	//$_SESSION['state'][$conf_field]['transactions']['f_show']=$f_show;




	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$where='where true ';
	$wheref='';

	if ($f_field=='note' and $f_value!='') {
		// $wheref.=" and  `Note` like '%".addslashes($f_value)."%'  or  `Note` REGEXP '[[:<:]]".$f_value."'  ";
		$wheref.=" and  `Note` like '".addslashes($f_value)."%'  ";

	}

	if ($parent=='part') {
		$where=$where.sprintf(" and `Part SKU`=%d ",$parent_key);
	}else if ($parent=='warehouse') {
			$where=$where.sprintf(" and `Warehouse Key`=%d ",$parent_key);
		}


$_elements='';
	$elements_count=0;
	foreach ($elements as $_key=>$_value) {


		if ($_value) {

			$elements_count++;



			$_elements.=','.prepare_mysql($_key);
		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($elements_count==0) {
		$where.=' and false' ;
	} elseif ($elements_count<5) {
		$where.=' and `Inventory Transaction Section` in ('.$_elements.')' ;
	}else {
		$where.=' and `Inventory Transaction Record Type`="Movement"' ;
	}
	
	
	

	$sql="select count(*) as total from `Inventory Transaction Fact`     $where $wheref";


	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Transaction Fact`   $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}



	$rtext=$total.' '.ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total_records==0) {
		$rtext=_('No stock movements');
		$rtext_rpp='';
	}




	$rtext=number($total_records)." ".ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('note'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any note like")." <b>".$f_value."*</b> ";
			break;

		}
	}
	else if ($filtered>0) {
			switch ($f_field) {
			case('note'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('notes with')." <b>".$f_value."*</b>";
				break;

			}
		}
	else
		$filter_msg='';



	$order=' `Date` desc , `Inventory Transaction Key` desc ';
	$order=' `Date` desc  ';

	$order_direction=' ';

	if ($parent=='part') {
		$sql="select `Inventory Transaction Key`,`Relations`,`User Alias`, ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ";
	}
	else if ($parent=='warehouse') {
			$sql="select  `User Alias`,ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)   $where $wheref limit $start_from,$number_results ";
		}



	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$qty=$data['Inventory Transaction Quantity'];



		if ($qty>0) {
			$qty='+'.$qty;
		}
		else if ($qty==0) {
				$qty='';
			}

		switch ($data['Inventory Transaction Type']) {
		case 'Order In Process':
			$transaction_type='OIP';
			$qty.='('.(-1*$data['Required']).')';
			break;

		case 'Move':
			$transaction_type=_('Move');
			$qty='&harr;';
			break;

		default:
			$transaction_type=$data['Inventory Transaction Type'];
			break;
		}


		if (in_array($data['Inventory Transaction Type'],array('Sale','Order In Process','Adjust','Associate','Disassociate') )) {

			$edit="";
			$delete="";
		}else {
			$delete='<img src="art/icons/delete.gif" alt="'._('Delete').'">';
			$edit='<img src="art/icons/edit.gif" alt="'._('Edit').'">';

		}


		$location=sprintf('<a href="location.php?id=%d">%s</a>',$data['Location Key'],$data {'Location Code'});
		$adata[]=array(
			'transaction_key'=>$data['Inventory Transaction Key'],
			'id'=>$data['Inventory Transaction Key'],

			'type'=>$transaction_type,
			'change'=>$qty,
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'])),
			'subject_data'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'])),
			'note'=>$data['Inventory Transaction Key'].' -> '.$data['Relations'].'*'.$data['Note'],
			'location'=>$location,
			'user'=>$data['User Alias'],
			'delete'=>$delete,
			'edit'=>$edit

		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

?>
