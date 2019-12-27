<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:02::37  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_help_module() {
    return array(
        'sections' => array(
            'help' => array(
                'type'  => 'object',
                'label' => _('Help'),
                'icon'  => 'shopping-cart',
                'id'    => 'forbidden',
                'tabs'  => array(
                    'help' => array()
                )
            )
        ),
        'about'    => array(
            'about' => array(
                'type'  => 'object',
                'label' => _('About'),
                'icon'  => '',
                'tabs'  => array(
                    'about' => array()
                )
            )
        )
    );
}