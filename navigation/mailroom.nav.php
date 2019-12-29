<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  16:01::25  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

/**
 * @param $data
 * @param $smarty \Smarty
 *
 * @return mixed
 */
function get_notifications_server_navigation($data, $smarty) {


    $branch = array(
        array(
            'label'     => '',
            'icon'      => 'home',
            'reference' => ''
        )
    );


    $left_buttons = array();

    $title = _("Customers notifications");


    $right_buttons = array();
    $sections      = get_sections('mailroom_server');
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(
        'branch'         => $branch,
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _(
                'Search mailroom'
            )
        )

    );
    $smarty->assign('content', $_content);
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}
