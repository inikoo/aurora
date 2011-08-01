<?php
/*
 File: email_template.php

 Copyright (c) 2011, Inikoo
 Author: Raul Perusquia

*/
require_once 'common.php';
require_once 'class.EmailCampaign.php';

$css_files=array(
               $yui_path.'menu/assets/skins/sam/menu.css',
              $yui_path.'assets/skins/sam/editor.css',
               'button.css',
               'container.css',
              'table.css',
                 'css/upload.css'

           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
                $yui_path.'dragdrop/dragdrop-min',
              
            $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
            $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
            //  $yui_path.'calendar/calendar-min.js',
              $yui_path.'uploader/uploader-min.js',

              'js/common.js',
              'js/table_common.js',
             // 'js/search.js',
             'js/edit_common.js',
             
         
               'upload_common.js.php',
          );




if(!isset($_REQUEST['email_campaign_key']) or !is_numeric($_REQUEST['email_campaign_key']) or !isset($_REQUEST['email_content_key']) or !is_numeric($_REQUEST['email_content_key'])      )
exit;

$email_campaign=new EmailCampaign($_REQUEST['email_campaign_key']);
if(!$email_campaign->id){
    exit('error no email campaign found');

}
$email_content_key=$_REQUEST['email_content_key'];
$email_content_data=$email_campaign->get_content($email_content_key);


if(!$email_content_data){
    exit('error no content found');
}


 $js_files[]='edit_email_template.js.php?email_content_key='.$email_content_key;
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('email_content_key',$email_content_key);


$smarty->assign('edit',true);

$customer_key=0;

$store=new Store($email_campaign->data['Email Campaign Store Key']);
$customer=new Store($customer_key);

if(!$customer->id){
$customer->data['Customer Main Plain Email']='customer@example.com';
$customer->data['Customer Name']='Albert Widgets Ltd';
$customer->data['Customer Type']='Company';
$customer->data['Customer Main Contact Name']='Mr Albert Mc Loving';

}
$smarty->assign('email_campaign',$email_campaign);


$smarty->assign('header_src',$email_content_data['header_src']);


//print_r($email_content_data);

$smarty->assign('paragraphs',$email_content_data['paragraphs']);


$smarty->assign('store',$store);
$smarty->assign('customer',$customer);



$smarty->display('email_basic.tpl');


?>
