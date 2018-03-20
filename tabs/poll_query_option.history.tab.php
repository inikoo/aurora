<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2018 at 12:49:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'poll_query_option.history';
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
if(!$state['_object']->deleted) {

    $table_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('New note'),
        'id'    => "show_history_note_dialog"
    );
}
$smarty->assign('table_buttons', $table_buttons);



$smarty->assign('history_notes_data',
                array(

                    'object'=>'customer_poll_query_option',
                    'key'=>$state['_object']->id
                )
);




$smarty->assign('aux_templates', array('history_notes.tpl'));
$smarty->assign('state', $state);

include('utils/get_table_html.php');

?>
