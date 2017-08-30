<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 11:40:13 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/


$webpage = new Public_Webpage($webpage_key);
if($webpage->id) {


    $content = $webpage->get('Content Data');
    $content_data =$content;

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


        require_once 'utils/get_addressing.php';
        list($address_format, $address_labels, $used_fields, $hidden_fields, $required_fields, $no_required_fields) = get_address_form_data($country_code, $website->get('Website Locale'));


        require_once 'utils/get_countries.php';
        $countries = get_countries($website->get('Website Locale'));


        $smarty->assign('address_labels', $address_labels);

        $smarty->assign('required_fields', $required_fields);
        $smarty->assign('no_required_fields', $no_required_fields);

        $smarty->assign('used_address_fields', $used_fields);
        $smarty->assign('countries', $countries);
        $smarty->assign('selected_country', $country_code);
        $template = $theme.'/register.'.$theme.'.'.$website->get('Website Type').'.tpl';

    } elseif ($webpage->get('Webpage Template Filename') == 'login') {

        if ($logged_in) {
            header('Location: /index.php');
            exit;
        }

        if (isset($_REQUEST['fp'])) {
            $smarty->assign('display', 'forgot_password');
        } else {
            $smarty->assign('display', 'login');
        }


        $template = $theme.'/login.'.$theme.'.'.$website->get('Website Type').'.tpl';
    } elseif ($webpage->get('Webpage Template Filename') == 'search') {

        if (!empty($_REQUEST['q'])) {
            $search_query = $_REQUEST['q'];
        } else {
            $search_query = '';
        }
        $smarty->assign('search_query', $search_query);

        $template = $theme.'/search.'.$theme.'.'.$website->get('Website Type').'.tpl';
    } elseif ($webpage->get('Webpage Template Filename') == 'welcome') {

        if (!$logged_in) {
            header('Location: /index.php');
            exit;
        }

        $template = $theme.'/webpage_blocks.'.$theme.'.'.$website->get('Website Type').'.tpl';

    } elseif ($webpage->get('Webpage Template Filename') == 'categories_showcase') {

        include_once 'class.Public_Category.php';
        $category = new Public_Category($webpage->get('Webpage Scope Key'));


        $smarty->assign('sections', $content_data['sections']);
        $smarty->assign('content_data', $content_data);
        $smarty->assign('category', $category);

        $template = $theme.'/categories_showcase.'.$theme.'.'.$website->get('Website Type').'.tpl';
    } elseif ($webpage->get('Webpage Template Filename') == 'products_showcase') {

        include_once 'class.Public_Category.php';
        include_once 'class.Public_Product.php';

        $category = new Public_Category($webpage->get('Webpage Scope Key'));


        if (isset($content_data['panels'])) {
            $panels = $content_data['panels'];
        } else {
            $panels = array();
        }




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




        $smarty->assign('products', $products);
        $smarty->assign('related_products', $related_products);




        $smarty->assign('content_data', $content_data);
        $smarty->assign('category', $category);

        //print $theme.'/products_showcase.'.$theme.'.'.$website->get('Website Type').'.tpl';
        //exit;

        $template = $theme.'/products_showcase.'.$theme.'.'.$website->get('Website Type').'.tpl';


    } elseif ($webpage->get('Webpage Template Filename') == 'product') {

        include_once 'class.Public_Product.php';

        $product = new Public_Product($webpage->get('Webpage Scope Key'));
        $smarty->assign('product', $product);


        $origin              = $product->get('Origin');
        $cpnp                = $product->get('CPNP Number');
        $materials           = $product->get('Materials');
        $weight              = $product->get('Unit Weight');
        $dimensions          = $product->get('Unit Dimensions');
        $product_attachments = $product->get_attachments();
        $barcode             = $product->get('Barcode Number');

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





        $template = $theme.'/product.'.$theme.'.'.$website->get('Website Type').$template_suffix.'.tpl';


    } elseif ($webpage->get('Webpage Template Filename') == 'reset_password') {
        include 'reset_password.inc.php';
        $template = $theme.'/reset_password.'.$theme.'.'.$website->get('Website Type').'.tpl';

    } elseif ($webpage->get('Webpage Template Filename') == 'not_found') {
        $template = $theme.'/not_found.'.$theme.'.'.$website->get('Website Type').'.tpl';
    } elseif ($webpage->get('Webpage Template Filename') == 'checkout') {


        if (isset($order) and $order->id) {

            $order->update_totals();
            $placeholders = array(

                '[Order Number]' => $order->get('Public ID'),
                '[Order Amount]' => $order->get('Basket To Pay Amount'),

            );

            if (isset($content['_bank_header'])) {
                $content['_bank_header'] = strtr($content['_bank_header'], $placeholders);
            }
            if (isset($content['_bank_footer'])) {
                $content['_bank_footer'] = strtr($content['_bank_footer'], $placeholders);
            }


            $template = $theme.'/checkout.'.$theme.'.'.$website->get('Website Type').'.tpl';
        } else {


            $template = $theme.'/checkout_no_order.'.$theme.'.'.$website->get('Website Type').'.tpl';
        }

    } elseif ($webpage->get('Webpage Template Filename') == 'profile') {

        if (!$logged_in) {
            header('Location: /index.php');
            exit;
        }

        require_once 'utils/get_addressing.php';
        require_once 'utils/get_countries.php';

        list(
            $invoice_address_format, $invoice_address_labels, $invoice_used_fields, $invoice_hidden_fields, $invoice_required_fields, $invoice_no_required_fields
            ) = get_address_form_data($customer->get('Customer Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

        $smarty->assign('invoice_address_labels', $invoice_address_labels);
        $smarty->assign('invoice_required_fields', $invoice_required_fields);
        $smarty->assign('invoice_no_required_fields', $invoice_no_required_fields);
        $smarty->assign('invoice_used_address_fields', $invoice_used_fields);


        list(
            $delivery_address_format, $delivery_address_labels, $delivery_used_fields, $delivery_hidden_fields, $delivery_required_fields, $delivery_no_required_fields
            ) = get_address_form_data($customer->get('Customer Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

        $smarty->assign('delivery_address_labels', $delivery_address_labels);
        $smarty->assign('delivery_required_fields', $delivery_required_fields);
        $smarty->assign('delivery_no_required_fields', $delivery_no_required_fields);
        $smarty->assign('delivery_used_address_fields', $delivery_used_fields);


        $countries = get_countries($website->get('Website Locale'));
        $smarty->assign('countries', $countries);


        $template = $theme.'/profile.'.$theme.'.'.$website->get('Website Type').'.tpl';


    } else {


        if ($webpage->get('Webpage Code') == 'thanks.sys') {


            if (empty($_REQUEST['order_key']) or !is_numeric($_REQUEST['order_key']) or !$logged_in) {
                header('Location: /index.php');
                exit;
            }

            $placed_order = get_object('Order', $_REQUEST['order_key']);

            if (!$placed_order->id OR $placed_order->get('Order Customer Key') != $customer->id) {
                header('Location: /index.php');
                exit;
            }
            require_once 'utils/placed_order_functions.php';


            $smarty->assign('placed_order', $placed_order);

            $placeholders = array(
                '[Greetings]'     => $customer->get_greetings(),
                '[Customer Name]' => $customer->get('Name'),
                '[Name]'          => $customer->get('Customer Main Contact Name'),
                '[Name,Company]'  => preg_replace(
                    '/^, /', '', $customer->get('Customer Main Contact Name').($customer->get('Customer Company Name') == '' ? '' : ', '.$customer->get('Customer Company Name'))
                ),
                '[Signature]'     => $webpage->get('Signature'),
                '[Order Number]'  => $placed_order->get('Public ID'),
                '[Order Amount]'  => $placed_order->get('To Pay'),
                '[Pay Info]'      => get_pay_info($placed_order, $website, $smarty),
                '[Order]'         => $smarty->fetch($theme.'/placed_order.'.$theme.'.EcomB2B.tpl')


            );

            foreach ($content['blocks'] as $block_key => $block) {

                if (isset($content['blocks'][$block_key]['_text'])) {
                    $content['blocks'][$block_key]['_text'] = strtr($content['blocks'][$block_key]['_text'], $placeholders);
                }

            }


        } elseif ($webpage->get('Webpage Code') == 'basket.sys') {


            if (isset($order) and $order->id) {

                $order->update_totals();

                require_once 'utils/get_addressing.php';
                require_once 'utils/get_countries.php';

                list(
                    $invoice_address_format, $invoice_address_labels, $invoice_used_fields, $invoice_hidden_fields, $invoice_required_fields, $invoice_no_required_fields
                    ) = get_address_form_data($order->get('Order Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

                $smarty->assign('invoice_address_labels', $invoice_address_labels);
                $smarty->assign('invoice_required_fields', $invoice_required_fields);
                $smarty->assign('invoice_no_required_fields', $invoice_no_required_fields);
                $smarty->assign('invoice_used_address_fields', $invoice_used_fields);


                list(
                    $delivery_address_format, $delivery_address_labels, $delivery_used_fields, $delivery_hidden_fields, $delivery_required_fields, $delivery_no_required_fields
                    ) = get_address_form_data($order->get('Order Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

                $smarty->assign('delivery_address_labels', $delivery_address_labels);
                $smarty->assign('delivery_required_fields', $delivery_required_fields);
                $smarty->assign('delivery_no_required_fields', $delivery_no_required_fields);
                $smarty->assign('delivery_used_address_fields', $delivery_used_fields);


                $countries = get_countries($website->get('Website Locale'));
                $smarty->assign('countries', $countries);

            } else {


                $template = $theme.'/basket_no_order.'.$theme.'.'.$website->get('Website Type').'.tpl';
            }


        }


        $template = $theme.'/webpage_blocks.'.$theme.'.'.$website->get('Website Type').'.tpl';



    }



    $smarty->assign('webpage', $webpage);
    $smarty->assign('content', $content);
    $smarty->assign('labels', $website->get('Localised Labels'));


    //print_r($website->get('Localised Labels'));


    $smarty->display($template);

}else{
    print 'error';

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


?>
