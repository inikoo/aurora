<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2015 at 15:21:51 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_employee_showcase($data) {

    global $smarty;
    
 
    if(!$data['_object']->id){
        return "";
    }
    
    $smarty->assign('_user',$data['_object']);

    return $smarty->fetch('showcase/employee.tpl');
    


}


?>