<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 November 2016 at 17:17:11 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once('class.Public_Category.php');
include_once('class.Public_Product.php');

$logged = true;


$category = $state['_object'];
$webpage  = $category->get_webpage();



$smarty->assign('webpage', $webpage);



if (!$webpage->id) {
    $html = '<div style="padding:40px">'._("This category don't have webpage").'</div>';

    return;
}

// todo migrate to new webpage & webpage version classes


switch ($webpage->get('Page Store Content Template Filename')) {
    case 'products_showcase':

        // todo remove this when all descriptions are moved inside webpage content data

        if ($webpage->id and $webpage->get('Content Data') == '') {
            $title = $category->get('Label');
            if ($title == '') {
                $title = $category->get('Code');
            }
            if ($title == '') {
                $title = _('Title');
            }

            $description = $category->get('Product Category Description');
            if ($description == '') {
                $description = $category->get('Label');
            }
            if ($description == '') {
                $description = $category->get('Code');
            }
            if ($description == '') {
                $description = _('Description');
            }


            $image_src = $category->get('Image');

            $content_data = array(
                'description_block' => array(

                    'blocks' => array(

                        'webpage_content_header_image' => array(
                            'type'      => 'image',
                            'image_src' => $image_src

                        ),

                        'webpage_content_header_text' => array(
                            'type'    => 'text',
                            'content' => sprintf('<h1 class="description_title">%s</h1><div class="description">%s</div>', $title, $description)

                        )

                    )
                )

            );

            //print_r($content_data);
            $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

        }


        $public_category = new Public_Category($category->id);
        $public_category->load_webpage();


        $html = '';

        if ($public_category->get('Scope') == 'Product') {

            $category->create_stack_index(true);

            $products = array();

            $sql = sprintf(
                "SELECT `Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
                $public_category->id
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $products[] = new Public_Product($row['Product ID']);
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            $smarty->assign('products', $products);
            $smarty->assign('category', $public_category);


            $html = $smarty->fetch('category.webpage.preview.tpl');

        }

        break;
    default:

        $html = '<div style="padding:40px">'._("There is no preview for this template").'</div>';

}


?>
