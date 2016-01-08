<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:18:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Timeseries.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';

require_once 'utils/date_functions.php';
require_once 'conf/timeseries.php';

$editor=array(
		'Author Name'=>'',
		'Author Alias'=>'',
		'Author Type'=>'',
		'Author Key'=>'',
		'User Key'=>0,
		'Date'=>gmdate('Y-m-d H:i:s')
	);

$sql=sprintf('select `Store Key` from `Store Dimension` ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {

		$store=new Store($row['Store Key']);

		$store_timeseries_data=$timeseries['Store'];

		foreach ($store_timeseries_data as $timeserie_data) {
		    
		    $editor['Date']=gmdate('Y-m-d H:i:s');
		    $timeserie_data['editor']=$editor;
			$store->create_timeseries($timeserie_data);

		}
	}

}else {print_r($error_info=$db->errorInfo());exit;}



?>
