<?php
/*
 File: track.php
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Copyright (c) 2010, Inikoo
 Version 2.0
*/


require_once 'app_files/db/dns.php';
require_once 'common_detect_agent.php';
require_once 'common_functions.php';


$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
    header('Content-Type: image/png');
    readfile('art/inikoo_footer_for_emails.png');
    exit;
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    header('Content-Type: image/png');
    readfile('art/inikoo_footer_for_emails.png');
    exit;
}
mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");


if (!isset($_REQUEST['s']) or !is_numeric($_REQUEST['s'])) {

    header('Content-Type: image/png');
    readfile('art/inikoo_footer_for_emails.png');
}


require_once("class.EmailSend.php");


$email_send=new EmailSend($_REQUEST['s']);
//print_r($email_send);
if ($email_send->id) {
    $sql=sprintf("insert into `Email Send Read Fact` values(%d,%s,%s,%s,%s)",
                 $email_send->id,
                 prepare_mysql(date('Y-m-d H:i:s',strtotime('now +0:00'))),
                 prepare_mysql(ip()),
                 prepare_mysql(get_user_browser($_SERVER['HTTP_USER_AGENT'])),
                 prepare_mysql(get_user_os($_SERVER['HTTP_USER_AGENT']))

                );
   mysql_query($sql);
   
   $email_send->update_read();
   
}



?>
