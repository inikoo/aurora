<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 17:39:38 GMT+8, Cyberjaya, Malaydia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$table = '`Page Store Dimension` P left join `Webpage Type Dimension` WTD on (WTD.`Webpage Type Key`=P.`Webpage Type Key`) ';

$where = 'where `Webpage State`="Ready"';

switch ($parameters['parent']) {

    case('website'):
        $where .= sprintf(' and  `Webpage Website Key`=%d  ', $parameters['parent_key']);
        break;
    case('webpage_type'):
        $where .= sprintf(' and  `Webpage Type Key`=%d  ', $parameters['parent_key']);
        break;
    case('node'):
        $where .= sprintf(' and  `Webpage Parent Key`=%d  ', $parameters['parent_key']);
        break;
    default:
        exit('parent not configured '.$parameters['parent']);

}

$group = '';



if (isset($parameters['elements_type'])) {

    switch ($parameters['elements_type']) {
        case 'type':
            $_elements      = '';
            $count_elements = 0;
            foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $count_elements++;

                    if($_key=='Others'){
                        $_elements.=",'Info','Home','Ordering','Customer','Portfolio','Sys'";
                    }else{
                        $_elements .= ','.prepare_mysql(preg_replace('/_/',' ',$_key));
                    }



                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 5) {
                $where .= ' and `Webpage Type Code` in ('.$_elements.')';

            }
            break;
        case 'version':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {

                    if($_key=='II')$_key=2;
                    if($_key=='I')$_key=1;
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
} elseif ($order == 'name') {
    $order = '`Webpage Name`';
} elseif ($order == 'template') {
    $order = '`Webpage Template Filename`';
}  elseif ($order == 'type') {
    $order = '`Webpage Type Code`';
} else {
    $order = '`Webpage Key`';
}


$sql_totals = "select count(Distinct `Page Key`) as num from $table  $where  ";



$fields = "`Page Key` as `Webpage Key` ,`Webpage Code`,`Webpage State`,`Webpage Scope`,`Webpage Website Key`,`Webpage Name`,`Webpage Template Filename`,`Webpage Type Code`";



?>
