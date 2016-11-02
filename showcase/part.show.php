<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2016 at 23:50:31 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_part_showcase($data, $smarty, $user, $db) {


    $part = $data['_object'];
    $part->load_acc_data();
    //$part->update_available_forecast();

    // $part->update_cost();
    // $part->updated_linked_products();

    //$part->discontinue_trigger();

    //$part->fix_stock_transactions();

    //	$part->update_stock_in_paid_orders();

    if (!$part->id) {
        return "";
    }


    $smarty->assign(
        'quarter_data', array(
            array(
                'header'                      => get_quarter_label(
                    strtotime('now')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part Quarter To Day Acc Invoiced Amount'), $part->get('Part Quarter To Day Acc 1YB Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part Quarter To Day Acc Invoiced Amount') > $part->get(
                    'Part Quarter To Day Acc 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($part->get('Part Quarter To Day Acc Invoiced Amount') < $part->get(
                        'Part Quarter To Day Acc 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part Quarter To Day Acc Dispatched'), $part->get('Part Quarter To Day Acc 1YB Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part Quarter To Day Acc Dispatched') > $part->get('Part Quarter To Day Acc 1YB Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part Quarter To Day Acc Dispatched') < $part->get(
                        'Part Quarter To Day Acc 1YB Dispatched'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                      => get_quarter_label(
                    strtotime('now -3 months')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 1 Quarter Ago Invoiced Amount'), $part->get('Part 1 Quarter Ago 1YB Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 1 Quarter Ago Invoiced Amount') > $part->get('Part 1 Quarter Ago 1YB Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 1 Quarter Ago Invoiced Amount') < $part->get(
                        'Part 1 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 1 Quarter Ago Dispatched'), $part->get('Part 1 Quarter Ago 1YB Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 1 Quarter Ago Dispatched') > $part->get(
                    'Part 1 Quarter Ago 1YB Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 1 Quarter Ago Dispatched') < $part->get('Part 1 Quarter Ago 1YB Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                      => get_quarter_label(
                    strtotime('now -6 months')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 2 Quarter Ago Invoiced Amount'), $part->get('Part 2 Quarter Ago 1YB Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 2 Quarter Ago Invoiced Amount') > $part->get('Part 2 Quarter Ago 1YB Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 2 Quarter Ago Invoiced Amount') < $part->get(
                        'Part 2 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 2 Quarter Ago Dispatched'), $part->get('Part 2 Quarter Ago 1YB Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 2 Quarter Ago Dispatched') > $part->get(
                    'Part 2 Quarter Ago 1YB Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 2 Quarter Ago Dispatched') < $part->get('Part 2 Quarter Ago 1YB Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                      => get_quarter_label(
                    strtotime('now -9 months')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 3 Quarter Ago Invoiced Amount'), $part->get('Part 3 Quarter Ago 1YB Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 3 Quarter Ago Invoiced Amount') > $part->get('Part 3 Quarter Ago 1YB Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 3 Quarter Ago Invoiced Amount') < $part->get(
                        'Part 3 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 3 Quarter Ago Dispatched'), $part->get('Part 3 Quarter Ago 1YB Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 3 Quarter Ago Dispatched') > $part->get(
                    'Part 3 Quarter Ago 1YB Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 3 Quarter Ago Dispatched') < $part->get('Part 3 Quarter Ago 1YB Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
            array(
                'header'                      => get_quarter_label(
                    strtotime('now -12 months')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 4 Quarter Ago Invoiced Amount'), $part->get('Part 4 Quarter Ago 1YB Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 4 Quarter Ago Invoiced Amount') > $part->get('Part 4 Quarter Ago 1YB Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 4 Quarter Ago Invoiced Amount') < $part->get(
                        'Part 4 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 4 Quarter Ago Dispatched'), $part->get('Part 4 Quarter Ago 1YB Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 4 Quarter Ago Dispatched') > $part->get(
                    'Part 4 Quarter Ago 1YB Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 4 Quarter Ago Dispatched') < $part->get('Part 4 Quarter Ago 1YB Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
        )
    );
    $smarty->assign(
        'year_data', array(
            array(
                'header'                      => date('Y', strtotime('now')),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part Year To Day Acc Invoiced Amount'), $part->get('Part Year To Day Acc 1YB Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part Year To Day Acc Invoiced Amount') > $part->get('Part Year To Day Acc 1YB Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($part->get('Part Year To Day Acc Invoiced Amount') < $part->get(
                        'Part Year To Day Acc 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part Year To Day Acc Dispatched'), $part->get('Part Year To Day Acc 1YB Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part Year To Day Acc Dispatched') > $part->get('Part Year To Day Acc 1YB Dispatched')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part Year To Day Acc Dispatched') < $part->get('Part Year To Day Acc 1YB Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                      => date(
                    'Y', strtotime('now -1 year')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 1 Year Ago Invoiced Amount'), $part->get('Part 2 Year Ago Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 1 Year Ago Invoiced Amount') > $part->get('Part 2 Year Ago Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 1 Year Ago Invoiced Amount') < $part->get('Part 2 Year Ago Invoiced Amount')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 1 Year Ago Dispatched'), $part->get('Part 2 Year Ago Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 1 Year Ago Dispatched') > $part->get(
                    'Part 2 Year Ago Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 1 Year Ago Dispatched') < $part->get('Part 2 Year Ago Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
            array(
                'header'                      => date(
                    'Y', strtotime('now -2 year')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 2 Year Ago Invoiced Amount'), $part->get('Part 3 Year Ago Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 2 Year Ago Invoiced Amount') > $part->get('Part 3 Year Ago Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 2 Year Ago Invoiced Amount') < $part->get('Part 3 Year Ago Invoiced Amount')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 2 Year Ago Dispatched'), $part->get('Part 3 Year Ago Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 2 Year Ago Dispatched') > $part->get(
                    'Part 3 Year Ago Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 2 Year Ago Dispatched') < $part->get('Part 3 Year Ago Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
            array(
                'header'                      => date(
                    'Y', strtotime('now -3 year')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 3 Year Ago Invoiced Amount'), $part->get('Part 4 Year Ago Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 3 Year Ago Invoiced Amoun') > $part->get(
                    'Part 4 Year Ago Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 3 Year Ago Invoiced Amount') < $part->get('Part 4 Year Ago Invoiced Amount')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 3 Year Ago Dispatched'), $part->get('Part 4 Year Ago Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 3 Year Ago Invoiced Amoun') > $part->get(
                    'Part 4 Year Ago Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 3 Year Ago Dispatched') < $part->get('Part 4 Year Ago Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                      => date(
                    'Y', strtotime('now -4 year')
                ),
                'invoiced_amount_delta_title' => delta(
                    $part->get('Part 4 Year Ago Invoiced Amount'), $part->get('Part 5 Year Ago Invoiced Amount')
                ),
                'invoiced_amount_delta'       => ($part->get('Part 4 Year Ago Invoiced Amount') > $part->get('Part 5 Year Ago Invoiced Amount')
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 4 Year Ago Invoiced Amount') < $part->get('Part 5 Year Ago Invoiced Amount')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'dispatched_delta_title'      => delta(
                    $part->get('Part 4 Year Ago Dispatched'), $part->get('Part 5 Year Ago Dispatched')
                ),
                'dispatched_delta'            => ($part->get('Part 4 Year Ago Dispatched') > $part->get(
                    'Part 5 Year Ago Dispatched'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 4 Year Ago Dispatched') < $part->get('Part 5 Year Ago Dispatched')
                        ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            )
        )
    );
    $customers = sprintf(
        '<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s (%s)', $part->get('Total Acc Customers'), percentage(
            $part->get('Part Total Acc Repeat Customers'), $part->get('Part Total Acc Customers')
        )
    );
    $smarty->assign('customers', $customers);

    $smarty->assign(
        'header_total_sales', sprintf(_('All sales since: %s'), $part->get('Valid From'))
    );


    $smarty->assign('part', $part);


    return $smarty->fetch('showcase/part.tpl');


}


?>
