<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29-04-2019 15:36:21 MYT,  Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'webpage.logbook';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';


$webpage = $state['_object'];


$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'note' => array(
        'label' => _('Notes'),
    ),
);




$parameters = array(
    'parent'     => 'webpage_logbook',
    'parent_key' => $webpage->id

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'  => 'sticky-note',
    'title' => _('New note'),
    'id'    => "show_history_note_dialog"
);


$smarty->assign('history_notes_data',
                array(

                    'object'=>'webpage',
                    'key'=>$webpage->id
                )
);


$smarty->assign('table_buttons', $table_buttons);


$smarty->assign('aux_templates', array('history_notes.tpl'));
$smarty->assign('state', $state);

include('utils/get_table_html.php');

?>
