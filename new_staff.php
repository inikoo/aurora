<?php
/*
 
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.1
*/

include_once('common.php');
include_once('class.Contact.php');


if(!$user->can_view('staff')){
  header('Location: index.php');
  exit();
}
if(!$user->can_edit('staff')){
  header('Location: hr.php');
  exit();

}

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/edit.css',
	'css/edit_address.css',
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
		'js/common.js',
		'js/table_common.js',
		'js/edit_common.js',
		'js/validate_telecom.js',
		'edit_address.js.php',
		'edit_contact_from_parent.js.php',
		'edit_contact_telecom.js.php',
		'edit_contact_name.js.php',
		'edit_contact_email.js.php','new_staff.js.php?scope=staff',
		//'new_contact.js.php?scope=staff'
		);

$sql=sprintf("select * from `Company Position Dimension`");
$result=mysql_query($sql);
while($row=mysql_fetch_assoc($result)){
	$staff_position[$row['Company Position Key']]=$row['Company Position Title'];
}

$smarty->assign('staff_position',$staff_position);

$sql=sprintf("select * from `Company Department Dimension`");
$result=mysql_query($sql);
while($row=mysql_fetch_assoc($result)){
	$staff_department[$row['Company Department Key']]=$row['Company Department Name'];
}

$smarty->assign('staff_department',$staff_department);

$sql=sprintf("select * from `Company Area Dimension`");
$result=mysql_query($sql);
while($row=mysql_fetch_assoc($result)){
	$staff_area[$row['Company Area Key']]=$row['Company Area Name'];
}

$smarty->assign('staff_area',$staff_area);


if(isset($_REQUEST['ref']) and $_REQUEST['ref']=='hr'){
$link_back='hr.php';
}else{
$link_back='edit_hr.php';
}
$smarty->assign('link_back',$link_back);

$smarty->assign('scope','staff');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','staff');
$smarty->assign('title','New Staff');
$smarty->display('new_staff.tpl');


?>
