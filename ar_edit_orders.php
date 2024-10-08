<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 January 2017 at 13:57:01 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutSdk;
use Checkout\Environment;
use Checkout\Payments\RefundRequest;

require 'vendor/autoload.php';

require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


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
    case 'update_delivery_note_picking_locations':
        $data = prepare_values(
            $_REQUEST, array(

                         'delivery_note_key' => array('type' => 'key'),
                     )
        );
        update_delivery_note_picking_locations($data, $editor);
        break;

    case 'get_input_delivery_note_packing_all_locations':
        $data = prepare_values(
            $_REQUEST, array(

                         'metadata' => array('type' => 'json array'),
                     )
        );
        get_input_delivery_note_packing_all_locations($data, $editor, $smarty, $db, $account, $user);
        break;
    case 'get_picked_offline_input_all_locations':
        $data = prepare_values(
            $_REQUEST, array(

                         'metadata' => array('type' => 'json array'),
                     )
        );
        get_picked_offline_input_all_locations($data, $editor, $smarty, $db, $account, $user);
        break;
    case 'create_replacement':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'          => array('type' => 'key'),
                         'transactions' => array('type' => 'json array')

                     )
        );
        create_replacement($data, $editor);
        break;
    case 'create_return':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'          => array('type' => 'key'),
                         'transactions' => array('type' => 'json array'),
                     )
        );
        create_return($data, $editor);
        break;
    case 'create_refund':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'          => array('type' => 'key'),
                         'transactions' => array('type' => 'json array'),


                     )
        );

        create_refund($data, $editor);
        break;
    case 'create_refund_tax_only':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'          => array('type' => 'key'),
                         'transactions' => array('type' => 'json array'),


                     )
        );

        create_refund_tax_only($data, $editor);

        break;

    case 'refund_payment':
        $data = prepare_values(
            $_REQUEST, array(
                         'operation'      => array('type' => 'string'),
                         'key'            => array('type' => 'key'),
                         'reference'      => array('type' => 'string'),
                         'submit_type'    => array('type' => 'string'),
                         'amount'         => array('type' => 'string'),
                         'payback_refund' => array('type' => 'string'),
                         'parent'         => array('type' => 'string'),
                         'parent_key'     => array('type' => 'string'),

                     )
        );

        refund_payment($data, $editor, $smarty, $db, $account, $user);


        break;


    case 'new_payment':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'         => array('type' => 'string'),
                         'parent_key'     => array('type' => 'key'),
                         'reference'      => array('type' => 'string'),
                         'payment_method' => array('type' => 'string'),

                         'amount'              => array('type' => 'string'),
                         'payment_account_key' => array('type' => 'key'),


                     )
        );


        new_payment($data, $editor, $db, $account, $user);


        break;


    case 'set_state':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),

                         'key'   => array('type' => 'key'),
                         'value' => array('type' => 'string'),

                     )
        );
        set_state($data, $editor, $smarty, $db);
        break;
    case 'set_picker':
        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_note_key' => array('type' => 'key'),
                         'staff_key'         => array('type' => 'numeric'),

                     )
        );
        set_order_handler('Picker', $data, $editor, $smarty, $db);
        break;

    case 'set_packer':
        $data = prepare_values(
            $_REQUEST, array(
                         'delivery_note_key' => array('type' => 'key'),
                         'staff_key'         => array('type' => 'numeric'),

                     )
        );
        set_order_handler('Packer', $data, $editor, $smarty, $db);
        break;

    case 'set_shipping_as_auto':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),

                     )
        );
        set_shipping_as_auto($data, $editor);
        break;

    case 'set_shipping_value':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),
                         'amount'    => array('type' => 'string'),

                     )
        );
        set_shipping_value($data, $editor);
        break;

    case 'set_charges_as_auto':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),

                     )
        );
        set_hanging_charges_as_auto($data, $editor);
        break;

    case 'set_hanging_charges_value':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),
                         'amount'    => array('type' => 'string'),

                     )
        );
        set_hanging_charges_value($data, $editor);
        break;
    case 'edit_item_discount':
        $data = prepare_values(
            $_REQUEST, array(
                         'field'      => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),

                         'transaction_key' => array('type' => 'key'),

                         'value' => array('type' => 'string'),

                     )
        );
        edit_item_discount($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'edit_item_in_order':


        $data = prepare_values(
            $_REQUEST, array(
                         'field'             => array('type' => 'string'),
                         'parent'            => array('type' => 'string'),
                         'parent_key'        => array('type' => 'key'),
                         'item_key'          => array(
                             'type'     => 'key',
                             'optional' => true
                         ),
                         'item_historic_key' => array(
                             'type'     => 'key',
                             'optional' => true
                         ),
                         'transaction_key'   => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'picker_key'        => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'packer_key'        => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'tab'               => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'qty'               => array('type' => 'numeric'),

                     )
        );
        edit_item_in_order($db, $editor, $data);
        break;


    case 'set_po_transaction_amount_to_current_cost':
        $data = prepare_values(
            $_REQUEST, array(
                         'type' => array('type' => 'string'),

                         'transaction_key' => array('type' => 'key'),


                     )
        );
        set_po_transaction_amount_to_current_cost($data, $editor, $account, $db);
        break;

    case 'toggle_charge':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),
                         'parent'     => array('type' => 'string'),
                         'charge_key' => array('type' => 'key'),
                         'operation'  => array('type' => 'string'),

                     )
        );
        toggle_charge($data, $editor, $smarty, $db, $account, $user);
        break;

    case 'toggle_deal_component_choose_by_customer':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key'                         => array('type' => 'key'),
                         'deal_component_key'                => array('type' => 'key'),
                         'product_id'                        => array('type' => 'key'),
                         'order_transaction_deal_bridge_key' => array('type' => 'order_transaction_deal_bridge_key'),


                     )
        );
        toggle_deal_component_choose_by_customer($data, $editor, $smarty, $db, $account, $user);
        break;

    case 'update_po_item_note':
        $data = prepare_values(
            $_REQUEST, array(
                         'purchase_order_transaction_fact_key' => array('type' => 'key'),
                         'note'                                => array('type' => 'string'),


                     )
        );
        update_po_item_note($data, $db);
        break;
    case 'clone_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array('type' => 'key'),
                     )
        );
        clone_order($data, $editor);
        break;
    case 'cancel_purchase_order_submitted_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'transaction_key' => array('type' => 'key'),
                     )
        );
        cancel_purchase_order_submitted_item($data, $db);
        break;
    case 'send_orders_to_warehouse':
        $data = prepare_values(
            $_REQUEST, array(

                         'order_keys' => array('type' => 'json array'),

                     )
        );
        send_orders_to_warehouse($data, $editor, $smarty);
        break;
    case 'set_orders_as_dispatched':
        $data = prepare_values(
            $_REQUEST, array(

                         'order_keys' => array('type' => 'json array'),

                     )
        );
        set_orders_as_dispatched($db, $data, $editor);
        break;

    case 'approve_orders':
        $data = prepare_values(
            $_REQUEST, array(

                         'order_keys' => array('type' => 'json array'),

                     )
        );
        approve_orders($db, $data, $editor);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);

        break;
}


function approve_orders($db, $data, $editor)
{
    $number_updated = 0;
    foreach ($data['order_keys'] as $order_key) {
        $order         = get_object('Order', $order_key);
        $order->editor = $editor;

        if ($order->get('Order State') == 'PackedDone') {
            if ($order->update_state('Approved')) {
                $number_updated++;
            }
        } else {
            $sql = "select `Delivery Note Key` from `Delivery Note Dimension` where `Delivery Note Order Key`=? and `Delivery Note State`='Packed Done' and `Delivery Note Type`  
                                                                                                                                            
                                                                        in ('Replacement & Shortages','Replacement','Shortages')  ";


            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $order_key
                )
            );
            while ($row = $stmt->fetch()) {
                $order->update(array('Replacement State' => 'Replacement Approved'), '', '{"Delivery Note Key":"'.$row['Delivery Note Key'].'"}');

                if ($order->updated) {
                    $number_updated++;
                }
            }
        }
    }


    if ($number_updated > 0) {
        $html = '<i class="fa fa-check success padding_left_5"></i>';
    } else {
        $html = '';
    }

    $html .= ' '.number($number_updated).' '.ngettext('order invoiced', 'orders invoiced', $number_updated);


    $response = array(
        'state' => 200,
        'msg'   => $html
    );
    echo json_encode($response);
}

