<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 May 2017 at 11:30:14 GMT-5, CdMx, Mexico
 Copyright (c) 2016, Inikoo

 Version 3

*/





if($state['_object']->get('Webpage Template Filename')=='products_showcase'  or  $state['_object']->get('Webpage Template Filename')=='categories_showcase'){

    $state['_object']=get_object('Category',$state['_object']->get('Webpage Scope Key'));

    include 'category.webpage.preview.tab.php';

}elseif($state['_object']->get('Webpage Template Filename')=='product'  ){

    $state['_object']=get_object('Product',$state['_object']->get('Webpage Scope Key'));

    include 'product.webpage.preview.tab.php';

}else {



    $webpage = $state['_object'];


    if (!$webpage->id) {
        $html = '<div style="padding:40px">'.'Webpage not found'.'</div>';

        return;
    }

    $website = new Website($webpage->get('Webpage Website Key'));

    $theme = $website->get('Website Theme');

    $smarty->assign('theme', $theme);
    $smarty->assign('webpage', $webpage);
    $smarty->assign('content', $webpage->get('Content Data'));
    $smarty->assign('metadata', $webpage->get('Scope MetaData'));


    $control_template = $theme.'/control.'.$webpage->get('Webpage Template Filename').'.'.$theme.'.tpl';

    if (file_exists('templates/'.$control_template)) {
        $smarty->assign('control_template', $control_template);

    }

    $html = $smarty->fetch('webpage_preview.tpl');

}


?>
