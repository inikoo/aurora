<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait DeliveryNoteAiku
{
    function model_updated($table, $field, $key)
    {
        $this->process_pika_fetch(
            'DeliveryNote', $key, $field,
            [
                'new',
                'deleted',
                'Delivery Note State',
                'Delivery Note Date',
                'Delivery Note Shipper Key',
                'Delivery Note ID',
                'Delivery Note Shipper Consignment',
                'Delivery Note Shipper Tracking',
                'Delivery Note Date Dispatched',
                'Delivery Note Date Start Picking',
                'Delivery Note Date Finish Picking',
                'Delivery Note Date Start Packing',
                'Delivery Note Date Finish Packing'

            ]
        );
    }

}