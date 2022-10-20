<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Oct 2022 10:38:45 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

function get_dashboard_sales_per_staff($db, $account, $smarty, $currency, $period)
{
    include_once 'utils/date_functions.php';


    $smarty->assign('currency', $currency);
    $smarty->assign('period', $period);

    $smarty->assign('account', $account);


    $produced_amount = 0;
    $sql             = "SELECT `Timeseries Record Float B` FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key` = 18 and `Timeseries Record Date`=?  ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 1, date('Y')))

        ]
    );
    while ($row = $stmt->fetch()) {
        $produced_amount = $row['Timeseries Record Float B'];
    }


    $number_staff = 0;
    $sql          = "select count(*) as num from `Staff Dimension` where `Staff Currently Working`='Yes' and `Staff Type`!='Contractor'   ";
    $stmt         = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $number_staff = $row['num']+1; // this 1 represent the extra admin contractors
    }

    $sales_per_staff_title = money($account->get('Account Last Month Acc Invoiced Amount')).' sales / '.$number_staff.' staff'.' / 19.24 days';
    $sales_per_staff       = money($account->get('Account Last Month Acc Invoiced Amount') / $number_staff / 19.24, $account->get('Currency Code')).'/wday';

    $smarty->assign('sales_per_staff_title', $sales_per_staff_title);
    $smarty->assign('sales_per_staff', $sales_per_staff);


    $produced_per_staff_title = money($produced_amount).' sales / '.$number_staff.' staff'.' / 19.24 days';
    $produced_per_staff       = money($produced_amount / $number_staff / 19.24, $account->get('Currency Code')).'/wday';

    $teams = [
        'Artisan' => 0,
        'Sales'   => 0,
        'Support' => 0,
        'Admin'   => 1,
    ];

    $sql  = "select count(*) as num , `Staff Team` from `Staff Dimension` where `Staff Currently Working`='Yes' and `Staff Type`!='Contractor' group by `Staff Team`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $teams[$row['Staff Team']] += $row['num'];

    }


    $smarty->assign('produced_per_staff_title', $produced_per_staff_title);
    $smarty->assign('produced_per_staff', $produced_per_staff);


    $teams_data = [];
    foreach ($teams as $key => $value) {
        $teams_data[$key] = [
            'sales'            => money($account->get('Account Last Month Acc Invoiced Amount') / ($value / $number_staff) / $number_staff / 19.24, $account->get('Currency Code')).'/wday',
            'producedx'         => money($produced_amount / ($value / $number_staff) / $number_staff / 19.24, $account->get('Currency Code')).'/wday',

            'produced'         => money($produced_amount  / $number_staff / 19.24, $account->get('Currency Code')).'/wday '.number($value / $number_staff,2) ,


            'staff'            => $value,
            'staff_percentage' => percentage($value, $number_staff)

        ];
    }

    $smarty->assign('teams_data', $teams_data);

    $report_title = 'Productivity '.strftime('%B %Y', strtotime('last month'));

    $smarty->assign('report_title', $report_title);

    return $smarty->fetch('dashboard/sales_per_staff.dbard.tpl');
}



