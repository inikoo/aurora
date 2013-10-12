<?php

/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

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
case('campaigns'):
	list_campaigns();
	break;
case('deals'):
	list_deals();
	break;
case('orders_with_deal'):
	$can_see_customers=$user->can_view('customers');
	list_orders_with_deal( $can_see_customers);
	break;
case('deal_data'):
	$data=prepare_values($_REQUEST,array(
			'deal_key'=>array('type'=>'key')
		));
	deal_data($data);
	break;
case('customers_who_use_deal'):
	list_customers_who_use_deal();
	break;
case('code_in_other_deal'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string'),
			'store_key'=>array('type'=>'key'),
			'deal_key'=>array('type'=>'key')
		));
	code_in_other_deal($data) ;
	break;	
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);
}



function deal_data($data) {

require_once 'class.Deal.php';

$deal_key=$data['deal_key'];
$deal=new Deal($deal_key);

$deal_data=array(
	'name'=>$deal->data['Deal Name'],
	'description'=>$deal->data['Deal Description'],
	'key'=>$deal->id,
);

$response=
	array('state'=>200,
	'data'=>$deal_data


);
echo json_encode($response);

}


function code_in_other_deal($data) {
$email=_trim($data['query']);
$sql=sprintf('select `Deal Code`,`Deal Key` from `Deal Dimension` where `Deal Code`=%s and `Deal Key`!=%d and `Store Key`=%d ',
	prepare_mysql($email),
	$data['customer_key'],
	$data['store_key']
);
$result=mysql_query($sql);
//print $sql;
$num_rows = mysql_num_rows($result);
if ($num_rows==0) {
	$response=array('state'=>200,'found'=>0,'msg'=>'');
	echo json_encode($response);
} else {
	$deals='';

	while ($row=mysql_fetch_assoc($result)) {
		$deals.=sprintf(', <a href="deal.php?id=%d">%s (%d)</a>',$row['Deal Key'],$row['Deal Name'],$row['Deal Key']);
	}
	$deals=preg_replace('/^, /','',$deals);



	$response=array('state'=>200,'found'=>1,'msg'=>_('Code found in another').' '.ngettext('offer','offers',$num_rows).'. '.$deals);
	echo json_encode($response);
}
}

function list_customers_who_use_deal() {

global $myconf;
$conf=$_SESSION['state']['deal']['customers'];

$deal_key=$_REQUEST['deal_key'];

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
$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
$_SESSION['state']['deal']['customers']['order']=$order;
$_SESSION['state']['deal']['customers']['order_dir']=$order_direction;
$_SESSION['state']['deal']['customers']['nr']=$number_results;
$_SESSION['state']['deal']['customers']['sf']=$start_from;
$_SESSION['state']['deal']['customers']['f_field']=$f_field;
$_SESSION['state']['deal']['customers']['f_value']=$f_value;


$_order=$order;
$_dir=$order_direction;
$filter_msg='';

$table=' `Order Deal Bridge` B left join  `Order Dimension` O on (O.`Order Key`=B.`Order Key`) left join `Customer Dimension` CD on (`Order Customer Key`=CD.`Customer Key`)          ';

$where=sprintf(" where `Deal Key`=%d and `Used`='Yes' ",$deal_key);




$wheref="";

//    if ($f_field=='max' and is_numeric($f_value) )
//       $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
//  elseif ($f_field=='min' and is_numeric($f_value) )
//     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
if ($f_field=='name'  and $f_value!='')
	$wheref.=" and `Customer Name` like '".addslashes($f_value)."%'";
elseif ($f_field=='country' and  $f_value!='') {
	if ($f_value=='UNK') {
		$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
		$find_data=' '._('a unknown country');
	} else {

		$f_value=Address::parse_country($f_value);
		if ($f_value!='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$country=new Country('code',$f_value);
			$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
		}

	}
}

$sql="select count(distinct `Customer Key`) as total from  $table  $where $wheref";
//   print $mode.' '.$sql;
$res = mysql_query($sql);
if ($row=mysql_fetch_array($res)) {
	$total=$row['total'];
}
mysql_free_result($res);
if ($wheref=='') {
	$filtered=0;
	$total_records=$total;
} else {
	$sql="select count(distinct `Customer Key`) as total from  $table  $where      ";

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total_records=$row['total'];
		$filtered=$total_records-$total;
	}
	mysql_free_result($res);
}


