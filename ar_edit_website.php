<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 December 2016 at 12:58:51 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';
include_once('utils/website_functions.php');


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
    case 'update_object_public':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'section_key' => array('type' => 'numeric'),
                         'object'      => array('type' => 'string'),
                         'object_key'  => array('type' => 'key'),
                         'value'       => array('type' => 'string'),
                     )
        );
        update_object_public($data, $editor, $smarty, $db);
        break;
    case 'add_panel':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'section_key' => array('type' => 'key'),
                         'value'       => array('type' => 'json array')

                     )
        );
        add_panel($data, $editor, $smarty, $db);
        break;
    case 'update_webpage_section_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'section_key' => array('type' => 'key'),
                         'target_key'  => array('type' => 'key'),

                     )
        );
        update_webpage_section_order($data, $editor, $smarty, $db);
        break;
    case 'add_webpage_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'item_key'    => array('type' => 'key'),
                         'section_key' => array('type' => 'key'),

                     )
        );
        add_webpage_item($data, $editor, $smarty, $db);
        break;
    case 'delete_webpage_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'item_key'    => array('type' => 'key'),

                     )
        );
        delete_webpage_item($data, $editor, $smarty, $db);
        break;

    case 'add_webpage_section':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),

                     )
        );
        add_webpage_section($data, $editor, $smarty, $db);
        break;
    case 'delete_webpage_section':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'section_key' => array('type' => 'key'),

                     )
        );
        delete_webpage_section($data, $editor, $smarty, $db);
        break;
    case 'update_webpage_items_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key'        => array('type' => 'key'),
                         'item_key'           => array('type' => 'key'),
                         'target_key'         => array('type' => 'numeric'),
                         'target_section_key' => array('type' => 'numeric'),
                     )
        );
        update_webpage_items_order($data, $editor, $smarty, $db);
        break;
    case 'publish_webpage':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),


                     )
        );
        publish_webpage($data, $editor, $db);
        break;
    case 'update_product_category_index':


        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),

                         'key'   => array('type' => 'key'),
                         'type'  => array('type' => 'string'),
                         'value' => array('type' => 'string')


                     )
        );
        update_product_category_index($data, $editor, $db, $smarty);
        break;
    case 'update_webpage_related_product':


        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),

                         'key'   => array('type' => 'key'),
                         'type'  => array('type' => 'string'),
                         'value' => array('type' => 'string')


                     )
        );
        update_webpage_related_product($data, $editor, $db);
        break;
    case 'update_webpage_section_data':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'      => array('type' => 'string'),
                         'parent_key'  => array('type' => 'key'),
                         'section_key' => array('type' => 'key'),

                         'value' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'type'  => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                     )
        );
        update_webpage_section_data($data, $editor, $db, $smarty);
        break;
    case 'webpage_content_data':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),
                         'section'    => array('type' => 'string'),
                         'block'      => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'value'      => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'type'       => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                     )
        );
        webpage_content_data($data, $editor, $db, $smarty);
        break;
    case 'edit_webpage':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'   => array('type' => 'key'),
                         'field' => array('type' => 'string'),
                         'value' => array('type' => 'string'),

                     )
        );
        edit_webpage($data, $editor, $db);

        break;

    case 'edit_category_stack_index':
        $data = prepare_values(
            $_REQUEST, array(
                         'stack_index' => array('type' => 'numeric'),
                         'key'         => array('type' => 'key'),
                         'subject_key' => array('type' => 'key'),
                         'webpage_key' => array('type' => 'key'),

                     )
        );
        edit_category_stack_index($data, $editor, $smarty, $db);

        break;

    case 'refresh_webpage_see_also':
        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),
                         'key'    => array('type' => 'key')
                     )
        );
        refresh_webpage_see_also($account, $db, $user, $editor, $data, $smarty);

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


function refresh_webpage_see_also($account, $db, $user, $editor, $data, $smarty) {

    // TODO remove this when class Webpage is implemented
    $data['object'] = 'old_page';

    $object         = get_object($data['object'], $data['key']);
    $object->editor = $editor;


    $see_also = $object->update_see_also();

    $see_also_data = $object->get_see_also_data();

    $links = '';
    foreach ($see_also_data['links'] as $link) {
        $links .= sprintf(
            '<tr class="webpage_tr"><td></td><td>%s</td></tr>', $link['code']
        );

    }


    $response = array(
        'state'                 => 200,
        'links'                 => $links,
        'see_also_last_updated' => $see_also_data['last_updated']
    );
    echo json_encode($response);

}

