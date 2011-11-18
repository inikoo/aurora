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
if (isset($_REQUEST['edit'])    ) {    $edit=true;}

if (!isset($_REQUEST['email_campaign_key']) or !is_numeric($_REQUEST['email_campaign_key'])     ) {
    exit;
}
$email_campaign_key=$_REQUEST['email_campaign_key'];


$email_campaign=new EmailCampaign($email_campaign_key);
if (!$email_campaign->id) {
    exit('error no email campaign found');

}

$email_mailing_list_key=0;
$email_content_key=0;
if ( isset($_REQUEST['email_mailing_list_key']) and is_numeric($_REQUEST['email_mailing_list_key'])) {
$email_mailing_list_key=$_REQUEST['email_mailing_list_key'];
}
if (isset($_REQUEST['email_content_key']) and is_numeric($_REQUEST['email_content_key'])   ) {
$email_content_key=$_REQUEST['email_content_key'];
}


if (isset($_REQUEST['color_scheme_key']) and is_numeric($_REQUEST['color_scheme_key'])) {
$color_scheme_key=$_REQUEST['color_scheme_key'];
}else{
$color_scheme_key=false;
}
$html_data=array('smarty'=>$smarty,'css_files'=>$css_files,'js_files'=>$js_files,'output_type'=>($edit?'edit':''),'inikoo_public_url'=>'');
$output=$email_campaign->get_templete_html($html_data,$email_mailing_list_key,$email_content_key,$color_scheme_key);

print $output;


?>