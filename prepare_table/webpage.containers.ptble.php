<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 11:58:28 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


$table = '`Website Webpage Scope Map`  left join  `Page Store Dimension` on (`Website Webpage Scope Webpage Key`=`Page Key`)   ';

$where = sprintf('where  `Website Webpage Scope Scope Webpage Key`=%d ',$parameters['parent_key']);



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
} elseif ($order == 'name') {
    $order = '`Webpage Name`';
} elseif ($order == 'scope') {
    $order = '`Webpage Scope`';
} elseif ($order == 'type') {
    $order = '`Website Webpage Scope Webpage Index`';
} else {
    $order = '`Website Webpage Scope Webpage Index`';
}


$sql_totals = "select count(Distinct `Page Key`) as num from $table  $where  ";


$fields = "`Page Key` as `Webpage Key` ,`Webpage Code`,`Webpage Name`,`Webpage State`,`Webpage Scope`,`Webpage Website Key`,`Website Webpage Scope Type`";





