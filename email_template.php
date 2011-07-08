<?php
/*
 File: email_template.php

 Copyright (c) 2011, Inikoo
 Author: Raul Perusquia

*/
require_once 'common.php';
require_once 'class.EmailCampaign.php';

$css_files=array(
//               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
              // $yui_path.'calendar/assets/skins/sam/calendar.css',
              // $yui_path.'button/assets/skins/sam/button.css',
              // $yui_path.'editor/assets/skins/sam/editor.css',
              // $yui_path.'assets/skins/sam/autocomplete.css',

              // 'text_editor.css',
              //'common.css',
               'button.css',
               'container.css',
            //   'table.css',
               

           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
                $yui_path.'dragdrop/dragdrop-min',
              
    //          $yui_path.'paginator/paginator-min.js',
      //        $yui_path.'datasource/datasource-min.js',
        //      $yui_path.'autocomplete/autocomplete-min.js',
          //    $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
            //  $yui_path.'calendar/calendar-min.js',
              $yui_path.'uploader/uploader-min.js',

              'js/common.js',
              //'js/table_common.js',
             // 'js/search.js',
             'js/edit_common.js',
             
             'edit_email_template.php.js',
               'upload_common.js.php',
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
exit;

$email_campaign=new EmailCampaign($_REQUEST['id']);

$smarty->assign('edit',true);

$customer_key=0;

$store=new Store($email_campaign->data['Email Campaign Store Key']);
$customer=new Store($customer_key);

if(!$customer->id){
$customer->data['Customer Main Plain Email']='customer@example.com';
}
$smarty->assign('email_campaign',$email_campaign);

$email_content_data=$email_campaign->get_contents_array();
$smarty->assign('header_src',$email_content_data[$email_campaign->id]['header_src']);


$metadata=unserialize($email_content_data[$email_campaign->id]['metadata']);
$smarty->assign('paragraphs',$metadata['p']);


$smarty->assign('store',$store);
$smarty->assign('customer',$customer);



$smarty->display('emails/basic.tpl');


?>