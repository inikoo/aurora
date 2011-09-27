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


$view_sales=false;
$view_stock=false;
$view_orders=false;
$create=false;
$modify=$user->can_edit('parts');

$modify_stock=false;
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=false;
$view_cust=false;
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);

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
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
               $yui_path.'container/assets/skins/sam/container.css',
               'button.css',
                'css/part_locations.css'
           );

include_once('Theme.php');

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

if(!$part->id){
header('Location: warehouse.php?msg=part_not_found');
exit;

}


$warehouse_key=0;


//show case 		
$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field In Showcase`='Yes' and `Custom Field Table`='Part'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$show_case=Array();
$sql=sprintf("select * from `Part Custom Field Dimension` where `Part SKU`=%d", $part->id);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){

	foreach($custom_field as $key=>$value){
		$show_case[$value]=$row[$key];
	}
}



$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Part'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$part_custom_fields=Array();
$sql=sprintf("select * from `Part Custom Field Dimension` where `Part SKU`=%d", $part->id);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){

	foreach($custom_field as $key=>$value){
		$part_custom_fields[$value]=$row[$key];
	}
}


$smarty->assign('show_case',$show_case);	
$smarty->assign('part_custom_fields',$part_custom_fields);	



$general_options_list=array();
if($warehouse_key){
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse.php?id='.$warehouse->id,'label'=>_('Warehouse'));
$general_options_list[]=array('tipo'=>'url','url'=>'locations.php?warehouse_id='.$warehouse->id,'label'=>_('Locations'));
$general_options_list[]=array('tipo'=>'url','url'=>'parts.php?warehouse_id='.$warehouse->id,'label'=>_('Parts'));
}else{
$general_options_list[]=array('tipo'=>'url','url'=>'warehouses.php','label'=>_('Warehouse'));
$general_options_list[]=array('tipo'=>'url','url'=>'locations.php','label'=>_('Locations'));
$general_options_list[]=array('tipo'=>'url','url'=>'parts.php','label'=>_('Parts'));

}



if ($modify) {
    $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_part.php?id='.$part->id,'label'=>_('Edit Part'));

}
$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('part',$part);
$smarty->assign('parent','warehouses');
$smarty->assign('title',$part->get('SKU'));

$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);

$js_files[]='part.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('show_stock_history_chart',$_SESSION['state']['part']['show_stock_history_chart']);
$smarty->assign('stock_history_type',$_SESSION['state']['part']['stock_history']['type']);



$transactions=array('all_transactions'=>0,'in_transactions'=>0,'out_transactions'=>0,'audit_transactions'=>0,'oip_transactions'=>0,'move_transactions'=>0);
$sql=sprintf("select count(*) as all_transactions , sum(if(`Inventory Transaction Type`='Not Found' or `Inventory Transaction Type`='No Dispatched' or `Inventory Transaction Type`='Associate' or `Inventory Transaction Type`='Disassociate' or `Inventory Transaction Type`='Adjust',1,0)) as audit_transactions,sum(if(`Inventory Transaction Type`='Move In' or `Inventory Transaction Type`='Move Out',1,0)) as move_transactions,sum(if(`Inventory Transaction Type`='Sale' or `Inventory Transaction Type`='Broken' or `Inventory Transaction Type`='Lost',1,0)) as out_transactions, sum(if(`Inventory Transaction Type`='Order In Process',1,0)) as oip_transactions, sum(if(`Inventory Transaction Type`='In',1,0)) as in_transactions from `Inventory Transaction Fact` where `Part SKU`=%d",$part_id);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $transactions=array('all_transactions'=>$row['all_transactions'],'in_transactions'=>$row['in_transactions'],'out_transactions'=>$row['out_transactions'],'audit_transactions'=>$row['audit_transactions'],'oip_transactions'=>$row['oip_transactions'],'move_transactions'=>$row['move_transactions']);
}
$smarty->assign('transactions',$transactions);
$smarty->assign('transaction_type',$_SESSION['state']['part']['transactions']['view']);


$q='';
$tipo_filter=($q==''?$_SESSION['state']['part']['transactions']['f_field']:'note');
$smarty->assign('filter_show1',$_SESSION['state']['part']['transactions']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',($q==''?$_SESSION['state']['part']['transactions']['f_value']:addslashes($q)));
$filter_menu=array(
                 'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
                 'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
// include_once('class.PartLocation.php');$part->update_stock_history();


$part->load_images_slidesshow();
$images=$part->images_slideshow;
$smarty->assign('div_img_width',190);
$smarty->assign('img_width',190);
$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));

$smarty->display('part.tpl');
?>
