<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 12:18:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab     = 'supplier.orders';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


if ($state['_object']->get('Supplier Type') != 'Archived') {

    $table_buttons   = array();




    if($state['_object']->get('Supplier Has Agent')=='Yes'){

        foreach($state['_object']->get_agents_data() as $agent_data){
            $table_buttons[] = array(
                'icon'  => 'plus',
                'title' => sprintf(_('New purchase order using %s agent'),$agent_data['Agent Name']),
                'id'    => 'new_purchase_order',
                'attr'  => array(
                    'parent'     => $state['object'],
                    'parent_key' => $state['key'],

                    'agent_key' => $agent_data['Agent Key'])
                );



        }


    }else{
        $table_buttons[] = array(
            'icon'  => 'plus',
            'title' => _('New purchase order'),
            'id'    => 'new_purchase_order',
            'attr'  => array(
                'parent'     => $state['object'],
                'parent_key' => $state['key'],
            )


        );

    }



    $smarty->assign('table_buttons', $table_buttons);
}

$smarty->assign('js_code', 'js/injections/supplier.orders.'.(_DEVEL ? '' : 'min.').'js');

include 'utils/get_table_html.php';

?>
