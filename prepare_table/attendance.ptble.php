<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 March 2020  17:54::52  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where = sprintf(
    " where  date(`Timesheet Date`)=%s  and `Staff Currently Working`='Yes'  ", prepare_mysql(gmdate('Y-m-d'))
);

$group_by = ' ';


$wheref = '';
if ($parameters['f_field'] == 'alias' and $f_value != '') {
    $wheref .= " and  `Staff Alias` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Staff Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


switch ($parameters['elements_type']) {

    case 'status':
        $_elements      = '';
        $count_elements = 0;
        foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }


        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 5) {
            $where .= ' and `Staff Attendance Status` in ('.$_elements.')';
        }
        break;


}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'alias') {
    $order = "`Staff Alias`";
} elseif ($order == 'name') {
    $order = "`Staff Name`  ";
} elseif ($order == 'payroll_id') {
    $order = '`Staff ID`';
} elseif ($order == 'staff_formatted_id') {
    $order = '`Timesheet Staff Key`';
} elseif ($order == 'clocking_records') {
    $order = '`clocking_records`';
} elseif ($order == 'status') {
    $order           = "`Staff Attendance Status` $order_direction, `Staff Name`";
    $order_direction = '';
} else {
    $order = '`Timesheet Key`';
}


$table
    = '  `Timesheet Dimension` as TD left join `Staff Dimension` SD on (SD.`Staff Key`=TD.`Timesheet Staff Key`) ';

$sql_totals
    = "select count(distinct `Timesheet Staff Key`) as num from $table  $where  ";

//print $sql_totals;
$fields
    = "

`Staff Attendance Status` as status,
`Timesheet Clocking Records` clocking_records,

`Timesheet Ignored Clocking Records` clocking_ignored_records,
`Staff Alias`,
`Timesheet Staff Key`,
`Staff Name`,
TD.`Timesheet Key`,
`Staff ID`
";


