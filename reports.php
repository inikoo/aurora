<?php
/*
 File: reports.php

 UI reports index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'common.php';



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'
);


$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	);




$sql="select `Page Parent Category`,`Page Short Title`,`Page URL`,`Page Snapshot Image Key` from `Page Dimension` P  left join `Page Internal Dimension`  I on (P.`Page Key`=I.`Page Key`)  where `Page Type`='Internal' and `Page Section`='Reports' ";

$res=mysql_query($sql);
$current_category='';
$report_index=array();

while ($row=mysql_fetch_array($res)) {
	//if($current_category!=$row['Page Parent Category']){
	switch ($row['Page Parent Category']) {
	case('Sales Reports'):
		$_title=_('Sales');
		break;
	case('Tax Reports'):
		$_title=_('Tax Reports');
		break;
	case('Activity/Performance Reports'):
		$_title=_('Activity/Performance');
		break;
	default:
		$_title=$row['Page Parent Category'];

	}
	if (!isset($report_index[$row['Page Parent Category']]))
		$report_index[$row['Page Parent Category']]=array('title'=>$_title,'reports'=>array());
	//  }
	$report_title=$row['Page Short Title'];

	$report_index[$row['Page Parent Category']]['reports'][$row['Page Short Title']]=array('title'=>$report_title,'url'=>$row['Page URL'],'snapshot'=>'image.php?id='.$row['Page Snapshot Image Key']);
}

$smarty->assign('report_index',$report_index);
$smarty->assign('parent','reports');
$smarty->assign('title', _('Reports'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('reports.tpl');

?>