function set_orders_as_dispatched($db, $data, $editor)
{
    $number_updated = 0;
    foreach ($data['order_keys'] as $order_key) {
        $sql  = "select `Delivery Note Key` from `Delivery Note Dimension` where `Delivery Note Order Key`=? and `Delivery Note State`='Approved'  ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $order_key
            )
        );
        while ($row = $stmt->fetch()) {
            $dn         = get_object('Delivery_Note', $row['Delivery Note Key']);
            $dn->editor = $editor;


            if ($dn->update_state('Dispatched')) {
                $number_updated++;
            }
        }
    }


    if ($number_updated > 0) {
        $html = '<i class="fa fa-check success  padding_left_5"></i>';
    } else {
        $html = '';
    }

    $html .= ' '.number($number_updated).' '.ngettext('order set as dispatched', 'orders set as dispatched', $number_updated);


    $response = array(
        'state' => 200,
        'msg'   => $html
    );
    echo json_encode($response);
}

function send_orders_to_warehouse($data, $editor, $smarty)
{
    $updated = [];
    foreach ($data['order_keys'] as $order_key) {
        $order         = get_object('Order', $order_key);
        $order->editor = $editor;


        if ($delivery_key = $order->update_state('InWarehouse')) {
            $updated[] = $delivery_key;
        }
    }

    $number_updated = count($updated);

    if ($number_updated > 0) {
        $html = '<i class="fa fa-check success  padding_left_5"></i>';
    } else {
        $html = '';
    }

    $html .= ' '.number($number_updated).' '.ngettext('order send to warehouse', 'orders send to warehouse', $number_updated);


    if ($number_updated > 0) {
        $html .= ' <div style="float:right;margin-left:20px" class="orders_operations ">';
        $html .= '<span class="orders_picking_sheets orders_op orders_pdf " data-type="orders_picking_sheets"> '._('Picking sheets');
        $html .= ' <i data-source="fix" data-ids="'.join(',', $updated).'"   data-type="picking_aid"  class="fal fa-fw fa-clipboard-list-check button"></i>';
        $html .= ' <i data-source="fix"  data-ids="'.join(',', $updated).'" data-type="picking_aid_with_labels"  class="fal fa-fw fa-pager button"></i>';
        $html .= '</span>';
        $html .= $smarty->fetch('control_order_operation_progress_bar.tpl');
        $html .= '</div>';
    }

    $response = array(
        'state' => 200,
        'msg'   => $html
    );
    echo json_encode($response);
}

/**
 * @param $data
 * @param $db \PDO
 */
function cancel_purchase_order_submitted_item($data, $db)
{
    $sql  =
        "select `Purchase Order Transaction Operator Key`,`Purchase Order Submitted SKOs Per Carton`,`Purchase Order Submitted Units Per SKO`,`Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Transaction State`,`Purchase Order Submitted Units` ,`Supplier Delivery Units` ,`Supplier Delivery Transaction State` ,`Purchase Order Submitted Cancelled Units`, `Purchase Order Transaction Part SKU` ,`Supplier Part Key` from `Purchase Order Transaction Fact` where `Purchase Order Transaction Fact Key`=?";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($data['transaction_key'])
    );
    if ($row = $stmt->fetch()) {
        // print_r($row);

        $units_per_carton = $row['Purchase Order Submitted Units Per SKO'] * $row['Purchase Order Submitted SKOs Per Carton'];
        $units_per_sko    = $row['Purchase Order Submitted Units Per SKO'];

        if ($row['Purchase Order Transaction State'] != 'Submitted') {
            $response = array(
                'state' => 400,
                'msg'   => "Can't cancel transaction"
            );
            echo json_encode($response);
            exit;
        }


        /**
         * @var $purchase_order \PurchaseOrder
         */
        $purchase_order = get_object('Purchase_Order', $row['Purchase Order Key']);
        if ($purchase_order->id) {
            $old_state = $purchase_order->get('Purchase Order State');

            $unit_qty = ($row['Supplier Delivery Units'] == '' ? 0 : $row['Supplier Delivery Units']);

            $to_cancel_qty = $row['Purchase Order Submitted Units'] - $unit_qty;


            $date = gmdate('Y-m-d H:i:s');

            $supplier_part = get_object('SupplierPart', $row['Supplier Part Key']);
            $amount        = $unit_qty * $supplier_part->get('Supplier Part Unit Cost');
            $extra_amount  = $unit_qty * $supplier_part->get('Supplier Part Unit Extra Cost');

            if (is_numeric($supplier_part->get('Supplier Part Carton CBM'))) {
                $cbm = $unit_qty * $supplier_part->get('Supplier Part Carton CBM') / $supplier_part->get('Supplier Part Packages Per Carton') / $supplier_part->part->get('Part Units Per Package');
            } else {
                if ($unit_qty == 0) {
                    $cbm = 0;
                } else {
                    $cbm = null;
                }
            }


            if (is_numeric($supplier_part->part->get('Part Package Weight'))) {
                $weight = $unit_qty * $supplier_part->part->get('Part Package Weight') / $supplier_part->part->get('Part Units Per Package');
            } else {
                if ($unit_qty == 0) {
                    $weight = 0;
                } else {
                    $weight = null;
                }
            }

            $sql = "update `Purchase Order Transaction Fact` set `Purchase Order Submitted Cancelled Units`=?,`Purchase Order Last Updated Date`=?,`Purchase Order Net Amount`=? ,
                        `Purchase Order Extra Cost Amount`=? ,
                        `Purchase Order CBM`=?,
                        `Purchase Order Weight`=?  where  `Purchase Order Transaction Fact Key`=? ";


            $stmt = $db->prepare($sql);


            $stmt->execute(
                array(
                    $to_cancel_qty,
                    $date,
                    $amount,
                    $extra_amount,
                    $cbm,
                    $weight,
                    $row['Purchase Order Transaction Fact Key']
                )
            );


            if ($row['Supplier Delivery Units'] == 0) {
                $sql  = "update `Purchase Order Transaction Fact` set `Purchase Order Transaction State`=?  where  `Purchase Order Transaction Fact Key`=? ";
                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array(
                        'Cancelled',
                        $row['Purchase Order Transaction Fact Key']
                    )
                );
            }


            if ($to_cancel_qty > 0) {
                $ordered_units   = '<span class="discreet strikethrough">'.number($to_cancel_qty).'</span>';
                $ordered_skos    = '<span class="discreet strikethrough">'.number($to_cancel_qty / $units_per_sko, 3).'</span>';
                $ordered_cartons = '<span class="discreet strikethrough">'.number($to_cancel_qty / $units_per_carton, 3).'</span>';

                if ($unit_qty > 0) {
                    $ordered_units   = ' '.number($unit_qty);
                    $ordered_skos    = ' '.number($unit_qty / $units_per_sko, 3);
                    $ordered_cartons = ' '.number($unit_qty / $units_per_carton, 3);
                }
            } else {
                $ordered_units   = number($unit_qty);
                $ordered_skos    = number($unit_qty / $units_per_sko, 3);
                $ordered_cartons = number($unit_qty / $units_per_carton, 3);
            }


            $purchase_order->update_purchase_order_item_state($row['Purchase Order Transaction Fact Key']);
            $purchase_order->update_totals();

            if ($old_state != $purchase_order->get('Purchase Order State') and $purchase_order->get('Purchase Order State') == 'Cancelled') {
                $purchase_order->fast_update(
                    array(
                        'Purchase Order Locked'                   => 'Yes',
                        'Purchase Order Cancelled Date'           => gmdate('Y-m-d H:i:s'),
                        'Purchase Order Estimated Receiving Date' => '',
                    )
                );
                $purchase_order->update_purchase_order_date();

                $history_data = array(
                    'History Abstract' => _('Purchase order cancelled'),
                    'History Details'  => '',
                    'Action'           => 'edited'
                );
                $purchase_order->add_subject_history(
                    $history_data,
                    true,
                    'No',
                    'Changes',
                    $purchase_order->get_object_name(),
                    $purchase_order->id
                );
            }

            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'          => 'update_operative_stats',
                    'operative_key' => $row['Purchase Order Transaction Operator Key'],
                ),
                DNS_ACCOUNT_CODE,
                $db
            );

            $response = array(
                'state'                => 200,
                'ordered_units'        => $ordered_units,
                'ordered_skos'         => $ordered_skos,
                'ordered_cartons'      => $ordered_cartons,
                'purchase_order_state' => $purchase_order->get('Purchase Order State'),

                'update_metadata' => array(
                    'class_html' => array(
                        'transaction_state_'.$row['Purchase Order Transaction Fact Key'] => _('Cancelled'),

                        'Purchase_Order_Total_Amount'                      => $purchase_order->get('Total Amount'),
                        'Purchase_Order_Total_Amount_Account_Currency'     => $purchase_order->get('Total Amount Account Currency'),
                        'Purchase_Order_Items_Net_Amount'                  => $purchase_order->get('Items Net Amount'),
                        'Purchase_Order_Items_Net_Amount_Account_Currency' => $purchase_order->get('Items Net Amount Account Currency'),
                        'Purchase_Order_AC_Total_Amount'                   => $purchase_order->get('AC Total Amount'),
                        'Purchase_Order_AC_Extra_Costs_Amount'             => $purchase_order->get('AC Extra Costs Amount'),
                        'Purchase_Order_AC_Subtotal_Amount'                => $purchase_order->get('AC Subtotal Amount'),
                        'Purchase_Order_Weight'                            => $purchase_order->get('Weight'),
                        'Purchase_Order_CBM'                               => $purchase_order->get('CBM'),
                        'Purchase_Order_Number_Items'                      => $purchase_order->get('Number Items'),
                        'Purchase_Order_Ordered_Number_Items'              => $purchase_order->get('Ordered Number Items'),
                        'Purchase_Order_State'                             => $purchase_order->get('State'),

                    )
                )
            );
            echo json_encode($response);
            exit;
        } else {
            $response = array(
                'state' => 400,
                'msg'   => 'PO not found'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'transaction not found'
        );
        echo json_encode($response);
        exit;
    }
}


