<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 April 2018 at 14:54:37 BST, Sheffield, UK

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_default_websites() {

    $styles_data= array(
        array(
            'body',
            'background-color',
            '#eee'
        ),
        array(
            'body',
            'color',
            '#727272'
        ),
        array(
            '.site_wrapper',
            'background-color',
            '#fff'
        ),
        array(
            '#top_header',
            'height',
            '60px'
        ),
        array(
            '#header_logo',
            'flex-basis',
            '80px'
        ),
        array(
            '#header_logo',
            'background-image',
            'none'
        ),
        array(
            '#body',
            'background-image',
            'none'
        ),
        array(
            'body',
            'background-image',
            'none'
        ),
        array(
            '#top_header',
            'color',
            '#727272'
        ),
        array(
            '#top_header',
            'background-color',
            '#fff'
        ),

        array(
            '#top_header',
            'background-image',
            'none'
        ),
        array(
            '.top_body',
            'border-bottom-width',
            '1px'
        ),
        array(
            '.top_body',
            'border-bottom-style',
            'solid'
        ),

        array(
            '.top_body',
            'border-bottom-color',
            '#ccc'
        ),
        array(
            '.top_body',
            'color',
            '#727272'
        ),
        array(
            '.top_body',
            'background-color',
            '#fff'
        ),


        array(
            '#bottom_header',
            'background-color',
            '#333'
        ),

        array(
            '#bottom_header a.menu',
            'color',
            '#f2f2f2'
        ),
        array(
            '#bottom_header a.menu',
            'background-color',
            '#333'
        ),

        array(
            '#bottom_header a.menu.active',
            'color',
            'black'
        ),
        array(
            '#bottom_header a.menu.active',
            'background-color',
            '#ddd'
        ),
        array(
            '#bottom_header a.menu.dropdown:hover',
            'color',
            'black'
        ),
        array(
            '#bottom_header a.menu.dropdown:hover',
            'background-color',
            '#ddd'
        ),
        // same as .menu_block', 'border-color'


        array(
            '.menu_block',
            'border-color',
            '#ddd'
        ),
        // same as #bottom_header a.menu:hover', 'background-color'
        array(
            '.single_column',
            'border-color',
            '#ddd'
        ),
        // same as #bottom_header a.menu:hover', 'background-color'


        array(
            'footer',
            'color',
            '#d7d7d7'
        ),
        array(
            'footer',
            'background-color',
            '#303030'
        ),
        array(
            'footer .copyright',
            'color',
            '#d7d7d7'
        ),
        array(
            'footer .copyright',
            'background-color',
            '#202020'
        ),


        array(
            '.vertical-menu a',
            'color',
            '#555'
        ),
        array(
            '.vertical-menu a',
            'background-color',
            '#fff'
        ),
        array(
            '.vertical-menu a:hover',
            'color',
            '#555'
        ),
        array(
            '.vertical-menu a:hover',
            'background-color',
            '#ccc'
        ),


        array(
            '.menu_block',
            'background-color',
            '#fff'
        ),
        array(
            '.menu_block',
            'color',
            '#727272'
        ),


        array(
            '#bottom_header .button',
            'color',
            '#fff'
        ),
        array(
            '#bottom_header .button',
            'background-color',
            '#111'
        ),
        array(
            '#bottom_header .button:hover',
            'color',
            '#fff'
        ),
        array(
            '#bottom_header .button:hover',
            'background-color',
            '#111'
        ),


        array(
            '.product_wrap',
            'color',
            '#727272'
        ),

        array(
            '.product_block',
            'border-color',
            '#ccc'
        ),
        array(
            '.product_block',
            'border-style',
            'solid'
        ),
        array(
            '.product_block',
            'border-left-width',
            '1px'
        ),
        array(
            '.product_block',
            'border-right-width',
            '1px'
        ),
        array(
            '.product_block',
            'border-top-width',
            '1px'
        ),
        array(
            '.product_block',
            'border-bottom-width',
            '0px'
        ),

        array(
            '.empty',
            'background-color',
            'rgba(255, 165, 0, 0.9)'
        ),
        array(
            '.empty',
            'color',
            'white'
        ),
        array(
            '.empty:hover',
            'background-color',
            'red'
        ),
        array(
            '.empty:hover',
            'color',
            'white'
        ),


        array(
            '.add_to_portfolio',
            'color',
            '#727272'
        ),
        array(
            '.add_to_portfolio:hover',
            'color',
            '#727272'
        ),

        array(
            '.remove_from_portfolio',
            'background-color',
            'rgba(255, 165, 0, 0.9)'
        ),
        array(
            '.remove_from_portfolio',
            'color',
            'white'
        ),
        array(
            '.remove_from_portfolio:hover',
            'background-color',
            'orange'
        ),
        array(
            '.remove_from_portfolio:hover',
            'color',
            'white'
        ),


        array(
            '.ordered',
            'background-color',
            'rgba(255, 165, 0, 0.9)'
        ),
        array(
            '.ordered',
            'color',
            'white'
        ),
        array(
            '.ordered:hover',
            'background-color',
            'orange'
        ),
        array(
            '.ordered:hover',
            'color',
            'white'
        ),

        array(
            '.out_of_stock',
            'background-color',
            'rgba(204, 204, 204, 1)'
        ),
        array(
            '.out_of_stock',
            'color',
            'white'
        ),
        array(
            '.out_of_stock:hover',
            'background-color',
            '#ccc'
        ),
        array(
            '.out_of_stock:hover',
            'color',
            'white'
        ),


        array(
            '.launching_soon',
            'background-color',
            'rgba(147, 196, 125, 0.9)'
        ),
        array(
            '.launching_soon',
            'color',
            'white'
        ),
        array(
            '.launching_soon:hover',
            'background-color',
            'rgba(147, 196, 125, 1)'
        ),
        array(
            '.launching_soon:hover',
            'color',
            'white'
        ),


        array(
            '.product_price',
            'color',
            '#236E4B'
        ),

        array(
            '.sky-form .button',
            'background-color',
            '#2da5da'
        ),
        array(
            '.sky-form .button',
            'color',
            '#fff'
        ),


    );

    $styles=array();
    foreach($styles_data as $value){
        $styles[$value[0].' '.$value[1]]=$value;
    }

    return $styles;

}