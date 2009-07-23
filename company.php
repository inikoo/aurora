<?
/*
 File: company.php 

 UI company page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
include_once('classes/Company.php');
if(!$LU->checkRight(CUST_VIEW))
  exit;

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit']){
  $edit=true;
  $_REQUEST['id']=$_REQUEST['edit'];
 }



if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['company']['id']=$_REQUEST['id'];
  $company_id=$_REQUEST['id'];
}else{
  $company_id=$_SESSION['state']['company']['id'];
}



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'assets/skins/sam/container.css',
		 $yui_path.'assets/skins/sam/menu.css',
		 $yui_path.'assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/editor.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',


		 'text_editor.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
		'js/company.js.php'
		);

if($edit ){

  $sql=sprintf("select * from `Salutation Dimension` S left join `Language Dimension` L on S.`Language Key`=L.`Language Key` where `Language Code`=%s limit 1000",prepare_mysql($myconf['lang']));
  $result=mysql_query($sql);
  $salutations=array();
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
  }
  

  $smarty->assign('prefix',$salutations);


  $editing_block='details';

  $smarty->assign('edit',$editing_block);
  $js_files[]='js/edit_address.js.php';
  $js_files[]='js/edit_company.js.php?edit='.$editing_block;
  

}

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$company=new company($company_id);
$address=new address($company->data['Company Main Address Key']);
$smarty->assign('company',$company);
$smarty->assign('address',$address);

$order=$_SESSION['state']['companies']['table']['order'];

if($order=='name')
  $order='Company File As';
elseif($order=='id')
$order='company id';
elseif($order=='location')
$order='Company Main Location';
elseif($order=='email')
  $order='Company Main Plain Email';
elseif($order=='telephone')
$order='Company Main Plain Telehone';
elseif($order=='address')
$order='Company Main Plain Address';
/* elseif($order=='town') */
/* $order='company main address town'; */
/* elseif($order=='postcode') */
/* $order='company main address postal code'; */
/* elseif($order=='region') */
/* $order='company main address country region'; */
/* elseif($order=='country') */
/* $order='company main address country'; */
/* elseif($order=='ship_address') */



$sql=sprintf("select `Company Name` as name from `Company Dimension`   where  `%s` < %s  order by `%s` desc  limit 1",$order,prepare_mysql($company->get($order)),$order);
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');
$smarty->assign('prev',$prev);
$sql=sprintf("select  `Company Name` as name from `Company Dimension`     where  `%s`>%s  order by `%s`   ",$order,prepare_mysql($company->get($order)),$order);
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');
$smarty->assign('prev',$prev);
$smarty->assign('next',$next);




if($edit){

  $smarty->display('edit_company.tpl');
}else{

$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','customers.php');
$smarty->assign('title','Company: '.$company->data['Company Name']);


$smarty->assign('id',$myconf['company_id_prefix'].sprintf("%05d",$company->id));
$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['company']['table']['f_field'];
$filter_value=$_SESSION['state']['company']['table']['f_value'];
$smarty->assign('filter_value',$filter_value);
$smarty->assign('filter_menu',$filter_menu);
$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);
$smarty->display('company.tpl');
}

?>