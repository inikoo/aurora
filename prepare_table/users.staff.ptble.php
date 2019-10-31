<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 12:18:06 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



$group_by = '';
$table
    = '`User Dimension` U left join `Staff Dimension` SD  on (`User Parent Key`=`Staff Key`)';

$where = " where  `User Type`='Staff' ";
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
} elseif ($order == 'payroll_id') {
    $order = '`Staff ID`';
} else {
    $order = '`User Key`';
}


$sql_totals
    = "select count(Distinct U.`User Key`) as num from $table  $where  ";

//print $sql_totals;


$fields
    = "`User Failed Login Count`,`User Last Failed Login`,`User Last Login`,`User Login Count`,`User Alias`,`User Handle`,`User Password Recovery Email`,`Staff Key`,`Staff ID`,
	(select GROUP_CONCAT(S.`Store Code` SEPARATOR ', ') from `User Right Scope Bridge` URSB  left join `Store Dimension` S on (URSB.`Scope Key`=S.`Store Key`) where URSB.`User Key`=U.`User Key` and `Scope`='Store' ) as Stores,
	(select GROUP_CONCAT(S.`Warehouse Code` SEPARATOR ', ') from `User Right Scope Bridge` URSB left join `Warehouse Dimension` S on (URSB.`Scope Key`=S.`Warehouse Key`) where URSB.`User Key`=U.`User Key`and `Scope`='Warehouse'  ) as Warehouses ,
	(select GROUP_CONCAT(S.`Website Code` SEPARATOR ', ') from `User Right Scope Bridge` URSB left join `Website Dimension` S on (URSB.`Scope Key`=S.`Website Key`)  where URSB.`User Key`=U.`User Key`and `Scope`='Website'  ) as Sites ,

	(select GROUP_CONCAT(S.`User Group Name` SEPARATOR ', ') from `User Group User Bridge` URSB left join `User Group Dimension` S on (URSB.`User Group Key`=S.`User Group Key`)   where URSB.`User Key`=U.`User Key` ) as _Groups,`User Key`,`User Active`
";

