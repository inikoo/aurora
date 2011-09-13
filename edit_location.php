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

if(!$user->can_view('warehouses') or !$user->can_edit('warehouses')  ){
  header('Location: index.php');
  exit;
}



if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $location_id=1;
else
  $location_id=$_REQUEST['id'];
$_SESSION['state']['location']['id']=$location_id;


$location= new location($location_id);
//print_r($location);

if( !$location->id or   !in_array($location->data['Location Warehouse Key'],$user->warehouses   ) ){
  header('Location: index.php');
  exit;
}


if(!$user->can_edit('warehouses')  ){
  header('Location: location.php?id='.$location->id);
  exit;
}

$smarty->assign('edit',$_SESSION['state']['location']['edit']);

$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'location.php?id='.$location->id,'label'=>_('Exit Edit'));
$smarty->assign('general_options_list',$general_options_list);


$used_for_list=array(
                  'picking'=>array('name'=>_('Picking')),
                  'storing'=>array('name'=>_('Storing')),
                  'loading'=>array('name'=>_('Loading')),
                  'displaying'=>array('name'=>_('Displaying')),
				  'other'=>array('name'=>_('Other'))
              );
			  
$smarty->assign('used_for_list',$used_for_list);

$shape_type_list=array(
                  'box'=>array('name'=>_('Box')),
                  'cylinder'=>array('name'=>_('Cylinder')),
                  'unknown'=>array('name'=>_('Unknown'))
              );
			  
$smarty->assign('shape_type_list',$shape_type_list);


$has_stock_list=array(
                  'yes'=>array('name'=>_('Yes')),
                  'no'=>array('name'=>_('No')),
                  'unknown'=>array('name'=>_('Unknown'))
              );
			  
$smarty->assign('has_stock_list',$has_stock_list);
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',

		 'common.css',
		 //	 'container.css',
		 'button.css',
		 'table.css',
		 		 'css/dropdown.css'

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
		'js/edit_common.js',
		'edit_location.js.php?location_id='.$location_id
		);




$order=$_SESSION['state']['locations']['table']['order'];

if($order=='code'){
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
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');
mysql_free_result($result);
$sql=sprintf("select `Location Key` as id,`Location Code` as code  from `Location Dimension` where  %s>'%s'   order by %s   ",$order,$location->data[$_order],$order);
//print "$sql";
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);
$location->load('product');
$smarty->assign('parent','warehouses');
$smarty->assign('title',_('Editing Location ').$location->data['Location Code']);
$smarty->assign('location',$location);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('edit_location.tpl');
?>