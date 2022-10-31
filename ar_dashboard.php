<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 21:45:48 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
include_once 'utils/date_functions.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'kpi':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'period'     => array('type' => 'period'),
                         'parent_key' => array('type' => 'key'),


                     )
        );
        kpi($data);
        break;
    case 'sales_overview':
        $data = prepare_values(
            $_REQUEST, array(
                         'type'             => array('type' => 'string'),
                         'subtype'          => array('type' => 'string'),
                         'period'           => array('type' => 'period'),
                         'currency'         => array('type' => 'currency'),
                         'orders_view_type' => array('type' => 'string'),


                     )
        );
        sales_overview($data, $db, $user, $account);
        break;
    case 'sales_per_staff':
        $data = prepare_values(
            $_REQUEST, array(

                         'period' => array('type' => 'period'),


                     )
        );
        sales_per_staff($data, $db, $user, $account);
        break;
    case 'pending_orders':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'   => array('type' => 'string'),
                         'currency' => array('type' => 'currency'),


                     )
        );
        pending_orders($data);
        break;

    case 'customers':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'   => array('type' => 'string'),
                         'currency' => array('type' => 'currency'),


                     )
        );
        customers($data);
        break;
    case 'dispatching_times':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent' => array('type' => 'string'),


                     )
        );
        dispatching_times($data, $account);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function pending_orders($data)
{
    $_SESSION['dashboard_state']['pending_orders'] = array(
        'parent'   => $data['parent'],
        'currency' => $data['currency'],

    );


    if ($data['parent'] != '') {
        include_once 'class.Store.php';

        $object = new Store($data['parent']);
        $object->load_acc_data();

        $title = $object->get('Code');
    } else {
        $object = new Account();
        $object->load_acc_data();
        $title = $object->get('Code');
    }


    $pending_orders_data = array(
        'Orders_In_Basket_Number' => array('value' => $object->get('Orders In Basket Number')),
        'Orders_In_Basket_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Basket Amount') : $object->get('Orders In Basket Amount')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Basket Amount') : $object->get('Orders In Basket Amount'))
        ),

        'Orders_In_Process_Not_Paid_Number' => array('value' => $object->get('Orders In Process Not Paid Number')),
        'Orders_In_Process_Paid_Number'     => array('value' => $object->get('Orders In Process Paid Number')),
        'Orders_In_Process_Not_Paid_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Process Not Paid Amount Minify') : $object->get('Orders In Process Not Paid Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Process Not Paid Amount') : $object->get('Orders In Process Not Paid Amount'))

        ),
        'Orders_In_Process_Paid_Amount'     => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Process Paid Amount Minify') : $object->get('Orders In Process Paid Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Process Paid Amount') : $object->get('Orders In Process Paid Amount'))
        ),

        'Orders_In_Warehouse_No_Alerts_Number'   => array('value' => $object->get('Orders In Warehouse No Alerts Number')),
        'Orders_In_Warehouse_No_Alerts_Amount'   => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Warehouse No Alerts Amount Minify') : $object->get('Orders In Warehouse No Alerts Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Warehouse No Alerts Amount') : $object->get('Orders In Warehouse No Alerts Amount'))

        ),
        'Orders_In_Warehouse_With_Alerts_Number' => array('value' => $object->get('Orders In Warehouse With Alerts Number')),
        'Orders_In_Warehouse_With_Alerts_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Warehouse With Alerts Amount Minify') : $object->get('Orders In Warehouse With Alerts Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Warehouse With Alerts Amount') : $object->get('Orders In Warehouse With Alerts Amount'))

        ),


        'Orders_Packed_Number' => array('value' => $object->get('Orders Packed Number')),
        'Orders_Packed_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders Packed Amount Minify') : $object->get('Orders Packed Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders Packed Amount') : $object->get('Orders Packed Amount'))
        ),

        'Orders_Dispatch_Approved_Number' => array('value' => $object->get('Orders Dispatch Approved Number')),
        'Orders_Dispatch_Approved_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders Dispatch Approved Amount Minify') : $object->get('Orders Dispatch Approved Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders Dispatch Approved Amount') : $object->get('Orders Dispatch Approved Amount'))
        ),

        'Delta_Today_Start_Orders_In_Warehouse_Number' => array('value' => $object->get('Delta Today Start Orders In Warehouse Number')),
        'Today_Orders_Dispatched'                      => array('value' => $object->get('Today Orders Dispatched'))


    );


    //  print_r($pending_orders_data);

    $response = array(
        'state' => 200,
        'title' => $title,
        'data'  => $pending_orders_data,
    );

    echo json_encode($response);
}


