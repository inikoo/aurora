<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 08 Jul 2021 20:39:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


/**
 * @throws \SmartyException
 */
function get_navigation($user, $smarty, $data, $db, $account) {

    switch ($data['module']) {

        case ('dashboard'):
            require_once 'navigation/dashboard.nav.php';

            return get_dashboard_navigation($data, $smarty, $user, $db, $account);

        case ('products_server'):
            require_once 'navigation/products.nav.php';
            switch ($data['section']) {
                case 'stores':
                    return get_stores_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case 'products':
                    return get_products_all_stores_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'store.new':
                    return get_new_store_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;
        case ('products'):
            require_once 'navigation/products.nav.php';


            switch ($data['section']) {

                case 'store':
                    return get_store_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case 'products':
                    return get_products_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'product':
                    return get_product_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'product.new':
                    return get_new_product_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case 'services':
                    return get_services_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'service':
                    return get_service_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'service.new':
                    return get_new_service_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'dashboard':
                    return get_store_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('categories'):
                    return get_products_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('category'):
                    return get_products_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('main_category.new'):
                    return get_products_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('order'):
                    return get_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('marketing_post'):

                    return get_marketing_post_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('charge'):
                    return get_charge_navigation($data, $smarty, $user, $db, $account);

                case ('shipping_zone'):
                    return get_shipping_zone_navigation($data, $smarty, $user, $db, $account);

                case ('shipping_option'):
                    return get_shipping_option_navigation($data, $smarty, $user, $db, $account);


                case ('charge.new'):
                    return get_charge_new_navigation($data, $smarty, $user, $db, $account);
                case ('shipping_zone.new'):
                    return get_shipping_zone_new_navigation($data, $smarty, $user, $db, $account);

                case ('shipping_option.new'):
                    return get_shipping_option_new_navigation($data, $smarty, $user, $db, $account);


                case 'shipping_zone_schema':
                    return get_shipping_zone_schema_navigation($data, $smarty, $user, $db, $account, $account);


                case ('settings'):
                    return get_settings_navigation($data, $smarty);
                case 'website.new':
                    return get_website_new_navigation($data, $smarty);
            }
            break;
        case ('mailroom_server'):
            require_once 'navigation/mailroom.nav.php';
            switch ($data['section']) {
                /*
                case 'notifications':
                    return get_notifications_server_navigation(
                        $data, $smarty
                    );
                break;
                */ case 'group_by_store':
                return get_group_by_store_server_navigation(
                    $data, $smarty
                );
                break;


            }
            break;
        case ('mailroom'):
            require_once 'navigation/mailroom.nav.php';
            switch ($data['section']) {

                case 'customer_notifications':
                    return get_subject_notifications_navigation(
                        'customers', $data, $smarty, $user, $db
                    );
                case 'user_notifications':
                    return get_subject_notifications_navigation(
                        'staff', $data, $smarty, $user, $db
                    );

                case 'marketing':
                    return get_subject_notifications_navigation(
                        'marketing', $data, $smarty, $user, $db
                    );

                case 'email_campaign_type':

                    if ($data['_object']->get('Email Campaign Type Scope') == 'User Notification') {
                        return get_user_notification_email_campaign_type_navigation($data, $smarty, $user, $db, $account);

                    } elseif ($data['_object']->get('Email Campaign Type Scope') == 'Customer Notification') {
                        return get_customer_notification_email_campaign_type_navigation($data, $smarty, $user, $db, $account);

                    } else {
                        return get_marketing_email_campaign_type_navigation($data, $smarty, $user, $db, $account);

                    }
                case ('mailshot'):
                    return get_mailshot_navigation($data, $smarty, $user, $db, $account);

                case ('email_tracking'):
                    return get_email_tracking_navigation($data, $smarty, $user, $db, $account, $account);
                case ('mailshot.new'):
                    return get_mailshot_new_navigation($data, $smarty);

            }
            break;
        case ('customers'):
            require_once 'navigation/customers.nav.php';


            switch ($data['section']) {

                case ('customer'):
                    return get_customer_navigation($data, $smarty, $user, $db, $account);


                case ('customers'):
                    return get_customers_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('categories'):

                    return get_customers_categories_navigation($data, $smarty, $user, $db, $account);

                case ('category'):
                case ('sub_category'):

                    return get_customers_category_navigation($data, $smarty, $user, $db, $account);

                case ('lists'):
                    return get_customers_lists_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('list'):
                    return get_customers_list_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('list.new'):
                    return get_new_list_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('dashboard'):
                    return get_customers_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('customer_notifications'):

                    return get_customers_notifications_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('insights'):

                    return get_customers_insights_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('poll_query.new'):
                    return get_customers_new_poll_query_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('poll_query'):
                    return get_customers_poll_query_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('poll_query_option.new'):
                    return get_customers_new_poll_query_option_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('poll_query_option'):
                    return get_customers_poll_query_option_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted_customer_poll_query_option'):
                    return get_customers_deleted_poll_query_option_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('pending_orders'):
                    return get_customers_pending_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('customer.new'):
                    return get_new_customer_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('customer_client.new'):
                    return get_new_customer_client_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('customer_client'):
                    return get_customer_client_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('prospect'):
                    return get_prospect_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case 'email_campaign_type':
                    return get_email_campaign_type_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case 'mailshot':
                    return get_mailshot_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('prospects'):
                    return get_prospects_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('prospect.new'):
                    return get_new_prospect_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('prospect.compose_email'):
                    return get_new_prospect_compose_email_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );


                case ('email_tracking'):
                    return get_email_tracking_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('prospects.template.new'):
                    return get_prospects_new_template_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('prospects.email_template'):
                    return get_prospects_email_template_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('product'):
                    return get_customer_product_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('deleted_customer'):
                    return get_deleted_customer_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }

            break;
        case ('customers_server'):
            require_once 'navigation/customers.nav.php';
            switch ($data['section']) {
                case ('customers'):
                case('pending_orders'):
                    return get_customers_server_navigation(
                        $data, $smarty, $user, $db
                    );


            }

            break;
        case ('orders_server'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {


                case ('dashboard'):
                    return get_orders_server_dashboard_navigation($data, $smarty, $user, $db, $account);

                case ('group_by_store'):
                    return get_orders_server_group_by_store_navigation($data, $smarty, $user, $db, $account);


                case ('orders'):

                    return get_orders_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);


                case ('mailshot'):
                    return get_abandoned_card_email_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }

            break;

        case ('delivery_notes_server'):
            require_once 'navigation/orders.nav.php';
            require_once 'navigation/delivery_notes.nav.php';

            switch ($data['section']) {
                case ('pending_delivery_notes'):

                    return get_pending_delivery_notes_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('delivery_notes'):

                    return get_delivery_notes_server_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('group_by_store'):
                    return get_delivery_notes_server_group_by_store_navigation($data, $smarty, $user, $db, $account);

                case ('shipper'):
                    return get_shipper_navigation($data, $smarty, $user, $db, $account);

                case ('shipper.new'):
                    return get_shipper_new_navigation($data, $smarty);
                case ('consignments'):
                    return get_consignments_navigation($data, $smarty);
                case ('consignment'):
                    return get_consignment_navigation($data, $smarty, $user, $db);

            }

            break;

        case ('orders'):
            require_once 'navigation/orders.nav.php';
            switch ($data['section']) {


                case ('dashboard'):
                    return get_dashboard_navigation($data, $smarty, $user, $db, $account);

                case ('basket_orders'):
                    return get_basket_orders_navigation($data, $smarty, $user, $db, $account);


                case ('pending_orders'):
                    return get_pending_orders_navigation($data, $smarty, $user, $db, $account);

                case ('orders'):

                    return get_orders_navigation($data, $smarty, $user, $db, $account);

                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);

                case ('invoice'):
                    include_once 'navigation/accounting.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);


                case ('refund'):
                    include_once 'navigation/accounting.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);

                case ('delivery_note'):
                    return get_delivery_note_navigation($data, $smarty, $user, $db, $account);

                case ('invoices'):
                    return get_invoices_navigation($data, $smarty, $user, $db, $account);


                case ('payment'):
                    return get_order_payment_navigation($data, $smarty, $user, $db);

                case ('mailshot'):
                    return get_abandoned_card_email_navigation($data, $smarty, $user, $db, $account);

                case ('refund.new'):
                    return get_refund_new_navigation($data, $smarty, $user, $db, $account);

                case ('replacement.new'):
                    return get_replacement_new_navigation($data, $smarty, $user, $db, $account);

                case ('return.new'):
                    return get_return_new_navigation($data, $smarty, $user, $db, $account);


                case ('replacement'):
                    return get_replacement_navigation($data, $smarty, $user, $db, $account);

                case ('return'):
                    return get_return_navigation($data, $smarty, $user, $db, $account);

                case ('email_tracking'):
                    return get_email_tracking_navigation($data, $smarty, $user, $db, $account);

                case ('purge'):
                    return get_purge_navigation($data, $smarty, $user, $db, $account);

                case ('deleted_invoice'):
                    include_once 'navigation/accounting.nav.php';

                    return get_deleted_invoice_navigation($data, $smarty, $user, $db, $account);

                default:
                    return 'View not found x2'.$data['section'];

            }
            break;

        case ('delivery_notes'):
            require_once 'navigation/orders.nav.php';

            switch ($data['section']) {
                case ('delivery_notes'):
                    return get_delivery_notes_navigation($data, $smarty, $user, $db, $account);


                case ('delivery_note'):
                    return get_delivery_note_navigation($data, $smarty, $user, $db, $account);


                case ('invoice'):
                    include_once 'navigation/accounting.nav.php';

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);

                case ('order'):
                    return get_order_navigation($data, $smarty, $user, $db, $account);

                case ('pick_aid'):
                    return get_pick_aid_navigation($data, $smarty, $user, $db, $account);

                case ('pack_aid'):
                    return get_pack_aid_navigation($data, $smarty, $user, $db, $account);

                default:
                    return 'View not found x1'.$data['section'];

            }
            break;
        case ('websites_server'):
            require_once 'navigation/websites.nav.php';
            switch ($data['section']) {
                case ('websites'):

                    return get_websites_navigation($data, $smarty, $user, $db, $account);

            }

            break;
        case ('websites'):

            require_once 'navigation/websites.nav.php';

            switch ($data['section']) {


                case 'analytics':
                case 'settings':
                case 'workshop':
                case 'web_users':
                    return get_website_navigation($data, $smarty, $user, $db, $account);

                case ('webpage'):
                    return get_webpage_navigation($data, $smarty, $user, $db, $account);

                case ('webpage_type'):
                    return get_webpage_type_navigation($data, $smarty, $user, $db, $account);

                case 'webpages':
                    return get_webpages_navigation($data, $smarty, $user, $db, $account);

                case ('webpage.new'):
                    return get_new_webpage_navigation($data, $smarty, $user, $db, $account);

                default:
                    return 'View not found '.$data['section'];

            }
            break;
        case ('offers_server'):
            require_once 'navigation/marketing.nav.php';
            switch ($data['section']) {
                case ('group_by_store'):

                    return get_offers_group_by_store_navigation(
                        $smarty
                    );

            }
            break;
        case 'offers':
            require_once 'navigation/marketing.nav.php';

            switch ($data['section']) {
                case ('offers'):
                    return get_offers_navigation($data, $smarty);
                    break;
                case ('campaigns'):
                    return get_campaigns_navigation($data, $smarty);
                    break;
                case ('campaign'):
                case ('campaign_order_recursion'):
                case ('vouchers'):
                    return get_campaign_navigation($data, $smarty, $user, $db, $account);

                case ('campaign.new'):
                    return get_new_campaign_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deal.new'):
                    return get_new_deal_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deal_component.new'):
                    return get_new_deal_component_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deal'):
                    return get_deal_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deal_component'):
                    return get_deal_component_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                    break;
            }
        case ('reports'):

            require_once 'navigation/reports.nav.php';
            switch ($data['section']) {
                case ('reports'):
                    return get_reports_navigation($user, $smarty, $data);

                case ('performance'):
                    return get_performance_navigation($user, $smarty, $data);

                case ('pickers'):
                    return get_pickers_navigation($user, $smarty, $data);

                case ('packers'):
                    return get_packers_navigation($user, $smarty, $data);
                case ('picker'):
                    return get_picker_packer_navigation($data, $db, $user, $smarty);

                case ('packer'):
                    return get_picker_packer_navigation($data, $db, $user, $smarty);

                case ('sales_representatives'):
                    return get_sales_representatives_navigation($user, $smarty, $data);

                case ('prospect_agents'):
                    return get_prospect_agents_navigation($user, $smarty, $data);

                case ('sales_representative'):
                    return get_sales_representative_navigation($user, $smarty, $data);

                case ('prospect_agent'):
                    return get_prospect_agent_navigation($user, $smarty, $data);

                case ('prospect_agent_email_tracking'):
                    return get_prospect_agent_email_tracking_navigation($data, $smarty, $user, $db);


                case ('lost_stock'):
                    return get_lost_stock_navigation($user, $smarty, $data);

                case ('stock_given_free'):
                    return get_stock_given_free_navigation($user, $smarty, $data);

                case ('sales'):
                    return get_sales_navigation($user, $smarty, $data);

                case ('report_orders'):
                    return get_report_orders_navigation($user, $smarty, $data);

                case ('report_orders_components'):
                    return get_report_orders_components_navigation($user, $smarty, $data);

                case ('report_delivery_notes'):
                    return get_report_delivery_notes_navigation($user, $smarty, $data);

                case ('intrastat'):
                    return get_intrastat_navigation($user, $smarty, $data, $account);

                case ('intrastat_orders'):
                    return get_intrastat_orders_navigation($user, $smarty, $data);

                case ('intrastat_products'):
                    return get_intrastat_products_navigation($user, $smarty, $data);

                case ('intrastat_imports'):
                    return get_intrastat_imports_navigation($user, $smarty, $data);

                case ('intrastat_parts'):
                    return get_intrastat_parts_navigation($user, $smarty, $data);

                case ('intrastat_deliveries'):
                    return get_intrastat_deliveries_navigation($user, $smarty, $data);

                case ('tax'):
                    return get_tax_navigation($user, $smarty, $data);

                case ('billingregion_taxcategory'):
                    return get_georegion_taxcategory_navigation(
                        $user, $smarty, $data
                    );

                case ('billingregion_taxcategory.invoices'):
                    return get_invoices_georegion_taxcategory_navigation(
                        $user, $smarty, $data, 'invoices'
                    );

                case ('billingregion_taxcategory.refunds'):
                    return get_invoices_georegion_taxcategory_navigation(
                        $user, $smarty, $data, 'refunds'
                    );

                case ('ec_sales_list'):
                    return get_ec_sales_list_navigation($user, $smarty, $data);

            }
        case ('production_server'):
            require_once 'navigation/production.nav.php';
            switch ($data['section']) {
                case ('production.suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('settings'):
                    return get_server_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;
        case ('production'):
            require_once 'navigation/production.nav.php';

            switch ($data['section']) {
                case ('delivery'):
                    return get_delivery_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('order'):
                    return get_purchase_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('production_supplier_orders'):
                    return get_production_supplier_purchase_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('production_supplier_deliveries'):
                    return get_production_supplier_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('manufacture_tasks'):
                    return get_manufacture_tasks_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('manufacture_task.new'):
                    return get_new_manufacture_task_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('operatives'):
                    return get_operatives_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('batches'):
                    return get_batches_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('manufacture_task'):
                    return get_manufacture_task_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('operative'):
                    return get_operative_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('batch'):
                    return get_batch_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('production_parts'):
                    return get_production_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('production_part'):
                    return get_production_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('raw_materials'):
                    return get_raw_materials_navigation($data, $smarty);

                case ('raw_material'):
                    return get_raw_material_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                case ('production_part.new'):
                    return get_new_production_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;
        case ('suppliers'):
            require_once 'navigation/suppliers.nav.php';

            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier_parts'):
                    return get_supplier_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier'):
                    return get_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('agent'):
                    return get_agent_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('orders'):
                    return get_purchase_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deliveries'):
                    return get_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('order'):
                    return get_purchase_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted_order'):
                    return get_deleted_purchase_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('delivery'):
                    return get_delivery_navigation($data, $smarty, $user, $db);

                case ('agents'):
                    return get_agents_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('categories'):

                    return get_suppliers_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('category'):

                    return get_suppliers_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('main_category.new'):
                    return get_suppliers_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('dashboard'):
                    return get_suppliers_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.new'):
                    return get_new_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('agent.new'):
                    return get_new_agent_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier_part'):
                    return get_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier_part.new'):
                    return get_new_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted_supplier'):
                    return get_deleted_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.user.new'):
                    return get_new_supplier_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('agent.user.new'):
                    return get_new_agent_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.order.item'):
                case ('agent.order.item'):
                    return get_order_item_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.attachment'):
                    return get_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.attachment.new'):
                    return get_new_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('timeseries_record'):
                    return get_timeseries_record_navigation($data, $smarty, $user, $db, $account);

                case ('supplier_delivery.attachment.new'):
                    return get_supplier_delivery_attachment_new_navigation($data, $smarty, $user, $db, $account);

                case ('supplier_delivery.attachment'):
                    return get_supplier_delivery_attachment_navigation($data, $smarty, $user, $db, $account);

                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );

            }

            break;

        case ('inventory'):
            require_once 'navigation/inventory.nav.php';

            //print $data['section'];
            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('inventory'):
                    return get_inventory_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('part'):
                    return get_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('part.new'):
                    return get_new_part_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('supplier_part.new'):
                    return get_new_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account, $account
                    );

                case ('product'):

                    return get_part_product_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('part.image'):
                    return get_part_image_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('transactions'):
                    return get_transactions_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('stock_history'):
                    return get_stock_history_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('stock_history.day'):
                    return get_stock_history_day_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('categories'):
                    return get_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('category'):
                    return get_parts_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('main_category.new'):
                    return get_parts_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('barcodes'):
                    return get_barcodes_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('barcode'):
                    return get_barcode_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted_barcode'):
                    return get_deleted_barcode_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('part.attachment'):
                    return get_part_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('part.attachment.new'):
                    return get_new_part_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('feedback'):
                    return get_feedback_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('feedback_item'):
                    return get_feedback_item_navigation(
                        $data, $smarty, $user, $db, $account
                    );

            }

            break;
        case ('warehouses'):
        case ('warehouses_server'):
            require_once 'navigation/warehouses.nav.php';


            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouses'):
                    return get_warehouses_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouse'):
                    return get_warehouse_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouse.new'):
                    return get_new_warehouse_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouse_area.new'):
                    return get_new_warehouse_area_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouse_areas'):
                    return get_warehouse_areas_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouse_area'):
                    return get_warehouse_area_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('locations'):
                    return get_locations_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('location'):
                    return get_location_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('location.new'):
                    return get_new_location_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted_location'):
                    return get_deleted_location_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                case 'production_deliveries':
                    return get_production_deliveries_navigation($data, $smarty);
                case 'production_delivery':
                    return get_production_delivery_navigation($data, $smarty, $user, $db);
                case ('categories'):
                    return get_categories_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('category'):
                    return get_locations_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('main_category.new'):
                    return get_locations_new_main_category_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('delivery_notes'):
                    return get_delivery_notes_navigation($data, $smarty, $user, $db, $account);

                case ('leakages'):
                    return get_leakages_navigation($data, $smarty, $user, $db, $account);

                case ('timeseries_record'):
                    return get_timeseries_record_navigation($data, $smarty, $user, $db, $account);

                case ('returns'):
                    return get_returns_navigation($data, $smarty, $user, $db, $account);

                case ('return'):
                    return get_return_navigation($data, $smarty, $user, $db, $account);


                case ('upload'):
                    return get_upload_navigation($data, $smarty, $user, $db, $account);

                case ('feedback'):
                    return get_feedback_navigation(
                        $data, $smarty, $user, $db, $account
                    );
                case ('warehouse_kpis'):
                    return get_warehouse_kips_navigation($data, $smarty, $user, $db, $account);
                case ('staff_warehouse_kpi'):
                    return get_staff_warehouse_kpi_navigation($data, $smarty, $user, $db, $account);


            }

            break;

        case ('hr'):
            require_once 'navigation/hr.nav.php';

            switch ($data['section']) {

                case ('employees'):
                case ('new_timesheet_record'):

                    return get_employees_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('contractors'):
                    return get_contractors_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('organization'):
                    return get_organization_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('employee'):
                    return get_employee_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted.employee'):
                    return get_deleted_employee_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('employee.new'):
                    return get_new_employee_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('contractor'):
                    return get_contractor_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted.contractor'):
                    return get_deleted_contractor_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('contractor.new'):
                    return get_new_contractor_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('timesheet'):
                    return get_timesheet_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('timesheets'):
                    return get_timesheets_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('employee.attachment'):
                    return get_employee_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('employee.attachment.new'):
                    return get_new_employee_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('employee.user.new'):
                    return get_new_employee_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('contractor.user.new'):
                    return get_new_contractor_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('overtimes'):
                    return get_overtimes_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('hr.history'):
                    return get_history_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('position'):
                    return get_position_navigation($data, $smarty, $user, $db, $account);

                case ('clocking_machines'):
                    return get_clocking_machines_navigation($data, $smarty, $user, $db, $account);

                case ('clocking_machine'):
                    return get_clocking_machine_navigation($data, $smarty, $user, $db, $account);

                case ('clocking_machine.new'):
                    return get_new_clocking_machine_navigation($data, $smarty, $user, $db, $account);

            }

            break;


        case ('utils'):
            require_once 'navigation/utils.nav.php';
            switch ($data['section']) {
                case ('forbidden'):
                case ('not_found'):
                    return get_utils_navigation($data);

                case ('fire'):
                    return get_fire_navigation($data);

            }

            break;
        case ('profile'):
            require_once 'navigation/users.nav.php';

            switch ($data['section']) {
                case ('profile.api_key.new'):
                    return get_profile_new_api_key_navigation($data, $smarty, $user, $db, $account);

                default:
                    return get_profile_navigation($data, $smarty, $user, $db, $account);

            }


            break;
        case ('accounting_server'):


            require_once 'navigation/accounting.nav.php';

            switch ($data['section']) {


                case ('payment_service_providers'):
                    return get_payment_service_providers_navigation($data, $user, $smarty, $db);

                case ('payment_service_provider'):
                    return get_payment_service_provider_navigation($data, $user, $smarty, $db);

                case ('payment_account'):
                    return get_payment_account_server_navigation($data, $user, $smarty, $db);

                case ('payment_accounts'):
                    return get_payment_accounts_navigation(
                        $data, $user, $smarty, $db
                    );

                case ('payment'):
                    return get_payment_navigation($data, $user, $smarty, $db);

                case ('payments'):
                    return get_payments_navigation($data, $user, $smarty, $db);

                case ('credits'):
                    return get_credits_navigation($data, $user, $smarty, $db);

                case ('payments_by_store'):
                    return get_payments_by_store_navigation($data, $user, $smarty, $db);

                case ('invoice'):

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);

                case ('invoices'):
                    return get_invoices_server_navigation($data, $smarty, $user, $db, $account);

                case ('deleted_invoices_server'):
                    return get_deleted_invoices_server_navigation($data, $smarty, $user, $db, $account);

                case ('categories'):
                    return get_invoices_categories_server_navigation($data, $smarty, $user, $db, $account);

                case ('category'):
                    return get_invoices_category_server_navigation($data, $smarty, $user, $db, $account);

                case ('deleted_invoice'):
                    return get_deleted_invoice_navigation($data, $smarty, $user, $db, $account);

            }
            break;
        case ('accounting'):
            require_once 'navigation/accounting.nav.php';


            switch ($data['section']) {


                case ('invoices'):
                    return get_invoices_navigation($data, $smarty, $user, $db, $account);


                case ('invoice'):

                    return get_invoice_navigation($data, $smarty, $user, $db, $account);

                case ('deleted_invoice'):
                    return get_deleted_invoice_navigation($data, $smarty, $user, $db, $account);

                case ('payment_service_provider'):
                    return get_payment_service_provider_navigation(
                        $data, $user, $smarty, $db
                    );

                case ('payment_service_providers'):
                    return get_payment_service_providers_navigation(
                        $data, $user, $smarty, $db
                    );

                case ('payment_account'):
                    return get_payment_account_navigation($data, $user, $smarty, $db);

                case ('payment_accounts'):
                    return get_payment_accounts_navigation(
                        $data, $user, $smarty, $db
                    );

                case ('payment'):
                    return get_payment_navigation($data, $user, $smarty, $db);

                case ('payments'):
                    return get_payments_navigation($data, $user, $smarty, $db);

                case ('credits'):
                    return get_credits_navigation($data, $user, $smarty, $db);

                case ('deleted_invoices'):
                    return get_deleted_invoices_navigation($data, $smarty, $user, $db, $account);

            }
            break;
        case ('account'):

            require_once 'navigation/account.nav.php';

            switch ($data['section']) {
                case ('account'):
                    return get_account_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('data_sets'):
                    return get_data_sets_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('timeseries'):
                    return get_timeseries_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('timeserie'):
                    return get_timeserie_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('images'):
                    return get_images_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('attachments'):
                    return get_attachments_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('uploads'):
                    return get_uploads_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('materials'):
                    return get_materials_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('material'):
                    return get_material_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('upload'):
                    return get_upload_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('osf'):
                    return get_osf_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('isf'):
                    return get_isf_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('orders_index'):
                    return get_orders_index_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('settings'):
                    return get_settings_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }


            break;
        case ('settings'):
            require_once 'navigation/account.nav.php';

            return get_settings_navigation($data);

        case 'agent_profile':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('profile'):
                    return get_agent_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;
        case 'agent_suppliers':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier'):
                    return get_supplier_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier_part'):
                    return get_supplier_part_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.attachment'):
                    return get_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('supplier.attachment.new'):
                    return get_new_supplier_attachment_navigation(
                        $data, $smarty, $user, $db, $account
                    );

            }
        case 'agent_client_orders':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('orders'):
                    return get_agent_client_orders_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('client_order'):
                    return get_agent_client_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('agent_supplier_order'):
                    return get_agent_supplier_order_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
        case 'agent_client_deliveries':

            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('deliveries'):
                    return get_deliveries_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('agent_delivery'):
                    return get_agent_delivery_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;
        case 'agent_parts':
            require_once 'navigation/agent.nav.php';
            switch ($data['section']) {
                case ('parts'):
                    return get_parts_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;
        case 'users':
            require_once 'navigation/users.nav.php';
            switch ($data['section']) {
                case ('users'):
                    return get_users_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('staff'):
                    return get_staff_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('contractors'):
                    return get_contractors_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('suppliers'):
                    return get_suppliers_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('agents'):
                    return get_agents_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('warehouse'):
                    return get_warehouse_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('root'):
                    return get_root_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('user'):
                    return get_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('deleted.user'):
                    return get_deleted_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('suppliers.user'):
                    return get_supplierss_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );


                case ('warehouse.user'):
                    return get_warehouse_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('root.user'):
                    return get_root_user_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('user.api_key') :
                    return get_api_key_navigation(
                        $data, $smarty, $user, $db, $account
                    );

                case ('user.api_key.new') :
                    return get_new_api_key_navigation($data, $smarty, $user, $db, $account);
                case ('deleted_api_key') :
                    return get_deleted_api_key_navigation(
                        $data, $smarty, $user, $db, $account
                    );


            }
            break;


        case ('fulfilment'):
            require_once 'navigation/fulfilment.nav.php';

            switch ($data['section']) {
                case ('dashboard'):
                    return get_dashboard_navigation($data, $smarty, $user, $db, $account);
                case ('locations'):
                    return get_locations_navigation($data, $smarty, $user, $db, $account);
                case ('location'):
                    return get_location_navigation($data, $smarty, $user, $db, $account);
                case ('customers'):
                    return get_customers_navigation($data, $smarty, $user, $db, $account);
                case ('asset_keeping_customer'):
                case ('dropshipping_customer'):
                    return get_customer_navigation($data, $smarty, $user, $db);
                case ('fulfilment_parts'):
                    return get_stored_parts_navigation($data, $smarty, $user, $db, $account);
                case 'production_deliveries':
                    return get_production_deliveries_navigation($data, $smarty);
                case 'delivery':
                    return get_delivery_navigation($data, $smarty, $user, $db);


            }

            break;


        default:
            return 'Module not found';
    }

}