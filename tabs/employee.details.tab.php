<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 November 2015 at 22:57:18 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';

include_once 'utils/invalid_messages.php';

$employee = $state['_object'];
$employee->get_user();

$employee->get_user_data();


$stores = array();
$sql    = sprintf(
    'SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` order by `Store Code` '
);
foreach ($db->query($sql) as $row) {
    $stores[$row['Store Key']] = $row;
}
$smarty->assign('stores', $stores);



$object_fields = get_object_fields($employee, $db, $user, $smarty);


$smarty->assign(
    'working_hours', json_decode($employee->data['Staff Working Hours'], true)
);
$smarty->assign('salary', json_decode($employee->data['Staff Salary'], true));

$smarty->assign(
    'day_labels', $day_labels = array(
    _('Weekdays'),
    _('Mon'),
    _('Tue'),
    _('Wed'),
    _('Thu'),
    _('Fri'),
    _('Weekend'),
    _('Sat'),
    _('Sun')
)
);


$default_country = $account->get('Account Country 2 Alpha Code');
$smarty->assign(
    'default_telephone_data', base64_encode(
        json_encode(
            array(
                'default_country'     => strtolower($default_country),
                'preferred_countries' => array_map('strtolower', preferred_countries($default_country)),
            )
        )
    )
);



$smarty->assign('object', $employee);
$smarty->assign('system_user', $state['_object']->system_user);

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);

$smarty->assign('js_code', 'js/injections/employee.'.(_DEVEL ? '' : 'min.').'js');


$html = $smarty->fetch('edit_object.tpl');


