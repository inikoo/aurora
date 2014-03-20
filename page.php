<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.Store.php';
include_once 'common_date_functions.php';

include_once 'class.Page.php';
include_once 'class.Site.php';


if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$page_key=$_REQUEST['id'];

} else {
	exit("no page id");
}




if (!($user->can_view('sites')    ) ) {
	header('Location: index.php?can_view_sites');
	exit;
}


include_once 'class.Image.php';
$page=new Page($page_key);


$page->update_preview_snapshot();
//exit;

if (!$page->id) {
	include_once 'class.PageDeleted.php';

	$deleted_page=new PageDeleted('page_key',$page_key);



	if ($deleted_page->id) {



		header('Location: page_deleted.php?id='.$deleted_page->id);
		exit;
	}else {
		header('Location: index.php?page_can_not_be_found');
		exit;
	}
}


$_SESSION['state']['page']['id']=$page->id;

$site=new Site($page->data['Page Site Key']);
$smarty->assign('site',$site);
$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);
$smarty->assign('page',$page);
$create=$user->can_create('sites');
$modify=$user->can_edit('sites');
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('search_label',_('Website'));
$smarty->assign('search_scope','site');

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'theme.css.php'
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
	$yui_path.'calendar/calendar-min.js',

	'js/php.default.min.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/localize_calendar.js',
	'js/calendar_interval.js',
	'js/reports_calendar.js',
	'js/search.js',
	'page.js.php'
);




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




if (isset($_REQUEST['view'])) {
	$valid_views=array('details','hits','visitors');
	if (in_array($_REQUEST['view'], $valid_views))
		$_SESSION['state']['page']['view']=$_REQUEST['view'];

}
$smarty->assign('block_view',$_SESSION['state']['page']['view']);

$subject_id=$page_key;
$smarty->assign('site',$site);

$smarty->assign('parent','websites');
$smarty->assign('title',_('Page').': '.$page->data['Page Code'].' ('.$site->data['Site Code'].')');


$order=$_SESSION['state']['site']['pages']['order'];
if ($order=='code') {
	$order='`Page Code`';
	$order_label=_('Code');
} else if ($order=='url') {
		$order='`Page URL`';
		$order_label=_('URL');
	} else if ($order=='title') {
		$order='`Page Store Title`';
		$order_label=_('Title');
	} else {
	$order='`Page Code`';
	$order_label=_('Code');
}

$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`   where  `Page Site Key`=%d  and %s < %s  order by %s desc  limit 1",
	$site->id,
	$order,
	prepare_mysql($page->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='page.php?id='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`    where  `Page Site Key`=%d  and  %s>%s  order by %s   ",
	$site->id,
	$order,
	prepare_mysql($page->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='page.php?id='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next',$next);
}
mysql_free_result($result);


$smarty->assign('parent_url','site.php?id='.$site->id);
$parent_title=$site->data['Site Name'].' '._('Pages').' ('.$order_label.')';
$smarty->assign('parent_title',$parent_title);

$tipo_filter=$_SESSION['state']['page']['requests']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['page']['requests']['f_value']);
$filter_menu=array(
	'handle'=>array('db_key'=>'handle','menu_label'=>_('Handle starting with  <i>x</i>'),'label'=>_('Handle')),

);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$tipo_filter=$_SESSION['state']['page']['users']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['page']['users']['f_value']);
$filter_menu=array(
	'handle'=>array('db_key'=>'handle','menu_label'=>_('Handle starting with  <i>x</i>'),'label'=>_('Handle')),


);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$flag_key=$page->data['Site Flag Key'];
$flag_list=array();
$sql=sprintf("select * from  `Site Flag Dimension` where `Site Key`=%d and `Site Flag Active`='Yes'",
	$page->data['Page Site Key']);

$result=mysql_query($sql);
$flag_icon='';
$flag_label='';
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$flag_list[strtolower($row['Site Flag Color'])]=array(
		'name'=>$row['Site Flag Label'],
		'key'=>$row['Site Flag Key'],
		'icon'=>"flag_".strtolower($row['Site Flag Color']).".png"
	);


	if ($flag_key==$row['Site Flag Key']) {
		$flag_icon="flag_".strtolower($row['Site Flag Color']).".png";
		$flag_label=$row['Site Flag Label'];
	}
}

$smarty->assign('flag_key',$flag_key);
$smarty->assign('flag_icon',$flag_icon);
$smarty->assign('flag_label',$flag_label);
$smarty->assign('flag_list',$flag_list);




$smarty->assign('requests_elements',$_SESSION['state']['page']['requests']['elements']);
if (isset($_REQUEST['period'])) {
	$period=$_REQUEST['period'];

}else {
	$period=$_SESSION['state']['page']['period'];
}
if (isset($_REQUEST['from'])) {
	$from=$_REQUEST['from'];
}else {
	$from=$_SESSION['state']['page']['from'];
}

if (isset($_REQUEST['to'])) {
	$to=$_REQUEST['to'];
}else {
	$to=$_SESSION['state']['page']['to'];
}

list($period_label,$from,$to)=get_period_data($period,$from,$to);
$_SESSION['state']['page']['period']=$period;
$_SESSION['state']['page']['from']=$from;
$_SESSION['state']['page']['to']=$to;

$smarty->assign('from',$from);
$smarty->assign('to',$to);
$smarty->assign('period',$period);
$smarty->assign('period_label',$period_label);
$to_little_edian=($to==''?'':date("d-m-Y",strtotime($to)));
$from_little_edian=($from==''?'':date("d-m-Y",strtotime($from)));
$smarty->assign('to_little_edian',$to_little_edian);
$smarty->assign('from_little_edian',$from_little_edian);
$smarty->assign('calendar_id','sales');


$smarty->display('page.tpl');

?>
