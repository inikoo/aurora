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

    case 'publish_webpage':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),


                     )
        );
        publish_webpage($data, $editor, $db);
        break;
    case 'update_product_category_index':


        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),

                         'key'   => array('type' => 'key'),
                         'type'  => array('type' => 'string'),
                         'value' => array('type' => 'string')


                     )
        );
        update_product_category_index($data, $editor, $db);
        break;
    case 'update_webpage_related_product':


        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),

                         'key'   => array('type' => 'key'),
                         'type'  => array('type' => 'string'),
                         'value' => array('type' => 'string')


                     )
        );
        update_webpage_related_product($data, $editor, $db);
        break;
    case 'webpage_content_data':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),
                         'section'    => array('type' => 'string'),
                         'block'      => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'value'      => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'type'       => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                     )
        );
        webpage_content_data($data, $editor, $db, $smarty);
        break;
    case 'edit_webpage':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'   => array('type' => 'key'),
                         'field' => array('type' => 'string'),
                         'value' => array('type' => 'string'),

                     )
        );
        edit_webpage($data, $editor, $db);

        break;

    case 'edit_category_stack_index':
        $data = prepare_values(
            $_REQUEST, array(
                         'stack_index' => array('type' => 'numeric'),
                         'key'         => array('type' => 'key'),
                         'subject_key' => array('type' => 'key'),
                         'webpage_key' => array('type' => 'key'),

                     )
        );
        edit_category_stack_index($data, $editor, $smarty, $db);

        break;
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
    case 'refresh_webpage_see_also':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),
                         'key'    => array('type' => 'key')
                     )
        );
        refresh_webpage_see_also($account, $db, $user, $editor, $data, $smarty);

        break;
    case 'edit_item_in_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'field'             => array('type' => 'string'),
                         'parent'            => array('type' => 'string'),
                         'parent_key'        => array('type' => 'key'),
                         'item_key'          => array('type' => 'key'),
                         'item_historic_key' => array('type' => 'key'),
                         'transaction_key'   => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'qty'               => array('type' => 'numeric'),

                     )
        );
        edit_item_in_order($account, $db, $user, $editor, $data, $smarty);
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


    $object = get_object(
        $data['object'], $data['key'], $load_other_data = true
    );


    if (!$object->id) {
        $response = array(
            'state' => 405,
            'resp'  => 'Object not found'
        );
        echo json_encode($response);
        exit;

    }

    $object->editor = $editor;

    $field = preg_replace('/_/', ' ', $data['field']);


    $formatted_field = preg_replace(
        '/^'.$object->get_object_name().' /', '', $field
    );


    if ($field == 'Staff Position' and $data['object'] == 'User') {
        $formatted_field = 'Position';
    }


    if (preg_match('/ Telephone$/', $field)) {
        $options = 'no_history';
    } else {
        $options = '';
    }


    if (isset($data['metadata'])) {


        $object->update(
            array($field => $data['value']), $options, $data['metadata']
        );

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
                    '<span class="deleted">%s</span> <span class="discret"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $object->deleted_value, $data['field'],
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

            } elseif ($field == 'Webpage See Also') {
                $smarty->assign('data', $object->get_see_also_data());
                $smarty->assign('mode', 'edit');

                $update_metadata['webpage_see_also_editor'] = $smarty->fetch(
                    'webpage_see_also.edit.tpl'
                );

            } elseif ($field == 'Webpage Related Products') {
                $smarty->assign('data', $object->get_related_products_data());
                $smarty->assign('mode', 'edit');

                $update_metadata['webpage_related_products_editor'] = $smarty->fetch('webpage_related_products.edit.tpl');

            }


        } elseif (isset($object->field_deleted)) {
            $msg             = sprintf(
                '<span class="discret"><i class="fa fa-check " onClick="hide_edit_field_msg(\'%s\')" ></i> %s</span>', $data['field'], _('Deleted')
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
                $directory          = $smarty->fetch(
                    'delivery_addresses_directory.tpl'
                );
                $items_in_directory = count(
                    $object->get_other_delivery_addresses_data()
                );
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
            'items_in_directory' => $items_in_directory,

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


    $object         = get_object($data['object'], $data['key']);
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
        case 'delete':
            $request = $object->delete();
            break;
        case 'archive':
            $request = $object->archive();
            break;
        case 'unarchive':
            $request = $object->unarchive();
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


function new_object($account, $db, $user, $editor, $data, $smarty) {


    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;

    $metadata = array();

    switch ($data['object']) {
        case 'Location':
            include_once 'class.Location.php';

            $data['fields_data']['user'] = $user;


            $object = $parent->create_location($data['fields_data']);


            if (!$parent->error) {

                $smarty->assign('account', $account);
                $smarty->assign('parent', $parent);

                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/location.pcard.tpl'
                );
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
            $object = $parent->create_order($data['fields_data']);
            if (!$parent->error) {

                $pcard        = '';
                $updated_data = array();
            }
            break;

        case 'Category_Product':

            include_once 'class.Product.php';

            if (isset($data['fields_data']['Store Product Code'])) {


                $object = new Product(
                    'store_code', $parent->get('Category Store Key'), $data['fields_data']['Store Product Code']
                );
            } else {
                $object = new Product(
                    $data['fields_data']['Store Product Key']
                );

            }

            if ($object->id) {

                $parent->associate_subject($object->id);


                // Migration -----
                /*
                $category=$parent;
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
                                    $object->id
                                );

                                $db->exec($sql);
                                // print $sql;
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


                        $family=new Category($object->id);


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
            $object = $parent->create_product($data['fields_data']);

            if ($parent->new_product) {

                $smarty->assign('object', $object);

                $pcard        = $smarty->fetch(
                    'presentation_cards/product.pcard.tpl'
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
                $object = $parent->create_customer($data['fields_data']);
                $smarty->assign('account', $account);
                $smarty->assign('object', $object);

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
            'metadata'     => $metadata
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

            //'Staff','Customer Communications','Customer History Attachment','Product History Attachment','Part History Attachment','Part MSDS','Product MSDS','Supplier Product MSDS','Product Info Sheet','Purchase Order History Attachment','Purchase Order','Supplier Delivery Note History Attachment','Supplier Delivery Note','Supplier Invoice History Attachment','Supplier Invoice','Order Note History Attachment','Delivery Note History Attachment','Invoice History Attachment'
            switch ($row['Subject']) {
                case 'Customer Communications':
                case 'Customer History Attachment':
                    $_object = 'Customer';
                    break;
                case 'Staff':
                    $_object = 'Staff';
                    $request = 'employee/'.$row['Subject Key'];
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


function edit_item_in_order($account, $db, $user, $editor, $data, $smarty) {

    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;

    $transaction_data = $parent->update_item($data);

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


function refresh_webpage_see_also($account, $db, $user, $editor, $data, $smarty) {

    // TODO remove this when class Webpage is implemented
    $data['object'] = 'old_page';

    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;


    $see_also = $object->update_see_also();

    $see_also_data = $object->get_see_also_data();

    $links = '';
    foreach ($see_also_data['links'] as $link) {
        $links .= sprintf(
            '<tr class="webpage_tr"><td></td><td>%s</td></tr>', $link['code']
        );

    }


    $response = array(
        'state'                 => 200,
        'links'                 => $links,
        'see_also_last_updated' => $see_also_data['last_updated']
    );
    echo json_encode($response);

}

function create_time_series($account, $db, $data, $editor) {


    require_once 'utils/new_fork.php';

    $data['editor'] = $editor;

    list($fork_key, $msg) = new_fork(
        'au_time_series', $data, $account->get('Account Code'), $db
    );


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

function edit_category_stack_index($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $object         = get_object('category', $data['key']);
    $object->editor = $editor;

    $object->change_subject_stack($data['stack_index'], $data['subject_key']);


    $response = array(
        'state'   => 200,
        'publish' => $webpage->get('Publish')


    );


    $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);


    if (isset($products_html)) {
        $response['products'] = $products_html;
    }

    echo json_encode($response);


}

function edit_webpage($data, $editor, $db) {


    // todo migrate to Webpage & WebpageVersion classes

    include_once('class.Page.php');
    $webpage = new Page($data['key']);

    switch ($data['field']) {
        case 'css':
            $value = base64_decode($data['value']);


            $webpage->update(array('Page Store CSS' => $value), 'no_history');


            break;
        default:
            break;
    }

    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );

    echo json_encode($response);

}

function webpage_content_data($data, $editor, $db, $smarty) {
    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['parent_key']);

    $content_data = $webpage->get('Content Data');

    if ($data['type'] == 'text') {


        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-e" style="z-index: 90; display: block;"></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90; display: block;"></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90; display: block;"></div>', '', $data['value']);


        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"><br></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"><br></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"><br></div>', '', $data['value']);


        if (isset($content_data[$data['section']])) {


            if ($data['section'] == 'panels') {
                //print_r($data);
                //print_r($content_data['panels']);

                foreach ($content_data['panels'] as $key => $value) {
                    if ($value['id'] == $data['block']) {
                        //   print 'xxxxx';
                        $content_data['panels'][$key]['content'] = $data['value'];
                    }
                    break;
                }


            } else {
                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {

                    $content_data[$data['section']]['blocks'][$data['block']]['content'] = $data['value'];
                } else {
                    $content_data[$data['section']]['blocks'][$data['block']] = array(
                        'content' => $data['value'],
                        'type'    => $data['type']
                    );

                }
            }


        }
    } elseif ($data['type'] == 'caption') {
        if (isset($content_data[$data['section']])) {


            if ($data['section'] == 'panels') {


                foreach ($content_data[$data['section']] as $panel_key => $panel) {
                    if ($data['block'] == $panel['id']) {

                        $content_data[$data['section']][$panel_key]['caption'] = $data['value'];
                        break;
                    }
                }

            } else {


                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {
                    $content_data[$data['section']]['blocks'][$data['block']]['caption'] = $data['value'];
                }
            }

        }
    } elseif ($data['type'] == 'code') {



        if (isset($content_data[$data['section']])) {


            if ($data['section'] == 'panels') {


                foreach ($content_data[$data['section']] as $panel_key => $panel) {
                    if ($data['block'] == $panel['id']) {

/*
                        $code=base64_decode(rawurldecode($data['value']));
                       // $data['value']=$code;

print_r($_REQUEST);
                        print_r(rawurldecode($data['value']));
                        print_r(base64_decode($data['value']));
*/


                        $code=base64_decode($data['value']);

                      //  exit;


                        $content_data['panels'][$panel_key]['content'] = $code;

                  //      print_r($content_data);

                        $sql = sprintf(
                            'UPDATE `Webpage Panel Dimension` SET `Webpage Panel Data`=%s ,`Webpage Panel Metadata`=%s WHERE `Webpage Panel Key`=%d ',
                            prepare_mysql($code),
                            prepare_mysql(json_encode($content_data['panels'][$panel_key])),
                            $content_data['panels'][$panel_key]['key']
                        );
                        $db->exec($sql);
                        break;
                    }
                }

            }

        }
    } elseif ($data['type'] == 'link') {
        if (isset($content_data[$data['section']])) {


            if ($data['section'] == 'panels') {


                foreach ($content_data[$data['section']] as $panel_key => $panel) {
                    if ($data['block'] == $panel['id']) {

                        $content_data[$data['section']][$panel_key]['link'] = $data['value'];
                        break;
                    }
                }

            } else {


                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {
                    $content_data[$data['section']]['blocks'][$data['block']]['link'] = $data['value'];
                }
            }

        }
    } elseif ($data['type'] == 'add_class') {
        if (isset($content_data[$data['section']])) {


            if ($data['block'] != '') {


                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {

                    if (isset($content_data[$data['section']]['blocks'][$data['block']]['class'])) {

                        $classes = preg_split('/\s+/', $content_data[$data['section']]['blocks'][$data['block']]['class']);

                        foreach (preg_split('/\s+/', $data['value']) as $value) {
                            if (!in_array($data['value'], $classes)) {
                                $classes[] = $value;
                            }
                        }


                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = join(' ', $classes);
                    } else {
                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = $data['value'];
                    }


                }

            } else {

                if (isset($content_data[$data['section']]['class'])) {

                    $classes = preg_split('/\s+/', $content_data[$data['section']]['class']);

                    foreach (preg_split('/\s+/', $data['value']) as $value) {
                        if (!in_array($data['value'], $classes)) {
                            $classes[] = $value;
                        }
                    }


                    $content_data[$data['section']]['class'] = join(' ', $classes);
                } else {
                    $content_data[$data['section']]['class'] = $data['value'];
                }


            }

        }
    } elseif ($data['type'] == 'remove_class') {
        if (isset($content_data[$data['section']])) {


            if ($data['block'] != '') {

                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {

                    if (isset($content_data[$data['section']]['blocks'][$data['block']]['class'])) {

                        $classes = preg_split('/\s/', $content_data[$data['section']]['blocks'][$data['block']]['class']);
                        foreach (preg_split('/\s+/', $data['value']) as $value) {
                            unset($classes[$value]);
                        }


                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = trim(join(' ', $classes));
                    } else {
                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = '';
                    }


                }

            } else {
                if (isset($content_data[$data['section']]['class'])) {

                    $classes = preg_split('/\s+/', $content_data[$data['section']]['class']);


                    $classes = array_diff($classes, preg_split('/\s+/', $data['value']));


                    $content_data[$data['section']]['class'] = trim(join(' ', $classes));
                } else {
                    $content_data[$data['section']]['class'] = '';
                }

            }

        }
    } elseif ($data['type'] == 'add_image') {
        if (isset($content_data[$data['section']])) {


            $content_data[$data['section']]['blocks'][$data['block']] = array(
                'type'      => 'image',
                'image_src' => $data['value'],
                'caption'   => '',
                'class'     => ''

            );


        }
    } elseif ($data['type'] == 'remove_block') {
        if (isset($content_data[$data['section']])) {


            unset($content_data[$data['section']]['blocks'][$data['block']]);


        }
    } elseif ($data['type'] == 'add_panel') {

        $panel_data = json_decode($data['value'], true);


        $size_tag = $panel_data['size'].'x';

        $panel = array(
            'id'   => $data['block'],
            'type' => $panel_data['type'],
            'size' => $size_tag

        );

        if ($panel_data['type'] == 'image') {


            $panel['image_src'] = '/art/panel_'.$size_tag.'_1.png';
            $panel['link']      = '';
            $panel['caption']   = '';
        } else {
            if ($panel_data['type'] == 'text') {

                $panel['content'] = 'bla bla bla';
                $panel['class']   = 'text_panel_default';

            } else {
                if ($panel_data['type'] == 'code') {

                    $panel['content'] = '';
                    $panel['class']   = 'code_panel_default';

                }
            }
        }


        $sql = sprintf(
            'INSERT INTO `Webpage Panel Dimension` (`Webpage Panel Id`,`Webpage Panel Webpage Key`,`Webpage Panel Type`,`Webpage Panel Data`,`Webpage Panel Metadata`) VALUES (%s,%d,%s,%s,%s) ',
            prepare_mysql($data['block']), $webpage->id, prepare_mysql($panel_data['type']), ($panel_data['type'] == 'code' ? prepare_mysql($panel['content']) : ''), prepare_mysql(json_encode($panel))

        );

        $db->exec($sql);
        $panel['key'] = $db->lastInsertId();


        $content_data['panels'][$panel_data['stack_index']] = $panel;

        ksort($content_data['panels']);

        $webpage->load_scope();
        if ($webpage->scope_found == 'Category') {
            $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);

        }

        //  print_r( $content_data);

    } elseif ($data['type'] == 'remove_panel') {


        if (isset($content_data['panels'])) {


        //    print_r($content_data['panels']);
            foreach ($content_data['panels'] as $panel_key => $panel) {
                if ($panel['id'] == $data['block']) {
                    $sql = sprintf('DELETE FROM `Webpage Panel Dimension` WHERE  `Webpage Panel Key`=%d ',
                                   $content_data['panels'][$panel_key]['key']
                                   );
                    $db->exec($sql);

                    unset($content_data['panels'][$panel_key]);
                    break;

                }

            }

            $webpage->load_scope();
            if ($webpage->scope_found == 'Category') {
                $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);

            }


        }


    }

    //print_r($content_data);
    //exit;

    $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish'),


    );

    if (isset($products_html)) {
        $response['products'] = $products_html;
    }

    echo json_encode($response);


}

