<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 16 December 2015 at 23:48:32 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table
    = '`Staff Dimension` SD left join `Company Position Staff Bridge` B on (B.`Staff Key`=SD.`Staff Key`) left join `Company Position Dimension` P on (P.`Company Position Key`=B.`Position Key`)  ';
$where
    = ' where `Company Position Code`="PROD.O"  and `Staff Currently Working`="Yes" ';


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Staff Name` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'id') {
    $wheref .= sprintf(" and  SD.`Staff Key`=%d ", $f_value);
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
} elseif ($order == 'payroll_id') {
    $order = '`Staff ID`';
} elseif ($order == 'type') {
    $order = '`Staff Type`';
} elseif ($order == 'id') {
    $order = 'SD.`Staff Key`';
} else {
    $order = 'SD.`Staff Key`';
}


//( select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.SD.`Staff Key`= SD.`Staff Key`) as roles


$sql_totals
    = "select count(Distinct SD.`Staff Key`) as num from $table  $where  ";

$fields
    = "`Staff ID`,`Staff Job Title`,`Staff Birthday`,`Staff Official ID`,`Staff Email`,`Staff Telephone Formatted`,`Staff Telephone`,`Staff Next of Kind`,
`Staff Alias`,SD.`Staff Key`,`Staff Name`,`Staff Type`,
(select GROUP_CONCAT(`Staff Alias`  order by `Staff Alias` separator \", \")    from  `Staff Supervisor Bridge` B left join `Staff Dimension` S on (B.`Supervisor Key`=S.`Staff Key`)  where  B.`Staff Key`=SD.`Staff Key` ) as supervisors
	
	
";
?>