function edit_category_stack_index($data, $editor, $smarty, $db) {

    // old way to move items use update_webpage_items_order instead

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $object         = get_object('category', $data['key']);
    $object->editor = $editor;

    $object->update_subject_stack($data['stack_index'], $data['subject_key']);


    $response = array(
        'state'   => 200,
        'publish' => $webpage->get('Publish')


    );

    $content_data = $webpage->get('Content Data');
    $webpage->load_scope();
    if ($object->get('Category Subject') == 'Product') {

        $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);
    } elseif ($object->get('Category Subject') == 'Category') {
        $categories_html = get_categories_html($data, $content_data, $webpage, $smarty, $db);
    }


    if (isset($products_html)) {
        $response['products'] = $products_html;
    }
    if (isset($categories_html)) {
        $response['categories'] = $categories_html;
    }

    echo json_encode($response);


}

function edit_webpage($data, $editor, $db) {


    // todo migrate to Webpage & WebpageVersion classes

    include_once('class.Page.php');
    $webpage = new Page($data['key']);

    switch ($data['field']) {
        case 'css':
            $value = base64_decode($data['value']);

            //print_r($value);

            $webpage->update(array('Page Store CSS' => $value), 'no_history');


            break;
        default:
            break;
    }

    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );

    echo json_encode($response);

}

