<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 June 2018 at 00:14:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_email_template_showcase($data, $smarty) {


    $email_template = $data['_object'];
    if (!$email_template->id) {
        return "";
    }

    $smarty->assign('email_template', $email_template);

    return $smarty->fetch('showcase/email_template.tpl');


}


?>