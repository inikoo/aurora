<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 14:21:08 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/
include_once 'conf/export_edit_template_fields.php';

function get_export_edit_template_fields($object) {
    $fields = [];
    if (isset($export_edit_template_fields[$object])) {
        $fields = $export_edit_template_fields[$object];
    }

    return $fields;
}




