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



        'text' => array(
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

        'blackboard' => array(
            'type'          => 'blackboard',
            'label'         => _('Blackboard'),
            'icon'          => 'fa-image',
            'show'          => 1,
            'top_margin'    => 0,
            'bottom_margin' => 0,
            'height'        => '200',
            'images'        => array(),
            'texts'         => array()

        ),


        'images' => array(
            'type'  => 'images',
            'label' => _('Images'),
            'icon'  => 'fa-camera',
            'show'  => 1,

            'images' => array()


        ),



        'button' => array(
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



        'iframe'    => array(
            'type'   => 'iframe',
            'label'  => 'iFrame',
            'icon'   => 'fa-window-restore',
            'show'   => 1,
            'height' => 250,
            'src'    => 'cdn.bannersnack.com/banners/bxmldll37/embed/index.html?userId=30149291&t=1499779573'
        ),

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
        'products'  => array(
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
        'see_also'  => array(
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



        'in_process'     => array(
            'type'          => 'in_process',
            'label'         => _('Under construction'),
            'icon'          => 'fa-seedling',
            'show'          => 1,
            'top_margin'    => 40,
            'bottom_margin' => 60,
            'labels'        => array(
                '_title'        => _('Under construction'),
                '_text'         => _('This page is under construction. Please come back soon!.'),


            )
        ),




        'favourites'  => array(
            'type'          => 'favourites',
            'label'         => _('Favourites'),
            'icon'          => 'fa-heart',
            'show'          => 1,
            'top_margin'    => 40,
            'bottom_margin' => 60,
            'labels'        => array(
                'with_items' => '<h1>'._('My favourites').'</h1><p>'._('Here you can see your favourites').'</p>',
                'no_items'   => '<h1>'._('My favourites').'</h1><p>'._('You still have no favourites').'</p>',
            )
        ),





        'reviews' => array(
            'type'          => 'reviews',
            'label'         => _('Reviews'),
            'icon'          => 'fa-comment-alt-smile',
            'show'          => 1,
            'top_margin'    => 40,
            'bottom_margin' => 60,
            'labels'        => array()

        ),








    );


    return $blocks;

}

?>
