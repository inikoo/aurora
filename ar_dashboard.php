<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 21:45:48 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
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
    case 'pending_orders':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'   => array('type' => 'string'),
                         'currency' => array('type' => 'currency'),


                     )
        );
        pending_orders($data, $db, $user, $account);
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


function pending_orders($data, $db, $user, $account) {


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
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Basket Amount Minify') : $object->get('Orders In Basket Amount Minify')),
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

        'Orders_In_Warehouse_Number' => array('value' => $object->get('Orders In Warehouse Number')),
        'Orders_In_Warehouse_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Warehouse Amount Minify') : $object->get('Orders In Warehouse Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Warehouse Amount') : $object->get('Orders In Warehouse Amount'))

        ),

        'Orders_Packed_Number' => array('value' => $object->get('Orders Packed Number')),
        'Orders_Packed_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders Packed Amount Minify') : $object->get('Orders Packed Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders Packed Amount') : $object->get('Orders Packed Amount'))
        ),

        'Orders_In_Dispatch_Area_Number' => array('value' => $object->get('Orders In Dispatch Area Number')),
        'Orders_In_Dispatch_Area_Amount' => array(
            'value' => ($data['currency'] == 'account' ? $object->get('DC Orders In Dispatch Area Amount Minify') : $object->get('Orders In Dispatch Area Amount Minify')),
            'title' => ($data['currency'] == 'account' ? $object->get('DC Orders In Dispatch Area Amount') : $object->get('Orders In Dispatch Area Amount'))
        ),

    );


    $response = array(
        'state' => 200,
        'title' => $title,
        'data'  => $pending_orders_data,
    );

    echo json_encode($response);

}

