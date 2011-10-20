<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');

include_once('../../class.Store.php');
include_once('../../class.EmailCampaign.php');
include_once('../../class.SendEmail.php');

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


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');





//$sql=sprintf("select * from `Email Campaign Dimension` where `Email Campaign Status` in ('Ready') and `Email Campaign Start Overdue Date`<%s ",prepare_mysql(date('Y-m-d H:i:s')));
$sql=sprintf("select * from `Email Campaign Dimension` where `Email Campaign Status` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $email_campaign=new EmailCampaign($row['Email Campaign Key']);
    if (!$email_campaign->id)continue;
    // $email_campaign->update(array('Email Campaign Status'=>'Sending','Email Campaign Start Overdue Date'=>date('Y-m-d H:i:s',strtotime('now +2 hours +00:00'))));
    $sql=sprintf("select * from `Email Campaign Mailing List`  where `Email Campaign Key`=%d  and `Email Send Key` is null   ",$email_campaign->id);

    $res2=mysql_query($sql);
    while ($row2=mysql_fetch_assoc($res2)) {
        $email_mailing_list_key=$row2['Email Campaign Mailing List Key'];
        $message_data=$email_campaign->get_message_data($email_mailing_list_key);

        if ($message_data['ok']) {
            $message_data['method']='smtp';
            $message_data['email_credentials_key']=1;
            $message_data['email_matter']='Marketing';
            $message_data['email_matter_key']=$email_mailing_list_key;
            $message_data['email_matter_parent_key']=$email_campaign->id;
            $message_data['recipient_type']=($row2['Customer Key']?'Customer':'Other');
            $message_data['recipient_key']=$row2['Customer Key'];
            $message_data['email_key']=$row2['Email Key'];


            $send_email=new SendEmail();

            $send_email->track=true;


            $send_result=$send_email->send($message_data);

        }
    }



}



?>
