<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2018 at 14:46:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/
/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */

$tab     = 'prospect.history';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';

$default = $user->get_tab_defaults($tab);

/**
 * @var $prospect \Prospect
 */
$prospect = $state['_object'];


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


if (in_array(
    $prospect->get('Prospect Status'), array(
                                         'Contacted',
                                         'NoContacted'
                                     )
)) {


    if ($prospect->has_address()) {
        $table_buttons[] = array(
            'icon'  => 'person-carry',
            'title' => _('Log postal mail'),
            'class' => "open_log_dialog log_post"
        );


    }

    if ($prospect->has_telephone()) {

        $table_buttons[] = array(
            'icon'  => 'phone',
            'title' => _('Log call'),
            'class' => "open_log_dialog log_call"
        );
    }


    $table_buttons[] = array(
        'icon'  => 'envelope',
        'title' => _('Send email'),
        'id'    => "show_send_email_dialog"
        //  'reference' => "prospects/".$state['parent_key']."/".$state['key'].'/email/new'
    );

}


$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('prospect', $prospect);

$templates = $prospect->get_templates('objects', 'Active');

$smarty->assign('templates', $templates);
$smarty->assign('number_templates', count($templates));

foreach ($templates as $template) {
    $smarty->assign('template_key', $template->id);
    $smarty->assign('template_name', $template->get('Email Template Name'));

    break;

}


$smarty->assign(
    'history_notes_data', array(

                            'object' => 'prospect',
                            'key'    => $prospect->id
                        )
);


$smarty->assign(
    'aux_templates', array(
                       'history_notes.tpl',
                       'prospect.history.tpl'
                   )
);
$smarty->assign('state', $state);


$smarty->assign(
    'js_code', 'js/injections/prospect.history.'.(_DEVEL ? '' : 'min.').'js'
);

include('utils/get_table_html.php');


