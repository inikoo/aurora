<?php
include_once('common.php');
include_once('class.Store.php');


if (!$user->can_view('customers') ) {
    header('Location: index.php');
    exit;
}
$modify=$user->can_edit('customers');
$general_options_list=array();
if (isset($_REQUEST['id']))
    $id=$_REQUEST['id'];
else {
    header('Location: index.php?error=no_id_in_customers_list');
    exit;

}


$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$id);

$res=mysql_query($sql);
if (!$customer_list_data=mysql_fetch_assoc($res)) {
    header('Location: index.php?error=id_in_customers_list_not_found');
    exit;

}
$store=new Store($customer_list_data['List Parent Key']);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);


$customer_list_name=$customer_list_data['List Name'];
$smarty->assign('customer_list_name',$customer_list_name);
$smarty->assign('customer_list_key',$customer_list_data['List Key']);
$smarty->assign('modify',$modify);





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
             
              'js/customers_common.js',
              'js/export_common.js',
              'customers_list.js.php'
              
              
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','customers');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['customers']['customers']['view']);

$smarty->assign('title', _('Customer Static List'));
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$tipo_filter=$_SESSION['state']['customers']['customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['customers']['f_value']);

$filter_menu=array(
                 'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
                 'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
                 'country'=>array('db_key'=>_('country'),'menu_label'=>_('Customer Country'),'label'=>_('Country')),

                 'min'=>array('db_key'=>_('min'),'menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
                 'max'=>array('db_key'=>_('min'),'menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
                 'last_more'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
                 'last_less'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
                 'maxvalue'=>array('db_key'=>_('maxvalue'),'menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
                 'minvalue'=>array('db_key'=>_('minvalue'),'menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
             );


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('table_key',1);


include('customers_export_common.php');



$smarty->display('customers_list.tpl');
?>
