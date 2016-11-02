<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 March 2016 at 00:48:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'employees':
    case 'employee':

        $where = sprintf(" where  `Upload Object`='employee' ");
        $link  = '/employee/';

        break;


    default:
        exit('parent not suported x '.$parameters['parent']);
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'object_name' and $f_value != '') {
    $wheref .= " and  object_name like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'row') {
    $order = '`Upload Record Upload File Key`,`Upload Record Row Index`';
} elseif ($order == 'status') {
    $order = '`Upload Record Status`';
} elseif ($order == 'state') {
    $order = '`Upload Record State`';
} elseif ($order == 'date') {
    $order = '`Upload Created`';
} elseif ($order == 'object_name') {
    $order = 'object_name';
} elseif ($order == 'msg') {
    $order = '`Upload Record Message Code`';
} else {
    $order = '`Upload Record Key`';
}


$table
    = '  `Upload Dimension` U left join `User Dimension` on (`Upload User Key`=`User Key`) ';


$sql_totals = "select count(*) as num from $table  $where  ";
//print $sql_totals;
$fields
    = "
`Upload Key`,
`User Alias`,
`Upload Created`,
`Upload User Key`,
`Upload OK`,
`Upload Warnings`,
`Upload Errors`,
`Upload State`

";

?>
