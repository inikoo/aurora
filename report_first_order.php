<?php
include_once('common.php');
include_once('report_functions.php');
include_once('class.Store.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

		 
		 'button.css',
		 'container.css',
		 'css/calendar.css'

		 );

$css_files[]='theme.css.php';


$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
	    $yui_path.'autocomplete/autocomplete-min.js',

		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		
		'report_first_order.js.php',
        'reports_calendar.js.php',

		'js/dropdown.js'

		);

$root_title=_('Sales Report');

include_once('reports_list.php');



$smarty->assign('parent','reports');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$report_name='report_first_order';


if(isset($_REQUEST['tipo'])){
  $tipo=$_REQUEST['tipo'];
  $_SESSION['state'][$report_name]['tipo']=$tipo;
}else
  $tipo=$_SESSION['state'][$report_name]['tipo'];
/*
if(isset($_REQUEST['currency_type'])){
  $currency_type=$_REQUEST['currency_type'];
  $_SESSION['state'][$report_name]['currency_type']=$currency_type;
}else
  $currency_type=$_SESSION['state'][$report_name]['currency_type'];

*/

$store_keys=join(',',$user->stores);

if($store_keys==''){
    exit("you can not be here\n");
}


if($tipo=='quick_all')
  $tipo='all_invoices';

include_once('report_dates.php');

$smarty->assign('tipo',$tipo);
//$smarty->assign('currency_type',$currency_type);

$smarty->assign('period',$period);
$smarty->assign('from',$from);
$smarty->assign('to',$to);

$_SESSION['state'][$report_name]['to']=$to;
$_SESSION['state'][$report_name]['from']=$from;


$sql=sprintf("select `Store Key`,`Store Code`,`Store Name` from   `Store Dimension` where `Store Key` in (%s)  ",$store_keys);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $stores_data[$row['Store Key']]=array(
    'store_key'=>$row['Store Key'],
    'store_name'=>$row['Store Name'],
    'store_code'=>$row['Store Code'],
    'number_first_orders'=>0
);
}

$sql=sprintf("select count(*) as number,`Order Store Key` from  `Order Dimension` where `Order Store Key` in (%s) and `Order Customer Order Number`=1 and Date(`Order Date`)>=%s and Date(`Order Date`)<=%s group by `Order Store Key` ",
$store_keys,
prepare_mysql($from),
prepare_mysql($to)
);


$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
      $stores_data[$row['Order Store Key']]['number_first_orders']  =$row['number'];
}

$smarty->assign('stores_data',$stores_data);




$smarty->display('report_first_order.tpl');
?>

