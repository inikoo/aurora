<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 15 Feb 2025 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait PurchaseOrderAiku
{
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'PurchaseOrder',
            $key,
            $field
        );
    }

}