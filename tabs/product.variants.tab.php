<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 04 May 2022 15:09:05 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var User $user */
/** @var array $state */
/** @var Smarty $smarty */


$tab     = 'product.variants';
$ar_file = 'ar_products_tables.php';
$tipo    = 'variants';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'    => array('label' => _('Overview')),
    'performance' => array('label' => _('Performance')),
    'sales'       => array('label' => _('Sales')),
    'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
    'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Website code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Website name')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons = array();


$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New variant'),
    'reference' => "products/".$state['store']->id."/".$state['key'].'/variants/new'
);

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('table_top_template', 'product.variants.edit.tpl');

include 'utils/get_table_html.php';



