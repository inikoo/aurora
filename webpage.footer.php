<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 17:37:07 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'common.php';

$footer_data = array(
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
                            'alt'   => '',
                            'title' => ''

                        ),
                        array(
                            'type' => 'text',
                            'icon' => 'fa-map-marker ',
                            'text' => '2901 Marmora Road, Glassgow,<br>Seattle, WA 98122-1090'
                        ),
                        array(
                            'type' => 'text',
                            'icon' => 'fa-phone',
                            'text' => '1 -234 -456 -7890'
                        ),
                        array(
                            'type' => 'email',
                            'text' => 'info@yourdomain.com'
                        ),
                        array(
                            'type'  => 'logo',
                            'src'   => 'theme_1/images/footer-wmap.png',
                            'alt'   => '',
                            'title' => ''

                        ),
                    ),


                ),

                array(
                    'type'   => 'links',
                    'header' => 'Useful Links',

                    'items' => array(
                        array(
                            'url'   => 'http://caca.com',
                            'label' => 'Home Page Variations'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Awsome Slidershows'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Features and Typography'
                        )
                    )
                ),

                array(
                    'type'   => 'links',
                    'header' => 'Useful Links 2',
                    'items'  => array(
                        array(
                            'url'   => '#',
                            'label' => 'Home Page Variations'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Awsome Slidershows'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Features and Typography'
                        )
                    )
                ),


                array(
                    'type'   => 'text',
                    'header' => 'About Us',
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
                    'type' => 'text',
                    'text' => 'Copyright © 2014 Aaika.com. All rights reserved.  <a href="#">Terms of Use</a> | <a href="#">Privacy Policy</a>'

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

$smarty->assign('footer_data', $footer_data);


$smarty->display('webpage.footer.tpl');

?>
