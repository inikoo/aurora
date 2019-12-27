<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:00::42  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_users_module() {
    return array(


        'sections' => array(


            'users' => array(
                'type'      => 'navigation',
                'label'     => _('Users').' ('._('All').')',
                'icon'      => 'users-class',
                'reference' => 'users',

                'tabs' => array(
                    'users'             => array(
                        'label' => _('Users')
                    ),
                    'users_by_category' => array(
                        'label' => _('Users categories')
                    ),


                    'deleted.users' => array(
                        'label' => _('Deleted users'),
                        'class' => 'right'
                    ),
                )
            ),
            /*
            'groups' => array(
                'type'  => 'navigation',
                'label' => _('Groups'),
                'icon'  => 'ball-pile',
                'reference' => 'users/groups',
                'tabs'  => array(
                    'users.groups' => array(
                        'label' => _('groups')
                    ),
                )
            ),
    */
            'staff' => array(
                'type'      => 'navigation',
                'label'     => _('Employees'),
                'icon'      => 'user-headset',
                'reference' => 'users/staff',

                'tabs' => array(
                    'users.staff' => array(
                        'label' => _('Users')
                    ),

                    'users.staff.login_history' => array(
                        'label' => _('Login History')
                    ),
                    'deleted.staff.users'       => array(
                        'label' => _('Deleted users'),
                        'class' => 'right'
                    ),
                )
            ),


            'contractors' => array(
                'type'      => 'navigation',
                'label'     => _('Contractors'),
                'icon'      => 'user-hard-hat',
                'reference' => 'users/contractors',

                'tabs' => array(
                    'users.contractors' => array(
                        'label' => _('Users')
                    ),


                    'users.contractors.login_history' => array(
                        'label' => _('Login History')
                    ),
                    'deleted.contractors.users'       => array(
                        'label' => _('Deleted users'),
                        'class' => 'right'
                    ),
                )
            ),


            'suppliers' => array(
                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'reference' => 'users/suppliers',

                'icon' => 'hand-holding-box',
                'tabs' => array(
                    'users.suppliers' => array(
                        'label' => _(
                            'Suppliers'
                        )
                    ),
                )
            ),

            'agents' => array(
                'type'      => 'navigation',
                'label'     => _('Agents'),
                'icon'      => 'user-secret',
                'reference' => 'users/agents',

                'tabs' => array(
                    'users.agents' => array(
                        'label' => _(
                            'Agents'
                        )
                    ),
                )
            ),
            /*
                        'others'      => array(
                            'type'  => 'navigation',
                            'label' => _('Other'),
                            'icon'  => 'users-crown',
                            'reference' => 'users/others',

                            'tabs'  => array(
                                'root.user' => array(
                                    'label' => _('Root user')
                                ),
                                'warehouse.user' => array(
                                    'label' => _('Warehouse user')
                                ),
                            )
                        ),

            */

            'user' => array(
                'type' => 'object',
                'tabs' => array(
                    'user.details'       => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database',
                        'title' => _(
                            'Details'
                        )
                    ),
                    'user.login_history' => array(
                        'label' => _(
                            'Login history'
                        ),
                        'title' => _('Login history'),
                        'icon'  => 'sign-in'
                    ),
                    'user.api_keys'      => array(
                        'label' => _('API keys'),
                        'icon'  => 'key'
                    ),


                    'user.history'          => array(
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


            'deleted.user' => array(
                'type' => 'object',
                'tabs' => array(

                    'deleted.user.login_history' => array(
                        'label' => _(
                            'Login history'
                        ),
                        'title' => _(
                            'Login history'
                        )
                    ),
                    'deleted.user.history'       => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )

                )

            ),


            'user.api_key.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'user.api_key.new' => array(
                        'label' => _(
                            'New API'
                        )
                    ),

                )
            ),
            'user.api_key'     => array(
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
            'deleted_api_key'  => array(
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