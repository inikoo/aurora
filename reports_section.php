<?php
/*
 File: reports.php 

 UI reports index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('map_url.php');

$report_category='';
if(isset($_REQUEST['category'])){
$report_category=$_REQUEST['category'];
}


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'theme.css.php'
           );


$js_files=array(





		$yui_path.'utilities/utilities.js',
		$yui_path.'calendar/calendar-min.js',
		$yui_path.'json/json-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'calendar/calendar-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',


		'js/common.js',
		'reports_section.js.php',
			'js/dropdown.js'
		);


$plot_title=array(
		  'total_sales_groupby_month'=>_('Net sales grouped by month')."."
		  ,'total_sales_month'=>_('Monthly net sales')."."

);



$sql=sprintf("select `Page Parent Category`,`Page Short Title`,`Page URL`,`Image Key` from `Page Dimension` P  left join `Page Internal Dimension`  I on (P.`Page Key`=I.`Page Key`) left join `Image Dimension` IM on (IM.`Image Key`=`Page Snapshot Image Key`) where `Page Type`='Internal' and `Page Section`='Reports' and `Page Parent Category`=%s",
prepare_mysql($report_category)
);

$res=mysql_query($sql);
$current_category='';
$report_index=array();
while($row=mysql_fetch_array($res)){
  if($current_category!=$row['Page Parent Category']){
    switch($row['Page Parent Category']){
    case('Sales Reports'):
      $title=_('Sales');
      break;
    case('Activity/Performace Reports'):
      $title=_('Activity/Performace');
      break;
    default:
      $title=$row['Page Parent Category'];

    }
    if(!isset($report_index[$row['Page Parent Category']]))
    $report_index[$row['Page Parent Category']]=array('title'=>$title,'reports'=>array());
  }
  $title=$row['Page Short Title'];
  
  $report_index[$row['Page Parent Category']]['reports'][$title]=array('title'=>$title,'url'=>$row['Page URL'],'snapshot'=>'image.php?id='.$row['Image Key']);
    

}
$smarty->assign('report_index',$report_index);






$smarty->assign('parent','reports');
$smarty->assign('title', _('Reports'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('reports_section.tpl');

?>

