<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10-09-2019 18:06:19 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

$tab     = 'agent.images';
$ar_file = 'ar_images_tables.php';
$tipo    = 'images';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'caption' => array('label' => _('Caption')),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$smarty->assign(
    'upload_file', array(
        'tipo'       => 'upload_images',
        'parent'     => $state['object'],
        'parent_key' => $state['key'],
        'object'     => 'image',
        'label'      => _('Upload image')
    )
);


$smarty->assign(
    'js_code', 'js/injections/edit_images.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';


