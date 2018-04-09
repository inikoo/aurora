<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 March 2018 at 14:58:36 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


migrate_families();
//migrate_departments();
//exit;

//2730

function migrate_families() {

    global $db;
    $left_offset = 158;

    $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE `Webpage Template Filename`="products_showcase"  ');
    $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE  `Page Key`=2562 ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row3) {


           //   print_r($row3);

            $webpage = get_object('Webpage', $row3['Page Key']);


            $_content_data = $webpage->get('Content Data');


            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            /* revert process
            $webpage->update(
                array(
                    'Page Store Content Data'   => json_encode($content_data),
                    'Webpage Template Filename' => 'categories_showcase'
                ), 'no_history'
            );
            exit;
            */
            $css = $webpage->get('Page Store CSS');
            // print_r($webpage);;
            // exit;

            $_height = 0;

            $images = array();
            $texts  = array();
            foreach ($content_data['description_block']['blocks'] as $block_id => $block) {

                if ($block['type'] == 'image') {

                    if (preg_match('/id=(\d+)/', $block['image_src'], $matches)) {
                        $image_key = $matches[1];


                        $sql = sprintf(
                            "SELECT `Image Key` FROM `Image Dimension` WHERE `Image Key`=%d", $image_key
                        );
                        if ($result2 = $db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $image_key = $row2['Image Key'];
                            } else {
                                $image_key = 0;
                            }
                        } else {
                            print_r($error_info = $db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                    } else {
                        $image_key = 'other';
                    }

                    if (preg_match('/#'.$block_id.'\{([a-zA-Z0-9.:\s;-]+)/', $css, $matches)) {
                        // print_r($matches);
                        $_css = trim($matches[1]);

                        $_css = preg_replace('/margin-left/', '', $_css);

                        if (preg_match('/top\:([0-9.]+)/', $_css, $_matches)) {
                            $top = $_matches[1];
                        } else {
                            $top = 0;
                        }
                        if (preg_match('/left\:([0-9.]+)/', $_css, $_matches)) {
                            $left = $_matches[1];
                        } else {
                            $left = 0;
                        }
                        if (preg_match('/width\:([0-9.]+)/', $_css, $_matches)) {
                            $width = $_matches[1];
                        } else {
                            $width = 0;
                        }
                        if (preg_match('/height\:([0-9.]+)/', $_css, $_matches)) {
                            $height = $_matches[1];
                        } else {
                            $height = 0;
                        }


                    } else {
                        $top    = 0;
                        $left   = 0;
                        $width  = 0;
                        $height = 0;

                    }

                    //print_r($block);

                    if ($height > 0 and $image_key) {
                        $images[] = array(
                            'id'     => $block_id,
                            'src'    => $block['image_src'],
                            'title'  => (!empty($block['caption']) ? $block['caption'] : ''),
                            'top'    => $top,
                            'left'   => $left_offset + $left,
                            'width'  => $width,
                            'height' => $height,
                        );
                        if (($height + $top) > $_height) {
                            $_height = $height + $top;
                        }

                    }


                } elseif ($block['type'] == 'text') {


                    if (preg_match('/#'.$block_id.'\{([a-zA-Z0-9.:\s;-]+)/', $css, $matches)) {
                        // print_r($matches);
                        $_css = trim($matches[1]);

                        $_css = preg_replace('/margin-left/', '', $_css);

                        if (preg_match('/top\:([0-9.]+)/', $_css, $_matches)) {
                            $top = $_matches[1];
                        } else {
                            $top = 0;
                        }
                        if (preg_match('/left\:([0-9.]+)/', $_css, $_matches)) {
                            $left = $_matches[1];
                        } else {
                            $left = 0;
                        }
                        if (preg_match('/width\:([0-9.]+)/', $_css, $_matches)) {
                            $width = $_matches[1];
                        } else {
                            $width = 0;
                        }
                        if (preg_match('/height\:([0-9.]+)/', $_css, $_matches)) {
                            $height = $_matches[1];
                        } else {
                            $height = 0;
                        }


                    } else {
                        $top    = 0;
                        $left   = 0;
                        $width  = 0;
                        $height = 0;

                    }


                    if ($height > 0) {
                        $texts[] = array(
                            'id'     => $block_id,
                            'text'   => $block['content'],
                            'top'    => $top,
                            'left'   => $left_offset + $left,
                            'width'  => $width,
                            'height' => $height,


                        );
                        if (($height + $top) > $_height) {
                            $_height = $height + $top;
                        }

                    }


                }
            }
            $matches = 200;
            if (preg_match('/\#description_block\{ height\:([0-9.]+)px\}/', $css, $matches)) {

                $blackboard_height = $matches[1];
            }


           // if ($_height > $blackboard_height) {
           //     $blackboard_height = $_height;
           // }


            //  print " $blackboard_height";

            $items = array();


            $sql = sprintf(
                "SELECT `Product Category Index Key`,`Product Category Index Content Data`,`Product Category Index Product ID`,`Product Category Index Category Key`,`Product Category Index Stack`, P.`Product ID`,`Product Code`,`Product Web State` 
                  FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  
                  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
                $row3['Webpage Scope Key']
            );

            $has_header = false;

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $product = get_object('Public_Product', $row['Product ID']);
                    $product->load_webpage();

                    $header_text = '';
                    if ($row['Product Category Index Content Data'] != '') {

                        $product_content_data = json_decode($row['Product Category Index Content Data'], true);

                        // print_r($product_content_data);
                        if (isset($product_content_data['header_text'])) {
                            $header_text = $product_content_data['header_text'];
                        }


                    }

                    if ($header_text != '') {
                        $has_header = true;

                    }

                    $header_text=mb_convert_encoding($header_text, 'UTF-8', 'UTF-8');


                    $items[] = array(
                        'type'                 => 'product',
                        'product_id'           => $row['Product ID'],
                        'web_state'            => $row['Product Web State'],
                        'price'                => $product->get('Price'),
                        'rrp'                  => $product->get('RRP'),
                        'header_text'          => $header_text,
                        'code'                 => $product->get('Code'),
                        'name'                 => $product->get('Name'),
                        'link'                 => $product->webpage->get('URL'),
                        'webpage_code'         => $product->webpage->get('Webpage Code'),
                        'webpage_key'          => $product->webpage->id,
                        'image_src'            => $product->get('Image'),
                        'image_mobile_website' => '',
                        'image_website'        => '',
                        'out_of_stock_class'   => $product->get('Out of Stock Class'),
                        'out_of_stock_label'   => $product->get('Out of Stock Label'),
                        'sort_code'            => $product->get('Code File As'),
                        'sort_name'            => mb_strtolower($product->get('Product Name')),


                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            if (isset($content_data['panels'])) {

                //  print_r($content_data['panels']);

                foreach ($content_data['panels'] as $panel_index => $panel_data) {
                    if ($panel_data['type'] == 'code' and $panel_data['content'] == '') {
                    } else {
                        if ($panel_data['type'] == 'code' and $panel_data['size'] == '2x') {
                            // suspect video

                            if (preg_match('/youtube\.com\/embed\/([0-9a-z]+)/i', $panel_data['content'], $matches)) {

                                //  print_r($matches);

                                $item = array(
                                    'type'       => 'video',
                                    'video_id'   => $matches[1],
                                    'size_class' => 'panel_2',
                                );


                                if (count($items) > $panel_index) {
                                    array_splice($items, $panel_index, 0, '');
                                    $items[$panel_index] = $item;
                                } else {
                                    $items[] = $item;
                                }

                            }

                        } elseif ($panel_data['type'] == 'text') {


                            $item = array(
                                'type'       => 'text',
                                'text'       => $panel_data['content'],
                                'size_class' => 'panel_'.preg_replace('/x/', '', $panel_data['size']),
                                'padding'    => 20
                            );


                            if (count($items) > $panel_index) {
                                array_splice($items, $panel_index, 0, '');
                                $items[$panel_index] = $item;
                            } else {
                                $items[] = $item;
                            }


                        } elseif ($panel_data['type'] == 'image') {
                            $item = array(
                                'type'       => 'image',
                                'image_src'  => $panel_data['image_src'],
                                'title'      => $panel_data['caption'],
                                'link'       => $panel_data['link'],
                                'size_class' => 'panel_'.preg_replace('/x/', '', $panel_data['size']),


                            );


                            if (count($items) > $panel_index) {
                                array_splice($items, $panel_index, 0, '');
                                $items[$panel_index] = $item;
                            } else {
                                $items[] = $item;
                            }


                        } else {

                            print_r($content_data['panels']);
                            exit('unknown panel');
                        }
                    }


                }


            }


            $related_products = array();
            $sql              = sprintf(
                "SELECT `Product ID`,`Webpage Related Product Product Page Key` ,`Product Web State` FROM  `Webpage Related Product Bridge`  LEFT JOIN `Product Dimension` P ON (`Webpage Related Product Product ID`=P.`Product ID`)   WHERE `Webpage Related Product Page Key`=%d AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Webpage Related Product Order` ",
                $webpage->id
            );

            if ($result4 = $db->query($sql)) {
                foreach ($result4 as $row4) {

                    $product = get_object('Public_Product', $row4['Product ID']);

                    if ($product->id) {
                        $product->load_webpage();
                        $related_products[] = array(
                            'type'                 => 'product',
                            'product_id'           => $row4['Product ID'],
                            'web_state'            => $row4['Product Web State'],
                            'price'                => $product->get('Price'),
                            'rrp'                  => $product->get('RRP'),
                            'header_text'          => $header_text,
                            'code'                 => $product->get('Code'),
                            'name'                 => $product->get('Name'),
                            'link'                 => $product->webpage->get('URL'),
                            'webpage_code'         => $product->webpage->get('Webpage Code'),
                            'webpage_key'          => $product->webpage->id,
                            'image_src'            => $product->get('Image'),
                            'image_mobile_website' => '',
                            'image_website'        => '',
                            'out_of_stock_class'   => $product->get('Out of Stock Class'),
                            'out_of_stock_label'   => $product->get('Out of Stock Label'),
                            'sort_code'            => $product->get('Code File As'),
                            'sort_name'            => mb_strtolower($product->get('Product Name')),


                        );

                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            $see_also = array();
            $sql      = sprintf(
                "SELECT `Page Store See Also Key`,`Correlation Type`,`Correlation Value` FROM  `Page Store See Also Bridge` WHERE `Page Store Key`=%d ORDER BY `Correlation Value` DESC ", $webpage->id
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $see_also_page = get_object('Webpage', $row['Page Store See Also Key']);

                    if ($see_also_page->id) {

                        //print_r($see_also_page);
                        //exit;

                        switch ($see_also_page->get('Webpage Scope')) {
                            case 'Category Products':

                                $category = get_object('Category', $see_also_page->get('Webpage Scope Key'));

                                $see_also[] = array(
                                    'type'                 => 'category',

                                    'header_text'          => $category->get('Category Label'),
                                    'image_src'            => $category->get('Image'),
                                    'image_mobile_website' => '',
                                    'image_website'        => '',

                                    'webpage_key'          => $see_also_page->id,
                                    'webpage_code'         => mb_strtolower($see_also_page->get('Webpage Code')),

                                    'category_key'         => $category->id,
                                    'category_code'        => $category->get('Category Code'),
                                    'number_products'      => $category->get('Product Category Active Products'),
                                    'link'                 => $see_also_page->get('Webpage URL'),


                                );
                                break;
                        }


                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $new_content_data = array(
                'blocks'   => array(
                  
                    array(
                        'type'              => 'category_products',
                        'label'             => _('Family'),
                        'icon'              => 'fa-cubes',
                        'show'              => 1,
                        'top_margin'        => 0,
                        'bottom_margin'     => ((count($see_also)==0 and count($related_products)==0  )?40:0),
                        'item_headers'      => $has_header,
                        'items'             => $items,
                        'sort'              => 'Manual',
                        'new_first'         => true,
                        'out_of_stock_last' => true,
                    )
                ),
                'old_data' => $content_data
            );

            if (count($related_products) > 0 and false) {

                switch ($row3['Webpage Website Key']) {
                    case 12:
                        $title = 'Súvisiace produkty';
                        break;
                    case 6:
                        $title = 'Produits Connexes';
                        //Voir aussi
                        break;
                    case 8:
                        $title = 'Prodotti correlati';
                        //Guarda anche

                        break;
                    case 10:
                        $title = 'Produkty powiązane';
                        //Zobacz także
                        break;
                    case 14:
                        $title = 'Související produkty';
                        //Viz též
                        break;
                    case 16:
                        $title = 'Kapcsolódó termékek';
                        //Lásd még
                        break;
                    case 4:

                        $title = 'Nützliche/Verwandte Produkte';
                        //Siehe auch
                        break;
                    default:
                        $title = 'Related products';
                }


                $new_content_data['blocks'][] = array(
                    'type'          => 'products',
                    'auto'              => false,
                    'auto_scope'        => 'webpage',
                    'auto_items'        => 5,
                    'auto_last_updated' => '',
                    'label'         => _('Products'),
                    'icon'          => 'fa-window-restore',
                    'show'          => 1,
                    'top_margin'    => 0,
                    'bottom_margin' => (count($see_also)==0?30:0),
                    'item_headers'  => false,
                    'items'         => $related_products,
                    'sort'          => 'Manual',
                    'title'         => $title,
                    'show_title'    => true

                );
            }


            if (count($see_also) > 0 and false) {

                switch ($row3['Webpage Website Key']) {
                    case 12:
                        $title = 'Pozrite si tiež';

                        break;
                    case 6:
                        $title = 'Voir aussi';

                        break;
                    case 8:
                        $title = 'Guarda anche';

                        break;
                    case 10:
                        $title = 'Zobacz także';

                        break;
                    case 14:
                        $title = 'Viz též';

                        break;
                    case 16:
                        $title = 'Lásd még';

                        break;
                    case 4:

                        $title = 'Siehe auch';

                        break;
                    default:
                        $title = 'See also';
                }


                $new_content_data['blocks'][] = array(
                    'type'              => 'see_also',
                    'auto'              => ($webpage->get('Page Store See Also Type') == 'Auto' ? true : false),
                    'auto_scope'        => 'webpage',
                    'auto_items'        => count($see_also),
                    'auto_last_updated' => ($webpage->get('Page Store See Also Type') == 'Auto' ? $webpage->get('Page See Also Last Updated') : ''),
                    'label'         => _('See also'),
                    'icon'          => 'fa-link',
                    'show'          => 1,
                    'top_margin'    => 0,
                    'bottom_margin' => 40,
                    'item_headers'  => false,
                    'items'         => $see_also,
                    'sort'          => 'Manual',
                    'title'         => $title,
                    'show_title'    => true

                );
            }

            $x=json_encode($new_content_data);


            print $x;

            //          print_r
            //exit;
            // continue;


           // print_r($new_content_data);


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="category_products" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);

            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );

            $webpage->reindex_items();
        }
    }
}

function migrate_departments() {

    global $db;

    $left_offset = 158;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="categories_showcase"   ');
      $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE   `Page Key`=2972 ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');

            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            /* revert process
            $webpage->update(
                array(
                    'Page Store Content Data'   => json_encode($content_data),
                    'Webpage Template Filename' => 'categories_showcase'
                ), 'no_history'
            );
            exit;
            */
            $css = $webpage->get('Page Store CSS');
            // print_r($content_data);;
            // exit;

            $images = array();
            $texts  = array();

            foreach ($content_data['description_block']['blocks'] as $block_id => $block) {

                if ($block['type'] == 'image') {

                    if (preg_match('/#'.$block_id.'\{([a-zA-Z0-9.:\s;-]+)/', $css, $matches)) {
                        // print_r($matches);
                        $_css = trim($matches[1]);

                        $_css = preg_replace('/margin-left/', '', $_css);

                        if (preg_match('/top\:([0-9.]+)/', $_css, $_matches)) {
                            $top = $_matches[1];
                        } else {
                            $top = 0;
                        }
                        if (preg_match('/left\:([0-9.]+)/', $_css, $_matches)) {
                            $left = $_matches[1];
                        } else {
                            $left = 0;
                        }
                        if (preg_match('/width\:([0-9.]+)/', $_css, $_matches)) {
                            $width = $_matches[1];
                        } else {
                            $width = 0;
                        }
                        if (preg_match('/height\:([0-9.]+)/', $_css, $_matches)) {
                            $height = $_matches[1];
                        } else {
                            $height = 0;
                        }


                    } else {
                        $top    = 0;
                        $left   = 0;
                        $width  = 0;
                        $height = 0;

                    }

                    if ($width > 0 and $height > 0) {
                        $images[] = array(
                            'id'     => $block_id,
                            'src'    => $block['image_src'],
                            'title'  => $block['caption'],
                            'top'    => $top,
                            'left'   => $left_offset + $left,
                            'width'  => $width,
                            'height' => $height,
                        );
                    }

                } elseif ($block['type'] == 'text') {


                    if (preg_match('/#'.$block_id.'\{([a-zA-Z0-9.:\s;-]+)/', $css, $matches)) {
                        // print_r($matches);
                        $_css = trim($matches[1]);

                        $_css = preg_replace('/margin-left/', '', $_css);

                        if (preg_match('/top\:([0-9.]+)/', $_css, $_matches)) {
                            $top = $_matches[1];
                        } else {
                            $top = 0;
                        }
                        if (preg_match('/left\:([0-9.]+)/', $_css, $_matches)) {
                            $left = $_matches[1];
                        } else {
                            $left = 0;
                        }
                        if (preg_match('/width\:([0-9.]+)/', $_css, $_matches)) {
                            $width = $_matches[1];
                        } else {
                            $width = 0;
                        }
                        if (preg_match('/height\:([0-9.]+)/', $_css, $_matches)) {
                            $height = $_matches[1];
                        } else {
                            $height = 0;
                        }


                    } else {
                        $top    = 0;
                        $left   = 0;
                        $width  = 0;
                        $height = 0;

                    }

                    if ($width > 0 and $height > 0) {
                        $texts[] = array(
                            'id'     => $block_id,
                            'text'   => $block['content'],
                            'top'    => $top,
                            'left'   => $left_offset + $left,
                            'width'  => $width,
                            'height' => $height,


                        );
                    }

                }
            }
            //$matches = 200;
            if (preg_match('/\#description_block\{ height\:([0-9.]+)px\}/', $css, $matches)) {

                $blackboard_height = $matches[1];
            }


            $sections = array();
            if (!empty($content_data['sections'])) {
                foreach ($content_data['sections'] as $section) {

                    $items = array();

                    if (!empty($section['items'])) {
                        foreach ($section['items'] as $item) {
                            if ($item['type'] == 'category') {

                                $_webpage = get_object('Webpage', $item['webpage_key']);



                                $items[] = array(
                                    'type'                 => $item['type'],
                                    'category_key'         => $item['category_key'],
                                    'header_text'          => trim(strip_tags(mb_convert_encoding($item['header_text'], 'UTF-8', 'UTF-8'))),
                                    'image_src'            => $item['image_src'],
                                    'image_mobile_website' => $item['image_mobile_website'],
                                    'image_website'        => '',
                                    'webpage_key'          => $item['webpage_key'],
                                    'webpage_code'         => mb_strtolower($item['webpage_code']),
                                    'item_type'            => $item['item_type'],
                                    'category_code'        => $item['category_code'],
                                    'number_products'      => $item['number_products'],
                                    'link'                 => $_webpage->get('Webpage URL')

                                );
                            } elseif ($item['type'] == 'panel') {
                                $panel_data = $item['data'];

                                if ($panel_data['type'] == 'image') {
                                    $items[] = array(
                                        'type'       => 'image',
                                        'image_src'  => $panel_data['image_src'],
                                        'title'      => $panel_data['caption'],
                                        'link'       => $panel_data['link'],
                                        'size_class' => 'panel_'.preg_replace('/x/', '', $panel_data['size']),


                                    );
                                } else {
                                    print_r($item);
                                    exit;

                                }


                            }
                        }
                    }


                    $sections[] = array(
                        'type'     => ($section['type'] == 'anchor' ? 'anchor' : 'non_anchor'),
                        'title'    => strip_tags($section['title']),
                        'subtitle' => $section['subtitle'],
                        'items'    => $items
                    );
                }


            } else {
                $sections = array(
                    'type'     => 'anchor',
                    'title'    => '',
                    'subtitle' => '',
                    'items'    => array()

                );
            }

            $blackboard_height *= 1.1;

            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'blackboard',
                        'label'         => _('Blackboard'),
                        'icon'          => 'fa-image',
                        'show'          => 1,
                        'top_margin'    => 0,
                        'bottom_margin' => 0,
                        'height'        => $blackboard_height,
                        'images'        => $images,
                        'texts'         => $texts
                    ),
                    array(
                        'type'          => 'category_categories',
                        'label'         => _('Department'),
                        'icon'          => 'fa-th',
                        'show'          => 1,
                        'top_margin'    => 0,
                        'bottom_margin' => 30,
                        'sections'      => $sections
                    )
                ),
                'old_data' => $content_data
            );


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="category_categories" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);

            print_r($new_content_data);

            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data),
                ), 'no_history'
            );

            $webpage->reindex_items();


        }
    }
}

function migrate_registration() {

    global $db;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Page Code`="register.sys" ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            print_r($_content_data);

            exit;


        }
    }
}


?>
