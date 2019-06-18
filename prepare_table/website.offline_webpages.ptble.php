<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 11:58:28 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

$table = '`Page Store Dimension` P left join `Webpage Type Dimension` WTD on (WTD.`Webpage Type Key`=P.`Webpage Type Key`) ';

$where = 'where `Webpage State`="Offline"';

switch ($parameters['parent']) {

    case('website'):
        $where .= sprintf(' and  `Webpage Website Key`=%d  ', $parameters['parent_key']);
        break;
    case('webpage_type'):
        $where .= sprintf(' and  P.`Webpage Type Key`=%d  ', $parameters['parent_key']);
        break;
    default:
        exit('parent not configured '.$parameters['parent']);

}

$group = '';



if (isset($parameters['elements_type'])) {

    switch ($parameters['elements_type']) {
        case 'state':
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
                $where .= ' and `Webpage State` in ('.$_elements.')';

            }
            break;
        case 'version':
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
                $where .= ' and `Webpage Version` in ('.$_elements.')';

            }
            break;

    }
}


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Webpage Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Webpage Name` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Webpage Code`';
} elseif ($order == 'state') {
    $order = '`Webpage State`';
} elseif ($order == 'type') {
    $order = '`Webpage Scope`';
} else {
    $order = '`Webpage Key`';
}


$sql_totals = "select count(Distinct `Page Key`) as num from $table  $where  ";

$fields = "`Webpage Name`,`Page Key` as `Webpage Key` ,`Webpage Code`,`Webpage State`,`Webpage Scope`,`Webpage Website Key`,`Webpage Type Code`";

/*
$table = '`Webpage Dimension` N';

switch ($parameters['parent']) {

    case('website'):
        $where = sprintf(
            ' where  `Webpage Website Key`=%d  ', $parameters['parent_key']
        );
        break;
    case('node'):
        $where = sprintf(
            ' where  `Webpage Parent Key`=%d  ', $parameters['parent_key']
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
                $where .= ' and `Webpage Status` in ('.$_elements.')';

            }
            break;

    }
}


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Webpage Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Webpage Name` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Webpage Code`';
}
if ($order == 'name') {
    $order = '`Webpage Name`';
} else {
    $order = 'N.`Webpage Key`';
}


$sql_totals = "select count(Distinct N.`Webpage Key`) as num from $table  $where  ";

$fields = "`Webpage Key`,`Webpage Code`,`Webpage Name`";

*/

?>
