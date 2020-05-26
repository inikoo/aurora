<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2017 at 09:34:36 CET, MIjas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Cleaning closed stores)',
    'Author Alias' => 'System (Cleaning closed stores)',
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$sql = sprintf(
    "select `Store Key` from `Store Dimension`  where `Store Status`='Closed'  order by `Store Key` desc  "
);


$stores = [];
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = get_object('Store', $row['Store Key']);

        $stores[] = $store;
        $store->update_new_products();
        $store->update_product_data();

    }

}


//locked uncoment exit if you really want to do it
exit;


$contador = 0;
foreach ($stores as $store) {
    $store->update_new_products();
    print $store->data['Store Name']."\n";

    $sql = "select `Product ID` from `Product Dimension`  where `Product Store Key`=?   order by `Product Code`  ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $store->id
        ]
    );
    while ($row = $stmt->fetch()) {
        $editor['Date'] = gmdate('Y-m-d H:i:s');
        $contador++;
        $product         = get_object('Product', $row['Product ID']);
        $product->editor = $editor;
        $product->update(
            array(
                'Product Status'=>'Discontinued'
            )
        );
        print $contador.' '.$product->get('ID')." ".$product->get('Code')."  \n";

        $store->update_new_products();
        $store->update_product_data();

    }


}

exit;
$contador = 0;
foreach ($stores as $store) {

    print $store->data['Store Name']."\n";

    $sql = "select `Delivery Note Key` from `Delivery Note Dimension`  where `Delivery Note Store Key`=? and `Delivery Note State` in ('Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Packed Done') ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $store->id
        ]
    );
    while ($row = $stmt->fetch()) {
        $editor['Date'] = gmdate('Y-m-d H:i:s');
        $contador++;
        $dn         = get_object('Delivery Note', $row['Delivery Note Key']);
        $dn->editor = $editor;
        $dn->delete();
        print $contador.' '.$dn->get('ID')."\n";


    }


}



$deals     = array();
$campaigns = array();

$contador = 0;
foreach ($stores as $store) {

    print $store->data['Store Name']."\n";

    $sql = "select `Order Key` from `Order Dimension`  where `Order Store Key`=? and `Order State` in ('InBasket','InProcess')   ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $store->id
        ]
    );
    while ($row = $stmt->fetch()) {
        $editor['Date'] = gmdate('Y-m-d H:i:s');
        $contador++;
        $order         = get_object('Order', $row['Order Key']);
        $order->editor = $editor;
        $order->cancel('', false, false);


        $sql = sprintf(
            "SELECT `Deal Component Key`,`Deal Key`,`Deal Campaign Key` FROM  `Order Deal Bridge` WHERE `Order Key`=%d", $order->id
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                /**
                 * @var $component \DealComponent
                 */
                $component = get_object('DealComponent', $row['Deal Component Key']);
                $component->update_usage();
                $deals[$row['Deal Key']]              = $row['Deal Key'];
                $campaigns[$row['Deal Campaign Key']] = $row['Deal Campaign Key'];
            }
        }


        print $contador.' '.$order->get('Public ID')."\n";


    }

    $store->update_orders();


}

$account->update_orders();


$sql = sprintf("SELECT `Transaction Type Key` FROM `Order No Product Transaction Fact` WHERE `Transaction Type`='Charges'  ");

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        /**
         * @var $charge \Charge
         */
        $charge = get_object('Charge', $row['Transaction Type Key']);
        $charge->update_charge_usage();

    }
}


foreach ($deals as $deal_key) {
    /**
     * @var $deal \Deal
     */
    $deal = get_object('Deal', $deal_key);
     $deal->update_usage();
}

foreach ($campaigns as $campaign_key) {
    $campaign = get_object('DealCampaign', $campaign_key);
    $campaign->update_usage();
}



$contador = 0;
foreach ($stores as $store) {
    $store->update_new_products();
    print $store->data['Store Name']."\n";

    $sql = "select `Page Key` from `Page Store Dimension`  where `Webpage Store Key`=?  and `Webpage State`!='Offline'  ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $store->id
        ]
    );
    while ($row = $stmt->fetch()) {
        $editor['Date'] = gmdate('Y-m-d H:i:s');
        $contador++;
        $webpage         = get_object('Webpage', $row['Page Key']);
        $webpage->editor = $editor;
        $webpage->unpublish();
        print $contador.' '.$webpage->get('Code')." ".$webpage->get('URL')."  \n";



    }


}
