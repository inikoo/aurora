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
    $sql=sprintf("select * from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Deal Key`=%d",
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



$smarty->assign('paragraphs',$email_content_data['paragraphs']);

if (isset($_REQUEST['color_scheme_key']) and is_numeric($_REQUEST['color_scheme_key'])) {
    $color_scheme=array();
    $sql=sprintf("select * from `Email Template Color Scheme Dimension` where `Email Template Color Scheme Key`=%d ",
                 $_REQUEST['color_scheme_key']);
    $res2=mysql_query($sql);
    if ($row2=mysql_fetch_assoc($res2)) {

        foreach($row2 as $key=>$value) {
            $color_scheme[preg_replace('/ /','_',$key)]=$value;
        }

    }
} else {

    $color_scheme=$email_content_data['color_scheme'];
}

$smarty->assign('color_scheme',$color_scheme);
if (!$email_content_data['header_image_key']) {
    if ($email_content_data['template_type']=='Postcard')
        $header_src=$color_scheme['Header_Slim_Image_Source'];

    else
        $header_src=$color_scheme['Header_Image_Source'];
} else {
    $header_src='image.php?id='.$email_content_data['header_image_key'];


}

if ($email_content_data['template_type']=='Postcard') {

if (!$email_content_data['postcard_image_key']) {

        $postcard_src=$color_scheme['Postcard_Image_Source'];
} else {
    $postcard_src='image.php?id='.$email_content_data['postcard_image_key'];


}

$smarty->assign('postcard_src',$postcard_src);

}




$smarty->assign('header_src',$header_src);



$smarty->assign('store',$store);

switch ($email_content_data['template_type']) {
case 'Basic':
    $output = $smarty->fetch('email_basic.tpl');
    break;
case 'Left Column':
    $output = $smarty->fetch('email_left_column.tpl');
    break;
case 'Right Column':
    $output = $smarty->fetch('email_right_column.tpl');
    break;
case 'Postcard':
    $output = $smarty->fetch('email_postcard.tpl');
    break;
default:
    $output='';
    break;
}



if (preg_match_all('/\%[a-z]+\%/',$output,$matches)) {
    foreach($matches[0] as $match) {
        $output=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$output);
    }
}
print $output;
?>