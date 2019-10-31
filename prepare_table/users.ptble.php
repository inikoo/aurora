<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 5 February 2019 at 00:05:23 GMT+8
 Copyright (c) 2018, Inikoo

 Version 3

*/



$group_by = '';


$table = '`User Dimension` U ';

switch ($parameters['parent']){
    case 'agent':
        $where = sprintf(" where  `User Type`='Agent' and `User Parent Key`=%d ",$parameters['parent_key']);
        break;
    default:
        $where = sprintf(" where  `User Type`!='Customer' ");

}




$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `User Alias` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'handle' and $f_value != '') {
    $wheref .= " and  `User Handle` like '".addslashes($f_value)."%'    ";
} else {
    if ($parameters['f_field'] == 'position_id' or $parameters['f_field'] == 'area_id' and is_numeric($f_value)) {
        $wheref .= sprintf(" and  %s=%d ", $parameters['f_field'], $f_value);
    }
}


if(isset($parameters['elements_type'])) {

    switch ($parameters['elements_type']) {

        case 'active':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= ','.prepare_mysql($_key);

                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 2) {
                $where .= ' and `User Active` in ('.$_elements.')';
            }
            break;


    }
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'name') {
    $order = '`User Alias`';
} elseif ($order == 'handle') {
    $order = '`User Handle`';
} elseif ($order == 'email') {
    $order = '`User Password Recovery Email`';
} elseif ($order == 'active') {
    $order = '`User Active`';
} elseif ($order == 'logins') {
    $order = '`User Login Count`';
} elseif ($order == 'last_login') {
    $order = '`User Last Login`';
} elseif ($order == 'fail_logins') {
    $order = '`User Failed Login Count`';
} elseif ($order == 'fail_last_login') {
    $order = '`User Last Failed Login`';
} elseif ($order == 'type') {
    $order = '`User Type`';
} else {
    $order = '`User Key`';
}


$sql_totals
    = "select count(Distinct U.`User Key`) as num from $table  $where  ";



$fields
    = "`User Failed Login Count`,`User Last Failed Login`,`User Last Login`,`User Login Count`,`User Alias`,`User Handle`,`User Password Recovery Email`,`User Type`,`User Parent Key`,`User Key`,`User Active`";

