<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2021 16:12 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/

include_once 'utils/prepare_table.php';


class prepare_table_fulfilment_parts extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s part',
                '%s parts'
            ],
            [
                '%s part of %s',
                '%s parts of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Part Reference`',
            'key'  => 'P.`Part SKU`'
        ];

    }


    function prepare_table() {

        $this->where = "where true  ";
        $this->table =
            "`Customer Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Customer Part Part SKU`) left join `Customer Dimension` S on (SP.`Customer Part Customer Key`=S.`Customer Key`)  left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";


        if ($this->parameters['parent'] == 'customer') {
            $this->where = sprintf(
                " where  `Customer Part Customer Key`=%d", $this->parameters['parent_key']
            );

        } elseif ($this->parameters['parent'] == 'warehouse') {


            $this->where = sprintf(
                " where  `Customer Part Warehouse Key`=%d", $this->parameters['parent_key']
            );


        } else {
            exit("parent not found x : ".$this->parameters['parent']);
        }


        if (isset($this->parameters['f_period'])) {

            $db_period = get_interval_db_name($this->parameters['f_period']);
            if (in_array(
                $db_period, array(
                              'Total',
                              '3 Year'
                          )
            )) {

                $yb_sales      = '0';
                $yb_dispatched = '0';
            } else {
                $yb_sales      = "`Part $db_period Acc 1YB Invoiced Amount`";
                $yb_dispatched = "`Part $db_period Acc 1YB Dispatched`";
            }

        } else {
            $db_period = 'Total';
            // $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";
            $yb_sales      = '0';
            $yb_dispatched = '0';
        }


        if (isset($this->parameters['elements_type'])) {

            switch ($this->parameters['elements_type']) {
                case 'status':
                    $_elements      = '';
                    $count_elements = 0;
                    foreach (
                        $this->parameters['elements'][$this->parameters['elements_type']]['items'] as $_key => $_value
                    ) {
                        if ($_value['selected']) {
                            $count_elements++;
                            $_elements .= ','.prepare_mysql($_key);

                        }
                    }


                    $_elements = preg_replace('/^,/', '', $_elements);
                    if ($_elements == '') {
                        $this->where .= ' and false';
                    } elseif ($count_elements < 3) {
                        $this->where .= ' and `Customer Part Status` in ('.$_elements.')';

                    }
                    break;
                case 'part_status':
                    $_elements      = '';
                    $count_elements = 0;
                    foreach ($this->parameters['elements'][$this->parameters['elements_type']]['items'] as $_key => $_value) {
                        if ($_value['selected']) {
                            $count_elements++;

                            if ($_key == "InUse") {
                                $_key = "In Use";
                            } elseif ($_key == "NotInUse") {
                                $_key = "Not In Use";
                            } elseif ($_key == 'InProcess') {
                                $_key = "In Process";
                            }


                            $_elements .= ','.prepare_mysql($_key);

                        }
                    }
                    $_elements = preg_replace('/^,/', '', $_elements);
                    if ($_elements == '') {
                        $this->where .= ' and false';
                    } elseif ($count_elements < 4) {

                        $this->where .= ' and `Part Status` in ('.$_elements.')';


                    }
                    break;


            }
        }

        if ($this->parameters['f_field'] == 'reference' and $this->f_value != '') {
            $this->wheref .= " and ( `Part Reference` like '".addslashes($this->f_value)."%'   or  `Customer Part Reference` like '".addslashes($this->f_value)."%' ) ";
        } elseif ($this->parameters['f_field'] == 'description' and $this->f_value != '') {
            $this->wheref .= " and  `Customer Part Description` like '".addslashes($this->f_value)."%'";
        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'reference') {
            $this->order = '`Customer Part Reference`';
        } elseif ($this->sort_key == 'description') {
            $this->order = '`Customer Part Description`';
        } elseif ($this->sort_key == 'cost') {
            $this->order = '`Customer Part Unit Cost`';
        } elseif ($this->sort_key == 'delivered_cost') {
            $this->order = '(`Customer Part Unit Cost`+`Customer Part Unit Extra Cost`)';
        } elseif ($this->sort_key == 'customer_code') {
            $this->order = '`Customer Code`';
        } elseif ($this->sort_key == 'barcode') {
            $this->order = '`Part Barcode Number`';
        } elseif ($this->sort_key == 'barcode_sko') {
            $this->order = '`Part SKO Barcode`';
        } elseif ($this->sort_key == 'barcode_carton') {
            $this->order = '`Part Carton Barcode`';
        } elseif ($this->sort_key == 'weight_sko') {
            $this->order = '`Part Package Weight`';
        } elseif ($this->sort_key == 'cbm') {
            $this->order = '`Customer Part Carton CBM`';
        } elseif ($this->sort_key == 'dispatched') {
            $this->order = "`Part $db_period Acc Dispatched` ";
        } elseif ($this->sort_key == 'dispatched_1yb') {
            $this->order = "(`Part $db_period Acc Dispatched`-$yb_dispatched) /$yb_dispatched ";
        } elseif ($this->sort_key == 'sales') {
            $this->order = "`Part $db_period Acc Invoiced Amount` ";
        } elseif ($this->sort_key == 'sales_1yb') {
            $this->order = "(`Part $db_period Acc Invoiced Amount`-$yb_sales) /$yb_sales ";
        } elseif ($this->sort_key == 'stock') {
            $this->order = '`Part Current On Hand Stock`';
        } elseif ($this->sort_key == 'stock_status') {
            $this->order = '`Part Stock Status`';
        } elseif ($this->sort_key == 'dispatched_per_week') {
            $this->order = '`Part 1 Quarter Acc Dispatched`';
        } elseif ($this->sort_key == 'available_forecast') {
            $this->order = '`Part Days Available Forecast`';
        } elseif ($this->sort_key == 'next_deliveries') {
            $this->order = "(`Part Number Active Deliveries`+`Part Number Draft Deliveries`)";
        } else {

            $this->order = '`Customer Part Key`';
        }


        $this->sql_totals = "select"." count(Distinct SP.`Customer Part Key`) as num from $this->table  $this->where  ";


        $this->fields = "`Part Status`,`Customer Name`,`Customer Part Key`,`Customer Part Part SKU`,`Part Reference`,`Customer Part Description`,`Customer Part Customer Key`,`Customer Part Reference`,`Customer Part Status`,`Customer Part From`,`Customer Part To`,`Customer Part Unit Cost`,`Customer Part Currency Code`,`Part Units Per Package`,`Customer Part Packages Per Carton`,`Customer Part Carton CBM`,
`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Barcode Number`,`Part SKO Barcode`,`Part Current On Hand Stock`,`Part Carton Barcode`,`Part Package Weight`,`Customer Part Carton CBM`,$yb_sales as sales_1yb,  $yb_dispatched as dispatched_1yb,
`Part Cost in Warehouse`,`Part Next Deliveries Data`,`Part On Demand`,`Part Days Available Forecast`,`Part $db_period Acc Dispatched` as dispatched,`Part $db_period Acc Invoiced Amount` as sales ,
`Part Commercial Value`,`Part 1 Quarter Acc Dispatched`
";
        //print $sql_totals;


    }


    function get_data() {

        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array()
        );
        while ($data = $stmt->fetch()) {




            switch ($data['Supplier Part Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-stop success" title="%s"></i>', _('Available')
                    );
                    break;
                case 'NoAvailable':
                    $status = sprintf(
                        '<i class="fa fa-stop warning" title="%s"></i>', _('No available')
                    );

                    break;
                case 'Discontinued':
                    $status = sprintf(
                        '<i class="fa fa-ban error" title="%s"></i>', _('Discontinued')
                    );

                    break;
                default:
                    $status = $data['Supplier Part Status'];
                    break;
            }

            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status       = '<i class="fa  fa-plus-circle fa-fw warning discreet"  aria-hidden="true" title="'._('To much stock').'" ></i>';
                    $stock_status_label = _('Surplus');
                    break;
                case 'Optimal':
                    $stock_status       = '<i class="fa fa-check-circle fa-fw success" aria-hidden="true"  title="'._('Good level of stock').'"></i>';
                    $stock_status_label = _('Ok');
                    break;
                case 'Low':
                    $stock_status       = '<i class="fa fa-minus-circle fa-fw warning discreet" aria-hidden="true" title="'._('Low stock, order now').'"></i>';
                    $stock_status_label = _('Low');
                    break;
                case 'Critical':
                    $stock_status       = '<i class="fa error fa-minus-circle fa-fw error discreet" aria-hidden="true" title="'._('Critical low stock, will be out of stock anytime').'"></i>';
                    $stock_status_label = _('Critical');
                    break;
                case 'Out_Of_Stock':
                    $stock_status       = '<i class="fa error fa-ban fa-fw error" aria-hidden="true" title="'._('Out of stock').'"></i>';
                    $stock_status_label = _('Out of stock');
                    break;
                case 'Error':
                    $stock_status       = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    $stock_status_label = _('Error');
                    break;
                default:
                    $stock_status       = $data['Part Stock Status'];
                    $stock_status_label = $data['Part Stock Status'];
                    break;
            }

            if ($data['Part Status'] == 'Not In Use') {
                $part_status = '<i class="fal fa-box fa-fw  error strikethrough" title="'._('Discontinued').'"></i> ';

            } elseif ($data['Part Status'] == 'Discontinuing') {
                $part_status = '<i class="fal fa-box fa-fw  error" title="'._('Discontinuing').'"></i> ';

            } else {
                $part_status = '<i class="fal fa-box fa-fw " aria-hidden="true"></i> ';
            }

            /*
                        if ($data['Part Cost in Warehouse'] == '') {
                            $stock_value = '<span class=" error italic">'._('Unknown cost').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


                        } elseif ($data['Part Cost in Warehouse'] == 0) {
                            $stock_value = '<span class=" error italic">'._('Cost is zero').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


                        } elseif ($data['Part Current On Hand Stock'] < 0) {
                            $stock_value = '<span class=" error italic">'._('Unknown stock').'</span> <i class="error fa fa-fw fa-exclamation-circle"></i>';


                        } else {
                            $stock_value = money($data['Part Cost in Warehouse'] * $data['Part Current On Hand Stock'], $this->account->get('Account Currency'));


                        }
            */


            if ($data['Part Next Deliveries Data'] == '') {
                $next_deliveries_array = array();
            } else {
                $next_deliveries_array = json_decode($data['Part Next Deliveries Data'], true);
            }


            $next_deliveries = '';

            foreach ($next_deliveries_array as $next_delivery) {


                $next_deliveries .= '<div class="as_row "><div class="as_cell padding_left_10" style="min-width: 150px" >'.$next_delivery['formatted_link'].'</div><div class="padding_left_20 as_cell strong" style="text-align: right;min-width: 70px" title="'._(
                        'SKOs ordered'
                    ).'">+'.number(
                        $next_delivery['raw_units_qty'] / $data['Part Units Per Package']
                    ).'<span style="font-weight: normal" class="small discreet">skos</span></div></div>';


            }


            $next_deliveries = '<div style="font-size: small" class="as_table">'.$next_deliveries.'</div>';


            if ($data['Part On Demand'] == 'Yes') {

                $available_forecast = '<span >'.sprintf(
                        '%s', '<span  title="'.sprintf("%s %s", number($data['Part Days Available Forecast']), ngettext("day", "days", intval($data['Part Days Available Forecast']))).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                    ).'</span>';

                if ($data['Part Fresh'] == 'No') {
                    $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5"  title="'._('On demand').'"></i>';
                } else {
                    $available_forecast = ' <i class="far fa-lemon padding_left_5"  title="'._('On demand').'"></i>';
                }
            } else {

                if ($data['Part Days Available Forecast'] == 0) {
                    $available_forecast = '';
                } else {

                    $available_forecast = '<span >'.sprintf(
                            '%s', '<span  title="'.sprintf(
                                    "%s %s", number($data['Part Days Available Forecast']), ngettext(
                                               "day", "days", intval($data['Part Days Available Forecast'])
                                           )
                                ).'">'.seconds_to_until($data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';

                }
            }


            $reference = sprintf('<span class="link" onClick="change_view(\'supplier/%d/part/%d\')" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']);
            if ($data['Supplier Part Reference'] != $data['Part Reference']) {
                $reference .= '<br><span  class="link '.($data['Part Status'] == 'Not In Use' ? 'strikethrough error' : '').'  " onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$part_status.' '.$data['Part Reference'].'</span> ';

            } else {
                $reference .= '<span  title="'._('Link to part').'" class="link margin_left_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$part_status.'</span> ';

            }

            if ($data['Part Cost in Warehouse'] == '') {
                $sko_stock_value = '<span class="super_discreet">'._('No set').'</span>';
            } else {
                $sko_stock_value = money($data['Part Cost in Warehouse'], $this->account->get('Account Currency'));
            }

            $this->table_data[] = array(
                'id'   => (integer)$data['Supplier Part Key'],
                'data' => '<span id="item_data_'.$data['Supplier Part Key'].'" class="item_data" data-key="'.$data['Supplier Part Key'].'" ></span>',

                'supplier_code'  => sprintf('<span class="link" onClick="change_view(\'supplier/%d/\')" >%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Code']),
                'part_reference' => $data['Part Reference'],
                'reference'      => $reference,


                'barcode'        => $data['Part Barcode Number'],
                'barcode_sko'    => $data['Part SKO Barcode'],
                'barcode_carton' => $data['Part Carton Barcode'],
                'weight_sko'     => ($data['Part Package Weight'] != '' ? weight($data['Part Package Weight'], 'Kg', 3, false, true) : '<i class="fa fa-exclamation-circle error"></i>'),
                'cbm'            => ($data['Supplier Part Carton CBM'] != '' ? $data['Supplier Part Carton CBM'].'m³' : '<i class="fa fa-exclamation-circle error"></i>'),


                'description'    => '<span  data-field="Supplier Part Description"  data-item_class="item_Supplier_Part_Description" class="table_item_editable item_Supplier_Part_Description"  >'.$data['Supplier Part Description'].'</span>',
                'status'         => $status,


                'stock'          => '<span class="'.($data['Part Current On Hand Stock'] < 0 ? 'error' : '').'">'.number(floor($data['Part Current On Hand Stock'])).'</span>',


                //'stock_value'        => $stock_value,

                'dispatched'     => number($data['dispatched'], 0),
                'dispatched_1yb' => '<span title="'.sprintf(_('%s dispatched same interval last year'), number($data['dispatched_1yb'])).'">'.delta($data['dispatched'], $data['dispatched_1yb']).'</span>',
                'sales'          => money($data['sales'], $this->account->get('Account Currency')),
                'sales_1yb'      => '<span title="'.sprintf(_('%s amount sold same interval last year'), money($data['sales_1yb'], $this->account->get('Account Currency'))).'">'.delta($data['sales'], $data['sales_1yb']).'</span>',

                'sko_stock_value'      => $sko_stock_value,
                'sko_commercial_value' => ($data['Part Commercial Value'] == '' ? '' : money($data['Part Commercial Value'], $this->account->get('Account Currency'))),
                'stock_status'         => $stock_status,
                'stock_status_label'   => $stock_status_label,
                'next_deliveries'      => $next_deliveries,
                'available_forecast'   => $available_forecast,
                'dispatched_per_week'  => number($data['Part 1 Quarter Acc Dispatched'] * 4 / 52, 0)

            );


        }


    }


}