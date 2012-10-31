<?php
/*
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2011, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');
include_once('class.Deal.php');

if (!$user->can_view('stores') or count($user->stores)==0 ) {
	
    header('Location: index.php');
    exit;
}



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $deal=new Deal($_REQUEST['id']);
    $store_id=$deal->data['Store Key'];
    if(!$deal->id){
    header('Location: index.php');
    exit;
    }

} else {
   header('Location: index.php');
    exit;

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
	
    header('Location: index.php');
    exit;
}

$modify=$user->can_edit('stores');
$smarty->assign('modify',$modify);



$store=new Store($store_id);



$smarty->assign('deal',$deal);

$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('block_view',$_SESSION['state']['deal']['view']);

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
              'deal.js.php',
          
          );


if($deal->get('Deal Terms Type')=='Order Interval'){




$js_files[]=$yui_path.'editor/editor-min.js';
$js_files[]=$yui_path.'slider/slider-min.js';
$js_files[]=$yui_path.'colorpicker/colorpicker-min.js';

$js_files[]='js/editor_image_uploader.js';
    $js_files[]='js/rgbcolor.js';
$js_files[]='js/build_email.js';	



    $color_schemes=array();
    $sql=sprintf("select * from `Email Template Color Scheme Dimension` where `Store Key`=%d  limit 100",$store->id);
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        $color_scheme=array();
        foreach($row as $key=>$value) {
            $color_scheme[preg_replace('/ /','_',$key)]=$value;
        }
        $color_schemes[]=$color_scheme;
    }
    $smarty->assign('color_schemes',$color_schemes);



$smarty->assign('filter10','name');
$smarty->assign('filter_value10','');
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Name like <i>x</i>'),'label'=>_('Name')),
);
$smarty->assign('filter_menu10',$filter_menu);
$smarty->assign('filter_name10',$filter_menu['name']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu10',$paginator_menu);


$smarty->assign('filter11','name');
$smarty->assign('filter_value11','');
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Name like <i>x</i>'),'label'=>_('Name')),
);
$smarty->assign('filter_menu11',$filter_menu);
$smarty->assign('filter_name11',$filter_menu['name']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu11',$paginator_menu);


}


$smarty->assign('parent','marketing');
$smarty->assign('title',$deal->data['Deal Name']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


;
  
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$tipo_filter=$_SESSION['state']['deal']['customers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['deal']['customers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
              //   'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
                 'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),


             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$tipo_filter=$_SESSION['state']['deal']['orders']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['deal']['orders']['f_value']);
$filter_menu=array(
                 'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number'),'label'=>_('Number')),
              //   'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
                 'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer Name'),'label'=>_('Customer')),


             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('paginator_menu1',$paginator_menu);



$smarty->display('deal.tpl');
?>
