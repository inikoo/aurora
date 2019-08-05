<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 09:35:34 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


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
    case 'back_to_stock_notification_request.products':
        back_to_stock_notification_request_products(get_table_parameters(), $db, $user, $account);
        break;
    case 'back_to_stock_notification_request.customers':
        back_to_stock_notification_request_customers(get_table_parameters(), $db, $user, $account);
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
    case 'product_categories_categories':
        product_categories_categories(get_table_parameters(), $db, $user, $account);
        break;
    case 'product_categories_products':
        product_categories_products(get_table_parameters(), $db, $user, $account);
        break;
    case 'charges':
        charges(get_table_parameters(), $db, $user);
        break;
    case 'shipping_zones':
        shipping_zones(get_table_parameters(), $db, $user);
        break;
    case 'shipping_zones_schemas':
        shipping_zones_schemas(get_table_parameters(), $db, $user);
        break;
    case 'shipping_options':
        shipping_options(get_table_parameters(), $db, $user);
        break;
    case 'customers':
        customers(get_table_parameters(), $db, $user);
        break;
    case 'user_notifications':
        user_notifications(get_table_parameters(), $db, $user);
        break;
    case 'category_correlations':
        category_sales_correlations(get_table_parameters(), $db, $user);
        break;
    case 'product_correlations':
        product_sales_correlations(get_table_parameters(), $db, $user,$type='correlation');
        break;
    case 'product_anticorrelations':
        product_sales_correlations(get_table_parameters(), $db, $user,$type='anticorrelation');
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

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    foreach ($db->query($sql) as $data) {


        $record_data[] = array(
            'access' => (in_array($data['Store Key'], $user->stores) ? '' : '<i class="fa fa-lock "></i>'),

            'id'      => (integer)$data['Store Key'],
            'code'    => sprintf('<span class="link" onClick="change_view(\'store/%d\')" >%s</span>', $data['Store Key'], $data['Store Code']),
            'name'    => sprintf('<span class="link" onClick="change_view(\'store/%d\')" >%s</span>', $data['Store Key'], $data['Store Name']),
            'website' => sprintf('<span class="link" onClick="change_view(\'store/%d/website\')" title="%s" >%s</span>', $data['Store Key'], $data['Website Name'], $data['Website Code']),

            'in_process'    => number($data['Store New Products']),
            'active'        => number($data['Store Active Products']),
            'discontinuing' => number($data['Store Discontinuing Products']),
            'discontinued'  => number($data['Store Discontinued Products']),

        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function products($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';

    if ($_data['parameters']['parent'] == 'customer_favourites') {
        $rtext_label = 'product favourited';
    } else {
        $rtext_label = 'product';
    }

    if ($_data['parameters']['parent'] == 'part') {
        include_once 'class.Product.php';

    } elseif ($_data['parameters']['parent'] == 'category') {
        include_once 'class.Category.php';
        $category = new Category($_data['parameters']['parent_key']);

        $path = sprintf('category/%s/', $category->get('Category Position'));
    }

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']
            );

            switch ($data['Product Status']) {
                case 'Active':
                    $status = sprintf('<i class="fa fa-cube" aria-hidden="true" title="%s"></i>', _('Active'));
                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-cube warning" aria-hidden="true" title="%s"></i>', _('Suspended'));
                    break;
                case 'Discontinued':
                    $status = sprintf('<i class="fa fa-cube very_discreet" aria-hidden="true" title="%s"></i>', _('Discontinued'));
                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-cube error" aria-hidden="true" title="%s"></i>', _('Suspended'));
                    break;
                default:
                    $status = $data['Product Status'];
                    break;
            }

            /*
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
*/
            switch ($data['Product Web State']) {
                case 'For Sale':
                    $web_state = '<span class="'.(($data['Product Availability'] <= 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Online').'</span>'.($data['Product Web Configuration'] == 'Online Force For Sale'
                            ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    break;
                case 'Out of Stock':


                    $web_state = '<span  class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Out of Stock').'</span>'.($data['Product Web Configuration'] == 'Online Force Out of Stock'
                            ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');


                    break;
                case 'Discontinued':
                    $web_state = _('Discontinued');
                    break;
                case 'Offline':

                    if ($data['Product Status'] != 'Active') {
                        $web_state = _('Offline');
                    } else {

                        $web_state = '<span class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Offline').'</span>'.($data['Product Status'] == 'Active'
                                ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    }
                    break;
                default:
                    $web_state = $data['Product Web State'];
                    break;
            }


            if ($data['Store Currency Code'] != $account->get('Account Currency')) {

                $exchange = currency_conversion(
                    $db, $data['Store Currency Code'], $account->get('Account Currency'), '- 180 minutes'
                );

            } else {
                $exchange = 1;
            }


            if ($_data['parameters']['parent'] == 'part') {

                $product = new Product('id', $data['Product ID']);
                $parts   = $product->get('Parts');
            } else {

                $parts = '';
            }


            if ($data['Product RRP'] == '') {
                $rrp = '';
            } else {
                $rrp = money($data['Product RRP'] / $data['Product Units Per Case'], $data['Store Currency Code']);
                if ($data['Product Units Per Case'] != 1) {
                    $rrp .= '/'.$data['Product Unit Label'];
                }
                $rrp = sprintf(
                    '<span style="cursor:text" class="product_rrp" title="%s" pid="%d" rrp="%s"  currency="%s"   onClick="open_edit_rrp(this)">%s</span>', sprintf(_('margin %s'), percentage($data['Product RRP'] - $data['Product Price'], $data['Product RRP'])),
                    $data['Product ID'], $data['Product RRP'] / $data['Product Units Per Case'], $data['Store Currency Code'], $rrp

                );

            }


            //  print_r($_data);


            $margin = '<span class="product_margin" title="'._('Cost').':'.money($data['Product Cost'], $account->get('Account Currency')).'">'.percentage(
                    $exchange * $data['Product Price'] - $data['Product Cost'], $exchange * $data['Product Price']
                ).'<span>';


            switch ($_data['parameters']['parent']) {

                case 'part':

                    $code = sprintf(
                        '<span class="link" onClick="change_view(\'part/%d/product/%d\')" title="%s">%s</span>', $_data['parameters']['parent_key'], $data['Product ID'], '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product Name'].'</span>',
                        $data['Product Code']
                    );
                    $name = number($data['Product Part Ratio'], 5).' <i class="far fa-box" title="'.sprintf(_('Each outer pick %s part SKOs'), number($data['Product Part Ratio'])).'"></i> =  '.$data['Product Name'].($data['Product Units Per Case'] != 1
                            ? '<span class="discreet italic small">('.$data['Product Units Per Case'].' '._('units').')</span>' : '');


                    if ($data['Part Units Per Package'] != 0 and $data['Part Unit Price'] != 0 and $exchange != 0 and $data['Product Price'] > 0) {
                        $_recommended_margin_ratio = ($data['Part Unit Price'] - ($data['Part Cost in Warehouse'] / $data['Part Units Per Package'])) / $data['Part Unit Price'];

                        $_actual_margin_ratio = ($exchange * $data['Product Price'] - $data['Product Cost']) / ($exchange * $data['Product Price']);

                        if ($_recommended_margin_ratio * .8 > $_actual_margin_ratio) {
                            $margin = '<i class="fa yellow fa-exclamation-triangle " title="'.sprintf(_('Margin %s lower than expected'), percentage($_actual_margin_ratio, $_recommended_margin_ratio)).'"></i> '.$margin;
                        }

                    }


                    break;
                case 'category':
                    if ($data['Product Units Per Case'] == 1) {
                        $name = '<span>'.$data['Product Name'].'</span>';

                    } else {
                        $name = '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product Name'].'</span>';

                    }
                    $code = sprintf('<span class="link" onClick="change_view(\'products/%d/%sproduct/%d\')" title="%s">%s</span>', $data['Store Key'], $path, $data['Product ID'], $name, $data['Product Code']);

                    break;
                default:

                    if ($data['Product Units Per Case'] == 1) {
                        $name = '<span>'.$data['Product Name'].'</span>';

                    } else {
                        $name = '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product Name'].'</span>';

                    }

                    $code = sprintf('<span class="link" onClick="change_view(\'products/%d/%d\')" title="%s">%s</span>', $data['Store Key'], $data['Product ID'], $name, $data['Product Code']);

                    break;
            }


            /*

                        events: {
                            "click": function() {
                                change_view( {if $data['section']=='part'}'part/{$data['key']}/product/' + this.model.get("id")

                                                                                                                      {else if $data['section']=='category'}'products/{$data['store']->id}/category/{$data['_object']->get('Category Position')}/product/' + this.model.get("id")

                       {else}'products/{$data['parent_key']}/'+this.model.get("id"){/if})
            }
            },
                        className: "link width_150",

            */


            $record_data[] = array(

                'id'         => (integer)$data['Product ID'],
                'store_key'  => (integer)$data['Store Key'],
                'associated' => $associated,
                'store'      => sprintf('<span class="button" onClick="change_view(\'store/%d\')" title="%s"">%s</span>', $data['Store Key'], $data['Store Name'], $data['Store Code']),
                'code'       => $code,
                'name'       => $name,
                'price'      => sprintf(
                    '<span style="cursor:text" class="product_price" title="%s" pid="%d" price="%s"    currency="%s"  exchange="%s" cost="%s" old_margin="%s" onClick="open_edit_price(this)">%s</span>',
                    money($exchange * $data['Product Price'], $account->get('Account Currency')), $data['Product ID'], $data['Product Price'], $data['Store Currency Code'], $exchange, $data['Product Cost'],
                    percentage($exchange * $data['Product Price'] - $data['Product Cost'], $exchange * $data['Product Price']), money($data['Product Price'], $data['Store Currency Code'])
                ),


                'rrp' => $rrp,


                'margin'           => $margin,
                'web_state'        => $web_state,
                'status'           => $status,
                'parts'            => $parts,
                'sales'            => money($data['sales'], $data['Store Currency Code']),
                'sales_1yb'        => delta($data['sales'], $data['sales_1yb']),
                'dc_sales'         => money($data['dc_sales'], $account->get('Account Currency')),
                'dc_sales_1yb'     => delta($data['dc_sales'], $data['dc_sales_1yb']),
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
            'data'          => $record_data,
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

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Product ID']
            );


            $record_data[] = array(

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
            'data'          => $record_data,
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

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

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


            $record_data[] = array(
                'id'                  => (integer)$data['Category Key'],
                'code'                => sprintf(
                    '<span class="link" onclick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Category Store Key'], $data['Category Key'], $data['Category Code']
                ),
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
            'data'          => $record_data,
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

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
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


            $record_data[] = array(
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
            'data'          => $record_data,
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


    if ($_data['parameters']['frequency'] == 'annually') {
        $rtext_label       = 'year';
        $_group_by         = ' group by Year(`Date`) ';
        $sql_totals_fields = 'Year(`Date`)';
    } elseif ($_data['parameters']['frequency'] == 'quarterly') {
        $rtext_label       = 'quarter';
        $_group_by         = '  group by YEAR(`Date`), QUARTER(`Date`) ';
        $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y %q")';
    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $rtext_label       = 'month';
        $_group_by         = '  group by DATE_FORMAT(`Date`,"%Y-%m") ';
        $sql_totals_fields = 'DATE_FORMAT(`Date`,"%Y-%m")';
    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $rtext_label       = 'week';
        $_group_by         = ' group by Yearweek(`Date`,3) ';
        $sql_totals_fields = 'Yearweek(`Date`,3)';
    } elseif ($_data['parameters']['frequency'] == 'daily') {
        $rtext_label = 'day';

        $_group_by         = ' group by Date(`Date`) ';
        $sql_totals_fields = '`Date`';
    }


    switch ($_data['parameters']['parent']) {
        case 'product':
            $product = get_object('Product', $_data['parameters']['parent_key']);
            //$store      = get_object('Store', $product->get('Product Store Key'));
            $currency   = $product->get('Product Currency');
            $from       = $product->get('Product Valid From');
            $to         = ($product->get('Product Status') == 'Discontinued' ? $product->get('Product Valid To') : gmdate('Y-m-d'));
            $date_field = '`Invoice Date`';
            break;
        case 'category':
            $category = get_object('Category', $_data['parameters']['parent_key']);
            //$store    = get_object('Store', $category->get('Store Key'));

            $currency   = $category->get('Product Category Currency Code');
            $from       = $category->get('Product Category Valid From');
            $to         = ($category->get('Product Category Status') == 'Discontinued' ? $product->get('Product Category Valid To') : gmdate('Y-m-d'));
            $date_field = '`Timeseries Record Date`';
            break;
        case 'store':
            $store = get_object('Store', $_data['parameters']['parent_key']);

            $currency   = $store->get('Store Currency Code');
            $from       = $store->get('Store Valid From');
            $to         = ($store->get('Product State') == 'Closed' ? $store->get('Store Valid To') : gmdate('Y-m-d'));
            $date_field = '`Timeseries Record Date`';


            break;
        case 'account':

            $currency   = $account->get('Account Currency');
            $from       = $account->get('Account Valid From');
            $to         = gmdate('Y-m-d');
            $date_field = '`Timeseries Record Date`';

            $version = 2;
            break;
        default:
            print_r($_data);
            exit('parent not configured');
            break;
    }


    $sql_totals = sprintf(
        'SELECT count(DISTINCT %s) AS num FROM kbase.`Date Dimension` WHERE `Date`>=DATE(%s) AND `Date`<=DATE(%s) ', $sql_totals_fields, prepare_mysql($from), prepare_mysql($to)

    );
    list($rtext, $total, $filtered) = get_table_totals($db, $sql_totals, '', $rtext_label, false);


    $sql = sprintf(
        'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) %s ORDER BY %s  LIMIT %s', prepare_mysql($from), prepare_mysql($to), $_group_by, "`Date` $order_direction ", "$start_from,$number_results"
    );


    $record_data = array();

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
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $date  = 'Q'.ceil(date('n', strtotime($data['Date'].' +0:00')) / 3).' '.strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date = $date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {


                $date  = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date = strftime("%b %Y", strtotime($data['Date'].' +0:00'));

            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $date  = strftime(
                    "(%e %b) %Y %W ", strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
                $_date = date('Y-m-d', strtotime($data['Date'].' +0:00'));
            }

            $record_data[$_date] = array(
                'sales'     => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'customers' => '<span class="very_discreet">'.number(0).'</span>',
                'invoices'  => '<span class="very_discreet">'.number(0).'</span>',
                'refunds'   => '<span class="very_discreet">'.money(0, $currency).'</span>',
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
                $from_date = gmdate("Y-01-01 00:00:00", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-12-31 23:59:59", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate("Y-m-01 00:00:00", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-m-01 00:00:00", strtotime($to_date.' + 1 month +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate("Y-m-d 00:00:00", strtotime($from_date.'  -1 week  +0:00'));
                $to_date   = gmdate("Y-m-d 00:00:00", strtotime($to_date.' + 1 week +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = $from_date.' 00:00:00';
                $to_date   = $to_date.' 23:59:59';
            }
            break;
        case 'category':
        case 'store':
        case 'account':
            if ($_data['parameters']['frequency'] == 'annually') {
                $from_date = gmdate("Y-01-01", strtotime($from_date.' +0:00'));
                $to_date   = gmdate("Y-12-31", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $from_date = gmdate("Y-m-01", strtotime($from_date.'  -1 year  +0:00'));
                $to_date   = gmdate("Y-m-01", strtotime($to_date.' + 3 month +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $from_date = gmdate("Y-m-01", strtotime($from_date.' -1 year  +0:00'));
                $to_date   = gmdate("Y-m-01", strtotime($to_date.' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $from_date = gmdate("Y-m-d", strtotime($from_date.'  -1 year  +0:00'));
                $to_date   = gmdate("Y-m-d", strtotime($to_date.'  +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = gmdate("Y-m-d", strtotime($from_date.' - 1 year +0:00'));
                $to_date   = $to_date;
            }
            $group_by = '';
            break;
        default:
            print_r($_data);
            exit('parent not configured');
            break;
    }


    $sql = sprintf(
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s order by $date_field    ", $date_field, prepare_mysql($from_date), $date_field, prepare_mysql($to_date), " $group_by "
    );


    $last_year_data = array();


    if ($result = $db->query($sql)) {


        foreach ($result as $data) {

            if ($_data['parameters']['frequency'] == 'annually') {
                $_date           = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%Y", strtotime($data['Date'].' - 1 year'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $_date           = 'Q'.ceil(date('n', strtotime($data['Date'].' +0:00')) / 3).' '.strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = 'Q'.ceil(date('n', strtotime($data['Date'].' - 1 year')) / 3).' '.strftime("%Y", strtotime($data['Date'].' - 1 year'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $_date           = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%b %Y", strtotime($data['Date'].' - 1 year'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $_date           = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%Y%W ", strtotime($data['Date'].' - 1 year'));
                $date            = strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $_date           = date('Y-m-d', strtotime($data['Date'].' +0:00'));
                $_date_last_year = date('Y-m-d', strtotime($data['Date'].'  -1 year'));
                $date            = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
            }

            $last_year_data[$_date] = array('_sales' => $data['sales']);


            if (array_key_exists($_date, $record_data)) {


                $record_data[$_date] = array(
                    'sales'     => money($data['sales'], $currency),
                    'customers' => number($data['customers']),
                    'invoices'  => number($data['invoices']),
                    'date'      => $record_data[$_date]['date']
                );


                if (isset($last_year_data[$_date_last_year])) {
                    $record_data[$_date]['delta_sales_1yb'] = '<span class="" title="'.money($last_year_data[$_date_last_year]['_sales'], $currency).'">'.delta($data['sales'], $last_year_data[$_date_last_year]['_sales']).' '.delta_icon(
                            $data['sales'], $last_year_data[$_date_last_year]['_sales']
                        ).'</span>';
                }

            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    // print_r($record_data);

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => array_values($record_data),
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

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status       = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Surplus');
                    break;
                case 'Optimal':
                    $stock_status       = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Ok');
                    break;
                case 'Low':
                    $stock_status       = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Low');
                    break;
                case 'Critical':
                    $stock_status       = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Critical');
                    break;
                case 'Out_Of_Stock':
                    $stock_status       = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Out of stock');
                    break;
                case 'Error':
                    $stock_status       = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Error');
                    break;
                default:
                    $stock_status       = $data['Part Stock Status'];
                    $stock_status_label = $data['Part Stock Status'];
                    break;
            }


            if ($data['Part Current On Hand Stock'] <= 0) {
                $weeks_available = '-';
            } else {
                $weeks_available = number(
                    $data['Part Days Available Forecast'] / 7, 0
                );
            }

            $dispatched_per_week = number(
                $data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0
            );


            $reference = sprintf(
                '<span class="link" onclick="change_view(\'part/%d\')">%s</span>', $data['Part SKU'],
                ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
            );


            $record_data[] = array(
                'id'                  => (integer)$data['Part SKU'],
                'reference'           => $reference,
                'package_description' => $data['Part Package Description'],
                'picking_ratio'       => number($data['Product Part Ratio'], 5),
                'picking_note'        => $data['Product Part Note'],
                'stock_status'        => $stock_status,
                'stock_status_label'  => $stock_status_label,
                'stock'               => '<span  class="  '.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>  <i class="fa fa-fighter-jet padding_left_5 super_discreet  '.($data['Part On Demand']
                    == 'Yes' ? '' : 'invisible').' " title='._('On demand').' aria-hidden="true"></i>     ',
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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function product_categories_categories($_data, $db, $user) {

    include_once 'class.Category.php';
    include_once 'class.Store.php';


    // print_r($_data);

    $parent = new Category($_data['parameters']['parent_key']);
    $store  = new Store($parent->get('Category Store Key'));
    if ($store->get('Store Family Category Key') == $parent->get(
            'Category Root Key'
        )) {
        $rtext_label = 'family';
    } elseif ($store->get('Store Department Category Key') == $parent->get(
            'Category Root Key'
        )) {
        $rtext_label = 'department';
    } else {
        $rtext_label = 'category';
    }

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $record_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Product Category Status']) {
                case 'In Process':
                    $status = _('In process');
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


            $record_data[] = array(
                'id'        => (integer)$data['Product Category Key'],
                'store_key' => (integer)$data['Category Store Key'],

                'code' => sprintf(
                    '<span class="link" onclick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Category Store Key'], $data['Category Key'], $data['Category Code']
                ),

                'label'            => $data['Category Label'],
                'status'           => $status,
                'products'         => number($data['products']),
                'families'         => number($data['subjects']),
                'in_process'       => number($data['Product Category In Process Products']),
                'active'           => number($data['Product Category Active Products']),
                'suspended'        => number($data['Product Category Suspended Products']),
                'discontinuing'    => number($data['Product Category Discontinuing Products']),
                'discontinued'     => number($data['Product Category Discontinued Products']),
                'sales'            => money($data['sales'], $data['Product Category Currency Code']),
                'sales_1yb'        => delta($data['sales'], $data['sales_1yb']),
                'qty_invoiced'     => number($data['qty_invoiced']),
                'qty_invoiced_1yb' => delta($data['qty_invoiced'], $data['qty_invoiced_1yb']),


                'sales_year0' => sprintf(
                    '<span>%s</span> %s', money($data['Product Category Year To Day Acc Invoiced Amount'], $data['Product Category Currency Code']),
                    delta_icon($data["Product Category Year To Day Acc Invoiced Amount"], $data["Product Category Year To Day Acc 1YB Invoiced Amount"])
                ),
                'sales_year1' => sprintf(
                    '<span>%s</span> %s', money($data['Product Category 1 Year Ago Invoiced Amount'], $data['Product Category Currency Code']), delta_icon($data["Product Category 1 Year Ago Invoiced Amount"], $data["Product Category 2 Year Ago Invoiced Amount"])
                ),
                'sales_year2' => sprintf(
                    '<span>%s</span> %s', money($data['Product Category 2 Year Ago Invoiced Amount'], $data['Product Category Currency Code']), delta_icon($data["Product Category 2 Year Ago Invoiced Amount"], $data["Product Category 3 Year Ago Invoiced Amount"])
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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function product_categories_products($_data, $db, $user) {

    include_once 'class.Category.php';
    include_once 'class.Store.php';


    //print_r($_data);

    $parent = new Category($_data['parameters']['parent_key']);
    $store  = new Store($parent->get('Category Store Key'));

    if ($store->get('Store Department Category Key') == $parent->get(
            'Category Root Key'
        )) {
        $rtext_label = 'family';
    } else {
        $rtext_label = 'category';
    }


    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $record_data = array();


    foreach ($db->query($sql) as $data) {

        switch ($data['Product Category Status']) {
            case 'In Process':
                $status = sprintf('<i class="fa fa-circle-thin warning discreet" aria-hidden="true" title="%s"></i>', _('Empty'));
                break;
            case 'Active':
                $status = sprintf('<i class="fa fa-sitemap" aria-hidden="true" title="%s"></i>', _('Active'));
                break;
            case 'Suspended':
                $status = _('Suspended');
                break;
            case 'Discontinued':
                $status = sprintf('<i class="fa fa-sitemap very_discreet" aria-hidden="true" title="%s"></i>', _('Discontinued'));

                break;
            case 'Discontinuing':
                $status = sprintf('<i class="fa fa-sitemap warning discreet" aria-hidden="true" title="%s"></i>', _('Discontinuing'));

                break;
            default:
                $status = $data['Product Category Status'];
                break;
        }


        if ($data['Page Key'] > 0 and $data['Product Category Public'] == 'Yes') {
            $webpage = sprintf(
                '<span onclick="change_view(\'webpage/%d\')" class="link %s">%s</span>', $data['Page Key'], ($data['Webpage State'] == 'Offline' ? 'discreet strikethrough' : ''), $data['Webpage Code']
            );

            if ($data['Webpage State'] == 'InProcess') {
                $webpage .= '  <i class="fa fa-child" aria-hidden="true"></i>';

            }

        } else {
            $webpage = '<span class="super_discreet">-</span>';
        }

        if ($data['Product Category Public'] == 'No') {
            $webpage_state = '<i class="fa fa-microphone-slash" aria-hidden="true"></i>';

        } else {

            if ($data['Page Key'] == '') {
                $webpage_state = '<i class="fa fa-exclamation-circle error" aria-hidden="true"></i>';

            } else {

                if ($data['Webpage State'] == 'Online') {
                    if ($data['Product Category Status'] == 'Discontinued') {
                        $webpage_state = '<i class="far fa-globe warning" aria-hidden="true"></i>';

                    } else {
                        $webpage_state = '<i class="far fa-globe success" aria-hidden="true"></i>';

                    }

                } else {
                    $webpage_state = '<i class="far fa-globe super_discreet" aria-hidden="true"></i>';

                }


            }

        }


        $code = sprintf(
            '<span class="link" onClick="change_view(\'products/%d/category/%d>%d\')">%s</span>', $data['Category Store Key'], $parent->id, $data['Product Category Key'], $data['Category Code']
        );

        $remove = sprintf(
            '<span class="button" onClick="disassociate_category_from_table(this,%s,%d)"> <i class="fa fas padding_left_5 fa-unlink"></i> %s</span>', $parent->id, $data['Product Category Key'], _('Unlink')
        );


        $record_data[] = array(
            'id'                      => (integer)$data['Product Category Key'],
            'code'                    => $code,
            'label'                   => $data['Category Label'],
            'status'                  => $status,
            'products'                => number($data['products']),
            'families'                => number($data['subjects']),
            'in_process'              => number($data['Product Category In Process Products']),
            'active'                  => number($data['Product Category Active Products']),
            'suspended'               => number($data['Product Category Suspended Products']),
            'discontinuing'           => number($data['Product Category Discontinuing Products']),
            'discontinued'            => number($data['Product Category Discontinued Products']),
            'sales'                   => money($data['sales'], $data['Product Category Currency Code']),
            'sales_1yb'               => delta($data['sales'], $data['sales_1yb']),
            'qty_invoiced'            => number($data['qty_invoiced']),
            'qty_invoiced_1yb'        => delta($data['qty_invoiced'], $data['qty_invoiced_1yb']),
            'online'                  => ($data['Product Category Public'] == 'Yes' ? number($data['online']) : '<span class="super_discreet">-</span>'),
            'out_of_stock'            => ($data['Product Category Public'] == 'Yes' ? number($data['Product Category Active Web Out of Stock']) : '<span class="super_discreet">-</span>'),
            'percentage_out_of_stock' => ($data['Product Category Public'] == 'Yes' ? percentage($data['Product Category Active Web Out of Stock'], $data['online']) : ''),
            'webpage'                 => $webpage,
            'webpage_state'           => $webpage_state,
            'remove'                  => $remove,

            'sales_year0' => sprintf(
                '<span>%s</span> %s', money($data['Product Category Year To Day Acc Invoiced Amount'], $data['Product Category Currency Code']),
                delta_icon($data["Product Category Year To Day Acc Invoiced Amount"], $data["Product Category Year To Day Acc 1YB Invoiced Amount"])
            ),
            'sales_year1' => sprintf(
                '<span>%s</span> %s', money($data['Product Category 1 Year Ago Invoiced Amount'], $data['Product Category Currency Code']), delta_icon($data["Product Category 1 Year Ago Invoiced Amount"], $data["Product Category 2 Year Ago Invoiced Amount"])
            ),
            'sales_year2' => sprintf(
                '<span>%s</span> %s', money($data['Product Category 2 Year Ago Invoiced Amount'], $data['Product Category Currency Code']), delta_icon($data["Product Category 2 Year Ago Invoiced Amount"], $data["Product Category 3 Year Ago Invoiced Amount"])
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

    //  print_r($record_data);

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function charges($_data, $db, $user) {


    $rtext_label = 'charge';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    foreach ($db->query($sql) as $data) {


        if ($data['Charge Active'] == 'Yes') {
            $status = '<i class="fa fa-play success"></i>';
        } else {
            $status = '<i class="fa fa-pause error"></i>';

        }

        $record_data[] = array(
            'id'        => (integer)$data['Charge Key'],
            'code'      => sprintf('<span class="link" onClick="change_view(\'store/%d/charge/%d\')" >%s</span>', $data['Charge Store Key'], $data['Charge Key'], $data['Charge Name']),
            'name'      => sprintf('<span class="link" onClick="change_view(\'store/%d/charge/%d\')" >%s</span>', $data['Charge Store Key'], $data['Charge Key'], $data['Charge Description']),
            'orders'    => number($data['Charge Total Acc Orders']),
            'customers' => number($data['Charge Total Acc Customers']),
            'amount'    => money($data['Charge Total Acc Amount'], $data['Store Currency Code']),
            'status'    => $status

        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function shipping_zones($_data, $db, $user) {


    $rtext_label = 'shipping zone';

    include_once 'prepare_table/init.php';


    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $price       = '';
            $territories = '';

            $price_data = json_decode($data['Shipping Zone Price'], true);


            if ($data['Shipping Zone Territories'] != '') {

                $territories_data = json_decode($data['Shipping Zone Territories'], true);


                //print_r($territories_data);

                foreach ($territories_data as $territory) {
                    $territories .= '<img class="padding_left_5" style="width:20px" src="/art/flags/'.strtolower($territory['country_code']).'.gif"> '.$territory['country_code'];

                    if (isset($territory['excluded_postal_codes'])) {
                        $territories .= ' <span class="error small">(<i class="fa fa-map-marker-alt-slash error" title="'._('Exclude postal codes').'" ></i> '.$territory['excluded_postal_codes'].')</span> ';
                    }
                    if (isset($territory['included_postal_codes'])) {
                        $territories .= ' <span class="success small discreet" >(<i class="fa fa-map-marker-smile " title="'._('Include postal codes').'" ></i> '.$territory['included_postal_codes'].')</span> ';
                    }

                }

            }


            switch ($price_data['type']) {
                case 'Step Order Items Net Amount':


                    $price .= '<div class="as_table">';
                    foreach ($price_data['steps'] as $step) {
                        $price .= '<div class="as_row">';

                        $price .= '<div class="as_cell  width_75"><span class="discreet">'._('Items').' <i class="fa fa-dollar-sign"></i></span> </div>';

                        $to = ($step['to'] == 'INF' ? '<i class="fal fa-infinity"></i>' : money($step['to'], $_data['parameters']['store_currency']));


                        $amount = ($step['price'] == 0 ? '<span class="success ">'._('free').'</span>' : '<span class="highlight">'.money($step['price'], $_data['parameters']['store_currency']).'</span>');
                        $price  .= ' <div class="as_cell">'.money($step['from'], $_data['parameters']['store_currency']).'</div> <div class="as_cell align_center width_50"><i class="fal fa-arrow-right"></i> </div><div class="as_cell">'.$to.'</div> ';
                        $price  .= '<div class="as_cell discreet align_center width_50"><i class="fal hide fa-equals"></i></div> <div  class="width_75 aright ">'.$amount.'</div>';
                        $price  .= '</div>';
                    }
                    $price .= '</div>';
                    break;
            }


            $record_data[] = array(
                'id'          => (integer)$data['Shipping Zone Key'],
                'code'        => sprintf('<span class="link" onClick="change_view(\'store/%d/shipping_zone/%d\')" >%s</span>', $data['Shipping Zone Store Key'], $data['Shipping Zone Key'], $data['Shipping Zone Code']),
                'name'        => sprintf('<span >%s</span>', $data['Shipping Zone Name']),
                'price'       => $price,
                'territories' => $territories,
                'customers'   => number($data['Shipping Zone Number Customers']),
                'orders'      => number($data['Shipping Zone Number Orders']),
                'amount'      => money($data['Shipping Zone Amount'], $data['Store Currency Code']),
                'first_used'  => ($data['Shipping Zone First Used'] != '' ? strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Shipping Zone First Used'].' +0:00')) : ''),
                'last_used'   => ($data['Shipping Zone Last Used'] != '' ? strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Shipping Zone Last Used'].' +0:00')) : ''),


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function shipping_zones_schemas($_data, $db, $user) {


    $rtext_label = 'shipping zone schema';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Shipping Zone Schema Type']) {
                case 'Current':
                    $type = sprintf('<i class="fa fa-play success margin_right_5" title="%s"></i> ', _('Current'));
                    break;
                case 'InReserve':
                    $type = sprintf('<i class="fa fa-pause discreet margin_right_5" title="%s"></i> ', _('In reserve'));
                    break;
                case 'Deal':
                    $type = sprintf('<i class="fa fa-tag  margin_right_5" title="%s"></i> ', _('Offer'));
                    break;
                case 'Discontinued':
                    $type = sprintf('<i class="fa fa-skull discreet margin_right_5" title="%s"></i> ', _('Discontinued'));
                    break;
                default:

                    $type = $data['Shipping Zone Schema Type'];
            }


            $record_data[] = array(
                'id'         => (integer)$data['Shipping Zone Schema Key'],
                'type'       => $type,
                'label'      => sprintf('<span class="link" onClick="change_view(\'store/%d/shipping_zone_schema/%d\')" >%s</span>', $data['Shipping Zone Schema Store Key'], $data['Shipping Zone Schema Key'], $data['Shipping Zone Schema Label']),
                'zones'      => number($data['Shipping Zone Schema Number Zones']),
                'customers'  => number($data['Shipping Zone Schema Number Customers']),
                'orders'     => number($data['Shipping Zone Schema Number Orders']),
                'first_used' => ($data['Shipping Zone Schema First Used'] != '' ? strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Shipping Zone Schema First Used'].' +0:00')) : ''),
                'last_used'  => ($data['Shipping Zone Schema Last Used'] != '' ? strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Shipping Zone Schema Last Used'].' +0:00')) : ''),
                'amount'     => money($data['Shipping Zone Schema Amount'], $data['Store Currency Code']),


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function back_to_stock_notification_request_products($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';

    $rtext_label = 'product';


    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Product Web State']) {
                case 'For Sale':
                    $web_state = '<span class="'.(($data['Product Availability'] <= 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Online').'</span>'.($data['Product Web Configuration'] == 'Online Force For Sale'
                            ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    break;
                case 'Out of Stock':


                    $web_state = '<span  class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Out of Stock').'</span>'.($data['Product Web Configuration'] == 'Online Force Out of Stock'
                            ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');


                    break;
                case 'Discontinued':
                    $web_state = _('Discontinued');
                    break;
                case 'Offline':

                    if ($data['Product Status'] != 'Active') {
                        $web_state = _('Offline');
                    } else {

                        $web_state = '<span class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Offline').'</span>'.($data['Product Status'] == 'Active'
                                ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    }
                    break;
                default:
                    $web_state = $data['Product Web State'];
                    break;
            }


            switch ($data['Product Status']) {
                case 'Active':
                    $status = sprintf('<i class="fa fa-cube" aria-hidden="true" title="%s"></i>', _('Active'));
                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-cube warning" aria-hidden="true" title="%s"></i>', _('Suspended'));
                    break;
                case 'Discontinuing':
                    $status = sprintf('<i class="fa fa-cube warning very_discreet" aria-hidden="true" title="%s"></i>', _('Discontinuing'));
                    break;
                case 'Discontinued':
                    $status = sprintf('<i class="fa fa-cube very_discreet" aria-hidden="true" title="%s"></i>', _('Discontinued'));
                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-cube error" aria-hidden="true" title="%s"></i>', _('Suspended'));
                    break;
                default:
                    $status = $data['Product Status'];
                    break;
            }


            $name = '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product Name'].'</span>';

            $code = sprintf('<span class="link" onClick="change_view(\'products/%d/%d\')" title="%s">%s</span>', $data['Store Key'], $data['Product ID'], $name, $data['Product Code']);


            $record_data[] = array(

                'id'        => (integer)$data['Product ID'],
                'code'      => $code,
                'name'      => $name,
                'status'    => $status,
                'web_state' => $web_state,
                'customers' => sprintf('<span>%s</span>', number($data['customers']))


            );


        }

    } else {
        print "$sql\n";
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function customers($_data, $db, $user) {

    $rtext_label = 'customer';


    include_once 'prepare_table/init.php';

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Customer Type by Activity']) {
                case 'ToApprove':
                    $activity = _('To be approved');
                    break;
                case 'Inactive':
                    $activity = _('Lost');
                    break;
                case 'Active':
                    $activity = _('Active');
                    break;
                case 'Prospect':
                    $activity = _('Prospect');
                    break;
                default:
                    $activity = $data['Customer Type by Activity'];
                    break;
            }


            $link_format = '/'.$parameters['parent'].'/%d/customer/%d';

            $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);


            $adata[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,

                'name' => $data['Customer Name'],

                'location' => $data['Customer Location'],
                'activity' => $activity,
                'invoices' => number($data['invoices']),
                'orders'   => number($data['orders']),
                'amount'   => money($data['amount'], $data['Store Currency Code'])


            );
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function back_to_stock_notification_request_customers($_data, $db, $user) {

    $rtext_label = 'back to stock request';


    include_once 'prepare_table/init.php';

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Back in Stock Reminder State']) {
                case 'Waiting':
                    $state = _('Waiting');
                    break;
                case 'Ready':
                    $state = _('Ready');
                    break;

                default:
                    $state = $data['Back in Stock Reminder State'];
                    break;
            }


            switch ($data['Customer Type by Activity']) {
                case 'ToApprove':
                    $activity = _('To be approved');
                    break;
                case 'Inactive':
                    $activity = _('Lost');
                    break;
                case 'Active':
                    $activity = _('Active');
                    break;
                case 'Prospect':
                    $activity = _('Prospect');
                    break;
                default:
                    $activity = $data['Customer Type by Activity'];
                    break;
            }


            $link_format = '/'.$parameters['parent'].'/%d/customer/%d';

            $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);


            $adata[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,

                'name' => $data['Customer Name'],

                'location' => $data['Customer Location'],
                'activity' => $activity,
                'date'     => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Back in Stock Reminder Creation Date'].' +0:00')),
                'state'    => $state,

            );
        }

    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function user_notifications($_data, $db, $user) {

    $rtext_label = 'operational email';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    foreach ($db->query($sql) as $data) {


        switch ($data['Email Campaign Type Code']) {
            case 'New Order':
                $_type = _('New order');
                break;
            case 'New Customer':
                $_type = _('New registration');

                break;
            case 'Invoice Deleted':
                $_type = _('Invoice deleted');

                break;
            case 'Delivery Note Undispatched':
                $_type = _('Delivery note undispatched');

                break;

            default:
                $_type = $data['Email Campaign Type Code'];


        }


        $type = sprintf('<span class="link" onClick="change_view(\'store/%d/notifications/%d\')">%s</span>', $data['Email Campaign Type Store Key'], $data['Email Campaign Type Key'], $_type);


        $adata[] = array(
            'id' => (integer)$data['Email Campaign Type Key'],

            '_type'       => $_type,
            'type'        => $type,
            'subscribers' => number($data['Email Campaign Type Subscribers']),
            'sent'        => number($data['Email Campaign Type Sent']),

            'hard_bounces' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Hard Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Hard Bounces']),
                percentage($data['Email Campaign Type Hard Bounces'], $data['Email Campaign Type Sent'])
            ),
            'soft_bounces' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Soft Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Soft Bounces']),
                percentage($data['Email Campaign Type Soft Bounces'], $data['Email Campaign Type Sent'])
            ),

            'bounces' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Bounces']),
                percentage($data['Email Campaign Type Bounces'], $data['Email Campaign Type Sent'])
            ),

            'delivered' => ($data['Email Campaign Type Sent'] == 0 ? '<span class="super_discreet">'._('NA').'</span>' : number($data['Email Campaign Type Delivered'])),

            'open'    => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Type Open']), percentage($data['Email Campaign Type Open'], $data['Email Campaign Type Delivered'])
            ),
            'clicked' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Type Clicked']), percentage($data['Email Campaign Type Clicked'], $data['Email Campaign Type Delivered'])
            ),
            'spam'    => sprintf(
                '<span class="%s " title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Spams'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Spams']),
                percentage($data['Email Campaign Type Spams'], $data['Email Campaign Type Delivered'])
            ),


        );

    }

    if ($_order == 'type') {


        $type = array();
        foreach ($adata as $key => $row) {
            $type[$key] = $row['_type'];
        }


        if ($_dir == 'desc') {
            array_multisort($type, SORT_DESC, $adata);

        } else {
            array_multisort($type, SORT_ASC, $adata);

        }


    }

    // print_r($_order);


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


function category_sales_correlations($_data, $db, $user) {

    include_once 'class.Category.php';
    include_once 'class.Store.php';


    // print_r($_data);

    $parent = new Category($_data['parameters']['parent_key']);
    $store  = new Store($parent->get('Category Store Key'));
    if ($store->get('Store Family Category Key') == $parent->get(
            'Category Root Key'
        )) {
        $rtext_label = 'family';
    } elseif ($store->get('Store Department Category Key') == $parent->get(
            'Category Root Key'
        )) {
        $rtext_label = 'department';
    } else {
        $rtext_label = 'category';
    }

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $record_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Product Category Status']) {
                case 'In Process':
                    $status = _('In process');
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


            $record_data[] = array(
                'id' => (integer)$data['Product Category Key'],

                'code' => sprintf(
                    '<span class="link" onclick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Category Store Key'], $data['Category Key'], $data['Category Code']
                ),

                'label'        => $data['Category Label'],
                'status'       => $status,
                'correlation'  => number($data['Correlation'], 5, true),
                'customers_AB' => number($data['Customers AB']),
                'customers_A'  => number($data['Customers A']),
                'customers_B'  => number($data['Customers B']),


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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function product_sales_correlations($_data, $db, $user,$type) {

    $rtext_label = 'correlations';

    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $record_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Product Status']) {
                case 'Active':
                    $status = sprintf('<i class="fa fa-cube" aria-hidden="true" title="%s"></i>', _('Active'));
                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-cube warning" aria-hidden="true" title="%s"></i>', _('Suspended'));
                    break;
                case 'Discontinued':
                    $status = sprintf('<i class="fa fa-cube very_discreet" aria-hidden="true" title="%s"></i>', _('Discontinued'));
                    break;
                case 'Suspended':
                    $status = sprintf('<i class="fa fa-cube error" aria-hidden="true" title="%s"></i>', _('Suspended'));
                    break;
                default:
                    $status = $data['Product Status'];
                    break;
            }


            switch ($data['Product Web State']) {
                case 'For Sale':
                    $web_state = '<span class="'.(($data['Product Availability'] <= 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Online').'</span>'.($data['Product Web Configuration'] == 'Online Force For Sale'
                            ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    break;
                case 'Out of Stock':


                    $web_state = '<span  class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Out of Stock').'</span>'.($data['Product Web Configuration'] == 'Online Force Out of Stock'
                            ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');


                    break;
                case 'Discontinued':
                    $web_state = _('Discontinued');
                    break;
                case 'Offline':

                    if ($data['Product Status'] != 'Active') {
                        $web_state = _('Offline');
                    } else {

                        $web_state = '<span class="'.(($data['Product Availability'] > 0 and $data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Offline').'</span>'.($data['Product Status'] == 'Active'
                                ? ' <i class="fa fa-thumb-tack padding_left_5" aria-hidden="true"></i>' : '');
                    }
                    break;
                default:
                    $web_state = $data['Product Web State'];
                    break;
            }


            $record_data[] = array(
                'id' => (integer)$data['Product ID'],

                'code' => sprintf(
                    '<span class="link" onclick="change_view(\'products/%d/%d\')">%s</span>', $data['Product Store Key'], $data['Product ID'], $data['Product Code']
                ),

                'label'     => $data['Product Name'],
                'status'    => $status,
                'web_state' => $web_state,

                'correlation'  => number($data['Correlation'], 5, true),
                'customers_AB' => number($data['Customers AB']),
                'customers_A'  => number($data['Customers A']),
                'customers_B'  => number($data['Customers B']),


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
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}
