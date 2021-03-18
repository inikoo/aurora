<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 23:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

function get_dashboard_navigation($data, $smarty, $user, $db, $account) {


    $left_buttons = array();

    $right_buttons = array();


    $corporate_account = $user->settings('corporate_accounts');
    if (is_array($corporate_account) and count($corporate_account) > 1) {
        $right_buttons[] = array(
            'icon'      => 'tachometer-alt',
            'reference' => 'dashboard/corporate',
            'class'     => 'text width_250',
            'text'      => _('Corporate'),
            'title'     => _('Corporate dashboard'),
            'id'        => "corporate_dashboard"
        );
    }
    $sections = get_sections('dashboard');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Dashboard').' <span class="id">'.$account->get('Name').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_corporate_dashboard_navigation($data, $smarty, $user, $db, $account) {


    $left_buttons = array();

    $right_buttons = array();


    $corporate_account = $user->settings('corporate_accounts');
    if (is_array($corporate_account) and count($corporate_account) > 1) {
        $right_buttons[] = array(
            'icon'      => 'tachometer-alt',
            'reference' => 'dashboard',
            'class'     => 'text width_250',
            'text'      => $account->get('Name'),
            'title'         => _('Dashboard').' '.$account->get('Name'),
            'id'        => "dashboard"
        );
    }
    $sections = get_sections('dashboard');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Corporate Dashboard'),
        'search'        => array(
            'show'        => false,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


