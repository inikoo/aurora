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


    $teams = [
        'Artisan' => 0,
        'Sales'   => 0,
        'Support' => 0,
        'Admin'   => 1,
        'Warehouse'   => 0,
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
    $smarty->assign('number_staff', $number_staff);




    $number_production_staff= $teams['Artisan']+$teams['Support'];

    if($number_production_staff==0){
        $produced_per_staff_title = '';
        $produced_per_staff       = 'NaN';

    }else{
        $produced_per_staff_title = money($account->get('Account Last Month Acc Invoiced Amount')).' sales / '.$number_production_staff.' staff'.' / 19.24 days';
        $produced_per_staff       = money(1.375*$account->get('Account Last Month Acc Invoiced Amount') / $number_production_staff / 19.24, $account->get('Currency Code')).'/wday';

    }







    $smarty->assign('produced_per_staff_title', $produced_per_staff_title);
    $smarty->assign('produced_per_staff', $produced_per_staff);
    $smarty->assign('number_production_staff', $number_production_staff);


    $number_warehouse_staff= $teams['Warehouse'];


    if($number_warehouse_staff==0){
        $warehouse_per_staff_title = '';
        $warehouse_per_staff       = 'NaN';

    }else{
        $warehouse_per_staff_title = money($account->get('Account Last Month Acc Invoiced Amount'),$account->get('Currency Code')).' sales / '.$number_warehouse_staff.' staff'.' / 19.24 days';
        $warehouse_per_staff       = money($account->get('Account Last Month Acc Invoiced Amount') / $number_warehouse_staff / 19.24, $account->get('Currency Code')).'/wday';

    }




    $smarty->assign('sales_per_warehouse_title', $warehouse_per_staff_title);
    $smarty->assign('sales_per_warehouse', $warehouse_per_staff);
    $smarty->assign('number_warehouse_staff', $number_warehouse_staff);




    $teams_data = [];
    foreach ($teams as $key => $value) {

        if($value==0){
            $sales=money(0,$account->get('Currency Code')).'/wday';
        }else{
            $sales=money($account->get('Account Last Month Acc Invoiced Amount') / ($value / $number_staff) / $number_staff / 19.24, $account->get('Currency Code')).'/wday';


        }



        $teams_data[$key] = [
            'sales'            => $sales,
          //  'producedx'         => money($produced_amount / ($value / $number_staff) / $number_staff / 19.24, $account->get('Currency Code')).'/wday',

          //  'produced'         => money($produced_amount  / $number_staff / 19.24, $account->get('Currency Code')).'/wday '.number($value / $number_staff,2) ,


            'staff'            => $value,
            'staff_percentage' => percentage($value, $number_staff)

        ];
    }

    $smarty->assign('teams_data', $teams_data);

    $report_title = 'Productivity '.strftime('%B %Y', strtotime('last month'));

    $smarty->assign('report_title', $report_title);

    return $smarty->fetch('dashboard/sales_per_staff.dbard.tpl');
}



