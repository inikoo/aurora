<?php
/*
 File: location.php

 UI location page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Location.php');
include_once('class.Warehouse.php');



if (!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
    $location_id=1;
else
    $location_id=$_REQUEST['id'];
$_SESSION['state']['location']['id']=$location_id;


$location= new location($location_id);

$warehouse=new Warehouse($location->data['Location Warehouse Key']);
$smarty->assign('warehouse',$warehouse);


$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');


$create=$user->can_create('locations');
$modify=$user->can_edit('locations');
$modify_stock=$user->can_edit('product stock');

$smarty->assign('search_label',_('Locations'));
$smarty->assign('search_scope','locations');

$smarty->assign('modify_stock',$modify_stock);

$view_suppliers=$user->can_view('suppliers');
$view_cust=$user->can_view('customers');

$smarty->assign('view',$_SESSION['state']['location']['view']);


$general_options_list=array();
if ($modify){
    $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_location.php?id='.$location_id,'label'=>_('Edit Location'));
    $general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'add_part','label'=>_('Add Part'));

}
$smarty->assign('general_options_list',$general_options_list);




$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);



$css_files=array(
              $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
                'css/edit.css',
               'theme.css.php'
           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/common.js',
              'js/search.js',
              'js/table_common.js',
              'js/dropdown.js',
              'edit_stock.js.php'

          );



$order=$_SESSION['state']['warehouse']['locations']['order'];

if ($order=='code') {
    $order='`Location Code`';
}
elseif($order=='parts')
$order='`Location Distinct Parts`';
elseif($order=='max_volumen')
$order='`Location Max Volume`';
elseif($order=='max_weight')
$order='`Location Max Weight`';
elseif($order=='tipo')
$order='`Location Mainly Used For`';
elseif($order=='area')
$order='`Location Area`';
$_order=str_replace('`','',$order);

$sql=sprintf("select `Location Key` as id,`Location Code` as code from `Location Dimension` where  %s<'%s'  order by %s desc  ",$order,$location->data[$_order],$order);
$result=mysql_query($sql);
if (!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
    $prev=array('id'=>0,'code'=>'');
mysql_free_result($result);
$sql=sprintf("select `Location Key` as id,`Location Code` as code  from `Location Dimension` where  %s>'%s'   order by %s   ",$order,$location->data[$_order],$order);
//print "$sql";
$result=mysql_query($sql);
if (!$next=mysql_fetch_array($result, MYSQL_ASSOC))
    $next=array('id'=>0,'code'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);


$location->load('product');

//print_r($locations);


$smarty->assign('parent','warehouses');
$smarty->assign('title',_('Location ').$location->data['Location Code']);

$smarty->assign('has_stock',$location->get('Location Has Stock'));

$smarty->assign('parts',$location->parts);
$smarty->assign('num_parts',count($location->parts));

$js_files[]='js/edit_common.js';

$js_files[]='location.js.php';

$smarty->assign('location',$location);


$flag=$location->data['Location Flag'];
$flag_name="flag_".strtolower($location->data['Location Flag']).".png";

$smarty->assign('flag_name',$flag_name);
$flag_list=array(
	'blue'=>array('name'=>_('Blue')),
	'green'=>array('name'=>_('Green')),
	'orange'=>array('name'=>_('Orange')),
	'pink'=>array('name'=>_('Pink')),
	'purple'=>array('name'=>_('Purple')),
	'red'=>array('name'=>_('Red')),
	'yellow'=>array('name'=>_('Yellow'))
);

$smarty->assign('flag_list',$flag_list);



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$tipo_filter0=$_SESSION['state']['location']['stock_history']['f_field'];
$filter_menu0=array(
                  'note'=>array('db_key'=>_('note'),'menu_label'=>'Part SKU','label'=>_('Note')),
		   'author'=>array('db_key'=>_('author'),'menu_label'=>'Used in','label'=>_('Author')),
              );
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0','');

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter1=$_SESSION['state']['location']['parts']['f_field'];
$filter_menu1=array(
                  'sku'=>array('db_key'=>_('code'),'menu_label'=>'Part SKU','label'=>'SKU'),
		   'used_in'=>array('db_key'=>_('used_in'),'menu_label'=>'Used in','label'=>_('Used in')),
              );
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1','');

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$smarty->assign('filter2','used_in');
$smarty->assign('filter_value2','');
$filter_menu=array(
		   'sku'=>array('db_key'=>_('code'),'menu_label'=>'Part SKU','label'=>'SKU'),
		   'used_in'=>array('db_key'=>_('used_in'),'menu_label'=>'Used in','label'=>'Used in'),

		   );
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu['used_in']['label']);


$smarty->display('location.tpl');
?>
