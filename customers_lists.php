<?php
include_once('common.php');

if(!$user->can_view('customers') or count($user->stores)==0 ){
header('Location: index.php');
   exit;
 }


		 	 
		 $css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'button.css',
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
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
	        'customers_lists.js.php'
		);

$store_options=array();
$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where `Store Key` in (%s)",join(',',$user->stores));
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$store_options[$row['Store Key']]=array('code'=>$row['Store Code']);
}
if(count($user->stores)==1){
$smarty->assign('direct_store_key',array_pop($user->stores));

}

$smarty->assign('store_options',$store_options);
		


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



   $general_options_list[]=array('tipo'=>'js','id'=>'new_customer_list','label'=>_('New Customer List'));
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->display('customers_lists.tpl');
?>
