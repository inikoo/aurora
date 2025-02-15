<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Oct 2020 15:03:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

trait Aiku
{

    protected bool $pika_ignore = false;

    protected string $use_field = '';


    public function get_table_name()
    {
    }

    public function update_aiku($a, $b)
    {
    }


    public function model_updated($table, $field, $key)
    {
    }


    public function process_aiku_fetch($model, $key, $field, $valid_fields)
    {
        if ($this->pika_ignore) {
            return;
        }

        if ($this->use_field) {
            $model = $this->use_field;
        }

        if (in_array($field, $valid_fields)) {
            include_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_aiku',
                array(
                    'model'    => $model,
                    'model_id' => $key,
                    'field'    => $field
                ),
                DNS_ACCOUNT_CODE
            );
        }
    }


}
