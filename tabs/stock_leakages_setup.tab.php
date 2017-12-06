<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  4 December 2017 at 13:54:44 CET, Mijas Costa, Spain

 Copyright (c) 2017, Inikoo

 Version 3.0
*/



include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';
include_once('conf/timeseries.php');
include_once('class.Timeserie.php');

$warehouse=$state['_parent'];


$state['_object']=$warehouse;

$smarty->assign('object',$warehouse);






include_once 'utils/invalid_messages.php';


$object_fields = get_object_fields($warehouse, $db, $user, $smarty,array('type'=>'leakages'));


$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);


$html = $smarty->fetch('edit_object.tpl');




$time_series_set=get_time_series_config()['Warehouse'];

foreach($time_series_set as $key => $time_series_data) {

    if($time_series_data['Timeseries Type']=='WarehouseStockLeakages') {

        $time_series_data['Timeseries Parent Key'] = $warehouse->id;

        $time_series                         = new Timeseries('find', $time_series_data);
        $time_series_set[$key]['key']        = $time_series->id;
        $time_series_set[$key]['object']     = $time_series;
        $time_series_set[$key]['parent']     = $warehouse->get_object_name();
        $time_series_set[$key]['parent_key'] =$warehouse->id;

        switch ($time_series_data['Timeseries Frequency']) {
            case 'Yearly':
                $label = 'Annually';
                break;
            case 'Quarterly':
                $label = 'Quarterly';
                break;
            case 'Monthly':
                $label = 'Monthly';
                break;
            case 'Weekly':
                $label = 'Weekly';
                break;
            case 'Daily':
                $label = 'Daily';
                break;
            default:
                $label = $time_series_data['Timeseries Frequency'];
        }
        $time_series_set[$key]['label'] = $label;
    }
}

$smarty->assign('time_series_set', $time_series_set);




$html .= $smarty->fetch('stock_leakages_setup.tpl')


?>
