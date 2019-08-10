<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2018 at 12:50:46 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018 Inikoo

 Version 3.0
*/

function parse_customer_list($data, $db) {

     // print_r($data);

    $table = '`Customer Dimension` C  ';
    $group = '';

    $with_otf = false;

    $where = '';
    if ($data['Customer Status Active'] == 'No' or $data['Customer Status Loosing'] == 'No' or $data['Customer Status Lost'] == 'No') {
        $tmp = ' and `Customer Type by Activity` in ( ';
        if ($data['Customer Status Active'] == 'Yes') {
            $tmp .= ' "Active", ';
        }
        if ($data['Customer Status Loosing'] == 'Yes') {
            $tmp .= ' "Loosing", ';
        }
        if ($data['Customer Status Lost'] == 'Yes') {
            $tmp .= ' "Lost" ';
        }

        $tmp = preg_replace('/\, $/', '', $tmp);

        $tmp   .= ')';
        $where .= $tmp;
    }


    if ($data['Register Date From'] != '' or $data['Register Date To'] != '') {
        $tmp = '';
        if ($data['Register Date From'] != '') {
            $tmp .= sprintf(' and  `Customer First Contacted Date`>=%s ', prepare_mysql($data['Register Date From']));
        }

        if ($data['Register Date To'] != '') {
            $tmp .= sprintf(' and  `Customer First Contacted Date`<=%s ', prepare_mysql($data['Register Date To']));
        }
        $where .= $tmp;
    }


    if ($data['Location'] != '') {
        $tmp           = '';
        $country_codes = array();

        include_once 'class.Country.php';
        $locations = preg_split('/\,/i', $data['Location']);

        foreach ($locations as $location) {
            $location = trim($location);
            if ($location == '') {
                continue;
            }
            if (strlen($location) == 2) {
                $country_code                 = addslashes(strtoupper($location));
                $country_codes[$country_code] = $country_code;

              } else {
                $country = new Country('find', $location);

                if ($country->id and $country->get('Country Code') != 'UNK') {
                    $country_codes[$country->get('Country 2 Alpha Code')] = $country->get('Country 2 Alpha Code');
                }


            }
        }

        //   print_r($country_codes);

        if (count($country_codes) > 0) {
            $tmp .= sprintf(' and `Customer Contact Address Country 2 Alpha Code` in (\'%s\') ', join("','", $country_codes));
        }

        $where .= $tmp;

    }

    if (isset($data['Customer Send Newsletter'])) {
        $where .= sprintf(' and `Customer Send Newsletter` =%s ', prepare_mysql($data['Customer Send Newsletter']));
    }
    if (isset($data['Customer Send Email Marketing'])) {
        $where .= sprintf(' and `Customer Send Email Marketing` =%s ', prepare_mysql($data['Customer Send Email Marketing']));
    }
    if (isset($data['Customer Send Postal Marketing'])) {
        $where .= sprintf(' and `Customer Send Postal Marketing` =%s ', prepare_mysql($data['Customer Send Postal Marketing']));
    }
    if ($data['With Email'] == 'Yes') {
        $where .= sprintf(' and `Customer Main Plain Email` !="" ');
    } elseif ($data['With Email'] == 'No') {
        $where .= sprintf(' and `Customer Main Plain Email` ="" ');
    }
    if ($data['With Telephone'] == 'Yes') {
        $where .= sprintf(' and `Customer Main Plain Telephone` !="" ');
    } elseif ($data['With Telephone'] == 'No') {
        $where .= sprintf(' and `Customer Main Plain Telephone` ="" ');
    }

    if ($data['With Mobile'] == 'Yes') {
        $where .= sprintf(' and `Customer Main Plain Mobile` !="" ');
    } elseif ($data['With Mobile'] == 'No') {
        $where .= sprintf(' and `Customer Main Plain Mobile` ="" ');
    }

    if ($data['With Tax Number'] == 'Yes') {
        $where .= sprintf(' and `Customer Tax Number Valid` ="Yes"     ');
    } elseif ($data['With Tax Number'] == 'No') {
        $where .= sprintf(' and `Customer Main Plain Mobile` !="Yes" ');
    }


    if ($data['Assets'] != '') {


        $store=get_object('Store',$data['store_key']);

        $tmp         = '';
        $product_ids = array();
        $families = array();
        $departments = array();

        $categories = array();

        $asset_codes = preg_split('/\,/i', $data['Assets']);

        foreach ($asset_codes as $asset_code) {
            $asset_code = trim($asset_code);
            if ($asset_code == '') {
                continue;
            }


            $sql = sprintf('select `Product ID` from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d ', prepare_mysql($asset_code), $data['store_key']);

           // print $sql;

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $product_ids[$row['Product ID']] = $row['Product ID'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf('select `Category Key` from `Category Dimension` where   `Category Branch Type`="Head"  and  `Category Code`=%s and `Category Root Key`=%d ', prepare_mysql($asset_code), $store->get('Store Family Category Key'));

            // print $sql;

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $families[$row['Category Key']] = $row['Category Key'];
                }
            }


            $sql = sprintf('select `Category Key` from `Category Dimension` where   `Category Branch Type`="Head"  and  `Category Code`=%s and `Category Root Key`=%d ', prepare_mysql($asset_code), $store->get('Store Department Category Key'));

            // print $sql;

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $departments[$row['Category Key']] = $row['Category Key'];
                }
            }


            $sql = sprintf(
                'select `Subject Key` from `Category Dimension` C left join `Category Bridge` B on (B.`Category Key`=C.`Category Key`) where `Category Code`=%s and `Category Store Key`=%d and `Category Scope`="Product" and  `Category Subject`="Product" and `Category Root Key`!=%d  ',
                prepare_mysql($asset_code), $data['store_key'],$store->get('Store Family Category Key')
            );
            //  print $sql;
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $product_ids[$row['Subject Key']] = $row['Subject Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

            $sql = sprintf(
                'select `Subject Key` from `Category Dimension` C left join `Category Bridge` B on (B.`Category Key`=C.`Category Key`) where `Category Code`=%s and `Category Store Key`=%d and `Category Scope`="Product" and  `Category Subject`="Category"  and `Category Root Key`!=%d ',
                prepare_mysql($asset_code), $data['store_key'],$store->get('Store Department Category Key')
            );
            //  print $sql;


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $categories[$row['Subject Key']] = $row['Subject Key'];


                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            if (count($categories) > 1) {

                $sql = sprintf('select `Subject Key` from  `Category Bridge` where  `Subject`="Product"  and `Category Key` in (%s) ', join(',', $categories));

                //  print $sql;

                if ($result = $db->query($sql)) {
                    foreach ($result as $row) {
                        $product_ids[$row['Subject Key']] = $row['Subject Key'];
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }

            // print_r($product_ids);


        }

        //   print_r($country_codes);

        if (count($product_ids) > 0) {
            $with_otf = true;
            $table    = '`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
            $group    = ' group by C.`Customer Key`';

            $tmp .= sprintf(' and `Product ID` in (\'%s\') ', join("','", $product_ids));
        }

        if (count($families) > 0) {
            $with_otf = true;
            $table    = '`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
            $group    = ' group by C.`Customer Key`';

            $tmp .= sprintf(' and `OTF Category Family Key` in (\'%s\') ', join("','", $families));
        }


        if (count($departments) > 0) {
            $with_otf = true;
            $table    = '`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
            $group    = ' group by C.`Customer Key`';

            $tmp .= sprintf(' and `OTF Department Family Key` in (\'%s\') ', join("','", $departments));
        }



        $where .= $tmp;

    }


    if ($data['Ordered Date From'] != '' or $data['Ordered Date To'] != '') {

        $table    = '`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
        $group    = ' group by C.`Customer Key`';
        $with_otf = true;
        $tmp      = '';
        if ($data['Ordered Date From'] != '') {
            $tmp .= sprintf(' and  `Order Date`>=%s ', prepare_mysql($data['Ordered Date From']));
        }

        if ($data['Ordered Date To'] != '') {
            $tmp .= sprintf(' and  `Order Date`<=%s ', prepare_mysql($data['Ordered Date To']));
        }
        $where .= $tmp;
    }


    if ($with_otf and ($data['Order State Basket'] == 'No' or $data['Order State Processing'] == 'No' or $data['Order State Dispatched'] == 'No' or $data['Order State Cancelled'] == 'No')) {
        //'In Process by Customer','Submitted by Customer','In Process','Ready to Pick','Picking','Ready to Pack','Ready to Ship','Dispatched','Unknown','Packing','Packed','Packed Done','Cancelled','No Picked Due Out of Stock','No Picked Due No Authorised','No Picked Due Not Found','No Picked Due Other','Suspended','Cancelled by Customer','Out of Stock in Basket'
        $tmp = ' and `Current Dispatching State` in ( ';
        if ($data['Order State Basket'] == 'Yes') {
            $tmp .= ' "In Process","Out of Stock in Basket" ';
        }
        if ($data['Order State Processing'] == 'Yes') {
            $tmp .= ' "Submitted by Customer","Ready to Pick" ';
        }
        if ($data['Order State Dispatched'] == 'Yes') {
            $tmp .= ' "Dispatched", ';
        }
        if ($data['Order State Cancelled'] == 'Yes') {
            $tmp .= ' "Cancelled" ';
        }

        $tmp = preg_replace('/\, $/', '', $tmp);

        $tmp   .= ')';
        $where .= $tmp;

    }

    //print $where;
    return array(
        $table,
        $where,
        $group
    );

}


?>
