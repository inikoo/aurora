<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 13:57:45 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


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
    case 'calculate_sales':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),
                         'scope'      => array('type' => 'string'),

                     )
        );
        calculate_sales($account, $db, $data, $editor);
        break;
    case 'create_time_series':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'           => array('type' => 'string'),
                         'parent_key'       => array('type' => 'key'),
                         'time_series_data' => array(
                             'type'     => 'json array',
                             'optional' => true
                         )
                     )
        );
        create_time_series($account, $db, $data, $editor);

        break;

    case 'create_isf':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key'),

                     )
        );
        create_isf($account, $db, $data, $editor);

        break;

    case 'bridge':
        $data = prepare_values(
            $_REQUEST, array(
                         'object'      => array('type' => 'string'),
                         'key'         => array('type' => 'key'),
                         'subject'     => array('type' => 'string'),
                         'subject_key' => array('type' => 'key'),
                         'operation'   => array('type' => 'string'),

                     )
        );
        edit_bridge($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_category_subject':

        $data = prepare_values(
            $_REQUEST, array(
                         'category_key' => array('type' => 'key'),
                         'subject_key'  => array('type' => 'key'),
                         'operation'    => array('type' => 'string'),

                     )
        );
        edit_category_subject($account, $db, $user, $editor, $data, $smarty);
        break;


    case 'edit_field':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'   => array('type' => 'string'),
                         'key'      => array('type' => 'string'),
                         'field'    => array('type' => 'string'),
                         'value'    => array('type' => 'string'),
                         'metadata' => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),

                     )
        );

        edit_field($account, $db, $redis, $editor, $data, $smarty, $user);
        break;
    case 'object_operation':

        $data = prepare_values(
            $_REQUEST, array(
                         'operation' => array('type' => 'string'),
                         'object'    => array('type' => 'string'),
                         'key'       => array('type' => 'key'),
                         'metadata'  => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),
                         'state'     => array(
                             'type'     => 'json array',
                             'optional' => true
                         )

                     )
        );

        object_operation($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'delete_object_component':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'   => array('type' => 'string'),
                         'key'      => array('type' => 'key'),
                         'field'    => array('type' => 'string'),
                         'metadata' => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),

                     )
        );

        delete_object_component($account, $db, $user, $editor, $data, $smarty);
        break;


    case 'set_as_main':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'   => array('type' => 'string'),
                         'key'      => array('type' => 'key'),
                         'field'    => array('type' => 'string'),
                         'metadata' => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),

                     )
        );

        set_as_main($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'delete_image':
        $data = prepare_values(
            $_REQUEST, array(
                         'image_bridge_key' => array('type' => 'key'),
                     )
        );

        delete_image($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'set_as_principal_image':
        $data = prepare_values(
            $_REQUEST, array(
                         'image_bridge_key' => array('type' => 'key'),
                     )
        );

        set_as_principal_image($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'edit_image':
        $data = prepare_values(
            $_REQUEST, array(
                         'image_bridge_key' => array('type' => 'key'),
                         'field'            => array('type' => 'string'),
                         'value'            => array('type' => 'string'),
                     )
        );

        edit_image($account, $db, $user, $editor, $data, $smarty);
        break;


    case 'delete_attachment':
        $data = prepare_values(
            $_REQUEST, array(
                         'attachment_bridge_key' => array('type' => 'key'),
                     )
        );

        delete_attachment($db, $editor, $data);
        break;


    case 'new_object':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'      => array('type' => 'string'),
                         'parent'      => array('type' => 'string'),
                         'parent_key'  => array('type' => 'key'),
                         'fields_data' => array('type' => 'json array'),

                     )
        );


        new_object($account, $db, $user, $editor, $data, $smarty);

        break;
    case 'get_available_barcode':
        get_available_barcode($db);
        break;


    case 'regenerate_api':

        $data = prepare_values(
            $_REQUEST, array(

                         'api_key' => array('type' => 'key'),

                     )
        );

        regenerate_api($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'disassociate_category':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),
                         'child_key'  => array('type' => 'key'),

                     )
        );
        disassociate_category($account, $db, $data, $editor);
        break;

    case 'transfer_customer_credit_to':
        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key'        => array('type' => 'key'),
                         'amount'              => array('type' => 'amount'),
                         'payment_account_key' => array('type' => 'key'),
                         'reference'           => array('type' => 'string'),
                         'note'                => array('type' => 'string'),


                     )
        );
        transfer_customer_credit_to($account, $db, $data, $editor, $user);
        break;
    case 'add_funds_to_customer_account':
        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key'            => array('type' => 'key'),
                         'amount'                  => array('type' => 'amount'),
                         'note'                    => array('type' => 'string'),
                         'credit_transaction_type' => array('type' => 'string'),
                     )
        );
        edit_customer_account_amount('add_funds', $account, $db, $data, $editor, $user);
        break;
    case 'remove_funds_to_customer_account':
        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key'            => array('type' => 'key'),
                         'amount'                  => array('type' => 'amount'),
                         'note'                    => array('type' => 'string'),
                         'credit_transaction_type' => array('type' => 'string'),
                     )
        );
        edit_customer_account_amount('remove_funds', $account, $db, $data, $editor, $user);
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

/**
 * @param $account \Account
 * @param $db      \PDO
 * @param $editor  array
 * @param $data    array
 * @param $smarty  \Smarty
 * @param $user    \User
 *
 * @throws \SmartyException
 */
function edit_field($account, $db, $redis, $editor, $data, $smarty, $user)
{
    $object = get_object($data['object'], $data['key']);


    if (!$object->id) {
        $response = array(
            'state' => 406,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;
    }

    $field = preg_replace('/_/', ' ', $data['field']);


    if (!$object->can_edit_field($user, $data['field'])) {
        $response = array(
            'state' => 400,
            'msg'   => _("Sorry you don't have permission to do this")

        );
        echo json_encode($response);
        exit;
    }


    $object->editor = $editor;
    $object->smarty = $smarty;


    if ($data['object'] == 'Website' and preg_match('/^Localised_Labels/', $data['field'])) {
        /**
         * @var $object \Website
         */
        $object->update_labels_in_localised_labels(array(preg_replace('/^Localised_Labels/', '', $data['field']) => $data['value']));


        $response = array(
            'state'              => 200,
            'msg'                => '',
            'action'             => '',
            'formatted_value'    => $data['value'],
            'value'              => $data['value'],
            'other_fields'       => $object->get_other_fields_update_info(),
            'new_fields'         => $object->get_new_fields_info(),
            'deleted_fields'     => $object->get_deleted_fields_info(),
            'update_metadata'    => '',
            'directory_field'    => '',
            'directory'          => '',
            'items_in_directory' => ''
        );

        echo json_encode($response);


        return;
    }
    if ($data['object'] == 'Website' and preg_match('/^Website_Settings_/', $data['field'])) {
        $object->update_settings(array(preg_replace('/_/', ' ', preg_replace('/^Website_Settings_/', '', $data['field'])) => $data['value']));

        $formatted_field = preg_replace('/^Website /', '', $field);

        $response = array(
            'state'              => 200,
            'msg'                => '',
            'action'             => '',
            'formatted_value'    => $object->get($formatted_field),
            'value'              => $object->get($field),
            'other_fields'       => $object->get_other_fields_update_info(),
            'new_fields'         => $object->get_new_fields_info(),
            'deleted_fields'     => $object->get_deleted_fields_info(),
            'update_metadata'    => '',
            'directory_field'    => '',
            'directory'          => '',
            'items_in_directory' => ''
        );


        echo json_encode($response);


        return;
    }


    if ($object->get_object_name() == 'Page') {
        $formatted_field = preg_replace('/^Webpage /', '', $field);
        $formatted_field = preg_replace('/^'.$object->get_object_name().' /', '', $formatted_field);
    } else {
        $formatted_field = preg_replace('/^'.$object->get_object_name().' /', '', $field);
    }


    if ($field == 'Product Category Department Category Key') {
        $formatted_field = 'Department Category Code';
    }

    if ($field == 'Staff Position' and $data['object'] == 'User') {
        $formatted_field = 'Position';
    } elseif ($field == 'Part Category Status Including Parts') {
        $formatted_field = 'Status Including Parts';
    }


    if (preg_match('/ Telephone$/', $field)) {
        $options = 'no_history';
    } else {
        $options = '';
    }


    if (isset($data['metadata'])) {
        $object->update(array($field => $data['value']), $options, $data['metadata']);
    } else {
        $object->update(array($field => $data['value']), $options);
    }


    if ($data['object'] == 'Store' or $data['object'] == 'Account') {
        $object->cache_object($redis, 'DNS_ACCOUNT_CODE');
    }


    //print_r($data['metadata']);

    if (isset($data['metadata'])) {
        if (isset($data['metadata']['extra_fields'])) {
            foreach ($data['metadata']['extra_fields'] as $extra_field) {
                $options = '';
                $_field  = preg_replace('/_/', ' ', $extra_field['field']);

                $_value = $extra_field['value'];

                $object->update(array($_field => $_value), $options);
            }
        }
    }


    if ($object->error) {
        $response = array(
            'state' => 400,
            'msg'   => $object->msg,

        );
    } else {
        $update_metadata = $object->get_update_metadata();

        $directory_field    = '';
        $directory          = '';
        $items_in_directory = '';


        if ($object->updated or true) {
            $msg = sprintf(
                '<span class="success"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>',
                $data['field'],
                _('Updated')
            );
            if (isset($object->deleted_value)) {
                $msg = sprintf(
                    '<span class="deleted">%s</span> <span class="discreet"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>',
                    $object->deleted_value,
                    $data['field'],
                    _('Deleted')
                );
            }


            $formatted_value = $object->get($formatted_field);


            $action = 'updated';

            if ($field == 'Product Parts') {
                $smarty->assign('parts_list', $object->get_parts_data(true));
                $update_metadata['parts_list_items'] = $smarty->fetch(
                    'parts_list_items.edit.tpl'
                );
            } elseif ($field == 'Part Cost in Warehouse') {
                include_once 'utils/new_fork.php';
                $parts_data = array(
                    $object->id => $object->get('Part Valid From')

                );

                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'               => 'update_parts_inventory_snapshot_fact',
                        'parts_data'         => $parts_data,
                        'all_parts_min_date' => $object->get('Part Valid From'),
                    ),
                    $account->get('Account Code')
                );
            } elseif ($field == 'Product Price') {
                if ($object->get('Store Currency Code') != $account->get('Account Currency')) {
                    $exchange = currency_conversion($db, $object->get('Store Currency Code'), $account->get('Account Currency'), '- 180 minutes');
                } else {
                    $exchange = 1;
                }


                $update_metadata['price_cell'] = sprintf(
                    '<span style="cursor:text" class="product_price" title="%s" pid="%d" price="%s"    currency="%s"  exchange="%s" cost="%s" old_margin="%s" onClick="open_edit_price(this)">%s</span>',
                    money($exchange * $object->get('Product Price'), $account->get('Account Currency')),
                    $object->id,
                    $object->get('Product Price'),
                    $object->get('Store Currency Code'),
                    $exchange,
                    $object->get('Product Cost'),
                    percentage($exchange * $object->get('Product Price') - $object->get('Product Cost'), $exchange * $object->get('Product Price')),
                    money($object->get('Product Price'), $object->get('Store Currency Code'))
                );


                $update_metadata['margin_cell'] = '<span  class="product_margin"  title="'._('Cost').':'.money($object->get('Product Cost'), $account->get('Account Currency')).'">'.percentage(
                        $exchange * $object->get('Product Price') - $object->get('Product Cost'),
                        $exchange * $object->get('Product Price')
                    ).'<span>';
            } elseif ($field == 'Product Unit RRP') {
                if ($object->get('Product RRP') == '') {
                    $rrp = '';
                } else {
                    $rrp = money($object->get('Product RRP') / $object->get('Product Units Per Case'), $object->get('Store Currency Code'));
                    if ($object->get('Product Units Per Case') != 1) {
                        $rrp .= '/'.$object->get('Product Unit Label');
                    }
                    $rrp = sprintf(
                        '<span style="cursor:text" class="product_rrp" title="%s" pid="%d" rrp="%s"  currency="%s"   onClick="open_edit_rrp(this)">%s</span>',
                        sprintf(_('margin %s'), percentage($object->get('Product RRP') - $object->get('Product Price'), $object->get('Product RRP'))),
                        $object->get('Product ID'),
                        $object->get('Product RRP') / $object->get('Product Units Per Case'),
                        $object->get('Store Currency Code'),
                        $rrp

                    );
                }


                $update_metadata['rrp_cell'] = $rrp;
            } elseif ($field == 'Supplier Part Unit Cost') {
                if ($object->get_object_name() == 'Part') {
                    $main_supplier_part = get_object('Supplier_Part', $object->get('Part Main Supplier Part Key'));


                    $cost = sprintf(
                        '<span class="part_cost"  pid="%d" cost="%s"  currency="%s"   onClick="open_edit_cost(this)">%s</span>',
                        $main_supplier_part->get('Supplier Part Key'),
                        $main_supplier_part->get('Supplier Part Unit Cost'),
                        $main_supplier_part->get('Supplier Part Currency Code'),
                        money(
                            $main_supplier_part->get('Supplier Part Unit Cost'),
                            $main_supplier_part->get('Supplier Part Currency Code')
                        )
                    );
                } else {
                    $cost = sprintf(
                        '<span class="part_cost"  pid="%d" cost="%s"  currency="%s"   onClick="open_edit_cost(this)">%s</span>',
                        $object->get('Supplier Part Key'),
                        $object->get('Supplier Part Unit Cost'),
                        $object->get('Supplier Part Currency Code'),
                        money(
                            $object->get('Supplier Part Unit Cost'),
                            $object->get('Supplier Part Currency Code')
                        )
                    );
                }


                $update_metadata['cost_cell'] = $cost;
            }
        } elseif (isset($object->field_deleted)) {
            $msg             = sprintf(
                '<span class="discreet"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>',
                $data['field'],
                _('Deleted')
            );
            $formatted_value = sprintf(
                '<span class="deleted">%s</span>',
                $object->deleted_value
            );
            $action          = 'deleted';
        } elseif (isset($object->field_created)) {
            $msg             = sprintf(
                '<span class="success"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>',
                $data['field'],
                _('Created')
            );
            $formatted_value = '';
            $action          = 'new_field';

            if ($field == 'new delivery address') {
                $directory_field = 'other_delivery_addresses';
                $smarty->assign('customer', $object);

                $other_delivery_addresses = $object->get_other_delivery_addresses_data();
                $smarty->assign('other_delivery_addresses', $other_delivery_addresses);


                $directory          = $smarty->fetch('delivery_addresses_directory.tpl');
                $items_in_directory = count($object->get_other_delivery_addresses_data());
            }
        } else {
            $msg             = '';
            $formatted_value = $object->get($formatted_field);
            $action          = '';
        }


        $response = array(
            'state'              => 200,
            'msg'                => $msg,
            'action'             => $action,
            'formatted_value'    => $formatted_value,
            'value'              => $object->get($field),
            'other_fields'       => $object->get_other_fields_update_info(),
            'new_fields'         => $object->get_new_fields_info(),
            'deleted_fields'     => $object->get_deleted_fields_info(),
            'update_metadata'    => $update_metadata,
            'directory_field'    => $directory_field,
            'directory'          => $directory,
            'items_in_directory' => $items_in_directory
        );
    }
    echo json_encode($response);
}


