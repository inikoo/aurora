<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  22 July 2017 at 18:43:54 CEST, Trnava, Slovalia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'conf/export_edit_template_fields.php';


 $objects = 'product';
    $smarty->assign('title', sprintf(_("Products in part family %s"), $state['_object']->get('Code')));
    $smarty->assign('parent', 'part_family');
    $smarty->assign('parent_key', $state['key']);
    $smarty->assign('parent_code', $state['_object']->get('Code'));

$smarty->assign('objects', $objects);
$smarty->assign('edit_fields', $export_edit_template_fields[$objects]);

$html = $smarty->fetch('edit_table.tpl');


?>
