<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 12:10:26 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function get_object_fields($object, $db, $user, $smarty, $options = false) {


    $account = new Account($db);

    $edit = true;




    switch ($object->get_object_name()) {
        case 'Page':
            include 'fields/webpage.fld.php';

            return $object_fields;
            break;
        case 'Account':

            if ($options['type'] == 'suppliers.settings') {
                include 'fields/suppliers.settings.fld.php';
            } elseif ($options['type'] == 'setup') {
                include 'fields/account.setup.fld.php';
            } else {
                include 'fields/account.fld.php';
            }


            return $object_fields;
            break;
        case 'Material':
            include 'fields/material.fld.php';

            return $object_fields;
            break;
        case 'Image':
            include 'fields/image.fld.php';

            return $object_fields;
            break;
        case 'Attachment':

            $object_fields = array();
            if ($options['type'] == 'employee') {

                $options_Attachment_Subject_Type = array(
                    'CV'       => _('Curriculum vitae'),
                    'Contract' => _('Employment contract'),
                    'Other'    => _('Other'),

                );

            } elseif ($options['type'] == 'supplier') {
                $options_Attachment_Subject_Type = array(
                    'Invoice'       => _('Invoice'),
                    'PurchaseOrder' => _('Purchase order'),
                    'Catalogue'     => _('Catalogue'),
                    'Image'       => _('Image'),
                    'Contact Card'       => _('Contact card'),
                    'Other'         => _('Other'),

                );

            } elseif ($options['type'] == 'part') {
                $options_Attachment_Subject_Type = array(
                    'Other' => _('Other'),
                    'MSDS'  => _('MSDS'),


                );

            }

            include 'fields/attachment.fld.php';

            return $object_fields;
            break;

        case 'Supplier Delivery':
            include 'fields/supplier.delivery.fld.php';

            return $object_fields;
            break;
        case 'Webpage':
            include 'fields/webpage.fld.php';

            return $object_fields;
            break;
        case 'Webpage Version':
            include 'fields/webpage_version.fld.php';

            return $object_fields;
            break;
        case 'Website Node':
            include 'fields/website.node.fld.php';

            return $object_fields;
            break;
        case 'Category':

            if (isset($options['type']) and $options['type'] == 'webpage_settings') {
                include 'fields/category.webpage.fld.php';
            } else {

                include 'fields/category.fld.php';
            }


            return $category_fields;
            break;
        case 'Purchase Order':
            include 'fields/supplier.order.fld.php';

            return $object_fields;
            break;
        case 'Order':
            include 'fields/order.fld.php';

            return $object_fields;
            break;
        case 'Deal Campaign':
            include 'fields/campaign.fld.php';

            return $object_fields;
            break;
        case 'Deal':
            include 'fields/deal.fld.php';

            return $object_fields;
            break;
        case 'Website':
            include 'fields/website.fld.php';

            return $object_fields;
            break;
        case 'Agent':

            if (isset($options['type']) and $options['type'] == 'user') {
                include 'fields/user.system.fld.php';
            } else {

                include 'fields/agent.fld.php';
            }

            return $object_fields;
            break;
        case 'Barcode':
            include 'fields/barcode.fld.php';

            return $barcode_fields;
            break;
        case 'User':

            if ($options['type'] == 'profile') {
                include 'fields/profile.fld.php';
            } else {
                include 'fields/user.system.fld.php';
            }

            return $object_fields;
            break;
        case 'Customer':

            include 'fields/customer.fld.php';

            return $customer_fields;
            break;
        case 'Product':
        case 'StoreProduct':


            $object->get_webpage();
            if (isset($options['type']) and $options['type'] == 'webpage_settings') {
                include 'fields/product.webpage.fld.php';
            } else {

                include 'fields/product.fld.php';
            }


            return $product_fields;
            break;

        case 'Supplier':

            if (isset($options['type']) and $options['type'] == 'user') {
                include 'fields/user.system.fld.php';
            } else {
                include 'fields/supplier.fld.php';
            }

            return $object_fields;
            break;

        case 'Supplier Part':

            $object->get_supplier_data();

            if ($user->get('User Type') != 'Agent') {

                if ($options['parent'] == 'supplier') {


                    $supplier = $options['parent_object'];

                    include 'fields/supplier_part.fld.php';


                    if (isset($options['new'])) {
                        $object = new Part(0);
                        include 'fields/part.fld.php';
                        $supplier_part_fields = array_merge(
                            $supplier_part_fields, $part_fields
                        );
                    } else {


                        $part = get_object('Part', $object->get('Supplier Part Part SKU'));

                        $object_fields_part = get_object_fields($part, $db, $user, $smarty, array('supplier_part_scope' => true));

                        $supplier_part_fields = array_merge($supplier_part_fields, $object_fields_part);

                        $operations = array(
                            'label'      => _('Operations'),
                            'show_title' => true,
                            'class'      => 'operations',
                            'fields'     => array(

                                array(
                                    'id'    => 'delete_supplier_part',
                                    'class' => 'operation',
                                    'value' => '',
                                    'label' => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'
                                        .$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._(
                                            "Delete supplier's part & related part"
                                        ).' <i class="fa fa-trash new_button link"></i></span>',

                                    'reference' => '',
                                    'type'      => 'operation'
                                ),


                            )

                        );

                        $supplier_part_fields[] = $operations;
                    }


                    return $supplier_part_fields;
                } elseif ($options['parent'] == 'part') {
                    include 'fields/part.supplier_part.new.fld.php';

                    return $supplier_part_fields;
                }
            } else {

                $agent = $options['parent_object'];

                $part = get_object('Part', $object->get('Supplier Part Part SKU'));

                include 'fields/agent_part.fld.php';

                return $supplier_part_fields;


            }
            break;

        case 'Part':

            if (isset($options['new'])) {
                $object = get_object('Supplier Part', 0);
                $object->get_supplier_data();
                include 'fields/supplier_part.fld.php';

                $object = new Part(0);
                include 'fields/part.fld.php';
                $part_fields = array_merge($supplier_part_fields, $part_fields);
            } else {
                include 'fields/part.fld.php';
            }

            return $part_fields;
            break;

        case 'Warehouse':
            include 'fields/warehouse.fld.php';

            return $object_fields;
            break;
        case 'Location':
            include 'fields/location.fld.php';

            return $object_fields;
            break;
        case 'Store':

            if (isset($options['new'])) {


            } else {


                if (!in_array($object->id, $user->stores)) {
                    $edit = false;
                }
            }
            include 'fields/store.fld.php';

            return $object_fields;
            break;
        case 'Staff':


            if ($object->get('Staff Type') == 'Contractor') {

                if (isset($options['type']) and $options['type'] == 'user') {
                    include 'fields/user.system.fld.php';
                } else {
                    include 'fields/contractor.fld.php';
                }

            } else {

                if (isset($options['type']) and $options['type'] == 'user') {
                    include 'fields/user.system.fld.php';
                } else {
                    include 'fields/employee.fld.php';
                }


            }

            return $object_fields;
            break;

        default:
            print $object->get_object_name();
            return '';
            break;
    }

}


?>
