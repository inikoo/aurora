<?php
/*
 File: staff.php 

 UI staff page

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
  $_SESSION['state']['staff']['id']=$_REQUEST['id'];
  $staff_id=$_REQUEST['id'];


}else{
  $staff_id=$_SESSION['state']['staff']['id'];
}


$staff=new Staff($staff_id);

if(!$staff->id){
 header('Location: hr.php?error='._('Staff not exists'));
  exit();

}

$_SESSION['state']['staff']['id']=$staff_id;
//$_SESSION['state']['staff']['store']=$customer->data['Customer Store Key'];


/*if(isset($_REQUEST['view']) and preg_match('/^(history|products|orders)$/',$_REQUEST['view']) ){
  $_SESSION['state']['staff']['view']=$_REQUEST['view'];
  $view=$_REQUEST['view'];
}else{
  $view=$_SESSION['state']['staff']['view'];
}
$smarty->assign('view',$view);
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
		'staff.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




//$customer->load('contacts');
$smarty->assign('staff',$staff);


  $smarty->assign('search_label',_('Staff'));
$smarty->assign('search_scope','staff');

  
  $order=$_SESSION['state']['hr']['staff']['order'];
  if($order=='name')
    $order='`Staff Name`';
 elseif($order=='id')
    $order='`Staff Key`';
 /*  elseif($order=='location')
     $order='`Customer Main Location`';
   elseif($order=='orders')
     $order='`Customer Orders`';
   elseif($order=='email')
     $order='`Customer Email`';
   elseif($order=='telephone')
     $order='`Customer Main Telehone`';
   elseif($order=='last_order')
     $order='`Customer Last Order Date`';
   elseif($order=='contact_name')
     $order='`Customer Main Contact Name`';
   elseif($order=='address')
     $order='`Customer Main Location`';
   elseif($order=='town')
     $order='`Customer Main Town`';
   elseif($order=='postcode')
     $order='`Customer Main Postal Code`';
   elseif($order=='region')
     $order='`Customer Main Country First Division`';
   elseif($order=='country')
     $order='`Customer Main Country`';
   //  elseif($order=='ship_address')
   //  $order='`customer main ship to header`';
   elseif($order=='ship_town')
     $order='`Customer Main Delivery Address Town`';
   elseif($order=='ship_postcode')
     $order='`Customer Main Delivery Address Postal Code`';
   elseif($order=='ship_region')
     $order='`Customer Main Delivery Address Country Region`';
   elseif($order=='ship_country')
     $order='`Customer Main Delivery Address Country`';
   elseif($order=='net_balance')
     $order='`Customer Net Balance`';
   elseif($order=='balance')
     $order='`Customer Outstanding Net Balance`';
   elseif($order=='total_profit')
     $order='`Customer Profit`';
   elseif($order=='total_payments')
     $order='`Customer Total Payments`';
   elseif($order=='top_profits')
     $order='`Customer Profits Top Percentage`';
   elseif($order=='top_balance')
     $order='`Customer Balance Top Percentage`';
   elseif($order=='top_orders')
     $order='``Customer Orders Top Percentage`';
   elseif($order=='top_invoices')
     $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
     $order='`Customer Total Refunds`';*/
else
   $order='`Staff Key`';

   $_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Staff Key` as id , `Staff Name` as name from `Staff Dimension`   where  %s < %s  order by %s desc  limit 1",$order,prepare_mysql($staff->get($_order)),$order);
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$sql=sprintf("select `Staff Key` as id , `Staff Name` as name from `Staff Dimension`     where  %s>%s  order by %s   ",$order,prepare_mysql($staff->get($_order)),$order);

$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



//$show_details=$_SESSION['state']['staff']['details'];
//$smarty->assign('show_details',$show_details);






$smarty->assign('parent','hr');
$smarty->assign('title','Staff: '.$staff->get('Staff Name'));
$staff_home=_("Staff List");
//$smarty->assign('id',$myconf['staff_id_prefix'].sprintf("%05d",$staff->id));







$tipo_filter=$_SESSION['state']['staff']['history']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['staff']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
                 'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
                 'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

             );
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu0',$filter_menu);





$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['hr']['staff']['f_field'];
$filter_value=$_SESSION['state']['hr']['staff']['f_value'];

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
//$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Code like','label'=>_('Code')),
		   );
//$tipo_filter=$_SESSION['state']['staff']['assets']['f_field'];
//$filter_value=$_SESSION['state']['staff']['assets']['f_value'];

$smarty->assign('filter_value1',$filter_value);
$smarty->assign('filter_menu1',$filter_menu);
//$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->display('staff.tpl');

?>
