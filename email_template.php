<?php
/*
 File: email_template.php

 Copyright (c) 2011, Inikoo
 Author: Raul Perusquia

*/
require_once 'common.php';
require_once 'class.EmailCampaign.php';

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
exit;

$email_campaign=new EmailCampaign($_REQUEST['id']);


$customer_key=0;

$store=new Store($email_campaign->data['Email Campaign Store Key']);
$customer=new Store($customer_key);

if(!$customer->id){
$customer->data['Customer Main Plain Email']='customer@example.com';
}
$smarty->assign('email_campaign',$email_campaign);

$email_content_data=$email_campaign->get_contents_array();
print_r($email_content_data);
exit;
$smarty->assign('store',$store);
$smarty->assign('customer',$customer);


$smarty->display('emails/basic.tpl');


?>