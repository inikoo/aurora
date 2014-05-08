<?php
/*
 File: ar_edit_porders.php

 Ajax Server Anchor for the Order Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyrigh (c) 2010, Inikoo

 Version 2.0
*/
require_once 'common.php';
require_once 'class.PurchaseOrder.php';
include_once 'class.SupplierDeliveryNote.php';
require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$editor=array(
	'User Key'=>$user->id,
);

$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case('dn_transactions_to_stock'):
	dn_transactions_to_stock();
	break;
case('dn_transactions_to_count'):
	dn_transactions_to_count();
	break;

case('edit_porder'):
	edit_porder();
	break;

case('delete_po'):

	$data=prepare_values($_REQUEST,array(

			'id'=>array('type'=>'key')


		));

	delete_purchase_order($data);
	break;
case('delete_dn'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
		));
	delete_supplier_delivery_note($data);
	break;
case('cancel'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'note'=>array('type'=>'string')
		));
	cancel_purchase_order($data);
	break;
case('submit'):


	require_once 'class.Staff.php';
	$data=prepare_values($_REQUEST,array(
			'submit_method'=>array('type'=>'string'),
			'staff_key'=>array('type'=>'number'),
			'submit_date'=>array('type'=>'string'),
			'id'=>array('type'=>'key')


		));
	submit_purchase_order($data);
	break;
case('receive_dn'):
	require_once 'class.Staff.php';
	receive_supplier_delivery_note();
	break;
case('input_dn'):
	require_once 'class.Staff.php';
	input_supplier_delivery_note();
	break;
case('take_values_from_pos'):
	take_values_from_pos();
	break;
case('set_dn_as_checked'):
	set_supplier_delivery_note_as_checked();
	break;
case('take_values_from_dn'):
	take_values_from_dn();
	break;

case('po_transactions_to_process'):
	po_transactions_to_process();
	break;
case('dn_transactions_to_process'):
	dn_transactions_to_process();
	break;
case('dn_transactions_to_count'):
	dn_transactions_to_count();
	break;
case('edit_new_porder'):
	edit_new_porder();
	break;
case('edit_new_supplier_dn'):

	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string'),
			'id'=>array('type'=>'numeric'),
			'supplier_delivery_note_key'=>array('type'=>'key'),
			'sp_key'=>array('type'=>'numeric'),
		));

	edit_new_supplier_dn($data);
	break;
case('edit_inputted_supplier_dn'):
	edit_inputted_supplier_dn();
	break;
default:
	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);
}

function take_values_from_pos() {
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$supplier_dn_key=$_REQUEST['supplier_dn_key'];
		$_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
	} else
		$supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];

	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);
	$supplier_dn->creating_take_values_from_pos();

	if (!$supplier_dn->error) {
		$response= array('state'=>200);
	} else {
		$response= array('state'=>400,'msg'=>$supplier_dn->msg);
	}
	echo json_encode($response);
}
function take_values_from_dn() {
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$supplier_dn_key=$_REQUEST['supplier_dn_key'];
		$_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
	} else
		$supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];

	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);
	$supplier_dn->counting_take_values_from_dn();

	if (!$supplier_dn->error) {
		$response= array('state'=>200);
	} else {
		$response= array('state'=>400,'msg'=>$supplier_dn->msg);
	}
	echo json_encode($response);
}


function delete_purchase_order($data) {

	$purchase_order_key=$data['id'];
	$po=new PurchaseOrder($purchase_order_key);
	$supplier_key=$po->data['Purchase Order Supplier Key'];
	$po->delete();
	if (!$po->error) {
		$response= array('state'=>200,'supplier_key'=>$supplier_key);

	} else {
		$response= array('state'=>400,'msg'=>$po->msg);

	}
	echo json_encode($response);
}


function delete_supplier_delivery_note() {

	$supplier_delivery_note_key=$data['id'];
	$supplier_dn=new SupplierDeliveryNote($supplier_delivery_note_key);
	$supplier_key=$supplier_dn->data['Supplier Delivery Note Supplier Key'];
	$supplier_dn->delete();
	if (!$supplier_dn->error) {
		$response= array('state'=>200,'supplier_key'=>$supplier_key);

	} else {
		$response= array('state'=>400,'msg'=>$supplier_dn->msg);

	}
	echo json_encode($response);
}


function submit_purchase_order($data) {
	global $user;


	$purchase_order_key=$data['id'];

	$po=new PurchaseOrder($purchase_order_key);


	$date=gmdate('Y-m-d H:i:s');


	$staff= new Staff($data['staff_key']);


	$data=array(
		'Purchase Order Submitted Date'=>$date,
		'Purchase Order Main Buyer Key'=>$staff->id,
		'Purchase Order Main Buyer Name'=>$staff->data['Staff Alias'],
		'Purchase Order Main Source Type'=>$data['submit_method'],
		'Purchase Order Estimated Receiving Date'=>''
	);






	$po->submit($data);
	if (!$po->error) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>$po->msg);

	}
	echo json_encode($response);
}


