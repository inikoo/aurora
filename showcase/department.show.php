<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 February 2016 at 17:33:42 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';


function get_department_showcase($data, $smarty) {

    $category = $data['_object'];
//$category->update_product_category_products_data();

    if (!$category->id) {
        return "";
    }

    $category->load_acc_data();

    $smarty->assign('category', $category);

    $images = $category->get_images_slidesshow();

    if (count($images) > 0) {
        $main_image = $images[0];
    } else {
        $main_image = '';
    }


    $smarty->assign('main_image', $main_image);
    $smarty->assign('images', $images);

    $smarty->assign(
        'quarter_data', array(
            array(
                'header'                        => get_quarter_label(
                    strtotime('now')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category Quarter To Day Acc Invoiced Amount'
                    ), $category->get(
                    'Product Category Quarter To Day Acc 1YB Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category Quarter To Day Acc Invoiced Amount'
                ) > $category->get(
                    'Product Category Quarter To Day Acc 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category Quarter To Day Acc Invoiced Amount'
                    ) < $category->get(
                        'Product Category Quarter To Day Acc 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category Quarter To Day Acc Quantity Invoiced'
                    ), $category->get(
                    'Product Category Quarter To Day Acc 1YB Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category Quarter To Day Acc Quantity Invoiced'
                ) > $category->get(
                    'Product Category Quarter To Day Acc 1YB Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category Quarter To Day Acc Quantity Invoiced'
                    ) < $category->get(
                        'Product Category Quarter To Day Acc 1YB Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                        => get_quarter_label(
                    strtotime('now -3 months')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 1 Quarter Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 1 Quarter Ago 1YB Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 1 Quarter Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 1 Quarter Ago 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 1 Quarter Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 1 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 1 Quarter Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 1 Quarter Ago 1YB Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 1 Quarter Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 1 Quarter Ago 1YB Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 1 Quarter Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 1 Quarter Ago 1YB Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                        => get_quarter_label(
                    strtotime('now -6 months')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 2 Quarter Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 2 Quarter Ago 1YB Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 2 Quarter Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 2 Quarter Ago 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 2 Quarter Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 2 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 2 Quarter Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 2 Quarter Ago 1YB Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 2 Quarter Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 2 Quarter Ago 1YB Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 2 Quarter Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 2 Quarter Ago 1YB Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                        => get_quarter_label(
                    strtotime('now -9 months')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 3 Quarter Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 3 Quarter Ago 1YB Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 3 Quarter Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 3 Quarter Ago 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 3 Quarter Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 3 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 3 Quarter Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 3 Quarter Ago 1YB Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 3 Quarter Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 3 Quarter Ago 1YB Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 3 Quarter Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 3 Quarter Ago 1YB Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
            array(
                'header'                        => get_quarter_label(
                    strtotime('now -12 months')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 4 Quarter Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 4 Quarter Ago 1YB Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 4 Quarter Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 4 Quarter Ago 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 4 Quarter Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 4 Quarter Ago 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 4 Quarter Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 4 Quarter Ago 1YB Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 4 Quarter Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 4 Quarter Ago 1YB Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 4 Quarter Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 4 Quarter Ago 1YB Quantity Invoiced'
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
                    $category->get(
                        'Product Category Year To Day Acc Invoiced Amount'
                    ), $category->get(
                    'Product Category Year To Day Acc 1YB Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category Year To Day Acc Invoiced Amount'
                ) > $category->get(
                    'Product Category Year To Day Acc 1YB Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category Year To Day Acc Invoiced Amount'
                    ) < $category->get(
                        'Product Category Year To Day Acc 1YB Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category Year To Day Acc Quantity Invoiced'
                    ), $category->get(
                    'Product Category Year To Day Acc 1YB Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category Year To Day Acc Quantity Invoiced'
                ) > $category->get(
                    'Product Category Year To Day Acc 1YB Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category Year To Day Acc Quantity Invoiced'
                    ) < $category->get(
                        'Product Category Year To Day Acc 1YB Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                        => date(
                    'Y', strtotime('now -1 year')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 1 Year Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 2 Year Ago Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 1 Year Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 2 Year Ago Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 1 Year Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 2 Year Ago Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 1 Year Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 2 Year Ago Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 1 Year Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 2 Year Ago Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 1 Year Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 2 Year Ago Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
            array(
                'header'                        => date(
                    'Y', strtotime('now -2 year')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 2 Year Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 3 Year Ago Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 2 Year Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 3 Year Ago Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 2 Year Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 3 Year Ago Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 2 Year Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 3 Year Ago Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 2 Year Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 3 Year Ago Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 2 Year Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 3 Year Ago Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            ),
            array(
                'header'                        => date(
                    'Y', strtotime('now -3 year')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 3 Year Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 4 Year Ago Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 3 Year Ago Invoiced Amoun'
                ) > $category->get(
                    'Product Category 4 Year Ago Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 3 Year Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 4 Year Ago Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 3 Year Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 4 Year Ago Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 3 Year Ago Invoiced Amoun'
                ) > $category->get(
                    'Product Category 4 Year Ago Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 3 Year Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 4 Year Ago Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))

            ),
            array(
                'header'                        => date(
                    'Y', strtotime('now -4 year')
                ),
                'invoiced_amount_delta_title'   => delta(
                    $category->get(
                        'Product Category 4 Year Ago Invoiced Amount'
                    ), $category->get(
                    'Product Category 5 Year Ago Invoiced Amount'
                )
                ),
                'invoiced_amount_delta'         => ($category->get(
                    'Product Category 4 Year Ago Invoiced Amount'
                ) > $category->get(
                    'Product Category 5 Year Ago Invoiced Amount'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 4 Year Ago Invoiced Amount'
                    ) < $category->get(
                        'Product Category 5 Year Ago Invoiced Amount'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : '')),
                'quantity_invoiced_delta_title' => delta(
                    $category->get(
                        'Product Category 4 Year Ago Quantity Invoiced'
                    ), $category->get(
                    'Product Category 5 Year Ago Quantity Invoiced'
                )
                ),
                'quantity_invoiced_delta'       => ($category->get(
                    'Product Category 4 Year Ago Quantity Invoiced'
                ) > $category->get(
                    'Product Category 5 Year Ago Quantity Invoiced'
                )
                    ? '<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>'
                    : ($category->get(
                        'Product Category 4 Year Ago Quantity Invoiced'
                    ) < $category->get(
                        'Product Category 5 Year Ago Quantity Invoiced'
                    ) ? '<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>' : ''))
            )
        )
    );
    $customers = sprintf(
        '<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s (%s)', $category->get('Total Acc Customers'), percentage(
            $category->get('Product Category Total Acc Repeat Customers'), $category->get('Product Category Total Acc Customers')
        )
    );
    $customers = sprintf(
        '<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s', $category->get('Total Acc Customers')
    );

    $smarty->assign('customers', $customers);

    $smarty->assign(
        'header_total_sales', sprintf(_('All sales since: %s'), $category->get('Valid From'))
    );


    return $smarty->fetch('showcase/department.tpl');


}


?>
