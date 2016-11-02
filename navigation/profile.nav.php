<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2015 at 22:44:07 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_profile_navigation($data) {
    global $smarty;


    $title = _('My profile');

    $_content = array(
        'sections_class' => '',
        'sections'       => array(),
        'left_buttons'   => array(),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show' => false,
            'placeholder' => ''
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}


?>
