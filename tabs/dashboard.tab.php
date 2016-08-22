<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 12:35:17 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


$html='';
foreach ($user->get_dashboard_items() as $item) {

	if ($item=='sales_overview') {

		$period='1y';

		include_once 'widgets/sales_overview.wget.php';

		if (isset($_SESSION['dashboard_state']['sales_overview']['type'])) {
			$type=$_SESSION['dashboard_state']['sales_overview']['type'];
		}else {
			$type='invoices';
		}
		if (isset($_SESSION['dashboard_state']['sales_overview']['period'])) {
			$period=$_SESSION['dashboard_state']['sales_overview']['period'];
		}else {
			$period='ytd';
		}
		if (isset($_SESSION['dashboard_state']['sales_overview']['currency'])) {
			$currency=$_SESSION['dashboard_state']['sales_overview']['currency'];
		}else {
			$currency='account';
		}
		$html.=get_dashbord_sales_overview($db, $account, $user, $smarty, $type, $period, $currency);

	}

}






?>
