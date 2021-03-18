<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4:07 pm Saturday, 13 February 2021 (MYT) Time in Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/


function get_dashboard_accounts_overview($db, $redis,$account, $user, $smarty, $type, $sub_type, $period, $currency, $orders_view_type, $display_device_version = 'desktop') {

    include_once 'utils/date_functions.php';

    list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($db, $period);


    $smarty->assign('type', $type);
    $smarty->assign('currency', $currency);
    $smarty->assign('orders_view_type', $orders_view_type);
    $smarty->assign('period', $period);
    $smarty->assign('subtype', $sub_type);


    $sales_overview = array();
    $period_tag     = get_interval_db_name($period);


    $sum_invoices           = 0;
    $sum_delivery_notes     = 0;
    $sum_refunds            = 0;
    $sum_invoices_1yb       = 0;
    $sum_dc_sales           = 0;
    $sum_dc_sales_1yb       = 0;
    $sum_refunds_1yb        = 0;
    $sum_replacements       = 0;
    $sum_replacements_1yb   = 0;
    $sum_delivery_notes_1yb = 0;

    $sum_in_basket                  = 0;
    $sum_in_basket_amount           = 0;
    $sum_in_process_paid            = 0;
    $sum_in_process_amount_paid     = 0;
    $sum_in_process_not_paid        = 0;
    $sum_in_process_amount_not_paid = 0;
    $sum_in_warehouse               = 0;
    $sum_in_warehouse_amount        = 0;
    $sum_packed                     = 0;
    $sum_packed_amount              = 0;
    $sum_in_dispatch_area           = 0;
    $sum_in_dispatch_area_amount    = 0;


    $corporate_accounts = $user->settings('corporate_accounts');
    if (is_array($corporate_accounts)) {
        foreach ($corporate_accounts as $account_code) {

            $key = '_acc_Sales_'.$account_code;

            print "$db_interval ";

            $sales_data=json_decode($redis->hGet(
                $key, preg_replace('/\s/','_',$db_interval)
            ));

            print_r($sales_data);

        }

    }
    exit;

    $sales_overview[] = array(
        'id'               => 'totals',
        'class'            => 'totals',
        'label'            => array(
            'label'       => _('Total'),
            'short_label' => _('Total')
        ),
        'in_basket'        => array('value' => number($sum_in_basket)),
        'in_basket_amount' => array(
            'value' => ($currency == 'store' ? '' : money($sum_in_basket_amount, $account->get('Account Currency')))
        ),

        'in_process_paid'            => array(
            'value' => number(
                $sum_in_process_paid
            )
        ),
        'in_process_amount_paid'     => array(
            'value' => ($currency == 'store'
                ? ''
                : money(
                    $sum_in_process_amount_paid, $account->get('Account Currency')
                ))
        ),
        'in_process_not_paid'        => array(
            'value' => number(
                $sum_in_process_not_paid
            )
        ),
        'in_process_amount_not_paid' => array(
            'value' => ($currency == 'store'
                ? ''
                : money(
                    $sum_in_process_amount_not_paid, $account->get('Account Currency')
                ))
        ),

        'in_warehouse'        => array('value' => number($sum_in_warehouse)),
        'in_warehouse_amount' => array(
            'value' => ($currency == 'store'
                ? ''
                : money(
                    $sum_in_warehouse_amount, $account->get('Account Currency')
                ))
        ),
        'packed'              => array('value' => number($sum_packed)),
        'packed_amount'       => array(
            'value' => ($currency == 'store' ? '' : money($sum_packed_amount, $account->get('Account Currency')))
        ),


        'in_dispatch_area'        => array(
            'value' => number(
                $sum_in_dispatch_area
            )
        ),
        'in_dispatch_area_amount' => array(
            'value' => ($currency == 'store'
                ? ''
                : money(
                    $sum_in_dispatch_area_amount, $account->get('Account Currency')
                ))
        ),

        'invoices'       => array('value' => number($sum_invoices)),
        'invoices_1yb'   => number($sum_invoices_1yb),
        'invoices_delta' => delta($sum_invoices, $sum_invoices_1yb).' '.delta_icon($sum_invoices, $sum_invoices_1yb),

        'delivery_notes'       => number($sum_delivery_notes),
        'delivery_notes_1yb'   => number($sum_delivery_notes_1yb),
        'delivery_notes_delta' => delta(
                $sum_delivery_notes, $sum_delivery_notes_1yb
            ).' '.delta_icon($sum_delivery_notes, $sum_delivery_notes_1yb),

        'refunds' => array('value' => number($sum_refunds)),

        'refunds_1yb'   => number($sum_refunds_1yb),
        'refunds_delta' => delta($sum_refunds, $sum_refunds_1yb).' '.delta_icon($sum_refunds, $sum_refunds_1yb, $inverse = true),

        'replacements'                => number($sum_replacements),
        'replacements_percentage'     => percentage(
            $sum_replacements, $sum_delivery_notes
        ),
        'replacements_delta'          => delta(
                $sum_replacements, $sum_replacements_1yb
            ).' '.delta_icon($sum_replacements, $sum_replacements_1yb, $inverse = true),
        'replacements_percentage_1yb' => percentage(
            $sum_replacements_1yb, $sum_delivery_notes_1yb
        ),
        'replacements_1yb'            => number($sum_replacements_1yb),

        'sales'       => ($currency == 'store'
            ? ''
            : money(
                $sum_dc_sales, $account->get('Account Currency')
            )),
        'sales_1yb'   => ($currency == 'store'
            ? ''
            : money(
                $sum_dc_sales_1yb, $account->get('Account Currency')
            )),
        'sales_delta' => ($currency == 'store'
                ? ''
                : delta(
                    $sum_dc_sales, $sum_dc_sales_1yb
                )).' '.delta_icon($sum_dc_sales, $sum_dc_sales_1yb)

    );

    // print_r($sales_overview);


    $smarty->assign('sales_overview', $sales_overview);
    $smarty->assign('interval_label', get_interval_label($period));

    //    print_r($sales_overview);


    switch ($type) {
        case 'invoices' :
            $report_title = _('Sales (Stores)');
            break;
        case 'invoice_categories' :
            $report_title = _('Sales (Categories)');
            break;
        default:
            $report_title = $type;
    }

    $smarty->assign('report_title', $report_title);


    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/sales_overview.mobile.dbard.tpl');
    } else {


        return $smarty->fetch('dashboard/sales_overview.dbard.tpl');
    }
}