function update_product_category_index($data, $editor, $db) {

    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $sql = sprintf(
        'SELECT `Product Category Index Key`,`Product Category Index Content Data` FROM `Product Category Index` WHERE `Product Category Index Key`=%d ', $data['key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Product Category Index Content Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {

                $product_content_data = json_decode($row['Product Category Index Content Data'], true);

            }

            $product_content_data[$data['type']] = $data['value'];


            $sql = sprintf(
                'UPDATE `Product Category Index` SET `Product Category Index Content Data`=%s   WHERE `Product Category Index Key`=%d ', prepare_mysql(json_encode($product_content_data)),
                $row['Product Category Index Key']
            );
            $db->exec($sql);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);

    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );

    if (isset($products_html)) {
        $response['products'] = $products_html;
    }


    echo json_encode($response);


}


function update_webpage_related_product($data, $editor, $db) {

    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $sql = sprintf(
        'SELECT `Webpage Related Product Key`,`Webpage Related Product Content Data` FROM `Webpage Related Product Bridge` WHERE `Webpage Related Product Key`=%d ', $data['key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Webpage Related Product Content Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {

                $product_content_data = json_decode($row['Webpage Related Product Content Data'], true);

            }

            $product_content_data[$data['type']] = $data['value'];


            $sql = sprintf(
                'UPDATE `Webpage Related Product Bridge` SET `Webpage Related Product Content Data`=%s   WHERE `Webpage Related Product Key`=%d ', prepare_mysql(json_encode($product_content_data)),
                $row['Webpage Related Product Key']
            );
            $db->exec($sql);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );
    echo json_encode($response);


}

