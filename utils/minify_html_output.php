<?php
/*
 File: login.php


 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  03 June 2020  14:13::19  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

function minify_html_output($tpl_output, Smarty_Internal_Template $template){
    include_once 'utils/html_minifier.php';

    $minifier = new TinyHtmlMinifier([]);
    $tpl_output     = $minifier->minify($tpl_output);
    $etag = md5($tpl_output);
    header('ETag: '.$etag);
    return $tpl_output;

}