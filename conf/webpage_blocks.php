<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 13:46:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

function get_webpage_blocks($theme = '') {


    $blocks = array(

        /*
                'static_banner'  => array(
                    'type'   => 'static_banner',
                    'label'  => _('Header'),
                    'icon'   => 'fa-header',
                    'show'   => 1,
                    '_top_text_left' => 'customize',
                    '_top_text_right' => 'your own',
                    '_title' => 'Chic &amp; Uniquefima Header',
                    '_text' => 'in easy peasy steps',
                    'link'=>'',
                    'bg_image'=>''
                ),
        */

        'text'       => array(
            'type'        => 'text',
            'label'       => _('Text'),
            'icon'        => 'fa-font',
            'show'        => 1,
            'template'    => 't1',
            'text_blocks' => array(
                array(
                    'text' => '<h1>Title</h1>When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting, remaining essentially believable.'
                )
            ),
        ),

        'blackboard'       => array(
            'type'        => 'blackboard',
            'label'       => _('Blackboard'),
            'icon'        => 'fa-image',
            'show'        => 1,
            'top_margin'=>0,
            'bottom_margin'=>0,
            'height'=>'200',
            'images'=>array(),
            'texts'=>array()

        ),


        'images'     => array(
            'type'  => 'images',
            'label' => _('Images'),
            'icon'  => 'fa-camera',
            'show'  => 1,

            'images' => array()


        ),


        /*
                'one_pack'   => array(
                    'type'      => 'one_pack',
                    'label'     => _('One-Pack').'discontinued',
                    'icon'      => 'fa-minus',
                    'show'      => 1,
                    '_title'    => _('Title'),
                    '_subtitle' => 'Here goes an abstract of your content',
                    '_text'     => 'When an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also electronics typesetting, remaining essentially believable.',
                ),
        */

        'two_pack'   => array(
            'type'  => 'two_pack',
            'label' => _('Two-Pack'),
            'icon'  => 'fa-pause',
            'show'  => 1,

            '_image'     => '',
            '_image_key' => '',
            '_title'     => _('Thanks for joining us!'),
            '_subtitle'  => 'Will cover many web sites still in their infancy various versions have evolved packages over the years.',
            '_text'      => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet anything embarrassing hidden in the middle many web sites.',
        ),
        'three_pack' => array(
            'type'     => 'three_pack',
            'label'    => _('Three-Pack'),
            'icon'     => 'fa-bars fa-rotate-90',
            'show'     => 1,
            'title'    => 'What We Do',
            'subtitle' => 'Aipsum therefore always',


            'columns' => array(

                array(
                    'icon'  => 'icon-screen-desktop',
                    'title' => 'Modern Design',
                    'text'  => 'Mombined with handful model sentence structures to generate which looks.',
                ),
                array(
                    'icon'  => 'icon-social-dropbox',
                    'title' => 'Mega Blobs',
                    'text'  => 'Mombined with handful model sentence structures to generate which looks.',
                ),
                array(
                    'icon'  => 'icon-cup',
                    'title' => 'Diffrent Prods',
                    'text'  => 'Mombined with handful model sentence structures to generate which looks.',
                ),

            )


        ),


        'six_pack'   => array(
            'type'  => 'six_pack',
            'label' => _('Six-Pack'),
            'icon'  => 'fa-th-large',
            'show'  => 1,


            'columns' => array(

                array(
                    array(
                        'icon'  => 'icon-cursor',
                        'title' => 'Several Design Options',
                        'text'  => 'Many desktop publishing packages and web page editors now use Ipsum their defau mode various versions have over the years.',
                    ),
                    array(
                        'icon'  => 'icon-basket-loaded',
                        'title' => 'Build Own Website',
                        'text'  => 'Many desktop publishing packages and web page editors now use Ipsum their defau mode various versions have over the years.',
                    )

                ),

                array(
                    array(
                        'icon'  => 'icon-badge',
                        'title' => 'Clean &amp; Modern Design',
                        'text'  => 'Many desktop publishing packages and web page editors now use Ipsum their defau mode various versions have over the years.',
                    ),
                    array(
                        'icon'  => 'icon-social-dropbox',
                        'title' => 'Useful Shortcut\'s',
                        'text'  => 'Many desktop publishing packages and web page editors now use Ipsum their defau mode various versions have over the years.',
                    )

                ),

                array(
                    array(
                        'icon'  => 'icon-settings',
                        'title' => 'Icon Fonts Easy to Use',
                        'text'  => 'Many desktop publishing packages and web page editors now use Ipsum their defau mode various versions have over the years.',
                    ),
                    array(
                        'icon'  => 'icon-bulb',
                        'title' => 'Excellent Customer Services',
                        'text'  => 'Many desktop publishing packages and web page editors now use Ipsum their defau mode various versions have over the years.',
                    )

                ),


            )


        ),


        'two_one'    => array(
            'type'  => 'two_one',
            'label' => _('Two-One'),
            'icon'  => ' fa-window-maximize fa-rotate-90',
            'show'  => 1,

            'columns' => array(
                array(
                    'type'   => 'two_third',
                    '_title' => _('Title'),
                    '_text'  => 'text'
                ),
                array(
                    'type'   => 'one_third',
                    '_title' => _('Title'),
                    '_text'  => 'text'
                )

            )


        ),



        'button'     => array(
            'type'              => 'button',
            'label'             => _('Button'),
            'icon'              => 'fa-hand-pointer',
            'show'              => 1,
            'title'             => 'Great Value to Get the Dash on TF Only',
            'text'              => 'Packages and web page editors search versions have over the years sometimes.',
            'button_label'      => 'Read More',
            'link'              => '',
            'bg_image'          => '',
            'bg_color'          => '',
            'text_color'        => '',
            'button_bg_color'   => '',
            'button_text_color' => '',


        ),
        /*

        'image'      => array(
            'type'    => 'image',
            'label'   => _('Image'),
            'icon'    => 'fa-image',
            'show'    => 1,
            'src'     => '',
            'tooltip' => '',
            'link'    => '',

        ),
*/


        'iframe'    => array(
            'type'   => 'iframe',
            'label'  => 'iFrame',
            'icon'   => 'fa-window-restore',
            'show'   => 1,
            'height' => 250,
            'src'    => 'cdn.bannersnack.com/banners/bxmldll37/embed/index.html?userId=30149291&t=1499779573'
        ),
        /*
        'counter'   => array(
            'type'  => 'counter',
            'label' => _('Counter'),
            'icon'  => 'fa-sort-numeric-down',

            'show'    => 1,
            'columns' => array(
                array(
                    'label'  => 'Projects',
                    'number' => 270,
                    'link'   => ''
                ),
                array(
                    'label'  => 'Clients',
                    'number' => 225,
                    'link'   => ''

                ),
                array(
                    'label'  => 'Likes',
                    'number' => 4500,
                    'link'   => ''

                ),
                array(
                    'label'  => 'Days',
                    'number' => 365,
                    'link'   => ''

                )

            )
        ),

        */
        'telephone' => array(
            'type'       => 'telephone',
            'label'      => _('Phone'),
            'icon'       => 'fa-phone',
            'show'       => 1,
            '_title'     => 'Need help? Ready to Help you with Whatever you Need',
            '_telephone' => '+88 123 456 7890',
            '_text'      => 'Answer Desk is Ready!',
        ),
        'map'       => array(
            'type'  => 'map',
            'label' => _('Map'),
            'icon'  => 'fa-map-marker-alt',
            'show'  => 1,
            'src'   => '#map'
        ),
        'products'       => array(
            'type'              => 'products',
            'auto'              => false,
            'auto_scope'        => 'webpage',
            'auto_items'        => 5,
            'auto_last_updated' => '',
            'label'             => _('Products'),
            'icon'              => 'fa-window-restore',
            'show'              => 1,
            'top_margin'        => 0,
            'bottom_margin'     => 0,
            'item_headers'      => false,
            'items'             => array(),
            'sort'              => 'Manual',
            'title'             => _('Products'),
            'show_title'        => true



        ),
        'see_also'       => array(
            'type'              => 'see_also',
            'auto'              => true,
            'auto_scope'        => 'webpage',
            'auto_items'        => 5,
            'auto_last_updated' => '',
            'label'             => _('See also'),
            'icon'              => 'fa-link',
            'show'              => 1,
            'top_margin'        => 0,
            'bottom_margin'     => 0,
            'item_headers'      => false,
            'items'             => array(),
            'sort'              => 'Manual',
            'title'             => _('See also'),
            'show_title'        => true
        ),


    );


    return $blocks;

}

?>
