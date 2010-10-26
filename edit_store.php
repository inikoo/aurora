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


$stores_order=$_SESSION['state']['stores']['table']['order'];
$stores_period=$_SESSION['state']['stores']['period'];
$stores_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));

$smarty->assign('stores_period',$stores_period);
$smarty->assign('stores_period_title',$stores_period_title[$stores_period]);

$show_details=$_SESSION['state'][$page]['details'];
$smarty->assign('show_details',$show_details);
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
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );
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
		'common.js.php',
		'table_common.js.php',
		
		'js/dropdown.js'
		);



  $smarty->assign('edit',$_SESSION['state'][$page]['edit']);
  $css_files[]='css/edit.css';
 
  $js_files[]='js/edit_common.js';
  $js_files[]='country_select.js.php';
  $js_files[]='edit_store.js.php';
 

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']=$page;
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state'][$page]['view']=$_REQUEST['view'];

 }
$smarty->assign('view',$_SESSION['state'][$page]['view']);


$smarty->assign('show_percentages',$_SESSION['state'][$page]['percentages']);
$smarty->assign('avg',$_SESSION['state'][$page]['avg']);
$smarty->assign('period',$_SESSION['state'][$page]['period']);
$info_period_menu=array(
			array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
		     ,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
		     ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
		     ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
		     ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
		     );
$smarty->assign('info_period_menu',$info_period_menu);


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
$smarty->display('edit_store.tpl');

?>