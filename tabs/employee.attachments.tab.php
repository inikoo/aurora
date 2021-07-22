<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2015 at 20:16:12 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */
if ($user->can_edit('Staff')) {
    include_once 'helpers/attachments/attachments_tab_snippet.php';
    $tab     = 'employee.attachments';
    $ar_file = 'ar_attachments_tables.php';
    $tipo    = 'attachments';
    $default = $user->get_tab_defaults($tab);
    list($table_views, $table_filters, $parameters, $smarty) = prepare_attachments_tab($state, $smarty, $state['request']);
    include('utils/get_table_html.php');
} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
    }
}

