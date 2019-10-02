<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 12:10:26 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


/**
 * @param       $object \DB_Table
 * @param       $db     \PDO
 * @param       $user   \User
 * @param       $smarty \Smarty
 * @param array $options
 *
 * @return array|string
 */
function get_object_fields($object, $db, $user, $smarty, $options = false) {

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
                    case 'Marketing':


                        //     if ($options['type'] == 'mailing_list') {
                        //         include 'fields/mailshot.mailing_list.fld.php';
                        //     } else {
                        include 'fields/mailshot.marketing.fld.php';
                        //     }


                        break;
                    default:
                        include 'fields/mailshot.fld.php';
                }
            }


            return $object_fields;
            break;
        case 'Charge':
            include 'fields/charge.fld.php';

            return $object_fields;
            break;
        case 'Delivery Note':
            include 'fields/delivery_note.fld.php';

            return $object_fields;
            break;
        case 'Invoice':
            include 'fields/invoice.fld.php';

            return $object_fields;
            break;

        case 'Payment Account':
            include 'fields/payment_account.fld.php';

            return $object_fields;
            break;
        case 'Payment':
            include 'fields/payment.fld.php';

            return $object_fields;
            break;

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

        case 'Attachment':

            $object_fields = array();
            if ($options['type'] == 'employee') {

                $options_Attachment_Subject_Type               = array(
                    'CV'       => _('Curriculum vitae'),
                    'Contract' => _('Employment contract'),
                    'Other'    => _('Other'),

                );
                $options_Attachment_Subject_Type_default_value = 'Contract';
            } elseif ($options['type'] == 'supplier') {
                $options_Attachment_Subject_Type               = array(
                    'Invoice'       => _('Invoice'),
                    'PurchaseOrder' => _('Purchase order'),
                    'Catalogue'     => _('Catalogue'),
                    'Image'         => _('Image'),
                    'Contact Card'  => _('Contact card'),
                    'Other'         => _('Other'),
                );
                $options_Attachment_Subject_Type_default_value = 'Contact Card';

            } elseif ($options['type'] == 'part') {
                $options_Attachment_Subject_Type               = array(
                    'Other' => _('Other'),
                    'MSDS'  => _('MSDS'),


                );
                $options_Attachment_Subject_Type_default_value = 'MSDS';
            } elseif ($options['type'] == 'supplier_delivery') {
                $options_Attachment_Subject_Type = array(
                    'Delivery Paperwork' => _('Delivery paperwork'),
                    'Invoice'            => _('Invoice'),
                    'Other'              => _('Other'),

                );

                $options_Attachment_Subject_Type_default_value = 'Delivery Paperwork';

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


            if ($options['store']->get('Store Order Recursion Campaign Key') == $object->id) {

                $deals = $object->get_deals();
                $deal  = array_pop($deals);
                $store = $options['store'];

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
            break;
        case 'Deal':

            if (isset($options['new'])) {


                switch ($options['parent']) {
                    case 'campaign':


                        switch ($options['parent_object']->get('Deal Campaign Code')) {
                            case 'VO':
                                $store = get_object('Store', $options['store_key']);;
                                include 'fields/new_voucher.fld.php';
                                break;
                            case 'FO':
                                $store = get_object('Store', $options['store_key']);;
                                include 'fields/new_first_order_offer.fld.php';
                                break;
                            case 'CA':
                                $store = get_object('Store', $options['store_key']);;
                                include 'fields/new_category_deal.fld.php';
                                break;
                            case 'CU':
                                $store = get_object('Store', $options['store_key']);;
                                include 'fields/new_customers_deal.fld.php';
                                break;
                            case 'SO':
                                $store = get_object('Store', $options['store_key']);;
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
            break;
        case 'Deal Component':

            if (isset($options['new'])) {
                switch ($options['parent']) {
                    case 'campaign':

                        switch ($options['parent_object']->get('Deal Campaign Code')) {
                            case 'VO':
                                $store = get_object('Store', $options['store_key']);;
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
            break;
        case 'Website':


            if (!empty($options['localization'])) {
                include 'fields/website_localization.fld.php';


            }  else {
                include 'fields/website.fld.php';
            }


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


            if (!empty($options['poll'])) {
                include 'fields/customer.poll.fld.php';
            } else {
                include 'fields/customer.fld.php';
            }

            return $customer_fields;
            break;

        case 'Customer Client':


            if (!empty($options['new'])) {
                include 'fields/customer_client.new.fld.php';
            } else {
                include 'fields/customer_client.fld.php';
            }


            return $object_fields;
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



                if ($user->get('User Type') == 'Agent') {
                    include 'fields/agent_supplier.fld.php';

                }else{
                    include 'fields/supplier.fld.php';

                }

            }

            return $object_fields;
            break;

        case 'Supplier Part':

            $object->get_supplier_data();


            if ($user->get('User Type') != 'Agent') {

                if ($options['parent'] == 'supplier') {


                    $supplier = $options['parent_object'];


                    if (isset($options['new'])) {
                        $part = new Part(0);

                        include 'fields/supplier_part.new.fld.php';


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
                                                data-labels=\'{ "text":"'._('Please ask an authorised user to delete this supplier part').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                                            onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'PS\')').'"  
                                            style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                                        .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._(
                                            "Delete supplier's part & related part"
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
                                            "Delete supplier's part & related part"
                                        ).' <i class="far fa-trash-alt new_button link"></i></span>',

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


            if (!empty($options['type']) and $options['type'] == 'leakages') {
                include 'fields/warehouse.leakages.fld.php';

            } else {
                include 'fields/warehouse.fld.php';

            }


            return $object_fields;
            break;
        case 'Warehouse Area':
            include 'fields/warehouse_area.fld.php';

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


            $stores = array();
            $sql    = sprintf(
                'SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` order by `Store Code` '
            );
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
            break;
        case 'Customer Poll Query':
            include 'fields/poll_query.fld.php';

            return $object_fields;
            break;
        case 'Customer Poll Query Option':
            include 'fields/poll_query_option.fld.php';

            return $object_fields;
            break;
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
            break;
        case 'Prospect':
            include 'fields/prospect.fld.php';

            return $prospect_fields;
            break;
        case 'Email Template':


            switch ($options['role']) {
                case 'Invite Mailshot':
                    include 'fields/prospects.email_template.fld.php';
            }

            return $object_fields;
            break;
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
                    break;

            }

            return $object_fields;


        case 'Shipper':
            include 'fields/shipper.fld.php';

            return $object_fields;

            break;
        case 'Order Basket Purge':
            include 'fields/purge.fld.php';

            return $object_fields;
            break;
        case 'Shipping Zone':
            include 'fields/shipping_zone.fld.php';

            return $object_fields;
            break;
        default:
            print 'todo object in object fields'.$object->get_object_name();

            return '';
            break;
    }

}


?>
