<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 11:40:13 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/

include_once 'class.Public_Webpage.php';

if (!isset($webpage_key) and isset($_REQUEST['id'])) {
    $webpage_key = $_REQUEST['id'];
}

if (!isset($webpage_key)) {
    header('Location: index.php?no_page_key');
    exit;
}

if (!isset($skip_common)) {
    include_once 'common.php';
}


if (!$is_cached) {

    $webpage = new Public_Webpage($webpage_key);


    if (!$webpage->id) {

        header('Location: index.php?no_page');
        exit;
    }


    if ($webpage->get('Webpage Website Key') != $website->id) {
        header('Location: index.php?site_page_not_match');
        //    exit("No site/page not match");
        exit;
    }


    if ($webpage->get('Webpage Scope Metadata') == 'search') {
        header('Location: search.php');
        exit;
    }

    if ($webpage->get('Webpage Scope Metadata') == 'register') {
        header('Location: registration.php');
        exit;
    }
    if ($webpage->get('Webpage Scope Metadata') == 'login') {
        header('Location: login.php');
        exit;
    }
    if ($webpage->get('Webpage Scope Metadata') == 'reset_password') {
        header('Location: reset.php');
        exit;
    }

    if ($webpage->get('Webpage Scope Metadata') == 'profile') {
        header('Location: profile.php');
        exit;
    }


    if ($webpage->get('Webpage State') == 'Offline') {


        $url = $_SERVER['REQUEST_URI'];
        $url = preg_replace('/^\//', '', $url);
        $url = preg_replace('/\?.*$/', '', $url);

        $original_url = $url;
        header("Location: /404.php?&url=$url&original_url=$original_url");

        exit;
    }

    //'System','Info','Department','Family','Product','FamilyCategory','ProductCategory','Thanks'
    if (in_array(
        $webpage->data['Page Store Section Type'], array(
                                                     'Family',
                                                     'Product'
                                                 )
    )) {

        if ($order_in_process and $order_in_process->id) {
            if ($order_in_process->data['Order Current Dispatch State'] == 'Waiting for Payment Confirmation') {
                header('Location: waiting_payment_confirmation.php');
                exit;

            }
        }
    }
    $template_suffix = '';

    if ($logged_in) {
        $webpage->customer = $customer;
        $webpage->order    = $order_in_process;
    }

    $smarty->assign('logged', $logged_in);


    //$webpage->site            = $site;
    //$webpage->user            = $user;
    //$webpage->logged          = $logged_in;
    //$webpage->currency        = $store->data['Store Currency Code'];
    //$webpage->currency_symbol = currency_symbol($store->data['Store Currency Code']);
    //$webpage->customer        = $customer;


    $smarty->assign('store', $store);
    $smarty->assign('webpage', $webpage);
    $smarty->assign('website', $website);


    if ($webpage->data['Webpage Scope'] == 'Category Products') {


        include_once 'class.Public_Category.php';
        include_once 'class.Public_Webpage.php';
        include_once 'class.Public_Product.php';
        include_once 'class.Public_Customer.php';
        include_once 'class.Public_Order.php';
        include_once 'class.Public_Website_User.php';

        $public_category = new Public_Category($webpage->data['Webpage Scope Key']);


        $public_category->load_webpage();

        $public_customer = new Public_Customer($customer->id);
        $public_order    = new Public_Order($order_in_process->id);


        if ($user == '') {
            $public_user = new Public_Website_User(0);
        } else {
            $public_user = new Public_Website_User($user->id);
        }


        $content_data = $public_category->webpage->get('Content Data');


        if (isset($content_data['panels'])) {
            $panels = $content_data['panels'];
        } else {
            $panels = array();
        }


        $products = array();

        $sql = sprintf(
            "SELECT  P.`Product ID`,`Product Category Index Content Published Data` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`, ifnull(`Product Category Index Published Stack`,99999999),`Product Code File As` ",
            $public_category->id
        );

        $stack_index = 0;
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                //	print $stack_index."\n";

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


                if ($row['Product Category Index Content Published Data'] == '') {
                    $product_content_data = array('header_text' => '');
                } else {
                    $product_content_data = json_decode($row['Product Category Index Content Published Data'], true);

                }

                $products[] = array(
                    'type'        => 'product',
                    'object'      => new Public_Product($row['Product ID']),
                    'header_text' => (isset($product_content_data['header_text']) ? $product_content_data['header_text'] : '')
                );
                $stack_index++;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $related_products = array();

        $sql = sprintf(
            "SELECT `Webpage Related Product Key`,`Webpage Related Product Product ID`,`Webpage Related Product Content Published Data`  FROM `Webpage Related Product Bridge` B  LEFT JOIN `Product Dimension` P ON (`Webpage Related Product Product ID`=P.`Product ID`)  WHERE  `Webpage Related Product Page Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Webpage Related Product Published Order`",
            $public_category->webpage->id
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                if ($row['Webpage Related Product Content Published Data'] == '') {
                    $product_content_data = array('header_text' => '');
                } else {
                    $product_content_data = json_decode($row['Webpage Related Product Content Published Data'], true);

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

        //print_r($products);

        $smarty->assign('products', $products);
        $smarty->assign('related_products', $related_products);
        $smarty->assign('category', $public_category);
        $smarty->assign('customer', $public_customer);
        $smarty->assign('order', $public_order);
        $smarty->assign('user', $public_user);

        $smarty->assign('webpage', $public_category->webpage);

    } else {
        if ($webpage->data['Webpage Scope'] == 'Category Categories') {


            include_once 'class.Public_Category.php';
            include_once 'class.Public_Webpage.php';
            include_once 'class.Public_Product.php';
            include_once 'class.Public_Customer.php';
            include_once 'class.Public_Order.php';
            include_once 'class.Public_Website_User.php';


            $public_webpage = new Public_Webpage($webpage->id);
            $public_webpage->load_scope();

            $public_category = $public_webpage->scope;

            //  $public_category=new Public_Category('root_key_code', $store->get('Store Department Category Key'), $department->get('Product Department Code'));


            $public_category->load_webpage();


            $public_customer = new Public_Customer($customer->id);
            $public_order    = new Public_Order($order_in_process->id);


            if ($user == '') {
                $public_user = new Public_Website_User(0);
            } else {
                $public_user = new Public_Website_User($user->id);
            }


            $content_data = $public_category->webpage->get('Content Data');

            $smarty->assign('content_data', $content_data);

            $smarty->assign('sections', $content_data['sections']);


            $smarty->assign('category', $public_category);
            $smarty->assign('customer', $public_customer);
            $smarty->assign('order', $public_order);
            $smarty->assign('user', $public_user);


        } else {
            if ($webpage->data['Webpage Scope'] == 'Product') {
                $smarty->assign('product', $webpage->get_product_data());


                include_once 'class.Public_Category.php';
                include_once 'class.Public_Webpage.php';
                include_once 'class.Public_Product.php';
                include_once 'class.Public_Customer.php';
                include_once 'class.Public_Order.php';
                include_once 'class.Public_Website_User.php';

                $public_product = new Public_Product($webpage->get('Webpage Scope Key'));


                $public_product->load_webpage();

                $public_customer = new Public_Customer($customer->id);
                $public_order    = new Public_Order($order_in_process->id);


                if ($user == '') {
                    $public_user = new Public_Website_User(0);
                } else {
                    $public_user = new Public_Website_User($user->id);
                }


                $content_data = $public_product->webpage->get('Content Data');

                $smarty->assign('content_data', $content_data);

                $smarty->assign('public_product', $public_product);
                $smarty->assign('customer', $public_customer);
                $smarty->assign('order', $public_order);
                $smarty->assign('user', $public_user);
                $smarty->assign('webpage', $public_product->webpage);

                $origin              = $public_product->get('Origin');
                $cpnp                = $public_product->get('CPNP Number');
                $materials           = $public_product->get('Materials');
                $weight              = $public_product->get('Unit Weight');
                $dimensions          = $public_product->get('Unit Dimensions');
                $product_attachments = $public_product->get_attachments();
                $barcode             = $public_product->get('Barcode Number');

                $smarty->assign('CPNP', $cpnp);
                $smarty->assign('Materials', $materials);
                $smarty->assign('Weight', $weight);
                $smarty->assign('Dimensions', $dimensions);
                $smarty->assign('Origin', $origin);
                $smarty->assign('product_attachments', $product_attachments);
                $smarty->assign('Barcode', $barcode);


                if ($weight != '' or $dimensions != '' or $origin != '' or $cpnp != '' or $materials != '' or count($product_attachments) > 0) {
                    $has_properties_tab = true;
                } else {
                    $has_properties_tab = false;

                }

                $smarty->assign('has_properties_tab', $has_properties_tab);


            }
        }
    }


}


$footer_data = array(
    'rows' => array(
        array(
            'type'    => 'main_4',
            'columns' => array(

                array(
                    'type' => 'address',

                    'items' => array(

                        array(
                            'type'  => 'logo',
                            'src'   => 'theme_1/images/footer-logo.png',
                            'alt'   => '',
                            'title' => ''

                        ),
                        array(
                            'type' => 'text',
                            'icon' => 'fa-map-marker ',
                            'text' => '2901 Marmora Road, Glassgow,<br>Seattle, WA 98122-1090'
                        ),
                        array(
                            'type' => 'text',
                            'icon' => 'fa-phone',
                            'text' => '1 -234 -456 -7890'
                        ),
                        array(
                            'type' => 'email',
                            'text' => 'info@yourdomain.com'
                        ),
                        array(
                            'type'  => 'logo',
                            'src'   => 'theme_1/images/footer-wmap.png',
                            'alt'   => '',
                            'title' => ''

                        ),
                    ),


                ),

                array(
                    'type'   => 'links',
                    'header' => 'Useful Links',

                    'items' => array(
                        array(
                            'url'   => '#',
                            'label' => 'Home Page Variations'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Awsome Slidershows'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Features and Typography'
                        )
                    )
                ),

                array(
                    'type'   => 'links',
                    'header' => 'Useful Links 2',
                    'items'  => array(
                        array(
                            'url'   => '#',
                            'label' => 'Home Page Variations'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Awsome Slidershows'
                        ),
                        array(
                            'url'   => '#',
                            'label' => 'Features and Typography'
                        )
                    )
                ),


                array(
                    'type'   => 'text',
                    'header' => 'About Us',
                    'text'   => '
                        
                        <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined</p>
                        <br />
                        <p>An chunks as necessary, making this the first true generator on the Internet. Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover desktop publishing packages many purpose web sites.</p>

                        '
                )

            )

        ),
        array(
            'type'    => 'copyright',
            'columns' => array(
                array(
                    'type' => 'text',
                    'text' => 'Copyright © 2014 Aaika.com. All rights reserved.  <a href="#">Terms of Use</a> | <a href="#">Privacy Policy</a>'

                ),
                array(
                    'type'  => 'social_links',
                    'items' => array(
                        array(
                            'icon' => 'fa-facebook',
                            'url'  => '#'

                        )

                    ),
                    'items' => array(
                        array(
                            'icon' => 'fa-twitter',
                            'url'  => '#'

                        ),
                        array(
                            'icon' => 'fa-linkedin',
                            'url'  => '#'

                        )

                    )


                )

            )

        )

    )


);
$smarty->assign('footer_data', $footer_data);


$smarty->display('webpage.tpl', $webpage_key);


?>