function customers($data)
{
    $_SESSION['dashboard_state']['customers'] = array(
        'parent'   => $data['parent'],
        'currency' => $data['currency'],

    );


    if ($data['parent'] != '') {
        $object = get_object('Store', $data['parent']);
        $object->load_acc_data();

        $title = $object->get('Code');
    } else {
        $object = get_object('Account', 1);
        $object->load_acc_data();
        $title = $object->get('Code');
    }


    $customers_data = array(
        'Contacts'                            => array('value' => $object->get('Contacts')),
        'New_Contacts'                        => array('value' => $object->get('New Contacts')),
        'Contacts_With_Orders'                => array('value' => $object->get('Contacts With Orders')),
        'Active_Contacts'                     => array('value' => $object->get('Active Contacts')),
        'Losing_Contacts'                     => array('value' => $object->get('Losing Contacts')),
        'Lost_Contacts'                       => array('value' => $object->get('Lost Contacts')),
        'Percentage_Active_Contacts'          => array('value' => $object->get('Percentage Active Contacts')),
        'Percentage_Contacts_With_Order'      => array('value' => $object->get('Percentage Contacts With Orders')),
        'Percentage_New_Contacts_With_Orders' => array('value' => $object->get('Percentage New Contacts With Orders')),

        'New_Contacts_With_Orders' => array('value' => $object->get('New Contacts With Orders')),


    );


    $response = array(
        'state' => 200,
        'title' => $title,
        'data'  => $customers_data,
    );

    echo json_encode($response);
}


function mailshots_sent($_data, $db, $user, $account)
{
    $data = [];

    $_SESSION['dashboard_state']['sales_overview'] = array(
        'type'             => $_data['type'],
        'subtype'          => $_data['subtype'],
        'period'           => $_data['period'],
        'currency'         => $_data['currency'],
        'orders_view_type' => $_data['orders_view_type'],

    );
    $period_tag                                    = get_interval_db_name($_data['period']);

    $fields = "
    `Store Code`,S.`Store Key` record_key ,`Store Name`,
    `Store $period_tag Acc Newsletter Mailshots` as newsletters,
    `Store $period_tag Acc Newsletter Emails` as newsletters_emails ,
     `Store $period_tag Acc Marketing Mailshots` as marketing,
    `Store $period_tag Acc Marketing Emails` as marketing_emails ,
     `Store $period_tag Acc AbandonedCart Mailshots` as abandonedCart,
    `Store $period_tag Acc AbandonedCart Emails` as abandonedCart_emails,
      `Store $period_tag Acc Mailshots` as mailshots,
    `Store $period_tag Acc Emails` as emails ,`Store Key`
    ";

    $sql = sprintf(
        "SELECT  %s FROM `Store Dimension` S LEFT JOIN `Store Emails Data` SD ON (S.`Store Key`=SD.`Store Emails Store Key`)  where `Store Status` in ('Normal','ClosingDown') and `Store Type` in ('B2B','Dropshipping') ",
        $fields
    );
    // print "$sql\n";
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $data['mailshots_sent_newsletters_email_'.$row['Store Key']] =
                [
                    'value' => number($row['newsletters'])
                ];

            $data['mailshots_sent_marketing_email_'.$row['Store Key']]       = [
                'value' => number($row['marketing'])
            ];
            $data['mailshots_sent_abandoned_carts_email_'.$row['Store Key']] = [
                'value' => number($row['abandonedCart'])
            ];

            $data['mailshots_sent_total_mailshots_email_'.$row['Store Key']] =
                ['value' => number($row['mailshots'])];
            $data['mailshots_sent_total_emails_email_'.$row['Store Key']]    =
                ['value' => number($row['emails'])];
        }
    }

    $response = array(
        'state'        => 200,
        'period_label' => get_interval_label($_data['period']),
        'data'         => $data,
    );

    echo json_encode($response);
}


