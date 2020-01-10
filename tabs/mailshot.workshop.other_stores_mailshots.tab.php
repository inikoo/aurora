<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12 February 2019 at 01:58:41 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

$tab     = 'mailshot.workshop.other_stores_mailshots';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'other_stores_mailshots';


$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'id' => array('label' => _('Id')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
    'redirect' => base64_url_encode('mailshot.workshop'),

);




$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);




include('utils/get_table_html.php');


?>