function webpage_content_data($data, $editor, $db, $smarty) {
    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['parent_key']);

    $content_data = $webpage->get('Content Data');

    if ($data['type'] == 'text') {


        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-e" style="z-index: 90; display: block;"></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90; display: block;"></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90; display: block;"></div>', '', $data['value']);


        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"><br></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"><br></div>', '', $data['value']);
        $data['value'] = str_replace('<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"><br></div>', '', $data['value']);


        if ($data['section'] == 'panels_in_section') {
            // print "yyyyy";


            foreach ($content_data['sections'] as $section_index => $section) {


                foreach ($section['panels'] as $panel_index => $panel) {

                    if ($panel['id'] == $data['block']) {

                        //  print_r($panel);
                        $content_data['sections'][$section_index]['panels'][$panel_index]['content'] = $data['value'];
                        //  print_r($panel);
                        break 2;
                    }
                }

            }


            $content_data['sections'][$section_index]['items'] = get_website_section_items($db, $content_data['sections'][$section_index]);


        } elseif ($data['section'] == 'panels') {


            foreach ($content_data['panels'] as $key => $value) {


                if ($value['id'] == $data['block']) {
                    $content_data['panels'][$key]['content'] = $data['value'];
                    break;
                }

            }


        } else {
            if (isset($content_data[$data['section']]['blocks'][$data['block']])) {

                $content_data[$data['section']]['blocks'][$data['block']]['content'] = $data['value'];
            } else {
                $content_data[$data['section']]['blocks'][$data['block']] = array(
                    'content' => $data['value'],
                    'type'    => $data['type']
                );

            }
        }


    } elseif ($data['type'] == 'item_header_text') {


        if ($data['section'] == 'panels_in_section') {
            // print "yyyyy";


            foreach ($content_data['sections'] as $section_index => $section) {


                foreach ($section['items'] as $item_index => $item) {


                    if ($item['type'] == 'category' and $item['category_key'] == $data['block']) {


                        $sql = sprintf(
                            'SELECT `Category Webpage Index Key` ,`Category Webpage Index Content Data` FROM `Category Webpage Index` WHERE `Category Webpage Index Key`=%d  ',
                            $content_data['sections'][$section_index]['items'][$item_index]['index_key']


                        );

                        if ($result = $db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $item_content_data = json_decode($row['Category Webpage Index Content Data'], true);

                                $item_content_data['header_text'] = $data['value'];


                                $sql = sprintf(
                                    'UPDATE `Category Webpage Index` SET `Category Webpage Index Content Data`=%s WHERE `Category Webpage Index Key`=%d ',
                                    prepare_mysql(json_encode($item_content_data)), $row['Category Webpage Index Key']
                                );

                                $db->exec($sql);
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        //    print_r(  $content_data['sections'][$section_index]['items']);

                        break 2;
                    }
                }

            }


            $content_data['sections'][$section_index]['items'] = get_website_section_items($db, $content_data['sections'][$section_index]);


        }


    } elseif ($data['type'] == 'caption') {


        if ($data['section'] == 'panels_in_section') {

            foreach ($content_data['sections'] as $section_index => $section) {


                foreach ($section['panels'] as $panel_index => $panel) {
                    if ($panel['id'] == $data['block']) {


                        $content_data['sections'][$section_index]['panels'][$panel_index]['caption'] = $data['value'];
                        break 2;
                    }
                }

            }


            $content_data['sections'][$section_index]['items'] = get_website_section_items($db, $content_data['sections'][$section_index]);


        } elseif ($data['section'] == 'panels') {


            foreach ($content_data[$data['section']] as $panel_key => $panel) {
                if ($data['block'] == $panel['id']) {

                    $content_data[$data['section']][$panel_key]['caption'] = $data['value'];
                    break;
                }
            }

        } else {


            if (isset($content_data[$data['section']]['blocks'][$data['block']])) {
                $content_data[$data['section']]['blocks'][$data['block']]['caption'] = $data['value'];
            }
        }


    } elseif ($data['type'] == 'code') {


        if (isset($content_data[$data['section']])) {


            if ($data['section'] == 'panels') {


                foreach ($content_data[$data['section']] as $panel_key => $panel) {
                    if ($data['block'] == $panel['id']) {

                        /*
                                                $code=base64_decode(rawurldecode($data['value']));
                                               // $data['value']=$code;

                        print_r($_REQUEST);
                                                print_r(rawurldecode($data['value']));
                                                print_r(base64_decode($data['value']));
                        */


                        $code = base64_decode($data['value']);

                        //  exit;


                        $content_data['panels'][$panel_key]['content'] = $code;

                        //      print_r($content_data);

                        $sql = sprintf(
                            'UPDATE `Webpage Panel Dimension` SET `Webpage Panel Data`=%s ,`Webpage Panel Metadata`=%s WHERE `Webpage Panel Key`=%d ', prepare_mysql($code),
                            prepare_mysql(json_encode($content_data['panels'][$panel_key])), $content_data['panels'][$panel_key]['key']
                        );
                        $db->exec($sql);
                        break;
                    }
                }

            }

        }
    } elseif ($data['type'] == 'link') {


        if ($data['section'] == 'panels_in_section') {

            foreach ($content_data['sections'] as $section_index => $section) {


                foreach ($section['panels'] as $panel_index => $panel) {
                    if ($panel['id'] == $data['block']) {


                        $content_data['sections'][$section_index]['panels'][$panel_index]['link'] = $data['value'];
                        break 2;
                    }
                }

            }


            $content_data['sections'][$section_index]['items'] = get_website_section_items($db, $content_data['sections'][$section_index]);


        } else {
            if ($data['section'] == 'panels') {


                foreach ($content_data[$data['section']] as $panel_key => $panel) {
                    if ($data['block'] == $panel['id']) {

                        $content_data[$data['section']][$panel_key]['link'] = $data['value'];
                        break;
                    }
                }

            } else {


                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {
                    $content_data[$data['section']]['blocks'][$data['block']]['link'] = $data['value'];
                }
            }
        }


    } elseif ($data['type'] == 'add_class') {
        if (isset($content_data[$data['section']])) {


            if ($data['block'] != '') {


                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {

                    if (isset($content_data[$data['section']]['blocks'][$data['block']]['class'])) {

                        $classes = preg_split('/\s+/', $content_data[$data['section']]['blocks'][$data['block']]['class']);

                        foreach (preg_split('/\s+/', $data['value']) as $value) {
                            if (!in_array($data['value'], $classes)) {
                                $classes[] = $value;
                            }
                        }


                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = join(' ', $classes);
                    } else {
                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = $data['value'];
                    }


                }

            } else {

                if (isset($content_data[$data['section']]['class'])) {

                    $classes = preg_split('/\s+/', $content_data[$data['section']]['class']);

                    foreach (preg_split('/\s+/', $data['value']) as $value) {
                        if (!in_array($data['value'], $classes)) {
                            $classes[] = $value;
                        }
                    }


                    $content_data[$data['section']]['class'] = join(' ', $classes);
                } else {
                    $content_data[$data['section']]['class'] = $data['value'];
                }


            }

        }
    } elseif ($data['type'] == 'remove_class') {
        if (isset($content_data[$data['section']])) {


            if ($data['block'] != '') {

                if (isset($content_data[$data['section']]['blocks'][$data['block']])) {

                    if (isset($content_data[$data['section']]['blocks'][$data['block']]['class'])) {

                        $classes = preg_split('/\s/', $content_data[$data['section']]['blocks'][$data['block']]['class']);
                        foreach (preg_split('/\s+/', $data['value']) as $value) {
                            unset($classes[$value]);
                        }


                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = trim(join(' ', $classes));
                    } else {
                        $content_data[$data['section']]['blocks'][$data['block']]['class'] = '';
                    }


                }

            } else {
                if (isset($content_data[$data['section']]['class'])) {

                    $classes = preg_split('/\s+/', $content_data[$data['section']]['class']);


                    $classes = array_diff($classes, preg_split('/\s+/', $data['value']));


                    $content_data[$data['section']]['class'] = trim(join(' ', $classes));
                } else {
                    $content_data[$data['section']]['class'] = '';
                }

            }

        }
    } elseif ($data['type'] == 'add_image') {
        if (isset($content_data[$data['section']])) {


            $content_data[$data['section']]['blocks'][$data['block']] = array(
                'type'      => 'image',
                'image_src' => $data['value'],
                'caption'   => '',
                'class'     => ''

            );


        }
    } elseif ($data['type'] == 'remove_block') {
        if (isset($content_data[$data['section']])) {


            unset($content_data[$data['section']]['blocks'][$data['block']]);


        }
    } elseif ($data['type'] == 'add_panel') {

        $panel_data = json_decode($data['value'], true);


        $size_tag = $panel_data['size'].'x';

        $panel = array(
            'id'   => $data['block'],
            'type' => $panel_data['type'],
            'size' => $size_tag

        );

        if ($panel_data['type'] == 'image') {


            $panel['image_src'] = '/art/panel_'.$size_tag.'_1.png';
            $panel['link']      = '';
            $panel['caption']   = '';
        } elseif ($panel_data['type'] == 'text') {

            $panel['content'] = 'bla bla bla';
            $panel['class']   = 'text_panel_default';

        } elseif ($panel_data['type'] == 'code') {

            $panel['content'] = '';
            $panel['class']   = 'code_panel_default';


        } elseif ($panel_data['type'] == 'page_break') {

            $panel['title']    = 'Bla bla';
            $panel['subtitle'] = 'bla bla';


        }

        $sql = sprintf(
            'INSERT INTO `Webpage Panel Dimension` (`Webpage Panel Id`,`Webpage Panel Webpage Key`,`Webpage Panel Type`,`Webpage Panel Data`,`Webpage Panel Metadata`) VALUES (%s,%d,%s,%s,%s) ',
            prepare_mysql($data['block']), $webpage->id, prepare_mysql($panel_data['type']), ($panel_data['type'] == 'code' ? prepare_mysql($panel['content']) : prepare_mysql('')),
            prepare_mysql(json_encode($panel))

        );


        //  print $sql;

        $db->exec($sql);
        $panel['key'] = $db->lastInsertId();


        $content_data['panels'][$panel_data['stack_index']] = $panel;

        ksort($content_data['panels']);

        $webpage->load_scope();
        if ($webpage->scope_found == 'Category') {
            $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);

        }

        //  print_r( $content_data);

    } elseif ($data['type'] == 'add_section') {


        //  print_r( $content_data);

    } elseif ($data['type'] == 'remove_panel') {

        if ($data['section'] == 'panels_in_section') {


            foreach ($content_data['sections'] as $section_index => $section) {


                foreach ($section['panels'] as $panel_index => $panel) {


                    if ($panel['id'] == $data['block']) {


                        $sql = sprintf(
                            'DELETE FROM `Webpage Panel Dimension` WHERE  `Webpage Panel Key`=%d ', $panel['key']
                        );
                        $db->exec($sql);
                        unset($content_data['sections'][$section_index]['panels'][$panel_index]);
                        $content_data['sections'][$section_index]['items'] = get_website_section_items($db, $content_data['sections'][$section_index]);

                        break 2;
                    }
                }

            }

            $section_key = $content_data['sections'][$section_index]['key'];
            $items       = $content_data['sections'][$section_index]['items'];
            $smarty->assign('categories', $items);
            $overview_items_html[$section_key] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
            $items_html[$section_key]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');


        } elseif ($data['section'] == 'panels') {

            //    print_r($content_data['panels']);
            foreach ($content_data['panels'] as $panel_key => $panel) {
                if ($panel['id'] == $data['block']) {
                    $sql = sprintf(
                        'DELETE FROM `Webpage Panel Dimension` WHERE  `Webpage Panel Key`=%d ', $content_data['panels'][$panel_key]['key']
                    );
                    $db->exec($sql);

                    unset($content_data['panels'][$panel_key]);
                    break;

                }

            }

            $webpage->load_scope();
            if ($webpage->scope_found == 'Category') {
                $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);

            }


        }

    }


    //print_r($content_data);
    //exit;


    $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish'),


    );

    if (isset($products_html)) {
        $response['products'] = $products_html;
    }
    if (isset($items_html)) {
        $response['items_html'] = $items_html;
    }
    if (isset($overview_items_html)) {
        $response['overview_items_html'] = $overview_items_html;
    }


    echo json_encode($response);


}

