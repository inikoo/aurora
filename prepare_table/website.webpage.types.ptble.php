<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 October 2015 at 18:14:31 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where  = sprintf(" where `Webpage Type Website Key`=%d ",$parameters['parent_key']);
$wheref = '';


$group_by = ' ';

$table = '`Webpage Type Dimension` ';



$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Webpage Type Code`';
} elseif ($order == 'in_process_webpages') {
    $order = '`Webpage Type In Process Webpages`';
} elseif ($order == 'online_webpages') {
    $order = '`Webpage Type Online Webpages`';
} elseif ($order == 'offline_webpages') {
    $order = '`Webpage Type Offline Webpages`';
} else {
    $order = '`Webpage Type Key`';
}


$sql_totals = "select count(*) as num from $table  $where  ";

$fields = "`Webpage Type Key`,`Webpage Type Code`,`Webpage Type Website Key`,`Webpage Type Online Webpages`,`Webpage Type In Process Webpages`,`Webpage Type Offline Webpages`";

?>
