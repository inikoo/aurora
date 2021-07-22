<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 02:01:17 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
include_once 'conf/fields/picking_pipeline.fld.php';


/**
 * @throws \SmartyException
 */
function get_picking_pipeline_showcase($data, Smarty $smarty): string {


    /**
     * @var $picking_pipeline \Picking_Pipeline
     */

    $picking_pipeline = $data['_object'];
    if (!$picking_pipeline->id) {

        $html = '';

    } else {


        $smarty->assign('asset', $picking_pipeline);

        $smarty->assign(
            'object_data', json_encode(
                             array(
                                 'object' => $data['object'],
                                 'key'    => $data['key'],

                             )
                         )

        );


        $html = $smarty->fetch('showcase/picking_pipeline.tpl');
    }


    return $html;

}



