<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:8 August 2017 at 13:58:01 CEST, Tranava, Slovakia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.User.php';


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Migration from Inikoo)',
    'Author Alias' => 'System (Migration from Inikoo)',


);


$_user = new User('handle', 'raul', 'Contractor');

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => $_user->id,
    'Date'         => gmdate('Y-m-d H:i:s')
);



$store_key = 3;



$sql = sprintf('SELECT `Store Key` FROM `Store Dimension`  where `Store Key`=%d ', $store_key);
//print $sql;
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


          print_r($row);

        $store = get_object('Store', $row['Store Key']);


        if ($store->get('Store Order Recursion Campaign Key') == '') {
            $categories_deals_data = array(
                'Deal Campaign Code'       => 'FO',
                'Deal Campaign Name'       => 'First order offer',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',


            );

            $categories_deals = $store->create_campaign($categories_deals_data);


        }


    }
}


$sql = sprintf('SELECT `Deal Component Key`,`Deal Component Deal Key`,`Deal Component Campaign Key` FROM `Deal Component Dimension` where `Deal Component Store Key`=%d ',$store_key);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        //print_r($row);


        $deal           = get_object('Deal', $row['Deal Component Deal Key']);
        $deal_component = get_object('DealComponent', $row['Deal Component Key']);
        $deal_campaign  = get_object('DealCampaign', $row['Deal Component Campaign Key']);


        $store = get_object('Store', $deal_component->data['Deal Component Store Key']);


        if ($deal_component->data['Deal Component Campaign Key'] == $store->get('Store Order Recursion Campaign Key')) {
            $icon = '<i class="fa fa-tag gold-text "  aria-hidden="true"></i>';
        } elseif ($deal_component->data['Deal Component Campaign Key'] == $store->get('Store Bulk Discounts Campaign Key')) {
            $icon = '<i class="fa fa-tag  " style="color:#31B96E" aria-hidden="true"></i>';
        } else {
            $icon = '<i class="fa fa-tag  "  aria-hidden="true"></i>';
        }

        $deal_campaign->update(
            array(
                'Deal Campaign Icon' => $icon,
                'Deal Campaign Name' => $deal_campaign->get('Deal Campaign Name'),

            ), 'no_history'

        );

        $deal->update(
            array(
                'Deal Term Label' => $deal->get('Deal XHTML Terms Description Label'),
            ), 'no_history'

        );


        // NOte for migration in dw why may need to use 'Deal Component XHTML Allowance Description Label' instead

        $deal_component->update(
            array(

                'Deal Component Allowance Label' => $deal_component->get('Deal Component Allowance Description')
            ), 'no_history'

        );


    }

}


$sql = sprintf('SELECT `Deal Campaign Store Key`,`Deal Campaign Key` FROM `Deal Campaign Dimension`   left join `Store Dimension` on (`Store Key`=`Deal Campaign Store Key`)  where `Store Key`=%d ',$store_key);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $store = get_object('Store', $row['Deal Campaign Store Key']);

        $campaign = get_object('DealCampaign', $row['Deal Campaign Key']);


        print $store->get('Store Order Recursion Campaign Key').' '.$campaign->id."\n";
        if ($store->get('Store Order Recursion Campaign Key') == $campaign->id) {
            $campaign->fast_update(array('Deal Campaign Code' => 'OR'));
        }
        if ($store->get('Store Bulk Discounts Campaign Key') == $campaign->id) {
            $campaign->fast_update(array('Deal Campaign Code' => 'VL'));
        }
        if ($store->get('Store First Order Campaign Key') == $campaign->id) {
            $campaign->fast_update(array('Deal Campaign Code' => 'FO'));
        }


        $categories_deals_data = array(
            'Deal Campaign Code'       => 'CA',
            'Deal Campaign Name'       => 'Family offers',
            'Deal Campaign Valid From' => gmdate('Y-m-d'),
            'Deal Campaign Valid To'   => '',


        );

        $categories_deals = $store->create_campaign($categories_deals_data);


        $sql = sprintf(
            'update `Deal Dimension` set `Deal Campaign Key`=%d where `Deal Campaign Key` is null and `Deal Store Key`=%d ',
            $categories_deals->id,
            $store->id
        );

        $db->exec($sql);


        $categories_deals->update_usage();
        $categories_deals->update_number_of_deals();
        $categories_deals->update_websites();


        $categories_deals_data = array(
            'Deal Campaign Code'       => 'CU',
            'Deal Campaign Name'       => 'Customers offers',
            'Deal Campaign Valid From' => gmdate('Y-m-d'),
            'Deal Campaign Valid To'   => '',


        );

        $categories_deals = $store->create_campaign($categories_deals_data);


        $categories_deals_data = array(
            'Deal Campaign Code'       => 'VO',
            'Deal Campaign Name'       => 'Vouchers',
            'Deal Campaign Valid From' => gmdate('Y-m-d'),
            'Deal Campaign Valid To'   => '',


        );

        $categories_deals = $store->create_campaign($categories_deals_data);

        $categories_deals_data = array(
            'Deal Campaign Code'       => 'SO',
            'Deal Campaign Name'       => 'Store offers',
            'Deal Campaign Valid From' => gmdate('Y-m-d'),
            'Deal Campaign Valid To'   => '',


        );

        $categories_deals = $store->create_campaign($categories_deals_data);


        $categories_deals_data = array(
            'Deal Campaign Code'       => 'PO',
            'Deal Campaign Name'       => 'Product offers',
            'Deal Campaign Valid From' => gmdate('Y-m-d'),
            'Deal Campaign Valid To'   => '',


        );

        $categories_deals = $store->create_campaign($categories_deals_data);

    }
}


