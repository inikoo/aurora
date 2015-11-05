<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 November 2015 at 16:56:14 CET, Tessara Italy

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_user_showcase($data) {

    global $smarty;
    
 
    if(!$data['_object']->id){
        return "";
    }
    
    $smarty->assign('_user',$data['_object']);

    return $smarty->fetch('showcase/user.tpl');
    


}


?>