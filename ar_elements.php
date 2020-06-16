<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 October 2015 at 11:53:01 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


if (!isset($_REQUEST['tab'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tab = $_REQUEST['tab'];

switch ($tab) {
    case 'stock_given_free':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_stock_given_free_element_numbers($db, $data['parameters'], $user, $account);
        break;
    case 'lost_stock':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_lost_stock_element_numbers($db, $data['parameters'], $user, $account);
        break;
    case 'agent_parts':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_agent_parts_element_numbers($db, $data['parameters'], $user);
        break;
    case 'suppliers':
    case 'agent.suppliers':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_suppliers_element_numbers($db, $data['parameters'], $user);
        break;
    case 'suppliers.deliveries':
        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        get_supplier_deliveries_element_numbers($db, $data['parameters'], $user);
        break;
    case 'production_supplier.deliveries':
        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        get_production_deliveries_element_numbers($db, $data['parameters'], $user);
        break;
    case 'warehouse.returns':
        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        get_returns_element_numbers($db, $data['parameters'], $user);
        break;
    case 'suppliers.orders':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_supplier_orders_elements($db, $data['parameters'], $user);
        break;
    case 'website.webpages':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_webpages_element_numbers($db, $data['parameters'], $user);
        break;
    case 'website.online_webpages':
    case 'webpage_type.online_webpages':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_webpages_by_state_element_numbers($db, $data['parameters'], $user, 'Online');
        break;
    case 'website.offline_webpages':
    case 'webpage_type.offline_webpages':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_webpages_by_state_element_numbers($db, $data['parameters'], $user, 'Offline');
        break;
    case 'website.in_process_webpages':
    case 'webpage_type.in_process_webpages':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_webpages_by_state_element_numbers($db, $data['parameters'], $user, 'InProcess');
        break;

    case 'website.ready_webpages':
    case 'webpage_type.ready_webpages':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_webpages_by_state_element_numbers($db, $data['parameters'], $user, 'Ready');
        break;

    case 'orders.in_process.paid':
    case 'orders.in_process.not_paid':
    case 'orders.in_warehouse_no_alerts':
    case 'orders.in_warehouse_with_alerts':
    case 'orders.packed_done':
    case 'orders.approved':
    case 'orders.dispatched_today':
    case 'orders.website':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_orders_control_panel_numbers($tab, $db, $data['parameters'], $user, $account);
        break;


    case 'campaigns':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_campaigns_element_numbers($db, $data['parameters'], $user);
        break;
    case 'deals':
    case 'vouchers':

        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        get_deals_element_numbers($db, $data['parameters'], $user);
        break;
    case 'campaign_bulk_deals':
        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        get_fixed_deals_element_numbers($db, $data['parameters'], $user);
        break;
    case 'inventory.parts':
    case 'category.parts':
    case 'category.all_parts':
    case 'material.parts':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );

        if ($tab == 'category.all_parts') {
            $data['parameters']['parent']     = 'account';
            $data['parameters']['parent_key'] = 1;
        }

        get_parts_elements($db, $data['parameters'], $user);
        break;

    case 'part_families':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));


        get_part_families_elements($db, $data['parameters'], $user);
        break;
    case 'warehouse.locations':
    case 'warehouse_area.locations':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_warehouse_locations_elements($db, $data['parameters'], $user);
        break;
    case 'customers':
    case 'website.favourites.customers':
    case 'product.customers':
    case 'product.customers.favored':
    case 'poll_query_option.customers':
    case 'sales_representative.customers':
    case 'customers_dropshipping':

        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_customers_element_numbers($db, $data['parameters'], $user);
        break;

    case 'store.products':
    case 'category.products':
    case 'part.products':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_products_element_numbers($db, $data['parameters'], $user);
        break;

    case 'store.services':


        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_services_element_numbers($db, $data['parameters'], $user);
        break;


    case 'category.product_categories':
    case 'category.product_families':
    case 'category.product_categories.products':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_product_categories_element_numbers($db, $data['parameters'], $user);
        break;
    case 'category.product_categories.categories':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_product_categories_element_numbers_bis($db, $data['parameters'], $user);
        break;

    case 'orders_server':
    case 'orders':
    case 'product.orders':
    case 'customer.orders':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_orders_element_numbers($db, $data['parameters'], $user);
        break;


    case 'delivery_notes_server':

        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_delivery_notes_element_numbers($db, $data['parameters'], $user);
        break;


    case 'invoices_server':
    case 'invoices':
    case 'customer.invoices':
    case 'category.invoices':
    case 'sales_representative.invoices':

        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_invoices_element_numbers($db, $data['parameters'], $user);
        break;
    case 'customer.history':
    case 'prospect.history':
    case 'supplier_part.history':
    case 'agent.history':
    case 'location.history':
    case 'deal.history':
    case 'campaign.history':
    case 'supplier.order.history':
    case 'supplier.delivery.history':

    case 'category.webpage.logbook':
    case 'supplier.history':
    case 'charge.history':
    case 'mailshot.history':
    case 'poll_query_option.history':
    case 'poll_query.history':
    case 'purge.history':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_history_elements($db, $data['parameters'], $user);
        break;
    case 'inventory.barcodes':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_barcodes_elements($db, $data['parameters'], $user);
        break;
    case 'supplier.supplier_parts':
    case 'agent.supplier_parts':
    case 'supplier.order.supplier_parts':
    case 'production.production_parts':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_supplier_parts_elements($db, $data['parameters'], $user);
        break;
    case 'supplier.orders':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_supplier_orders_elements($db, $data['parameters'], $user);
        break;
    case 'production_supplier.orders':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_production_orders_elements($db, $data['parameters'], $user);
        break;
    case 'production_part.supplier.orders':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_production_orders_with_part_elements($db, $data['parameters'], $user);
        break;
    case 'agent.orders':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_agent_orders_elements($db, $data['parameters'], $user);
        break;

    case 'agent.client_orders':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_agent_client_orders_elements($db, $data['parameters'], $user);
        break;


    case 'part.stock.transactions':
    case 'inventory.stock.transactions':
    case 'location.stock.transactions':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_stock_transactions_elements($db, $data['parameters'], $user);
        break;
    case 'category_root.all_parts':

        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_category_root_all_parts_elements($db, $data['parameters'], $user);
        break;
    case 'ec_sales_list':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_ec_sales_list_elements($db, $data['parameters'], $account, $user);
        break;

    case 'warehouse.leakages.transactions':

        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        warehouse_leakages_transactions($db, $data['parameters'], $user);
        break;
    case 'inventory.parts_barcode_errors.wget':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        parts_barcode_errors($db, $data['parameters'], $user);
        break;
    case 'prospects':
    case 'prospect_agent.prospects':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_prospects_elements($db, $data['parameters'], $user);
        break;
    case 'purge.purged_orders':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_purged_orders_elements($db, $data['parameters'], $user);
        break;
    case 'email_campaign.sent_emails':
    case 'mailshot.sent_emails':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_email_campaign_sent_emails_elements($db, $data['parameters'], $user);
        break;
    case 'account.mailshots':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_account_mailshots_elements($db, $data['parameters'], $user);
        break;
    case 'users.staff':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_users_elements($db, 'Staff');
        break;
    case 'users.contractors':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_users_elements($db, 'Contractor');
        break;
    case 'users.suppliers':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_users_elements($db, 'Supplier');
        break;
    case 'users.agents':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_users_elements($db, 'Agent');
        break;

    case 'users':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_all_users_elements($db, 'Staff');
        break;
    case 'category.deal_components':
        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        get_category_deal_components_element_numbers($db, $data['parameters'], $user);
        break;
    case 'inventory.parts_weight_errors.wget':
        $data = prepare_values(
            $_REQUEST, array('parameters' => array('type' => 'json array'))
        );
        parts_weight_errors($db, $data['parameters'], $user);
        break;
    case 'websites':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_websites_elements($db, $data['parameters'], $account, $user);
        break;
    case 'supplier.order.items':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_purchase_order_items_elements($db, $data['parameters']);
        break;
    case 'customer.active_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_customer_active_portfolio_elements($db, $data['parameters']);
        break;
    case 'delivery_notes':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_delivery_notes_elements($db, $data['parameters'], $user);
        break;
    case 'attendance':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        get_attendance_elements($db, $data['parameters'], $user);
        break;
    case 'job_order.items':
        $data = prepare_values($_REQUEST, array('parameters' => array('type' => 'json array')));
        job_order_items_elements($db, $data['parameters'], $user);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tab not found '.$tab
        );
        echo json_encode($response);
        exit;
        break;
}

function get_delivery_notes_elements($db, $data, $user) {

    $elements_numbers = array(
        'type' => array(
            'Ready'    => 0,
            'Picking'  => 0,
            'Packing'  => 0,
            'Packed'   => 0,
            'Done'     => 0,
            'Sent'     => 0,
            'Returned' => 0,
        ),
    );


    if ($data['parent'] == 'store') {
        if (is_numeric($data['parent_key']) and in_array(
                $data['parent_key'], $user->stores
            )) {
            $where = sprintf(
                ' where  `Delivery Note Store Key`=%d ', $data['parent_key']
            );

        } else {
            $where = sprintf(' where  false');
        }


    } else {
        $where = '';
    }


    $sql  = "select count(*) as number,`Delivery Note State` as element from `Delivery Note Dimension` $where group by `Delivery Note State` ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($data['parent_key'])
    );
    while ($row = $stmt->fetch()) {


        if ($row['element'] == 'Picking' or $row['element'] == 'Picked' or $row['element'] == 'Picker Assigned') {
            $row['element'] = 'Picking';
        }
        if ($row['element'] == 'Ready to be Picked') {
            $row['element'] = 'Ready';
        }

        if ($row['element'] == 'Packing' or $row['element'] == 'Packed' or $row['element'] == 'Packed Done') {
            $row['element'] = 'Packing';
        }

        if ($row['element'] == 'Cancelled' or $row['element'] == 'Cancelled to Restock') {
            $row['element'] = 'Returned';
        }

        if ($row['element'] == 'Dispatched') {
            $row['element'] = 'Sent';
        }

        if ($row['element'] == 'Approved') {
            $row['element'] = 'Done';
        }


        $elements_numbers['type'][$row['element']] += $row['number'];

    }

    foreach ($elements_numbers['type'] as $key => $value) {
        $elements_numbers['type'][$key] = number($value);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);
}

/**
 * @param $db \PDO
 * @param $data
 */
