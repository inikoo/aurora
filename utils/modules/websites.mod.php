<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:34::08  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_websites_module() {
    return array(
        'section'     => 'websites',
        'parent'      => 'website',
        'parent_type' => 'key',
        'sections'    => array(


            'webpage.new' => array(
                'type'  => 'new_object',
                'title' => _("New website"),
                'tabs'  => array(
                    'webpage.new' => array(
                        'label' => _(
                            'New Webpage'
                        )
                    ),

                )

            ),
            'analytics'   => array(
                'type'  => 'left_button',
                'label' => _('Analytics'),
                'title' => _('Analytics'),

                'icon'      => 'analytics',
                'reference' => 'website/%d/analytics',

                'tabs' => array(
                    'website.analytics' => array(
                        'label' => _('Analytics'),
                        'icon'  => 'analytics'
                    ),
                )

            ),
            'webpages'    => array(
                'type'      => 'navigation',
                'label'     => _('Web pages'),
                'icon'      => 'browser',
                'reference' => 'webpages/%d',

                'tabs' => array(
                    'website.webpage.types'       => array(
                        'label' => _('Web pages by type'),
                        'icon'  => 'server'
                    ),
                    'website.online_webpages'     => array(
                        'label' => _('Online web pages'),
                        'icon'  => 'browser'
                    ),
                    'website.in_process_webpages' => array(
                        'label' => _('To be published web pages'),
                        'icon'  => 'seedling'
                    ),


                    'website.offline_webpages' => array(
                        'label' => _('Offline web pages'),
                        'class' => 'right icon_only',
                        'icon'  => 'eye-slash'
                    ),

                )

            ),
            'web_users'   => array(
                'type'      => 'navigation',
                'label'     => _('Users'),
                'icon'      => 'users-class',
                'reference' => 'website/%d/users',

                'tabs' => array(
                    'website.users' => array(
                        'label' => _('Users'),
                        'icon'  => 'users'
                    )


                )

            ),


            'workshop' => array(
                'type'      => 'right_button',
                'label'     => _('Workshop'),
                'title'     => _('Workshop'),
                'icon'      => 'drafting-compass',
                'reference' => 'website/%d/workshop',

                'tabs' => array(


                    'website.header.preview' => array(
                        'label' => _('Header'),
                        'icon'  => 'arrow-alt-to-top'
                    ),
                    'website.menu.preview'   => array(
                        'label' => _('Menu'),
                        'icon'  => 'bars'
                    ),
                    'website.footer.preview' => array(
                        'label' => _('Footer'),
                        'icon'  => 'arrow-alt-to-bottom'
                    ),


                )

            ),


            'settings' => array(
                'type' => 'right_button',

                'title'     => _('Settings'),
                'icon'      => 'sliders-h',
                'reference' => 'website/%d/settings',

                'tabs' => array(
                    'website.details'      => array(
                        'label' => _('Setting'),
                        'icon'  => 'sliders-h'
                    ),
                    'website.colours'      => array(
                        'label' => _('Colours'),
                        'icon'  => 'tint',


                    ),
                    'website.localization' => array(
                        'label' => _('Localization'),
                        'icon'  => 'language',
                    ),
                ),


            ),

            'webpage_type' => array(
                'type' => 'object',
                'tabs' => array(

                    'webpage_type.online_webpages'     => array(
                        'label' => _('Online web pages'),
                        'icon'  => 'browser'
                    ),
                    'webpage_type.in_process_webpages' => array(
                        'label' => _('To be published web pages'),
                        'icon'  => 'seedling'
                    ),


                    'webpage_type.offline_webpages' => array(
                        'label' => _('Offline web pages'),
                        'class' => 'right icon_only',
                        'icon'  => 'eye-slash'
                    ),

                )
            ),


            'page_version' => array(
                'type' => 'object',
                'tabs' => array(


                    'page_version.analytics' => array(
                        'label' => _(
                            'Analytics'
                        ),
                        'icon'  => 'line-chart'
                    ),
                    'page_version.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'page_version.preview' => array(
                        'label' => _(
                            'Preview'
                        ),
                        'icon'  => 'eye'
                    ),

                )
            ),
            'website.user' => array(
                'type' => 'object',
                'tabs' => array(
                    'website.user.details'       => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database',
                        'title' => _(
                            'Details'
                        )
                    ),
                    'website.user.history'       => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note'
                    ),
                    'website.user.login_history' => array(
                        'label' => _(
                            'Sessions'
                        ),
                        'title' => _(
                            'Login history'
                        ),
                        'icon'  => 'login'
                    ),
                    'website.user.pageviews'     => array(
                        'label' => _(
                            'Pageviews'
                        ),
                        'icon'  => 'eye'
                    ),

                )
            ),

            'webpage' => array(
                'type'           => 'object',
                'label'          => _('Web page'),
                'icon'           => 'globe',
                'subtabs_parent' => array(
                    'webpage.favourites.families'  => 'webpage.favourites',
                    'webpage.favourites.products'  => 'webpage.favourites',
                    'webpage.favourites.customers' => 'webpage.favourites',
                    'webpage.search.queries'       => 'webpage.search',
                    'webpage.search.history'       => 'webpage.search',
                    'webpage.reminders.requests'   => 'webpage.reminders',
                    'webpage.reminders.customers'  => 'webpage.reminders',
                    'webpage.reminders.families'   => 'webpage.reminders',
                    'webpage.reminders.products'   => 'webpage.reminders',

                    'webpage.online_webpages'     => 'webpage.webpages',
                    'webpage.offline_webpages'    => 'webpage.webpages',
                    'webpage.webpage.types'       => 'webpage.webpages',
                    'webpage.in_process_webpages' => 'webpage.webpages',

                    'webpage.footer.preview' => 'webpage.templates',
                    'webpage.header.preview' => 'webpage.templates',


                    'webpage.templates' => 'webpage.templates',


                    'user_notifications'             => 'store.notifications',
                    'store.notifications_recipients' => 'store.notifications',
                    'localization.materials'         => 'store.localization',
                    'localization.website'           => 'store.localization'

                ),

                'tabs' => array(


                    'webpage.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'webpage.assets'     => array(
                        'label' => _('Asset links'),
                        'icon'  => 'grip-horizontal'
                    ),
                    'webpage.containers' => array(
                        'label' => _('Where is shown'),
                        'icon'  => 'grip-vertical'
                    ),
                    'webpage.preview'    => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench',
                        'class' => 'hide'
                    ),


                    'webpage.logbook' => array(
                        'label' => _('Logbook'),
                        'icon'  => 'road'
                    ),


                )
            ),


            'deleted.webpage' => array(
                'type'  => 'object',
                'title' => _('Deleted web page'),
                'tabs'  => array(


                    'deleted.webpage.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )

                )

            ),


            //'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
        )
    );
}