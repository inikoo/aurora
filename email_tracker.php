<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2018 at 14:57:32 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/



require_once 'keyring/dns.php';

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$sql = sprintf(
    'insert into atest  (`date`,`headers`,`request`) values (NOW(),"%s","%s")  ', addslashes(json_encode(getallheaders())), addslashes(json_encode($_REQUEST))

    );

print $sql;

$db->exec($sql);


/*
$date=gmdate('Y-m-d H:i:s');




$sql=sprintf('update `Email Send Dimension` 
  set `Email Send First Read Date` =CASE WHEN `Email Send Last Read Date` IS NULL THEN  "%s"  ELSE `Email Send Last Read Date` END  , `Email Send Last Read Date`="%s" ,`Email Send Number Reads`=`Email Send Number Reads`+1   where `Email Send Key`=%d    ',
    addslashes($date),
             addslashes($date),
$_REQUEST['id']

    );


$db->exec($sql);
*/



?>
