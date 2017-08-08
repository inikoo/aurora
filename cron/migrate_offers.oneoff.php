<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:8 August 2017 at 13:58:01 CEST, Tranava, Slovakia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$_handle = fopen("sk.csv", "r");
$handle  = fopen("sk.csv", "r");

chdir('../');

require_once 'keyring/dns.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once "class.Account.php";


$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);


$dns_db = 'sk';

$dns_db_aw = 'dw';


$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$db_aw = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db_aw;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db_aw->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$account = new Account($db);


date_default_timezone_set($account->data['Account Timezone']);


require_once 'utils/get_addressing.php';
require_once 'utils/object_functions.php';

require_once 'utils/parse_natural_language.php';
require_once 'class.User.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Part.php';
require_once 'class.Agent.php';
require_once 'class.Image.php';

require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';
include_once 'utils/currency_functions.php';
require_once 'conf/footer_data.php';
require_once 'conf/header_data.php';


$_user = new User('handle', 'raul', 'Contractor');

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => $_user->id,
    'Date'         => gmdate('Y-m-d H:i:s')
);


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
        $deal      = $gold_reward_campaign->add_deal($deal_data);

        $component_data = array(
            'Deal Component Terms Type' => 'Order Interval',
            'Deal Component Trigger'    => 'Order',

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
        $deal      = $gold_reward_campaign->add_deal($deal_data);


        $families_category_data = array(
            'Category Code'      => 'Marketing.'.$store->get('Store Code'),
            'Category Label'     => 'Marketing Families',
            'Category Scope'     => 'Product',
            'Category Subject'   => 'Product',
            'Category Store Key' => $store->id


        );


        $families = $account->create_category($families_category_data);

        $fam=$families->create_category(
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
                $deal = $bulk_campaign->add_deal($deal_data);

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

                //   $deal->add_component($component_data);

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