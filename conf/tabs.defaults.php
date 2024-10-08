<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 12:09:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


function get_tab_defaults_data(): array {

    include_once 'conf/export_fields.php';
    include_once 'conf/elements_options.php';

    $default_rrp_options = array(
        500,
        100,
        50,
        20
    );


    return array(


        'customers'                   => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),
        'customer_category.customers' => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),

        'customers_dropshipping'   => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),
        'customers.list'           => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'export_fields' => get_export_fields('customers')

        ),
        'customers.sub_categories' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),

        'customers.lists'           => array(
            'view'        => 'overview',
            'sort_key'    => 'creation_date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'customers.categories'      => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'customers.geo'             => array(
            'view'        => 'overview',
            'sort_key'    => 'customers',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'country'
        ),
        'customers_poll.queries'    => array(
            'view'        => 'overview',
            'sort_key'    => 'position',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'query'
        ),
        'customers.registrations'   => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',

        ),
        'poll_query.history'        => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('poll_query_history'))[0],
            'elements'      => get_elements_option('poll_query_history')
        ),
        'poll_query_option.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('poll_query_option_history'))[0],
            'elements'      => get_elements_option('poll_query_option_history')
        ),


        'customers_poll.query.answers'   => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'customers_poll.query.options'   => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'customers_poll.reply.customers' => array(
            'view'        => 'overview',
            'sort_key'    => 'query',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'query'
        ),
        'poll_query_option.customers'    => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),

        'prospects' => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'email',
            'elements_type' => array_keys(get_elements_option('prospects'))[0],
            'elements'      => get_elements_option('prospects'),
            'export_fields' => get_export_fields('prospects')

        ),


        'email_campaign_type.mailshots' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),

        'account.mailshots' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('mailshots'))[0],
            'elements'      => get_elements_option('mailshots'),
        ),


        'customer_list.mailshots' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),

        'category.mailshots' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),

        'product.mailshots' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),

        'oss_notification.next_recipients' => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),


        'gr_reminder.next_recipients' => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),

        'email_campaigns.newsletters'              => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',
        ),
        'mailshot.workshop.previous_mailshots'     => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',
        ),
        'mailshot.workshop.other_stores_mailshots' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',
        ),
        'email_campaigns.mailshots'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',
        ),
        'email_campaigns.reminders'                => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,

        ),
        'email_campaigns.abandoned_basket'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',
        ),
        'email_campaigns.back_in_stock'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',
        ),

        'email_template_types'   => array(
            'view'        => 'overview',
            'sort_key'    => 'type',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'type',


        ),
        'customer_notifications' => array(
            'view'        => 'overview',
            'sort_key'    => 'type',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,


        ),
        'user_notifications'     => array(
            'view'        => 'overview',
            'sort_key'    => 'type',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,


        ),
        'marketing_emails'       => array(
            'view'        => 'overview',
            'sort_key'    => 'type',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,


        ),


        'customer.history'              => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('customer_history'))[0],
            'elements'      => get_elements_option('customer_history')
        ),
        'customer.orders'               => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders')
        ),
        'customer.orders_dropshipping'  => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders')
        ),
        'customer.invoices'             => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices'),
            'export_fields' => get_export_fields('invoices')

        ),
        'customer.products'             => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('products'))[0],
            'elements'      => get_elements_option('products'),
            'export_fields' => get_export_fields('products')

        ),
        'customer.active_portfolio'     => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('customer_portfolio'))[0],
            'elements'      => get_elements_option('customer_portfolio'),
            'export_fields' => get_export_fields('products')

        ),
        'customer.removed_portfolio'    => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',


        ),
        'customer.marketing.favourites' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',
        ),

        'customers_server'            => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),
        'customer.marketing.products' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),
        'customer.marketing.families' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),

        'customer.sales.history'        => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),
        'customer.product.orders'       => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',

            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders'),
            'export_fields' => get_export_fields('orders')
        ),
        'customer.product.invoices'     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices'),
            'export_fields' => get_export_fields('invoices')
        ),
        'customer.product.transactions' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'customer.credit_blockchain'    => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'from'        => '',
            'to'          => '',
            'f_field'     => '',


        ),
        'prospect.history'              => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('prospect_history'))[0],
            'elements'      => get_elements_option('prospect_history')
        ),
        'orders.website'                => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),


        ),

        'orders.website.purges' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',


        ),

        'orders.website.mailshots' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id',


        ),

        'orders.in_process.not_paid' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),

        ),
        'orders.in_process.paid'     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),

        ),


        'orders.in_warehouse_no_alerts'   => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),
        ),
        'orders.in_warehouse_with_alerts' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),
        ),

        'orders.packed' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),
        ),

        'orders.packed_done' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),
        ),


        'orders.approved' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),
        ),


        'orders.dispatched_today' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => 'location',
            'elements'      => get_elements_option('orders_in_control_panel'),
        ),

        'orders' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 20,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => 'state',
            'elements'      => get_elements_option('orders'),
            'export_fields' => get_export_fields('orders')

        ),

        'orders_server'      => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => 'state',
            'elements'      => get_elements_option('orders')

        ),
        'order.items'        => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'order.all_products' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',


        ),


        'order_customer.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('customer_history'))[0],
            'elements'      => get_elements_option('customer_history')
        ),

        'order.history'        => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'order.invoices'       => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('invoices')

        ),
        'order.delivery_notes' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('delivery_notes')

        ),
        'shipper.consignments' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('shipper_consignments')

        ),
        'order.payments'       => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'

        ),

        'refund.new.items'      => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'refund.new.items_tax'  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'replacement.new.items' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'return.new.items'      => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'refund.items'          => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'replacement.items'     => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'return.items'          => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),

        'delivery_note.invoices'           => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('invoices')


        ),
        'delivery_note.orders'             => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('orders')


        ),
        'delivery_note.history'            => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'delivery_note.items'              => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            //   'export_fields' => get_export_fields('delivery_note.parts')


        ),
        'delivery_note.units'              => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('delivery_note.units')


        ),
        'delivery_note.tariff_codes'       => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'tariff_code',
            'export_fields' => get_export_fields('delivery_note.tariff_codes')


        ),
        'delivery_note.picking_aid'        => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'delivery_note_cancelled.items'    => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'delivery_note.fast_track_packing' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),

        'invoice.items'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'refund.items_tax_only' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),

        'deleted_invoice.items'  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,

        ),
        'invoice.orders'         => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('orders'),


        ),
        'invoice.delivery_notes' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('delivery_notes')


        ),
        'invoice.history'        => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'invoices'               => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices'),
            'export_fields' => get_export_fields('invoices'),

        ),

        'invoices_per_store' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',


        ),

        'sales_representative.invoices' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices'),
            'export_fields' => get_export_fields('invoices')

        ),

        'sales_representative.invoices_group_by_customer' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'customer',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',
            //   'export_fields' => get_export_fields('invoices_group_by_customer')

        ),


        'category.invoices' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices'),
            'export_fields' => get_export_fields('invoices')

        ),


        'invoices.categories'         => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'category.invoice_categories' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'label',
            'f_period'    => 'ytd',
            //  'elements_type'=>array_keys(get_elements_option('suppliers'))[0],
            //  'elements'=>get_elements_option('suppliers')

        ),

        'invoices_server'            => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices'),
            'export_fields' => get_export_fields('invoices'),

        ),
        'deleted_invoices_server'    => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices')
        ),
        'deleted_invoices'           => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('invoices'))[0],
            'elements'      => get_elements_option('invoices')
        ),
        'invoices_server.categories' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'delivery_notes'             => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('delivery_notes'))[0],
            'elements'      => get_elements_option('delivery_notes'),
            'export_fields' => get_export_fields('delivery_notes')

        ),
        'delivery_notes_server'      => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('delivery_notes'))[0],
            'elements'      => get_elements_option('delivery_notes')
        ),
        'consignments'               => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('consignments'))[0],
            'elements'      => get_elements_option('consignments'),
            'export_fields' => get_export_fields('consignments')

        ),
        'consignment.delivery_notes' => array(
            'view'          => 'overview',
            'sort_key'      => 'number',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => array_keys(get_elements_option('delivery_notes'))[0],
            'export_fields' => get_export_fields('delivery_notes')

        ),
        'consignment.parts'          => array(
            'view'        => 'overview',
            'sort_key'    => 'part',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            //'elements_type' => array_keys(get_elements_option('delivery_notes'))[0],
            //'export_fields' => get_export_fields('delivery_notes')

        ),
        'consignment.tariff_codes'   => array(
            'view'        => 'overview',
            'sort_key'    => 'tariff_code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'tariff_code',
            //  'elements_type' => array_keys(get_elements_option('delivery_notes'))[0],
            //  'export_fields' => get_export_fields('delivery_notes')

        ),
        'returns_server'             => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'customer',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),

        'pending_delivery_notes' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'customer',


        ),

        'delivery_notes.ready_to_pick' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
        ),

        'delivery_notes.assigned' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
        ),

        'orders_index' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),


        'orders_group_by_store' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),

        'mailroom_group_by_store' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),
        'offers_group_by_store'   => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),


        'delivery_notes_group_by_store' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),

        'payments_group_by_store' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),
        'credits_group_by_store'  => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'percentages' => 0
        ),

        'stores'                       => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),
        'stores.sales.history'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',

        ),
        'store.charges'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'store.current_shipping_zones' => array(
            'view'        => 'overview',
            'sort_key'    => 'position',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),
        'store.shipping_zones_schemas' => array(
            'view'        => 'overview',
            'sort_key'    => 'label',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'label'
        ),
        'shipping_zone_schema.zones'   => array(
            'view'        => 'overview',
            'sort_key'    => 'position',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),

        'store.products' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('products'))[0],
            'elements'      => get_elements_option('products'),
            'export_fields' => get_export_fields('products')

        ),

        'back_to_stock_notification_request.products' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',


        ),

        'store.services'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('services'))[0],
            'elements'      => get_elements_option('services'),

        ),
        'store.sales.history'   => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',

        ),
        'category.products'     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('products'))[0],
            'elements'      => get_elements_option('products'),

        ),
        'category.all_products' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('products'))[0],
            'elements'      => get_elements_option('products'),

        ),
        'products.categories'   => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'product.parts'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),


        'product.variants' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',

        ),

        'product.history'       => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'product.sales.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),
        'product.orders'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'ytd',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders'),
            'export_fields' => get_export_fields('orders')
        ),

        'product.customers'                                    => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

            'export_fields' => get_export_fields('customers')

        ),
        'product.back_to_stock_notification_request.customers' => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

            'export_fields' => get_export_fields('customers')

        ),

        'product.customers.favored' => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),


        'product.images'        => array(
            'view'        => 'overview',
            'sort_key'    => 'image_order',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'service.history'       => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'service.sales.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),
        'service.orders'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'ytd',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders'),
            'export_fields' => get_export_fields('orders')
        ),

        'charge.orders'    => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'customer',
            'from'          => '',
            'to'            => '',
            'period'        => 'ytd',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders'),
            'export_fields' => get_export_fields('orders')
        ),
        'charge.customers' => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers')
        ),

        'charge.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'elements_type' => array_keys(get_elements_option('deal_history'))[0],
            'elements'      => get_elements_option('deal_history')
        ),


        'category_customers' => array(
            'view'        => 'overview',
            'sort_key'    => 'invoiced_amount',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

            'export_fields' => get_export_fields('customers')

        ),

        'category_customers_favored' => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),


        'category.product.sales.history'       => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),
        /*
    'category.product_families'      => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'elements_type' => array_keys(get_elements_option('product_categories'))[0],
        'elements'      => get_elements_option('product_categories'),
        'f_field'       => 'code'
    ),
    */
        'category.product_categories.products' => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 0,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'elements_type' => array_keys(get_elements_option('product_categories'))[0],
            'elements'      => get_elements_option('product_categories'),
            'f_period'      => 'ytd',
            'f_field'       => 'code'
        ),


        'category.product_categories.categories' => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 0,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'elements_type' => array_keys(get_elements_option('product_categories'))[0],
            'elements'      => get_elements_option('product_categories'),
            'f_period'      => 'ytd',
            'f_field'       => 'code'
        ),


        'websites'         => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'elements_type' => array_keys(get_elements_option('websites'))[0],
            'elements'      => get_elements_option('websites'),
        ),
        'website.webpages' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('webpages'))[0],
            'elements'      => get_elements_option('webpages'),
        ),

        'website.in_process_webpages' => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 0,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('online_webpages'))[0],
            'elements'      => get_elements_option('online_webpages'),
        ),

        'website.ready_webpages' => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 0,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('online_webpages'))[0],
            'elements'      => get_elements_option('online_webpages'),
        ),

        'website.online_webpages'          => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('online_webpages'))[0],
            'elements'      => get_elements_option('online_webpages'),
        ),
        'website.offline_webpages'         => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'elements_type' => array_keys(get_elements_option('online_webpages'))[0],
            'elements'      => get_elements_option('online_webpages'),

        ),
        'webpage_type.online_webpages'     => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),
        'webpage_type.in_process_webpages' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),
        'webpage_type.offline_webpages'    => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),

        'website.webpage.types' => array(
            'view'        => 'overview',
            'sort_key'    => 'type',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),


        'website.footer.versions' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),


        'page.blocks' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),

        'website.favourites.customers' => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers')
        ),
        'website.search.queries'       => array(
            'view'        => 'overview',
            'sort_key'    => 'number',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'query',

        ),
        'website.search.history'       => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'query',

        ),
        'website.users'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'handle',

        ),
        'page.users'                   => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'handle',

        ),
        'page.versions'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'website.user.login_history'   => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'ip',
            'f_period'    => 'all',

        ),
        'webpage.assets'               => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),
        'webpage.containers'           => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),

        'suppliers' => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('suppliers'))[0],
            'elements'      => get_elements_option('suppliers')

        ),

        'suppliers.supplier_parts.surplus' => array(
            'view'        => 'reorder',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',

            'export_fields' => get_export_fields('supplier_parts')


        ),
        'suppliers.supplier_parts.ok'      => array(
            'view'        => 'reorder',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',

            'export_fields' => get_export_fields('supplier_parts')


        ),

        'suppliers.supplier_parts.low'          => array(
            'view'        => 'reorder',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',

            'export_fields' => get_export_fields('supplier_parts')
        ),
        'suppliers.supplier_parts.critical'     => array(
            'view'        => 'reorder',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',

            'export_fields' => get_export_fields('supplier_parts')
        ),
        'suppliers.supplier_parts.out_of_stock' => array(
            'view'        => 'reorder',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',

            'export_fields' => get_export_fields('supplier_parts')
        ),
        'suppliers.supplier_parts.all'          => array(
            'view'        => 'reorder',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',

            'export_fields' => get_export_fields('supplier_parts')
        ),

        'category.suppliers'     => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'f_period'    => 'ytd',
        ),
        'category.all_suppliers' => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'f_period'    => 'ytd',
        ),
        'suppliers_edit'         => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

        ),
        'suppliers.lists'        => array(
            'view'        => 'overview',
            'sort_key'    => 'creation_date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'suppliers.categories'   => array(
            'view'       => 'overview',
            'sort_key'   => 'code',
            'sort_order' => 1,
            'rpp'        => 100,

            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),


        'suppliers.orders'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_orders'))[0],
            'elements'      => get_elements_option('supplier_orders')
        ),
        'suppliers.deliveries'    => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            'elements'      => get_elements_option('supplier_deliveries')
        ),
        'supplier.history'        => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('supplier_history'))[0],
            'elements'      => get_elements_option('supplier_history')
        ),
        'supplier.images'         => array(
            'view'        => 'overview',
            'sort_key'    => 'image_order',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'supplier.supplier_parts' => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('supplier_parts'))[0],
            'elements'      => get_elements_option('supplier_parts'),
            'export_fields' => get_export_fields('supplier_parts')

        ),

        'agent_parts' => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('supplier_parts'))[0],
            'elements'      => get_elements_option('agent_parts'),
            'export_fields' => get_export_fields('agent_parts')

        ),


        'supplier.order.all_supplier_parts'         => array(
            'view'        => 'cartons',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'job_order.all_production_parts'            => array(
            'view'        => 'batches',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'supplier.orders'                           => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_orders'))[0],
            'elements'      => get_elements_option('supplier_orders')
        ),
        'production_supplier.orders'                => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('production_orders'))[0],
            'elements'      => get_elements_option('production_orders')
        ),
        'operative.job_orders'                      => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('production_orders'))[0],
            'elements'      => get_elements_option('production_orders')
        ),
        'supplier.deliveries'                       => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            'elements'      => get_elements_option('supplier_deliveries')
        ),
        'production_supplier.deliveries'            => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('production_deliveries'))[0],
            'elements'      => get_elements_option('production_deliveries')
        ),
        'warehouse.production_deliveries.todo'      => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',

        ),
        'warehouse.production_deliveries.done'      => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'warehouse.production_deliveries.cancelled' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'warehouse.production_deliveries.all'       => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'picking_bands'                             => array(
            'view'        => 'overview',
            'sort_key'    => 'amount',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),
        'packing_bands'                             => array(
            'view'        => 'overview',
            'sort_key'    => 'amount',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),

        'supplier.order.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('supplier_order_history'))[0],
            'elements'      => get_elements_option('supplier_order_history')
        ),

        'supplier.delivery.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('supplier_delivery_history'))[0],
            'elements'      => get_elements_option('supplier_delivery_history')
        ),


        'supplier.order.items' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('supplier.order.items'),
            'elements_type' => array_keys(get_elements_option('supplier_order_items'))[0],
            'elements'      => get_elements_option('supplier_order_items')

        ),

        'job_order.items'                   => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('supplier.order.items'),
            'elements_type' => array_keys(get_elements_option('job_order_items'))[0],
            'elements'      => get_elements_option('job_order_items')

        ),
        'supplier.order.items_in_process'   => array(
            'view'          => 'cartons',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('supplier.order.items')
        ),
        'job_order.items_in_process'        => array(
            'view'          => 'batches',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('supplier.order.items')
        ),
        'fulfilment.delivery.assets'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'id',
            'export_fields' => get_export_fields('fulfilment.delivery.items')
        ),
        'fulfilment.customer.assets'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'id',
            'elements_type' => array_keys(get_elements_option('fulfilment_customer_assets'))[0],
            'elements'      => get_elements_option('fulfilment_customer_assets')
        ),
        'fulfilment.asset.history'          => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'picking_pipeline.history'          => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'fulfilment.delivery.history'       => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => 'type',
            'elements'      => get_elements_option('history_notes'),
        ),
        'deleted.supplier.order.items'      => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'supplier.order.supplier_parts'     => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('supplier_parts'))[0],
            'elements'      => get_elements_option('supplier_parts')

        ),
        'category.supplier_categories'      => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'label',
            'f_period'    => 'ytd',
            //  'elements_type'=>array_keys(get_elements_option('suppliers'))[0],
            //  'elements'=>get_elements_option('suppliers')

        ),
        'client_order.items'                => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('client_order_items')


        ),
        'agent_supplier_order.items'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('client_order_items')


        ),
        'client_order.suppliers'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'client_order.supplier.items'       => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'agent.delivery.items'              => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'agent.delivery.items_in_warehouse' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),

        'supplier.delivery.items'          => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'supplier.delivery.items_mismatch' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'supplier.delivery.costing'        => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),

        'supplier.delivery.items_done' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'return.items_done'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),

        'supplier.delivery.check_items' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'return.check_items'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),

        'supplier.delivery.supplier_parts' => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('supplier_parts'))[0],
            'elements'      => get_elements_option('supplier_parts')

        ),


        'supplier_part.supplier.orders'     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_orders'))[0],
            'elements'      => get_elements_option('supplier_orders')
        ),
        'supplier_part.supplier.deliveries' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            'elements'      => get_elements_option('supplier_deliveries')
        ),


        'production_part.supplier.orders'     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('production_orders'))[0],
            'elements'      => get_elements_option('production_orders')
        ),
        'production_part.supplier.deliveries' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('production_deliveries'))[0],
            'elements'      => get_elements_option('production_deliveries')
        ),


        'agents'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'f_period'    => 'ytd',
        ),
        'agent.history'           => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('agent_history'))[0],
            'elements'      => get_elements_option('agent_history')
        ),
        'agent.images'            => array(
            'view'        => 'overview',
            'sort_key'    => 'image_order',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'agent.suppliers'         => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('agent_suppliers'))[0],
            'elements'      => get_elements_option('agent_suppliers')

        ),
        'agent.supplier_parts'    => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('supplier_parts'))[0],
            'elements'      => get_elements_option('supplier_parts'),
            'export_fields' => get_export_fields('supplier_parts')


        ),
        'agent.orders'            => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('agent_orders'))[0],
            'elements'      => get_elements_option('agent_orders')
        ),
        'agent.client_orders'     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('agent_client_orders'))[0],
            'elements'      => get_elements_option('agent_client_orders')
        ),
        'agent.deliveries'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            'elements'      => get_elements_option('supplier_deliveries')
        ),
        'agent.client_deliveries' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('agent_client_deliveries'))[0],
            'elements'      => get_elements_option('agent_client_deliveries')
        ),


        'agent.users'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'handle'
        ),
        'supplier.users'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'handle'
        ),
        'warehouses'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
        ),
        'warehouse.areas'             => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
        ),
        'warehouse.picking_pipelines' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),
        'warehouse.returns'           => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            'elements'      => get_elements_option('supplier_deliveries')
        ),


        'category.location_categories' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),

        'locations.categories' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'part.history'         => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'part.images'          => array(
            'view'        => 'overview',
            'sort_key'    => 'image_order',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'part.products'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('products'))[0],
            'elements'      => get_elements_option('products'),

        ),

        'part.locations' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),


        'part.attachment.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'part.supplier_parts'     => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
        ),

        'part.supplier.orders'        => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_orders'))[0],
            'elements'      => get_elements_option('supplier_orders')
        ),
        'part.supplier.deliveries'    => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            'elements'      => get_elements_option('supplier_deliveries')
        ),
        'part.sales.history'          => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),
        'category.part.sales.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),
        'warehouse.locations'         => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'elements_type' => array_keys(get_elements_option('locations'))[0],
            'elements'      => get_elements_option('locations'),
            'export_fields' => get_export_fields('locations')
        ),
        'warehouse.deleted_locations' => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('deleted_locations')
        ),

        'warehouse.replenishments'                             => array(
            'view'        => 'overview',
            'sort_key'    => 'location',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'location'
        ),
        'warehouse.parts'                                      => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('part_locations')

        ),
        'warehouse.part_locations_with_errors.wget'            => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'warehouse.parts_to_replenish_external_warehouse.wget' => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'warehouse.parts_to_replenish_pipeline.wget'           => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'warehouse_area.locations'                             => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'elements_type' => array_keys(get_elements_option('locations'))[0],
            'elements'      => get_elements_option('locations'),
            'export_fields' => get_export_fields('locations')
        ),
        'warehouse_area.parts'                                 => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('part_locations')

        ),
        'warehouse_area.history'                               => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'parts_with_unknown_location.wget'                     => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),

        'warehouse.history'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'location.history'                   => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('location_history'))[0],
            'elements'      => get_elements_option('location_history')
        ),
        'location.parts'                     => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'location.stock.transactions'        => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('part_stock_transactions'))[0],
            'elements'      => get_elements_option('part_stock_transactions'),
        ),
        'fulfilment.locations'               => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
        ),
        'customer.parts'                     => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            // 'elements_type' => array_keys(get_elements_option('supplier_parts'))[0],
            // 'elements'      => get_elements_option('customer_parts'),
            // 'export_fields' => get_export_fields('customer_parts')

        ),
        'fulfilment.stored_parts'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'fulfilment.asset_keeping_customers' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),
        'fulfilment.dropshipping_customers'  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),
        'customer.deliveries'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',
            //'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            //'elements'      => get_elements_option('supplier_deliveries')
        ),
        'fulfilment.deliveries'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',
            //'elements_type' => array_keys(get_elements_option('supplier_deliveries'))[0],
            //'elements'      => get_elements_option('supplier_deliveries')
        ),
        'inventory.parts'                    => array(
            'view'            => 'overview',
            'sort_key'        => 'id',
            'sort_order'      => 1,
            'rpp'             => 100,
            'rpp_options'     => $default_rrp_options,
            'f_field'         => 'reference',
            'f_period'        => 'ytd',
            'show_production' => 'No',
            'elements_type'   => array_keys(get_elements_option('parts'))[0],
            'elements'        => get_elements_option('parts'),
            'export_fields'   => get_export_fields('parts')

        ),
        'production.active_parts'            => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('parts'))[0],
            'elements'      => get_elements_option('parts'),
            'export_fields' => get_export_fields('parts')

        ),

        'inventory.parts_no_sko_barcode.wget'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'inventory.parts_weight_errors.wget'                  => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('parts_weight_errors'))[0],
            'elements'      => get_elements_option('parts_weight_errors'),
            'export_fields' => get_export_fields('parts_weight_errors')

        ),
        'inventory.parts_no_products.wget'                    => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',


        ),
        'inventory.parts_forced_not_for_sale_on_website.wget' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',


        ),
        'inventory.parts_barcode_errors.wget'                 => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'barcode',
            'elements_type' => array_keys(get_elements_option('part_barcode_errors'))[0],
            'elements'      => get_elements_option('part_barcode_errors'),
            'export_fields' => get_export_fields('part_barcode_errors')


        ),


        'inventory.discontinued_parts'       => array(
            'view'            => 'overview',
            'sort_key'        => 'valid_to',
            'sort_order'      => 1,
            'rpp'             => 100,
            'f_period'        => 'all',
            'show_production' => 'No',
            'rpp_options'     => $default_rrp_options,
            'f_field'         => 'reference',
            'export_fields'   => get_export_fields('parts')

        ),
        'inventory.discontinuing_parts'      => array(
            'view'            => 'overview',
            'sort_key'        => 'stock_value',
            'sort_order'      => 1,
            'rpp'             => 100,
            'f_period'        => 'all',
            'show_production' => 'No',
            'rpp_options'     => $default_rrp_options,
            'f_field'         => 'reference',
            'export_fields'   => get_export_fields('parts')

        ),
        'production.discontinued_parts'      => array(
            'view'          => 'overview',
            'sort_key'      => 'valid_to',
            'sort_order'    => 1,
            'rpp'           => 100,
            'f_period'      => 'all',
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('parts')

        ),
        'production.discontinuing_parts'     => array(
            'view'          => 'overview',
            'sort_key'      => 'stock_value',
            'sort_order'    => 1,
            'rpp'           => 100,
            'f_period'      => 'all',
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('parts')

        ),
        'inventory.in_process_parts'         => array(
            'view'            => 'overview',
            'sort_key'        => 'valid_from',
            'sort_order'      => 1,
            'rpp'             => 100,
            'show_production' => 'No',
            'rpp_options'     => $default_rrp_options,
            'f_field'         => 'reference',
            'export_fields'   => get_export_fields('parts')

        ),
        'production.in_process_parts'        => array(
            'view'          => 'overview',
            'sort_key'      => 'valid_from',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('parts')

        ),
        'warehouse.feedback'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'inventory.feedback'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'supplier.feedback'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'part.feedback'                      => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'part_category.feedback'             => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'supplier_part.feedback'             => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'inventory.feedback_per_part'        => array(
            'view'        => 'overview',
            'sort_key'    => 'number_feedback',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'inventory.feedback_per_part_family' => array(
            'view'        => 'overview',
            'sort_key'    => 'number_feedback',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'f_period'    => 'ytd',


        ),
        'part_families'                      => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('part_categories'))[0],
            'elements'      => get_elements_option('part_categories'),
            'export_fields' => get_export_fields('part_categories')

        ),


        'category.part.discontinued_subjects' => array(
            'view'        => 'overview',
            'sort_key'    => 'to',
            'sort_order'  => 1,
            'rpp'         => 100,
            'f_period'    => 'all',
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'
        ),
        'part.stock.transactions'             => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('part_stock_transactions'))[0],
            'elements'      => get_elements_option('part_stock_transactions'),
        ),
        'part.stock.cost'                     => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note',
        ),

        'part.stock.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note',
            'frequency'   => 'monthly',

        ),


        'inventory.stock.transactions' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('part_stock_transactions'))[0],
            'elements'      => get_elements_option('part_stock_transactions'),

        ),

        'inventory.stock.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note',
            'frequency'   => 'monthly',


        ),

        'category.parts'          => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 0,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('parts'))[0],
            'elements'      => get_elements_option('parts'),
        ),
        'category.all_parts'      => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('parts'))[0],
            'elements'      => get_elements_option('parts'),

        ),
        'category_root.all_parts' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('category_root_subjects'))[0],
            'elements'      => get_elements_option('category_root_subjects'),

        ),

        'inventory.barcodes' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'elements_type' => array_keys(get_elements_option('barcodes'))[0],
            'elements'      => get_elements_option('barcodes'),
        ),


        'part_family.part_locations' => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),

        'part_family.product_families' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd'
        ),
        'part_family.products'         => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('products'))[0],
            'elements'      => get_elements_option('products'),

        ),
        'barcode.history'              => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'production.suppliers'         => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',


        ),
        'production.production_parts'  => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('production_products'))[0],
            'elements'      => get_elements_option('production_products'),
            'export_fields' => get_export_fields('production_products')

        ),
        'production.materials'         => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',


        ),
        'bill_of_materials'            => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',


        ),
        'production_part.tasks'        => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',

        ),
        'operatives'                   => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'batches'                      => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'id'
        ),
        'manufacture_tasks'            => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),

        'overtimes'           => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'
        ),
        'overtime.timesheets' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'alias',

        ),
        'overtime.employees'  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'alias',

        ),

        'employees'           => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'exemployees'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'salesmen'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'deleted.employees'   => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'contractors'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'deleted.contractors' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'position.employees'  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),

        'timesheets.months'     => array(
            'view'        => 'overview',
            'sort_key'    => 'month',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'year'        => strtotime('now'),

        ),
        'timesheets.weeks'      => array(
            'view'        => 'overview',
            'sort_key'    => 'month',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'year'        => strtotime('now'),

        ),
        'timesheets.days'       => array(
            'view'        => 'overview',
            'sort_key'    => 'month',
            'sort_order'  => -1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'year'        => strtotime('now'),

        ),
        'timesheets.timesheets' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'alias',
        ),
        'timesheets.employees'  => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

        ),
        'fire'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'status',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

        ),
        'attendance'            => array(
            'view'          => 'overview',
            'sort_key'      => 'status',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('attendance'))[0],
            'elements'      => get_elements_option('attendance'),

        ),

        'employees.timesheets'         => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',
        ),
        'employees.timesheets.records' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'employee.timesheets.records'  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'employee.timesheets'          => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',
        ),
        'employee.history'             => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'employee.attachments'         => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'employee.images'              => array(
            'view'        => 'overview',
            'sort_key'    => 'image_order',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'deleted.employee.history'     => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'hr.history'                   => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'timesheet.records'            => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',


        ),
        'employee.attachment.history'  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),

        'contractor.history'         => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'deleted.contractor.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'upload.employees'           => array(
            'view'        => 'overview',
            'sort_key'    => 'row',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'object_name'
        ),
        'hr.uploads'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),
        'organization.positions'     => array(
            'view'        => 'overview',
            'sort_key'    => 'position',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),
        'reports'                    => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
        ),
        'data_sets'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'timeseries_types'           => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'timeseries'                 => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'timeserie.records'          => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'frequency'     => 'monthly',
            'f_field'       => '',
            'export_fields' => get_export_fields('timeserie_records')

        ),
        'data_sets.images'           => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),

        'data_sets.attachments'     => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'data_sets.uploads'         => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'data_sets.materials'       => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'upload.records'            => array(
            'view'        => 'overview',
            'sort_key'    => 'row',
            'sort_order'  => 0,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'user_categories'           => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'users'                     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'handle',
            'elements_type' => array_keys(get_elements_option('users'))[0],
            'elements'      => get_elements_option('users'),
        ),
        'users.staff.groups'        => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
        ),
        'payment_service_providers' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'

        ),
        'payments'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'


        ),
        'account.payments'          => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('payments'))[0],
            'elements'      => get_elements_option('payments'),


        ),

        'credits'         => array(
            'view'        => 'overview',
            'sort_key'    => 'customer',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'customer'


        ),
        'account.credits' => array(
            'view'        => 'overview',
            'sort_key'    => 'customer',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'customer'


        ),
        'store.credits'   => array(
            'view'        => 'overview',
            'sort_key'    => 'customer',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'customer'


        ),

        'invoice.payments'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'


        ),
        'refund.payments'                   => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'


        ),
        'payment_service_provider.history'  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'payment_service_provider.accounts' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'payment_service_provider.payments' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'
        ),

        'payment_account.history'  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'payment_account.payments' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('payments'))[0],
            'elements'      => get_elements_option('payments'),
        ),
        'store.payments'           => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'elements_type' => array_keys(get_elements_option('payments'))[0],
            'elements'      => get_elements_option('payments'),
        ),
        'payment_account.stores'   => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'payment_account.websites' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),


        'payment.history'          => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'account.payment_accounts' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'store.payment_accounts'   => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'users.staff'              => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'handle',
            'elements_type' => array_keys(get_elements_option('users'))[0],
            'elements'      => get_elements_option('users'),
        ),


        'staff.user.history'          => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'staff.user.login_history'    => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'ip',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'staff.user.api_keys'         => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',

        ),
        'staff.user.deleted_api_keys' => array(
            'view'        => 'overview',
            'sort_key'    => 'formatted_id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',

        ),
        'staff.user.api_key.requests' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),


        'api_key.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),


        'deleted_api_key.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),

        'staff.user.deleted_api_key.requests' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),

        'deleted.staff.user.history'       => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'deleted.staff.user.login_history' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'ip',
            'from'        => '',
            'to'          => '',
            'period'      => 'all',

        ),
        'users.staff.login_history'        => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'handle',
            'f_period'    => 'all',

        ),

        'users.suppliers'   => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'handle',
            'elements_type' => array_keys(get_elements_option('users'))[0],
            'elements'      => get_elements_option('users'),
        ),
        'users.contractors' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'handle',
            'elements_type' => array_keys(get_elements_option('users'))[0],
            'elements'      => get_elements_option('users'),
        ),
        'users.agents'      => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'handle',
            'elements_type' => array_keys(get_elements_option('users'))[0],
            'elements'      => get_elements_option('users'),
        ),
        'deleted.users'     => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'handle'
        ),

        'ec_sales_list' => array(
            'view'          => 'overview',
            'sort_key'      => 'country_code',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'tax_number',
            'from'          => '',
            'to'            => '',
            'period'        => 'last_m',
            'elements_type' => array_keys(get_elements_option('ec_sales_list'))[0],
            'elements'      => get_elements_option('ec_sales_list'),
            'export_fields' => get_export_fields('ec_sales_list')

        ),

        'intrastat' => array(
            'view'        => 'overview',
            'sort_key'    => 'country_code',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'commodity',
            'from'        => '',
            'to'          => '',
            'period'      => 'last_m',


            'invoices_vat'    => 1,
            'invoices_no_vat' => 1,
            'invoices_null'   => 1,

            'export_fields' => get_export_fields('intrastat')


        ),


        'intrastat_imports' => array(
            'view'        => 'overview',
            'sort_key'    => 'country_code',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'commodity',
            'from'        => '',
            'to'          => '',
            'period'      => 'last_m',


            'export_fields' => get_export_fields('intrastat')


        ),

        'sales' => array(
            'view'        => 'overview',
            'sort_key'    => 'store',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'store',
            'from'        => '',
            'to'          => '',
            'period'      => 'mtd',
            'currency'    => 'account'

        ),

        'sales_invoice_category' => array(
            'view'        => 'overview',
            'sort_key'    => 'category',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'category',
            'from'        => '',
            'to'          => '',
            'period'      => 'mtd',
            'currency'    => 'account'

        ),


        'report_orders'                      => array(
            'view'        => 'overview',
            'sort_key'    => 'store',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'store',
            'from'        => '',
            'to'          => '',
            'period'      => 'mtd'

        ),
        'report_orders_components'           => array(
            'view'        => 'overview',
            'sort_key'    => 'store',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'store',
            'from'        => '',
            'to'          => '',
            'period'      => 'mtd'

        ),
        'report_delivery_notes'              => array(
            'view'        => 'overview',
            'sort_key'    => 'store',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'store',
            'from'        => '',
            'to'          => '',
            'period'      => 'mtd'

        ),
        'warehouse_bonus_report'             => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => -1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'from'        => '',
            'to'          => '',
            'period'      => 'last_w'

        ),
        'staff_warehouse_kpi.delivery_notes' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('delivery_notes'),
            'period'        => 'last_w',
            'from'          => '',
            'to'            => '',

        ),
        'pickers'                            => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => -1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'from'        => '',
            'to'          => '',
            'period'      => 'last_w'

        ),
        'picker.delivery_notes'              => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('delivery_notes'),
            'period'        => 'last_w',
            'from'          => '',
            'to'            => '',

        ),
        'picker.feedback'                    => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'period'      => 'last_w',
            'from'        => '',
            'to'          => '',
        ),
        'packers'                            => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => -1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'period'      => 'last_w',
            'from'        => '',
            'to'          => '',

        ),
        'packer.delivery_notes'              => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('delivery_notes'),
            'period'        => 'last_w',
            'from'          => '',
            'to'            => '',


        ),
        'packer.feedback'                    => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            'period'      => 'last_w',
            'from'        => '',
            'to'          => '',

        ),
        'sales_representatives'              => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => -1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'from'        => '',
            'to'          => '',
            'period'      => 'last_w'

        ),
        'prospect_agents'                    => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => -1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            'from'        => '',
            'to'          => '',
            'period'      => 'last_w'

        ),
        'lost_stock'                         => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => -1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'from'          => '',
            'to'            => '',
            'period'        => 'mtd',
            'elements_type' => array_keys(get_elements_option('lost_stock'))[0],
            'elements'      => get_elements_option('lost_stock'),

        ),
        'stock_given_free'                   => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => -1,
            'rpp'           => 500,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'from'          => '',
            'to'            => '',
            'period'        => 'mtd',
            'elements_type' => array_keys(get_elements_option('stock_given_free'))[0],
            'elements'      => get_elements_option('stock_given_free'),

        ),

        'intrastat_orders' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',

        ),

        'intrastat_products' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',


        ),


        'intrastat_deliveries' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',

        ),

        'intrastat_parts' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',


        ),

        'billingregion_taxcategory' => array(
            'view'            => 'overview',
            'sort_key'        => 'billing_region',
            'sort_order'      => 1,
            'rpp'             => 100,
            'rpp_options'     => $default_rrp_options,
            'f_field'         => '',
            'from'            => '',
            'to'              => '',
            'period'          => 'last_m',
            'excluded_stores' => array()
        ),

        'billingregion_taxcategory.invoices' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('invoices')


        ),
        'billingregion_taxcategory.refunds'  => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('invoices')

        ),
        'category.history'                   => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => ''
        ),
        'category.categories'                => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'category.images'                    => array(
            'view'        => 'overview',
            'sort_key'    => 'image_order',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),

        'subject_categories' => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),

        'deals'            => array(
            'view'          => 'overview',
            'sort_key'      => 'name',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('deals'))[0],
            'elements'      => get_elements_option('deals'),
        ),
        'campaigns'        => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',
            // 'elements_type' => array_keys(get_elements_option('campaigns'))[0],
            // 'elements'      => get_elements_option('campaigns'),
        ),
        'campaign.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'elements_type' => array_keys(get_elements_option('campaign_history'))[0],
            'elements'      => get_elements_option('campaign_history')
        ),
        'campaign.deals'   => array(
            'view'          => 'overview',
            'sort_key'      => 'name',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('deals'))[0],
            'elements'      => get_elements_option('deals'),
        ),

        'vouchers' => array(
            'view'          => 'overview',
            'sort_key'      => 'name',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('deals'))[0],
            'elements'      => get_elements_option('deals'),
        ),

        'campaign_bulk_deals'                 => array(
            'view'          => 'overview',
            'sort_key'      => 'from',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'target',
            'elements_type' => array_keys(get_elements_option('fixed_deals'))[0],
            'elements'      => get_elements_option('fixed_deals'),

        ),
        'campaign.orders'                     => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders')
        ),
        'campaign.customers'                  => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers')

        ),
        'deal.history'                        => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'elements_type' => array_keys(get_elements_option('deal_history'))[0],
            'elements'      => get_elements_option('deal_history')
        ),
        'deal.components'                     => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

        ),
        'campaign_order_recursion.components' => array(
            'view'        => 'overview',
            'sort_key'    => 'from',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'target',
            'elements_type' => array_keys(get_elements_option('deal_components'))[0],
            'elements'      => get_elements_option('deal_components'),

        ),
        'deal.orders'                         => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders')
        ),
        'deal.customers'                      => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers')

        ),

        'category.deal_components' => array(
            'view'          => 'overview',
            'sort_key'      => 'name',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('deal_components'))[0],
            'elements'      => get_elements_option('deal_components'),
        ),

        'category.deals' => array(
            'view'          => 'overview',
            'sort_key'      => 'name',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('deal_components'))[0],
            'elements'      => get_elements_option('deal_components'),
        ),

        'customer.deals' => array(
            'view'          => 'overview',
            'sort_key'      => 'name',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('deals'))[0],
            'elements'      => get_elements_option('deals'),
        ),


        'material.parts'              => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'f_period'      => 'ytd',
            'elements_type' => array_keys(get_elements_option('parts'))[0],
            'elements'      => get_elements_option('parts'),
        ),
        'inventory.stock.history.day' => array(
            'view'          => 'overview',
            'sort_key'      => 'part_reference',
            'sort_order'    => 1,
            'rpp'           => 50,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'part_reference',
            'export_fields' => get_export_fields('inventory_stock_history_day')


        ),


        'warehouse.parts_to_replenish_picking_location.wget' => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',
            //    'export_fields' => get_export_fields('warehouse_parts_to_replenish_picking_location')

        ),
        'supplier.parts_to_replenish_picking_location.wget'  => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 500,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),

        'warehouse.part_locations_to_replenish.wget' => array(
            'view'        => 'overview',
            'sort_key'    => 'location',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference'
        ),

        'supplier.part_locations_to_replenish.wget' => array(
            'view'        => 'overview',
            'sort_key'    => 'location',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'location'
        ),

        'supplier.part_locations_with_errors.wget' => array(
            'view'        => 'overview',
            'sort_key'    => 'reference',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'supplier.surplus_parts.wget'              => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'production.todo_parts.wget'               => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'production.external_products.wget'               => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'production_urgent_to_do.wget'             => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 0,
            'rpp'         => 1000,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'reference',

        ),
        'webpage.logbook'                          => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('webpage_publishing_history'))[0],
            'elements'      => get_elements_option('webpage_publishing_history')
        ),
        'category.webpage.logbook'                 => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('webpage_publishing_history'))[0],
            'elements'      => get_elements_option('webpage_publishing_history')
        ),
        'product.webpages'                         => array(
            'view'        => 'overview',
            'sort_key'    => 'type',
            'sort_order'  => -1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',
            'f_period'    => 'ytd',

        ),
        'product.webpage.logbook'                  => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('webpage_publishing_history'))[0],
            'elements'      => get_elements_option('webpage_publishing_history')
        ),

        'deleted.webpage.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),


        'supplier.timeseries_record.parts'    => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'reference',
            'export_fields' => get_export_fields('supplier_timeseries_drill_down_parts')

        ),
        'supplier.timeseries_record.families' => array(
            'view'          => 'overview',
            'sort_key'      => 'code',
            'sort_order'    => 1,
            'rpp'           => 1000,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'code',
            'export_fields' => get_export_fields('supplier_timeseries_drill_down_families')

        ),
        'email_campaign.mail_list'            => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'email',
            'export_fields' => get_export_fields('mail_list')

        ),
        'newsletter.mail_list'                => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => -1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'email',
            'export_fields' => get_export_fields('mail_list')


        ),
        'abandoned_cart.mail_list'            => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'email',
            'export_fields' => get_export_fields('abandoned_cart.mail_list')


        ),

        'email_campaign.sent_emails'      => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'email',
            'elements_type' => array_keys(get_elements_option('sent_emails'))[0],
            'elements'      => get_elements_option('sent_emails'),
            'export_fields' => get_export_fields('customer_sent_emails')


        ),
        'email_campaign_type.sent_emails' => array(
            'view'        => 'overview',
            'sort_key'    => 'email',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'email',

        ),
        'user_notification.sent_emails'   => array(
            'view'        => 'overview',
            'sort_key'    => 'email',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'email',

        ),
        'mailshot.sent_emails'            => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'email',
            'elements_type' => array_keys(get_elements_option('sent_emails'))[0],
            'elements'      => get_elements_option('sent_emails'),


        ),
        'customer.sent_emails'            => array(
            'view'        => 'overview',
            'sort_key'    => 'email',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'email',


        ),
        'customer_clients'                => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',


        ),

        'customer_client.orders'         => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'number',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('orders'))[0],
            'elements'      => get_elements_option('orders')
        ),
        'customer_client.delivery_notes' => array(
            'view'          => 'overview',
            'sort_key'      => 'id',
            'sort_order'    => 1,
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'export_fields' => get_export_fields('delivery_notes')

        ),
        'prospect.sent_emails'           => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'subject',


        ),


        'prospects.email_templates' => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',


        ),

        'prospects.base_templates' => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',


        ),


        'mailshot.email_blueprints'            => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'email_campaign_type.email_blueprints' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name'
        ),
        'mailshot.history'                     => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'elements_type' => array_keys(get_elements_option('deal_history'))[0],
            'elements'      => get_elements_option('deal_history')
        ),

        'stock_leakages'                  => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',
            'frequency'   => 'monthly',

        ),
        'warehouse.leakages.transactions' => array(
            'view'          => 'overview',
            'sort_key'      => 'reference',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'elements_type' => array_keys(get_elements_option('leakages_transactions'))[0],
            'elements'      => get_elements_option('leakages_transactions')
        ),
        'list.history'                    => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => '',
            'elements_type' => array_keys(get_elements_option('list_history'))[0],
            'elements'      => get_elements_option('list_history')
        ),
        'email_tracking.events'           => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => '',


        ),
        'shippers'                        => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code',


        ),
        'sales_representative.customers'  => array(
            'view'          => 'overview',
            'sort_key'      => 'formatted_id',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'elements_type' => array_keys(get_elements_option('customers'))[0],
            'elements'      => get_elements_option('customers'),
            'export_fields' => get_export_fields('customers')

        ),
        'prospect_agent.prospects'        => array(
            'view'          => 'overview',
            'sort_key'      => 'email',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'name',
            'from'          => '',
            'to'            => '',
            'period'        => 'all',
            'elements_type' => array_keys(get_elements_option('prospect_agent_prospects'))[0],
            'elements'      => get_elements_option('prospect_agent_prospects'),
            'export_fields' => get_export_fields('prospects')

        ),
        'prospect_agent.sent_emails'      => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'from'        => '',
            'to'          => '',
            'period'      => 'all',
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'subject',


        ),
        'order.sent_emails'               => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            //'f_field'     => 'subject',


        ),
        'order.deals'                     => array(
            'view'        => 'overview',
            'sort_key'    => 'name',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'name',

        ),
        'purge.purged_orders'             => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'number',

            'elements_type' => array_keys(get_elements_option('purged_orders'))[0],
            'elements'      => get_elements_option('purged_orders')
        ),

        'purge.history'               => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('purges_history'))[0],
            'elements'      => get_elements_option('purges_history')
        ),
        'clocking_machines'           => array(
            'view'        => 'overview',
            'sort_key'    => 'code',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'code'
        ),
        'part.attachments'            => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'customer.attachments'        => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'customer.attachment.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'order.attachments'           => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),
        'order.attachment.history'    => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),
        'supplier.attachments'        => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),

        'supplier.attachment.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),


        'supplier.delivery.attachments' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'caption'
        ),

        'supplier_delivery.attachment.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'date',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'f_field'     => 'note'
        ),


        'supplier.sales.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),

        'agent.sales.history' => array(
            'view'        => 'overview',
            'sort_key'    => 'id',
            'sort_order'  => 1,
            'rpp'         => 100,
            'rpp_options' => $default_rrp_options,
            'frequency'   => 'monthly',
            'f_field'     => '',
            //  'export_fields'=>get_export_fields('timeserie_records')

        ),

        'supplier_part.history' => array(
            'view'          => 'overview',
            'sort_key'      => 'date',
            'sort_order'    => 1,
            'rpp'           => 100,
            'rpp_options'   => $default_rrp_options,
            'f_field'       => 'note',
            'elements_type' => array_keys(get_elements_option('supplier_part_history'))[0],
            'elements'      => get_elements_option('supplier_part_history')
        ),

    );


}

