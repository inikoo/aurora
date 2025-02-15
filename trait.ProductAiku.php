<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait ProductAiku
{
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'Product',
            $key,
            $field,
            [
                'new',
                'deleted',
                'parts',
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