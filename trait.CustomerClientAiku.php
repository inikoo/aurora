<?php

/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 14:36:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait CustomerClientAiku {

    function model_updated($table, $field, $key)
    {
        if ($field == 'deleted') {
            $this->use_field = 'delete_customer_client';
        }


        $this->process_pika_fetch(
            'CustomerClient', $key, $field,
            [
                'new',
                'deleted',
                'deactivate',
                'Customer Client Customer Key',
                'Customer Client Status',
                'Customer Client Metadata',
                'Customer Client Code',
                'Customer Client Main Contact Name',
                'Customer Client Company Name',
                'Customer Client Main Plain Email',
                'Customer Client Main Plain Mobile',
                'Customer Client Creation Date'
            ]
        );
    }
}