<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2018 at 18:13:27 GMT+8, , Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'email_campaigns.newsletters';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'newsletters';


$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'id' => array('label' => _('Id')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);




$table_buttons   = array();

if( $state['parent']=='store') {

    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New newsletter'),
        'id'=>'new_newsletter',
        'attr'=>array(
            'parent'=>$state['parent'],
            'parent_key'=>$state['parent_key'],

        )

    );

}
$smarty->assign('table_buttons', $table_buttons);



$smarty->assign(
    'js_code', 'js/injections/newsletters.'.(_DEVEL ? '' : 'min.').'js'
);

include('utils/get_table_html.php');


?>
