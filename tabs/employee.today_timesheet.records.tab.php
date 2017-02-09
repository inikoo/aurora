<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 February 2017 at 10:01:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once('class.Timesheet.php');

$tab     = 'timesheet.records';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'timesheet_records';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$sql = sprintf(
    "SELECT `Timesheet Key` FROM `Timesheet Dimension` T  WHERE `Timesheet Staff Key` =%d AND `Timesheet Date`=%s ",
$state['key'],
    prepare_mysql(gmdate('Y-m-d'))
);

//print $sql;

if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $timesheet=new Timesheet($row['Timesheet Key']);
    }else{
        $html= _('Timesheet not found');
    return;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$parameters    = array(
    'parent'     => 'Timesheet',
    'parent_key' => $timesheet->id,

);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'              => 'plus',
    'title'             => _('New timesheet record'),
    'id'                => 'new_record',
    'inline_new_object' => array(
        'field_id'    => 'Timesheet_Record_Date',
        'field_label' => _('New clocking record').':',
        'field_edit'  => 'time',
        'date'        => $timesheet->get('IsoDate'),
        'object'      => 'Timesheet_Record',
         'parent'     => 'Timesheet',
    'parent_key' => $timesheet->id,

    )

);
$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'js_code', 'js/injections/timesheet_records.'.(_DEVEL ? '' : 'min.').'js'
);


include 'utils/get_table_html.php';

$html = $html.'<div id="fields" object="Timesheet_Record"></div>';

?>
