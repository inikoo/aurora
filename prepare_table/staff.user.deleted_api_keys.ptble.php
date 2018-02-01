<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2018 at 17:53:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case 'user':
        $where = sprintf(
            " where  AKD.`API Key Deleted User Key`=%d ", $parameters['parent_key']
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
}  elseif ($order == 'deleted_date') {
    $order = '`API Key Deleted Date`';
} elseif ($order == 'scope') {
    $order = '`API Key Deleted Scope`';
} else {
    $order = '`API Key Deleted Key`';
}


$table
    = ' `API Key Deleted Dimension` AKD left join `User Dimension` U on (U.`User Key`=AKD.`API Key Deleted User Key`) ';

$sql_totals
    = "select count(Distinct `API Key Deleted Key`) as num from $table  $where  ";

//print $sql_totals;
$fields
    = "`User Alias`,`User Handle`,AKD.`API Key Deleted User Key`,`API Key Deleted Key`,`API Key Deleted Date`,
`API Key Deleted Scope`,`API Key Deleted Key`,`User Parent Key`,`API Key Deleted Code`";

?>
