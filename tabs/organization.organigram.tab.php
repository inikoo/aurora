<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 November 2015 at 10:46:01 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';


$tree = array(
    array(
        'position' => 'xxx',
        'children' => array(array('position' => 'yyy'))


    ),


);

$smarty->assign('tree', $tree);


$html = $smarty->fetch('organigram.tpl');

?>
