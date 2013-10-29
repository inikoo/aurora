<?php
/*
 File: marketing.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');

include_once('class.Product.php');
include_once('class.Order.php');

if(!$user->can_view('marketing')){
  header('Location: index.php');
    exit;
}

if($user->can_edit('marketing')){
$modify=true;
}else{
$modify=false;
}
$smarty->assign('modify',$modify);




if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['marketing']['store'];
}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);
 $store->update_email_campaign_data();
if ($store->id) {
    $_SESSION['state']['marketing']['store']=$store_id;
} else {
    header('Location: index.php');
    exit;
}

$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);


$smarty->assign('search_scope','marketing');
$smarty->assign('search_label',_('Search'));






$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
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
              'js/list_function.js',
              	'js/deals_common.js',
              'marketing.js.php',
              'js/menu.js'
          );



if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['marketing']['view']=$_REQUEST['view'];

}
$smarty->assign('view',$_SESSION['state']['marketing']['view']);

$smarty->assign('parent','marketing');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$q='';
$tipo_filter=($q==''?$_SESSION['state']['marketing']['email_campaigns']['f_field']:'code');
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['marketing']['email_campaigns']['f_value']:addslashes($q)));
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>'Campaign with name like <i>x</i>','label'=>'Name')
             );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$tipo_filter=$_SESSION['state']['marketing']['offers']['f_field'];
$smarty->assign('filter10',$tipo_filter);
$smarty->assign('filter_value10',$_SESSION['state']['marketing']['offers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
                  'code'=>array('db_key'=>'code','menu_label'=>_('Offers with code like x</i>*'),'label'=>_('Code')),
            );
$smarty->assign('filter_menu10',$filter_menu);
             
$smarty->assign('filter_name10',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu10',$paginator_menu);


$tipo_filter=$_SESSION['state']['marketing']['campaigns']['f_field'];
$smarty->assign('filter11',$tipo_filter);
$smarty->assign('filter_value11',$_SESSION['state']['marketing']['campaigns']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Campaign with name like *<i>x</i>*'),'label'=>_('Name')),
                  'code'=>array('db_key'=>'code','menu_label'=>_('Campaign with code like x</i>*'),'label'=>_('Code')),
            );
$smarty->assign('filter_menu11',$filter_menu);
             
$smarty->assign('filter_name11',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu11',$paginator_menu);

$deals_block_view=$_SESSION['state']['marketing']['deals_block_view'];
$smarty->assign('deals_block_view',$deals_block_view);

$smarty->assign('offer_elements',$_SESSION['state']['marketing']['offers']['elements']);



$smarty->display('marketing.tpl');


?>
