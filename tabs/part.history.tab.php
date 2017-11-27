<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2015 at 12:52:22 CEST, Malaga Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'part.history';
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



$smarty->assign('history_notes_data',
                array(

                    'object'=>'part',
                    'key'=>$state['_object']->id
                )
);




$smarty->assign('aux_templates', array('history_notes.tpl'));


include('utils/get_table_html.php');

?>
