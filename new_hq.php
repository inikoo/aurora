<?php
/*
  File: company.php 

  UI company page

  About: 
  Autor: Raul Perusquia <rulovico@gmail.com>
 
  Copyright (c) 2009, Inikoo 
 
  Version 2.0
*/

include_once('common.php');
include_once('class.Company.php');
include_once('class.HQ.php');

/*if(!$user->can_view('contacts'))
  exit();

$modify=$user->can_edit('contacts');
$create=$user->can_create('contacts');

if(!$modify or!$create){
  exit();
}*/



// ----------------------------------checking for empty HQ table start----------------------------------------------------
/*$sql="select count(*) from `HQ Dimension`";
$query=mysql_query($query);
if(mysql_num_rows($query)==0)
header("Location:new_hq.php"); */
// ----------------------------------checking for empty HQ table end----------------------------------------------------

$store_key=$_SESSION['state']['customers']['store'];

$store=new Store($store_key);
$smarty->assign('store',$store);



$smarty->assign('store_key',$store_key);
$smarty->assign('scope','customer');


$general_options_list=array();


$general_options_list[]=array('tipo'=>'url','url'=>'customers.php','label'=>_('Go Back'));

$smarty->assign('general_options_list',$general_options_list);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'text_editor.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/edit.css'
		 );
$css_files[]='theme.css.php';
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/phpjs.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
	    'new_subject.js.php'
		);


 $sql=sprintf("select * from kbase.`Salutation Dimension` S left join kbase.`Language Dimension` L on S.`Language Code`=L.`Language ISO 639-1 Code` where `Language ISO 639-1 Code`=%s limit 1000",prepare_mysql($myconf['lang']));
//print $sql;

$result=mysql_query($sql);
$salutations=array();
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
}
mysql_free_result($result);



$smarty->assign('prefix',$salutations);
$editing_block='details';
$smarty->assign('edit',$editing_block);



$tipo='Company';
if(isset($_REQUEST['tipo']) and $_REQUEST['tipo']=='person'){
  $tipo='Person';
}
$smarty->assign('customer_type',$tipo);



//if($tipo=='company'){
$js_files[]='company.js.php';
$js_files[]='js/validate_telecom.js';
//$js_files[]='new_company.js.php?scope=customer&store_key='.$store_key;
$js_files[]='new_hq.js.php?&store_key='.$store_key;

$js_files[]='edit_address.js.php';
$js_files[]='edit_contact_from_parent.js.php';
$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';
/*
}else{
$js_files[]='contact.js.php';
$js_files[]='js/validate_telecom.js';
$js_files[]='new_contact.js.php?scope=customer&store_key='.$store_key;
$js_files[]='new_contact.js.php?scope=customer&store_key='.$store_key;

$js_files[]='edit_address.js.php';
$js_files[]='edit_contact_from_parent.js.php';
$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';


}
*/
/**
$categories=array();
$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d",$store_key);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$tmp=new Category($row['Category Key']);



$categories[$row['Category Key']]=$tmp;

}
$smarty->assign('categories',$categories);
**/




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','customers');

$smarty->assign('title','Creating New HQ');
$smarty->display('new_hq.tpl');




?>
