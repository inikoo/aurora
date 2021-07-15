<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 14 Jul 2021 19:41:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

function prepare_history_tab_with_notes($state, $smarty): array {

    $object = $state['_object'];
    $table_buttons   = array();
    $table_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('New note'),
        'id'    => "show_history_note_dialog"
    );
    $smarty->assign('table_buttons', $table_buttons);


    $smarty->assign(
        'history_notes_data', array(

                                'object' => $object->get_object_name(),
                                'key'    => $object->id
                            )
    );


    $smarty->assign(
        'aux_templates', array(
                           'history_notes.tpl',
                       )
    );
    $smarty->assign('state', $state);


    return [
        [],
        array(
            'note' => array(
                'label' => _('Notes'),
                'title' => _('Notes')
            ),
        ),
        array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        ),
        $smarty
    ];
}

function prepare_history_tab($state, $smarty): array {


    $smarty->assign('table_buttons', []);
    $smarty->assign('state', $state);

    return [
        [],
        array(
            'note' => array(
                'label' => _('Notes'),
                'title' => _('Notes')
            ),
        ),
        array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        ),
        $smarty
    ];
}