function edit_porder() {

	global $user;


	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$purchase_order_key=$_REQUEST['id'];
		$_SESSION['state']['porder']['id']=$purchase_order_key;
	} else
		$purchase_order_key=$_SESSION['state']['porder']['id'];

	$po=new PurchaseOrder($purchase_order_key);


	$key_dic=array(
		'estimated_delivery'=>'Purchase Order Estimated Receiving Date'


	);
	if (array_key_exists($_REQUEST['key'],$key_dic))
		$key=$key_dic[$_REQUEST['key']];


	$po->update(array($key=>stripslashes(urldecode($_REQUEST['newvalue']))));






	if ($po->updated) {
		$response= array('state'=>200,'newvalue'=>$po->new_value);

	} else {
		$response= array('state'=>400,'msg'=>$po->msg);

	}
	echo json_encode($response);
}





function cancel_purchase_order($data) {
	global $user;
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$purchase_order_key=$_REQUEST['id'];
		$_SESSION['state']['porder']['id']=$purchase_order_key;
	} else
		$purchase_order_key=$data['id'];

	$po=new PurchaseOrder($purchase_order_key);


	$data=array(
		'Purchase Order Cancelled Date'=>gmdate('Y-m-d H:i:s'),
		'Purchase Order Cancel Note'=>$data['note'],
	);



	$po->cancel($data);
	if (!$po->error) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>$po->msg);

	}
	echo json_encode($response);
}

