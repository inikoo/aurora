<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('assets_header_functions.php');

if(!$user->can_view('product families'))
  exit();

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('product families');

$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);

$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		  'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'families.js.php',
		 'js/dropdown.js'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']='families';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['families']['view']=$_REQUEST['view'];

 }


$smarty->assign('view',$_SESSION['state']['families']['view']);
$smarty->assign('show_details',$_SESSION['state']['families']['details']);
$smarty->assign('show_percentages',$_SESSION['state']['families']['percentages']);


//$sql="select id from product";
//$result=mysql_query($sql);

// include_once('class.product.php');
// while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//   $product= new product($row['id']);
//   $product->set_stock();
// }


 $table_title=_('Families List');
  $sql="select count(*) as numberof ,sum(`Product Family Total Invoiced Gross Amount`-`Product Family Total Invoiced Discount Amount`) as total_sales  from `Product Family Dimension`  ";
$result =mysql_query($sql);
if(!$families=mysql_fetch_array($result, MYSQL_ASSOC))
  exit("Internal Error DEPS");



// //$smarty->assign('table_info',$families['numberof'].' '.ngettext('Family','Families',$families['numberof']));
// $sql="select count(*) as numberof from product_group";
// $result=mysql_query($sql);
// $families=mysql_fetch_array($result, MYSQL_ASSOC);
// $sql="select count(*) as numberof from product";
// $result=mysql_query($sql);
// $products=mysql_fetch_array($result, MYSQL_ASSOC);





// $smarty->assign('stock_value',money($families['stock_value']));
$smarty->assign('total_sales',money($families['total_sales']));
$smarty->assign('families',number($families['numberof']));
// $smarty->assign('families',number($families['numberof']));
// $smarty->assign('products',number($products['numberof']));

$smarty->assign('parent','products');
$smarty->assign('title', _('Product Families'));
//$smarty->assign('total_families',$families['numberof']);


$q='';
$tipo_filter=($q==''?$_SESSION['state']['families']['table']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['families']['table']['f_value']:addslashes($q)));
$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
		   'description'=>array('db_key'=>'description','menu_label'=>'Family Description with <i>x</i>','label'=>'Description'),
		   );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->display('families.tpl');

?>