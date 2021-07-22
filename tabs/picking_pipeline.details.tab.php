<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 22 Jul 2021 19:48:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/country_functions.php';
include_once 'utils/invalid_messages.php';
include_once 'conf/fields/picking_pipeline.fld.php';

/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */

if ($state['parent'] == 'store') {

    if ($user->can_edit('stores') and in_array($state['_parent']->id, $user->stores)) {

        $warehouse     = get_object('Warehouse', $state['current_warehouse']);
        $object_fields = get_picking_pipeline_fields($state['_object'], $warehouse, $user);
        $smarty->assign('object_fields', $object_fields);
        $smarty->assign('state', $state);
        $smarty->assign('object', $state['_object']);

        if ($state['_object']->id) {
            $template = 'edit_object.tpl';
        } else {
            $template = 'new_object.tpl';
        }

        try {
            $html = $smarty->fetch();
        } catch (Exception $e) {
            $html = '';
        }

    } else {
        try {
            $html = $smarty->fetch('access_denied.tpl');
        } catch (Exception $e) {
            $html = '';
        }
    }

}

