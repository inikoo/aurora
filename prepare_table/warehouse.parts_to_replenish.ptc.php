<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 28 Jul 2021 17:19:53 Malaysia Time, Kuala Lumpur, Malaysia
 *  Original: 29 September 2015 16:56:07 BST, Sheffield, UK
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_warehouse_parts_to_replenish extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s replenishment',
                '%s replenishments'
            ],
            [
                '%s replenishment of %s',
                '%s replenishments of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Part Reference`',
            'key'  => '`Part SKU`'
        ];
    }


    function prepare_table() {

        $this->where = " where L.`Location Pipeline`='No' and  `Can Pick`='Yes' and `Minimum Quantity`>=0 and   `Minimum Quantity`>=(`Quantity On Hand`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` ) and (P.`Part Current On Hand Stock`-`Quantity On Hand`)>=0  and (`Part Distinct Locations`-`Part Pipeline Locations`)>1 ";

        $this->table = "
    `Part Location Dimension` PL left join `Location Dimension` L on (PL.`Location Key`=L.`Location Key`) left join `Part Dimension` P on (PL.`Part SKU`=P.`Part SKU`) left join `Warehouse Flag Dimension` on (`Warehouse Flag Key`=`Location Warehouse Flag Key`)
     ";

        $this->fields = " `Part Symbol`, `Part Distinct Locations`,  P.`Part Current On Hand Stock`,  `Part Current Stock In Process`+ `Part Current Stock Ordered Paid` as ordered_quantity,`Quantity On Hand`- `Part Current Stock In Process`- `Part Current Stock Ordered Paid` as effective_stock,`Location Warehouse Key`,`Quantity On Hand`,`Minimum Quantity`,`Maximum Quantity`,PL.`Location Key`,`Location Code`,P.`Part Reference`,`Warehouse Flag Color`,`Warehouse Flag Key`,`Warehouse Flag Label`,PL.`Part SKU`,
        IFNULL((select GROUP_CONCAT(SL.`Location Key`,':',SL.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') from `Part Location Dimension` PLD  left join `Location Dimension` SL on (SL.`Location Key`=PLD.`Location Key`) where SL.`Location Pipeline`='No' and   PLD.`Part SKU`=P.`Part SKU`),'') as location_data,
            `Part Next Deliveries Data`,`Part Units Per Package`,`Part Package Description`
            
            ";



        if (($this->parameters['f_field'] == 'reference') and $this->f_value != '') {

            $this->wheref = sprintf(
                '  and  `Part Reference`  like "%s%%" ', addslashes($this->f_value)
            );


        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'reference') {
            $this->order = '`Fulfilment Asset Reference`';
        } elseif ($this->sort_key == 'type') {
            $this->order = '`Fulfilment Asset Type`';
        } else {
            $this->order = '`Fulfilment Asset Key`';
        }



        if ($this->sort_key == 'part') {
            $this->order = '`Part Reference`';
        } elseif ($this->sort_key == 'location') {
            $this->order  = '`Location File As`';
        } elseif ($this->sort_key == 'quantity') {
            $this->order  = '`Quantity On Hand`';
        } elseif ($this->sort_key == 'quantity') {
            $this->order  = '`Quantity On Hand`';
        } elseif ($this->sort_key == 'ordered_quantity') {
            $this->order  = 'ordered_quantity';
        } elseif ($this->sort_key == 'effective_stock') {
            $this->order  = 'effective_stock';
        } elseif ($this->sort_key == 'next_deliveries') {
            $this->order  = "(`Part Number Active Deliveries`+`Part Number Draft Deliveries`)";
        } else {

            $this->order  = '`Part SKU`';
        }



        $this->sql_totals = "select count(*) as num from $this->table $this->where ";


    }

    function get_data() {


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {

            $locations_data = preg_split('/,/', $data['location_data']);


            $stock = '<div >';

            foreach ($locations_data as $raw_location_data) {
                if ($raw_location_data != '') {
                    $_locations_data = preg_split('/:/', $raw_location_data);
                    if ($_locations_data[0] != $data['Location Key']) {
                        $stock .= '<div style="clear:both">';
                        $stock .= '<div style="float:left;min-width:100px;">
<span class="link"  onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$_locations_data[0].'\')" >'.$_locations_data[1].'</span>
</div><div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
                        $stock .= '</div>';
                    }
                }
            }
            $stock .= '</div>';


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries_array = array();
            } else {
                $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
            }


            $next_deliveries = '';

            foreach ($next_deliveries_array as $next_delivery) {


                $next_deliveries .= '<div class="as_row "><div class="as_cell padding_left_5" style="min-width: 100px" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_10 as_cell strong" style="text-align: right;min-width: 40px" title="'._('SKOs ordered')
                    .'">+'.number(
                        $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


            }


            $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


            $reference = sprintf(
                '<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'],
                ($data['Part Reference'] == '' ? '<i class="fa error fa-exclamation-circle"></i> <span class="discreet italic">'._('Reference missing').'</span>' : $data['Part Reference'])
            );

            if ($data['Part Symbol'] != '') {
                if ($data['Part Symbol'] != '') {

                    switch ($data['Part Symbol']) {
                        case 'star':
                            $symbol = '&#9733;';
                            break;

                        case 'skull':
                            $symbol = '&#9760;';
                            break;
                        case 'radioactive':
                            $symbol = '&#9762;';
                            break;
                        case 'peace':
                            $symbol = '&#9774;';
                            break;
                        case 'sad':
                            $symbol = '&#9785;';
                            break;
                        case 'gear':
                            $symbol = '&#9881;';
                            break;
                        case 'love':
                            $symbol = '&#10084;';
                            break;
                        default:
                            $symbol = '';

                    }
                    $reference .= ' '.$symbol;
                }

            }


            $this->table_data[] = array(
                'id'                    => (integer)$data['Location Key'],
                'location'              => ($data['Warehouse Flag Key'] ? sprintf(
                        '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>', strtolower($data['Warehouse Flag Color']), $data['Warehouse Flag Label']
                    ) : '<i class="far fa-flag super_discreet" aria-hidden="true"></i>').' <span class="link" onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$data['Location Key'].'\')">'.$data['Location Code'].'</span>',
                'part'                  => $reference,
                'other_locations_stock' => $stock,

                'quantity'             => number($data['Quantity On Hand']),
                'ordered_quantity'     => number($data['ordered_quantity']),
                'effective_stock'      => number($data['effective_stock']),
                'recommended_quantity' => ' <span class="padding_left_5">(<span style="display: inline-block;min-width: 20px;text-align: center">'.number($data['Minimum Quantity']).'</span>,<span style="display: inline-block;min-width: 25px;text-align: center">'.number(
                        $data['Maximum Quantity']
                    ).'</span>)</span>',
                'next_deliveries'      => $next_deliveries
            );


        }

    }
}