function dn_transactions_to_process() {




	if (isset( $_REQUEST['supplier_dn_key']) and is_numeric( $_REQUEST['supplier_dn_key'])) {
		$supplier_dn_key=$_REQUEST['supplier_dn_key'];
	} else {
		exit('no id');
	}



	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);
	$supplier_key=$supplier_dn->data['Supplier Delivery Note Supplier Key'];

	$pos='';
	if (isset( $_REQUEST['pos'])) {
		$pos=preg_replace('/[^\d\,]/','',$_REQUEST['pos']);

		$_SESSION['state']['supplier_dn']['pos']=$pos;
	} else
		$pos=$_SESSION['state']['supplier_dn']['pos'];

	$conf=$_SESSION['state']['supplier_dn']['products'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	}      else
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



	/*  if (isset( $_REQUEST['where'])) */
	/*         $where=addslashes($_REQUEST['where']); */
	/*     else */
	/*         $where=$conf['where']; */


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


	if (isset( $_REQUEST['display']))
		$display=$_REQUEST['display'];
	else
		$display=$conf['display'];






	$_SESSION['state']['supplier_dn']['products']['order']=$order;
	$_SESSION['state']['supplier_dn']['products']['order_dir']=$order_direction;
	$_SESSION['state']['supplier_dn']['products']['nr']=$number_results;
	$_SESSION['state']['supplier_dn']['products']['sf']=$start_from;
	$_SESSION['state']['supplier_dn']['products']['f_field']=$f_field;
	$_SESSION['state']['supplier_dn']['products']['f_value']=$f_value;
	$_SESSION['state']['supplier_dn']['products']['display']=$display;

	if ($display=='ordered_products') {
		$start_from=0;
		$number_results=1000;

	}

	//`Purchase Order Transaction Fact Key`
	if ($display=='all_products') {
		$table=' `Supplier Product Dimension` PD left join `Supplier Product History Dimension` PHD on (PD.`Supplier Product Current Key`=`SPH Key`)';
		$where=sprintf('where `Supplier Key`=%d   ',$supplier_key);


		$sql_qty=sprintf(
			', PD.`Supplier Product Current Key` as  `Supplier Product Key` ,0 as `Purchase Order Transaction Fact Key`,
                     IFNULL( (select `Purchase Order Quantity Type`   from `Purchase Order Transaction Fact` POTF  where POTF.`Supplier Product Key`=PD.`Supplier Product Current Key` and `Purchase Order Key` in (%s) and `Supplier Delivery Note Key`=%d  limit 1 ),"") as `Purchase Order Quantity Type`  ,
                     IFNULL((select `Supplier Delivery Note Quantity Type` from `Purchase Order Transaction Fact` POTF where POTF.`Supplier Product Key`=PD.`Supplier Product Current Key`  and `Supplier Delivery Note Key`=%d  limit 1),"") as `Supplier Delivery Note Quantity Type`    ,
                     IFNULL((select sum(`Supplier Delivery Note Quantity`) from `Purchase Order Transaction Fact` POTF  where POTF.`Supplier Product Key`=PD.`Supplier Product Current Key`  and `Supplier Delivery Note Key`=%d  ),0) as `Supplier Delivery Note Quantity`   ,
                     IFNULL((select sum(`Purchase Order Quantity`) from `Purchase Order Transaction Fact` POTF where POTF.`Supplier Product Key`=PD.`Supplier Product Current Key` and `Purchase Order Key` in (%s) and `Supplier Delivery Note Key`=%d  ),"") as `Purchase Order Quantity` '
			,$pos
			,$supplier_dn_key
			,$supplier_dn_key
			,$supplier_dn_key
			,$pos
			,$supplier_dn_key

		);




	}
	else {
		$table='  `Purchase Order Transaction Fact` OTF
               left join `Supplier Product History Dimension` PHD on (`SPH Key`=OTF.`Supplier Product Key`)
               left join `Supplier Product Dimension` PD on (PD.`Supplier Product ID`=OTF.`Supplier Product ID`)

               ';
		$where=sprintf(' where  (`Purchase Order Key` in (%s) or `Supplier Delivery Note Key`=%d)',$pos,$supplier_dn_key);
		$sql_qty=',  OTF.`Supplier Product Key`  , `Purchase Order Transaction Fact Key`,`Purchase Order Quantity` ,`Purchase Order Quantity Type` ,`Purchase Order Net Amount`,`Supplier Delivery Note Quantity`,`Supplier Delivery Note Quantity Type`';
	}
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';

	if ($f_field=='code' and $f_value!='')
		$wheref.=" and   `Supplier Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='p.code' and $f_value!='')
		$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table   $where $wheref   ";

	// print_r($conf);exit;
	//  print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Supplier Product Code`';

	if ($order=='code')
		$order='`Supplier Product Code`';
	else if ($order=='name')
			$order='`Supplier Product Name`';

		else if ($order=='parts') {
				$order='`Supplier Product XHTML Parts`';
			}
		elseif ($order=='supplied') {
			$order='`Supplier Product XHTML Supplied By`';
		}




	$sql="select  `Supplier Product XHTML Sold As` ,`Supplier Product Unit Type`,`Supplier Product Tax Code`,`Supplier Product Current Key`,PD.`Supplier Product Code`,`Supplier Product Name`,`SPH Case Cost`,`SPH Units Per Case`,`Supplier Product Unit Type`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$adata=array();
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$unit_type=$row['Purchase Order Quantity Type'];
		if ($unit_type=='ea') {
			$unit_type='piece';
		}

		$dn_unit_type=$row['Supplier Delivery Note Quantity Type'];
		if ($dn_unit_type=='ea') {
			$dn_unit_type='piece';
		}


		if (!$row['Purchase Order Quantity']) {
			$unit_type='';
		}


		if ($row['SPH Units Per Case']!=0)
			$cost='@ '.money($row['SPH Case Cost']/$row['SPH Units Per Case']);
		else
			$cost=' ';

		$adata[]=array(
			'id'=>$row['Purchase Order Transaction Fact Key'],
			'sp_key'=>$row['Supplier Product Key'],
			'code'=>$row['Supplier Product Code'],
			'description'=>'<span style="font-size:95%">'.number($row['SPH Units Per Case']).'x '.$row['Supplier Product Name'].' '.$cost.' '.$row['Supplier Product Unit Type'].'</span>',
			'used_in'=>$row['Supplier Product XHTML Sold As'],
			'quantity'=>$row['Purchase Order Quantity'],
			'quantity_static'=>number($row['Purchase Order Quantity']),
			'dn_quantity'=>$row['Supplier Delivery Note Quantity'],

			'unit_type'=>$unit_type,
			'dn_unit_type'=>$dn_unit_type,
			'tax_code'=>$row['Supplier Product Tax Code'],
			'add'=>'+',
			'remove'=>'-',


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



function po_transactions_to_process() {







	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$purchase_order_key=$_REQUEST['id'];

	} else {
		exit;
	}

	if (isset( $_REQUEST['supplier_key']) and is_numeric( $_REQUEST['supplier_key'])) {
		$supplier_key=$_REQUEST['supplier_key'];

	} else {
		exit;
	}


	$conf=$_SESSION['state']['porder']['products'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	}      else
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





	if (isset( $_REQUEST['display']))
		$display=$_REQUEST['display'];
	else
		$display=$conf['display'];

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



	$_SESSION['state']['porder']['products']['order']=$order;
	$_SESSION['state']['porder']['products']['order_dir']=$order_direction;
	$_SESSION['state']['porder']['products']['nr']=$number_results;
	$_SESSION['state']['porder']['products']['sf']=$start_from;
	$_SESSION['state']['porder']['products']['f_field']=$f_field;
	$_SESSION['state']['porder']['products']['f_value']=$f_value;
	$_SESSION['state']['porder']['products']['display']=$display;






	if ($display=='all_products') {
		$start_from=0;
		$number_results=1000;

	}


	if ($display=='all_products') {
		$table=' `Supplier Product Dimension` PD left join `Supplier Product History Dimension` PHD on (`Supplier Product Current Key`=`SPH Key`)';
		$where=sprintf('where `Supplier Key`=%d   ',$supplier_key);
		$sql_qty=sprintf(',
        IFNULL((select sum(`Purchase Order Quantity`) from `Purchase Order Transaction Fact` POTF where POTF.`Supplier Product ID`=PD.`Supplier Product ID` and `Purchase Order Key`=%d),0) as `Purchase Order Quantity`,
        IFNULL((select sum(`Purchase Order Net Amount`) from `Purchase Order Transaction Fact` POTF where POTF.`Supplier Product ID`=PD.`Supplier Product ID` and `Purchase Order Key`=%d),0) as `Purchase Order Net Amount` ',$purchase_order_key,$purchase_order_key);





	} else {
		$table='  `Purchase Order Transaction Fact` OTF
               left join `Supplier Product History Dimension` PHD on (`SPH Key`=OTF.`Supplier Product Key`)
               left join `Supplier Product Dimension` PD on (OTF.`Supplier Product ID`=PD.`Supplier Product ID`) ';
		$where=sprintf(' where  `Purchase Order Key`=%d',$purchase_order_key);
		$sql_qty=', `Purchase Order Quantity`,`Purchase Order Net Amount`';



	}



	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Supplier Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='p.code' and $f_value!='')
		$wheref.=" and  `Supplier Product XHTML Sold As` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table   $where $wheref   ";

	// print_r($conf);exit;
	//  print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);

	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('p.code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier product used in")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('supplier products with code like')." <b>".$f_value."*</b>";
			break;
		case('p.code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('supplier products used in')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Supplier Product Code`';

	if ($order=='code')
		$order='`Supplier Product Code`';
	else if ($order=='name')
			$order='`Supplier Product Name`';

		elseif ($order=='parts') {
			$order='`Supplier Product XHTML Parts`';
		}
	elseif ($order=='supplied') {
		$order='`Supplier Product XHTML Supplied By`';
	}




	$sql="select  `Supplier Product XHTML Store As`,`SPH Units Per Case`,`Supplier Product XHTML Sold As` ,`Supplier Product Unit Type`,`Supplier Product Tax Code`,`Supplier Product Current Key`,PD.`Supplier Product Code`,`Supplier Product Name`,`SPH Case Cost`,`Supplier Product Unit Type`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$adata=array();
	// print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if ($row['Purchase Order Quantity']==0)
			$amount='';
		else
			$amount=money($row['Purchase Order Net Amount']);

		$unit_type=$row['Supplier Product Unit Type'];
		if ($unit_type=='ea') {
			$unit_type='piece';
		}
		$adata[]=array(
			'id'=>$row['Supplier Product Current Key'],
			'code'=>$row['Supplier Product Code'],
			'description'=>'<span style="font-size:95%">'.number($row['SPH Units Per Case']).'x '.$row['Supplier Product Name'].' @'.money($row['SPH Case Cost']).' '.$row['Supplier Product Unit Type'].'</span>',
			'used_in'=>$row['Supplier Product XHTML Sold As'],
			'store_as'=>$row['Supplier Product XHTML Store As'],


			'quantity'=>$row['Purchase Order Quantity'],
			'quantity_static'=>number($row['Purchase Order Quantity']),
			'amount'=>$amount,
			'unit_type'=>$unit_type,

			'tax_code'=>$row['Supplier Product Tax Code'],
			'add'=>'+',
			'remove'=>'-',


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



function edit_new_porder() {

	$purchase_order_key=$_SESSION['state']['porder']['id'];
	$supplier_product_key=$_REQUEST['id'];
	$quantity=$_REQUEST['newvalue'];

	if (!isset($_REQUEST['qty_type']))
		$quantity_type='ea';
	else
		$quantity_type=$_REQUEST['qty_type'];
	if (is_numeric($quantity) and $quantity>=0) {

		$order=new PurchaseOrder($purchase_order_key);


		$supplier_product=new SupplierProduct('key',$supplier_product_key);

		$gross=$quantity*$supplier_product->data['SPH Case Cost'];


		$data=array(

			'date'=>date('Y-m-d H:i:s')
			,'Supplier Product ID'=>$supplier_product->data['Supplier Product ID']
			,'Supplier Product Key'=>$supplier_product->data['Supplier Product Current Key']

			,'amount'=>$gross
			,'qty'=>$quantity
			,'qty_type'=>$quantity_type
			,'tax_code'=>$supplier_product->data['Supplier Product Tax Code']
			,'Current Dispatching State'=>'In Process'
			,'Current Payment State'=>'Waiting Payment'

		);


		$transaction_data=$order->add_order_transaction($data);


		$adata=array();

		$order->update_item_totals_from_order_transactions();
		$order->update_totals_from_order_transactions();

		//$order->update_charges();
		//$order->get_original_totals();
		// $order->update_totals();
		//$order->update_totals_from_order_transactions();




		$updated_data=array(
			'goods'=>$order->get('Items Net Amount')
			//,'order_net'=>$order->get('Total Net Amount')
			,'vat'=>$order->get('Total Tax Amount')
			//,'order_charges'=>$order->get('Charges Net Amount')
			// ,'order_credits'=>$order->get('Net Credited Amount')
			,'shipping'=>$order->get('Shipping Net Amount')
			,'total'=>$order->get('Total Amount')
			,'distinct_products'=>$order->get('Number Items')
		);







		$response= array('state'=>200,'quantity'=>$transaction_data['qty'],'key'=>$_REQUEST['key'],'data'=>$updated_data,'to_charge'=>$transaction_data['to_charge']);
	} else
		$response= array('state'=>200,'quantity'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['key']);
	echo json_encode($response);

}


function edit_new_supplier_dn($data) {


	$quantity=$_REQUEST['newvalue'];

	if (!isset($data['qty_type']))
		$quantity_type='ea';
	else
		$quantity_type=$data['qty_type'];
	if (is_numeric($quantity) and $quantity>=0) {

		//    $order=new SupplierDeliveryNote($supplier_delivery_note_key);

		// $product=new SupplierProduct('key',$supplier_product_key);

		$_data=array(

			'date'=>gmdate('Y-m-d H:i:s'),
			'Purchase Order Transaction Fact Key'=>$data['id'],
			'Supplier Delivery Note Key'=>$data['supplier_delivery_note_key'],

			//         'Supplier Product Key'=>$product->data['Supplier Product Current Key'],
			//       'Supplier Product ID'=>$product->data['Supplier Product ID'],
			'qty'=>$quantity,
			'qty_type'=>$quantity_type


		);

		if (!$data['id']) {
			$supplier_product=new SupplierProduct('key',$data['sp_key']);
			$_data['Supplier Product Key']=$supplier_product->data['Supplier Product Current Key'];
			$_data['Supplier Product ID']=$supplier_product->data['Supplier Product ID'];
		}


		$sdn=new   SupplierDeliveryNote($data['supplier_delivery_note_key']);
		$transaction_data=$sdn->add_order_transaction($_data);
		$adata=array();
		$updated_data=array(

			'ordered_products_number'=>$sdn->get('Number Items')
			,'ordered_products_number'=>$sdn->get('Number Ordered Items')
			,'ordered_products_number'=>$sdn->get('Number Items')
		);

		$response= array('state'=>200,'quantity'=>$transaction_data['qty'],'key'=>$data['key'],'data'=>$updated_data);
	} else
		$response= array('state'=>400);
	echo json_encode($response);

}

function edit_inputted_supplier_dn() {

	$supplier_delivery_note_key=$_REQUEST['supplier_deliver_note_key'];
	$purchase_order_transaction_fact_key=$_REQUEST['id'];
	$order=new SupplierDeliveryNote($supplier_delivery_note_key);

	//$product=new SupplierProduct('key',$supplier_product_key);




	if ($_REQUEST['key']=='quantity' or $_REQUEST['key']=='received_quantity') {

		$quantity=$_REQUEST['newvalue'];

		if (is_numeric($quantity) and $quantity>=0) {


			$data=array(

				'Supplier Delivery Note Last Updated Date'=>date('Y-m-d H:i:s'),
				'Purchase Order Transaction Fact Key'=>$purchase_order_transaction_fact_key,
				'Supplier Delivery Note Received Quantity'=>$quantity
			);

			//print_r( $data);
			$transaction_data=$order->update_delivered_transaction($data);
			$transaction_data['counted']='Yes';
			$updated_data=array(
				'distinct_products'=>$order->get('Number Items')
			);


			$data=array(

				'Supplier Delivery Note Last Updated Date'=>date('Y-m-d H:i:s'),
				'Purchase Order Transaction Fact Key'=>$purchase_order_transaction_fact_key,
				'Supplier Delivery Note Counted'=>$transaction_data['counted']
			);

			$order->update_transaction_counted($data);


			if ($order->error) {
				$response= array('state'=>400,'msg'=>$order->msg);

			} else {
				$response= array('state'=>200,'damaged_quantity'=>$transaction_data['damaged_qty'],'quantity'=>$transaction_data['qty'],'counted'=>$transaction_data['counted'],'key'=>$_REQUEST['key'],'data'=>$updated_data);
			}
			echo json_encode($response);

		} else {
			$response= array('state'=>200,'quantity'=>$_REQUEST['old_quantity'],'counted'=>$_REQUEST['old_counted'],'key'=>$_REQUEST['key']);
			echo json_encode($response);
		}



	} else if ($_REQUEST['key']=='counted') {
			$data=array(

				'Supplier Delivery Note Last Updated Date'=>date('Y-m-d H:i:s'),
				'Purchase Order Transaction Fact Key'=>$purchase_order_transaction_fact_key,
				'Supplier Delivery Note Counted'=>$_REQUEST['newvalue']
			);

			$transaction_data=$order->update_transaction_counted($data);

			$updated_data=array();
			// print_r($transaction_data);
			$response= array('state'=>200,'quantity'=>$transaction_data['qty'],'damaged_quantity'=>$transaction_data['damaged_qty'],'counted'=>$transaction_data['counted'],'key'=>$_REQUEST['key'],'data'=>$updated_data);
			echo json_encode($response);
		}
	if ( $_REQUEST['key']=='damaged_quantity') {

		$quantity=$_REQUEST['newvalue'];

		if (is_numeric($quantity) and $quantity>=0) {


			$data=array(

				'Supplier Delivery Note Last Updated Date'=>date('Y-m-d H:i:s'),
				'Purchase Order Transaction Fact Key'=>$purchase_order_transaction_fact_key,
				'Supplier Delivery Note Damaged Quantity'=>$quantity
			);

			//print_r( $data);
			$transaction_data=$order->update_damaged_transaction($data);
			$transaction_data['counted']='Yes';
			$updated_data=array(
				'distinct_products'=>$order->get('Number Items')
			);


			$data=array(

				'Supplier Delivery Note Last Updated Date'=>date('Y-m-d H:i:s'),
				'Purchase Order Transaction Fact Key'=>$purchase_order_transaction_fact_key,
				'Supplier Delivery Note Counted'=>'Yes'
			);

			$order->update_transaction_counted($data);


			if ($order->error) {
				$response= array('state'=>400,'msg'=>$order->msg);

			} else {
				$response= array('state'=>200,'quantity'=>$transaction_data['qty'],'damaged_quantity'=>$transaction_data['damaged_qty'],'counted'=>$transaction_data['counted'],'key'=>$_REQUEST['key'],'data'=>$updated_data);
			}
			echo json_encode($response);

		} else {
			$response= array('state'=>200,'quantity'=>$_REQUEST['old_quantity'],'counted'=>$_REQUEST['old_counted'],'damaged_quantity'=>$_REQUEST['old_damaged_quantity'],'key'=>$_REQUEST['key']);
			echo json_encode($response);
		}



	}


}


function input_supplier_delivery_note() {
	global $user;
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$supplier_delivery_note_key=$_REQUEST['id'];
		$_SESSION['state']['supplier_dn']['id']=$supplier_delivery_note_key;
	} else
		$supplier_delivery_note_key=$_SESSION['state']['supplier_dn']['id'];

	$dn=new SupplierDeliveryNote($supplier_delivery_note_key);

	$data=array(
		'Supplier Delivery Note Input Date'=>date('Y-m-d H:i:s'),
		'Supplier Delivery Note Main Inputter Key'=>$user->data['User Parent Key'],
	);


	if (isset($_REQUEST['staff_key'])  ) {

		$staff=new Staff($_REQUEST['staff_key']);
		if (!$staff->id) {
			$response= array('state'=>400,'msg'=>'Wrong Inputter');
			echo json_encode($response);
			return;
		}

		$data['Supplier Delivery Note Main Inputter Key']=$staff->id;

	}


	$dn->input($data);




	if (!$dn->error) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>$dn->msg);

	}
	echo json_encode($response);
}

function dn_transactions_to_count() {

	if (isset( $_REQUEST['supplier_dn_key']) and is_numeric( $_REQUEST['supplier_dn_key'])) {
		$supplier_dn_key=$_REQUEST['supplier_dn_key'];
	} else {
		exit;
	}


	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);
	$supplier_key=$supplier_dn->data['Supplier Delivery Note Supplier Key'];



	$conf=$_SESSION['state']['supplier_dn']['products'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	}      else
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



	/*  if (isset( $_REQUEST['where'])) */
	/*         $where=addslashes($_REQUEST['where']); */
	/*     else */
	/*         $where=$conf['where']; */


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['display']))
		$display=$_REQUEST['display'];
	else
		$display=$conf['display'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	//    if(isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ){
	//      if($_REQUEST['show_all']=='yes')
	// $show_all=true;
	//  else
	// $show_all=false;
	//  $_SESSION['state']['supplier_dn']['show_all']=$show_all;
	//}else
	//  $show_all=$_SESSION['state']['supplier_dn']['show_all'];




	//    print_r($_SESSION['state']['supplier_dn']);

	$_SESSION['state']['supplier_dn']['products']['order']=$order;
	$_SESSION['state']['supplier_dn']['products']['order_dir']=$order_dir;
	$_SESSION['state']['supplier_dn']['products']['nr']=$number_results;
	$_SESSION['state']['supplier_dn']['products']['sf']=$start_from;
	$_SESSION['state']['supplier_dn']['products']['f_field']=$f_field;
	$_SESSION['state']['supplier_dn']['products']['f_value']=$f_value;
	$_SESSION['state']['supplier_dn']['products']['display']=$display;




	if ($display=='ordered_products') {
		$start_from=0;
		$number_results=1000;

	}



	$table='  `Purchase Order Transaction Fact` OTF  left join `Supplier Product History Dimension` PHD on (`SPH Key`=OTF.`Supplier Product Key`) left join `Supplier Product Dimension` PD on (PD.`Supplier Product Current Key`=PHD.`SPH Key`) ';
	$where=sprintf(' where  `Supplier Delivery Note Key`=%d',$supplier_dn_key);
	$sql_qty=',`Purchase Order Transaction Fact Key`,`Supplier Delivery Note Damaged Quantity`, `Supplier Delivery Note Received Quantity`, `Supplier Delivery Note Counted` ,`Supplier Delivery Note Quantity`,`Supplier Delivery Note Quantity Type`';

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table   $where $wheref   ";

	// print_r($conf);exit;

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Supplier Product Code`';

	if ($order=='code')
		$order='`Supplier Product Code`';
	else if ($order=='name')
			$order='`Supplier Product Name`';

		elseif ($order=='parts') {
			$order='`Supplier Product XHTML Parts`';
		}
	elseif ($order=='supplied') {
		$order='`Supplier Product XHTML Supplied By`';
	}



	$sql="select  `Supplier Product XHTML Sold As` ,`Supplier Product Unit Type`,`Supplier Product Tax Code`,`Supplier Product Current Key`,PD.`Supplier Product Code`,`Supplier Product Name`,`SPH Case Cost`,`SPH Units Per Case`,`Supplier Product Unit Type`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$adata=array();
	print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




		$dn_unit_type=$row['Supplier Delivery Note Quantity Type'];
		if ($dn_unit_type=='ea') {
			$dn_unit_type='piece';
		}

		if ($row['Supplier Delivery Note Damaged Quantity']!=0)
			$notes='('.-1.*$row['Supplier Delivery Note Damaged Quantity'].')';
		else
			$notes='';


		if ($row['SPH Units Per Case']!=0)
			$cost=  ' @'.money($row['SPH Case Cost']/$row['SPH Units Per Case']).' ';
		else
			$cost=' ';

		$adata[]=array(
			'id'=>$row['Purchase Order Transaction Fact Key'],
			'code'=>$row['Supplier Product Code'],
			'description'=>'<span style="font-size:95%">'.number($row['SPH Units Per Case']).'x '.$row['Supplier Product Name'].$cost.$row['Supplier Product Unit Type'].'</span>',
			'used_in'=>$row['Supplier Product XHTML Sold As'],
			'received_quantity'=>$row['Supplier Delivery Note Received Quantity'],
			'damaged_quantity'=>$row['Supplier Delivery Note Damaged Quantity'],
			'notes_damaged'=>$notes,

			'counted'=>$row['Supplier Delivery Note Counted'],

			'dn_quantity'=>$row['Supplier Delivery Note Quantity'],


			'dn_unit_type'=>$dn_unit_type,
			'add_damaged'=>'+',
			'remove_damaged'=>'-',

			'add'=>'+',
			'remove'=>'-',


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



function dn_transactions_to_stock() {

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$supplier_dn_key=$_REQUEST['supplier_dn_key'];
		$_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
	} else
		$supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];


	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);
	$supplier_key=$supplier_dn->data['Supplier Delivery Note Supplier Key'];



	$conf=$_SESSION['state']['supplier_dn']['products'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;


	$number_results=1000;

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	/*  if (isset( $_REQUEST['where'])) */
	/*         $where=addslashes($_REQUEST['where']); */
	/*     else */
	/*         $where=$conf['where']; */


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


	$show_all=false;



	$_SESSION['state']['supplier_dn']['products']['order']=$order;
	$_SESSION['state']['supplier_dn']['products']['order_dir']=$order_direction;
	$_SESSION['state']['supplier_dn']['products']['nr']=$number_results;
	$_SESSION['state']['supplier_dn']['products']['sf']=$start_from;
	$_SESSION['state']['supplier_dn']['products']['f_field']=$f_field;
	$_SESSION['state']['supplier_dn']['products']['f_value']=$f_value;





	$table="`Supplier Delivery Note Item Part Bridge` P left join `Purchase Order Transaction Fact` F on (P.`Purchase Order Transaction Fact Key`=F.`Purchase Order Transaction Fact Key`)  left join `Part Location Dimension` PLD on (P.`Part SKU`=PLD.`Part SKU` and  F.`Supplier Delivery Note Received Location Key`=`Location Key` )  left join `Part Dimension` PA on (PA.`Part SKU`=P.`Part SKU`) left join `Supplier Product History Dimension` SPH on (SPH.`SPH Key`=F.`Supplier Product ID`) left join `Supplier Product Dimension` SP on (SPH.`SPH Key`=SP.`Supplier Product Current Key`)";
	$where=sprintf(' where F.`Supplier Delivery Note Key`=%d',$supplier_dn_key);
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table     $where   ";

	// print_r($conf);exit;
	//  print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select  count(*) as total from $table   $where $wheref   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}


	$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$_order='sku';
	$order='PA.`Part SKU`';

	// if ($order=='code')
	//   $order='SP.`Supplier Product Code`';
	// else if ($order=='name')
	//   $order='SP.`Supplier Product Name`';

	//  elseif($order=='parts') {
	//   $order='`Supplier Product XHTML Parts`';
	// }
	// elseif($order=='supplied') {
	//   $order='`Supplier Product XHTML Supplied By`';
	// }




	$sql="select `Purchase Order Transaction Fact Key`,`Supplier Delivery Note Received Location Key`,`Quantity On Hand`,  `Part XHTML Currently Used In`,`Supplier Product Code`,`Part Unit Description`,`Supplier Delivery Note Damaged Quantity`,`Supplier Product XHTML Sold As`,`Supplier Delivery Note Quantity Type`,`Part Quantity`,`Done`,PA.`Part SKU`,`Notes`, `Supplier Product Unit Type`,`SPH Case Cost`,`SPH Units Per Case`,`Supplier Product Name`,`Supplier Delivery Note Received Quantity`

    from $table  $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$adata=array();
	// print $sql;


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$dn_unit_type=$row['Supplier Delivery Note Quantity Type'];
		if ($dn_unit_type=='ea') {
			$dn_unit_type='piece';
		}

		// if ($row['Supplier Delivery Note Damaged Quantity']!=0)
		//     $notes='('.-1.*$row['Supplier Delivery Note Damaged Quantity'].')';
		// else
		//     $notes='';


		if ($row['Part Quantity']>$row['Quantity On Hand'])
			$qty=$row['Quantity On Hand'];
		else
			$qty=$row['Part Quantity'];
		$notes=sprintf('SKUs to place: <button class="option" onClick="place(this)" sku="%d" qty="%d" old_location_key="%d"   potfk="%d"    >%s</button>',
			$row['Part SKU'],
			$qty,
			$row['Supplier Delivery Note Received Location Key'],
			$row['Purchase Order Transaction Fact Key'],
			number($qty)
		);



		if ($row['SPH Units Per Case']!='')
			$cost=  ' @'.money($row['SPH Case Cost']/$row['SPH Units Per Case']).' ';
		else
			$cost=' ';
		$adata[]=array(
			//   'id'=>$row['Supplier Product Current Key'],
			'code'=>$row['Supplier Product Code'],
			'description'=>'<span style="font-size:95%">'.number($row['SPH Units Per Case']).'x '.$row['Supplier Product Name'].$cost.$row['Supplier Product Unit Type'].'</span>',
			'used_in'=>$row['Supplier Product XHTML Sold As'],
			'to_stock_quantity'=>$qty,
			'sku'=>sprintf("<a href='part.php?sku=%d'>SKU%05d</a>",$row['Part SKU'],$row['Part SKU']),
			'sku_name'=>$row['Part Unit Description'].'<br/>'.$row['Part XHTML Currently Used In'],
			'part_quantity'=>$row['Part Quantity'],
			'notes'=>$notes,
			'done'=>$row['Done'],
			'sp_data'=>'('.$row['Supplier Product Code'].') <span style="font-size:95%">'.number($row['SPH Units Per Case']).'x '.$row['Supplier Product Name'].$cost.$row['Supplier Product Unit Type'].'</span> <span style="font-size:110%;font-weight:800"> To Place: '.($qty).'</span>'


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







function receive_supplier_delivery_note() {
	global $user,$editor;
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$supplier_dn_key=$_REQUEST['id'];
		$_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
	} else
		$supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];

	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);
	$supplier_dn->editor=$editor;
	$data=array(
		'Supplier Delivery Note Received Date'=>date('Y-m-d H:i:s'),
		'Supplier Delivery Note Main Receiver Key'=>$user->data['User Parent Key'],
		'Supplier Delivery Note Received Location Key'=>1,

	);

	if (isset($_REQUEST['date_type']) and $_REQUEST['date_type']=='manual' ) {
		if (isset($_REQUEST['received_date']) and  isset($_REQUEST['received_time']) ) {
			$_date=$_REQUEST['received_date'].' '.$_REQUEST['received_time'];
			$date_data=prepare_mysql_datetime($_date);
			if (!$date_data['ok']) {
				$response= array('state'=>400,'msg'=>_('Wrong date/time'));
				echo json_encode($response);
				return;
			}
			$data['Supplier Delivery Note Received Date']=$date_data['mysql_date'];
		}
	}

	if (isset($_REQUEST['location_key'])  ) {

		$location=new Location($_REQUEST['location_key']);
		if (!$location->id) {
			$response= array('state'=>400,'msg'=>'Wrong location');
			echo json_encode($response);
			return;
		}

		$data['Supplier Delivery Note Received Location Key']=$location->id;



	}

	if (isset($_REQUEST['staff_key'])  ) {

		$staff=new Staff($_REQUEST['staff_key']);
		if (!$staff->id) {
			$response= array('state'=>400,'msg'=>'Wrong receiver');
			echo json_encode($response);
			return;
		}

		$data['Supplier Delivery Note Main Receiver Key']=$staff->id;



	}


	$supplier_dn->mark_as_received($data);
	if (!$supplier_dn->error) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>$supplier_dn->msg);

	}
	echo json_encode($response);
}



function set_supplier_delivery_note_as_checked() {
	global $user;
	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$supplier_dn_key=$_REQUEST['id'];
		$_SESSION['state']['supplier_dn']['id']=$supplier_dn_key;
	} else
		$supplier_dn_key=$_SESSION['state']['supplier_dn']['id'];

	$supplier_dn=new SupplierDeliveryNote($supplier_dn_key);

	$data=array(
		'Supplier Delivery Note Checked Date'=>date('Y-m-d H:i:s'),
		'Supplier Delivery Note Main Checker Key'=>$user->data['User Parent Key'],
	);




	if (isset($_REQUEST['staff_key'])  ) {

		$staff=new Staff($_REQUEST['staff_key']);
		if (!$staff->id) {
			$response= array('state'=>400,'msg'=>'Wrong checker');
			echo json_encode($response);
			return;
		}

		$data['Supplier Delivery Note Main Checker Key']=$staff->id;



	}


	$supplier_dn->mark_as_checked($data);
	if (!$supplier_dn->error) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>$supplier_dn->msg);

	}
	echo json_encode($response);
}
