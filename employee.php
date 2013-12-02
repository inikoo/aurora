<?php
/*

 UI employee page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Staff.php');
if(!$user->can_view('staff')){
header('Location: index.php');
   exit;
 }

$modify=$user->can_edit('contacts');


if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $employee_key=$_REQUEST['id'];


}

$employee=new Staff($employee_key);

if(!$employee->id){
 header('Location: hr.php?error='._('Employee not exists'));
  exit();

}


if(isset($_REQUEST['block']) and preg_match('/^(history|details|working_hours)$/',$_REQUEST['block']) ){
  $_SESSION['state']['employee']['block']=$_REQUEST['block'];
  $block_view=$_REQUEST['block'];
}else{
  $block_view=$_SESSION['state']['employee']['block'];
}
$smarty->assign('block_view',$block_view);



$css_files=array(
	   $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
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
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'employee.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




//$customer->load('contacts');
$smarty->assign('employee',$employee);


  $smarty->assign('search_label',_('Staff'));
$smarty->assign('search_scope','staff');

  
  $order=$_SESSION['state']['hr']['employees']['order'];
  if($order=='name')
    $order='`Staff Name`';
 elseif($order=='id')
    $order='`Staff Key`';

else
   $order='`Staff Key`';

   $_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Staff Key` as id , `Staff Name` as name from `Staff Dimension`   where  %s < %s  order by %s desc  limit 1",$order,prepare_mysql($employee->get($_order)),$order);
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$sql=sprintf("select `Staff Key` as id , `Staff Name` as name from `Staff Dimension`     where  %s>%s  order by %s   ",$order,prepare_mysql($employee->get($_order)),$order);

$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



$smarty->assign('parent','staff');
$smarty->assign('title','Employee: '.$employee->get('Staff Name'));

$tipo_filter=$_SESSION['state']['employee']['history']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['employee']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
                 'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
                 'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

             );
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu0',$filter_menu);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);





$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
		   'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );


$tipo_filter=$_SESSION['state']['employee']['working_hours']['f_field'];
$filter_value=$_SESSION['state']['employee']['working_hours']['f_value'];

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
//$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$filter_menu=array(
		   'hours_worked'=>array('db_key'=>'hours_worked','menu_label'=>_('Hours'),'label'=>_('Hours Worked')),
		   );
//$tipo_filter=$_SESSION['state']['staff']['assets']['f_field'];
//$filter_value=$_SESSION['state']['staff']['assets']['f_value'];
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$smarty->assign('filter_value1',$filter_value);
$smarty->assign('filter_menu1',$filter_menu);
//$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->display('employee.tpl');

?>
