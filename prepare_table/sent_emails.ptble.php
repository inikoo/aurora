<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 12:01:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


if ($parameters['parent'] == 'prospect') {


    $table  = '`Email Tracking Dimension`  left join `Published Email Template Dimension` S on (`Email Tracking Published Email Template Key`=`Published Email Template Key`) ';
    $fields = "`Email Tracking Delivery Status Code`,`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`";

} else {


    $table  = '`Email Tracking Dimension`  left join `Email Campaign Type Dimension`  on (`Email Tracking Email Template Type Key`=`Email Campaign Type Key`)  
    left join `Published Email Template Dimension` on (`Email Tracking Published Email Template Key`=`Published Email Template Key`)
    left join `Customer Dimension` on (`Email Tracking Recipient Key`=`Customer Key`) ';
    $fields =
        "`Email Tracking Delivery Status Code`,`Email Tracking Email`,`Customer Store Key` as store_key,`Customer Key` as recipient_key,`Customer Name` as recipient_name,`Email Campaign Type Code`,`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`";

}


switch ($parameters['parent']) {
    case('prospect'):
        $where = sprintf(
            ' where `Email Tracking Recipient`="Prospect"  and  `Email Tracking Recipient Key`=%d', $parameters['parent_key']
        );
        break;
    case('customer'):
        $where = sprintf(
            ' where `Email Tracking Recipient`="Customer"  and  `Email Tracking Recipient Key`=%d', $parameters['parent_key']
        );
        break;
    case 'email_campaign':
    case 'mailshot':
        if ($email_campaign_type->get('Code') == 'Invite Full Mailshot') {

            $table  = '`Email Tracking Dimension`  left join `Email Campaign Type Dimension`  on (`Email Tracking Email Template Type Key`=`Email Campaign Type Key`)  
    left join `Published Email Template Dimension` on (`Email Tracking Published Email Template Key`=`Published Email Template Key`)
    left join `Prospect Dimension` on (`Email Tracking Recipient Key`=`Prospect Key`) ';


            $fields =
                "`Email Tracking Delivery Status Code`,`Email Tracking Email`,`Prospect Store Key` as store_key,`Prospect Key` as recipient_key,`Prospect Name` as recipient_name,`Email Campaign Type Code`,`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`";


        }
        $where = sprintf(
            ' where   `Email Tracking Email Mailshot Key`=%d', $parameters['parent_key']
        );

        break;
    case 'email_campaign_type':

        $email_campaign_type = get_object('email_campaign_type', $parameters['parent_key']);
        if ($email_campaign_type->get('Code') == 'Invite' or $email_campaign_type->get('Code') == 'Invite Mailshot') {

            $table  = '`Email Tracking Dimension`  left join `Email Campaign Type Dimension`  on (`Email Tracking Email Template Type Key`=`Email Campaign Type Key`)  
    left join `Published Email Template Dimension` on (`Email Tracking Published Email Template Key`=`Published Email Template Key`)
    left join `Prospect Dimension` on (`Email Tracking Recipient Key`=`Prospect Key`) ';
            $fields =
                "`Email Tracking Delivery Status Code`,`Email Tracking Email`,`Prospect Store Key` as store_key,`Prospect Key` as recipient_key,`Prospect Name` recipient_name,`Prospect Store Key` store_key,`Email Campaign Type Code`,`Published Email Template Subject`,`Email Tracking Key`,`Email Tracking State`,`Email Tracking Created Date`";


        }

        $where = sprintf(
            ' where   `Email Tracking Email Template Type Key`=%d', $parameters['parent_key']
        );
        break;
    default:
        $where = 'where false';
}


if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Email Tracking Created Date`');
    $where          .= $where_interval['mysql'];

}


if (isset($parameters['elements_type'])) {


    switch ($parameters['elements_type']) {


        case('state'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['state']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    if ($_key == 'Bounced') {
                        $_elements = ",'Hard Bounce','Soft Bounce'";
                    } elseif ($_key == 'Sending') {
                        $_elements = ",'Ready','Sent to SES','Sent'";
                    } else {
                        $_elements .= ", '$_key'";

                    }


                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 7) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where     .= ' and `Email Tracking State` in ('.$_elements.')';
            }
            break;

    }
}

//print $where;

$wheref = '';
if ($parameters['f_field'] == 'subject' and $f_value != '') {
    $wheref = sprintf(
        ' and `Published Email Template Subject` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'subject') {
    $order = '`Published Email Template Subject`';
} elseif ($order == 'date') {
    $order = '`Email Tracking Created Date`';
} elseif ($order == 'state') {
    $order = '`Email Tracking State`';
} else {
    $order = '`Email Tracking Created Date`';
}


$sql_totals = "select count(*) as num from $table $where ";



