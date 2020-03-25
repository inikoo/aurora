<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:02::00  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

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


            'fire' => array(
                'type'  => 'object',
                'label' => _('Attendance'),
                'icon'  => 'chess-clock',
                'id'    => 'fire',

                'tabs' => array(
                    'attendance' => array(
                        'label' => _('Attendance'),
                        'title' => _('Attendance'),
                        'icon'=>'chess-clock'
                    ),
                    'fire' => array(
                        'label' => '<i class="fa fa-fire " style="color:orange"></i> '._('Fire'),
                        'title' => _('Fire evacuation roll call')
                    ),
                )
            ),

        )
    );
}