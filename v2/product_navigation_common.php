<?php
$order=$_SESSION['state']['family']['products']['order'];
if ($order=='code') {
	$order='`Product Code File As`';
	$order_label=_('Code');
} else {
	$order='`Product Code File As`';
	$order_label=_('Code');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Product ID` as id , `Product Code` as name from `Product Dimension`  where  `Product Family Key`=%d  and %s < %s  order by %s desc  limit 1",
	$product->data['Product Family Key'],
	$order,
	prepare_mysql($product->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']=$link.'?pid='.$row['id'];
	$prev['title']=$row['name'];
	$prev['to_end']=false;
	$smarty->assign('prev',$prev);
}else {
	$sql=sprintf("select `Product ID` as id , `Product Code` as name from `Product Dimension`  where  `Product Family Key`=%d  and %s > %s  order by %s desc  limit 1",
		$product->data['Product Family Key'],
		$order,
		prepare_mysql($product->get($_order)),
		$order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$prev['link']=$link.'?pid='.$row['id'];
		$prev['title']=$row['name'];
		$prev['to_end']=true;
		$smarty->assign('prev',$prev);
	}

}


$sql=sprintf(" select `Product ID` as id , `Product Code` as name from `Product Dimension`  where  `Product Family Key`=%d    and  %s>%s  order by %s   ",
	$product->data['Product Family Key'],
	$order,
	prepare_mysql($product->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']=$link.'?pid='.$row['id'];
	$next['title']=$row['name'];
	$next['to_end']=false;
	$smarty->assign('next',$next);
}else {
	$sql=sprintf(" select `Product ID` as id , `Product Code` as name from `Product Dimension`  where  `Product Family Key`=%d    and  %s<%s  order by %s   ",
		$product->data['Product Family Key'],
		$order,
		prepare_mysql($product->get($_order)),
		$order
	);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$next['link']=$link.'?pid='.$row['id'];
		$next['title']=$row['name'];
		$next['to_end']=true;
		$smarty->assign('next',$next);
	}

}


?>