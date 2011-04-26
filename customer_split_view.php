<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');
include_once('class.Store.php');
if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit;
}

$modify=$user->can_edit('contacts');


if (isset($_REQUEST['id_a']) and is_numeric($_REQUEST['id_a']) ) {
 
    $customer_id_a=$_REQUEST['id_a'];
} else {
   header('Location: customers.php?error=no_id_a');
    exit();
}
$customer_a=new customer($customer_id_a);


if (isset($_REQUEST['id_b']) and is_numeric($_REQUEST['id_b']) ) {
 
    $customer_id_b=$_REQUEST['id_b'];
} else {
   header('Location: customers.php?error=no_id_b');
    exit();
}
$customer_b=new customer($customer_id_b);


if (!$customer_a->id or !$customer_b->id) {
    header('Location: customers.php?error=Customers_not_exists');
    exit();

}

if ($customer_a->data['Customer Store Key']!=$customer_b->data['Customer Store Key']) {
    header('Location: customers.php?error=Customers_not_same_store');
    exit();

}

$store=new Store($customer_a->data['Customer Store Key']);
$smarty->assign('store',$store);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               'text_editor.css',
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'css/customer.css'

           );
include_once('Theme.php');
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'customer_split_view.js.php'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('customer_a',$customer_a);
$smarty->assign('customer_b',$customer_b);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');




$general_options_list=array();




if ($modify) {

    $general_options_list[]=array('tipo'=>'js','id'=>'open_merge_dialog','label'=>_('Merge'));

}

//  $general_options_list[]=array('tipo'=>'url','url'=>'customer_csv.php?id='.$customer->id,'label'=>_('Export Data (CSV)'));

$general_options_list[]=array('tipo'=>'url','url'=>'customers.php','label'=>_('Customers'));


$smarty->assign('general_options_list',$general_options_list);






$smarty->assign('options_box_width','550px');
$smarty->assign('customer_id_prefix',$myconf['customer_id_prefix']);

//$smarty->assign('id_a',$myconf['customer_id_prefix'].sprintf("%05d",$customer_a->id));
//$smarty->assign('id_b',$myconf['customer_id_prefix'].sprintf("%05d",$customer_b->id));

$smarty->display('customer_split_view.tpl');

?>
