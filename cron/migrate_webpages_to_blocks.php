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


$where=' and `Webpage Website Key`=14';
$where=' and true';
//migrate_families();
//migrate_departments();
//exit;

//2730



migrate_thanks();
migrate_search();
migrate_profile();
migrate_favourites();
migrate_login();
migrate_register();
migrate_not_found();
migrate_offline();
migrate_checkout();
migrate_blocks();

migrate_products();



function migrate_blocks() {

    global $db,$where;
   // $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` where `Page Key`=47952 ');
   $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` where true  %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage       = get_object('Webpage', $row['Page Key']);
            $_content_data = $webpage->get('Content Data');

            if (isset($_content_data['blocks'])) {
                foreach ($_content_data['blocks'] as $key => $block) {


                    if (in_array(
                        $block['type'], array(
                                          'product',
                                          'see_also',
                                          'category_products',
                                          'blackboard',
                                          'products',
                                          'text',
                                          'images',
                                          'map',
                                          'button',
                                          'category_categories',
                                          'favourites',
                                          'image',
                                          'telephone',
                                          'iframe',
                                          'login',
                                          'register',
                                          'profile',
                                          'basket',
                                          'checkout',
                                          'thanks',
                                          'offline',
                                          'not_found',
                                          'search'
                                      )
                    )) {
                        continue;
                    } else {
                        print $block['type']."\n";

                        //print_r($row);

                      //  exit;



                        switch ($block['type']) {

                            case 'six_pack':

                              //  print_r($block);




                                $_text1 = '';
                                if ($block['columns'][0][0]['title'] != '') {
                                    $_text1 .= sprintf('<h4>%s</h4>', $block['columns'][0][0]['title']);
                                }
                                $_text1 .= $block['columns'][0][0]['text'];

                                $_text2 = '';
                                if ($block['columns'][1][0]['title'] != '') {
                                    $_text2 .= sprintf('<h4>%s</h4>', $block['columns'][1][0]['title']);
                                }
                                $_text2 .= $block['columns'][1][0]['text'];

                                $_text3 = '';
                                if ($block['columns'][2][0]['title'] != '') {
                                    $_text3 .= sprintf('<h4>%s</h4>', $block['columns'][2][0]['title']);
                                }
                                $_text3 .= $block['columns'][2][0]['text'];




                                $new_block = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => '3',
                                    'top_margin'    => 20,
                                    'bottom_margin' => 10,
                                    'text_blocks'   => array(

                                        array(
                                            'text' => $_text1
                                        ),
                                        array(
                                            'text' => $_text2
                                        ),
                                        array(
                                            'text' => $_text3
                                        )

                                    )


                                );
                                $_content_data['blocks'][$key] = $new_block;




                                $_text1 = '';
                                if ($block['columns'][0][1]['title'] != '') {
                                    $_text1 .= sprintf('<h4>%s</h4>', $block['columns'][0][1]['title']);
                                }
                                $_text1 .= $block['columns'][0][1]['text'];

                                $_text2 = '';
                                if ($block['columns'][1][1]['title'] != '') {
                                    $_text2 .= sprintf('<h4>%s</h4>', $block['columns'][1][1]['title']);
                                }
                                $_text2 .= $block['columns'][1][1]['text'];

                                $_text3 = '';
                                if ($block['columns'][2][1]['title'] != '') {
                                    $_text3 .= sprintf('<h4>%s</h4>', $block['columns'][2][1]['title']);
                                }
                                $_text3 .= $block['columns'][2][1]['text'];




                                $new_block = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => '3',
                                    'top_margin'    => 20,
                                    'bottom_margin' => 10,
                                    'text_blocks'   => array(

                                        array(
                                            'text' => $_text1
                                        ),
                                        array(
                                            'text' => $_text2
                                        ),
                                        array(
                                            'text' => $_text3
                                        )

                                    )


                                );
                                array_splice($_content_data['blocks'], $key , 0, array($new_block));



                                break 2;
                            case 'three_pack':





                                $text = '';
                                if ($block['title'] != '') {
                                    $text .= sprintf('<h1>%s</h1>', $block['title']);
                                }
                                if ($block['subtitle'] != '') {
                                    $text .= sprintf('<h4>%s</h4>', $block['subtitle']);
                                }

                                $new_block                     = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => 't1',
                                    'top_margin'    => 20,
                                    'bottom_margin' => 10,
                                    'text_blocks'   => array(

                                        array(
                                            'text' => $text
                                        )

                                    )


                                );
                                $_content_data['blocks'][$key] = $new_block;


                                $_text1 = '';
                                if ($block['columns'][0]['title'] != '') {
                                    $_text1 .= sprintf('<h4>%s</h4>', $block['columns'][0]['title']);
                                }
                                $_text1 .= $block['columns'][0]['text'];

                                $_text2 = '';
                                if ($block['columns'][1]['title'] != '') {
                                    $_text2 .= sprintf('<h4>%s</h4>', $block['columns'][1]['title']);
                                }
                                $_text2 .= $block['columns'][1]['text'];

                                $_text3 = '';
                                if ($block['columns'][2]['title'] != '') {
                                    $_text3 .= sprintf('<h4>%s</h4>', $block['columns'][2]['title']);
                                }
                                $_text3 .= $block['columns'][2]['text'];


                                $new_block = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => '3',
                                    'top_margin'    => 0,
                                    'bottom_margin' => 20,
                                    'text_blocks'   => array(

                                        array(
                                            'text' => $_text1
                                        ),
                                        array(
                                            'text' => $_text2
                                        ),
                                        array(
                                            'text' => $_text3
                                        )

                                    )


                                );

                                array_splice($_content_data['blocks'], $key - 1, 0, array($new_block));


                                //print_r($_content_data);
                                //exit;
                                break 2;

                            case 'two_one':


                                $text1 = $block['columns'][0];
                                $text2 = $block['columns'][1];

                                if ($text1['type'] == 'one_third') {

                                    $_tmp  = $text2;
                                    $text2 = $text1;
                                    $text1 = $_tmp;

                                }


                                $_text1 = '';
                                if ($text1['_title'] != '') {
                                    $_text1 .= sprintf('<h1>%s</h1>', $text1['_title']);
                                }

                                $_text1 .= $text1['_text'];

                                $_text2 = '';
                                if ($text2['_title'] != '') {
                                    $_text2 .= sprintf('<h1>%s</h1>', $text2['_title']);
                                }

                                $_text2 .= $text2['_text'];


                                $new_block                     = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => '21',
                                    'top_margin'    => 20,
                                    'bottom_margin' => 20,
                                    'text_blocks'   => array(

                                        array(
                                            'text' => $_text1
                                        ),
                                        array(
                                            'text' => $_text2
                                        )

                                    )


                                );
                                $_content_data['blocks'][$key] = $new_block;


                                break;
                            case 'counter':

                                unset($_content_data['blocks'][$key]);

                                break;


                            case 'one_pack':
                                $text = '';
                                if (!empty($block['_title'] )) {
                                    $text .= sprintf('<h1>%s</h1>', $block['_title']);
                                }
                                if (!empty($block['_subtitle'])) {
                                    $text .= sprintf('<h3>%s</h3>', $block['_subtitle']);
                                }
                                $text                          .= $block['_text'];
                                $new_block                     = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => 't1',
                                    'top_margin'    => 20,
                                    'bottom_margin' => 20,
                                    'text_blocks'   => array(

                                        array(
                                            'text' => $text
                                        )

                                    )


                                );
                                $_content_data['blocks'][$key] = $new_block;

                                break;

                            case 'two_pack':
                                $text = '';
                                if ($block['_title'] != '') {
                                    $text .= sprintf('<h1>%s</h1>', $block['_title']);
                                }
                                if ($block['_subtitle'] != '') {
                                    $text .= sprintf('<h3>%s</h3>', $block['_subtitle']);
                                }
                                $text .= $block['_text'];

                                $tooltip = (empty($block['_image_tooltip']) ? '' : $block['_image_tooltip']);

                                $image = '<div><img src="'.$block['_image'].'" title="'.$tooltip.'" alt="'.$tooltip.'"  ></div>';

                                $new_block = array(
                                    'type'          => 'text',
                                    'label'         => _('Text'),
                                    'icon'          => 'fa-font',
                                    'show'          => $block['show'],
                                    'template'      => 2,
                                    'top_margin'    => 20,
                                    'bottom_margin' => 20,
                                    'text_blocks'   => array(
                                        array(
                                            'text' => $image,
                                        ),
                                        array(
                                            'text' => $text
                                        )

                                    )


                                );

                                $_content_data['blocks'][$key] = $new_block;
                                break;
                        }


                    }


                }


                // print_r($_content_data);


                $webpage->update(
                    array(
                        'Page Store Content Data' => json_encode($_content_data)
                    ), 'no_history'
                );


            }

        }
    }


}

function migrate_thanks() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="thanks"  %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $content_data = $webpage->get('Content Data');
            print_r($content_data);

            $text = '<h1>'.$content_data['blocks'][0]['_title'].'</h1>';

            if (!empty($content_data['blocks'][0]['_subtitle'])) {
                $text .= '<h2>'.$content_data['blocks'][0]['_subtitle'].'</h2>';
            }
            $text .= $content_data['blocks'][0]['_text'];


            $new_content_data = array(
                'blocks' => array(
                    array(
                        'type'          => 'thanks',
                        'label'         => _('Thanks'),
                        'icon'          => 'fa-thumbs-up',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'text'          => $text


                    ),
                    $content_data['blocks'][1]


                )

            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }

            print_r($new_content_data);


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="search2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_search() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="search"  %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $content_data = array();

            $new_content_data = array(
                'blocks' => array(
                    array(
                        'type'          => 'search',
                        'label'         => _('Search'),
                        'icon'          => 'fa-search',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                )

            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="search2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);
            /*


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );

            */


        }
    }
}

function migrate_profile() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="profile"  %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'profile',
                        'label'         => _('Profile'),
                        'icon'          => 'fa-user',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="profile2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_favourites() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="favourites"  %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            // print_r($content_data);
            unset($content_data['undefined']);


            $labels               = array();
            $labels['with_items'] = '<h1>'.$content_data['_title'].'</h1>'.$content_data['_text'];
            $labels['no_items']   = '<h1>'.$content_data['_title'].'</h1>'.$content_data['_text_empty'];

            unset($_content_data['old_data']);


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'favourites',
                        'label'         => _('Favourites'),
                        'icon'          => 'fa-heart',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $labels


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            //          print_r($new_content_data);
            //exit;
            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="favourites2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_checkout() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="checkout"  %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            print_r($_content_data);
            //exit;

            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'checkout',
                        'label'         => _('Checkout'),
                        'icon'          => 'fa-credit-card',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="checkout2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_offline() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="offline"   %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            print_r($_content_data);

            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'offline',
                        'label'         => _('Offline page'),
                        'icon'          => 'fa-ban',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="offline2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_not_found() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="not_found"   %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            print_r($_content_data);

            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'not_found',
                        'label'         => _('Not found'),
                        'icon'          => 'fa-times-octagon',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="not_found2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_register() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="register"   %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            print_r($_content_data);

            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'register',
                        'label'         => _('Registration form'),
                        'icon'          => 'fa-registered',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="register2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_login() {

    global $db,$where;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="login"   %s ',$where);


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = get_object('Webpage', $row['Page Key']);


            $_content_data = $webpage->get('Content Data');


            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'          => 'login',
                        'label'         => _('Login'),
                        'icon'          => 'fa-sign-in-alt',
                        'show'          => 1,
                        'top_margin'    => 40,
                        'bottom_margin' => 60,
                        'labels'        => $content_data


                    )

                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="login2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);


            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );


        }
    }
}

function migrate_products() {
    global $db,$where;

    $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE `Webpage Template Filename`="product"  %s ',$where);
    //   $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE `Page Key`=2572 ');

    if ($result3 = $db->query($sql)) {
        foreach ($result3 as $row3) {


            $webpage = get_object('Webpage', $row3['Page Key']);


            $content = '';

            $_content_data = $webpage->get('Content Data');


            if (isset($_content_data['old_data'])) {
                $content_data = $_content_data['old_data'];
            } else {
                $content_data = $_content_data;
            }


            //print_r($content_data);;


            if (isset($content_data['description_block']['content'])) {

                $content = $content_data['description_block']['content'];

                if ($content == '<div class="description"></div>') {
                    $content = '';
                }

                if (preg_match('/\<div class\=\"description\"\>(.+)\<\/div\>/s', $content, $match)) {
                    $content = $match[1];
                }


                $content = preg_replace('/\<p\>\<br\>\<\/p\>/', '', $content);
                $content = preg_replace('/\<p style\=\"text-align: left;\"\><br\>\<\/p\>/', '', $content);
                $content = preg_replace('/\<p style\=\"\"\>\<br\>\<\/p\>/', '', $content);
                $content = preg_replace('/\<span>\&nbsp\;\<\/span\>/', '', $content);

                $content = preg_replace('/line-height\s*\:\s*[0-9.]+px\;/', '', $content);
                $content = preg_replace('/line-height\s*\:\s*[0-9.]+\;/', '', $content);

                $content = preg_replace('/font-size\s*\:\s*[0-9.]+px\;/', '', $content);
                $content = preg_replace('/size=\s*[0-9.]+px\;/', '', $content);


                $content = preg_replace('/\<br\>\s*$/', '', $content);


                $content = str_replace("font-family: 'Open Sans', Helvetica, Arial, sans-serif;", '', $content);
                $content = str_replace("<br><br>", '<br>', $content);

                $content = str_replace("<p><br>", '<p>', $content);
                $content = str_replace("font-family: Arial, sans-serif;", '', $content);
                $content = str_replace('class="Normal-C"', '', $content);

                $content = str_replace("font-family: Open Sans, Helvetica, Arial, sans-serif;", '', $content);

                $content = str_replace("font-family: Tahoma, Geneva, sans-serif;", '', $content);
                $content = str_replace("font-family: Arial, Helvetica, sans-serif;", '', $content);
                $content = str_replace("font-family: Ubuntu, Helvetica, Arial, sans-serif;", '', $content);
                $content = str_replace("font-family: inherit;", '', $content);
                $content = str_replace("font-family: 'Lucida Grande', 'Lucida Sans Unicode', Verdana, Arial, sans-serif;", '', $content);

                $content = str_replace("font-family: inherit;", '', $content);

                $content = str_replace('face="verdana"', '', $content);
                $content = str_replace("font-family: inherit;", '', $content);


                //  $content=str_replace('style=""','',$content);

                // if(preg_match('/font/',$content)){
                //  print $webpage->id."\n";
                //print $content."|\n";
                // }


            }


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
                case 141:
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


            $product    = get_object('Public_Product', $webpage->get('Webpage Scope Key'));
            $image_data = $product->get('Image Data');


            $image_gallery = array();
            foreach ($product->get_image_gallery() as $image_item) {
                if ($image_item['key'] != $image_data['key']) {
                    $image_gallery[] = $image_item;
                }
            }


            $new_content_data = array(
                'blocks'   => array(
                    array(
                        'type'            => 'product',
                        'label'           => _('Product'),
                        'icon'            => 'fa-cube',
                        'show'            => 1,
                        'top_margin'      => 20,
                        'bottom_margin'   => 30,
                        'text'            => $content,
                        'show_properties' => true,

                        'image'        => array(
                            'key'           => $image_data['key'],
                            'src'           => $image_data['src'],
                            'caption'       => $image_data['caption'],
                            'width'         => $image_data['width'],
                            'height'        => $image_data['height'],
                            'image_website' => $image_data['image_website']

                        ),
                        'other_images' => $image_gallery


                    ),
                    array(
                        'type'              => 'see_also',
                        'auto'              => true,
                        'auto_scope'        => 'webpage',
                        'auto_items'        => 5,
                        'auto_last_updated' => '',
                        'label'             => _('See also'),
                        'icon'              => 'fa-link',
                        'show'              => 1,
                        'top_margin'        => 0,
                        'bottom_margin'     => 40,
                        'item_headers'      => false,
                        'items'             => array(),
                        'sort'              => 'Manual',
                        'title'             => $title,
                        'show_title'        => true
                    )
                ),
                'old_data' => $_content_data
            );


            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row3);

                print_r($webpage->id);

                continue;
            }


            $sql = sprintf('UPDATE `Page Store Dimension` SET `Webpage Template Filename`="products2" WHERE `Page Key`=%d ', $webpage->id);

            $db->exec($sql);

            $webpage->update(
                array(
                    'Page Store Content Data' => json_encode($new_content_data)
                ), 'no_history'
            );

            $webpage->reindex_items();
            $webpage->refill_see_also();
            $webpage->update_navigation();

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}

function migrate_families() {

    global $db,$where;
    $left_offset = 158;



    $sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE `Webpage Template Filename`="products_showcase"  %s ',$where);
    //$sql = sprintf('SELECT `Webpage Scope Key`,`Page Key`,`Webpage Website Key` FROM `Page Store Dimension` WHERE  `Page Key`=32302 ');




    if ($result = $db->query($sql)) {
        foreach ($result as $row3) {


            print_r($row3);

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
            } else {
                $blackboard_height = $_height;
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
            $header_text='';

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

                    $header_text = mb_convert_encoding($header_text, 'UTF-8', 'UTF-8');


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
                        'sort_name'            => $product->get('Product Name'),


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
                            'sort_name'            => $product->get('Product Name'),


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
                                    'type' => 'category',

                                    'header_text'          => $category->get('Category Label'),
                                    'image_src'            => $category->get('Image'),
                                    'image_mobile_website' => '',
                                    'image_website'        => '',

                                    'webpage_key'  => $see_also_page->id,
                                    'webpage_code' => $see_also_page->get('Webpage Code'),

                                    'category_key'    => $category->id,
                                    'category_code'   => $category->get('Category Code'),
                                    'number_products' => $category->get('Product Category Active Products'),
                                    'link'            => $see_also_page->get('Webpage URL'),


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
                        'type'          => 'blackboard',
                        'label'         => _('Blackboard'),
                        'icon'          => 'fa-image',
                        'show'          => 1,
                        'top_margin'    => 20,
                        'bottom_margin' => 0,
                        'height'        => $blackboard_height,
                        'images'        => $images,
                        'texts'         => $texts
                    ),
                    array(
                        'type'              => 'category_products',
                        'label'             => _('Family'),
                        'icon'              => 'fa-cubes',
                        'show'              => 1,
                        'top_margin'        => 0,
                        'bottom_margin'     => ((count($see_also) == 0 and count($related_products) == 0) ? 40 : 0),
                        'item_headers'      => $has_header,
                        'items'             => $items,
                        'sort'              => 'Manual',
                        'new_first'         => true,
                        'out_of_stock_last' => true,
                    )
                ),
                'old_data' => $content_data
            );

            if (count($related_products) > 0) {

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
                    case 141:
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
                    'type'              => 'products',
                    'auto'              => false,
                    'auto_scope'        => 'webpage',
                    'auto_items'        => 5,
                    'auto_last_updated' => '',
                    'label'             => _('Products'),
                    'icon'              => 'fa-window-restore',
                    'show'              => 1,
                    'top_margin'        => 0,
                    'bottom_margin'     => (count($see_also) == 0 ? 30 : 0),
                    'item_headers'      => false,
                    'items'             => $related_products,
                    'sort'              => 'Manual',
                    'title'             => $title,
                    'show_title'        => true

                );
            }


            if (count($see_also) > 0) {

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
                    case 141:
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
                    'label'             => _('See also'),
                    'icon'              => 'fa-link',
                    'show'              => 1,
                    'top_margin'        => 0,
                    'bottom_margin'     => 40,
                    'item_headers'      => false,
                    'items'             => $see_also,
                    'sort'              => 'Manual',
                    'title'             => $title,
                    'show_title'        => true

                );
            }

            $x = json_encode($new_content_data);
            if ($x == '') {
                print_r($row3);

                print_r($webpage->id);

                continue;
            }


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

    global $db,$where;

    $left_offset = 158;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE  `Webpage Template Filename`="categories_showcase"   %s ',$where);
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
                                    'header_text'          => trim(strip_tags(mb_convert_encoding($item['header_text'], 'UTF-8', 'UTF-8'))),
                                    'image_src'            => $item['image_src'],
                                    'image_mobile_website' => $item['image_mobile_website'],
                                    'image_website'        => '',
                                    'webpage_key'          => $item['webpage_key'],
                                    'webpage_code'         => $item['webpage_code'],
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


?>