function set_as_main($account, $db, $user, $editor, $data, $smarty)
{
    $object = get_object($data['object'], $data['key']);


    if (!$object->id) {
        $response = array(
            'state' => 405,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;
    }

    $object->editor = $editor;


    if ($data['field'] == 'Customer_Main_Plain_Mobile') {
        $object->update(array('Customer Preferred Contact Number' => 'Mobile'));
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Mobile' : '')
        );
    } elseif ($data['field'] == 'Customer_Client_Main_Plain_Mobile') {
        $object->update(array('Customer Client Preferred Contact Number' => 'Mobile'));


        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Mobile' : '')
        );
    } elseif ($data['field'] == 'Supplier_Main_Plain_Mobile') {
        $object->update(array('Supplier Preferred Contact Number' => 'Mobile'));
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Mobile' : '')
        );
    } elseif ($data['field'] == 'Agent_Main_Plain_Mobile') {
        $object->update(array('Agent Preferred Contact Number' => 'Mobile'));
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Mobile' : '')
        );
    } elseif ($data['field'] == 'Customer_Main_Plain_Telephone') {
        $object->update(
            array('Customer Preferred Contact Number' => 'Telephone')
        );
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Telephone' : '')
        );
    } elseif ($data['field'] == 'Customer_Client_Main_Plain_Telephone') {
        $object->update(
            array('Customer Client Preferred Contact Number' => 'Telephone')
        );
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Telephone' : '')
        );
    } elseif ($data['field'] == 'Supplier_Main_Plain_Telephone') {
        $object->update(
            array('Supplier Preferred Contact Number' => 'Telephone')
        );
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Telephone' : '')
        );
    } elseif ($data['field'] == 'Agent_Main_Plain_Telephone') {
        $object->update(
            array('Agent Preferred Contact Number' => 'Telephone')
        );
        $response = array(
            'state'          => 200,
            'other_fields'   => $object->get_other_fields_update_info(),
            'new_fields'     => $object->get_new_fields_info(),
            'deleted_fields' => $object->get_deleted_fields_info(),
            'action'         => ($object->updated ? 'set_main_contact_number_Telephone' : '')
        );
    } elseif (preg_match('/(.+)(_\d+)$/', $data['field'], $matches)) {
        $value = trim(preg_replace('/_/', ' ', $matches[2]));
        $field = trim(preg_replace('/_/', ' ', $matches[1]));

        $object->set_as_main($field, $value);

        if ($field == 'Customer Other Delivery Address') {
            $smarty->assign('customer', $object);
            $directory_field = 'other_delivery_addresses';

            $other_delivery_addresses = $object->get_addresses_data();

            $smarty->assign('other_delivery_addresses', $other_delivery_addresses);

            $directory          = $smarty->fetch('delivery_addresses_directory.tpl');
            $items_in_directory = count($object->get_other_delivery_addresses_data());
            $action             = ($object->updated ? 'set_main_delivery_address' : '');
            $value              = $object->get('Customer Delivery Address');
        } else {
            $directory          = '';
            $directory_field    = '';
            $items_in_directory = 0;
            $action             = '';
            $value              = '';
        }


        if ($object->error) {
            $response = array(
                'state' => 400,
                'msg'   => $object->msg,

            );
        } else {
            $response = array(
                'state'              => 200,
                'other_fields'       => $object->get_other_fields_update_info(),
                'new_fields'         => $object->get_new_fields_info(),
                'deleted_fields'     => $object->get_deleted_fields_info(),
                'action'             => $action,
                'directory_field'    => $directory_field,
                'directory'          => $directory,
                'items_in_directory' => $items_in_directory,
                'value'              => $value
            );
        }
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'invalid field data',

        );
    }

    echo json_encode($response);
}


function delete_object_component($account, $db, $user, $editor, $data, $smarty)
{
    $object = get_object($data['object'], $data['key']);


    if (!$object->id) {
        $response = array(
            'state' => 405,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;
    }

    $object->editor = $editor;


    if (preg_match('/(.+)(_\d+)$/', $data['field'], $matches)) {
        $value = trim(preg_replace('/_/', ' ', $matches[2]));
        $field = trim(preg_replace('/_/', ' ', $matches[1]));


        $object->delete_component($field, $value);


        if ($object->error) {
            $response = array(
                'state' => 400,
                'msg'   => $object->msg,

            );
        } else {
            if ($field == 'Customer Other Delivery Address') {
                $smarty->assign('customer', $object);
                $directory_field = 'other_delivery_addresses';

                $other_delivery_addresses = $object->get_other_delivery_addresses_data();
                $smarty->assign('other_delivery_addresses', $other_delivery_addresses);

                $directory          = $smarty->fetch('delivery_addresses_directory.tpl');
                $items_in_directory = count(
                    $object->get_other_delivery_addresses_data()
                );
            } else {
                $directory_field    = '';
                $directory          = '';
                $items_in_directory = 0;
            }


            $response = array(
                'state'              => 200,
                'other_fields'       => $object->get_other_fields_update_info(),
                'new_fields'         => $object->get_new_fields_info(),
                'deleted_fields'     => $object->get_deleted_fields_info(),
                'action'             => '',
                'directory_field'    => $directory_field,
                'directory'          => $directory,
                'items_in_directory' => $items_in_directory,
            );
        }
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'invalid field data',

        );
    }

    echo json_encode($response);
}


