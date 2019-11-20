<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2017 at 10:17:56 GMT-5, CdMx Mexico
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'conf/object_fields.php';

/**
 * @var $webpage \Page
 */
$webpage = $state['_object'];


$object_fields = get_object_fields($webpage, $db, $user, $smarty, array());
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('object', $webpage);

$html='';
if($webpage->get('Webpage Scope')=='Category Categories' or $webpage->get('Webpage Scope')=='Category Products' ){

    $scope=get_object('Category',$webpage->get('Webpage Scope Key'));


    if($scope->get('Product Category Public')=='No'){

        if ($scope->get('Product Category Public')=='No') {

            $html = '<div style="background-color: tomato;color:whitesmoke;padding:5px 20px"><h1>'._('Category not public, webpage offline').'</h1></div>';
        }
    }

}


$html .= $smarty->fetch('edit_object.tpl');


