<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2018 at 14:47:44 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';

include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

include_once 'class.Prospect.php';


$email_template = $state['_object'];

$object_fields = get_object_fields(
    $email_template, $db, $user, $smarty, array(
                       'new'  => true,
                       'role' => 'Invite Mailshot'
                   )
);


$smarty->assign('state', $state);
$smarty->assign('object', $email_template);


$smarty->assign('object_name', $email_template->get_object_name());


$smarty->assign('object_fields', $object_fields);


$html = $smarty->fetch('new_object.tpl');

?>
