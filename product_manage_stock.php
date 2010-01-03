<?php
include_once('common.php');
include_once('class.Product.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$view_orders=$LU->checkRight(ORDER_VIEW);

$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$modify_stock=$LU->checkRight(PROD_STK_MODIFY);
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$LU->checkRight(SUP_VIEW);
$view_cust=$LU->checkRight(CUST_VIEW);
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',	$yui_path.'datatable/datatable-debug.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/search.js',
		'js/common.js.php',
		'js/table_common.js.php',
		);



if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $product_id=1;
else
  $product_id=$_REQUEST['id'];
$_SESSION['state']['product']['id']=$product_id;


$product= new product($product_id);
$product->load(array(
		     'product_tree'
		     ,'images'
		     ,'locations'
		     ,'same_products'
		     )
	       );

$fam_order=$_SESSION['state']['family']['table']['order'];
$sql=sprintf("select id,code from product where  %s<'%s' and  group_id=%d order by %s desc  ",$fam_order,$product->get($fam_order),$product->get('group_id'),$fam_order);
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,code from product where  %s>'%s' and group_id=%d order by %s   ",$fam_order,$product->get($fam_order),$product->get('group_id'),$fam_order);
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);


$locations=($product->get('locations'));
$smarty->assign('locations',$locations);
//print_r($locations);

$smarty->assign('parent','products');
$smarty->assign('title',$product->get('code'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product->get('department'));
$smarty->assign('department_id',$product->get('department_id'));
$smarty->assign('family',$product->get('group'));
$smarty->assign('family_id',$product->get('group_id'));
$smarty->assign('images',$product->get('images'));
$smarty->assign('image_dir',$myconf['images_dir']);

$smarty->assign('num_images',count($product->get('images')));
$smarty->assign('units',number($product->data['units']));

$smarty->assign('data',$product->data);
$smarty->assign('same_products',$product->same_products);



$_SESSION['state']['product']['manage_stock_data']=json_encode($locations);


$js_files[]='js/product_manage_stock.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('product_manage_stock.tpl');
?>