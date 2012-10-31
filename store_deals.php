<?php
/*
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 201q, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');
if (!$user->can_view('stores') or count($user->stores)==0 ) {
	
    header('Location: index.php');
    exit;
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['store']['id'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);




$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('block_view',$_SESSION['state']['store_offers']['view']);

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'button.css',
               'css/container.css',
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
              'js/edit_common.js',
              'js/search.js',
              'store_deals.js.php',
          
          );


$smarty->assign('parent','marketing');
$smarty->assign('title', _('Store Offers').' ('.$store->data['Store Code'].')');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


;
  
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$tipo_filter=$_SESSION['state']['store']['campaigns']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['store']['campaigns']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'notes','menu_label'=>_('Campaigns with name like *<i>x</i>*'),'label'=>_('Name')),
               
            
             );
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter=$_SESSION['state']['store_offers']['offers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['store_offers']['offers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Offers with name like *<i>x</i>*'),'label'=>_('Name')),
                  'code'=>array('db_key'=>'code','menu_label'=>_('Offers with code like x</i>*'),'label'=>_('Code')),

            
             );
$smarty->assign('filter_menu1',$filter_menu);
             
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$elements_number=array('Order'=>0,'Department'=>0,'Family'=>0,'Product'=>0);
$sql=sprintf("select count(*) as num,`Deal Terms Object` from  `Deal Dimension` where `Store Key`=%d group by `Deal Terms Object`",$store->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Deal Terms Object']]=$row['num'];
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['store_offers']['offers']['elements']);

$smarty->display('store_deals.tpl');
?>
