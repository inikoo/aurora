<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 November 2015 at 15:21:08 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_help_navigation($data) {
    global $smarty;


    $sections                      = get_sections('help', '');
    $sections['users']['selected'] = true;
    $title                         = _('Help');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => array(),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search help')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}


?>