$rtext=$total_records." ".ngettext('customer','customers',$total_records);
if ($total_records>$number_results)
	$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
else
	$rtext_rpp=_("Showing all customers");




if ($total==0 and $filtered>0) {
	switch ($f_field) {
	case('name'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
		break;
	case('postcode'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
		break;
	case('country'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
		break;

	case('id'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
		break;

	case('last_more'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
		break;
	case('last_more'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
		break;
	case('maxvalue'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
		break;
	case('minvalue'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
		break;


	}
}
elseif ($filtered>0) {
	switch ($f_field) {
	case('name'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
		break;
	case('id'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
		break;
	case('postcode'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
		break;
	case('country'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
		break;
	case('last_more'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
		break;
	case('last_less'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
		break;
	case('maxvalue'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
		break;
	case('minvalue'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
		break;
	}
}
else
	$filter_msg='';


$_order=$order;
$_dir=$order_direction;

if ($order=='orders')
	$order='orders';
elseif ($order=='location')
	$order='`Customer Main Location`';
else
	$order='`Customer Name`';


$sql="select   CD.`Customer Key` as customer_id,`Customer Name`,`Customer Main Location`,count(distinct O.`Order Key`) as orders  from    $table   $where $wheref  group by `Customer Key`    order by $order $order_direction  limit $start_from,$number_results ";


$data=array();
//print $sql;
$res = mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

	$id="<a href='customer.php?p=cs&id=".$row['customer_id']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$row['customer_id']).'</a>';

	$data[]=array(
		'id'=>$id,
		'name'=>sprintf('<a href="customer.php?id=%d">%s</a>',$row['customer_id'],$row['Customer Name']),
		'location'=>$row['Customer Main Location'],
		// 'charged'=>money($row['charged']),
		'orders'=>number($row['orders']),
		// 'to_dispatch'=>number($row['to_dispatch']),
		// 'dispatched'=>number($row['dispatched']),
		// 'nodispatched'=>number($row['nodispatched'])

	);
}
mysql_free_result($res);

$response=array('resultset'=>
	array(
		'state'=>200,
		'data'=>$data,
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

function list_orders_with_deal($can_see_customers=false) {

	$conf=$_SESSION['state']['deal']['orders'];


	$deal_key=$_REQUEST['deal_key'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (!is_numeric($start_from))
		$start_from=0;

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



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['deal']['orders']['order']=$order;
	$_SESSION['state']['deal']['orders']['order_dir']=$order_direction;
	$_SESSION['state']['deal']['orders']['nr']=$number_results;
	$_SESSION['state']['deal']['orders']['sf']=$start_from;
	$_SESSION['state']['deal']['orders']['where']=$where;
	$_SESSION['state']['deal']['orders']['f_field']=$f_field;
	$_SESSION['state']['deal']['orders']['f_value']=$f_value;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$where=sprintf(" where `Deal Key`=%d and `Used`='Yes' ",$deal_key);


	$wheref='';

	if (($f_field=='customer_name')  and $f_value!='') {
		$wheref="  and  `Order Customer Name` like '%".addslashes($f_value)."%'";
	}
	elseif (($f_field=='postcode')  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='public_id')
		$wheref.=" and  `Order Public ID`  like '".addslashes($f_value)."%'";

	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {
			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Order Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img style="vertical-align: text-bottom;position:relative;bottom:2px" src="art/flags/'.strtolower($country->data['Country 2 Alpha Code']).'.gif" alt="'.$country->data['Country Code'].'"/>';
			}
		}
	}



	$sql="select count(DISTINCT B.`Order Key`) as total from `Order Deal Bridge` B left join  `Order Dimension` O on (O.`Order Key`=B.`Order Key`)    $where $wheref";
	// print $sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(DISTINCT B.`Order Key`) as total from  `Order Deal Bridge` B left join  `Order Dimension` O on (O.`Order Key`=B.`Order Key`)  $where      ";
		$res = mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._("Showing all").')';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with customer like")." <b>$f_value</b> ";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with number like")." <b>$f_value</b> ";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with balance")."< <b>".money($f_value,$currency)."</b> ";
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with balance")."> <b>".money($f_value,$currency)."</b> ";
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order from")." <b>".$find_data."</b> ";
			break;

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with name like')." <b>*".$f_value."*</b>";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with Number like')." <b>".$f_value."*</b>";
			break;
		case('maxvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('which balance')."< ".money($f_value,$currency);
			break;
		case('minvalue'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('which balance')."> ".money($f_value,$currency);
			break;
		case('country'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('from')." ".$find_data;
			break;
		}
	}
	else
		$filter_msg='';







	if ($order=='order') {
		$order='`Order Public ID`';

	} else {
		$order='`Order Date`';

	}


	$sql=sprintf("select * from `Order Deal Bridge` B left join  `Order Dimension` O on (O.`Order Key`=B.`Order Key`)    %s %s     order by  $order $order_direction  limit $start_from,$number_results"
		,$where
		,$wheref
	);
	//  print $sql;

	$res=mysql_query($sql);
	$data=array();

	while ($row= mysql_fetch_array($res, MYSQL_ASSOC) ) {
		if ($can_see_customers)
			$customer='<a href="customer.php?id='.$row['Order Customer Key'].'">'.$row['Order Customer Name'].'</a>';
		else
			$customer=$myconf['customer_id_prefix'].sprintf("%05d",$row['Order Customer Key']);



		$data[]=array(
			'order'=>sprintf("<a href='order.php?id=%d'>%s</a>",$row['Order Key'],$row['Order Public ID']),
			'customer_name'=>$customer,
			'date'=> strftime("%e %b %y", strtotime($row['Order Date'].' +0:00')),


		);
	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$data,
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

function list_campaigns() {



if (!isset($_REQUEST['parent']) or !isset($_REQUEST['parent_key'])) {

	exit("no parent");
}

$parent= $_REQUEST['parent'];
$parent_key=$_REQUEST['parent_key'];

$conf=$_SESSION['state'][$parent]['campaigns'];


if (isset( $_REQUEST['sf']))
	$start_from=$_REQUEST['sf'];
else
	$start_from=$conf['sf'];


if (isset( $_REQUEST['nr'])) {
	$number_results=$_REQUEST['nr'];
	if ($start_from>0) {
		$page=floor($start_from/$number_results);
		$start_from=$start_from-$page;
	}

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



$_SESSION['state'][$parent]['campaigns']['order']=$order;
$_SESSION['state'][$parent]['campaigns']['order_dir']=$order_direction;
$_SESSION['state'][$parent]['campaigns']['nr']=$number_results;
$_SESSION['state'][$parent]['campaigns']['sf']=$start_from;
$_SESSION['state'][$parent]['campaigns']['f_field']=$f_field;
$_SESSION['state'][$parent]['campaigns']['f_value']=$f_value;



if ($parent=='store')
	$where=sprintf("where  `Store Key`=%d    ",$parent_key);
else
	$where=sprintf("where true ");;

$filter_msg='';
$wheref='';
if ($f_field=='description' and $f_value!='')
	$wheref.=" and  `Deal Campaign Description` like '".addslashes($f_value)."%'";
elseif ($f_field=='name' and $f_value!='')
	$wheref.=" and  `Deal Campaign Name` like '".addslashes($f_value)."%'";

$sql="select count(*) as total from `Deal Campaign Dimension`   $where $wheref";
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
	$sql="select count(*) as total `Deal Campaign Dimension`   $where ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total_records=$row['total'];
		$filtered=$total_records-$total;
	}
	mysql_free_result($result);

}


$rtext=$total_records." ".ngettext('campaign','campaigns',$total_records);
if ($total_records>$number_results)
	$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
else
	$rtext_rpp=' ('._('Showing all').')';

if ($total==0 and $filtered>0) {
	switch ($f_field) {
	case('name'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with this name ")." <b>".$f_value."*</b> ";
		break;
	case('description'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with description like ")." <b>".$f_value."*</b> ";
		break;
	}
}
elseif ($filtered>0) {
	switch ($f_field) {
	case('name'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with name like')." <b>".$f_value."*</b>";
		break;
	case('description'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with description like')." <b>".$f_value."*</b>";
		break;
	}
}
else
	$filter_msg='';

$_dir=$order_direction;
$_order=$order;

if ($order=='name')
	$order='`Deal Campaign Name`';
elseif ($order=='description')
	$order='`Deal Campaign Description`';
else
	$order='`Deal Campaign Name`';


$sql="select *  from `Deal Campaign Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

$res = mysql_query($sql);

$total=mysql_num_rows($res);
$adata=array();
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

	//$sql=sprintf("select * from `Campaign Deal Schema`  where `Deal Key`=%d  ",$row['Deal Key']);
	//$res2 = mysql_query($sql);

	//$deals='<ul style="padding:10px 20px">';
	//while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
	//  $deals.=sprintf("<li style='list-style-type: circle' >%s</li>",$row2['Deal Metadata Name']);
	// }
	// $deals.='</ul>';

	$deals='';
	$used=0;

	if (!$row['Deal Campaign Valid To'] ) {
		$duration=_('Permanent');
	} else {
		if (!$row['Deal Campaign Valid From']) {
			$duration=strftime("%c", $row['Deal Campaign Valid From']." +00:00").' - ';
		}
		$duration.=strftime("%c", $row['Deal Campaign Valid To']." +00:00");
	}



	$adata[]=array(
		'name'=>$row['Deal Campaign Name'],
		'description'=>$row['Deal Campaign Description'].$deals,
		'duration'=>$duration,
		'used'=>$used
	);
}
mysql_free_result($res);



// if($total<$number_results)
//  $rtext=$total.' '.ngettext('store','stores',$total);
//else
//  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_deals() {


$parent='store';

if ( isset($_REQUEST['parent']))
	$parent= $_REQUEST['parent'];

if ($parent=='store' or $parent=='store_with_children')
	$parent_id=$_REQUEST['parent_key'];
elseif ($parent=='department')
	$parent_id=$_REQUEST['parent_key'];
elseif ($parent=='family')
	$parent_id=$_REQUEST['parent_key'];
elseif ($parent=='product')
	$parent_id=$_REQUEST['parent_key'];
else
	return;


if ($parent=='store_with_children') {
	$conf=$_SESSION['state']['store_offers']['offers'];

} else {
	$conf=$_SESSION['state'][$parent]['offers'];
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

if ($parent=='store_with_children') {
	$_SESSION['state']['store_offers']['offers']['order']=$order;
	$_SESSION['state']['store_offers']['offers']['order_dir']=$order_direction;
	$_SESSION['state']['store_offers']['offers']['nr']=$number_results;
	$_SESSION['state']['store_offers']['offers']['sf']=$start_from;
	$_SESSION['state']['store_offers']['offers']['where']=$where;
	$_SESSION['state']['store_offers']['offers']['f_field']=$f_field;
	$_SESSION['state']['store_offers']['offers']['f_value']=$f_value;


	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];



	if (isset( $_REQUEST['elements_order'])) {
		$elements['Order']=$_REQUEST['elements_order'];
	}
	if (isset( $_REQUEST['elements_department'])) {
		$elements['Department']=$_REQUEST['elements_department'];
	}
	if (isset( $_REQUEST['elements_family'])) {
		$elements['Family']=$_REQUEST['elements_family'];
	}
	if (isset( $_REQUEST['elements_product'])) {
		$elements['Product']=$_REQUEST['elements_product'];
	}

	$_SESSION['state']['store_offers']['offers']['elements']=$elements;


}
// $conf=$_SESSION['state']['deals']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
else {
	$_SESSION['state'][$parent]['offers']['order']=$order;
	$_SESSION['state']['store_offers']['offers']['order_dir']=$order_direction;
	$_SESSION['state']['store_offers']['offers']['nr']=$number_results;
	$_SESSION['state']['store_offers']['offers']['sf']=$start_from;
	$_SESSION['state']['store_offers']['offers']['where']=$where;
	$_SESSION['state']['store_offers']['offers']['f_field']=$f_field;
	$_SESSION['state']['store_offers']['offers']['f_value']=$f_value;
}





if ($parent=='store')
	$where=sprintf("where  (`Store Key`=%d and `Deal Trigger`='Order')     ",$parent_id);
elseif ($parent=='store_with_children') {
	$where=sprintf("where  `Store Key`=%d     ",$parent_id);
	$_elements='';
	foreach ($elements as $_key=>$_value) {
		if ($_value)
			$_elements.=','.prepare_mysql($_key);
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} else {
		$where.=' and `Deal Terms Object` in ('.$_elements.')' ;
	}


}
elseif ($parent=='department')
	$where=sprintf("where    `Deal Trigger`='Department' and  `Deal Trigger Key`=%d     ",$parent_id);
elseif ($parent=='family')
	$where=sprintf("where    `Deal Trigger`='Family' and  `Deal Trigger Key`=%d   ",$parent_id);
elseif ($parent=='product')
	$where=sprintf("where    `Deal Trigger`='Product' and  `Deal Trigger Key`=%d   ",$parent_id);
else
	$where=sprintf("where true ");;



// print "$parent $where";
$filter_msg='';
$wheref='';
if ($f_field=='description' and $f_value!='')
	$wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
elseif ($f_field=='name' and $f_value!='')
	$wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";
elseif ($f_field=='code' and $f_value!='')
	$wheref.=" and  `Deal Code` like '%".addslashes($f_value)."%'";







$sql="select count(*) as total from `Deal Dimension`   $where $wheref";

$res=mysql_query($sql);
if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

	$total=$row['total'];
}
if ($wheref!='') {
	$sql="select count(*) as total_without_filters from `Deal Dimension`   $where ";


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


$rtext=$total_records." ".ngettext('deal','deals',$total_records);
if ($total_records>$number_results)
	$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
else
	$rtext_rpp=_("Showing all");







if ($total==0 and $filtered>0) {
	switch ($f_field) {
	case('name'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
		break;
	case('code'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this code ")." <b>*".$f_value."*</b> ";
		break;
	case('description'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
		break;
	}
}
elseif ($filtered>0) {
	switch ($f_field) {
	case('name'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
		break;
	case('code'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with code like')." <b>*".$f_value."*</b>";
		break;
	case('description'):
		$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
		break;
	}
}
else
	$filter_msg='';

$_dir=$order_direction;
$_order=$order;

if ($order=='code')
	$order='`Deal Code`';
elseif ($order=='description')
	$order='`Deal Name`,`Deal Description`';
elseif ($order=='orders')
	$order='`Deal Total Acc Used Orders`';
elseif ($order=='customers')
	$order='`Deal Total Acc Customers`';
else
	$order='`Deal Name`';


$sql="select *  from `Deal Dimension` $where  $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
$res = mysql_query($sql);

$total=mysql_num_rows($res);
$adata=array();
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


	$name=sprintf('<a href="deal.php?id=%d">%s</a>',$row['Deal Key'],$row['Deal Name']);
	$code=sprintf('<a href="deal.php?id=%d">%s</a>',$row['Deal Key'],$row['Deal Code']);

	$orders=number($row['Deal Total Acc Used Orders']);
	$customers=number($row['Deal Total Acc Customers']);

	$adata[]=array(
		'code'=>$code,
		'name'=>$name,
		'description'=>'<b>'.$row['Deal Name'].'</b><br/>'.$row['Deal Description'],
		'orders'=>$orders,
		'customers'=>$customers,


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

