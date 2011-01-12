<?php
/*
 File: email_campaign.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2011, Kaktus 
 
 Version 2.0
*/

include_once('common.php');

include_once('class.Store.php');

include_once('class.EmailCampaign.php');

$page='email_campaign';
$smarty->assign('page',$page);
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $email_campaign_id=$_REQUEST['id'];

} else {
    $email_campaign_id=$_SESSION['state'][$page]['id'];
}

if (isset($_REQUEST['edit'])) {
    header('Location: edit_email_campaign.php?id='.$email_campaign_id);

    exit("E2");
}

$email_campaign=new EmailCampaign($email_campaign_id);
if(!$email_campaign->id){
 header('Location: marketing.php?error=no_EC');
exit;
}



if (!($user->can_view('stores') and in_array($email_campaign->data['Email Campaign Store Key'],$user->stores)   ) ) {
    header('Location: index.php?error=ns');
    exit;
}



$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'marketing_reports.php','label'=>_('Reports'));
$general_options_list[]=array('tipo'=>'url','url'=>'campaign.php?new','label'=>_('Create Campaign'));
$general_options_list[]=array('tipo'=>'url','url'=>'newsletter.php?new','label'=>_('Create Newsletter'));
$smarty->assign('general_options_list',$general_options_list);

$store=new Store($email_campaign->data['Email Campaign Store Key']);
$smarty->assign('store',$store);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css','css/users.css'
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
		'email_campaign.js.php'
		);




$smarty->assign('parent','home');
$smarty->assign('title', _('Marketing'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('email_campaign',$email_campaign);

switch ($email_campaign->data['Email Campaign State']) {
    case 'Creating':
        $tpl_file='email_campaign_in_process.tpl';
        break;
    
}

$smarty->display($tpl_file);





?>

