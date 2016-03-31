<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 March 2016 at 18:09:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_upload_showcase($data) {

    global $smarty;
    
 
    if(!$data['_object']->id){
        return "";
    }
    
    $smarty->assign('upload',$data['_object']);

    return $smarty->fetch('showcase/upload.tpl');
    


}


?>