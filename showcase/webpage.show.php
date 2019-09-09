<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 10:22:57 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_webpage_showcase($data, $smarty) {


    $webpage = $data['_object'];


    //   print_r($webpage->get('Content Data'));

    if (!$webpage->id) {
        return "";
    }


    if ($data['_object']->deleted) {
        $template = 'showcase/deleted_webpage.tpl';

        $smarty->assign('webpage', $webpage);

    } else {

        $website = get_object('Website', $webpage->get('Webpage Website Key'));


        switch ($webpage->get('Webpage Scope')) {
            case 'Product':

                $product = get_object('Product', $webpage->get('Webpage Scope Key'));
                $smarty->assign('product', $product);

                $template = 'showcase/webpage.product.tpl';
                break;
            case 'Category Products':

                $category = get_object('Category', $webpage->get('Webpage Scope Key'));
                $smarty->assign('category', $category);

                $template = 'showcase/webpage.category_products.tpl';
                break;
            case 'Category Categories':
                $category = get_object('Category', $webpage->get('Webpage Scope Key'));
                $smarty->assign('category', $category);

                $template = 'showcase/webpage.category_categories.tpl';

                break;
            default:
                $template = 'showcase/webpage.tpl';
        }
        $smarty->assign('webpage', $webpage);
        $smarty->assign('website', $website);
    }


    return $smarty->fetch($template);


}


