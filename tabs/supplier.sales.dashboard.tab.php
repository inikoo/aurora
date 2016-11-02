<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 October 2016 at 14:49:01 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';


$supplier = $state['_object'];
$supplier->load_acc_data();


$smarty->assign(
    'quarter_data', array(
        array(
            'header'                      => get_quarter_label(
                strtotime('now')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get(
                    'Supplier Quarter To Day Acc Parts Invoiced Amount'
                ), $supplier->get(
                'Supplier Quarter To Day Acc 1Yb Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($supplier->get(
                'Supplier Quarter To Day Acc Parts Invoiced Amount'
            ) > $supplier->get(
                'Supplier Quarter To Day Acc 1Yb Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get(
                    'Supplier Quarter To Day Acc Parts Invoiced Amount'
                ) < $supplier->get(
                    'Supplier Quarter To Day Acc 1Yb Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier Quarter To Day Acc Dispatched'), $supplier->get('Supplier Quarter To Day Acc 1YB Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier Quarter To Day Acc Dispatched') > $supplier->get('Supplier Quarter To Day Acc 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get('Supplier Quarter To Day Acc Dispatched') < $supplier->get(
                    'Supplier Quarter To Day Acc 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -3 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 1 Quarter Ago Invoiced Amount'), $supplier->get(
                'Supplier 1 Quarter Ago 1YB Parts Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 1 Quarter Ago Invoiced Amount') > $supplier->get(
                'Supplier 1 Quarter Ago 1YB Parts Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get('Supplier 1 Quarter Ago Invoiced Amount') < $supplier->get(
                    'Supplier 1 Quarter Ago 1YB Parts Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 1 Quarter Ago Dispatched'), $supplier->get('Supplier 1 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 1 Quarter Ago Dispatched') > $supplier->get('Supplier 1 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 1 Quarter Ago Dispatched') < $supplier->get(
                    'Supplier 1 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -6 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 2 Quarter Ago Invoiced Amount'), $supplier->get(
                'Supplier 2 Quarter Ago 1YB Parts Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 2 Quarter Ago Invoiced Amount') > $supplier->get(
                'Supplier 2 Quarter Ago 1YB Parts Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get('Supplier 2 Quarter Ago Invoiced Amount') < $supplier->get(
                    'Supplier 2 Quarter Ago 1YB Parts Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 2 Quarter Ago Dispatched'), $supplier->get('Supplier 2 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 2 Quarter Ago Dispatched') > $supplier->get('Supplier 2 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 2 Quarter Ago Dispatched') < $supplier->get(
                    'Supplier 2 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -9 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 3 Quarter Ago Invoiced Amount'), $supplier->get(
                'Supplier 3 Quarter Ago 1YB Parts Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 3 Quarter Ago Invoiced Amount') > $supplier->get(
                'Supplier 3 Quarter Ago 1YB Parts Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get('Supplier 3 Quarter Ago Invoiced Amount') < $supplier->get(
                    'Supplier 3 Quarter Ago 1YB Parts Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 3 Quarter Ago Dispatched'), $supplier->get('Supplier 3 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 3 Quarter Ago Dispatched') > $supplier->get('Supplier 3 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 3 Quarter Ago Dispatched') < $supplier->get(
                    'Supplier 3 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -12 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 4 Quarter Ago Invoiced Amount'), $supplier->get(
                'Supplier 4 Quarter Ago 1YB Parts Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 4 Quarter Ago Invoiced Amount') > $supplier->get(
                'Supplier 4 Quarter Ago 1YB Parts Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get('Supplier 4 Quarter Ago Invoiced Amount') < $supplier->get(
                    'Supplier 4 Quarter Ago 1YB Parts Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 4 Quarter Ago Dispatched'), $supplier->get('Supplier 4 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 4 Quarter Ago Dispatched') > $supplier->get('Supplier 4 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 4 Quarter Ago Dispatched') < $supplier->get(
                    'Supplier 4 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
    )
);
$smarty->assign(
    'year_data', array(
        array(
            'header'                      => date('Y', strtotime('now')),
            'invoiced_amount_delta_title' => delta(
                $supplier->get(
                    'Supplier Year To Day Acc Parts Invoiced Amount'
                ), $supplier->get('Supplier Year To Day Acc 1Yb Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($supplier->get(
                'Supplier Year To Day Acc Parts Invoiced Amount'
            ) > $supplier->get(
                'Supplier Year To Day Acc 1Yb Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get(
                    'Supplier Year To Day Acc Parts Invoiced Amount'
                ) < $supplier->get(
                    'Supplier Year To Day Acc 1Yb Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier Year To Day Acc Dispatched'), $supplier->get('Supplier Year To Day Acc 1YB Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier Year To Day Acc Dispatched') > $supplier->get('Supplier Year To Day Acc 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($supplier->get('Supplier Year To Day Acc Dispatched') < $supplier->get(
                    'Supplier Year To Day Acc 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -1 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 1 Year Ago Invoiced Amount'), $supplier->get('Supplier 2 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 1 Year Ago Invoiced Amount') > $supplier->get('Supplier 2 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 1 Year Ago Invoiced Amount') < $supplier->get(
                    'Supplier 2 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 1 Year Ago Dispatched'), $supplier->get('Supplier 2 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 1 Year Ago Dispatched') > $supplier->get('Supplier 2 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 1 Year Ago Dispatched') < $supplier->get('Supplier 2 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -2 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 2 Year Ago Invoiced Amount'), $supplier->get('Supplier 3 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 2 Year Ago Invoiced Amount') > $supplier->get('Supplier 3 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 2 Year Ago Invoiced Amount') < $supplier->get(
                    'Supplier 3 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 2 Year Ago Dispatched'), $supplier->get('Supplier 3 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 2 Year Ago Dispatched') > $supplier->get('Supplier 3 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 2 Year Ago Dispatched') < $supplier->get('Supplier 3 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -3 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 3 Year Ago Invoiced Amount'), $supplier->get('Supplier 4 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 3 Year Ago Invoiced Amoun') > $supplier->get('Supplier 4 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 3 Year Ago Invoiced Amount') < $supplier->get(
                    'Supplier 4 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 3 Year Ago Dispatched'), $supplier->get('Supplier 4 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 3 Year Ago Invoiced Amoun') > $supplier->get('Supplier 4 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 3 Year Ago Dispatched') < $supplier->get('Supplier 4 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -4 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $supplier->get('Supplier 4 Year Ago Invoiced Amount'), $supplier->get('Supplier 5 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($supplier->get('Supplier 4 Year Ago Invoiced Amount') > $supplier->get('Supplier 5 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 4 Year Ago Invoiced Amount') < $supplier->get(
                    'Supplier 5 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $supplier->get('Supplier 4 Year Ago Dispatched'), $supplier->get('Supplier 5 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($supplier->get('Supplier 4 Year Ago Dispatched') > $supplier->get('Supplier 5 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($supplier->get('Supplier 4 Year Ago Dispatched') < $supplier->get('Supplier 5 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        )
    )
);

$customers = sprintf(
    '<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s', $supplier->get('Total Acc Customers')
);

$smarty->assign('supplier', $supplier);
$smarty->assign('customers', $customers);
$smarty->assign(
    'header_total_sales', sprintf(_('All sales since: %s'), $supplier->get('Valid From'))
);


$html = $smarty->fetch('dashboard/supplier.sales.tpl');


?>
