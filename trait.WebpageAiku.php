<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait WebpageAiku
{

    public function model_published($key)
    {
        $this->process_aiku_fetch(
            'PublishWebpage',
            $key
        );
    }

    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'Webpage',
            $key,
            $field,
            [
                'new',
                'Webpage Code',
                'Webpage Scope',
                'Webpage Scope Key',
                'Webpage State',
                'Webpage Name',
                'Webpage Creation Date',
                'Webpage Meta Description'

            ]
        );
    }

}