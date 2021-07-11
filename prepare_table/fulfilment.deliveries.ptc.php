<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoD.com>
 Created: 7 July 2021 at 20:38:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/prepare_table.php';


class prepare_table_fulfilment_deliveries extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s delivery',
                '%s deliveries'
            ],
            [
                '%s delivery of %s',
                '%s deliveries of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => 'CONCAT(LPAD(D.`Fulfilment Delivery Key`,6,0),\' \',`Fulfilment delivery Public ID`)',
            'key'  => 'D.`Fulfilment Delivery Key`'
        ];
    }


    function prepare_table() {


        $this->where = 'where true ';
        $this->table = '`Fulfilment Delivery Dimension` D  left join `Store Dimension` on (`Fulfilment Delivery Store Key`=`Store Key`)  ';

        if ($this->parameters['parent'] == 'warehouse') {

            $this->where = sprintf(
                'where   `Fulfilment Delivery Warehouse Key`=%d', $this->parameters['parent_key']
            );


        } elseif ($this->parameters['parent'] == 'customer') {
            $this->where = sprintf(
                'where   `Fulfilment Delivery Customer Key`=%d  ', $this->parameters['parent_key']
            );
        }

        if (isset($this->parameters['period'])) {
            include_once 'utils/date_functions.php';
            $tmp = calculate_interval_dates(
                $this->db, $this->parameters['period'], $this->parameters['from'], $this->parameters['to']
            );

            $where_interval = prepare_mysql_dates(
                $tmp[1], $tmp[2], 'D.`Fulfilment Delivery Date`'
            );
            $this->where    .= $where_interval['mysql'];
        }

        if (isset($parameters['elements_type'])) {


            switch ($parameters['elements_type']) {
                case('state'):
                    $_elements            = '';
                    $num_elements_checked = 0;

                    //enum('InProcess','Received','Checked','ReadyToPlace','Placed','Cancelled')

                    foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
                        $_value = $_value['selected'];
                        if ($_value) {
                            $num_elements_checked++;
                            $_elements .= ",'".addslashes($_key)."'";
                        }
                    }

                    if ($_elements == '') {
                        $this->where .= ' and false';
                    } elseif ($num_elements_checked < 6) {
                        $_elements   = preg_replace('/^,/', '', $_elements);
                        $this->where .= ' and `Fulfilment Delivery State` in ('.$_elements.')';
                    }
                    break;
            }
        }


        if (($this->parameters['f_field'] == 'number') and $this->f_value != '') {

            $this->wheref = sprintf(
                '  and  `Fulfilment Delivery Public ID`  like "%%%s%%" ', addslashes($this->f_value)
            );


        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'customer_delivery_reference') {
            $this->order = '`Fulfilment Delivery File As`';
        } elseif ($this->sort_key == 'last_date') {
            $this->order = 'D.`Fulfilment Delivery Last Updated Date`';
        } elseif ($this->sort_key == 'date') {
            $this->order = 'D.`Fulfilment Delivery Creation Date`';
        } elseif ($this->sort_key == 'customer') {
            $this->order = 'D.`Fulfilment Delivery Customer Name`';
        } elseif ($this->sort_key == 'state') {
            $this->order = 'D.`Fulfilment Delivery State`';
        } else {
            $this->order = 'D.`Fulfilment Delivery Key`';
        }

        $this->fields = '`Fulfilment Delivery Customer Key`,D.`Fulfilment Delivery Key`,`Fulfilment Delivery State`,`Fulfilment Delivery Public ID`,D.`Fulfilment Delivery Last Updated Date`,`Fulfilment Delivery Creation Date`,
`Fulfilment Delivery Customer Name`,`Fulfilment Delivery Received Date`,`Fulfilment Delivery Estimated Receiving Date`,`Store Type`,`Fulfilment Delivery Warehouse Key`
';

        $this->sql_totals = "select "."count(Distinct D.`Fulfilment Delivery Key`) as num from $this->table $this->where ";


    }

    function get_data() {


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {

            switch ($data['Fulfilment Delivery State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;
                case 'Consolidated':
                    $state = sprintf('%s', _('Consolidated'));
                    break;
                case 'Dispatched':
                    $state = sprintf('%s', _('Dispatched'));
                    break;
                case 'Received':
                    $state = sprintf('%s', _('Received'));
                    break;
                case 'Checked':
                    $state = sprintf('%s', _('Checked'));
                    break;
                case 'Placed':
                    $state = _('Booked in');
                    break;
                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Fulfilment Delivery State'];
                    break;
            }

            if ($data['Store Type'] == 'Dropshipping') {
                $_link_customer = 'fulfilment/'.$data['Fulfilment Delivery Warehouse Key'].'/customers/dropshipping/'.$data['Fulfilment Delivery Customer Key'];
            } else {
                $_link_customer = 'fulfilment/'.$data['Fulfilment Delivery Warehouse Key'].'/customers/asset_keeping/'.$data['Fulfilment Delivery Customer Key'];
            }


            $customer_delivery_reference = $data['Fulfilment Delivery Public ID'];
            if ($customer_delivery_reference == '') {
                $customer_delivery_reference = '<span class="discreet italic">'._('No set').'</span>';
            }

            $this->table_data[] = array(
                'id'                          => (integer)$data['Fulfilment Delivery Key'],
                'date'                        => strftime("%e %b %Y", strtotime($data['Fulfilment Delivery Creation Date'].' +0:00')),
                'last_date'                   => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Fulfilment Delivery Last Updated Date'].' +0:00')),
                'customer'                    => sprintf('<span class="link" onclick="change_view(\'/%s\')" >%s</span>  ', $_link_customer, $data['Fulfilment Delivery Customer Name']),
                'customer_delivery_reference' => $customer_delivery_reference,
                'formatted_id'                => sprintf('<span class="link" onclick="change_view(\'%s\')" >%06d</span>  ', $_link_customer.'/delivery/'.$data['Fulfilment Delivery Key'], $data['Fulfilment Delivery Key']),
                'state'                       => $state,
            );


        }

    }
}