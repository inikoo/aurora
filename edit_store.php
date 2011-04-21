<?php
/*
 File: store.php 

 UI store page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2010, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
include_once('assets_header_functions.php');

$page='store';
$smarty->assign('page',$page);
if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $store_id=$_REQUEST['id'];

}else{
  $store_id=$_SESSION['state'][$page]['id'];
}


if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php');
   exit;
}
if(!$user->can_edit('stores') ){
  header('Location: store.php?error=cannot_edit');
   exit;
}


$store=new Store($store_id);
$_SESSION['state'][$page]['id']=$store->id;

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');





$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);



get_header_info($user,$smarty);

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'store.php?id='.$store_id,'label'=>_('Exit Edit'));

$smarty->assign('general_options_list',$general_options_list);




$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //	 $yui_path.'assets/skins/sam/autocomplete.css',
		
		 'container.css',
		 'button.css'
		 );

include_once('Theme.php');

$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		
		'js/dropdown.js'
		);



  $smarty->assign('edit',$_SESSION['state'][$page]['edit']);
  $css_files[]='css/edit.css';
 
  $js_files[]='js/edit_common.js';
  $js_files[]='country_select.js.php';
  $js_files[]='edit_store.js.php';
 

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);







$subject_id=$store_id;


$smarty->assign($page,$store);

$smarty->assign('parent','products');
$smarty->assign('title', $store->data['Store Name']);


$stores=array();
$sql=sprintf("select * from `Store Dimension` CD order by `Store Key`");

$res=mysql_query($sql);
 $first=true;
while($row=mysql_fetch_array($res)){
    $stores[$row['Store Key']]=array('code'=>$row['Store Code'],'selected'=>0);
    if($first){
      $stores[$row['Store Key']]['selected']=1;
      $first=FALSE;
    }
}
mysql_free_result($res);





 $smarty->assign('stores',$stores);
 
 $q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['history']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['store']['history']['f_value']:addslashes($q)));
$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
		   'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))
            
		   );
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
 
$smarty->display('edit_store.tpl');

?>
