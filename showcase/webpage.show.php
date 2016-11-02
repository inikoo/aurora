<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 10:22:57 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_webpage_showcase($data, $smarty) {


    $node = $data['_object'];
    if (!$node->id) {
        return "";
    }

    /*
      $images=$node->get_images_slidesshow();

      if (count($images)>0) {
          $main_image=$images[0];
      }else {
          $main_image='';
      }
  */

    $smarty->assign('node', $node);

    return $smarty->fetch('showcase/webpage.tpl');


}


?>