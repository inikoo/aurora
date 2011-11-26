<?php
include_once('common.php');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'container.css',
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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/js/common.js',
		'js/js/table_common.js',

		'js/report_outofstock.js.php'
		);



$tipo=$_REQUEST['tipo'];
if($tipo=='f'){

  $from=$_REQUEST['from'];
  $to=$_REQUEST['to'];
   $title=_('Sales Report');
 }elseif($tipo=='m'){
   $year=$_REQUEST['y'];
   $month=$_REQUEST['m'];
   
   $_time=mktime(0, 0, 0,$month ,1 , $year);
   $_time_n=mktime(0, 0, 0,$month+1 ,1 , $year);
   $_time_p=mktime(0, 0, 0,$month-1 ,1 , $year);

   $from=date("Y-m-d", $_time);
   $to=date("Y-m-d", mktime(0, 0, 0, $month+1, 0, $year));
   $period=date("F Y", $_time);
   $title="$period "._('Out od Stock Report');

   $smarty->assign('up',array('url'=>'tipo=y&y='.date("Y",$_time),'title'=>date("Y",$_time)));
   $smarty->assign('next',array('url'=>'tipo=m&m='.date("m",$_time_n).'&y='.date("Y",$_time_n),'title'=>date("F",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=m&m='.date("m",$_time_p).'&y='.date("Y",$_time_p),'title'=>date("F",$_time_p)));

 }elseif($tipo=='y'){
   $year=$_REQUEST['y'];

   
   $_time=mktime(0, 0, 0,1 ,1 , $year);
   $_time_n=mktime(0, 0, 0,1 ,1 , $year+1);
   $_time_p=mktime(0, 0, 0,1 ,1 , $year-1);

   $from=date("Y-m-d", $_time);
   $to=date("Y-m-d", mktime(0, 0, 0, 1, 0, $year+1));
   $period=date("Y", $_time);
   $title="$period "._('Sales Report');

   $smarty->assign('tipo_title',_('Annual Report'));
   $smarty->assign('next',array('url'=>'tipo=y&y='.date("Y",$_time_n),'title'=>date("Y",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=y&y='.date("Y",$_time_p),'title'=>date("Y",$_time_p)));
   $m=array();
   foreach( range(1,12) as $_m){
     
     $m[]=substr(strftime("%b", mktime(0, 0, 0, $_m, 1, 2000)),0,1);
     
   }

    $smarty->assign('m',$m);
  }
  


$_SESSION['state']['report_outofstock']['from']=$from;
$_SESSION['state']['report_outofstock']['to']=$to;




  


$smarty->assign('parent','reports');
$smarty->assign('title', _('Out of Stock Report'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Products marked as out of stock'));

$tipo_filter=$_SESSION['state']['report_outofstock']['table']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['report_outofstock']['table']['f_value']);


$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Product Code Starting with <i>x</i>','label'=>_('Product Code')),
		   'family_code'=>array('db_key'=>'family_code','menu_label'=>'Family Code  starting with <i>x</i>','label'=>_('Family Code')),
		   'picker'=>array('db_key'=>'picker','menu_label'=>'Picker name starting with <i>x</i>','label'=>_('Picker')),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->display('report_outofstock.tpl');
?>