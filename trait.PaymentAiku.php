<?php


trait PaymentAiku
{

    public function model_updated($table, $field, $key)
    {
        $this->process_aiku_fetch(
            'Payment',
            $key,
            $field,
            [
                'new',
                'Payment Currency Exchange Rate',
                'Payment Store Key',
                'Payment Transaction Status',
                'Payment Created Date',
                'Payment Last Updated Date',
                'Payment Completed Date',
                'Payment Transaction ID',
                'Payment Transaction Amount',
                'Payment Currency Code',
            ]
        );
    }

}