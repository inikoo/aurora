<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: 2020-10-23T02:33:49+08:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait OrderAiku
{

    public function model_updated($table, $field, $key)
    {
        $this->process_aiku_fetch(
            'Order',
            $key,
            $field,
            [
                'Order For Collection',
                'Order Email',
                'Order Telephone',
                'Order State',
                'Order Customer Client Key',
                'Order Customer Key',
                'Order Invoiced Date',
                'Order Dispatched Date',
                'Order Packed Date',
                'Order Packed Done Date',
                'Order Send to Warehouse Date',
                'Order Public ID',
                'Order Customer Purchase Order ID',
                'Order Currency Exchange',
                'Order Created Date',

            ]
        );
    }

}