<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: 2020-10-23T02:33:49+08:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait PublicOrderAiku
{

    public function model_updated($field, $key)
    {

        $this->process_aiku_fetch(
            'Order',
            $key,
            $field,
            [
              'Order State',
              '_new'

            ]
        );
    }

}