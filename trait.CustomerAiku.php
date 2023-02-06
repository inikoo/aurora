<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait CustomerAiku
{
    function model_updated($table, $field, $key)
    {
        $this->process_pika_fetch(
            'Customer', $key, $field,
            [
                'new',
                'Customer Main Contact Name',
                'Customer Company Name',
                'Customer Type by Activity',
                'Customer Main Plain Email',
                'Customer Main Plain Mobile',
                'Customer Registration Number',
                'Customer Website',
                'Customer Tax Number',
                'Customer Tax Number Valid'
            ]
        );
    }

}