function publish_webpage($data, $editor, $db) {

    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['parent_key']);

    $webpage->publish();


    $response = array(
        'state' => 200

    );
    echo json_encode($response);


}


function get_products_html($data, $content_data, $webpage, $smarty, $db) {

    include_once 'class.Public_Product.php';
    $public_category = new Public_Category($webpage->scope->id);
    $public_category->load_webpage();


    if (isset($content_data['panels'])) {
        $panels = $content_data['panels'];
    } else {
        $panels = array();
    }

    ksort($panels);
    $products = array();

    $sql = sprintf(
        "SELECT `Product Category Index Key`,`Product Category Index Content Data`,`Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
        $public_category->id
    );


    $stack_index = 0;
    if ($result = $db->query($sql)) {

        foreach ($result as $row) {


            if (isset($panels[$stack_index])) {
                $products[] = array(
                    'type' => 'panel',
                    'data' => $panels[$stack_index]
                );

                $size = floatval($panels[$stack_index]['size']);


                unset($panels[$stack_index]);
                $stack_index += $size;

                list($stack_index, $products) = get_next_panel($stack_index, $products, $panels);

            }


            if ($row['Product Category Index Content Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {
                $product_content_data = json_decode($row['Product Category Index Content Data'], true);

            }

            $products[] = array(
                'type'        => 'product',
                'object'      => new Public_Product($row['Product ID']),
                'index_key'   => $row['Product Category Index Key'],
                'header_text' => (isset($product_content_data['header_text']) ? $product_content_data['header_text'] : '')
            );

            $stack_index++;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    //  print_r($products);


    $panel_rows          = array();
    $max_row_free_slots  = array();
    $max_cell_free_slots = array();

    $row_index = -1;

    $stack_index = -1;

    foreach ($products as $key => $item) {


        if ($item['type'] == 'product') {
            $stack_index++;
        } else {
            $stack_index += floatval($item['data']['size']);
        }
        $products[$key]['stack_index'] = $stack_index;


        $current_row = floor($stack_index / 4);
        if ($row_index != $current_row) {
            //       print "- $current_row \n";
            $row_index          = $current_row;
            $max_free_slots     = 0;
            $current_free_slots = 0;


        }

        if ($item['type'] == 'product') {
            $current_free_slots++;
            if ($current_free_slots > $max_free_slots) {
                $max_free_slots = $current_free_slots;
            }
        } else {

            //$key+=floatval($item['data']['size'])-1;

            if ($current_free_slots > $max_free_slots) {
                $max_free_slots = $current_free_slots;
            }
            $current_free_slots = 0;
        }


        //      print "$stack_index ".($stack_index%4)." ".floor($stack_index/4)." | $current_free_slots $max_free_slots  \n";
        if ($item['type'] == 'panel') {


            if (isset($panel_rows[floor($stack_index / 4)])) {
                $panel_rows[floor($stack_index / 4)] += floatval($item['data']['size']);
            } else {
                $panel_rows[floor($stack_index / 4)] = floatval($item['data']['size']);
            }

        }

        $max_row_free_slots[$current_row] = $max_free_slots;


        if ($stack_index % 4 == 1 and $item['type'] != 'product' and $products[$stack_index - 1]['type'] == 'product') {
            $max_cell_free_slots[$stack_index - 1] = 1;

        }


    }

    //   print_r(  $max_row_free_slots);
    //    print_r(  $max_cell_free_slots);

    $stack_index = -1;
    foreach ($products as $key => $item) {

        if ($item['type'] == 'product') {
            $stack_index++;
        } else {
            $stack_index += floatval($item['data']['size']);
        }

        $current_row = floor($stack_index / 4);
        if (isset($panel_rows[$current_row])) {
            $panels_in_row = $panel_rows[$current_row];
        } else {
            $panels_in_row = 0;
        }
        $products[$key]['data']['panels_in_row']  = $panels_in_row;
        $products[$key]['data']['max_free_slots'] = $max_row_free_slots[$current_row];
        if (isset($max_cell_free_slots[$stack_index])) {
            $products[$stack_index]['data']['max_free_slots'] = $max_cell_free_slots[$stack_index];
        }


    }
    // print_r($panel_rows);
    // print_r($products);


    $related_products = array();

    $sql = sprintf(
        "SELECT `Webpage Related Product Key`,`Webpage Related Product Product ID`,`Webpage Related Product Content Data`  FROM `Webpage Related Product Bridge` B  LEFT JOIN `Product Dimension` P ON (`Webpage Related Product Product ID`=P.`Product ID`)  WHERE  `Webpage Related Product Page Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Webpage Related Product Order`",
        $webpage->id
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Webpage Related Product Content Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {
                $product_content_data = json_decode($row['Webpage Related Product Content Data'], true);

            }

            $related_products[] = array(
                'header_text' => (isset($product_content_data['header_text']) ? $product_content_data['header_text'] : ''),
                'object'      => new Public_Product($row['Webpage Related Product Product ID']),
                'index_key'   => $row['Webpage Related Product Key'],


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $smarty->assign('products', $products);
    $smarty->assign('content_data', $content_data);

    $smarty->assign('category', $public_category);


    return $smarty->fetch('category.webpage.preview.products.tpl');


}

function get_next_panel($stack_index, $products, $panels) {

    if (isset($panels[$stack_index])) {
        $products[] = array(
            'type' => 'panel',
            'data' => $panels[$stack_index]
        );

        $size = floatval($panels[$stack_index]['size']);
        unset($panels[$stack_index]);
        $stack_index += $size;
        list($stack_index, $products) = get_next_panel($stack_index, $products, $panels);
    }

    return array(
        $stack_index,
        $products
    );

}


?>
