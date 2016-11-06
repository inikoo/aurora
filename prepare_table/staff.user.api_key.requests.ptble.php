<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 22 November 2015 at 10:55:43 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'user':
        $where = sprintf(
            " where  AKD.`API Key User Key`=%d ", $parameters['parent_key']
        );
        break;
    case 'api_key':
        $where = sprintf(
            " where  AKD.`API Key Key`=%d ", $parameters['parent_key']
        );
        break;
    default:
        $where = " where  true";
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'handle' and $f_value != '') {
    $wheref .= " and  `User Handle` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'handle') {
    $order = '`User Handle`';
} elseif ($order == 'user') {
    $order = '`User Alias`';
} elseif ($order == 'ip') {
    $order = '`IP`';
} elseif ($order == 'method') {
    $order = '`HTTP Method`';
} elseif ($order == 'scope') {
    $order = '`API Key Scope`';
} elseif ($order == 'response') {
    $order = '`Response`';
} elseif ($order == 'response_code') {
    $order = '`Response Code`';
} else {
    $order = '`Date`';
}


$table
    = '  `API Request Dimension` as ARD left join   `API Key Dimension` AKD   on (ARD.`API Key Key`=AKD.`API Key Key`)   left join `User Dimension` U on (U.`User Key`=AKD.`API Key User Key`) ';

$sql_totals = "select count(*) as num from $table  $where  ";

//print $sql_totals;
$fields
    = "`HTTP Method`,`Date`,`IP`,`Response`,`Response Code`,`User Alias`,`User Handle`,AKD.`API Key User Key`, ARD.`API Key Key`,`API Key Active`,`API Key Scope`";

?>
