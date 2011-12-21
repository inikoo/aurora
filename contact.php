<?php
/*
 File: contact.php 

 UI contact page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Contact.php');
if(!$user->can_view('contacts'))
  exit();

$modify=$user->can_edit('contacts');




$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit']){
  $edit=true;
  $_REQUEST['id']=$_REQUEST['edit'];
 }



if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['contact']['id']=$_REQUEST['id'];
  $contact_id=$_REQUEST['id'];
}else{
  $contact_id=$_SESSION['state']['contact']['id'];
}
if(!$modify)
  $edit=false;


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'assets/skins/sam/container.css',
		 $yui_path.'assets/skins/sam/menu.css',
		 $yui_path.'assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/editor.css',

		 'text_editor.css',
		 
		 'button.css',
		 'css/container.css'
		 );


$css_files[]='theme.css.php';
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
		'contact.js.php'
		);

if($edit ){

  $sql=sprintf("select * from kbase.`Salutation Dimension` S left join kbase.`Language Dimension` L on S.`Language Key`=L.`Language Key` where `Language Code`=%s limit 1000",prepare_mysql($myconf['lang']));
  $result=mysql_query($sql);
  $salutations=array();
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
  }
  

  $smarty->assign('prefix',$salutations);


  $editing_block='work';

  $smarty->assign('edit',$editing_block);

  $js_files[]='edit_contact.js.php?edit='.$editing_block;
  

}

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$contact=new contact($contact_id);


$smarty->assign('contact',$contact);

$order=$_SESSION['state']['contacts']['table']['order'];

if($order=='name')
  $order='Contact File As';
elseif($order=='id')
$order='Contact Key';
elseif($order=='location')
$order='Contact Main Location';
elseif($order=='email')
  $order='Contact Main Plain Email';
elseif($order=='telephone')
$order='Contact Main Plain Telehone';
elseif($order=='address')
$order='Contact Main Plain Address';
elseif($order=='id')
$order='Contact Key';



/* elseif($order=='town') */
/* $order='contact main address town'; */
/* elseif($order=='postcode') */
/* $order='contact main address postal code'; */
/* elseif($order=='region') */
/* $order='contact main address country region'; */
/* elseif($order=='country') */
/* $order='contact main address country'; */
/* elseif($order=='ship_address') */



$sql=sprintf("select `Contact Name` as name from `Contact Dimension`   where  `%s` < %s  order by `%s` desc  limit 1",$order,prepare_mysql($contact->get($order)),$order);
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');
$smarty->assign('prev',$prev);
$sql=sprintf("select  `Contact Name` as name from `Contact Dimension`     where  `%s`>%s  order by `%s`   ",$order,prepare_mysql($contact->get($order)),$order);
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');
$smarty->assign('prev',$prev);
$smarty->assign('next',$next);




if($edit){

  $smarty->display('edit_contact.tpl');
}else{

$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','contacts');
$smarty->assign('title','Contact: '.$contact->data['Contact Name']);


$smarty->assign('id',$myconf['contact_id_prefix'].sprintf("%05d",$contact->id));
$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['contact']['table']['f_field'];
$filter_value=$_SESSION['state']['contact']['table']['f_value'];
$smarty->assign('filter_value',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->display('contact.tpl');
}

?>
