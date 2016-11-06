<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished:11 January 2016 at 11:35:38 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table = '`Attachment Dimension` A ';
$where = ' where true';

$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'file_type') {
    $order = '`Attachment MIME Type`';
} elseif ($order == 'filesize') {
    $order = '`Attachment File Size`';
} else {
    $order = '`Attachment Key`';
}


$sql_totals
    = "select count(Distinct A.`Attachment Key`) as num from $table $where  ";
$fields
    = "`Attachment Key`,`Attachment MIME Type`,	`Attachment Type`,`Attachment File Size`,`Attachment Thumbnail Image Key`";

?>
