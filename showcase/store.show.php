<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 13:31:36 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_store_showcase($data,$smarty) {

    
    $store=$data['_object'];
    if(!$store->id){
        return "";
    }
    
    $smarty->assign('store',$store);

    return $smarty->fetch('showcase/store.tpl');
    


}


?>