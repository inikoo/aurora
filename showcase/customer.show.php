<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 16:54:39 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_customer_showcase($data,$smarty) {

   
    
    $customer=$data['_object'];
    if(!$customer->id){
        return "";
    }
    
    $smarty->assign('customer',$customer);

    return $smarty->fetch('showcase/customer.tpl');
    


}


?>