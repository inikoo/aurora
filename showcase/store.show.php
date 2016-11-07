<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 13:31:36 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_store_showcase($data, $smarty) {


    $store = $data['_object'];
    $store->load_acc_data();

    if (!$store->id) {
        return "";
    }

    $smarty->assign('store', $store);


    $smarty->assign(
        'quarter_data', array(
                          array(
                              'header'                      => get_quarter_label(strtotime('now')),
                              'invoiced_amount_delta_title' => delta($store->get('Store Quarter To Day Acc Invoiced Amount'), $store->get('Store Quarter To Day Acc 1YB Invoiced Amount')),
                              'invoiced_amount_delta'       => ($store->get('Store Quarter To Day Acc Invoiced Amount') > $store->get('Store Quarter To Day Acc 1YB Invoiced Amount')
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get('Store Quarter To Day Acc Invoiced Amount') < $store->get(
                                      'Store Quarter To Day Acc 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta($store->get('Store Quarter To Day Acc Invoices'), $store->get('Store Quarter To Day Acc 1YB Invoices')),
                              'invoices_delta'              => ($store->get('Store Quarter To Day Acc Invoices') > $store->get('Store Quarter To Day Acc 1YB Invoices')
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get('Store Quarter To Day Acc Invoices') < $store->get(
                                      'Store Quarter To Day Acc 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(strtotime('now -3 months')),
                              'invoiced_amount_delta_title' => delta(
                                  $store->get('Store 1 Quarter Ago Invoiced Amount'), $store->get('Store 1 Quarter Ago 1YB Invoiced Amount')
                              ),
                              'invoiced_amount_delta'       => ($store->get(
                                  'Store 1 Quarter Ago Invoiced Amount'
                              ) > $store->get(
                                  'Store 1 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 1 Quarter Ago Invoiced Amount'
                                  ) < $store->get(
                                      'Store 1 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $store->get('Store 1 Quarter Ago Invoices'), $store->get(
                                  'Store 1 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($store->get('Store 1 Quarter Ago Invoices') > $store->get(
                                  'Store 1 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 1 Quarter Ago Invoices'
                                  ) < $store->get(
                                      'Store 1 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(
                                  strtotime('now -6 months')
                              ),
                              'invoiced_amount_delta_title' => delta(
                                  $store->get(
                                      'Store 2 Quarter Ago Invoiced Amount'
                                  ), $store->get(
                                  'Store 2 Quarter Ago 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'       => ($store->get(
                                  'Store 2 Quarter Ago Invoiced Amount'
                              ) > $store->get(
                                  'Store 2 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 2 Quarter Ago Invoiced Amount'
                                  ) < $store->get(
                                      'Store 2 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $store->get('Store 2 Quarter Ago Invoices'), $store->get(
                                  'Store 2 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($store->get('Store 2 Quarter Ago Invoices') > $store->get(
                                  'Store 2 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 2 Quarter Ago Invoices'
                                  ) < $store->get(
                                      'Store 2 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(
                                  strtotime('now -9 months')
                              ),
                              'invoiced_amount_delta_title' => delta(
                                  $store->get(
                                      'Store 3 Quarter Ago Invoiced Amount'
                                  ), $store->get(
                                  'Store 3 Quarter Ago 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'       => ($store->get(
                                  'Store 3 Quarter Ago Invoiced Amount'
                              ) > $store->get(
                                  'Store 3 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 3 Quarter Ago Invoiced Amount'
                                  ) < $store->get(
                                      'Store 3 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $store->get('Store 3 Quarter Ago Invoices'), $store->get(
                                  'Store 3 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($store->get('Store 3 Quarter Ago Invoices') > $store->get(
                                  'Store 3 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 3 Quarter Ago Invoices'
                                  ) < $store->get(
                                      'Store 3 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                          ),
                          array(
                              'header'                      => get_quarter_label(
                                  strtotime('now -12 months')
                              ),
                              'invoiced_amount_delta_title' => delta(
                                  $store->get(
                                      'Store 4 Quarter Ago Invoiced Amount'
                                  ), $store->get(
                                  'Store 4 Quarter Ago 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'       => ($store->get(
                                  'Store 4 Quarter Ago Invoiced Amount'
                              ) > $store->get(
                                  'Store 4 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 4 Quarter Ago Invoiced Amount'
                                  ) < $store->get(
                                      'Store 4 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $store->get('Store 4 Quarter Ago Invoices'), $store->get(
                                  'Store 4 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($store->get('Store 4 Quarter Ago Invoices') > $store->get(
                                  'Store 4 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($store->get(
                                      'Store 4 Quarter Ago Invoices'
                                  ) < $store->get(
                                      'Store 4 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                          ),
                      )
    );
    $smarty->assign(
        'year_data', array(
                       array(
                           'header'                      => date('Y', strtotime('now')),
                           'invoiced_amount_delta_title' => delta(
                               $store->get(
                                   'Store Year To Day Acc Invoiced Amount'
                               ), $store->get(
                               'Store Year To Day Acc 1YB Invoiced Amount'
                           )
                           ),
                           'invoiced_amount_delta'       => ($store->get(
                               'Store Year To Day Acc Invoiced Amount'
                           ) > $store->get(
                               'Store Year To Day Acc 1YB Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get(
                                   'Store Year To Day Acc Invoiced Amount'
                               ) < $store->get(
                                   'Store Year To Day Acc 1YB Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $store->get(
                                   'Store Year To Day Acc Invoices'
                               ), $store->get(
                               'Store Year To Day Acc 1YB Invoices'
                           )
                           ),
                           'invoices_delta'              => ($store->get('Store Year To Day Acc Invoices') > $store->get(
                               'Store Year To Day Acc 1YB Invoices'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get(
                                   'Store Year To Day Acc Invoices'
                               ) < $store->get(
                                   'Store Year To Day Acc 1YB Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -1 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $store->get(
                                   'Store 1 Year Ago Invoiced Amount'
                               ), $store->get('Store 2 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($store->get('Store 1 Year Ago Invoiced Amount') > $store->get(
                               'Store 2 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get(
                                   'Store 1 Year Ago Invoiced Amount'
                               ) < $store->get(
                                   'Store 2 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $store->get('Store 1 Year Ago Invoices'), $store->get('Store 2 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($store->get('Store 1 Year Ago Invoices') > $store->get('Store 2 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get('Store 1 Year Ago Invoices') < $store->get(
                                   'Store 2 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -2 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $store->get(
                                   'Store 2 Year Ago Invoiced Amount'
                               ), $store->get('Store 3 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($store->get('Store 2 Year Ago Invoiced Amount') > $store->get(
                               'Store 3 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get(
                                   'Store 2 Year Ago Invoiced Amount'
                               ) < $store->get(
                                   'Store 3 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $store->get('Store 2 Year Ago Invoices'), $store->get('Store 3 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($store->get('Store 2 Year Ago Invoices') > $store->get('Store 3 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get('Store 2 Year Ago Invoices') < $store->get(
                                   'Store 3 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -3 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $store->get(
                                   'Store 3 Year Ago Invoiced Amount'
                               ), $store->get('Store 4 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($store->get('Store 3 Year Ago Invoiced Amoun') > $store->get(
                               'Store 4 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get(
                                   'Store 3 Year Ago Invoiced Amount'
                               ) < $store->get(
                                   'Store 4 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $store->get('Store 3 Year Ago Invoices'), $store->get('Store 4 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($store->get('Store 3 Year Ago Invoiced Amoun') > $store->get('Store 4 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get('Store 3 Year Ago Invoices') < $store->get(
                                   'Store 4 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -4 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $store->get(
                                   'Store 4 Year Ago Invoiced Amount'
                               ), $store->get('Store 5 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($store->get('Store 4 Year Ago Invoiced Amount') > $store->get(
                               'Store 5 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get(
                                   'Store 4 Year Ago Invoiced Amount'
                               ) < $store->get(
                                   'Store 5 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $store->get('Store 4 Year Ago Invoices'), $store->get('Store 5 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($store->get('Store 4 Year Ago Invoices') > $store->get('Store 5 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($store->get('Store 4 Year Ago Invoices') < $store->get(
                                   'Store 5 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       )
                   )
    );

    $customers = sprintf('<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s <span title="%s">(%s)</span>',
                         $store->get('Total Acc Customers'),
                         sprintf(_('Repeat customers %s'),$store->get('Total Acc Repeat Customers')),
                         percentage($store->get('Store Total Acc Repeat Customers'),$store->get('Store Total Acc Customers'))
                         );

    $smarty->assign('customers', $customers);

    $smarty->assign(
        'header_total_sales', sprintf(_('All sales since: %s'), $store->get('Valid From'))
    );


    return $smarty->fetch('showcase/store.tpl');


}


?>