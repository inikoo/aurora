<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 08 Jul 2021 17:43:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021., Inikoo
 *  Version 3.0
 */


/**
 * @param $showcase
 * @param $data
 * @param $smarty  \Smarty
 * @param $user    \User
 * @param $db      \PDO
 * @param $account \Account
 * @param $redis   \Redis
 *
 * @return mixed|string
 * @throws \SmartyException
 */
function get_object_showcase($showcase, $data, $smarty, $user, $db, $account, $redis) {


    $title        = '';
    $web_location = '';
    if (preg_match('/_edit$/', $data['tab'])) {
        return array(
            '',
            ''
        );
    }

    switch ($showcase) {
        case 'material':
            include_once 'showcase/material.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'webpage':
            include_once 'showcase/webpage.show.php';
            $html         = get_webpage_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-browser"></i> '.$title;

            break;

        case 'website':
            include_once 'showcase/website.show.php';
            $html         = get_website_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-globe"></i> '.$title;

            break;
        case 'dashboard':
            $html         = '';
            $web_location = '<i class="fal fa-fw fa-tachometer-alt"></i> '._('Dashboard');
            break;

        case 'upload':
            include_once 'showcase/upload.show.php';
            $html = get_upload_showcase($data, $smarty, $user, $db);
            break;
        case 'purchase_order':
            include_once 'showcase/supplier.order.show.php';
            $html         = get_supplier_order_showcase($data, $smarty, $user);
            $title        = $data['_object']->get('Public ID');
            $web_location = '<i class="fal fa-fw fa-clipboard"></i> '.$data['_object']->get('Public ID');
            if ($data['module'] == 'production') {
                $web_location .= ' <i class="fal fa-fw fa-industry"></i>';
            }

            break;
        case 'campaign':
            include_once 'showcase/campaign.show.php';
            $html = get_campaign_showcase($data, $smarty, $user, $db);
            break;
        case 'deal':
            include_once 'showcase/deal.show.php';
            $html = get_deal_showcase($data, $smarty, $user, $db);
            break;
        case 'deal_component':
            include_once 'showcase/deal_component.show.php';
            $html = get_deal_component_showcase($data, $smarty, $user, $db);
            break;
        case 'store':

            include_once 'showcase/store.show.php';
            $html         = get_store_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-store"></i> '.$title;
            break;
        case 'products_special_categories':
            include_once 'showcase/products_special_categories.show.php';
            $html         = get_products_special_categories_showcase(
                $data, $smarty, $user, $db
            );
            $title        = _("Product's categories").' '.$data['store']->get('Code');
            $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
            break;
        case 'account':
            if ($data['module'] == 'products_server') {
                include_once 'showcase/stores.show.php';
                $html         = get_stores_showcase($data, $smarty, $user, $db);
                $title        = _('Stores');
                $web_location = '<i class="fal fa-fw fa-store"></i> '.$title;
            } else {

                include_once 'showcase/account.show.php';
                $html         = get_account_showcase($data, $smarty, $user, $db);
                $title        = _('Account');
                $web_location = '<i class="fal fa-fw fa-toolbox"></i> '.$title;
            }
            break;
        case 'product':

            if ($data['module'] == 'customers') {
                include_once 'showcase/customer.product.show.php';
                $html = get_customer_product_showcase($data, $smarty, $user, $db);
            } else {
                include_once 'showcase/product.show.php';
                $html = get_product_showcase($data, $smarty, $user, $db);
            }

            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-cube"></i> '.$title;

            break;
        case 'part':
            include_once 'showcase/part.show.php';
            $html         = get_part_showcase($data, $smarty, $account);
            $title        = $data['_object']->get('Reference');
            $web_location = '<i class="fal fa-fw fa-box"></i> '.$title;

            break;
        case 'supplier_part':
            include_once 'showcase/supplier_part.show.php';
            $html         = get_supplier_part_showcase($data, $smarty, $user, $account);
            $title        = $data['_object']->get('Reference');
            $web_location = '<i class="fal fa-fw fa-hand-receiving"></i> '.$title;

            break;
        case 'employee':
            include_once 'showcase/employee.show.php';
            $html         = get_employee_showcase($data, $smarty, $user);
            $title        = $data['_object']->get('Name');
            $web_location = '<i class="fal fa-fw fa-hand-rock"></i> '.$data['_object']->get('ID');

            break;
        case 'contractor':
            include_once 'showcase/contractor.show.php';
            $html         = get_contractor_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Name');
            $web_location = '<i class="fal fa-fw fa-hand-spock"></i> '.$data['_object']->get('ID');

            break;
        case 'customer':
            include_once 'showcase/customer.show.php';
            $html         = get_customer_showcase($data, $smarty, $db, $redis, $account);
            $title        = 'C'.$data['_object']->get('Formatted ID');
            $web_location = '<i class="fal fa-fw fa-user"></i> '.$title;

            break;
        case 'customer_client':
            include_once 'showcase/customer_client.show.php';
            $html         = get_customer_client_showcase($data, $smarty, $user, $db, $redis, $account);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-address-book"></i> '.$title;

            break;
        case 'supplier':
            include_once 'showcase/supplier.show.php';
            $html         = get_supplier_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-hand-holding-box"></i> '.$title;
            break;
        case 'agent':
            include_once 'showcase/agent.show.php';
            $html = get_agent_showcase($data, $smarty, $user, $db);
            break;
        case 'order':
            include_once 'showcase/order.show.php';
            $html         = get_order_showcase($data, $smarty, $user, $db);
            $title        = $data['_object']->get('Public ID');
            $web_location = '<i class="fal fa-fw fa-shopping-cart"></i> '.$title;


            break;
        case 'invoice':
        case 'refund':
            include_once 'showcase/invoice.show.php';
            $html         = get_invoice_showcase($data, $smarty, $user, $db, $account);
            $title        = $data['_object']->get('Public ID');
            $web_location = '<i class="fal fa-fw fa-file-invoice-dollar"></i> '.$title;

            break;
        case 'delivery_note':
            include_once 'showcase/delivery_note.show.php';
            $html         = get_delivery_note_showcase($data, $smarty);
            $title        = $data['_object']->get('ID');
            $web_location = '<i class="fal fa-fw fa-truck"></i> '.$title;

            break;
        case 'user':
            include_once 'showcase/user.show.php';
            $html  = get_user_showcase($data, $smarty, $user, $db);
            $title = $data['_object']->get('Handle');


            if ($user->id == $data['_object']->id and preg_match('/profile/', $data['request'])) {
                $web_location = '<i class="fal fa-fw fa-user-circle"></i> '._('Profile');

            } else {
                $web_location = '<i class="fal fa-fw fa-terminal"></i> '.$title;

            }


            break;
        case 'warehouse':
            include_once 'showcase/warehouse.show.php';

            if (!$user->can_view('locations') or !in_array($data['key'], $user->warehouses)) {
                $html = get_locked_warehouse_showcase($data, $smarty, $user, $db);

            } else {
                $html = get_warehouse_showcase($data, $smarty, $user, $db);
            }

            $title        = 'W '.$data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-warehouse-alt"></i> '.$title;


            break;
        case 'warehouse_area':
            include_once 'showcase/warehouse_area.show.php';

            if (!$user->can_view('locations') or !in_array($data['warehouse']->id, $user->warehouses)) {
                $html = get_locked_warehouse_area_showcase($data, $smarty, $user, $db);

            } else {
                $html = get_warehouse_area_showcase($data, $smarty, $user, $db);
            }
            break;
        case 'location':
            include_once 'showcase/location.show.php';

            if (!$user->can_view('locations') or !in_array($data['warehouse']->id, $user->warehouses)) {
                $html = get_locked_location_showcase($data, $smarty, $user, $db);

            } else {
                $html = get_location_showcase($data, $smarty, $user, $db);
            }
            $title        = $data['_object']->get('Code');
            $web_location = '<i class="fal fa-fw fa-pallet"></i> '.$title;

            break;


        case 'timesheet':
            include_once 'showcase/timesheet.show.php';
            $html = get_timesheet_showcase($data, $smarty, $user, $db);
            break;
        case 'attachment':
            include_once 'showcase/attachment.show.php';
            $html = get_attachment_showcase($data, $smarty, $user, $db);
            break;
        case 'manufacture_task':
            include_once 'showcase/manufacture_task.show.php';
            $html = get_manufacture_task_showcase($data, $smarty, $user, $db);
            break;
        case 'upload':
            include_once 'showcase/upload.show.php';
            $html = get_upload_showcase($data, $smarty, $user, $db);
            break;
        case 'barcode':
            include_once 'showcase/barcode.show.php';
            $html = get_barcode_showcase($data, $smarty, $user, $db);
            break;
        case 'category':

            if ($data['_object']->get('Category Scope') == 'Product') {


                if ($data['_object']->id == $data['store']->get('Store Family Category Key')) {
                    $html         = '';
                    $title        = _('Families').' '.$data['store']->get('Code');
                    $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                } else {
                    if ($data['_object']->id == $data['store']->get('Store Department Category Key')) {
                        $html         = '';
                        $title        = _('Departments').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    } elseif ($data['_object']->get('Root Key') == $data['store']->get('Store Family Category Key')) {
                        include_once 'showcase/family.show.php';
                        $html         = get_family_showcase($data, $smarty, $user, $db);
                        $title        = $data['_object']->get('Code').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    } elseif ($data['_object']->get('Root Key') == $data['store']->get('Store Department Category Key')) {
                        include_once 'showcase/department.show.php';
                        $html         = get_department_showcase($data, $smarty, $user, $db);
                        $title        = $data['_object']->get('Code').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    } else {

                        $html         = '';
                        $title        = $data['_object']->get('Code').' '.$data['store']->get('Code');
                        $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;
                    }
                }


            } elseif ($data['_object']->get('Category Scope') == 'Part') {

                if ($data['_object']->id == $account->get('Account Part Family Category Key')) {
                    include_once 'showcase/part_families.show.php';
                    $html         = get_part_familes_showcase($data, $smarty, $user, $db);
                    $title        = _("Families").' <i class="fal fa-fw fa-box"></i>';
                    $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;


                } elseif ($data['_object']->get('Root Key') == $account->get('Account Part Family Category Key')) {
                    include_once 'showcase/part_family.show.php';
                    $html         = get_part_family_showcase($data, $smarty, $user, $db);
                    $title        = $data['_object']->get('Code').' <i class="fal fa-fw fa-box"></i>';
                    $web_location = '<i class="fal fa-fw fa-sitemap"></i> '.$title;

                } else {
                    return '_';
                }

            } elseif ($data['_object']->get('Category Scope') == 'Supplier') {
                include_once 'showcase/supplier_category_showcase.show.php';
                $html = get_supplier_category_showcase($data, $smarty, $user, $db);

            } elseif ($data['_object']->get('Category Scope') == 'Invoice') {
                include_once 'showcase/invoice_category_showcase.show.php';
                $html = get_invoice_category_showcase($data, $smarty, $user, $db);

            } elseif ($data['_object']->get('Category Scope') == 'Customer') {
                include_once 'showcase/customer_category_showcase.show.php';
                $html = get_customer_category_showcase($data, $smarty, $user, $db);

                $web_location = '<i class="fal fa-fw fa-users"></i> <i class="fal fa-fw fa-sitemap"></i> '.$data['_object']->get('Category Code');


            } else {
                return '_';
            }

            $title = $data['_object']->get('Code');

            break;
        case 'PurchaseOrderItem':
            include_once 'showcase/supplier.order.item.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'supplierdelivery':
        case 'supplier_delivery':

            if ($user->get('User Type') == 'Agent') {
                include_once 'showcase/agent_delivery.show.php';
                $html = get_showcase($data, $smarty, $user, $db);
            } else {
                include_once 'showcase/supplier.delivery.show.php';
                $html = get_supplier_delivery_showcase($data, $smarty);
            }
            $title = $data['_object']->get('Public ID');

            if ($data['module'] == 'production') {
                $web_location = '<i class="fal fa-fw fa-clipboard-check"></i> '.$data['_object']->get('Public ID');
                $web_location .= ' <i class="fal fa-fw fa-industry"></i>';
            } else {
                $web_location = '<i class="fal fa-fw fa-truck"></i> '.$data['_object']->get('Public ID');
            }
            break;
        case 'fulfilment_delivery':
            include_once 'showcase/fulfilment.delivery.show.php';
            $html  = get_fulfilment_delivery_showcase($data, $smarty);
            $web_location = '<i class="fal fa-fw fa-arrow-square-down"></i> '.$data['_object']->get('Formatted ID');
            break;
        case 'fulfilment_asset':
            include_once 'showcase/fulfilment.asset.show.php';
            $html  = get_fulfilment_asset_showcase($data, $smarty);
            $web_location = $data['_object']->get('Type Icon').' '.$data['_object']->get('Formatted ID Reference');
            break;
        case 'position':
            include_once 'showcase/job_position.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;
        case 'webpage_type':
            include_once 'showcase/webpage_type.show.php';
            $html = get_showcase($data, $smarty, $user, $db);
            break;

        case 'payment_account':
            include_once 'showcase/payment_account.show.php';
            $html = get_payment_account_showcase($data, $smarty, $user, $db);
            break;
        case 'payment_service_provider':
            include_once 'showcase/payment_service_provider.show.php';
            $html = get_payment_service_provider_showcase($data, $smarty, $user, $db);
            break;
        case 'charge':
            include_once 'showcase/charge.show.php';
            $html = get_charge_showcase($data, $smarty, $user, $db);
            break;
        case 'timeseries_record':
            include_once 'showcase/timeseries_record.show.php';
            $html = get_timeseries_record_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'mailshot':
            include_once 'showcase/email_campaign.show.php';
            $html         = get_email_campaign_showcase($data, $smarty, $user, $db, $account);
            $web_location = '<i class="fal fa-fw fa-mail-bulk"></i> '.(strlen($data['_object']->get('Name')) > 17 ? substr($data['_object']->get('Name'), 0, 20).'&hellip;' : $data['_object']->get('Name'));

            break;

        case 'newsletter':
            include_once 'showcase/email_campaign.show.php';
            $html         = get_email_campaign_showcase($data, $smarty, $user, $db, $account);
            $web_location = '<i class="fal fa-fw fa-newsletter"></i> '.(strlen($data['_object']->get('Name')) > 17 ? substr($data['_object']->get('Name'), 0, 20).'&hellip;' : $data['_object']->get('Name'));

            break;

        case 'api_key':
        case 'deleted_api_key':
            include_once 'showcase/api_key.show.php';
            $html = get_api_key_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'Customer_Poll_Query':
            include_once 'showcase/customer_poll_query.show.php';
            $html = get_customer_poll_query_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'Customer_Poll_Query_Option':
            include_once 'showcase/customer_poll_query_option.show.php';
            $html = get_customer_poll_query_option_showcase($data, $smarty, $user, $db, $account);
            break;
        case 'list':
            include_once 'showcase/list.show.php';
            $html = get_list_showcase($data, $smarty, $user, $db);
            break;
        case 'email_campaign_type':
            include_once 'showcase/email_campaign_type.show.php';
            $html = get_email_campaign_type_showcase($data, $smarty, $user, $db);
            break;
        case 'prospect':
            include_once 'showcase/prospect.show.php';
            $html = get_prospect_showcase($data, $smarty, $user, $db);
            break;
        case 'email_tracking':
            include_once 'showcase/email_tracking.show.php';
            $html = get_prospect_email_tracking($data, $smarty, $user, $db);
            break;
        case 'email_template':
            include_once 'showcase/email_template.show.php';
            $html = get_email_template_showcase($data, $smarty, $user, $db);
            break;
        case 'sales_representative':
            include_once 'showcase/sales_representative.show.php';
            $html = get_sales_representative_showcase($data, $smarty, $user, $db);
            break;
        case 'agent_supplier_order':
            include_once 'showcase/agent_supplier_order.show.php';
            $html = get_agent_supplier_order_showcase($data, $smarty, $user, $db);
            break;
        case 'payment':
            include_once 'showcase/payment.show.php';
            $html = get_payment_showcase($data, $smarty);
            break;
        case 'purge':
            include_once 'showcase/purge.show.php';
            $html = get_purge_showcase($data, $smarty, $user, $db);
            break;
        case 'shipper':
            include_once 'showcase/shipper.show.php';
            $html = get_shipper_showcase($data, $smarty, $user, $db);
            break;
        case 'production_part':
            include_once 'showcase/production_part.show.php';
            $html = get_production_part_showcase($data, $smarty);
            break;
        case 'shipping_zone_schema':
            $html         = '';
            $title        = $data['_object']->get('Label');
            $web_location = '<if/> class="fal fa-fw fa-bring-front"></if> '._('Shipping schema');
            break;
        case 'raw_material':
            include_once 'showcase/raw_material.show.php';
            $html = get_raw_material_showcase($data, $smarty, $account);
            break;
        case 'new_shipper':
            include_once 'conf/shipper_chooser.php';
            $html = get_new_shipper_showcase($data, $smarty, $account);
            break;
        case 'consignment':
            include_once 'showcase/consignment.show.php';
            $html = get_consignment_showcase($data, $smarty, $user, $db);
            break;
        default:
            $html = $data['object'].' -> '.$data['key'];
            break;
    }


    return array(
        $html,
        $title,
        $web_location
    );

}
