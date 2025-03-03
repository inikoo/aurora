<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait InvoiceAiku
{
    public function model_updated($field, $key)
    {
        if ($field == 'deleted') {
            $this->use_field = 'delete_invoice';
            $model='DeleteInvoice';
        }else{
            $model='Invoice';
        }

        $this->process_aiku_fetch(
            $model,
            $key,
            $field,
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