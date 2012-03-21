<?php
include_once('common.php');
include_once('class.Store.php');

if (!$user->can_view('customers') ) {
    header('Location: index.php');
    exit;
}
$modify=$user->can_edit('customers');
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['customers']['store'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);
if ($store->id) {
    $_SESSION['state']['customers']['store']=$store->id;
} else {
    header('Location: index.php?error=store_not_found');
    exit();
}

//print_r($_SESSION['state']['customers']);

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);


$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);


$smarty->assign('modify',$modify);



//$general_options_list[]=array('tipo'=>'js','id'=>'export_data','label'=>_('Export Data(CSV)'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_address_label.pdf.php?label=l7159&scope=list&id='.$id,'label'=>_('Print Address Labels'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Customers Lists'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

//$smarty->assign('general_options_list',$general_options_list);

//$smarty->assign('options_box_width','450px');



$css_files=array(
                 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
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
                  'js/csv_common.js',

              'common_customers.js.php',
              'customers_send_post.js.php'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','customers');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['customers']['table']['view']);

$smarty->assign('title', _('Post to send'));
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['table']['f_value']);

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


$smarty->display('customers_send_post.tpl');
?>
