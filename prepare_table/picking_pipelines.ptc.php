<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 11 Jul 2021 16:46:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_picking_pipelines extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s pipeline',
                '%s pipelines'
            ],
            [
                '%s pipeline of %s',
                '%s pipelines of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Picking Pipeline Name`',
            'key'  => '`Picking Pipeline Key`'
        ];
    }


    function prepare_table() {


        $this->where = 'where true ';
        $this->table = '`Picking Pipeline Dimension` PP  left join `Store Dimension` on (`Store Key`=`Picking Pipeline Store Key`) 
         ';

        if ($this->parameters['parent'] == 'warehouse') {

            $this->where = sprintf(
                'where   `Picking Pipeline Warehouse Key`=%d', $this->parameters['parent_key']
            );


        }


        if (($this->parameters['f_field'] == 'name') and $this->f_value != '') {

            $this->wheref = sprintf(
                '  and  `Picking Pipeline Name`  like "%s%%" ', addslashes($this->f_value)
            );


        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'name') {
            $this->order = '`Picking Pipeline Name`';
        } elseif ($this->sort_key == 'locations') {
            $this->order = '`Picking Pipeline Number Locations`';
        } elseif ($this->sort_key == 'part_locations') {
            $this->order = '`Picking Pipeline Number Part Locations`';
        } else {
            $this->order = '`Picking Pipeline Key`';
        }

        $this->fields = '`Picking Pipeline Key`,`Store Type`,`Picking Pipeline Warehouse Key`,`Picking Pipeline Store Key`,`Store Code`,
        `Picking Pipeline Name`,`Picking Pipeline Number Locations`,`Picking Pipeline Number Part Locations`';

        $this->sql_totals = "select "."count(`Picking Pipeline Key`) as num from $this->table $this->where ";


    }

    function get_data() {


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {


            $this->table_data[] = array(
                'id'   => (integer)$data['Picking Pipeline Key'],
                'name' => sprintf('<span class="link" onclick="change_view(\'%s\')" >%s</span>  ', '/warehouse/'.$data['Picking Pipeline Warehouse Key'].'/pipelines/'.$data['Picking Pipeline Key'], $data['Picking Pipeline Name']),
                'store' => sprintf('<span class="link" onclick="change_view(\'%s\')" >%s</span>  ', '/store/'.$data['Picking Pipeline Store Key'].'/pipeline/'.$data['Picking Pipeline Key'], $data['Store Code']),

                'locations'=>number($data['Picking Pipeline Number Locations']),
                'part_locations'=>number($data['Picking Pipeline Number Part Locations']),
            );


        }





    }
}