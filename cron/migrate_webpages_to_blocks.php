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


//migrate_families();
migrate_departments();
//exit;


function migrate_families() {

    global $db;
    $left_offset = 158;

    $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE `Webpage Template Filename`="products_showcase" AND   `Page Key`=3070 ');
    $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Page Key`=3070 ');


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


                    $images[] = array(
                        'id'     => $block_id,
                        'src'    => $block['image_src'],
                        'title'  => $block['caption'],
                        'top'    => $top,
                        'left'   => $left_offset + $left,
                        'width'  => $width,
                        'height' => $height,
                    );
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
            $matches = 200;
            if (preg_match('/\#description_block\{ height\:([0-9.]+)px\}/', $css, $matches)) {

                $blackboard_height = $matches[1];
            }

            //  print " $blackboard_height";

            $items = array();

            $sql = sprintf(
                "SELECT P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)    WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State` ",
                $row['Webpage Scope Key']
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $items[] = array(
                        'type'       => 'product',
                        'product_id' => $row['Product ID'],
                        'code'       => $row['Product Code'],
                        'web_state'  => $row['Product Web State'],
                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


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
                        'type'          => 'category_products',
                        'label'         => _('Products'),
                        'icon'          => 'fa-cube',
                        'show'          => 1,
                        'top_margin'    => 0,
                        'bottom_margin' => 0,
                        'items'         => $items
                    )
                ),
                'old_data' => $content_data
            );


            print_r($new_content_data);


            //exit;
            $webpage->update(
                array(
                    'Page Store Content Data'   => json_encode($new_content_data),
                    'Webpage Template Filename' => 'category_products'
                ), 'no_history'
            );


        }
    }
}

function migrate_departments() {

    global $db;

    $left_offset = 158;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="categories_showcase"   ');
    //  $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE   `Page Key`=2972 ');


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
                                    'header_text'          => trim(strip_tags($item['header_text'])),
                                    'image_src'            => $item['image_src'],
                                    'image_mobile_website' => $item['image_mobile_website'],
                                    'image_website'        => '',
                                    'webpage_key'          => $item['webpage_key'],
                                    'webpage_code'         => strtolower($item['webpage_code']),
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
                        'label'         => _('Categories').' ('._('sections').')',
                        'icon'          => 'fa-th',
                        'show'          => 1,
                        'top_margin'    => 0,
                        'bottom_margin' => 30,
                        'sections'      => $sections
                    )
                ),
                'old_data' => $content_data
            );


            // print_r($new_content_data);

            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="category_categories" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);

            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data),
                ), 'no_history'
            );

            $webpage->reindex_category_categories();


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
