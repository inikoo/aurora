<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 April 2017 at 19:13:51 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function website_system_webpages_config($website_type) {

    $website_system_webpages = array(

        'EcomB2B' => array(


            'homepage'       => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'homepage',
                'Webpage Template Filename' => 'homepage',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'homepage',
                'Webpage Browser Title'     => _('Home'),
                'Webpage Name'              => _('Home'),
                'Webpage Meta Description'  => ''

            ),
            'login'          => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'login',
                'Webpage Template Filename' => 'login',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'login',
                'Webpage Browser Title'     => _('Login'),
                'Webpage Name'              => _('Login'),
                'Webpage Meta Description'  => ''
            ),
            'register'       => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'register',
                'Webpage Template Filename' => 'register',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'register',
                'Webpage Browser Title'     => _('Register'),
                'Webpage Name'              => _('Register'),
                'Webpage Meta Description'  => ''
            ),
            'welcome'        => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'welcome',
                'Webpage Template Filename' => 'welcome',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'welcome',
                'Webpage Browser Title'     => _('Welcome'),
                'Webpage Name'              => _('Welcome'),
                'Webpage Meta Description'  => ''
            ),
            'reset_password' => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'reset_password',
                'Webpage Template Filename' => 'reset_password',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'reset_password',
                'Webpage Browser Title'     => _('Reset password'),
                'Webpage Name'              => _('Reset password'),
                'Webpage Meta Description'  => ''
            ),
            'profile'        => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'profile',
                'Webpage Template Filename' => 'profile',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'profile',
                'Webpage Browser Title'     => _('Customer section'),
                'Webpage Name'              => _('Customer section'),
                'Webpage Meta Description'  => ''
            ),

            'contact' => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'contact',
                'Webpage Template Filename' => 'contact',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'contact',
                'Webpage Browser Title'     => _('Contact'),
                'Webpage Name'              => _('Contact'),
                'Webpage Meta Description'  => ''
            ),

            'basket'    => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'basket',
                'Webpage Template Filename' => 'basket',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'basket',
                'Webpage Browser Title'     => _('Basket'),
                'Webpage Name'              => _('Basket'),
                'Webpage Meta Description'  => ''
            ),
            'checkout'  => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'checkout',
                'Webpage Template Filename' => 'checkout',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'checkout',
                'Webpage Browser Title'     => _('Checkout'),
                'Webpage Name'              => _('Checkout'),
                'Webpage Meta Description'  => ''
            ),
            'thanks'    => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'thanks',
                'Webpage Template Filename' => 'thanks',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'thanks',
                'Webpage Browser Title'     => _('Thanks for your order'),
                'Webpage Name'              => _('Thanks'),
                'Webpage Meta Description'  => ''
            ),
            'not_found' => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'not_found',
                'Webpage Template Filename' => 'not_found',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'not_found',
                'Webpage Browser Title'     => _('Home'),
                'Webpage Name'              => _('Home'),
                'Webpage Meta Description'  => ''
            ),
            'offline' => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'offline',
                'Webpage Template Filename' => 'offline',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'offline',
                'Webpage Browser Title'     => _('Offline'),
                'Webpage Name'              => _('Offline'),
                'Webpage Meta Description'  => ''
            ),
            'in_process' => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'in_process',
                'Webpage Template Filename' => 'in_process',
                'Webpage Type'              => 'Info',
                'Webpage Code'              => 'in_process',
                'Webpage Browser Title'     => _('Under construction'),
                'Webpage Name'              => _('Under construction'),
                'Webpage Meta Description'  => ''
            ),
            'search'    => array(
                'Webpage Scope'             => 'System',
                'Webpage Scope Metadata'    => 'search',
                'Webpage Template Filename' => 'search',
                'Webpage Type'              => 'Sys',
                'Webpage Code'              => 'search',
                'Webpage Browser Title'     => _('Search'),
                'Webpage Name'              => _('Search'),
                'Webpage Meta Description'  => ''
            )


        ),


    );

    return $website_system_webpages[$website_type];

}

?>
