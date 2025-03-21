<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'class.Store.php';

/** @var User $user */
/** @var PDO $db */
/** @var Account $account */


if (!$user->can_view('customers')) {
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
    case 'customers':
        customers(get_table_parameters(), $db, $user);
        break;
    case 'customers_dropshipping':
        customers_dropshipping(get_table_parameters(), $db, $user);
        break;
    case 'prospects':
        prospects(get_table_parameters(), $db, $user);
        break;
    case 'asset_customers':
        asset_customers(get_table_parameters(), $db, $user);
        break;
    case 'lists':
        lists(get_table_parameters(), $db, $user);
        break;

    case 'customers_server':
        customers_server(get_table_parameters(), $db, $user);
        break;
    case 'categories':
        categories(get_table_parameters(), $db, $user);
        break;
    case 'sub_categories':
        sub_categories(get_table_parameters(), $db, $user);
        break;
    case 'customers_geographic_distribution':
        customers_geographic_distribution(get_table_parameters(), $db, $user, $account);
        break;
    case 'poll_queries':
        poll_queries(get_table_parameters(), $db, $user);
        break;
    case 'poll_query_options':
        poll_query_options(get_table_parameters(), $db, $user);
        break;
    case 'poll_query_answers':
        poll_query_answers(get_table_parameters(), $db, $user);
        break;
    case 'abandoned_cart_mail_list':
        abandoned_cart_mail_list(get_table_parameters(), $db, $user);
        break;
    case 'newsletter_mail_list':
        newsletter_mail_list(get_table_parameters(), $db, $user);
        break;
    case 'prospects.email_templates':
        prospects_email_templates(get_table_parameters(), $db, $user);
        break;
    case 'sales_history':
        sales_history(get_table_parameters(), $db, $user, $account);
        break;
    case 'products':
        products(get_table_parameters(), $db, $user, $account);
        break;
    case 'families':
        families(get_table_parameters(), $db, $user, $account);
        break;
    case 'credit_blockchain':
        credit_blockchain(get_table_parameters(), $db, $user, $account);
        break;
    case 'customer_clients':
        customer_clients(get_table_parameters(), $db, $user, $account);
        break;
    case 'customer_portfolio':
        customer_portfolio(get_table_parameters(), $db, $user);
        break;
    case 'customers_registrations':
        customers_registrations(get_table_parameters(), $db, $user, $account);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
}


