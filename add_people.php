<?php
/*
 File: marketing.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

include_once('common.php');

include_once('class.Product.php');
include_once('class.Order.php');


$page='marketing';
$user_key = $_SESSION['user_key'];
$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));

$general_options_list[]=array('tipo'=>'url','url'=>'new_email_campaign.php','label'=>_('Create Email Campaign'));
$general_options_list[]=array('tipo'=>'url','url'=>'newsletter.php?new','label'=>_('Create Newsletter'));
$smarty->assign('general_options_list',$general_options_list);

$view_orders=$user->can_view('Orders');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		'css/create_list.css',
		'css/marketing_menu.css',
		 'css/marketing_campaigns.css'
	);
$js_files=array(
		'external_libs/jquery/jquery-1.3.2.min.js',
		'js/jquery.js',
		'js/jquery-1.4.4.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/add_people.js',
		
		
		);


 
if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}

// adding  new people to a list
if(!isset($_GET['l'])){

	header('Location:marketing.php');

}else{




$current_list_id = trim($_GET['l']);
$qry1=mysql_query("SELECT `List Name` FROM `Email Campaign Mailing List` WHERE  `Email Campaign Mailing List Key` LIKE '$current_list_id' AND `User Key` LIKE '$user_key'"); 
if(mysql_num_rows($qry1) == 0){
	header('Location:marketing.php');
	
}
$r1 = mysql_fetch_assoc($qry1);
$current_list = $r1['List Name'];
$smarty->assign('current_list', $current_list);


$qry2=mysql_query("SELECT `Email Campaign Group Key` , `Group Title` FROM `Email Campaign Group Titile` WHERE `Email List Key` LIKE '$current_list_id'"); 
$r2 = mysql_fetch_assoc($qry2);
$group_title = $r2['Group Title'];
$group_key = $r2['Email Campaign Group Key'];
$smarty->assign('group_title', $group_title);

$qry3=mysql_query("SELECT `Email Campaign Group Name Key`, `Group Name` FROM `Email Campaign Group Titile Name Bridge` WHERE `Email Campaign Group Key` LIKE '$group_key'"); 
$k=0;
$group=array();
while($group_name=mysql_fetch_array($qry3))
{
	$group[$k]=$group_name;
	
	$k++;
}

$smarty->assign('group',$group);


}


if(isset($_POST['add_people'])){ 

$list_name = $_POST['list_name'];
$people_email = trim($_POST['people_email']);
$people_first_name = trim($_POST['people_first_name']);
$people_last_name = trim($_POST['people_last_name']);
$people_email_type = trim($_POST['people_email_type']);

//print_r($_POST['group_name']);
if(isset($_POST['group_name'])){
	$group = implode(',', $_POST['group_name']);

}else{

	$group = '';
}



 $sql = "INSERT INTO `Email People Dimension` (`People List Key` ,`People Group Key` ,`People Email` ,`People First Name` ,`People Last Name` ,`People Email Type`)VALUES('$list_name', '$group', '$people_email', '$people_first_name', '$people_last_name', '$people_email_type');";
	
 mysql_query($sql);


}

$list_sql=mysql_query("SELECT `Email Campaign Mailing List Key`, `List Name` FROM `Email Campaign Mailing List` WHERE `User Key` LIKE '$user_key'");
$i=0;
$list=array();
while($list_name=mysql_fetch_array($list_sql))
{
	//echo"$list_name['$listname]<br>";
	$list[$i]=$list_name;
	
	$i++;
}

$smarty->assign('list',$list);

$smarty->assign('view',$_SESSION['state'][$page]['view']);


$smarty->assign('parent','home');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$q='';
$tipo_filter=($q==''?$_SESSION['state'][$page]['email_campaigns']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state'][$page]['email_campaigns']['f_value']:addslashes($q)));
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>'Campaign with name like <i>x</i>','label'=>'Name')
             );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('add_people.tpl');








?>

