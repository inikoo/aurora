<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:30:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'campaign.history';
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


$table_buttons   = array();
$table_buttons[] = array(
    'icon'  => 'sticky-note-o',
    'title' => _('New note'),
    'id'    => "show_history_note_dialog"
);
$smarty->assign('table_buttons', $table_buttons);


$smarty->assign('aux_templates', array('history_notes.tpl'));
$smarty->assign('state', $state);

include('utils/get_table_html.php');

?>
