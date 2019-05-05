<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2018 at 22:01:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$group='';

$where=' where true ' ;



switch ($parameters['elements_type']) {

    case 'type':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;

                if($_key=='GRReminder'){
                    $_key='GR Reminder';
                }elseif($_key=='OOSNotification'){
                    $_key='OOS Notification';
                }

                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 5) {
            $where .= ' and `Email Campaign Type` in ('.$_elements.')';
        }
        break;



}




$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Email Campaign Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Email Campaign Name`';
} elseif ($order == 'date') {
    $order = '`Email Campaign Last Updated Date`';
} elseif ($order == 'state') {
    $order = '`Email Campaign State`';
} elseif ($order == 'open') {
    $order = '`Email Campaign Open`/`Email Campaign Delivered`';
}elseif ($order == 'click') {
    $order = '`Email Campaign Clicked`/`Email Campaign Delivered`';
} elseif ($order == 'state') {
    $order = '`Email Campaign State`';
}elseif ($order == 'bounces') {
    $order = '(`Email Campaign Hard Bounces`+`Email Campaign Soft Bounces`)';
} else {
    $order = '`Email Campaign Key`';
}
$table  = '`Email Campaign Dimension`  left join `Store Dimension` on (`Email Campaign Store Key`=`Store Key`) ';
$fields = "`Email Campaign Key`,`Email Campaign Name`,`Email Campaign Store Key`,`Email Campaign Last Updated Date`,`Email Campaign State`,`Email Campaign Number Estimated Emails`,`Store Code`,`Store Name`,`Store Key`,`Email Campaign Type`,`Email Campaign Email Template Type Key`,
`Email Campaign Sent`,`Email Campaign Delivered`,`Email Campaign Hard Bounces`,`Email Campaign Soft Bounces`,(`Email Campaign Hard Bounces`+`Email Campaign Soft Bounces`) as `Email Campaign Bounces`,`Email Campaign Open`,`Email Campaign Clicked`,`Email Campaign Spams`,`Email Campaign Unsubscribed`

";


$sql_totals = "select count(*) as num from $table $where ";
//print $sql_totals;

?>
