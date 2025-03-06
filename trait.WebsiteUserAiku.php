<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait WebsiteUserAiku
{
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'WebsiteUser',
            $key,
            $field,
            [
                'new',
                'Website User Active',
                'Website User Handle',
                'Website User Created',
                'Website User Password'
            ]
        );
    }

}