<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:27::59  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_mailroom_module() {
    return array(
        'section'     => 'mailroom',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(

            'marketing' => array(
                'type'  => 'navigation',
                'label' => _('Marketing'),

                'icon'      => 'bullhorn',
                'reference' => 'mailroom/%d',
                'tabs'      => array(
                    'marketing_emails' => array(
                        'label' => _('Marketing emails'),
                        'icon'  => 'tags',
                    ),


                )

            ),

            'customer_notifications' => array(
                'type'      => 'navigation',
                'label'     => _('Customers notifications'),
                'icon'      => 'user',
                'reference' => 'mailroom/%s/notifications',


                'tabs' => array(


                    'customer_notifications' => array(
                        'label' => _('Operations'),
                        'icon'  => 'handshake-alt',


                    ),



                )
            ),

            'user_notifications' => array(
                'type'      => 'navigation',
                'label'     => _('Staff notifications'),
                'icon'      => 'bell',
                'reference' => 'mailroom/%s/staff_notifications',


                'tabs' => array(


                    'user_notifications' => array(
                        'label' => _('Notifications'),
                        'icon'  => 'bell',


                    ),



                )
            ),
/*
            'store.notifications'    => array(
                'label'   => _('Notifications'),
                'icon'    => 'bell',
                'subtabs' => array(

                    'user_notifications' => array(
                        'label' => _('Notifications by type'),
                        'icon'  => 'bell-school',
                    ),

                    'store.notifications_recipients' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'ear ',
                    ),


                ),
            ),

*/
            'email_campaign_type' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign_type.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                    ),

                    'email_campaign_type.next_recipients' => array(
                        'label' => _('Notifications to be send next shot'),
                        'title' => _('Next mailshot recipients'),
                        'icon'  => 'user-clock'
                    ),
                    'email_campaign_type.workshop'        => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'email_campaign_type.mailshots'   => array(
                        'label' => _('Mailshots'),
                        'icon'  => 'container-storage'
                    ),
                    'email_campaign_type.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'email_campaign_type.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),


            'mailshot' => array(
                'type' => 'object',
                'subtabs_parent' => array(
                    'mailshot.workshop.templates'              => 'mailshot.workshop',
                    'mailshot.workshop.previous_mailshots'     => 'mailshot.workshop',
                    'mailshot.workshop.other_stores_mailshots' => 'mailshot.workshop',
                    'mailshot.workshop.composer'               => 'mailshot.workshop',
                    'mailshot.workshop.composer_text'          => 'mailshot.workshop',

                ),
                'tabs' => array(
                    'mailshot.details'       => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),
                    'mailshot.set_mail_list' => array(
                        'label' => _('Set recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.workshop' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench',
                        'subtabs' => array(

                            'mailshot.workshop.composer'      => array(
                                'label'   => _('HTML email composer'),
                                'icon_v2' => 'fab fa-html5'
                            ),
                            'mailshot.workshop.composer_text' => array(
                                'label' => _('Plain text version'),
                                'icon'  => 'align-left'
                            ),

                            'mailshot.workshop.templates' => array(
                                'label' => _('Templates'),
                                'icon'  => 'clone'
                            ),

                            'mailshot.workshop.previous_mailshots'     => array(
                                'label' => _('Previous mailshots'),
                                'icon'  => 'history'
                            ),
                            'mailshot.workshop.other_stores_mailshots' => array(
                                'label' => _('Other stores mailshots'),
                                'icon'  => 'repeat-1'
                            ),


                        )
                    ),

                    'mailshot.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),


                    'mailshot.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'mailshot.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),

            'mailshot.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'mailshot.new' => array(
                        'label' => 'new mailshot'
                    ),

                )

            ),

            'email_tracking' => array(
                'type'  => 'object',
                'title' => _("Email tracking"),

                'tabs' => array(
                    'email_tracking.email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope',
                    ),

                    'email_tracking.events' => array(
                        'label' => _('Tracking'),
                        'icon'  => 'stopwatch'
                    ),

                )
            ),

        )
    );
}