<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2014 14:13:03 BST, Leeds , UK
 Copyright (c) 2013, Inikoo
*/

require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

if (count($user->stores)==0) return;


$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('payments'):
	list_payments();
	break;

default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);
}

function list_deals() {



	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];
	else {
		exit("no parent arg");
	}

	if ( isset($_REQUEST['parent_key']))
		$parent_key= $_REQUEST['parent_key'];
	else {
		exit("no parent key arg");
	}

	$conf=$_SESSION['state'][$parent]['payments'];



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


	$_SESSION['state'][$parent]['payments']['order']=$order;
	$_SESSION['state'][$parent]['payments']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['payments']['nr']=$number_results;
	$_SESSION['state'][$parent]['payments']['sf']=$start_from;
	$_SESSION['state'][$parent]['payments']['f_field']=$f_field;
	$_SESSION['state'][$parent]['payments']['f_value']=$f_value;

	switch($parent){
	
		case 'store':
				$where=sprintf("where `Payment Store Key`=%d ",$parent_key);
break;
		case 'payment_service_provider':
				$where=sprintf("where `Payment Service Provider Key`=%d ",$parent_key);
break;
	case 'payment_account':
				$where=sprintf("where `Payment Account Key`=%d ",$parent_key);
break;
	case 'none':
				$where=sprintf("where true ",$parent_key);
break;
	
	
	}

	$filter_msg='';
	$wheref='';
	if ($f_field=='id' and $f_value!='')
		$wheref.=" and  `Payment ID`='".addslashes($f_value)."%'";


	$sql="select count(distinct `Payment Key`) as total from `Payment Dimension`  $where $wheref";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count( distinct `Payment Key`) as total_without_filters from `Payment Dimension`  $where ";


		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('deal','deals',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>=10)
		$rtext_rpp='('._("Showing all").')';
	else
		$rtext_rpp='';






	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any payment with this id")." <b>".$f_value."*</b> ";
			break;
//		case('code'):
//			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this code ")." <b>*".$f_value."*</b> ";
//			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('payments with id')." <b>".$f_value."*</b>";
			break;
//		case('code'):
//			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with code like')." <b>*".$f_value."*</b>";
//			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='key')
		$order='`Payment Key`';
	elseif ($order=='date')
		$order='`Payment Date`';
	elseif ($order=='amount')
		$order='`Payment Amount`';
	elseif ($order=='method')
		$order='`Payment Account Code`,`Payment Method`';


	$sql="select *  from `Payment Dimension`  $where  $wheref   order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$key=sprintf('<a href="payment.php?id=%d">%s</a>',$row['Deal Key'],$referrer,$row['Deal Name']);
		$code=sprintf('<a href="deal.php?id=%d&referrer=%s">%s</a>',$row['Deal Key'],$referrer,$row['Deal Code']);

		$orders=number($row['Deal Total Acc Used Orders']);
		$customers=number($row['Deal Total Acc Used Customers']);






		$duration='';
		if ($row['Deal Expiration Date']=='' and $row['Deal Begin Date']=='') {
			$duration=_('Permanent');
		}else {

			if ($row['Deal Begin Date']!='') {
				$duration=strftime("%x", strtotime($row['Deal Begin Date']." +00:00"));

			}
			$duration.=' - ';
			if ($row['Deal Expiration Date']!='') {
				$duration.=strftime("%x", strtotime($row['Deal Expiration Date']." +00:00"));

			}else {
				$duration.=_('Present');
			}

		}




		$store=sprintf("<a href='marketing.php?store=%d'>%s</a>",$row['Deal Store Key'],$row['Store Code']);


		$adata[]=array(
			'store'=>$store,
			'code'=>$code,
			'name'=>$name,
			'description'=>'<b>'.$row['Deal Name'].'</b><br/>'.$row['Deal Description'],
			'orders'=>$orders,
			'customers'=>$customers,
			'duration'=>$duration


		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array(

			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered


		)
	);
	echo json_encode($response);

}

?>
