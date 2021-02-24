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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_sales_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Sales report');

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_report_orders_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();


    $right_buttons[] = array(
        'icon'     => 'tasks',
        'title'    => '',
        'click'    => "change_view('/report/report_orders_components')",
        'pre_text' => _("X-rays"),
        'class'    => 'text'
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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_report_orders_components_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();


    $right_buttons[] = array(
        'icon'     => 'fa-dollar-sign',
        'title'    => '',
        'click'    => "change_view('/report/report_orders')",
        'pre_text' => _("Sales"),
        'class'    => 'text'
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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_pickers_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();


    $right_buttons[] = array(
        'icon'     => 'arrow-right',
        'title'    => '',
        'click'    => "change_view('/report/packers')",
        'pre_text' => _('Packers'),
        'class'    => 'text'
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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_packers_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();


    $right_buttons[] = array(
        'icon'  => 'arrow-left',
        'title' => '',
        'click' => "change_view('/report/pickers')",
        'text'  => _('Pickers'),
        'class' => 'text'
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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_intrastat_imports_navigation($user, $smarty, $data) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Intrastat imports');

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_intrastat_navigation($user, $smarty, $data,$account) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Intrastat exports');



    if ($account->get('Account Country 2 Alpha Code') == 'SK') {


        $right_buttons[] = array(
            'icon'     => 'code',
            'title'    => _('XML export'),
            'click'    => "sk_xml_export_intrastat()",
            'pre_text' => 'XML',
            'class'    => 'text'
        );
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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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

    $left_buttons = array($up_button);

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_prospect_agent_navigation($user, $smarty, $data) {


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
        'title'     => _("Prospect's agents productivity"),
        'reference' => 'report/prospect_agents'
    );

    $left_buttons = array($up_button);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = sprintf(_("Agent %s prospects report"), '<span class="id">'.$data['_object']->user->get('Alias').'</span>');

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_prospect_agent_email_tracking_navigation($data, $smarty, $user, $db) {


    if (!$data['_parent']->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {
            case 'prospect_agent':
                $tab      = 'prospect_agent.sent_emails';
                $_section = 'prospects';
                break;


        }


        if (isset($_SESSION['table_state'][$tab])) {
            $number_results  = $_SESSION['table_state'][$tab]['nr'];
            $start_from      = 0;
            $order           = $_SESSION['table_state'][$tab]['o'];
            $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value         = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];
        } else {

            $default                  = $user->get_tab_defaults($tab);
            $number_results           = $default['rpp'];
            $start_from               = 0;
            $order                    = $default['sort_key'];
            $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
            $f_value                  = '';
            $parameters               = $default;
            $parameters['parent']     = $data['parent'];
            $parameters['parent_key'] = $data['parent_key'];
        }

        include_once 'prepare_table/'.$tab.'.ptble.php';

        $_order_field       = $order;
        $order              = preg_replace('/^.*\.`/', '', $order);
        $order              = preg_replace('/^`/', '', $order);
        $order              = preg_replace('/`$/', '', $order);
        $_order_field_value = $data['_object']->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Email Tracking Email` object_name, `Email Tracking Created Date` as object_date,  `Email Tracking Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Tracking Key` < %d))  order by $_order_field desc , `Email Tracking Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $data['_object']->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Email tracking").' '.$row['object_name'].' ('.strftime("%a, %e %b %Y %R:%S", strtotime($row['object_date']." +00:00")).')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Email Tracking Email` object_name, `Email Tracking Created Date` as object_date,`Email Tracking Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Tracking Key` > %d))  order by $_order_field   , `Email Tracking Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $data['_object']->id
                );

                // print $sql;


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $prev_title = _("Email tracking").' '.$row['object_name'].' ('.strftime("%a, %e %b %Y %R:%S", strtotime($row['object_date']." +00:00")).')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                if ($order_direction == 'desc') {
                    $_tmp1      = $prev_key;
                    $_tmp2      = $prev_title;
                    $prev_key   = $next_key;
                    $prev_title = $next_title;
                    $next_key   = $_tmp1;
                    $next_title = $_tmp2;
                }


            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        switch ($data['parent']) {
            case 'prospect_agent':

                $receiver    = get_object($data['_object']->get('Email Tracking Recipient'), $data['_object']->get('Email Tracking Recipient Key'));
                $placeholder = _('Search reports');
                $sections    = get_sections('reports', '');


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => $receiver->get('Name'),
                    'reference' => 'report/prospect_agents/'.$data['parent_key']
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'report/prospect_agents/'.$data['parent_key'].'/email/'.$prev_key
                    );
                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-left disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }
                $left_buttons[] = $up_button;


                if ($next_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-right',
                        'title'     => $next_title,
                        'reference' => 'report/prospect_agents/'.$data['parent_key'].'/email/'.$next_key
                    );

                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }

                $title = sprintf(_('Invitation email for %s'), '<span class="id">'.$receiver->get('Name').'</span>');

                break;


        }


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_intrastat_parts_navigation($user, $smarty, $data) {


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Intrastat imports report"),
        'reference' => 'report/intrastat_imports'
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


    $title = _('Intrastat').": ".sprintf(_('Parts received from %s with commodity code %s'), $country->get('Country Name'), $__data[1]);

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_intrastat_deliveries_navigation($user, $smarty, $data) {

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Intrastat imports report"),
        'reference' => 'report/intrastat_imports'
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


    $title = _('Intrastat').": ".sprintf(_('%s deliveries with commodity code %s'), $country->get('Country Name'), $__data[1]);

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
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_picker_packer_navigation($data, $db, $user, $smarty) {


    $object        = get_object('Staff', $data['key']);
    $left_buttons  = array();
    $right_buttons = array();

    $sections      = array();



    switch ($data['section']) {
        case 'picker':
            $tab      = 'pickers';
            $_section = 'pickers';
            $parent_title=_('Pickers productivity');
            break;
        case 'packer':
            $tab      = 'packers';
            $_section = 'packers';
            $parent_title=_('Packers productivity');
            break;
        default:
            exit();

    }


    if (isset($_SESSION['table_state'][$tab])) {
        $number_results  = $_SESSION['table_state'][$tab]['nr'];
        $start_from      = 0;
        $order           = $_SESSION['table_state'][$tab]['o'];
        $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
        $f_value         = $_SESSION['table_state'][$tab]['f_value'];
        $parameters      = $_SESSION['table_state'][$tab];
    } else {

        $default                  = $user->get_tab_defaults($tab);
        $number_results           = $default['rpp'];
        $start_from               = 0;
        $order                    = $default['sort_key'];
        $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
        $f_value                  = '';
        $parameters               = $default;
        $parameters['parent']     = $data['parent'];
        $parameters['parent_key'] = $data['parent_key'];
    }

    include_once 'prepare_table/'.$tab.'.ptble.php';

    $_order_field       = $order;
    $order              = preg_replace('/^.*\.`/', '', $order);
    $order              = preg_replace('/^`/', '', $order);
    $order              = preg_replace('/`$/', '', $order);
    $_order_field_value = $object->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;


    $sql = sprintf(
        "select `Staff Name` object_name,S.`Staff Key` as object_key from  %s  
	                and ($_order_field < %s OR ($_order_field = %s AND S.`Staff Key` < %d))  order by $_order_field desc , S.`Staff Key` desc limit 1", "$table   $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $prev_key   = $row['object_key'];
            $prev_title = _("Employee").' '.$row['object_name'].' ('.$row['object_key'].')';
        }
    }


    $sql = sprintf(
        "select `Staff Name` object_name,S.`Staff Key` as object_key from %s  
	                and ($_order_field  > %s OR ($_order_field  = %s AND S.`Staff Key` > %d))  order by $_order_field   , S.`Staff Key`  limit 1", "$table   $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $next_key   = $row['object_key'];
            $next_title = _("Employee").' '.$row['object_name'].' ('.$row['object_key'].')';

        }
    }

    if ($order_direction == 'desc') {
        $_tmp1      = $prev_key;
        $_tmp2      = $prev_title;
        $prev_key   = $next_key;
        $prev_title = $next_title;
        $next_key   = $_tmp1;
        $next_title = $_tmp2;
    }


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => $parent_title,
        'reference' => 'report/'.$_section
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'report/'.$_section.'/'.$prev_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-left disabled',
            'title' => '',
            'url'   => ''
        );

    }
    $left_buttons[] = $up_button;


    if ($next_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' =>  'report/'.$_section.'/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }




    $title = '<span class="id Staff_Name">'.$object->get('Alias').'</span> (<span class="id Staff_ID ">'.$object->get('ID').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search staff')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}
