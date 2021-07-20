<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 20 Jul 2021 12:56:49 Malaysia Time, Kuala Lumpur, Malaysia
 *  Based from: 16 January 2018 at 15:43:12 GMT+8, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_customers_geographic_distribution extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s country',
                '%s countries'
            ],
            [
                '%s country of %s',
                '%s countries of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Country Name`',
            'key'  => '`Country Key`'
        ];
    }


    function prepare_table() {


        $this->where = 'where true ';
        $this->table = '`Customer Dimension` C left join kbase.`Country Dimension` Co on (C.`Customer Contact Address Country 2 Alpha Code`=Co.`Country 2 Alpha Code`)  ';


        if ($this->parameters['parent'] == 'store') {

            $this->where  = sprintf(
                ' where  `Customer Store Key`=%d', $this->parameters['parent_key']
            );
            $sales_fields = "sum(`Customer Sales Amount`) as sales";

        } else {
            $sales_fields = "sum(`Customer Sales DC Amount`) as sales";

        }


        if (($this->parameters['f_field'] == 'country') and $this->f_value != '') {

            $this->wheref = sprintf(
                '  and `Country Name`  like  "\\\\b%s"  ', addslashes($this->f_value)
            );


        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'country') {
            $this->order = '`Country Name`';
        } elseif ($this->sort_key == 'customers' or $this->sort_key == 'customers_percentage') {
            $this->order = 'customers';
        } elseif ($this->sort_key == 'sales' or $this->sort_key == 'sales_percentage') {
            $this->order = 'sales';
        } elseif ($this->sort_key == 'invoices') {
            $this->order = 'invoices';
        } elseif ($this->sort_key == 'flag') {
            $this->order = '`Country 2 Alpha Code`';
        } elseif ($this->sort_key == 'sales_per_customer') {
            $this->order = 'sales_per_registration';
        } else {
            $this->order = '`Country Key`';
        }


        $this->fields   = "`Country Key`,`Country Name`,`Country 2 Alpha Code`,count(*) as customers,
        $sales_fields,
        sum(`Customer Number Invoices`) as invoices,  
        
        sum(`Customer Sales Amount`)/count(*) as sales_per_registration";
        $this->group_by = ' group by `Customer Contact Address Country 2 Alpha Code` ';

        $this->sql_totals = "select "."count(distinct `Customer Contact Address Country 2 Alpha Code`) as num from $this->table $this->where ";


    }

    function get_data() {


        if ($this->parameters['parent'] == 'store') {
            $store           = get_object('Store', $this->parameters['parent_key']);
            $total_customers = $store->get('Store Contacts');
            $total_sales     = $store->get('Store Total Acc Invoiced Amount');

            $currency = $store->get('Store Currency Code');
        } else {
            $total_customers = $this->account->get('Store Contacts');
            $total_sales     = $this->account->get('Store Total Acc Invoiced Amount');

            $currency = $this->account->get('Currency Code');

        }


        $sql = "select $this->fields from $this->table $this->where $this->wheref  $this->group_by order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {


            $flag_img_source = "/art/flags/".strtolower($data['Country 2 Alpha Code']).".png";


            $this->table_data[] = array(
                'id'      => (integer)$data['Country Key'],
                'country' => $data['Country Name'],
                'flag'    => sprintf("<img style='max-width:16px' alt='%s' title='%s' src='$flag_img_source'/>", $data['Country 2 Alpha Code'], $data['Country 2 Alpha Code'].' '.$data['Country Name']),

                'customers'            => number($data['customers']),
                'customers_percentage' => percentage($data['customers'], $total_customers),
                'invoices'             => number($data['invoices']),
                'sales'                => money($data['sales'], $currency),
                'sales_percentage'     => percentage($data['sales'], $total_sales),
                'sales_per_customer'   => money($data['sales_per_registration'], $currency),
            );


        }

    }
}