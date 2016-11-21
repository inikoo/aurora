<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 November 2016 at 20:04:11 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_stores_showcase($data, $smarty) {


    $account = $data['_object'];
    $account->load_acc_data();



    if (!$account->id) {
        return "";
    }

    $smarty->assign('account', $account);


    $smarty->assign(
        'quarter_data', array(
                          array(
                              'header'                      => get_quarter_label(strtotime('now')),
                              'invoiced_amount_delta_title' => delta($account->get('Account Quarter To Day Acc Invoiced Amount'), $account->get('Account Quarter To Day Acc 1YB Invoiced Amount')),
                              'invoiced_amount_delta'       => ($account->get('Account Quarter To Day Acc Invoiced Amount') > $account->get('Account Quarter To Day Acc 1YB Invoiced Amount')
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($account->get('Account Quarter To Day Acc Invoiced Amount') < $account->get(
                                      'Account Quarter To Day Acc 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta($account->get('Account Quarter To Day Acc Invoices'), $account->get('Account Quarter To Day Acc 1YB Invoices')),
                              'invoices_delta'              => ($account->get('Account Quarter To Day Acc Invoices') > $account->get('Account Quarter To Day Acc 1YB Invoices')
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($account->get('Account Quarter To Day Acc Invoices') < $account->get(
                                      'Account Quarter To Day Acc 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(strtotime('now -3 months')),
                              'invoiced_amount_delta_title' => delta(
                                  $account->get('Account 1 Quarter Ago Invoiced Amount'), $account->get('Account 1 Quarter Ago 1YB Invoiced Amount')
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
                              'invoices_delta_title'        => delta(
                                  $account->get('Account 1 Quarter Ago Invoices'), $account->get(
                                  'Account 1 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($account->get('Account 1 Quarter Ago Invoices') > $account->get(
                                  'Account 1 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($account->get(
                                      'Account 1 Quarter Ago Invoices'
                                  ) < $account->get(
                                      'Account 1 Quarter Ago 1YB Invoices'
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
                              'invoices_delta_title'        => delta(
                                  $account->get('Account 2 Quarter Ago Invoices'), $account->get(
                                  'Account 2 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($account->get('Account 2 Quarter Ago Invoices') > $account->get(
                                  'Account 2 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($account->get(
                                      'Account 2 Quarter Ago Invoices'
                                  ) < $account->get(
                                      'Account 2 Quarter Ago 1YB Invoices'
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
                              'invoices_delta_title'        => delta(
                                  $account->get('Account 3 Quarter Ago Invoices'), $account->get(
                                  'Account 3 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($account->get('Account 3 Quarter Ago Invoices') > $account->get(
                                  'Account 3 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($account->get(
                                      'Account 3 Quarter Ago Invoices'
                                  ) < $account->get(
                                      'Account 3 Quarter Ago 1YB Invoices'
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
                              'invoices_delta_title'        => delta(
                                  $account->get('Account 4 Quarter Ago Invoices'), $account->get(
                                  'Account 4 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($account->get('Account 4 Quarter Ago Invoices') > $account->get(
                                  'Account 4 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($account->get(
                                      'Account 4 Quarter Ago Invoices'
                                  ) < $account->get(
                                      'Account 4 Quarter Ago 1YB Invoices'
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
                           'invoices_delta_title'        => delta(
                               $account->get(
                                   'Account Year To Day Acc Invoices'
                               ), $account->get(
                               'Account Year To Day Acc 1YB Invoices'
                           )
                           ),
                           'invoices_delta'              => ($account->get('Account Year To Day Acc Invoices') > $account->get(
                               'Account Year To Day Acc 1YB Invoices'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($account->get(
                                   'Account Year To Day Acc Invoices'
                               ) < $account->get(
                                   'Account Year To Day Acc 1YB Invoices'
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
                           'invoices_delta_title'        => delta(
                               $account->get('Account 1 Year Ago Invoices'), $account->get('Account 2 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($account->get('Account 1 Year Ago Invoices') > $account->get('Account 2 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($account->get('Account 1 Year Ago Invoices') < $account->get(
                                   'Account 2 Year Ago Invoices'
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
                           'invoices_delta_title'        => delta(
                               $account->get('Account 2 Year Ago Invoices'), $account->get('Account 3 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($account->get('Account 2 Year Ago Invoices') > $account->get('Account 3 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($account->get('Account 2 Year Ago Invoices') < $account->get(
                                   'Account 3 Year Ago Invoices'
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
                           'invoices_delta_title'        => delta(
                               $account->get('Account 3 Year Ago Invoices'), $account->get('Account 4 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($account->get('Account 3 Year Ago Invoiced Amoun') > $account->get('Account 4 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($account->get('Account 3 Year Ago Invoices') < $account->get(
                                   'Account 4 Year Ago Invoices'
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
                           'invoices_delta_title'        => delta(
                               $account->get('Account 4 Year Ago Invoices'), $account->get('Account 5 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($account->get('Account 4 Year Ago Invoices') > $account->get('Account 5 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($account->get('Account 4 Year Ago Invoices') < $account->get(
                                   'Account 5 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       )
                   )
    );

    $customers = sprintf('<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s <span title="%s">(%s)</span>',
                         $account->get('Total Acc Customers'),
                         sprintf(_('Repeat customers %s'),$account->get('Total Acc Repeat Customers')),
                         percentage($account->get('Account Total Acc Repeat Customers'),$account->get('Account Total Acc Customers'))
                         );

    $smarty->assign('customers', $customers);

    $smarty->assign(
        'header_total_sales', sprintf(_('All sales since: %s'), $account->get('Valid From'))
    );


    return $smarty->fetch('showcase/stores.tpl');


}


?>