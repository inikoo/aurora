<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2016 at 10:06:02 GMT+8, Yiwu, China
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



$image_scope_options=array(
    array(
        'label'=>_('SKO image'),'value'=>'SKO'),
    array(
        'label'=>_('Marketing'),'value'=>'Marketing')

);

$smarty->assign('image_scope_options',$image_scope_options);

$smarty->assign('aux_templates', array('edit_images.tpl'));


$smarty->assign(
    'js_code', 'js/injections/edit_images.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';

?>