function update_product_category_index($data, $editor, $db, $smarty) {

    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $content_data = $webpage->get('Content Data');


    $sql = sprintf(
        'SELECT `Product Category Index Key`,`Product Category Index Content Data` FROM `Product Category Index` WHERE `Product Category Index Key`=%d ', $data['key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Product Category Index Content Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {

                $product_content_data = json_decode($row['Product Category Index Content Data'], true);

            }

            $product_content_data[$data['type']] = $data['value'];


            $sql = sprintf(
                'UPDATE `Product Category Index` SET `Product Category Index Content Data`=%s   WHERE `Product Category Index Key`=%d ', prepare_mysql(json_encode($product_content_data)),
                $row['Product Category Index Key']
            );
            $db->exec($sql);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $products_html = get_products_html($data, $content_data, $webpage, $smarty, $db);

    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );

    if (isset($products_html)) {
        $response['products'] = $products_html;
    }


    echo json_encode($response);


}


function update_webpage_related_product($data, $editor, $db) {

    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $sql = sprintf(
        'SELECT `Webpage Related Product Key`,`Webpage Related Product Content Data` FROM `Webpage Related Product Bridge` WHERE `Webpage Related Product Key`=%d ', $data['key']
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Webpage Related Product Content Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {

                $product_content_data = json_decode($row['Webpage Related Product Content Data'], true);

            }

            $product_content_data[$data['type']] = $data['value'];


            $sql = sprintf(
                'UPDATE `Webpage Related Product Bridge` SET `Webpage Related Product Content Data`=%s   WHERE `Webpage Related Product Key`=%d ', prepare_mysql(json_encode($product_content_data)),
                $row['Webpage Related Product Key']
            );
            $db->exec($sql);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );
    echo json_encode($response);


}

