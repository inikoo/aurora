<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 May 2018 at 16:50:46 CEST, Mijas, Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'email_campaigns.abandoned_basket';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'abandoned_basket';


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
        'title'     => _('New abandoned cart mailshot'),
        'id'=>'new_abandoned_cart',
        'attr'=>array(
            'parent'=>$state['parent'],
            'parent_key'=>$state['parent_key'],

        )

    );

}
$smarty->assign('table_buttons', $table_buttons);



//$smarty->assign('js_code', 'js/injections/newsletters.'.(_DEVEL ? '' : 'min.').'js');

include('utils/get_table_html.php');


?>
