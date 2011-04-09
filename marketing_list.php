<?php
/*
 File: marketing.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Product.php');
include_once('class.Order.php');
$page='marketing';

/* this line(s) is/are added by PrimeDiart Technologies (Kallol Chakraborty) */
$user_key = $_SESSION['user_key']; 
/* changes done up to this */

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
		'js/marketing_list.js',
		'js/search_subscriber.js'		
		);


if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}

/* this line(s) is/are added by PrimeDiart Technologies (Kallol Chakraborty) */

/* CREATE LIST */

if(isset($_POST['save_list'])){ // adding  new list

  $list_name = trim($_POST['list_name']);
  $default_from_name = trim($_POST['default_name']);
  $default_reply_to_email = trim($_POST['default_email']);
  $default_subject = trim($_POST['default_subject']);	
  $permission_reminder_list = trim($_POST['permission_reminder_list']);
  $reminder_text = trim($_POST['description']);
  $people_subscribe = trim($_POST['subscribe']);
  $people_unsubscribe = trim($_POST['unsubscribe']);
  $pick_email_format = trim($_POST['email_format']);
  $activate_social_pro = trim($_POST['social_pro']);


  $sql = "INSERT INTO `Email Campaign Mailing List` (`User Key`, `List Name` ,`Default From Name` ,`Default Reply To Email` ,`Default Subject` ,`Permission Reminder List` ,`Reminder Text` ,`People Subscribe` ,`People Unsubscribe` ,`Pick Email Format` ,`Activate Social Pro`)VALUES ('$user_key', '$list_name', '$default_from_name', '$default_reply_to_email ', '$default_subject ', '$permission_reminder_list', '$reminder_text', '$people_subscribe', '$people_unsubscribe', '$pick_email_format', '$activate_social_pro');";
	
  mysql_query($sql); // new listed added
	
}

/* CREATE GROUP */

if(isset($_POST['save_group'])){ // adding new group

	$group_name_arr = array();

	$list_group = trim($_POST['list_group']);
	$how_show_options = trim($_POST['how_show_options']);
 	$group_title = trim($_POST['group_title']);
  	
	$group_name_arr[0] = trim($_POST['group_name0']);
	$group_name_arr[1] = trim($_POST['group_name1']);
	$group_name_arr[2] = trim($_POST['group_name2']);
	$group_name_arr[3] = trim($_POST['group_name3']);
	$group_name_arr[4] = trim($_POST['group_name4']);
	
	$group_key = time().mt_rand(111, 999);	
	

	$sql_1 = "INSERT INTO `Email Campaign Group Titile` (`Email Campaign Group Key` ,`Email List Key` ,`How Show Options` ,
`Group Title`)VALUES ('$group_key' , '$list_group ', '$how_show_options', '$group_title');";
	
	mysql_query($sql_1);
	
	foreach($group_name_arr as $group_name){
	
	if(trim($group_name) == ''){
		continue;
	}
		
	$sql_2 = "INSERT INTO `Email Campaign Group Titile Name Bridge` (`Email Campaign Group Key` ,
`Group Name`)VALUES ('$group_key', '$group_name');";

	mysql_query($sql_2);
       }
	

	unset($group_name_arr);
}

/* VIEW LIST */

$list_sql=mysql_query("SELECT `Email Campaign Mailing List Key`, `List Name` FROM `Email Campaign Mailing List` WHERE `User Key` LIKE '$user_key'");

$i=0;
$list=array();
while($list_name=mysql_fetch_array($list_sql))
{
	$list[$i]=$list_name;
	$i++;
}

$list_count=mysql_num_rows($list_sql);
$smarty->assign('list',$list);
$smarty->assign('list_count',$list_count);
/* changes done up to this */


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

$smarty->display('marketing_list.tpl');






?>

