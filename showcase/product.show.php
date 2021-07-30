<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 14:12:03 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_product_showcase($data, $smarty, $db) {


    $product = $data['_object'];


    if (!$product->id) {
        return "";
    }

    $product->load_acc_data();
    $product->get_webpage();
    //$product->update_weight();

    //$product->update_availability(false);

    //  if($product->id==30297) {
    //   $product->update_availability();
    //}

    //$product->get_next_deliveries_data();

    //$product->sync_aiku();
    //$product->sync_aiku();
   // exit();

    $images = $product->get_images_slideshow();

    if (count($images) > 0) {
        $main_image = $images[0];
    } else {
        $main_image = '';
    }


    $smarty->assign('main_image', $main_image);
    $smarty->assign('images', $images);


    $department_data=[];
    $sql = sprintf(
        "SELECT `Category Label`,`Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key`=%d ", $product->get('Product Department Category Key')
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $department_data = array(
                'id'    => $row['Category Key'],
                'code'  => $row['Category Code'],
                'label' => $row['Category Label'],
            );
        } else {
            $department_data = array('id' => false);
        }
    }
    $family_data=[];
    $sql = sprintf(
        "SELECT `Category Label`,`Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key`=%d ", $product->get('Product Family Category Key')
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $family_data = array(
                'id'    => $row['Category Key'],
                'code'  => $row['Category Code'],
                'label' => $row['Category Label'],
            );
        } else {
            $family_data = array('id' => false);
        }
    }

    $smarty->assign('product', $product);
    $smarty->assign('department_data', $department_data);
    $smarty->assign('family_data', $family_data);


    $smarty->assign(
        'quarter_data', array(
                          array(
                              'header'                        => get_quarter_label(
                                  strtotime('now')
                              ),
                              'invoiced_amount_delta_title'   => delta(
                                  $product->get(
                                      'Product Quarter To Day Acc Invoiced Amount'
                                  ), $product->get(
                                  'Product Quarter To Day Acc 1YB Invoiced Amount'
                              )
                              ),
                              'invoiced_amount_delta'         => ($product->get(
                                  'Product Quarter To Day Acc Invoiced Amount'
                              ) > $product->get(
                                  'Product Quarter To Day Acc 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product Quarter To Day Acc Invoiced Amount'
                                  ) < $product->get(
                                      'Product Quarter To Day Acc 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'quantity_invoiced_delta_title' => delta(
                                  $product->get(
                                      'Product Quarter To Day Acc Quantity Invoiced'
                                  ), $product->get(
                                  'Product Quarter To Day Acc 1YB Quantity Invoiced'
                              )
                              ),
                              'quantity_invoiced_delta'       => ($product->get(
                                  'Product Quarter To Day Acc Quantity Invoiced'
                              ) > $product->get(
                                  'Product Quarter To Day Acc 1YB Quantity Invoiced'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product Quarter To Day Acc Quantity Invoiced'
                                  ) < $product->get(
                                      'Product Quarter To Day Acc 1YB Quantity Invoiced'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                        => get_quarter_label(
                                  strtotime('now -3 months')
                              ),
                              'invoiced_amount_delta_title'   => delta(
                                  $product->get('Product 1 Quarter Ago Invoiced Amount'), $product->get('Product 1 Quarter Ago 1YB Invoiced Amount')
                              ),
                              'invoiced_amount_delta'         => ($product->get('Product 1 Quarter Ago Invoiced Amount') > $product->get(
                                  'Product 1 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 1 Quarter Ago Invoiced Amount'
                                  ) < $product->get(
                                      'Product 1 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'quantity_invoiced_delta_title' => delta(
                                  $product->get('Product 1 Quarter Ago Quantity Invoiced'), $product->get(
                                  'Product 1 Quarter Ago 1YB Quantity Invoiced'
                              )
                              ),
                              'quantity_invoiced_delta'       => ($product->get('Product 1 Quarter Ago Quantity Invoiced') > $product->get(
                                  'Product 1 Quarter Ago 1YB Quantity Invoiced'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 1 Quarter Ago Quantity Invoiced'
                                  ) < $product->get(
                                      'Product 1 Quarter Ago 1YB Quantity Invoiced'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                        => get_quarter_label(
                                  strtotime('now -6 months')
                              ),
                              'invoiced_amount_delta_title'   => delta(
                                  $product->get('Product 2 Quarter Ago Invoiced Amount'), $product->get('Product 2 Quarter Ago 1YB Invoiced Amount')
                              ),
                              'invoiced_amount_delta'         => ($product->get('Product 2 Quarter Ago Invoiced Amount') > $product->get(
                                  'Product 2 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 2 Quarter Ago Invoiced Amount'
                                  ) < $product->get(
                                      'Product 2 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'quantity_invoiced_delta_title' => delta(
                                  $product->get('Product 2 Quarter Ago Quantity Invoiced'), $product->get(
                                  'Product 2 Quarter Ago 1YB Quantity Invoiced'
                              )
                              ),
                              'quantity_invoiced_delta'       => ($product->get('Product 2 Quarter Ago Quantity Invoiced') > $product->get(
                                  'Product 2 Quarter Ago 1YB Quantity Invoiced'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 2 Quarter Ago Quantity Invoiced'
                                  ) < $product->get(
                                      'Product 2 Quarter Ago 1YB Quantity Invoiced'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                          ),
                          array(
                              'header'                        => get_quarter_label(
                                  strtotime('now -9 months')
                              ),
                              'invoiced_amount_delta_title'   => delta(
                                  $product->get('Product 3 Quarter Ago Invoiced Amount'), $product->get('Product 3 Quarter Ago 1YB Invoiced Amount')
                              ),
                              'invoiced_amount_delta'         => ($product->get('Product 3 Quarter Ago Invoiced Amount') > $product->get(
                                  'Product 3 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 3 Quarter Ago Invoiced Amount'
                                  ) < $product->get(
                                      'Product 3 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'quantity_invoiced_delta_title' => delta(
                                  $product->get('Product 3 Quarter Ago Quantity Invoiced'), $product->get(
                                  'Product 3 Quarter Ago 1YB Quantity Invoiced'
                              )
                              ),
                              'quantity_invoiced_delta'       => ($product->get('Product 3 Quarter Ago Quantity Invoiced') > $product->get(
                                  'Product 3 Quarter Ago 1YB Quantity Invoiced'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 3 Quarter Ago Quantity Invoiced'
                                  ) < $product->get(
                                      'Product 3 Quarter Ago 1YB Quantity Invoiced'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                          ),
                          array(
                              'header'                        => get_quarter_label(
                                  strtotime('now -12 months')
                              ),
                              'invoiced_amount_delta_title'   => delta(
                                  $product->get('Product 4 Quarter Ago Invoiced Amount'), $product->get('Product 4 Quarter Ago 1YB Invoiced Amount')
                              ),
                              'invoiced_amount_delta'         => ($product->get('Product 4 Quarter Ago Invoiced Amount') > $product->get(
                                  'Product 4 Quarter Ago 1YB Invoiced Amount'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 4 Quarter Ago Invoiced Amount'
                                  ) < $product->get(
                                      'Product 4 Quarter Ago 1YB Invoiced Amount'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                              'quantity_invoiced_delta_title' => delta(
                                  $product->get('Product 4 Quarter Ago Quantity Invoiced'), $product->get(
                                  'Product 4 Quarter Ago 1YB Quantity Invoiced'
                              )
                              ),
                              'quantity_invoiced_delta'       => ($product->get('Product 4 Quarter Ago Quantity Invoiced') > $product->get(
                                  'Product 4 Quarter Ago 1YB Quantity Invoiced'
                              )
                                  ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                                  : ($product->get(
                                      'Product 4 Quarter Ago Quantity Invoiced'
                                  ) < $product->get(
                                      'Product 4 Quarter Ago 1YB Quantity Invoiced'
                                  ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                          ),
                      )
    );
    $smarty->assign(
        'year_data', array(
                       array(
                           'header'                        => date(
                               'Y', strtotime('now')
                           ),
                           'invoiced_amount_delta_title'   => delta(
                               $product->get('Product Year To Day Acc Invoiced Amount'), $product->get(
                               'Product Year To Day Acc 1YB Invoiced Amount'
                           )
                           ),
                           'invoiced_amount_delta'         => ($product->get('Product Year To Day Acc Invoiced Amount') > $product->get(
                               'Product Year To Day Acc 1YB Invoiced Amount'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get(
                                   'Product Year To Day Acc Invoiced Amount'
                               ) < $product->get(
                                   'Product Year To Day Acc 1YB Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'quantity_invoiced_delta_title' => delta(
                               $product->get(
                                   'Product Year To Day Acc Quantity Invoiced'
                               ), $product->get(
                               'Product Year To Day Acc 1YB Quantity Invoiced'
                           )
                           ),
                           'quantity_invoiced_delta'       => ($product->get('Product Year To Day Acc Quantity Invoiced') > $product->get(
                               'Product Year To Day Acc 1YB Quantity Invoiced'
                           )
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get(
                                   'Product Year To Day Acc Quantity Invoiced'
                               ) < $product->get(
                                   'Product Year To Day Acc 1YB Quantity Invoiced'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                        => date(
                               'Y', strtotime('now -1 year')
                           ),
                           'invoiced_amount_delta_title'   => delta(
                               $product->get('Product 1 Year Ago Invoiced Amount'), $product->get('Product 2 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'         => ($product->get('Product 1 Year Ago Invoiced Amount') > $product->get('Product 2 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 1 Year Ago Invoiced Amount') < $product->get(
                                   'Product 2 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'quantity_invoiced_delta_title' => delta(
                               $product->get('Product 1 Year Ago Quantity Invoiced'), $product->get('Product 2 Year Ago Quantity Invoiced')
                           ),
                           'quantity_invoiced_delta'       => ($product->get('Product 1 Year Ago Quantity Invoiced') > $product->get('Product 2 Year Ago Quantity Invoiced')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 1 Year Ago Quantity Invoiced') < $product->get(
                                   'Product 2 Year Ago Quantity Invoiced'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       ),
                       array(
                           'header'                        => date(
                               'Y', strtotime('now -2 year')
                           ),
                           'invoiced_amount_delta_title'   => delta(
                               $product->get('Product 2 Year Ago Invoiced Amount'), $product->get('Product 3 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'         => ($product->get('Product 2 Year Ago Invoiced Amount') > $product->get('Product 3 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 2 Year Ago Invoiced Amount') < $product->get(
                                   'Product 3 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'quantity_invoiced_delta_title' => delta(
                               $product->get('Product 2 Year Ago Quantity Invoiced'), $product->get('Product 3 Year Ago Quantity Invoiced')
                           ),
                           'quantity_invoiced_delta'       => ($product->get('Product 2 Year Ago Quantity Invoiced') > $product->get('Product 3 Year Ago Quantity Invoiced')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 2 Year Ago Quantity Invoiced') < $product->get(
                                   'Product 3 Year Ago Quantity Invoiced'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       ),
                       array(
                           'header'                        => date(
                               'Y', strtotime('now -3 year')
                           ),
                           'invoiced_amount_delta_title'   => delta(
                               $product->get('Product 3 Year Ago Invoiced Amount'), $product->get('Product 4 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'         => ($product->get('Product 3 Year Ago Invoiced Amount') > $product->get('Product 4 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 3 Year Ago Invoiced Amount') < $product->get(
                                   'Product 4 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'quantity_invoiced_delta_title' => delta(
                               $product->get('Product 3 Year Ago Quantity Invoiced'), $product->get('Product 4 Year Ago Quantity Invoiced')
                           ),
                           'quantity_invoiced_delta'       => ($product->get('Product 3 Year Ago Invoiced Amount') > $product->get('Product 4 Year Ago Quantity Invoiced')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 3 Year Ago Quantity Invoiced') < $product->get(
                                   'Product 4 Year Ago Quantity Invoiced'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

                       ),
                       array(
                           'header'                        => date(
                               'Y', strtotime('now -4 year')
                           ),
                           'invoiced_amount_delta_title'   => delta(
                               $product->get('Product 4 Year Ago Invoiced Amount'), $product->get('Product 5 Year Ago Invoiced Amount')
                           ),
                           'invoiced_amount_delta'         => ($product->get('Product 4 Year Ago Invoiced Amount') > $product->get('Product 5 Year Ago Invoiced Amount')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 4 Year Ago Invoiced Amount') < $product->get(
                                   'Product 5 Year Ago Invoiced Amount'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                           'quantity_invoiced_delta_title' => delta(
                               $product->get('Product 4 Year Ago Quantity Invoiced'), $product->get('Product 5 Year Ago Quantity Invoiced')
                           ),
                           'quantity_invoiced_delta'       => ($product->get('Product 4 Year Ago Quantity Invoiced') > $product->get('Product 5 Year Ago Quantity Invoiced')
                               ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                               : ($product->get('Product 4 Year Ago Quantity Invoiced') < $product->get(
                                   'Product 5 Year Ago Quantity Invoiced'
                               ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
                       )
                   )
    );

    $customers       = sprintf(
        '<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s (%s)', $product->get('Total Acc Customers'), percentage(
                                                                                    $product->get('Product Total Acc Repeat Customers'), $product->get('Product Total Acc Customers')
                                                                                )
    );
    $smarty->assign('customers', $customers);

    $smarty->assign(
        'header_total_sales', sprintf(_('All sales since: %s'), $product->get('Valid From'))
    );


    return $smarty->fetch('showcase/product.tpl');


}

