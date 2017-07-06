<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 18:16:46 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$table
    = '`Email Blueprint Dimension` EB left join `Staff Dimension`  on (`Email Blueprint Created By`=`Staff Key`)';

$fields = "`Email Blueprint Name`,`Email Blueprint Created`,`Email Blueprint Key`,`Email Blueprint Image Key`,`Staff Alias`";

switch ($parameters['parent']) {
    case 'Welcome':
        $where = sprintf(
            " where `Email Blueprint Role`='Welcome' and `Email Blueprint Scope Key`=%d", $parameters['parent_key']
        );
        break;
    case 'product':
        $where = sprintf(
            " where `Email Blueprint Subject Object`='Product' and `Email Blueprint Subject Object Key`=%d", $parameters['parent_key']
        );
        break;
    case 'employee':
        $where = sprintf(
            " where `Email Blueprint Subject Object`='Staff' and `Email Blueprint Subject Object Key`=%d", $parameters['parent_key']
        );
        break;
    case 'category':
        $where = sprintf(
            " where `Email Blueprint Subject Object`='Category' and `Email Blueprint Subject Object Key`=%d", $parameters['parent_key']
        );
        break;
    case 'account':
        // $table='`Email Blueprint Dimension` I ';
        $where = ' where true';
        break;
    default:

        exit('email blueprint parent not done yet '.$parameters['parent']);

}


$wheref = '';


$_order = $order;
$_dir   = $order_direction;

if ($order == 'name') {
    $order = '`Email Blueprint Name`';
}elseif ($order == 'author') {
    $order = '`Staff Alias`';
} elseif ($order == 'data') {
    $order = '`Email Blueprint Created`';
}  else {
    $order = '`Email Blueprint Key`';
}




$sql_totals = "select count(Distinct EB.`Email Blueprint Key`) as num from $table $where  ";


?>
