<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 May 2017 at 11:30:14 GMT-5, CdMx, Mexico
 Copyright (c) 2016, Inikoo

 Version 3

*/

$smarty->assign('webpage',$state['_object']);


$webpage = $state['_object'];


if (!$webpage->id) {
    $html = '<div style="padding:40px">'.'Webpage not found'.'</div>';

    return;
}


$theme = 'theme_1';

$smarty->assign('theme', $theme);
$smarty->assign('webpage', $webpage);
$smarty->assign('content', $webpage->get('Content Data'));


$control_template=$theme.'/control.'.$webpage->get('Webpage Template Filename').'.'.$theme.'.tpl';

if( file_exists('templates/'.$control_template) ){
    $smarty->assign('control_template', $control_template);

}

$html = $smarty->fetch('webpage_preview.tpl');



?>
