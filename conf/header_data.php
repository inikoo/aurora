<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 March 2017 at 13:50:45 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

function get_default_header_data($template) {


    if ($template == 1) {


        return array(

            'logo'                  => 'theme_1/images/logo.png',
            'background_image'      => '',
            'background_repeat'     => '',
            'logo_show'             => true,
            'background_image_show' => true,
            'color'                 => array(
                'header'            => '#FFF',
                'header_background' => '#333',
                'menu'              => '#FFF',
                'menu_background'   => '#e54c4c',
                'menu_background_highlight'=>'#c54242',
                'menu_text_background_highlight'=>'#FFF',
                'items'             => '#777',
                'items_title'       => '#454545',
                'items_background'  => '#FFF',
                'items_background_border'=>'#CCC'
            ),


            'menu' => array(
                'columns' => array(
                    array(
                        'type'        => 'three_columns',
                        'show'        => true,
                        'label'       => _('Catalogue'),
                        'icon'        => 'far fa-th-large',
                        'sub_columns' => array(
                            array(
                                'type'       => 'departments',
                                'page'       => '0-10',
                                'page_label' => '1-10',
                                'label'      => _('Departments')

                            ),
                            array(
                                'type'       => 'web_departments',
                                'page'       => '0-10',
                                'page_label' => '1-10',
                                'label'      => _('Special Departments')

                            ),
                            array(
                                'type'       => 'web_families',
                                'page'       => '0-10',
                                'page_label' => '1-10',
                                'label'      => _('Special Families')

                            )


                        )
                    ),
                    array(
                        'type'        => 'three_columns',
                        'show'        => true,
                        'label'       => _('Info pages'),
                        'icon'        => 'far fa-info-circle',
                        'sub_columns' => array(
                            array(
                                'type'  => 'items',
                                'label' => '',
                                'items' => array(
                                    array(
                                        'label' => _('Homepage'),
                                        'icon'  => 'far fa-home',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('About us'),
                                        'icon'  => 'far fa-smile',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Contact'),
                                        'icon'  => 'far fa-phone',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Terms & Conditions'),
                                        'icon'  => 'far fa-file-alt',
                                        'url'   => ''
                                    )
                                )

                            ),
                            array(
                                'type'  => 'items',
                                'label' => '',
                                'items' => array(
                                    array(
                                        'label' => _('Catalogue'),
                                        'icon'  => 'far fa-th-large',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Search'),
                                        'icon'  => 'far fa-search',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Delivery'),
                                        'icon'  => 'far fa-truck',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('FAQ'),
                                        'icon'  => 'far fa-question',
                                        'url'   => ''
                                    ),

                                )

                            ),
                            array(
                                'type'  => 'text',
                                'title' => _('About Website'),
                                'image' => 'https://placehold.co/400x160.png',
                                'url'   => '',
                                'text'  => 'There are many variations passages available the majority have alteration in some form, by injected humour on randomised words if you are going to use a passage of lorem anything.'


                            ),


                        )
                    ),
                    array(
                        'type'        => 'three_columns',
                        'show'        => true,
                        'label'       => _('Offers'),
                        'icon'        => 'far fa-tag',
                        'sub_columns' => array(
                            array(
                                'type'  => 'items',
                                'label' => '',
                                'items' => array(
                                    array(
                                        'label' => _('Link to offer 1'),
                                        'icon'  => 'far fa-tag',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link to offer 2'),
                                        'icon'  => 'far fa-tag',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link to offer 3'),
                                        'icon'  => 'far fa-tag',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link to offer 4'),
                                        'icon'  => 'far fa-tag',
                                        'url'   => ''
                                    )
                                )

                            ),

                            array(
                                'type' => 'image',

                                'title' => _('Big offer A'),
                                'image' => 'https://placehold.co/400x350.png',
                                'url'   => '',


                            ),

                            array(
                                'type'  => 'text',
                                'title' => _('Big offer B'),
                                'image' =>  'https://placehold.co/400x200.png',
                                'url'   => '',
                                'text'  => 'There are many variations passages available the majority have alteration in some form, by injected humour on randomised words if you are going to use a passage of lorem anything.'


                            ),


                        )
                    ),
                    array(
                        'type'        => 'three_columns',
                        'show'        => true,
                        'label'       => _('Inspiration'),
                        'icon'        => 'far fa-lightbulb',
                        'sub_columns' => array(


                            array(
                                'type'  => 'image',
                                'title' => _('Big Idea A'),
                                'image' => 'https://placehold.co/400x350.png',
                                'url'   => '',


                            ),

                            array(
                                'type'  => 'text',
                                'title' => _('Big Idea B'),
                                'image' =>  'https://placehold.co/400x200.png',
                                'url'   => '',
                                'text'  => 'There are many variations passages available the majority have alteration in some form, by injected humour on randomised words if you are going to use a passage of lorem anything.'


                            ),
                            array(
                                'type'  => 'items',
                                'label' => '',
                                'items' => array(
                                    array(
                                        'label' => _('List of bright ideas'),
                                        'icon'  => 'far fa-bolt',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Warm welcoming stuff'),
                                        'icon'  => 'far fa-sun',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Eco friendly lifestyle'),
                                        'icon'  => 'far fa-leaf',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Rocket science inspiration'),
                                        'icon'  => 'far fa-rocket',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link 1'),
                                        'icon'  => 'far fa-bolt',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link 2'),
                                        'icon'  => 'far fa-tree',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link 3'),
                                        'icon'  => 'far fa-thumbs-up',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link 4'),
                                        'icon'  => 'far fa-paper-plane',
                                        'url'   => ''
                                    )
                                )

                            ),


                        )
                    ),
                    array(
                        'type'  => 'single_column',
                        'show'  => true,
                        'label' => _('Extra column'),
                        'icon'  => '',
                        'items' => array(
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' 1',
                                'url'   => ''
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' 2',
                                'url'   => ''
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' 3',
                                'url'   => ''
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' 4',
                                'url'   => ''
                            ),
                            array(
                                'type'      => 'submenu',
                                'label'     => _('Submenu'),
                                'sub_items' => array(
                                    array(
                                        'label' => _('Link').' &alpha;	',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link').' &beta;	',
                                        'url'   => ''
                                    ),
                                    array(
                                        'label' => _('Link').' &gamma;	',
                                        'url'   => ''
                                    )
                                )
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' A	',
                                'url'   => ''
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' B	',
                                'url'   => ''
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' C	',
                                'url'   => ''
                            ),
                            array(
                                'type'  => 'item',
                                'label' => _('Link').' D	',
                                'url'   => ''
                            )
                        )
                    ),


                )

            )


        );

    } else {
        return false;

    }


}


