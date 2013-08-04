<?php
include_once 'common.php';
include_once 'class.Supplier.php';
include_once 'class.Staff.php';

include_once 'class.Invoice.php';

$_SESSION['views']['assets']='index';


if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
	exit(_('Error'));
$invoice_id=$_REQUEST['id'];

$_SESSION['state']['invoice']['id']=$invoice_id;
$invoice=new Invoice($invoice_id);
$invoice->update_xhtml_delivery_notes();
if (!$invoice->id)
	exit(_('Error, invoice not found'));


$customer=new Customer($invoice->data['Invoice Customer Key']);
$store=new Store($invoice->data['Invoice Store Key']);

//print_r($invoice->data);

$smarty->assign('search_label',_('Orders'));
		$smarty->assign('search_scope','orders');

if ($invoice->data['Invoice Type']=='Invoice') {
//	if ($invoice->data['Invoice Paid']=='Yes') {
		$js_file='invoice.js.php';
		$template='invoice.tpl';
//	} else {
//		$js_file='invoice_in_process.js.php';
//		$template='invoice_in_process.tpl';

//	}
	$smarty->assign('title',_('Invoice').' '.$invoice->get('Invoice Public ID') );

} else {
	if ($invoice->data['Invoice Paid']=='Yes') {
		$js_file='refund.js.php';
		$template='refund.tpl';
	} else {
		$js_file='refund_in_process.js.php';
		$template='refund_in_process.tpl';
	}
	$smarty->assign('title',_('Refund').' '.$invoice->get('Invoice Public ID') );

}
$smarty->assign('invoice',$invoice);
$smarty->assign('customer',$customer);
$smarty->assign('store',$store);


$smarty->assign('box_layout','yui-t0');
$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'
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
	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	$js_file
);






$smarty->assign('parent','orders');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('customer_id',$myconf['customer_id_prefix'].sprintf("%05d",$customer->id));

$tax_data=array();
$sql=sprintf("select `Tax Category Name`,`Tax Category Rate`,`Tax Amount` from  `Invoice Tax Bridge` B  left join `Tax Category Dimension` T on (T.`Tax Category Code`=B.`Tax Code`)  where B.`Invoice Key`=%d ",$invoice->id);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$tax_data[]=array('name'=>$row['Tax Category Name'],'amount'=>money($row['Tax Amount'],$invoice->data['Invoice Currency']));
}

$smarty->assign('tax_data',$tax_data);
//print_r($tax_data);
$smarty->display($template);
?>
