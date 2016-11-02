<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2016 at 18:44:22 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'employee.images';
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

?>
