<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2018 at 19:20:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$ar_file = 'ar_agents_tables.php';


$tab  = 'agent.delivery.items_in_warehouse';
$tipo = 'agent.items_in_warehouse';

$table_views = array(
    'overview' => array('label' => _("Item's descriptions")),
    // 'placement_notes'=>array('label'=>_('Placement notes')),

);
//$smarty->assign('aux_templates', array('supplier.delivery.checking.tpl'));

$smarty->assign(
    'js_code', array(
                 'js/injections/agent.deliveries.'.(_DEVEL ? '' : 'min.').'js',
             )
);


$default = $user->get_tab_defaults($tab);


$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => 'Agent',
    'parent_key' => $user->get('User Parent Key')

);


$table_buttons = array();

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'table_metadata', base64_encode(
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key']
                            )
                        )
                    )
);

$smarty->assign('dn', $state['_object']);


$smarty->assign('table_top_template', 'supplier.delivery.options.tpl');


include 'utils/get_table_html.php';


?>
