<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait CustomerAiku
{
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'Customer',
            $key,
            $field,
            [
                'new',
                'deleted',
                'sync_portfolio',
                'tax_number_validation',
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