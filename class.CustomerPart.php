<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 Jun 2021 12:29 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';
include_once 'trait.NotesSubject.php';

include_once 'utils/natural_language.php';

class CustomerPart extends DB_Table {
    use NotesSubject;

    /**
     * @var \Part
     */
    public $part;

    function __construct($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Customer Part';
        $this->ignore_fields = array('Customer Part Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf("SELECT * FROM `Customer Part Dimension` WHERE `Customer Part Key`=%d", $tag);
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['Customer Part Key'];
            $this->properties = json_decode($this->data['Customer Part Properties'], true);

            $this->part = get_object('Part', $this->data['Customer Part Part SKU'], false, $this->db);
        }


    }

    function find($raw_data, $options) {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor) or in_array(
                        $key, [
                                'Subject',
                                'Subject Key'
                            ]
                    )) {
                    $this->editor[$key] = $value;
                }
            }
        }


        $this->found     = false;
        $this->found_key = false;

        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        if (!$data['Customer Part Part SKU'] and isset($raw_data['Part Reference'])) {

            $sql = sprintf(
                "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`=%s", prepare_mysql($raw_data['Part Reference'])
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $this->found            = true;
                    $this->found_key        = $row['Part SKU'];
                    $this->duplicated_field = 'Part Reference';

                    return;
                }
            }


        }


        if ($data['Customer Part Part SKU'] > 0 and $data['Customer Part Status'] != 'Discontinued') {
            $sql = sprintf(
                "SELECT `Customer Part Key` FROM `Customer Part Dimension` WHERE `Customer Part Customer Key`=%d AND `Customer Part Part SKU`=%d AND `Customer Part Status`!='Discontinued' ", $data['Customer Part Customer Key'], $data['Customer Part Part SKU']
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $this->found     = true;
                    $this->found_key = $row['Customer Part Key'];
                    $this->get_data('id', $this->found_key);
                    $this->duplicated_field = 'Available Customer Part';

                    return;
                }
            } 

        }


        $sql = sprintf(
            "SELECT `Customer Part Key` FROM `Customer Part Dimension` WHERE  `Customer Part Customer Key`=%d AND `Customer Part Reference`=%s  ", $data['Customer Part Customer Key'], prepare_mysql($data['Customer Part Reference'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Customer Part Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Customer Part Reference';

                return;
            }
        } 


        if ($create and !$this->found) {
            $this->create($data);

        }


    }

    function create($data) {

        $this->new = false;
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        if ($base_data['Customer Part From'] == '') {
            $base_data['Customer Part From'] = gmdate('Y-m-d H:i:s');
        }

      

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "`Customer Part Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec("INSERT INTO ".$sql)) {
            $this->id = $this->db->lastInsertId();

            $this->fast_update(array('Customer Part Properties' => '{}'));


            $this->msg = "Customer part added";
            $this->get_data('id', $this->id);


            $this->new = true;

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => _("Customer part created"),
                'History Details'  => ''
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );
            $this->fork_index_elastic_search();


            return;
        } else {
            $this->msg = "Error can not create customer part ";
            // print $sql;
            exit;
        }
    }



    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        $field = preg_replace('/^Part Part /', 'Part ', $field);


        switch ($field) {

            case 'label carton':
                $this->fast_update_json_field('Customer Part Properties', preg_replace('/\s/', '_', $field), $value);

                break;
            case 'Customer Part Carton Weight':

                $this->fast_update_json_field('Customer Part Properties', strtolower(preg_replace('/^Customer_Part_/', '', preg_replace('/\s/', '_', $field))), $value);

                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Weight' => $this->get('Carton Weight')

                    )
                );

                break;

            case 'Customer Part Units Per Carton':


                if ($this->part->data['Part Units Per Package'] == 0 or $this->part->data['Part Units Per Package'] == '') {
                    $this->error;
                    $this->msg = 'Units per package are null or zero';

                    return;

                }

                $this->update(array('Customer Part Packages Per Carton' => $value / $this->part->data['Part Units Per Package']), $options);

                break;

            case 'Customer Part Unit Label':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit label missing');

                    return;
                }

                $this->part->update(
                    array('Part Unit Label' => $value), $options
                );
                $this->updated = $this->part->updated;


                break;

            case 'Customer Part Description':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Description missing');

                    return;
                }

                $this->update_field($field, $value, $options);


                break;
            case 'Customer Part Package Description':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Outers (SKO) description');

                    return;
                }


                $this->part->update(
                    array('Part Package Description' => $value), $options
                );
                $this->updated = $this->part->updated;


                break;
            case 'Customer Part Status':


                if (!in_array(
                    $value, array(
                              'Available',
                              'NoAvailable',
                              'Discontinued'
                          )
                )) {
                    $this->error = true;
                    $this->msg   = _('Invalid availability value');

                    return;
                }

                $this->update_field($field, $value, $options);
                $this->update_metadata = array(
                    'class_html' => array(
                        'Status'           => $this->get('Status'),
                        'Average_Delivery' => $this->get('Average Delivery')

                    )
                );

                if ($this->updated) {
                    $this->part->update_on_demand();

                }


                break;




            case 'Customer Part Currency Code':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Currency code missing');

                    return;
                }

                $sql  = "SELECT count(*) AS num FROM kbase.`Currency Dimension` WHERE `Currency Code`=? AND `Currency Status`='Active'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $value
                    )
                );
                if ($row = $stmt->fetch()) {
                    if ($row['num'] == 0) {
                        $this->error = true;
                        $this->msg   = sprintf(_('Currency code not found (%s)'), $value);

                        return;
                    }
                }


                $this->update_field($field, $value, $options);


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'      => $this->get('Carton Cost'),
                        'SKO_Cost'         => $this->get('SKO Cost'),
                        'Unit_Cost_Amount' => $this->get('Unit Cost Amount'),

                    )
                );
                break;
            case 'Customer Part Reference':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = sprintf(_('Reference missing'));

                    return;
                }

                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Customer Part Dimension` WHERE `Customer Part Reference`=%s AND `Customer Part Customer Key`=%d AND `Customer Part Key`!=%d ', prepare_mysql($value), $this->get('Customer Part Customer Key'), $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated reference (%s)'), $value
                            );

                            return;
                        }
                    }
                }

                $this->update_field($field, $value, $options);


                break;

            case 'Customer Part Carton CBM':


                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid carton CBM (%s)'), $value);

                    return;
                }


                $this->update_field($field, $value, $options);

                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_CBM' => $this->get('Carton CBM')
                    )
                );


                break;


            case 'Part Units Per Package':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Units per SKO missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid units per SKO (%s)'), $value
                    );

                    return;
                }


                $this->part->update(array($field => $value), $options);
                $this->updated = $this->part->updated;

                $this->other_fields_updated = array(
                    'Customer_Part_Unit_Cost'           => array(
                        'field'           => 'Customer_Part_Unit_Cost',
                        'render'          => true,
                        'value'           => $this->get(
                            'Customer Part Unit Cost'
                        ),
                        'formatted_value' => $this->get('Unit Cost'),
                    ),
                    'Customer_Part_Packages_Per_Carton' => array(
                        'field'           => 'Customer_Part_Packages_Per_Carton',
                        'render'          => true,
                        'value'           => $this->get(
                            'Customer Part Packages Per Carton'
                        ),
                        'formatted_value' => $this->get('Packages Per Carton'),
                    )
                );


                $this->update_field($field, $value, $options);



                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Net_Weight'               => $this->get('Carton Net Weight'),
                        'Carton_Cost'                     => $this->get(
                            'Carton Cost'
                        ),
                        'SKO_Weight'                      => $this->get(
                            'SKO Weight'
                        ),
                        'SKO_Cost'                        => $this->get(
                            'SKO Cost'
                        ),
                        'Packages_Per_Carton'             => $this->get(
                            'Customer Part Packages Per Carton'
                        ),
                        'Customer_Part_Units_Per_Package' => $this->get(
                            'Part Units Per Package'
                        ),
                        'Customer_Part_Units_Per_Carton'  => $this->get(
                            'Units Per Carton'
                        )


                    )
                );

                break;

            case 'Customer Part Carton Barcode':


                $this->update_field($field, $value, $options);

                //if ($this->part->get('Part Main Customer Part Key') == $this->id) {
                $this->part->editor = $this->editor;
                $this->part->update(array('Part Carton Barcode' => $value), $options);
                // }


                break;
            case 'Customer Part Packages Per Carton':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Outers (SKO) per carton missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid outers (SKO) per carton (%s)'), $value
                    );

                    return;
                }


                $this->update_field('Customer Part Packages Per Carton', $value, $options);


                $this->other_fields_updated = array(
                    'Customer_Part_Unit_Cost' => array(
                        'field'           => 'Customer_Part_Unit_Cost',
                        'render'          => true,
                        'value'           => $this->get(
                            'Customer Part Unit Cost'
                        ),
                        'formatted_value' => $this->get('Unit Cost'),
                    ),

                );


                //if ($this->part->get('Part Main Customer Part Key') == $this->id) {
                $this->part->editor = $this->editor;
                $this->part->update(array('Part SKOs per Carton' => $value));
                // }


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Net_Weight'                 => $this->get('Carton Net Weight'),
                        'Carton_Cost'                       => $this->get(
                            'Carton Cost'
                        ),
                        'Customer_Part_Packages_Per_Carton' => $this->get(
                            'Customer Part Packages Per Carton'
                        ),
                        'Customer_Part_Units_Per_Carton'    => $this->get(
                            'Units Per Carton'
                        )


                    )
                );

                break;


            case 'History Note':


                $this->add_note($value, '', '', $metadata['deletable']);
                break;
            case 'Part Unit Price':
                $this->part->update(array($field => $value), $options);
                $this->updated = $this->part->updated;
                $this->msg     = $this->part->msg;
                $this->error   = $this->part->error;

                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'         => $this->get('Carton Cost'),
                        'SKO_Cost'            => $this->get('SKO Cost'),
                        'Unit_Cost_Amount'    => $this->get('Unit Cost Amount'),
                        'Unit_Delivered_Cost' => $this->get('Unit Delivered Cost'),

                    )
                );

                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'formatted_value' => $this->get('Unit Price'),
                        'value'           => $this->part->get('Part Unit Price')
                    ),
                );

                break;
            default:


                if (preg_match('/^Part /', $field)) {


                    $this->part->update(array($field => $value), $options);
                    $this->updated = $this->part->updated;
                    $this->msg     = $this->part->msg;
                    $this->error   = $this->part->error;


                    if (isset($this->part->other_fields_updated)) {

                        $this->other_fields_updated = $this->part->other_fields_updated;
                    }

                } else {

                    $base_data = $this->base_data();

                    if (array_key_exists($field, $base_data)) {

                        if ($value != $this->data[$field]) {


                            $this->update_field($field, $value, $options);


                        }
                    }


                }

        }


    }

    function get($key, $data = false) {

        global $account;


        if (!$this->id) {
            return '';
        }


        switch ($key) {


            case 'Customer Part Units Per Package':
            case 'Customer Part Unit Label':
            case 'Customer Part Package Description':
                $key = preg_replace('/Customer /', '', $key);

                return $this->part->get($key);

            case 'Unit Description':
            case 'Unit Label':
            case 'Package Description':


                return $this->part->get($key);

            case 'Customer Name':

                $customer = get_object('Customer', $this->data['Customer Part Customer Key']);

                return $customer->get('Code');



            case 'Unit Barcode':

                return $this->part->get('Barcode');
            case 'SKO Barcode':

                return $this->part->get('SKO Barcode');



            case 'Carton CBM':
                if ($this->data['Customer Part Carton CBM'] == '') {
                    return '';
                }

                return number($this->data['Customer Part Carton CBM'], 4).' m³';
            case 'SKO Dimensions':
                return $this->part->get('Package Dimensions');
            case 'Unit Dimensions':
                return $this->part->get('Unit Dimensions');


            case 'Carton Net Weight':

                return weight(
                    $this->part->data['Part Package Weight'] * $this->data['Customer Part Packages Per Carton']
                );
            case 'Carton Net Weight Approx':

                return weight(
                    round($this->part->data['Part Package Weight'] * $this->data['Customer Part Packages Per Carton'], 1)
                );
            case 'Customer Part Carton Weight':

                return $this->properties('carton_weight');

            case 'Carton Weight':

                $carton_weight = $this->properties('carton_weight');
                if (is_numeric($carton_weight) and $carton_weight > 0) {
                    return weight($carton_weight);
                } else {
                    return '';
                }

            case 'Carton Weight Approx':
                $carton_weight = $this->properties('carton_weight');
                if (is_numeric($carton_weight) and $carton_weight > 0) {
                    return weight(
                        ceil($carton_weight)
                    );
                } else {
                    return '';
                }



            case 'SKO Weight':
                return $this->part->get('Package Weight');
                break;
            case 'Unit Weight':
                return $this->part->get('Unit Weight');
                break;
            case 'Carton Cost':
                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                return money(
                    $this->data['Customer Part Unit Cost'] * $this->part->data['Part Units Per Package'] * $this->data['Customer Part Packages Per Carton'], $this->data['Customer Part Currency Code']
                );

            case 'Carton Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }



                $exchange = currency_conversion(
                    $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                );

                $delivered_cost = $this->part->data['Part Units Per Package'] * $this->data['Customer Part Packages Per Carton'] * ($this->data['Customer Part Unit Cost'] ) / $exchange;

                return money(
                    $delivered_cost, $account->get('Account Currency')
                );


            case 'SKO Cost':
                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                return money(
                    $this->data['Customer Part Unit Cost'] * $this->part->data['Part Units Per Package'], $this->data['Customer Part Currency Code']
                );
                break;
            case 'SKO Cost AC':

                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                include_once 'utils/currency_functions.php';

                $exchange = currency_conversion(
                    $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                );

                return money(
                    $this->data['Customer Part Unit Cost'] * $this->part->data['Part Units Per Package'] / $exchange, $account->get('Account Currency')
                );
                break;

            case 'Carton Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                if ($this->data['Customer Part Unit Extra Cost'] == '') {
                    $extra_cost = 0;
                } else {
                    $extra_cost = $this->data['Customer Part Unit Extra Cost'];
                }

                $exchange = currency_conversion(
                    $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                );

                $delivered_cost = $this->part->data['Part Units Per Package'] * ($this->data['Customer Part Unit Cost'] + $extra_cost) / $exchange;

                return money(
                    $delivered_cost, $account->get('Account Currency')
                );
            case 'Customer Part Units Per Carton':
                if ($this->data['Customer Part Packages Per Carton'] == '' or $this->part->data['Part Units Per Package'] == '') {
                    return '';
                }

                return $this->data['Customer Part Packages Per Carton'] * $this->part->data['Part Units Per Package'];
            case 'Units Per Carton':
                if ($this->data['Customer Part Packages Per Carton'] == '' or $this->part->data['Part Units Per Package'] == '') {
                    return '';
                }

                $units_per_carton = number($this->data['Customer Part Packages Per Carton'] * $this->part->data['Part Units Per Package']);

                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric($this->part->data['Part Units Per Package'])) {

                    $units_per_carton .= '<span class="discreet"> ('.number($this->data['Customer Part Packages Per Carton']).' '.ngettext(
                            'pack', 'packs', $this->data['Customer Part Packages Per Carton']
                        ).')</span>';
                }

                return $units_per_carton;

                break;

            case 'Packages Per Carton':
                if ($this->data['Customer Part Packages Per Carton'] == '') {
                    return _('Not Set');
                }
                if ($this->data['Customer Part Packages Per Carton'] == 1) {
                    return '<span class="italic discreet">'._('Not packed in cartons').'</span>';
                }


                $value = number($this->data['Customer Part Packages Per Carton']);


                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric($this->part->data['Part Units Per Package'])) {
                    $value .= ' <span class="discreet italic">('.number(
                            $this->data['Customer Part Packages Per Carton'] * $this->part->data['Part Units Per Package']
                        ).' '._('units').')</span>';
                }

                // $value .= ' <span class="italic very_discreet">('.$this->part->data['Part SKOs per Carton'].' '._("SKOs per selling carton").')</span>';


                return $value;
                break;

            case 'Unit Cost Amount':

                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }
                $cost = money(
                    $this->data['Customer Part Unit Cost'], $this->data['Customer Part Currency Code'], false, 'FOUR_FRACTION_DIGITS'
                );

                return $cost;
                break;


            case 'Unit Cost':


                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                $cost = money(
                    $this->data['Customer Part Unit Cost'], $this->data['Customer Part Currency Code'], false, 'FOUR_FRACTION_DIGITS'
                );


                $cost_other_info = '';
                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric($this->part->data['Part Units Per Package'])) {
                    $cost_other_info = money(
                            $this->data['Customer Part Unit Cost'] * $this->part->data['Part Units Per Package'], $this->data['Customer Part Currency Code']
                        ).' '._('per SKO');
                }
                if ($this->data['Customer Part Packages Per Carton'] != 1 and is_numeric(
                        $this->data['Customer Part Packages Per Carton']
                    )) {
                    $cost_other_info .= ', '.money(
                            $this->data['Customer Part Unit Cost'] * $this->part->data['Part Units Per Package'] * $this->data['Customer Part Packages Per Carton'], $this->data['Customer Part Currency Code']
                        ).' '._('per carton');
                }

                $cost_other_info = preg_replace('/^, /', '', $cost_other_info);
                if ($cost_other_info != '') {
                    $cost .= ' <span class="discreet">('.$cost_other_info.')</span>';
                }


                return $cost;

            case 'SKO Margin':
                include_once 'utils/currency_functions.php';
                $exchange = currency_conversion(
                    $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                );

                $unit_margin = $this->part->data['Part Unit Price'] - $this->data['Customer Part Unit Cost'] / $exchange;

                return sprintf(
                    '<span class="'.($unit_margin < 0 ? 'error' : '').'">'._('margin %s').'</span>', percentage($unit_margin, $this->part->data['Part Unit Price'])
                );
                break;

            case 'Unit Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                if ($this->data['Customer Part Unit Extra Cost'] == '') {
                    $extra_cost = 0;
                } else {
                    $extra_cost = $this->data['Customer Part Unit Extra Cost'];
                }
                $exchange       = currency_conversion(
                    $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                );
                $delivered_cost = ($this->data['Customer Part Unit Cost'] + $extra_cost) / $exchange;

                $cost = sprintf(
                    '<span title="%s(%s) +%s(%s) @%s">%s</span>', money($this->data['Customer Part Unit Cost'], $this->data['Customer Part Currency Code']), _('cost'), money($extra_cost, $this->data['Customer Part Currency Code']), _('extra costs'), $exchange,
                    money($delivered_cost, $account->get('Account Currency'))
                );

                return $cost;
                break;

            case 'SKO Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Customer Part Unit Cost'] == '') {
                    return '';
                }

                if ($this->data['Customer Part Unit Extra Cost'] == '') {
                    $extra_cost = 0;
                } else {
                    $extra_cost = $this->data['Customer Part Unit Extra Cost'];
                }
                $exchange       = currency_conversion(
                    $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                );
                $delivered_cost = ($this->data['Customer Part Unit Cost'] + $extra_cost) / $exchange;


                $cost = sprintf(
                    '<span title="%s(%s) +%s(%s) @%s ">%s </span>', money($this->data['Customer Part Unit Cost'], $this->data['Customer Part Currency Code']), _('cost'), money($extra_cost, $this->data['Customer Part Currency Code']), _('extra costs'), $exchange,


                    money($delivered_cost, $account->get('Account Currency'))
                );

                return $cost;
                break;




            case 'Status':

                switch ($this->data['Customer Part Status']) {
                    case 'Available':
                        $status = sprintf(
                            '<i class="fa fa-hand-receiving success" ></i> %s', _('Available')
                        );
                        break;
                    case 'NoAvailable':
                        $status = sprintf(
                            '<i class="fa fa-hand-receiving warning" ></i> %s', _('No available')
                        );

                        break;
                    case 'Discontinued':
                        $status = sprintf(
                            '<i class="fa fa-hand-receiving error" ></i> %s', _('Discontinued')
                        );

                        break;
                    default:
                        $status = $this->data['Customer Part Status'];
                        break;
                }

                return $status;
            case 'Status Icon':

                switch ($this->data['Customer Part Status']) {
                    case 'Available':
                        $status = sprintf(
                            '<i title="%s" class="fa fa-hand-receiving success" ></i> ', _('Available')
                        );
                        break;
                    case 'NoAvailable':
                        $status = sprintf(
                            '<i title="%s" class="fa fa-hand-receiving warning" ></i> ', _('No available')
                        );

                        break;
                    case 'Discontinued':
                        $status = sprintf(
                            '<i title="%s" class="fa fa-hand-receiving error" ></i> ', _('Discontinued')
                        );

                        break;
                    default:
                        $status = $this->data['Customer Part Status'];
                        break;
                }

                return $status;


            case 'Unit Price':


                if ($this->part->data['Part Unit Price'] == '') {
                    return '';
                }
                include_once 'utils/natural_language.php';
                $unit_price = money(
                    $this->part->data['Part Unit Price'], $account->get('Account Currency')
                );

                $price_other_info = '';
                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric(
                        $this->part->data['Part Units Per Package']
                    )) {
                    $price_other_info = '('.money(
                            $this->part->data['Part Unit Price'] * $this->part->data['Part Units Per Package'], $account->get('Account Currency')
                        ).' '._('per SKO').'), ';
                }


                if ($this->part->data['Part Units Per Package'] != 0 and is_numeric($this->part->data['Part Units Per Package'])) {

                    include_once 'utils/currency_functions.php';


                    $exchange = currency_conversion(
                        $this->db, $account->get('Account Currency'), $this->data['Customer Part Currency Code'], '- 1 hour'
                    );

                    $unit_margin = $this->part->data['Part Unit Price'] - ($this->data['Customer Part Unit Cost'] + $this->data['Customer Part Unit Extra Cost']) / $exchange;

                    $price_other_info .= sprintf(
                        '<span class="'.($unit_margin < 0 ? 'error' : '').'">'._('future margin %s').'</span>', percentage($unit_margin, $this->part->data['Part Unit Price'])
                    );
                }

                $price_other_info = preg_replace(
                    '/^, /', '', $price_other_info
                );
                if ($price_other_info != '') {
                    $unit_price .= ' <span class="discreet">'.$price_other_info.'</span>';
                }

                return $unit_price;




            default:
                if (preg_match('/^Part /', $key)) {

                    return $this->part->get(preg_replace('/^Part /', '', $key));

                }

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer Part '.$key, $this->data)) {
                    return $this->data['Customer Part '.$key];
                }
        }

        return '';
    }

    function properties($key) {
        return (isset($this->properties[$key]) ? $this->properties[$key] : '');
    }



    function delete($metadata = false) {


        $sql = "INSERT INTO `Customer Part Deleted Dimension`  (`Customer Part Deleted Key`,`Customer Part Deleted Reference`,`Customer Part Deleted Date`,`Customer Part Deleted Metadata`) VALUES (?,?,?,?) ";


        $this->db->prepare($sql)->execute(
            array(
                $this->id,
                $this->get('Customer Part Reference'),
                gmdate('Y-m-d H:i:s'),
                gzcompress(json_encode($this->data), 9)
            )
        );


        $sql = sprintf(
            'DELETE FROM `Customer Part Dimension`  WHERE `Customer Part Key`=%d ', $this->id
        );
        $this->db->exec($sql);
        //print "$sql\n";

        $history_data = array(
            'History Abstract' => sprintf(_("Customer part record %s deleted"), $this->data['Customer Part Reference']),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


            $this->part->delete();
        


        $this->deleted = true;
        $this->fork_index_elastic_search('delete_elastic_index_object');


        return 'part/'.$this->data['Customer Part Part SKU'];

    }


    function get_field_label($field) {


        switch ($field) {

            case 'Customer Part Reference':
                $label = _("Reference");
                break;
            case 'Customer Part Cost':
                $label = _('unit cost');
                break;
            case 'Customer Part Unit Expense':
                $label = _('unit expense');
                break;
            case 'Customer Part Batch':
                $label = _('batch');
                break;
            case 'Customer Part Status':
                $label = _('availability');
                break;
            case 'Customer Part Minimum Carton Order':
                $label = _("Minimum order (cartons)");
                break;
            case 'Customer Part Packages Per Carton':
                $label = _("Packed units per carton");
                break;
            case 'Customer Part Carton CBM':
                $label = _("carton CBM");
                break;
            case 'Customer Part Average Delivery Days':
                $label = _("Average delivery time (days)");
                break;
            case 'Customer Part Unit Cost':
                $label = _("unit cost");
                break;

            case 'Part Reference':
                $label = _("part reference");
                break;
            case 'Part Barcode Number':
                $label = _("part barcode");
                break;
            case 'Part Unit Price':
                $label = _("unit recommended price");
                break;
            case 'Part Unit RRP':
                $label = _("unit recommended RRP");
                break;
            case 'Part Package Description':
                $label = _("outers (SKO) description");
                break;
            case 'Customer Part Description':
                $label = _("Unit description");
                break;
            case 'Part Unit Label':
                $label = _("Unit label");
                break;
            case 'Part Units Per Package':
                $label = _("units per SKO");
                break;
            case 'Customer Part On Demand':
                $label = _('on demand');
                break;
            case 'Customer Part Fresh':
                $label = _('make to order');
                break;
            case 'Part Recommended Product Unit Name':
                $label = _('unit recommended description');
                break;
            case 'Customer Part Carton Weight':
                $label = _('carton gross weight');
                break;
            default:
                $label = $field;

        }

        return $label;

    }





}


