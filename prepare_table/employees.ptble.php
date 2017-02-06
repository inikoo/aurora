<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 15:13:45 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table = '`Staff Dimension` SD left join `User Dimension` U on (SD.`Staff Key`=U.`User Parent Key` and `User Type`="Staff") ';
$where = ' where `Staff Type`!="Contractor" ';

if ($parameters['parent'] == 'account') {

} elseif ($parameters['parent'] == 'department') {
    $where .= sprintf(
        '  and  `Staff Department Key`=%d', $parameters['parent_key']
    );

} elseif ($parameters['parent'] == 'area') {

    $where .= sprintf(' and  `Staff Area Key`=%d', $parameters['parent_key']);

} elseif ($parameters['parent'] == 'position') {
    $table
        = '`Staff Dimension` SD  left join `Staff Role Bridge`  B on  (B.`Staff Key`=SD.`Staff Key`) left join `User Dimension` U on (SD.`Staff Key`=U.`User Parent Key` and `User Type`="Staff") ';

    $where = sprintf(' where  B.`Role Code`=%s', prepare_mysql($parameters['parent_key']));

}

if (isset($extra_where)) {
    $where .= " $extra_where";
}


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Staff Name` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'id') {
    $wheref .= sprintf(" and  `Staff Key`=%d ", $f_value);
}
if ($parameters['f_field'] == 'alias' and $f_value != '') {
    $wheref .= " and  `Staff Alias` like '".addslashes($f_value)."%'    ";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'name') {
    $order = '`Staff Name`';
} elseif ($order == 'code' or $order == 'code_link') {
    $order = '`Staff Alias`';
} elseif ($order == 'birthday') {
    $order = '`Staff Birthday`';
} elseif ($order == 'official_id') {
    $order = '`Staff Official ID`';
} elseif ($order == 'telephone') {
    $order = '`Staff Telephone Formatted`';
} elseif ($order == 'email') {
    $order = '`Staff Email`';
} elseif ($order == 'next_of_kind') {
    $order = '`Staff Next of Kind`';
} elseif ($order == 'job_title') {
    $order = '`Staff Job Title`';
} elseif ($order == 'roles') {
    $order = 'roles';
} elseif ($order == 'supervisors') {
    $order = 'supervisors';
} elseif ($order == 'from') {
    $order = '`Staff Valid From`';
} elseif ($order == 'until') {
    $order = '`Staff Valid To`';
} elseif ($order == 'roles') {
    $order = 'roles';
} elseif ($order == 'user_login') {
    $order = '`User Handle`';
} elseif ($order == 'user_active') {
    $order = '`User Active`';
} elseif ($order == 'user_last_login') {
    $order = '`User Last Login`';
} elseif ($order == 'user_number_logins') {
    $order = '`User Login Count`';
} elseif ($order == 'payroll_id') {
    $order = '`Staff ID`';
} elseif ($order == 'type') {
    $order = '`Staff Type`';
} elseif ($order == 'id') {
    $order = '`Staff Key`';
} else {
    $order = '`Staff Key`';
}


//( select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as roles


$sql_totals
    = "select count(Distinct SD.`Staff Key`) as num from $table  $where  ";


$fields
    = "`Staff ID`,`Staff Job Title`,`Staff Birthday`,`Staff Official ID`,`Staff Email`,`Staff Telephone Formatted`,`Staff Telephone`,`Staff Next of Kind`,
`Staff Alias`,SD.`Staff Key`,`Staff Name`,`Staff Type`,
`Staff Valid To`,`Staff Valid From`,`User Login Count`,
`User Handle`,`User Active`,`User Last Login`,
(select GROUP_CONCAT(`Role Code` ) from `Staff Role Bridge` B  where  B.`Staff Key`=SD.`Staff Key` ) as roles,
(select GROUP_CONCAT(`Staff Alias`  order by `Staff Alias` separator \", \")    from  `Staff Supervisor Bridge` B left join `Staff Dimension` S on (B.`Supervisor Key`=S.`Staff Key`)  where  B.`Staff Key`=SD.`Staff Key` ) as supervisors
	
	
";
?>
