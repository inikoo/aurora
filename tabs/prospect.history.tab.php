<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2018 at 14:46:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'prospect.history';
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
    'icon'  => 'sticky-note',
    'title' => _('New note'),
    'id'    => "show_history_note_dialog"
);

$table_buttons[] = array(
    'icon'  => 'person-carry',
    'title' => _('Log postal mail'),
    'id'    => "show_history_post_dialog"
);


$table_buttons[] = array(
    'icon'  => 'phone',
    'title' => _('Log call'),
    'id'    => "show_history_call_dialog"
);


$table_buttons[] = array(
    'icon'  => 'envelope',
    'title' => _('Send email'),
    'reference' => "prospects/".$state['parent_key']."/".$state['key'].'/email/new'
);
$smarty->assign('table_buttons', $table_buttons);



$smarty->assign('history_notes_data',
                array(

                    'object'=>'prospect',
                    'key'=>$state['_object']->id
                )
);




$smarty->assign('aux_templates', array('history_notes.tpl'));
$smarty->assign('state', $state);

include('utils/get_table_html.php');

?>
