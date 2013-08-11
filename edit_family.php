<?php
/*
 File: family.php

 UI family page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


include_once('common.php');
include_once('class.Family.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');

if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
    $family_id=$_SESSION['state']['family']['id'];
else
    $family_id=$_REQUEST['id'];
$_SESSION['state']['family']['id']=$family_id;

$family=new Family($family_id);



//print_r($page_data);

$_SESSION['state']['department']['id']=$family->data['Product Family Main Department Key'];
$_SESSION['state']['store']['id']=$family->data['Product Family Store Key'];



if (!( $user->can_view('stores') and in_array($family->data['Product Family Store Key'],$user->stores))) {
    header('Location: index.php');
    exit();
}

$store=new Store($family->data['Product Family Store Key']);

$can_delete = true;

//if($store->data['Store Orphan Products Family Key'] == $family->id){
//	$can_delete = false;
//}



$smarty->assign('can_delete',$can_delete);


$department=new Department($family->get('Product Family Main Department Key'));

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');

if (!$modify) {
    header('Location: family.php?id='.$family_id);
    exit();
}


if (isset($_REQUEST['edit_tab']) and in_array($_REQUEST['edit_tab'],array('web'))) {
    $edit=$_REQUEST['edit_tab'];
    $_SESSION['state']['family']['editing']=$edit;
} else {
    $edit=$_SESSION['state']['family']['editing'];
}

$smarty->assign('edit',$edit);


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);



$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'css/edit.css',
               'css/upload_files.css',
               'theme.css.php'
           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'uploader/uploader.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/search.js',
              'js/table_common.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'js/pages_common.js',
              'js/edit_common.js',
              'country_select.js.php',
              'js/upload_image.js',
              'edit_family.js.php?id='.$family->id.'&store_key='.$store->id,
              'js/asset_elements.js'
          );


$smarty->assign('yui_path',$yui_path);





$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('store_key',$store->id);



$_SESSION['state']['assets']['page']='department';
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['product']['view']=$_REQUEST['view'];

}

$department_order=$_SESSION['state']['department']['families']['order'];
$department_period=$_SESSION['state']['department']['period'];
//$department_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));


//$smarty->assign('department_period',$department_period);
//$smarty->assign('department_period_title',$department_period_title[$department_period]);

$smarty->assign('pages_view',$_SESSION['state']['family']['edit_pages']['view']);



if (isset($_REQUEST['department_id']) and $_REQUEST['department_id']>0) {
    $department_id=$_REQUEST['department_id'];
    $order=$_SESSION['state']['department']['families']['order'];
    if ($order=='per_tsall' or $order=='tsall')
        $order='total_sales';
    if ($order=='per_tsm' or $order=='tms')
        $order='month_sales';
    if ($order=='code')
        $order='Product Family Code';
    if ($order=='name')
        $order='Product Family Name';
    if ($order=='active')
        $order='Product Family For Sale Products';
    if ($order=='outofstock')
        $order='Product Family Out Of Stock Products';
    if ($order=='stockerror')
        $order='Product Family Unknown Stock Products';





    $sql=sprintf("select  F.`Product Family Key` as id, `Product Family Code` as code  from `Product Family Dimension`   F left join `Product Family Department Bridge` FD on (FD.`Product Family Key`=F.`Product Family Key`) where  `%s`<'%s' and `Product Department Key`=%d  order by `%s` desc  ",$order,$family->get($order),$department_id,$order);


    $res = mysql_query($sql);
    if (!$prev=mysql_fetch_array($res, MYSQL_ASSOC))
        $prev=array('id'=>0,'code'=>'');

    $sql=sprintf("select F.`Product Family Key` as id, `Product Family Code` as code   from `Product Family Dimension`   F left join `Product Family Department Bridge`  FD on (FD.`Product Family Key`=F.`Product Family Key`)  where  `%s`>'%s' and `Product Department Key`=%d order by `%s`   ",$order,$family->get($order),$department_id,$order);

    $res = mysql_query($sql);

    if (!$next=mysql_fetch_array($res, MYSQL_ASSOC))
        $next=array('id'=>0,'code'=>'');






    $smarty->assign('prev',$prev);
    $smarty->assign('next',$next);

}







$smarty->assign('parent','products');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
// $smarty->assign('department',$family->get('department'));
// $smarty->assign('department_id',$family->data['department_id']);
// $smarty->assign('products',$family->get('product_numbers'));
// $smarty->assign('data',$family->data);




$smarty->assign('family',$family);
$smarty->assign('store',$store);
$smarty->assign('department',$department);





$q='';
$tipo_filter=($q==''?$_SESSION['state']['family']['products']['f_field']:'code');
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['family']['products']['f_value']:addslashes($q)));
$filter_menu=array(


             );
$smarty->assign('filter_menu0',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);


//print show_currency_conversion('USD','GBP');



$units_tipo=array(
                'Piece'=>array('fname'=>_('Piece'),'name'=>'Piece','selected'=>false),
                'Grams'=>array('fname'=>_('Grams'),'name'=>'Grams','selected'=>false),
                'Liters'=>array('fname'=>_('Liters'),'name'=>'Liters','selected'=>false),
                'Meters'=>array('fname'=>_('Meters'),'name'=>'Meters','selected'=>false),
                'Other'=>array('fname'=>_('Other'),'name'=>'Other','selected'=>false),
            );
$units_tipo['Piece']['selected']=true;

$smarty->assign('units_tipo',$units_tipo);
$smarty->assign('title', _('Editing Family').': '.$family->get('Product Family Code'));



$smarty->assign('view',$_SESSION['state']['family']['edit_products']['view']);


$tipo_filter=$_SESSION['state']['family']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['family']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
                 'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
                 'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

             );
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$tipo_filter2='code';
$filter_menu2=array(
                  'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
                  'name'=>array('db_key'=>_('name'),'menu_label'=>_('Name'),'label'=>_('Name')),
              );
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');


$tipo_filter7='code';
$filter_menu7=array(
                  'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
                  'header'=>array('db_key'=>_('header'),'menu_label'=>_('Header'),'label'=>_('Header')),
              );
$smarty->assign('filter_name7',$filter_menu7[$tipo_filter7]['label']);
$smarty->assign('filter_menu7',$filter_menu7);
$smarty->assign('filter7',$tipo_filter7);
$smarty->assign('filter_value7','');




$tipo_filter=$_SESSION['state']['family']['edit_pages']['f_field'];
$smarty->assign('filter6',$tipo_filter);
$smarty->assign('filter_value6',$_SESSION['state']['family']['edit_pages']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>_('code'),'menu_label'=>_('Code'),'label'=>_('Code')),
                  'header'=>array('db_key'=>_('header'),'menu_label'=>_('Header'),'label'=>_('Header')),

             );
$smarty->assign('filter_menu6',$filter_menu);
$smarty->assign('filter_name6',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu6',$paginator_menu);










$order=$_SESSION['state']['department']['families']['order'];
if ($order=='code') {
    $order='`Product Family Code`';
    $order_label=_('Code');
} else {
     $order='`Product Family Code`';
    $order_label=_('Code');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d  and %s < %s  order by %s desc  limit 1",
             $family->data['Product Family Main Department Key'],
             $order,
             prepare_mysql($family->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $prev['link']='edit_family.php?id='.$row['id'];
    $prev['title']=$row['name'];
    $smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select`Product Family Key` as id , `Product Family Code` as name from `Product Family Dimension`  where  `Product Family Main Department Key`=%d   and  %s>%s  order by %s   ",
  $family->data['Product Family Main Department Key'],
             $order,
             prepare_mysql($family->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $next['link']='edit_family.php?id='.$row['id'];
    $next['title']=$row['name'];
    $smarty->assign('next',$next);
}
mysql_free_result($result);


$smarty->assign('elements_product_elements_type',$_SESSION['state']['family']['products']['elements_type']);
$smarty->assign('elements_type',$_SESSION['state']['family']['products']['elements']['type']);
$smarty->assign('elements_web',$_SESSION['state']['family']['products']['elements']['web']);
$smarty->assign('elements_stock',$_SESSION['state']['family']['products']['elements']['stock']);
$smarty->assign('elements_stock_aux',$_SESSION['state']['family']['products']['elements_stock_aux']);

$smarty->assign('show_history',$_SESSION['state']['family']['show_history']);

$smarty->display('edit_family.tpl');

?>
