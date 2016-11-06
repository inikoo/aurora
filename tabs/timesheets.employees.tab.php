<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2015 at 15:45:49 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'timesheets.employees';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'timesheets.employees';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'                => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'clocked_per_day'         => array('label' => _('Clocked/d')),
    'unpaid_overtime_per_day' => array('label' => _('nOKD overtime/d')),
    'work_time_per_day'       => array('label' => _('Paid time/d')),
    'paid_overtime_per_day'   => array('label' => _('OKD overtime/d')),

    'worked_per_day' => array('label' => _('Worked/d')),
);


$table_filters = array(
    'alias' => array(
        'label' => _('Alias'),
        'title' => _('Employee alias')
    ),
    'name'  => array(
        'label' => _('Name'),
        'title' => _('Employee name')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],


);


include 'utils/get_table_html.php';

?>
