<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait InvoiceAiku
{
    function model_updated($table, $field, $key)
    {
        if ($field == 'deleted') {
            $this->use_field = 'delete_invoice';
        }

        $this->process_aiku_fetch(
            'Invoice', $key, $field,
            [
                'new',
                'deleted',
                'Invoice Public ID',
                'Invoice Type',
                'Invoice Date',
                'Invoice Currency Exchange',
                'Invoice Total Net Amount',
                'Invoice Total Amount',
                'Invoice Message'

            ]
        );
    }
}