<?
include_once('common.php');


$view_orders=$LU->checkRight(ORDER_VIEW);
$smarty->assign('view_orders',$view_orders);



$week=date("W");
$sql='select sum(total) as total,count(*) from orden where tipo=2 and week(date_index)='.$week;


$smarty->assign('box_layout','yui-t4');

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
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/index.js.php'
		);




$smarty->assign('parent','index.php');
$smarty->assign('title', _('Home'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//set_stock_value_all();
//update_department_all();
//update_family_all();


//fix_todotransaction_all();

$smarty->assign('filter',$_SESSION['tables']['proinvoice_list'][5]);
$smarty->assign('filter_value',$_SESSION['tables']['proinvoice_list'][6]);

switch($_SESSION['tables']['proinvoice_list'][5]){
 case('max'):
   $filter_text=_('Maximun Day Interval');
   break;
 case('min'):
   $filter_text=_('Minimun Day Interval');
   break;
 case('public_id'):
   $filter_text=_('Order Number');
   break;
 case('customer_name'):
   $filter_text=_('Customer Name');
   break;
 default:
   $filter_text='?';
 }
include_once('common/string.php');
include_once('common/_customer.php');


$smarty->assign('filter_name',$filter_text);
$smarty->assign('f_date',_('Week').strftime(" %W %Y" ));

$smarty->assign('t_title0',_('Outstanding Orders'));

$smarty->display('index.tpl');





?>

