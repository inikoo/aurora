<?php
require_once 'common.php';
require_once 'class.TimeSeries.php';

$tm=new TimeSeries(array('m','invoices'));
$tm->get_values();
$tm->save_values();
$tm->forecast();
?>