function publish_webpage($data, $editor, $db) {

    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['parent_key']);

    $webpage->publish();


    $response = array(
        'state' => 200

    );
    echo json_encode($response);


}


function get_products_html($data, $content_data, $webpage, $smarty, $db) {

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


function get_categories_html($data, $content_data, $webpage, $smarty, $db) {

    include_once 'class.Public_Product.php';
    include_once 'class.Public_Category.php';

    $public_category = new Public_Category($webpage->scope->id);
    $public_category->load_webpage();


}


function update_webpage_section_data($data, $editor, $db, $smarty) {
    // todo migrate to Webpage & WebpageVersion classes
    include_once('class.Page.php');
    $webpage = new Page($data['parent_key']);

    $content_data = $webpage->get('Content Data');
    $result_data  = array();
    //  print_r($content_data);
    if ($data['type'] == 'title' or $data['type'] == 'subtitle') {
        $data['value'] = trim($data['value']);

        foreach ($content_data['sections'] as $_key => $_data_section) {
            if ($_data_section['key'] == $data['section_key']) {
                $content_data['sections'][$_key][$data['type']] = $data['value'];

                $result_data['title']    = $content_data['sections'][$_key]['title'];
                $result_data['subtitle'] = $content_data['sections'][$_key]['subtitle'];

                break;

            }

        }


    }


    $webpage->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


    $response = array(
        'state'   => 200,
        'data'    => $result_data,
        'publish' => $webpage->get('Publish'),


    );

    if (isset($products_html)) {
        $response['products'] = $products_html;
    }

    echo json_encode($response);


}


function update_webpage_items_order($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);


    $webpage->load_scope();
    $updated_result = $webpage->update_items_order($data['item_key'], $data['target_key'], $data['target_section_key']);


    $overview_items_html = array();
    $items_html          = array();

    foreach ($updated_result as $section_key => $items) {
        $smarty->assign('categories', $items);
        $overview_items_html[$section_key] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
        $items_html[$section_key]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );

    echo json_encode($response);

}


