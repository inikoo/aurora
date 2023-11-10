<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 oct 2023 17:40 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';





$agent = $state['_object'];
$agent->load_acc_data();


$smarty->assign(
    'quarter_data', array(
        array(
            'header'                      => get_quarter_label(
                strtotime('now')
            ),
            'invoiced_amount_delta_title' => delta($agent->get('Agent Quarter To Day Acc Invoiced Amount'), $agent->get('Agent Quarter To Day Acc 1YB Invoiced Amount')),
            'invoiced_amount_delta'       => ($agent->get('Agent Quarter To Day Acc Invoiced Amount') > $agent->get('Agent Quarter To Day Acc 1YB Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get(
                    'Agent Quarter To Day Acc Invoiced Amount'
                ) < $agent->get(
                    'Agent Quarter To Day Acc 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent Quarter To Day Acc Dispatched'), $agent->get('Agent Quarter To Day Acc 1YB Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent Quarter To Day Acc Dispatched') > $agent->get('Agent Quarter To Day Acc 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get('Agent Quarter To Day Acc Dispatched') < $agent->get(
                    'Agent Quarter To Day Acc 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -3 months')
            ),
            'invoiced_amount_delta_title' => delta($agent->get('Agent 1 Quarter Ago Invoiced Amount'), $agent->get('Agent 1 Quarter Ago 1YB Invoiced Amount')
            ), 'invoiced_amount_delta'       => ($agent->get('Agent 1 Quarter Ago Invoiced Amount') > $agent->get('Agent 1 Quarter Ago 1YB Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get('Agent 1 Quarter Ago Invoiced Amount') < $agent->get(
                    'Agent 1 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 1 Quarter Ago Dispatched'), $agent->get('Agent 1 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 1 Quarter Ago Dispatched') > $agent->get('Agent 1 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 1 Quarter Ago Dispatched') < $agent->get(
                    'Agent 1 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -6 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $agent->get('Agent 2 Quarter Ago Invoiced Amount'), $agent->get('Agent 2 Quarter Ago 1YB Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 2 Quarter Ago Invoiced Amount') > $agent->get(
                'Agent 2 Quarter Ago 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get('Agent 2 Quarter Ago Invoiced Amount') < $agent->get(
                    'Agent 2 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 2 Quarter Ago Dispatched'), $agent->get('Agent 2 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 2 Quarter Ago Dispatched') > $agent->get('Agent 2 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 2 Quarter Ago Dispatched') < $agent->get(
                    'Agent 2 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -9 months')
            ),
            'invoiced_amount_delta_title' =>  delta($agent->get('Agent 3 Quarter Ago Invoiced Amount'), $agent->get('Agent 3 Quarter Ago 1YB Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 3 Quarter Ago Invoiced Amount') > $agent->get(
                'Agent 3 Quarter Ago 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get('Agent 3 Quarter Ago Invoiced Amount') < $agent->get(
                    'Agent 3 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 3 Quarter Ago Dispatched'), $agent->get('Agent 3 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 3 Quarter Ago Dispatched') > $agent->get('Agent 3 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 3 Quarter Ago Dispatched') < $agent->get(
                    'Agent 3 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => get_quarter_label(
                strtotime('now -12 months')
            ),
            'invoiced_amount_delta_title' => delta(
                $agent->get('Agent 4 Quarter Ago Invoiced Amount'), $agent->get(
                'Agent 4 Quarter Ago 1YB Invoiced Amount'
            )
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 4 Quarter Ago Invoiced Amount') > $agent->get(
                'Agent 4 Quarter Ago 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get('Agent 4 Quarter Ago Invoiced Amount') < $agent->get(
                    'Agent 4 Quarter Ago 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 4 Quarter Ago Dispatched'), $agent->get('Agent 4 Quarter Ago 1YB Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 4 Quarter Ago Dispatched') > $agent->get('Agent 4 Quarter Ago 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 4 Quarter Ago Dispatched') < $agent->get(
                    'Agent 4 Quarter Ago 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
    )
);
$smarty->assign(
    'year_data', array(
        array(
            'header'                      => date('Y', strtotime('now')),
            'invoiced_amount_delta_title' => delta(
                $agent->get(
                    'Agent Year To Day Acc Invoiced Amount'
                ), $agent->get('Agent Year To Day Acc 1YB Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get(
                'Agent Year To Day Acc Invoiced Amount'
            ) > $agent->get(
                'Agent Year To Day Acc 1YB Invoiced Amount'
            )
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get(
                    'Agent Year To Day Acc Invoiced Amount'
                ) < $agent->get(
                    'Agent Year To Day Acc 1YB Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent Year To Day Acc Dispatched'), $agent->get('Agent Year To Day Acc 1YB Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent Year To Day Acc Dispatched') > $agent->get('Agent Year To Day Acc 1YB Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                : ($agent->get('Agent Year To Day Acc Dispatched') < $agent->get(
                    'Agent Year To Day Acc 1YB Dispatched'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -1 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $agent->get('Agent 1 Year Ago Invoiced Amount'), $agent->get('Agent 2 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 1 Year Ago Invoiced Amount') > $agent->get('Agent 2 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 1 Year Ago Invoiced Amount') < $agent->get(
                    'Agent 2 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 1 Year Ago Dispatched'), $agent->get('Agent 2 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 1 Year Ago Dispatched') > $agent->get('Agent 2 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 1 Year Ago Dispatched') < $agent->get('Agent 2 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -2 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $agent->get('Agent 2 Year Ago Invoiced Amount'), $agent->get('Agent 3 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 2 Year Ago Invoiced Amount') > $agent->get('Agent 3 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 2 Year Ago Invoiced Amount') < $agent->get(
                    'Agent 3 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 2 Year Ago Dispatched'), $agent->get('Agent 3 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 2 Year Ago Dispatched') > $agent->get('Agent 3 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 2 Year Ago Dispatched') < $agent->get('Agent 3 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -3 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $agent->get('Agent 3 Year Ago Invoiced Amount'), $agent->get('Agent 4 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 3 Year Ago Invoiced Amoun') > $agent->get('Agent 4 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 3 Year Ago Invoiced Amount') < $agent->get(
                    'Agent 4 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 3 Year Ago Dispatched'), $agent->get('Agent 4 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 3 Year Ago Invoiced Amoun') > $agent->get('Agent 4 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 3 Year Ago Dispatched') < $agent->get('Agent 4 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

        ),
        array(
            'header'                      => date(
                'Y', strtotime('now -4 year')
            ),
            'invoiced_amount_delta_title' => delta(
                $agent->get('Agent 4 Year Ago Invoiced Amount'), $agent->get('Agent 5 Year Ago Invoiced Amount')
            ),
            'invoiced_amount_delta'       => ($agent->get('Agent 4 Year Ago Invoiced Amount') > $agent->get('Agent 5 Year Ago Invoiced Amount')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 4 Year Ago Invoiced Amount') < $agent->get(
                    'Agent 5 Year Ago Invoiced Amount'
                ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
            'dispatched_delta_title'      => delta(
                $agent->get('Agent 4 Year Ago Dispatched'), $agent->get('Agent 5 Year Ago Dispatched')
            ),
            'dispatched_delta'            => ($agent->get('Agent 4 Year Ago Dispatched') > $agent->get('Agent 5 Year Ago Dispatched')
                ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($agent->get('Agent 4 Year Ago Dispatched') < $agent->get('Agent 5 Year Ago Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
        )
    )
);

$customers = sprintf(
    '<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s', $agent->get('Total Acc Customers')
);

$smarty->assign('agent', $agent);
$smarty->assign('customers', $customers);
$smarty->assign(
    'header_total_sales', sprintf(_('All sales since: %s'), $agent->get('Valid From'))
);


$html = $smarty->fetch('dashboard/agent.sales.tpl');



