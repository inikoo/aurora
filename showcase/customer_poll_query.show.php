<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2018 at 19:00:42 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_customer_poll_query_showcase($data, $smarty) {


    $poll_query = $data['_object'];


    if (!$poll_query->id) {
        return "";
    }

   

    $smarty->assign('poll_query', $poll_query);

    return $smarty->fetch('showcase/customer_poll_query.tpl');


}


?>