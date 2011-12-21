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

if (count($user->stores)==0) {
    header('Location: marketing.php');
}

include_once('class.Store.php');
include_once('class.EmailCampaign.php');


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'common.css',
               'button.css',
               'css/container.css',
               'table.css','css/users.css','css/edit.css'
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
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',

          );


$status=false;

if(!isset($_REQUEST['store'])  ) {
 header('Location: marketing.php?error');
        exit;
}

$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);


    $js_files[]='new_email_campaign.js.php';
    if (!is_numeric($_REQUEST['store']) ) {
        header('Location: marketing.php?error');
        exit;
    }
    $store_id=$_REQUEST['store'];
    if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
        header('Location: index.php?error2');
        exit;
    }

    $store=new Store($store_id);

    if ($store->id) {
        $_SESSION['state']['marketing']['store']=$store->id;
    } else {
        header('Location: index.php?error3');
        exit;
    }

    $smarty->assign('store',$store);
    $smarty->assign('store_key',$store->id);
    $smarty->assign('parent','marketing');
    $smarty->assign('title', _('New Email Campaign'));
    $smarty->assign('css_files',$css_files);
    $smarty->assign('js_files',$js_files);

    $smarty->display('new_email_campaign.tpl');

 






?>

