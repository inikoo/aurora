<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 16 Jul 2021 23:01:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

function prepare_attachments_tab($state, $smarty,$link): array {

    $table_buttons   = [];
    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New attachment'),
        'reference' => $link."/attachment/new"
    );

    $smarty->assign('table_buttons', $table_buttons);
    $smarty->assign('state', $state);

    return [
        [],
        array(
            'caption' => array(
                'label' => _('Caption'),
                'title' => _('Caption')
            ),
        ),
        array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        ),
        $smarty
    ];
}


