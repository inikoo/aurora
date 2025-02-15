<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 15 Feb 2025 13:38:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */
include_once 'trait.Aiku.php';

trait PublishedEmailTemplateAiku
{

    use Aiku;
    public function model_updated($field, $key)
    {
        $this->process_aiku_fetch(
            'Published_Email_Template',
            $key,
            $field,
            [
                'dispatched_email',
            ]
        );
    }

}