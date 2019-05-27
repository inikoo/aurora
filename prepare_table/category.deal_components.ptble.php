<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 May 2018 at 10:27:01 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/


$table = ' `Deal Component Dimension` DCD left join   `Deal Dimension` D on (`Deal Key`=`Deal Component Deal Key`) left join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) left join `Store Dimension` on (`Deal Store Key`=`Store Key`)';


$where = sprintf(
    ' where  `Deal Component Allowance Target`="Category" AND `Deal Component Allowance Target Key`=%d ', $parameters['parent_key']
);


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
                $where .= ' and `Deal Component Status` in ('.$_elements.')';


            }

            break;


    }
}


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Component Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Deal Name Label`';
} elseif ($order == 'orders') {
    $order = '`Deal Component Total Acc Used Orders`';
} elseif ($order == 'customers') {
    $order = '`Deal Component Total Acc Used Customers`';
} elseif ($order == 'from') {
    $order = '`Deal Component Begin Date`';
} elseif ($order == 'to') {
    $order = '`Deal Component Expiration Date`';
} elseif ($order == 'description') {
    $order = '`Deal Component Term Allowances Label`';
} else {
    $order = '`Deal Component Key`';
}
$fields = "
`Deal Component Term Allowances Label`,
`Deal Component Key`,`Deal Name`,`Deal Term Allowances Label`,`Deal Component Store Key`,D.`Deal Campaign Key`,`Deal Component Status`,`Deal Component Begin Date`,`Deal Component Expiration Date`,
`Deal Component Total Acc Used Orders`,`Deal Component Total Acc Used Customers`,`Store Bulk Discounts Campaign Key`,`Deal Name Label`,`Deal Component Allowance Type`,
`Deal Component Allowance`,`Deal Component Allowance Target`,`Deal Component Allowance Target Key`,`Deal Component Allowance Target Label`,`Deal Term Label`,`Deal Component Allowance Label`,`Deal Component Deal Key`

";


$sql_totals = "select count(*) as num from $table $where ";


?>
