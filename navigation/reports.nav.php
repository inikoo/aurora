<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 18:17:55 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/



function get_lost_stock_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Lost stock');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_stock_given_free_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Stock given for free');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_sales_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Invoice sales');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_report_orders_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();



    $right_buttons[]
        = array(
        'icon'  => 'tasks',
        'title' => '',
        'click'=>"change_view('/report/report_orders_components')",
        'pre_text'=>_("X-rays"),
        'class'=>'text'
    );


    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Dispatched orders sales');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_report_orders_components_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();



    $right_buttons[]
        = array(
        'icon'  => 'fa-dollar-sign',
        'title' => '',
        'click'=>"change_view('/report/report_orders')",
        'pre_text'=>_("Sales"),
        'class'=>'text'
    );


    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Dispatched orders X-rays');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



function get_report_delivery_notes_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Delivery notes');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_pickers_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();


    $right_buttons[]
        = array(
        'icon'  => 'arrow-right',
        'title' => '',
        'click'=>"change_view('/report/packers')",
        'pre_text'=>_('Packers'),
        'class'=>'text'
    );

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Pickers productivity');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_packers_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();


    $right_buttons[]
        = array(
        'icon'  => 'arrow-left',
        'title' => '',
        'click'=>"change_view('/report/pickers')",
        'text'=>_('Pickers'),
        'class'=>'text'
        );


    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Packers productivity');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_reports_navigation($user, $smarty, $data) {

    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Reports');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_performance_navigation($user, $smarty, $data) {


    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('reports', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    switch ($data['tab']) {
        case ('report.pp'):
            $title = _('Pickers & Packers Report');
            break;

        case ('report.outofstock'):
            $title = _('Out of Stock');
            break;
        case ('report.top_customers'):
            $title = _('Top Customers');
            break;
        case ('report.top_customers'):
            $title = _('Top Customers');
            break;
        default:
            $title = '';
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_ec_sales_list_navigation($user, $smarty, $data) {

    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();
    //array(array('icon'=>'cog', 'title'=>_("Settings"), 'id'=>'report_settings'));
    $sections = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('EC Sales List (ESL)');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_georegion_taxcategory_navigation($user, $smarty, $data) {

    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array(
        array(
            'icon'  => 'cog',
            'title' => _("Settings"),
            'id'    => 'report_settings'
        )
    );
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Billing region & Tax code report');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_georegion_taxcategory_navigation($user, $smarty, $data, $type) {

    $block_view = $data['section'];

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Billing region & Tax code report"),
        'reference' => 'report/billingregion_taxcategory'
    );

    $left_buttons  = array($up_button);
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $parents = preg_split('/_/', $data['parent_key']);

    switch ($parents[0]) {
        case 'EU':
            $billing_region = _('European Union');
            break;
        case 'Unknown':
            $billing_region = _('Unknown');
            break;
        case 'NOEU':
            $billing_region = _('Outside European Union');
            break;
        case 'GBIM':
            $billing_region = 'GB+IM';
            break;
        default:
            $billing_region = $parents[0];
            break;
    }

    if ($type == 'invoices') {
        $title = _('Invoices')." $billing_region & ".$parents[1];
    } elseif ($type == 'invoices') {
        $title = _('Refunds')." $billing_region & ".$parents[1];
    } else {
        $title = "$billing_region & ".$parents[1];
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_intrastat_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Intrastat');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_intrastat_products_navigation($user, $smarty, $data) {



    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Intrastat report"),
        'reference' => 'report/intrastat'
    );

    $left_buttons  = array($up_button);
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_data  = preg_split('/\|/', $data['extra']);
    $__data = preg_split('/\_/', $_data[1]);

    include_once 'class.Country.php';

    $country = new Country('2alpha', $__data[0]);


    $title = _('Intrastat').": ".sprintf(_('Products send to %s with commodity code %s'), $country->get('Country Name'), $__data[1]);

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_intrastat_orders_navigation($user, $smarty, $data) {


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Intrastat report"),
        'reference' => 'report/intrastat'
    );

    $left_buttons  = array($up_button);
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_data  = preg_split('/\|/', $data['extra']);
    $__data = preg_split('/\_/', $_data[1]);

    include_once 'class.Country.php';

    $country = new Country('2alpha', $__data[0]);


    $title = _('Intrastat').": ".sprintf(_('%s orders with commodity code %s'), $country->get('Country Name'), $__data[1]);

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



function get_sales_representatives_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

/*
    $right_buttons[]
        = array(
        'icon'  => 'arrow-right',
        'title' => '',
        'click'=>"change_view('/report/packers')",
        'pre_text'=>_('Packers'),
        'class'=>'text'
    );
*/
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Account managers sales');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



function get_sales_representative_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    /*
        $right_buttons[]
            = array(
            'icon'  => 'arrow-right',
            'title' => '',
            'click'=>"change_view('/report/packers')",
            'pre_text'=>_('Packers'),
            'class'=>'text'
        );
    */


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Sales representatives report"),
        'reference' => 'report/sales_representatives'
    );

    $left_buttons  = array($up_button);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = $data['_object']->user->get('Alias');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_prospect_agents_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    /*
        $right_buttons[]
            = array(
            'icon'  => 'arrow-right',
            'title' => '',
            'click'=>"change_view('/report/packers')",
            'pre_text'=>_('Packers'),
            'class'=>'text'
        );
    */
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Prospect's agents productivity");

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search reports')
        )
    );
    $smarty->assign('_content', $_content);
    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


?>
