<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 26 Oct 2022 15:04:26 British Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

function get_external_production_alerts($external_account_code, $production, $db, $account, $user, $smarty)
{
    $html = '';


    switch ($external_account_code) {
        case 'sk':
            $supplier_key = 355;
            $db           = 'sk';
            $title="SK";
            break;
        case 'es':
            $supplier_key = 209;
            $db           = 'es';
            $title="ES";
            break;
        case 'aw':
            $supplier_key = 6737;
            $db           = 'dw';
            $title="AW";
            break;
    }

    include 'keyring/dns.php';


    $db2 = new PDO(
        "mysql:host=$dns_host;dbname=$db;charset=utf8mb4", $dns_user, $dns_pwd
    );


    $low_stock_products = 0;
    $all_products = 0;

    $sql                = "select count(*) as num from `Supplier Part Dimension` left join `Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`)  where `Supplier Part Supplier Key`=? 
and `Part Status`='In Use' and `Supplier Part Status`!='Discontinued'  and   `Part Stock Status` in ('Critical','Out_Of_Stock')  ";
    $stmt               = $db2->prepare($sql);
    $stmt->execute(
        [
            $supplier_key
        ]
    );
    while ($row = $stmt->fetch()) {
        $low_stock_products = $row['num'];
    }


    $sql                = "select count(*) as num from `Supplier Part Dimension` left join `Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`)   and `Part Status`!='Not In Use' where `Supplier Part Supplier Key`=?  ";
    $stmt               = $db2->prepare($sql);
    $stmt->execute(
        [
            $supplier_key
        ]
    );
    while ($row = $stmt->fetch()) {
        $all_products = $row['num'];
    }



    $data = get_widget_data(
        $low_stock_products,
        $all_products,
        .05,
        .20

    );




    if ($data['ok']) {
        $smarty->assign('data', $data);
        $smarty->assign('title', $title);
        $smarty->assign('external_account_code', $external_account_code);


        $html .= $smarty->fetch('dashboard/production.external_products.dbard.tpl');
    }



    return $html;
}
