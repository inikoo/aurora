<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 December 2015 at 21:49:25 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/
if ($user->can_edit('Staff')) {

    $tab     = 'employee.attachment.history';
    $ar_file = 'ar_history_tables.php';
    $tipo    = 'object_history';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'note' => array(
            'label' => _('Notes'),
            'title' => _('Notes')
        ),
    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );

    include('utils/get_table_html.php');

} else {
    $html = '<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';

}
