<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 20:26:00 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where = 'where true ';


switch ($parameters['parent']) {
    case('store'):
        $where .= sprintf(' and `Website Store Key`=%d ', $parameters['parent_key']);
        break;
    default:
        $where .= sprintf(' and true');
        break;

}

$group = '';


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
        } elseif ($count_elements < 3) {
            $where .= ' and `Website Status` in ('.$_elements.')';
        }
        break;


}


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and `Website Name` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'url' and $f_value != '') {
    $wheref .= " and  `Website URL` like '%".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'name') {
    $order = '`Website Name`';
} elseif ($order == 'url') {
    $order = '`Website URL`';
} elseif ($order == 'users') {
    $order = '`Website Total Acc Users`';
} elseif ($order == 'code') {
    $order = '`Website Code`';

} elseif ($order == 'online_webpages') {
    $order = '`Website Number Online Webpages`';
} elseif ($order == 'offline_webpages') {
    $order = '`Website Number Offline Webpages`';
} elseif ($order == 'in_process_webpages') {
    $order = '`Website Number In Process Webpages`';
} elseif ($order == 'products') {
    $order = '`Website Number Products`';
} elseif ($order == 'visitors') {
    $order = '`Website Total Acc Visitors`';
} elseif ($order == 'requests') {
    $order = '`Website Total Acc Requests`';
} elseif ($order == 'sessions') {
    $order = '`Website Total Acc Sessions`';
} elseif ($order == 'pages_products') {
    $order = '`Website Number WebPages with Products`';
} elseif ($order == 'pages_out_of_stock') {
    $order = '`Website Number WebPages with Out of Stock Products`';
} elseif ($order == 'pages_out_of_stock_percentage') {
    $order = '`Website Number WebPages with Out of Stock Products`/`Website Number WebPages with Products`';
} elseif ($order == 'email_reminders_customers') {
    $order = '`Website Number Back in Stock Reminder Customers`';
} elseif ($order == 'email_reminders_products') {
    $order = '`Website Number Back in Stock Reminder Products`';
} elseif ($order == 'email_reminders_waiting') {
    $order = '`Website Number Back in Stock Reminder Waiting`';
} elseif ($order == 'email_reminders_ready') {
    $order = '`Website Number Back in Stock Reminder Ready`';
} elseif ($order == 'email_reminders_sent') {
    $order = '`Website Number Back in Stock Reminder Sent`';
} elseif ($order == 'email_reminders_cancelled') {
    $order = '`Website Number Back in Stock Reminder Cancelled`';
} elseif ($order == 'out_of_stock') {
    $order = '`Website Number Out of Stock Products`';
} elseif ($order == 'out_of_stock_percentage') {
    $order = '`Website Number Out of Stock Products`/`Website Number Products`';
} elseif ($order == 'gsc_clicks') {
    $order = '`Website GSC Clicks`';
} elseif ($order == 'gsc_impressions') {
    $order = '`Website GSC Impressions`';
} elseif ($order == 'gsc_ctr') {
    $order = '`Website GSC CTR`';
} elseif ($order == 'gsc_position') {
    $order = '`Website GSC Position`';
} else {

    $order = 'W.`Website Key`';

}


$table = '`Website Dimension` W left join `Website Data` D on (W.`Website Key`=D.`Website Key`)';

$sql_totals = "select count(Distinct W.`Website Key`) as num from $table  $where  ";

$fields = "`Website Store Key`,`Website Number Products`,`Website Number Out of Stock Products`,`Website Number WebPages with Out of Stock Products`,`Website Number WebPages with Products`,`Website Number WebPages`,`Website Total Acc Requests`,`Website Total Acc Sessions`,`Website Total Acc Visitors`,`Website Total Acc Users`,`Website Code`,`Website Name`,W.`Website Key`,`Website URL`,
    `Website GSC Clicks`,`Website GSC Impressions`,`Website GSC CTR`,`Website GSC Position`,`Website Number Online Webpages`,`Website Status`,`Website Number Offline Webpages`,`Website Number In Process Webpages`
";

