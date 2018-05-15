<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2018 at 21:34:19 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'email_campaigns.transactional';
$ar_file = 'ar_email_template.php';
$tipo    = 'transactional_emails';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'type' => array(
        'label' => _('Type')
    )

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include('utils/get_table_html.php');


?>
