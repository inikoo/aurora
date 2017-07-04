<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 11:40:13 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


$webpage = new Public_Webpage($webpage_key);

if (!isset($template)) {
    $template = $theme.'/'.$webpage->get('Webpage Template Filename').'.'.$theme.'.'.$website->get('Website Type').'.tpl';


}

if ($webpage->get('Webpage Template Filename') == 'register') {

    if ($logged_in) {
        header('Location: /index.php');
        exit;
    }


    if (array_key_exists("HTTP_CF_IPCOUNTRY", $_SERVER) and $_SERVER["HTTP_CF_IPCOUNTRY"] != 'XX') {
        $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
    } else {
        $country_code = $store->get('Store Home Country Code 2 Alpha');
    }

    $labels_fallback = array(
        'validation_required'           => _('This field is required'),
        'validation_same_password'      => _("Enter the same password as above"),
        'validation_minlength_password' => _("Enter at least 8 characters"),
        'validation_accept_terms'       => _("Please accept our terms and conditions to proceed"),
        'validation_handle_registered'  => _("Email address is already in registered"),
        'validation_email_invalid'      => _("Please enter a valid email address")


    );


    require_once 'utils/get_addressing.php';
    list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields, $no_required_fields) = get_address_form_data($country_code, $website->get('Website Locale'));


    require_once 'utils/get_countries.php';
    $countries = get_countries($website->get('Website Locale'));


    $smarty->assign('address_labels', $address_labels);
    $smarty->assign('labels_fallback', $labels_fallback);
    $smarty->assign('required_fields', $required_fields);
    $smarty->assign('no_required_fields', $no_required_fields);

    $smarty->assign('used_address_fields', $used_fields);
    $smarty->assign('countries', $countries);
    $smarty->assign('selected_country', $country_code);


} elseif ($webpage->get('Webpage Template Filename') == 'login') {

    if ($logged_in) {
        header('Location: /index.php');
        exit;
    }

    $labels_fallback = array(
        'validation_email_invalid'    => _("Please enter a valid email address"),
        'validation_handle_missing'   => _("Please enter your registered email address"),
        'validation_password_missing' => _("Please enter your password"),


    );
    $smarty->assign('labels_fallback', $labels_fallback);


} elseif ($webpage->get('Webpage Template Filename') == 'welcome') {

    if (!$logged_in) {
        header('Location: /index.php');
        exit;
    }


} elseif ($webpage->get('Webpage Template Filename') == 'categories_showcase') {

    include_once 'class.Public_Category.php';
    $category = new Public_Category($webpage->get('Webpage Scope Key'));

    $content_data = $webpage->get('Content Data');

    $smarty->assign('sections', $content_data['sections']);
    $smarty->assign('content_data', $content_data);
    $smarty->assign('category', $category);


}
elseif ($webpage->get('Webpage Template Filename') == 'products_showcase') {

    include_once 'class.Public_Category.php';
    include_once 'class.Public_Product.php';

    $category = new Public_Category($webpage->get('Webpage Scope Key'));



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
        $category->id
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



    $content_data = $webpage->get('Content Data');

    $smarty->assign('content_data', $content_data);
    $smarty->assign('category', $category);


}
elseif ($webpage->get('Webpage Template Filename') == 'product'){

    include_once 'class.Public_Product.php';

    $product = new Public_Product($webpage->get('Webpage Scope Key'));
    $smarty->assign('product', $product);


}

$content = $webpage->get('Content Data');

$smarty->assign('webpage', $webpage);
$smarty->assign('content', $content);
$smarty->assign('labels', $website->get('Localised Labels'));


$smarty->display($template, $webpage_key);


exit;


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

    /*


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

*/

    if ($webpage->get('Webpage State') == 'Offline') {


        $url = $_SERVER['REQUEST_URI'];
        $url = preg_replace('/^\//', '', $url);
        $url = preg_replace('/\?.*$/', '', $url);

        $original_url = $url;


        header("Location: /404.php?&url=$url&original_url=$original_url");

        exit;
    }

    //'System','Info','Department','Family','Product','FamilyCategory','ProductCategory','Thanks'

    /*

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

    */

    if ($logged_in) {
        $webpage->customer = $customer;
        $webpage->order    = $order_in_process;
    }

    //$smarty->assign('logged', $logged_in);


    //$webpage->site            = $site;
    //$webpage->user            = $user;
    //$webpage->logged          = $logged_in;
    //$webpage->currency        = $store->data['Store Currency Code'];
    //$webpage->currency_symbol = currency_symbol($store->data['Store Currency Code']);
    //$webpage->customer        = $customer;


    $smarty->assign('store', $store);
    $smarty->assign('webpage', $webpage);
    $smarty->assign('website', $website);

    /*

    if ($webpage->data['Webpage Scope'] == 'Category Products') {


        include_once 'class.Public_Category.php';
        include_once 'class.Public_Webpage.php';
        include_once 'class.Public_Product.php';
        include_once 'class.Public_Customer.php';
        include_once 'class.Public_Order.php';
        include_once 'class.Public_Website_User.php';

        $category = new Public_Category($webpage->data['Webpage Scope Key']);


        $category->load_webpage();

        $public_customer = new Public_Customer($customer->id);
        $public_order    = new Public_Order($order_in_process->id);


        if ($user == '') {
            $public_user = new Public_Website_User(0);
        } else {
            $public_user = new Public_Website_User($user->id);
        }


        $content_data = $category->webpage->get('Content Data');


        if (isset($content_data['panels'])) {
            $panels = $content_data['panels'];
        } else {
            $panels = array();
        }


        $products = array();

        $sql = sprintf(
            "SELECT  P.`Product ID`,`Product Category Index Content Published Data` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`, ifnull(`Product Category Index Published Stack`,99999999),`Product Code File As` ",
            $category->id
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
            $category->webpage->id
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
        $smarty->assign('category', $category);
        $smarty->assign('customer', $public_customer);
        $smarty->assign('order', $public_order);
        $smarty->assign('user', $public_user);

        $smarty->assign('webpage', $category->webpage);

    }
    else {
        if ($webpage->data['Webpage Scope'] == 'Category Categories') {


            include_once 'class.Public_Category.php';
            include_once 'class.Public_Webpage.php';
            include_once 'class.Public_Product.php';
            include_once 'class.Public_Customer.php';
            include_once 'class.Public_Order.php';
            include_once 'class.Public_Website_User.php';


            $public_webpage = new Public_Webpage($webpage->id);
            $public_webpage->load_scope();

            $category = $public_webpage->scope;

            //  $category=new Public_Category('root_key_code', $store->get('Store Department Category Key'), $department->get('Product Department Code'));


            $category->load_webpage();


            $public_customer = new Public_Customer($customer->id);
            $public_order    = new Public_Order($order_in_process->id);


            if ($user == '') {
                $public_user = new Public_Website_User(0);
            } else {
                $public_user = new Public_Website_User($user->id);
            }


            $content_data = $category->webpage->get('Content Data');

            $smarty->assign('content_data', $content_data);

            $smarty->assign('sections', $content_data['sections']);


            $smarty->assign('category', $category);
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

    */


}


$smarty->display('webpage.tpl', $webpage_key);


?>
