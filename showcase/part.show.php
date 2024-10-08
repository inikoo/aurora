<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2016 at 23:50:31 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_part_showcase($data, $smarty, $account) {

    $account->load_acc_data();

    $part = $data['_object'];
    $part->load_acc_data();


    //$part->update_weight_status();
    // $part->update_cost();


    // $part->update_available_forecast();
    //   $part->update_stock_status();

    //   foreach($part->get_locations('part_location_object') as $pl) {
    //       $pl->update_stock();
    //   }

    //$part->update_stock_run();
    //$part->update_stock();
    //  $part->validate_barcode();

    //$part->update_available_forecast();

    // $part->update_cost();
    // $part->updated_linked_products();

    //$part->discontinue_trigger();

    //$part->fix_stock_transactions();

    //	$part->update_stock_in_paid_orders();

    //  $part->update_next_deliveries_data();

    
    //$part->update_stock_run();

    //$part->update_stock_status();


    if (!$part->id) {
        return "";
    }

    $main_supplier_part = get_object('Supplier_Part', $part->get('Part Main Supplier Part Key'));
    $smarty->assign('main_supplier_part', $main_supplier_part);


    $labels_data['unit'] = json_decode($part->properties('label_unit'), true);
    $labels_data['sko']  = json_decode($part->properties('label_sko'), true);


    if ($part->get('Part Number Supplier Parts') == 1) {
        $supplier_part         = get_object('SupplierPart', $part->get('Part Main Supplier Part Key'));
        $labels_data['carton'] = json_decode($supplier_part->properties('label_carton'), true);
        if ($labels_data['carton'] == '') {
            $labels_data['carton'] = json_decode($account->properties('part_label_carton'), true);
        }
    } else {
        $labels_data['carton'] = json_decode($account->properties('part_label_carton'), true);

    }

    if ($labels_data['unit'] == '') {
        $labels_data['unit'] = json_decode($account->properties('part_label_unit'), true);
    }
    if ($labels_data['sko'] == '') {
        $labels_data['sko'] = json_decode($account->properties('part_label_sko'), true);
    }


    $smarty->assign('labels_data', $labels_data);


    $a4_labels_options = array(
        array(
            'code'        => 'EL30',
            'description' => 'EL30 (3x10) '._('No bleed')
        ),
        array(
            'code'        => '30UP',
            'description' => '30UP (3x10)'
        ),
        array(
            'code'        => 'EP40sp',
            'description' => 'EP40sp L(5x8) '._('No bleed, with padding')
        ),
        array(
            'code'        => '5x15',
            'description' => '5x15 '._('No bleed')
        ),
        array(
            'code'        => '6x18',
            'description' => '6x18  '._('No bleed')
        ),


    );


    $smarty->assign('a4_labels_options', $a4_labels_options);


    $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
    $smarty->assign('warehouse_unknown_location_key', $warehouse->get('Warehouse Unknown Location Key'));

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
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($part->get('Part Quarter To Day Acc Dispatched') < $part->get(
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
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($part->get('Part 1 Quarter Ago Invoiced Amount') < $part->get(
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
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($part->get('Part 2 Quarter Ago Invoiced Amount') < $part->get(
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
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($part->get('Part 3 Quarter Ago Invoiced Amount') < $part->get(
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
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($part->get('Part 4 Quarter Ago Invoiced Amount') < $part->get(
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
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($part->get(
                                   'Part Year To Day Acc Dispatched'
                               ) < $part->get('Part Year To Day Acc 1YB Dispatched') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -1 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $part->get('Part 1 Year Ago Invoiced Amount'), $part->get('Part 2 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($part->get('Part 1 Year Ago Invoiced Amount') > $part->get('Part 2 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($part->get(
                                   'Part 1 Year Ago Invoiced Amount'
                               ) < $part->get('Part 2 Year Ago Invoiced Amount') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'dispatched_delta_title'      => delta(
                               $part->get('Part 1 Year Ago Dispatched'), $part->get('Part 2 Year Ago Dispatched')
                           ),
                           'dispatched_delta'            => ($part->get('Part 1 Year Ago Dispatched') > $part->get(
                               'Part 2 Year Ago Dispatched'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 1 Year Ago Dispatched') < $part->get('Part 2 Year Ago Dispatched') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>'
                                   : ''))
                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -2 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $part->get('Part 2 Year Ago Invoiced Amount'), $part->get('Part 3 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($part->get('Part 2 Year Ago Invoiced Amount') > $part->get('Part 3 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($part->get(
                                   'Part 2 Year Ago Invoiced Amount'
                               ) < $part->get('Part 3 Year Ago Invoiced Amount') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'dispatched_delta_title'      => delta(
                               $part->get('Part 2 Year Ago Dispatched'), $part->get('Part 3 Year Ago Dispatched')
                           ),
                           'dispatched_delta'            => ($part->get('Part 2 Year Ago Dispatched') > $part->get(
                               'Part 3 Year Ago Dispatched'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 2 Year Ago Dispatched') < $part->get('Part 3 Year Ago Dispatched') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>'
                                   : ''))
                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -3 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $part->get('Part 3 Year Ago Invoiced Amount'), $part->get('Part 4 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($part->get('Part 3 Year Ago Invoiced Amount') > $part->get(
                               'Part 4 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 3 Year Ago Invoiced Amount') < $part->get('Part 4 Year Ago Invoiced Amount')
                                   ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'dispatched_delta_title'      => delta(
                               $part->get('Part 3 Year Ago Dispatched'), $part->get('Part 4 Year Ago Dispatched')
                           ),
                           'dispatched_delta'            => ($part->get('Part 3 Year Ago Invoiced Amount') > $part->get(
                               'Part 4 Year Ago Dispatched'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 3 Year Ago Dispatched') < $part->get('Part 4 Year Ago Dispatched') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>'
                                   : ''))

                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -4 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $part->get('Part 4 Year Ago Invoiced Amount'), $part->get('Part 5 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($part->get('Part 4 Year Ago Invoiced Amount') > $part->get('Part 5 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($part->get(
                                   'Part 4 Year Ago Invoiced Amount'
                               ) < $part->get('Part 5 Year Ago Invoiced Amount') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'dispatched_delta_title'      => delta(
                               $part->get('Part 4 Year Ago Dispatched'), $part->get('Part 5 Year Ago Dispatched')
                           ),
                           'dispatched_delta'            => ($part->get('Part 4 Year Ago Dispatched') > $part->get(
                               'Part 5 Year Ago Dispatched'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>' : ($part->get('Part 4 Year Ago Dispatched') < $part->get('Part 5 Year Ago Dispatched') ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>'
                                   : ''))
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



