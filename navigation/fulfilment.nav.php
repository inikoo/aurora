<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Jun 2021 02:03  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'utils/navigation_functions.php';

function get_dashboard_navigation($data, $smarty): array {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections($data['module'], $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Fulfilment dashboard'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_locations_navigation($data, $smarty): array {


    $warehouse = new Warehouse($data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Fulfilment locations').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_deliveries_navigation($data, $smarty): array {


    $warehouse = new Warehouse($data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Fulfilment deliveries').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


/** @noinspection DuplicatedCode */
function get_location_navigation($data, $smarty, $user, $db, $account): array {


    $warehouse = $data['warehouse'];
    $object    = $data['_object'];


    include_once 'prepare_table/fulfilment.locations.ptc.php';
    $table = new prepare_table_fulfilment_locations($db, $account, $user);

    $sections = get_sections($data['module'], $warehouse->id);
    $_section = 'locations';


    switch ($data['parent']) {
        case 'warehouse':
            $tab = 'warehouse.locations';

            $link = 'fulfilment/'.$data['parent_key'].'/locations';

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _('Locations'),
                'reference' => $link,
                'parent'    => ''
            );

            break;
        case 'customer':
            $tab = 'customer.locations';
            //$link='fulfilment/'.$object->get('Location Warehouse Key').'/locations';

            //TODO
            $up_button = [];
            $link      = '';
            break;
        default:

            exit('location navigation no parent');
    }

    $left_buttons = get_navigation_buttons(
        $table->get_navigation($object, $tab, $data), $up_button, $link.'/location/%d'

    );


    $right_buttons = array();

    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Location').' <span  class="id Location_Code" >'.$data['_object']->get('Code').'</span>';
    $title .= ' <i id="_External_Warehouse_icon" title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car '.($data['_object']->get('Location Place') != 'External' ? 'hide' : '').'  "></i>';


    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($warehouse->id, $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'class' => 'open_sticky_note  square_button right object_sticky_note  '.($data['_object']->get('Sticky Note') == '' ? '' : 'hide')

    );


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_navigation($data, $smarty): array {


    $warehouse = new Warehouse($data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Fulfilment customers').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )
    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_stored_parts_navigation($data, $smarty): array {


    $warehouse = new Warehouse($data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Stored fulfilment parts').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_fulfilment_customer_navigation($data, $smarty, $user, $db, $account): array {


    $customer = $data['_object'];
    $store    = $data['store'];


    $right_buttons = array();


    if ($store->get('Store Type') == 'Dropshipping') {
        $tab   = 'fulfilment.dropshipping_customers';
        $_link = 'dropshipping';
        include_once 'prepare_table/fulfilment.dropshipping_customers.ptc.php';
        $table = new prepare_table_fulfilment_dropshipping_customers($db, $account, $user);
    } else {
        $tab   = 'fulfilment.asset_keeping_customers';
        $_link = 'asset_keeping';
        include_once 'prepare_table/fulfilment.asset_keeping_customers.ptc.php';
        $table = new prepare_table_fulfilment_asset_keeping_customers($db, $account, $user);

    }


    $_section = 'customers';


    $placeholder = _('Search customers');
    $sections    = get_sections('fulfilment', $customer->get('Customer Fulfilment Warehouse Key'));

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customers"),
        'reference' => 'fulfilment/'.$data['parent_key'].'/customers/'
    );

    $left_buttons = get_navigation_buttons(
        $table->get_navigation($customer, $tab, $data), $up_button, 'fulfilment/'.$data['parent_key'].'/customers/'.$_link.'/%d'

    );


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Note for warehouse'),
        'class' => 'open_sticky_note square_button right delivery_note_sticky_note '.($customer->get('Customer Delivery Sticky Note') == '' ? '' : 'hide')
    );


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'class' => 'open_sticky_note  square_button right customer_sticky_note  '.($customer->get('Sticky Note') == '' ? '' : 'hide')
    );


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $avatar = '';


    $title = '<span class="Customer_Level_Type_Icon">'.$customer->get('Level Type Icon').'</span>';
    $title .= '<span class="id"><span class="Customer_Name_Truncated Name_Truncated">'.(strlen($customer->get('Customer Name')) > 50 ? substrwords($customer->get('Customer Name'), 55) : $customer->get('Customer Name')).'</span> ('.$customer->get_formatted_id()
        .')</span>';
    if ($customer->get('Customer Type by Activity') == 'ToApprove') {
        $title .= ' <span class="error padding_left_10"><i class="far fa-exclamation-circle"></i> '._('To be approved').'</span>';
    } elseif ($customer->get('Customer Type by Activity') == 'Rejected') {
        $title .= ' <span class="error padding_left_10"><i class="far fa-times"></i> '._('Rejected').'</span>';
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'avatar'         => $avatar,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_fulfilment_delivery_navigation($data, $smarty, $user, $db, $account): array {


    $object        = $data['_object'];
    $right_buttons = array();

    $_section = 'fulfilment';


    $tab = 'customer.deliveries';
    include_once 'prepare_table/fulfilment.deliveries.ptc.php';
    $table = new prepare_table_fulfilment_deliveries($db, $account, $user);


    $sections           = get_sections('fulfilment', $object->get('Fulfilment Delivery Warehouse Key'));
    $search_placeholder = _('Search fulfilment');

    $link = 'fulfilment/'.$object->get('Fulfilment Delivery Warehouse Key').'/customers/'.($object->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$data['parent_key'];

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customer").' '.$data['_parent']->get('Formatted ID'),
        'reference' => $link
    );


    $left_buttons = get_navigation_buttons(
        $table->get_navigation($object, $tab, $data), $up_button, $link.'/delivery/%d'

    );


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Delivery').' <span class="id Fulfilment_Delivery_Formatted_ID">'.$object->get('Formatted ID').'</span> ';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $search_placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_fulfilment_asset_navigation($data, $smarty, $user, $db, $account): array {


    $object        = $data['_object'];
    $right_buttons = array();

    $_section = 'fulfilment';

    $delivery = $data['_parent'];

    $tab = 'fulfilment.delivery.assets';
    include_once 'prepare_table/fulfilment.assets.ptc.php';
    $table = new prepare_table_fulfilment_assets($db, $account, $user);


    $sections           = get_sections('fulfilment', $object->get('Fulfilment Delivery Warehouse Key'));
    $search_placeholder = _('Search fulfilment');


    $link = get_delivery_link($delivery);

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Delivery").' '.$data['_parent']->get('Formatted ID'),
        'reference' => $link
    );


    $left_buttons = get_navigation_buttons(
        $table->get_navigation($object, $tab, $data), $up_button, $link.'/asset/%d'

    );


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $title = '<span class="Type_Icon padding_right_10">'.$object->get('Type Icon').'</span> ';
    $title .= '<span class="id Fulfilment_Asset_Formatted_ID_Reference">'.$object->get('Formatted ID Reference').'</span> ';


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Notes'),
        'class' => 'open_sticky_note  square_button right object_sticky_note yellow_note object_sticky_note '.($data['_object']->get('Note') == '' ? '' : 'hide')

    );

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $search_placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_upload_navigation($data, $smarty): array {

    $sections = get_sections('fulfilment', $data['current_warehouse']);
    $sections['customers']['selected'] = true;
    $delivery = $data['_parent'];

    $delivery_link = get_delivery_link($delivery);
    $up_button     = array(
        'icon'      => 'arrow-up',
        'title'     => _("Delivery").' '.$data['_parent']->get('Formatted ID'),
        'reference' => $delivery_link
    );

    $title = _('Uploading items to delivery').' <span onclick="change_view(\''.$delivery_link.'\')" class="id link">'.$delivery->get('Formatted ID').'</span> ';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => array($up_button),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}

function get_delivery_link($delivery): string {
    return 'fulfilment/'.$delivery->get('Fulfilment Delivery Warehouse Key').'/customers/'.($delivery->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$delivery->get('Fulfilment Delivery Customer Key').'/delivery/'.$delivery->id;

}