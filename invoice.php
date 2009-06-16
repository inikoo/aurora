<?
include_once('common.php');
include_once('_supplier.php');
include_once('staff.php');

include_once('classes/Invoice.php');

$_SESSION['views']['assets']='index';


if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  exit(_('Error'));
$invoice_id=$_REQUEST['id'];

$_SESSION['state']['invoice']['id']=$invoice_id;

if(!$invoice=new Invoice($invoice_id))
  exit(_('Error, invoice not found'));


$customer=new Customer($invoice->data['Invoice Customer Key']);

//print_r($invoice->data);

$js_file='invoice.js.php';
$template='invoice.tpl';


$smarty->assign('invoice',$invoice);
$smarty->assign('customer',$customer);


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
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/'.$js_file
		);




$smarty->assign('parent','orders.php');
$smarty->assign('title',_('Invoice').' '.$invoice->get('Invoice Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






$smarty->display($template);
?>