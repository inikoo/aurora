<?php
/*
 File: login.php


 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  03 June 2020  14:13::19  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

function etag_output($tpl_output, Smarty_Internal_Template $template){


    $etag = 'W/"'.md5($tpl_output).'"';
    header('ETag: '.$etag);
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && strpos($_SERVER['HTTP_IF_NONE_MATCH'], $etag) !== FALSE)
    {
        header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
        return '';
    }
    return $tpl_output;

}