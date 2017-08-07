<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2017 at 11:45:44 GMT+8, Damansara, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/

function get_website_showcase($data, $smarty) {


    $website = $data['_object'];

    $website->update_sitemap();
    if (!$website->id) {
        return "";
    }

    $smarty->assign('website', $website);

    if($website->get('Website Status')=='InProcess'){


            $smarty->assign('to_launch_webpage_key',$website->get_system_webpage_key('launching.sys'));

        return $smarty->fetch('showcase/website.to_launch.tpl');

    }else{


        $header_total_views='';
        $customers='';

        $smarty->assign('header_total_views', $header_total_views);
        $smarty->assign('customers', $customers);

        $smarty->assign(
            'quarter_data', array(
                              array(
                                  'header'                      => get_quarter_label(strtotime('now')),
                                  'invoiced_amount_delta_title' => delta($website->get('Store Quarter To Day Acc Invoiced Amount'), $website->get('Store Quarter To Day Acc 1YB Invoiced Amount')),
                                  'invoiced_amount_delta'       => ($website->get('Store Quarter To Day Acc Invoiced Amount') > $website->get('Store Quarter To Day Acc 1YB Invoiced Amount')
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get('Store Quarter To Day Acc Invoiced Amount') < $website->get(
                                          'Store Quarter To Day Acc 1YB Invoiced Amount'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                                  'invoices_delta_title'        => delta($website->get('Store Quarter To Day Acc Invoices'), $website->get('Store Quarter To Day Acc 1YB Invoices')),
                                  'invoices_delta'              => ($website->get('Store Quarter To Day Acc Invoices') > $website->get('Store Quarter To Day Acc 1YB Invoices')
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get('Store Quarter To Day Acc Invoices') < $website->get(
                                          'Store Quarter To Day Acc 1YB Invoices'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                              ),
                              array(
                                  'header'                      => get_quarter_label(strtotime('now -3 months')),
                                  'invoiced_amount_delta_title' => delta(
                                      $website->get('Store 1 Quarter Ago Invoiced Amount'), $website->get('Store 1 Quarter Ago 1YB Invoiced Amount')
                                  ),
                                  'invoiced_amount_delta'       => ($website->get(
                                      'Store 1 Quarter Ago Invoiced Amount'
                                  ) > $website->get(
                                      'Store 1 Quarter Ago 1YB Invoiced Amount'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 1 Quarter Ago Invoiced Amount'
                                      ) < $website->get(
                                          'Store 1 Quarter Ago 1YB Invoiced Amount'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                                  'invoices_delta_title'        => delta(
                                      $website->get('Store 1 Quarter Ago Invoices'), $website->get(
                                      'Store 1 Quarter Ago 1YB Invoices'
                                  )
                                  ),
                                  'invoices_delta'              => ($website->get('Store 1 Quarter Ago Invoices') > $website->get(
                                      'Store 1 Quarter Ago 1YB Invoices'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 1 Quarter Ago Invoices'
                                      ) < $website->get(
                                          'Store 1 Quarter Ago 1YB Invoices'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                              ),
                              array(
                                  'header'                      => get_quarter_label(
                                      strtotime('now -6 months')
                                  ),
                                  'invoiced_amount_delta_title' => delta(
                                      $website->get(
                                          'Store 2 Quarter Ago Invoiced Amount'
                                      ), $website->get(
                                      'Store 2 Quarter Ago 1YB Invoiced Amount'
                                  )
                                  ),
                                  'invoiced_amount_delta'       => ($website->get(
                                      'Store 2 Quarter Ago Invoiced Amount'
                                  ) > $website->get(
                                      'Store 2 Quarter Ago 1YB Invoiced Amount'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 2 Quarter Ago Invoiced Amount'
                                      ) < $website->get(
                                          'Store 2 Quarter Ago 1YB Invoiced Amount'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                                  'invoices_delta_title'        => delta(
                                      $website->get('Store 2 Quarter Ago Invoices'), $website->get(
                                      'Store 2 Quarter Ago 1YB Invoices'
                                  )
                                  ),
                                  'invoices_delta'              => ($website->get('Store 2 Quarter Ago Invoices') > $website->get(
                                      'Store 2 Quarter Ago 1YB Invoices'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 2 Quarter Ago Invoices'
                                      ) < $website->get(
                                          'Store 2 Quarter Ago 1YB Invoices'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                              ),
                              array(
                                  'header'                      => get_quarter_label(
                                      strtotime('now -9 months')
                                  ),
                                  'invoiced_amount_delta_title' => delta(
                                      $website->get(
                                          'Store 3 Quarter Ago Invoiced Amount'
                                      ), $website->get(
                                      'Store 3 Quarter Ago 1YB Invoiced Amount'
                                  )
                                  ),
                                  'invoiced_amount_delta'       => ($website->get(
                                      'Store 3 Quarter Ago Invoiced Amount'
                                  ) > $website->get(
                                      'Store 3 Quarter Ago 1YB Invoiced Amount'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 3 Quarter Ago Invoiced Amount'
                                      ) < $website->get(
                                          'Store 3 Quarter Ago 1YB Invoiced Amount'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                                  'invoices_delta_title'        => delta(
                                      $website->get('Store 3 Quarter Ago Invoices'), $website->get(
                                      'Store 3 Quarter Ago 1YB Invoices'
                                  )
                                  ),
                                  'invoices_delta'              => ($website->get('Store 3 Quarter Ago Invoices') > $website->get(
                                      'Store 3 Quarter Ago 1YB Invoices'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 3 Quarter Ago Invoices'
                                      ) < $website->get(
                                          'Store 3 Quarter Ago 1YB Invoices'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                              ),
                              array(
                                  'header'                      => get_quarter_label(
                                      strtotime('now -12 months')
                                  ),
                                  'invoiced_amount_delta_title' => delta(
                                      $website->get(
                                          'Store 4 Quarter Ago Invoiced Amount'
                                      ), $website->get(
                                      'Store 4 Quarter Ago 1YB Invoiced Amount'
                                  )
                                  ),
                                  'invoiced_amount_delta'       => ($website->get(
                                      'Store 4 Quarter Ago Invoiced Amount'
                                  ) > $website->get(
                                      'Store 4 Quarter Ago 1YB Invoiced Amount'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 4 Quarter Ago Invoiced Amount'
                                      ) < $website->get(
                                          'Store 4 Quarter Ago 1YB Invoiced Amount'
                                      ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                                  'invoices_delta_title'        => delta(
                                      $website->get('Store 4 Quarter Ago Invoices'), $website->get(
                                      'Store 4 Quarter Ago 1YB Invoices'
                                  )
                                  ),
                                  'invoices_delta'              => ($website->get('Store 4 Quarter Ago Invoices') > $website->get(
                                      'Store 4 Quarter Ago 1YB Invoices'
                                  )
                                      ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                      : ($website->get(
                                          'Store 4 Quarter Ago Invoices'
                                      ) < $website->get(
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
                                   $website->get(
                                       'Store Year To Day Acc Invoiced Amount'
                                   ), $website->get(
                                   'Store Year To Day Acc 1YB Invoiced Amount'
                               )
                               ),
                               'invoiced_amount_delta'       => ($website->get(
                                   'Store Year To Day Acc Invoiced Amount'
                               ) > $website->get(
                                   'Store Year To Day Acc 1YB Invoiced Amount'
                               )
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get(
                                       'Store Year To Day Acc Invoiced Amount'
                                   ) < $website->get(
                                       'Store Year To Day Acc 1YB Invoiced Amount'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                               'invoices_delta_title'        => delta(
                                   $website->get(
                                       'Store Year To Day Acc Invoices'
                                   ), $website->get(
                                   'Store Year To Day Acc 1YB Invoices'
                               )
                               ),
                               'invoices_delta'              => ($website->get('Store Year To Day Acc Invoices') > $website->get(
                                   'Store Year To Day Acc 1YB Invoices'
                               )
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get(
                                       'Store Year To Day Acc Invoices'
                                   ) < $website->get(
                                       'Store Year To Day Acc 1YB Invoices'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                           ),
                           array(
                               'header'                      => date(
                                   'Y', strtotime('now -1 year')
                               ),
                               'invoiced_amount_delta_title' => delta(
                                   $website->get(
                                       'Store 1 Year Ago Invoiced Amount'
                                   ), $website->get('Store 2 Year Ago Invoiced Amount')
                               ),
                               'invoiced_amount_delta'       => ($website->get('Store 1 Year Ago Invoiced Amount') > $website->get(
                                   'Store 2 Year Ago Invoiced Amount'
                               )
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get(
                                       'Store 1 Year Ago Invoiced Amount'
                                   ) < $website->get(
                                       'Store 2 Year Ago Invoiced Amount'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                               'invoices_delta_title'        => delta(
                                   $website->get('Store 1 Year Ago Invoices'), $website->get('Store 2 Year Ago Invoices')
                               ),
                               'invoices_delta'              => ($website->get('Store 1 Year Ago Invoices') > $website->get('Store 2 Year Ago Invoices')
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get('Store 1 Year Ago Invoices') < $website->get(
                                       'Store 2 Year Ago Invoices'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                           ),
                           array(
                               'header'                      => date(
                                   'Y', strtotime('now -2 year')
                               ),
                               'invoiced_amount_delta_title' => delta(
                                   $website->get(
                                       'Store 2 Year Ago Invoiced Amount'
                                   ), $website->get('Store 3 Year Ago Invoiced Amount')
                               ),
                               'invoiced_amount_delta'       => ($website->get('Store 2 Year Ago Invoiced Amount') > $website->get(
                                   'Store 3 Year Ago Invoiced Amount'
                               )
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get(
                                       'Store 2 Year Ago Invoiced Amount'
                                   ) < $website->get(
                                       'Store 3 Year Ago Invoiced Amount'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                               'invoices_delta_title'        => delta(
                                   $website->get('Store 2 Year Ago Invoices'), $website->get('Store 3 Year Ago Invoices')
                               ),
                               'invoices_delta'              => ($website->get('Store 2 Year Ago Invoices') > $website->get('Store 3 Year Ago Invoices')
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get('Store 2 Year Ago Invoices') < $website->get(
                                       'Store 3 Year Ago Invoices'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                           ),
                           array(
                               'header'                      => date(
                                   'Y', strtotime('now -3 year')
                               ),
                               'invoiced_amount_delta_title' => delta(
                                   $website->get(
                                       'Store 3 Year Ago Invoiced Amount'
                                   ), $website->get('Store 4 Year Ago Invoiced Amount')
                               ),
                               'invoiced_amount_delta'       => ($website->get('Store 3 Year Ago Invoiced Amoun') > $website->get(
                                   'Store 4 Year Ago Invoiced Amount'
                               )
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get(
                                       'Store 3 Year Ago Invoiced Amount'
                                   ) < $website->get(
                                       'Store 4 Year Ago Invoiced Amount'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                               'invoices_delta_title'        => delta(
                                   $website->get('Store 3 Year Ago Invoices'), $website->get('Store 4 Year Ago Invoices')
                               ),
                               'invoices_delta'              => ($website->get('Store 3 Year Ago Invoiced Amoun') > $website->get('Store 4 Year Ago Invoices')
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get('Store 3 Year Ago Invoices') < $website->get(
                                       'Store 4 Year Ago Invoices'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                           ),
                           array(
                               'header'                      => date(
                                   'Y', strtotime('now -4 year')
                               ),
                               'invoiced_amount_delta_title' => delta(
                                   $website->get(
                                       'Store 4 Year Ago Invoiced Amount'
                                   ), $website->get('Store 5 Year Ago Invoiced Amount')
                               ),
                               'invoiced_amount_delta'       => ($website->get('Store 4 Year Ago Invoiced Amount') > $website->get(
                                   'Store 5 Year Ago Invoiced Amount'
                               )
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get(
                                       'Store 4 Year Ago Invoiced Amount'
                                   ) < $website->get(
                                       'Store 5 Year Ago Invoiced Amount'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                               'invoices_delta_title'        => delta(
                                   $website->get('Store 4 Year Ago Invoices'), $website->get('Store 5 Year Ago Invoices')
                               ),
                               'invoices_delta'              => ($website->get('Store 4 Year Ago Invoices') > $website->get('Store 5 Year Ago Invoices')
                                   ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                   : ($website->get('Store 4 Year Ago Invoices') < $website->get(
                                       'Store 5 Year Ago Invoices'
                                   ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                           )
                       )
        );
        

        return $smarty->fetch('showcase/website.tpl');

    }




}


?>