function clone_order($data, $editor)
{
    /**
     * @var $order        Order
     * @var $target_order Order
     */
    $order    = get_object('Order', $data['order_key']);
    $customer = get_object('Customer', $order->get('Order Customer Key'));

    $target_order_key = $customer->get_order_in_process_key();

    if ($target_order_key) {
        $target_order = get_object('Order', $target_order_key);
    }

    if (!(isset($target_order) and $target_order->id)) {
        $target_order = $customer->create_order('{}');
    }
    $target_order->editor = $editor;
    $target_items_data    = array();
    foreach ($target_order->get_items() as $_item) {
        $target_items_data[$_item['product_id']] = $_item['qty'];
    }


    $items = $order->get_items();

    foreach ($items as $item) {
        if ($item['webpage_state'] == 'Online') {
            if (array_key_exists($item['product_id'], $target_items_data) and $target_items_data[$item['product_id']] <= $item['qty']) {
                $skip = true;
            } else {
                $skip = false;
            }


            if ($item['qty'] > 0 and !$skip) {
                $quantity = $item['qty'];


                $dispatching_state = 'In Process';


                $payment_state = 'Waiting Payment';


                $product = get_object('Product', $item['product_id']);
                $data    = array(
                    'date'                      => gmdate('Y-m-d H:i:s'),
                    'item_historic_key'         => $product->get('Product Current Key'),
                    'item_key'                  => $product->id,
                    'Metadata'                  => '',
                    'qty'                       => $quantity,
                    'Current Dispatching State' => $dispatching_state,
                    'Current Payment State'     => $payment_state
                );


                $target_order->update_item($data);
            }
        }
    }


    $response = array(
        'state'    => 200,
        'redirect' => sprintf('orders/%d/%d', $target_order->get('Order Store Key'), $target_order->id),

    );
    echo json_encode($response);
    exit;
}

/**
 * @param $data
 * @param $db \PDO
 */
function update_po_item_note($data, $db)
{
    $note = trim(strip_tags($data['note']));

    $sql = "update  `Purchase Order Transaction Fact` set `Note to Supplier`=? where `Purchase Order Transaction Fact Key`=?";
    $db->prepare($sql)->execute(
        array(
            $note,
            $data['purchase_order_transaction_fact_key']
        )
    );

    $response = array(
        'state' => 200,
        'note'  => $note
    );
    echo json_encode($response);
    exit;
}

/**
 * @param $db \PDO
 * @param $editor
 * @param $data
 */
