<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait PartAiku
{
    function model_updated($table, $field, $key)
    {
        $this->process_aiku_fetch(
            'Stock', $key, $field,
            [
                'new',
                'deleted',
                'locations',
                'Part Reference',
                'Part Recommended Product Unit Name',
                'Part Valid From',
                'Part Active From',
                'Part Units Per Package',
                'Part Valid To',
                'Part Status'

            ]
        );
    }

}