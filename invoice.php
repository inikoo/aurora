<?php
include_once 'common.php';
include_once 'class.Supplier.php';
include_once 'class.Staff.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';

include_once 'class.Invoice.php';



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

/*
if($invoice->data['Invoice Type']=='Invoice'){
$invoice->update_totals();

}else{
$invoice->update_refund_totals();

}

$invoice->update_payment_state();
*/
/*
$invoice->update_payment_state();

//exit;
$invoice->update_totals();

if($invoice->data['Invoice Type']=='Refund'){
$invoice->update_refund_totals();
}

$invoice->update_payment_state();
//print_r($invoice->data);
*/

$invoice->update_totals();

$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');
$smarty->assign('corporate_currency',$corporate_currency);

if ($invoice->data['Invoice Type']=='Invoice') {
	$smarty->assign('invoice_type_label',_('Invoice'));


	$smarty->assign('title',_('Invoice').' '.$invoice->get('Invoice Public ID') );

} else {
	$smarty->assign('invoice_type_label',_('Refund'));

	$smarty->assign('title',_('Refund').' '.$invoice->get('Invoice Public ID') );

}


if(isset($_REQUEST['ref']) and in_array($_REQUEST['ref'],array('c')))  {
		$smarty->assign('referrer',$_REQUEST['ref']);


}else{
	$smarty->assign('referrer','invoices');

}

$template='invoice.tpl';

$smarty->assign('invoice',$invoice);
$smarty->assign('customer',$customer);
$smarty->assign('store',$store);


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/order.css',
	'css/edit.css',
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
		'js/php.default.min.js',

	'js/common.js',
	'js/table_common.js',
	'js/search.js',
	'js/add_payment.js',
	'invoice.js.php'
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
