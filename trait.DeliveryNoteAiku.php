<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait DeliveryNoteAiku
{
    public function model_updated($table, $field, $key)
    {
        if ($field == 'deleted') {
            $this->use_field = 'delete_delivery_note';
        }


        $this->process_aiku_fetch(
            'DeliveryNote',
            $key,
            $field,
            [
                'new',
                'deleted',
                'Delivery Note State',
                'Delivery Note Date',
                'Delivery Note Weight',
                'Delivery Note Shipper Key',
                'Delivery Note ID',
                'Delivery Note Shipper Consignment',
                'Delivery Note Shipper Tracking',
                'Delivery Note Date Dispatched',
                'Delivery Note Date Start Picking',
                'Delivery Note Date Finish Picking',
                'Delivery Note Date Start Packing',
                'Delivery Note Date Finish Packing',
                'Delivery Note Date Done Approved',
                'Delivery Note Email',
                'Delivery Note Telephone'

            ]
        );
    }

}