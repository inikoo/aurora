<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2018 at 10:03:28 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/
/** @var array $state */
/** @var \Smarty $smarty */

include_once 'utils/date_functions.php';

/** @var \Customer $customer */
$customer = $state['_object'];



$customer->load_previous_years_data();
$customer->load_previous_quarters_data();
$customer->load_sales_data('Year To Day');
$customer->load_sales_data('Quarter To Day');


$smarty->assign(
    'quarter_data', array(
        array(
            'header'                      => get_quarter_label(
                strtotime('now')
            ),
            'invoiced_amount_delta_title' => delta($customer->get('Customer Quarter To Day Acc Invoiced Amount'), $customer->get('Customer Quarter To Day Acc 1YB Invoiced Amount')),
            'invoiced_amount_delta'       => ($customer->get('Customer Quarter To Day Acc Invoiced Amount') > $customer->get('Customer Quarter To Day Acc 1YB Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer Quarter To Day Acc Invoiced Amount'
                ) < $customer->get(
                    'Customer Quarter To Day Acc 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer Quarter To Day Acc Invoices'), $customer->get('Customer Quarter To Day Acc 1YB Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer Quarter To Day Acc Invoices') > $customer->get('Customer Quarter To Day Acc 1YB Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer Quarter To Day Acc Invoices') < $customer->get(
                    'Customer Quarter To Day Acc 1YB Invoices'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -3 months')
            ),
            'invoiced_amount_delta_title' => delta($customer->get('Customer 1 Quarter Ago Invoiced Amount'), $customer->get('Customer 1 Quarter Ago 1YB Invoiced Amount')
            ), 'invoiced_amount_delta'       => ($customer->get('Customer 1 Quarter Ago Invoiced Amount') > $customer->get('Customer 1 Quarter Ago 1YB Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer 1 Quarter Ago Invoiced Amount') < $customer->get(
                    'Customer 1 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 1 Quarter Ago Invoices'), $customer->get('Customer 1 Quarter Ago 1YB Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 1 Quarter Ago Invoices') > $customer->get('Customer 1 Quarter Ago 1YB Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 1 Quarter Ago Invoices') < $customer->get(
                    'Customer 1 Quarter Ago 1YB Invoices'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(strtotime('now -6 months')),
            'invoiced_amount_delta_title' => delta(
                $customer->get('Customer 2 Quarter Ago Invoiced Amount'), $customer->get('Customer 2 Quarter Ago 1YB Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 2 Quarter Ago Invoiced Amount') > $customer->get(
                'Customer 2 Quarter Ago 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer 2 Quarter Ago Invoiced Amount') < $customer->get(
                    'Customer 2 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 2 Quarter Ago Invoices'), $customer->get('Customer 2 Quarter Ago 1YB Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 2 Quarter Ago Invoices') > $customer->get('Customer 2 Quarter Ago 1YB Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 2 Quarter Ago Invoices') < $customer->get(
                    'Customer 2 Quarter Ago 1YB Invoices'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -9 months')
            ),
            'invoiced_amount_delta_title' =>  delta($customer->get('Customer 3 Quarter Ago Invoiced Amount'), $customer->get('Customer 3 Quarter Ago 1YB Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 3 Quarter Ago Invoiced Amount') > $customer->get(
                'Customer 3 Quarter Ago 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer 3 Quarter Ago Invoiced Amount') < $customer->get(
                    'Customer 3 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 3 Quarter Ago Invoices'), $customer->get('Customer 3 Quarter Ago 1YB Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 3 Quarter Ago Invoices') > $customer->get('Customer 3 Quarter Ago 1YB Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 3 Quarter Ago Invoices') < $customer->get(
                    'Customer 3 Quarter Ago 1YB Invoices'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -12 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $customer->get('Customer 4 Quarter Ago Invoiced Amount'), $customer->get(
                'Customer 4 Quarter Ago 1YB Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 4 Quarter Ago Invoiced Amount') > $customer->get(
                'Customer 4 Quarter Ago 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer 4 Quarter Ago Invoiced Amount') < $customer->get(
                    'Customer 4 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 4 Quarter Ago Invoices'), $customer->get('Customer 4 Quarter Ago 1YB Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 4 Quarter Ago Invoices') > $customer->get('Customer 4 Quarter Ago 1YB Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 4 Quarter Ago Invoices') < $customer->get(
                    'Customer 4 Quarter Ago 1YB Invoices'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
    )
);
$smarty->assign(
    'year_data', array(
        array(
            'header'                      => date('Y', strtotime('now')),
            'invoiced_amount_delta_title' => delta(
                $customer->get(
                    'Customer Year To Day Acc Invoiced Amount'
                ), $customer->get('Customer Year To Day Acc 1YB Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get(
                'Customer Year To Day Acc Invoiced Amount'
            ) > $customer->get(
                'Customer Year To Day Acc 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get(
                    'Customer Year To Day Acc Invoiced Amount'
                ) < $customer->get(
                    'Customer Year To Day Acc 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer Year To Day Acc Invoices'), $customer->get('Customer Year To Day Acc 1YB Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer Year To Day Acc Invoices') > $customer->get('Customer Year To Day Acc 1YB Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($customer->get('Customer Year To Day Acc Invoices') < $customer->get(
                    'Customer Year To Day Acc 1YB Invoices'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -1 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $customer->get('Customer 1 Year Ago Invoiced Amount'), $customer->get('Customer 2 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 1 Year Ago Invoiced Amount') > $customer->get('Customer 2 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 1 Year Ago Invoiced Amount') < $customer->get(
                    'Customer 2 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 1 Year Ago Invoices'), $customer->get('Customer 2 Year Ago Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 1 Year Ago Invoices') > $customer->get('Customer 2 Year Ago Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 1 Year Ago Invoices') < $customer->get('Customer 2 Year Ago Invoices')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -2 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $customer->get('Customer 2 Year Ago Invoiced Amount'), $customer->get('Customer 3 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 2 Year Ago Invoiced Amount') > $customer->get('Customer 3 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 2 Year Ago Invoiced Amount') < $customer->get(
                    'Customer 3 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 2 Year Ago Invoices'), $customer->get('Customer 3 Year Ago Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 2 Year Ago Invoices') > $customer->get('Customer 3 Year Ago Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 2 Year Ago Invoices') < $customer->get('Customer 3 Year Ago Invoices')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -3 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $customer->get('Customer 3 Year Ago Invoiced Amount'), $customer->get('Customer 4 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 3 Year Ago Invoiced Amoun') > $customer->get('Customer 4 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 3 Year Ago Invoiced Amount') < $customer->get(
                    'Customer 4 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 3 Year Ago Invoices'), $customer->get('Customer 4 Year Ago Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 3 Year Ago Invoiced Amoun') > $customer->get('Customer 4 Year Ago Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 3 Year Ago Invoices') < $customer->get('Customer 4 Year Ago Invoices')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -4 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $customer->get('Customer 4 Year Ago Invoiced Amount'), $customer->get('Customer 5 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($customer->get('Customer 4 Year Ago Invoiced Amount') > $customer->get('Customer 5 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 4 Year Ago Invoiced Amount') < $customer->get(
                    'Customer 5 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'invoices_delta_title'      => delta(
                $customer->get('Customer 4 Year Ago Invoices'), $customer->get('Customer 5 Year Ago Invoices')
            ),
            'invoices_delta'            => ($customer->get('Customer 4 Year Ago Invoices') > $customer->get('Customer 5 Year Ago Invoices')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($customer->get('Customer 4 Year Ago Invoices') < $customer->get('Customer 5 Year Ago Invoices')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        )
    )
);


$customer->store=$state['store'];

$smarty->assign('customer', $customer);
$smarty->assign('header_total_sales', sprintf(_('Customer since: %s'), $customer->get('First Contacted Date')));


$html = $smarty->fetch('dashboard/customer.sales.tpl');


?>