function get_purchase_order_items_elements($db, $data) {

    $elements_numbers = array(
        'type' => array(
            'InProcess'  => 0,
            'Submitted'  => 0,
            'Confirmed'  => 0,
            'InDelivery' => 0,
            'Receiving'  => 0,
            'Received'   => 0,
            'Cancelled'  => 0
        ),
    );

    $sql  = "select count(*) as number,`Purchase Order Transaction State` as element from `Purchase Order Transaction Fact` where `Purchase Order Key`=? group by `Purchase Order Transaction State` ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($data['parent_key'])
    );
    while ($row = $stmt->fetch()) {

        if ($row['element'] == 'ProblemSupplier'  or $row['element'] == 'ReceivedAgent' or $row['element'] == 'Inputted') {
            $row['element'] = 'Submitted';
        }
        if ($row['element'] == 'Dispatched') {
            $row['element'] = 'InDelivery';
        }

        if ($row['element'] == 'Received' or $row['element'] == 'Checked') {
            $row['element'] = 'Receiving';
        }

        if ($row['element'] == 'Placed' or $row['element'] == 'InvoiceChecked') {
            $row['element'] = 'Received';
        }

        if ($row['element'] == 'NoReceived') {
            $row['element'] = 'Cancelled';
        }

        $elements_numbers['type'][$row['element']] = $row['number'];

    }

    foreach ($elements_numbers['type'] as $key => $value) {
        $elements_numbers['type'][$key] = number($value);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);
}


/**
 * @param $db \PDO
 * @param $data
 */
function job_order_items_elements($db, $data) {

    $elements_numbers = array(
        'type' => array(
            'InProcess'  => 0,
            'Submitted'  => 0,
            'Confirmed'  => 0,
            'InDelivery' => 0,
            'Receiving'  => 0,
            'Received'   => 0,
            'Cancelled'  => 0
        ),
    );

    $sql  = "select count(*) as number,`Purchase Order Transaction State` as element from `Purchase Order Transaction Fact` where `Purchase Order Key`=? group by `Purchase Order Transaction State` ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($data['parent_key'])
    );
    while ($row = $stmt->fetch()) {

        if ($row['element'] == 'ProblemSupplier' or $row['element'] == 'ReceivedAgent' or $row['element'] == 'Inputted') {
            $row['element'] = 'Submitted';
        }

        if ($row['element'] == 'Dispatched') {
            $row['element'] = 'InDelivery';
        }

        if ($row['element'] == 'Received' or $row['element'] == 'Checked') {
            $row['element'] = 'Receiving';
        }

        if ($row['element'] == 'Placed' or $row['element'] == 'InvoiceChecked') {
            $row['element'] = 'Received';
        }

        if ($row['element'] == 'NoReceived') {
            $row['element'] = 'Cancelled';
        }

        $elements_numbers['type'][$row['element']] = $row['number'];

    }

    foreach ($elements_numbers['type'] as $key => $value) {
        $elements_numbers['type'][$key] = number($value);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);
}

