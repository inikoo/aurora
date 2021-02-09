<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4:48 pm Monday, 8 February 2021 (MYT) Time in Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/


/**
 * @var $delivery_note \DeliveryNote
 */
$delivery_note = $state['_object'];


$tipo    = 'delivery_note.tariff_codes';
$tab     = 'delivery_note.tariff_codes';
$ar_file = 'ar_orders_tables.php';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
    )

);

$table_filters = array(
    'tariff_code' => array(
        'label' => _('Tariff code'),
        'title' => _('Tariff code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$smarty->assign(
    'table_metadata', json_encode(
                        array(
                            'parent'     => $state['object'],
                            'parent_key' => $state['key']
                        )
                    )

);
$smarty->assign('dn', $delivery_note);


$warehouse = get_object('warehouse', $delivery_note->get('Delivery Note Warehouse Key'));

$table_buttons = array();
$smarty->assign('table_buttons', $table_buttons);

include('utils/get_table_html.php');

