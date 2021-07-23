<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 13:30:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
require_once 'navigation/customers.nav.php';
function get_navigation_customers($data, $smarty, $user, $db, $account){
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
            return get_upload_navigation($data, $smarty, $user, $db, $account);


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
        case ('customer.attachment'):
            return get_customer_attachment_navigation($data, $smarty,$user, $db, $account);
        case ('customer.attachment.new'):
            return get_new_customer_attachment_navigation($data, $smarty);


    }
}


function get_navigation_customers_server($data, $smarty): array {

    switch ($data['section']) {
        case ('customers'):
        case('pending_orders'):
        case('insights'):
            return get_customers_server_navigation($data, $smarty);



    }
}