$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="VO" ', prepare_mysql('<i class="far fa-money-bill-wave"></i>'));
$db->exec($sql);

$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="VL" ', prepare_mysql('<i class="far fa-ball-pile"></i>'));
$db->exec($sql);

$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="SO" ', prepare_mysql('<i class="far fa-badge-percent"></i>'));
$db->exec($sql);

$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="OR" ', prepare_mysql('<i class="far fa-repeat-1"></i>'));
$db->exec($sql);

$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="CU" ', prepare_mysql('<i class="far fa-user-crown"></i>'));
$db->exec($sql);

$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="FO" ', prepare_mysql('<i class="far fa-trophy-alt"></i>'));
$db->exec($sql);


$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="CA" ', prepare_mysql('<i class="far fa-bullseye-arrow"></i>'));
$db->exec($sql);

$sql = sprintf('update `Deal Campaign Dimension` set `Deal Campaign Icon`=%s where `Deal Campaign Code`="PO" ', prepare_mysql('<i class="far fa-crosshairs"></i>'));
$db->exec($sql);






$category_offers=get_object('campaign_code-store_key','CA|'.$store_key);
$voucher_offers=get_object('campaign_code-store_key','VO|'.$store_key);
$customer_offers=get_object('campaign_code-store_key','CU|'.$store_key);
$store_offers=get_object('campaign_code-store_key','SO|'.$store_key);
$product_offers=get_object('campaign_code-store_key','PO|'.$store_key);

$sql = sprintf('SELECT `Deal Component Key`,`Deal Component Deal Key`,`Deal Component Campaign Key` FROM `Deal Component Dimension` where `Deal Component Store Key`=%d ',$store_key);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        //print_r($row);


        $deal           = get_object('Deal', $row['Deal Component Deal Key']);
        $deal_component = get_object('DealComponent', $row['Deal Component Key']);
        $deal_campaign  = get_object('DealCampaign', $row['Deal Component Campaign Key']);


        $deal_component->fast_update(
            array('Deal Component Allowance Target Label'=>strip_tags($deal_component->get('Deal Component Allowance Target Label')))
        );


