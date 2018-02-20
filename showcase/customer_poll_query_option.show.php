<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2018 at 13:50:49 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_customer_poll_query_option_showcase($data, $smarty) {


    $poll_option = $data['_object'];


    if (!$poll_option->id) {
        return "";
    }

   

    $smarty->assign('poll_option', $poll_option);

    return $smarty->fetch('showcase/customer_poll_query_option.tpl');


}


?>