<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 5 November 2015 at 17:31:59 CET, Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'user':
        $where = sprintf(
            " where  AKD.`API Key User Key`=%d ", $parameters['parent_key']
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
} elseif ($order == 'valid_ip') {
    $order = '`API Key Allowed IP`';
} elseif ($order == 'to') {
    $order = '`API Key Valid To`';
} elseif ($order == 'from') {
    $order = '`API Key Valid From`';
} elseif ($order == 'request_per_hours') {
    $order = '`API Key Allowed Requests per Hour`';
} elseif ($order == 'scope') {
    $order = '`API Key Scope`';
} elseif ($order == 'ok_requests') {
    $order = '`API Key Successful Requests`';
} elseif ($order == 'fail_ip') {
    $order = '`API Key Failed IP Requests`';
} elseif ($order == 'fail_limit') {
    $order = '`API Key Failed Time Limit Requests`';
} elseif ($order == 'last_request_date') {
    $order = '`API Key Last Request Date`';
} else {
    $order = '`API Key Key`';
}


$table
    = ' `API Key Dimension` AKD left join `User Dimension` U on (U.`User Key`=AKD.`API Key User Key`) ';

$sql_totals
    = "select count(Distinct `API Key Key`) as num from $table  $where  ";

//print $sql_totals;
$fields
    = "`User Alias`,`User Handle`,AKD.`API Key User Key`,`API Key Key`,`API Key Valid From`,`API Key Valid To`,`API Key Allowed IP`,`API Key Active`,
`API Key Scope`,`API Key Successful Requests`,`API Key Failed IP Requests`,`API Key Failed Time Limit Requests`,`API Key Last Request Date`";

?>
