<?php
/*
 File: customers.php

 UI customers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit;
}

if (!$user->can_edit('customers')) {
    header('Location: customers.php');
    exit;
}



if (isset($_REQUEST['list_key'])  and is_numeric($_REQUEST['list_key']) ) {

    $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['list_key']);

    $res=mysql_query($sql);
    if (!$customer_list_data=mysql_fetch_assoc($res)) {
        header('Location: index.php?error=id_in_customers_list_not_found');
        exit;

    }
    $store_key=$customer_list_data['List Parent Key'];


    $customer_list_name=$customer_list_data['List Name'];
    $smarty->assign('customer_list_name',$customer_list_name);
    $smarty->assign('customer_list_id',$customer_list_data['List Key']);




} else if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_key=$_REQUEST['store'];
  $smarty->assign('customer_list_name','');
    $smarty->assign('customer_list_id',0);
} else {
    $store_key=$_SESSION['state']['customers']['store'];
$smarty->assign('customer_list_name','');
    $smarty->assign('customer_list_id',0);
}




if (!($user->can_view('stores') and in_array($store_key,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}






$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');




$store=new Store($store_key);
$smarty->assign('store',$store);

$_SESSION['state']['customers']['store']=$store_key;

$smarty->assign('store_key',$store->id);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'css/edit.css',
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
              'js/search.js',
              'js/table_common.js',
              'js/edit_common.js',
              	'js/customers_common.js',

              'edit_customers.js.php'
          );



$smarty->assign('options_box_width','200px');

$smarty->assign('parent','customers');
$smarty->assign('title', _('Edit Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);

$smarty->assign('view',$_SESSION['state']['customers']['edit_customers']['view']);

$tipo_filter=$_SESSION['state']['customers']['edit_customers']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['edit_customers']['f_value']);

$filter_menu=array(
	'customer name'=>array('db_key'=>'customer name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
	'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
	'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),
	'min'=>array('db_key'=>'min','menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
	'max'=>array('db_key'=>'min','menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
	'last_more'=>array('db_key'=>'last_more','menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
	'last_less'=>array('db_key'=>'last_more','menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
);


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$smarty->assign('orders_type',$_SESSION['state']['customers']['edit_customers']['orders_type']);
$smarty->assign('elements_activity',$_SESSION['state']['customers']['edit_customers']['elements']['activity']);
$smarty->assign('elements_level_type',$_SESSION['state']['customers']['edit_customers']['elements']['level_type']);
$smarty->assign('elements_customers_elements_type',$_SESSION['state']['customers']['edit_customers']['elements_type']);



$smarty->display('edit_customers.tpl');
?>