function sales_overview($_data, $db, $user, $account)
{
    if ($_data['type'] == 'mailshots_sent') {
        return mailshots_sent($_data, $db, $user, $account);
    }


    $_SESSION['dashboard_state']['sales_overview'] = array(
        'type'             => $_data['type'],
        'subtype'          => $_data['subtype'],
        'period'           => $_data['period'],
        'currency'         => $_data['currency'],
        'orders_view_type' => $_data['orders_view_type'],

    );

    $currency   = $_data['currency'];
    $period_tag = get_interval_db_name($_data['period']);


    $data = array();

    if ($_data['type'] == 'invoice_categories') {
        $fields = "
		`Invoice Category $period_tag Acc Refunds` as refunds,
		`Invoice Category $period_tag Acc Invoices` as invoices,
		`Invoice Category $period_tag Acc Amount` as sales,
		 `Invoice Category DC $period_tag Acc Amount` as dc_sales,
        0 delivery_notes,
        0 delivery_notes_1yb,

        0 replacements,
        0 replacements_1yb,
        
        0 `Store Orders In Basket Number`,
        
        0 ` Store Orders In Basket Amount`,
         0 `Store Orders In Process Paid Number`,
          0 `Store Orders In Process Paid Amount`,
            0 `Store Orders In Process Not Paid Number`,
         0 `Store Orders In Process Not Paid Amount`,
          0 `Store Orders In Warehouse Number`,
             0 `Store Orders In Warehouse Amount`,
           0 `Store Orders Packed Number`,
            0 `Store Orders Packed Amount`,
             0 `Store Orders Dispatch Approved Number`,
              0 `Store Orders Dispatch Approved Amount`,
               0 `Store Orders In Warehouse Amount`,
                0 `Store Orders Packed Amount`,
                
             0   `Store DC Orders In Basket Amount`,
              0   `Store DC Orders In Process Paid Amount`,
               0   ` Store DC Orders In Process Not Paid Amount`,
                0   `Store DC Orders In Warehouse Amount`,
                 0   `Store DC Orders Packed Amount`,
                  0   `Store DC Orders Dispatch Approved Amount `,
                   0   `Store DC Orders Dispatch Approved Amount`
          
        ,
                        ";


        if ($period_tag == '3 Year' or $period_tag == 'All' or $period_tag == 'Total') {
            $fields .= "
	    0 as refunds_1yb,

	    0 as invoices_1yb,
	    0 as sales_1yb,
        0 as dc_sales_1yb
                        ";
        } else {
            $fields .= "
		`Invoice Category $period_tag Acc 1YB Refunds` as refunds_1yb,

	    `Invoice Category $period_tag Acc 1YB Invoices` as invoices_1yb,
	    `Invoice Category $period_tag Acc 1YB Amount` as sales_1yb,
        `Invoice Category DC $period_tag Acc 1YB Amount` as dc_sales_1yb
                        ";
        }
        $sql =
            "select concat('cat',C.`Category Key`) record_key, `Category Code`, C.`Category Key`,`Category Store Key`,`Store Currency Code` currency, $fields from `Invoice Category Dimension` IC left join `Invoice Category Data` ICD on (IC.`Invoice Category Key`=ICD.`Invoice Category Key`)  left join `Invoice Category DC Data` ICSCD on (IC.`Invoice Category Key`=ICSCD.`Invoice Category Key`)  left join `Category Dimension` C on (C.`Category Key`=IC.`Invoice Category Key`) left join `Store Dimension` S on (S.`Store Key`=C.`Category Store Key`) 
where  `Category Branch Type`='Head' and `Invoice Category Status` in ('Normal','ClosingDown')  and `Invoice Category Hide Dashboard`!='Yes'  order by C.`Category Store Key` ,`Category Function Order`";
    } else {
        //  $request = 'invoices';
        $fields = "
			`Store Orders In Basket Number`,`Store Orders In Basket Amount`,`Store DC Orders In Basket Amount`,
	`Store Orders In Process Paid Number`,`Store Orders In Process Paid Amount`,`Store DC Orders In Process Paid Amount`,
	`Store Orders In Process Not Paid Number`,`Store Orders In Process Not Paid Amount`,`Store DC Orders In Process Not Paid Amount`,

	`Store Orders In Warehouse Number`,`Store Orders In Warehouse Amount`,`Store DC Orders In Warehouse Amount`,
	`Store Orders Packed Number`,`Store Orders Packed Amount`,`Store DC Orders Packed Amount`,
	`Store Orders Dispatch Approved Number`,`Store Orders Dispatch Approved Amount`,`Store DC Orders Dispatch Approved Amount`,

		`Store Code`,S.`Store Key` record_key ,`Store Name`, `Store Currency Code` currency, `Store $period_tag Acc Invoices` as invoices,`Store $period_tag Acc Refunds` as refunds,`Store $period_tag Acc Delivery Notes` delivery_notes,`Store $period_tag Acc Replacements` replacements,`Store $period_tag Acc Invoiced Amount` as sales,`Store DC $period_tag Acc Invoiced Amount` as dc_sales,";


        if (!($period_tag == '3 Year' or $period_tag == 'Total')) {
            $fields .= "`Store $period_tag Acc 1YB Refunds` as refunds_1yb,`Store $period_tag Acc 1YB Delivery Notes` delivery_notes_1yb,`Store $period_tag Acc 1YB Replacements` replacements_1yb, `Store $period_tag Acc 1YB Invoices` as invoices_1yb,`Store $period_tag Acc 1YB Invoiced Amount` as sales_1yb,`Store DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb";
        } else {
            $fields .= '0 as refunds_1yb, 0 as replacements_1yb,0 as delivery_notes_1yb, 0 as invoices_1yb, 0 as sales_1yb, 0 as dc_sales_1yb';
        }

        $sql = sprintf(
            "SELECT  %s FROM `Store Dimension` S LEFT JOIN `Store Data` SD ON (S.`Store Key`=SD.`Store Key`)LEFT JOIN `Store DC Data` DC ON (S.`Store Key`=DC.`Store Key`)  where `Store Status` in ('Normal','ClosingDown') and `Store Hide Dashboard`='No' ",
            $fields
        );
    }


    $sum_invoices = 0;
    $sum_refunds  = 0;

    $sum_invoices_1yb = 0;
    $sum_dc_sales     = 0;
    $sum_dc_sales_1yb = 0;

    $sum_refunds_1yb = 0;

    $sum_delivery_notes     = 0;
    $sum_delivery_notes_1yb = 0;
    $sum_replacements       = 0;
    $sum_replacements_1yb   = 0;

    $sum_in_basket                  = 0;
    $sum_in_basket_amount           = 0;
    $sum_in_process_paid            = 0;
    $sum_in_process_amount_paid     = 0;
    $sum_in_process_not_paid        = 0;
    $sum_in_process_amount_not_paid = 0;
    $sum_in_warehouse               = 0;
    $sum_in_warehouse_amount        = 0;
    $sum_packed_amount              = 0;
    $sum_in_dispatch_area           = 0;
    $sum_in_dispatch_area_amount    = 0;

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $sum_invoices       += $row['invoices'];
            $sum_refunds        += $row['refunds'];
            $sum_replacements   += $row['replacements'];
            $sum_delivery_notes += $row['delivery_notes'];
            $sum_dc_sales       += $row['dc_sales'];


            // print " $sum_dc_sales ";

            $sum_refunds_1yb        += $row['refunds_1yb'];
            $sum_replacements_1yb   += $row['replacements_1yb'];
            $sum_delivery_notes_1yb += $row['delivery_notes_1yb'];
            $sum_invoices_1yb       += $row['invoices_1yb'];
            $sum_dc_sales_1yb       += $row['dc_sales_1yb'];


            $sum_in_basket                  += $row['Store Orders In Basket Number'];
            $sum_in_basket_amount           += $row['Store Orders In Basket Amount'];
            $sum_in_process_paid            += $row['Store Orders In Process Paid Number'];
            $sum_in_process_amount_paid     += $row['Store Orders In Process Paid Amount'];
            $sum_in_process_not_paid        += $row['Store Orders In Process Not Paid Number'];
            $sum_in_process_amount_not_paid += $row['Store Orders In Process Not Paid Amount'];
            $sum_in_warehouse               += $row['Store Orders In Warehouse Number'];
            $sum_in_warehouse_amount        += $row['Store Orders In Warehouse Amount'];
            $sum_in_warehouse               += $row['Store Orders Packed Number'];
            $sum_in_warehouse_amount        += $row['Store Orders Packed Amount'];
            $sum_in_dispatch_area           += $row['Store Orders Dispatch Approved Number'];
            $sum_in_dispatch_area_amount    += $row['Store Orders Dispatch Approved Amount'];


            if ($_data['currency'] == 'store') {
                $data['orders_overview_sales_'.$row['record_key']]       = array(
                    'value' => money(
                        $row['sales'],
                        $row['currency']
                    )
                );
                $data['orders_overview_sales_delta_'.$row['record_key']] = array(
                    'value' => delta(
                            $row['sales'],
                            $row['sales_1yb']
                        ).' '.delta_icon($row['sales'], $row['sales_1yb']),
                    'title' => money(
                        $row['sales_1yb'],
                        $row['currency']
                    )
                );


                $data['orders_overview_in_basket_amount_'.$row['record_key']]           = array(
                    'value' => money(
                        $row['Store Orders In Basket Amount'],
                        $row['currency']
                    )
                );
                $data['orders_overview_in_process_paid_amount_'.$row['record_key']]     = array(
                    'value' => money(
                        $row['Store Orders In Process Paid Amount'],
                        $row['currency']
                    )
                );
                $data['orders_overview_in_process_not_paid_amount_'.$row['record_key']] = array(
                    'value' => money(
                        $row['Store Orders In Process Not Paid Amount'],
                        $row['currency']
                    )
                );
                $data['orders_overview_in_warehouse_amount_'.$row['record_key']]        = array(
                    'value' => money(
                        $row['Store Orders In Warehouse Amount'],
                        $row['currency']
                    )
                );
                $data['orders_overview_packed_amount_'.$row['record_key']]              = array(
                    'value' => money(
                        $row['Store Orders Packed Amount'],
                        $row['currency']
                    )
                );
                $data['orders_overview_in_dispatch_area_amount_'.$row['record_key']]    = array(
                    'value' => money(
                        $row['Store Orders Dispatch Approved Amount'],
                        $row['currency']
                    )
                );
            } else {
                $data['orders_overview_sales_'.$row['record_key']]       = array(
                    'value' => money(
                        $row['dc_sales'],
                        $account->get('Account Currency')
                    )
                );
                $data['orders_overview_sales_delta_'.$row['record_key']] = array(
                    'value' => delta(
                            $row['dc_sales'],
                            $row['dc_sales_1yb']
                        ).' '.delta_icon($row['dc_sales'], $row['dc_sales_1yb']),
                    'title' => money(
                        $row['dc_sales_1yb'],
                        $account->get(
                            'Account Currency'
                        )
                    )
                );


                $data['orders_overview_in_basket_amount_'.$row['record_key']]           = array(
                    'value' => money(
                        $row['Store DC Orders In Basket Amount'],
                        $account->get('Account Currency')
                    )
                );
                $data['orders_overview_in_process_paid_amount_'.$row['record_key']]     = array(
                    'value' => money($row['Store DC Orders In Process Paid Amount'], $account->get('Account Currency'))
                );
                $data['orders_overview_in_process_not_paid_amount_'.$row['record_key']] = array(
                    'value' => money($row['Store DC Orders In Process Not Paid Amount'], $account->get('Account Currency'))
                );
                $data['orders_overview_in_warehouse_amount_'.$row['record_key']]        = array(
                    'value' => money(
                        $row['Store DC Orders In Warehouse Amount'],
                        $account->get('Account Currency')
                    )
                );
                $data['orders_overview_packed_amount_'.$row['record_key']]              = array(
                    'value' => money(
                        $row['Store DC Orders Packed Amount'],
                        $account->get('Account Currency')
                    )
                );
                $data['orders_overview_in_dispatch_area_amount_'.$row['record_key']]    = array(
                    'value' => money(
                        $row['Store DC Orders Dispatch Approved Amount'],
                        $account->get('Account Currency')
                    )
                );
            }

            if ($_data['type'] == 'invoice_categories') {
                $data['orders_overview_invoices_'.$row['record_key']] = array(
                    'special_type' => 'invoice',
                    'value'        => number(
                        $row['invoices']
                    ),
                    'request'      => "invoices/category/".$row['Category Key']
                );
                $data['orders_overview_refunds_'.$row['record_key']]  = array(
                    'special_type' => 'refund',
                    'value'        => number(
                        $row['refunds']
                    ),
                    'request'      => "invoices/category/".$row['Category Key']
                );


                if ($row['Category Code'] == 'VIPs') {
                    $data['representatives_link_'.$row['record_key']] = array(

                        'value' => '<i onclick="change_view(\'report/sales_representatives\', { parameters:{ period:\''.$_data['period'].'\'}} )"  class="far button fa-chart-line fa-fw"></i>',
                    );
                }
            } else {
                $data['orders_overview_invoices_'.$row['record_key']] = array(
                    'special_type' => 'invoice',
                    'value'        => number(
                        $row['invoices']
                    ),
                    'request'      => "invoices/".$row['record_key']
                );
                $data['orders_overview_refunds_'.$row['record_key']]  = array(
                    'special_type' => 'refund',
                    'value'        => number(
                        $row['refunds']
                    ),
                    'request'      => "invoices/".$row['record_key']
                );
            }


            $data['orders_overview_invoices_delta_'.$row['record_key']] = array(
                'value' => delta($row['invoices'], $row['invoices_1yb']).' '.delta_icon($row['invoices'], $row['invoices_1yb']),
                'title' => number($row['invoices_1yb'])
            );


            $data['orders_overview_delivery_notes_'.$row['record_key']]       = array(
                'value'   => number(
                    $row['delivery_notes']
                ),
                'request' => 'delivery_notes/'.$row['record_key']
            );
            $data['orders_overview_delivery_notes_delta_'.$row['record_key']] = array(
                'value' => delta(
                        $row['delivery_notes'],
                        $row['delivery_notes_1yb']
                    ).' '.delta_icon(
                        $row['delivery_notes'],
                        $row['delivery_notes_1yb']
                    ),
                'title' => number(
                    $row['delivery_notes_1yb']
                )
            );


            $data['orders_overview_refunds_delta_'.$row['record_key']] = array(
                'value' => delta($row['refunds'], $row['refunds_1yb']).' '.delta_icon($row['refunds'], $row['refunds_1yb'], $inverse = true),
                'title' => number($row['refunds_1yb'])
            );


            $data['orders_overview_replacements_'.$row['record_key']]                = array(
                'value'   => number(
                    $row['replacements']
                ),
                'request' => 'delivery_notes/'.$row['record_key']
            );
            $data['orders_overview_replacements_delta_'.$row['record_key']]          = array(
                'value' => delta($row['replacements'], $row['replacements_1yb']).' '.delta_icon($row['replacements'], $row['replacements_1yb'], $inverse = true),
                'title' => number($row['replacements_1yb'])
            );
            $data['orders_overview_replacements_percentage_'.$row['record_key']]     = array(
                'value' => percentage(
                    $row['replacements'],
                    $row['delivery_notes']
                )
            );
            $data['orders_overview_replacements_percentage_1yb_'.$row['record_key']] = array(
                'value' => percentage(
                    $row['replacements_1yb'],
                    $row['delivery_notes_1yb']
                ),
                'title' => number(
                        $row['replacements_1yb']
                    ).'/'.number(
                        $row['delivery_notes_1yb']
                    )
            );
            /*
            'in_basket'=>array('value'=>number($row['Store Orders In Basket Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'in_basket_amount'=>array('value'=>($currency=='store'?money($row['Store Orders In Basket Amount'], $row['currency']):money($row['Store DC Orders In Basket Amount'], $account->get('Account Currency'))))  ,
                'in_process_paid'=>array('value'=>number($row['Store Orders In Process Paid Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'in_process_amount_paid'=>array('value'=>($currency=='store'?money($row['Store Orders In Process Paid Amount'], $row['currency']):money($row['Store DC Orders In Process Paid Amount'], $account->get('Account Currency'))))  ,
                'in_process_not_paid'=>array('value'=>number($row['Store Orders In Process Not Paid Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'in_process_amount_not_paid'=>array('value'=>($currency=='store'?money($row['Store Orders In Process Not Paid Amount'], $row['currency']):money($row['Store DC Orders In Process Not Paid Amount'], $account->get('Account Currency'))))  ,
                'in_warehouse'=>array('value'=>number($row['Store Orders In Warehouse Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'in_warehouse_amount'=>array('value'=>($currency=='store'?money($row['Store Orders In Warehouse Amount'], $row['currency']):money($row['Store DC Orders In Warehouse Amount'], $account->get('Account Currency'))))  ,
                'packed'=>array('value'=>number($row['Store Orders Packed Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'packed_amount'=>array('value'=>($currency=='store'?money($row['Store Orders Packed Amount'], $row['currency']):money($row['Store DC Orders Packed Amount'], $account->get('Account Currency'))))  ,
                'in_dispatch_area'=>array('value'=>number($row['Store Orders Dispatch Approved Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'in_dispatch_area_amount'=>array('value'=>($currency=='store'?money($row['Store Orders Dispatch Approved Amount'], $row['currency']):money($row['Store DC Orders Dispatch Approved Amount'], $account->get('Account Currency'))))  ,
*/
        }
    }


    $data['orders_overview_sales_totals']       = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_dc_sales,
                $account->get('Account Currency')
            )
        ));
    $data['orders_overview_sales_delta_totals'] = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => delta(
                    $sum_dc_sales,
                    $sum_dc_sales_1yb
                ).' '.delta_icon($sum_dc_sales, $sum_dc_sales_1yb),
            'title' => money(
                $sum_dc_sales_1yb,
                $account->get('Account Currency')
            )
        ));


    $data['orders_overview_invoices_totals']       = array(
        'value' => number(
            $sum_invoices
        )
    );
    $data['orders_overview_invoices_delta_totals'] = array(
        'value' => delta(
                $sum_invoices,
                $sum_invoices_1yb
            ).' '.delta_icon($sum_invoices, $sum_invoices_1yb),
        'title' => number(
            $sum_invoices_1yb
        )
    );


    $data['orders_overview_refunds_totals']       = array(
        'value' => number(
            $sum_refunds
        )
    );
    $data['orders_overview_refunds_delta_totals'] = array(
        'value' => delta($sum_refunds, $sum_refunds_1yb).' '.delta_icon($sum_refunds, $sum_refunds_1yb, $inverse = true),
        'title' => number($sum_refunds_1yb)
    );


    $data['orders_overview_delivery_notes_totals']       = array(
        'value' => number(
            $sum_delivery_notes
        )
    );
    $data['orders_overview_delivery_notes_delta_totals'] = array(
        'value' => delta(
                $sum_delivery_notes,
                $sum_delivery_notes_1yb
            ).' '.delta_icon($sum_delivery_notes, $sum_delivery_notes_1yb),
        'title' => number(
            $sum_delivery_notes_1yb
        )
    );

    $data['orders_overview_replacements_totals']                = array(
        'value' => number(
            $sum_replacements
        )
    );
    $data['orders_overview_replacements_delta_totals']          = array(
        'value' => delta($sum_replacements, $sum_replacements_1yb).' '.delta_icon($sum_replacements, $sum_replacements_1yb, $inverse = true),
        'title' => number(
            $sum_replacements_1yb
        )
    );
    $data['orders_overview_replacements_percentage_totals']     = array(
        'value' => percentage(
            $sum_replacements,
            $sum_delivery_notes
        )
    );
    $data['orders_overview_replacements_percentage_1yb_totals'] = array(
        'value' => percentage(
            $sum_replacements_1yb,
            $sum_delivery_notes_1yb
        ),
        'title' => number(
                $sum_replacements_1yb
            ).'/'.number(
                $sum_delivery_notes_1yb
            )
    );


    $data['orders_overview_in_basket_amount_totals']           = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_basket_amount,
                $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_process_paid_amount_totals']     = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_process_amount_paid,
                $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_process_not_paid_amount_totals'] = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_process_amount_not_paid,
                $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_warehouse_amount_totals']        = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_warehouse_amount,
                $account->get('Account Currency')
            )
        ));
    $data['orders_overview_packed_amount_totals']              = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_packed_amount,
                $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_dispatch_area_amount_totals']    = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_dispatch_area_amount,
                $account->get('Account Currency')
            )
        ));


    $response = array(
        'state'        => 200,
        'period_label' => get_interval_label($_data['period']),
        'data'         => $data,
    );

    echo json_encode($response);
}


