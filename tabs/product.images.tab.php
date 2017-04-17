<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 September 2016 at 12:26:28 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'product.images';
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

    'js_code',
    array(
        'js/injections/edit_images.'.(_DEVEL ? '' : 'min.').'js',
        'js/injections/images_popups.'.(_DEVEL ? '' : 'min.').'js'

    )



);


include 'utils/get_table_html.php';

?>
