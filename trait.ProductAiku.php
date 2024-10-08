<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait ProductAiku
{
    function model_updated($table, $field, $key)
    {
        $this->process_pika_fetch(
            'Product', $key, $field,
            [
                'new',
                'deleted',
                'Product Customer Key',
                'Product Status',
                'Product Units Per Case',
                'Product Valid From',
                'Product Price',
                'Product Current Key',
                'Product Code',
                'Product Name'
            ]
        );
    }

}