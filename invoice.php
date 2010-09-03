<?php
include_once('common.php');
include_once('class.Supplier.php');
include_once('class.Staff.php');

include_once('class.Invoice.php');

$_SESSION['views']['assets']='index';


if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  exit(_('Error'));
$invoice_id=$_REQUEST['id'];

$_SESSION['state']['invoice']['id']=$invoice_id;
$invoice=new Invoice($invoice_id);
if(!$invoice->id)
  exit(_('Error, invoice not found'));


$customer=new Customer($invoice->data['Invoice Customer Key']);

//print_r($invoice->data);



if($invoice->data['Invoice Paid']=='Yes'){
$js_file='invoice.js.php';
$template='invoice.tpl';
}else{
$js_file='invoice_in_process.js.php';
$template='invoice_in_process.tpl';

}

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
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		$js_file
		);




$smarty->assign('parent','orders');
$smarty->assign('title',_('Invoice').' '.$invoice->get('Invoice Public ID') );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






$smarty->display($template);
?>