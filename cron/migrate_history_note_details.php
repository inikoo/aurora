<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2016 at 18:04:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected = mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");


require_once 'utils/get_addressing.php';


$sql = sprintf(
    'SELECT `History Key`,`History Details` FROM `History Dimension` WHERE `History Key`=5963711 ORDER BY `History Key` DESC '
);
$sql = sprintf(
    'SELECT `History Key`,`History Details` FROM `History Dimension` ORDER BY `History Key` DESC '
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $details = $row['History Details'];
        $details = trim($details);
        // print $row['History Key']." $details\n";
        //		if (preg_match('/^\<table\>(\s|.)+\<\/table\>$/', $details)) {


        if (preg_match('/^\<table\>/', $details)) {
            if (preg_match('/\<\/table\>$/', $details)) {


                $details = preg_replace(
                    '/\<\/td\>\<tr>\<tr\>\<td\>/', '</td></tr><tr><td>', $details
                );
                $details = preg_replace(
                    '/\<\/td\>\<tr\>\<\/table\>$/', '</td></tr></table>', $details
                );


                $details = preg_replace(
                    '/\<table\>/', '<div class="table">', $details
                );
                $details = preg_replace('/\<\/table\>/', '</div>', $details);
                $details = preg_replace(
                    '/\<tr\>/', '<div class="field tr">', $details
                );
                $details = preg_replace('/\<\/tr\>/', '</div>', $details);

                $details = preg_replace(
                    '/\<td\>/', '<div class="td">', $details
                );
                $details = preg_replace(
                    '/\<td\s.+\>/', '<div class="td">', $details
                );

                $details = preg_replace('/\<\/td\>/', '</div>', $details);

                //print $row['History Key']."\n";
                //print $row['History Details']."\n\n";
                //print $details."\n";
                //exit;

                $sql = sprintf(
                    'UPDATE `History Dimension` SET `History Details`=%s WHERE `History Key`=%d', prepare_mysql($details), $row['History Key']
                );
                $db->exec($sql);


            }
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
