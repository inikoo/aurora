<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 09:35:34 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'stores':
        stores(get_table_parameters(), $db, $user);
        break;

    case 'products':
        products(get_table_parameters(), $db, $user, $account);
        break;
    case 'services':
        services(get_table_parameters(), $db, $user);
        break;
    case 'categories':
        categories(get_table_parameters(), $db, $user);
        break;

    case 'category_all_products':
        category_all_products(get_table_parameters(), $db, $user);
        break;
    case 'sales_history':
        sales_history(get_table_parameters(), $db, $user, $account);
        break;
    case 'parts':
        parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'product_categories':
        product_categories(get_table_parameters(), $db, $user, $account);
        break;
    case 'product_families':
        product_families(get_table_parameters(), $db, $user, $account);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function stores($_data, $db, $user) {


    $rtext_label = 'store';

    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;
    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'access' => (in_array($data['Store Key'], $user->stores) ? '' : '<i class="fa fa-lock "></i>'),

            'id'   => (integer)$data['Store Key'],
            'code' => $data['Store Code'],
            'name' => $data['Store Name'],

        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function products($_data, $db, $user, $account) {


    if ($_data['parameters']['parent'] == 'customer_favourites') {
        $rtext_label = 'product favourited';
    } else {
        $rtext_label = 'product';
    }


    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']
            );

            switch ($data['Product Status']) {
                case 'Active':
                    $status = _('Active');
                    break;
                case 'Suspended':
                    $status = _('Suspended');
                    break;
                case 'Discontinued':
                    $status = _('Discontinued');
                    break;
                default:
                    $status = $data['Product Status'];
                    break;
            }

            switch ($data['Product Web Configuration']) {
                case 'Online Auto':
                    $web_configuration = _('Automatic');
                    break;
                case 'Online Force For Sale':
                    $web_configuration = _('For sale').' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>';
                    break;
                case 'Online Force Out of Stock':
                    $web_configuration = _('Out of Stock').' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>';
                    break;
                case 'Offline':
                    $web_configuration = _('Offline');
                    break;
                default:
                    $web_configuration = $data['Product Web Configuration'];
                    break;
            }

            switch ($data['Product Web State']) {
                case 'For Sale':
                    $web_state = '<span class="'.(($data['Product Availability'] <= 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Online').'</span>'
                        .($data['Product Web Configuration'] == 'Online Force For Sale' ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    break;
                case 'Out of Stock':
                    $web_state = '<span  class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Out of Stock').'</span>'
                        .($data['Product Web Configuration'] == 'Online Force Out of Stock' ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    break;
                case 'Discontinued':
                    $web_state = _('Discontinued');
                    break;
                case 'Offline':

                    if ($data['Product Status'] != 'Active') {
                        $web_state = _('Offline');
                    } else {

                        $web_state = '<span class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Offline').'</span>'.($data['Product Status']
                            == 'Active' ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    }
                    break;
                default:
                    $web_state = $data['Product Web State'];
                    break;
            }


            $adata[] = array(

                'id'               => (integer)$data['Product ID'],
                'store_key'        => (integer)$data['Store Key'],
                'associated'       => $associated,
                'store'            => $data['Store Code'],
                'code'             => $data['Product Code'],
                'name'             => $data['Product Units Per Case'].'x '.$data['Product Name'],
                'price'            => money(
                    $data['Product Price'], $data['Store Currency Code']
                ),
                'margin'           => '<span title="'._('Cost price').':'.money(
                        $data['Product Cost'], $account->get('Account Currency')
                    ).'">'.percentage(
                        $data['Product Price'] - $data['Product Cost'], $data['Product Price']
                    ).'<span>',
                'web_state'        => $web_state,
                'status'           => $status,
                'sales'            => money(
                    $data['sales'], $data['Store Currency Code']
                ),
                'sales_1yb'        => delta($data['sales'], $data['sales_1yb']),
                'qty_invoiced'     => number($data['qty_invoiced']),
                'qty_invoiced_1yb' => delta(
                    $data['qty_invoiced'], $data['qty_invoiced_1yb']
                ),


                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Year To Day Acc Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product Year To Day Acc Invoiced Amount"], $data["Product Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 1 Year Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 1 Year Ago Invoiced Amount"], $data["Product 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 2 Year Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 2 Year Ago Invoiced Amount"], $data["Product 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 3 Year Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 3 Year Ago Invoiced Amount"], $data["Product 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 4 Year Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 4 Year Ago Invoiced Amount"], $data["Product 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Quarter To Day Acc Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product Quarter To Day Acc Invoiced Amount"], $data["Product Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 1 Quarter Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 1 Quarter Ago Invoiced Amount"], $data["Product 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 2 Quarter Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 2 Quarter Ago Invoiced Amount"], $data["Product 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 3 Quarter Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 3 Quarter Ago Invoiced Amount"], $data["Product 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product 4 Quarter Ago Invoiced Amount'], $data['Store Currency Code']
                ), delta_icon(
                        $data["Product 4 Quarter Ago Invoiced Amount"], $data["Product 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


                'sales_total'                      => money(
                    $data['Product Total Acc Invoiced Amount'], $data['Store Currency Code']
                ),
                'dispatched_total'                 => number(
                    $data['Product Total Acc Quantity Invoiced'], 0
                ),
                'customer_total'                   => number(
                    $data['Product Total Acc Customers'], 0
                ),
                'percentage_repeat_customer_total' => percentage(
                    $data['Product Total Acc Repeat Customers'], $data['Product Total Acc Customers']
                ),


            );


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function services($_data, $db, $user) {


    $rtext_label = 'service';


    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']
            );


            $adata[] = array(

                'id'         => (integer)$data['Product ID'],
                'store_key'  => (integer)$data['Store Key'],
                'associated' => $associated,
                'store'      => $data['Store Code'],
                'code'       => $data['Product Code'],
                'name'       => $data['Product Name'],
                'price'      => money(
                    $data['Product Price'], $data['Store Currency Code']
                ),
            );


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function categories($_data, $db, $user) {

    $rtext_label = 'category';
    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Category Branch Type']) {
                case 'Root':
                    $level = _('Root');
                    break;
                case 'Head':
                    $level = _('Head');
                    break;
                case 'Node':
                    $level = _('Node');
                    break;
                default:
                    $level = $data['Category Branch Type'];
                    break;
            }
            $level = $data['Category Branch Type'];


            $adata[] = array(
                'id'                  => (integer)$data['Category Key'],
                'store_key'           => (integer)$data['Category Store Key'],
                'code'                => $data['Category Code'],
                'label'               => $data['Category Label'],
                'subjects'            => number(
                    $data['Category Number Subjects']
                ),
                'level'               => $level,
                'subcategories'       => number($data['Category Children']),
                'percentage_assigned' => percentage(
                    $data['Category Number Subjects'], ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])
                )
            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function category_all_products($_data, $db, $user) {


    $rtext_label = 'product';

    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $adata = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            if ($data['associated']) {
                $associated = sprintf(
                    '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']
                );
            } else {
                $associated = sprintf(
                    '<i key="%d" class="fa fa-fw fa-unlink button very_discreet" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']
                );
            }


            $adata[] = array(
                'id'         => (integer)$data['Product ID'],
                'associated' => $associated,
                'code'       => $data['Product Code'],
                'name'       => $data['Product Name'],
                'price'      => money(
                    $data['Product Price'], $data['Store Currency Code']
                ),
                'family'     => $data['Category Code']
            );
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function sales_history($_data, $db, $user, $account) {


    $skip_get_table_totals = true;

    include_once 'prepare_table/init.php';
    include_once 'utils/natural_language.php';
    include_once 'class.Store.php';


    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label       = 'year';
        $_group_by         = ' group by Year(`Date`) ';
        $sql_totals_fields = 'Year(`Date`)';
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label       = 'month';
        $_group_by         = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
        $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label       = 'week';
        $_group_by         = ' group by Yearweek(`Date`) ';
        $sql_totals_fields = 'Yearweek(`Date`)';
    } elseif ($_data['parameters']['frequency'] == 'daily') {
        $rtext_label = 'day';

        $_group_by         = ' group by Date(`Date`) ';
        $sql_totals_fields = '`Date`';
    }

    switch ($_data['parameters']['parent']) {
        case 'product':
            include_once 'class.Product.php';
            $product    = new Product($_data['parameters']['parent_key']);
            $currency   = $product->get('Product Currency');
            $from       = $product->get('Product Valid From');
            $to         = ($product->get('Product Status') == 'Discontinued' ? $product->get('Product Valid To') : gmdate('Y-m-d'));
            $date_field = '`Invoice Date`';
            break;
        case 'category':
            include_once 'class.Category.php';
            $category   = new Category($_data['parameters']['parent_key']);
            $currency   = $category->get('Product Category Currency Code');
            $from       = $category->get('Product Category Valid From');
            $to         = ($category->get('Product Category Status') == 'Discontinued' ? $product->get('Product Category Valid To') : gmdate('Y-m-d'));
            $date_field = '`Timeseries Record Date`';
            break;
        default:
            print_r($_data);
            exit('parent not configurated');
            break;
    }


    $sql_totals = sprintf(
        'SELECT count(DISTINCT %s) AS num FROM kbase.`Date Dimension` WHERE `Date`>=DATE(%s) AND `Date`<=DATE(%s) ', $sql_totals_fields, prepare_mysql($from), prepare_mysql($to)

    );
    list($rtext, $total, $filtered) = get_table_totals(
        $db, $sql_totals, '', $rtext_label, false
    );


    $sql = sprintf(
        'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) %s ORDER BY %s  LIMIT %s', prepare_mysql($from), prepare_mysql($to), $_group_by,
        "`Date` $order_direction ", "$start_from,$number_results"
    );
    //print $sql;


    $adata = array();

    $from_date = '';
    $to_date   = '';
    if ($result = $db->query($sql)) {


        foreach ($result as $data) {

            if ($to_date == '') {
                $to_date = $data['Date'];
            }
            $from_date = $data['Date'];

            if ($_data['parameters']['frequency'] == 'annually') {
                $date  = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $date  = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime(
                    "(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime(
                    "%a %e %b %Y", strtotime($data['Date'].' +0:00')
                );
                $_date = $date;
            }

            $adata[$_date] = array(
                'sales'     => '<span class="very_discreet">'.money(
                        0, $currency
                    ).'</span>',
                'customers' => '<span class="very_discreet">'.number(0).'</span>',
                'invoices'  => '<span class="very_discreet">'.number(0).'</span>',
                'date'      => $date


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    switch ($_data['parameters']['parent']) {
        case 'product':
            if ($_data['parameters']['frequency'] == 'annually') {
                $from_date = gmdate(
                    "Y-01-01 00:00:00", strtotime($from_date.' +0:00')
                );
                $to_date   = gmdate(
                    "Y-12-31 23:59:59", strtotime($to_date.' +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate(
                    "Y-m-01 00:00:00", strtotime($from_date.' +0:00')
                );
                $to_date   = gmdate(
                    "Y-m-01 00:00:00", strtotime($to_date.' + 1 month +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate(
                    "Y-m-d 00:00:00", strtotime($from_date.'  -1 week  +0:00')
                );
                $to_date   = gmdate(
                    "Y-m-d 00:00:00", strtotime($to_date.' + 1 week +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = $from_date.' 00:00:00';
                $to_date   = $to_date.' 23:59:59';
            }
            break;
        case 'category':
            if ($_data['parameters']['frequency'] == 'annually') {
                $from_date = gmdate("Y-01-01", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-12-31", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate("Y-m-01", strtotime($from_date.' +0:00'));
                $to_date   = gmdate(
                    "Y-m-01", strtotime($to_date.' + 1 month +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate(
                    "Y-m-d", strtotime($from_date.'  -1 week  +0:00')
                );
                $to_date   = gmdate(
                    "Y-m-d", strtotime($to_date.' + 1 week +0:00')
                );
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = $from_date.'';
                $to_date   = $to_date.'';
            }

            break;
        default:
            print_r($_data);
            exit('parent not configurated');
            break;
    }


    $sql = sprintf(
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s", $date_field, prepare_mysql($from_date), $date_field, prepare_mysql($to_date), " $group_by "
    );

    //print $sql;
    if ($result = $db->query($sql)) {


        foreach ($result as $data) {
            if ($_data['parameters']['frequency'] == 'annually') {
                $date  = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $date  = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime(
                    "(%e %b) %Y %W ", strtotime($data['Invoice Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime(
                    "%a %e %b %Y", strtotime($data['Date'].' +0:00')
                );
                $_date = $date;
            }

            if (array_key_exists($_date, $adata)) {

                $adata[$_date] = array(
                    'sales'     => money($data['sales'], $currency),
                    'customers' => number($data['customers']),
                    'invoices'  => number($data['invoices']),
                    'date'      => $date


                );
            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => array_values($adata),
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function parts($_data, $db, $user, $account) {


    if (!$user->can_view('stores')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'part';


    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status
                                        = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Surplus');
                    break;
                case 'Optimal':
                    $stock_status
                                        = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Ok');
                    break;
                case 'Low':
                    $stock_status
                                        = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Low');
                    break;
                case 'Critical':
                    $stock_status
                                        = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Critical');
                    break;
                case 'Out_Of_Stock':
                    $stock_status
                                        = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Out of stock');
                    break;
                case 'Error':
                    $stock_status
                                        = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Error');
                    break;
                default:
                    $stock_status       = $data['Part Stock Status'];
                    $stock_status_label = $data['Part Stock Status'];
                    break;
            }


            if ($data['Part Current Stock'] <= 0) {
                $weeks_available = '-';
            } else {
                $weeks_available = number(
                    $data['Part Days Available Forecast'] / 7, 0
                );
            }

            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0
            );


            $adata[] = array(
                'id'                  => (integer)$data['Part SKU'],
                'reference'           => $data['Part Reference'],
                'package_description' => $data['Part Package Description'],
                'picking_ratio'       => number($data['Product Part Ratio'], 5),
                'picking_note'        => $data['Product Part Note'],
                'stock_status'        => $stock_status,
                'stock_status_label'  => $stock_status_label,
                'stock'               => '<span  class="  '.($data['Part Current Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current Stock']))
                    .'</span>  <i class="fa fa-fighter-jet padding_left_5 super_discreet  '.($data['Part On Demand'] == 'Yes' ? '' : 'invisible').' " title='._('On demand')
                    .' aria-hidden="true"></i>     ',
                'weeks_available'     => $weeks_available,
                'dispatched_per_week' => $dispatched_per_week
            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function product_categories($_data, $db, $user) {

    include_once 'class.Category.php';
    include_once 'class.Store.php';

    $parent = new Category($_data['parameters']['parent_key']);
    $store  = new Store($parent->get('Category Store Key'));
    if ($store->get('Store Family Category Key') == $parent->get(
            'Category Root Key'
        )
    ) {
        $rtext_label = 'family';
    } elseif ($store->get('Store Department Category Key') == $parent->get(
            'Category Root Key'
        )
    ) {
        $rtext_label = 'department';
    } else {
        $rtext_label = 'category';
    }

    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Product Category Status']) {
                case 'In Process':
                    $status = _('Empty');
                    break;
                case 'Active':
                    $status = _('Active');
                    break;
                case 'Suspended':
                    $status = _('Suspended');
                    break;
                case 'Discontinued':
                    $status = _('Discontinued');
                    break;
                case 'Discontinuing':
                    $status = _('Discontinuing');
                    break;
                default:
                    $status = $data['Product Category Status'];
                    break;
            }


            $adata[] = array(
                'id'               => (integer)$data['Product Category Key'],
                'store_key'        => (integer)$data['Category Store Key'],
                'code'             => $data['Category Code'],
                'label'            => $data['Category Label'],
                'status'           => $status,
                'products'         => number($data['products']),
                'in_process'       => number(
                    $data['Product Category In Process Products']
                ),
                'active'           => number(
                    $data['Product Category Active Products']
                ),
                'suspended'        => number(
                    $data['Product Category Suspended Products']
                ),
                'discontinuing'    => number(
                    $data['Product Category Discontinuing Products']
                ),
                'discontinued'     => number(
                    $data['Product Category Discontinued Products']
                ),
                'sales'            => money(
                    $data['sales'], $data['Product Category Currency Code']
                ),
                'sales_1yb'        => delta($data['sales'], $data['sales_1yb']),
                'qty_invoiced'     => number($data['qty_invoiced']),
                'qty_invoiced_1yb' => delta(
                    $data['qty_invoiced'], $data['qty_invoiced_1yb']
                ),


                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category Year To Day Acc Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category Year To Day Acc Invoiced Amount"], $data["Product Category Year To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 1 Year Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 1 Year Ago Invoiced Amount"], $data["Product Category 2 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 2 Year Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 2 Year Ago Invoiced Amount"], $data["Product Category 3 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 3 Year Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 3 Year Ago Invoiced Amount"], $data["Product Category 4 Year Ago Invoiced Amount"]
                    )
                ),
                'sales_year4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 4 Year Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 4 Year Ago Invoiced Amount"], $data["Product Category 5 Year Ago Invoiced Amount"]
                    )
                ),

                'sales_quarter0' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category Quarter To Day Acc Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category Quarter To Day Acc Invoiced Amount"], $data["Product Category Quarter To Day Acc 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter1' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 1 Quarter Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 1 Quarter Ago Invoiced Amount"], $data["Product Category 1 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter2' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 2 Quarter Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 2 Quarter Ago Invoiced Amount"], $data["Product Category 2 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter3' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 3 Quarter Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 3 Quarter Ago Invoiced Amount"], $data["Product Category 3 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),
                'sales_quarter4' => sprintf(
                    '<span>%s</span> %s', money(
                    $data['Product Category 4 Quarter Ago Invoiced Amount'], $data['Product Category Currency Code']
                ), delta_icon(
                        $data["Product Category 4 Quarter Ago Invoiced Amount"], $data["Product Category 4 Quarter Ago 1YB Invoiced Amount"]
                    )
                ),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print " <br>\n $sql \n";

        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function product_families($_data, $db, $user) {

    include_once 'class.Category.php';
    include_once 'class.Store.php';

    $parent = new Category($_data['parameters']['parent_key']);
    $store  = new Store($parent->get('Category Store Key'));

    if ($store->get('Store Department Category Key') == $parent->get(
            'Category Root Key'
        )
    ) {
        $rtext_label = 'family';
    } else {
        $rtext_label = 'category';
    }

    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'id'        => (integer)$data['Category Key'],
            'store_key' => (integer)$data['Category Store Key'],
            'code'      => $data['Category Code'],
            'label'     => $data['Category Label'],
        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
