<?php
/*
 File: customers.php 

 UI customers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');

if(!$user->can_view('reports')){
  header('Location: index.php');
  exit();
}


$general_options_list=array();

$smarty->assign('general_options_list',$general_options_list);


if(isset($_REQUEST['year']) and preg_match('/\d{2,4}/',$_REQUEST['year'])){
$year=$_REQUEST['year'];
$_SESSION['state']['report_data']['ES1']['year']=$year;
}


if(isset($_REQUEST['umbral'])){
list($tmp,$umbral)=parse_money($_REQUEST['umbral']);
$_SESSION['state']['report_data']['ES1']['umbral']=$umbral;
}


$year=$_SESSION['state']['report_data']['ES1']['year'];
$umbral=$_SESSION['state']['report_data']['ES1']['umbral'];


$titulo=_('Customers who invoiced more than').' '.money($umbral).' '._('in').' '.$year;

$smarty->assign('titulo',$titulo);
$smarty->assign('umbral',money($umbral));
$smarty->assign('year',$year);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'css/container.css',
               'button.css',
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
		'js/search.js',
		'js/dropdown.js',
		'report_tax_ES1.js.php?umbral='.$umbral.'&year='.$year
		);


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','reports');
$smarty->assign('title', _('Modelo 337'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['customers']['table']['f_value']);

$filter_menu=array(
		   'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>'Customer Name','label'=>'Name'),
		   'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>'Customer Postcode','label'=>'Postcode'),
		   'min'=>array('db_key'=>_('min'),'menu_label'=>'Mininum Number of Orders','label'=>'Min No Orders'),
		   'max'=>array('db_key'=>_('min'),'menu_label'=>'Maximum Number of Orders','label'=>'Max No Orders'),

		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('report_tax_ES1.tpl');






?>
