<?php
include_once('common.php');

if(!$user->can_view('contacts'))
  exit();



$_SESSION['views']['assets']='index';

$_SESSION['new_contact']=array();




$smarty->assign('box_layout','yui-t4');


$css_files=array(
	
		  $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 
		 'container.css'
		 );

if($common)
{
array_push($css_files, 'themes_css/'.$common);   
array_push($css_files, 'themes_css/'.$row['Themes css4']);
array_push($css_files, 'themes_css/'.$row['Themes css2']); 
array_push($css_files, 'themes_css/'.$row['Themes css3']);
}    

else{
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/dropdown.css'); 
array_push($css_files, 'css/index.css');
array_push($css_files, 'table.css');
}


$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'contacts.js.php'
		);




$smarty->assign('parent','customers');
$smarty->assign('title', _('Contacts'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);





  $q='';
  $tipo_filter=($q==''?$_SESSION['state']['contacts']['table']['f_field']:'name');
  $smarty->assign('filter',$tipo_filter);
  $smarty->assign('filter_value',($q==''?$_SESSION['state']['contacts']['table']['f_value']:addslashes($q)));
  $filter_menu=array(
		   'contact name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Name')
		     );
  $smarty->assign('filter_menu0',$filter_menu);
  
  $smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
  $paginator_menu=array(10,25,50,100,500);
  $smarty->assign('paginator_menu0',$paginator_menu);

 $smarty->assign('view',$_SESSION['state']['contacts']['view']);

$smarty->display('contacts.tpl');



?>
