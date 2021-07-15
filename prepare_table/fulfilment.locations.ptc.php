<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 22:24 MYR , Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

class prepare_table_fulfilment_locations extends prepare_table {

    function __construct($_data, $db) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s location',
                '%s locations'
            ],
            [
                '%s location of %s',
                '%s locations of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Location Code`',
            'key'  => '`Location Key`'
        ];

    }


    function prepare_table() {


        switch ($this->parameters['parent']) {
            case('warehouse'):
                $this->where = sprintf(
                    ' where  `Location Warehouse Key`=%d and `Location Fulfilment`="Yes"', $this->parameters['parent_key']
                );
                break;

            default:
                exit ('parent not found '.$this->parameters['parent']);
        }

        $this->where .= ' and `Location Type`!="Unknown"';

        $this->wheref = '';
        if ($this->parameters['f_field'] == 'code' and $this->f_value != '') {
            $this->wheref .= " and  `Location Code` like '".addslashes($this->f_value)."%'";
        }


        if ($this->sort_key == 'code') {
            $this->order = '`Location File As`';
        } elseif ($this->sort_key == 'parts') {
            $this->order = '`Location Distinct Parts`';
        } elseif ($this->sort_key == 'stock_value') {
            $this->order = '`Location Stock Value`';
        } elseif ($this->sort_key == 'max_volume') {
            $this->order = '`Location Max Volume`';
        } elseif ($this->sort_key == 'max_weight') {
            $this->order = '`Location Max Weight`';
        } elseif ($this->sort_key == 'area') {
            $this->order = '`Warehouse Area Code`';
        } elseif ($this->sort_key == 'flag') {
            $this->order = '`Warehouse Flag Key`';
        } elseif ($this->sort_key == 'warehouse') {
            $this->order = '`Warehouse Code`';
        } else {
            $this->order = '`Location Key`';
        }

        $this->order_direction=$this->sort_direction;


        $this->table  =
            '`Location Dimension` L left join `Warehouse Area Dimension` WAD on (`Location Warehouse Area Key`=WAD.`Warehouse Area Key`) left join `Warehouse Dimension` WD on (`Location Warehouse Key`=WD.`Warehouse Key`) left join `Warehouse Flag Dimension`F  on (F.`Warehouse Flag Key`=L.`Location Warehouse Flag Key`)';
        $this->fields =
            "`Location Place`,`Location Key`,`Warehouse Flag Label`,`Warehouse Flag Color`,`Location Warehouse Key`,`Location Warehouse Area Key`,`Location Code`,`Location Distinct Parts`,`Location Max Volume`,`Location Max Weight`, `Location Mainly Used For`,`Warehouse Area Code`,`Warehouse Flag Key`,`Warehouse Code`,`Location Stock Value`";

        $this->sql_totals = "select count(*) as num from $this->table $this->where ";

    }

    function get_data() {

        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";


        $link = 'fulfilment/locations/'.$this->parameters['parent_key'].'/';

        foreach ($this->db->query($sql) as $data) {


            if ($data['Location Max Weight'] == '' or $data['Location Max Weight'] <= 0) {
                $max_weight = '<span class="super_discreet italic">'._('Unknown').'</span>';
            } else {
                $max_weight = number($data['Location Max Weight'])._('Kg');
            }
            if ($data['Location Max Volume'] == '' or $data['Location Max Volume'] <= 0) {
                $max_vol = '<span class="super_discreet italic">'._('Unknown').'</span>';
            } else {
                $max_vol = number($data['Location Max Volume']).'m³';
            }


            $code = sprintf('<span class="link" onclick="change_view(\'%s/%d\')">%s</span>', $link, $data['Location Key'], $data['Location Code']);
            //$area = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/areas/%d\')">%s</span>', $data['Location Warehouse Key'], $data['Location Warehouse Area Key'], $data['Warehouse Area Code']);

            if ($data['Location Place'] == 'External') {
                $type = ' <i  title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car   "></i>';
            } else {
                $type = '';
            }


            $this->table_data[] = array(
                'id'          => (integer)$data['Location Key'],
                'code'        => $code,
                //     'flag'        => ($data['Warehouse Flag Key'] ? sprintf(
                //         '<i id="flag_location_%d" class="fa fa-flag %s button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" location_key="%d" title="%s"></i>', $data['Location Key'], strtolower($data['Warehouse Flag Color']), $data['Location Key'],
                //         $data['Warehouse Flag Label']
                //     ) : '<i id="flag_location_'.$data['Location Key'].'"  class="far fa-flag super_discreet button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" key="" ></i>'),
                //    'flag_key'    => $data['Warehouse Flag Key'],
                //    'area'        => $area,
                'max_weight'  => $max_weight,
                'max_volume'  => $max_vol,
                'type'        => $type,
                'parts'       => number($data['Location Distinct Parts']),
                'stock_value' => money($data['Location Stock Value'], $this->account->get('Account Currency')),

                // 'used_for'           => $used_for
            );

        }

    }

}



