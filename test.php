<?php
$timezone='Europe/Madrid';

date_default_timezone_set($timezone);


print date('Y-m-d H:i:s',strtotime('2014-03-01 15:00:00 +0:00'));


?>