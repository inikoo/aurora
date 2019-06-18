<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 15:00:36 GMT+7, Bangkok , Thailand
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'campaign_order_recursion.components';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'campaign_order_recursion_components';

$default = $user->get_tab_defaults($tab);

$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
);

$table_filters = array(
    'target' => array('label' => _('Target')),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


$table_buttons   = array();

$table_buttons[] = array(
    'icon'     => 'layer-plus',
    'title'    => _('Add family to this offer'),
    'id'       => 'new_item',
    'class'    => 'items_operation',
    'add_allowance_to_order_recursion_deal' => array(

        'field_label' => _("Category").':',
        'metadata'    => base64_encode(
            json_encode(
                array(
                    'scope'      => 'targets',
                    'store_key'     => $state['_object']->get('Store Key'),
                    'parent'     => 'campaign',
                    'parent_key' => $state['_object']->id,
                    'options'    => array('order_recursion')
                )
            )
        )

    )

);

$smarty->assign(
    'table_metadata',
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key'],
                                'field'      => 'target'
                            )
                        )

);


$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('deal_key', $state['_object']->id);

$smarty->assign('table_top_template', 'campaign_order_recursion_components.edit.tpl');

include 'utils/get_table_html.php';


?>
