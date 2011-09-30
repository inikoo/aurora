<?php
/*
 File: email_template.php

 Copyright (c) 2011, Inikoo
 Author: Raul Perusquia

*/
require_once 'common.php';
require_once 'class.EmailCampaign.php';
require_once 'class.LightCustomer.php';

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
              $yui_path.'uploader/uploader-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/edit_common.js',
              'upload_common.js.php',
          );


$edit=false;
if (isset($_REQUEST['edit'])    ) {
    $edit=true;
}

if (!isset($_REQUEST['email_campaign_key']) or !is_numeric($_REQUEST['email_campaign_key'])     ) {
    exit;
}
$email_campaign=new EmailCampaign($_REQUEST['email_campaign_key']);
if (!$email_campaign->id) {
    exit('error no email campaign found');

}


if ( isset($_REQUEST['email_mailing_list_key']) and is_numeric($_REQUEST['email_mailing_list_key'])) {
    $email_mailing_list_key=$_REQUEST['email_mailing_list_key'];
    $sql=sprintf("select * from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
                 $email_mailing_list_key,
                 $email_campaign->id
                );
    $res=mysql_query($sql);

    if ($row=mysql_fetch_assoc($res)) {
        $email_content_key=$row['Email Content Key'];


        $customer=new LightCustomer($row['Customer Key']);
        if (!$customer->id) {
            $customer->data['Customer Main Contact Name']=$row['Email Contact Name'];
            $customer->data['Customer Name']=$row['Email Contact Name'];
            $customer->data['Customer Main Plain Email']=$row['Email Address'];
            $customer->data['Customer Type']='person';
        }

    }



}
if (isset($_REQUEST['email_content_key']) and is_numeric($_REQUEST['email_content_key'])   ) {


    $email_content_key=$_REQUEST['email_content_key'];
    $customer=new LightCustomer(0);
    $customer->data['Customer Main Contact Name']='Albert Mc Loving';
    $customer->data['Customer Name']="Albert's Widgets";
    $customer->data['Customer Type']='company';
    $customer->data['Customer Main Plain Email']="albert@example.com";
}




$email_content_data=$email_campaign->get_content($email_content_key);


if (!$email_content_data) {
    exit('error no content found');
}

$js_files[]='edit_email_template.js.php?email_content_key='.$email_content_key;
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('email_content_key',$email_content_key);
$smarty->assign('edit',$edit);
$store=new Store($email_campaign->data['Email Campaign Store Key']);
$smarty->assign('email_campaign',$email_campaign);
$smarty->assign('header_src',$email_content_data['header_src']);
$smarty->assign('paragraphs',$email_content_data['paragraphs']);
$smarty->assign('store',$store);


$output = $smarty->fetch('email_basic.tpl');
if (preg_match_all('/\%[a-z]+\%/',$output,$matches)) {
    foreach($matches[0] as $match) {
        $output=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$output);
    }
}
print $output;
?>