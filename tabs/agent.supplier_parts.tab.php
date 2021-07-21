<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 13:24:33 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/
include_once 'utils/get_export_edit_template_fields.php';
/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */

$tab     = 'agent.supplier_parts';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'parts'    => array(
        'label' => _('Inventory Part'),
        'title' => _('Part details')
    ),
    'reorder'  => array('label' => _('Reorder')),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$smarty->assign('table_top_template', 'supplier_parts.edit.tpl');




$edit_table_dialog = array(

    'spreadsheet_edit' => array(
        'tipo'       => 'edit_objects',
        'parent'     => $state['object'],
        'parent_key' => $state['key'],
        'object'     => 'supplier_part',
        'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
    ),

);
$smarty->assign('edit_table_dialog', $edit_table_dialog);

$objects = 'supplier_part';
$edit_fields = get_export_edit_template_fields($objects);

$smarty->assign('edit_fields', $edit_fields);

$table_buttons = array();

$table_buttons[] = array(
    'icon'  => 'edit_add',
    'title' => _("Edit supplier's products"),
    'id'    => 'edit_dialog'
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



