<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:00::17  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_profile_module() {
    return array(


        'sections' => array(
            'profile' => array(
                'type'      => 'object',
                'label'     => '',
                'title'     => '',
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'profile.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'user.login_history' => array(
                        'label' => _('Login history'),
                        'icon'  => 'sign-in'
                    ),

                    'profile.api_keys'      => array(
                        'label' => _('API keys'),
                        'icon'  => 'key'
                    ),
                    'profile.history'       => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'user.deleted_api_keys' => array(
                        'icon'  => 'ban',
                        'label' => _('Deleted API keys'),
                        'title' => _('Deleted API keys'),
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'profile_admin' => array(
                'type'      => 'object',
                'label'     => '',
                'title'     => '',
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'profile.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'user.login_history' => array(
                        'label' => _(
                            'Login history'
                        ),
                        'icon'  => 'sign-in'
                    ),


                    'profile.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'profile.api_key.new'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'user.api_key.new' => array(
                        'label' => _('New API')
                    ),

                )
            ),
            'profile.api_key'         => array(
                'type' => 'object',
                'tabs' => array(
                    'user.api_key.details'  => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'user.api_key.requests' => array(
                        'label' => _('Requests'),
                        'icon'  => 'arrow-circle-right'
                    ),
                    'user.api_key.history'  => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),
            'profile.deleted_api_key' => array(
                'type'     => 'object',
                'showcase' => 'deleted_api_key',
                'tabs'     => array(

                    'api_key.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

        )

    );
}