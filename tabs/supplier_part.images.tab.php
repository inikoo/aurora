<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2016 at 13:03:26 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'part.images';
$ar_file = 'ar_images_tables.php';
$tipo    = 'images';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'caption' => array('label' => _('Caption')),
);

$parameters = array(
    'parent'     => 'part',
    'parent_key' => $state['_object']->get('Supplier Part Part SKU'),

);


$smarty->assign(
    'upload_file', array(
        'tipo'       => 'upload_images',
        'parent'     => 'part',
        'parent_key' => $state['_object']->get('Supplier Part Part SKU'),
        'object'     => 'image',
        'label'      => _('Upload image')
    )
);


$smarty->assign(
    'js_code', 'js/injections/edit_images.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';

?>
