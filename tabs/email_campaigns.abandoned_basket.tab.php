<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 May 2018 at 08:20:37 CEST , Trnava, Slovakia
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
        'title'     => _('New abandoned_basket email'),
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
