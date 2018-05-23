<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 10:45:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'lost_stock';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'lost_stock';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['lost_stock']['to'])) {
    $default['to'] = $_SESSION['table_state']['lost_stock']['to'];
}
if (isset($_SESSION['table_state']['lost_stock']['from'])) {
    $default['from'] = $_SESSION['table_state']['lost_stock']['from'];
}
if (isset($_SESSION['table_state']['lost_stock']['period'])) {
    $default['period'] = $_SESSION['table_state']['lost_stock']['period'];
}
$table_views = array();

$table_filters = array(
    'part_reference' => array('label' => _('Part')),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,
);


$elements_data =array(
        'Broken' => array(
            'number' => 0,
            'parts'  => 0,
            'amount' => money(0, $account->get('Currency Code'))
        ),
        'Lost'   => array(
            'number' => 0,
            'parts'  => 0,
            'amount' => money(0, $account->get('Currency Code'))
        ),
        'Error'  => array(
            'Number' => 0,
            'Parts'  => 0,
            'Amount' => money(0, $account->get('Currency Code'))
        ),


);


$where = " where  `Inventory Transaction Quantity`<0  and `Inventory Transaction Type` in ('Broken','Lost','Other Out') ";







if (isset($_SESSION['table_state'][$tab]['period'])) {

    include_once 'utils/date_functions.php';
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $_SESSION['table_state'][$tab]['period'], $_SESSION['table_state'][$tab]['from'], $_SESSION['table_state'][$tab]['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Date`');
    $where          .= $where_interval['mysql'];

}

$sql = sprintf("select count(*) as number,sum(`Inventory Transaction Amount`) as amount,count(distinct `Part SKU`) as parts,`Inventory Transaction Type` as element from `Inventory Transaction Fact`  $where  group by `Inventory Transaction Type` ");

foreach ($db->query($sql) as $row) {

    if ($row['element'] == 'Other Out') {
        $row['element'] = 'Error';
    }
    $elements_data[$row['element']] = array(
        'Number' => number($row['number']),
        'Parts'  => number($row['parts']),
        'Amount' => money(-1*$row['amount'], $account->get('Currency Code'))
    );

}




$smarty->assign('elements_data', $elements_data);

$smarty->assign('table_top_template', 'lost_stock.tpl');


include 'utils/get_table_html.php';


?>
