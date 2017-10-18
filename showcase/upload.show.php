<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2016 at 12:51:07 CEST, Mlaga, Spain

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_upload_showcase($data, $smarty, $user, $db) {

    $object = $data['_object'];
    if (!$object->id) {
        return "";
    }
    $object->load_file_data();
    $smarty->assign('upload', $object);


    if($object->get('Upload Type')=='EditObjects'){
        $title=sprintf(_('Editing %s in %s'),$object->get('Object'),$object->get('Parent'));

    } elseif($object->get('Upload Type')=='NewObjects'){
        $title=sprintf(_('Adding %s to %s'),$object->get('Object'),$object->get('Parent'));

    }else{
        $title='';
    }

    $smarty->assign('_title', $title);

    return $smarty->fetch('showcase/upload.tpl');


}


?>
