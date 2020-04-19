<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 January 2017 at 15:45:41 GMT, Sheffield, UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/

$account->load_acc_data();

// todo get this info from Account dimension
$warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);
$smarty->assign('warehouse', $warehouse);

$html = '';

$smarty->assign('account', $account);


$smarty->assign(
    'quarter_data', array(
                      array(
                          'header'                      => get_quarter_label(
                              strtotime('now')
                          ),
                          'invoiced_amount_delta_title' => delta(
                              $account->get('Account Quarter To Day Acc Invoiced Amount'), $account->get('Account Quarter To Day Acc 1YB Invoiced Amount')
                          ),
                          'invoiced_amount_delta'       => ($account->get('Account Quarter To Day Acc Invoiced Amount') > $account->get('Account Quarter To Day Acc 1YB Invoiced Amount')
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account Quarter To Day Acc Invoiced Amount'
                              ) < $account->get(
                                  'Account Quarter To Day Acc 1YB Invoiced Amount'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                          'dispatched_delta_title'      => delta(
                              $account->get(
                                  'Account Quarter To Day Acc Distinct Parts Dispatched'
                              ), $account->get(
                              'Account Quarter To Day Acc 1YB Distinct Parts Dispatched'
                          )
                          ),
                          'dispatched_delta'            => ($account->get(
                              'Account Quarter To Day Acc Distinct Parts Dispatched'
                          ) > $account->get(
                              'Account Quarter To Day Acc 1YB Distinct Parts Dispatched'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account Quarter To Day Acc Distinct Parts Dispatched'
                              ) < $account->get(
                                  'Account Quarter To Day Acc 1YB Distinct Parts Dispatched'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                      ),
                      array(
                          'header'                      => get_quarter_label(
                              strtotime('now -3 months')
                          ),
                          'invoiced_amount_delta_title' => delta(
                              $account->get(
                                  'Account 1 Quarter Ago Invoiced Amount'
                              ), $account->get(
                              'Account 1 Quarter Ago 1YB Invoiced Amount'
                          )
                          ),
                          'invoiced_amount_delta'       => ($account->get(
                              'Account 1 Quarter Ago Invoiced Amount'
                          ) > $account->get(
                              'Account 1 Quarter Ago 1YB Invoiced Amount'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 1 Quarter Ago Invoiced Amount'
                              ) < $account->get(
                                  'Account 1 Quarter Ago 1YB Invoiced Amount'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                          'dispatched_delta_title'      => delta(
                              $account->get('Account 1 Quarter Ago Distinct Parts Dispatched'), $account->get(
                              'Account 1 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                          ),
                          'dispatched_delta'            => ($account->get('Account 1 Quarter Ago Distinct Parts Dispatched') > $account->get(
                              'Account 1 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 1 Quarter Ago Distinct Parts Dispatched'
                              ) < $account->get(
                                  'Account 1 Quarter Ago 1YB Distinct Parts Dispatched'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                      ),
                      array(
                          'header'                      => get_quarter_label(
                              strtotime('now -6 months')
                          ),
                          'invoiced_amount_delta_title' => delta(
                              $account->get(
                                  'Account 2 Quarter Ago Invoiced Amount'
                              ), $account->get(
                              'Account 2 Quarter Ago 1YB Invoiced Amount'
                          )
                          ),
                          'invoiced_amount_delta'       => ($account->get(
                              'Account 2 Quarter Ago Invoiced Amount'
                          ) > $account->get(
                              'Account 2 Quarter Ago 1YB Invoiced Amount'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 2 Quarter Ago Invoiced Amount'
                              ) < $account->get(
                                  'Account 2 Quarter Ago 1YB Invoiced Amount'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                          'dispatched_delta_title'      => delta(
                              $account->get('Account 2 Quarter Ago Distinct Parts Dispatched'), $account->get(
                              'Account 2 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                          ),
                          'dispatched_delta'            => ($account->get('Account 2 Quarter Ago Distinct Parts Dispatched') > $account->get(
                              'Account 2 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 2 Quarter Ago Distinct Parts Dispatched'
                              ) < $account->get(
                                  'Account 2 Quarter Ago 1YB Distinct Parts Dispatched'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                      ),
                      array(
                          'header'                      => get_quarter_label(
                              strtotime('now -9 months')
                          ),
                          'invoiced_amount_delta_title' => delta(
                              $account->get(
                                  'Account 3 Quarter Ago Invoiced Amount'
                              ), $account->get(
                              'Account 3 Quarter Ago 1YB Invoiced Amount'
                          )
                          ),
                          'invoiced_amount_delta'       => ($account->get(
                              'Account 3 Quarter Ago Invoiced Amount'
                          ) > $account->get(
                              'Account 3 Quarter Ago 1YB Invoiced Amount'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 3 Quarter Ago Invoiced Amount'
                              ) < $account->get(
                                  'Account 3 Quarter Ago 1YB Invoiced Amount'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                          'dispatched_delta_title'      => delta(
                              $account->get('Account 3 Quarter Ago Distinct Parts Dispatched'), $account->get(
                              'Account 3 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                          ),
                          'dispatched_delta'            => ($account->get('Account 3 Quarter Ago Distinct Parts Dispatched') > $account->get(
                              'Account 3 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 3 Quarter Ago Distinct Parts Dispatched'
                              ) < $account->get(
                                  'Account 3 Quarter Ago 1YB Distinct Parts Dispatched'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                      ),
                      array(
                          'header'                      => get_quarter_label(
                              strtotime('now -12 months')
                          ),
                          'invoiced_amount_delta_title' => delta(
                              $account->get(
                                  'Account 4 Quarter Ago Invoiced Amount'
                              ), $account->get(
                              'Account 4 Quarter Ago 1YB Invoiced Amount'
                          )
                          ),
                          'invoiced_amount_delta'       => ($account->get(
                              'Account 4 Quarter Ago Invoiced Amount'
                          ) > $account->get(
                              'Account 4 Quarter Ago 1YB Invoiced Amount'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 4 Quarter Ago Invoiced Amount'
                              ) < $account->get(
                                  'Account 4 Quarter Ago 1YB Invoiced Amount'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                          'dispatched_delta_title'      => delta(
                              $account->get('Account 4 Quarter Ago Distinct Parts Dispatched'), $account->get(
                              'Account 4 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                          ),
                          'dispatched_delta'            => ($account->get('Account 4 Quarter Ago Distinct Parts Dispatched') > $account->get(
                              'Account 4 Quarter Ago 1YB Distinct Parts Dispatched'
                          )
                              ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                              : ($account->get(
                                  'Account 4 Quarter Ago Distinct Parts Dispatched'
                              ) < $account->get(
                                  'Account 4 Quarter Ago 1YB Distinct Parts Dispatched'
                              ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                      ),
                  )
);
$smarty->assign(
    'year_data', array(
                   array(
                       'header'                      => date('Y', strtotime('now')),
                       'invoiced_amount_delta_title' => delta(
                           $account->get(
                               'Account Year To Day Acc Invoiced Amount'
                           ), $account->get(
                           'Account Year To Day Acc 1YB Invoiced Amount'
                       )
                       ),
                       'invoiced_amount_delta'       => ($account->get(
                           'Account Year To Day Acc Invoiced Amount'
                       ) > $account->get(
                           'Account Year To Day Acc 1YB Invoiced Amount'
                       )
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get(
                               'Account Year To Day Acc Invoiced Amount'
                           ) < $account->get(
                               'Account Year To Day Acc 1YB Invoiced Amount'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                       'dispatched_delta_title'      => delta(
                           $account->get(
                               'Account Year To Day Acc Distinct Parts Dispatched'
                           ), $account->get(
                           'Account Year To Day Acc 1YB Distinct Parts Dispatched'
                       )
                       ),
                       'dispatched_delta'            => ($account->get('Account Year To Day Acc Distinct Parts Dispatched') > $account->get(
                           'Account Year To Day Acc 1YB Distinct Parts Dispatched'
                       )
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get(
                               'Account Year To Day Acc Distinct Parts Dispatched'
                           ) < $account->get(
                               'Account Year To Day Acc 1YB Distinct Parts Dispatched'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                   ),
                   array(
                       'header'                      => date(
                           'Y', strtotime('now -1 year')
                       ),
                       'invoiced_amount_delta_title' => delta(
                           $account->get(
                               'Account 1 Year Ago Invoiced Amount'
                           ), $account->get('Account 2 Year Ago Invoiced Amount')
                       ),
                       'invoiced_amount_delta'       => ($account->get('Account 1 Year Ago Invoiced Amount') > $account->get(
                           'Account 2 Year Ago Invoiced Amount'
                       )
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get(
                               'Account 1 Year Ago Invoiced Amount'
                           ) < $account->get(
                               'Account 2 Year Ago Invoiced Amount'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                       'dispatched_delta_title'      => delta(
                           $account->get('Account 1 Year Ago Distinct Parts Dispatched'), $account->get('Account 2 Year Ago Distinct Parts Dispatched')
                       ),
                       'dispatched_delta'            => ($account->get('Account 1 Year Ago Distinct Parts Dispatched') > $account->get('Account 2 Year Ago Distinct Parts Dispatched')
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get('Account 1 Year Ago Distinct Parts Dispatched') < $account->get(
                               'Account 2 Year Ago Distinct Parts Dispatched'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                   ),
                   array(
                       'header'                      => date(
                           'Y', strtotime('now -2 year')
                       ),
                       'invoiced_amount_delta_title' => delta(
                           $account->get(
                               'Account 2 Year Ago Invoiced Amount'
                           ), $account->get('Account 3 Year Ago Invoiced Amount')
                       ),
                       'invoiced_amount_delta'       => ($account->get('Account 2 Year Ago Invoiced Amount') > $account->get(
                           'Account 3 Year Ago Invoiced Amount'
                       )
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get(
                               'Account 2 Year Ago Invoiced Amount'
                           ) < $account->get(
                               'Account 3 Year Ago Invoiced Amount'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                       'dispatched_delta_title'      => delta(
                           $account->get('Account 2 Year Ago Distinct Parts Dispatched'), $account->get('Account 3 Year Ago Distinct Parts Dispatched')
                       ),
                       'dispatched_delta'            => ($account->get('Account 2 Year Ago Distinct Parts Dispatched') > $account->get('Account 3 Year Ago Distinct Parts Dispatched')
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get('Account 2 Year Ago Distinct Parts Dispatched') < $account->get(
                               'Account 3 Year Ago Distinct Parts Dispatched'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                   ),
                   array(
                       'header'                      => date(
                           'Y', strtotime('now -3 year')
                       ),
                       'invoiced_amount_delta_title' => delta(
                           $account->get(
                               'Account 3 Year Ago Invoiced Amount'
                           ), $account->get('Account 4 Year Ago Invoiced Amount')
                       ),
                       'invoiced_amount_delta'       => ($account->get('Account 3 Year Ago Invoiced Amoun') > $account->get(
                           'Account 4 Year Ago Invoiced Amount'
                       )
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get(
                               'Account 3 Year Ago Invoiced Amount'
                           ) < $account->get(
                               'Account 4 Year Ago Invoiced Amount'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                       'dispatched_delta_title'      => delta(
                           $account->get('Account 3 Year Ago Distinct Parts Dispatched'), $account->get('Account 4 Year Ago Distinct Parts Dispatched')
                       ),
                       'dispatched_delta'            => ($account->get('Account 3 Year Ago Invoiced Amoun') > $account->get('Account 4 Year Ago Distinct Parts Dispatched')
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get('Account 3 Year Ago Distinct Parts Dispatched') < $account->get(
                               'Account 4 Year Ago Distinct Parts Dispatched'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                   ),
                   array(
                       'header'                      => date(
                           'Y', strtotime('now -4 year')
                       ),
                       'invoiced_amount_delta_title' => delta(
                           $account->get(
                               'Account 4 Year Ago Invoiced Amount'
                           ), $account->get('Account 5 Year Ago Invoiced Amount')
                       ),
                       'invoiced_amount_delta'       => ($account->get('Account 4 Year Ago Invoiced Amount') > $account->get(
                           'Account 5 Year Ago Invoiced Amount'
                       )
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get(
                               'Account 4 Year Ago Invoiced Amount'
                           ) < $account->get(
                               'Account 5 Year Ago Invoiced Amount'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                       'dispatched_delta_title'      => delta(
                           $account->get('Account 4 Year Ago Distinct Parts Dispatched'), $account->get('Account 5 Year Ago Distinct Parts Dispatched')
                       ),
                       'dispatched_delta'            => ($account->get('Account 4 Year Ago Distinct Parts Dispatched') > $account->get('Account 5 Year Ago Distinct Parts Dispatched')
                           ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                           : ($account->get('Account 4 Year Ago Distinct Parts Dispatched') < $account->get(
                               'Account 5 Year Ago Distinct Parts Dispatched'
                           ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                   )
               )
);


$deliveries = sprintf('<i class="fal fa-conveyor-belt-alt padding_right_5" aria-hidden="true"></i> %s', number($account->get('Account Total Acc Delivery Notes')));

$smarty->assign('deliveries', $deliveries);

$distinct_parts = sprintf('<i class="fal fa-truck-loading padding_right_5" aria-hidden="true"></i> %s', number($account->get('Account Total Acc Distinct Parts Dispatched')));
$smarty->assign('distinct_parts', $distinct_parts);




$smarty->assign(
    'header_total_sales', sprintf(_('Delivering since: %s'), $account->get('Pretty Valid From'))
);


$html = $smarty->fetch('inventory.sales.tpl');


$smarty->assign('show_widget', $state['extra']);

include_once 'widgets/inventory_alerts.wget.php';

$html .= '<div class="widget_container">'.get_inventory_alerts($db, $account, $user, $smarty).'</div>';
$html .= '<div id="widget_details" class="hide" style="clear:both;font-size:90%;border-top:1px solid #ccc"><div>';


switch ($state['extra']) {
    case 'barcode_errors':
    case 'barcodes_errors':
        $html .= "<script>get_widget_details($('#inventory_parts_barcode_errors_wget'),'inventory.parts_barcode_errors.wget',{ parent: 'account','parent_key':1})</script>";
        break;
    case 'missing_sko_barcodes':
        $html .= "<script>get_widget_details($('#inventory_parts_no_sko_barcode_wget'),'inventory.parts_no_sko_barcode.wget',{ parent: 'account','parent_key':1})</script>";
        break;
    case 'weight_errors':
        $html .= "<script>get_widget_details($('#inventory_parts_weight_errors_wget'),'inventory.parts_weight_errors.wget',{ parent: 'account','parent_key':1})</script>";
        break;
    default:
        break;
}



