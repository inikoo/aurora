<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 27 Oct 2022 09:30:44 British Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

$tab     = 'production.external_products.wget';
$ar_file = 'ar_production_tables.php';
$tipo    = 'production_external_products';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

include 'utils/get_table_html.php';


