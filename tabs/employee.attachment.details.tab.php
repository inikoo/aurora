<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 December 2015 at 18:04:35 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

if ($user->can_edit('Staff')) {

    include_once 'utils/invalid_messages.php';
    include_once 'conf/object_fields.php';

    /**
     * @var $attachment \Attachment
     */
    $attachment = $state['_object'];

    $object_fields = get_object_fields(
        $attachment, $db, $user, $smarty, array('type' => 'employee')
    );

    $smarty->assign('state', $state);
    $smarty->assign('object', $attachment);
    $smarty->assign('object_name', $attachment->get_object_name());

    $smarty->assign('object_fields', $object_fields);

    $html = $smarty->fetch('edit_object.tpl');


} else {
    $html = '<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';

}
