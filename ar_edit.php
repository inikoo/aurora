<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 13:57:45 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
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

        edit_field($account, $db, $user, $editor, $data, $smarty);
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

        delete_attachment($account, $db, $user, $editor, $data, $smarty);
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
                         'child_key'      => array('type' => 'key'),

                     )
        );
        disassociate_category($account, $db, $data, $editor);
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


function edit_field($account, $db, $user, $editor, $data, $smarty) {


    $object = get_object($data['object'], $data['key']);



    if (!$object->id) {
        $response = array(
            'state' => 406,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;

    }

    $object->editor = $editor;


    if ($data['object'] == 'Website' and preg_match('/^Localised_Labels/', $data['field'])) {


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

        $field           = preg_replace('/_/', ' ', $data['field']);
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


    $field = preg_replace('/_/', ' ', $data['field']);

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
                '<span class="success"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Updated')
            );
            if (isset($object->deleted_value)) {
                $msg = sprintf(
                    '<span class="deleted">%s</span> <span class="discreet"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $object->deleted_value, $data['field'], _('Deleted')
                );
            }


           // print $formatted_field;

            $formatted_value = $object->get($formatted_field);


            $action = 'updated';

            if ($field == 'Product Parts') {
                $smarty->assign('parts_list', $object->get_parts_data(true));
                $update_metadata['parts_list_items'] = $smarty->fetch(
                    'parts_list_items.edit.tpl'
                );

            } elseif ($field == 'Webpage See Also') {
                $smarty->assign('data', $object->get_see_also_data());
                $smarty->assign('mode', 'edit');

                $update_metadata['webpage_see_also_editor'] = $smarty->fetch('webpage_see_also.edit.tpl');

            } elseif ($field == 'Webpage Related Products') {
                $smarty->assign('data', $object->get_related_products_data());
                $smarty->assign('mode', 'edit');

                $update_metadata['webpage_related_products_editor'] = $smarty->fetch('webpage_related_products.edit.tpl');

            } elseif ($field == 'Product Price') {


                if ($object->get('Store Currency Code') != $account->get('Account Currency')) {

                    $exchange = currency_conversion($db, $object->get('Store Currency Code'), $account->get('Account Currency'), '- 180 minutes');

                } else {
                    $exchange = 1;
                }


                $update_metadata['price_cell'] = sprintf(
                    '<span style="cursor:text" class="product_price" title="%s" pid="%d" price="%s"    currency="%s"  exchange="%s" cost="%s" old_margin="%s" onClick="open_edit_price(this)">%s</span>',
                    money($exchange * $object->get('Product Price'), $account->get('Account Currency')), $object->id, $object->get('Product Price'), $object->get('Store Currency Code'), $exchange, $object->get('Product Cost'),
                    percentage($exchange * $object->get('Product Price') - $object->get('Product Cost'), $exchange * $object->get('Product Price')), money($object->get('Product Price'), $object->get('Store Currency Code'))
                );


                $update_metadata['margin_cell'] = '<span  class="product_margin"  title="'._('Cost').':'.money($object->get('Product Cost'), $account->get('Account Currency')).'">'.percentage(
                        $exchange * $object->get('Product Price') - $object->get('Product Cost'), $exchange * $object->get('Product Price')
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
                        sprintf(_('margin %s'), percentage($object->get('Product RRP') - $object->get('Product Price'), $object->get('Product RRP'))), $object->get('Product ID'), $object->get('Product RRP') / $object->get('Product Units Per Case'),
                        $object->get('Store Currency Code'), $rrp

                    );

                }


                $update_metadata['rrp_cell'] = $rrp;

            } elseif ($field == 'Supplier Part Unit Cost') {


                $cost = sprintf(
                    '<span class="part_cost"  pid="%d" cost="%s"  currency="%s"   onClick="open_edit_cost(this)">%s</span>', $object->get('Supplier Part Key'), $object->get('Supplier Part Unit Cost'), $object->get('Supplier Part Currency Code'),
                    money($object->get('Supplier Part Unit Cost'), $object->get('Supplier Part Currency Code'))
                );


                $update_metadata['cost_cell'] = $cost;

            }


        } elseif (isset($object->field_deleted)) {
            $msg             = sprintf(
                '<span class="discreet"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Deleted')
            );
            $formatted_value = sprintf(
                '<span class="deleted">%s</span>', $object->deleted_value
            );
            $action          = 'deleted';
        } elseif (isset($object->field_created)) {
            $msg             = sprintf(
                '<span class="success"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Created')
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


function set_as_main($account, $db, $user, $editor, $data, $smarty) {


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

    }elseif ($data['field'] == 'Supplier_Main_Plain_Mobile') {
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

    }elseif ($data['field'] == 'Agent_Main_Plain_Telephone') {
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

            $directory          = $smarty->fetch(
                'delivery_addresses_directory.tpl'
            );
            $items_in_directory = count(
                $object->get_other_delivery_addresses_data()
            );
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


function delete_object_component($account, $db, $user, $editor, $data, $smarty) {


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

                $directory          = $smarty->fetch(
                    'delivery_addresses_directory.tpl'
                );
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


function object_operation($account, $db, $user, $editor, $data, $smarty) {


    if ($data['object'] == 'website_footer' or $data['object'] == 'website_header') {
        $object            = get_object('website', $data['key']);
        $data['operation'] = 'reset_element';

    } else {
        $object = get_object($data['object'], $data['key']);
    }


    $object->editor = $editor;

    if (!$object->id) {
        $response = array(
            'state' => 405,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;

    }

    switch ($data['operation']) {
        case 'reset':
            $request = $object->reset_object();
            break;
        case 'reset_element':
            $request = $object->reset_element($data['object']);
            break;
        case 'delete':
            $request = $object->delete();
            break;
        case 'suspend':
            $request = $object->suspend();
            break;
        case 'activate':
            $request = $object->activate();
            break;
        case 'approve':
            $request = $object->approve();
            break;
        case 'reject':
            $request = $object->reject();
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
        case 'un_dispatch':
            $request = $object->update_state('un_dispatch');
            break;
        case 'set_all_products_web_configuration':


            foreach ($object->get_products('objects') as $product) {
                $product->update(
                    array('Product Web Configuration' => $data['metadata']['value'])
                );
            }
            $request = '';
            break;


        default:
            exit('unknown operation '.$data['operation']);
            break;
    }


    if (!$object->error) {
        $response = array('state' => 200);

        if ($object->get_object_name() == 'Category') {

            if ($object->get('Category Scope') == 'Product') {

                if ($object->get('Category Branch Type') == 'Root') {
                    $response['request'] = sprintf(
                        'products/%d/categories', $object->get('Category Store Key')
                    );
                } else {

                    $response['request'] = sprintf(
                        'products/%d/category/%d', $object->get('Category Store Key'), $object->get('Category Parent Key')
                    );
                }
            }

        } else {
            if ($object->get_object_name() == 'Deal Campaign') {

                $response['request'] = sprintf('campaigns/%d', $object->get('Deal Campaign Store Key'));

            } else {

                if (is_string($request) and $request != '') {
                    $response['request'] = $request;
                }

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


function new_object($account, $db, $user, $editor, $data, $smarty) {


    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;

    $metadata = array();
    $redirect = '';

    switch ($data['object']) {
        case 'Page':
        case 'Webpage':
            include_once 'class.Page.php';

            //$data['fields_data']['user'] = $user;


            $data['fields_data']['Webpage Type']              = 'Info';
            $data['fields_data']['Webpage Template Filename'] = 'blocks';
            $data['fields_data']['Webpage Scope']             = 'Info';

            $object = $parent->create_system_webpage($data['fields_data']);


            if (!$parent->error) {

                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $pcard = '';


                $redirect     = 'website/'.$object->get('Webpage Website Key').'/in_process/webpage/'.$object->id;
                $updated_data = array();
            }
            break;
        case 'Website':
            include_once 'class.Website.php';


            ////TODO clear this after migration
            // exit('Cant create website ony after migration');

            $data['fields_data']['user'] = $user;


            $object = $parent->create_website($data['fields_data']);


            if (!$parent->error) {

                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $pcard = '';


                $redirect     = 'store/'.$object->get('Website Store Key').'/website/';
                $updated_data = array();
            }
            break;
        case 'Deal Campaign':
            include_once 'class.Location.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_campaign($data['fields_data']);


            if (!$parent->error) {

                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $pcard        = '';
                $redirect     = sprintf('campaigns/%d/%d', $object->get('Deal Campaign Store Key'), $object->id);
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

                $pcard        = $smarty->fetch('presentation_cards/location.pcard.tpl');
                $updated_data = array();
            }
            break;


        case 'Category':
            include_once 'class.Category.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_category($data['fields_data']);

            // Migration -----
            /*
            include_once 'class.Store.php';
            $store=new Store($parent->get('Category Store Key'));

            if ($parent->get('Category Scope')=='Product') {
                if ($parent->get('Category Subject')=='Product') {

                    // creating family



                    $sql=sprintf("select * from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s",
                        $parent->get('Category Store Key'),
                        prepare_mysql($parent->get('Category Code'))
                    );

                    $code=$data['fields_data']['Category Code'];
                    if ($result=$db->query($sql)) {
                        if ($department = $result->fetch()) {
                            $department_key=$department['Product Department Key'];




                            $sql=sprintf('insert into `Product Family Dimension` (
                        `Product Family Store Key`,`Product Family Currency Code`,
                        `Product Family Main Department Key`,`Product Family Main Department Code`,`Product Family Main Department Name`,
                        `Product Family Code`,`Product Family Name`,`Product Family Description`,`Product Family Special Characteristic`)
                        values (%d,%s,
                        %d,%s,%s,
                        %s,%s,"","")',
                                $parent->get('Category Store Key'),
                                prepare_mysql($store->get('Store Currency Code')),
                                $department['Product Department Key'],
                                prepare_mysql($department['Product Department Code']),
                                prepare_mysql($department['Product Department Name']),
                                prepare_mysql($code),
                                prepare_mysql($code)
                            );
                            $db->exec($sql);



                        }else {
                            $sql=sprintf('insert into `Product Family Dimension` (
                        `Product Family Store Key`,`Product Family Currency Code`,
                        `Product Family Main Department Key`,`Product Family Main Department Code`,`Product Family Main Department Name`,
                        `Product Family Code`,`Product Family Name`,`Product Family Description`,`Product Family Special Characteristic`)
                        values (%d,%s,
                        %d,%s,%s,
                        %s,%s,"","")',
                                $parent->get('Category Store Key'),
                                prepare_mysql($store->get('Store Currency Code')),

                                0, prepare_mysql(""), prepare_mysql(""),
                                prepare_mysql($code),
                                prepare_mysql($code)
                            );
                            print $sql;
                            $db->exec($sql);
                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        exit;
                    }


                }
                else {
                    // insert department
                    $code=$data['fields_data']['Category Code'];
                    $sql=sprintf('insert into `Product Department Dimension` (
                        `Product Department Store Key`,`Product Department Store Code`,`Product Department Currency Code`,
                        `Product Department Code`,`Product Department Name`,`Product Department Description`)
                        values (%d,%s,%s,
                        %s,%s,"")',
                        $parent->get('Category Store Key'),
                        prepare_mysql($store->get('Store Code')),
                        prepare_mysql($store->get('Store Currency Code')),

                        0, prepare_mysql(""), prepare_mysql(""),
                        prepare_mysql($code),
                        prepare_mysql($code)
                    );
                    print $sql;
                    $db->exec($sql);


                }


            }

    */
            // -----------


            if (!$parent->error) {

                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/main_category.pcard.tpl'
                );
                $updated_data = array();
            }
            break;


        case 'PurchaseOrder':
            include_once 'class.PurchaseOrder.php';

            $data['fields_data']['user'] = $user;

            $object = $parent->create_order($data['fields_data']);


            if (!$parent->error and $object->id) {

                $pcard        = '';
                $updated_data = array();
            }
            break;
        case 'SupplierDelivery':
            include_once 'class.SupplierDelivery.php';

            $data['fields_data']['user'] = $user;

            $object = $parent->create_delivery($data['fields_data']);
            if (!$parent->error) {

                $pcard        = '';
                $updated_data = array();
            }
            break;
        case 'Order':
            include_once 'class.Order.php';


            $order_key = $parent->get_order_in_process_key();
            if ($order_key) {
                $object = get_object('Order', $order_key);
            }

            if (!(isset($object) and $object->id)) {
                $object = $parent->create_order($data['fields_data']);
            }


            if (!$parent->error) {

                $pcard        = '';
                $updated_data = array();
            }
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
            $pcard        = '';
            $updated_data = array();

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
            $pcard        = '';
            $updated_data = array();

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
            $pcard        = '';
            $updated_data = array();

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
            $pcard        = '';
            $updated_data = array();
            break;

        case 'Agent':
            include_once 'class.Agent.php';
            $object = $parent->create_agent($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/agent.pcard.tpl'
                );
                $updated_data = array();
            }
            break;
        case 'Barcode':
            include_once 'class.Barcode.php';
            $object = $parent->create_barcode($data['fields_data']);
            if (!$parent->error) {

            }

            $pcard        = '';
            $updated_data = array();


            break;

        case 'Part':
            include_once 'class.Part.php';
            $object = $parent->create_part($data['fields_data']);

            if ($parent->new_part) {

                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/part.pcard.tpl'
                );
                $updated_data = array();
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


            //print_r($data['fields_data']);

            $object = $parent->create_product($data['fields_data']);

            if ($parent->new_product) {

                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch('presentation_cards/product.pcard.tpl');
                $updated_data = array();
            } else {


                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'Manufacture_Task':
            include_once 'class.Manufacture_Task.php';
            $object = $parent->create_manufacture_task($data['fields_data']);

            if ($parent->new_manufacture_task) {

                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/manufacture_task.pcard.tpl'
                );
                $updated_data = array();
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

            $object = $parent->user;


            $smarty->assign('account', $account);
            $smarty->assign('parent', $parent);

            $smarty->assign('object', $object);


            if ($parent->get_object_name() == 'Staff') {
                $pcard = $smarty->fetch(
                    'presentation_cards/staff.system_user.pcard.tpl'
                );
            } elseif ($parent->get_object_name() == 'Agent') {
                $pcard = $smarty->fetch(
                    'presentation_cards/agent.system_user.pcard.tpl'
                );

            } elseif ($parent->get_object_name() == 'Supplier') {
                $pcard = $smarty->fetch(
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

                    $pcard        = $smarty->fetch(
                        'presentation_cards/store.pcard.tpl'
                    );
                    $updated_data = array();

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

                    $pcard        = $smarty->fetch(
                        'presentation_cards/warehouse.pcard.tpl'
                    );
                    $updated_data = array();
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
            if (!$parent->error) {
                list($customer, $website_user) = $parent->create_customer($data['fields_data']);
                $object = $customer;
                $smarty->assign('account', $account);
                $smarty->assign('customer', $customer);
                $smarty->assign('website_user', $website_user);

                $pcard        = $smarty->fetch(
                    'presentation_cards/customer.pcard.tpl'
                );
                $updated_data = array();
            }
            break;
        case 'Supplier':
            include_once 'class.Supplier.php';
            $object = $parent->create_supplier($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/supplier.pcard.tpl'
                );
                $updated_data = array();
            }
            break;
        case 'Contractor':
            include_once 'class.Staff.php';

            $data['fields_data']['Staff Type'] = 'Contractor';

            $object = $parent->create_staff($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/contractor.pcard.tpl'
                );
                $updated_data = array();
            }
            break;
        case 'Staff':
            include_once 'class.Staff.php';

            $object = $parent->create_staff($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/employee.pcard.tpl'
                );
                $updated_data = array();
            }


            break;
        case 'API_Key':
            include_once 'class.API_Key.php';

            $object = $parent->create_api_key($data['fields_data']);
            if (!$parent->error) {
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/api_key.pcard.tpl'
                );
                $updated_data = array();
            }
            break;
        case 'Timesheet_Record':
            include_once 'class.Timesheet_Record.php';
            $object = $parent->create_timesheet_record($data['fields_data']);
            if (!$parent->error) {
                $pcard        = '';
                $updated_data = array(
                    'Timesheet_Clocked_Hours' => $parent->get('Clocked Hours')
                );
            }
            break;
        case 'Supplier Part':
            include_once 'class.SupplierPart.php';
            $object = $parent->create_supplier_part_record(
                $data['fields_data']
            );
            if (!$parent->error) {
                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/supplier_part.pcard.tpl'
                );
                $updated_data = array();
            }
            break;
        case 'EmailCampaign':
            include_once 'class.EmailCampaign.php';
            $object = $parent->create_email_campaign($data['fields_data']);
            if (!$parent->error) {
                $pcard        = '';
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

                    $pcard        = '';
                    $redirect     = 'customers/'.$parent->id.'/poll_query/'.$object->id;
                    $updated_data = array();

                } else {


                    $pcard        = $smarty->fetch('presentation_cards/customer_poll_query.pcard.tpl');
                    $updated_data = array();
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


                $pcard        = $smarty->fetch('presentation_cards/customer_poll_query_option.pcard.tpl');
                $updated_data = array();


            }
            break;
        case 'Customers_List':
            include_once 'class.List.php';
            $object = $parent->create_customers_list($data['fields_data']);

            if ($parent->new_list) {

                $pcard        = '';
                $redirect     = 'customers/'.$parent->id.'/lists/'.$object->id;
                $updated_data = array();
            } else {


                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        default:
            $response = array(
                'state' => 400,
                'msg'   => 'object process not found '.$data['object']

            );

            echo json_encode($response);
            exit;
            break;
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
            'state'        => 200,
            'msg'          => '<i class="fa fa-check"></i> '._('Success'),
            'pcard'        => $pcard,
            'new_id'       => $object->id,
            'updated_data' => $updated_data,
            'metadata'     => $metadata,
            'redirect'     => $redirect
        );


    }
    echo json_encode($response);

}


function delete_image($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.Image.php';


    $sql = sprintf(
        'SELECT `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $data['image_bridge_key']
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
                'state'          => 200,
                'msg'            => _('Image deleted'),
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


function set_as_principal_image($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.Image.php';


    $sql = sprintf(
        'SELECT `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $data['image_bridge_key']
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $object         = get_object(
                $row['Image Subject Object'], $row['Image Subject Object Key']
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


function delete_attachment($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.Attachment.php';


    $sql = sprintf(
        'SELECT `Subject`,`Subject Key`,`Attachment Key` FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d ', $data['attachment_bridge_key']
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            switch ($row['Subject']) {
                case 'Customer Communications':
                case 'Customer History Attachment':
                    $_object = 'Customer';
                    break;
                case 'Staff':
                    $_object = 'Staff';
                    $request = 'employee/'.$row['Subject Key'];
                    break;
                case 'Supplier':
                    $_object = 'Supplier';
                    $request = 'supplier/'.$row['Subject Key'];
                    break;
                default:
                    $_object = $row['Subject'];
                    break;
            }

            $object         = get_object($_object, $row['Subject Key']);
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
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function get_available_barcode($db) {
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


function edit_category_subject($account, $db, $user, $editor, $data, $smarty) {

    $category         = get_object('category', $data['category_key']);
    $category->editor = $editor;


    if ($data['operation'] == 'link') {
        $category->associate_subject($data['subject_key']);
        // Migration -----

        /*
        if ($category->get('Category Scope')=='Product') {
            if ($category->get('Category Subject')=='Product') {

                $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                    $category->get('Category Store Key'),
                    prepare_mysql($category->get('Category Code'))
                );


                if ($result=$db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $sql=sprintf("update `Product Dimension`set `Product Family Key`=%d, `Product Family Code`=%s, `Product Family Name`=%s,`Product Main Department Key`=%d,
                     `Product Main Department Code`=%s,
                     `Product Main Department Name`=%s
                     where `Product ID`=%d",
                            $row['Product Family Key'],
                            prepare_mysql($row['Product Family Code']),
                            prepare_mysql($row['Product Family Name']),
                            $row['Product Family Main Department Key'],
                            prepare_mysql($row['Product Family Main Department Code']),
                            prepare_mysql($row['Product Family Main Department Name']),
                            $data['subject_key']
                        );

                        $db->exec($sql);
                        //print $sql;
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    print $sql;
                    exit;
                }





            }else {
                // DEpartment


                $sql=sprintf("select * from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s",
                    $category->get('Category Store Key'),
                    prepare_mysql($category->get('Category Code'))
                );


                if ($result=$db->query($sql)) {
                    if ($department = $result->fetch()) {
                        $department_key=$department['Product Department Key'];
                    }else {
                        $department_key=false;
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    exit;
                }


                $family=new Category($data['subject_key']);


                $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                    $family->get('Category Store Key'),
                    prepare_mysql($family->get('Category Code'))
                );


                if ($result=$db->query($sql)) {
                    if ($family = $result->fetch()) {
                        $family_key=$department['Product Department Key'];
                    }else {
                        $family_key=false;
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    exit;
                }


                if ($family_key and $department_key) {


                    $sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Key`=%d, `Product Family Main Department Code`=%s, `Product Family Main Department Name`=%s where `Product Family Key`=%d",
                        0,
                        '',
                        '',
                        $family_key);


                    $db->exec($sql);

                    $sql=sprintf("update `Product Dimension` set `Product Main Department Key`=%d, `Product Main Department Code`=%s, `Product Main Department Name`=%s where `Product Family Key`=%d",
                        0,
                        '',
                        '',
                        $family_key
                    );
                    $db->exec($sql);

                }







            }


        }

*/
        // -----------


    } else {
        $category->disassociate_subject($data['subject_key']);

        // Migration -----
        /*
        if ($category->get('Category Scope')=='Product') {
            if ($category->get('Category Subject')=='Product') {

                $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                    $category->get('Category Store Key'),
                    prepare_mysql($category->get('Category Code'))
                );


                if ($result=$db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $sql=sprintf("update `Product Dimension`set `Product Family Key`=0, `Product Family Code`='', `Product Family Name`='',`Product Main Department Key`=0,
                     `Product Main Department Code`='',
                     `Product Main Department Name`=''
                     where `Product ID`=%d",

                            $data['subject_key']
                        );


                        //print $sql;
                        $db->exec($sql);

                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    exit;
                }





            }else {
                // DEpartment


                $sql=sprintf("select * from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s",
                    $category->get('Category Store Key'),
                    prepare_mysql($category->get('Category Code'))
                );


                if ($result=$db->query($sql)) {
                    if ($department = $result->fetch()) {
                        $department_key=$department['Product Department Key'];
                    }else {
                        $department_key=false;
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    exit;
                }


                $family=new Category($data['subject_key']);


                $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                    $family->get('Category Store Key'),
                    prepare_mysql($family->get('Category Code'))
                );


                if ($result=$db->query($sql)) {
                    if ($family = $result->fetch()) {
                        $family_key=$department['Product Department Key'];
                    }else {
                        $family_key=false;
                    }
                }else {
                    print_r($error_info=$db->errorInfo());
                    exit;
                }


                if ($family_key and $department_key) {


                    $sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Key`=0, `Product Family Main Department Code`='', `Product Family Main Department Name`='' where `Product Family Key`=%d",

                        $family_key);


                    $db->exec($sql);

                    $sql=sprintf("update `Product Dimension` set `Product Main Department Key`=0, `Product Main Department Code`='', `Product Main Department Name`='' where `Product Family Key`=%d",

                        $family_key
                    );
                    $db->exec($sql);

                }







            }


        }
        */

        //----------

    }

    $response = array('state' => 200);
    echo json_encode($response);

}


function edit_bridge($account, $db, $user, $editor, $data, $smarty) {

    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;


    if ($data['operation'] == 'link') {
        $object->associate_subject($data['subject_key']);


        // Migration -----
        /*
        if ($object->get_object_name()=='Category') {



            if ($object->get('Category Scope')=='Product') {
                if ($object->get('Category Subject')=='Product') {

                    $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                        $object->get('Category Store Key'),
                        prepare_mysql($object->get('Category Code'))
                    );


                    if ($result=$db->query($sql)) {
                        if ($row = $result->fetch()) {

                            $sql=sprintf("update `Product Dimension`set `Product Family Key`=%d, `Product Family Code`=%s, `Product Family Name`=%s,`Product Main Department Key`=%d,
                     `Product Main Department Code`=%s,
                     `Product Main Department Name`=%s
                     where `Product ID`=%d",
                                $row['Product Family Key'],
                                prepare_mysql($row['Product Family Code']),
                                prepare_mysql($row['Product Family Name']),
                                $row['Product Family Main Department Key'],
                                prepare_mysql($row['Product Family Main Department Code']),
                                prepare_mysql($row['Product Family Main Department Name']),
                                $data['subject_key']
                            );

                            $db->exec($sql);
                            //print $sql;
                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        print $sql;
                        exit;
                    }





                }else {
                    // DEpartment


                    $sql=sprintf("select * from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s",
                        $object->get('Category Store Key'),
                        prepare_mysql($object->get('Category Code'))
                    );


                    if ($result=$db->query($sql)) {
                        if ($department = $result->fetch()) {
                            $department_key=$department['Product Department Key'];
                        }else {
                            $department_key=false;
                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        exit;
                    }


                    $family=new Category($data['subject_key']);


                    $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                        $family->get('Category Store Key'),
                        prepare_mysql($family->get('Category Code'))
                    );


                    if ($result=$db->query($sql)) {
                        if ($family = $result->fetch()) {
                            $family_key=$department['Product Department Key'];
                        }else {
                            $family_key=false;
                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        exit;
                    }


                    if ($family_key and $department_key) {


                        $sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Key`=%d, `Product Family Main Department Code`=%s, `Product Family Main Department Name`=%s where `Product Family Key`=%d",
                            0,
                            '',
                            '',
                            $family_key);


                        $db->exec($sql);

                        $sql=sprintf("update `Product Dimension` set `Product Main Department Key`=%d, `Product Main Department Code`=%s, `Product Main Department Name`=%s where `Product Family Key`=%d",
                            0,
                            '',
                            '',
                            $family_key
                        );
                        $db->exec($sql);

                    }







                }


            }



        }
        */
        // -----------
    } else {
        $object->disassociate_subject($data['subject_key']);

        // Migration -----
        /*
        if ($object->get_object_name()=='Category') {


            if ($object->get('Category Scope')=='Product') {
                if ($object->get('Category Subject')=='Product') {

                    $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                        $object->get('Category Store Key'),
                        prepare_mysql($object->get('Category Code'))
                    );


                    if ($result=$db->query($sql)) {
                        if ($row = $result->fetch()) {

                            $sql=sprintf("update `Product Dimension`set `Product Family Key`=0, `Product Family Code`='', `Product Family Name`='',`Product Main Department Key`=0,
                     `Product Main Department Code`='',
                     `Product Main Department Name`=''
                     where `Product ID`=%d",

                                $data['subject_key']
                            );


                            //print $sql;
                            $db->exec($sql);

                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        exit;
                    }





                }else {
                    // DEpartment


                    $sql=sprintf("select * from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s",
                        $object->get('Category Store Key'),
                        prepare_mysql($object->get('Category Code'))
                    );


                    if ($result=$db->query($sql)) {
                        if ($department = $result->fetch()) {
                            $department_key=$department['Product Department Key'];
                        }else {
                            $department_key=false;
                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        exit;
                    }


                    $family=new Category($data['subject_key']);


                    $sql=sprintf("select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s",
                        $family->get('Category Store Key'),
                        prepare_mysql($family->get('Category Code'))
                    );


                    if ($result=$db->query($sql)) {
                        if ($family = $result->fetch()) {
                            $family_key=$department['Product Department Key'];
                        }else {
                            $family_key=false;
                        }
                    }else {
                        print_r($error_info=$db->errorInfo());
                        exit;
                    }


                    if ($family_key and $department_key) {


                        $sql=sprintf("update `Product Family Dimension` set `Product Family Main Department Key`=0, `Product Family Main Department Code`='', `Product Family Main Department Name`='' where `Product Family Key`=%d",

                            $family_key);


                        $db->exec($sql);

                        $sql=sprintf("update `Product Dimension` set `Product Main Department Key`=0, `Product Main Department Code`='', `Product Main Department Name`='' where `Product Family Key`=%d",

                            $family_key
                        );
                        $db->exec($sql);

                    }

                }


            }
        }
        */
        //----------

    }

    $response = array(
        'state'    => 200,
        'metadata' => $object->get_update_metadata()
    );
    echo json_encode($response);

}


function create_time_series($account, $db, $data, $editor) {


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


function create_isf($account, $db, $data, $editor) {


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


}

function calculate_sales($account, $db, $data, $editor) {


    require_once 'utils/new_fork.php';

    $data['editor'] = $editor;

    list($fork_key, $msg) = new_fork(
        'au_calculate_sales', $data, $account->get('Account Code'), $db
    );


    $response = array(
        'state'    => 200,
        'fork_key' => $fork_key,
        'msg'      => $msg

    );
    echo json_encode($response);


}


function edit_image($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.Image.php';


    $sql = sprintf(
        'SELECT `Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $data['image_bridge_key']
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            $object         = get_object(
                $row['Image Subject Object'], $row['Image Subject Object Key']
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
                            'SELECT `Product ID` FROM `Product Dimension` WHERE  `Category Code`=%s  ', prepare_mysql($object->get('Reference'))
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $product         = get_object('Product', $row['Product ID']);
                                $product->editor = $this->editor;
                                $product->link_image($image->id, $object_image_scope);

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

function regenerate_api($account, $db, $user, $editor, $data, $smarty) {

    $api_key         = get_object('api_key', $data['api_key']);
    $api_key->editor = $editor;
    $private_key     = $api_key->regenerate_private_key();

    $response = array(
        'state'  => 200,
        'qrcode' => $api_key->get('Address').','.$api_key->get('Code').','.$private_key

    );
    echo json_encode($response);


}

function disassociate_category($account, $db, $data, $editor){

    $category=get_object('Category',$data['parent_key']);
    $category->editor;
    $category->disassociate_subject($data['child_key']);

    $response = array(
        'state'  => 200,

    );
    echo json_encode($response);
}


?>
