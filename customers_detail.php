<?
include_once('common.php');
if(!$LU->checkRight(CUST_VIEW))
  exit;



$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/customers_details.js.php'
		);



$smarty->assign('parent','customers.php');
$smarty->assign('title', _('Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Customers List'));


//$smarty->assign('total_products',$products['numberof']);
//$smarty->assign('rpp',$_SESSION['tables']['pindex_list'][2]);
//$smarty->assign('products_perpage',$_SESSION['tables']['pindex_list'][2]);



$smarty->assign('filter',$_SESSION['tables']['customers_list'][5]);
$smarty->assign('filter_value',$_SESSION['tables']['customers_list'][6]);




$smarty->assign('customer_id2',$customers_ids[1]);
$smarty->assign('customer_id3',$customers_ids[2]);

$sql="select count(*) as customers from customer";
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $total_customers=$row['customers'];
 }
$now="'2006-11-29 08:30:00'";
$sql="select AVG(order_interval) as avg_interval,STD(order_interval) as std_interval  from customer where order_interval>0";
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
$active_customers=0;
if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $avg_interval=$row['avg_interval'];
  $std_interval=$row['std_interval'];

 }



$smarty->assign('avg_interval',number($avg_interval).'('.number($std_interval).') '.ngettext('day','days',$avg_interval));





$smarty->display('customers_detail.tpl');
?>