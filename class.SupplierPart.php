<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 10:49:10 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';
include_once 'class.Part.php';
include_once 'utils/natural_language.php';

class SupplierPart extends DB_Table {


    function SupplierPart($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Supplier Part';
        $this->ignore_fields = array('Supplier Part Key');

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
            $sql = sprintf(
                "SELECT * FROM `Supplier Part Dimension` WHERE `Supplier Part Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Part Key'];




            $this->part = new Part($this->data['Supplier Part Part SKU']);
        }


    }

    function find($raw_data, $options) {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;

        $create = '';
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        if (!$data['Supplier Part Part SKU'] and isset($raw_data['Part Reference'])) {

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
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }


        if ($data['Supplier Part Part SKU'] > 0 and $data['Supplier Part Status'] != 'Discontinued') {
            $sql = sprintf(
                "SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d AND `Supplier Part Part SKU`=%d AND `Supplier Part Status`!='Discontinued' ",
                $data['Supplier Part Supplier Key'], $data['Supplier Part Part SKU']
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $this->found     = true;
                    $this->found_key = $row['Supplier Part Key'];
                    $this->get_data('id', $this->found_key);
                    $this->duplicated_field = 'Available Supplier Part';

                    return;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

        }


        $sql = sprintf(
            "SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE  `Supplier Part Supplier Key`=%d AND `Supplier Part Reference`=%s  ", $data['Supplier Part Supplier Key'],
            prepare_mysql($data['Supplier Part Reference'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Supplier Part Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Supplier Part Reference';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
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


        if ($base_data['Supplier Part From'] == '') {
            $base_data['Supplier Part From'] = gmdate('Y-m-d H:i:s');
        }


        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            //   if (preg_match('/^(Supplier Part Address|Supplier Part Company Name|Supplier Part Company Number|Supplier Part VAT Number|Supplier Part Telephone|Supplier Part Email)$/i', $key))
            //    $values.=prepare_mysql($value, false).",";
            //   else
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Supplier Part Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = "Supplier part added";
            $this->get_data('id', $this->id);


            $this->new = true;

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => _("Supplier's part created"),
                'History Details'  => ''
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id()
            );


            return;
        } else {
            $this->msg = _(" Error can not create supplier part");
            print $sql;
            exit;
        }
    }

    function get_historic_data($key) {

        $sql = sprintf(
            'SELECT * FROM `Supplier Part Historic Dimension` WHERE `Supplier Part Historic Dimension`=%d ', $key
        );
        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        $field = preg_replace('/^Part Part /', 'Part ', $field);
        switch ($field) {


            case 'Supplier Part Units Per Carton':



                if($this->part->data['Part Units Per Package']==0 or $this->part->data['Part Units Per Package']==''){
                    $this->error;
                    $this->msg='Units per package are null or zero';
                    return;

                }

                    $this->update(array('Supplier Part Packages Per Carton'=>$value/$this->part->data['Part Units Per Package']),$options);

                break;
            case 'Supplier Part On Demand':

                $this->get_supplier_data();
                if (!in_array(
                    $value, array(
                    'No',
                    'Yes'
                )
                )
                ) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid value, valid values: %s'), '"Yes", "No"'
                    );

                    return;
                }
                if ($this->data['Supplier On Demand'] == 'No' and $value == 'Yes') {
                    $this->error = true;
                    $this->msg   = _(
                        "Supplier part can't set up as on demand because supplier isn't configured to allow that"
                    );

                    return;
                }
                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                if ($value == 'No') {
                    $this->update_field(
                        'Supplier Part Fresh', $value, $options
                    );
                    if ($this->updated) {
                        $updated = $this->updated;
                    }
                }

                $this->other_fields_updated = array(

                    'Supplier_Part_Fresh' => array(
                        'field'           => 'Supplier_Part_Fresh',
                        'render'          => ($value == 'Yes' ? true : false),
                        'value'           => $this->get('Supplier Part Fresh'),
                        'formatted_value' => $this->get('Fresh'),
                    ),

                );


                if ($updated) {
                    $this->part->update_on_demand();
                }
                break;

            case 'Supplier Part Fresh':

                if (!in_array(
                    $value, array(
                    'No',
                    'Yes'
                )
                )
                ) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid value, valid values: %s'), '"Yes", "No"'
                    );

                    return;
                }
                if ($this->data['Supplier Part On Demand'] == 'No' and $value == 'Yes') {
                    $this->error = true;
                    $this->msg   = _("Supplier part must be on demand");

                    return;
                }
                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                if ($this->updated) {
                    $this->part->update_fresh();
                }
                break;
            case 'Supplier Part Minimum Carton Order':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Minimum missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid minimum order (%s)'), $value
                    );

                    return;
                }

                $this->update_field($field, $value, $options);

                break;
            case 'Supplier Part Unit Label':


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

            case 'Supplier Part Unit Description':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit description missing');

                    return;
                }

                $this->part->update(
                    array('Part Unit Description' => $value), $options
                );
                $this->updated = $this->part->updated;


                break;
            case 'Supplier Part Package Description':
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
            case 'Supplier Part Status':


                if (!in_array(
                    $value, array(
                              'Available',
                              'NoAvailable',
                              'Discontinued'
                          )
                )
                ) {
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
            case 'Supplier Part Average Delivery Days':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid delivery time (%s)'), $value
                    );

                    return;
                }

                $this->update_field($field, $value, $options);
                $this->update_metadata = array(
                    'class_html' => array(
                        'Average_Delivery' => $this->get('Average Delivery')
                    )
                );

                break;

            case 'Supplier Code':

            case 'Supplier Part Supplier Code':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Supplier's code missing");

                    return;
                }

            include_once 'class.Supplier.php';


                $supplier = new Supplier('code', $value);
                if (!$supplier->id) {
                    $this->error = true;
                    $this->msg   = sprintf(_("Supplier %s not found"), $value);

                    return;
                }
                $this->update_field_switcher(
                    'Supplier Part Supplier Key', $supplier->id, $options
                );


                break;

            case 'Supplier Part Supplier Key':
                include_once 'class.Supplier.php';

                if ($value == $this->get('Supplier Part Supplier Key')) {

                    return;
                }

                $supplier = new Supplier($value);
                if (!$supplier->id) {
                    $this->error = true;
                    $this->msg   = _("Supplier not found");

                    return;
                }


                $old_supplier = new Supplier(
                    $this->get('Supplier Part Supplier Key')
                );

                if (!$supplier->id) {
                    $this->error = true;
                    $this->msg   = 'Supplier not found';

                    return;

                }


                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Supplier Part Dimension` WHERE `Supplier Part Reference`=%s AND `Supplier Part Supplier Key`=%d AND `Supplier Part Key`!=%d ',
                    prepare_mysql($this->get('Supplier Part Reference')), $supplier->id, $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _(
                                    "Can't move supplier, another supplier part has same reference (%s)"
                                ), $this->get('Supplier Part Reference')
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $this->update_field($field, $supplier->id, 'no_history');
                $updated = $this->updated;


                $this->update_field(
                    'Supplier Part Currency Code', $supplier->get('Supplier Default Currency Code'), 'no_history'
                );


                if($supplier->get('Supplier Default Currency Code')!=$old_supplier->get('Supplier Default Currency Code')){

                    include_once 'utils/currency_functions.php';

                    $exchange       = currency_conversion($this->db, $old_supplier->get('Supplier Default Currency Code'),$supplier->get('Supplier Default Currency Code'), '- 1 day');

                    $this->update_field_switcher(
                        'Supplier Part Unit Cost', $exchange*$this->get('Supplier Part Unit Cost'), 'no_history'
                    );
                }


                $supplier->update_supplier_parts();
                //$supplier->update_up_today_sales();
                //$supplier->update_last_period_sales();
                //$supplier->update_interval_sales();
                //$supplier->update_previous_years_data();


                if ($old_supplier->id) {
                    $old_supplier->update_supplier_parts();
                    //$old_supplier->update_up_today_sales();
                    //$old_supplier->update_last_period_sales();
                    //$old_supplier->update_interval_sales();
                    //$old_supplier->update_previous_years_data();
                    $old_supplier_label = sprintf(
                        ' from <span class="discreet button" onClick="change_view(\'supplier/%d\')">%s</span>', $old_supplier->id, $old_supplier->get('Code')
                    );

                } else {
                    $old_supplier_label = '';
                }


                $this->update_metadata = array(
                    'request' => sprintf(
                        'supplier/%d/part/%d', $supplier->id, $this->id
                    )
                );

                $history_data = array(
                    'Action'           => 'edited',
                    'History Abstract' => sprintf(
                        "Suppier's part %s supplier moved to supplier %s%s", sprintf(
                        '<span class="button" onClick="change_view(\'supplier/%d/part/%d\')">%s</span>', $supplier->id, $this->id, $this->get('Reference')
                    ), sprintf(
                            '<span class="button" onClick="change_view(\'supplier/%d\')">%s</span>', $supplier->id, $supplier->get('Code')
                        ), $old_supplier_label

                    ),
                    'History Details'  => ''
                );
                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id()
                );


                $this->updated = $updated;
                break;

            case 'Supplier Part Unit Cost':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Cost missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid cost (%s)'), $value);

                    return;
                }

                $this->update_field($field, $value, $options);




            if($this->data['Supplier Part Unit Extra Cost Percentage']!=''){

                $this->update_field('Supplier Part Unit Extra Cost', $value*$this->data['Supplier Part Unit Extra Cost Percentage'], 'no_history');


            }



                $updated = $this->updated;

                if (!preg_match('/skip_update_historic_object/', $options)) {
                    $this->update_historic_object();
                }
                $this->part->update_cost();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'      => $this->get('Carton Cost'),
                        'SKO_Cost'         => $this->get('SKO Cost'),
                        'Unit_Cost_Amount' => $this->get('Unit Cost Amount'),

                    )
                );

                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'formatted_value' => $this->get('Part Unit Price'),
                        'value'           => $this->get('Part Part Unit Price'),
                    ),


                );


                $this->updated = $updated;
                break;
            case 'Supplier Part Currency Code':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = sprintf(_('Currency code missing'));

                    return;
                }

                $sql = sprintf(
                    'SELECT count(*) AS num FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s AND `Currency Status`="Active" ', prepare_mysql($value)
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] == 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Currency code not found (%s)'), $value
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $this->update_field($field, $value, $options);

                $updated = $this->updated;

                if (!preg_match('/skip_update_historic_object/', $options)) {
                    $this->update_historic_object();
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'      => $this->get('Carton Cost'),
                        'SKO_Cost'         => $this->get('SKO Cost'),
                        'Unit_Cost_Amount' => $this->get('Unit Cost Amount'),

                    )
                );
                $this->updated         = $updated;
                break;
            case 'Supplier Part Reference':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = sprintf(_('Reference missing'));

                    return;
                }

                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Supplier Part Dimension` WHERE `Supplier Part Reference`=%s AND `Supplier Part Supplier Key`=%d AND `Supplier Part Key`!=%d ', prepare_mysql($value),
                    $this->get('Supplier Part Supplier Key'), $this->id
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
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $this->update_field($field, $value, $options);

                $updated = $this->updated;

                if (!preg_match('/skip_update_historic_object/', $options)) {
                    $this->update_historic_object();
                }


                $this->updated = $updated;
                break;

            case 'Supplier Part Carton CBM':


                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid carton CBM (%s)'), $value
                    );

                    return;
                }


                $this->update_field($field, $value, $options);
                if (!preg_match('/skip_update_historic_object/', $options)) {
                    $this->update_historic_object();
                }

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
                    'Supplier_Part_Unit_Cost'           => array(
                        'field'           => 'Supplier_Part_Unit_Cost',
                        'render'          => true,
                        'value'           => $this->get(
                            'Supplier Part Unit Cost'
                        ),
                        'formatted_value' => $this->get('Unit Cost'),
                    ),
                    'Supplier_Part_Packages_Per_Carton' => array(
                        'field'           => 'Supplier_Part_Packages_Per_Carton',
                        'render'          => true,
                        'value'           => $this->get(
                            'Supplier Part Packages Per Carton'
                        ),
                        'formatted_value' => $this->get('Packages Per Carton'),
                    )
                );


                $this->update_field($field, $value, $options);
                if (!preg_match('/skip_update_historic_object/', $options)) {
                    $this->update_historic_object();
                }


                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Weight'                   => $this->get(
                            'Carton Weight'
                        ),
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
                            'Supplier Part Packages Per Carton'
                        ),
                        'Supplier_Part_Units_Per_Package' => $this->get(
                            'Part Units Per Package'
                        ),
                        'Supplier_Part_Units_Per_Carton'  => $this->get(
                            'Units Per Carton'
                        )


                    )
                );

                break;
            case 'Supplier Part Packages Per Carton':

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


                $this->update_field($field, $value, $options);


                $this->other_fields_updated = array(
                    'Supplier_Part_Unit_Cost' => array(
                        'field'           => 'Supplier_Part_Unit_Cost',
                        'render'          => true,
                        'value'           => $this->get(
                            'Supplier Part Unit Cost'
                        ),
                        'formatted_value' => $this->get('Unit Cost'),
                    ),

                );


                if (!preg_match('/skip_update_historic_object/', $options)) {
                    $this->update_historic_object();
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Weight'                     => $this->get(
                            'Carton Weight'
                        ),
                        'Carton_Cost'                       => $this->get(
                            'Carton Cost'
                        ),
                        'Supplier_Part_Packages_Per_Carton' => $this->get(
                            'Supplier Part Packages Per Carton'
                        ),
                        'Supplier_Part_Units_Per_Carton'    => $this->get(
                            'Units Per Carton'
                        )


                    )
                );

                break;


            case 'Supplier Part Unit Extra Cost Percentage':

                if ($value == '') {
                    $value = 0;
                }



                if (preg_match('/\%$/', $value)) {
                    $value = preg_replace('/\%^/', '', $value)/ 100;
                   // $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                }


                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid percentage (%s)'), $value
                    );

                    return;
                }

                $this->update_field($field, $value, $options);

                $this->update_field('Supplier Part Unit Extra Cost', $this->data['Supplier Part Unit Cost']*$value, 'no_history');



                $this->part->update_cost();




                $this->update_metadata = array(
                    'class_html' => array(
                        'Carton_Cost'      => $this->get('Carton Cost'),
                        'SKO_Cost'         => $this->get('SKO Cost'),
                        'Unit_Cost_Amount' => $this->get('Unit Cost Amount'),

                    )
                );

                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'formatted_value' => $this->get('Part Unit Price'),
                        'value'           => $this->get('Part Part Unit Price'),
                    ),


                );



                break;


            case 'Supplier Part Unit Extra Cost':

                if ($value == '') {
                    $value = 0;
                }

                if (preg_match('/\%$/', $value)) {
                    $value = preg_replace('/\%^/', '', $value);
                   $value = $this->data['Supplier Part Unit Cost'] * $value / 100;
                }


                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid extra cost (%s)'), $value
                    );

                    return;
                }

                $this->update_field($field, $value, $options);
                $this->part->update_cost();
                break;
            default:


                if (preg_match('/^Part /', $field)) {

                    //$field=preg_replace('/^Part /', '', $field);

                    //print "xxx $field  ".$value." \n  ";
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

    function get_supplier_data() {

        $sql = sprintf(
            'SELECT * FROM `Supplier Dimension` WHERE `Supplier Key`=%d ', $this->get('Supplier Part Supplier Key')
        );
        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }


    }

    function get($key, $data = false) {

        global $account;


        if (!$this->id) {
            return '';
        }


        switch ($key) {


            case 'Supplier Part Units Per Package':
            case 'Supplier Part Unit Description':
            case 'Supplier Part Unit Label':
            case 'Supplier Part Package Description':
                $key = preg_replace('/Supplier /', '', $key);

                return $this->part->get($key);
                break;

            case 'Unit Description':
            case 'Unit Label':
            case 'Package Description':


                return $this->part->get($key);
                break;


            case 'Supplier Key':
                return $this->get('Supplier Name').(($this->get('Supplier Code') != '' and $this->get('Supplier Code') != $this->get(
                            'Supplier Name'
                        )) ? ' ('.$this->get('Supplier Code').')' : '');
                break;
            case 'Average Delivery Days':
                if ($this->data['Supplier Part Average Delivery Days'] == '') {
                    return '';
                }

                return sprintf(
                    "%d %s", $this->data['Supplier Part Average Delivery Days'], ngettext(
                               "day", "days", $this->data['Supplier Part Average Delivery Days']
                           )
                );

                break;

            case 'SKO Barcode':

                return $this->part->get('Barcode');

            case 'Average Delivery':


                if ($this->data['Supplier Part Status'] == 'Available') {
                    if ($this->data['Supplier Part Average Delivery Days'] != '') {
                        return '<span class="discreet"><i class="fa fa-hourglass-end fa-fw" aria-hidden="true" title="'._('Delivery time').'" ></i>  <span title="'.sprintf(
                                "%s %s", number(
                                $this->data['Supplier Part Average Delivery Days'], 1
                            ), ngettext(
                                    "day", "days", number(
                                             $this->data['Supplier Part Average Delivery Days'], 1
                                         )
                                )
                            ).'">'.seconds_to_natural_string(
                                $this->data['Supplier Part Average Delivery Days'] * 86400, true
                            ).'</span></span>';
                    } else {
                        return '<span class="error">'._('Unknown delivery time').'</span>';
                    }

                } elseif ($this->data['Supplier Part Status'] == 'NoAvailable') {
                    return '<span class="discreet error">'._(
                            'Supplier has not stock'
                        ).'</span>';
                }
                break;

            case 'Minimum Carton Order':

                if ($this->data['Supplier Part Minimum Carton Order'] == '') {
                    return '';
                }

                return number($this->data['Supplier Part Minimum Carton Order']).' '._('cartons');
                break;

            case 'Carton CBM':
                if ($this->data['Supplier Part Carton CBM'] == '') {
                    return '';
                }

                return number($this->data['Supplier Part Carton CBM'], 4).' m³';
                break;
            case 'SKO Dimensions':
                return $this->part->get('Package Dimensions');
                break;
            case 'Unit Dimensions':
                return $this->part->get('Unit Dimensions');
                break;

            case 'SKO Barcode':


                break;
            case 'Carton Weight':

                return weight(
                    $this->part->data['Part Package Weight'] * $this->data['Supplier Part Packages Per Carton']
                );
                break;
            case 'SKO Weight':
                return $this->part->get('Package Weight');
                break;
            case 'Unit Weight':
                return $this->part->get('Unit Weight');
                break;
            case 'Carton Cost':
                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }

                return money(
                    $this->data['Supplier Part Unit Cost'] * $this->part->data['Part Units Per Package'] * $this->data['Supplier Part Packages Per Carton'], $this->data['Supplier Part Currency Code']
                );
                break;

            case 'Carton Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }

                if ($this->data['Supplier Part Unit Extra Cost'] == '') {
                    $extra_cost = 0;
                } else {
                    $extra_cost = $this->data['Supplier Part Unit Extra Cost'];
                }
                $exchange       = currency_conversion($this->db, $this->data['Supplier Part Currency Code'], $account->get('Account Currency'), '- 1 day');
                $delivered_cost = $exchange * $this->part->data['Part Units Per Package'] * $this->data['Supplier Part Packages Per Carton'] * ($this->data['Supplier Part Unit Cost'] + $extra_cost);

                $cost = money(
                    $delivered_cost, $account->get('Account Currency')
                );

                return $cost;
                break;


            case 'SKO Cost':
                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }

                return money(
                    $this->data['Supplier Part Unit Cost'] * $this->part->data['Part Units Per Package'], $this->data['Supplier Part Currency Code']
                );
                break;

            case 'Carton Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }

                if ($this->data['Supplier Part Unit Extra Cost'] == '') {
                    $extra_cost = 0;
                } else {
                    $extra_cost = $this->data['Supplier Part Unit Extra Cost'];
                }
                $exchange       = currency_conversion(
                    $this->db, $this->data['Supplier Part Currency Code'], $account->get('Account Currency'), '- 1 day'
                );
                $delivered_cost = $exchange * $this->part->data['Part Units Per Package'] * ($this->data['Supplier Part Unit Cost'] + $extra_cost);

                $cost = money(
                    $delivered_cost, $account->get('Account Currency')
                );

                return $cost;
                break;
            case 'Supplier Part Units Per Carton':
                if ($this->data['Supplier Part Packages Per Carton'] == '' or $this->part->data['Part Units Per Package'] == '') {
                    return '';
                }

                return $this->data['Supplier Part Packages Per Carton'] * $this->part->data['Part Units Per Package'];
                break;
            case 'Units Per Carton':
                if ($this->data['Supplier Part Packages Per Carton'] == '' or $this->part->data['Part Units Per Package'] == '') {
                    return '';
                }

                $units_per_carton = number($this->data['Supplier Part Packages Per Carton'] * $this->part->data['Part Units Per Package']);

                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric($this->part->data['Part Units Per Package'])) {

                    $units_per_carton .= '<span class="discreet"> ('.number($this->data['Supplier Part Packages Per Carton']).' '.ngettext(
                            'pack', 'packs', $this->data['Supplier Part Packages Per Carton']
                        ).')</span>';
                }

                return $units_per_carton;

                break;

            case 'Packages Per Carton':
                if ($this->data['Supplier Part Packages Per Carton'] == '') {
                    return '';
                }
                $value = number(
                    $this->data['Supplier Part Packages Per Carton']
                );




                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric($this->part->data['Part Units Per Package'])) {
                    $value .= ' <span class="very_discreet">('.number(
                            $this->data['Supplier Part Packages Per Carton'] * $this->part->data['Part Units Per Package']
                        ).' '._('units').')</span>';
                }

                return $value;
                break;

            case 'Unit Cost Amount':

                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }
                $cost = money(
                    $this->data['Supplier Part Unit Cost'], $this->data['Supplier Part Currency Code']
                );

                return $cost;
                break;


            case 'Unit Cost':


                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }

                $cost = money(
                    $this->data['Supplier Part Unit Cost'], $this->data['Supplier Part Currency Code']
                );


                $cost_other_info = '';
                if ($this->part->data['Part Units Per Package'] != 1 and is_numeric($this->part->data['Part Units Per Package'])) {
                    $cost_other_info = money(
                            $this->data['Supplier Part Unit Cost'] * $this->part->data['Part Units Per Package'], $this->data['Supplier Part Currency Code']
                        ).' '._('per SKO');
                }
                if ($this->data['Supplier Part Packages Per Carton'] != 1 and is_numeric(
                        $this->data['Supplier Part Packages Per Carton']
                    )
                ) {
                    $cost_other_info .= ', '.money(
                            $this->data['Supplier Part Unit Cost'] * $this->part->data['Part Units Per Package'] * $this->data['Supplier Part Packages Per Carton'],
                            $this->data['Supplier Part Currency Code']
                        ).' '._('per carton');
                }

                $cost_other_info = preg_replace('/^, /', '', $cost_other_info);
                if ($cost_other_info != '') {
                    $cost .= ' <span class="discreet">('.$cost_other_info.')</span>';
                }


                return $cost;
                break;

            case 'Unit Delivered Cost':

                include_once 'utils/currency_functions.php';

                if ($this->data['Supplier Part Unit Cost'] == '') {
                    return '';
                }

                if ($this->data['Supplier Part Unit Extra Cost'] == '') {
                    $extra_cost = 0;
                } else {
                    $extra_cost = $this->data['Supplier Part Unit Extra Cost'];
                }
                $exchange       = currency_conversion(
                    $this->db, $this->data['Supplier Part Currency Code'], $account->get('Account Currency'), '- 1 day'
                );
                $delivered_cost = $exchange * ($this->data['Supplier Part Unit Cost'] + $extra_cost);

                $cost = money(
                    $delivered_cost, $account->get('Account Currency')
                );

                return $cost;
                break;

            case 'Supplier Part Unit Extra Cost Percentage':
                if ($this->data['Supplier Part Unit Extra Cost Percentage'] == '') {
                    return '';
                }




                return percentage($this->data['Supplier Part Unit Extra Cost Percentage'],1);





            case 'Unit Extra Cost Percentage':
                if ($this->data['Supplier Part Unit Extra Cost Percentage'] == '') {
                    return '';
                }




                    $extra_cost = ' <span class="">'.percentage($this->data['Supplier Part Unit Extra Cost Percentage'],1).'</span>';


                $extra_cost .= ' <span class="discreet">'.money($this->data['Supplier Part Unit Extra Cost'], $this->data['Supplier Part Currency Code']).'</span>';


                return $extra_cost;

            case 'Unit Extra Cost':
                if ($this->data['Supplier Part Unit Extra Cost'] == '') {
                    return '';
                }

                $extra_cost = money(
                    $this->data['Supplier Part Unit Extra Cost'], $this->data['Supplier Part Currency Code']
                );

                if ($this->data['Supplier Part Unit Cost'] > 0) {
                    $extra_cost .= ' <span class="discreet">'.percentage(
                            $this->data['Supplier Part Unit Extra Cost'], $this->data['Supplier Part Unit Cost']
                        ).'</span>';
                }

                return $extra_cost;

            case 'Status':

                switch ($this->data['Supplier Part Status']) {
                    case 'Available':
                        $status = sprintf(
                            '<i class="fa fa-stop success" ></i> %s', _('Available')
                        );
                        break;
                    case 'NoAvailable':
                        $status = sprintf(
                            '<i class="fa fa-stop warning" ></i> %s', _('No available')
                        );

                        break;
                    case 'Discontinued':
                        $status = sprintf(
                            '<i class="fa fa-ban error" ></i> %s', _('Discontinued')
                        );

                        break;
                    default:
                        $status = $this->data['Supplier Part Status'];
                        break;
                }

                return $status;
                break;
            case 'On Demand':
            case 'Fresh':

                switch ($this->data['Supplier Part '.$key]) {
                    case 'Yes':
                        return _('Yes');
                        break;
                    case 'No':
                        return _('No');
                        break;
                    default:
                        return $this->data['Supplier Part '.$key];
                        break;
                }
                break;
            default:
                if (preg_match('/^Part /', $key)) {

                    return $this->part->get(preg_replace('/^Part /', '', $key));

                }

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Supplier Part '.$key, $this->data)) {
                    return $this->data['Supplier Part '.$key];
                }
        }

        return '';
    }

    function update_historic_object() {

        if (!$this->id) {
            return;
        }

        $old_value = $this->get('Supplier Part Historic Key');
        $changed   = false;

        $sql = sprintf(
            'SELECT `Supplier Part Historic Key` FROM `Supplier Part Historic Dimension` WHERE
		`Supplier Part Historic Supplier Part Key`=%d AND `Supplier Part Historic Reference`=%s AND `Supplier Part Historic Unit Cost`=%f AND
		`Supplier Part Historic Units Per Package`=%d AND `Supplier Part Historic Packages Per Carton`=%d  AND `Supplier Part Historic Carton CBM`=%f AND `Supplier Part Historic Currency Code`=%s',
            $this->id, prepare_mysql($this->data['Supplier Part Reference']), $this->data['Supplier Part Unit Cost'], $this->part->data['Part Units Per Package'],
            $this->data['Supplier Part Packages Per Carton'], $this->data['Supplier Part Carton CBM'], prepare_mysql($this->data['Supplier Part Currency Code'])
        );

        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $this->update(
                    array('Supplier Part Historic Key' => $row['Supplier Part Historic Key']), 'no_history'
                );
                $changed = true;

            } else {
                $sql = sprintf(
                    'INSERT INTO `Supplier Part Historic Dimension` (`Supplier Part Historic Supplier Part Key`,`Supplier Part Historic Reference`,`Supplier Part Historic Unit Cost`,
						`Supplier Part Historic Units Per Package`, `Supplier Part Historic Packages Per Carton`,`Supplier Part Historic Carton CBM`, `Supplier Part Historic Currency Code`

				) VALUES (%d,%s,%f,%d,%d,%f,%s) ', $this->id, prepare_mysql($this->data['Supplier Part Reference']), $this->data['Supplier Part Unit Cost'],
                    $this->part->data['Part Units Per Package'], $this->data['Supplier Part Packages Per Carton'], $this->data['Supplier Part Carton CBM'],
                    prepare_mysql($this->data['Supplier Part Currency Code'])
                );
                //print "$sql\n";
                if ($this->db->exec($sql)) {
                    $this->update(
                        array(
                            'Supplier Part Historic Key' => $this->db->lastInsertId()
                        ), 'no_history'
                    );
                    $changed = true;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print $sql;
            exit;
        }


        if ($changed) {

            $purchase_order_keys = array();
            $sql                 = sprintf(
                "SELECT `Purchase Order Transaction Fact Key`,PO.`Purchase Order Key`,`Purchase Order Quantity` FROM `Purchase Order Transaction Fact` POTF  LEFT JOIN `Purchase Order Dimension` PO ON (POTF.`Purchase Order Key`=PO.`Purchase Order Key`) WHERE `Supplier Part Key`=%d  AND `Purchase Order Locked`='No'  ",
                $this->id
            );
            //print $sql;
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $purchase_order_keys[$row['Purchase Order Key']] = $row['Purchase Order Key'];

                    $units_per_carton = $this->part->get(
                            'Part Units Per Package'
                        ) * $this->get('Supplier Part Packages Per Carton');

                    $sql = sprintf(
                        'UPDATE `Purchase Order Transaction Fact` SET
						  `Supplier Part Historic Key`=%d,
						 `Purchase Order CBM`=%f,
						 `Purchase Order Weight`=%f,
						 `Purchase Order Net Amount`=%.2f
						  WHERE `Purchase Order Transaction Fact Key`=%d', $this->get('Supplier Part Historic Key'), $row['Purchase Order Quantity'] * $this->get(
                                                                                                     'Supplier Part Carton CBM'
                                                                                                 ), $row['Purchase Order Quantity'] * $this->get(
                                                                                                     'Supplier Part Packages Per Carton'
                                                                                                 ) * $this->get('Part Package Weight'),
                        $row['Purchase Order Quantity'] * $units_per_carton * $this->get('Supplier Part Unit Cost'),

                        $row['Purchase Order Transaction Fact Key']
                    );

                    $this->db->exec($sql);
                }
                include_once 'class.PurchaseOrder.php';
                foreach ($purchase_order_keys as $purchase_order_key) {
                    $purchase_order = new PurchaseOrder($purchase_order_key);
                    $purchase_order->update_totals();
                }

            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
        }


    }


    function delete($metadata = false) {


        $sql = sprintf(
            'INSERT INTO `Supplier Part Deleted Dimension`  (`Supplier Part Deleted Key`,`Supplier Part Deleted Reference`,`Supplier Part Deleted From`,`Supplier Part Deleted To`,`Supplier Part Metadata`) VALUES (%d,%s,%s,%s,%s) ',
            $this->id, prepare_mysql($this->get('Supplier Part Reference')), prepare_mysql($this->get('Supplier Part From')), prepare_mysql(gmdate('Y-m-d H:i:s')),
            prepare_mysql(gzcompress(json_encode($this->data), 9))

        );

        //print "$sql\n";

        $this->db->exec($sql);


        $sql = sprintf(
            'DELETE FROM `Supplier Part Dimension`  WHERE `Supplier Part Key`=%d ', $this->id
        );
        $this->db->exec($sql);
        //print "$sql\n";

        $history_data = array(
            'History Abstract' => sprintf(
                _("Supplier's part record %s deleted"), $this->data['Supplier Part Reference']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id()
        );


        if (count($this->part->get_supplier_parts()) == 0) {
            $this->part->delete();
        }


        $this->deleted = true;
    }


    function get_field_label($field) {
        global $account;

        switch ($field) {

            case 'Supplier Part Reference':
                $label = _("supplier's SKU");
                break;
            case 'Supplier Part Cost':
                $label = _('unit cost');
                break;
            case 'Supplier Part Batch':
                $label = _('batch');
                break;
            case 'Supplier Part Status':
                $label = _('availability');
                break;
            case 'Supplier Part Minimum Carton Order':
                $label = _("Minimum order (cartons)");
                break;

            case 'Supplier Part Packages Per Carton':
                $label = _("SKOs per carton");
                break;
            case 'Supplier Part Carton CBM':
                $label = _("carton CBM");
                break;
            case 'Supplier Part Average Delivery Days':
                $label = _("Average delivery time (days)");
                break;
            case 'Supplier Part Unit Cost':
                $label = _("unit cost");
                break;
            case 'Supplier Part Unit Extra Cost':
                $label = _("unit extra costs");
                break;
            case 'Supplier Part Unit Extra Cost Percentage':
             $label = _("percentage extra costs");
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
            case 'Part Unit Description':
                $label = _("unit description");
                break;
            case 'Part Unit Label':
                $label = _("unit label");
                break;
            case 'Part Units Per Package':
                $label = _("units per SKO");
                break;
            case 'Supplier Part On Demand':
                $label = _('On demand');
                break;
            case 'Supplier Part Fresh':
                $label = _('Make to order');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


    function update_next_supplier_shippment() {

    }


}


?>
