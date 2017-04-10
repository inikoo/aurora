<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 January 2017 at 12:48:18 CET,  Mijas Costa, Spain

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_website_section_items($db, $section_data) {

    $section_key = $section_data['key'];

    $panels = $section_data['panels'];



    $sql = sprintf(
        "SELECT `Product Category Public`,`Webpage State`,`Category Webpage Index Key`,`Page Code`,`Category Webpage Index Category Webpage Key`,`Category Webpage Index Subject Type`,`Category Webpage Index Stack`,`Product Category Active Products`,`Category Webpage Index Category Key`,`Category Code`,`Category Webpage Index Content Data`,`Category Webpage Index Key` 
            FROM `Category Webpage Index` CWI
            LEFT JOIN `Product Category Dimension` P ON (`Category Webpage Index Category Key`=P.`Product Category Key`)   
            LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=`Category Webpage Index Category Key`)     
            
            LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Category Webpage Index Category Webpage Key`)     

            
            WHERE  `Category Webpage Index Section Key`=%d AND (`Product Category Active Products`+`Product Category Discontinuing Products`+`Product Category In Process Products`)>0  AND `Product Category Public`='Yes' and `Webpage State`='Online' ORDER BY  ifnull(`Category Webpage Index Stack`,99999999)",
        $section_key


    );


  // print $sql;


    $categories = array();

    $stack_index          = 0;
    $category_stack_index = 1;
    if ($result = $db->query($sql)) {

        foreach ($result as $row) {

            //print_r($row);
            if (isset($panels[$stack_index])) {


                if (isset($page_breaks[$category_stack_index])) {
                    $categories[] = array(
                        'type'                 => 'panel',
                        'category_stack_index' => $category_stack_index,
                        'data'                 => $page_breaks[$category_stack_index]
                    );


                    unset($page_breaks[$category_stack_index]);
                    //  $category_stack_index++;
                    //  $stack_index++;
                }

                $categories[] = array(
                    'type'                 => 'panel',
                    'category_stack_index' => $category_stack_index,
                    'data'                 => $panels[$stack_index]
                );


                $size = floatval($panels[$stack_index]['size']);


                unset($panels[$stack_index]);
                $stack_index += $size;


                list($stack_index, $categories) = get_next_panel($stack_index, $categories, $panels);

            }


            if ($row['Category Webpage Index Content Data'] == '') {
                $item_content_data = array(
                    'header_text' => '',
                    'footer_text' => ''

                );
            } else {
                $item_content_data = json_decode($row['Category Webpage Index Content Data'], true);

            }



            if (isset($page_breaks[$row['Category Webpage Index Stack']])) {
                $categories[] = array(
                    'type'                 => 'panel',
                    'category_stack_index' => $row['Category Webpage Index Stack'],
                    'data'                 => $page_breaks[$row['Category Webpage Index Stack']]
                );
                unset($page_breaks[$row['Category Webpage Index Stack']]);
                // $category_stack_index++;
                //  $stack_index++;
            }


            $categories[]         = array(
                'type'                 => 'category',
                'category_key'         => $row['Category Webpage Index Category Key'],
                'category_code'        => $row['Category Code'],
                'number_products'      => $row['Product Category Active Products'],
                'index_key'            => $row['Category Webpage Index Key'],
                'webpage_key'          => $row['Category Webpage Index Category Webpage Key'],
                'webpage_code'         => $row['Page Code'],
                'header_text'          => (isset($item_content_data['header_text']) ? $item_content_data['header_text'] : ''),
                'footer_text'          => (isset($item_content_data['footer_text']) ? $item_content_data['footer_text'] : ''),
                'image_src'            => $item_content_data['image_src'],
                'category_stack_index' => $row['Category Webpage Index Stack'],
                'item_type'            => $row['Category Webpage Index Subject Type'],
                

            );
            $category_stack_index = $row['Category Webpage Index Stack'];
            $stack_index++;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    $panel_rows          = array();
    $max_row_free_slots  = array();
    $max_cell_free_slots = array();

    $row_index = -1;

    $stack_index = -1;

    foreach ($categories as $key => $item) {


        if ($item['type'] == 'category') {
            $stack_index++;
        } else {
            $stack_index += floatval($item['data']['size']);
        }
        $categories[$key]['stack_index'] = $stack_index;


        $current_row = floor($stack_index / 4);
        if ($row_index != $current_row) {
            //       print "- $current_row \n";
            $row_index          = $current_row;
            $max_free_slots     = 0;
            $current_free_slots = 0;


        }

        if ($item['type'] == 'category') {
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


        if ($stack_index % 4 == 1 and $item['type'] != 'category' and $categories[$stack_index - 1]['type'] == 'category') {
            $max_cell_free_slots[$stack_index - 1] = 1;

        }


    }


    $stack_index = -1;
    foreach ($categories as $key => $item) {

        if ($item['type'] == 'category') {
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
        $categories[$key]['data']['panels_in_row']  = $panels_in_row;
        $categories[$key]['data']['max_free_slots'] = $max_row_free_slots[$current_row];
        if (isset($max_cell_free_slots[$stack_index])) {
            $categories[$stack_index]['data']['max_free_slots'] = $max_cell_free_slots[$stack_index];
        }


    }


    return $categories;

}


function get_next_panel($stack_index, $products, $panels) {

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

    return array(
        $stack_index,
        $products
    );

}

function get_products_html($content_data, $webpage, $smarty, $db) {

    include_once 'class.Public_Product.php';
    include_once 'class.Public_Category.php';

    $public_category = new Public_Category($webpage->get('Webpage Scope Key'));
    $public_category->load_webpage();


    if (isset($content_data['panels'])) {
        $panels = $content_data['panels'];
    } else {
        $panels = array();
    }

    ksort($panels);
    $products = array();

    $sql = sprintf(
        "SELECT `Product Category Index Key`,`Product Category Index Content Data`,`Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
        $public_category->id
    );
    //697

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

    //  print_r($products);


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


    $smarty->assign('products', $products);
    $smarty->assign('content_data', $content_data);

    $smarty->assign('category', $public_category);


    return $smarty->fetch('webpage.preview.products_showcase.products.tpl');


}


?>
