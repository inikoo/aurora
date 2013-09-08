<?php
include_once('common.php');
include_once('class.Store.php');

if (!$user->can_view('orders') ) {
    header('Location: index.php');
    exit;
}
$general_options_list=array();
if (isset($_REQUEST['id']))
    $id=$_REQUEST['id'];
else {
    header('Location: index.php?error=no_id_in_invoices_list');
    exit;

}
$modify=$user->can_edit('orders');
$smarty->assign('modify',$modify);


$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$id);

$res=mysql_query($sql);
if (!$invoice_list_data=mysql_fetch_assoc($res)) {
    header('Location: index.php?error=id_in_invoices_list_not_found');
    exit;

}
$store=new Store($invoice_list_data['List Parent Key']);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);


$invoice_list_name=$invoice_list_data['List Name'];
$smarty->assign('invoice_list_name',$invoice_list_name);
$smarty->assign('invoice_list_key',$invoice_list_data['List Key']);


$css_files=array(
                 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
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
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',


              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
             
              'js/export_common.js',
              'invoices_list.js.php'
              
              
          );   
          
          
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','orders');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['orders']['view']);

$smarty->assign('title', _('Invoice List'));
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


$tipo_filter0=$_SESSION['state']['orders']['invoices']['f_field'];
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($_SESSION['state']['orders']['invoices']['f_value']));
$filter_menu0=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Invoice Number starting with <i>x</i>','label'=>'Invoice Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Invoice with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Invoice with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Invoice billed to country code <i>xxx</i>','label'=>'Country Code')
);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

$smarty->assign('elements_invoice_type',$_SESSION['state']['orders']['invoices']['elements']['type']);
$smarty->assign('elements_invoice_payment',$_SESSION['state']['orders']['invoices']['elements']['payment']);

$smarty->assign('elements_invoice_elements_type',$_SESSION['state']['orders']['invoices']['elements_type']);

$smarty->assign('block_view','invoices');



$smarty->display('invoices_list.tpl');
?>
