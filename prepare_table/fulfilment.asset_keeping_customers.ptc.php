<?php /** @noinspection DuplicatedCode */
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 Jun 2021 01:16 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 2.0
*/
include_once 'utils/prepare_table.php';

class prepare_table_fulfilment_asset_keeping_customers extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s customer',
                '%s customers'
            ],
            [
                '%s customer of %s',
                '%s customers of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Customer Name`',
            'key'  => 'C.`Customer Key`'
        ];

    }

    function prepare_table() {

        $this->group_by = ' group by C.`Customer Key` ';


        $this->table = '`Customer Fulfilment Dimension` CFD  left join `Customer Dimension` C on (CFD.`Customer Fulfilment Customer Key`=C.`Customer Key`) 
    left join `Store Dimension` S on (S.`Store Key`=C.`Customer Store Key`)
    ';

        $this->where = sprintf(' where  `Customer Fulfilment Warehouse Key`=%d and `Store Type`="Fulfilment" ', $this->parameters['parent_key']);
        if (isset($this->parameters['extra']) and $this->parameters['extra'] == 'only_with_stored_parts') {
            $this->where .= ' where  `Customer Fulfilment Status`="Storing" ';

        }

        $this->wheref = '';


        if (($this->parameters['f_field'] == 'name') and $this->f_value != '') {
            $this->wheref = sprintf(
                ' and `Customer Name` REGEXP "\\\\b%s" ', addslashes($this->f_value)
            );
        }


        if ($this->sort_key == 'name') {
            $this->order = '`Customer Name`';
        } elseif ($this->sort_key == 'formatted_id') {
            $this->order = 'C.`Customer Key`';
        } elseif ($this->sort_key == 'location') {
            $this->order = '`Customer Location`';
        } else {
            $this->order = '`Customer Name`';
        }

        $this->order_direction = $this->sort_direction;

        $this->sql_totals = "select count(Distinct C.`Customer Key`) as num from"." $this->table  $this->where ";

        include_once 'utils/object_functions.php';


        $this->fields = 'C.`Customer Key`,`Customer Name`,`Customer Location`,`Customer Type by Activity`,`Customer Store Key`,`Customer Fulfilment Status`';

    }

    function get_data() {


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";


        if ($result = $this->db->query($sql)) {

            foreach ($result as $data) {


                switch ($data['Customer Fulfilment Status']) {
                    case 'ToApprove':
                        $activity = _('To be approved');
                        break;
                    case 'Inactive':
                        $activity = _('Inactive');
                        break;
                    case 'Active':
                        $activity = _('Active');
                        break;
                    case 'Prospect':
                        $activity = _('Prospect');
                        break;
                    default:
                        $activity = $data['Customer Type by Activity'];
                        break;
                }


                $link_format = '/fulfilment/%d/customers/asset_keeping/%d';

                $formatted_id = sprintf('<span class="link" onClick="change_view(\''.$link_format.'\')">%06d</span>', $this->parameters['parent_key'], $data['Customer Key'], $data['Customer Key']);


                $this->table_data[] = array(
                    'id'           => (integer)$data['Customer Key'],
                    'store_key'    => $data['Customer Store Key'],
                    'formatted_id' => $formatted_id,

                    'name' => $data['Customer Name'],

                    'location' => $data['Customer Location'],
                    'activity' => $activity,
                    //'invoices' => number($data['invoices']),
                    //'orders'   => number($data['orders']),
                    //'amount'   => money($data['amount'], $data['Store Currency Code'])


                );
            }

        }


    }

}