<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 January 2019 at 14:06:33 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/


include_once 'class.SupplierPart.php';

class ProductionPart extends SupplierPart {


    function get_data($key, $tag) {

        $this->table_name = 'Production Part';


        if ($key == 'id') {
            $sql = sprintf("SELECT * FROM `Supplier Part Dimension` WHERE `Supplier Part Key`=%d", $tag);
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Supplier Part Key'];

            $this->part = get_object('Part', $this->data['Supplier Part Part SKU'], false, $this->db);
        }


        $sql = sprintf("SELECT * FROM `Production Part Dimension`  WHERE `Production Part Supplier Part Key`=%d", $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


    function get($key, $data = false) {

        switch ($key) {

            case 'Raw Materials':

                $account = get_object('Account', 1);
                $account->load_acc_data();

                $raw_materials = '';


                $raw_materials_data = $this->get_raw_materials_data(true);


                foreach ($raw_materials_data as $raw_material_data) {

                    $raw_materials .= '<br/><span>'.number($raw_material_data['Ratio'], 5).' '.$raw_material_data['Unit Label'].'   <span class="button padding_left_20" onClick="change_view(\'production/'.$account->properties('production_supplier_key').'/raw_materials/'
                        .$raw_material_data['Raw Material Key'].'\')">'.$raw_material_data['Code'].'</span></span>';


                }

                if ($raw_materials == '') {
                    $raw_materials = '<span class="discreet">'._('No parts assigned').'</span>';
                }
                $raw_materials = preg_replace('/^\<br\/\>/', '', $raw_materials);

                return $raw_materials;

            default;

                if (array_key_exists($key, $this->base_data())) {
                    return $this->data[$key];
                }

                if (array_key_exists('Production Part '.$key, $this->base_data())) {
                    return $this->data['Production Part '.$key];
                }


                $supplier_part = get_object('SupplierPart', $this->id);

                return $supplier_part->get($key, $data);

        }
    }

    function update_bill_of_materials($data) {
        $part = get_object('Part', $data['item_key']);


        if ($part->sku == $this->get('Supplier Part Part SKU')) {
            $this->error = true;
            $this->msg   = _("You can't add same part as a material");

            return false;
        }

        if ($data['field'] == 'Units') {
            $qty = $data['qty'] / $part->get('Part Units Per Package');

        } else {
            $qty = $data['qty'];

        }

        $sql = 'insert into  `Bill of Materials Bridge` (`Bill of Materials Supplier Part Key`,`Bill of Materials Supplier Part Component Key`,`Bill of Materials Quantity`) values (?,?,?) ON DUPLICATE KEY UPDATE `Bill of Materials Quantity`=?';


        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            print_r($this->db->errorInfo());
        }


        if (!$stmt->execute(
            [
                $this->id,
                $part->id,
                $qty,
                $qty
            ]
        )) {
            print_r($stmt->errorInfo());
        }

        $this->update_number_components();
        $part->update_production_supply_data();
        $this->update_available_to_make_up();


        return true;
    }

    function update_number_components() {

        $number_components = 0;

        $sql = "select count(*) as num from `Bill of Materials Bridge` where  `Bill of Materials Supplier Part Key`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
            )
        );
        if ($row = $stmt->fetch()) {
            $number_components = $row['num'];
        }


