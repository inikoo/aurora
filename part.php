<?php
/*
 File: part.php

 UI part page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');
//include_once('stock_functions.php');
include_once('class.Location.php');

include_once('class.Part.php');

$view_parts=$user->can_view('parts');

if (!$view_parts) {
    header('Location: index.php');
    exit();
}


$modify=$user->can_edit('parts');
$modify_stock=false;

$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);
$smarty->assign('modify',$modify);




$page='part';
$smarty->assign('page',$page);

/*
$parts_period=$_SESSION['state']['parts']['period'];
print $parts_period;

$parts_period_title=array(
            'three_year'=>_('Last 3 Years'),
            'year'=>_('Last Year'),
            'quarter'=>_('Last Quarter'),
            'month'=>_('Last Month'),
            'week'=>_('Last Week'),
            'all'=>_('All'));

$smarty->assign('parts_period',$parts_period);
$smarty->assign('parts_period_title',$parts_period_title[$parts_period]);
*/


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'css/part_locations.css',
                'css/edit.css',
               'theme.css.php'
           );

$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'dragdrop/dragdrop-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/common.js',
              'external_libs/amstock/amstock/swfobject.js',
              'js/table_common.js',
              'js/search.js',
              'edit_stock.js.php',
              'js/dropdown.js'
          );



$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);


$smarty->assign('view',$_SESSION['state']['part']['view']);

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])) {
    $part_id=$_REQUEST['id'];
    $_SESSION['state']['part']['id']=$part_id;
    $part= new part($part_id);
    $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
} else if (isset($_REQUEST['sku']) and is_numeric($_REQUEST['sku'])) {
    $part= new part('sku',$_REQUEST['sku']);
    $part_id=$part->id;
    $_SESSION['state']['part']['id']=$part_id;
    $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
} else {
    $part_id=$_SESSION['state']['part']['id'];
    $_SESSION['state']['part']['id']=$part_id;
    $part= new part($part_id);
    $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
}

$subject_id=$part->id;

if (!$part->id) {
    header('Location: warehouse.php?msg=part_not_found');
    exit;
}


$warehouse_keys=$part->get_warehouse_keys();
foreach($warehouse_keys as $warehouse_key) {
    if (in_array($warehouse_key,$user->warehouses)) {
        $warehouse=new Warehouse($warehouse_key);
        break;
    }
    header('Location: index.php?forbidden');
    exit;
}



//show case
$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field In Showcase`='Yes' and `Custom Field Table`='Part'");
$res = mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
    $custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$show_case=Array();
$sql=sprintf("select * from `Part Custom Field Dimension` where `Part SKU`=%d", $part->id);
$res=mysql_query($sql);
if ($row=mysql_fetch_array($res)) {

    foreach($custom_field as $key=>$value) {
        $show_case[$value]=$row[$key];
    }
}



$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Part'");
$res = mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
    $custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$part_custom_fields=Array();
$sql=sprintf("select * from `Part Custom Field Dimension` where `Part SKU`=%d", $part->id);
$res=mysql_query($sql);
if ($row=mysql_fetch_array($res)) {

    foreach($custom_field as $key=>$value) {
        $part_custom_fields[$value]=$row[$key];
    }
}


$smarty->assign('show_case',$show_case);
$smarty->assign('part_custom_fields',$part_custom_fields);
$smarty->assign('number_part_custom_fields',count($part_custom_fields));


$smarty->assign('part',$part);
$smarty->assign('parent','parts');
$smarty->assign('title',$part->get('SKU'));

$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);

$js_files[]='part.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('show_stock_history_chart',$_SESSION['state']['part']['stock_history']['show_chart']);
$smarty->assign('stock_history_chart_output',$_SESSION['state']['part']['stock_history']['chart_output']);

$smarty->assign('stock_history_type',$_SESSION['state']['part']['stock_history']['type']);

//$part->update_number_transactions();

$transactions=array(
	'all_transactions'=>$part->data['Part Transactions'],
	'in_transactions'=>$part->data['Part Transactions In'],
	'out_transactions'=>$part->data['Part Transactions Out'],
	'audit_transactions'=>$part->data['Part Transactions Audit'],
	'oip_transactions'=>$part->data['Part Transactions OIP'],
	'move_transactions'=>$part->data['Part Transactions Move'],
	);


$smarty->assign('transactions',$transactions);
$smarty->assign('transaction_type',$_SESSION['state']['part']['transactions']['view']);



$tipo_filter=$_SESSION['state']['part']['stock_history']['f_field'];
$smarty->assign('filter_show0',$_SESSION['state']['part']['stock_history']['f_show']);
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['part']['stock_history']['f_value']);
$filter_menu=array(
                 'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$tipo_filter=$_SESSION['state']['part']['transactions']['f_field'];
$smarty->assign('filter_show1',$_SESSION['state']['part']['transactions']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part']['transactions']['f_value']);
$filter_menu=array(
                 'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
                 'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$tipo_filter2=$_SESSION['state']['part']['delivery_notes']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['part']['delivery_notes']['f_value']));
$filter_menu2=array(
                  'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'DN Number'),
                  'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
                  'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
                  'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
                  'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
              );
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);

$order=$_SESSION['state']['warehouse']['parts']['order'];
if ($order=='sku') {
$_order='Part SKU';
     $order='P.`Part SKU`';
    $order_label=_('SKU');;
   
} else {
$_order='Part SKU';
     $order='P.`Part SKU`';
    $order_label=_('SKU');
}
//$_order=preg_replace('/`/','',$order);
$sql=sprintf("select  P.`Part SKU` as id , `Part Unit Description` as name from `Part Dimension` P left join  `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`)  where  `Warehouse Key`=%d  and %s < %s  order by %s desc  limit 1",
             $warehouse->id,
             $order,
             prepare_mysql($part->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $prev['link']='part.php?sku='.$row['id'];
    $prev['title']=$row['name'];
    $smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select P.`Part SKU` as id , `Part Unit Description` as name from `Part Dimension` P  left join  `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`) where  `Warehouse Key`=%d    and  %s>%s  order by %s   ",
  $warehouse->id,
             $order,
             prepare_mysql($part->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $next['link']='part.php?sku='.$row['id'];
    $next['title']=$row['name'];
    $smarty->assign('next',$next);
}
mysql_free_result($result);

$smarty->display('part.tpl');
?>
