<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 20:20:42 GMT+8, Juala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_prospect_email_tracking($data, $smarty) {


    $email_tracking = $data['_object'];
    if (!$email_tracking->id) {
        return "";
    }

    $published_email=get_object('Published_Email_Template',$email_tracking->get('Email Tracking Published Email Template Key'));

    $smarty->assign('email_tracking', $email_tracking);
    $smarty->assign('receiver',$data['_parent']);
    $smarty->assign('published_email', $published_email);

    return $smarty->fetch('showcase/email_tracking.tpl');


}


?>