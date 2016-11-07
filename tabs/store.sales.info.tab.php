<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  7 November 2016 at 00:20:15 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/
include_once('class.Timeserie.php');

include_once('conf/timeseries.php');

$smarty->assign('object', $state['_object']);

$time_series_set=get_time_series_config()['Store'];

foreach($time_series_set as $key => $time_series_data) {

    $time_series_data['Timeseries Parent Key']=$state['_object']->id;

    $time_series=new Timeseries('find',$time_series_data);
    $time_series_set[$key]['key']=$time_series->id;
    $time_series_set[$key]['object']=$time_series;
    $time_series_set[$key]['parent']=$state['_object']->get_object_name();
    $time_series_set[$key]['parent_key']=$state['_object']->id;

    switch ($time_series_data['Timeseries Frequency']){
        case 'Yearly':
            $label='Annually';
            break;
        case 'Quarterly':
            $label='Quarterly';
            break;
        case 'Monthly':
            $label='Monthly';
            break;
        case 'Weekly':
            $label='Weekly';
            break;
        case 'Daily':
            $label='Daily';
            break;
        default:
            $label=$time_series_data['Timeseries Frequency'];
    }
    $time_series_set[$key]['label']=$label;
}

$smarty->assign('time_series_set', $time_series_set);
$html = $smarty->fetch('asset_sales_info.tpl')


?>
