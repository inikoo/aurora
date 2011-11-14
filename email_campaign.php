<?php
/*
 File: email_campaign.php

 UI index page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

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
if (!$email_campaign->id) {
    header('Location: marketing.php?error=no_EC');
    exit;
}



if (!($user->can_view('stores') and in_array($email_campaign->data['Email Campaign Store Key'],$user->stores)   ) ) {
    header('Location: index.php?error=ns');
    exit;
}



$general_options_list=array();

$store=new Store($email_campaign->data['Email Campaign Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
                              $yui_path.'assets/skins/sam/colorpicker.css',

               'common.css',
               'button.css',
               'container.css',
               'table.css',
                'theme.css.php'
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
              $yui_path.'editor/editor-min.js',
              $yui_path.'slider/slider-min.js',
              $yui_path.'colorpicker/colorpicker-min.js',

              
              
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              //'email_campaign.js.php'
          );




$smarty->assign('parent','marketing');
$smarty->assign('title', _('Marketing'));


$smarty->assign('email_campaign',$email_campaign);


$smarty->assign('search_scope','marketing');
$smarty->assign('search_label',_('Search'));

switch ($email_campaign->data['Email Campaign Status']) {
case 'Creating':
    $general_options_list=array();
    $css_files[]='css/edit.css';
    $css_files[]='css/email_campaign_in_process.css';
    $js_files[]='js/editor_image_uploader.js';
    $js_files[]='js/rgbcolor.js';
    $js_files[]='time_interval_functions.js.php';

    $js_files[]='js/edit_common.js';
    $js_files[]='email_campaign_in_process.js.php?email_campaign_key='.$email_campaign->id;
    $js_files[]='js/sugar-0.9.5.min.js';
    $tpl_file='email_campaign_in_process.tpl';
    $current_content_key=$email_campaign->get_first_content_key();
    $smarty->assign('current_content_key',$current_content_key);
    $smarty->assign('current_template_type',$email_campaign->get_template_type($current_content_key));
    $smarty->assign('current_color_scheme',$email_campaign->get_color_scheme($current_content_key));


    $color_schemes=array();
    $sql=sprintf("select * from `Email Template Color Scheme Dimension` where `Store Key`=%d  limit 100",$store->id);
    $res=mysql_query($sql);
    while ($row=mysql_fetch_assoc($res)) {
        $color_scheme=array();
        foreach($row as $key=>$value) {
            $color_scheme[preg_replace('/ /','_',$key)]=$value;
        }
        $color_schemes[]=$color_scheme;
    }
    $smarty->assign('color_schemes',$color_schemes);

    break;

case 'Ready':
    $tpl_file='email_campaign.tpl';

    $js_files[]='js/countdown.js';
    $js_files[]='email_campaign.js.php';

    $js_files[]='email_campaign_ready.js.php';

    break;
case 'Sending':
    $tpl_file='email_campaign.tpl';

    $js_files[]='js/countdown.js';
    $js_files[]='email_campaign.js.php';

    break;
case 'Complete':
    $tpl_file='email_campaign.tpl';

    $js_files[]='js/countdown.js';
    $js_files[]='email_campaign.js.php';

    break;

}



$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($tpl_file);





?>

