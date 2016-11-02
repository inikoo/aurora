<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 11:19:48 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_website_node_showcase($data, $smarty) {


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

    return $smarty->fetch('showcase/website_node.tpl');


}


?>