<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   09 June 2020  12:20::48  +0800 Kuala Lumpur Malaysia

 Copyright (c) 2020, Inikoo

 Version 3.0
*/

function get_utils_module() {
    return array(
        'sections' => array(
            'forbidden' => array(
                'type'  => 'object',
                'label' => _('Forbidden'),
                'title' => _('Forbidden'),
                'id'    => 'forbidden',
                'tabs'  => array(
                    'forbidden' => array()
                )
            ),
            'not_found' => array(
                'type'  => 'object',
                'label' => _('Not found'),
                'title' => _('Not found'),
                'id'    => 'not_found',

                'tabs' => array(
                    'not_found' => array(),
                )
            ),




        )
    );
}