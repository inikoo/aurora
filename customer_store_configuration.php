<?php
/*

  About:
  Autor: Migara Ekanayake

  Copyright (c) 2011, Inikoo

  Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');


if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit();
}

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

$smarty->assign('store',$store);

$smarty->assign('store_key',$store->id);
$smarty->assign('scope','customer');

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'text_editor.css',
               'common.css',
               'button.css',
               'css/container.css',
               'table.css',
               'css/edit.css',
               'theme.css.php'
           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'animation/animation-min.js',

              $yui_path.'datasource/datasource.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'js/phpjs.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'customer_store_configuration.js.php?store_key='.$store->id
          );



$view=$_SESSION['state']['customer_store_configuration']['view'];


//print $view;
$smarty->assign('view',$view);
$_SESSION['state']['customer']['view']=$view;



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','customers');

$smarty->assign('title','Creating New Customer');
$smarty->display('customer_store_configuration.tpl');




?>

