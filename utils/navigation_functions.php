<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 11 Jul 2021 03:12:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_navigation_buttons($navigation_data, $up_button, $link_format): array {

    $prev_key   = $navigation_data[0];
    $prev_title = $navigation_data[1];
    $next_key   = $navigation_data[2];
    $next_title = $navigation_data[3];


    $left_buttons = [];


    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => sprintf($link_format, $prev_key)
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-left disabled',
            'title' => '',
            'url'   => ''
        );

    }
    $left_buttons[] = $up_button;


    if ($next_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => sprintf($link_format, $next_key)
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }

    return $left_buttons;
}