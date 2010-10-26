<?php
/*
 File: supplier.php

 UI supplier page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('common.php');
include_once('class.Supplier.php');

if ($user->data['User Type']!='Supplier' and !$user->can_view('suppliers')) {
      $smarty->display('forbidden.tpl');
    exit;
}


$modify=$user->can_edit('suppliers');

if (isset($_REQUEST['edit']) and $_REQUEST['edit']) {
    header('Location: edit_suplier.php?id='.$_REQUEST['edit']);
    exit;
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
    $supplier_id=$_REQUEST['id'];
else
    $supplier_id=$_SESSION['state']['supplier']['id'];


if ($user->data['User Type']=='Supplier' and !in_array($supplier_id,$user->suppliers)) {

    $smarty->display('forbidden.tpl');
    exit;
}

$_SESSION['state']['supplier']['id']=$supplier_id;
$smarty->assign('supplier_id',$supplier_id);

$smarty->assign('orders_view',$_SESSION['state']['supplier']['orders_view']);

$supplier=new Supplier($supplier_id);
if (!$supplier->id) {
    header('Location: suppliers.php?msg=SNPF');
    exit;
}


$show_details=$_SESSION['state']['supplier']['details'];
$smarty->assign('show_details',$show_details);

$general_options_list=array();


if ($modify) {
    $general_options_list[]=array('tipo'=>'url','url'=>'edit_supplier.php?id='.$supplier_id,'label'=>_('Edit Supplier'));
    $general_options_list[]=array('tipo'=>'url','url'=>'supplier_invoice.php?new=1&supplier_id='.$supplier_id,'label'=>_('Input Invoice'));
    $general_options_list[]=array('tipo'=>'url','url'=>'supplier_delivery.php?new=1&supplier_id='.$supplier_id,'label'=>_('New Delivery'));
    $general_options_list[]=array('tipo'=>'url','url'=>'porder.php?new=1&supplier_id='.$supplier_id,'label'=>_('New Purchase Order'));

}
$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));




$smarty->assign('general_options_list',$general_options_list);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               //$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

               'common.css',
               'button.css',
               'container.css',
               'table.css'
           );
$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'connection/connection-debug.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'animation/animation-min.js',

              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'common.js.php',
              'table_common.js.php',

          );





$company=new Company($supplier->data['Supplier Company Key']);
//$supplier->load('contacts');
$smarty->assign('supplier',$supplier);
$smarty->assign('company',$company);

$address=new address($company->data['Company Main Address Key']);
$smarty->assign('address',$address);



$smarty->assign('parent','suppliers');
$smarty->assign('title','Supplier: '.$supplier->get('Supplier Code'));


$tipo_filter=$_SESSION['state']['supplier']['products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier']['products']['f_value']);

$filter_menu=array(
                 'p.code'=>array('db_key'=>_('p.code'),'menu_label'=>'Our Product Code','label'=>'Code'),
                 'sup_code'=>array('db_key'=>_('sup_code'),'menu_label'=>'Supplier Product Code','label'=>'Supplier Code'),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$js_files[]=sprintf('supplier.js.php');
$smarty->assign('display',$_SESSION['state']['supplier']['display']);
$smarty->assign('products_view',$_SESSION['state']['supplier']['products']['view']);
$smarty->assign('products_percentage',$_SESSION['state']['supplier']['products']['percentage']);
$smarty->assign('products_period',$_SESSION['state']['supplier']['products']['period']);


$tipo_filter=$_SESSION['state']['supplier']['po']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['supplier']['po']['f_value']);

$filter_menu=array(
                 'id'=>array('db_key'=>_('p.code'),'menu_label'=>'Purchase order','label'=>'Id'),
                 'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
                 'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
                 'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('supplier.tpl');

?>