function customers($_data, $db, $user)
{
    if ($_data['parameters']['parent'] == 'favourites') {
        $rtext_label = 'customer who favored';
    } else {
        $rtext_label = 'customer';
    }

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    if ($_data['parameters']['parent'] == 'campaign') {
        $campaign = get_object('campaign', $_data['parameters']['parent_key']);
    }


    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($parameters['parent'] == 'category') {
                $category_other_value = $data['Other Note'];
            } else {
                $category_other_value = '';
            }


            if ($data['Customer Orders'] == 0) {
                $last_order_date = '';
            } else {
                $last_order_date = strftime(
                    "%e %b %y",
                    strtotime($data['Customer Last Order Date'].' +0:00')
                );
            }

            if ($data['Customer Number Invoices'] == 0 or $data['Customer Last Invoiced Order Date'] == '') {
                $last_invoice_date = '';
            } else {
                $last_invoice_date = strftime(
                    "%e %b %y",
                    strtotime(
                        $data['Customer Last Invoiced Order Date'].' +0:00'
                    )
                );
            }


            $contact_since = strftime("%e %b %y", strtotime($data['Customer First Contacted Date'].' +0:00'));


            if ($data['Customer Billing Address Link'] == 'Contact') {
                $billing_address = '<i>'._('Same as Contact').'</i>';
            } else {
                $billing_address = $data['Customer Invoice Address Formatted'];
            }

            if ($data['Customer Delivery Address Link'] == 'Contact') {
                $delivery_address = '<i>'._('Same as Contact').'</i>';
            } elseif ($data['Customer Delivery Address Link'] == 'Billing') {
                $delivery_address = '<i>'._('Same as Billing').'</i>';
            } else {
                $delivery_address = $data['Customer Delivery Address Formatted'];
            }

            //enum('Rejected','ToApprove','Active','Losing','Lost','NeverOrder')
            switch ($data['Customer Type by Activity']) {
                case 'ToApprove':
                    $activity = _('To be approved');
                    break;
                case 'Rejected':
                    $activity = _('Rejected');
                    break;
                case 'Active':
                    $activity = _('Active');
                    break;
                case 'Losing':
                    $activity = _('Losing');
                    break;
                case 'Lost':
                    $activity = _('Lost');
                    break;
                case 'NeverOrder':
                    $activity = _('Never order');
                    break;
                default:
                    $activity = $data['Customer Type by Activity'];
                    break;
            }

            if ($parameters['parent'] == 'store') {
                $link_format  = '/customers/%d/%d';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);
            } elseif ($parameters['parent'] == 'customer_poll_query_option' or $parameters['parent'] == 'customer_poll_query' or $parameters['parent'] == 'sales_representative') {
                $link_format  = '/customers/%d/%d';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Key']);
            } elseif ($parameters['parent'] == 'list') {
                $link_format  = '/customers/list/%d/%d';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);
            } elseif ($parameters['parent'] == 'campaign') {
                $link_format  = '/customers/%d/%d';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $campaign->get('Deal Campaign Store Key'), $data['Customer Key'], $data['Customer Key']);
            } elseif ($parameters['parent'] == 'category') {
                $link_format  = '/customers/%d/category/%d/%d/customer/%s';
                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['store_key'], $parameters['grandparent_key'], $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);
            } else {
                $link_format = '/'.$parameters['parent'].'/%d/customer/%d';

                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);
            }

            $disassociate_from_category = '';

            if (isset($_data['parameters']['parent_key'])) {
                $disassociate_from_category = sprintf('<i class="fa button fa-unlink" onclick="disassociate_category_from_table(this,%d,%d)" ></i>', $_data['parameters']['parent_key'], $data['Customer Key']);
            }
            $table_data[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,

                'name'         => $data['Customer Name'],
                'company_name' => $data['Customer Company Name'],
                'contact_name' => $data['Customer Main Contact Name'],

                'location' => $data['Customer Location'],

                'invoices'  => (integer)$data['Customer Number Invoices'],
                'email'     => $data['Customer Main Plain Email'],
                'telephone' => $data['Customer Main XHTML Telephone'],
                'mobile'    => $data['Customer Main XHTML Mobile'],
                'orders'    => number($data['Customer Orders']),

                'last_order'    => $last_order_date,
                'last_invoice'  => $last_invoice_date,
                'contact_since' => $contact_since,

                'other_value' => $category_other_value,

                'total_payments'        => money($data['Customer Payments Amount'], $currency),
                'total_invoiced_amount' => money($data['Customer Sales Amount'], $currency),
                'total_sales'           => money($data['Customer Sales Amount'], $currency),


                'top_orders'       => percentage(
                    $data['Customer Orders Top Percentage'],
                    1,
                    2
                ),
                'top_invoices'     => percentage(
                    $data['Customer Invoices Top Percentage'],
                    1,
                    2
                ),
                'top_balance'      => percentage($data['Customer Balance Top Percentage'], 1, 2),
                'top_profits'      => percentage($data['Customer Profits Top Percentage'], 1, 2),
                'address'          => $data['Customer Contact Address Formatted'],
                'billing_address'  => $billing_address,
                'delivery_address' => $delivery_address,

                'activity'                   => $activity,
                'logins'                     => number($data['Customer Number Web Logins']),
                'failed_logins'              => number($data['Customer Number Web Failed Logins']),
                'requests'                   => number($data['Customer Number Web Requests']),
                'clients'                    => number($data['Customer Number Clients']),
                'disassociate_from_category' => $disassociate_from_category


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function customers_dropshipping($_data, $db, $user)
{
    if ($_data['parameters']['parent'] == 'favourites') {
        $rtext_label = 'customer who favored';
    } else {
        $rtext_label = 'customer';
    }

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($parameters['parent'] == 'category') {
                $category_other_value = $data['Other Note'];
            } else {
                $category_other_value = '';
            }


            if ($data['Customer Orders'] == 0) {
                $last_order_date = '';
            } else {
                $last_order_date = strftime(
                    "%e %b %y",
                    strtotime($data['Customer Last Order Date'].' +0:00')
                );
            }

            if ($data['Customer Number Invoices'] == 0 or $data['Customer Last Invoiced Order Date'] == '') {
                $last_invoice_date = '';
            } else {
                $last_invoice_date = strftime(
                    "%e %b %y",
                    strtotime(
                        $data['Customer Last Invoiced Order Date'].' +0:00'
                    )
                );
            }


            $contact_since = strftime("%e %b %y", strtotime($data['Customer First Contacted Date'].' +0:00'));


            if ($data['Customer Billing Address Link'] == 'Contact') {
                $billing_address = '<i>'._('Same as Contact').'</i>';
            } else {
                $billing_address = $data['Customer Invoice Address Formatted'];
            }


            switch ($data['Customer Type by Activity']) {
                case 'ToApprove':
                    $customer_status = sprintf('<i class="fal purple fa-question-circle" title="%s"></i>', _('To be approved'));
                    break;
                case 'Lost':
                    $customer_status = sprintf('<i class="fa error fa-do-not-enter" title="%s"></i>', _('Lost'));
                    break;
                case 'Losing':
                    $customer_status = sprintf('<i class="fa warning fa-check-circle" title="%s"></i>', _('Losing'));
                    break;
                case 'Active':
                    $customer_status = sprintf('<i class="fa success fa-check-circle" title="%s"></i>', _('Active'));
                    break;
                case 'Prospect':
                    $customer_status = sprintf('<i class="fal success fa-user-circle" title="%s"></i>', _('Prospect'));
                    break;
                case 'Rejected':
                    $customer_status = sprintf('<i class="fal  very_discreet fa-ban" title="%s"></i>', _('Rejected'));
                    break;
                default:
                    $customer_status = $data['Customer Type by Activity'];
                    break;
            }


            $link_format  = '/customers/%d/%d';
            $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);


            $table_data[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => $formatted_id,

                'name'         => $data['Customer Name'],
                'company_name' => $data['Customer Company Name'],
                'contact_name' => $data['Customer Main Contact Name'],

                'location' => $data['Customer Location'],

                'invoices'  => (integer)$data['Customer Number Invoices'],
                'email'     => $data['Customer Main Plain Email'],
                'telephone' => $data['Customer Main XHTML Telephone'],
                'mobile'    => $data['Customer Main XHTML Mobile'],
                'orders'    => number($data['Customer Orders']),

                'last_order'    => $last_order_date,
                'last_invoice'  => $last_invoice_date,
                'contact_since' => $contact_since,

                'other_value' => $category_other_value,

                'total_payments' => money($data['Customer Payments Amount'], $currency),
                'total_sales'    => money($data['Customer Sales Amount'], $currency),


                'address'         => $data['Customer Contact Address Formatted'],
                'billing_address' => $billing_address,

                'customer_status' => $customer_status,

                'clients'   => number($data['Customer Number Clients']),
                'portfolio' => number($data['Customer Number Products in Portfolio']),


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


/**
 * @param $_data
 * @param $db   \PDO
 * @param $user \User
 */
function lists($_data, $db, $user)
{
    $rtext_label = 'list';
    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select $fields from `List Dimension` CLD $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $table_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['List Type']) {
                case 'Static':
                    $customer_list_type = _('Static');
                    $items              = number($data['List Number Items']);
                    break;
                default:
                    $customer_list_type = _('Dynamic');
                    $items              = '~'.number($data['List Number Items']);
                    break;
            }

            $table_data[] = array(
                'id'            => (integer)$data['List key'],
                'type'          => $customer_list_type,
                'name'          => sprintf('<span class="link"  onclick="change_view(\'customers/list/%d\')">%s</span>', $data['List key'], $data['List Name']),
                'creation_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['List Creation Date'].' +0:00')),
                'items'         => $items,
                'delete'        => ''
            );
        }
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function categories($_data, $db, $user)
{
    $rtext_label = 'category';
    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

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


            $table_data[] = array(
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
                    $data['Category Number Subjects'],
                    ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])
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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function sub_categories($_data, $db, $user)
{
    $rtext_label = 'category';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print_r($_data);

    foreach ($db->query($sql) as $data) {
        $adata[] = array(
            'id'                  => (integer)$data['Category Key'],
            'code'                => sprintf(
                '<span class="link" onClick="change_view(\'customers/%d/category/%d/%d\')">%s</span>',
                $data['Category Store Key'],
                $data['Category Parent Key'],
                $data['Category Key'],
                $data['Category Code']
            ),
            'label'               => $data['Category Label'],
            'customers'           => number($data['Category Number Subjects']),
            'subcategories'       => number($data['Category Children']),
            'percentage_assigned' => percentage(
                $data['Category Number Subjects'],
                ($data['Category Number Subjects'] + $data['Category Subjects Not Assigned'])
            )
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

function customers_server($_data, $db, $user)
{
    //print_r($_data);

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $total_contacts                    = 0;
    $total_active_contacts             = 0;
    $total_new_contacts                = 0;
    $total_lost_contacts               = 0;
    $total_losing_contacts             = 0;
    $total_contacts_with_orders        = 0;
    $total_active_contacts_with_orders = 0;
    $total_new_contacts_with_orders    = 0;
    $total_lost_contacts_with_orders   = 0;
    $total_losing_contacts_with_orders = 0;
    $total_users                       = 0;
    $total_contacts_never_order        = 0;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $total_contacts += $data['Store Contacts'];

            $total_active_contacts             += $data['active'];
            $total_new_contacts                += $data['Store New Contacts'];
            $total_lost_contacts               += $data['Store Lost Contacts'];
            $total_losing_contacts             += $data['Store Losing Contacts'];
            $total_contacts_with_orders        += $data['Store Contacts With Orders'];
            $total_active_contacts_with_orders += $data['active_with_orders'];
            $total_new_contacts_with_orders    += $data['Store New Contacts With Orders'];
            $total_lost_contacts_with_orders   += $data['Store Lost Contacts With Orders'];
            $total_losing_contacts_with_orders += $data['Store Losing Contacts With Orders'];
            $total_contacts_never_order        += $data['Store Lost Contacts With No Orders'];


            $contacts_never_order        = number($data['Store Lost Contacts With No Orders']);
            $contacts                    = number($data['Store Contacts']);
            $new_contacts                = number($data['Store New Contacts']);
            $active_contacts             = number($data['active']);
            $losing_contacts             = number(
                $data['Store Losing Contacts']
            );
            $lost_contacts               = number($data['Store Lost Contacts']);
            $contacts_with_orders        = number(
                $data['Store Contacts With Orders']
            );
            $new_contacts_with_orders    = number(
                $data['Store New Contacts With Orders']
            );
            $active_contacts_with_orders = number($data['active_with_orders']);
            $losing_contacts_with_orders = number(
                $data['Store Losing Contacts With Orders']
            );
            $lost_contacts_with_orders   = number(
                $data['Store Lost Contacts With Orders']
            );
            $total_users                 = $data['Store Total Users'];

            //  $contacts_with_orders=number($data['contacts_with_orders']);
            // $active_contacts=number($data['active_contacts']);
            // $new_contacts=number($data['new_contacts']);
            // $lost_contacts=number($data['lost_contacts']);
            // $new_contacts_with_orders=number($data['new_contacts']);


            /*
                if ($parameters['percentages']) {
                    $contacts_with_orders=percentage($data['contacts_with_orders'],$total_contacts_with_orders);
                    $active_contacts=percentage($data['active_contacts'],$total_active);
                    $new_contacts=percentage($data['new_contacts'],$total_new);
                    $lost_contacts=percentage($data['los_contactst'],$total_lost);
                    $contacts=percentage($data['contacts'],$total_contacts);
                    $new_contacts_with_orders=percentage($data['new_contacts'],$total_new_contacts);

                } else {
                    $contacts_with_orders=number($data['contacts_with_orders']);
                    $active_contacts=number($data['active_contacts']);
                    $new_contacts=number($data['new_contacts']);
                    $lost_contacts=number($data['lost_contacts']);
                    $contacts=number($data['contacts']);
                    $new_contacts_with_orders=number($data['new_contacts']);

                }
        */
            $table_data[] = array(
                'store_key'                   => $data['Store Key'],
                'code'                        => sprintf('<span class="link" onClick="change_view(\'customers/%d\')">%s</span>', $data['Store Key'], $data['Store Code']),
                'name'                        => sprintf('<span class="link" onClick="change_view(\'customers/%d\')">%s</span>', $data['Store Key'], $data['Store Name']),
                'contacts'                    => (integer)$data['Store Contacts'],
                'active_contacts'             => (integer)$data['active'],
                'new_contacts'                => (integer)$data['Store New Contacts'],
                'lost_contacts'               => (integer)$data['Store Lost Contacts'],
                'losing_contacts'             => (integer)$data['Store Losing Contacts'],
                'contacts_with_orders'        => $contacts_with_orders,
                'active_contacts_with_orders' => $active_contacts_with_orders,
                'new_contacts_with_orders'    => $new_contacts_with_orders,
                'lost_contacts_with_orders'   => $lost_contacts_with_orders,
                'losing_contacts_with_orders' => $losing_contacts_with_orders,
                'users'                       => $total_users,
                'losing_contacts'             => (integer)$data['Store Losing Contacts'],
                'never_order'                 => $data['Store Lost Contacts With No Orders']


            );
        }
    }


    if ($parameters['percentages']) {
        $sum_total        = '100.00%';
        $sum_active       = '100.00%';
        $sum_new          = '100.00%';
        $sum_lost         = '100.00%';
        $sum_contacts     = '100.00%';
        $sum_new_contacts = '100.00%';
    } else {
        // $total_contacts=number($total_contacts);
        // $total_active_contacts=number($total_active_contacts);
        // $total_new_contacts=number($total_new_contacts);
        // $total_lost_contacts=number($total_lost_contacts);
        // $total_losing_contacts=number($total_losing_contacts);
        // $total_contacts_with_orders=number($total_contacts_with_orders);
        // $total_active_contacts_with_orders=number($total_active_contacts_with_orders);
        // $total_new_contacts_with_orders=number($total_new_contacts_with_orders);
        // $total_lost_contacts_with_orders=number($total_lost_contacts_with_orders);
        // $total_losing_contacts_with_orders=number($total_losing_contacts_with_orders);

        // $sum_total=number($total_contacts_with_orders);
        // $sum_active=number($total_active_contacts);
        // $sum_new=number($total_new_contacts);
        // $sum_lost=number($total_lost_contacts);
        // $sum_contacts=number($total_contacts);
        // $sum_new_contacts=number($total_new_contacts);
    }


    $table_data[] = array(
        'store_key'                   => '',
        'name'                        => '',
        'code'                        => _('Total').($filtered > 0 ? ' '.'<i class="fa fa-filter fa-fw"></i>' : ''),
        'contacts'                    => (integer)$total_contacts,
        'active_contacts'             => (integer)$total_active_contacts,
        'new_contacts'                => (integer)$total_new_contacts,
        'lost_contacts'               => (integer)$total_lost_contacts,
        'losing_contacts'             => (integer)$total_losing_contacts,
        'contacts_with_orders'        => (integer)$total_contacts_with_orders,
        'active_contacts_with_orders' => (integer)$total_active_contacts_with_orders,
        'new_contacts_with_orders'    => (integer)$total_new_contacts_with_orders,
        'lost_contacts_with_orders'   => (integer)$total_lost_contacts_with_orders,
        'losing_contacts_with_orders' => (integer)$total_losing_contacts_with_orders,
        'users'                       => (integer)$total_users,
        'never_order'                 => (integer)$total_contacts_never_order

    );


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total
        )
    );
    echo json_encode($response);
}


