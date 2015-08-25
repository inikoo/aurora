<?php
include_once 'common.php';
include_once 'class.Store.php';

if (!$user->can_view('customers') or count($user->stores)==0 ) {

	header('Location: index.php');
	exit;
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
	$store_key=$_REQUEST['store'];

} else {
	$store_key=$_SESSION['state']['customers']['store'];

}

if (!($user->can_view('stores') and in_array($store_key,$user->stores)   ) ) {

	header('Location: index.php');
	exit;
}

$store=new Store($store_key);

$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
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
	'js/jquery.min.js',
'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/search.js',
	'customers_lists.js.php'
);


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$tipo_filter=$_SESSION['state']['customers']['list']['f_field'];
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['list']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('List name like <i>x</i>'),'label'=>_('Name'))
);

$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter=$_SESSION['state']['customers']['imported_records']['f_field'];
$smarty->assign('filter_name1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['customers']['imported_records']['f_value']);
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('List name like <i>x</i>'),'label'=>_('Name'))
);

$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$block_view=$_SESSION['state']['customers']['list']['block_view'];
$smarty->assign('block_view',$block_view);

//--------------- Content spa

$branch=array(array('label'=>'','icon'=>'home','url'=>'index.php'));
if ( $user->get_number_stores()>1) {
	$branch[]=array('label'=>_('Customers'),'icon'=>'bars','url'=>'customers_server.php');
}
	$branch[]=array('label'=>_('Customers').' '.$store->data['Store Code'],'icon'=>'users','url'=>'customers.php?store='.$store->id);


$left_buttons=array();
if ($user->stores>1) {

	
	

	list($prev_key,$next_key)=get_prev_next($store->id,$user->stores);
	
	$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$prev_key);
	$res=mysql_query($sql);
	if($row=mysql_fetch_assoc($res)){
	    $prev_title=_("Customer's Lists").' '.$row['Store Code'];
	}else{$prev_title='';}
	$sql=sprintf("select `Store Code` from `Store Dimension` where `Store Key`=%d",$next_key);
	$res=mysql_query($sql);
	if($row=mysql_fetch_assoc($res)){
	    $next_title=_("Customer's Lists").' '.$row['Store Code'];
	}else{$next_title='';}
	

	$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'url'=>'customers_lists.php?store='.$prev_key);
	$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'url'=>'customers.php?store='.$store->id);

	$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'url'=>'customers_lists.php?store='.$next_key);
}


$right_buttons=array();

$right_buttons[]=array('icon'=>'plus','title'=>_('New list'),'url'=>"new_customers_list.php?store=".$store->id);

$_content=array(
	'branch'=>$branch,
	'sections_class'=>'only_icons',
	'sections'=>get_sections('customers',$store->id),
	'left_buttons'=>$left_buttons,
	'right_buttons'=>$right_buttons,
	'title'=>_("Customer's Lists").' <span class="id">'.$store->get('Store Code').'<span>',
	'search'=>array('show'=>true,'placeholder'=>_('Search customers'))

);
$smarty->assign('content',$_content);

$smarty->display('customers_lists.tpl');
?>
