<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2015 12:46:17 GMT+8, Kuala Lumour, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';


if (isset($_SESSION['table_state'][$tab])) {
    $table_state = $_SESSION['table_state'][$tab];
} else {
    $table_state = array();
}


foreach ($default as $key => $value) {
    if ($key == 'rpp_options') {

        $parameters[$key] = $value;
    } elseif ($key == 'export_fields') {


        $smarty->assign('export_fields', $value);

    } elseif ($key == 'sort_key') {

        if (isset($table_state['o'])) {
            $sort_key = $table_state['o'];
        } else {
            $sort_key = $default['sort_key'];
        }

    } elseif ($key == 'sort_order') {
        if (isset($table_state['od'])) {
            $sort_order = $table_state['od'];
        } else {
            $sort_order = $default['sort_order'];
        }


    } elseif ($key == 'rpp') {
        if (isset($table_state['nr'])) {
            $results_per_page = $table_state['nr'];
        } else {
            $results_per_page = $default['rpp'];
        }


    } else {
        if (isset($table_state[$key])) {
            $parameters[$key] = $table_state[$key];
        } else {
            $parameters[$key] = $value;
        }
    }
}


if (isset($metadata['parameters'])) {
    foreach ($metadata['parameters'] as $_key => $_value) {
        if (isset($parameters[$_key])) {
            $parameters[$_key] = $_value;
        }
    }
}

//print_r( $parameters['elements']);

//print_r($metadata);

if (isset($metadata['element'])) {

    foreach ($metadata['element'] as $element_type => $elements) {

        if (isset($parameters['elements'][$element_type])) {



            foreach ($elements as $_key => $value) {

             //  print $element_type.' '.$_key.': '.$value."\n";

                $parameters['elements'][$element_type]['items'][$_key]['selected'] = $value;
            }

        }

    }

}


$parameters['tab'] = $tab;

if (isset($parameters['period'])) {
    $smarty->assign('period', $parameters['period']);

    if ($parameters['period'] == 'day' or $parameters['period'] == 'interval') {

        $smarty->assign('from', $parameters['from']);
        $smarty->assign('to', $parameters['to']);
        $smarty->assign(
            'from_mmddyy', strftime("%m/%d/%Y", strtotime($parameters['from']))
        );
        $smarty->assign(
            'to_mmddyy', strftime("%m/%d/%Y", strtotime($parameters['to']))
        );
        $smarty->assign(
            'from_locale', strftime("%x", strtotime($parameters['from']))
        );
        $smarty->assign(
            'to_locale', strftime("%x", strtotime($parameters['to']))
        );
    } else {
        $smarty->assign('from', '');
        $smarty->assign('to', '');
        $smarty->assign('from_mmddyy', '');
        $smarty->assign('to_mmddyy', '');
        $smarty->assign('from_locale', '');
        $smarty->assign('to_locale', '');
    }

}

$smarty->assign('f_field', $parameters['f_field']);
$smarty->assign(
    'f_label', ($parameters['f_field'] ? $table_filters[$parameters['f_field']]['label'] : '')
);

$smarty->assign('f_options', $table_filters);


$table_view = $parameters['view'];
$smarty->assign('table_view', $parameters['view']);

if (array_key_exists('f_period', $parameters)) {
    $smarty->assign('f_period', $parameters['f_period']);
    $smarty->assign(
        'f_period_label', get_interval_db_name($parameters['f_period'])
    );

    $f_periods = array(
        'all'    => get_interval_db_name('all'),
        'ytd'    => get_interval_db_name('ytd'),
        'mtd'    => get_interval_db_name('mtd'),
        'wtd'    => get_interval_db_name('wtd'),
        '1y'     => get_interval_db_name('1y'),
        '1q'     => get_interval_db_name('1q'),
        'last_w' => get_interval_db_name('last_w'),
        'last_m' => get_interval_db_name('last_m'),
        //'today'=>get_interval_db_name('today'),

    );
    $smarty->assign('f_periods', $f_periods);


}


if (array_key_exists('frequency', $parameters)) {

    $frequencies = array(
        'annually'  => _('Annually'),
        'quarterly' => _('Quarterly'),
        'monthly'    => _('Monthly'),
        'weekly'    => _('Weekly'),
        'daily'     => _('Daily'),

    );

    $smarty->assign('frequency', $parameters['frequency']);
    $smarty->assign('frequency_label', $frequencies[$parameters['frequency']]);


    $smarty->assign('frequencies', $frequencies);

}

if (array_key_exists('elements', $parameters)) {
    $smarty->assign('elements', $parameters['elements']);
}
if (array_key_exists('elements_type', $parameters)) {
    $smarty->assign('elements_type', $parameters['elements_type']);
}


$parameters = json_encode($parameters);


$request = '/'.$ar_file.'?tipo='.$tipo.'&parameters='.$parameters;


$smarty->assign('results_per_page_options', $default['rpp_options']);
$smarty->assign('results_per_page', $results_per_page);


$smarty->assign('sort_key', $sort_key);
$smarty->assign('sort_order', $sort_order);
$smarty->assign('request', $request);
$smarty->assign('ar_file', $ar_file);
$smarty->assign('tipo', $tipo);

$smarty->assign('parameters', $parameters);
$smarty->assign('tab', $tab);

if (isset($columns_parameters)) {
    $smarty->assign('columns_parameters', $columns_parameters);

}


if (isset($table_views[$table_view])) {
    $table_views[$table_view]['selected'] = true;
}


$smarty->assign('table_views', $table_views);

$html = $smarty->fetch('table.tpl');

?>
