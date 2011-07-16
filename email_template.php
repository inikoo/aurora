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
              $yui_path.'assets/skins/sam/editor.css',

              // 'text_editor.css',
              //'common.css',
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
}
$smarty->assign('email_campaign',$email_campaign);


$smarty->assign('header_src',$email_content_data['header_src']);


//print_r($email_content_data);

$smarty->assign('paragraphs',$email_content_data['paragraphs']);


$smarty->assign('store',$store);
$smarty->assign('customer',$customer);

$data=array(
'p'=>array(array('title'=>'Donec eleifend nunc ut libero fringilla posuere','subtitle'=>'Duis mauris massa','content'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium sapien nec augue dictum tincidunt. Phasellus in vulputate nibh. Morbi ac odio lorem. Suspendisse ut nibh vel nibh malesuada ullamcorper vitae sed magna. Aliquam erat volutpat.'),
array('title'=>'Nullam interdum posuere ultricies','subtitle'=>'In sagittis augue tellus','content'=>'Morbi porttitor posuere venenatis. Aliquam tincidunt scelerisque porttitor. Vivamus vulputate tortor ut augue eleifend semper. Curabitur venenatis placerat porta. Aliquam semper magna vitae libero porttitor vulputate.'),
array('title'=>'Pellentesque sed sapien','subtitle'=>'Aliquam urna dui','content'=>'Quisque in purus eu purus malesuada porttitor. Proin sed arcu nisi. Ut in enim arcu. Cras consectetur commodo dolor, id tempus tortor imperdiet quis. Donec iaculis interdum congue. Nullam ultrices hendrerit lectus, vitae lobortis magna sagittis et.')
)
);
//a:1:{s:1:"p";a:3:{i:0;a:3:{s:5:"title";s:47:"Donec eleifend nunc ut libero fringilla posuere";s:8:"subtitle";s:17:"Duis mauris massa";s:7:"content";s:248:"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium sapien nec augue dictum tincidunt. Phasellus in vulputate nibh. Morbi ac odio lorem. Suspendisse ut nibh vel nibh malesuada ullamcorper vitae sed magna. Aliquam erat volutpat.";}i:1;a:3:{s:5:"title";s:33:"Nullam interdum posuere ultricies";s:8:"subtitle";s:24:"In sagittis augue tellus";s:7:"content";s:217:"Morbi porttitor posuere venenatis. Aliquam tincidunt scelerisque porttitor. Vivamus vulputate tortor ut augue eleifend semper. Curabitur venenatis placerat porta. Aliquam semper magna vitae libero porttitor vulputate.";}i:2;a:3:{s:5:"title";s:23:"Pellentesque sed sapien";s:8:"subtitle";s:16:"Aliquam urna dui";s:7:"content";s:248:"Quisque in purus eu purus malesuada porttitor. Proin sed arcu nisi. Ut in enim arcu. Cras consectetur commodo dolor, id tempus tortor imperdiet quis. Donec iaculis interdum congue. Nullam ultrices hendrerit lectus, vitae lobortis magna sagittis et.";}}}
//print serialize($data);
//exit;

$smarty->display('email_basic.tpl');


?>