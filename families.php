<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('stock_functions.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$view_sales=true;
$view_stock=true;
$create=true;
$modify=true;
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
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
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
		'js/families.js.php',
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
//$result =& $db->query($sql);

// include_once('classes/product.php');
// while($row=$result->fetchRow()){
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
// $result =& $db->query($sql);
// $families=$result->fetchRow();
// $sql="select count(*) as numberof from product";
// $result =& $db->query($sql);
// $products=$result->fetchRow();





// $smarty->assign('stock_value',money($families['stock_value']));
$smarty->assign('total_sales',money($families['total_sales']));
$smarty->assign('families',number($families['numberof']));
// $smarty->assign('families',number($families['numberof']));
// $smarty->assign('products',number($products['numberof']));

$smarty->assign('parent','families.php');
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
$smarty->assign('filter_menu',$filter_menu);

$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);



$smarty->display('families.tpl');

?>