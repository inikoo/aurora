<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2017 at 21:37:40 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


    $email_templates_data = array(


        'welcome' => array(

            'fields'    => array(

                'Email Template Filename'  => 'welcome.minimalistic',
                'Email Template Name'      => _('Welcome'),
                'Email Template Role Type' => 'Transactional',
                'Email Template Role'      => 'Welcome',
                'Email Template Scope'     => 'Website',

            ),
            'website_types'=>array('EcomB2B'),

            'templates' => array(
                'welcome.minimalistic',
                'welcome.yummy'
            )


        )


    );


?>