function edit_item_in_order(PDO $db, $editor, $data)
{
    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;


    if ($data['parent'] == 'order') {
        /**
         * @var $parent \Order
         */
        $parent->skip_update_after_individual_transaction = false;

        if (in_array(
            $parent->data['Order State'], array(
                                            'InWarehouse',
                                            'PackedDone'
                                        )
        )) {
            $dispatching_state = 'Ready to Pick';
        } else {
            $dispatching_state = 'In Process';
        }

        $payment_state = 'Waiting Payment';

        $data['Current Dispatching State'] = $dispatching_state;
        $data['Current Payment State']     = $payment_state;
        $data['Metadata']                  = '';
    }


    $transaction_data = $parent->update_item($data);


    $discounts_data = array();

    if ($data['parent'] == 'order') {
        $sql = sprintf(
            'SELECT `Product Number of Parts`, `Product Units Per Case`,`Order Quantity`,`Product Properties`,`Order Transaction Amount`,OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Order Currency Code`,OTF.`Order Transaction Fact Key`, `Deal Info` 
FROM `Order Transaction Fact` OTF left join  
    `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`) left join  
    `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)

WHERE OTF.`Order Key`=%s ',
            $parent->id
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                if (in_array(
                    $parent->get('Order State'), array(
                                                   'Cancelled',
                                                   'Approved',
                                                   'Dispatched',
                                               )
                )) {
                    $discounts_class = '';
                    $discounts_input = '';
                } else {
                    $discounts_class = 'button';
                    $discounts_input = sprintf(
                        '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                        $row['Order Transaction Fact Key'],
                        $row['Product ID'],
                        $row['Product Key'],
                        percentage($row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount'])
                    );
                }
                $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($row['Order Transaction Total Discount Amount'] == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                        $row['Order Transaction Total Discount Amount'],
                        $row['Order Transaction Gross Amount']
                    ).'</span> <span class="'.($row['Order Transaction Total Discount Amount'] == 0 ? 'hide' : '').'">'.money($row['Order Transaction Total Discount Amount'], $row['Order Currency Code']).'</span></span>';


                $properties = json_decode($row['Product Properties'], true);

                if (empty($properties['packing_sko'])) {
                    $properties['packing_sko'] = '';
                }
                if (empty($properties['packing_carton'])) {
                    $properties['packing_carton'] = '';
                }
                if (empty($properties['packing_batch'])) {
                    $properties['packing_batch'] = '';
                }

                $packing = '';
                if ($row['Product Number of Parts'] <= 0) {
                    $packing .= '<span class="error">'._('No parts').'</span>';
                } elseif ($row['Product Number of Parts'] > 1) {
                    $packing .= '<span class="dicreet italic">'._('Multi part').'</span>';
                } else {
                    if ($row['Product Units Per Case'] != 1 and $row['Product Units Per Case'] != $properties['packing_sko']) {
                        $packing .= '<i class="fa-fw fal fa-stop-circle" title="'._('Units').'"></i>';


                        $packing .= ' '.number($row['Order Quantity'] * $row['Product Units Per Case']);
                    }
                    if ($properties['packing_sko'] > 0) {
                        $packing .= '<i class="padding_left_5 fa-fw fal fa-box" title="'._('SKOs').'"></i> '.number($row['Order Quantity'] * $properties['packing_sko']);
                    }
                    if ($properties['packing_carton'] > 0 and $properties['packing_carton'] != $properties['packing_sko']) {
                        $packing .= '<i class="padding_left_5 fa-fw fal fa-pallet" title="'._('Cartons').'"></i> '.float2rat($row['Order Quantity'] * $properties['packing_carton']);
                    }

                    if ($properties['packing_batch'] > 0) {
                        $packing .= '<i class="padding_left_5 fa-fw fal fa-fill-drip" title="'._('Batch').'"></i> '.number($row['Order Quantity'] * $properties['packing_batch']);
                    }
                }


                if (isset($data['tab']) and $data['tab'] == 'order.all_products') {
                    $discounts_data[$row['Product ID']] = array(
                        'deal_info' => $row['Deal Info'],
                        'discounts' => $discounts,
                        'packing'   => $packing,
                        'item_net'  => money($row['Order Transaction Amount'], $row['Order Currency Code'])
                    );
                } else {
                    $discounts_data[$row['Order Transaction Fact Key']] = array(
                        'deal_info' => $row['Deal Info'],
                        'discounts' => $discounts,
                        'packing'   => $packing,
                        'item_net'  => money($row['Order Transaction Amount'], $row['Order Currency Code'])
                    );
                }
            }
        }

        $update_metadata                 = $parent->get_update_metadata();
        $update_metadata['deleted_otfs'] = $parent->deleted_otfs;
        $update_metadata['new_otfs']     = $parent->new_otfs;
    } else {
        $update_metadata = $parent->get_update_metadata();
    }


    if ($parent->error) {
        $response = array(
            'state' => 400,
            'msg'   => $parent->msg
        );
    } else {
        $response = array(
            'state'            => 200,
            'transaction_data' => $transaction_data,
            'metadata'         => $update_metadata,
            'discounts_data'   => $discounts_data
        );
    }
    echo json_encode($response);
}


function set_shipping_value($data, $editor)
{
    /** @var Order $order */
    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;

    $order->update_shipping_amount($data['amount']);


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);
}

function set_shipping_as_auto($data, $editor)
{
    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;

    $order->use_calculated_shipping();


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);
}


function set_hanging_charges_value($data, $editor)
{
    /** @var Order $order */
    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;


    $order->update_hanging_charges_amount($data['amount']);


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);
}

function set_hanging_charges_as_auto($data, $editor)
{
    /** @var Order $order */
    $order         = get_object('order', $data['order_key']);
    $order->editor = $editor;

    $order->use_calculated_items_charges();


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        //  'operations'    => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);
}


function set_order_handler($type, $data, $editor, $smarty, $db)
{
    $dn         = get_object('delivery_note', $data['delivery_note_key']);
    $dn->editor = $editor;

    $staff = get_object('staff', $data['staff_key']);

    if ($staff->id) {
        $dn->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => $staff->id,
                'Delivery Note Assigned '.$type.' Alias' => $staff->get('Alias')
            )
        );
        $response = array(
            'state'       => 200,
            'staff_alias' => $staff->get('Alias'),
            'staff_key'   => $staff->id
        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'Staff not found'
        );
    }


    echo json_encode($response);
}

function set_state($data, $editor, $smarty, $db)
{
    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;


    $object->set_state($data['value']);


    $response = array(
        'state'       => 200,
        'metadata'    => $object->get_update_metadata(),
        'state_index' => $object->get('State Index')
    );

    echo json_encode($response);
}

function new_payment($data, $editor, $db, $account, $user)
{
    include_once 'utils/currency_functions.php';


    $payback_refund = false;

    if ($data['parent'] == 'invoice' or $data['parent'] == 'refund') {
        $invoice = get_object($data['parent'], $data['parent_key']);

        if ($invoice->get('Invoice Type') == 'Refund') {
            $payback_refund = true;
            $data['amount'] = -1 * $data['amount'];
        }

        $order = get_object('Order', $invoice->get('Invoice Order Key'));
    } else {
        $order = get_object('Order', $data['parent_key']);
    }


    $order->editor = $editor;

    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;

    $date     = gmdate('Y-m-d H:i:s');
    $exchange = currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code'));

    $payment_data = array(
        'Payment Store Key'                   => $order->get('Store Key'),
        'Payment Customer Key'                => $order->get('Customer Key'),
        'Payment Transaction Amount'          => $data['amount'],
        'Payment Currency Code'               => $order->get('Currency Code'),
        'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
        'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
        'Payment Sender Email'                => $order->get('Email'),
        'Payment Sender Card Type'            => '',
        'Payment Created Date'                => $date,
        'Payment Order Key'                   => $order->id,
        'Payment Completed Date'              => $date,
        'Payment Last Updated Date'           => $date,
        'Payment Transaction Status'          => 'Completed',
        'Payment Transaction ID'              => $data['reference'],
        'Payment Method'                      => $data['payment_method'],
        'Payment Location'                    => 'Order',
        'Payment Metadata'                    => '',
        'Payment Submit Type'                 => 'Manual',
        'Payment Currency Exchange Rate'      => $exchange,
        'Payment User Key'                    => $user->id,
        'Payment Type'                        => ($payback_refund ? 'Refund' : 'Payment')


    );

    $customer = get_object('Customer', $order->get('Customer Key'));


    if ($payment_account->get('Payment Account Block') == 'Accounts' and !$payback_refund) {
        if ($customer->get('Customer Account Balance') < $data['amount']) {
            $response = array(
                'state' => 400,
                'msg'   => _('Payment amount exceeds customer account balance')
            );
            echo json_encode($response);
            exit;
        }
    }


    $payment = $payment_account->create_payment($payment_data);

    if ($payment_account->get('Payment Account Block') == 'Accounts') {
        $sql = "INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES (?,?,?,?,?,?)";


        $db->prepare($sql)->execute(
            array(
                $date,
                round(-$data['amount'], 2),
                $order->get('Currency Code'),
                $exchange,
                $order->get('Customer Key'),
                $payment->id

            )
        );


        $reference = $db->lastInsertId();

        $payment->fast_update(array('Payment Transaction ID' => sprintf('%05d', $reference)));


        $customer->update_account_balance();
        $customer->update_credit_account_running_balances();
    }


    $order->add_payment($payment);
    $order->update_totals();

    if (!$payback_refund) {
        $invoice = get_object('invoice', $order->get('Order Invoice Key'));
    }

    if ($invoice->id) {
        $invoice->add_payment($payment);
    }


    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects', 'Completed') as $payment) {
        if ($payment->payment_account->get('Payment Account Block') == 'Accounts') {
            $_code = _('Credit');
        } else {
            $_code = $payment->get('Payment Account Code');
        }

        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>',
            '/order/'.$order->id.'/payment/'.$payment->id,
            $_code,
            $payment->get('Transaction Amount')

        );
    }


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),
            'To_Pay_Amount_Absolute'        => $order->get('To Pay Amount Absolute'),

            'Order_Number_items' => $order->get('Number Items')

        ),
        'operations'  => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),
        'payments'    => $order->get('Order Payments Amount'),

        'payments_xhtml' => $payments_xhtml
    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);
}


function edit_item_discount($account, $db, $user, $editor, $data, $smarty)
{
    /** @var Order $parent */
    $parent         = get_object('Order', $data['parent_key']);
    $parent->editor = $editor;


    if ($data['field'] == 'Percentage') {
        $percentage       = $data['value'];
        $transaction_data = $parent->update_transaction_discount_percentage($data['transaction_key'], $percentage);
    } else {
        $amount           = $data['value'];
        $transaction_data = $parent->update_transaction_discount_amount($data['transaction_key'], $amount);
    }

    if ($parent->error) {
        $response = array(
            'state' => 400,
            'msg'   => $parent->msg
        );
    } else {
        $response = array(
            'state'            => 200,
            'transaction_data' => $transaction_data,
            'metadata'         => $parent->get_update_metadata()
        );
    }
    echo json_encode($response);
}


function refund_payment($data, $editor, $smarty, $db, $account, $user)
{
    include_once 'utils/currency_functions.php';


    $payment         = get_object('payment', $data['key']);
    $payment->editor = $editor;

    $payment_account         = get_object('Payment_Account', $payment->get('Payment Account Key'));
    $payment_account->editor = $editor;

    $order = get_object('Order', $payment->get('Payment Order Key'));
    $metadata='';

    switch ($data['operation']) {
        case 'Refund':

            switch ($data['submit_type']) {
                case 'Manual':


                    $reference = $data['reference'];

                    break;
                case 'Online':

                    switch ($payment_account->get('Payment Account Block')) {
                        case 'Checkout':


                            $secretKey=$payment_account->get('Payment Account Password');


                            $api = CheckoutSdk::builder()->staticKeys()

                                ->secretKey($secretKey)
                                ->environment(ENVIRONMENT == 'DEVEL' ?Environment::sandbox(): Environment::production())
                                ->build();


                            $request = new RefundRequest();
                            $request->amount = (integer)(round($data['amount'], 2) * 100);

                            try {
                                // or, refundPayment("payment_id") for a full refund
                                $response = $api->getPaymentsClient()->refundPayment($payment->data['Payment Transaction ID'], $request);
                            } catch (CheckoutApiException $e) {
                                // API error

                                $error_details='Error: ';

                                $error_details .= $e->error_details;
                                $http_status_code = isset($e->http_metadata) ? $e->http_metadata->getStatusCode() : null;


                                switch ($http_status_code) {

                                    case 403:
                                        $error_details .= ' Refund not allowed';
                                        break;
                                    case 404:
                                        $error_details .= 'Payment not found';
                                        break;
                                }

                                $error_details=trim($error_details);
                                $response = array(
                                    'state' => 400,
                                    'msg'   => $error_details." ($http_status_code)"
                                );
                                echo json_encode($response);
                                exit;


                            } catch (CheckoutAuthorizationException $e) {
                                $response = array(
                                    'state' => 400,
                                    'msg'   => $e->getMessage()
                                );
                                echo json_encode($response);
                                exit;
                            }

                            if ($response['http_metadata']->getStatusCode()==202) {
                                $reference = $response['action_id'];
                                $metadata  = json_encode($response);
                            }else{
                                $msg = 'Error refund could not been processed';
                                switch ($response['http_metadata']->getStatusCode()) {
                                    case 422:
                                        $msg = 'Error: '.$response['error_type'];
                                        break;
                                    case 403:
                                        $msg = 'Refund not allowed';
                                        break;
                                    case 404:
                                        $msg = 'Payment not found';
                                        break;
                                }


                                $response = array(
                                    'state' => 400,
                                    'msg'   => $msg
                                );
                                echo json_encode($response);
                                exit;
                            }



                            /*

                            // Partial capture: $payment->amount = 999;
                            $_payment         = new Checkout\Models\Payments\Refund($payment->data['Payment Transaction ID']);
                            $_payment->amount = (integer)(round($data['amount'], 2) * 100);

                            // print_r($_payment->amount );
                            // exit;

                            try {
                                $_refund = $checkout->payments()->refund($_payment);
                            } catch (Exception $e) {
                                $response = array(
                                    'state' => 400,
                                    'msg'   => $e->getMessage()
                                );
                                echo json_encode($response);
                                exit;
                            }
*/



/*

                            if ($_refund->http_code == 202) {
                                $reference = $_refund->action_id;
                                $metadata  = json_encode($_refund);
                            } else {
                                $msg = 'Error refund could not been processed';
                                switch ($_refund->http_code) {
                                    case 422:
                                        $msg = 'Error: '.$_refund->error_type;
                                        break;
                                    case 403:
                                        $msg = 'Refund not allowed';
                                        break;
                                    case 404:
                                        $msg = 'Payment not found';
                                        break;
                                }


                                $response = array(
                                    'state' => 400,
                                    'msg'   => $msg
                                );
                                echo json_encode($response);
                                exit;
                            }

*/
                            break;
                        case 'BTree':
                        case 'BTreePaypal':

                            $metadata = '';

                            $gateway = new Braintree_Gateway(
                                [
                                    'environment' => BRAINTREE_ENV,
                                    'merchantId'  => $payment_account->get('Payment Account ID'),
                                    'publicKey'   => $payment_account->get('Payment Account Login'),
                                    'privateKey'  => $payment_account->get('Payment Account Password')
                                ]
                            );

                            $transaction = $gateway->transaction()->find($payment->data['Payment Transaction ID']);

                            //print_r($transaction);

                            switch ($transaction->status) {
                                case 'settled';
                                case 'settling';


                                    $result = $gateway->transaction()->refund($payment->data['Payment Transaction ID'], $data['amount']);
                                    if ($result->success) {
                                        $reference = $result->transaction->id;
                                    } else {
                                        if (isset($result->transaction->processorSettlementResponseText)) {
                                            $msg = $result->transaction->processorSettlementResponseText.' ('.$result->transaction->processorSettlementResponseCode.')';
                                        } else {
                                            $msg = $result->message;
                                        }


                                        $response = array(
                                            'state' => 400,
                                            'msg'   => $msg
                                        );
                                        echo json_encode($response);
                                        exit;
                                    }

                                    break;


                                case 'authorized':
                                case 'submitted_for_settlement':

                                    if ($data['amount'] != $payment->get('Payment Transaction Amount')) {
                                        $response = array(
                                            'state' => 400,
                                            'msg'   => sprintf(_("Transaction still not settled, can't do partial refunds"))
                                        );
                                        echo json_encode($response);
                                        exit;
                                    }

                                    $result = $gateway->transaction()->void($payment->data['Payment Transaction ID']);
                                    if ($result->success) {
                                        $reference = $result->transaction->id;
                                    } else {
                                        if (isset($result->transaction->processorSettlementResponseText)) {
                                            $msg = $result->transaction->processorSettlementResponseText.' ('.$result->transaction->processorSettlementResponseCode.')';
                                        } else {
                                            $msg = $result->message;
                                        }


                                        $response = array(
                                            'state' => 400,
                                            'msg'   => $msg
                                        );
                                        echo json_encode($response);
                                        exit;
                                    }


                                    break;
                                default:
                                    $response = array(
                                        'state' => 400,
                                        'msg'   => sprintf(_("Can't refund transaction with status %s"), $transaction->status)
                                    );

                                    echo json_encode($response);
                                    exit;
                            }


                            break;
                        default:


                            $response = array(
                                'state' => 400,
                                'msg'   => 'Payment account cant make online refunds'
                            );
                            echo json_encode($response);
                            exit;


                            break;
                    }


                    break;
                default:
                    $response = array(
                        'state' => 400,
                        'msg'   => 'unknown refund method '.$data['submit_type']
                    );
                    echo json_encode($response);
                    exit;
            }
            $store = get_object('Store', $order->get('Store Key'));


            $payment_data = array(
                'Payment Store Key'   => $order->get('Store Key'),
                'Payment Website Key' => $store->get('Store Website Key'),

                'Payment Customer Key'                   => $order->get('Customer Key'),
                'Payment Transaction Amount'             => -$data['amount'],
                'Payment Currency Code'                  => $order->get('Currency Code'),
                'Payment Sender'                         => $order->get('Order Invoice Address Recipient'),
                'Payment Sender Country 2 Alpha Code'    => $order->get('Order Invoice Address Country 2 Alpha Code'),
                'Payment Sender Email'                   => $order->get('Email'),
                'Payment Sender Card Type'               => '',
                'Payment Created Date'                   => gmdate('Y-m-d H:i:s'),
                'Payment Order Key'                      => $order->id,
                'Payment Completed Date'                 => gmdate('Y-m-d H:i:s'),
                'Payment Last Updated Date'              => gmdate('Y-m-d H:i:s'),
                'Payment Transaction Status'             => 'Completed',
                'Payment Transaction ID'                 => $reference,
                'Payment Method'                         => $payment->get('Payment Method'),
                'Payment Location'                       => 'Order',
                'Payment Metadata'                       => $metadata,
                'Payment Submit Type'                    => ($data['submit_type'] == 'Online' ? 'EPS' : 'Manual'),
                'Payment Currency Exchange Rate'         => currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code')),
                'Payment Related Payment Key'            => $payment->id,
                'Payment Related Payment Transaction ID' => $payment->get('Payment Transaction ID'),
                'Payment User Key'                       => $user->id


            );

            if ($data['parent'] == 'Invoice') {
                $payment_data['Payment Invoice Key'] = $data['parent_key'];
                $payment_data['Payment Type']        = 'Refund';
            } else {
                $payment_data['Payment Type'] = 'Return';
            }


            $refund = $payment_account->create_payment($payment_data);


            $payment->fast_update(array('Payment Transaction Amount Refunded' => $payment->get('Payment Transaction Amount Refunded') + $data['amount']));


            break;

        case 'Credit':

            $date     = gmdate('Y-m-d H:i:s');
            $customer = get_object('Customer', $order->get('Customer Key'));

            $exchange = currency_conversion($db, $order->get('Currency Code'), $account->get('Currency Code'));

            $store = get_object('Store', $order->get('Store Key'));

            $reference = '';

            $payment_data = array(
                'Payment Store Key'                      => $order->get('Store Key'),
                'Payment Website Key'                    => $store->get('Store Website Key'),
                'Payment Customer Key'                   => $order->get('Customer Key'),
                'Payment Transaction Amount'             => -$data['amount'],
                'Payment Currency Code'                  => $order->get('Currency Code'),
                'Payment Sender'                         => $order->get('Order Invoice Address Recipient'),
                'Payment Sender Country 2 Alpha Code'    => $order->get('Order Invoice Address Country 2 Alpha Code'),
                'Payment Sender Email'                   => $order->get('Email'),
                'Payment Sender Card Type'               => '',
                'Payment Created Date'                   => $date,
                'Payment Order Key'                      => $order->id,
                'Payment Completed Date'                 => $date,
                'Payment Last Updated Date'              => $date,
                'Payment Transaction Status'             => 'Completed',
                'Payment Transaction ID'                 => $reference,
                'Payment Method'                         => 'Account',
                'Payment Location'                       => 'Order',
                'Payment Metadata'                       => '',
                'Payment Submit Type'                    => ($data['submit_type'] == 'Online' ? 'EPS' : 'Manual'),
                'Payment Currency Exchange Rate'         => $exchange,
                'Payment Related Payment Key'            => $payment->id,
                'Payment Related Payment Transaction ID' => $payment->get('Payment Transaction ID'),
                'Payment User Key'                       => $user->id


            );


            if ($data['parent'] == 'Invoice') {
                $payment_data['Payment Invoice Key'] = $data['parent_key'];
                $payment_data['Payment Type']        = 'Refund';
            } else {
                $payment_data['Payment Type'] = 'Return';
            }


            $refund = $payment_account->create_payment($payment_data);

            $sql = "INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES (?,?,?,?,?,?)";

            $db->prepare($sql)->execute(
                array(
                    $date,
                    round($data['amount'], 2),
                    $order->get('Currency Code'),
                    $exchange,
                    $order->get('Customer Key'),
                    $refund->id
                )
            );


            $reference = $db->lastInsertId();

            $refund->fast_update(array('Payment Transaction ID' => sprintf('%05d', $reference)));


            $customer->update_account_balance();
            $customer->update_credit_account_running_balances();

            $payment->fast_update(array('Payment Transaction Amount Credited' => $payment->get('Payment Transaction Amount Credited') + $data['amount']));


            break;
        default:

            $response = array(
                'state' => 400,
                'msg'   => 'unknown refund operation '.$data['operation']
            );
            echo json_encode($response);
            exit;
            break;
    }


    $order->add_payment($refund);
    $order->update_totals();


    if ($data['payback_refund']) {
        $invoice = get_object('invoice', $data['payback_refund']);
    } else {
        $invoice = get_object('invoice', $order->get('Invoice Order Key'));
    }

    if ($invoice->id) {
        $invoice->add_payment($refund);
    }


    $operations = array();


    $payments_xhtml = '';

    foreach ($order->get_payments('objects', 'Completed') as $payment) {
        $payments_xhtml .= sprintf(
            '<div class="payment node"><span class="node_label link" onClick="change_view(\'%s\')" >%s</span><span class="node_amount" >%s</span></div>',
            '/order/'.$order->id.'/payment/'.$payment->id,
            $payment->get('Payment Account Code'),
            $payment->get('Transaction Amount')

        );
    }


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Shipping_Net_Amount'           => $order->get('Shipping Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),


            'Order_Number_items' => $order->get('Number Items')

        ),
        'operations'  => $operations,
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'shipping'    => $order->get('Order Shipping Net Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),
        'payments'    => $order->get('Order Payments Amount'),

        'payments_xhtml' => $payments_xhtml
    );


    $response = array(
        'state'    => 200,
        'metadata' => $metadata
    );
    echo json_encode($response);
}


function create_refund($data, $editor)
{
    $object         = get_object('order', $data['key']);
    $object->editor = $editor;


    $refund = $object->create_refund(gmdate('Y-m-d H:i:s'), $data['transactions']);


    if ($refund->id) {
        $response = array(
            'state'      => 200,
            'refund_key' => $refund->id,
            'order_key'  => $refund->get('Invoice Order Key'),
            'store_key'  => $refund->get('Store Key')

        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => $object->msg
        );
    }


    echo json_encode($response);
}


function create_refund_tax_only($data, $editor)
{
    $object         = get_object('order', $data['key']);
    $object->editor = $editor;


    $refund = $object->create_refund(gmdate('Y-m-d H:i:s'), $data['transactions'], true);


    if ($refund->id) {
        $response = array(
            'state'      => 200,
            'refund_key' => $refund->id,
            'order_key'  => $refund->get('Invoice Order Key'),
            'store_key'  => $refund->get('Store Key')

        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => $object->msg
        );
    }


    echo json_encode($response);
}


function create_replacement($data, $editor)
{
    /**
     * @var  $order Order
     */
    $order         = get_object('order', $data['key']);
    $order->editor = $editor;


    $replacement = $order->create_replacement($data['transactions']);


    if ($replacement->id) {
        $response = array(
            'state'           => 200,
            'replacement_key' => $replacement->id,
            'store_key'       => $replacement->get('Store Key')

        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => $order->msg
        );
    }


    echo json_encode($response);
}


function create_return($data, $editor)
{
    $object         = get_object('order', $data['key']);
    $object->editor = $editor;


    $return = $object->create_return($data['transactions']);


    if ($return->id) {
        $response = array(
            'state'      => 200,
            'return_key' => $return->id,
            'store_key'  => $object->get('Store Key'),
            'order_key'  => $object->id
        );
    } else {
        $response = array(
            'state' => 400,
            'msg'   => $object->msg
        );
    }


    echo json_encode($response);
}


function set_po_transaction_amount_to_current_cost($data, $editor, $account, $db)
{
    $sql = sprintf(
        'select SPD.`Supplier Part Historic Key`,`Supplier Delivery Units`,`Supplier Delivery Key`,`Purchase Order Submitted Unit Extra Cost Percentage`,`Purchase Order Submitted Unit Cost`,`Supplier Part Unit Extra Cost Percentage`,`Purchase Order Transaction State`,`Purchase Order Key`,`Purchase Order Transaction Fact Key`,`Supplier Part Unit Cost`,`Purchase Order Submitted Units`,`Supplier Part Currency Code` from `Purchase Order Transaction Fact` POTF left join `Supplier Part Dimension` SPD  on (POTF.`Supplier Part Key`=SPD.`Supplier Part Key`) where `Purchase Order Transaction Fact Key`=%d ',
        $data['transaction_key']
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            // $supplier_part = get_object('Supplier_Part', $row['Supplier Part Key']);


            $purchase_order = get_object('Purchase Order', $row['Purchase Order Key']);


            $net_amount = $row['Supplier Part Unit Cost'] * $row['Purchase Order Submitted Units'];


            $unit_cost       = $row['Purchase Order Submitted Unit Cost'];
            $extra_unit_cost = $row['Supplier Part Unit Extra Cost Percentage'];

            $supplier_part_historic_key = $row['Supplier Part Historic Key'];

            switch ($row['Purchase Order Transaction State']) {
                case 'Cancelled':
                    $response = array(
                        'state' => 400,
                        'msg'   => 'Purchase order cancelled'
                    );

                    echo json_encode($response);
                    exit;
                    break;
                default:

                    if ($data['type'] == 'cost') {
                        //todo $supplier_part_historic_key is not necessary tru can be other this can be a mess, $supplier_part_historic_keys should come from the UI

                        $unit_cost = $row['Supplier Part Unit Cost'];
                        $sql       = "update `Purchase Order Transaction Fact` set `Purchase Order Submitted Unit Cost`=? ,`Purchase Order Net Amount`=?, `Supplier Part Historic Key`=?  where `Purchase Order Transaction Fact Key`=?";


                        $db->prepare($sql)->execute(
                            array(
                                round($row['Supplier Part Unit Cost'], 4),
                                round($net_amount, 2),
                                $supplier_part_historic_key,
                                $row['Purchase Order Transaction Fact Key']
                            )
                        );
                    } elseif ($data['type'] == 'extra_cost') {
                        $extra_unit_cost = $row['Supplier Part Unit Extra Cost Percentage'];
                        $sql             = "update `Purchase Order Transaction Fact` set `Purchase Order Submitted Unit Extra Cost Percentage`=?  where `Purchase Order Transaction Fact Key`=?";

                        $db->prepare($sql)->execute(
                            array(
                                round($row['Supplier Part Unit Extra Cost Percentage'], 4),
                                $row['Purchase Order Transaction Fact Key']
                            )
                        );
                    }

                    if ($row['Supplier Delivery Key']) {
                        $delivery = get_object('Supplier Delivery', $row['Supplier Delivery Key']);

                        if ($data['type'] == 'cost') {
                            $sql = "update `Purchase Order Transaction Fact` set `Supplier Delivery Net Amount`=? where `Purchase Order Transaction Fact Key`=?";

                            $db->prepare($sql)->execute(
                                array(
                                    round($row['Supplier Part Unit Cost'] * $row['Supplier Delivery Units'], 4),
                                    $row['Purchase Order Transaction Fact Key']
                                )
                            );
                        }

                        $delivery->update_totals();
                    }


                    break;
            }

            $purchase_order->update_totals();


            $amount = money($net_amount, $purchase_order->get('Purchase Order Currency Code'));


            if ($row['Supplier Part Currency Code'] != $account->get('Account Currency')) {
                $amount .= ' <span >('.money($net_amount * $purchase_order->get('Purchase Order Currency Exchange'), $account->get('Account Currency')).')</span>';
            }

            if ($unit_cost != $row['Supplier Part Unit Cost']) {
                $amount .= '<div style="color:#ffc822" class="small"><i class="fa fa-exclamation-triangle attention"></i> <span class="warning">'._('Unit cost changed').' 
                <span title="'._('Submitted unit cost').'">'.money($row['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code')).'</span>  <i class="far fa-arrow-right"></i>
                <span class="strong" title="'._('Current unit cost').'">'.money($row['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code')).'</span> <i onclick="set_po_transaction_amount_to_current_cost(this,\'cost\','
                    .$row['Purchase Order Transaction Fact Key'].')" class="button fa fa-sync-alt" title="'._('Update item amount to current price').'"></i></div>';
            }


            if ($extra_unit_cost != $row['Supplier Part Unit Extra Cost Percentage']) {
                $amount .= '<div style="color:#ffc822" class="small"><i class="fa fa-exclamation-triangle attention"></i> <span class="warning">'._('Extra cost % changed').' 
                <span title="'._('Submitted extra cost %').'">'.percentage($row['Purchase Order Submitted Unit Extra Cost Percentage'], 1, 1).'</span>  <i class="far fa-arrow-right"></i>
                <span class="strong" title="'._('Current extra cost %').'">'.percentage($row['Supplier Part Unit Extra Cost Percentage'], 1, 1).'</span> <i onclick="set_po_transaction_amount_to_current_cost(this,\'extra_cost\','
                    .$row['Purchase Order Transaction Fact Key'].')" class="button fa fa-sync-alt" title="'._('Update item amount to current extra cost').'"></i></div>';
            }


            $update_metadata = array(
                'class_html' => array(
                    'Purchase_Order_Total_Amount'                      => $purchase_order->get('Total Amount'),
                    'Purchase_Order_Total_Amount_Account_Currency'     => $purchase_order->get('Total Amount Account Currency'),
                    'Purchase_Order_Items_Net_Amount'                  => $purchase_order->get('Items Net Amount'),
                    'Purchase_Order_Items_Net_Amount_Account_Currency' => $purchase_order->get('Items Net Amount Account Currency'),
                    'Purchase_Order_AC_Total_Amount'                   => $purchase_order->get('AC Total Amount'),
                    'Purchase_Order_AC_Extra_Costs_Amount'             => $purchase_order->get('AC Extra Costs Amount'),
                    'Purchase_Order_AC_Subtotal_Amount'                => $purchase_order->get('AC Subtotal Amount'),
                    'Purchase_Order_AC_Total_Amount'                   => $purchase_order->get('AC Total Amount'),


                ),
            );

            if (isset($delivery)) {
                $update_metadata['class_html']['Supplier_Delivery_Items_Amount']          = $delivery->get('Items Amount');
                $update_metadata['class_html']['Supplier_Delivery_Extra_Costs_Amount']    = $delivery->get('Extra Costs Amount');
                $update_metadata['class_html']['Supplier_Delivery_Total_Amount']          = $delivery->get('Total Amount');
                $update_metadata['class_html']['Supplier_Delivery_AC_Subtotal_Amount']    = $delivery->get('AC Subtotal Amount');
                $update_metadata['class_html']['Supplier_Delivery_AC_Extra_Costs_Amount'] = $delivery->get('AC Extra Costs Amount');
                $update_metadata['class_html']['Supplier_Delivery_AC_Total_Amount']       = $delivery->get('AC Total Amount');
            }
            // exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    $response = array(
        'state'           => 200,
        'update_metadata' => $update_metadata,
        'amount'          => $amount
    );

    echo json_encode($response);
    exit;
}


function get_input_delivery_note_packing_all_locations($data, $editor, $smarty, $db, $account, $user)
{
    include_once 'utils/order_handing_functions.php';

    $total_required       = 0;
    $total_picked         = 0;
    $locations            = '';
    $picked_offline_input = '';
    $date                 = gmdate('Y-m-d Hi:i:s');


    $itf_data = array();
    $sql      = sprintf(
        'SELECT PL.`Location Key` as pl_ok  ,ITF.`Location Key`, sum(`Required`+`Given`) AS required ,group_concat(`Inventory Transaction Key`) as itf_keys 
        FROM `Inventory Transaction Fact` ITF    LEFT JOIN  `Part Location Dimension` PL ON  (ITF.`Location Key`=PL.`Location Key` and ITF.`Part SKU`=PL.`Part SKU`)
        WHERE   `Delivery Note Key`=%d  and  ITF.`Part SKU`=%d  group by `Location Key` ',
        $data['metadata']['delivery_note_key'],
        $data['metadata']['part_sku']
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $itf_data[$row['Location Key']] = array(
                'required' => $row['required'],
                'picked'   => 0,
                'pending'  => $row['required'],
                'itf_keys' => $row['itf_keys'],
                'pl_ok'    => $row['pl_ok']
            );


            $total_required += $row['required'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $part = get_object('part', $data['metadata']['part_sku']);
    foreach ($part->get_locations('part_location_object', '', true) as $part_location) {
        $locations .= '<div style="height: 32px">'.get_delivery_note_fast_track_packing_item_location(

                'Yes',
                $total_required - $total_picked,
                $part_location->get('Quantity On Hand'),
                $date,
                $part_location->location->id,
                $part_location->location->get('Code'),
                $part_location->part->get('Part Current On Hand Stock'),
                1,
                $part_location->part->id,
                (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['itf_keys'] : ''),
                $data['metadata']['delivery_note_key']
            ).'</div>';

        $picked_offline_input .= '<div style="margin-bottom: 4px">'.get_delivery_note_fast_track_packing_input(
                'Yes',
                $total_required,
                $total_picked,
                (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['picked'] : 0),
                ($part_location->get('Can Pick') == 'Yes' ? (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['pending'] : 0) : 0),
                $part_location->get('Quantity On Hand'),

                (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['itf_keys'] : ''),
                $part_location->part->id,
                $part_location->part->get('Part Current On Hand Stock'),
                $part_location->location->id
            ).'</div>';
    }


    $response = array(
        'state'                => 200,
        'locations'            => $locations,
        'picked_offline_input' => $picked_offline_input
    );

    echo json_encode($response);
    exit;
}


function get_picked_offline_input_all_locations($data, $editor, $smarty, $db, $account, $user)
{
    include_once 'utils/order_handing_functions.php';

    $total_required       = 0;
    $total_picked         = 0;
    $locations            = '';
    $picked_offline_input = '';
    $date                 = gmdate('Y-m-d Hi:i:s');


    $itf_data = array();
    $sql      = sprintf(
        'SELECT `Location Key`,sum(`Picked`) as picked,  sum(`Required`+`Given`) AS required ,group_concat(`Inventory Transaction Key`) as itf_keys FROM `Inventory Transaction Fact` ITF   WHERE   `Delivery Note Key`=%d  and  ITF.`Part SKU`=%d  group by `Location Key` ',
        $data['metadata']['delivery_note_key'],
        $data['metadata']['part_sku']
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $itf_data[$row['Location Key']] = array(
                'required' => $row['required'],
                'picked'   => $row['picked'],
                'pending'  => $row['required'] - $row['picked'],
                'itf_keys' => $row['itf_keys']
            );


            $total_required += $row['required'];
            $total_picked   += $row['picked'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $part = get_object('part', $data['metadata']['part_sku']);
    foreach ($part->get_locations('part_location_object', '', true) as $part_location) {
        //     $pending, $quantity_on_location, $date_picked,
        // $location_key, $location_code,
        // $part_stock, $part_barcode,$part_distinct_locations, $part_sku,
        // $itf_key,$delivery_note_key ) {

        $locations .= '<div style="height: 32px">'.get_item_location(
                $total_required - $total_picked,
                $part_location->get('Quantity On Hand'),
                $date,
                $part_location->location->id,
                $part_location->location->get('Code'),
                $part_location->part->get('Part Current On Hand Stock'),
                $part_location->part->get('Part SKO Barcode'),
                1,
                $part_location->part->id,
                (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['itf_keys'] : ''),
                $data['metadata']['delivery_note_key']
            ).'</div>';
        //function get_picked_offline_input($total_required,$total_picked,$picked_in_location, $quantity_on_location, $itf_key, $part_sku,  $part_stock) {
        $picked_offline_input .= '<div style="margin-bottom: 4px">'.get_picked_offline_input(
                $total_required,
                $total_picked,
                (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['picked'] : 0),
                ($part_location->get('Can Pick') == 'Yes' ? (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['pending'] : 0) : 0),
                $part_location->get('Quantity On Hand'),

                (isset($itf_data[$part_location->location->id]) ? $itf_data[$part_location->location->id]['itf_keys'] : ''),
                $part_location->part->id,
                $part_location->part->get('Part Current On Hand Stock'),
                $part_location->location->id
            ).'</div>';
    }


    $response = array(
        'state'                => 200,
        'locations'            => $locations,
        'picked_offline_input' => $picked_offline_input
    );

    echo json_encode($response);
    exit;
}


function toggle_charge($data, $editor, $smarty, $db, $account, $user)
{
    $order = get_object($data['parent'], $data['parent_key']);
    if (!$order->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'Order not found',
        );

        echo json_encode($response);
        exit;
    }

    $charge = get_object('Charge', $data['charge_key']);
    if (!$charge->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'Charge not found',
        );

        echo json_encode($response);
        exit;
    }

    if ($charge->get('Store Key') != $order->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Charge not in same store as order',
        );

        echo json_encode($response);
        exit;
    }


    if ($data['operation'] == 'add_charge') {
        $transaction_data = $order->add_charge($charge);
    } else {
        $transaction_data = $order->remove_charge($charge);
    }


    $metadata = array(

        'class_html'  => array(
            'Order_State'                   => $order->get('State'),
            'Items_Net_Amount'              => $order->get('Items Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
            'Total_Net_Amount'              => $order->get('Total Net Amount'),
            'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
            'Total_Amount'                  => $order->get('Total Amount'),
            'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
            'To_Pay_Amount'                 => $order->get('To Pay Amount'),
            'Payments_Amount'               => $order->get('Payments Amount'),
            'Order_Number_items'            => $order->get('Number Items')

        ),
        'state_index' => $order->get('State Index'),
        'to_pay'      => $order->get('Order To Pay Amount'),
        'total'       => $order->get('Order Total Amount'),
        'charges'     => $order->get('Order Charges Net Amount'),


    );


    $response = array(
        'state'            => 200,
        'metadata'         => $metadata,
        'operation'        => $data['operation'],
        'transaction_data' => $transaction_data,
        'charge_scope'     => $charge->get('Charge Scope')
    );
    echo json_encode($response);
}

function toggle_deal_component_choose_by_customer($data, $editor, $smarty, $db, $account, $user)
{
    $sql = sprintf('select * from `Order Transaction Deal Bridge` where `Order Transaction Deal Key`=%d ', $data['order_transaction_deal_bridge_key']);
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Product ID'] == $data['product_id']) {
                $response = array(
                    'state' => 400,
                    'msg'   => 'nothing to change'
                );

                echo json_encode($response);
                exit;
            }


            $deal_component = get_object('DealComponent', $data['deal_component_key']);

            $allowance_data = json_decode($deal_component->get('Deal Component Allowance'), true);


            if (!array_key_exists($data['product_id'], $allowance_data['options'])) {
                $response = array(
                    'state' => 400,
                    'msg'   => 'product not in offer'
                );

                echo json_encode($response);
                exit;
            }
            $order = get_object('Order', $data['order_key']);

            $product = get_object('Product', $data['product_id']);


            $customer = get_object('Customer', $order->get('Order Customer Key'));
            $customer->fast_update_json_field('Customer Metadata', 'DC_'.$deal_component->id, $product->id);

            //  $sql=sprintf('update `Order Transaction Fact` set `Product ID`  ')


            $deal = get_object('Deal', $deal_component->get('Deal Component Deal Key'));


            $deal_info = sprintf(
                "%s: %s, %s",
                ($deal->get('Deal Name Label') == '' ? _('Offer') : $deal->get('Deal Name Label')),
                (!empty($deal->get('Deal Term Label')) ? $deal->get('Deal Term Label') : ''),
                $deal_component->get('Deal Component Allowance Label')

            );


            $deal_info .= ' <span class="highlight"><i class="fa fa-plus-square padding_left_10"></i> '.sprintf('%d %s', $allowance_data['qty'], $product->get('Code')).'</span>';


            $sql = sprintf(
                'update `Order Transaction Deal Bridge` set `Product ID`=%d,`Product Key`=%d,`Category Key`=%d,`Order Transaction Deal Metadata`=%s,`Deal Info`=%s where `Order Transaction Deal Key`=%d  ',
                $product->id,
                $product->get('Product Current Key'),
                $product->get('Product Family Category Key'),
                prepare_mysql('{"selected": "'.$product->id.'"}'),
                prepare_mysql($deal_info),
                $data['order_transaction_deal_bridge_key']
            );

            $db->exec($sql);


            global $_locale;


            if ($product->get('Product Availability State') == 'OnDemand') {
                $stock = _('On demand');
            } else {
                if (is_numeric($product->get('Product Availability'))) {
                    $stock = number($product->get('Product Availability'));
                } else {
                    $stock = '?';
                }
            }


            $units    = $product->get('Product Units Per Case');
            $name     = $product->get('Product Name');
            $price    = $product->get('Product Price');
            $currency = $product->get('Product Currency');


            $description = '';
            if ($units > 1) {
                $description = number($units).'x ';
            }
            $description .= ' '.$name;
            if ($price > 0) {
                $description .= ' ('.money($price, $currency, $_locale).')';
            }


            $description .= ' <span style="color:#777">['.$stock.']</span> '.$deal_info;


            $sql = "update `Order Transaction Fact` set `Product ID`=?,`Product Key`=?,`OTF Category Family Key`=?,`OTF Category Department Key`=?  where `Order Transaction Fact Key`=?";

            $db->prepare($sql)->execute(
                array(
                    $product->id,
                    $product->get('Product Current Key'),
                    $product->get('Product Family Category Key'),
                    $product->get('Product Department Category Key'),
                    $row['Order Transaction Fact Key']
                )
            );


            $transaction_deal_data = array(
                'otf_key'     => $row['Order Transaction Fact Key'],
                'product_id'  => $product->id,
                'Code'        => sprintf('<span class="link" onclick="change_view(\'/products/%d/%d\')">%s</span>', $order->get('Order Store Key'), $product->id, $product->get('Product Code')),
                'Description' => $description

            );


            $metadata = array(

                'class_html'  => array(
                    'Order_State'                   => $order->get('State'),
                    'Items_Net_Amount'              => $order->get('Items Net Amount'),
                    'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
                    'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
                    'Total_Net_Amount'              => $order->get('Total Net Amount'),
                    'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
                    'Total_Amount'                  => $order->get('Total Amount'),
                    'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
                    'To_Pay_Amount'                 => $order->get('To Pay Amount'),
                    'Payments_Amount'               => $order->get('Payments Amount'),


                    'Order_Number_items' => $order->get('Number Items')

                ),
                //  'operations'    => $operations,
                'state_index' => $order->get('State Index'),
                'to_pay'      => $order->get('Order To Pay Amount'),
                'total'       => $order->get('Order Total Amount'),
                'charges'     => $order->get('Order Charges Net Amount'),


            );


            $response = array(
                'state'                 => 200,
                'metadata'              => $metadata,
                'transaction_deal_data' => $transaction_deal_data
            );
            echo json_encode($response);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}


function update_delivery_note_picking_locations($data, $editor)
{
    /** @var  $delivery_note \DeliveryNote */
    $delivery_note         = get_object('delivery_note', $data['delivery_note_key']);
    $delivery_note->editor = $editor;
    $delivery_note->update_delivery_note_picking_locations();

    if ($delivery_note->error) {
        $response = array(
            'state' => 400,
            'msg'   => $delivery_note->msg,
        );
    } else {
        $response = array(
            'state' => 200,

        );
    }


    echo json_encode($response);
}