function kpi($data)
{
    require_once 'utils/object_functions.php';


    $_SESSION['dashboard_state']['kpis'] = array('period' => $data['period']);

    $object = get_object($data['parent'], $data['parent_key']);


    $kpi_data = $object->get_kpi($data['period']);


    $response = array(
        'state' => 200,
        'kpi'   => $kpi_data,
    );

    echo json_encode($response);
}


function dispatching_times($data, $account)
{
    $_SESSION['dashboard_state']['dispatching_times'] = array(
        'parent' => $data['parent'],

    );

    $_data = array(
        'formatted_sitting_time_avg'  => array('value' => '-'),
        'sitting_time_samples'        => array('value' => '0'),
        'formatted_dispatch_time_avg' => array('value' => '-'),
        'dispatch_time_samples'       => array('value' => '0')
    );

    if ($data['parent'] != '') {
        $object = get_object('Store', $data['parent']);
        $object->load_acc_data();
    } else {
        $object = $account;
        $object->load_acc_data();
    }

    $_data['formatted_sitting_time_avg']['value'] = $object->get('formatted_sitting_time_avg');
    $_data['formatted_sitting_time_avg']['title'] = _('Average sitting time').': '.$object->get('formatted_bis_sitting_time_avg');
    $_data['sitting_time_samples']['value']       = $object->get('sitting_time_samples');

    $_data['formatted_dispatch_time_avg']['value'] = $object->get('formatted_dispatch_time_avg', '1 Month');
    $_data['formatted_dispatch_time_avg']['title'] = _('Average dispatch time (last 30 days)').': '.$object->get('formatted_bis_dispatch_time_avg', '1 Month');
    $_data['dispatch_time_samples']['value']       = $object->get('dispatch_time_samples', '1 Month');


    $_data['percentage_dispatch_time_day_0']['value'] = $object->get('percentage_dispatch_time_histogram', [0, '1 Month']);
    $_data['dispatch_time_day_0']['value']            = $object->get('dispatch_time_histogram', [0, '1 Month']);

    $_data['percentage_dispatch_time_day_1']['value'] = $object->get('percentage_dispatch_time_histogram', [1, '1 Month']);
    $_data['dispatch_time_day_1']['value']            = $object->get('dispatch_time_histogram', [1, '1 Month']);

    $_data['percentage_dispatch_time_day_2']['value'] = $object->get('percentage_dispatch_time_histogram', [2, '1 Month']);
    $_data['dispatch_time_day_2']['value']            = $object->get('dispatch_time_histogram', [2, '1 Month']);

    $_data['percentage_dispatch_time_day_3']['value'] = $object->get('percentage_dispatch_time_histogram', [3, '1 Month']);
    $_data['dispatch_time_day_3']['value']            = $object->get('dispatch_time_histogram', [3, '1 Month']);


    $response = array(
        'state' => 200,
        'data'  => $_data,
    );

    echo json_encode($response);
}