function delete_webpage_section($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $content_data = $webpage->get('Content Data');

    $webpage->load_scope();
    $updated_result = $webpage->delete_section($data['section_key']);


    $overview_items_html = array();
    $items_html          = array();

    foreach ($updated_result as $section_key => $items) {
        $smarty->assign('categories', $items);
        $overview_items_html[$section_key] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
        $items_html[$section_key]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );


    echo json_encode($response);

}


function add_webpage_section($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);


    $webpage->load_scope();
    $updated_metadata = $webpage->add_section();


    $smarty->assign('section_data', $updated_metadata['new_section']);

    $new_section_html          = $smarty->fetch('webpage.preview.categories_showcase.section.tpl');
    $new_overview_section_html = $smarty->fetch('webpage.preview.categories_showcase.overview_section.tpl');


    $response = array(
        'state'                     => 200,
        'new_section_html'          => $new_section_html,
        'new_overview_section_html' => $new_overview_section_html,
        'publish'                   => $webpage->get('Publish')


    );

    echo json_encode($response);

}

function add_webpage_item($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $webpage->load_scope();


    $updated_result = $webpage->add_section_item($data['item_key'], $data['section_key']);


    $overview_items_html = array();
    $items_html          = array();

    foreach ($updated_result as $section_key => $items) {
        $smarty->assign('categories', $items);
        $overview_items_html[$section_key] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
        $items_html[$section_key]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );
    echo json_encode($response);


}

function delete_webpage_item($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $webpage->load_scope();


    $updated_result = $webpage->remove_section_item($data['item_key']);


    $overview_items_html = array();
    $items_html          = array();

    foreach ($updated_result as $section_key => $items) {
        $smarty->assign('categories', $items);
        $overview_items_html[$section_key] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
        $items_html[$section_key]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );
    echo json_encode($response);
}

function update_webpage_section_order($data, $editor, $smarty, $db) {


    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);

    $webpage->load_scope();


    $webpage->update_webpage_section_order($data['section_key'], $data['target_key']);

    $content_data = $webpage->get('Content Data');

    $overview = '';
    $items    = '';
    foreach ($content_data['sections'] as $section) {
        $smarty->assign('section_data', $section);

        $overview .= $smarty->fetch('webpage.preview.categories_showcase.overview_section.tpl');
        $items .= $smarty->fetch('webpage.preview.categories_showcase.section.tpl');

    }


    $response = array(
        'state'    => 200,
        'overview' => $overview,
        'items'    => $items,

        'publish' => $webpage->get('Publish')


    );
    echo json_encode($response);


}

function add_panel($data, $editor, $smarty, $db) {

    include_once('class.Page.php');
    $webpage = new Page($data['webpage_key']);


    $updated_result = $webpage->add_panel($data['section_key'], $data['value']);


    $overview_items_html = array();
    $items_html          = array();

    foreach ($updated_result as $section_key => $items) {
        $smarty->assign('categories', $items);
        $overview_items_html[$section_key] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
        $items_html[$section_key]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );
    echo json_encode($response);

}

function update_object_public($data, $editor, $smarty, $db) {


    include_once('class.Page.php');


    $object = get_object($data['object'], $data['object_key']);


    if ($object->get_object_name() == 'Category') {
        $object->update(array('Product Category Public' => $data['value']));

    } elseif ($object->get_object_name() == 'Product') {

        $object->update(array('Product Public' => $data['value']));
    }


    $webpage             = new Page($data['webpage_key']);
    $content_data        = $webpage->get('Content Data');
    $overview_items_html = array();
    $items_html          = array();


    foreach ($content_data['sections'] as $section_stack_index => $section_data) {
        if ($section_data['key'] == $data['section_key']) {


            $smarty->assign('categories', $content_data['sections'][$section_stack_index]['items']);
            $overview_items_html[$data['section_key']] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
            $items_html[$data['section_key']]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');


            break;
        }
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );
    echo json_encode($response);


}

?>
