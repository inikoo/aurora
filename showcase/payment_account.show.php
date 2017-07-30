<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 July 2017 at 10:20:42 CEST, Trnava, 
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_payment_account_showcase($data, $smarty) {


    $payment_account = $data['_object'];
    $payment_account->load_acc_data();

    if (!$payment_account->id) {
        return "";
    }

    $smarty->assign('payment_account', $payment_account);


    $smarty->assign(
        'quarter_data', array(
                          array(
                              'header'                      => get_quarter_label(strtotime('now')),
                              'invoiced_amount_delta_title' => delta($payment_account->get('Payment Account Quarter To Day Acc Invoiced Amount'), $payment_account->get('Payment Account Quarter To Day Acc 1YB Invoiced Amount')),
                              'invoiced_amount_delta'       => ($payment_account->get('Payment Account Quarter To Day Acc Invoiced Amount') > $payment_account->get('Payment Account Quarter To Day Acc 1YB Invoiced Amount')
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get('Payment Account Quarter To Day Acc Invoiced Amount') < $payment_account->get(
                                      'Payment Account Quarter To Day Acc 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta($payment_account->get('Payment Account Quarter To Day Acc Invoices'), $payment_account->get('Payment Account Quarter To Day Acc 1YB Invoices')),
                              'invoices_delta'              => ($payment_account->get('Payment Account Quarter To Day Acc Invoices') > $payment_account->get('Payment Account Quarter To Day Acc 1YB Invoices')
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get('Payment Account Quarter To Day Acc Invoices') < $payment_account->get(
                                      'Payment Account Quarter To Day Acc 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(strtotime('now -3 months')),
                              'invoiced_amount_delta_title' => delta(
                                  $payment_account->get('Payment Account 1 Quarter Ago Invoiced Amount'), $payment_account->get('Payment Account 1 Quarter Ago 1YB Invoiced Amount')
                              ),
                              'invoiced_amount_delta'       => ($payment_account->get(
                                  'Payment Account 1 Quarter Ago Invoiced Amount'
                              ) > $payment_account->get(
                                  'Payment Account 1 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 1 Quarter Ago Invoiced Amount'
                                  ) < $payment_account->get(
                                      'Payment Account 1 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $payment_account->get('Payment Account 1 Quarter Ago Invoices'), $payment_account->get(
                                  'Payment Account 1 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($payment_account->get('Payment Account 1 Quarter Ago Invoices') > $payment_account->get(
                                  'Payment Account 1 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 1 Quarter Ago Invoices'
                                  ) < $payment_account->get(
                                      'Payment Account 1 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(
                                  strtotime('now -6 months')
                              ),
                              'invoiced_amount_delta_title' => delta(
                                  $payment_account->get(
                                      'Payment Account 2 Quarter Ago Invoiced Amount'
                                  ), $payment_account->get(
                                  'Payment Account 2 Quarter Ago 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'       => ($payment_account->get(
                                  'Payment Account 2 Quarter Ago Invoiced Amount'
                              ) > $payment_account->get(
                                  'Payment Account 2 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 2 Quarter Ago Invoiced Amount'
                                  ) < $payment_account->get(
                                      'Payment Account 2 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $payment_account->get('Payment Account 2 Quarter Ago Invoices'), $payment_account->get(
                                  'Payment Account 2 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($payment_account->get('Payment Account 2 Quarter Ago Invoices') > $payment_account->get(
                                  'Payment Account 2 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 2 Quarter Ago Invoices'
                                  ) < $payment_account->get(
                                      'Payment Account 2 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                      => get_quarter_label(
                                  strtotime('now -9 months')
                              ),
                              'invoiced_amount_delta_title' => delta(
                                  $payment_account->get(
                                      'Payment Account 3 Quarter Ago Invoiced Amount'
                                  ), $payment_account->get(
                                  'Payment Account 3 Quarter Ago 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'       => ($payment_account->get(
                                  'Payment Account 3 Quarter Ago Invoiced Amount'
                              ) > $payment_account->get(
                                  'Payment Account 3 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 3 Quarter Ago Invoiced Amount'
                                  ) < $payment_account->get(
                                      'Payment Account 3 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $payment_account->get('Payment Account 3 Quarter Ago Invoices'), $payment_account->get(
                                  'Payment Account 3 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($payment_account->get('Payment Account 3 Quarter Ago Invoices') > $payment_account->get(
                                  'Payment Account 3 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 3 Quarter Ago Invoices'
                                  ) < $payment_account->get(
                                      'Payment Account 3 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                          ),
                          array(
                              'header'                      => get_quarter_label(
                                  strtotime('now -12 months')
                              ),
                              'invoiced_amount_delta_title' => delta(
                                  $payment_account->get(
                                      'Payment Account 4 Quarter Ago Invoiced Amount'
                                  ), $payment_account->get(
                                  'Payment Account 4 Quarter Ago 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'       => ($payment_account->get(
                                  'Payment Account 4 Quarter Ago Invoiced Amount'
                              ) > $payment_account->get(
                                  'Payment Account 4 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 4 Quarter Ago Invoiced Amount'
                                  ) < $payment_account->get(
                                      'Payment Account 4 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'invoices_delta_title'        => delta(
                                  $payment_account->get('Payment Account 4 Quarter Ago Invoices'), $payment_account->get(
                                  'Payment Account 4 Quarter Ago 1YB Invoices'
                              )
                              ),
                              'invoices_delta'              => ($payment_account->get('Payment Account 4 Quarter Ago Invoices') > $payment_account->get(
                                  'Payment Account 4 Quarter Ago 1YB Invoices'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($payment_account->get(
                                      'Payment Account 4 Quarter Ago Invoices'
                                  ) < $payment_account->get(
                                      'Payment Account 4 Quarter Ago 1YB Invoices'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                          ),
                      )
    );
    $smarty->assign(
        'year_data', array(
                       array(
                           'header'                      => date('Y', strtotime('now')),
                           'invoiced_amount_delta_title' => delta(
                               $payment_account->get(
                                   'Payment Account Year To Day Acc Invoiced Amount'
                               ), $payment_account->get(
                               'Payment Account Year To Day Acc 1YB Invoiced Amount'
                           )
                           ),
                           'invoiced_amount_delta'       => ($payment_account->get(
                               'Payment Account Year To Day Acc Invoiced Amount'
                           ) > $payment_account->get(
                               'Payment Account Year To Day Acc 1YB Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get(
                                   'Payment Account Year To Day Acc Invoiced Amount'
                               ) < $payment_account->get(
                                   'Payment Account Year To Day Acc 1YB Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $payment_account->get(
                                   'Payment Account Year To Day Acc Invoices'
                               ), $payment_account->get(
                               'Payment Account Year To Day Acc 1YB Invoices'
                           )
                           ),
                           'invoices_delta'              => ($payment_account->get('Payment Account Year To Day Acc Invoices') > $payment_account->get(
                               'Payment Account Year To Day Acc 1YB Invoices'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get(
                                   'Payment Account Year To Day Acc Invoices'
                               ) < $payment_account->get(
                                   'Payment Account Year To Day Acc 1YB Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -1 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $payment_account->get(
                                   'Payment Account 1 Year Ago Invoiced Amount'
                               ), $payment_account->get('Payment Account 2 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($payment_account->get('Payment Account 1 Year Ago Invoiced Amount') > $payment_account->get(
                               'Payment Account 2 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get(
                                   'Payment Account 1 Year Ago Invoiced Amount'
                               ) < $payment_account->get(
                                   'Payment Account 2 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $payment_account->get('Payment Account 1 Year Ago Invoices'), $payment_account->get('Payment Account 2 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($payment_account->get('Payment Account 1 Year Ago Invoices') > $payment_account->get('Payment Account 2 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get('Payment Account 1 Year Ago Invoices') < $payment_account->get(
                                   'Payment Account 2 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -2 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $payment_account->get(
                                   'Payment Account 2 Year Ago Invoiced Amount'
                               ), $payment_account->get('Payment Account 3 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($payment_account->get('Payment Account 2 Year Ago Invoiced Amount') > $payment_account->get(
                               'Payment Account 3 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get(
                                   'Payment Account 2 Year Ago Invoiced Amount'
                               ) < $payment_account->get(
                                   'Payment Account 3 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $payment_account->get('Payment Account 2 Year Ago Invoices'), $payment_account->get('Payment Account 3 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($payment_account->get('Payment Account 2 Year Ago Invoices') > $payment_account->get('Payment Account 3 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get('Payment Account 2 Year Ago Invoices') < $payment_account->get(
                                   'Payment Account 3 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -3 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $payment_account->get(
                                   'Payment Account 3 Year Ago Invoiced Amount'
                               ), $payment_account->get('Payment Account 4 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($payment_account->get('Payment Account 3 Year Ago Invoiced Amoun') > $payment_account->get(
                               'Payment Account 4 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get(
                                   'Payment Account 3 Year Ago Invoiced Amount'
                               ) < $payment_account->get(
                                   'Payment Account 4 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $payment_account->get('Payment Account 3 Year Ago Invoices'), $payment_account->get('Payment Account 4 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($payment_account->get('Payment Account 3 Year Ago Invoiced Amoun') > $payment_account->get('Payment Account 4 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get('Payment Account 3 Year Ago Invoices') < $payment_account->get(
                                   'Payment Account 4 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                      => date(
                               'Y', strtotime('now -4 year')
                           ),
                           'invoiced_amount_delta_title' => delta(
                               $payment_account->get(
                                   'Payment Account 4 Year Ago Invoiced Amount'
                               ), $payment_account->get('Payment Account 5 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'       => ($payment_account->get('Payment Account 4 Year Ago Invoiced Amount') > $payment_account->get(
                               'Payment Account 5 Year Ago Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get(
                                   'Payment Account 4 Year Ago Invoiced Amount'
                               ) < $payment_account->get(
                                   'Payment Account 5 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'invoices_delta_title'        => delta(
                               $payment_account->get('Payment Account 4 Year Ago Invoices'), $payment_account->get('Payment Account 5 Year Ago Invoices')
                           ),
                           'invoices_delta'              => ($payment_account->get('Payment Account 4 Year Ago Invoices') > $payment_account->get('Payment Account 5 Year Ago Invoices')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($payment_account->get('Payment Account 4 Year Ago Invoices') < $payment_account->get(
                                   'Payment Account 5 Year Ago Invoices'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       )
                   )
    );

    $customers = sprintf('<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s <span title="%s">(%s)</span>',
                         $payment_account->get('Total Acc Customers'),
                         sprintf(_('Repeat customers %s'),$payment_account->get('Total Acc Repeat Customers')),
                         percentage($payment_account->get('Payment Account Total Acc Repeat Customers'),$payment_account->get('Payment Account Total Acc Customers'))
                         );

    $smarty->assign('customers', $customers);

    $smarty->assign('header_total_sales', sprintf(_('All payments since: %s'), $payment_account->get('Valid From')));


    return $smarty->fetch('showcase/payment_account.tpl');


}


?>