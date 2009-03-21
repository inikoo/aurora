<?
include_once('common.php');
include_once('stock_functions.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);



$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


if(isset($_REQUEST['edit']))
  $edit=$_REQUEST['edit'];
else
  $edit=$_SESSION['state']['stores']['edit'];



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
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		);

if($edit)
  $js_files[]='js/edit_stores.js.php';
 else{
   $js_files[]='js/search.js';
   $js_files[]='js/stores.js.php';
 }


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$_SESSION['state']['assets']['page']='stores';
//if(isset($_REQUEST['view'])){
//  $valid_views=array('sales','general','stoke');
//  if (in_array($_REQUEST['view'], $valid_views)) 
//    $_SESSION['state']['stores']['view']=$_REQUEST['view'];
//
// }
//$smarty->assign('view',$_SESSION['state']['stores']['view']);
//$smarty->assign('show_details',$_SESSION['state']['stores']['details']);
//$smarty->assign('show_percentages',$_SESSION['state']['stores']['percentages']);
//$smarty->assign('avg',$_SESSION['state']['stores']['avg']);
//smarty->assign('period',$_SESSION['state']['stores']['period']);


//$sql="select id from product";
//$result =& $db->query($sql);

// include_once('classes/product.php');
// while($row=$result->fetchRow()){
//   $product= new product($row['id']);
//   $product->set_stock();
// }




$smarty->assign('parent','stores.php');
$smarty->assign('title', _('Stores'));
//$smarty->assign('total_stores',$stores['numberof']);
//$smarty->assign('table_title',$table_title);



$stores=array();
$sql=sprintf("select count(*) as num from `Store Dimension` CD order by `Store Key`");

$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
  $stores=$row['num'];
 }
 
$smarty->assign('stores',$stores);




if($edit){
$smarty->display('edit_stores.tpl');
 }else
$smarty->display('stores.tpl');

?>