function sales_overview($_data, $db, $user, $account) {


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
        $request = 'invoices/all';

        $fields = "
		`Invoice Category $period_tag Acc Refunds` as refunds,
		`Invoice Category $period_tag Acc Invoices` as invoices,
		`Invoice Category $period_tag Acc Amount` as sales,
		 `Invoice Category DC $period_tag Acc Amount` as dc_sales,
        0 delivery_notes,
        0 delivery_notes_1yb,

        0 replacements,
        0 replacements_1yb,
                        ";


        if ($period_tag == '3 Year' or $period_tag == 'All') {

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
            "select  concat('cat',C.`Category Key`) record_key, C.`Category Key`,`Category Store Key`,`Store Currency Code` currency, $fields from `Invoice Category Dimension` IC left join `Invoice Category Data` ICD on (IC.`Invoice Category Key`=ICD.`Invoice Category Key`)  left join `Invoice Category DC Data` ICSCD on (IC.`Invoice Category Key`=ICSCD.`Invoice Category Key`)  left join `Category Dimension` C on (C.`Category Key`=IC.`Invoice Category Key`) left join `Store Dimension` S on (S.`Store Key`=C.`Category Store Key`) order by C.`Category Store Key` ,`Category Function Order`";


    } else {
        $request = 'invoices';
        $fields  = "
			`Store Orders In Basket Number`,`Store Orders In Basket Amount`,`Store DC Orders In Basket Amount`,
	`Store Orders In Process Paid Number`,`Store Orders In Process Paid Amount`,`Store DC Orders In Process Paid Amount`,
	`Store Orders In Process Not Paid Number`,`Store Orders In Process Not Paid Amount`,`Store DC Orders In Process Not Paid Amount`,

	`Store Orders In Warehouse Number`,`Store Orders In Warehouse Amount`,`Store DC Orders In Warehouse Amount`,
	`Store Orders Packed Number`,`Store Orders Packed Amount`,`Store DC Orders Packed Amount`,
	`Store Orders In Dispatch Area Number`,`Store Orders In Dispatch Area Amount`,`Store DC Orders In Dispatch Area Amount`,

		`Store Code`,S.`Store Key` record_key ,`Store Name`, `Store Currency Code` currency, `Store $period_tag Acc Invoices` as invoices,`Store $period_tag Acc Refunds` as refunds,`Store $period_tag Acc Delivery Notes` delivery_notes,`Store $period_tag Acc Replacements` replacements,`Store $period_tag Acc Invoiced Amount` as sales,`Store DC $period_tag Acc Invoiced Amount` as dc_sales,";


        if (!($period_tag == '3 Year' or $period_tag == 'Total')) {
            $fields .= "`Store $period_tag Acc 1YB Refunds` as refunds_1yb,`Store $period_tag Acc 1YB Delivery Notes` delivery_notes_1yb,`Store $period_tag Acc 1YB Replacements` replacements_1yb, `Store $period_tag Acc 1YB Invoices` as invoices_1yb,`Store $period_tag Acc 1YB Invoiced Amount` as sales_1yb,`Store DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb";

        } else {
            $fields .= '0 as refunds_1yb, 0 as replacements_1yb,0 as delivery_notes_1yb, 0 as invoices_1yb, 0 as sales_1yb, 0 as dc_sales_1yb';
        }

        $sql = sprintf(
            "SELECT  %s FROM `Store Dimension` S LEFT JOIN `Store Data` SD ON (S.`Store Key`=SD.`Store Key`)LEFT JOIN `Store DC Data` DC ON (S.`Store Key`=DC.`Store Key`)", $fields
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
    $sum_packed                     = 0;
    $sum_packed_amount              = 0;
    $sum_in_dispatch_area           = 0;
    $sum_in_dispatch_area_amount    = 0;

    if ($result = $db->query($sql)) {

        foreach ($result as $row) {


            $sum_invoices += $row['invoices'];
            $sum_refunds += $row['refunds'];
            $sum_replacements += $row['replacements'];
            $sum_delivery_notes += $row['delivery_notes'];
            $sum_dc_sales += $row['dc_sales'];


            $sum_refunds_1yb += $row['refunds_1yb'];
            $sum_replacements_1yb += $row['replacements_1yb'];
            $sum_delivery_notes_1yb += $row['delivery_notes_1yb'];
            $sum_invoices_1yb += $row['invoices_1yb'];
            $sum_dc_sales_1yb += $row['dc_sales_1yb'];


            $sum_in_basket += $row['Store Orders In Basket Number'];
            $sum_in_basket_amount += $row['Store Orders In Basket Amount'];
            $sum_in_process_paid += $row['Store Orders In Process Paid Number'];
            $sum_in_process_amount_paid += $row['Store Orders In Process Paid Amount'];
            $sum_in_process_not_paid += $row['Store Orders In Process Not Paid Number'];
            $sum_in_process_amount_not_paid += $row['Store Orders In Process Not Paid Amount'];
            $sum_in_warehouse += $row['Store Orders In Warehouse Number'];
            $sum_in_warehouse_amount += $row['Store Orders In Warehouse Amount'];
            $sum_in_warehouse += $row['Store Orders Packed Number'];
            $sum_in_warehouse_amount += $row['Store Orders Packed Amount'];
            $sum_in_dispatch_area += $row['Store Orders In Dispatch Area Number'];
            $sum_in_dispatch_area_amount += $row['Store Orders In Dispatch Area Amount'];


            if ($_data['currency'] == 'store') {
                $data['orders_overview_sales_'.$row['record_key']]       = array(
                    'value' => money(
                        $row['sales'], $row['currency']
                    )
                );
                $data['orders_overview_sales_delta_'.$row['record_key']] = array(
                    'value' => delta(
                            $row['sales'], $row['sales_1yb']
                        ).' '.delta_icon($row['sales'], $row['sales_1yb']),
                    'title' => money(
                        $row['sales_1yb'], $row['currency']
                    )
                );


                $data['orders_overview_in_basket_amount_'.$row['record_key']]           = array(
                    'value' => money(
                        $row['Store Orders In Basket Amount'], $row['currency']
                    )
                );
                $data['orders_overview_in_process_paid_amount_'.$row['record_key']]     = array(
                    'value' => money(
                        $row['Store Orders In Process Paid Amount'], $row['currency']
                    )
                );
                $data['orders_overview_in_process_not_paid_amount_'.$row['record_key']] = array(
                    'value' => money(
                        $row['Store Orders In Process Not Paid Amount'], $row['currency']
                    )
                );
                $data['orders_overview_in_warehouse_amount_'.$row['record_key']]        = array(
                    'value' => money(
                        $row['Store Orders In Warehouse Amount'], $row['currency']
                    )
                );
                $data['orders_overview_packed_amount_'.$row['record_key']]              = array(
                    'value' => money(
                        $row['Store Orders Packed Amount'], $row['currency']
                    )
                );
                $data['orders_overview_in_dispatch_area_amount_'.$row['record_key']]    = array(
                    'value' => money(
                        $row['Store Orders In Dispatch Area Amount'], $row['currency']
                    )
                );


            } else {
                $data['orders_overview_sales_'.$row['record_key']]       = array(
                    'value' => money(
                        $row['dc_sales'], $account->get('Account Currency')
                    )
                );
                $data['orders_overview_sales_delta_'.$row['record_key']] = array(
                    'value' => delta(
                            $row['dc_sales'], $row['dc_sales_1yb']
                        ).' '.delta_icon($row['dc_sales'], $row['dc_sales_1yb']),
                    'title' => money(
                        $row['dc_sales_1yb'], $account->get(
                        'Account Currency'
                    )
                    )
                );


                $data['orders_overview_in_basket_amount_'.$row['record_key']]           = array(
                    'value' => money(
                        $row['Store DC Orders In Basket Amount'], $account->get('Account Currency')
                    )
                );
                $data['orders_overview_in_process_paid_amount_'.$row['record_key']]     = array(
                    'value' => money(
                        $row['Store DC Orders In Process Paid Amount'], $account->get('Account Currency')
                    )
                );
                $data['orders_overview_in_process_not_paid_amount_'.$row['record_key']] = array(
                    'value' => money(
                        $row['Store DC Orders In Process Not Paid Amount'], $account->get('Account Currency')
                    )
                );
                $data['orders_overview_in_warehouse_amount_'.$row['record_key']]        = array(
                    'value' => money(
                        $row['Store DC Orders In Warehouse Amount'], $account->get('Account Currency')
                    )
                );
                $data['orders_overview_packed_amount_'.$row['record_key']]              = array(
                    'value' => money(
                        $row['Store DC Orders Packed Amount'], $account->get('Account Currency')
                    )
                );
                $data['orders_overview_in_dispatch_area_amount_'.$row['record_key']]    = array(
                    'value' => money(
                        $row['Store DC Orders In Dispatch Area Amount'], $account->get('Account Currency')
                    )
                );

            }

            if ($_data['type'] == 'invoice_categories') {
                $data['orders_overview_invoices_'.$row['record_key']] = array(
                    'special_type' => 'invoice',
                    'value'        => number(
                        $row['invoices']
                    ),
                    'request'      => "invoices/all/category/".$row['Category Key']
                );
                $data['orders_overview_refunds_'.$row['record_key']]  = array(
                    'special_type' => 'refund',
                    'value'        => number(
                        $row['refunds']
                    ),
                    'request'      => "invoices/all/category/".$row['Category Key']
                );


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
                        $row['delivery_notes'], $row['delivery_notes_1yb']
                    ).' '.delta_icon(
                        $row['delivery_notes'], $row['delivery_notes_1yb']
                    ),
                'title' => number(
                    $row['delivery_notes_1yb']
                )
            );


            $data['orders_overview_refunds_delta_'.$row['record_key']] = array(
                'value' => delta($row['refunds'], $row['refunds_1yb']).' '.delta_icon($row['refunds'], $row['refunds_1yb']),
                'title' => number($row['refunds_1yb'])
            );


            $data['orders_overview_replacements_'.$row['record_key']]                = array(
                'value'   => number(
                    $row['replacements']
                ),
                'request' => 'delivery_notes/'.$row['record_key']
            );
            $data['orders_overview_replacements_delta_'.$row['record_key']]          = array(
                'value' => delta(
                        $row['replacements'], $row['replacements_1yb']
                    ).' '.delta_icon(
                        $row['replacements'], $row['replacements_1yb']
                    ),
                'title' => number(
                    $row['replacements_1yb']
                )
            );
            $data['orders_overview_replacements_percentage_'.$row['record_key']]     = array(
                'value' => percentage(
                    $row['replacements'], $row['delivery_notes']
                )
            );
            $data['orders_overview_replacements_percentage_1yb_'.$row['record_key']] = array(
                'value' => percentage(
                    $row['replacements_1yb'], $row['delivery_notes_1yb']
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
                'in_dispatch_area'=>array('value'=>number($row['Store Orders In Dispatch Area Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
                'in_dispatch_area_amount'=>array('value'=>($currency=='store'?money($row['Store Orders In Dispatch Area Amount'], $row['currency']):money($row['Store DC Orders In Dispatch Area Amount'], $account->get('Account Currency'))))  ,
*/


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $data['orders_overview_sales_totals']       = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_dc_sales, $account->get('Account Currency')
            )
        ));
    $data['orders_overview_sales_delta_totals'] = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => delta(
                    $sum_dc_sales, $sum_dc_sales_1yb
                ).' '.delta_icon($sum_dc_sales, $sum_dc_sales_1yb),
            'title' => money(
                $sum_dc_sales_1yb, $account->get('Account Currency')
            )
        ));


    $data['orders_overview_invoices_totals']       = array(
        'value' => number(
            $sum_invoices
        )
    );
    $data['orders_overview_invoices_delta_totals'] = array(
        'value' => delta(
                $sum_invoices, $sum_invoices_1yb
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
        'value' => delta(
                $sum_refunds, $sum_refunds_1yb
            ).' '.delta_icon($sum_refunds, $sum_refunds_1yb),
        'title' => number(
            $sum_refunds_1yb
        )
    );


    $data['orders_overview_delivery_notes_totals']       = array(
        'value' => number(
            $sum_delivery_notes
        )
    );
    $data['orders_overview_delivery_notes_delta_totals'] = array(
        'value' => delta(
                $sum_delivery_notes, $sum_delivery_notes_1yb
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
        'value' => delta(
                $sum_replacements, $sum_replacements_1yb
            ).' '.delta_icon($sum_replacements, $sum_replacements_1yb),
        'title' => number(
            $sum_replacements_1yb
        )
    );
    $data['orders_overview_replacements_percentage_totals']     = array(
        'value' => percentage(
            $sum_replacements, $sum_delivery_notes
        )
    );
    $data['orders_overview_replacements_percentage_1yb_totals'] = array(
        'value' => percentage(
            $sum_replacements_1yb, $sum_delivery_notes_1yb
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
                $sum_in_basket_amount, $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_process_paid_amount_totals']     = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_process_amount_paid, $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_process_not_paid_amount_totals'] = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_process_amount_not_paid, $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_warehouse_amount_totals']        = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_warehouse_amount, $account->get('Account Currency')
            )
        ));
    $data['orders_overview_packed_amount_totals']              = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_packed_amount, $account->get('Account Currency')
            )
        ));
    $data['orders_overview_in_dispatch_area_amount_totals']    = ($currency == 'store'
        ? array('value' => '')
        : array(
            'value' => money(
                $sum_in_dispatch_area_amount, $account->get('Account Currency')
            )
        ));


    $response = array(
        'state'        => 200,
        'period_label' => get_interval_label($_data['period']),
        'data'         => $data,
    );

    echo json_encode($response);
}


?>
