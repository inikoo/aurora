<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 December 2013 12:08:44 CET, Malaga, Spain
 Copyright (c) 2013, Inikoo

 Version 2.0
*/


switch ($referer) {
case 'o':
	$_order=$_SESSION['state']['orders']['orders']['order'];
	$_order_dir=$_SESSION['state']['orders']['orders']['order_dir'];

	if ($_order=='id') {
		$_order='`Order File As`';

	}elseif ($_order=='last_date' or $_order=='date') {
		$_order="`Order Date`";

	}elseif ($_order=='customer') {
		$_order='`Order Customer Name`';
	}elseif ($_order=='state') {
		$_order='`Order Current Dispatch State`';
	}elseif ($_order=='total_amount') {
		$_order='`Order Total Amount`';
	}else {
		$_order='`Order File As`';
	}

	$_order_field=preg_replace('/`/','',$_order);
	$sql=sprintf("select `Order Key` as id , `Order Public ID` as name from `Order Dimension`  where  `Order Store Key`=%d  and %s < %s  order by %s desc  limit 1",
		$order->data['Order Store Key'],
		$_order,
		prepare_mysql($order->get($_order_field)),
		$_order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$prev['link']='order.php?r=o&id='.$row['id'];
		$prev['title']=$row['name'];
		$prev['to_end']=false;

		$smarty->assign('order_prev',$prev);
	}else {
		$sql=sprintf("select `Order Key` as id , `Order Public ID` as name from `Order Dimension`  where  `Order Store Key`=%d and %s > %s  order by %s desc  limit 1",
			$order->data['Order Store Key'],
			$_order,
			prepare_mysql($order->get($_order_field)),
			$_order
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$prev['link']='order.php?r=o&id='.$row['id'];
			$prev['title']=$row['name'];
			$prev['to_end']=true;
			$smarty->assign('order_prev',$prev);
		}

	}


	$sql=sprintf("select`Order Key` as id , `Order Public ID` as name from `Order Dimension`  where  `Order Store Key`=%d   and  %s>%s  order by %s   ",
		$order->data['Order Store Key'],
		$_order,
		prepare_mysql($order->get($_order_field)),
		$_order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$next['link']='order.php?r=o&id='.$row['id'];
		$next['title']=$row['name'];
		$next['to_end']=false;
		
		$smarty->assign('order_next',$next);
	}else {
		$sql=sprintf("select`Order Key` as id , `Order Public ID` as name from `Order Dimension`  where  `Order Store Key`=%d  and  %s<%s  order by %s   ",
			$order->data['Order Store Key'],
			$_order,
			prepare_mysql($order->get($_order_field)),
			$_order
		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$next['link']='order.php?r=o&id='.$row['id'];
			$next['title']=$row['name'];
			$next['to_end']=true;
			
			$smarty->assign('order_next',$next);
		}

	}



	break;

}



?>
