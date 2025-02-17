<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 15 Feb 2025 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait SupplierDeliveryAiku
{
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'SupplierDelivery',
            $key,
            $field
        );
    }

}