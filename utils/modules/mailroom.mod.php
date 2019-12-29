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
            'customer_notifications' => array(
                'type'      => 'navigation',
                'label'     => _('Notifications'),
                'icon'      => 'paper-plane',
                'reference' => 'customers/%s/notifications',


                'tabs' => array(

                    /*
                    'email_campaigns.newsletters' => array(
                        'label' => _('Newsletters'),
                        'icon'  => 'newspaper'
                    ),
                    'email_campaigns.mailshots'   => array(
                        'label' => _('Marketing mailshots'),
                        'icon'  => 'bullhorn'
                    ),

  */
                    'customer_notifications' => array(
                        'label' => _('Operations'),
                        'icon'  => 'handshake-alt',


                    ),


                    /*
                    'contacts'       => array(
                        'label' => _('Contacts')
                    ),
                    'customers'      => array(
                        'label' => _('Customers')
                    ),
                    'orders'         => array(
                        'label' => _('Orders')
                    ),
                    'data_integrity' => array(
                        'label' => _('Data Integrity')
                    ),

                    'correlations'   => array(
                        'label' => _('Correlations')
                    ),
                    */

                )
            ),


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
                        'icon'  => 'wrench'
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

        )
    );
}