        $this->fast_update(array('Production Part Components Number' => $number_components), 'Production Part Dimension');


    }

    function update_available_to_make_up() {


        $available_to_make_up = '';
        $counter              = 0;

        $sql  = 'select `Part Current On Hand Stock`,`Bill of Materials Quantity`   from   `Bill of Materials Bridge`   left join `Part Dimension` P on (P.`Part SKU`=`Bill of Materials Supplier Part Component Key`) where  `Bill of Materials Supplier Part Key`=?';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id


            )
        )) {
            while ($row = $stmt->fetch()) {


                if (is_numeric($row['Part Current On Hand Stock']) and $row['Part Current On Hand Stock'] >= 0 and $row['Bill of Materials Quantity'] > 0) {
                    $stock_ok = true;
                } else {
                    $stock_ok = false;
                }


                if ($stock_ok) {
                    if ($counter == 0) {
                        $available_to_make_up = $row['Part Current On Hand Stock'] / $row['Bill of Materials Quantity'];
                    } else {
                        if ($row['can_make'] < $available_to_make_up) {
                            $available_to_make_up = $row['can_make'];

                        }

                    }


                    $counter++;
                } else {
                    $available_to_make_up = '';
                    break;
                }


            }
        } else {
            print $sql;
            print_r($error_info = $this->db->errorInfo());
            exit();
        }


        $this->fast_update(array('Production Part Available to Make up' => $available_to_make_up), 'Production Part Dimension');


        return $available_to_make_up;

    }


    //todo
    function update_production_supply_data() {


        $number_of_parts_using_part = 0;

        $sql  = 'select count(*) as num from `Bill of Materials Bridge` where  `Bill of Materials Supplier Part Component Key`=? ';
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id,
            )
        )) {
            if ($row = $stmt->fetch()) {
                $number_of_parts_using_part = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }

        $this->fast_update(
            array(
                'Part Number Production Links' => $number_of_parts_using_part,
            )
        );


    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Raw Materials':

                $this->update_raw_materials($value, $options);


                break;
            case 'Supplier Part Packages Per Carton':
                $supplier_part         = get_object('SupplierPart', $this->id);
                $supplier_part->editor = $this->editor;
                $supplier_part->update_field_switcher($field, $value, $options, $metadata);
                if ($supplier_part->updated) {
                    $this->updated;
                }
                $this->get_data('id', $this->id);


                if ($value == 1) {
                    $this->other_fields_updated = array(
                        'Supplier_Part_Carton_CBM'     => array(
                            'field'  => 'Supplier_Part_Carton_CBM',
                            'render' => false,
                        ),
                        'Supplier_Part_Carton_Weight'  => array(
                            'field'  => 'Supplier_Part_Carton_Weight',
                            'render' => false,
                        ),
                        'Supplier_Part_Carton_Barcode' => array(
                            'field'  => 'Supplier_Part_Carton_Barcode',
                            'render' => false,
                        )
                    );

                    $this->update_metadata = array(
                        'class_html' => array(
                            'carton_info' => '',
                        )

                    );


                } else {
                    $this->other_fields_updated = array(
                        'Supplier_Part_Carton_CBM'     => array(
                            'field'  => 'Supplier_Part_Carton_CBM',
                            'render' => true,


                        ),
                        'Supplier_Part_Carton_Weight'  => array(
                            'field'  => 'Supplier_Part_Carton_Weight',
                            'render' => true,
                        ),
                        'Supplier_Part_Carton_Barcode' => array(
                            'field'  => 'Supplier_Part_Carton_Barcode',
                            'render' => true,
                        )
                    );

                    $this->update_metadata = array(
                        'class_html' => array(
                            'carton_info' => _('Packs per carton').": ".$this->get('Packages Per Carton'),
                        )

                    );
                }


                break;
            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {

                    if ($value != $this->data[$field]) {


                        $this->update_field($field, $value, $options);


                    }
                }


        }

    }


    function get_field_label($field) {


        switch ($field) {

            case 'Production Part Batch Size':
                $label = _('Units per batch');
                break;
            case 'Supplier Part Packages Per Carton':
                $label = _('SKOs per carton');
                break;
            case 'Supplier Part Minimum Carton Order':
                $label = _("Minimum order (cartons)");
                break;
            case 'Supplier Part Packages Per Carton':
                $label = _("Packed units per carton");
                break;
            case 'Supplier Part Carton CBM':
                $label = _("carton CBM");
                break;
            case 'Supplier Part Carton Weight':
                $label = _('carton gross weight');
                break;
            case 'Production Part Raw Materials':
                $label = _('raw materials');
                break;


            default:
                $label = $field;

        }

        return $label;

    }

    function get_raw_materials_data($with_objects = false) {

        $raw_materials_data = array();

        $sql = "SELECT `Raw Material Unit Label`,`Production Part Raw Material Raw Material Key`,`Raw Material Part Raw Material Unit Ratio`,`Raw Material Code`,`Production Part Raw Material Key`,`Raw Material Key`,`Production Part Raw Material Ratio`,`Production Part Raw Material Note` ,`Raw Material Description`,`Raw Material Unit`
        FROM `Production Part Raw Material Bridge` LEFT JOIN `Raw Material Dimension` ON (`Raw Material Key`=`Production Part Raw Material Raw Material Key`)  WHERE `Production Part Raw Material Production Part Key`=? ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $raw_material_data = array(
                'Key'              => $row['Production Part Raw Material Key'],
                'Ratio'            => $row['Production Part Raw Material Ratio'],
                'Note'             => $row['Production Part Raw Material Note'],
                'Raw Material Key' => $row['Production Part Raw Material Raw Material Key'],
                'Code'             => $row['Raw Material Code'],
                'Name'             => $row['Raw Material Description'],
                'Unit Type'        => $row['Raw Material Unit'],
                'Unit Label'       => $row['Raw Material Unit Label'],
                'Part Unit Ratio'  => $row['Raw Material Part Raw Material Unit Ratio']

            );


            if ($with_objects) {
                $raw_material_data['Raw Material'] = get_object('RawMaterial', $row['Raw Material Key']);
            }


            $raw_materials_data[] = $raw_material_data;
        }


        return $raw_materials_data;
    }

    function update_raw_materials($value, $options = '') {


        $num_parts_edited = 0;

        $value              = json_decode($value, true);
        $raw_materials_list = $this->get_raw_materials_data();


        $old_raw_materials_list_keys = array();
        foreach ($raw_materials_list as $raw_material_data) {
            $old_raw_materials_list_keys[$raw_material_data['Key']] = $raw_material_data['Key'];
        }


        $new_raw_materials_list_keys = array();

        foreach ($value as $raw_material_data) {
            if (isset($raw_material_data['Key'])) {
                $new_raw_materials_list_keys[$raw_material_data['Key']] = $raw_material_data['Key'];
            }
        }



        foreach (array_diff($old_raw_materials_list_keys, $new_raw_materials_list_keys) as $production_part_raw_material_key) {
            $num_parts_edited++;
            $sql = "delete from `Production Part Raw Material Bridge` where `Production Part Raw Material Key`=? ";

            $this->db->prepare($sql)->execute(
                array(
                    $production_part_raw_material_key
                )
            );

        }


        foreach ($value as $raw_material_data) {


            if (isset($raw_material_data['Key']) and $raw_material_data['Key'] > 0) {

                $sql = sprintf(
                    'UPDATE `Production Part Raw Material Bridge` SET `Production Part Raw Material Note`=%s WHERE `Production Part Raw Material Key`=%d AND `Production Part Raw Material Production Part Key`=%d ', prepare_mysql($raw_material_data['Note']),
                    $raw_material_data['Key'], $this->id
                );

                $updt = $this->db->prepare($sql);
                $updt->execute();
                if ($updt->rowCount()) {
                    $num_parts_edited++;
                    $this->updated = true;
                }


                if ($raw_material_data['Ratio'] == 0) {
                    $sql = sprintf(
                        'DELETE FROM `Production Part Raw Material Bridge` WHERE `Production Part Raw Material Key`=%d AND `Production Part Raw Material Production Part Key`=%d ', $raw_material_data['Key'], $this->id
                    );

                    $updt = $this->db->prepare($sql);
                    $updt->execute();
                    if ($updt->rowCount()) {
                        $num_parts_edited++;
                        $this->updated = true;
                    }

                } else {

                    $sql = sprintf(
                        'UPDATE `Production Part Raw Material Bridge` SET `Production Part Raw Material Ratio`=%f WHERE `Production Part Raw Material Key`=%d AND `Production Part Raw Material Production Part Key`=%d ', $raw_material_data['Ratio'],
                        $raw_material_data['Key'], $this->id
                    );

                    $updt = $this->db->prepare($sql);
                    $updt->execute();
                    if ($updt->rowCount()) {
                        $num_parts_edited++;
                        $this->updated = true;
                    }
                }

                if ($this->updated) {
                    $sql  = sprintf(
                        'UPDATE `Production Part Raw Material Bridge` SET `Production Part Raw Material Updated`=%s WHERE `Production Part Raw Material Key`=%d AND `Production Part Raw Material Production Part Key`=%d ',
                        prepare_mysql(gmdate('Y-m-d H:i:s')), $raw_material_data['Key'],
                        $this->id
                    );


                    $updt = $this->db->prepare($sql);
                    $updt->execute();
                }

            } else {

                if ($raw_material_data['Production Part'] > 0) {

                    $sql = sprintf(
                        "INSERT INTO `Production Part Raw Material Bridge` 
                        (`Production Part Raw Material Updated`,`Production Part Raw Material Created`,`Production Part Raw Material Production Part Key`,`Production Part Raw Material Raw Material Key`,`Production Part Raw Material Ratio`,`Production Part Raw Material Note`) VALUES (%s,%s,%d,%d,%f,%s)",
                        prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(gmdate('Y-m-d H:i:s')), $this->id, $raw_material_data['Production Part'], $raw_material_data['Ratio'], prepare_mysql($raw_material_data['Note'], false)
                    );

                    $this->db->exec($sql);
                    $num_parts_edited++;
                    $this->updated = true;
                }
            }
        }


        $this->get_data('id', $this->id);


        $parts        = $this->get_raw_materials();
        $number_parts = count($parts);


        if ($num_parts_edited > 0) {

            if ($number_parts == 0) {
                $history_abstract = _("Raw materials deleted");
            } else {
                $history_abstract = sprintf(_("Raw materials changed to %s"), $this->get('Parts'));
            }

            $history_data = array(
                'History Abstract' => $history_abstract,
                'History Details'  => '',
                'Action'           => 'edit'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );
        }


    }


    function get_raw_materials($scope = 'keys') {


        $raw_materials = array();

        $sql = "SELECT `Production Part Raw Material Raw Material Key`  FROM `Production Part Raw Material Bridge` WHERE `Production Part Raw Material Production Part Key `=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($scope == 'objects') {
                $raw_materials[$row['Production Part Raw Material Raw Material Key']] = get_object('RawMaterial', $row['Production Part Raw Material Raw Material Key']);
            } else {
                $raw_materials[$row['Production Part Raw Material Raw Material Key']] = $row['Production Part Raw Material Raw Material Key'];
            }
        }


        return $raw_materials;
    }

}

