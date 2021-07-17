<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2016 at 00:07:32 GMT+8, Plane Kuala Lumpur, Malaysia - Denpasar, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */
if ($user->can_edit('parts')) {
    include_once 'helpers/attachments/attachments_tab_snippet.php';
    $tab     = 'part.attachments';
    $ar_file = 'ar_attachments_tables.php';
    $tipo    = 'attachments';
    $default = $user->get_tab_defaults($tab);
    list($table_views, $table_filters, $parameters, $smarty) = prepare_attachments_tab($state, $smarty, $state['request']);
    include('utils/get_table_html.php');
} else {
    try {
        $html = $smarty->fetch('access_denied');
    } catch (Exception $e) {
    }
}
