<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2015 12:55:36 GMT+8 Singapore
 Refurbished:  27 December 2019  10:28::24  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

/**
 * @param $user \User
 *
 * @return array
 */
function get_modules($user) {
    $modules = array();


    switch ($user->get('User Type')) {
        case 'Staff':
        case 'Contractor':

            foreach (glob("utils/modules/*.mod.php") as $filename) {
                include $filename;
            }


            $modules['dashboard'] = get_dashboard_module();

            $modules['customers_server'] = get_customers_server_module();
            $modules['customers']        = get_customers_module();

            $modules['products_server'] = get_products_server_module();
            $modules['products']        = get_products_module();

            $modules['websites_server'] = get_websites_server_module();
            $modules['websites']        = get_websites_module();

            $modules['orders_server'] = get_orders_server_module();
            $modules['orders']        = get_orders_module();

            $modules['mailroom_server'] = get_mailroom_server_module();
            $modules['mailroom']        = get_mailroom_module();

            $modules['offers_server'] = get_offers_server_module();
            $modules['offers']        = get_offers_module();

            $modules['delivery_notes_server'] = get_delivery_notes_server_module();
            $modules['delivery_notes']        = get_delivery_notes_module();

            $modules['accounting_server'] = get_accounting_server_module();
            $modules['accounting']        = get_accounting_module();

            $modules['inventory'] = get_inventory_module();

            $modules['warehouses_server'] = get_warehouses_server_module();
            $modules['warehouses']        = get_warehouses_module();

            $modules['production_server'] = get_production_server_module();
            $modules['production']        = get_production_module();

            $modules['suppliers'] = get_suppliers_module();

            $modules['hr'] = get_hr_module();

            $modules['reports'] = get_reports_module();

            $modules['profile'] = get_profile_module();
            $modules['users']   = get_users_module();
            $modules['account'] = get_account_module();
            $modules['utils']   = get_utils_module();
            $modules['help']    = get_help_module();

            return $modules;
            break;
        case 'Supplier':
            return $modules;
            break;
        case 'Agent':

            foreach (glob("utils/modules/*.mod.agent.php") as $filename) {
                include $filename;
            }

            $modules['dashboard'] = get_dashboard_module();

            $modules['agent_profile']           = get_agent_profile_module();
            $modules['agent_suppliers']         = get_agent_suppliers_module();
            $modules['agent_client_orders']     = get_agent_client_orders_module();
            $modules['agent_client_deliveries'] = get_agent_client_deliveries_module();
            $modules['agent_parts']             = get_agent_parts_module();

            return $modules;
            break;
    }


}


function get_sections($module, $parent_key = false) {
    global $modules;

    $sections = array(
        'left_button'  => array(),
        'navigation'   => array(),
        'right_button' => array(),
    );


    foreach ($modules[$module]['sections'] as $key => $value) {

        if ($value['type'] == 'navigation' or $value['type'] == 'left_button' or $value['type'] == 'right_button') {
            if ($parent_key) {
                $value['reference'] = sprintf($value['reference'], $parent_key);
            }
            $sections[$value['type']][$key] = $value;
        }
    }


    return $sections;

}


