<?php
/*
  About: 
  Autor: Raul Perusquia <rulovico@gmail.com>
  Copyright (c) 2010, Inikoo 
  Version 2.0
*/

include_once('common.php');
include_once('class.Company.php');

if(!$user->can_view('contacts'))
  exit();

$modify=$user->can_edit('contacts');
$create=$user->can_create('contacts');

if(!$modify or!$create){
  exit();
}

$smarty->assign('scope','supplier');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',

		 'text_editor.css',
		
		 'button.css',
		 'css/container.css'
		 );

if($common)
{
array_push($css_files, 'themes_css/'.$common);   
array_push($css_files, 'themes_css/'.$row['Themes css4']);
array_push($css_files, 'themes_css/'.$row['Themes css2']); 
}    

else{
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/dropdown.css');
array_push($css_files, 'table.css');
}



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
		'js/search.js'	
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

$css_files[]='css/edit.css';
$css_files[]=$yui_path.'autocomplete/assets/skins/sam/autocomplete.css';

$tipo='company';

$js_files[]='new_subject.js.php';
$js_files[]='company.js.php';
$js_files[]='js/validate_telecom.js';
$js_files[]='new_company.js.php?scope=supplier';
$js_files[]='edit_address.js.php';
$js_files[]='edit_contact_from_parent.js.php';
$js_files[]='edit_contact_telecom.js.php';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','suppliers');
$smarty->assign('tipo',$tipo);

$smarty->assign('title','Creating New Supplier');
$smarty->display('new_supplier.tpl');
?>
