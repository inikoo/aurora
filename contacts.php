<?php
include_once('common.php');
$_SESSION['views']['assets']='index';

$_SESSION['new_contact']=array();




$smarty->assign('box_layout','yui-t4');


$css_files=array(
	
		  $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
		'js/contacts.js.php'
		);






/* $sql="select `Country Key` id from `Country Dimension` order by `Country Code`"; */
/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/*   $country[$row['id']]=$_country[$row['id']]; */
/*  } */




//$smarty->assign('default_country',$default_country);
//$smarty->assign('default_country_encoded',urlencode($default_country));


//$smarty->assign('default_country_id',$default_country_id);
$smarty->assign('email_tipo',$_tipo_email);
$smarty->assign('address_tipo',$_tipo_address);
$smarty->assign('tel_tipo',$_tipo_tel);
$smarty->assign('prefix',$_prefix);

//$smarty->assign('country',$country);

$smarty->assign('parent','customers.php');
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
  $smarty->assign('filter_menu',$filter_menu);
  
  $smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
  $paginator_menu=array(10,25,50,100,500);
  $smarty->assign('paginator_menu',$paginator_menu);

 $smarty->assign('view',$_SESSION['state']['contacts']['view']);

$smarty->display('contacts.tpl');



?>