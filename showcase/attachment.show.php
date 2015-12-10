<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 December 2015 at 00:11:02 GMT Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_attachment_showcase($data) {

    global $smarty;
    
    $attachment=$data['_object'];
    if(!$attachment->id){
        return "";
    }
    
    $smarty->assign('attachment',$attachment);

    return $smarty->fetch('showcase/attachment.tpl');
    


}


?>