function object_operation($account, $db, $user, $editor, $data, $smarty)
{
    if ($data['object'] == 'website_footer' or $data['object'] == 'website_header') {
        $object            = get_object('website', $data['key']);
        $data['operation'] = 'reset_element';
    } else {
        $object = get_object($data['object'], $data['key']);
    }


    $object->editor = $editor;

    if (isset($data['state'])) {
        $object->web_state = $data['state'];
    }


    if (!$object->id) {
        $response = array(
            'state' => 405,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;
    }

    switch ($data['operation']) {
        case 'policy':
            $request = $object->set_policy($data['metadata']);
            break;
        case 'reset':
            $request = $object->reset_object();
            break;
        case 'reset_element':
            $request = $object->reset_element($data['object']);
            break;
        case 'delete':

            //TODO this note can be also metadata in form of a string change note to metadata
            if (!empty($data['metadata']['note'])) {
                $request = $object->delete($data['metadata']['note']);
            } else {
                $request = $object->delete();
            }


            break;
        case 'suspend':
            $request = $object->suspend();
            break;
        case 'finish':
            $request = $object->finish();
            break;
        case 'activate':
            $request = $object->activate();
            break;
        case 'suspend_parent':
            $request = $object->suspend_parent();
            break;
        case 'activate_parent':
            $request = $object->activate_parent();
            break;
        case 'approve':
            $request = $object->approve();
            break;
        case 'reject':
            $request = $object->reject();
            break;
        case 'reindex':
            /**
             * @var $object \Page
             */ $request = $object->reindex();
            break;
        case 'archive':
            $request = $object->archive();
            break;
        case 'unarchive':
            $request = $object->unarchive();
            break;
        case 'clean_cache':
            $request = $object->clean_cache();
            break;
        case 'unlink_customer':
            $request = $object->unlink_customer();
            break;
        case 'un_dispatch':
            $request = $object->update_state('un_dispatch', '', $data['metadata']);
            break;
        case 'set_all_products_web_configuration':


            foreach ($object->get_products('objects') as $product) {
                $product->update(
                    array('Product Web Configuration' => $data['metadata']['value'])
                );
            }
            $request = '';
            break;
        case 'set_up_raw_material':
            $request = $object->create_raw_material();
            break;

        default:
            exit('unknown operation '.$data['operation']);
    }


    if (!$object->error) {
        $response                    = array('state' => 200);
        $response['update_metadata'] = $object->get_update_metadata();
        if ($object->get_object_name() == 'Category') {
            if ($object->get('Category Scope') == 'Product') {
                if ($object->get('Category Branch Type') == 'Root') {
                    $response['request'] = sprintf(
                        'products/%d/categories',
                        $object->get('Category Store Key')
                    );
                } else {
                    $response['request'] = sprintf(
                        'products/%d/category/%d',
                        $object->get('Category Store Key'),
                        $object->get('Category Parent Key')
                    );
                }
            }
        } elseif ($object->get_object_name() == 'Deal Campaign') {
            // $response['request'] = sprintf('offers/%d/%s', $object->get('Deal Campaign Store Key'));

        } elseif ($object->get_object_name() == 'Supplier Delivery') {
            if ($user->get('User Type') == 'Agent') {
                $response['request'] = 'agent_deliveries';
            } else {
                $response['request'] = $request;
            }
        } else {
            if (is_string($request) and $request != '') {
                $response['request'] = $request;
            }
        }
    } else {
        $response = array(
            'state' => 400,
            'resp'  => $object->msg
        );
    }


    echo json_encode($response);
}

/**
 * @param $account \Account
 * @param $db      PDO
 * @param $user
 * @param $editor
 * @param $data
 * @param $smarty  \Smarty
 *
 * @throws \SmartyException
 */
function new_object($account, $db, $user, $editor, $data, $smarty)
{
    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;

    $metadata          = array();
    $redirect          = '';
    $redirect_metadata = '';


    switch ($data['object']) {
        case 'shipment_label':


            $delivery_note = get_object('Delivery_Note', $data['parent_key']);

            $order         = get_object('Order', $delivery_note->get('Delivery Note Order Key'));
            $order->editor = $editor;


            //exit;

            $address_data = [
                "Address Recipient"            => $data['fields_data']['Order Delivery Address recipient'],
                "Address Organization"         => $data['fields_data']['Order Delivery Address organization'],
                "Address Line 1"               => $data['fields_data']['Order Delivery Address addressLine1'],
                "Address Line 2"               => $data['fields_data']['Order Delivery Address addressLine2'],
                "Address Sorting Code"         => $data['fields_data']['Order Delivery Address sortingCode'],
                "Address Postal Code"          => $data['fields_data']['Order Delivery Address postalCode'],
                "Address Dependent Locality"   => $data['fields_data']['Order Delivery Address dependentLocality'],
                "Address Locality"             => $data['fields_data']['Order Delivery Address locality'],
                "Address Administrative Area"  => $data['fields_data']['Order Delivery Address administrativeArea'],
                "Address Country 2 Alpha Code" => $data['fields_data']['Order Delivery Address country'],
            ];


            $order->update(
                ['Order Delivery Address' => json_encode($address_data)],
                'force'
            );


            //print_r($data['fields_data']);

            $delivery_note->update(
                ['Delivery Note Address' => $order->get('Order Delivery Address')],
                'force'
            );

            $delivery_note->get_data('id', $delivery_note->id);


            $delivery_note->fast_update(
                [
                    'Delivery Note Shipper Key' => $data['fields_data']['Shipment Shipper'],
                    'Delivery Note Telephone'   => $data['fields_data']['Delivery Note Telephone'],
                    'Delivery Note Email'       => $data['fields_data']['Delivery Note Email'],
                ]
            );

            $order->fast_update(
                [
                    'Order Customer Message' => $data['fields_data']['Note'],

                ]
            );

            $shipper = get_object('Shipper', $data['fields_data']['Shipment Shipper']);


            $reference2 = '';
            $service    = '';


            if ($shipper->get('Code') == 'Whistl') {
                $parcels = json_decode($delivery_note->properties('parcels'), true);


                if (count($parcels) > 1) {
                    $response = array(
                        'state' => 400,
                        'msg'   => 'only 1 parcel allowed'

                    );
                    echo json_encode($response);
                    exit;
                }

                if ($parcels[0]['weight'] > 2) {
                    $response = array(
                        'state' => 400,
                        'msg'   => 'Max weight is 2Kg'

                    );
                    echo json_encode($response);
                    exit;
                }

                $dim = [
                    $parcels[0]['height'],
                    $parcels[0]['width'],
                    $parcels[0]['depth']
                ];


                if ($dim[0] > 61 or $dim[1] > 26 or $dim[2] > 26) {
                    $response = array(
                        'state' => 400,
                        'msg'   => 'Max allowed dimension is 61x26x26 cm'

                    );
                    echo json_encode($response);
                    exit;
                }

                if ($dim[0] == 0 or $dim[1] == 0 or $dim[2] == 0 or $dim[0] == '' or $dim[1] == '' or $dim[2] == '') {
                    $response = array(
                        'state' => 400,
                        'msg'   => 'Dimensions can not be zero'

                    );
                    echo json_encode($response);
                    exit;
                }



                $service    = [
                    'ServiceId'          => '78109',
                    'ServiceProviderId'  => '77',
                    'ServiceCustomerUID' => '21753',
                    //'21753',
                ];
                $reference2 = 'packet';
                if ($parcels[0]['weight'] < .75 and $dim[0] <= 35 and $dim[1] <= 25 and $dim[1] <= 2.5) {
                    $service    = [
                        'ServiceId'          => '78108',
                        'ServiceProviderId'  => '77',
                        'ServiceCustomerUID' => '21751',
                        //'21751',
                    ];
                    $reference2 = 'envelop';
                }


                $reference2='yodel';
                $service    = [
                    'ServiceId'          => '663',
                    'ServiceProviderId'  => '10',
                    'ServiceCustomerUID' => '31107',
                ];

                $service    = json_encode($service);
            }


            if (!empty($data['fields_data']['Service '.$shipper->id]) and $data['fields_data']['Service '.$shipper->id] != '_AUTO_') {
                $service = $data['fields_data']['Service '.$shipper->id];
            }

            $result = $delivery_note->get_label($service, $reference2);

            switch ($result['status']) {
                case 'success':
                    $redirect          = 'orders/'.$order->get('Order Store Key').'/'.$order->id;
                    $redirect_metadata = array(
                        'tab'             => 'order.items',
                        'reload_showcase' => 1
                    );

                    break;
                case 'fail':
                    $redirect          = 'orders/'.$order->get('Order Store Key').'/'.$order->id;
                    $redirect_metadata = array(
                        'tab'             => 'retry_shipment_label',
                        'dn_key'          => $delivery_note->id,
                        'reload_showcase' => 1
                    );

                    break;
                default:
                    $response = array(
                        'state' => 400,
                        'msg'   => 'Unknown error'

                    );
                    echo json_encode($response);
                    exit;
            }


            $object = $delivery_note;
            $parent = $delivery_note;

            $new_object_html = '';
            $updated_data    = array();

            break;

        case 'Deal':

            include_once 'utils/parse_deal_data.php';

            switch ($data['parent']) {
                case 'category':

                    $category      = get_object('Category', $data['parent_key']);
                    $store         = get_object('Store', $category->get('Store Key'));
                    $store->editor = $editor;


                    if ($data['fields_data']['Entitled To Voucher']) {
                        $voucher      = true;
                        $voucher_data = array(
                            'Voucher Auto Code' => $data['fields_data']['Deal Voucher Auto Code'],
                            'Voucher Code'      => $data['fields_data']['Deal Voucher Code']
                        );
                    } else {
                        $voucher      = false;
                        $voucher_data = array();
                    }


                    $deal_new_data = array(
                        'Deal Name'        => $data['fields_data']['Deal Name'],
                        'Deal Description' => '',

                        'Deal Begin Date'      => $data['fields_data']['Deal Interval From'],
                        'Deal Expiration Date' => $data['fields_data']['Deal Interval To'],


                        'Deal Name Label' => $data['fields_data']['Deal Name'],
                        'Deal Icon'       => '<i class="fa fa-tag" ></i>',

                        'Deal Trigger'     => 'Category',
                        'Deal Trigger Key' => $category->id,
                        'Voucher'          => $voucher,
                        'Voucher Data'     => $voucher_data,


                    );

                    //print_r($deal_new_data);

                    if ($data['fields_data']['Deal Type Percentage Off']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']);
                        $deal_new_data['Deal Terms']           = 1;
                        $deal_new_data['Deal Terms Type']      = ($voucher ? 'Category Quantity Ordered AND Voucher' : 'Category Quantity Ordered');
                        $deal_new_data['Deal Term Label']      = sprintf(_('%s products'), $category->get('Code'));


                        $new_component_data = array(


                            'Deal Component Allowance Label'        => sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']),
                            'Deal Component Allowance Type'         => 'Percentage Off',
                            'Deal Component Allowance Target'       => 'Category',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $data['fields_data']['Percentage Off'] / 100
                        );
                    } elseif ($data['fields_data']['Deal Type Buy n get n free']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('get %d free'), $data['fields_data']['Deal Buy n get n free B']);


                        $deal_new_data['Deal Terms']      = $data['fields_data']['Deal Buy n get n free A'];
                        $deal_new_data['Deal Terms Type'] = ($voucher ? 'Category For Every Quantity Ordered AND Voucher' : 'Category For Every Quantity Ordered');
                        $deal_new_data['Deal Term Label'] = sprintf(_('%s products, buy %d'), $category->get('Code'), $data['fields_data']['Deal Buy n get n free A']);

                        $allowance_data = json_encode(
                            array(
                                'object'       => 'Category',
                                'key'          => $category->id,
                                'qty'          => $data['fields_data']['Deal Buy n get n free B'],
                                'same_product' => true
                            )
                        );


                        $new_component_data = array(


                            'Deal Component Allowance Label'        => sprintf(_('get %d free'), $data['fields_data']['Deal Buy n get n free B']),
                            'Deal Component Allowance Type'         => 'Get Free',
                            'Deal Component Allowance Target'       => 'Category',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $allowance_data
                        );
                    } elseif ($data['fields_data']['Deal Type Buy n pay n']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('get cheapest %d free'), $data['fields_data']['Deal Buy n n free B']);


                        $deal_new_data['Deal Terms']      = $data['fields_data']['Deal Buy n n free A'];
                        $deal_new_data['Deal Terms Type'] = ($voucher ? 'Category For Every Quantity Any Product Ordered AND Voucher' : 'Category For Every Quantity Any Product Ordered');
                        $deal_new_data['Deal Term Label'] = sprintf(_('%s (Mix & match), buy %d'), $category->get('Code'), $data['fields_data']['Deal Buy n n free A']);

                        $new_component_data = array(

                            'Deal Component Allowance Label'        => sprintf(_('get cheapest %d free'), $data['fields_data']['Deal Buy n n free B']),
                            'Deal Component Allowance Type'         => 'Get Cheapest Free',
                            'Deal Component Allowance Target'       => 'Category',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $data['fields_data']['Deal Buy n n free B']
                        );
                    } else {
                        $response = array(
                            'state' => 400,
                            'resp'  => 'Missing deal type'
                        );
                        echo json_encode($response);
                        exit;
                    }


                    $campaign = get_object('campaign_code-store_key', 'CA|'.$store->id);


                    // print_r($deal_new_data);
                    // print_r($new_component_data);
                    // exit;

                    $object = $campaign->create_deal($deal_new_data, $new_component_data);

                    // print_r($deal);

                    $new_object_html = '';


                    $redirect     = 'products/'.$category->get('Store Key').'/category/'.$category->id.'/deal/'.$object->id;
                    $updated_data = array();


                    break;
                case 'campaign':

                    $campaign = get_object('Campaign', $data['parent_key']);
                    /** @var $store \Store */
                    $store            = get_object('Store', $campaign->get('Store Key'));
                    $store->editor    = $editor;
                    $campaign->editor = $editor;


                    $deal_new_data = array(
                        'Deal Name'        => $data['fields_data']['Deal Name'],
                        'Deal Description' => '',

                        'Deal Begin Date'      => $data['fields_data']['Deal Interval From'],
                        'Deal Expiration Date' => $data['fields_data']['Deal Interval To'],


                        'Deal Name Label' => $data['fields_data']['Deal Name'],
                        'Deal Icon'       => $campaign->get('Icon'),

                        'Deal Trigger'     => 'Order',
                        'Deal Trigger Key' => '',
                        'Voucher'          => false,
                        'Voucher Data'     => array()


                    );


                    //print_r($campaign);
                    //print_r($data);

                    switch ($campaign->get('Code')) {
                        case 'VO':

                            if ($data['fields_data']['Trigger Extra Amount Net'] == 0) {
                                $deal_new_data['Deal Term Label'] = _('Voucher');
                            } else {
                                $deal_new_data['Deal Term Label'] = sprintf(_('Voucher & +%s'), money($data['fields_data']['Trigger Extra Amount Net'], $store->get('Store Currency Code')));
                            }

                            //'Category For Every Quantity Ordered AND Voucher','Category For Every Quantity Ordered','Category For Every Quantity Any Product Ordered AND Voucher','Category For Every Quantity Any Product Ordered','Category Quantity Ordered','Category Quantity Ordered AND Voucher','Department Quantity Ordered','Every Order','Family For Every Quantity Any Product Ordered','Department For Every Quantity Any Product Ordered','Voucher AND Order Interval','Amount AND Order Number','Amount AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Amount','Order Total Net Amount','Order Total Net Amount AND Order Number','Order Total Net Amount AND Shipping Country','Order Total Net Amount AND Order Interval','Order Items Net Amount','Order Items Net Amount AND Order Number','Order Items Net Amount AND Shipping Country','Order Items Net Amount AND Order Interval','Order Total Amount','Order Total Amount AND Order Number','Order Total Amount AND Shipping Country','Order Total Amount AND Order Interval','Order Interval','Product Quantity Ordered','Family Quantity Ordered','Order Number','Shipping Country','Voucher','Department For Every Quantity Ordered','Family For Every Quantity Ordered','Product For Every Quantity Ordered AND Voucher','Product For Every Quantity Ordered'
                            $deal_new_data['Deal Terms Type'] = 'Voucher AND Amount';
                            $deal_new_data['Voucher']         = true;
                            $deal_new_data['Voucher Data']    = array(
                                'Voucher Auto Code' => $data['fields_data']['Deal Voucher Auto Code'],
                                'Voucher Code'      => $data['fields_data']['Deal Voucher Code']
                            );

                            if (!empty($data['fields_data']['Deal Type Shipping Off'])) {
                                $deal_new_data['Deal Allowance Label'] = _('Discounted shipping');
                                if ($deal_new_data['Deal Terms Type'] == 'Voucher AND Amount') {
                                    $deal_new_data['Deal Terms'] = ';'.$data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount';
                                } else {
                                    $deal_new_data['Deal Terms'] = 1;
                                }


                                //todo send shipping_deal_zones_schemas key from the UI and just test it if is valid

                                $shipping_deal_zones_schemas        = $store->get_shipping_zones_schemas('Deal', 'objects');
                                $number_shipping_deal_zones_schemas = count($shipping_deal_zones_schemas);

                                if ($number_shipping_deal_zones_schemas == 0) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'there is no discounted shipping zone schema for this store'
                                    );
                                    echo json_encode($response);
                                    exit;
                                } elseif ($number_shipping_deal_zones_schemas > 1) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'there is more than one discounted shipping zone schema for this store'
                                    );
                                    echo json_encode($response);
                                    exit;
                                } else {
                                    $shipping_deal_zones_schema = array_pop($shipping_deal_zones_schemas);
                                }


                                $new_component_data = array(


                                    'Deal Component Allowance Label'        => _('Discounted shipping'),
                                    'Deal Component Allowance Type'         => 'Shipping Off',
                                    'Deal Component Allowance Target'       => 'Shipping',
                                    'Deal Component Allowance Target Type'  => 'No Items',
                                    'Deal Component Allowance Target Key'   => $shipping_deal_zones_schema->id,
                                    'Deal Component Allowance Target Label' => $shipping_deal_zones_schema->get('Label'),
                                    'Deal Component Allowance'              => 'Shipping Off'
                                );
                            } elseif ($data['fields_data']['Deal Type Percentage Off']) {
                                if (preg_match('/%\s*$/', $data['fields_data']['Percentage'])) {
                                    $percentage = floatval(preg_replace('/%\s*$/', '', $data['fields_data']['Percentage']));
                                    // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                                } else {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                                    );
                                    echo json_encode($response);
                                    exit;
                                }

                                if ($percentage < 0 or $percentage > 100) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                                    );
                                    echo json_encode($response);
                                    exit;
                                } elseif ($percentage == 0) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Percentage off cant be zero')
                                    );
                                    echo json_encode($response);
                                    exit;
                                }


                                $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $percentage);


                                if ($deal_new_data['Deal Terms Type'] == 'Voucher AND Amount') {
                                    $deal_new_data['Deal Terms'] = ';'.$data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount';
                                } else {
                                    $deal_new_data['Deal Terms'] = 1;
                                }


                                $new_component_data = array(


                                    'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
                                    'Deal Component Allowance Type'         => 'Percentage Off',
                                    'Deal Component Allowance Target'       => 'Order',
                                    'Deal Component Allowance Target Type'  => 'Items',
                                    'Deal Component Allowance Target Key'   => '',
                                    'Deal Component Allowance Target Label' => '',
                                    'Deal Component Allowance'              => $percentage / 100
                                );
                            } elseif ($data['fields_data']['Deal Type Get Item Free']) {
                                if ($deal_new_data['Deal Terms Type'] == 'Voucher AND Amount') {
                                    $deal_new_data['Deal Terms'] = ';'.$data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount';
                                } else {
                                    $deal_new_data['Deal Terms'] = 1;
                                }

                                list($success, $result) = parse_deal_not_ordered_free_item($data, $deal_new_data, $store);

                                if ($success) {
                                    list($deal_new_data, $new_component_data) = $result;
                                    $new_component_data['Deal Component Allowance Target Type'] = 'Items';
                                } else {
                                    echo json_encode($result);
                                    exit;
                                }
                            } elseif ($data['fields_data']['Deal Type Amount Off']) {
                                if ($deal_new_data['Deal Terms Type'] == 'Voucher AND Amount') {
                                    $deal_new_data['Deal Terms'] = ';'.$data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount';
                                } else {
                                    $deal_new_data['Deal Terms'] = 1;
                                }

                                list($success, $result) = parse_deal_amount_off($data, $deal_new_data, $store);
                                if ($success) {
                                    list($deal_new_data, $new_component_data) = $result;
                                    $new_component_data['Deal Component Allowance Target Type'] = 'No Items';
                                } else {
                                    echo json_encode($result);
                                    exit;
                                }
                            } else {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => 'Error no allowance type'
                                );
                                echo json_encode($response);
                                exit;
                            }

                            break;

                        case 'SO':


                            if ($data['fields_data']['Trigger Extra Amount Net'] == 0) {
                                $deal_new_data['Deal Term Label'] = _('All orders');
                            } else {
                                $deal_new_data['Deal Term Label'] = sprintf(_('Orders +%s'), money($data['fields_data']['Trigger Extra Amount Net'], $store->get('Store Currency Code')));
                            }

                            //'Category For Every Quantity Ordered AND Voucher','Category For Every Quantity Ordered','Category For Every Quantity Any Product Ordered AND Voucher','Category For Every Quantity Any Product Ordered','Category Quantity Ordered','Category Quantity Ordered AND Voucher','Department Quantity Ordered','Every Order','Family For Every Quantity Any Product Ordered','Department For Every Quantity Any Product Ordered','Voucher AND Order Interval','Amount AND Order Number','Amount AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Amount','Order Total Net Amount','Order Total Net Amount AND Order Number','Order Total Net Amount AND Shipping Country','Order Total Net Amount AND Order Interval','Order Items Net Amount','Order Items Net Amount AND Order Number','Order Items Net Amount AND Shipping Country','Order Items Net Amount AND Order Interval','Order Total Amount','Order Total Amount AND Order Number','Order Total Amount AND Shipping Country','Order Total Amount AND Order Interval','Order Interval','Product Quantity Ordered','Family Quantity Ordered','Order Number','Shipping Country','Voucher','Department For Every Quantity Ordered','Family For Every Quantity Ordered','Product For Every Quantity Ordered AND Voucher','Product For Every Quantity Ordered'
                            $deal_new_data['Deal Terms Type'] = 'Amount';

                            $deal_new_data['Deal Terms'] = $data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount';

                            if (!empty($data['fields_data']['Deal Type Shipping Off'])) {
                                $deal_new_data['Deal Allowance Label'] = _('Discounted shipping');


                                //todo send shipping_deal_zones_schemas key from the UI and just test it if is valid

                                $shipping_deal_zones_schemas        = $store->get_shipping_zones_schemas('Deal', 'objects');
                                $number_shipping_deal_zones_schemas = count($shipping_deal_zones_schemas);

                                if ($number_shipping_deal_zones_schemas == 0) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'there is no discounted shipping zone schema for this store'
                                    );
                                    echo json_encode($response);
                                    exit;
                                } elseif ($number_shipping_deal_zones_schemas > 1) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'there is more than one discounted shipping zone schema for this store'
                                    );
                                    echo json_encode($response);
                                    exit;
                                } else {
                                    $shipping_deal_zones_schema = array_pop($shipping_deal_zones_schemas);
                                }

                                $new_component_data = array(

                                    'Deal Component Allowance Label'        => _('Discounted shipping'),
                                    'Deal Component Allowance Type'         => 'Shipping Off',
                                    'Deal Component Allowance Target'       => 'Shipping',
                                    'Deal Component Allowance Target Type'  => 'No Items',
                                    'Deal Component Allowance Target Key'   => $shipping_deal_zones_schema->id,
                                    'Deal Component Allowance Target Label' => $shipping_deal_zones_schema->get('Label'),
                                    'Deal Component Allowance'              => 'Shipping Off'
                                );
                            } elseif ($data['fields_data']['Deal Type Percentage Off']) {
                                if (preg_match('/%\s*$/', $data['fields_data']['Percentage'])) {
                                    $percentage = floatval(preg_replace('/%\s*$/', '', $data['fields_data']['Percentage']));
                                    // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                                } else {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                                    );
                                    echo json_encode($response);
                                    exit;
                                }

                                if ($percentage < 0 or $percentage > 100) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                                    );
                                    echo json_encode($response);
                                    exit;
                                } elseif ($percentage == 0) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Percentage off cant be zero')
                                    );
                                    echo json_encode($response);
                                    exit;
                                }


                                $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $percentage);


                                $new_component_data = array(


                                    'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
                                    'Deal Component Allowance Type'         => 'Percentage Off',
                                    'Deal Component Allowance Target'       => 'Order',
                                    'Deal Component Allowance Target Type'  => 'Items',
                                    'Deal Component Allowance Target Key'   => '',
                                    'Deal Component Allowance Target Label' => '',
                                    'Deal Component Allowance'              => $percentage / 100
                                );
                            } elseif ($data['fields_data']['Deal Type Get Item Free']) {
                                list($success, $result) = parse_deal_not_ordered_free_item($data, $deal_new_data, $store);

                                if ($success) {
                                    list($deal_new_data, $new_component_data) = $result;
                                    $new_component_data['Deal Component Allowance Target Type'] = 'Items';
                                } else {
                                    echo json_encode($result);
                                    exit;
                                }
                            } elseif ($data['fields_data']['Deal Type Amount Off']) {
                                list($success, $result) = parse_deal_amount_off($data, $deal_new_data, $store);
                                if ($success) {
                                    list($deal_new_data, $new_component_data) = $result;
                                    $new_component_data['Deal Component Allowance Target Type'] = 'No Items';
                                } else {
                                    echo json_encode($result);
                                    exit;
                                }
                            } else {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => 'Error no allowance type'
                                );
                                echo json_encode($response);
                                exit;
                            }

                            break;


                        case 'CA':

                            $category      = get_object('Category', $data['fields_data']['Product Family Category Key']);
                            $store         = get_object('Store', $category->get('Store Key'));
                            $store->editor = $editor;


                            $voucher      = false;
                            $voucher_data = array();


                            $deal_new_data = array(
                                'Deal Name'            => $data['fields_data']['Deal Name'],
                                'Deal Description'     => '',
                                'Deal Begin Date'      => $data['fields_data']['Deal Interval From'],
                                'Deal Expiration Date' => $data['fields_data']['Deal Interval To'],
                                'Deal Name Label'      => $data['fields_data']['Deal Name'],
                                'Deal Icon'            => '<i class="fa fa-tag" ></i>',

                                'Deal Trigger'     => 'Category',
                                'Deal Trigger Key' => $category->id,
                                'Voucher'          => $voucher,
                                'Voucher Data'     => $voucher_data,


                            );


                            //   print_r($data['fields_data']);


                            switch ($data['fields_data']['Allowance Type']) {
                                case 'Deal_Type_Percentage_Off':
                                    $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']);
                                    $deal_new_data['Deal Terms']           = 1;
                                    $deal_new_data['Deal Terms Type']      = 'Category Quantity Ordered';
                                    $deal_new_data['Deal Term Label']      = sprintf(_('%s products'), $category->get('Code'));


                                    $new_component_data = array(


                                        'Deal Component Allowance Label'        => sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']),
                                        'Deal Component Allowance Type'         => 'Percentage Off',
                                        'Deal Component Allowance Target'       => 'Category',
                                        'Deal Component Allowance Target Type'  => 'Items',
                                        'Deal Component Allowance Target Key'   => $category->id,
                                        'Deal Component Allowance Target Label' => $category->get('Code'),
                                        'Deal Component Allowance'              => $data['fields_data']['Percentage Off'] / 100
                                    );
                                    break;
                                case 'Deal Type Buy n get n free':
                                case 'Deal_Type_Buy_n_get_n_free':
                                    $deal_new_data['Deal Allowance Label'] = sprintf(_('get %d free'), $data['fields_data']['Deal Buy n get n free B']);


                                    $deal_new_data['Deal Terms']      = $data['fields_data']['Deal Buy n get n free A'];
                                    $deal_new_data['Deal Terms Type'] = 'Category For Every Quantity Ordered';
                                    $deal_new_data['Deal Term Label'] = sprintf(_('%s products, buy %d'), $category->get('Code'), $data['fields_data']['Deal Buy n get n free A']);


                                    $allowance_data = json_encode(
                                        array(
                                            'object'       => 'Category',
                                            'key'          => $category->id,
                                            'qty'          => $data['fields_data']['Deal Buy n get n free B'],
                                            'same_product' => true
                                        )
                                    );


                                    $new_component_data = array(

                                        'Deal Component Allowance Label'        => sprintf(_('get %d free'), $data['fields_data']['Deal Buy n get n free B']),
                                        'Deal Component Allowance Type'         => 'Get Free',
                                        'Deal Component Allowance Target'       => 'Category',
                                        'Deal Component Allowance Target Type'  => 'Items',
                                        'Deal Component Allowance Target Key'   => $category->id,
                                        'Deal Component Allowance Target Label' => $category->get('Code'),
                                        'Deal Component Allowance'              => $allowance_data
                                    );
                                    break;
                                case 'Deal Type Buy n pay n':

                                    $deal_new_data['Deal Allowance Label'] = sprintf(_('get cheapest %d free'), $data['fields_data']['Deal Buy n n free B']);


                                    $deal_new_data['Deal Terms']      = $data['fields_data']['Deal Buy n n free A'];
                                    $deal_new_data['Deal Terms Type'] = 'Category For Every Quantity Any Product Ordered';
                                    $deal_new_data['Deal Term Label'] = sprintf(_('%s (Mix & match), buy %d'), $category->get('Code'), $data['fields_data']['Deal Buy n n free A']);

                                    $new_component_data = array(

                                        'Deal Component Allowance Label'        => sprintf(_('get cheapest %d free'), $data['fields_data']['Deal Buy n n free B']),
                                        'Deal Component Allowance Type'         => 'Get Cheapest Free',
                                        'Deal Component Allowance Target'       => 'Category',
                                        'Deal Component Allowance Target Type'  => 'Items',
                                        'Deal Component Allowance Target Key'   => $category->id,
                                        'Deal Component Allowance Target Label' => $category->get('Code'),
                                        'Deal Component Allowance'              => $data['fields_data']['Deal Buy n n free B']
                                    );
                                    break;
                                case 'Deal_Type_Amount_Off':


                                    if (!is_numeric($data['fields_data']['Trigger Extra Items Amount Net']) or $data['fields_data']['Trigger Extra Items Amount Net'] <= 0) {
                                        $response = array(
                                            'state' => 400,
                                            'resp'  => 'minimum amount not numeric'
                                        );
                                        echo json_encode($response);
                                        exit;
                                    }

                                    if (!is_numeric($data['fields_data']['Amount Off']) or $data['fields_data']['Amount Off'] <= 0) {
                                        $response = array(
                                            'state' => 400,
                                            'resp'  => 'invalid amount off'
                                        );
                                        echo json_encode($response);
                                        exit;
                                    }


                                    $deal_new_data['Deal Allowance Label'] = sprintf(_('%s off'), money($data['fields_data']['Amount Off'], $store->get('Store Currency Code')));
                                    $deal_new_data['Deal Terms Type']      = 'Category Amount Ordered';

                                    if ($data['fields_data']['Trigger Extra Items Amount Net'] == 0) {
                                        $deal_new_data['Deal Term Label'] = sprintf(_('%s products'), $category->get('Code'));
                                    } else {
                                        $deal_new_data['Deal Term Label'] = sprintf(_('%s products +%s'), $category->get('Code'), money($data['fields_data']['Trigger Extra Items Amount Net'], $store->get('Store Currency Code')));
                                    }


                                    $deal_new_data['Deal Terms'] = json_encode(
                                        array(
                                            'amount'       => $data['fields_data']['Trigger Extra Items Amount Net'],
                                            'amount_field' => 'Order Items Gross Amount',
                                            'object'       => 'Category',
                                            'key'          => $category->id
                                        )
                                    );


                                    $new_component_data = array(


                                        'Deal Component Allowance Label'        => sprintf(_('%s off'), money($data['fields_data']['Amount Off'], $store->get('Store Currency Code'))),
                                        'Deal Component Allowance Type'         => 'Amount Off',
                                        'Deal Component Allowance Target'       => 'Order',
                                        'Deal Component Allowance Target Type'  => 'No Items',
                                        'Deal Component Allowance Target Key'   => '',
                                        'Deal Component Allowance Target Label' => '',
                                        'Deal Component Allowance'              => $data['fields_data']['Amount Off']
                                    );

                                    break;
                                default:

                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'Error unknown allowance type :'.$data['fields_data']['Allowance Type']
                                    );
                                    echo json_encode($response);
                                    exit;
                            }


                            break;
                        case 'FO':


                            $deal_new_data['Deal Terms Type'] = 'Amount AND Order Number';
                            $deal_new_data['Deal Term Label'] = sprintf(_('1st order & +%s'), money($data['fields_data']['Trigger Extra Amount Net'], $store->get('Store Currency Code')));

                            $deal_new_data['Deal Terms'] = $data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount;1';


                            if ($data['fields_data']['Deal Type Shipping Off']) {
                                $deal_new_data['Deal Allowance Label'] = _('Discounted shipping');

                                //todo send shipping_deal_zones_schemas key from the UI and just test it if is valid

                                $shipping_deal_zones_schemas        = $store->get_shipping_zones_schemas('Deal', 'objects');
                                $number_shipping_deal_zones_schemas = count($shipping_deal_zones_schemas);

                                if ($number_shipping_deal_zones_schemas == 0) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'there is no discounted shipping zone schema for this store'
                                    );
                                    echo json_encode($response);
                                    exit;
                                } elseif ($number_shipping_deal_zones_schemas > 1) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'there is more than one discounted shipping zone schema for this store'
                                    );
                                    echo json_encode($response);
                                    exit;
                                } else {
                                    $shipping_deal_zones_schema = array_pop($shipping_deal_zones_schemas);
                                }

                                $new_component_data = array(

                                    'Deal Component Allowance Label'        => _('Discounted shipping'),
                                    'Deal Component Allowance Type'         => 'Shipping Off',
                                    'Deal Component Allowance Target'       => 'Shipping',
                                    'Deal Component Allowance Target Type'  => 'No Items',
                                    'Deal Component Allowance Target Key'   => $shipping_deal_zones_schema->id,
                                    'Deal Component Allowance Target Label' => $shipping_deal_zones_schema->get('Label'),
                                    'Deal Component Allowance'              => 'Shipping Off'
                                );
                            } elseif ($data['fields_data']['Deal Type Percentage Off']) {
                                if (preg_match('/%\s*$/', $data['fields_data']['Percentage'])) {
                                    $percentage = floatval(preg_replace('/%\s*$/', '', $data['fields_data']['Percentage']));
                                    // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                                } else {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                                    );
                                    echo json_encode($response);
                                    exit;
                                }

                                if ($percentage < 0 or $percentage > 100) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                                    );
                                    echo json_encode($response);
                                    exit;
                                } elseif ($percentage == 0) {
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => _('Percentage off cant be zero')
                                    );
                                    echo json_encode($response);
                                    exit;
                                }


                                $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $percentage);


                                $new_component_data = array(


                                    'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
                                    'Deal Component Allowance Type'         => 'Percentage Off',
                                    'Deal Component Allowance Target'       => 'Order',
                                    'Deal Component Allowance Target Type'  => 'Items',
                                    'Deal Component Allowance Target Key'   => '',
                                    'Deal Component Allowance Target Label' => '',
                                    'Deal Component Allowance'              => $percentage / 100
                                );
                            } elseif ($data['fields_data']['Deal Type Get Item Free']) {
                                list($success, $result) = parse_deal_not_ordered_free_item($data, $deal_new_data, $store);

                                if ($success) {
                                    list($deal_new_data, $new_component_data) = $result;
                                } else {
                                    echo json_encode($result);
                                    exit;
                                }
                            } elseif ($data['fields_data']['Deal Type Amount Off']) {
                                list($success, $result) = parse_deal_amount_off($data, $deal_new_data, $store);
                                if ($success) {
                                    list($deal_new_data, $new_component_data) = $result;
                                } else {
                                    echo json_encode($result);
                                    exit;
                                }
                            } else {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => 'Error no allowance type'
                                );
                                echo json_encode($response);
                                exit;
                            }

                            break;


                        case 'CU':
                            $deal_new_data['Deal Trigger'] = 'Customer';


                            $customer = get_object('Customer', $data['fields_data']['Customer Key']);

                            if (!$customer->id) {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => 'Customer not found'
                                );
                                echo json_encode($response);
                                exit;
                            }

                            if ($customer->get('Store Key') != $campaign->get('Store Key')) {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => 'Customer wrong store'
                                );
                                echo json_encode($response);
                                exit;
                            }

                            $deal_new_data['Deal Trigger Key'] = $data['fields_data']['Customer Key'];


                            //          print_r($data['fields_data']);

                            switch ($data['fields_data']['Terms']) {
                                case 'All_products':

                                    if ($data['fields_data']['Trigger Extra Amount Net'] == 0) {
                                        $deal_new_data['Deal Term Label'] = _('All orders');
                                    } else {
                                        $deal_new_data['Deal Term Label'] = sprintf(_('Orders +%s'), money($data['fields_data']['Trigger Extra Amount Net'], $store->get('Store Currency Code')));
                                    }

                                    $deal_new_data['Deal Terms Type'] = 'Amount';
                                    $deal_new_data['Deal Terms']      = $data['fields_data']['Trigger Extra Amount Net'].';Order Items Gross Amount';


                                    break;
                                case 'Product_Category':


                                    if (preg_match('/^C/', $data['fields_data']['Asset'])) {
                                        $category_key = preg_replace('/^C/', '', $data['fields_data']['Asset']);
                                        $category     = get_object('Category', $category_key);

                                        if ($data['fields_data']['Trigger Extra Items Amount Net'] == 0) {
                                            $deal_new_data['Deal Term Label'] = sprintf(_('%s products'), $category->get('Code'));
                                        } else {
                                            $deal_new_data['Deal Term Label'] = sprintf(_('%s products +%s'), $category->get('Code'), money($data['fields_data']['Trigger Extra Items Amount Net'], $store->get('Store Currency Code')));
                                        }

                                        $deal_new_data['Deal Terms Type'] = 'Category Amount Ordered';
                                        $deal_new_data['Deal Terms']      = json_encode(
                                            array(
                                                'amount'       => $data['fields_data']['Trigger Extra Items Amount Net'],
                                                'amount_field' => 'Order Items Gross Amount',
                                                'object'       => 'Category',
                                                'key'          => $category->id
                                            )
                                        );
                                    } elseif (preg_match('/^P/', $data['fields_data']['Asset'])) {
                                        $product_id = preg_replace('/^P/', '', $data['fields_data']['Asset']);
                                        $product    = get_object('Product', $product_id);

                                        if ($data['fields_data']['Trigger Extra Items Amount Net'] == 0) {
                                            $deal_new_data['Deal Term Label'] = $product->get('Code');
                                        } else {
                                            $deal_new_data['Deal Term Label'] = $product->get('Code').' +'.money($data['fields_data']['Trigger Extra Items Amount Net'], $store->get('Store Currency Code'));
                                        }

                                        $deal_new_data['Deal Terms Type'] = 'Product Amount Ordered';
                                        $deal_new_data['Deal Terms']      = json_encode(
                                            array(
                                                'amount'       => $data['fields_data']['Trigger Extra Items Amount Net'],
                                                'amount_field' => 'Order Items Gross Amount',
                                                'object'       => 'Product',
                                                'key'          => $product->id
                                            )
                                        );
                                    } else {
                                        $response = array(
                                            'state' => 400,
                                            'resp'  => _('Percentage off cant be zero')
                                        );
                                        echo json_encode($response);
                                        exit;
                                    }


                                    break;

                                default:
                                    $response = array(
                                        'state' => 400,
                                        'resp'  => 'Unknown term '.$data['fields_data']['Terms']
                                    );
                                    echo json_encode($response);
                                    exit;
                                    break;
                            }


                            $data['fields_data']['Percentage Off'] .= '%';


                            if (preg_match('/%\s*$/', $data['fields_data']['Percentage Off'])) {
                                $percentage = floatval(preg_replace('/%\s*$/', '', $data['fields_data']['Percentage Off']));
                                // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                            } else {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage Off']
                                );
                                echo json_encode($response);
                                exit;
                            }

                            if ($percentage < 0 or $percentage > 100) {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage Off']
                                );
                                echo json_encode($response);
                                exit;
                            } elseif ($percentage == 0) {
                                $response = array(
                                    'state' => 400,
                                    'resp'  => _('Percentage off cant be zero')
                                );
                                echo json_encode($response);
                                exit;
                            }


                            $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $percentage);


                            $new_component_data = array(


                                'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
                                'Deal Component Allowance Type'         => 'Percentage Off',
                                'Deal Component Allowance Target'       => 'Order',
                                'Deal Component Allowance Target Type'  => 'Items',
                                'Deal Component Allowance Target Key'   => '',
                                'Deal Component Allowance Target Label' => '',
                                'Deal Component Allowance'              => $percentage / 100
                            );


                            break;

                        default:
                            $response = array(
                                'state' => 400,
                                'resp'  => 'Unknown deal campaign'
                            );
                            echo json_encode($response);
                            exit;
                    }


                    // print_r($deal_new_data);
                    // print_r($new_component_data);
                    // exit;
                    $object = $campaign->create_deal($deal_new_data, $new_component_data);

                    //print_r($deal);

                    $new_object_html = '';


                    $redirect     = 'offers/'.$campaign->get('Store Key').'/'.strtolower($campaign->get('Deal Campaign Code')).'/'.$object->id;
                    $updated_data = array();


                    break;
                default:
                    break;
            }


            break;

        case 'Deal_Component':

            switch ($data['parent']) {
                case 'category':

                    $category      = get_object('Category', $data['parent_key']);
                    $store         = get_object('Store', $category->get('Store Key'));
                    $store->editor = $editor;


                    $data['fields_data']['Deal Name'] = $data['fields_data']['Deal Name'];

                    if ($data['fields_data']['Entitled To Voucher']) {
                        $voucher      = true;
                        $voucher_data = array(
                            'Voucher Auto Code' => $data['fields_data']['Deal Voucher Auto Code'],
                            'Voucher Code'      => $data['fields_data']['Deal Voucher Code']
                        );
                    } else {
                        $voucher      = false;
                        $voucher_data = array();
                    }


                    $deal_new_data = array(
                        'Deal Name'        => $data['fields_data']['Deal Name'],
                        'Deal Description' => '',

                        'Deal Begin Date'      => $data['fields_data']['Deal Interval From'],
                        'Deal Expiration Date' => $data['fields_data']['Deal Interval To'],


                        'Deal Name Label' => $data['fields_data']['Deal Name'],
                        'Deal Name Label' => $data['fields_data']['Deal Name'],
                        'Deal Icon'       => '<i class="fa fa-tag" ></i>',

                        'Deal Trigger'     => 'Category',
                        'Deal Trigger Key' => $category->id,
                        'Voucher'          => $voucher,
                        'Voucher Data'     => $voucher_data,


                    );


                    if ($data['fields_data']['Deal Type Percentage Off']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']);
                        $deal_new_data['Deal Terms']           = 1;
                        $deal_new_data['Deal Terms Type']      = ($voucher ? 'Category Quantity Ordered AND Voucher' : 'Category Quantity Ordered');
                        $deal_new_data['Deal Term Label']      = sprintf(_('%s products'), $category->get('Code'));


                        $new_component_data = array(

                            'Deal Component Allowance Label'        => sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']),
                            'Deal Component Allowance Type'         => 'Percentage Off',
                            'Deal Component Allowance Target'       => 'Category',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $data['fields_data']['Percentage Off'] / 100
                        );
                    } elseif ($data['fields_data']['Deal Type Family Carton']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']);
                        $deal_new_data['Deal Terms']           = 1;
                        $deal_new_data['Deal Terms Type']      = 'Product In Category Carton';
                        $deal_new_data['Deal Term Label']      = sprintf(_('%s cartons'), $category->get('Code'));


                        $new_component_data = array(

                            'Deal Component Allowance Label'        => sprintf(_('%s%% off'), $data['fields_data']['Percentage Off']),
                            'Deal Component Allowance Type'         => 'Percentage Off',
                            'Deal Component Allowance Target'       => 'Category Product',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $data['fields_data']['Percentage Off'] / 100,
                            'Deal Component Terms Type'             => 'Product In Category Carton',
                            'Deal Component Trigger Scope Type'     => 'Products'
                        );
                    } elseif ($data['fields_data']['Deal Type Buy n get n free']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('get %d free'), $data['fields_data']['Deal Buy n get n free B']);


                        $deal_new_data['Deal Terms']      = $data['fields_data']['Deal Buy n get n free A'];
                        $deal_new_data['Deal Terms Type'] = ($voucher ? 'Category For Every Quantity Ordered AND Voucher' : 'Category For Every Quantity Ordered');
                        $deal_new_data['Deal Term Label'] = sprintf(_('%s products, buy %d'), $category->get('Code'), $data['fields_data']['Deal Buy n get n free A']);


                        $allowance_data = json_encode(
                            array(
                                'object'       => 'Category',
                                'key'          => $category->id,
                                'qty'          => $data['fields_data']['Deal Buy n get n free B'],
                                'same_product' => true
                            )
                        );


                        $new_component_data = array(


                            'Deal Component Allowance Label'        => sprintf(_('get %d free'), $data['fields_data']['Deal Buy n get n free B']),
                            'Deal Component Allowance Type'         => 'Get Free',
                            'Deal Component Allowance Target'       => 'Category',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $allowance_data
                        );
                    } elseif ($data['fields_data']['Deal Type Buy n pay n']) {
                        $deal_new_data['Deal Allowance Label'] = sprintf(_('get cheapest %d free'), $data['fields_data']['Deal Buy n n free B']);


                        $deal_new_data['Deal Terms']      = $data['fields_data']['Deal Buy n n free A'];
                        $deal_new_data['Deal Terms Type'] = ($voucher ? 'Category For Every Quantity Any Product Ordered AND Voucher' : 'Category For Every Quantity Any Product Ordered');
                        $deal_new_data['Deal Term Label'] = sprintf(_('%s (Mix & match), buy %d'), $category->get('Code'), $data['fields_data']['Deal Buy n n free A']);

                        $new_component_data = array(


                            'Deal Component Allowance Label'        => sprintf(_('get cheapest %d free'), $data['fields_data']['Deal Buy n n free B']),
                            'Deal Component Allowance Type'         => 'Get Cheapest Free',
                            'Deal Component Allowance Target'       => 'Category',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => $category->id,
                            'Deal Component Allowance Target Label' => $category->get('Code'),
                            'Deal Component Allowance'              => $data['fields_data']['Deal Buy n n free B']
                        );
                    } elseif ($data['fields_data']['Deal Type Shipping']) {
                        //todo send shipping_deal_zones_schemas key from the UI and just test it if is valid

                        $shipping_deal_zones_schemas        = $store->get_shipping_zones_schemas('Deal', 'objects');
                        $number_shipping_deal_zones_schemas = count($shipping_deal_zones_schemas);

                        //print_r($shipping_deal_zones_schemas);


                        if ($number_shipping_deal_zones_schemas == 0) {
                            $response = array(
                                'state' => 400,
                                'resp'  => 'there is no discounted shipping zone schema for this store'
                            );
                            echo json_encode($response);
                            exit;
                        } elseif ($number_shipping_deal_zones_schemas > 1) {
                            $response = array(
                                'state' => 400,
                                'resp'  => 'there is more than one discounted shipping zone schema for this store'
                            );
                            echo json_encode($response);
                            exit;
                        } else {
                            $shipping_deal_zones_schema = array_pop($shipping_deal_zones_schemas);
                        }


                        //print_r($shipping_deal_zones_schema);
                        //exit;
                        $deal_new_data['Deal Allowance Label'] = _('Discounted shipping');
                        $deal_new_data['Deal Terms']           = 1;


                        $deal_new_data['Deal Terms']      = 1;
                        $deal_new_data['Deal Terms Type'] = ($voucher ? 'Category Quantity Ordered AND Voucher' : 'Category Quantity Ordered');
                        $deal_new_data['Deal Term Label'] = sprintf(_('%s products'), $category->get('Code'));

                        $new_component_data = array(


                            'Deal Component Allowance Label'        => _('Discounted shipping'),
                            'Deal Component Allowance Type'         => 'Shipping Off',
                            'Deal Component Allowance Target'       => 'Shipping',
                            'Deal Component Allowance Target Type'  => 'No Items',
                            'Deal Component Allowance Target Key'   => $shipping_deal_zones_schema->id,
                            'Deal Component Allowance Target Label' => $shipping_deal_zones_schema->get('Label'),
                            'Deal Component Allowance'              => 'Shipping Off'
                        );
                    }


                    $campaign = get_object('campaign_code-store_key', 'CA|'.$store->id);


                    $object = $campaign->create_deal($deal_new_data, $new_component_data);

                    //$deal_components        = $object->get_deal_components('objects');
                    $deal_component = $campaign->new_deal_component;


                    //  print_r($deal_component);

                    $new_object_html = '';


                    $redirect     = 'products/'.$category->get('Store Key').'/category/'.$category->id.'/deal_component/'.$deal_component->id;
                    $updated_data = array();


                    break;
                case
                'campaign':

                    $campaign         = get_object('Campaign', $data['parent_key']);
                    $store            = get_object('Store', $campaign->get('Store Key'));
                    $store->editor    = $editor;
                    $campaign->editor = $editor;


                    $deal_new_data = array(
                        'Deal Name'        => $data['fields_data']['Deal Name'],
                        'Deal Description' => '',

                        'Deal Begin Date'      => $data['fields_data']['Deal Interval From'],
                        'Deal Expiration Date' => $data['fields_data']['Deal Interval To'],


                        'Deal Name Label' => $data['fields_data']['Deal Name'],
                        'Deal Name Label' => $data['fields_data']['Deal Name'],
                        'Deal Icon'       => $campaign->get('Icon'),

                        'Deal Trigger'     => 'Order',
                        'Deal Trigger Key' => '',
                        'Voucher'          => false,
                        'Voucher Data'     => array()


                    );

                    if ($data['fields_data']['Trigger Extra Amount Net'] == 0) {
                        $deal_new_data['Deal Term Label'] = _('Voucher');
                    } else {
                        $deal_new_data['Deal Term Label'] = sprintf(_('Voucher & +%s'), money($data['fields_data']['Trigger Extra Amount Net'], $store->get('Store Currency Code')));
                    }

                    switch ($campaign->get('Code')) {
                        case 'VO':
                            //'Category For Every Quantity Ordered AND Voucher','Category For Every Quantity Ordered','Category For Every Quantity Any Product Ordered AND Voucher','Category For Every Quantity Any Product Ordered','Category Quantity Ordered','Category Quantity Ordered AND Voucher','Department Quantity Ordered','Every Order','Family For Every Quantity Any Product Ordered','Department For Every Quantity Any Product Ordered','Voucher AND Order Interval','Amount AND Order Number','Amount AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Amount','Order Total Net Amount','Order Total Net Amount AND Order Number','Order Total Net Amount AND Shipping Country','Order Total Net Amount AND Order Interval','Order Items Net Amount','Order Items Net Amount AND Order Number','Order Items Net Amount AND Shipping Country','Order Items Net Amount AND Order Interval','Order Total Amount','Order Total Amount AND Order Number','Order Total Amount AND Shipping Country','Order Total Amount AND Order Interval','Order Interval','Product Quantity Ordered','Family Quantity Ordered','Order Number','Shipping Country','Voucher','Department For Every Quantity Ordered','Family For Every Quantity Ordered','Product For Every Quantity Ordered AND Voucher','Product For Every Quantity Ordered'
                            $deal_new_data['Deal Terms Type'] = 'Voucher AND Amount';
                            $deal_new_data['Voucher']         = true;
                            $deal_new_data['Voucher Data']    = array(
                                'Voucher Auto Code' => $data['fields_data']['Deal Voucher Auto Code'],
                                'Voucher Code'      => $data['fields_data']['Deal Voucher Code']
                            );


                            break;
                    }


                    if ($data['fields_data']['Deal Type Shipping Off']) {
                        $deal_new_data['Deal Allowance Label'] = _('Discounted shipping');
                        $deal_new_data['Deal Terms']           = 1;


                        //todo send shipping_deal_zones_schemas key from the UI and just test it if is valid

                        $shipping_deal_zones_schemas        = $store->get_shipping_zones_schemas('Deal', 'objects');
                        $number_shipping_deal_zones_schemas = count($shipping_deal_zones_schemas);

                        if ($number_shipping_deal_zones_schemas == 0) {
                            $response = array(
                                'state' => 400,
                                'resp'  => 'there is no discounted shipping zone schema for this store'
                            );
                            echo json_encode($response);
                            exit;
                        } elseif ($number_shipping_deal_zones_schemas > 1) {
                            $response = array(
                                'state' => 400,
                                'resp'  => 'there is more than one discounted shipping zone schema for this store'
                            );
                            echo json_encode($response);
                            exit;
                        } else {
                            $shipping_deal_zones_schema = array_pop($shipping_deal_zones_schemas);
                        }

                        $new_component_data = array(

                            'Deal Component Allowance Label'        => _('Discounted shipping'),
                            'Deal Component Allowance Type'         => 'Shipping Off',
                            'Deal Component Allowance Target'       => 'Shipping',
                            'Deal Component Allowance Target Type'  => 'No Items',
                            'Deal Component Allowance Target Key'   => $shipping_deal_zones_schema->id,
                            'Deal Component Allowance Target Label' => $shipping_deal_zones_schema->get('Label'),
                            'Deal Component Allowance'              => 'Shipping Off'
                        );
                    } elseif ($data['fields_data']['Deal Type Percentage Off']) {
                        if (preg_match('/\%\s*$/', $data['fields_data']['Percentage'])) {
                            $percentage = floatval(preg_replace('/\%\s*$/', '', $data['fields_data']['Percentage']));
                            // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                        } else {
                            $response = array(
                                'state' => 400,
                                'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                            );
                            echo json_encode($response);
                            exit;
                        }

                        if ($percentage < 0 or $percentage > 100) {
                            $response = array(
                                'state' => 400,
                                'resp'  => _('Invalid percentage').': '.$data['fields_data']['Percentage']
                            );
                            echo json_encode($response);
                            exit;
                        } elseif ($percentage == 0) {
                            $response = array(
                                'state' => 400,
                                'resp'  => _('Percentage off cant be zero')
                            );
                            echo json_encode($response);
                            exit;
                        }


                        $deal_new_data['Deal Allowance Label'] = sprintf(_('%s%% off'), $percentage);
                        $deal_new_data['Deal Terms']           = 1;


                        $new_component_data = array(


                            'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
                            'Deal Component Allowance Type'         => 'Percentage Off',
                            'Deal Component Allowance Target'       => 'Order',
                            'Deal Component Allowance Target Type'  => 'Items',
                            'Deal Component Allowance Target Key'   => '',
                            'Deal Component Allowance Target Label' => '',
                            'Deal Component Allowance'              => $percentage / 100
                        );
                    }


                    $object = $campaign->create_deal($deal_new_data, $new_component_data);
                    //print_r($campaign);
                    //print_r($object);

                    $new_object_html = '';


                    $redirect     = 'offers/'.$object->get('Store Key').'/'.strtolower($campaign->get('Code')).'/deal/'.$object->id;
                    $updated_data = array();


                    break;
                default:
                    break;
            }


            break;

        case'Page':
        case 'Webpage':
            include_once 'class.Page.php';

            //$data['fields_data']['user'] = $user;


            $data['fields_data']['Webpage Type']  = 'Info';
            $data['fields_data']['Webpage Scope'] = 'Info';

            $object = $parent->create_system_webpage($data['fields_data']);


            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $new_object_html = '';


                $redirect     = 'website/'.$object->get('Webpage Website Key').'/in_process/webpage/'.$object->id;
                $updated_data = array();
            }
            break;
        case 'Website':


            include_once 'class.Website.php';


            $data['fields_data']['user'] = $user;


            $object = $parent->create_website($data['fields_data']);


            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $new_object_html = '';


                $redirect     = 'store/'.$object->get('Website Store Key').'/website/';
                $updated_data = array();
            }
            break;


        case 'Location':
            include_once 'class.Location.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_location($data['fields_data']);


            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch('presentation_cards/location.pcard.tpl');
                $updated_data    = array();
            }
            break;


        case 'Category':
            include_once 'class.Category.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_category($data['fields_data']);

            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/main_category.pcard.tpl'
                );
                $updated_data    = array();
            }
            break;


        case 'PurchaseOrder':
            include_once 'class.PurchaseOrder.php';

            $data['fields_data']['user'] = $user;

            $object = $parent->create_order($data['fields_data']);

            if (!$parent->error and $object->id) {
                $new_object_html = '';
                $updated_data    = array();

                if ($object->get('Purchase Order Type') == 'Production') {
                    $redirect = sprintf('production/%d/order/%d', $object->get('Purchase Order Parent Key'), $object->id);
                } else {
                    $redirect = sprintf('%s/%d/order/%d', strtolower($object->get('Purchase Order Parent')), $object->get('Purchase Order Parent Key'), $object->id);
                }
            }
            break;
        case 'SupplierDelivery':

            include_once 'class.SupplierDelivery.php';

            $data['fields_data']['user'] = $user;
            $object                      = $parent->create_supplier_delivery($data['fields_data']);

            if (!$parent->error) {
                $new_object_html = '';
                $updated_data    = array();
            }
            break;
        case 'Fulfilment_Delivery':

            include_once 'class.Fulfilment_Delivery.php';

            $data['fields_data']['user'] = $user;

            $fulfilment_customer         = get_object('Customer_Fulfilment', $parent->id);
            $fulfilment_customer->editor = $editor;
            $object                      = $fulfilment_customer->create_customer_delivery($data['fields_data']);

            $store = get_object('Store', $parent->get('Store Key'));
            if ($store->get('Store Type') == 'Dropshipping') {
                $tab   = 'fulfilment.dropshipping_customers';
                $_link = 'dropshipping';
            } else {
                $tab   = 'fulfilment.asset_keeping_customers';
                $_link = 'asset_keeping';
            }

            if (!$parent->error) {
                $redirect = 'fulfilment/'.$fulfilment_customer->get('Customer Fulfilment Warehouse Key').'/customers/'.$_link.'/'.$fulfilment_customer->id.'/delivery/'.$object->id;

                $new_object_html = '';
                $updated_data    = array();
            }
            break;
        case 'Order':
            include_once 'class.Order.php';


            $order_key = $parent->get_order_in_process_key();
            if ($order_key) {
                $object = get_object('Order', $order_key);
            }


            if (!(isset($object) and $object->id)) {
                $object = $parent->create_order(json_encode($data['fields_data']));
            }


            if (!$parent->error) {
                $new_object_html = '';
                $updated_data    = array();
            }
            break;


        case 'WarehouseArea_Location':
            include_once 'class.Location.php';


            if (isset($data['fields_data']['WarehouseArea Location Code'])) {
                $object = new Location('warehouse_code', $parent->get('Warehouse Key'), $data['fields_data']['WarehouseArea Location Code']);
            } else {
                $object = get_object('Location', $data['fields_data']['WarehouseArea Location Key']);
            }


            if ($object->id) {
                $object->update_area_key($parent->id);
            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => _('Location not found')
                );
                echo json_encode($response);
                exit;
            }
            $new_object_html = '';
            $updated_data    = array();


            break;
        case 'Category_Product':

            include_once 'class.Product.php';

            if (isset($data['fields_data']['Store Product Code'])) {
                $object = new Product('store_code', $parent->get('Category Store Key'), $data['fields_data']['Store Product Code']);
            } else {
                $object = new Product($data['fields_data']['Store Product Key']);
            }

            if ($object->id) {
                $parent->associate_subject($object->id);
            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => _('Product not found')
                );
                echo json_encode($response);
                exit;
            }
            $new_object_html = '';
            $updated_data    = array();

            break;

        case 'Category_Category_Bis':

            include_once 'class.Category.php';


            $store = get_object('Store', $parent->get('Store Key'));

            if (isset($data['fields_data']['Store Category Code'])) {

                $q=$data['fields_data']['Store Category Code'];

                $sql = sprintf(
                    "select `Webpage URL`,`Webpage Code`,`Product Category Webpage Key`,`Category Main Image Key`,`Category Parent Key`,`Product Category Public`,`Webpage State`,`Page Key`,`Category Code`,`Category Label`,`Category Subject`,`Category Key`,`Product Category Active Products`,`Product Category Discontinuing Products` ,`Product Category In Process Products`, `Product Category Status` 
                      from `Page Store Dimension`  left join `Category Dimension` on (`Webpage Scope Key`=`Category Key` and `Webpage Scope` in ('Category Products','Category Categories')  )   left join `Product Category Dimension` on (`Category Key`=`Product Category Key`)  where  `Category Code`=?  and `Webpage Store Key`=? ",

                );



                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array(
                        $q,$store->id
                    )
                );
                if ($row = $stmt->fetch()) {
                    $category_key=$row['Category Key'];
                    $object = new Category($category_key);

                }else{
                    $response = array(
                        'state' => 400,
                        'resp'  => _('Category not found')
                    );
                    echo json_encode($response);
                    exit;
                }



            }

            if ($object->id) {
                $parent->associate_subject($object->id);
            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => _('Category not found')
                );
                echo json_encode($response);
                exit;
            }
            $new_object_html = '';
            $updated_data    = array();

            break;

        case 'Category_Category':

            include_once 'class.Category.php';


            $store = get_object('Store', $parent->get('Store Key'));

            if (isset($data['fields_data']['Store Category Code'])) {
                $object = new Category('root_key_code', $store->get('Store Family Category Key'), $data['fields_data']['Store Category Code']);
            } else {
                $object = new Category($data['fields_data']['Store Category Key']);
            }

            if ($object->id) {
                $parent->associate_subject($object->id);
            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => _('Category not found')
                );
                echo json_encode($response);
                exit;
            }
            $new_object_html = '';
            $updated_data    = array();

            break;

        case 'Category_Part':

            include_once 'class.Part.php';

            if (isset($data['fields_data']['Part Reference'])) {
                $object = new Part(
                    'reference', $data['fields_data']['Part Reference']
                );
            } else {
                $object = new Part($data['fields_data']['Part SKU']);
            }

            if ($object->id) {
                $parent->associate_subject($object->id);
                $object->update('Part Family Category Key', $parent->id);
            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => _('Part not found')
                );
                echo json_encode($response);
                exit;
            }
            $new_object_html = '';
            $updated_data    = array();

            break;

        case 'Agent_Supplier':

            include_once 'class.Supplier.php';

            if (isset($data['fields_data']['Supplier Code'])) {
                $object = new Supplier(
                    'code', $data['fields_data']['Supplier Code']
                );
            } else {
                $object = new Supplier($data['fields_data']['Supplier Key']);
            }

            if ($object->id) {
                $parent->associate_subject($object->id);
                $metadata = $parent->get_update_metadata();
            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => _('Supplier not found')
                );
                echo json_encode($response);
                exit;
            }
            $new_object_html = '';
            $updated_data    = array();
            break;

        case 'Agent':
            include_once 'class.Agent.php';
            $object = $parent->create_agent($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/agent.pcard.tpl'
                );
                $updated_data    = array();
            }
            break;
        case 'Barcode':
            include_once 'class.Barcode.php';
            $object = $parent->create_barcode($data['fields_data'], $user);

            if ($object == 'fork') {
                $response = array(
                    'state' => 200,
                    'msg'   => '<i class="fa fa-check"></i> '._('Adding barcodes in the background'),

                );

                echo json_encode($response);
                exit;
            }

            if (!$parent->error) {
            }

            $new_object_html = '';
            $updated_data    = array();


            break;

        case 'Part':
            include_once 'class.Part.php';
            $object = $parent->create_part($data['fields_data']);

            if ($parent->new_part) {
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/part.pcard.tpl'
                );
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'Product':
            include_once 'class.Product.php';


            if($data['parent']=='product'){

                //print_r($data);

                $product_parent=get_object('Product',$data['parent_key']);






                $object = $product_parent->create_variant($data['fields_data']);

                if ($product_parent->new_product) {
                    $smarty->assign('object', $object);

                    $new_object_html = $smarty->fetch('presentation_cards/product.pcard.tpl');
                    $updated_data    = array();
                } else {
                    $response = array(
                        'state' => 400,
                        'msg'   => $product_parent->msg

                    );
                    echo json_encode($response);
                    exit;
                }

            }else {



                if ($data['fields_data']['Product Type'] == 'Service') {
                    $data['fields_data']['Product Code']           = $data['fields_data']['Service Code'];
                    $data['fields_data']['Product Name']           = $data['fields_data']['Service Name'];
                    $data['fields_data']['Product Price']          = $data['fields_data']['Service Price'];
                    $data['fields_data']['Product Unit Label']     = $data['fields_data']['Service Unit Label'];
                    $data['fields_data']['Product Units Per Case'] = 1;
                    $data['fields_data']['Product Type']           = 'Service';
                    unset($data['fields_data']['Product Parts']);
                    unset($data['fields_data']['Service Code']);
                    unset($data['fields_data']['Service Name']);
                    unset($data['fields_data']['Service Price']);
                    unset($data['fields_data']['Service Unit Label']);
                } else {
                    $data['fields_data']['Product Type'] = 'Product';
                }


                $object = $parent->create_product($data['fields_data']);

                if ($parent->new_product) {
                    $smarty->assign('object', $object);

                    $new_object_html = $smarty->fetch('presentation_cards/product.pcard.tpl');
                    $updated_data    = array();
                } else {
                    $response = array(
                        'state' => 400,
                        'msg'   => $parent->msg

                    );
                    echo json_encode($response);
                    exit;
                }
            }

            break;
        case 'Manufacture_Task':
            include_once 'class.Manufacture_Task.php';
            $object = $parent->create_manufacture_task($data['fields_data']);

            if ($parent->new_manufacture_task) {
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/manufacture_task.pcard.tpl'
                );
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'User':
            include_once 'class.User.php';

            $parent->get_user_data();
            $object = $parent->create_user($data['fields_data']);

            if ($parent->create_user_error or !$object->id) {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->create_user_msg

                );
                echo json_encode($response);
                exit;
            }


            $smarty->assign('account', $account);
            $smarty->assign('parent', $parent);

            $smarty->assign('object', $object);


            if ($parent->get_object_name() == 'Staff') {
                $new_object_html = $smarty->fetch(
                    'presentation_cards/staff.system_user.pcard.tpl'
                );
            } elseif ($parent->get_object_name() == 'Agent') {
                $new_object_html = $smarty->fetch(
                    'presentation_cards/agent.system_user.pcard.tpl'
                );
            } elseif ($parent->get_object_name() == 'Supplier') {
                $new_object_html = $smarty->fetch(
                    'presentation_cards/supplier.system_user.pcard.tpl'
                );
            }

            $updated_data = array();
            break;
        case 'Store':
            include_once 'class.Store.php';
            if (!$parent->error) {
                $object = $parent->create_store($data['fields_data']);

                if ($parent->new_object) {
                    $smarty->assign('account', $account);
                    $smarty->assign('object', $object);

                    $new_object_html = $smarty->fetch(
                        'presentation_cards/store.pcard.tpl'
                    );
                    $updated_data    = array();
                } else {
                    $response = array(
                        'state' => 400,
                        'msg'   => $parent->msg

                    );
                    echo json_encode($response);
                    exit;
                }
            }
            break;
        case 'Warehouse':
            include_once 'class.Warehouse.php';
            if (!$parent->error) {
                $object = $parent->create_warehouse($data['fields_data']);
                if ($parent->new_object) {
                    $smarty->assign('account', $account);
                    $smarty->assign('object', $object);

                    $new_object_html = $smarty->fetch(
                        'presentation_cards/warehouse.pcard.tpl'
                    );
                    $updated_data    = array();
                } else {
                    $response = array(
                        'state' => 400,
                        'msg'   => $parent->msg

                    );
                    echo json_encode($response);
                    exit;
                }
            }
            break;
        case 'Customer':
            include_once 'class.Customer.php';

            /**
             * @var $parent \Store
             */
            $_result      = $parent->create_customer($data['fields_data']);
            $customer     = $_result['Customer'];
            $website_user = $_result['Website_User'];

            if ($parent->new_customer) {
                $object = $customer;
                $smarty->assign('account', $account);
                $smarty->assign('customer', $customer);
                $smarty->assign('website_user', $website_user);


                $new_object_html = $smarty->fetch('presentation_cards/customer.pcard.tpl');
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }


            break;
        case 'Customer Client':

            /**
             * @var $parent \Customer
             */ $customer_client = $parent->create_client($data['fields_data']);

            if ($parent->new_client) {
                $object = $customer_client;


                $redirect = 'customers/'.$customer_client->get('Store Key').'/'.$parent->id.'/client/'.$customer_client->id;

                $new_object_html = '';
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }


            break;
        case 'Prospect':
            include_once 'class.Prospect.php';
            if (!$parent->error) {
                $prospect = $parent->create_prospect($data['fields_data']);
                $object   = $prospect;
                $smarty->assign('account', $account);
                $smarty->assign('prospect', $prospect);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/prospect.pcard.tpl'
                );
                $updated_data    = array();
            }
            break;
        case 'Supplier':
            include_once 'class.Supplier.php';
            $object = $parent->create_supplier($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/supplier.pcard.tpl'
                );
                $updated_data    = array();
            }
            break;
        case 'Contractor':


            if ($user->can_edit('Staff')) {
                include_once 'class.Staff.php';

                $data['fields_data']['Staff Type'] = 'Contractor';

                $object = $parent->create_staff($data['fields_data']);
                if (!$parent->error) {
                    $smarty->assign('account', $account);
                    $smarty->assign('object', $object);

                    $new_object_html = $smarty->fetch(
                        'presentation_cards/contractor.pcard.tpl'
                    );
                    $updated_data    = array();
                }
                break;
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => _("Sorry you don't have permission to do this")

                );
                echo json_encode($response);
                exit;
            }

        case 'Staff':

            if ($user->can_edit('Staff')) {
                include_once 'class.Staff.php';

                $object = $parent->create_staff($data['fields_data']);
                if (!$parent->error) {
                    $smarty->assign('account', $account);
                    $smarty->assign('object', $object);

                    $new_object_html = $smarty->fetch(
                        'presentation_cards/employee.pcard.tpl'
                    );
                    $updated_data    = array();
                }
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => _("Sorry you don't have permission to do this")

                );
                echo json_encode($response);
                exit;
            }

            break;
        case 'API_Key':
            include_once 'class.API_Key.php';

            $object = $parent->create_api_key($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);


                $qr_code = json_encode(
                    array(
                        'url'    => $object->get('Address'),
                        'handle' => $object->get('Code'),
                        'secret' => $object->secret_key


                    )
                );

                $smarty->assign('qr_code', $qr_code);


                $new_object_html = $smarty->fetch(
                    'presentation_cards/api_key.pcard.tpl'
                );
                $updated_data    = array();
            }
            break;
        case 'Timesheet_Record':

            if ($user->can_edit('Staff')) {
                include_once 'class.Timesheet_Record.php';
                $object = $parent->create_timesheet_record($data['fields_data']);
                if (!$parent->error) {
                    $new_object_html = '';
                    $updated_data    = array(
                        'Timesheet_Clocked_Hours' => $parent->get('Clocked Hours')
                    );
                }
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => _("Sorry you don't have permission to do this")

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'Supplier Part':
        case 'Supplier_Part':

            include_once 'class.SupplierPart.php';
            /**
             * @var $parent \Supplier|\Part
             */
            $object = $parent->create_supplier_part_record(
                $data['fields_data']
            );
            if (!$parent->error) {
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/supplier_part.pcard.tpl'
                );
                $updated_data    = array();
            }
            break;
        case 'EmailCampaign':
        case 'Mailshot':

            include_once 'class.EmailCampaign.php';
            $object = $parent->create_mailshot($data['fields_data']);


            if (!$parent->error) {
                $new_object_html   = '';
                $redirect          = 'mailroom/'.$object->get('Store Key').'/marketing/'.$object->get('Email Campaign Email Template Type Key').'/mailshot/'.$object->id;
                $redirect_metadata = array('tab' => 'mailshot.workshop');

                $updated_data = array();
            }
            break;
        case 'Customer Poll Query':
            include_once 'class.Customer_Poll_Query.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_poll_query($data['fields_data']);


            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                if ($object->get('Customer Poll Query Type') == 'Options') {
                    $new_object_html = '';
                    $redirect        = 'customers/'.$parent->id.'/poll_query/'.$object->id;
                    $updated_data    = array();
                } else {
                    $new_object_html = $smarty->fetch('presentation_cards/customer_poll_query.pcard.tpl');
                    $updated_data    = array();
                }
            }
            break;

        case 'Customer Poll Query Option':
            include_once 'class.Customer_Poll_Query_Option.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_option($data['fields_data']);


            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('poll_option', $object);


                $new_object_html = $smarty->fetch('presentation_cards/customer_poll_query_option.pcard.tpl');
                $updated_data    = array();
            }
            break;
        case 'Customers_List':
            include_once 'class.List.php';
            /** @var $parent \Store */
            $object = $parent->create_customers_list($data['fields_data']);

            if ($parent->new_list) {
                $new_object_html   = '';
                $redirect          = 'customers/list/'.$object->id;
                $redirect_metadata = array('tab' => 'customers.list');
                $updated_data      = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;

        case 'Email Template':

            include_once 'class.EmailCampaignType.php';
            $email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $data['parent_key']);


            include_once 'class.Email_Template.php';

            $email_template_data = array(
                'Email Template Name'                    => $data['fields_data']['Email Template Name'],
                'Email Template Email Campaign Type Key' => $email_campaign_type->id,
                'Email Template Role Type'               => 'Marketing',
                'Email Template Role'                    => $data['fields_data']['Email Template Role'],
                'Email Template Scope'                   => 'EmailCampaignType',
                'Email Template Scope Key'               => $email_campaign_type->id,
                'Email Template Text'                    => '',
                'Email Template Subject'                 => $data['fields_data']['Email Template Subject'],


                'Email Template Editing JSON' => ''
            );


            $object = new Email_Template('find', $email_template_data, 'create');

            if ($object->id) {
                $new_object_html = '';
                $redirect        = sprintf('prospects/%d/template/%d', $data['parent_key'], $object->id);
                $updated_data    = array();
            }

            break;
        case 'Shipper':

            $data['fields_data']['user'] = $user;

            /**
             * @var $parent \Warehouse
             */
            $object = $parent->create_shipper($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch('presentation_cards/shipper.pcard.tpl');
                $updated_data    = array();
            }
            break;
        case 'Order_Basket_Purge':

            $sql = sprintf("select `Order Basket Purge Key` from `Order Basket Purge Dimension` where `Order Basket Purge Store Key`=%d and `Order Basket Purge Type`='Manual' and `Order Basket Purge State`='In Process'", $parent->id);

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $response = array(

                        'new_id' => $row['Order Basket Purge Key']

                    );
                    echo json_encode($response);
                    exit;
                }
            }

            include_once 'class.Order_Basket_Purge.php';

            /** @var $parent \Store */
            $object = $parent->create_purge($data['fields_data']);


            if (!$parent->error) {
                $new_object_html = '';
                $updated_data    = array();
            }
            break;
        case 'Picking Pipeline':

            /** @var $parent \Store */
            $object = $parent->create_picking_pipeline($data['fields_data']);


            if (!$parent->error) {
                $new_object_html = '';
                $redirect        = 'store/'.$parent->id.'/pipeline';
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'Warehouse Area':
            include_once 'class.WarehouseArea.php';
            $object = $parent->create_warehouse_area($data['fields_data']);


            if ($parent->new_warehouse_area) {
                $smarty->assign('object', $object);

                $new_object_html = $smarty->fetch(
                    'presentation_cards/warehouse_area.pcard.tpl'
                );
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'Clocking Machine':
            $object = $parent->set_up_clocking_machine($data['fields_data']);


            if ($parent->new_clocking_machine) {
                $new_object_html = '';
                $redirect        = 'clocking_machines/'.$object->id;
                $updated_data    = array();
            } else {
                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'Associate_Customer_Category':

            $object = get_object('Customer', $data['fields_data']['Customer Key']);
            if ($object->id) {
                $parent->associate_subject($object->id);
            }
            $new_object_html = '';
            $updated_data    = array();
            break;

        case 'Charge':

            include_once 'class.Charge.php';

            switch ($data['fields_data']['Charge Type']) {
                case 'SOC':


                    break;
                default:
                    break;
            }


            $object = $parent->create_charge($data['fields_data']);
            print_r($data);

            exit;

        default:
            $response = array(
                'state' => 400,
                'msg'   => 'object process not found: >>'.$data['object']

            );

            echo json_encode($response);
            exit;
    }


    if ($parent->error) {
        $response = array(
            'state' => 400,
            'msg'   => '<i class="fa fa-exclamation-circle"></i> '.$parent->msg,

        );
    } elseif (!$object->id) {
        $response = array(
            'state' => 400,
            'msg'   => '<i class="fa fa-exclamation-circle"></i> '.$object->msg,

        );
    } else {
        $response = array(
            'state'             => 200,
            'msg'               => '<i class="fa fa-check"></i> '._('Success'),
            'pcard'             => $new_object_html,
            'new_id'            => $object->id,
            'updated_data'      => $updated_data,
            'metadata'          => $metadata,
            'redirect'          => $redirect,
            'redirect_metadata' => $redirect_metadata
        );
    }
    echo json_encode($response);
}


function delete_image($account, $db, $user, $editor, $data, $smarty)
{
    include_once 'class.Image.php';


    $sql = sprintf(
        'SELECT `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ',
        $data['image_bridge_key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $object         = get_object($row['Image Subject Object'], $row['Image Subject Object Key']);
            $object->editor = $editor;

            if (!$object->id) {
                $msg      = 'object key not found';
                $response = array(
                    'state' => 400,
                    'msg'   => $msg
                );
                echo json_encode($response);
                exit;
            }

            $object->delete_image($data['image_bridge_key']);

            $response = array(
                'state'         => 200,
                'msg'           => _('Image deleted'),
                'number_images' => $object->get_number_images(),

                'main_image_key' => $object->get_main_image_key()

            );
            echo json_encode($response);
            exit;
        } else {
            $msg      = _('Image not found');
            $response = array(
                'state' => 400,
                'msg'   => $msg
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function set_as_principal_image($account, $db, $user, $editor, $data, $smarty)
{
    include_once 'class.Image.php';


    $sql = sprintf(
        'SELECT `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ',
        $data['image_bridge_key']
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $object         = get_object(
                $row['Image Subject Object'],
                $row['Image Subject Object Key']
            );
            $object->editor = $editor;

            if (!$object->id) {
                $msg      = 'object key not found';
                $response = array(
                    'state' => 400,
                    'msg'   => $msg
                );
                echo json_encode($response);
                exit;
            }

            $object->set_as_principal($data['image_bridge_key']);

            $response = array(
                'state'          => 200,
                'msg'            => 'Image order changed',
                'number_images'  => $object->get_number_images(),
                'main_image_key' => $object->get_main_image_key()

            );
            echo json_encode($response);
            exit;
        } else {
            $msg      = _('Image not found');
            $response = array(
                'state' => 400,
                'msg'   => $msg
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}

/**
 * @param $db \PDO
 * @param $editor
 * @param $data
 */
function delete_attachment($db, $editor, $data)
{
    include_once 'class.Attachment.php';


    $sql = "SELECT `Subject`,`Subject Key`,`Attachment Key` FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=? ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $data['attachment_bridge_key']
        )
    );
    if ($row = $stmt->fetch()) {
        switch ($row['Subject']) {
            case 'Customer Communications':
            case 'Customer History Attachment':
                $_object = 'Customer';
                $object  = get_object($_object, $row['Subject Key']);

                break;
            case 'Staff':
                $_object = 'Staff';
                $object  = get_object($_object, $row['Subject Key']);

                $request = 'employee/'.$row['Subject Key'];
                break;
            case 'Supplier':
                $_object = 'Supplier';
                $object  = get_object($_object, $row['Subject Key']);

                $request = 'supplier/'.$row['Subject Key'];
                break;
            case 'Supplier Delivery':
                $_object = 'SupplierDelivery';
                /**
                 * @var $object \SupplierDelivery
                 */
                $object = get_object($_object, $row['Subject Key']);

                $request = sprintf(
                    '%s/%d/delivery/%d',
                    strtolower($object->get('Supplier Delivery Parent')),
                    $object->get('Supplier Delivery Parent Key'),
                    $object->id

                );
            default:
                $_object = $row['Subject'];
                $object  = get_object($_object, $row['Subject Key']);

                break;
        }

        $object->editor = $editor;

        if (!$object->id) {
            $msg      = 'object key not found';
            $response = array(
                'state' => 400,
                'msg'   => $msg
            );
            echo json_encode($response);
            exit;
        }


        $object->delete_attachment($data['attachment_bridge_key']);

        $response = array(
            'state' => 200,
            'msg'   => _('Attachment deleted')

        );

        if (isset($request)) {
            $response['request'] = $request;
        }

        echo json_encode($response);
        exit;
    } else {
        $msg      = _('Attachment not found');
        $response = array(
            'state' => 400,
            'msg'   => $msg
        );
        echo json_encode($response);
        exit;
    }
}


function get_available_barcode($db)
{
    $barcode_number = '';
    $sql            = sprintf(
        "SELECT `Barcode Number` FROM `Barcode Dimension` WHERE `Barcode Status`='Available' ORDER BY `Barcode Number`"
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $barcode_number = $row['Barcode Number'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $response = array(
        'state'          => 200,
        'barcode_number' => $barcode_number
    );
    echo json_encode($response);
    exit;
}


function edit_category_subject($account, $db, $user, $editor, $data, $smarty)
{
    $category         = get_object('category', $data['category_key']);
    $category->editor = $editor;


    if ($data['operation'] == 'link') {
        $category->associate_subject($data['subject_key']);
    } else {
        $category->disassociate_subject($data['subject_key']);
    }

    $response = array('state' => 200);
    echo json_encode($response);
}


function edit_bridge($account, $db, $user, $editor, $data, $smarty)
{
    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;


    if ($data['operation'] == 'link') {
        $object->associate_subject($data['subject_key']);
    } else {
        $object->disassociate_subject($data['subject_key']);
    }

    $response = array(
        'state'    => 200,
        'metadata' => $object->get_update_metadata()
    );
    echo json_encode($response);
}


function create_time_series($account, $db, $data, $editor)
{
    require_once 'utils/new_fork.php';

    $data['editor'] = $editor;
    $data['type']   = 'timeseries';

    list($fork_key, $msg) = new_fork('au_time_series', $data, $account->get('Account Code'), $db);


    $response = array(
        'state'    => 200,
        'fork_key' => $fork_key,
        'msg'      => $msg

    );
    echo json_encode($response);
}


function create_isf($account, $db, $data, $editor)
{
    //No longer in use
    /*
    require_once 'utils/new_fork.php';

    $data['editor'] = $editor;
    $data['type']   = 'isf';

    list($fork_key, $msg) = new_fork('au_time_series', $data, $account->get('Account Code'), $db);


    $response = array(
        'state'    => 200,
        'fork_key' => $fork_key,
        'msg'      => $msg

    );
    echo json_encode($response);
*/
}

function calculate_sales($account, $db, $data, $editor)
{
    require_once 'utils/new_fork.php';

    $data['editor'] = $editor;

    list($fork_key, $msg) = new_fork(
        'au_calculate_sales',
        $data,
        $account->get('Account Code'),
        $db
    );


    $response = array(
        'state'    => 200,
        'fork_key' => $fork_key,
        'msg'      => $msg

    );
    echo json_encode($response);
}


function edit_image($account, $db, $user, $editor, $data, $smarty)
{
    include_once 'class.Image.php';


    $sql = sprintf(
        'SELECT `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ',
        $data['image_bridge_key']
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $object         = get_object(
                $row['Image Subject Object'],
                $row['Image Subject Object Key']
            );
            $object->editor = $editor;

            if (!$object->id) {
                $msg      = 'object key not found';
                $response = array(
                    'state' => 400,
                    'msg'   => $msg
                );
                echo json_encode($response);
                exit;
            }

            switch ($data['field']) {
                case 'caption':
                    $sql = sprintf('UPDATE  `Image Subject Bridge` SET `Image Subject Image Caption`=%s WHERE `Image Subject Key`=%d ', prepare_mysql($data['value']), $data['image_bridge_key']);
                    $db->exec($sql);
                    $formatted_value = $data['value'];
                    break;
                case 'scope':
                    $sql = sprintf('UPDATE  `Image Subject Bridge` SET `Image Subject Object Image Scope`=%s WHERE `Image Subject Key`=%d ', prepare_mysql($data['value']), $data['image_bridge_key']);
                    $db->exec($sql);


                    if ($object->get_object_name() == 'Part' and $data['value'] == 'Marketing') {
                        $sql = sprintf(
                            'SELECT `Product ID` FROM `Product Dimension` WHERE  `Product Code`=%s  ',
                            prepare_mysql($object->get('Reference'))
                        );


                        if ($result = $db->query($sql)) {
                            foreach ($result as $row) {
                                $product         = get_object('Product', $row['Product ID']);
                                $product->editor = $editor;
                                $product->link_image($row['Image Subject Image Key'], 'Marketing');
                            }
                        } else {
                            print_r($error_info = $db->errorInfo());
                            print $sql;
                            exit;
                        }
                    }


                    switch ($data['value']) {
                        case 'SKO':
                            $formatted_value = _('SKO image');
                            break;
                        case 'Marketing':
                            $formatted_value = _('SKO image');
                            break;

                        default:
                            $formatted_value = $data['value'];
                    }

                    break;

                default:
                    break;
            }


            $response = array(
                'state' => 200,
                'msg'   => 'Image changed',
                'value' => $formatted_value

            );
            echo json_encode($response);
            exit;
        } else {
            $msg      = _('Image not found');
            $response = array(
                'state' => 400,
                'msg'   => $msg
            );
            echo json_encode($response);
            exit;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}

function regenerate_api($account, $db, $user, $editor, $data, $smarty)
{
    $api_key         = get_object('api_key', $data['api_key']);
    $api_key->editor = $editor;
    $private_key     = $api_key->regenerate_private_key();

    $response = array(
        'state'  => 200,
        'qrcode' => json_encode(
            array(
                'url'    => $api_key->get('Address'),
                'handle' => $api_key->get('Code'),
                'secret' => $private_key


            )
        )
    );
    echo json_encode($response);
}

function disassociate_category($account, $db, $data, $editor)
{
    $category = get_object('Category', $data['parent_key']);
    $category->editor;
    $category->disassociate_subject($data['child_key']);

    $response = array(
        'state' => 200,

    );
    echo json_encode($response);
}

/**
 * @param $account \Account
 * @param $db      \PDO
 * @param $data
 * @param $editor
 * @param $user    \User
 */
function transfer_customer_credit_to($account, $db, $data, $editor, $user)
{
    include_once 'utils/currency_functions.php';

    $customer         = get_object('Customer', $data['customer_key']);
    $customer->editor = $editor;

    $store = get_object('Store', $customer->get('Store Key'));

    $payment_account         = get_object('Payment_Account', $data['payment_account_key']);
    $payment_account->editor = $editor;

    $date = gmdate('Y-m-d H:i:s');


    if (!is_numeric($data['amount']) or $data['amount'] <= 0) {
        $response = array(
            'state' => 400,
            'msg'   => 'invalid amount'
        );
        echo json_encode($response);
        exit;
    }


    if ($data['amount'] > $customer->get('Customer Account Balance')) {
        $response = array(
            'state' => 400,
            'msg'   => 'amount greater current customer credits'
        );
        echo json_encode($response);
        exit;
    }


    $exchange = currency_conversion($db, $store->get('Store Currency Code'), $account->get('Account Currency Code'));

    $payment_data = array(
        'Payment Store Key' => $customer->get('Store Key'),

        'Payment Customer Key'       => $customer->id,
        'Payment Transaction Amount' => -$data['amount'],
        'Payment Currency Code'      => $store->get('Store Currency Code'),

        'Payment Sender Email'           => $customer->get('Customer Mail Plain Email'),
        'Payment Sender Card Type'       => '',
        'Payment Created Date'           => $date,
        'Payment Completed Date'         => $date,
        'Payment Last Updated Date'      => $date,
        'Payment Transaction Status'     => 'Completed',
        'Payment Transaction ID'         => $data['reference'],
        'Payment Method'                 => 'Account',
        'Payment Location'               => 'Customer',
        'Payment Metadata'               => '',
        'Payment Submit Type'            => 'Manual',
        'Payment Currency Exchange Rate' => $exchange,
        'Payment User Key'               => $user->id,
        'Payment Type'                   => 'Return'


    );
    $return       = $payment_account->create_payment($payment_data);

    $sql = sprintf(
        'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Type`,`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`,`Credit Transaction Payment Key`) 
                    VALUES ("Return",%s,%.2f,%s,%f,%d,%d) ',
        prepare_mysql($date),
        -$data['amount'],
        prepare_mysql($store->get('Store Currency Code')),
        $exchange,
        $customer->id,
        $return->id


    );

    $db->exec($sql);


    $credit_key = $db->lastInsertId();


    $history_data = array(
        'History Abstract' => '<i class="fa fa-reply"  title="'._('Customer credit returned').'" ></i> '.money($data['amount'], $store->get('Store Currency Code')).' <i class="fal fa-sack-dollar"></i>  <span class="link" onclick="change_view(\'payments/'.$store->id.'/'
            .$return->id.'\')">'.$return->get('Payment Transaction ID').'</span>'.($data['note'] != '' ? ', '.$data['note'] : ''),

        'History Details' => '',
        'Action'          => 'edited'
    );

    $history_key = $customer->add_subject_history(
        $history_data,
        true,
        'No',
        'Changes',
        $customer->get_object_name(),
        $customer->id
    );

    $sql = sprintf(
        'INSERT INTO `Credit Transaction History Bridge` 
                    (`Credit Transaction History Credit Transaction Key`,`Credit Transaction History History Key`) 
                    VALUES (%d,%d) ',
        $credit_key,
        $history_key


    );
    $db->exec($sql);


    $customer->update_account_balance();
    $customer->update_credit_account_running_balances();


    $response = array('state' => 200);
    echo json_encode($response);
}

function edit_customer_account_amount($operation_type, $account, $db, $data, $editor, $user)
{
    if (!$user->can_supervisor('accounting')) {
        $response = array(
            'state' => 400,
            'msg'   => "Restricted operation, you don't have authorisation to do this"
        );
        echo json_encode($response);
        exit;
    }

    include_once 'utils/currency_functions.php';

    $customer         = get_object('Customer', $data['customer_key']);
    $customer->editor = $editor;

    $store = get_object('Store', $customer->get('Store Key'));


    $date = gmdate('Y-m-d H:i:s');


    if (!is_numeric($data['amount']) or $data['amount'] <= 0) {
        $response = array(
            'state' => 400,
            'msg'   => 'invalid amount'
        );
        echo json_encode($response);
        exit;
    }


    if ($operation_type == 'remove_funds') {
        $data['amount'] = -$data['amount'];
    }


    $exchange = currency_conversion($db, $store->get('Store Currency Code'), $account->get('Account Currency Code'));

    $sql = sprintf(
        'INSERT INTO `Credit Transaction Fact` 
                    (`Credit Transaction Type`,`Credit Transaction Date`,`Credit Transaction Amount`,`Credit Transaction Currency Code`,`Credit Transaction Currency Exchange Rate`,`Credit Transaction Customer Key`) 
                    VALUES (%s,%s,%.2f,%s,%f,%d) ',
        prepare_mysql($data['credit_transaction_type']),
        prepare_mysql($date),
        $data['amount'],
        prepare_mysql($store->get('Store Currency Code')),
        $exchange,
        $customer->id

    );

    $db->exec($sql);


    $credit_key = $db->lastInsertId();

    $note = '';
    if ($operation_type == 'remove_funds') {
        switch ($data['credit_transaction_type']) {
            case 'MoneyBack':
                $note = '<i class="fal fa-download "  title="'._('Funds withdrawn from customer account').'" ></i> '.money(-$data['amount'], $store->get('Store Currency Code')).' <i class="fal fa-user-minus"  title="'._('Customer wanted they money back').'" ></i>  '
                    .($data['note'] != '' ? ', '.$data['note'] : '');
                break;
            case 'TransferOut':
                $note = '<i class="fal fa-download "  title="'._('Funds withdrawn from customer account').'" ></i> '.money(-$data['amount'], $store->get('Store Currency Code')).' <i class="fal fa-exchange"  title="'._('Transfer funds other account').'" ></i>  '
                    .($data['note'] != '' ? ', '.$data['note'] : '');
                break;
            case 'RemoveFundsOther':
                $note = '<i class="fal fa-download "  title="'._('Funds withdrawn from customer account').'" ></i> '.money(-$data['amount'], $store->get('Store Currency Code')).($data['note'] != '' ? ', '.$data['note'] : '');
                break;
        }
    } else {
        switch ($data['credit_transaction_type']) {
            case 'PayReturn':
                $note =
                    '<i class="fal fa-upload "  title="'._('Funds added to customer account').'" ></i> '.money($data['amount'], $store->get('Store Currency Code')).' <i class="fal fa-mailbox"  title="'._('Money add to customer account to pay for postage of a return')
                    .'" ></i>  '.($data['note'] != '' ? ', '.$data['note'] : '');
                break;
            case 'Compensation':
                $note =
                    '<i class="fal fa-upload "  title="'._('Funds added to customer account').'" ></i> '.money($data['amount'], $store->get('Store Currency Code')).' <i class="fal fa-angry"  title="'._('Compensation to customer').'" ></i>  '.($data['note'] != '' ? ', '
                        .$data['note'] : '');
                break;
            case 'TransferIn':
                $note =
                    '<i class="fal fa-upload "  title="'._('Funds added to customer account').'" ></i> '.money($data['amount'], $store->get('Store Currency Code')).' <i class="fal fa-exchange"  title="'._('Transfer funds other account').'" ></i>  '.($data['note'] != ''
                        ? ', '.$data['note'] : '');
                break;
            case 'AddFundsOther':
                $note = '<i class="fal fa-upload "  title="'._('Funds added to customer account').'" ></i> '.money($data['amount'], $store->get('Store Currency Code')).($data['note'] != '' ? ', '.$data['note'] : '');
                break;
        }
    }

    $history_data = array(
        'History Abstract' => $note,

        'History Details' => '',
        'Action'          => 'edited'
    );

    $history_key = $customer->add_subject_history(
        $history_data,
        true,
        'No',
        'Changes',
        $customer->get_object_name(),
        $customer->id
    );

    $sql = sprintf(
        'INSERT INTO `Credit Transaction History Bridge` 
                    (`Credit Transaction History Credit Transaction Key`,`Credit Transaction History History Key`) 
                    VALUES (%d,%d) ',
        $credit_key,
        $history_key


    );
    $db->exec($sql);


    $customer->update_account_balance();
    $customer->update_credit_account_running_balances();


    $response = array('state' => 200);
    echo json_encode($response);
}