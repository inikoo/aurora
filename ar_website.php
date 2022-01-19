<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 July 2017 at 17:00:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'see_also':

        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key'  => array('type' => 'key'),
                         'number_items' => array('type' => 'numeric'),


                     )
        );

        see_also($data, $db, $user, $smarty);
        break;
    case 'webpage_block':

        $data = prepare_values(
            $_REQUEST, array(
                         'code'        => array('type' => 'string'),
                         'theme'       => array('type' => 'string'),
                         'store_key'   => array(
                             'type'     => 'key',
                             'optional' => true
                         ),
                         'webpage_key' => array(
                             'type'     => 'key',
                             'optional' => true
                         )

                     )
        );

        webpage_block($data, $db, $user, $smarty);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function see_also($data, $db, $user, $smarty)
{
    include_once('utils/image_functions.php');

    $webpage = get_object('Webpage', $data['webpage_key']);


    $html = '';


    foreach ($webpage->get_related_webpages_key($data['number_items']) as $webpage_key) {
        $see_also_page = get_object('Webpage', $webpage_key);


        if ($see_also_page->get('Webpage Scope') == 'Category Products' or $see_also_page->get('Webpage Scope') == 'Category Categories') {
            $category = get_object('Category', $see_also_page->get('Webpage Scope Key'));

            $image_src = $category->get('Image');


            if (preg_match('/id=(\d+)/', $image_src, $matches)) {
                $image_key            = $matches[1];
                $image_mobile_website = 'wi.php?id='.$image_key.'&s=320x200';
                $image_website        = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');
            } else {
                $image_mobile_website = $image_src;
                $image_website        = $image_src;
            }

            $see_also = array(
                'type'                 => 'category',
                'category_key'         => $category->id,
                'header_text'          => $category->get('Category Label'),
                'image_src'            => $category->get('Image'),
                'image_mobile_website' => $image_mobile_website,
                'image_website'        => $image_website,
                'webpage_key'          => $see_also_page->id,
                'webpage_code'         => strtolower($see_also_page->get('Webpage Code')),
                'category_code'        => $category->get('Category Code'),
                'number_products'      => $category->get('Product Category Active Products'),
                'link'                 => $see_also_page->get('Webpage URL')

            );
        } elseif ($see_also_page->get('Webpage Scope') == 'Product') {
            $product = get_object('Public_Product', $see_also_page->get('Webpage Scope Key'));

            $image_src = $product->get('Image');


            if (preg_match('/id=(\d+)/', $image_src, $matches)) {
                $image_key = $matches[1];


                $image_mobile_website = 'wi.php?id='.$image_key.'&s=320x200';
                $image_website        = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');
            } else {
                $image_mobile_website = $image_src;
                $image_website        = $image_src;
            }

            $see_also = array(
                'type'                 => 'product',
                'product_id'           => $product->id,
                'header_text'          => $product->get('Name'),
                'image_src'            => $product->get('Image'),
                'image_mobile_website' => $image_mobile_website,
                'image_website'        => $image_website,
                'webpage_key'          => $see_also_page->id,
                'webpage_code'         => strtolower($see_also_page->get('Webpage Code')),

                'product_code'      => $product->get('Code'),
                'product_web_state' => $product->get('Web State'),
                'link'              => $see_also_page->get('Webpage URL')

            );
        }


        // print_r($see_also);


        $smarty->assign('item_data', $see_also);
        $html .= $smarty->fetch('splinters/see_also_item.splinter.tpl');
    }

    $response = array(
        'state'       => 200,
        'html'        => $html,
        'update_date' => gmdate('Y-m-d H:i:s')
    );
    echo json_encode($response);
}


function webpage_block($data, $db, $user, $smarty)
{
    include_once('conf/webpage_blocks.php');

    $blocks = get_webpage_blocks();

    $block = $blocks[$data['code']];


    $block_id = preg_replace('/\./', '_', uniqid('web_block', true));

    if ($data['code'] == 'text') {
        $smarty->assign('template', 't1');
        // $block['text_blocks'][0]['text'] = preg_replace('/block_block_key_t1_editor/', 'block_'.$block_id.'_t1_editor', $block['text_blocks'][0]['text']);
    } elseif ($data['code'] == 'map') {
        $smarty->assign('store', get_object('store', $data['store_key']));
    } elseif ($data['code'] == 'reviews') {
        $store = get_object('store', $data['store_key']);

        $reviews_data  = $store->get('Reviews Settings');
        $review_widget = '';

        if ($reviews_data) {
            if ($reviews_data['provider'] == 'reviews.io') {
                $review_widget     = "
                 <div id=\"carousel-inline-widget-810\" style=\"width:100%;margin:0 auto;\"></div>
            <script>
                richSnippetReviewsWidgets('carousel-inline-widget-810', {
                    store:         '".$reviews_data['data']['store']."',
                    widgetName:    'carousel-inline',
                    primaryClr:    '#f47e27',
                    neutralClr:    '#f4f4f4',
                    reviewTextClr: '#2f2f2f',
                    ratingTextClr: '#2f2f2f',
                    layout:        'fullWidth',
                    numReviews:    21
                });
            </script>
                
                ";
                $block['provider'] = 'reviews.io';
            } elseif ($reviews_data['provider'] == 'trust_pilot') {
                $review_widget     = '';


                $block['provider'] = 'trust_pilot';
                $block['template_id'] = $reviews_data['data']['template_id'];
                $block['business_unit_id'] = $reviews_data['data']['business_unit_id'];
                $block['url'] = $reviews_data['data']['url'];
                $block['locale'] = $reviews_data['data']['locale'];
                $block['lang'] = $reviews_data['data']['lang'];


            }
        }

        $block['html'] = $review_widget;
    }


    if (!empty($data['webpage_key'])) {
        $webpage = get_object('Webpage', $data['webpage_key']);
        $website = get_object('Website', $webpage->get('Webpage Website Key'));
        $smarty->assign('webpage', $webpage);
        $smarty->assign('website', $website);
    }

    $smarty->assign('key', $block_id);


    $smarty->assign('data', $block);
    $smarty->assign('block', $block);

    //print $smarty->fetch($data['theme'].'/blk.'.$data['code'].'.'.$data['theme'].'.tpl');
    $response = array(
        'state'     => 200,
        'button'    => $smarty->fetch($data['theme'].'/blk.control_label.'.$data['theme'].'.tpl'),
        'controls'  => $smarty->fetch($data['theme'].'/blk.control.'.$data['code'].'.'.$data['theme'].'.tpl'),
        'block'     => $smarty->fetch($data['theme'].'/blk.'.$data['code'].'.'.$data['theme'].'.tpl'),
        'type'      => $data['code'],
        'block_key' => $block_id
    );
    echo json_encode($response);
}


?>
