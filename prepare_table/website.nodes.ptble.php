<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 11:58:28 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);


$table
    = '`Website Node Dimension` N  left join `Webpage Dimension`  on (`Webpage Key`=`Website Node Webpage Key`) ';

switch ($parameters['parent']) {

    case('website'):
        $where = sprintf(
            ' where  `Website Node Website Key`=%d  ', $parameters['parent_key']
        );
        break;
    case('node'):
        $where = sprintf(
            ' where  `Website Node Parent Key`=%d and `Website Node Parent Key`!=`Website Node Key` ', $parameters['parent_key']
        );
        break;
    default:
        exit('parent not configured '.$parameters['parent']);

}

$group = '';


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
                    $_elements .= ','.prepare_mysql($_key);

                }
            }


            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 2) {
                $where .= ' and `Website Node Status` in ('.$_elements.')';

            }
            break;

    }
}


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Website Node Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Website Node Name` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Webpage Code`';
}
if ($order == 'name') {
    $order = '`Webpage Name`';
} else {
    $order = 'N.`Website Node Key`';
}


$sql_totals
    = "select count(Distinct N.`Website Node Key`) as num from $table  $where  ";

$fields
    = "
`Website Node Key`,`Webpage Code`,`Webpage Name`
";
?>