//'Category','Department','Family','Product','Order','Customer','Customer Category','Customer List'

        if(!in_array($deal_campaign->get('Code'),array('OR','VL','VO','FO'))){



//'Category For Every Quantity Any Product Ordered','Category Quantity Ordered','Category Quantity Ordered AND Voucher','Department Quantity Ordered','Every Order','Family For Every Quantity Any Product Ordered','Department For Every Quantity Any Product Ordered','Voucher AND Order Interval','Amount AND Order Number','Amount AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Amount','Order Total Net Amount','Order Total Net Amount AND Order Number','Order Total Net Amount AND Shipping Country','Order Total Net Amount AND Order Interval','Order Items Net Amount','Order Items Net Amount AND Order Number','Order Items Net Amount AND Shipping Country','Order Items Net Amount AND Order Interval','Order Total Amount','Order Total Amount AND Order Number','Order Total Amount AND Shipping Country','Order Total Amount AND Order Interval','Order Interval','Product Quantity Ordered','Family Quantity Ordered','Order Number','Shipping Country','Voucher','Department For Every Quantity Ordered','Family For Every Quantity Ordered','Product For Every Quantity Ordered AND Voucher','Product For Every Quantity Ordered'

            if(in_array($deal->get('Deal Terms Type'),array('Category For Every Quantity Ordered AND Voucher','Category For Every Quantity Any Product Ordered AND Voucher','Category Quantity Ordered AND Voucher','Voucher','Voucher AND Order Number','Voucher AND Amount','Product For Every Quantity Ordered AND Voucher')  )) {

                $deal_component->fast_update(array('Deal Component Campaign Key'=>$voucher_offers->id));
                $deal->fast_update(array('Deal Campaign Key'=>$voucher_offers->id));
            }elseif(in_array($deal_component->get('Deal Component Trigger'),array('Product')  )){

                $deal_component->fast_update(array('Deal Component Campaign Key'=>$product_offers->id));
                $deal->fast_update(array('Deal Campaign Key'=>$product_offers->id));

            }elseif(in_array($deal_component->get('Deal Component Trigger'),array('Category','Department','Family')  )){

                $deal_component->fast_update(array('Deal Component Campaign Key'=>$category_offers->id));
                $deal->fast_update(array('Deal Campaign Key'=>$category_offers->id));

            }elseif(in_array($deal_component->get('Deal Component Trigger'),array('Customer')  )){

                $deal_component->fast_update(array('Deal Component Campaign Key'=>$customer_offers->id));
                $deal->fast_update(array('Deal Campaign Key'=>$customer_offers->id));

            }elseif(in_array($deal_component->get('Deal Component Trigger'),array('Order')  )){

                $deal_component->fast_update(array('Deal Component Campaign Key'=>$store_offers->id));
                $deal->fast_update(array('Deal Campaign Key'=>$store_offers->id));

            }


        }





    }
}



$sql = sprintf("SELECT `Deal Campaign Key` FROM `Deal Campaign Dimension` where `Deal Campaign Store Key`=%d ",$store_key);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_campaign = get_object('DealCampaign', $row['Deal Campaign Key']);

        $deal_campaign->update_number_of_deals();


        if($deal_campaign->get('Deal Campaign Code')==''
            and $deal_campaign->get('Deal Campaign Number Active Deal Components')==0
            and $deal_campaign->get('Deal Campaign Number Suspended Deal Components')==0
            and $deal_campaign->get('Deal Campaign Number Waiting Deal Components')==0
            and $deal_campaign->get('Deal Campaign Number Finish Deal Components')==0
        ){

            $sql=sprintf('delete from `Deal Campaign Dimension`  where `Deal Campaign Key`=%d ',$deal_campaign->id);

            $db->exec($sql);

        }



    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}





include_once 'class.Category.php';


$store = get_object('Store', $store_key);

$sql = sprintf('SELECT `Deal Component Key`,`Deal Component Deal Key`,`Deal Component Campaign Key` FROM `Deal Component Dimension` where `Deal Component Store Key`=%d    ', $store_key);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


      //  print_r($row);


        $deal           = get_object('Deal', $row['Deal Component Deal Key']);
        $deal_component = get_object('DealComponent', $row['Deal Component Key']);
        $deal_campaign  = get_object('DealCampaign', $row['Deal Component Campaign Key']);




        if ($deal_component->get('Deal Component Trigger') == 'Family') {


            $sql = sprintf('select * from `Product Family Dimension` where `Product Family Key`=%d', $deal_component->get('Deal Component Trigger Key'));


            $stmt = $db->prepare($sql);
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {



                    $category = new Category('root_key_code', $store->get('Store Family Category Key'), $row['Product Family Code']);


                    if ($category->id) {



                        $deal_component->fast_update(
                            array(
                                'Deal Component Trigger'=>'Category',
                                'Deal Component Trigger Key'=>$category->id,

                            )
                        );

                        if($deal_component->get('Deal Component Allowance Target')=='Family'){
                            $deal_component->fast_update(
                                array(

                                    'Deal Component Allowance Target'=>'Category',
                                    'Deal Component Allowance Target Key'=>$category->id

                                )
                            );
                        }


                        if($deal_component->get('Deal Component Terms Type')=='Family Quantity Ordered'){
                            $deal_component->fast_update(
                                array(

                                    'Deal Component Terms Type'=>'Category Quantity Ordered'

                                )
                            );
                        }


                    } else {
                        print $row['Product Family Code'].' '.$store->get('Code')." not found\n";
                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit();
            }



        }


    }
}



