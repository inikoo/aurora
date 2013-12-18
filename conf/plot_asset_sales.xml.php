<?php

chdir("../");
include_once 'common.php';
if (!isset($_REQUEST['tipo'])) {

	exit;
}

$tipo=$_REQUEST['tipo'];

$colors=array('0033CC','0099CC','00CC99','00CC33','CC9900','CCCC00','99CCCC','0033CC','0099CC','00CC99','00CC33','CC9900','CCCC00','99CCCC');

switch ($tipo) {

case('sales_from_invoices'):
	global $user;
	$stores_keys=$user->stores;
	$use_corporate=0;
	$staked_by_store=false;
	$staked_by_subregion=false;
	$region_level='Continent';
	$region_code='WERP';
	if (isset($_REQUEST['stacked_by_store']) and $_REQUEST['stacked_by_store'])$staked_by_store=true;
	if (isset($_REQUEST['stacked_by_subregion']) and $_REQUEST['stacked_by_subregion'])$staked_by_subregion=true;
	if (isset($_REQUEST['region_level']) and $_REQUEST['region_level'])$region_level=$_REQUEST['region_level'];
	if (isset($_REQUEST['region_code']) and $_REQUEST['region_code'])$region_code=$_REQUEST['region_code'];

	$title=_('Sales by Region');
	if (isset($_REQUEST['title']) and $_REQUEST['title'])$title=$_REQUEST['title'];

	$graphs_data=array();
	$gid=0;
	if ($staked_by_store) {
		$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));
		//  .'&stacked_by_store='.$_REQUEST['stacked_by_store'].'&stacked_by_subregion='.$_REQUEST['stacked_by_subregion']

		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$graphs_data[]=array(
				'gid'=>$gid,
				'title'=>$row['Store Code'],
				'currency_code'=>$corporate_currency,
				'color'=>$colors[$gid]
			);
			$gid++;
		}
		$data_args='tipo=stacked_store_sales&store_key='.join(',',$stores_keys);
		$template='plot_stacked_asset_sales.xml.tpl';

	} else {// no stakecked


		$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension`");
		// where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));
		$res=mysql_query($sql);
		$title='';
		$currencies=array();
		while ($row=mysql_fetch_assoc($res)) {
			// $title.=','.$row['Store Code'];


			$currency_code=$row['Store Currency Code'];
			$currencies[$currency_code]=1;

		}


		if (count($currencies)>1)
			$use_corporate=1;




		$graphs_data[]=array(
			'gid'=>0,
			'title'=>$title,
			'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
		);
		$data_args='tipo=region_sales&store_key='.join(',',$stores_keys).'&use_corporate='.$use_corporate.'&region_level='.$region_level.'&region_code='.$region_code;

		$template='plot_asset_sales.xml.tpl';

	}





	break;
case('store_sales'):


	if (!isset($_REQUEST['store_key'])) {
		exit;
	}
	$tmp=preg_split('/\|/', $_REQUEST['store_key']);
	$stores_keys=array();
	foreach ($tmp as $store_key) {

		if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
			$stores_keys[]=$store_key;
		}
	}
	$use_corporate=0;
	$staked=false;$per_category=false;
	if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
	if (isset($_REQUEST['per_category']) and $_REQUEST['per_category'])$per_category=true;
	$graphs_data=array();
	$gid=0;
	if ($staked) {


		if ($per_category) {


			$sql=sprintf("select `Category Label` from `Category Bridge` B left join `Category Dimension` C on  (`Subject`='Invoice' and B.`Category Key`=C.`Category Key`)  where `Category Store Key` in (%s) group by C.`Category Key`",addslashes(join(',',$stores_keys)));
			$res=mysql_query($sql);
			
			while ($row=mysql_fetch_assoc($res)) {
				$graphs_data[]=array(
					'gid'=>$gid,
					'title'=>$row['Category Label'],
					'currency_code'=>$corporate_currency,
					'color'=>$colors[$gid]
				);
				$gid++;
			}
		
	$data_args='tipo=stacked_invoice_categories_sales&store_key='.join(',',$stores_keys);

	}else {

		$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$graphs_data[]=array(
				'gid'=>$gid,
				'title'=>$row['Store Code'],
				'currency_code'=>$corporate_currency,
				'color'=>$colors[$gid]
			);
			$gid++;
		}
			$data_args='tipo=stacked_store_sales&store_key='.join(',',$stores_keys);

	}
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Store Code'];


		$currency_code=$row['Store Currency Code'];
		$currencies[$currency_code]=1;

	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=store_sales&store_key='.join(',',$stores_keys).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}






break;

case('department_sales'):

if (!isset($_REQUEST['department_key'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['department_key']);
$departments_keys=array();
foreach ($tmp as $department_key) {

	if (is_numeric($department_key) ) {
		$departments_keys[]=$department_key;
	}
}
$use_corporate=0;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
if ($staked) {
	$sql=sprintf("select `Product Department Store Key`,`Product Department Name`,`Product Department Code`,`Store Currency Code` from `Product Department Dimension` left join `Store Dimension` on (`Product Department Store Key`=`Store Key`)  where `Product Department Key` in (%s)",addslashes(join(',',$departments_keys)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		if (!in_array($row['Product Department Store Key'], $user->stores)) {
			continue;
		}
		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Product Department Code'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_department_sales&department_key='.join(',',$departments_keys);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Product Department Name`,`Product Department Code`,`Store Currency Code` from `Product Department Dimension` left join `Store Dimension` on (`Product Department Store Key`=`Store Key`) where `Product Department Key` in (%s)",addslashes(join(',',$departments_keys)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Product Department Code'];


		$currency_code=$row['Store Currency Code'];
		$currencies[$currency_code]=1;

	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=department_sales&department_key='.join(',',$departments_keys).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;

case('family_sales'):

if (!isset($_REQUEST['family_key'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['family_key']);
$familys_keys=array();
foreach ($tmp as $family_key) {

	if (is_numeric($family_key) ) {
		$familys_keys[]=$family_key;
	}
}
$use_corporate=0;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
if ($staked) {
	$sql=sprintf("select `Product Family Store Key`,`Product Family Name`,`Product Family Code`,`Store Currency Code` from `Product Family Dimension`  left join `Store Dimension` on (`Product Family Store Key`=`Store Key`) where `Product Family Key` in (%s)",addslashes(join(',',$familys_keys)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		if (!in_array($row['Product Family Store Key'], $user->stores)) {
			continue;
		}
		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Product Family Code'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_family_sales&family_key='.join(',',$familys_keys);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Product Family Name`,`Product Family Code`,`Store Currency Code` from `Product Family Dimension` left join `Store Dimension` on (`Product Family Store Key`=`Store Key`) where `Product Family Key` in (%s)",addslashes(join(',',$familys_keys)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Product Family Code'];


		$currency_code=$row['Store Currency Code'];
		$currencies[$currency_code]=1;

	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=family_sales&family_key='.join(',',$familys_keys).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;

case('part_sales'):

if (!isset($_REQUEST['part_sku'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['part_sku']);
$parts_skus=array();
foreach ($tmp as $part_sku) {

	if (is_numeric($part_sku) ) {
		$parts_skus[]=$part_sku;
	}
}
$use_corporate=1;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
//TODO anly display warehiuse $user->wherehouses;


if ($staked) {
	$sql=sprintf("select `Part Unit Description` from `Part Dimension`  where `Part SKU` in (%s)",addslashes(join(',',$parts_skus)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {

		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Part Unit Description'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_part_sales&part_sku='.join(',',$parts_skus);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Part Unit Description` from `Part Dimension`  where `Part SKU` in (%s)",addslashes(join(',',$parts_skus)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Part Unit Description'];
		$currency_code=$corporate_currency;
		$currencies[$currency_code]=1;
	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=part_sales&part_sku='.join(',',$parts_skus).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;
case('supplier_product_sales'):

if (!isset($_REQUEST['supplier_product_pid'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['supplier_product_pid']);
$pids=array();
foreach ($tmp as $pid) {

	if (is_numeric($pid) ) {
		$pids[]=$pid;
	}
}

$use_corporate=1;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
//TODO anly display warehiuse $user->wherehouses;


if ($staked) {
	$sql=sprintf("select `Supplier Product Name` from `Supplier Product Dimension`  where `Supplier Product ID` in (%s)",addslashes(join(',',$pids)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {

		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Supplier Product Name'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_supplier_product_sales&pid='.join(',',$pids);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Supplier Product Name` from `Supplier Product Dimension`  where `Supplier Product ID` in (%s)",addslashes(join(',',$pids)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Supplier Product Name'];
		$currency_code=$corporate_currency;
		$currencies[$currency_code]=1;
	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=supplier_product_sales&pid='.join(',',$pids).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;

case('product_id_sales'):

if (!isset($_REQUEST['product_id'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['product_id']);
$product_ids=array();
foreach ($tmp as $product_id) {

	if (is_numeric($product_id) ) {
		$product_ids[]=$product_id;
	}
}
$use_corporate=0;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
if ($staked) {
	$sql=sprintf("select `Product Store Key`,`Product Name`,`Product Code`,`Store Currency Code` from `Product Dimension`  left join `Store Dimension` on (`Product Store Key`=`Store Key`) where `Product ID` in (%s)",addslashes(join(',',$product_ids)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		if (!in_array($row['Product Store Key'], $user->stores)) {
			continue;
		}
		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Product Code'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_product_id_sales&product_id='.join(',',$product_ids);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Product Name`,`Product Code`,`Store Currency Code` from `Product Dimension` left join `Store Dimension` on (`Product Store Key`=`Store Key`) where `Product ID` in (%s)",addslashes(join(',',$product_ids)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Product Code'];


		$currency_code=$row['Store Currency Code'];
		$currencies[$currency_code]=1;

	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=product_id_sales&product_id='.join(',',$product_ids).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;

case('sales_from_country'):
global $user;
$stores_keys=$user->stores;
$use_corporate=0;
$staked_by_store=false;
$staked_by_subregion=false;
$region_level='Country';
$country_code='GBR';
if (isset($_REQUEST['stacked_by_store']) and $_REQUEST['stacked_by_store'])$staked_by_store=true;
if (isset($_REQUEST['stacked_by_subregion']) and $_REQUEST['stacked_by_subregion'])$staked_by_subregion=true;
// if (isset($_REQUEST['region_level']) and $_REQUEST['region_level'])$region_level=$_REQUEST['region_level'];
if (isset($_REQUEST['country_code']) and $_REQUEST['country_code'])$country_code=$_REQUEST['country_code'];

$title=_('Sales by Country');
if (isset($_REQUEST['title']) and $_REQUEST['title'])$title=$_REQUEST['title'];

$graphs_data=array();
$gid=0;
if ($staked_by_store) {
	$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code`, `Country Code` from `Store Dimension` left join kbase.`Country Dimension` C on (C.`Country Name`=`Store Dimension`.`Store Home Country Code 2 Alpha`) where `Store Key` in (%s) and `Country Code`=%s",addslashes(join(',',$stores_keys)),$_SESSION['state']['country']['code']);
	//  .'&stacked_by_store='.$_REQUEST['stacked_by_store'].'&stacked_by_subregion='.$_REQUEST['stacked_by_subregion']

	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {
		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Store Code'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_store_sales&store_key='.join(',',$stores_keys);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	//$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension`");
	$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension`  where `Store Key` in (%s) ",
		addslashes(join(',',$stores_keys)),
		prepare_mysql($_SESSION['state']['country']['code']));

	// where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));

	//print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		// $title.=','.$row['Store Code'];


		$currency_code=$row['Store Currency Code'];
		$currencies[$currency_code]=1;

	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title,
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=region_sales&store_key='.join(',',$stores_keys).'&use_corporate='.$use_corporate.'&region_level='.$region_level.'&region_code='.$country_code;

	$template='plot_asset_sales.xml.tpl';

}





break;

case('part_category_sales'):

if (!isset($_REQUEST['category_key'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['category_key']);
$categories_keys=array();
foreach ($tmp as $category_key) {

	if (is_numeric($category_key) ) {
		$categories_keys[]=$category_key;
	}
}
$use_corporate=0;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
if ($staked) {
	//Todo see family_sales

} else {// no stakecked


	$sql=sprintf("select `Category Label`,`Category Code` from `Category Dimension` where `Category Key` in (%s)",addslashes(join(',',$categories_keys)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Category Label'];




	}



	$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>$corporate_currency
	);
	$data_args='tipo=category_part_sales&category_key='.join(',',$categories_keys).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;

case('supplier_sales'):

if (!isset($_REQUEST['supplier_key'])) {
	exit;
}
$tmp=preg_split('/\|/', $_REQUEST['supplier_key']);
$supplier_keys=array();
foreach ($tmp as $part_sku) {

	if (is_numeric($part_sku) ) {
		$supplier_keys[]=$part_sku;
	}
}
$use_corporate=1;
$staked=false;
if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;
$graphs_data=array();
$gid=0;
//TODO anly display warehiuse $user->wherehouses;


if ($staked) {
	$sql=sprintf("select `Supplier Name` from `Supplier Dimension`  where `Supplier Key` in (%s)",addslashes(join(',',$supplier_keys)));
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {

		$graphs_data[]=array(
			'gid'=>$gid,
			'title'=>$row['Supplier Name'],
			'currency_code'=>$corporate_currency,
			'color'=>$colors[$gid]
		);
		$gid++;
	}
	$data_args='tipo=stacked_part_sales&part_sku='.join(',',$supplier_keys);
	$template='plot_stacked_asset_sales.xml.tpl';

} else {// no stakecked


	$sql=sprintf("select `Supplier Name` from `Supplier Dimension`  where `Supplier Key` in (%s)",addslashes(join(',',$supplier_keys)));
	// print $sql;
	$res=mysql_query($sql);
	$title='';
	$currencies=array();
	while ($row=mysql_fetch_assoc($res)) {
		$title.=','.$row['Supplier Name'];
		$currency_code=$corporate_currency;
		$currencies[$currency_code]=1;
	}


	if (count($currencies)>1)
		$use_corporate=1;




	$graphs_data[]=array(
		'gid'=>0,
		'title'=>$title.' '._('Sales'),
		'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
	);
	$data_args='tipo=supplier_sales&supplier_key='.join(',',$supplier_keys).'&use_corporate='.$use_corporate;

	$template='plot_asset_sales.xml.tpl';

}


break;
default:
exit("error 112");

}


if (isset($_REQUEST['from'])) {
$from=$_REQUEST['from'];
$data_args.=sprintf("&from=%s",$_REQUEST['from']);
}else {
$from='';
}

if (isset($_REQUEST['to'])) {
$to=$_REQUEST['to'];

$data_args.=sprintf("&to=%s",$_REQUEST['to']);
}else {
$to='';
}
$smarty->assign('from',$from);
$smarty->assign('to',$to);

$smarty->assign('locale_data',localeconv());
$smarty->assign('graphs_data',$graphs_data);
$smarty->assign('data_args',$data_args);
$smarty->display($template);
?>
