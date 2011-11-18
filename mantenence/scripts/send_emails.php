<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 LW

chdir('../../');

include_once('app_files/db/dns.php');
include_once('class.Image.php');

include_once('class.Store.php');
include_once('class.EmailCampaign.php');
include_once('class.SendEmail.php');
include_once('external_libs/Smarty/Smarty.class.php');
    $smarty = new Smarty();


            $smarty->template_dir = 'templates';
            $smarty->compile_dir = 'server_files/smarty/templates_c';
            $smarty->cache_dir = 'server_files/smarty/cache';
            $smarty->config_dir = 'server_files/smarty/configs';



error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once 'common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');


$sql=sprintf("select `Inikoo Public URL`,`HQ Country 2 Alpha Code`,`HQ Country Code`,`HQ Currency`,`Currency Symbol` from  `HQ Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`HQ Currency`) ");
//print $sql;

$res=mysql_query($sql);

if ($row=mysql_fetch_array($res)) {
$inikoo_public_url=$row['Inikoo Public URL'];
}


//$sql=sprintf("select * from `Email Campaign Dimension` where `Email Campaign Status` in ('Ready') and `Email Campaign Start Overdue Date`<%s ",prepare_mysql(date('Y-m-d H:i:s')));
$sql=sprintf("select * from `Email Campaign Dimension` where `Email Campaign Status` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $email_campaign=new EmailCampaign($row['Email Campaign Key']);
    if (!$email_campaign->id) {
        print "x1\n";
        continue;
    }

    $store=new Store($row['Email Campaign Store Key']);
    if (!$store->id) {
    print "x2\n";
        continue;
    }


    $email_credentials_key=$store->get_email_credential_key('Marketing Email');
    if (!$email_credentials_key) {
print "x3\n";
        continue;
    }

$email_campaign->consolidate();

    // $email_campaign->update(array('Email Campaign Status'=>'Sending','Email Campaign Start Overdue Date'=>date('Y-m-d H:i:s',strtotime('now +2 hours +00:00'))));
    $sql=sprintf("select * from `Email Campaign Mailing List`  where `Email Campaign Key`=%d  and `Email Send Key` is null   ",$email_campaign->id);

    $res2=mysql_query($sql);
    while ($row2=mysql_fetch_assoc($res2)) {
        $email_mailing_list_key=$row2['Email Campaign Mailing List Key'];
        
        $message_data=$email_campaign->get_message_data($email_mailing_list_key,$smarty,$inikoo_public_url);

        if ($message_data['ok']) {
            $message_data['method']='smtp';
            $message_data['email_credentials_key']=$store->get_email_credential_key('Marketing Email');
            $message_data['email_matter']='Marketing';
            $message_data['email_matter_key']=$email_mailing_list_key;
            $message_data['email_matter_parent_key']=$email_campaign->id;
            $message_data['recipient_type']=($row2['Customer Key']?'Customer':'Other');
            $message_data['recipient_key']=$row2['Customer Key'];
            $message_data['email_key']=$row2['Email Key'];




            $send_email=new SendEmail();

            $send_email->track=true;

//print_r($message_data);
            $send_result=$send_email->send($message_data);

        }
    }



}



?>