$sql = sprintf('SELECT `Deal Key`,`Deal Campaign Key` FROM `Deal Dimension` where `Deal Store Key`=%d ', $store_key);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        //print_r($row);


        $deal           = get_object('Deal', $row['Deal Key']);
        $deal_campaign  = get_object('DealCampaign', $row['Deal Campaign Key']);


        if ($deal->get('Deal Trigger') == 'Family') {


            $sql = sprintf('select * from `Product Family Dimension` where `Product Family Key`=%d', $deal->get('Deal Trigger Key'));


            $stmt = $db->prepare($sql);
            if ($stmt->execute()) {
                while ($row = $stmt->fetch()) {

                    $category = new Category('root_key_code', $store->get('Store Family Category Key'), $row['Product Family Code']);


                    if ($category->id) {
                        $deal->fast_update(
                            array(
                                'Deal Trigger'=>'Category',
                                'Deal Trigger Key'=>$category->id,

                            )
                        );



                        if($deal->get('Deal Terms Type')=='Family Quantity Ordered'){
                            $deal->fast_update(
                                array(

                                    'Deal Terms Type'=>'Category Quantity Ordered'

                                )
                            );
                        }


                    } else {
                        print $row['Product Family Code'].' '.$store->get('Code')." not found\n";
                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit();
            }



        }


    }
}


$gr_offers=get_object('campaign_code-store_key','OR|'.$store_key);



$sql = sprintf('update `Deal Component Dimension` set `Deal Component Allowance Target Type`="Items"  where `Deal Component Campaign Key`=%d ',$gr_offers->id);
$db->exec($sql);

$sql = sprintf('update `Deal Component Dimension` set `Deal Component Allowance Target Type`="No Items"  where `Deal Component Campaign Key`=%d and `Deal Component Allowance Target`="Charge" ',$gr_offers->id);
$db->exec($sql);


exit;
// Here first part of the migration


exit;

$db->exec('truncate  `Deal Dimension`');
$db->exec('truncate  `Deal Component Dimension`');


