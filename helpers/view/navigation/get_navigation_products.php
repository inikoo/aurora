<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 13:34:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
require_once 'navigation/products.nav.php';
function get_navigation_products($data, $smarty, $user, $db, $account) {
    switch ($data['section']) {

        case 'store':
            return get_store_navigation($data, $smarty, $user, $db, $account);
        case 'products':
            return get_products_navigation($data, $smarty, $user, $db, $account);
        case 'product':
            return get_product_navigation($data, $smarty, $user, $db, $account);
        case 'product.new':
            return get_new_product_navigation($data, $smarty, $user, $db, $account);
        case 'services':
            return get_services_navigation($data, $smarty, $user, $db, $account);
        case 'service':
            return get_service_navigation($data, $smarty, $user, $db, $account);
        case 'service.new':
            return get_new_service_navigation($data, $smarty, $user, $db, $account);
        case 'dashboard':
            return get_store_dashboard_navigation($data, $smarty, $user, $db, $account);
        case 'categories':
            return get_products_categories_navigation($data, $smarty, $user, $db, $account);
        case 'category':
            return get_products_category_navigation($data, $smarty, $user, $db);
        case 'main_category.new':
            return get_products_new_main_category_navigation($data, $smarty, $user, $db, $account);
        case 'order':
            return get_order_navigation($data, $smarty, $user, $db, $account);
        case 'charge':
            return get_charge_navigation($data, $smarty, $user, $db, $account);
        case 'shipping_zone':
            return get_shipping_zone_navigation($data, $smarty, $user, $db, $account);
        case 'charge.new':
            return get_charge_new_navigation($data, $smarty, $user, $db, $account);
        case 'shipping_zone_schema':
            return get_shipping_zone_schema_navigation($data, $smarty, $user, $db);
        case 'settings':
            return get_settings_navigation($data, $smarty);
        case 'website.new':
            return get_website_new_navigation($data, $smarty);
        case 'picking_pipeline':
            return get_picking_pipeline_navigation($data, $smarty);

    }
    return array([],'','');

}

function get_navigation_products_server($data, $smarty, $user, $db, $account): array {

    switch ($data['section']) {
        case 'stores':
            return get_stores_navigation($data, $smarty, $user, $db, $account);
        case 'products':
            return get_products_all_stores_navigation($data, $smarty, $user, $db, $account);
        case 'store.new':
            return get_new_store_navigation($data, $smarty, $user, $db, $account);
    }
    return array([],'','');
}