function customers_geographic_distribution($_data, $db, $user, $account)
{
    include_once 'prepare_table/customers_geographic_distribution.ptc.php';
    $table = new prepare_table_customers_geographic_distribution($db, $account, $user);
    echo $table->fetch($_data);
}


function abandoned_cart_mail_list($_data, $db, $user)
{
    $rtext_label = 'recipient';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $inactive_since = strftime("%e %b %y", strtotime($data['Order Date'].' +0:00'));


            $customer_link_format = '/customers/%d/%d';
            $order_link_format    = '/orders/%d/%d';


            $table_data[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => sprintf('<span class="link" onClick="change_view(\''.$customer_link_format.'\')">%06d</span>', $data['Order Store Key'], $data['Customer Key'], $data['Customer Key']),
                'order'        => sprintf('<span class="link" onClick="change_view(\''.$order_link_format.'\')">%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']),

                'name'         => $data['Customer Name'],
                'company_name' => $data['Customer Company Name'],
                'contact_name' => $data['Customer Main Contact Name'],

                'email'          => $data['Customer Main Plain Email'],
                'inactive_since' => $inactive_since,
                'inactive_days'  => '<span title="'.sprintf(_('Inactive since %s'), $inactive_since).'">'.number($data['inactive_days']).'</span>'


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function newsletter_mail_list($_data, $db, $user)
{
    $rtext_label = 'recipient';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $customer_link_format = '/customers/%d/%d';


            $table_data[] = array(
                'id'           => (integer)$data['Customer Key'],
                'store_key'    => $data['Customer Store Key'],
                'formatted_id' => sprintf('<span class="link" onClick="change_view(\''.$customer_link_format.'\')">%06d</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Key']),

                'name'         => $data['Customer Name'],
                'company_name' => $data['Customer Company Name'],
                'contact_name' => $data['Customer Main Contact Name'],

                'email' => $data['Customer Main Plain Email'],


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function poll_queries($_data, $db, $user)
{
    if ($_data['parameters']['parent'] == 'store') {
        $store           = get_object('Store', $_data['parameters']['parent_key']);
        $total_customers = $store->get('Store Contacts');
    } else {
        exit('ar_customers_tables, todo E:1234a');
    }

    $rtext_label = 'poll query';

    $ordinal_formatter = new \NumberFormatter("en-GB", \NumberFormatter::ORDINAL);

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            switch ($data['Customer Poll Query Type']) {
                case 'Options':
                    $type = _('Multiple choice');

                    $title_on_enough_options = _('Query will not be shown to customer until it has more than one option');

                    if ($data['Customer Poll Query Options'] == 0) {
                        $type            .= ' <span class="error">('._('Not options set').')</span>';
                        $in_registration = ($data['Customer Poll Query In Registration'] == 'Yes' ? '<i title="'.$title_on_enough_options.'" class="fa fa-check error discreet"></i>' : '<i class="fa fa-check discreet"></i>');
                        $in_profile      = ($data['Customer Poll Query In Profile'] == 'Yes' ? '<i title="'.$title_on_enough_options.'" class="fa fa-check error discreet"></i>' : '<i class="fa fa-check discreet"></i>');
                    } elseif ($data['Customer Poll Query Options'] == 1) {
                        $type            .= ' <span class="warning">('._('Only one options set').')</span>';
                        $in_registration = ($data['Customer Poll Query In Registration'] == 'Yes' ? '<i title="'.$title_on_enough_options.'" class="fa fa-check error warning"></i>' : '<i class="fa fa-check discreet"></i>');
                        $in_profile      = ($data['Customer Poll Query In Profile'] == 'Yes' ? '<i title="'.$title_on_enough_options.'" class="fa fa-check error warning"></i>' : '<i class="fa fa-check discreet"></i>');
                    } else {
                        $type .= ' ('.sprintf(
                                ngettext(
                                    "%s option",
                                    "%s options",
                                    $data['Customer Poll Query Options']
                                ),
                                number($data['Customer Poll Query Options'])
                            ).')';

                        $in_registration = ($data['Customer Poll Query In Registration'] == 'Yes' ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-check discreet"></i>');
                        $in_profile      = ($data['Customer Poll Query In Profile'] == 'Yes' ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-check discreet"></i>');
                    }


                    break;
                case 'Open':
                    $type            = _('Open answer');
                    $in_registration = ($data['Customer Poll Query In Registration'] == 'Yes' ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-check discreet"></i>');
                    $in_profile      = ($data['Customer Poll Query In Profile'] == 'Yes' ? '<i class="fa fa-check success"></i>' : '<i class="fa fa-check discreet"></i>');
                    break;
                default:
                    exit('error not customer poll query E1');
                    break;
            }


            $table_data[] = array(
                'id'                   => (integer)$data['Customer Poll Query Key'],
                'type'                 => $type,
                'query'                => sprintf(
                    '<span class="link" onclick="change_view(\'/customers/%d/poll_query/%d\')" title="%s">%s</span>',
                    $data['Customer Poll Query Store Key'],
                    $data['Customer Poll Query Key'],
                    $data['Customer Poll Query Label'],
                    $data['Customer Poll Query Name']
                ),
                'label'                => $data['Customer Poll Query Label'],
                'in_registration'      => $in_registration,
                'in_profile'           => $in_profile,
                'customers'            => number($data['Customer Poll Query Customers']),
                'customers_percentage' => percentage($data['Customer Poll Query Customers'], $total_customers),

                'position' => $ordinal_formatter->format($data['Customer Poll Query Position']),


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function poll_query_options($_data, $db, $user)
{
    if ($_data['parameters']['parent'] == 'Customer_Poll_Query') {
        $poll            = get_object('Customer_Poll_Query', $_data['parameters']['parent_key']);
        $total_customers = $poll->get('Customer Poll Query Customers');
    } else {
        exit('ar_customers_tables, todo E:1234a');
    }

    $rtext_label = 'poll option';

    $ordinal_formatter = new \NumberFormatter("en-GB", \NumberFormatter::ORDINAL);

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($data['Customer Poll Query Option Last Answered'] != '') {
                $last_chose = strftime("%e %b %y", strtotime($data['Customer Poll Query Option Last Answered'].' +0:00'));
            } else {
                $last_chose = '';
            }


            $table_data[] = array(
                'id'    => (integer)$data['Customer Poll Query Option Key'],
                'code'  => sprintf(
                    '<span class="link" onclick="change_view(\'/customers/%d/poll_query/%d/option/%d\')" title="%s">%s</span>',
                    $data['Customer Poll Query Option Store Key'],
                    $data['Customer Poll Query Option Query Key'],
                    $data['Customer Poll Query Option Key'],
                    $data['Customer Poll Query Option Label'],
                    $data['Customer Poll Query Option Name']
                ),
                'label' => $data['Customer Poll Query Option Label'],

                'customers'            => number($data['Customer Poll Query Option Customers']),
                'customers_percentage' => percentage($data['Customer Poll Query Option Customers'], $total_customers),
                'last_chose'           => $last_chose


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function poll_query_answers($_data, $db, $user)
{
    $rtext_label = 'answer';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $link_format = '/customers/%d/%d';


            $table_data[] = array(
                'id'           => (integer)$data['Customer Poll Key'],
                'formatted_id' => sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Key']),
                'customer'     => $data['Customer Name'],
                'answer'       => $data['Customer Poll Reply'],
                'date'         => strftime("%e %b %y", strtotime($data['Customer Poll Date'].' +0:00'))


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function asset_customers($_data, $db, $user)
{
    if ($_data['parameters']['parent'] == 'favourites') {
        $rtext_label = 'customer who favored';
    } else {
        $rtext_label = 'customer';
    }

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($data['invoices'] == 0 or $data['last_invoice'] == '') {
                $last_invoice_date = '';
                $invoiced_amount   = '';
            } else {
                $last_invoice_date = strftime(
                    "%e %b %y",
                    strtotime(
                        $data['last_invoice'].' +0:00'
                    )
                );
                $invoiced_amount   = money($data['invoiced_amount'], $data['Order Currency Code']);
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


            $link_format  = '/customers/%d/%d';
            $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $data['Customer Store Key'], $data['Customer Key'], $data['Customer Key']);


            $table_data[] = array(
                'id'              => (integer)$data['Customer Key'],
                'store_key'       => $data['Customer Store Key'],
                'formatted_id'    => $formatted_id,
                'name'            => $data['Customer Name'],
                'location'        => $data['Customer Location'],
                'invoices'        => $data['invoices'],
                'last_invoice'    => $last_invoice_date,
                'activity'        => $activity,
                'invoiced_amount' => $invoiced_amount,
                'favourited'      => '<span class="'.(!$data['favourited'] ? 'super_discreet' : '').'">'.number($data['favourited']).'</span>',
                'basket_amount'   => ($data['basket_amount'] == 0 ? '' : money($data['basket_amount'], $data['Order Currency Code']))


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function prospects($_data, $db, $user)
{
    $rtext_label = 'prospect';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    //  print $sql;

    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $contact_since = strftime(
                "%e %b %y",
                strtotime($data['Prospect First Contacted Date'].' +0:00')
            );


            switch ($data['Prospect Status']) {
                case 'NoContacted':
                    $status = _('To be contacted');
                    break;
                case 'Contacted':
                    $status = _('Contacted');
                    break;
                case 'NotInterested':
                    $status = _('Not interested');
                    break;
                case 'Registered':
                    $status = _('Registered');
                    break;
                case 'Invoiced':
                    $status = _('Invoiced');
                    break;
                case 'Bounced':
                    $status = _('Bounced');
                    break;
                default:
                    $status = $data['Prospect Status'];
                    break;
            }


            $link_format = '/prospects/%d/%d';

            $prospect_name=$data['Prospect Name'];
            if($prospect_name==''){
                $prospect_name='Unknown';
            }

            $name        = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%s</span>',
                $parameters['parent_key'], $data['Prospect Key'],$prospect_name);


            $table_data[] = array(
                'id'        => (integer)$data['Prospect Key'],
                'store_key' => $data['Prospect Store Key'],

                'name'         => $name,
                'company_name' => $data['Prospect Company Name'],
                'contact_name' => $data['Prospect Main Contact Name'],

                'location' => $data['Prospect Location'],

                'email'     => $data['Prospect Main Plain Email'],
                'telephone' => $data['Prospect Main XHTML Telephone'],
                'mobile'    => $data['Prospect Main XHTML Mobile'],


                'contact_since' => $contact_since,

                'status'  => $status,
                'address' => $data['Prospect Contact Address Formatted']


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function prospects_email_templates($_data, $db, $user)
{
    include_once 'utils/natural_language.php';

    $rtext_label = 'email template';
    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    //  print $sql;

    $table_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($data['Staff Alias'] != '') {
                $author = sprintf('<span>%s</span>', $data['Staff Alias']);
            } else {
                $author = sprintf('<span class="discreet">%s</span>', _('Anonymous'));
            }

            switch ($data['Email Template State']) {
                case 'Active':
                    $state = '<i class="fa success fa-play fa-fw"></i> '.('Active');
                    break;
                case 'Suspended':
                    $state = '<i class="fa error fa-stop fa-fw"></i> '._('Suspended');
                    break;
            }

            $table_data[] = array(
                'id'      => (integer)$data['Email Template Key'],
                'author'  => $author,
                'state'   => $state,
                'subject' => $data['Email Template Subject'],

                'name' => sprintf('<span class="link" onclick="change_view(\'prospects/%d/template/%d\')">%s</span>', $_data['parameters']['store_key'], $data['Email Template Key'], $data['Email Template Name']),
                'date' => strftime("%a %e %b %Y", strtotime($data['Email Template Created'].' +0:00')),

            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function sales_history($_data, $db, $user, $account)
{
    $skip_get_table_totals = true;


    //print_r($_data);

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

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
        case 'customer':
            $customer = get_object('Customer', $_data['parameters']['parent_key']);
            $store    = get_object('Store', $customer->get('Customer Store Key'));

            $currency   = $store->get('Store Currency Code');
            $from       = $customer->get('Customer First Contacted Date');
            $to         = gmdate('Y-m-d');
            $date_field = '`Timeseries Record Date`';
            break;
        /*
    case 'category':
        include_once 'class.Category.php';
        $category   = new Category($_data['parameters']['parent_key']);
        $currency   = $account->get('Account Currency');
        $from       = $category->get('Part Category Valid From');
        $to         = ($category->get('Part Category Status') == 'NotInUse' ? $product->get('Part Category Valid To') : gmdate('Y-m-d'));
        $date_field = '`Timeseries Record Date`';
        break;
        */ default:
        print_r($_data);
        exit('parent not configured '.$_data['parameters']['parent']);
        break;
    }


    $sql_totals = sprintf(
        'SELECT count(DISTINCT %s) AS num FROM kbase.`Date Dimension` WHERE `Date`>=DATE(%s) AND `Date`<=DATE(%s) ',
        $sql_totals_fields,
        prepare_mysql($from),
        prepare_mysql($to)

    );


    list($rtext, $total, $filtered) = get_table_totals(
        $db,
        $sql_totals,
        '',
        $rtext_label,
        false
    );


    $sql = sprintf(
        'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) %s ORDER BY %s  LIMIT %s',
        prepare_mysql($from),
        prepare_mysql($to),
        $_group_by,
        "`Date` $order_direction ",
        "$start_from,$number_results"
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
                    "(%e %b) %Y %W ",
                    strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
                $_date = date('Y-m-d', strtotime($data['Date'].' +0:00'));
            }


            $record_data[$_date] = array(
                'sales'    => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'profit'   => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'invoices' => '<span class="very_discreet">'.number(0).'</span>',
                'refunds'  => '<span class="very_discreet">'.number(0).'</span>',

                'invoiced_amount' => '<span class="very_discreet">'.money(0, $currency).'</span>',
                'refunded_amount' => '<span class="very_discreet">'.money(0, $currency).'</span>',


                'date' => $date


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


    switch ($_data['parameters']['parent']) {
        case 'customer':
        case 'category':
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
                $from_date = gmdate("Y-m-d", strtotime($from_date.' -1 year  +0:00'));
                $to_date   = gmdate("Y-m-d", strtotime($to_date.'  +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $from_date = gmdate("Y-m-d", strtotime($from_date.' - 1 year +0:00'));
                $to_date   = $to_date;
            }
            $group_by = '';

            break;
        default:
            print_r($_data);
            exit('Parent not configured '.$_data['parameters']['parent']);
            break;
    }


    $sql = sprintf(
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s order by $date_field    ",
        $date_field,
        prepare_mysql($from_date),
        $date_field,
        prepare_mysql($to_date),
        " $group_by "
    );

    $last_year_data = array();


    //print $sql;
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($_data['parameters']['frequency'] == 'annually') {
                $_date           = strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%Y", strtotime($data['Date'].' - 1 year +0:00'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'quarterly') {
                $_date           = 'Q'.ceil(date('n', strtotime($data['Date'].' +0:00')) / 3).' '.strftime("%Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = 'Q'.ceil(date('n', strtotime($data['Date'].' - 1 year +0:00')) / 3).' '.strftime("%Y", strtotime($data['Date'].' - 1 year +0:00'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'monthly') {
                $_date           = strftime("%b %Y", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%b %Y", strtotime($data['Date'].' - 1 year +0:00'));
                $date            = $_date;
            } elseif ($_data['parameters']['frequency'] == 'weekly') {
                $_date           = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
                $_date_last_year = strftime("%Y%W ", strtotime($data['Date'].' - 1 year +0:00'));
                $date            = strftime("(%e %b) %Y %W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $_date           = date('Y-m-d', strtotime($data['Date'].' +0:00'));
                $_date_last_year = date('Y-m-d', strtotime($data['Date'].' -1 year +0:00'));
                $date            = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
            }

            $last_year_data[$_date] = array('_sales' => $data['sales']);


            if (array_key_exists($_date, $record_data)) {
                if (in_array(
                        $_data['parameters']['frequency'], array(
                                                             'annually',
                                                             'quarterly',
                                                             'monthly'
                                                         )
                    ) and $_data['parameters']['parent'] == 'customer' and false) {
                    $invoices = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/timeseries/%d/%d\')">%s</span>',
                        $_data['parameters']['parent'],
                        $_data['parameters']['parent_key'],
                        $data['Timeseries Record Timeseries Key'],

                        $data['Timeseries Record Key'],
                        number($data['invoices'])
                    );

                    $refunds = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/timeseries/%d/%d\')">%s</span>',
                        $_data['parameters']['parent'],
                        $_data['parameters']['parent_key'],
                        $data['Timeseries Record Timeseries Key'],
                        $data['Timeseries Record Key'],
                        number($data['refunds'])
                    );

                    $sales = sprintf(
                        '<span class="link" onclick="change_view(\'%s/%d/timeseries/%d/%d\')">%s</span>',
                        $_data['parameters']['parent'],
                        $_data['parameters']['parent_key'],
                        $data['Timeseries Record Timeseries Key'],
                        $data['Timeseries Record Key'],
                        money($data['sales'], $currency)
                    );
                } else {
                    $invoices        = number($data['invoices']);
                    $refunds         = number($data['refunds']);
                    $sales           = money($data['sales'], $currency);
                    $invoiced_amount = money($data['invoiced_amount'], $currency);
                    $refunded_amount = money($data['refunded_amount'], $currency);
                }


                $record_data[$_date] = array(
                    'sales'           => $sales,
                    'invoices'        => $invoices,
                    'refunds'         => $refunds,
                    'invoiced_amount' => $invoiced_amount,
                    'refunded_amount' => $refunded_amount,
                    'date'            => $record_data[$_date]['date']


                );
            }


            if (isset($last_year_data[$_date_last_year])) {
                $record_data[$_date]['delta_sales_1yb'] =
                    '<span title="'.money($last_year_data[$_date_last_year]['_sales'], $currency).'">'.delta($data['sales'], $last_year_data[$_date_last_year]['_sales']).' '.delta_icon($data['sales'], $last_year_data[$_date_last_year]['_sales']).'</span>';
            }
            //    print_r($record_data);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql";
        exit;
    }


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

function products($_data, $db, $user, $account)
{
    include_once 'utils/currency_functions.php';

    $rtext_label = 'product';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

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

                'id'       => (integer)$data['Product ID'],
                'code'     => $code,
                'name'     => $name,
                'status'   => $status,
                'amount'   => sprintf('<span>%s</span>', money($data['amount'], $data['Store Currency Code'])),
                'invoices' => sprintf(
                    '<span class="link" onclick="change_view(\'customers/%d/%d/product/%d\',{ })">%s</span>',
                    $data['Store Key'],
                    $data['Customer Key'],
                    $data['Product ID'],
                    number($data['invoices'])
                ),
                'qty'      => sprintf('<span>%s</span>', number($data['qty']))


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

function families($_data, $db, $user, $account)
{
    include_once 'utils/currency_functions.php';

    $rtext_label = 'family';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $label = $data['Category Label'];

            $code = sprintf('<span class="link" onClick="change_view(\'category/%d/\')" >%s</span>', $data['Category Key'], $data['Category Code']);


            $record_data[] = array(

                'id'       => (integer)$data['Category Key'],
                'code'     => $code,
                'label'    => $label,
                'amount'   => sprintf('<span>%s</span>', money($data['amount'], $data['Store Currency Code'])),
                'invoices' => sprintf('<span >%s</span>', number($data['invoices'])),
                'products' => sprintf('<span>%s</span>', number($data['products']))


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


function credit_blockchain($_data, $db, $user, $account)
{
    //'Payment','Adjust','Cancel','Return','PayReturn','AddFunds'

    $rtext_label = 'transaction';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            $note = '';
            switch ($data['Credit Transaction Type']) {
                case 'Payment':

                    if ($data['Credit Transaction Amount'] < 0) {
                        $type = _('Used');
                        $note = sprintf('<span class="link" onclick="change_view(\'orders/%d/%d\')" >%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']);
                    } else {
                        $note = sprintf('<span class="link" onclick="change_view(\'orders/%d/%d\')" >%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']);


                        if ($data['Invoice Key'] and $data['Invoice Type'] == 'Refund') {
                            $type = _('Credited from refund');

                            $note .= sprintf(
                                ' <span class="error padding_left_10"> <i class="fal fa-file-invoice-dollar error"></i> <span class="link error" onclick="change_view(\'invoices/%d/%d\')" >%s</span></span>',
                                $data['Order Store Key'],
                                $data['Invoice Key'],
                                $data['Invoice Public ID']
                            );
                        } else {
                            $icon = 'fa-sack-dollar';

                            $type = _('Credited from payment');
                            $note .= sprintf(
                                ' <span class=" padding_left_10"> <i class="fal %s "></i> <span class="link discreet" onclick="change_view(\'payments/%d/%d\')" >%s</span></span>',
                                $icon,
                                $data['Order Store Key'],
                                $data['Payment Related Payment Key'],
                                $data['Payment Related Payment Transaction ID']
                            );
                        }
                    }


                    break;
                case 'Cancel':
                    $type = _('Cancelled');

                    break;
                case 'Return':
                    $type = _('Return');
                    $note = $data['History Abstract'];

                    break;
                case 'MoneyBack':
                case 'RemoveFundsOther':
                    $type = _('Withdraw');
                    $note = $data['History Abstract'];

                    break;
                case 'PayReturn':
                case 'Compensation':
                case 'AddFundsOther':
                    $type = _('Deposit');
                    $note = $data['History Abstract'];

                    break;

                case 'TransferOut':
                case 'TransferIn':
                    $type = _('Transfer');
                    $note = $data['History Abstract'];

                    break;

                case 'Adjust':
                    $type = _('Adjust');
                    $note = $data['History Abstract'];
                    break;
                case 'TopUp':
                    $type = _('Top up');

                    break;
                default:
                    $type = $data['Credit Transaction Type'];
            }


            $amount_ac = $data['Credit Transaction Amount'] * $data['Credit Transaction Currency Exchange Rate'];
            $date      = strftime(
                "%a %e %b %y %T %Z",
                strtotime($data['Credit Transaction Date'].' +0:00')
            );

            $table_data[] = array(
                'id'             => (integer)$data['Credit Transaction Key'],
                'amount'         => money($data['Credit Transaction Amount'], $data['Credit Transaction Currency Code']),
                'running_amount' => money($data['Credit Transaction Running Amount'], $data['Credit Transaction Currency Code']),

                'type'  => $type,
                'notes' => $note,


                'amount_ac' => money($amount_ac, $account->get('Currency Code')),
                'date'      => $date


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

/**
 * @param $_data
 * @param $db \PDO
 * @param $user
 */
function customer_clients($_data, $db, $user)
{
    $rtext_label = 'customer clients';

    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            if ($data['Customer Client Code'] == '') {
                $code = sprintf('<span class="italic">%05d</span>', $data['Customer Client Key']);
            } else {
                $code = $data['Customer Client Code'];
            }

            if ($data['Customer Client Status'] == 'Active') {
                $status = '';
            } else {
                $status = '<i class="warning fa fa-eye-slash fa-fw" title="'._('Removed').'"></i>';
            }


            $table_data[] = array(
                'id'             => (integer)$data['Customer Client Key'],
                'status'         => $status,
                'code'           => sprintf('<span class="link" onclick="change_view(\'customers/%d/%d/client/%d\')">%s</span>', $data['Customer Client Store Key'], $data['Customer Client Customer Key'], $data['Customer Client Key'], $code),
                'name'           => $data['Customer Client Name'],
                'since'          => strftime("%e %b %y", strtotime($data['Customer Client Creation Date'].' +0:00')),
                'location'       => $data['Customer Client Location'],
                'pending_orders' => number($data['Customer Client Pending Orders']),
                'invoices'       => number($data['Customer Client Number Invoices']),
                'last_invoice'   => ($data['Customer Client Last Invoice Date'] == '' ? '' : strftime("%e %b %y", strtotime($data['Customer Client Last Invoice Date'].' +0:00'))),

                'total_invoiced_amount' => money($data['Customer Client Invoiced Amount'], $data['Customer Client Currency Code']),
                'address'               => $data['Customer Client Contact Address Formatted'],
                'email'                 => $data['Customer Client Main Plain Email'],
                'telephone'             => $data['Customer Client Main XHTML Telephone'],
                'mobile'                => $data['Customer Client Main XHTML Mobile'],

            );
        }
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function customer_portfolio($_data, $db, $user)
{
    include_once 'utils/currency_functions.php';

    $rtext_label = 'product';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();

    $record_data = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {
            //'Excess','Normal','Low','VeryLow','OutofStock','Error','OnDemand'
            switch ($data['Product Availability State']) {
                case 'Excess':
                case 'Normal':


                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#13D13D" title="%s"></i>', _('Active'));
                    break;
                case 'OnDemand':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#13D13D" title="%s"></i>', _('On demand'));
                    break;
                case 'Error':
                case 'OutofStock':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#F25056" title="%s"></i>', _('Out of stock'));
                    break;
                case 'VeryLow':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#ff7c00" title="%s"></i>', _('Very low stock'));
                    break;
                case 'Low':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#FCBE07" title="%s"></i>', _('Low stock'));
                    break;

                default:
                    $stock_status = $data['Product Availability State'];
                    break;
            }


            if ($data['Product Availability State'] == 'Discontinued') {
                $status_icon = ' <i class="fa fa-skull" title="'._('Discontinued').'"></i>';
            } elseif ($data['Product Availability State'] == 'Discontinuing') {
                $status_icon = ' <i class="fa fa-skull" title="'._('Discontinuing').'"></i>';
            } else {
                $status_icon = '';
            }


            $name = '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product Name'].'</span>';

            $code = sprintf('<span class="link" onClick="change_view(\'products/%d/%d\')" title="%s">%s</span>', $data['Store Key'], $data['Product ID'], $name, $data['Product Code']);


            $record_data[] = array(

                'id'           => (integer)$data['Product ID'],
                'code'         => $code.$status_icon,
                'name'         => $name,
                'stock_status' => $stock_status,
                'created'      => ($data['Customer Portfolio Creation Date'] == ''
                    ? ''
                    : sprintf(
                        '<span title="%s">%s</span>',
                        strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Customer Portfolio Creation Date'].' +0:00')),
                        strftime("%e %b %Y", strtotime($data['Customer Portfolio Creation Date'].' +0:00'))

                    )),
                'until'        => ($data['Customer Portfolio Removed Date'] == ''
                    ? ''
                    : sprintf(
                        '<span title="%s">%s</span>',
                        strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Customer Portfolio Removed Date'].' +0:00')),
                        strftime("%e %b %Y", strtotime($data['Customer Portfolio Removed Date'].' +0:00'))

                    )),
                'last_order'   => ($data['Customer Portfolio Last Ordered'] == '' ? '' : strftime("%a %e %b %Y", strtotime($data['Customer Portfolio Last Ordered'].' +0:00'))),

                'amount' => sprintf('<span>%s</span>', money($data['Customer Portfolio Amount'], $data['Store Currency Code'])),

                'qty'        => sprintf('<span>%s</span>', number($data['Customer Portfolio Ordered Quantity'])),
                'orders'     => sprintf('<span>%s</span>', number($data['Customer Portfolio Orders'])),
                'clients'    => sprintf('<span>%s</span>', number($data['Customer Portfolio Clients'])),
                'operations' => sprintf('<i class="far button fa-trash-alt" onclick="remove_item_from_portfolio(this,%d,%s)" ></i>', $data['Customer Portfolio Customer Key'], $data['Product ID'])

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


function customers_registrations($_data, $db, $user, $account)
{
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

            break;
        default:
            print_r($_data);
            exit('parent not configured');
    }


    $sql_totals = sprintf(
        'SELECT count(DISTINCT %s) AS num FROM kbase.`Date Dimension` WHERE `Date`>=DATE(%s) AND `Date`<=DATE(%s) ',
        $sql_totals_fields,
        prepare_mysql($from),
        prepare_mysql($to)

    );
    list($rtext, $total, $filtered) = get_table_totals($db, $sql_totals, '', $rtext_label, false);


    $sql = sprintf(
        'SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) %s ORDER BY %s  LIMIT %s',
        prepare_mysql($from),
        prepare_mysql($to),
        $_group_by,
        "`Date` $order_direction ",
        "$start_from,$number_results"
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
                    "(%e %b) %Y %W ",
                    strtotime($data['Date'].' +0:00')
                );
                $_date = strftime("%Y%W ", strtotime($data['Date'].' +0:00'));
            } elseif ($_data['parameters']['frequency'] == 'daily') {
                $date  = strftime("%a %e %b %Y", strtotime($data['Date'].' +0:00'));
                $_date = date('Y-m-d', strtotime($data['Date'].' +0:00'));
            }

            $record_data[$_date] = array(
                'customers'    => '<span class="very_discreet">'.number(0).'</span>',
                'new_invoices' => '<span class="very_discreet">'.number(0).'</span>',

                'date' => $date


            );
        }
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
        "select $fields from $table $where $wheref and %s>=%s and  %s<=%s %s order by $date_field    ",
        $date_field,
        prepare_mysql($from_date),
        $date_field,
        prepare_mysql($to_date),
        " $group_by "
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

            $last_year_data[$_date] =
                array(
                    '_new_customers'         => $data['new_customers'],
                    '_customers_with_orders' => $data['customers_with_orders']

                );


            if (array_key_exists($_date, $record_data)) {
                $record_data[$_date] = array(
                    //   'sales'     => money($data['sales'], $currency),
                    'new_customers'         => number($data['new_customers']),
                    'customers_with_orders' => number($data['customers_with_orders']),

                    // 'invoices'  => number($data['invoices']),
                    // 'outers'    => (isset($data['outers']) ? number($data['outers']) : ''),
                    'date'                  => $record_data[$_date]['date']
                );


                if (isset($last_year_data[$_date_last_year])) {
                    $record_data[$_date]['delta_new_customers_1yb']         = '<span title="'.number($last_year_data[$_date_last_year]['_new_customers']).'">'.delta($data['new_customers'], $last_year_data[$_date_last_year]['_new_customers']).' '.delta_icon(
                            $data['new_customers'],
                            $last_year_data[$_date_last_year]['_new_customers']
                        ).'</span>';
                    $record_data[$_date]['delta_customers_with_orders_1yb'] =
                        '<span title="'.number($last_year_data[$_date_last_year]['_customers_with_orders']).'">'.delta($data['customers_with_orders'], $last_year_data[$_date_last_year]['_customers_with_orders']).' '.delta_icon(
                            $data['customers_with_orders'],
                            $last_year_data[$_date_last_year]['_customers_with_orders']
                        ).'</span>';
                }
            }
        }
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