$sql = sprintf('SELECT `Store Key` FROM `Store Dimension`    ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $account = get_object('Account', 1);
        $store   = get_object('Store', $row['Store Key']);

        $first_order_incentive_campaign_data = array(
            'Deal Campaign Name'       => 'First order incentive',
            'Deal Campaign Valid From' => gmdate('Y-m-d'),
            'Deal Campaign Valid To'   => '',


        );

        $first_order_incentive_campaign = $store->create_campaign($first_order_incentive_campaign_data);


        $store->update(
            array(


                'Store First Order Campaign Key' => $first_order_incentive_campaign->id,

            ), 'no_history'
        );

        $gold_reward_campaign = get_object('DealCampaign', $store->get('Store Order Recursion Campaign Key'));


        $deal_data = array(
            'Deal Name'                          => 'Order Recursion Campaign',
            'Deal Description'                   => "",
            'Deal Term Allowances'               => "",
            'Deal Term Allowances Label'         => "",
            'Deal Trigger'                       => 'Order',
            'Deal Trigger Key'                   => '0',
            'Deal Trigger XHTML Label'           => '',
            'Deal Terms Type'                    => 'Order Interval',
            'Deal Terms Description'             => "last order within 30 days",
            'Deal XHTML Terms Description Label' => "Reorder within 30 days to qualify",
            'Deal Terms'                         => '30 day',
            'Deal Terms Lock'                    => 'No',
            'Deal Allowance Target Type'         => 'No Items'


        );
        $deal      = $gold_reward_campaign->create_deal($deal_data);

        $component_data = array(
            'Deal Component Terms Type'                        => 'Order Interval',
            'Deal Component Trigger'                           => 'Order',
            'Deal Component Allowance Type'                    => 'Get Free',
            'Deal Component Allowance Target'                  => 'Charge',
            'Deal Component Allowance Target Type'             => 'No Items',
            'Deal Component Allowance Target Key'              => 0,
            'Deal Component Allowance Target XHTML Label'      => '',
            'Deal Component Allowance Description'             => 'no hanging charges',
            'Deal Component Allowance Plain Description'       => 'no hanging charges',
            'Deal Component Allowance XHTML Description'       => 'no hanging charges',
            'Deal Component XHTML Allowance Description Label' => 'no hanging charges',
            'Deal Component Allowance'                         => 1,
            'Deal Component Public'                            => 'Yes'

        );

        $deal->add_component($component_data);


        $gold_reward_campaign = get_object('DealCampaign', $store->get('Store First Order Campaign Key'));


        $deal_data = array(
            'Deal Name'                          => 'First Order Campaign',
            'Deal Description'                   => "With your first order over €100 + vat (excluding shipping) you will receive a first order bonus - worth over €100 (at retail value).",
            'Deal Term Allowances'               => "When order over €100 + vat (excluding shipping) for the first time we give you over a €100 of stock. (at retail value). &#8594; Get one from family",
            'Deal Term Allowances Label'         => "When order over €100 + vat (excluding shipping) for the first time we give you over a €100 of stock. (at retail value). &#8594; Get one from family",
            'Deal Trigger'                       => 'Order',
            'Deal Trigger Key'                   => '0',
            'Deal Trigger XHTML Label'           => '',
            'Deal Terms Type'                    => 'Order Total Net Amount And Order Number',
            'Deal Terms Description'             => "When order over €100 + vat (excluding shipping) for the first time we give you over a €100 of stock. (at retail value). &#8594; Get one from family",
            'Deal XHTML Terms Description Label' => "When order over €100 + vat (excluding shipping) for the first time we give you over a €100 of stock. (at retail value). &#8594; Get one from family",
            'Deal Terms'                         => '100;Order Items Net Amount;1',
            'Deal Terms Lock'                    => 'No',
            'Deal Allowance Target Type'         => 'No Items'


        );
        $deal      = $gold_reward_campaign->create_deal($deal_data);


        $families_category_data = array(
            'Category Code'      => 'Marketing.'.$store->get('Store Code'),
            'Category Label'     => 'Marketing Families',
            'Category Scope'     => 'Product',
            'Category Subject'   => 'Product',
            'Category Store Key' => $store->id


        );


        $families = $account->create_category($families_category_data);

        $fam = $families->create_category(
            array(
                'Category Code'  => 'FOC',
                'Category Label' => 'First Order Campaign'
            )
        );

        // print_r($fam);


        $category_key = '';

        $component_data = array(
            'Deal Component Terms Type' => 'Order Total Net Amount And Order Number',
            'Deal Component Trigger'    => 'Order',

            'Deal Component Allowance Type'                    => 'Get Free',
            'Deal Component Allowance Target'                  => 'Category',
            'Deal Component Allowance Target Type'             => 'No Items',
            'Deal Component Allowance Target Key'              => $fam->id,
            'Deal Component Allowance Target XHTML Label'      => '',
            'Deal Component Allowance Description'             => 'Get one from category',
            'Deal Component Allowance Plain Description'       => 'Get one from category',
            'Deal Component Allowance XHTML Description'       => 'Get one from category',
            'Deal Component XHTML Allowance Description Label' => 'Get one from category',
            'Deal Component Allowance'                         => '1;Bonus-01',
            'Deal Component Public'                            => 'Yes'

        );

        $deal->add_component($component_data);


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf('SELECT `Category Key`,`Category Code` ,`Category Store Key` FROM `Category Dimension`  WHERE `Category Scope`="Product"  AND  `Category Subject`="Product"  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = get_object('store', $row['Category Store Key']);


        $bulk_campaign = get_object('DealCampaign', $store->get('Store Bulk Discounts Campaign Key'));

        $sql = sprintf(
            'SELECT `Product Family Key`,`Deal Key`,`Deal Component Allowance`,`Deal Terms`,`Deal Component Allowance Plain Description` FROM `Product Family Dimension`  LEFT JOIN `Deal Dimension` ON (`Deal Trigger`="Family" AND `Deal Trigger Key`=`Product Family Key` )  LEFT JOIN `Deal Component Dimension` ON (`Deal Key`=`Deal Component Deal Key`)  WHERE `Deal Campaign Key`=3 AND `Deal Status`="Active" AND   `Product Family Code`=%s  AND `Product Family Store Key`=1 ',
            prepare_mysql($row['Category Code'])
        );


        if ($result2 = $db_aw->query($sql)) {
            if ($row2 = $result2->fetch()) {


                $category_code = $row['Category Code'];

                $qty       = $row2['Deal Terms'];
                $off       = $row2['Deal Component Allowance Plain Description'];
                $off_ratio = $row2['Deal Component Allowance'];

                $deal_data = array(
                    'Deal Name'                          => 'Bulk discount '.$category_code,
                    'Deal Description'                   => "order $qty or more $category_code family products and get $off",
                    'Deal Term Allowances'               => "order $qty or more  $category_code&#8594; $off",
                    'Deal Term Allowances Label'         => "order $qty or more $category_code &#8594; $off",
                    'Deal Trigger'                       => 'Category',
                    'Deal Trigger Key'                   => $row['Category Key'],
                    'Deal Trigger XHTML Label'           => $category_code,
                    'Deal Terms Type'                    => 'Category Quantity Ordered',
                    'Deal Terms Description'             => "order $qty or more",
                    'Deal XHTML Terms Description Label' => "order $qty or more",
                    'Deal Terms'                         => $qty,
                    'Deal Terms Lock'                    => 'No',
                    'Deal Allowance Target Type'         => 'items'


                );

                // print_r($deal_data);
                $deal = $bulk_campaign->create_deal($deal_data);

                $component_data = array(
                    'Deal Component Terms Type'                   => 'Category Quantity Ordered',
                    'Deal Component Trigger'                      => 'Category',
                    'Deal Component Allowance Type'               => 'Percentage Off',
                    'Deal Component Allowance Target'             => 'Category',
                    'Deal Component Allowance Target Key'         => $row['Category Key'],
                    'Deal Component Allowance Target XHTML Label' => $category_code,
                    'Deal Component Allowance Description'        => $off,
                    'Deal Component Allowance Description'        => $off,
                    'Deal Component Allowance Plain Description'  => $off,
                    'Deal Component Allowance XHTML Description'  => $off,
                    'Deal Component Allowance'                    => $off_ratio,
                    'Deal Component Public'                       => 'Yes'

                );

                $deal->add_component($component_data);

            }
        } else {
            print_r($error_info = $db_aw->errorInfo());
            print "$sql\n";
            exit;
        }


        $gold_reward_deal = new Deal('store_name', $store->id, 'Order Recursion Campaign');


        $sql = sprintf(
            'SELECT `Product Family Key`,`Deal Component Allowance`,`Deal Component Allowance Description`,`Deal Component Allowance Plain Description` FROM `Product Family Dimension`  LEFT JOIN `Deal Component Dimension` ON (`Deal Component Allowance Target`="Family" AND `Deal Component Allowance Target Key`=`Product Family Key` )   WHERE `Deal Component Campaign Key`=1 AND `Deal Component Status`="Active" AND   `Product Family Code`=%s  AND `Product Family Store Key`=1 ',
            prepare_mysql($row['Category Code'])
        );


        if ($result2 = $db_aw->query($sql)) {
            if ($row2 = $result2->fetch()) {


                $category_code = $row['Category Code'];

                $off       = $row2['Deal Component Allowance Description'];
                $off_ratio = $row2['Deal Component Allowance'];


                $component_data = array(
                    'Deal Component Terms Type' => 'Order Interval',
                    'Deal Component Trigger'    => 'Order',

                    'Deal Component Allowance Type'                    => 'Percentage Off',
                    'Deal Component Allowance Target'                  => 'Category',
                    'Deal Component Allowance Target Key'              => $row['Category Key'],
                    'Deal Component Allowance Target XHTML Label'      => $category_code,
                    'Deal Component Allowance Description'             => $off,
                    'Deal Component Allowance Plain Description'       => $off,
                    'Deal Component Allowance XHTML Description'       => $off,
                    'Deal Component XHTML Allowance Description Label' => $off,
                    'Deal Component Allowance'                         => $off_ratio,
                    'Deal Component Public'                            => 'Yes'

                );

                $gold_reward_deal->add_component($component_data);

            }
        } else {
            print_r($error_info = $db_aw->errorInfo());
            print "$sql\n";
            exit;
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>