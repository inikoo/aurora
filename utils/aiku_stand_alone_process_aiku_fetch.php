<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 Feb 2025 19:22 Sanur Bali

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



function stand_alone_process_aiku_fetch(string $model, ?int $key, ?string $field = null, ?array $valid_fields = null)
{

    if(!$key){
        return;
    }

    if (is_null($valid_fields) or is_null($field) or in_array($field, $valid_fields)) {
        include_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_aiku',
            array(
                'model'    => $model,
                'model_id' => $key,
                'field'    => $field
            ),
            DNS_ACCOUNT_CODE
        );
    }
}