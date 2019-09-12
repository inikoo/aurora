<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 19:55:23 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'websites';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'websites';



if($account->get('Account Stores')==0){

    $html='<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}

$default = $user->get_tab_defaults($tab);



$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'webpages'   => array(
        'label' => _('Webpages'),
        'title' => _('Webpages')
    ),
    'gsc'  => array(
        'label' => _('Organic search'),
        'title' => _('Organic search (Google)')
    ),
    'ga' => array(
        'label' => _('Analytics'),
        'title' => _('Analytics'),
    ),
    'users'   => array(
        'label' => _('Registered customers'),
        'title' => _('Registered customers')
    )

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Website code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Website name')
    ),

);

$parameters = array(
    'parent'     => '',
    'parent_key' => '',
);


include('utils/get_table_html.php');



