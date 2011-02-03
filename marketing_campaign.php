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


//$page='marketing';

///$general_options_list=array();
//$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));

//$general_options_list[]=array('tipo'=>'url','url'=>'new_email_campaign.php','label'=>_('Create Email Campaign'));
//$general_options_list[]=array('tipo'=>'url','url'=>'newsletter.php?new','label'=>_('Create Newsletter'));
//$smarty->assign('general_options_list',$general_options_list);

//$view_orders=$user->can_view('Orders');



$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/marketing_menu.css',
		 'css/marketing_campaigns.css'
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
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'marketing.js.php',
		'js/menu.js',
		'js/jquery-1.4.4.js',
		'js/marketing_ajax.js',
		'js/edit_delete.js',
		'js/jquery.jeditable.js'
		);

$dbvalue = array();
$create = array();

//extract the id 
$http = $_REQUEST['t'];
$extract = explode('_',$http);

//delete a folder
$request = $_REQUEST['del'];
$r = explode('_',$request);


if($extract[1] > 0)
{
	
	$queryString = "update `Mail Folder` set `Mail Folder Name` = '".$_REQUEST['n']."' where `Mail Folder Key` = '".$extract[1]."'";
	mysql_query($queryString);
	
}

if($r[1] > 0)
{
	
	$sqlDelete = "delete from `Mail Folder` where `Mail Folder Key` = '".$r[1]."'";
	mysql_query($sqlDelete);
	
}

  $sql = sprintf("select `Email Campaign Key`,`Email Campaign Status`,`Email Campaign Maximum Emails`,`Email Campaign Content` from `Email Campaign Dimension`");
		$res = mysql_query($sql);
	
	

	$smarty->assign('status','Email Campaign Status');
	$smarty->assign('email','Email Campaign Maximum Emails');
	$smarty->assign('key','Email Campaign Key');
	$smarty->assign('content','Email Campaign Content');
	if(mysql_num_rows($res) > 0)
	{
		while($fetchArray = mysql_fetch_assoc($res))
		{
		
		$dbvalue[] = $fetchArray;

		}
	}
//change email as per login credentials
$mail = 'carlos@aw-regalos.com';	


$folder_name = 'Mail Folder Name';
$edit_id = 'Mail Folder Key';	
$sqlString = sprintf("select `Mail Folder Name`,`Mail Folder Key` from `Mail Folder` where `Mail Folder Email`='".$mail."'");
$result = mysql_query($sqlString);
if(mysql_num_rows($result) > 0)
{
	while($ss=mysql_fetch_assoc($result))
	{

 	$create[] = $ss;
	}

	
} 

if (isset($_REQUEST['view'])) {
    $valid_views=array('metrics','email','web_internal','web','other','newsletter');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}
//$smarty->assign('view',$_SESSION['state'][$page]['view']);



$smarty->assign('parent','home');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('create',$create);
$smarty->assign('value',$dbvalue);
$smarty->assign('folder_name',$folder_name);
$smarty->assign('edit_id',$edit_id);

$smarty->display('marketing_campaign.tpl');

?>
