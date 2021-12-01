<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 12:10:26 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


/**
 * @param       $db     \PDO
 * @param       $user   \User
 * @param       $smarty \Smarty
 * @param array $options
 *
 * @return array|string
 */
function get_object_fields($object, PDO $db, User $user, Smarty $smarty, $options = false) {

    /**
     * @var array $object_fields
     */

    $account = new Account($db);
    $edit    = true;

    switch ($object->get_object_name()) {
        case 'Email Campaign':// to delete this line
        case 'Mailshot':


            if ($options['new']) {


                switch ($options['parent_object']->get('Email Campaign Type Code')) {
                    case 'Marketing':
                        include 'fields/mailshot.marketing.new.fld.php';

                }
            } else {


                switch ($object->get('Email Campaign Type')) {
                    case 'AbandonedCart':
                        include 'fields/mailshot.abandoned_cart.fld.php';
                        break;
                    case 'Invite Full Mailshot':
                        include 'fields/mailshot.prospects_invite.fld.php';
                        break;
                    case 'Marketing':
                        include 'fields/mailshot.marketing.fld.php';
                        break;
                    default:
                        include 'fields/mailshot.fld.php';
                }
            }


            return $object_fields;
        case 'Charge':
            include 'fields/charge.fld.php';

            return $object_fields;

        case 'Delivery Note':


            if (!empty($options['retry_shipment_label'])) {
                include 'fields/retry_shipment_label.fld.php';
            } else {
                include 'fields/delivery_note.fld.php';
            }


            return $object_fields;

        case 'Invoice':
            include 'fields/invoice.fld.php';

            return $object_fields;

        case 'Payment Account':
            include 'fields/payment_account.fld.php';

            return $object_fields;
        case 'Payment':
            include 'fields/payment.fld.php';

            return $object_fields;

        case 'Webpage':
        case 'Page':


            include 'fields/webpage.fld.php';

            return $object_fields;
        case 'Account':

            if ($options['type'] == 'suppliers.settings') {
                include 'fields/suppliers.settings.fld.php';
            } elseif ($options['type'] == 'setup') {
                include 'fields/account.setup.fld.php';
            } else {
                include 'fields/account.fld.php';
            }


            return $object_fields;

        case 'Material':
            include 'fields/material.fld.php';

            return $object_fields;

        case 'Supplier Delivery':
            include 'fields/supplier.delivery.fld.php';

            return $object_fields;
        case 'Fulfilment Delivery':
            include 'fields/fulfilment.delivery.fld.php';

            return $object_fields;
        case 'Category':
            include 'fields/category.fld.php';

            /** @var array $category_fields */
            return $category_fields;

        case 'Purchase Order':

            if ($object->get('Purchase Order Type') == 'Production') {
                include 'fields/job_order.fld.php';

            } else {
                include 'fields/supplier.order.fld.php';
            }

            return $object_fields;

        case 'Order':
            include 'fields/order.fld.php';

            return $object_fields;

        case 'Deal Campaign':


            if ($options['store']->get('Store Order Recursion Campaign Key') == $object->id) {


                include 'fields/campaign_order_recursion.fld.php';

            } elseif ($options['store']->get('Store Bulk Discounts Campaign Key') == $object->id) {

                $deals = $object->get_deals();
                $deal  = array_pop($deals);
                $store = $options['store'];

                include 'fields/campaign_bulk_discounts.fld.php';

            } elseif ($object->get('Deal Campaign Code') == 'VO') {

                $deals = $object->get_deals();
                $deal  = array_pop($deals);
                $store = $options['store'];

                include 'fields/vouchers.fld.php';

            } else {
                include 'fields/campaign.fld.php';

            }


            return $object_fields;
        case 'Deal':

            if (isset($options['new'])) {


                switch ($options['parent']) {
                    case 'campaign':


                        switch ($options['parent_object']->get('Deal Campaign Code')) {
                            case 'VO':
                                include 'fields/new_voucher.fld.php';
                                break;
                            case 'FO':
                                $store = get_object('Store', $options['store_key']);
                                include 'fields/new_first_order_offer.fld.php';
                                break;
                            case 'CA':
                                $store = get_object('Store', $options['store_key']);
                                include 'fields/new_category_deal.fld.php';
                                break;
                            case 'CU':
                                $store = get_object('Store', $options['store_key']);
                                include 'fields/new_customers_deal.fld.php';
                                break;
                            case 'SO':
                                $store = get_object('Store', $options['store_key']);
                                include 'fields/new_store_deal.fld.php';
                                break;
                            default:
                                $store = get_object('Store', $options['store_key']);

                                include 'fields/new_deal.fld.php';
                        }


                        break;
                    case 'category':

                        $smarty->assign('control_class', 'hide');

                        $smarty->assign('overwrite_parent_key', $options['store_key']);


                        include 'fields/new_category_deal.fld.php';


                        break;
                }

            } else {

                $campaign = get_object('DealCampaign', $object->get('Deal Campaign Key'));
                $store    = get_object('Store', $object->get('Deal Store Key'));


                switch ($campaign->get('Deal Campaign Code')) {
                    case 'VO':
                        include 'fields/voucher.fld.php';
                        break;
                    case 'VL':
                        include 'fields/bulk_deal.fld.php';
                        break;
                    case 'FO':
                        include 'fields/first_order_offer.fld.php';
                        break;
                    default:
                        include 'fields/deal.fld.php';
                }


            }

            return $object_fields;
        case 'Deal Component':

            if (isset($options['new'])) {
                switch ($options['parent']) {
                    case 'campaign':

                        switch ($options['parent_object']->get('Deal Campaign Code')) {
                            case 'VO':
                                $store = get_object('Store', $options['store_key']);
                                include 'fields/new_voucher.fld.php';
                                break;

                            default:
                                $store = get_object('Store', $options['store_key']);

                                include 'fields/new_deal.fld.php';
                        }


                        break;
                    case 'category':

                        $smarty->assign('control_class', 'hide');

                        $smarty->assign('overwrite_parent_key', $options['store_key']);

                        include 'fields/new_category_deal_component.fld.php';


                        break;
                }

            } else {

                $campaign = get_object('DealCampaign', $object->get('Deal Component Campaign Key'));
                // $store    = get_object('Store', $object->get('Deal Store Key'));


                switch ($campaign->get('Code')) {
                    case 'OR':
                        include 'fields/order_recursion_deal_component.fld.php';
                        break;
                    case 'VL':
                        include 'fields/bulk_deal_component.fld.php';
                        break;
                    default:
                        include 'fields/deal_component.fld.php';
                }


            }

            return $object_fields;
        case 'Website':
            if (!empty($options['new'])) {
                include 'fields/website.new.fld.php';
            } else {
                if (!empty($options['localization'])) {
                    include 'fields/website_localization.fld.php';
                } else {
                    include 'fields/website.fld.php';
                }
            }

            return $object_fields;
        case 'Agent':


                include 'fields/agent.fld.php';


            return $object_fields;
        case 'Barcode':
            include 'fields/barcode.fld.php';

            /** @var array $barcode_fields */
            return $barcode_fields;

        case 'User':


            if (is_array($options) and $options['type'] == 'profile') {
                include 'fields/profile.fld.php';
            } else {
                include 'fields/user.system.fld.php';
                $object_fields = get_user_fields($object, $db, array(
                    'new'    => true,
                    'type'   => 'user',
                    'parent' => 'Staff'
                ));
            }

            return $object_fields;
        case 'Customer':


            if (!empty($options['poll'])) {
                include 'fields/customer.poll.fld.php';
            } else {
                include 'fields/customer.fld.php';
            }

            /** @var array $customer_fields */
            return $customer_fields;


        case 'Customer Client':


            if (!empty($options['new'])) {
                include 'fields/customer_client.new.fld.php';
            } else {
                include 'fields/customer_client.fld.php';
            }


            return $object_fields;

        case 'Supplier':


                if ($user->get('User Type') == 'Agent') {
                    include 'fields/agent_supplier.fld.php';

                } else {
                    if (isset($options['new']) and $options['new']) {
                        include 'fields/supplier.new.fld.php';
                    } else {
                        include 'fields/supplier.fld.php';
                    }


                }



            return $object_fields;

        case 'Supplier Part':

            $object->get_supplier_data();


            if ($user->get('User Type') != 'Agent') {

                if ($options['parent'] == 'supplier') {


                    $supplier = $options['parent_object'];


                    if (isset($options['new'])) {
                        $part = new Part(0);


                        if ($supplier->get('Supplier Production') == 'Yes') {
                            include 'fields/production.new.fld.php';

                        } else {
                            include 'fields/supplier_part.new.fld.php';

                        }


                    } else {
                        include 'fields/supplier_part.fld.php';

                        $operations = array(
                            'label'      => _('Operations'),
                            'show_title' => true,
                            'class'      => 'operations',
                            'fields'     => array(

                                array(
                                    'id'    => 'delete_supplier_part',
                                    'class' => 'operation',
                                    'value' => '',
                                    'label' => '<i class="fa fa-fw fa-'.($super_edit ? 'lock-alt' : 'lock').'  button" 
                                                data-labels=\'{ "text":"'._("Please ask an authorised user to delete this supplier's product").'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                                            onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'PS\')').'"  
                                            style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._(
                                            "Delete supplier's product & related part"
                                        ).' <i class="far fa-trash-alt new_button link"></i></span>',

                                    'reference' => '',
                                    'type'      => 'operation'
                                ),


                            )

                        );

                        $supplier_part_fields[] = $operations;
                    }


                    return $supplier_part_fields;
                } elseif ($options['parent'] == 'production') {

                    /*
                                        include 'fields/production_part.fld.php';


                                        if (isset($options['new'])) {
                                            $object = get_object('Part', 0);
                                            include 'fields/part.fld.php';
                                            $supplier_part_fields = array_merge($supplier_part_fields, $part_fields);
                                        } else {


                                            $operations = array(
                                                'label'      => _('Operations'),
                                                'show_title' => true,
                                                'class'      => 'operations',
                                                'fields'     => array(

                                                    array(
                                                        'id'    => 'delete_supplier_part',
                                                        'class' => 'operation',
                                                        'value' => '',
                                                        'label' => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                                                            .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._(
                                                                "Delete supplier's product & related part"
                                                            ).' <i class="far fa-trash-alt new_button link"></i></span>',

                                                        'reference' => '',
                                                        'type'      => 'operation'
                                                    ),


                                                )

                                            );

                                            $supplier_part_fields[] = $operations;
                                        }


                                        return $supplier_part_fields;
                                        */
                    return [];

                } elseif ($options['parent'] == 'part') {
                    include 'fields/part.supplier_part.new.fld.php';

                    /** @var array $supplier_part_fields */
                    return $supplier_part_fields;
                }
            } else {


                include 'fields/agent_part.fld.php';

                /** @var array $supplier_part_fields */
                return $supplier_part_fields;


            }
            break;

        case 'Production Part':

            $object->get_supplier_data();


            include 'fields/production_part.fld.php';


            if (isset($options['new'])) {
                $object = get_object('Part', 0);
                include 'fields/part.fld.php';
                $supplier_part_fields = array_merge($supplier_part_fields, $part_fields);
            } else {


                $operations = array(
                    'label'      => _('Operations'),
                    'show_title' => true,
                    'class'      => 'operations',
                    'fields'     => array(

                        array(
                            'id'    => 'delete_supplier_part',
                            'class' => 'operation',
                            'value' => '',
                            'label' => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                                .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._(
                                    "Delete supplier's product & related part"
                                ).' <i class="far fa-trash-alt new_button link"></i></span>',

                            'reference' => '',
                            'type'      => 'operation'
                        ),


                    )

                );

                $supplier_part_fields[] = $operations;
            }


            return $supplier_part_fields;

        case 'Customer Part':


            $customer = $options['parent_object'];


            if (isset($options['new'])) {
                $part = new Part(0);


                include 'fields/customer_part.new.fld.php';


            } else {
                include 'fields/customer_part.fld.php';

                $operations = array(
                    'label'      => _('Operations'),
                    'show_title' => true,
                    'class'      => 'operations',
                    'fields'     => array(

                        array(
                            'id'    => 'delete_supplier_part',
                            'class' => 'operation',
                            'value' => '',
                            'label' => '<i class="fa fa-fw fa-'.($super_edit ? 'lock-alt' : 'lock').'  button" 
                                                data-labels=\'{ "text":"'._("Please ask an authorised user to delete this supplier's product").'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                                            onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'PS\')').'"  
                                            style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._(
                                    "Delete supplier's product & related part"
                                ).' <i class="far fa-trash-alt new_button link"></i></span>',

                            'reference' => '',
                            'type'      => 'operation'
                        ),


                    )

                );

                $customer_part_fields[] = $operations;
            }


            return $customer_part_fields;


        case 'Part':
            $part_fields = [];
            if (isset($options['new'])) {
                $object = get_object('Supplier Part', 0);
                $object->get_supplier_data();
                include 'fields/supplier_part.fld.php';
                include 'fields/part.fld.php';
                /** @var array $supplier_part_fields */
                $part_fields = array_merge($supplier_part_fields, $part_fields);
            } else {
                include 'fields/part.fld.php';
            }

            return $part_fields;
        case 'Raw Material':
            include 'fields/raw_material.fld.php';

            if (isset($options['new'])) {
                return get_raw_material_new_fields($object, $user);

            } else {
                return get_raw_material_edit_fields($object, $user);

            }

        case 'Warehouse':


            if (!empty($options['type']) and $options['type'] == 'leakages') {
                include 'fields/warehouse.leakages.fld.php';

            } else {
                include 'fields/warehouse.fld.php';

            }


            return $object_fields;
        case 'Warehouse Area':
            include 'fields/warehouse_area.fld.php';

            return $object_fields;
        case 'Location':
            include 'fields/location.fld.php';

            return get_location_object_fields($object, $user, $account, $db, $options);
        case 'Store':
            if (!empty($options['new'])) {
                include 'fields/store.new.fld.php';
            } else {
                include 'fields/store.fld.php';
            }

            return $object_fields;

        case 'Staff':


            $stores = array();
            $sql    = 'SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` order by `Store Code` ';
            foreach ($db->query($sql) as $row) {
                $stores[$row['Store Key']] = $row;
            }


            if ($object->get('Staff Type') == 'Contractor') {


                if (!empty($options['new'])) {
                    include 'fields/contractor.new.fld.php';

                } else {


                    include 'fields/contractor.fld.php';

                }
            } else {

                if (!empty($options['new'])) {
                    include 'fields/employee.new.fld.php';

                } else {


                    include 'fields/employee.fld.php';

                }

            }

            return $object_fields;
        case 'Customer Poll Query':
            include 'fields/poll_query.fld.php';

            return $object_fields;
        case 'Customer Poll Query Option':
            include 'fields/poll_query_option.fld.php';

            return $object_fields;
        case 'List':


            switch ($options['scope']) {
                case 'customers':
                    include 'fields/customers_new_list.fld.php';
                    break;
                default:
                    include 'fields/list.fld.php';
                    break;

            }

            return $object_fields;
        case 'Prospect':
            include 'fields/prospect.fld.php';

            /** @var array $prospect_fields */
            return $prospect_fields;
        case 'Email Template':


            switch ($options['role']) {
                case 'Invite Mailshot':
                    include 'fields/prospects.email_template.fld.php';
            }

            return $object_fields;
        case 'Email Campaign Type':

            switch ($object->get('Email Campaign Type Code')) {
                case 'OOS Notification':
                    include 'fields/email_campaign_type.oos_notification.fld.php';
                    break;
                case 'Registration':
                case 'Registration Approved':
                case 'Registration Rejected':

                    include 'fields/email_campaign_type.registration.fld.php';
                    break;
                case 'Password Reminder':
                    include 'fields/email_campaign_type.password_reminder.fld.php';
                    break;
                case 'Order Confirmation':
                case 'Delivery Confirmation':

                    include 'fields/email_campaign_type.order_confirmation.fld.php';
                    break;
                case 'GR Reminder':
                    include 'fields/email_campaign_type.gr_reminder.fld.php';
                    break;


                case 'Invite Mailshot':
                case 'Invite':
                case 'AbandonedCart':
                case 'Newsletter':
                    $object_fields = array();
                    break;
                default:
                    print 'todo  Email Campaign Type -->> '.$object->get('Email Campaign Type Code');
                    exit;


            }

            return $object_fields;


        case 'Shipper':
            include 'fields/shipper.fld.php';

            return $object_fields;


        case 'Order Basket Purge':
            include 'fields/purge.fld.php';

            return $object_fields;
        case 'Shipping Zone':
            include 'fields/shipping_zone.fld.php';

            return $object_fields;
        case 'Clocking Machine':


            if (!empty($options['new'])) {
                include 'fields/clocking_machine.new.fld.php';
            }
            //else {
            //include 'fields/clocking_machine.fld.php';
            //}


            return $object_fields;
        default:
            print 'todo object in object fields'.$object->get_object_name();


    }

    return '';
}



