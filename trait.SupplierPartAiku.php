<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait SupplierPartAiku
{
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'SupplierPart',
            $key,
            $field
        );
    }

}