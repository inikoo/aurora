<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 13:44:56 GMT+7, Bangkok, Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('deal'):
        $where = sprintf(
            ' where  `Deal Component Deal Key`=%d', $parameters['parent_key']
        );
        break;
    case('campaign'):
        $where = sprintf(
            ' where  `Deal Component Campaign Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}

/*
if (isset($parameters['elements_type'])) {
    switch ($parameters['elements_type']) {
        case 'status':

            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= ",'".addslashes($_key)."'";


                }
            }

            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 4) {
                $where .= ' and `Deal Status` in ('.$_elements.')';


            }

            break;
        case 'trigger':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= ",'".addslashes(
                            preg_replace('/_/', ' ', $_key)
                        )."'";


                }
            }

            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 7) {
                $where .= ' and `Deal Trigger` in ('.$_elements.')';


            }

            break;

    }
}
*/

$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'target') {
    $order = '`Deal Component Allowance Target Label`';
} elseif ($order == 'orders') {
    $order = '`Deal Total Acc Used Orders`';
}elseif ($order == 'allowance') {
    $order = '`Deal Component Allowance`';
} elseif ($order == 'customers') {
    $order = '`Deal Total Acc Used Customers`';
} elseif ($order == 'from') {
    $order = '`Deal Begin Date`';
} elseif ($order == 'to') {
    $order = '`Deal Expiration Date`';
} elseif ($order == 'description') {
    $order = '`Deal Term Allowances Label`';
} else {
    $order = '`Deal Key`';
}
$table  = '`Deal Component Dimension` left join `Deal Dimension` D on (`Deal Component Deal Key`=`Deal Key`)  left join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) ';
$fields = "`Deal Component Key`,`Deal Component Begin Date`,`Deal Component Expiration Date`,`Deal Component Status`,`Deal Key`,`Deal Name`,`Deal Term Allowances`,`Deal Term Allowances Label`,`Deal Store Key`,D.`Deal Campaign Key`,`Deal Status`,`Deal Begin Date`,`Deal Expiration Date`,
`Deal Total Acc Used Orders`,`Deal Total Acc Used Customers`,`Deal Component Total Acc Used Orders`,`Deal Component Total Acc Used Customers`,`Deal Component Name Label`,`Deal Component Term Label`,`Deal Component Allowance Label`,
`Deal Component Allowance Target Label`,`Deal Component Allowance Target`,`Deal Component Allowance Target Key`,`Deal Component Store Key`,`Deal Component Allowance Type`,`Deal Component Allowance`
";


$sql_totals = "select count(*) as num from $table $where ";


?>
