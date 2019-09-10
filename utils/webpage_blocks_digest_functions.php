<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21-06-2019 11:52:55 MYT Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 2.0

*/

include_once('utils/image_functions.php');


function digest_website_content_data_blocks($content_data) {


    include_once('utils/image_functions.php');


    foreach ($content_data['blocks'] as $block_key => $block) {


        switch ($block['type']) {
            case 'blackboard':




                $items = array();
                $index = 0;

                $max_images = count($block['texts']);
                if ($max_images == 0) {
                    $max_images = 1;
                }

                $counter = 0;
                foreach ($block['images'] as $key_item => $item) {
                    $index        = $index + 10;
                    $item['type'] = 'image';


                    if ($item['width'] > 0 and $item['height'] > 0) {
                        $image_website = $item['src'];
                        if (preg_match('/id=(\d+)/', $item['src'], $matches)) {
                            $image_key = $matches[1];

                            $width  = $item['width'] * 2;
                            $height = $item['height'] * 2;

                            $image_website = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, $width, $height, 'do_not_enlarge');

                        }


                        $content_data['blocks'][$block_key]['images'][$key_item]['image_website'] = $image_website;

                        $item['image_website']                                                    = $image_website;

                    }

                    $items[$index] = $item;
                    $counter++;

                }




                $index = 5;
                foreach ($block['texts'] as $item) {
                    $index         = $index + 10;
                    $item['type']  = 'text';
                    $items[$index] = $item;
                }

                ksort($items);


                $image_counter = 0;
                $mobile_html   = '';
                $tablet_html   = '';


                foreach ($items as $item) {
                    if ($item['type'] == 'text') {
                        $tablet_html .= '<p>'.$item['text'].'</p>';
                    }
                    if ($item['type'] == 'image') {

                        if ($image_counter >= $max_images) {
                            break;
                        }

                        if ($image_counter % 2 == 0) {
                            $tablet_html .= '<img src="'.$item['image_website'].'" style="width:45%;float:left;margin-right:20px;" alt="'.$item['title'].'">';

                        } else {
                            $tablet_html .= '<img src="'.$item['image_website'].'" style="width:40%;float:right;margin-left:20px;" alt="'.$item['title'].'">';

                        }


                        $image_counter++;

                    }

                }
                $image_counter = 0;

                foreach ($items as $key_item => $item) {

                    if ($item['type'] == 'image') {

                        if ($item['height'] == 0 or $item['width'] == 0) {
                            unset($items[$key_item]);
                        } else {
                            $ratio = $item['width'] / $item['height'];
                            //print "$ratio\n";

                            if ($ratio > 7.5) {
                                $mobile_html .= '<img src="'.$item['image_website'].'" style="width:100%;" alt="'.$item['title'].'">';
                                unset($items[$key_item]);
                                break;
                            }
                        }

                    }


                }


                foreach ($items as $item) {
                    if ($item['type'] == 'text') {
                        $mobile_html .= '<p>'.$item['text'].'</p>';
                    }
                    if ($item['type'] == 'image') {


                        if ($image_counter % 2 == 0) {
                            $mobile_html .= '<img src="'.$item['image_website'].'" style="width:40%;padding-top:15px;float:left;margin-right:15px;" alt="'.$item['title'].'">';

                        } else {
                            $mobile_html .= '<img src="'.$item['image_website'].'" style="width:40%;padding-top:15px;float:right;margin-left:15px;" alt="'.$item['title'].'">';

                        }


                        $image_counter++;

                    }

                }


                $mobile_html = preg_replace('/\<p\>\<br\>\<\/p\>/', '', $mobile_html);
                $mobile_html = preg_replace('/\<p style\=\"text-align: left;\"\><br\>\<\/p\>/', '', $mobile_html);
                $mobile_html = preg_replace('/\<p style\=\"\"\>\<br\>\<\/p\>/', '', $mobile_html);


                $tablet_html = preg_replace('/\<p\>\<br\>\<\/p\>/', '', $tablet_html);
                $tablet_html = preg_replace('/\<p style\=\"text-align: left;\"\><br\>\<\/p\>/', '', $tablet_html);
                $tablet_html = preg_replace('/\<p style\=\"\"\>\<br\>\<\/p\>/', '', $tablet_html);

                // print_r($mobile_html);
                $content_data['blocks'][$block_key]['mobile_html'] = $mobile_html;
                $content_data['blocks'][$block_key]['tablet_html'] = $tablet_html;




                break;
            case 'category_products':
                foreach ($block['items'] as $item_key => $item) {
                    if ($item['type'] == 'product') {

                            $image_mobile_website = $item['image_src'];
                            if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                $image_key = $matches[1];
//340x214id
                                $image_mobile_website = 'wi.php?s='.get_image_size($image_key,340,214,'height').'&id='.$image_key;

                            }


                            $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;





                            $image_website = $item['image_src'];
                            if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                $image_key     = $matches[1];
                                $image_website = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');

                            }


                            $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;



                    } elseif ($item['type'] == 'image') {


                            $image_website = $item['image_src'];
                            if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                $image_key = $matches[1];

                                if ($content_data['blocks'][$block_key]['item_headers']) {
                                    $height = 330;
                                } else {
                                    $height = 290;
                                }


                                switch ($item['size_class']) {
                                    case 'panel_1':
                                        $width = 226;

                                        break;
                                    case 'panel_2':
                                        $width = 470;
                                        break;
                                    case 'panel_3':
                                        $width = 714;
                                        break;
                                    case 'panel_4':
                                        $width = 958;
                                        break;
                                    case 'panel_5':
                                        $width = 1202;
                                        break;

                                }

                                $image_website = 'wi.php?id='.$image_key.'&s='.$width.'x'.$height;

                            }


                            $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;





                    }

                }
                break;

            case 'products':
                foreach ($block['items'] as $item_key => $item) {


                    $content_data['blocks'][$block_key]['items'][$item_key]['header_text'] = trim($item['header_text']);

                    $content_data['blocks'][$block_key]['items'][$item_key]['image_src'] = preg_replace('/image_root/', 'wi', $item['image_src']);


                        $image_mobile_website = $item['image_src'];
                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                            $image_key = $matches[1];

                            $image_mobile_website = 'wi.php?id='.$image_key.'&s=340x214';


                        }


                        $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;





                        $image_website = $item['image_src'];
                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                            $image_key     = $matches[1];
                            $image_website = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');

                        }


                        $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;





                    //   print_r( $content_data['blocks'][$block_key]['items'][$item_key]);


                }
                break;
            case 'see_also':
                foreach ($block['items'] as $item_key => $item) {


                    $content_data['blocks'][$block_key]['items'][$item_key]['image_src'] = preg_replace('/image_root/', 'wi', $item['image_src']);


                        $image_mobile_website = $item['image_src'];
                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                            $image_key = $matches[1];

                            $image_mobile_website = 'wi.php?id='.$image_key.'&s=320x200';


                        }


                        $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;




                        $image_website = $item['image_src'];
                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                            $image_key     = $matches[1];
                            $image_website = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');

                        }


                        $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;



                }
                break;
            case 'category_categories':




                foreach ($block['sections'] as $section_key => $section) {

                    if (isset($section['items']) and is_array($section['items'])) {


                        foreach ($section['items'] as $item_key => $item) {

                            if ($item['type'] == 'category') {

                                    $image_mobile_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key            = $matches[1];
                                        $image_mobile_website = 'wi.php?id='.$image_key.'&s=320x200';
                                    }
                                    $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;



                                    $image_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key     = $matches[1];
                                        $image_website = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');


                                    }


                                    $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_website'] = $image_website;



                            } elseif ($item['type'] == 'image') {


                                    $image_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key = $matches[1];
                                        $height    = 220;
                                        switch ($item['size_class']) {
                                            case 'panel_1':
                                                $width = 226;

                                                break;
                                            case 'panel_2':
                                                $width = 470;
                                                break;
                                            case 'panel_3':
                                                $width = 714;
                                                break;
                                            case 'panel_4':
                                                $width = 958;
                                                break;
                                            case 'panel_5':
                                                $width = 1202;
                                                break;

                                        }

                                        $image_website = 'wi.php?id='.$image_key.'&s='.$width.'x'.$height;

                                    }


                                    $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_website'] = $image_website;



                            }
                        }
                    }
                }

                break;

            case 'images':


                if (isset($block['images'])) {


                    foreach ($block['images'] as $_key => $_data) {


                        $content_data['blocks'][$block_key]['images'][$_key]['src'] = preg_replace('/image_root.php/', 'iw.php', $_data['src']);

                    }

                }


            default:


        }


    }


    return $content_data;


}