function sales_per_staff($data, $db, $user, $account)
{
    $account->load_acc_data();

    $period = $data['period'];

    $factor=0;
    switch ($period) {
        case 'ytd':
            $factor = 12 * 19.24 * (date('z')/365)  ;
            break;
        case '1m':
        case 'last_m':
            $factor = 19.24;
            break;
        case '1y':
            $factor = 12 * 19.24;
            break;
        case '1q':
            $factor = 12 * 19.24 /4;
            break;
        case '1w':
        case 'last_w':
            $factor = 12 * 19.24/52.1429;
            break;


    }


    if($factor==0){
        $factor=1;
    }

    $_SESSION['dashboard_state']['sales_per_staff']['period'] = $period;


    $adjust = 0;
    if (DNS_ACCOUNT_CODE == 'AROMA') {
        $adjust = 1;
    }


    $teams = [
        'Artisan'   => 0,
        'Sales'     => 0,
        'Support'   => 0,
        'Admin'     => $adjust,
        'Warehouse' => 0,
    ];

    $sql  = "select count(*) as num , `Staff Team` from `Staff Dimension` where `Staff Currently Working`='Yes' and `Staff Type`!='Contractor' group by `Staff Team`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $teams[$row['Staff Team']] += $row['num'];
    }


    $db_interval = get_interval_db_name($period);


    $number_staff = 0;

    $sql  = "select count(*) as num from `Staff Dimension` where `Staff Currently Working`='Yes' and `Staff Type`!='Contractor'   ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $number_staff = $row['num'] + $adjust; // this 1 represent the extra admin contractors
    }


    $sales_per_staff_title = money($account->get("Account $db_interval Acc Invoiced Amount"), $account->get('Currency Code')).' sales / '.$number_staff.' staff'.' / $factor days';
    $sales_per_staff       = money($account->get("Account $db_interval Acc Invoiced Amount") / $number_staff / $factor, $account->get('Currency Code')).'/wday';

    $sales_per_staff_data['sales_per_staff']['title'] = $sales_per_staff_title;
    $sales_per_staff_data['sales_per_staff']['value'] = $sales_per_staff;
    $sales_per_staff_data['number_staff']['value']    = $number_staff;


    $number_production_staff = $teams['Artisan'] + $teams['Support'];

    if ($number_production_staff == 0) {
        $produced_per_staff_title = '';
        $produced_per_staff       = 'NaN';
    } else {
        $produced_per_staff_title = money($account->get("Account $db_interval Acc Invoiced Amount"), $account->get('Currency Code')).' sales / '.$number_production_staff.' staff'.' / $factor days';
        $produced_per_staff       = money($account->get("Account $db_interval Acc Invoiced Amount") / $number_production_staff / $factor, $account->get('Currency Code')).'/wday';
    }


    $sales_per_staff_data['produced_per_staff']['title']      = $produced_per_staff_title;
    $sales_per_staff_data['produced_per_staff']['value']      = $produced_per_staff;
    $sales_per_staff_data['number_production_staff']['value'] = $number_production_staff;


    $number_warehouse_staff = $teams['Warehouse'];


    if ($number_warehouse_staff == 0) {
        $warehouse_per_staff_title = '';
        $warehouse_per_staff       = 'NaN';
    } else {
        $warehouse_per_staff_title = money($account->get("Account $db_interval Acc Invoiced Amount"), $account->get('Currency Code')).' sales / '.$number_warehouse_staff.' staff'.' / $factor days';
        $warehouse_per_staff       = money($account->get("Account $db_interval Acc Invoiced Amount") / $number_warehouse_staff / $factor, $account->get('Currency Code')).'/wday';
    }


    $sales_per_staff_data['sales_per_warehouse']['title']    = $warehouse_per_staff_title;
    $sales_per_staff_data['sales_per_warehouse']['value']    = $warehouse_per_staff;
    $sales_per_staff_data['number_warehouse_staff']['value'] = $number_warehouse_staff;

    $response = array(
        'state' => 200,
        'data'  => $sales_per_staff_data,
    );

    echo json_encode($response);
}