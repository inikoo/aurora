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
include_once('utils/website_functions.php');

$logged = true;


$category = $state['_object'];
$webpage  = $category->get_webpage();




$smarty->assign('webpage', $webpage);


if (!$webpage->id) {
    $html = '<div style="padding:40px">'._("This category don't have webpage").'</div>';

    return;
}

// todo migrate to new webpage & webpage version classes


$public_category = new Public_Category($category->id);
$public_category->load_webpage();
$content_data = $category->webpage->get('Content Data');


//print_r($content_data);

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
                    'class' => '',

                    'blocks' => array(

                        'webpage_content_header_image' => array(
                            'type'      => 'image',
                            'image_src' => $image_src,
                            'caption'   => '',
                            'class'     => ''

                        ),

                        'webpage_content_header_text' => array(
                            'class'   => '',
                            'type'    => 'text',
                            'content' => sprintf('<h1 class="description_title">%s</h1><div class="description">%s</div>', $title, $description)

                        )

                    )
                )

            );

            //print_r($content_data);
            $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

        }

        $category->create_stack_index(true);


        $html = '';


        if (isset($content_data['panels'])) {
            $panels = $content_data['panels'];
        } else {
            $panels = array();
        }

        // print_r($panels);

        ksort($panels);
        $products = array();

        $sql = sprintf(
            "SELECT `Product Category Index Key`,`Product Category Index Content Data`,`Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
            $public_category->id
        );


        $stack_index         = 0;
        $product_stack_index = 0;
        if ($result = $db->query($sql)) {

            foreach ($result as $row) {


                if (isset($panels[$stack_index])) {
                    $products[] = array(
                        'type' => 'panel',
                        'data' => $panels[$stack_index]
                    );

                    $size = floatval($panels[$stack_index]['size']);


                    unset($panels[$stack_index]);
                    $stack_index += $size;

                    list($stack_index, $products) = get_next_panel($stack_index, $products, $panels);

                }


                if ($row['Product Category Index Content Data'] == '') {
                    $product_content_data = array('header_text' => '');
                } else {
                    $product_content_data = json_decode($row['Product Category Index Content Data'], true);

                }

                $products[] = array(
                    'type'                => 'product',
                    'object'              => new Public_Product($row['Product ID']),
                    'index_key'           => $row['Product Category Index Key'],
                    'header_text'         => (isset($product_content_data['header_text']) ? $product_content_data['header_text'] : ''),
                    'product_stack_index' => $product_stack_index
                );
                $product_stack_index++;
                $stack_index++;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        // print_r($products);


        $panel_rows          = array();
        $max_row_free_slots  = array();
        $max_cell_free_slots = array();

        $row_index = -1;

        $stack_index = -1;

        foreach ($products as $key => $item) {


            if ($item['type'] == 'product') {
                $stack_index++;
            } else {
                $stack_index += floatval($item['data']['size']);
            }
            $products[$key]['stack_index'] = $stack_index;


            $current_row = floor($stack_index / 4);
            if ($row_index != $current_row) {
                //       print "- $current_row \n";
                $row_index          = $current_row;
                $max_free_slots     = 0;
                $current_free_slots = 0;


            }

            if ($item['type'] == 'product') {
                $current_free_slots++;
                if ($current_free_slots > $max_free_slots) {
                    $max_free_slots = $current_free_slots;
                }
            } else {

                //$key+=floatval($item['data']['size'])-1;

                if ($current_free_slots > $max_free_slots) {
                    $max_free_slots = $current_free_slots;
                }
                $current_free_slots = 0;
            }


            //      print "$stack_index ".($stack_index%4)." ".floor($stack_index/4)." | $current_free_slots $max_free_slots  \n";
            if ($item['type'] == 'panel') {


                if (isset($panel_rows[floor($stack_index / 4)])) {
                    $panel_rows[floor($stack_index / 4)] += floatval($item['data']['size']);
                } else {
                    $panel_rows[floor($stack_index / 4)] = floatval($item['data']['size']);
                }

            }

            $max_row_free_slots[$current_row] = $max_free_slots;


            if ($stack_index % 4 == 1 and $item['type'] != 'product' and $products[$stack_index - 1]['type'] == 'product') {
                $max_cell_free_slots[$stack_index - 1] = 1;

            }


        }

        //   print_r(  $max_row_free_slots);
        //    print_r(  $max_cell_free_slots);

        $stack_index = -1;
        foreach ($products as $key => $item) {

            if ($item['type'] == 'product') {
                $stack_index++;
            } else {
                $stack_index += floatval($item['data']['size']);
            }

            $current_row = floor($stack_index / 4);
            if (isset($panel_rows[$current_row])) {
                $panels_in_row = $panel_rows[$current_row];
            } else {
                $panels_in_row = 0;
            }
            $products[$key]['data']['panels_in_row']  = $panels_in_row;
            $products[$key]['data']['max_free_slots'] = $max_row_free_slots[$current_row];
            if (isset($max_cell_free_slots[$stack_index])) {
                $products[$stack_index]['data']['max_free_slots'] = $max_cell_free_slots[$stack_index];
            }


        }
        // print_r($panel_rows);
        // print_r($products);


        $related_products = array();

        $sql = sprintf(
            "SELECT `Webpage Related Product Key`,`Webpage Related Product Product ID`,`Webpage Related Product Content Data`  FROM `Webpage Related Product Bridge` B  LEFT JOIN `Product Dimension` P ON (`Webpage Related Product Product ID`=P.`Product ID`)  WHERE  `Webpage Related Product Page Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Webpage Related Product Order`",
            $webpage->id
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Webpage Related Product Content Data'] == '') {
                    $product_content_data = array('header_text' => '');
                } else {
                    $product_content_data = json_decode($row['Webpage Related Product Content Data'], true);

                }

                $related_products[] = array(
                    'header_text' => (isset($product_content_data['header_text']) ? $product_content_data['header_text'] : ''),
                    'object'      => new Public_Product($row['Webpage Related Product Product ID']),
                    'index_key'   => $row['Webpage Related Product Key'],


                );
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        //  print_r($products);

        $smarty->assign('products', $products);
        $smarty->assign('related_products', $related_products);
        $smarty->assign('content_data', $content_data);


        $smarty->assign('category', $public_category);


        $html = $smarty->fetch('webpage.preview.products_showcase.tpl');


        //   $product_rows=ceil(count($products)/4);
        //  $smarty->assign('product_rows', ceil(count($products)/4));

        //   print $number_products/4;


        break;

    case 'categories_showcase':


        // todo remove this when all descriptions are moved inside webpage content data




        if (  ( $webpage->id and $webpage->get('Content Data') == '' )  or $webpage->id==73  ) {


            $sql = sprintf(
                'DELETE FROM  `Webpage Section Dimension` WHERE `Webpage Section Webpage Key`=%d  ', $webpage->id

            );

            $db->exec($sql);

            $sql = sprintf(
                'DELETE FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d  ', $webpage->id

            );

            $db->exec($sql);



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
                    'class' => '',

                    'blocks' => array(

                        'webpage_content_header_image' => array(
                            'type'      => 'image',
                            'image_src' => $image_src,
                            'caption'   => '',
                            'class'     => ''

                        ),

                        'webpage_content_header_text' => array(
                            'class'   => '',
                            'type'    => 'text',
                            'content' => sprintf('<h1 class="description_title">%s</h1><div class="description">%s</div>', $title, $description)

                        )

                    )
                ),
                'sections'          => array()

            );

            $section = array(
                'type'     => 'anchor',
                'title'    => '',
                'subtitle' => '',
                'panels'   => array()
            );


            $sql = sprintf(
                'INSERT INTO `Webpage Section Dimension` (`Webpage Section Webpage Key`,`Webpage Section Webpage Stack Index`,`Webpage Section Data`) VALUES (%d,%d,%s) ', $webpage->id, 0,
                prepare_mysql(json_encode($section))

            );

          //  print $sql;

            $db->exec($sql);

            $section['key'] = $db->lastInsertId();

            $content_data['sections'][] = $section;
            $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');




            $category->create_stack_index(true);
            $content_data = $category->webpage->get('Content Data');


            /*
                        $sql = sprintf(
                            "SELECT `Subject Key`,`Category Webpage Index Key`,`Category Webpage Index Content Data`,`Category Webpage Index Key`,Cat.`Category Key` FROM `Category Bridge` B  LEFT JOIN `Product Category Dimension` P ON (B.`Subject Key`=P.`Product Category Key`)   LEFT JOIN `Category Webpage Index`    ON (`Category Webpage Index Category Key`=`Subject Key`)  LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=B.`Subject Key`)  WHERE  B.`Category Key`=%d  AND `Product Category Public`='Yes' ",
                            $public_category->id

                        );


                        if ($result = $db->query($sql)) {

                            foreach ($result as $row) {

                                $_category = new Public_Category($row['Category Key']);

                                if ($row['Category Webpage Index Content Data'] == '') {

                                    $data = array(
                                        'header_text' => $_category->get('Label'),
                                        'image_src'   => $_category->get('Image'),
                                        'footer_text' => $_category->get('Code'),
                                    );

                                    $sql = sprintf(
                                        'UPDATE `Category Webpage Index` SET `Category Webpage Index Section Key`=%d,  `Category Webpage Index Content Data`=%s WHERE `Category Webpage Index Key`=%d ',
                                        $section['key'], prepare_mysql(json_encode($data)), $row['Category Webpage Index Key']
                                    );

                                    $db->exec($sql);


                                }

                            }
                        }

                        $sections = array();


                        foreach ($content_data['sections'] as $section_stack_index => $section_data) {

                            $content_data['sections'][$section_stack_index]['items']= get_website_section_items($db,$section_data);

                        }


                        $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');
            */


        }

    //    $category->create_stack_index($force_reindex = false);




        $smarty->assign('sections', $content_data['sections']);

        $smarty->assign('content_data', $content_data);

        $smarty->assign('category', $public_category);

        $smarty->assign('store_key', $category->get('Category Store Key'));



        $html = $smarty->fetch('webpage.preview.categories_showcase.tpl');

        break;
    default:

        $html = '<div style="padding:40px">'._("There is no preview for this template").'</div>';

}


?>