function parts_barcode_errors($db, $data, $user) {


    $elements_numbers = array(
        'type' => array(
            'Duplicated'       => 0,
            'Size'             => 0,
            'Short_Duplicated' => 0,
            'Checksum_missing' => 0,
            'Checksum'         => 0
        ),


    );


    $sql = sprintf(
        "select count(*) as number,`Part Barcode Number Error` as element from `Part Dimension` where `Part Barcode Number Error` is not null  group by `Part Barcode Number Error` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function parts_weight_errors($db, $data, $user) {


    $elements_numbers = array(
        'type' => array(
            'Missing'     => 0,
            'Underweight' => 0,
            'Overweight'  => 0,
        ),


    );


    $sql = sprintf(
        "select count(*) as number,`Part Package Weight Status` as element from `Part Dimension` where `Part Status`!='Not In Use' and   `Part Package Weight Status`!='OK'  group by `Part Package Weight Status` "
    );
    foreach ($db->query($sql) as $row) {


        if ($row['element'] == 'Underweight Web' or $row['element'] == 'Underweight Cost') {
            $elements_numbers['type']['Underweight'] += $row['number'];

        } elseif ($row['element'] == 'Overweight Web' or $row['element'] == 'Overweight Cost') {
            $elements_numbers['type']['Overweight'] += $row['number'];

        } else {
            $elements_numbers['type'][$row['element']] = $row['number'];

        }

    }

    foreach ($elements_numbers['type'] as $key => $value) {
        $elements_numbers['type'][$key] = number($value);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

function warehouse_leakages_transactions($db, $data, $user) {


    $elements_numbers = array(
        'type' => array(
            'found' => 0,
            'lost'  => 0,
        ),

    );


    $timeseries_record = get_object('timeseries_record', $data['parent_key']);
    //print_r($timeseries_record);

    $_tmp      = json_decode($timeseries_record->get('Timeseries Record Metadata'), true);
    $from_date = $_tmp['f'];
    $to_date   = $_tmp['t'];

    $where = sprintf(
        " where `Inventory Transaction Type` = 'Adjust' and `Inventory Transaction Section`='Audit'  AND `Warehouse Key`=%d %s %s  ", $timeseries_record->get('Timeseries Parent Key'), ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''),
        ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
    );


    $sql = sprintf(
        "select count(*) as number from `Inventory Transaction Fact` %s    and `Inventory Transaction Quantity`<0  ", $where
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type']['lost'] = number($row['number']);

    }

    $sql = sprintf(
        "select count(*) as number from `Inventory Transaction Fact` %s    and `Inventory Transaction Quantity`>0  ", $where
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type']['found'] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_deals_element_numbers($db, $data, $user) {


    $elements_numbers = array(
        'status'  => array(
            'Active'    => 0,
            'Waiting'   => 0,
            'Suspended' => 0,
            'Finish'    => 0
        ),
        'trigger' => array(
            'Order'             => 0,
            'Product_Category'  => 0,
            'Product'           => 0,
            'Customer'          => 0,
            'Customer_Category' => 0,
            'Customer_List'     => 0
        ),

    );


    switch ($data['parent']) {
        case 'store':
            $where = sprintf(
                ' where `Deal Store Key`=%d ', $data['parent_key']
            );
            break;
        case 'campaign':
            $where = sprintf(
                ' where `Deal Campaign Key`=%d  ', $data['parent_key']
            );
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'customer parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Deal Status` as element from `Deal Dimension` D $where  group by `Deal Status` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }

    $sql = sprintf(
        "select count(*) as number,`Deal Trigger` as element from `Deal Dimension` D $where  group by `Deal Trigger` "
    );

    foreach ($db->query($sql) as $row) {

        $elements_numbers['trigger'][preg_replace('/\s/', '_', $row['element'])] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_fixed_deals_element_numbers($db, $data, $user) {


    $elements_numbers = array(
        'status' => array(
            'Active' => 0,

            'Suspended' => 0,
        ),


    );


    switch ($data['parent']) {
        case 'store':
            $where = sprintf(
                ' where `Deal Store Key`=%d and D.`Deal Campaign Key` is NULL ', $data['parent_key']
            );
            break;
        case 'campaign':
            $where = sprintf(
                ' where `Deal Campaign Key`=%d  ', $data['parent_key']
            );
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'customer parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Deal Status` as element from `Deal Dimension` D $where  group by `Deal Status` "
    );
    foreach ($db->query($sql) as $row) {

        if ($row['element'] != 'Active') {
            $row['element'] = 'Suspended';
        }

        $elements_numbers['status'][$row['element']] += $row['number'];

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_campaigns_element_numbers($db, $data, $user) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'status' => array(
            'Active'    => 0,
            'Waiting'   => 0,
            'Suspended' => 0,
            'Finish'    => 0
        ),

    );


    switch ($data['parent']) {
        case 'store':
            $where = sprintf(
                ' where `Deal Campaign Store Key`=%d  ', $data['parent_key']
            );
            break;

        default:
            $response = array(
                'state' => 405,
                'resp'  => 'customer parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Deal Campaign Status` as element from `Deal Campaign Dimension` D $where  group by `Deal Campaign Status` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }

    $sql = sprintf(
        "select count(*) as number,`Deal Trigger` as element from `Deal Dimension` D $where  group by `Deal Trigger` "
    );

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_stock_transactions_elements($db, $data, $user) {


    $elements_numbers = array(
        'stock_status' => array(
            'In'           => 0,
            'Out'          => 0,
            'Lost'         => 0,
            'Move'         => 0,
            'Audit'        => 0,
            'NoDispatched' => 0,
            'OIP'          => 0
        ),

    );


    $table = '`Inventory Transaction Fact`  ITF  ';
    switch ($data['parent']) {
        case 'part':
            $where = sprintf(
                "where `Part SKU`=%d", $data['parent_key']
            );
            break;
        case 'account':
            $where = sprintf(
                "where  true "
            );
            break;
        case 'location':
            $where = sprintf(
                "where  `Location Key`=%d", $data['parent_key']
            );
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Inventory Transaction Section` as element from $table $where  group by `Inventory Transaction Section` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['stock_status'][preg_replace('/\s/', '', $row['element'])] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_parts_elements($db, $data, $user) {


    $parent_key       = $data['parent_key'];
    $elements_numbers = array(
        'stock_status' => array(
            'Surplus'      => 0,
            'Optimal'      => 0,
            'Low'          => 0,
            'Critical'     => 0,
            'Out_Of_Stock' => 0,
            'Error'        => 0
        ),

    );


    $table = '`Part Dimension`  P  ';
    switch ($data['parent']) {
        case 'account':
            $where = "where `Part Status`='In Use'";
            break;
        case 'category':
            $where = sprintf(
                " where `Subject`='Part' and  `Category Key`=%d  and `Part Status`='In Use' ", $data['parent_key']
            );
            $table = ' `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`) ';
            break;
        case 'material':
            $where = sprintf(" where `Material Key`=%d", $data['parent_key']);
            $table = ' `Part Material Bridge` B left join  `Part Dimension` P on (B.`Part SKU`=P.`Part SKU`) ';
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'part parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Part Stock Status` as element from $table $where  group by `Part Stock Status` "
    );

    foreach ($db->query($sql) as $row) {

        $elements_numbers['stock_status'][preg_replace(
            '/\s/', '', $row['element']
        )] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_supplier_parts_elements($db, $data, $user) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'status'      => array(
            'Available'    => 0,
            'NoAvailable'  => 0,
            'Discontinued' => 0
        ),
        'part_status' => array(
            'InUse'    => 0,
            'NotInUse' => 0
        ),

    );


    $table = '`Supplier Part Dimension`  SP left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) ';
    switch ($data['parent']) {
        case 'supplier':
        case 'supplier_production':

            $where = sprintf(
                ' where `Supplier Part Supplier Key`=%d  ', $data['parent_key']
            );
            break;
        case 'agent':

            $where = sprintf(
                " where  `Agent Supplier Agent Key`=%d", $data['parent_key']
            );
            $table .= ' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';

            break;
        case 'purchase_order':

            $purchase_order = get_object('PurchaseOrder', $data['parent_key']);

            if ($purchase_order->get('Purchase Order Parent') == 'Supplier') {

                $where = sprintf(
                    " where  `Supplier Part Supplier Key`=%d", $purchase_order->get('Purchase Order Parent Key')
                );


            } else {


                $where = sprintf(
                    "  where  `Agent Supplier Agent Key`=%d", $purchase_order->get('Purchase Order Parent Key')
                );
                $table .= ' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';


            }

            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Part Status` as element from $table $where  group by `Part Status` "
    );


    foreach ($db->query($sql) as $row) {

        $elements_numbers['part_status'][preg_replace(
            '/\s/', '', $row['element']
        )] = number($row['number']);

    }

    $sql = sprintf(
        "select count(*) as number,`Supplier Part Status` as element from $table $where  group by `Supplier Part Status` "
    );

    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_warehouse_locations_elements($db, $data, $user) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'flags' => array(
            'None'   => 0,
            'Blue'   => 0,
            'Green'  => 0,
            'Orange' => 0,
            'Pink'   => 0,
            'Purple' => 0,
            'Red'    => 0,
            'Yellow' => 0
        ),

    );


    $table = '`Location Dimension` left join `Warehouse Flag Dimension` on (`Warehouse Flag Key`=`Location Warehouse Flag Key`)     where   `Location Type`!="Unknown" ';

    switch ($data['parent']) {
        case 'warehouse':
            $where = sprintf(' and `Location Warehouse Key`=%d  ', $data['parent_key']);
            break;
        case 'warehouse_area':
            $where = sprintf(' and `Location Warehouse Area Key`=%d  ', $data['parent_key']);
            break;
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf("select count(*) as number,`Warehouse Flag Color` as element from $table $where  group by `Location Warehouse Flag Key` ");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['element'] != '') {
                $elements_numbers['flags'][preg_replace('/\s/', '', $row['element'])] = number($row['number']);
            } else {
                $elements_numbers['flags']['None'] = number($row['number']);

            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_products_element_numbers($db, $data, $user) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'status' => array(
            'InProcess'     => 0,
            'Active'        => 0,
            'Suspended'     => 0,
            'Discontinued'  => 0,
            'Discontinuing' => 0
        ),

    );


    $table = '`Product Dimension`  P';
    switch ($data['parent']) {
        case 'store':
            $where = sprintf(
                " where `Product Type`='Product' and `Product Store Key`=%d  ", $data['parent_key']
            );
            break;
        case 'part':
            $table = '`Product Dimension`  P left join `Product Part Bridge` B on (B.`Product Part Product ID`=P.`Product ID`)';

            $where = sprintf(
                " where `Product Type`='Product' and `Product Part Part SKU`=%d  ", $data['parent_key']
            );
            break;
        case 'account':
            $where = sprintf(
                " where `Product Type`='Product' and `Product Store Key` in (%s) ", join(',', $user->stores)
            );

            break;
        case 'category':


            $where = sprintf(" where `Product Type`='Product' and `Subject`='Product' and  `Category Key`=%d", $data['parent_key']);
            $table = ' `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) ';


            break;


        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf("select count(*) as number,`Product Status` as element from $table $where  group by `Product Status` ");


    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_services_element_numbers($db, $data, $user) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'status' => array(
            'Active'       => 0,
            'Suspended'    => 0,
            'Discontinued' => 0
        ),

    );


    $table = '`Product Dimension`  P';
    switch ($data['parent']) {
        case 'store':
            $where = sprintf(
                " where `Product Type`='Service' and `Product Store Key`=%d  ", $data['parent_key']
            );
            break;
        case 'account':
            $where = sprintf(
                " where `Product Type`='Service' and `Product Store Key` in (%s) ", join(',', $user->stores)
            );

            break;

        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Product Status` as element from $table $where  group by `Product Status` "
    );

    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_product_categories_element_numbers($db, $data, $user) {


    $elements_numbers = array(
        'status' => array(
            'InProcess'     => 0,
            'Active'        => 0,
            'Suspended'     => 0,
            'Discontinued'  => 0,
            'Discontinuing' => 0
        ),

    );


    $table = '`Category Bridge` B left join `Product Category Dimension` PC on (B.`Subject Key`=`Product Category Key`)';
    $where = sprintf('where `Category Key`=%d', $data['parent_key']);


    $sql = sprintf("select count(*) as number,`Product Category Status` as element from $table $where  group by `Product Category Status` ");


    foreach ($db->query($sql) as $row) {
        if ($row['element'] == 'In Process') {
            $row['element'] = 'InProcess';
        }
        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_product_categories_element_numbers_bis($db, $data, $user) {


    $elements_numbers = array(
        'status' => array(
            'InProcess'     => 0,
            'Active'        => 0,
            'Suspended'     => 0,
            'Discontinued'  => 0,
            'Discontinuing' => 0
        ),

    );


    $table = '`Category Dimension` C left join `Product Category Dimension` PC on (C.`Category Key`=`Product Category Key`)';
    $where = sprintf('where `Category Parent Key`=%d', $data['parent_key']);


    $sql = sprintf("select count(*) as number,`Product Category Status` as element from $table $where  group by `Product Category Status` ");


    foreach ($db->query($sql) as $row) {
        if ($row['element'] == 'In Process') {
            $row['element'] = 'InProcess';
        }
        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_customers_element_numbers($db, $data) {

    global $user;


    $elements_numbers = array(
        'orders'   => array(
            'Yes' => 0,
            'No'  => 0
        ),
        'activity' => array(
            'Rejected'  => 0,
            'ToApprove' => 0,
            'Active'    => 0,
            'Losing'    => 0,
            'Lost'      => 0
        ),
        'type'     => array(
            'Normal'  => 0,
            'VIP'     => 0,
            'Partner' => 0,
            'Staff'   => 0
        ),
        'location' => array(
            'Domestic' => 0,
            'Export'   => 0
        )
    );

    $table = '`Customer Dimension`  C';

    switch ($data['parent']) {
        case 'store':
            $where = sprintf(' where `Customer Store Key`=%d  ', $data['parent_key']);
            break;
        case 'sales_representative':
            $where = sprintf(' where `Customer Sales Representative Key`=%d  ', $data['parent_key']);
            break;
        case 'category':
            $tab = 'customer.categories';
            break;
        case 'list':
            $tab = 'customers.list';
            break;
        case 'campaign':
            $table = '`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';
            $where = sprintf(
                ' where `Deal Campaign Key`=%d', $data['parent_key']
            );
            break;
        case 'deal':
            $table = '`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';
            $where = sprintf(' where `Deal Key`=%d', $data['parent_key']);
            break;

        case 'product':
            $table = '`Order Transaction Fact` OTF  left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) ';
            $where = sprintf(' where  `Product ID`=%d ', $data['parent_key']);
            break;
        case 'customer_poll_query_option':
            $table = '`Customer Poll Fact` CPF  left join `Customer Dimension` C on (CPF.`Customer Poll Customer Key`=C.`Customer Key`) ';
            $where = sprintf(' where  `Customer Poll Query Option Key`=%d ', $data['parent_key']);


            break;
        case 'favourites':
            $table = '`Customer Favourite Product Fact` F  left join `Customer Dimension` C   on (C.`Customer Key`=F.`Customer Favourite Product Customer Key`)  ';
            $where = sprintf(' where  F.`Customer Favourite Product Product ID`=%d ', $data['parent_key']);

            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'customer parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(Distinct C.`Customer Key`) as number,`Customer With Orders` as element from $table $where  group by `Customer With Orders` "
    );


    foreach ($db->query($sql) as $row) {

        $elements_numbers['orders'][$row['element']] = number($row['number']);

    }


    $sql = sprintf(
        "select count(Distinct C.`Customer Key`) as number,`Customer Type by Activity` as element from $table $where group by `Customer Type by Activity` "
    );

    foreach ($db->query($sql) as $row) {

        $elements_numbers['activity'][$row['element']] = number($row['number']);

    }

    $sql = sprintf(
        "select count(Distinct C.`Customer Key`) as number,`Customer Level Type` as element from $table $where group by `Customer Level Type` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type'][$row['element']] = number($row['number']);

    }


    $sql = sprintf(
        "select count(Distinct C.`Customer Key`) as number,`Customer Location Type` as element from $table $where group by `Customer Location Type` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['location'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_suppliers_element_numbers($db, $data) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'type' => array(
            'Free'     => 0,
            'Agent'    => 0,
            'Archived' => 0
        ),
    );

    $table = '`Supplier Dimension` S';

    switch ($data['parent']) {
        case 'account':
            $where = sprintf(' where `Supplier Production`="No"  ');
            break;
        case 'agent':
            $where = sprintf(
                " where `Supplier Production`='No' and `Agent Supplier Agent Key`=%d", $parent_key
            );
            $table = ' `Agent Supplier Bridge` B left join  `Supplier Dimension` S on (`Agent Supplier Supplier Key`=`Supplier Key`) ';

            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Supplier Type` as element from $table $where group by `Supplier Type` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_history_elements($db, $data) {


    $elements_numbers = array(
        'type' => array(
            'Changes'     => 0,
            'Assign'      => 0,
            'Notes'       => 0,
            'Orders'      => 0,
            'Changes'     => 0,
            'Attachments' => 0,
            'WebLog'      => 0,
            'Emails'      => 0,
            'Deployment'  => 0,
            'Calls'       => 0,
            'Posts'       => 0
        )
    );

    // print_r($data);

    if ($data['parent'] == 'category') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `%s Category History Bridge` WHERE  `Category Key`=%d GROUP BY  `Type`", $data['subject'], $data['parent_key']
        );
    } elseif ($data['parent'] == 'warehouse') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `%s Category History Bridge` WHERE  `Warehouse Key`=%d GROUP BY  `Type`", $data['subject'], $data['parent_key']
        );
    } elseif ($data['parent'] == 'customer') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Customer History Bridge` WHERE  `Customer Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'location') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Location History Bridge` WHERE  `Location Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'supplier_part') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Supplier Part History Bridge` WHERE  `Supplier Part Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'agent') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Agent History Bridge` WHERE  `Agent Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'store') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `%s Category History Bridge` WHERE  `Store Key`=%d GROUP BY  `Type`", $data['subject'], $data['parent_key']
        );
    } elseif ($data['parent'] == 'supplier') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Supplier History Bridge` WHERE  `Supplier Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'deal') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Deal History Bridge` WHERE  `Deal Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'campaign') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Deal Campaign History Bridge` WHERE  `Deal Campaign Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'purchase_order') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Purchase Order History Bridge` WHERE  `Purchase Order Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    }  elseif ($data['parent'] == 'supplierdelivery') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Supplier Delivery History Bridge` WHERE  `Supplier Delivery Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'webpage_logbook') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Webpage Publishing History Bridge` WHERE  `Webpage Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'charge') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Charge History Bridge` WHERE  `Charge Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'mailshot') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Email Campaign History Bridge` WHERE  `Email Campaign Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'Customer_Poll_Query_Option') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Customer Poll Query Option History Bridge` WHERE  `Customer Poll Query Option Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'Customer_Poll_Query') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Customer Poll Query History Bridge` WHERE  `Customer Poll Query Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'none') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `%s Category History Bridge`  GROUP BY  `Type`", $data['subject']
        );
    } elseif ($data['parent'] == 'prospect') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Prospect History Bridge` WHERE  `Prospect Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } elseif ($data['parent'] == 'purge') {
        $sql = sprintf(
            "SELECT count(*) AS num ,`Type` FROM  `Order Basket Purge History Bridge` WHERE  `Order Basket Purge Key`=%d GROUP BY  `Type`", $data['parent_key']
        );
    } else {
        $response = array(
            'state' => 405,
            'resp'  => 'parent not found: '.$data['parent']
        );
        echo json_encode($response);

        return;
    }


    foreach ($db->query($sql) as $row) {
        $elements_numbers['type'][$row['Type']] = number($row['num']);
    }

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );


    echo json_encode($response);


}


function get_orders_element_numbers($db, $data, $user) {


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $parent_key = $data['parent_key'];

    $count = ' count(*)';


    switch ($data['parent']) {
        case 'account':
            $table = '`Order Dimension` O';
            $where = sprintf('where  true');

            $object = get_object('account', 1);

            break;
        case 'store':
            $table = '`Order Dimension` O';
            $where = sprintf('where  `Order Store Key`=%d', $parent_key);

            $object = get_object('store', $parent_key);

            break;
        case 'customer':
            $table = '`Order Dimension` O';
            $where = sprintf('where  `Order Customer Key`=%d', $parent_key);

            $object = get_object('store', $parent_key);

            break;
        case 'campaign':
            $table = '`Order Dimension` O left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) ';
            $where = sprintf('where  `Deal Campaign Key`=%d', $parent_key);
            break;
        case 'deal':
            $table = '`Order Dimension` O left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) ';
            $where = sprintf('where  `Deal Key`=%d', $parent_key);
            break;
        case 'product':
            $table = '`Order Transaction Fact` OTF  left join     `Order Dimension` O   on (OTF.`Order Key`=O.`Order Key`)   ';

            $where = sprintf(' where  `Product ID`=%d ', $parent_key);
            $count = ' count(Distinct O.`Order Key`)';

            break;
        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, 'O.`Order Date`');
    $where_interval = $where_interval['mysql'];

    $elements_numbers = array(
        'state'   => array(
            'InBasket'    => 0,
            'InProcess'   => 0,
            'InWarehouse' => 0,
            'PackedDone'  => 0,
            'Approved'    => 0,
            'Dispatched'  => 0,
            'Cancelled'   => 0,
        ),
        'source'  => array(
            'Internet' => 0,
            'Call'     => 0,
            'Store'    => 0,
            'Other'    => 0,
            'Email'    => 0,
            'Fax'      => 0
        ),
        'payment' => array(
            'Paid'           => 0,
            'PartiallyPaid'  => 0,
            'Unknown'        => 0,
            'WaitingPayment' => 0,
            'NA'             => 0
        ),
        'type'    => array(
            'Order'    => 0,
            'Sample'   => 0,
            'Donation' => 0,
            'Other'    => 0
        ),

    );
    /*
        if ($data['parent'] == 'account' or $data['parent'] == 'store') {

            $object->load_acc_data();

            $elements_numbers['state']['InBasket']    = $object->get('Orders In Basket Number');
            $elements_numbers['state']['InProcess']   = $object->get('Orders In Process Number');
            $elements_numbers['state']['InWarehouse'] = $object->get('Orders In Warehouse Number');

            $elements_numbers['state']['PackedDone'] = $object->get('Orders Packed Number');
            $elements_numbers['state']['Approved']   = $object->get('Orders Dispatch Approved Number');
            $elements_numbers['state']['Dispatched'] = $object->get('Orders Dispatched Number');

            $elements_numbers['state']['Cancelled'] = $object->get('Orders Cancelled Number');


        } else {
    */
    $sql = sprintf(
        "SELECT %s AS number,`Order State` AS element FROM %s %s %s GROUP BY `Order State` ", $count, $table, $where, $where_interval
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['state'][$row['element']] = number($row['number']);
    }

    //    }


    $sql = sprintf(
        "SELECT %s AS number,`Order Type` AS element FROM %s %s %s GROUP BY `Order Type` ", $count, $table, $where, $where_interval
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type'][$row['element']] = number($row['number']);
    }
    //USE INDEX (`Current Dispatch State Store Key`)

    // USE INDEX (`Current Payment State Store Key`)
    $sql = sprintf(
        "SELECT %s AS number,`Order Payment State` AS element FROM %s  %s %s GROUP BY `Order Current Payment State` ", $count, $table, $where, $where_interval
    );


    foreach ($db->query($sql) as $row) {
        $_element = $row['element'];

        $elements_numbers['payment'][$_element] = number($row['number']);
    }

    //print_r($elements_numbers);


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_delivery_notes_element_numbers($db, $data, $user) {

    if (!$user->can_view('orders')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $parent_key = $data['parent_key'];

    $count = ' count(*)';

    switch ($data['parent']) {
        case 'account':
            $table = '`Delivery Note Dimension` DN';
            $where = sprintf('where  true');


            break;

        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, 'O.`Delivery Note Date`');
    $where_interval = $where_interval['mysql'];

    $elements_numbers = array(
        'state' => array(
            'Ready'    => 0,
            'Picking'  => 0,
            'Packed'   => 0,
            'Done'     => 0,
            'Send'     => 0,
            'Returned' => 0
        ),
        'type'  => array(
            'Order'        => 0,
            'Replacements' => 0
        ),


    );

    $sql = sprintf(
        "SELECT %s AS number,`Delivery Note State` AS element FROM %s %s %s GROUP BY `Delivery Note State` ", $count, $table, $where, $where_interval
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['element'] == 'Ready to be Picked' or $row['element'] == 'Picker Assigned') {
                $row['element'] = 'Ready';
            }
            if ($row['element'] == 'Picking' or $row['element'] == 'Picked' or $row['element'] == 'Packing') {
                $row['element'] = 'Picking';
            }

            if ($row['element'] == 'Approved' or $row['element'] == 'Packed Done') {
                $row['element'] = 'Done';
            }
            if ($row['element'] == 'Dispatched') {
                $row['element'] = 'Send';
            }
            if ($row['element'] == 'Cancelled' or $row['element'] == 'Cancelled to Restock') {
                $row['element'] = 'Returned';
            }

            $elements_numbers['state'][$row['element']] += $row['number'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    //'Replacement & Shortages','Order','Replacement','Shortages','Sample','Donation'
    $sql = sprintf(
        "SELECT %s AS number,`Delivery Note Type` AS element FROM %s %s %s GROUP BY `Delivery Note Type` ", $count, $table, $where, $where_interval
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['element'] == 'Replacement & Shortages' or $row['element'] == 'Replacement' or $row['element'] == 'Shortages') {
                $row['element'] = 'Replacements';
            }

            if ($row['element'] == 'Order' or $row['element'] == 'Sample' or $row['element'] == 'Donation') {
                $row['element'] = 'Order';
            }

            $elements_numbers['type'][$row['element']] += $row['number'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    //print_r($elements_numbers);


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_invoices_element_numbers($db, $parameters) {

    global $user;


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );


    $parent_key = $parameters['parent_key'];


    $where_interval = prepare_mysql_dates($from, $to, '`Invoice Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'type'          => array(
            'Invoice' => 0,
            'Refund'  => 0
        ),
        'payment_state' => array(
            'Yes'       => 0,
            'No'        => 0,
            'Partially' => 0
        ),
    );
    $table            = '`Invoice Dimension` I left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)  ';


    if (isset($parameters['awhere']) and $parameters['awhere']) {

        include_once 'invoices_awhere.php';

        $tmp = preg_replace('/\\\"/', '"', $parameters['awhere']);
        $tmp = preg_replace('/\\\\\"/', '"', $tmp);
        $tmp = preg_replace('/\'/', "\'", $tmp);

        $raw_data = json_decode($tmp, true);
        //$raw_data['store_key']=$store;
        //print_r( $raw_data);exit;
        list($where, $table) = invoices_awhere($raw_data);


    } elseif ($parameters['parent'] == 'category') {
        $category = get_object('Category', $parameters['parent_key']);


        $where      = sprintf(
            " where `Subject`='Invoice' and  `Category Key`=%d", $parameters['parent_key']
        );
        $table      = ' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';
        $where_type = '';

        $store_key = $category->data['Category Store Key'];

    } elseif ($parameters['parent'] == 'store') {
        if (is_numeric($parameters['parent_key']) and in_array(
                $parameters['parent_key'], $user->stores
            )) {
            $where = sprintf(
                ' where  `Invoice Store Key`=%d ', $parameters['parent_key']
            );
            $store = get_object('Store', $parameters['parent_key']);


            $currency = $store->data['Store Currency Code'];
        } else {
            $where = sprintf(' and  false');
        }


    } elseif ($parameters['parent'] == 'account') {


        if (count($user->stores) == 0) {
            $where = ' where false';
        } else {

            $where = sprintf(
                'where  `Invoice Store Key` in (%s)  ', join(',', $user->stores)
            );

        }


    } elseif ($parameters['parent'] == 'order') {

        $table = '`Order Invoice Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
        $where = sprintf(
            'where  B.`Order Key`=%d  ', $parameters['parent_key']
        );

    } elseif ($parameters['parent'] == 'delivery_note') {

        $table = '`Invoice Delivery Note Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
        $where = sprintf(
            'where  B.`Delivery Note Key`=%d  ', $parameters['parent_key']
        );

    } elseif ($parameters['parent'] == 'customer') {
        $table = '`Invoice Dimension` I  ';

        $where = sprintf(
            'where `Invoice Customer Key`=%d  ', $parameters['parent_key']
        );

    } elseif ($parameters['parent'] == 'sales_representative') {
        $table = '`Invoice Dimension` I  ';

        $where = sprintf(
            'where `Invoice Sales Representative Key`=%d  ', $parameters['parent_key']
        );

    } elseif ($parameters['parent'] == 'billingregion_taxcategory.invoices') {

        $fields = '`Store Code`,`Store Name`,`Country Name`,';
        $table  = '`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Address Country 2 Alpha Code`=C.`Country 2 Alpha Code`) ';

        $parents = preg_split('/_/', $parameters['parent_key']);
        $where   = sprintf(
            'where  `Invoice Type`="Invoice" and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ', prepare_mysql($parents[0]), prepare_mysql($parents[1])
        );


    } elseif ($parameters['parent'] == 'billingregion_taxcategory.refunds') {

        $table = '`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Address Country 2 Alpha Code`=C.`Country 2 Alpha Code`) ';

        $parents = preg_split('/_/', $parameters['parent_key']);
        $where   = sprintf(
            'where  `Invoice Type`!="Invoice"  and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ', prepare_mysql($parents[0]), prepare_mysql($parents[1])
        );


    } else {
        exit("unknown parent ".$parameters['parent']." \n");
    }


    $sql = sprintf(
        "SELECT count(*) AS number,`Invoice Paid` AS element FROM %s %s %s GROUP BY `Invoice Paid` ", $table, $where, $where_interval
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $elements_numbers['payment_state'][$row['element']] = number(
                $row['number']
            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $sql = sprintf(
        "SELECT count(*) AS number,`Invoice Type` AS element   FROM %s %s %s GROUP BY `Invoice Type` ", $table, $where, $where_interval
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type'][$row['element']] = number($row['number']);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_delivery_note_element_numbers($db, $data) {

    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $data['period'], $data['from'], $data['to']
    );


    $parent_key = $data['parent_key'];


    $where_interval = prepare_mysql_dates($from, $to, '`Order Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'dispatch' => array(
            'Ready'    => 0,
            'Picking'  => 0,
            'Packing'  => 0,
            'Done'     => 0,
            'Send'     => 0,
            'Returned' => 0
        ),
        'type'     => array(
            'Order'        => 0,
            'Sample'       => 0,
            'Donation'     => 0,
            'Replacements' => 0,
            'Shortages'    => 0
        )
    );

    $sql = sprintf(
        "SELECT count(*) AS number,`Delivery Note Type` AS element FROM %s %s GROUP BY `Delivery Note Type` ", $table, $where

    );
    //print $sql;
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {

        if ($row['element'] == 'Replacement & Shortages') {
            $_element = 'Replacements';
        } elseif ($row['element'] == 'Replacement') {
            $_element = 'Replacements';
        } else {
            $_element = $row['element'];
        }
        if ($_element != '') {
            $elements_numbers['type'][$_element] += $row['number'];
        }
    }

    foreach ($elements_numbers['type'] as $key => $value) {
        $elements_numbers['type'][$key] = number($value);
    }


    $sql = sprintf(
        "SELECT count(*) AS number,`Delivery Note State` AS element  FROM %s %s GROUP BY `Delivery Note State` ", $table, $where
    );
    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {

        if ($row['element'] == 'Ready to be Picked') {
            $_element = 'Ready';
        } elseif ($row['element'] == 'Picking' or $row['element'] == 'Picked' or $row['element'] == 'Picker Assigned') {
            $_element = 'Picking';
        } elseif ($row['element'] == 'Packing' or $row['element'] == 'Packed' or $row['element'] == 'Packed Done') {
            $_element = 'Packing';
        } elseif ($row['element'] == 'Approved') {
            $_element = 'Done';
        } elseif ($row['element'] == 'Dispatched') {
            $_element = 'Send';
        } elseif ($row['element'] == 'Cancelled' or $row['element'] == 'Cancelled to Restock') {
            $_element = 'Returned';
        } else {
            continue;
        }

        $elements_numbers['dispatch'][$_element] += $row['number'];
    }

    foreach ($elements_numbers['dispatch'] as $key => $value) {
        $elements_numbers['dispatch'][$key] = number($value);
    }

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_barcodes_elements($db, $data, $user) {


    $elements_numbers = array(
        'status' => array(
            'Available' => 0,
            'Used'      => 0,
            'Reserved'  => 0
        ),

    );


    $table = '`Barcode Dimension`  B';
    switch ($data['parent']) {
        case 'account':
            $where = '';
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Barcode Status` as element from $table $where  group by `Barcode Status` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_supplier_orders_elements($db, $data) {


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $parent_key = $data['parent_key'];


    switch ($data['parent']) {
        case 'supplier':
            $table = '`Purchase Order Dimension` O';
            $where = sprintf(
                'where  `Purchase Order Parent`="Supplier" and `Purchase Order Parent Key`=%d', $parent_key
            );
            break;
        case 'agent':
            $table = '`Purchase Order Dimension` O';
            $where = sprintf(
                'where  `Purchase Order Parent`="Agent" and `Purchase Order Parent Key`=%d', $parent_key
            );


            break;
        case 'account':
            $table = '`Purchase Order Dimension` O left join `Supplier Dimension` on (`Supplier Key`=`Purchase Order Parent Key`)   ';
            $where = sprintf('where (( `Purchase Order Parent`="Supplier" and `Supplier Production`="No" ) or  `Purchase Order Parent`="Agent") ');
            break;
        case 'production':
            $table = '`Purchase Order Dimension` O left join `Supplier Dimension` on (`Supplier Key`=`Purchase Order Parent Key`)  ';
            $where = sprintf('where `Purchase Order Parent`="Supplier" and  `Supplier Production`="Yes"');
            break;
        case 'production_supplier':
            $table = '`Purchase Order Dimension` O left join `Supplier Dimension` on (`Supplier Key`=`Purchase Order Parent Key`)  ';
            $where = sprintf(
                'where  `Purchase Order Parent`="Supplier" and `Purchase Order Parent Key`=%d and  `Supplier Production`="Yes"', $parent_key
            );
            break;
        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, '`Purchase Order Creation Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'state' => array(

            'InProcess'       => 0,
            'Submitted'       => 0,
            'InTransit'       => 0,
            'ReceivedChecked' => 0,
            'Placed'          => 0,
            'InvoiceChecked'  => 0,
            'Cancelled'       => 0
        ),
    );


    //USE INDEX (`Main Source Type Store Key`)
    $sql = sprintf(
        "SELECT count(*) AS number,`Purchase Order State` AS element FROM %s %s %s GROUP BY `Purchase Order State` ", $table, $where, $where_interval
    );

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            //'InProcess','Submitted','Inputted','Dispatched','Received','Checked','Placed', 'Costing','InvoiceChecked' ,'Cancelled'
            if ($row['element'] == 'InProcess') {
                $element = 'InProcess';
            } elseif ($row['element'] == 'Submitted' or $row['element'] == 'Inputted') {
                $element = 'Submitted';
            } elseif ($row['element'] == 'Dispatched') {
                $element = 'InTransit';
            } elseif ($row['element'] == 'Received' or $row['element'] == 'Checked') {
                $element = 'ReceivedChecked';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing') {
                $element = 'Placed';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }
        }
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_production_orders_elements($db, $data) {


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $parent_key = $data['parent_key'];


    switch ($data['parent']) {

        case 'production':
            $table = '`Purchase Order Dimension` O left join `Supplier Dimension` on (`Supplier Key`=`Purchase Order Parent Key`)  ';
            $where = sprintf('where `Purchase Order Parent`="Supplier" and  `Supplier Production`="Yes"');
            break;
        case 'production_supplier':
            $table = '`Purchase Order Dimension` O left join `Supplier Dimension` on (`Supplier Key`=`Purchase Order Parent Key`)  ';
            $where = sprintf(
                'where  `Purchase Order Parent`="Supplier" and `Purchase Order Parent Key`=%d and  `Supplier Production`="Yes"', $parent_key
            );
            break;
        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, '`Purchase Order Creation Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'state' => array(

            'InProcess'       => 0,
            'Manufacturing'   => 0,
            'ReceivedChecked' => 0,
            'Placed'          => 0,
            'Cancelled'       => 0
        ),
    );


    //USE INDEX (`Main Source Type Store Key`)
    $sql = sprintf(
        "SELECT count(*) AS number,`Purchase Order State` AS element FROM %s %s %s GROUP BY `Purchase Order State` ", $table, $where, $where_interval
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['element'] == 'InProcess') {
                $element = 'InProcess';
            } elseif ($row['element'] == 'Submitted') {
                $element = 'Manufacturing';
            } elseif ($row['element'] == 'Received' or $row['element'] == 'Checked' or $row['element'] == 'Inputted' or $row['element'] == 'Dispatched') {
                $element = 'ReceivedChecked';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing' or $row['element'] == 'InvoiceChecked') {
                $element = 'Placed';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }
        }
    } else {
        print "$sql";
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

function get_production_orders_with_part_elements($db, $data) {


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $table = ' `Purchase Order Transaction Fact` POTF  left join  `Purchase Order Dimension` O on (POTF.`Purchase Order Key`=O.`Purchase Order Key`)
	left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
    left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)';

    switch ($data['parent']) {

        case 'production_part':
            $where = sprintf(
                'where POTF.`Supplier Part Key`=%d  ', $data['parent_key']
            );
            break;

            break;
        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, '`Purchase Order Creation Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'state' => array(

            'InProcess'       => 0,
            'Manufacturing'   => 0,
            'ReceivedChecked' => 0,
            'Placed'          => 0,
            'Cancelled'       => 0
        ),
    );


    //USE INDEX (`Main Source Type Store Key`)
    $sql = sprintf(
        "SELECT count(*) AS number,`Purchase Order Transaction State` AS element FROM %s %s %s GROUP BY `Purchase Order Transaction State` ", $table, $where, $where_interval
    );


    //'','','','Confirmed','ReceivedAgent','InDelivery','Inputted','Dispatched','Received','Checked','Placed','Cancelled'

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['element'] == 'InProcess') {
                $element = 'InProcess';
            } elseif ($row['element'] == 'Submitted') {
                $element = 'Manufacturing';
            } elseif ($row['element'] == 'InDelivery' or $row['element'] == 'Inputted' or $row['element'] == 'Dispatched' or $row['element'] == 'Received' or $row['element'] == 'Checked') {
                $element = 'ReceivedChecked';
            } elseif ($row['element'] == 'Placed') {
                $element = 'Placed';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }
        }
    } else {
        print "$sql";
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

function get_agent_orders_elements($db, $data) {


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $parent_key = $data['parent_key'];


    switch ($data['parent']) {
        case 'supplier':

        case 'agent':
            $table = '`Purchase Order Dimension` O';
            $where = sprintf('where  `Purchase Order Parent`="Agent" and `Purchase Order Parent Key`=%d', $parent_key);
            break;

        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, '`Order Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'state' => array(
            'InProcess'       => 0,
            'Submitted'       => 0,
            'InTransit'       => 0,
            'ReceivedChecked' => 0,
            'Placed'          => 0,
            'Cancelled'       => 0
        ),
    );


    //USE INDEX (`Main Source Type Store Key`)
    $sql = sprintf(
        "SELECT count(*) AS number,`Purchase Order State` AS element FROM %s GROUP BY `Purchase Order State` ", $table.' '.$where.' '.$where_interval
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['element'] == 'Submitted') {
                $element = 'Submitted';
            } elseif ($row['element'] == 'Dispatched') {
                $element = 'InTransit';
            } elseif ($row['element'] == 'Received' or $row['element'] == 'Checked') {
                $element = 'ReceivedChecked';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }
        }
    } else {
        print "$sql";
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

function get_agent_client_orders_elements($db, $data, $user) {


    if ($user->get('User Type') != 'Agent') {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }
    // $_data['parameters']['parent']     = 'agent';
    $agent_key = $user->get('User Parent Key');


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb) = calculate_interval_dates($db, $data['period'], $data['from'], $data['to']);


    $parent_key = $data['parent_key'];


    switch ($data['parent']) {

        case 'Agent':
            $table = '`Purchase Order Dimension` O';
            $where = sprintf('where  `Purchase Order Parent`="Agent" and `Purchase Order Parent Key`=%d ', $parent_key, $agent_key);
            break;

        default:
            exit ($data['parent']);
            break;
    }

    $where_interval = prepare_mysql_dates($from, $to, '`Order Date`');
    $where_interval = $where_interval['mysql'];


    $elements_numbers = array(
        'state' => array(
            'Submitted'  => 0,
            'Dispatched' => 0,
            'Cancelled'  => 0
        ),
    );


    //USE INDEX (`Main Source Type Store Key`)
    $sql = sprintf(
        "SELECT count(*) AS number,`Purchase Order State` AS element FROM %s    %s  %s GROUP BY `Purchase Order State` ", $table, $where, $where_interval
    );
    //  print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $element = $row['element'];
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }
        }
    } else {
        print "$sql";
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

function get_supplier_deliveries_element_numbers($db, $data) {


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $data['period'], $data['from'], $data['to']
    );

    $parent_key     = $data['parent_key'];
    $where_interval = prepare_mysql_dates(
        $from, $to, '`Supplier Delivery Date`'
    );

    $table = '`Supplier Delivery Dimension`  SD  ';
    switch ($data['parent']) {
        case 'account':
            $table = '`Supplier Delivery Dimension` D left join `Supplier Dimension` on (`Supplier Key`=`Supplier Delivery Parent Key`)   ';


            $where = sprintf(
                'where (( `Supplier Delivery Parent`="Supplier" and  `Supplier Production`="No"  ) or  `Supplier Delivery Parent`="Agent" )'
            );
            break;
        case 'production':
            $table = '`Supplier Delivery Dimension` D left join `Supplier Dimension` on (`Supplier Key`=`Supplier Delivery Parent Key`)   ';

            $where = sprintf(
                'where  `Supplier Delivery Parent`="Supplier"  and  `Supplier Production`="Yes"  '
            );
            break;
        case 'supplier_production':
        case 'production_supplier':
            $table = '`Supplier Delivery Dimension` D left join `Supplier Dimension` on (`Supplier Key`=`Supplier Delivery Parent Key`)   ';

            $where = sprintf(
                'where  `Supplier Delivery Parent`="Supplier" and `Supplier Delivery Parent Key`=%d   and  `Supplier Production`="Yes" ', $parent_key
            );
            break;
        case 'supplier':
            $where = sprintf(
                'where  `Supplier Delivery Parent`="Supplier" and `Supplier Delivery Parent Key`=%d  ', $parent_key
            );
            break;
        case 'agent':
            $where = sprintf(
                'where  `Supplier Delivery Parent`="Agent" and `Supplier Delivery Parent Key`=%d  ', $parent_key
            );
            break;
        case 'supplier_part':
            $table = ' `Purchase Order Transaction Fact` POTF  left join  `Supplier Delivery Dimension` D on (POTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`) ';
            $where = sprintf(
                'where `Supplier Part Key`=%d  ', $parent_key
            );
            break;
        case 'part':
            $table = ' `Purchase Order Transaction Fact` POTF  left join  `Supplier Delivery Dimension` D on (POTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`) 
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

	 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)

	
	';
            $where = sprintf('where `Part SKU`=%d  ', $parent_key);
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $elements_numbers = array(
        'state' => array(
            'InProcess'      => 0,
            'Received'       => 0,
            'Checked'        => 0,
            'Placed'         => 0,
            'Cancelled'      => 0,
            'InvoiceChecked' => 0
        ),
    );

    $sql = sprintf(
        "SELECT count(*) AS number,`Supplier Delivery State` AS element FROM %s %s GROUP BY `Supplier Delivery State` ", $table, $where

    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['element'] == 'Consolidated' or $row['element'] == 'Dispatched') {
                $element = 'InProcess';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing') {
                $element = 'Placed';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_production_deliveries_element_numbers($db, $data) {


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $data['period'], $data['from'], $data['to']
    );

    $parent_key     = $data['parent_key'];
    $where_interval = prepare_mysql_dates(
        $from, $to, '`Supplier Delivery Date`'
    );

    $table = '`Supplier Delivery Dimension`  SD  ';
    switch ($data['parent']) {

        case 'production':
            $table = '`Supplier Delivery Dimension` D left join `Supplier Dimension` on (`Supplier Key`=`Supplier Delivery Parent Key`)   ';

            $where = sprintf(
                'where  `Supplier Delivery Parent`="Supplier"  and  `Supplier Production`="Yes"  '
            );
            break;
        case 'supplier_production':
        case 'production_supplier':
            $table = '`Supplier Delivery Dimension` D left join `Supplier Dimension` on (`Supplier Key`=`Supplier Delivery Parent Key`)   ';

            $where = sprintf(
                'where  `Supplier Delivery Parent`="Supplier" and `Supplier Delivery Parent Key`=%d   and  `Supplier Production`="Yes" ', $parent_key
            );
            break;

        case 'supplier_part':
            $table = ' `Purchase Order Transaction Fact` POTF  left join  `Supplier Delivery Dimension` D on (POTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`) ';
            $where = sprintf(
                'where `Supplier Part Key`=%d  ', $parent_key
            );
            break;
        case 'part':
            $table = ' `Purchase Order Transaction Fact` POTF  left join  `Supplier Delivery Dimension` D on (POTF.`Supplier Delivery Key`=D.`Supplier Delivery Key`) 
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)

	 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)

	
	';
            $where = sprintf('where `Part SKU`=%d  ', $parent_key);
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $elements_numbers = array(
        'state' => array(
            'InProcess' => 0,
            'Checked'   => 0,
            'Placed'    => 0,
            'Cancelled' => 0,
        ),
    );

    $sql = sprintf(
        "SELECT count(*) AS number,`Supplier Delivery State` AS element FROM %s %s GROUP BY `Supplier Delivery State` ", $table, $where

    );

    //'InProcess','Consolidated','Dispatched','Received','Checked','Placed','Costing','Cancelled','InvoiceChecked'
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['element'] == 'Consolidated' or $row['element'] == 'Dispatched' or $row['element'] == 'Received') {
                $element = 'InProcess';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing' or $row['element'] == 'InvoiceChecked') {
                $element = 'Placed';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }


        }
    }

    foreach ($elements_numbers['state'] as $key => $value) {
        $elements_numbers['state'][$key] = number($value);
    }

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_returns_element_numbers($db, $data) {


    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $data['period'], $data['from'], $data['to']
    );

    $parent_key     = $data['parent_key'];
    $where_interval = prepare_mysql_dates(
        $from, $to, '`Supplier Delivery Date`'
    );
    $where_interval = $where_interval['mysql'];

    $table = '`Supplier Delivery Dimension`  SD  ';
    switch ($data['parent']) {
        case 'warehouse':
            $where = sprintf(' where `Supplier Delivery Parent`="Order"  ');
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $elements_numbers = array(
        'state' => array(
            'InProcess'      => 0,
            'Received'       => 0,
            'Checked'        => 0,
            'Placed'         => 0,
            'Cancelled'      => 0,
            'InvoiceChecked' => 0
        ),
    );

    $sql = sprintf(
        "SELECT count(*) AS number,`Supplier Delivery State` AS element FROM %s %s GROUP BY `Supplier Delivery State` ", $table, $where

    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['element'] == 'Consolidated' or $row['element'] == 'Dispatched') {
                $element = 'InProcess';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing') {
                $element = 'Placed';
            } else {
                $element = $row['element'];
            }
            if (isset($elements_numbers['state'][$element])) {
                $elements_numbers['state'][$element] += $row['number'];
            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_category_root_all_parts_elements($db, $data) {


    $elements_numbers = array(
        'status' => array(
            'Assigned'   => 0,
            'NoAssigned' => 0
        ),
    );

    $sql = sprintf(
        "SELECT count(*) AS number FROM `Category Bridge` WHERE `Subject`='Part' AND `Category Key`=%d ", $data['parent_key']

    );

    $assigned = 0;

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $assigned                               = $row['number'];
            $elements_numbers['status']['Assigned'] = number($row['number']);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $sql = sprintf("SELECT count(*) AS number FROM `Part Dimension`");


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $elements_numbers['status']['NoAssigned'] = number(
                $row['number'] - $assigned
            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_ec_sales_list_elements($db, $parameters, $account) {

    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );


    $where_interval = prepare_mysql_dates($from, $to, '`Invoice Date`');
    $where_interval = $where_interval['mysql'];

    $elements_numbers = array(
        'tax_status' => array(
            'Yes'     => 0,
            'No'      => 0,
            'Missing' => 0
        ),
    );


    include_once('class.Country.php');

    $account_country = new Country('code', $account->get('Account Country Code'));


    $european_union_2alpha = array(
        'NL',
        'BE',
        'GB',
        'BG',
        'ES',
        'IE',
        'IT',
        'AT',
        'GR',
        'CY',
        'LV',
        'LT',
        'LU',
        'MT',
        'PT',
        'PL',
        'FR',
        'RO',
        'SE',
        'DE',
        'SK',
        'SI',
        'FI',
        'DK',
        'CZ',
        'HU',
        'EE'
    );


    $european_union_2alpha = "'".implode("','", $european_union_2alpha)."'";


    $european_union_2alpha = preg_replace('/,?\''.$account_country->get('Country 2 Alpha Code').'\'/', '', $european_union_2alpha);

    $european_union_2alpha = preg_replace('/^,/', '', $european_union_2alpha);


    $where = ' where `Invoice Address Country 2 Alpha Code` in ('.$european_union_2alpha.')';


    $table = '`Invoice Dimension`';

    $group_by = 'group by `Invoice Tax Number`,`Invoice Address Country 2 Alpha Code`,`Invoice Customer Key`';


    $sql = sprintf(
        "select `Invoice Key` as number from %s %s %s and `Invoice Tax Number`!='' and `Invoice Tax Number Valid`='Yes'  $group_by ", $table, $where, $where_interval
    );
    //print $sql;

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $elements_numbers['tax_status']['Yes'] = $stmt->rowCount();


    $sql = sprintf(
        "select `Invoice Key` as number from %s %s %s and `Invoice Tax Number`!='' and `Invoice Tax Number Valid`!='Yes'  $group_by ", $table, $where, $where_interval
    );
    //print $sql;

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $elements_numbers['tax_status']['No'] = $stmt->rowCount();


    $sql = sprintf(
        "select `Invoice Key` as number from %s %s %s and ( `Invoice Tax Number` is NULL or `Invoice Tax Number`='' ) $group_by ", $table, $where, $where_interval
    );
    //print $sql;

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $elements_numbers['tax_status']['Missing'] = $stmt->rowCount();


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_part_families_elements($db, $data, $user) {


    $elements_numbers = array(
        'status' => array(
            'InUse'         => 0,
            'NotInUse'      => 0,
            'InProcess'     => 0,
            'Discontinuing' => 0,

        ),

    );


    $table = '`Category Dimension` B left join `Part Category Dimension` PC on (B.`Category Key`=`Part Category Key`)';
    $where = sprintf("where `Category Branch Type`='Head' and `Category Scope`='Part' ");


    $sql = sprintf("select count(*) as number,`Part Category Status` as element from $table $where  group by `Part Category Status` ");


    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_webpages_element_numbers($db, $data, $user) {

    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'state'   => array(
            'Online'  => 0,
            'Offline' => 0,
        ),
        'version' => array(
            'I'  => 0,
            'II' => 0,
        ),

    );


    switch ($data['parent']) {
        case 'website':
            $where = sprintf(
                ' where `Webpage Website Key`=%d  ', $data['parent_key']
            );
            break;

        default:
            $response = array(
                'state' => 405,
                'resp'  => 'customer parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf("select count(*) as number,`Webpage State` as element from `Page Store Dimension`  $where  group by `Webpage State` ");
    foreach ($db->query($sql) as $row) {
        $elements_numbers['status'][$row['element']] = number($row['number']);
    }


    $sql = sprintf("select count(*) as number,`Webpage Version` as element from `Page Store Dimension`  $where  group by `Webpage Version` ");
    foreach ($db->query($sql) as $row) {
        if ($row['element'] == 1) {
            $_element = 'I';
        }
        if ($row['element'] == 2) {
            $_element = 'II';
        }


        $elements_numbers['version'][$_element] = number($row['number']);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_webpages_by_state_element_numbers($db, $data, $user, $state) {


    $elements_numbers = array(
        'type' => array(
            'Cats'   => 0,
            'Prods'  => 0,
            'Prod'   => 0,
            'Others' => 0,

        ),


    );
    $where            = ' where `Webpage State`="'.$state.'"';

    switch ($data['parent']) {
        case 'website':
            $where .= sprintf(' and `Webpage Website Key`=%d  ', $data['parent_key']);
            break;
        case 'webpage_type':
            $where .= sprintf(' and `Webpage Type Key`=%d  ', $data['parent_key']);
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Webpage Type Code` as element from `Page Store Dimension` P left join `Webpage Type Dimension` WTD on (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  $where  group by P.`Webpage Type Key` "
    );


    foreach ($db->query($sql) as $row) {

        if ($row['element'] == 'Cats' or $row['element'] == 'Prods' or $row['element'] == 'Prod') {
            $elements_numbers['type'][$row['element']] = number($row['number']);
        } else {
            $elements_numbers['type']['Others'] += $row['number'];
        }

        $elements_numbers['type']['Others'] = number($elements_numbers['type']['Others']);


    }
    $elements_numbers['type']['Others'] = number($elements_numbers['type']['Others']);


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_agent_parts_element_numbers($db, $data, $user) {


    $parent_key = $data['parent_key'];

    $elements_numbers = array(
        'status'      => array(
            'Available'    => 0,
            'NoAvailable'  => 0,
            'Discontinued' => 0
        ),
        'part_status' => array(
            'Required'    => 0,
            'NotRequired' => 0
        ),

    );


    $table = '`Supplier Part Dimension`  SP left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) ';
    switch ($data['parent']) {
        case 'supplier':
        case 'supplier_production':

            $where = sprintf(
                ' where `Supplier Part Supplier Key`=%d  ', $data['parent_key']
            );
            break;
        case 'agent':

            $where = sprintf(
                " where  `Agent Supplier Agent Key`=%d", $data['parent_key']
            );
            $table .= ' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';

            break;
        case 'purchase_order':

            $purchase_order = get_object('PurchaseOrder', $data['parent_key']);

            if ($purchase_order->get('Purchase Order Parent') == 'Supplier') {

                $where = sprintf(
                    " where  `Supplier Part Supplier Key`=%d", $purchase_order->get('Purchase Order Parent Key')
                );


            } else {


                $where = sprintf(
                    "  where  `Agent Supplier Agent Key`=%d", $purchase_order->get('Purchase Order Parent Key')
                );
                $table .= ' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';


            }

            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'product parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    $sql = sprintf(
        "select count(*) as number,`Part Status` as element from $table $where  group by `Part Status` "
    );


    foreach ($db->query($sql) as $row) {


        if ($row['element'] == 'Discontinuing' or $row['element'] == 'Not In Use') {
            $_element = 'NotRequired';
        } else {
            $_element = 'Required';
        }


        $elements_numbers['part_status'][$_element] += $row['number'];

    }


    foreach ($elements_numbers['part_status'] as $key => $value) {
        $elements_numbers['part_status'][$key] = number($value);
    }


    $sql = sprintf(
        "select count(*) as number,`Supplier Part Status` as element from $table $where  group by `Supplier Part Status` "
    );

    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_lost_stock_element_numbers($db, $data, $user, $account) {


    $elements_numbers = array(

        'type' => array(
            'Broken' => 0,
            'Lost'   => 0,
            'Error'  => 0,
        ),

    );


    $class_html = array(
        'Broken_Parts'  => 0,
        'Broken_Amount' => money(0, $account->get('Currency Code')),
        'Lost_Parts'    => 0,
        'Lost_Amount'   => money(0, $account->get('Currency Code')),
        'Error_Parts'   => 0,
        'Error_Amount'  => money(0, $account->get('Currency Code'))

    );


    $where = " where  `Inventory Transaction Quantity`<0  and `Inventory Transaction Type` in ('Broken','Lost','Other Out') ";


    if (isset($data['period'])) {

        include_once 'utils/date_functions.php';
        list(
            $db_interval, $from, $to, $from_date_1yb, $to_1yb
            ) = calculate_interval_dates(
            $db, $data['period'], $data['from'], $data['to']
        );
        $where_interval = prepare_mysql_dates($from, $to, '`Date`');
        $where          .= $where_interval['mysql'];

    }

    $sql = sprintf("select count(*) as number,`Inventory Transaction Type` as element,sum(`Inventory Transaction Amount`) as amount,count(distinct `Part SKU`) as parts from `Inventory Transaction Fact`  $where  group by `Inventory Transaction Type` ");

    // print $sql;
    foreach ($db->query($sql) as $row) {

        if ($row['element'] == 'Other Out') {
            $row['element'] = 'Error';
        }
        $elements_numbers['type'][$row['element']] = number($row['number']);

        $class_html[$row['element'].'_Amount'] = money(-1 * $row['amount'], $account->get('Currency Code'));
        $class_html[$row['element'].'_Parts']  = $row['parts'];

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers,
        'class_html'       => $class_html
    );
    echo json_encode($response);


}


function get_stock_given_free_element_numbers($db, $data, $user, $account) {


    $elements_numbers = array(

        'type' => array(
            'Order'       => 0,
            'Replacement' => 0,
        ),

    );


    $class_html = array(
        'Order_Parts'        => 0,
        'Order_Amount'       => money(0, $account->get('Currency Code')),
        'Replacement_Parts'  => 0,
        'Replacement_Amount' => money(0, $account->get('Currency Code')),


    );


    $where = " where  `Amount In`=0 and `Inventory Transaction Type`='Sale' ";


    if (isset($data['period'])) {

        include_once 'utils/date_functions.php';
        list(
            $db_interval, $from, $to, $from_date_1yb, $to_1yb
            ) = calculate_interval_dates(
            $db, $data['period'], $data['from'], $data['to']
        );
        $where_interval = prepare_mysql_dates($from, $to, '`Date`');
        $where          .= $where_interval['mysql'];

    }

    $sql = sprintf(
        "select count(*) as number,`Delivery Note Type` as element,sum(`Inventory Transaction Amount`) as amount,count(distinct `Part SKU`) as parts from `Inventory Transaction Fact` ITF left join `Delivery Note Dimension` DN on (ITF.`Delivery Note Key`=DN.`Delivery Note Key`)  $where  group by `Delivery Note Type` "
    );

    // print $sql;
    foreach ($db->query($sql) as $row) {

        $elements_numbers['type'][$row['element']] = number($row['number']);

        $class_html[$row['element'].'_Amount'] = money(-1 * $row['amount'], $account->get('Currency Code'));
        $class_html[$row['element'].'_Parts']  = $row['parts'];

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers,
        'class_html'       => $class_html
    );
    echo json_encode($response);


}


function get_prospects_elements($db, $data) {


    $elements_numbers = array(

        'status' => array(
            'Contacted'     => 0,
            'Bounced'       => 0,
            'NoContacted'   => 0,
            'NotInterested' => 0,
            'Registered'    => 0,
            'Invoiced'      => 0,
        )
    );

    $table = '`Prospect Dimension`  P';

    switch ($data['parent']) {
        case 'store':
            $where = sprintf(
                ' where `Prospect Store Key`=%d  ', $data['parent_key']
            );
            break;
        case 'sales_representative':
            $where = sprintf(
                ' where `Prospect Sales Representative Key`=%d  ', $data['parent_key']
            );
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'prospect parent not found '.$data['parent']
            );
            echo json_encode($response);

            return;
    }


    if (isset($data['period'])) {
        include_once 'utils/date_functions.php';
        list(
            $db_interval, $from, $to, $from_date_1yb, $to_1yb
            ) = calculate_interval_dates(
            $db, $data['period'], $data['from'], $data['to']
        );
        $where_interval = prepare_mysql_dates($from, $to, 'P.`Prospect First Contacted Date`');
        $where          .= $where_interval['mysql'];

    }


    $sql = sprintf(
        "select count(Distinct P.`Prospect Key`) as number,`Prospect Status` as element from $table $where  group by `Prospect Status` "
    );


    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_purged_orders_elements($db, $data) {


    $elements_numbers = array(

        'state' => array(
            'In_Process' => 0,
            'Purged'     => 0,
            'Exculpated' => 0,
            'Cancelled'  => 0,

        )
    );


    $table = '`Order Basket Purge Order Fact` left join   `Order Dimension` O on (`Order Key`=`Order Basket Purge Order Order Key`)  ';


    $where = sprintf(
        ' where `Order Basket Purge Order Basket Purge Key`=%d ', $data['parent_key']
    );


    $sql = sprintf(
        "select count(*) as number,`Order Basket Purge Order Status` as element from $table $where  group by `Order Basket Purge Order Status` "
    );


    foreach ($db->query($sql) as $row) {

        if ($row['element'] == 'In Process') {
            $row['element'] = 'In_Process';
        }

        $elements_numbers['state'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

function get_email_campaign_sent_emails_elements($db, $data) {


    $elements_numbers = array(

        'state' => array(
            'Rejected by SES'   => 0,
            'Sending'   => 0,
            'Delivered' => 0,
            'Opened'    => 0,
            'Clicked'   => 0,
            'Bounced'   => 0,
            'Spam'      => 0,
            'Error'     => 0,

        )
    );


    $table = '`Email Tracking Dimension`   ';


    $where = sprintf(
        ' where `Email Tracking Email Mailshot Key`=%d ', $data['parent_key']
    );


    $sql = sprintf(
        "select count(*) as number,`Email Tracking State` as element from $table $where  group by `Email Tracking State` "
    );


    foreach ($db->query($sql) as $row) {

        if ($row['element'] == 'Ready' or $row['element'] == 'Sent to SES' or $row['element'] == 'Sent') {
            $row['element'] = 'Sending';
        }

        if ($row['element'] == 'Soft Bounce') {
            $row['element'] = 'Bounced';
        }

        if ($row['element'] == 'Hard Bounce') {
            $row['element'] = 'Bounced';
        }

        $elements_numbers['state'][$row['element']] += $row['number'];

    }

    foreach ($elements_numbers['state'] as $_key => $_value) {
        $elements_numbers['state'][$_key] = number($_value);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_account_mailshots_elements($db, $data) {


    $elements_numbers = array(

        'type' => array(
            'Newsletter'      => 0,
            'Marketing'       => 0,
            'AbandonedCart'   => 0,
            'GRReminder'      => 0,
            'OOSNotification' => 0,


        )
    );


    $sql = sprintf(
        "select count(*) as number,`Email Campaign Type` as element from `Email Campaign Dimension`   group by `Email Campaign Type` "
    );


    foreach ($db->query($sql) as $row) {

        if ($row['element'] == 'GR Reminder') {
            $row['element'] = 'GRReminder';
        }

        if ($row['element'] == 'OOS Notification') {
            $row['element'] = 'OOSNotification';
        }


        $elements_numbers['type'][$row['element']] += $row['number'];

    }

    foreach ($elements_numbers['type'] as $_key => $_value) {
        $elements_numbers['type'][$_key] = number($_value);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_users_elements($db, $user_type) {


    $elements_numbers = array(

        'active' => array(
            'Yes' => 0,
            'No'  => 0,


        )
    );


    $sql = "select count(*) as number,`User Active` as element from `User Dimension` where `User Type`=?  group by `User Active`";

    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        array(
            $user_type
        )
    )) {
        while ($row = $stmt->fetch()) {
            $elements_numbers['active'][$row['element']] = number($row['number']);

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit();
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_orders_control_panel_numbers($tipo, $db, $data, $user, $account) {
    $elements_numbers = array(
        'location' => array(
            'Domestic' => 0,
            'Export'   => 0,
        )
    );
    switch ($tipo) {
        case 'orders.website':
            $where = 'where `Order State`="InBasket" ';
            break;
        case 'orders.in_process.paid':
            $where = 'where `Order State`="InProcess"  and `Order To Pay Amount`<=0 ';
            break;
        case 'orders.in_process.not_paid':
            $where = 'where `Order State`="InProcess"   and `Order To Pay Amount`>0 ';
            break;
        case 'orders.in_warehouse_no_alerts':
            $where = 'where (`Order State`="InWarehouse" or `Order Replacement State`="InWarehouse"  ) and `Order Delivery Note Alert`!="Yes" ';
            break;
        case 'orders.in_warehouse_with_alerts':
            $where = 'where (`Order State`="InWarehouse" or `Order Replacement State`="InWarehouse"  ) and `Order Delivery Note Alert`="Yes" ';
            break;
        case 'orders.packed_done':
            $where = 'where  (`Order State`="PackedDone" or `Order Replacement State`="PackedDone" ) ';
            break;
        case 'orders.approved':
            $where = 'where `Order State`="Approved"  ';
            break;
        case 'orders.dispatched_today':
            $where = sprintf(
                'where ((`Order State`="Dispatched" and `Order Dispatched Date`>%s ) or (`Order Replacement State`="Dispatched" and `Order Post Transactions Dispatched Date`>%s )) ', prepare_mysql(gmdate('Y-m-d 00:00:00')), prepare_mysql(gmdate('Y-m-d 00:00:00'))
            );
            break;
        default:
            print $tipo;
            exit;
    }

    $home_country = $account->get('Account Country 2 Alpha Code');


    if ($data['parent'] == 'store') {
        $where        .= sprintf(' and  `Order Store Key`=%d ', $data['parent_key']);
        $store        = get_object('store', $data['parent_key']);
        $home_country = $store->get('Store Home Country Code 2 Alpha');
        /*
        if (is_numeric($data['parent_key']) and in_array($data['parent_key'], $user->stores)) {
            $where .= sprintf(' and  `Order Store Key`=%d ', $data['parent_key']);
            $store        = get_object('store', $data['parent_key']);
            $home_country = $store->get('Store Home Country Code 2 Alpha');
        } else {
            $where .= sprintf(' and  false');
        }
        */


    } elseif ($data['parent'] == 'account') {
        $where .= ' and true';
        /*

        if (is_numeric($data['parent_key']) and in_array($data['parent_key'], $user->stores)) {
            if (count($user->stores) == 0) {
                $where .= ' and false';
            } else {
                $where .= sprintf('and  `Order Store Key` in (%s)  ', join(',', $user->stores));
            }
        }

        */
    }
    $sql = "select count(*) as number from `Order Dimension` O $where and `Order Invoice Address Country 2 Alpha Code`!=? ";
    //print $sql;
    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        array(
            $home_country
        )
    )) {
        while ($row = $stmt->fetch()) {
            $elements_numbers['location']['Export'] = number($row['number']);
        }
    } else {
        print_r($error_info = $stmt->errorInfo());
        exit();
    }
    $sql  = "select count(*) as number from `Order Dimension` O $where and `Order Invoice Address Country 2 Alpha Code`=?  ";
    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        array(
            $home_country
        )
    )) {
        while ($row = $stmt->fetch()) {
            $elements_numbers['location']['Domestic'] = number($row['number']);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit();
    }
    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);
}

function get_all_users_elements($db) {


    $elements_numbers = array(

        'active' => array(
            'Yes' => 0,
            'No'  => 0,


        )
    );


    $sql = "select count(*) as number,`User Active` as element from `User Dimension` where `User Type`!='Customer'  group by `User Active`";

    $stmt = $db->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $elements_numbers['active'][$row['element']] = number($row['number']);

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit();
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_category_deal_components_element_numbers($db, $data, $user) {


    $elements_numbers = array(
        'status' => array(
            'Active'    => 0,
            'Waiting'   => 0,
            'Suspended' => 0,
            'Finish'    => 0
        )

    );


    $sql = "select count(*) as number,`Deal Component Status` as element from `Deal Component Dimension` D where  `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key`=? group by `Deal Component Status` ";

    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        array(
            $data['parent_key']
        )
    )) {
        while ($row = $stmt->fetch()) {
            $elements_numbers['status'][$row['element']] = number($row['number']);

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit();
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


function get_websites_elements($db, $data, $account, $user) {


    $elements_numbers = array(
        'status' => array(
            'Active'    => 0,
            'InProcess' => 0,
            'Closed'    => 0,
        ),

    );


    switch ($data['parent']) {
        case 'store':
            $where = sprintf(' where `Website Store Key`=%d ', $data['parent_key']);
            break;
        default:
            $where = sprintf('');
    }


    $sql = sprintf(
        "select count(*) as number,`Website Status` as element from `Website Dimension` D $where  group by `Website Status` "
    );
    foreach ($db->query($sql) as $row) {

        $elements_numbers['status'][$row['element']] = number($row['number']);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}


/**
 * @param $db \PDO
 * @param $data
 */
function get_customer_active_portfolio_elements($db, $data) {


    $elements_numbers = array(
        'availability_state' => array(
            'OutofStock' => 0,
            'VeryLow'    => 0,
            'Low'        => 0,
            'Ok'         => 0,
        ),

    );


    $sql  =
        "select count(*) as number,`Product Availability State` as element from  `Customer Portfolio Fact` CPF left join    `Product Dimension` P  on (`Customer Portfolio Product ID`=P.`Product ID`) where  `Customer Portfolio Customer Key`=? and  `Customer Portfolio Customers State`='Active' group by `Product Availability State` ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $data['parent_key']
        )
    );
    while ($row = $stmt->fetch()) {
        if ($row['element'] == 'Error') {
            $row['element'] = 'OutofStock';
        } elseif ($row['element'] == 'Excess' or $row['element'] == 'Normal' or $row['element'] == 'OnDemand') {
            $row['element'] = 'Ok';
        }

        $elements_numbers['availability_state'][$row['element']] = $row['number'];
    }


    foreach ($elements_numbers['availability_state'] as $key => $value) {

        $elements_numbers['availability_state'][$key] = number($value);

    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

/**
 * @param $db \PDO
 * @param $data
 */
function get_attendance_elements($db, $data) {


    $elements_numbers = array(
        'status' => array(
            'Work       ' => 0,
            'Home'    => 0,
            'Outside'        => 0,
            'Break'         => 0,
            'Finish'         => 0,
            'Off'         => 0,
        ),

    );




    $sql  =
        "select count(*) as number,`Staff Attendance Status` as element from `Timesheet Dimension` as TD left join `Staff Dimension` SD on (SD.`Staff Key`=TD.`Timesheet Staff Key`) where   date(`Timesheet Date`)=?  and `Staff Currently Working`='Yes' group by `Staff Attendance Status`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            gmdate('Y-m-d')
        )
    );
    while ($row = $stmt->fetch()) {


        $elements_numbers['status'][$row['element']] = number($row['number']);
    }





    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}