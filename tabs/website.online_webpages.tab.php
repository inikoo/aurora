<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 February 2017 at 13:11:40 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

/**
 * @var $website \Website
 */
$website=$state['_parent'];

$tab     = 'website.online_webpages';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'online_webpages';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array('label' => _('Code')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);

if($website->get('Website Status')=='Active'){
    $smarty->assign('website', $website);
    $smarty->assign('table_top_template', 'showcase/website.online_webpages.tpl');
}

include('utils/get_table_html.php');