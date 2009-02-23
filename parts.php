<?
include_once('common.php');
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

$q='';

$sql="select count(*) as total_parts   from `Part Dimension` ";
$result=mysql_query($sql);
if(!$parts=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  exit("Internal Error\n");
 }
  


$smarty->assign('box_layout','yui-t0');
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(

		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'connection/connection-min.js',
		$yui_path.'json/json-min.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search_product.js',
		'js/parts.js.php'
		);






$smarty->assign('parent','departments.php');
$smarty->assign('title', _('Parts Index'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$product_home="Products Home";
$smarty->assign('home',$product_home);




$tipo_filter=($q==''?$_SESSION['state']['parts']['table']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['parts']['table']['f_value']:addslashes($q)));
$filter_menu=array(
		   'used_in'=>array('db_key'=>'used_in','menu_label'=>'Used in <i>x</i>','label'=>'Used in'),
		   'supplied_by'=>array('db_key'=>'supplied_by','menu_label'=>'Supplied by <i>x</i>','label'=>'Supplied by'),
		   'description'=>array('db_key'=>'description','menu_label'=>'Part Description like <i>x</i>','label'=>'Description'),

		   );
$smarty->assign('filter_menu',$filter_menu);

$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);
$smarty->assign('view',$_SESSION['state']['parts']['view']);
$smarty->assign('period',$_SESSION['state']['parts']['period']);
$smarty->assign('avg',$_SESSION['state']['parts']['avg']);

$smarty->assign('currency',$myconf['currency_symbol']);
$smarty->assign('parts',$parts['total_parts']);


$smarty->display('parts.tpl');
?>