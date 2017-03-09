<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2016 at 20:22:17 GMT+8 Kuta Bali
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';
include_once 'conf/object_fields.php';






$category = $state['_object'];

$webpage=$category->get_webpage();


if(!$webpage->id){



    if($category->get('Product Category Public')=='Yes'){



        $html='<div style="padding:40px"><span class="button save valid changed" onclick="create_webpage()"><i class="fa fa-plus" aria-hidden="true"></i> '._("Create web page").'</span></div>
        <script>
        function create_webpage(){
        
          var request = \'/ar_edit_website.php?tipo=create_webpage&parent=category&parent_key=' . $category->id.'\'

        $.getJSON(request, function (data) {

            if (data.state == 200) {

               change_view(state.request)

            }

        })


        
        
        }
        </script>
        
        ';



    //    $html='<div style="padding:40px">'._("This category has no webpage").'</div>';


    }else{
        $html='<div style="padding:40px">'._("This category is not public").'</div>';
    }



    return;

}


$object_fields = get_object_fields($category, $db, $user, $smarty, array('type' => 'webpage_settings'));

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('object', $category);

$html = $smarty->fetch('edit_object.tpl');

?>
