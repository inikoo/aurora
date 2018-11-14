<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 12:09:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include 'conf/export_fields.php';
include 'conf/elements_options.php';

$default_rrp_options = array(
    500,
    100,
    50,
    20
);


$tab_defaults = array(


    'customers' => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['customers']) ['key'],
        'elements'      => $elements_options['customers'],
        'export_fields' => $export_fields['customers']

    ),


    'customers.list' => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'export_fields' => $export_fields['customers']

    ),

    'customers.lists'        => array(
        'view'        => 'overview',
        'sort_key'    => 'creation_date',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'name'
    ),
    'customers.categories'   => array(
        'view'        => 'overview',
        'sort_key'    => 'code',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code'
    ),
    'customers.geo'          => array(
        'view'        => 'overview',
        'sort_key'    => 'customers',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'country'
    ),
    'customers_poll.queries' => array(
        'view'        => 'overview',
        'sort_key'    => 'position',
        'sort_order'  => -1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'query'
    ),

    'poll_query.history'        => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['poll_query_history'])['key'],
        'elements'      => $elements_options['poll_query_history']
    ),
    'poll_query_option.history' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['poll_query_option_history'])['key'],
        'elements'      => $elements_options['poll_query_option_history']
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
        'elements_type' => each($elements_options['customers']) ['key'],
        'elements'      => $elements_options['customers'],
        'export_fields' => $export_fields['customers']

    ),

    'prospects' => array(
        'view'          => 'overview',
        'sort_key'      => 'email',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['prospects']) ['key'],
        'elements'      => $elements_options['prospects'],
        'export_fields' => $export_fields['prospects']

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
        'elements_type' => each($elements_options['mailshots']) ['key'],
        'elements'      => $elements_options['mailshots'],
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

    'email_campaigns.newsletters'      => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'id',
    ),
    'email_campaigns.mailshots'        => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'id',
    ),
    'email_campaigns.reminders'        => array(
        'view'        => 'overview',
        'sort_key'    => 'name',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,

    ),
    'email_campaigns.abandoned_basket' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'id',
    ),
    'email_campaigns.back_in_stock'    => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'id',
    ),

    'email_template_types' => array(
        'view'        => 'overview',
        'sort_key'    => 'type',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'type',


    ),


    'customer.history'              => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['customer_history'])['key'],
        'elements'      => $elements_options['customer_history']
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
        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders']
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
        'elements_type' => each($elements_options['invoices'])['key'],
        'elements'      => $elements_options['invoices'],
        'export_fields' => $export_fields['invoices']

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
        //  'export_fields'=>$export_fields['timeserie_records']

    ),
    'customer.product.orders'       => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',

        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders'],
        'export_fields' => $export_fields['orders']
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
        'elements_type' => each($elements_options['invoices'])['key'],
        'elements'      => $elements_options['invoices'],
        'export_fields' => $export_fields['invoices']
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

    'prospect.history' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['prospect_history'])['key'],
        'elements'      => $elements_options['prospect_history']
    ),
    'orders.website'   => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',


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
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',


    ),
    'orders.in_process.paid'     => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',


    ),
    'orders.in_process'          => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',

        'elements_type' => 'state',
        'elements'      => $elements_options['orders_pending'],
        //  'export_fields' => $export_fields['orders_pending']

    ),

    'orders.in_warehouse'             => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',
    ),
    'orders.in_warehouse_no_alerts'   => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',
    ),
    'orders.in_warehouse_with_alerts' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',
    ),

    'orders.packed_done' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',
    ),


    'orders.approved' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',
    ),


    'orders.dispatched_today' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',
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
        'elements'      => $elements_options['orders'],
        'export_fields' => $export_fields['orders']

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
        'elements'      => $elements_options['orders']

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
        'elements_type' => each($elements_options['customer_history'])['key'],
        'elements'      => $elements_options['customer_history']
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
        'export_fields' => $export_fields['invoices']

    ),
    'order.delivery_notes' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'export_fields' => $export_fields['delivery_notes']

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
    'replacement.new.items' => array(
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

    'delivery_note.invoices'           => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'export_fields' => $export_fields['invoices']


    ),
    'delivery_note.orders'             => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'export_fields' => $export_fields['orders']


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
        'f_field'     => 'code',

    ),
    'delivery_note_cancelled.items'    => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',

    ),
    'delivery_note.fast_track_packing' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',

    ),

    'invoice.items'                 => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',

    ),
    'invoice.orders'                => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'export_fields' => $export_fields['orders'],


    ),
    'invoice.delivery_notes'        => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'export_fields' => $export_fields['delivery_notes']


    ),
    'invoice.history'               => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'note'
    ),
    'invoices'                      => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'customer',
        'from'          => '',
        'to'            => '',
        'period'        => 'all',
        'elements_type' => each($elements_options['invoices'])['key'],
        'elements'      => $elements_options['invoices'],
        'export_fields' => $export_fields['invoices']

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
        'elements_type' => each($elements_options['invoices'])['key'],
        'elements'      => $elements_options['invoices'],
        'export_fields' => $export_fields['invoices']

    ),

    'sales_representative.invoices_group_by_customer' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'customer',
        'from'          => '',
        'to'            => '',
        'period'        => 'all',
     //   'export_fields' => $export_fields['invoices_group_by_customer']

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
        'elements_type' => each($elements_options['invoices'])['key'],
        'elements'      => $elements_options['invoices'],
        'export_fields' => $export_fields['invoices']

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
        //  'elements_type'=>each($elements_options['suppliers'])['key'],
        //  'elements'=>$elements_options['suppliers']

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
        'elements_type' => each($elements_options['invoices'])['key'],
        'elements'      => $elements_options['invoices']
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
        'elements_type' => each($elements_options['delivery_notes'])['key'],
        'elements'      => $elements_options['delivery_notes'],
        'export_fields' => $export_fields['delivery_notes']

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
        'elements_type' => each($elements_options['delivery_notes'])['key'],
        'elements'      => $elements_options['delivery_notes']
    ),
    'pending_delivery_notes'     => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'customer',


    ),
    'orders_index'               => array(
        'view'        => 'overview',
        'sort_key'    => 'code',
        'sort_order'  => -1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'percentages' => 0
    ),

    'orders_group_by_store'         => array(
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


    'stores'               => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'ytd',

    ),
    'stores.sales.history' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'frequency'   => 'monthly',
        'f_field'     => '',

    ),
    'store.charges'        => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'name'
    ),
    'store.shipping_zones' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'name'
    ),


    'store.products'                              => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['products']) ['key'],
        'elements'      => $elements_options['products'],
        'export_fields' => $export_fields['products']

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
        'elements_type' => each($elements_options['services']) ['key'],
        'elements'      => $elements_options['services'],

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
        'elements_type' => each($elements_options['products']) ['key'],
        'elements'      => $elements_options['products'],

    ),
    'category.all_products' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['products']) ['key'],
        'elements'      => $elements_options['products'],

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
        //  'export_fields'=>$export_fields['timeserie_records']

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
        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders'],
        'export_fields' => $export_fields['orders']
    ),

    'product.customers'                                    => array(
        'view'        => 'overview',
        'sort_key'    => 'formatted_id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'name',

        'export_fields' => $export_fields['customers']

    ),
    'product.back_to_stock_notification_request.customers' => array(
        'view'        => 'overview',
        'sort_key'    => 'formatted_id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'name',

        'export_fields' => $export_fields['customers']

    ),

    'product.customers.favored' => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['customers']) ['key'],
        'elements'      => $elements_options['customers'],
        'export_fields' => $export_fields['customers']

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
        //  'export_fields'=>$export_fields['timeserie_records']

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
        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders'],
        'export_fields' => $export_fields['orders']
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
        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders'],
        'export_fields' => $export_fields['orders']
    ),
    'charge.customers' => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['customers'])['key'],
        'elements'      => $elements_options['customers']
    ),

    'charge.history' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'elements_type' => each($elements_options['deal_history'])['key'],
        'elements'      => $elements_options['deal_history']
    ),


    'category_customers' => array(
        'view'        => 'overview',
        'sort_key'    => 'invoiced_amount',
        'sort_order'  => -1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'name',

        'export_fields' => $export_fields['customers']

    ),

    'category_customers_favored' => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['customers']) ['key'],
        'elements'      => $elements_options['customers'],
        'export_fields' => $export_fields['customers']

    ),


    'category.product.sales.history'       => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'frequency'   => 'monthly',
        'f_field'     => '',
        //  'export_fields'=>$export_fields['timeserie_records']

    ),
    /*
    'category.product_families'      => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'elements_type' => each($elements_options['product_categories'])['key'],
        'elements'      => $elements_options['product_categories'],
        'f_field'       => 'code'
    ),
    */
    'category.product_categories.products' => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 0,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'elements_type' => each($elements_options['product_categories'])['key'],
        'elements'      => $elements_options['product_categories'],
        'f_period'      => 'ytd',
        'f_field'       => 'code'
    ),


    'category.product_categories.categories' => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 0,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'elements_type' => each($elements_options['product_categories'])['key'],
        'elements'      => $elements_options['product_categories'],
        'f_period'      => 'ytd',
        'f_field'       => 'code'
    ),


    'websites'         => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code'
    ),
    'website.webpages' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['webpages'])['key'],
        'elements'      => $elements_options['webpages'],
    ),

    'website.in_process_webpages' => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 0,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['online_webpages'])['key'],
        'elements'      => $elements_options['online_webpages'],
    ),

    'website.ready_webpages' => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 0,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['online_webpages'])['key'],
        'elements'      => $elements_options['online_webpages'],
    ),

    'website.online_webpages'       => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['online_webpages'])['key'],
        'elements'      => $elements_options['online_webpages'],
    ),
    'website.offline_webpages'      => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'elements_type' => each($elements_options['online_webpages'])['key'],
        'elements'      => $elements_options['online_webpages'],

    ),
    'webpage_type.online_webpages'  => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['online_webpages_in_webpage_type'])['key'],
        'elements'      => $elements_options['online_webpages'],
    ),
    'webpage_type.offline_webpages' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code'

    ),
    'website.webpage.types'         => array(
        'view'        => 'overview',
        'sort_key'    => 'type',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => ''
    ),

    'website.root_nodes' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'ytd',

    ),
    'node.nodes'         => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'ytd',

    ),
    'website.node.pages' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'ytd',

    ),
    'website.node.nodes' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'ytd',

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
        'elements_type' => each($elements_options['customers'])['key'],
        'elements'      => $elements_options['customers']
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
    'website.user.pageviews'       => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'page',
        'f_period'    => 'all',

    ),

    'website.templates'      => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'all',

    ),
    'marketing_server'       => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        'f_period'    => 'ytd',
    ),
    'suppliers'              => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['suppliers'])['key'],
        'elements'      => $elements_options['suppliers']

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
        'elements_type' => each($elements_options['supplier_orders'])['key'],
        'elements'      => $elements_options['supplier_orders']
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
        'elements_type' => each(
                               $elements_options['supplier_deliveries']
                           )['key'],
        'elements'      => $elements_options['supplier_deliveries']
    ),
    'supplier.history'        => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['supplier_history'])['key'],
        'elements'      => $elements_options['supplier_history']
    ),
    'supplier.supplier_parts' => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['supplier_parts'],
        'export_fields' => $export_fields['supplier_parts']

    ),

    'agent_parts' => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['agent_parts'],
        'export_fields' => $export_fields['agent_parts']

    ),


    'supplier.order.all_supplier_parts' => array(
        'view'        => 'cartons',
        'sort_key'    => 'reference',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',

    ),
    'supplier.orders'                   => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'number',
        'from'          => '',
        'to'            => '',
        'period'        => 'all',
        'elements_type' => each($elements_options['supplier_orders'])['key'],
        'elements'      => $elements_options['supplier_orders']
    ),
    'supplier.deliveries'               => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'number',
        'from'          => '',
        'to'            => '',
        'period'        => 'all',
        'elements_type' => each(
                               $elements_options['supplier_deliveries']
                           )['key'],
        'elements'      => $elements_options['supplier_deliveries']
    ),
    'supplier.order.history'            => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each(
                               $elements_options['supplier_order_history']
                           )['key'],
        'elements'      => $elements_options['supplier_order_history']
    ),
    'supplier.order.items'              => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 1000,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'export_fields' => $export_fields['supplier.order.items']


    ),
    'supplier.order.items_in_process'   => array(
        'view'        => 'cartons',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        //'export_fields' => $export_fields['supplier.order.items']


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
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['supplier_parts']

    ),
    'category.supplier_categories'      => array(
        'view'        => 'overview',
        'sort_key'    => 'code',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'label',
        'f_period'    => 'ytd',
        //  'elements_type'=>each($elements_options['suppliers'])['key'],
        //  'elements'=>$elements_options['suppliers']

    ),
    'client_order.items'                => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 1000,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'export_fields' => $export_fields['client_order_items']


    ),
    'agent_supplier_order.items'        => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 1000,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'export_fields' => $export_fields['client_order_items']


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


    'supplier.delivery.check_items' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',

    ),

    'supplier.delivery.supplier_parts' => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['supplier_parts']

    ),
    'supplier.attachments'             => array(
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
        //  'export_fields'=>$export_fields['timeserie_records']

    ),

    'supplier_part.history' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each(
                               $elements_options['supplier_part_history']
                           )['key'],
        'elements'      => $elements_options['supplier_part_history']
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
        'elements_type' => each($elements_options['supplier_orders'])['key'],
        'elements'      => $elements_options['supplier_orders']
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
        'elements_type' => each(
                               $elements_options['supplier_deliveries']
                           )['key'],
        'elements'      => $elements_options['supplier_deliveries']
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
        'elements_type' => each($elements_options['agent_history'])['key'],
        'elements'      => $elements_options['agent_history']
    ),
    'agent.suppliers'         => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['agent_suppliers'])['key'],
        'elements'      => $elements_options['agent_suppliers']

    ),
    'agent.supplier_parts'    => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['supplier_parts'],
        'export_fields' => $export_fields['supplier_parts']


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
        'elements_type' => each($elements_options['agent_orders'])['key'],
        'elements'      => $elements_options['agent_orders']
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
        'elements_type' => each($elements_options['agent_client_orders'])['key'],
        'elements'      => $elements_options['agent_client_orders']
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
        'elements_type' => each(
                               $elements_options['supplier_deliveries']
                           )['key'],
        'elements'      => $elements_options['supplier_deliveries']
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
        'elements_type' => each(
                               $elements_options['agent_client_deliveries']
                           )['key'],
        'elements'      => $elements_options['agent_client_deliveries']
    ),


    'agent.users'                  => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'handle'
    ),
    'warehouses'                   => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
    ),
    'warehouse.areas'                   => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
    ),
    'category.location_categories' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',
        //  'elements_type'=>each(  $elements_options['parts']  ) ['key'],
        //  'elements'=>$elements_options['parts'],
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
        'elements_type' => each($elements_options['products']) ['key'],
        'elements'      => $elements_options['products'],

    ),

    'part.locations' => array(
        'view'        => 'overview',
        'sort_key'    => 'code',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code',

    ),

    'part.attachments' => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'caption'
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
        'elements_type' => each($elements_options['supplier_orders'])['key'],
        'elements'      => $elements_options['supplier_orders']
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
        'elements_type' => each(
                               $elements_options['supplier_deliveries']
                           )['key'],
        'elements'      => $elements_options['supplier_deliveries']
    ),
    'part.sales.history'          => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'frequency'   => 'monthly',
        'f_field'     => '',
        //  'export_fields'=>$export_fields['timeserie_records']

    ),
    'category.part.sales.history' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'frequency'   => 'monthly',
        'f_field'     => '',
        //  'export_fields'=>$export_fields['timeserie_records']

    ),
    'warehouse.locations'         => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'elements_type' => each($elements_options['locations']) ['key'],
        'elements'      => $elements_options['locations'],
        'export_fields' => $export_fields['locations']
    ),

    'warehouse.replenishments'                  => array(
        'view'        => 'overview',
        'sort_key'    => 'location',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'location'
    ),
    'warehouse.parts'                           => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'export_fields' => $export_fields['part_locations']

    ),
    'warehouse.part_locations_with_errors.wget' => array(
        'view'        => 'overview',
        'sort_key'    => 'reference',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',

    ),
    'warehouse_area.locations'         => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'elements_type' => each($elements_options['locations']) ['key'],
        'elements'      => $elements_options['locations'],
        'export_fields' => $export_fields['locations']
    ),
    'warehouse_area.history'           => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'note'
    ),
    'parts_with_unknown_location.wget' => array(
        'view'        => 'overview',
        'sort_key'    => 'reference',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',

    ),

    'warehouse.history'           => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'note'
    ),
    'location.history'            => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['location_history'])['key'],
        'elements'      => $elements_options['location_history']
    ),
    'location.parts'              => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',

    ),
    'location.stock.transactions' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each(
                               $elements_options['part_stock_transactions']
                           ) ['key'],
        'elements'      => $elements_options['part_stock_transactions'],
    ),

    'inventory.parts' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['parts']) ['key'],
        'elements'      => $elements_options['parts'],
        'export_fields' => $export_fields['parts']

    ),

    'inventory.parts_no_sko_barcode.wget' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',

    ),

    'inventory.parts_barcode_errors.wget' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 1000,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'barcode',
        'elements_type' => each($elements_options['part_barcode_errors']) ['key'],
        'elements'      => $elements_options['part_barcode_errors'],
        'export_fields' => $export_fields['part_barcode_errors']


    ),


    'inventory.discontinued_parts'  => array(
        'view'          => 'overview',
        'sort_key'      => 'valid_to',
        'sort_order'    => 1,
        'rpp'           => 100,
        'f_period'      => 'all',
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'export_fields' => $export_fields['parts']

    ),
    'inventory.discontinuing_parts' => array(
        'view'          => 'overview',
        'sort_key'      => 'stock_value',
        'sort_order'    => 1,
        'rpp'           => 100,
        'f_period'      => 'all',
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'export_fields' => $export_fields['parts']

    ),
    'inventory.in_process_parts'    => array(
        'view'          => 'overview',
        'sort_key'      => 'valid_from',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'export_fields' => $export_fields['parts']

    ),
    'category.part_categories'      => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['part_categories']) ['key'],
        'elements'      => $elements_options['part_categories'],
        'export_fields' => $export_fields['part_categories']

    ),

    'parts.categories'                    => array(
        'view'        => 'overview',
        'sort_key'    => 'code',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code'
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
        'elements_type' => each(
                               $elements_options['part_stock_transactions']
                           ) ['key'],
        'elements'      => $elements_options['part_stock_transactions'],
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
        'elements_type' => each($elements_options['part_stock_transactions']) ['key'],
        'elements'      => $elements_options['part_stock_transactions'],

    ),

    'inventory.stock.history' => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 1,
        'rpp'         => 5000,
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
        'elements_type' => each($elements_options['parts']) ['key'],
        'elements'      => $elements_options['parts'],
    ),
    'category.all_parts'      => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['parts']) ['key'],
        'elements'      => $elements_options['parts'],

    ),
    'category_root.all_parts' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'f_period'      => 'ytd',
        'elements_type' => each(
                               $elements_options['category_root_subjects']
                           ) ['key'],
        'elements'      => $elements_options['category_root_subjects'],

    ),

    'inventory.barcodes' => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'number',
        'elements_type' => each($elements_options['barcodes']) ['key'],
        'elements'      => $elements_options['barcodes'],
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
        'elements_type' => each($elements_options['products']) ['key'],
        'elements'      => $elements_options['products'],

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
    'production.supplier_parts'    => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['supplier_parts'],
        'export_fields' => $export_fields['supplier_parts']

    ),
    'production.materials'         => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'elements_type' => each($elements_options['supplier_parts'])['key'],
        'elements'      => $elements_options['supplier_parts'],
        'export_fields' => $export_fields['supplier_parts']

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
    'overtimes'           => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference'
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
        'export_fields' => $export_fields['timeserie_records']

    ),
    'data_sets.images'           => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => '',
    ),

    'data_sets.attachments' => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => '',
    ),
    'data_sets.uploads'     => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => '',
    ),
    'data_sets.materials'   => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => '',
    ),
    'upload.records'        => array(
        'view'        => 'overview',
        'sort_key'    => 'row',
        'sort_order'  => 0,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => '',
    ),

    'account.users'             => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => '',
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
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => -1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference'


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
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => -1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference'
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


    'payment.history'     => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'note'
    ),
    'payment_accounts'    => array(
        'view'        => 'overview',
        'sort_key'    => 'code',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'code'
    ),
    'account.users.staff' => array(
        'view'        => 'privileges',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'handle'
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

    'account.users.suppliers'   => array(
        'view'        => 'privileges',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'handle'
    ),
    'account.users.contractors' => array(
        'view'        => 'privileges',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'handle'
    ),
    'account.users.agents'      => array(
        'view'        => 'privileges',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'handle'
    ),
    'account.deleted.users'     => array(
        'view'        => 'privileges',
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
        'elements_type' => each($elements_options['ec_sales_list']) ['key'],
        'elements'      => $elements_options['ec_sales_list'],
        'export_fields' => $export_fields['ec_sales_list']

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

        'export_fields' => $export_fields['intrastat']


    ),

    'sales'                    => array(
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

     'sales_invoice_category'                    => array(
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



    'report_orders'            => array(
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
    'report_orders_components' => array(
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
    'report_delivery_notes'    => array(
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
    'pickers'                  => array(
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
    'packers'                  => array(
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
    'sales_representatives'    => array(
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
    'prospect_agents'          => array(
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
    'lost_stock'               => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => -1,
        'rpp'           => 500,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'from'          => '',
        'to'            => '',
        'period'        => 'mtd',
        'elements_type' => each($elements_options['lost_stock'])['key'],
        'elements'      => $elements_options['lost_stock'],

    ),
    'stock_given_free'         => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => -1,
        'rpp'           => 500,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'from'          => '',
        'to'            => '',
        'period'        => 'mtd',
        'elements_type' => each($elements_options['stock_given_free'])['key'],
        'elements'      => $elements_options['stock_given_free'],

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
        'export_fields' => $export_fields['invoices']


    ),
    'billingregion_taxcategory.refunds'  => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'export_fields' => $export_fields['invoices']

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
    'subject_categories'                 => array(
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
        'elements_type' => each($elements_options['deals'])['key'],
        'elements'      => $elements_options['deals'],
    ),
    'campaigns'        => array(
        'view'          => 'overview',
        'sort_key'      => 'name',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['campaigns'])['key'],
        'elements'      => $elements_options['campaigns'],
    ),
    'campaign.history' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'elements_type' => each($elements_options['campaign_history'])['key'],
        'elements'      => $elements_options['campaign_history']
    ),
    'campaign.deals'   => array(
        'view'          => 'overview',
        'sort_key'      => 'name',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['deals'])['key'],
        'elements'      => $elements_options['deals'],
    ),

    'campaign_bulk_deals'                 => array(
        'view'        => 'overview',
        'sort_key'    => 'from',
        'sort_order'  => -1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'target',

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
        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders']
    ),
    'campaign.customers'                  => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['customers'])['key'],
        'elements'      => $elements_options['customers']

    ),
    'deal.history'                        => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'elements_type' => each($elements_options['deal_history'])['key'],
        'elements'      => $elements_options['deal_history']
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
        'elements_type' => each($elements_options['orders'])['key'],
        'elements'      => $elements_options['orders']
    ),
    'deal.customers'                      => array(
        'view'          => 'overview',
        'sort_key'      => 'formatted_id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['customers'])['key'],
        'elements'      => $elements_options['customers']

    ),

    'category.deal_components' => array(
        'view'          => 'overview',
        'sort_key'      => 'name',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',
        'elements_type' => each($elements_options['deal_components'])['key'],
        'elements'      => $elements_options['deal_components'],
    ),


    'material.parts'              => array(
        'view'          => 'overview',
        'sort_key'      => 'id',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'f_period'      => 'ytd',
        'elements_type' => each($elements_options['parts']) ['key'],
        'elements'      => $elements_options['parts'],
    ),
    'inventory.stock.history.day' => array(
        'view'          => 'overview',
        'sort_key'      => 'reference',
        'sort_order'    => 1,
        'rpp'           => 1000,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'reference',
        'export_fields' => $export_fields['inventory_stock_history_day']


    ),


    'warehouse.parts_to_replenish_picking_location.wget' => array(
        'view'        => 'overview',
        'sort_key'    => 'reference',
        'sort_order'  => 1,
        'rpp'         => 500,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',
        //    'export_fields' => $export_fields['warehouse_parts_to_replenish_picking_location']

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
        'f_field'     => 'location'
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
        // 'f_period'=>'ytd',
        // 'elements_type'=>each(  $elements_options['parts']  ) ['key'],
        //  'elements'=>$elements_options['parts'],
    ),
    'supplier.todo_parts.wget'                 => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',
        // 'f_period'=>'ytd',
        // 'elements_type'=>each(  $elements_options['parts']  ) ['key'],
        //  'elements'=>$elements_options['parts'],
    ),
    'supplier.todo_paid_parts.wget'            => array(
        'view'        => 'overview',
        'sort_key'    => 'date',
        'sort_order'  => 0,
        'rpp'         => 1000,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'reference',
        // 'f_period'=>'ytd',
        // 'elements_type'=>each(  $elements_options['parts']  ) ['key'],
        //  'elements'=>$elements_options['parts'],
    ),

    'category.webpage.logbook' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['webpage_publishing_history'])['key'],
        'elements'      => $elements_options['webpage_publishing_history']
    ),
    'product.webpage.logbook'  => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['webpage_publishing_history'])['key'],
        'elements'      => $elements_options['webpage_publishing_history']
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
        'export_fields' => $export_fields['supplier_timeseries_drill_down_parts']

    ),
    'supplier.timeseries_record.families' => array(
        'view'          => 'overview',
        'sort_key'      => 'code',
        'sort_order'    => 1,
        'rpp'           => 1000,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'code',
        'export_fields' => $export_fields['supplier_timeseries_drill_down_families']

    ),
    'email_campaign.mail_list'            => array(
        'view'          => 'overview',
        'sort_key'      => 'email',
        'sort_order'    => -1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'email',
        'export_fields' => $export_fields['mail_list']

    ),
    'newsletter.mail_list'                => array(
        'view'          => 'overview',
        'sort_key'      => 'email',
        'sort_order'    => -1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'email',
        'export_fields' => $export_fields['mail_list']


    ),
    'abandoned_cart.mail_list'            => array(
        'view'          => 'overview',
        'sort_key'      => 'email',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'email',
        'export_fields' => $export_fields['abandoned_cart.mail_list']


    ),

    'email_campaign.sent_emails'      => array(
        'view'          => 'overview',
        'sort_key'      => 'email',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'email',
        'elements_type' => each($elements_options['sent_emails']) ['key'],
        'elements'      => $elements_options['sent_emails'],
        'export_fields' => $export_fields['customer_sent_emails']


    ),
    'email_campaign_type.sent_emails' => array(
        'view'        => 'overview',
        'sort_key'    => 'email',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'email',

    ),
    'mailshot.sent_emails'            => array(
        'view'        => 'overview',
        'sort_key'    => 'email',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'email',


    ),
    'customer.sent_emails'            => array(
        'view'        => 'overview',
        'sort_key'    => 'email',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'email',


    ),

    'prospect.sent_emails' => array(
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


    'email_campaign.email_blueprints'      => array(
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
    'email_campaign.history'               => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'elements_type' => each($elements_options['deal_history'])['key'],
        'elements'      => $elements_options['deal_history']
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
        'elements_type' => each($elements_options['leakages_transactions'])['key'],
        'elements'      => $elements_options['leakages_transactions']
    ),
    'list.history'                    => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => '',
        'elements_type' => each($elements_options['list_history'])['key'],
        'elements'      => $elements_options['list_history']
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
        'elements_type' => each($elements_options['customers']) ['key'],
        'elements'      => $elements_options['customers'],
        'export_fields' => $export_fields['customers']

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
        'elements_type' => each($elements_options['prospect_agent_prospects']) ['key'],
        'elements'      => $elements_options['prospect_agent_prospects'],
        'export_fields' => $export_fields['prospects']

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
    'order.deals'            => array(
        'view'          => 'overview',
        'sort_key'      => 'name',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'name',

    ),
    'purge.purged_orders'             => array(
        'view'        => 'overview',
        'sort_key'    => 'id',
        'sort_order'  => 1,
        'rpp'         => 100,
        'rpp_options' => $default_rrp_options,
        'f_field'     => 'number',

        'elements_type' => each($elements_options['purged_orders'])['key'],
        'elements'      => $elements_options['purged_orders']
    ),

    'purge.history' => array(
        'view'          => 'overview',
        'sort_key'      => 'date',
        'sort_order'    => 1,
        'rpp'           => 100,
        'rpp_options'   => $default_rrp_options,
        'f_field'       => 'note',
        'elements_type' => each($elements_options['purges_history'])['key'],
        'elements'      => $elements_options['purges_history']
    ),


);


?>
