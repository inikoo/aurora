<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 March 2017 at 16:44:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

function get_default_footer_data($template) {


    if ($template == 1) {


        return array(
            'rows' => array(
                array(
                    'type'    => 'main_4',
                    'columns' => array(

                        array(
                            'type' => 'address',

                            'items' => array(

                                array(
                                    'type'  => 'logo',
                                    'src'   => 'theme_1/images/footer-logo.png',
                                    'title' => ''

                                ),
                                array(
                                    'type' => 'text',
                                    'icon' => 'fa-map-marker ',
                                    'text' => '#address'
                                ),
                                array(
                                    'type' => 'text',
                                    'icon' => 'fa-phone',
                                    'text' => '#tel'
                                ),
                                array(
                                    'type' => 'email',
                                    'text' => '#email'
                                ),
                                array(
                                    'type'  => 'logo',
                                    'src'   => 'theme_1/images/footer-wmap.png',
                                    'title' => ''

                                ),
                            ),


                        ),

                        array(
                            'type'   => 'links',
                            'header' => _('Useful Links'),

                            'items' => array(
                                array(
                                    'url'   => '#',
                                    'label' => _('Home Page Variations')
                                ),
                                array(
                                    'url'   => '#',
                                    'label' => _('Awesome Products')
                                ),
                                array(
                                    'url'   => '#',
                                    'label' => _('Features and Benefits')
                                )
                            )
                        ),

                        array(
                            'type'   => 'links',
                            'header' => _('Useful Links 2'),
                            'items'  => array(
                                array(
                                    'url'   => '#',
                                    'label' => _('Home Page Variations')
                                ),
                                array(
                                    'url'   => '#',
                                    'label' => _('Awesome Products')
                                ),
                                array(
                                    'url'   => '#',
                                    'label' => _('Features and Benefits')
                                )
                            )
                        ),


                        array(
                            'type'   => 'text',
                            'header' => _('About Us'),
                            'text'   => '
                        
                        <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined</p>
                        <br />
                        <p>An chunks as necessary, making this the first true generator on the Internet. Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover desktop publishing packages many purpose web sites.</p>

                        '
                        )

                    )

                ),
                array(
                    'type'    => 'copyright',
                    'columns' => array(
                        array(
                            'type'  => 'copyright_bundle',
                            'owner' => 'Aurora',


                            'links' => array(
                                array(
                                    'label' => _('Terms of Use'),
                                    'url'   => '#'
                                ),
                                array(
                                    'label' => _('Privacy Policy'),
                                    'url'   => '#'
                                )


                            )


                        ),
                        array(
                            'type'  => 'social_links',
                            'items' => array(
                                array(
                                    'icon' => 'fa-facebook',
                                    'url'  => '#'

                                ),
                                array(
                                    'icon' => 'fa-twitter',
                                    'url'  => '#'

                                ),
                                array(
                                    'icon' => 'fa-linkedin',
                                    'url'  => '#'

                                )

                            )

                        )

                    )

                )

            )


        );

    } else {
        return false;

    }


}

?>
