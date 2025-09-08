<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 19:32:50 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
include_once 'trait.ImageSubject.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.NotesSubject.php';
include_once 'utils/natural_language.php';


class Asset extends DB_Table {
    use ImageSubject, NotesSubject, AttachmentSubject;

    function update_asset_field_switcher($field, $value, $options = '', $metadata) {


        switch ($field) {


            case $this->table_name.' Barcode':


                $_old_value = $this->data[$this->table_name.' Barcode Number'];


                if ($value == '') {
                    /**
                     * @var $barcode \Barcode
                     */
                    $barcode         = get_object('Barcode', $this->get('Barcode Key'));
                    $barcode->editor = $this->editor;
                    if ($barcode->id) {
                        $asset_data = array(
                            'Barcode Asset Type' => $this->table_name,
                            'Barcode Asset Key'  => $this->id
                        );

                        $barcode->withdrawn_asset($asset_data);
                    }
                    $this->deleted_value = $this->get('Barcode Number');


                    $this->update_field_switcher($this->table_name.' Barcode Number', '', $options);
                    $this->update_field_switcher($this->table_name.' Barcode Key', '', 'no_history');


                } else {


                    $barcode = $value;
                    $error   = '';

                    if (strlen($barcode) != (12 + 1)) {
                        $error = 'Size';
                        if (strlen($barcode) == 12) {
                            $error = 'Checksum_missing';


                            $sql  = "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number` LIKE ? AND `Part SKU`!=?";
                            $stmt = $this->db->prepare($sql);
                            $stmt->execute(
                                array(
                                    $barcode.'%',
                                    $this->id
                                )
                            );
                            if ($stmt->fetch()) {
                                $error = 'Short_Duplicated';
                            }


                        }


                    } else {
                        $digits = substr($barcode, 0, 12);

                        $digits         = (string)$digits;
                        $even_sum       = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
                        $even_sum_three = $even_sum * 3;
                        $odd_sum        = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
                        $total_sum      = $even_sum_three + $odd_sum;
                        $next_ten       = (ceil($total_sum / 10)) * 10;
                        $check_digit    = $next_ten - $total_sum;

                        if ($check_digit != substr($barcode, -1)) {
                            $error = 'Checksum';
                        } else {
                            $sql = sprintf(
                                'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number`=%s AND `Part SKU`!=%d', prepare_mysql($barcode), $this->id
                            );

                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {
                                    $part = get_object('Part', $row['Part SKU']);
                                    $part->fast_update(array('Part Barcode Number Error' => 'Duplicated'));
                                    $error = 'Duplicated';
                                }

                            }


                        }


                    }

                    if ($error != '') {


                        switch ($error) {

                            case 'Duplicated':
                                $error_msg = _('Duplicated');
                                break;
                            case 'Size':
                                $error_msg = _('Barcode should be 13 digits');
                                break;
                            case 'Short_Duplicated':
                                $error_msg = _('Check digit missing, will duplicate');
                                break;
                            case 'Checksum_missing':
                                $error_msg = _('Check digit missing');
                                break;
                            case 'Checksum':
                                $error_msg = _('Invalid check digit');
                                break;
                            default:
                                $error_msg = $this->data['Part Barcode Number Error'];
                        }


                        $this->error      = true;
                        $this->msg        = $error_msg;
                        $this->error_code = $error;

                        return;
                    }


                    $sql = sprintf(
                        "SELECT `Barcode Key` ,`Barcode Status` ,`Barcode Sticky Note` FROM `Barcode Dimension` WHERE `Barcode Number`=%s", prepare_mysql($value)
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            include_once 'class.Barcode.php';


                            $barcode_status = $row['Barcode Status'];

                            if ($barcode_status == 'Reserved' and preg_match('/ignore_reserved/', $options)) {

                                $barcode = new Barcode($row['Barcode Key']);
                                $barcode->update(array('Barcode Status' => 'Available'));
                                $barcode_status = $barcode->get('Barcode Status');
                            }


                            if ($barcode_status == 'Available') {


                                $barcode         = new Barcode($row['Barcode Key']);
                                $barcode->editor = $this->editor;

                                if ($this->get('Barcode Key')) {

                                    $asset_data = array(
                                        'Barcode Asset Type' => $this->table_name,
                                        'Barcode Asset Key'  => $this->id
                                    );

                                    $barcode->withdrawn_asset($asset_data);
                                }


                                $asset_data = array(
                                    'Barcode Asset Type'          => $this->table_name,
                                    'Barcode Asset Key'           => $this->id,
                                    'Barcode Asset Assigned Date' => gmdate('Y-m-d H:i:s')
                                );


                                $barcode->assign_asset_to_barcode($asset_data);
                                $barcode_label = sprintf(
                                    '<i class="fa fa-barcode fa-fw"></i> <span class="link" onClick="change_view(\'inventory/barcode/%d\')">%s</span>', $barcode->id, $barcode->get('Barcode Number')
                                );


                                $history_data = array(
                                    'History Abstract' => sprintf(
                                        _('Barcode %s associated'), $barcode_label
                                    ),
                                    'History Details'  => '',
                                    'Action'           => 'associated'
                                );

                                $this->add_subject_history(
                                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                                );


                                $this->update_field_switcher(
                                    $this->table_name.' Barcode Number', $value, 'no_history'
                                );
                                $this->update_field_switcher($this->table_name.' Barcode Key', $barcode->id, 'no_history');

                            } else {
                                if ($barcode_status == 'Reserved') {
                                    $this->error = true;
                                    $this->msg   = _("Can't update barcode reserved").' '.$row['Barcode Sticky Note'];

                                    return true;
                                } else {
                                    if ($barcode_status == 'Used') {
                                        $this->error = true;
                                        $this->msg   = _("Can't update, barcode already used");

                                        return true;
                                    } else {
                                        $this->error = true;
                                        $this->msg   = _('Barcode no available');

                                        return true;
                                    }
                                }
                            }


                        } else {
                            if ($this->get('Barcode Key')) {
                                include_once 'class.Barcode.php';
                                $barcode         = new Barcode($this->get('Barcode Key'));
                                $barcode->editor = $this->editor;
                                if ($barcode->id) {
                                    $asset_data = array(
                                        'Barcode Asset Type' => $this->table_name,
                                        'Barcode Asset Key'  => $this->id
                                    );

                                    $barcode->withdrawn_asset($asset_data);
                                }
                            }

                            $this->update_field_switcher($this->table_name.' Barcode Number', $value, $options);
                            $this->update_field_switcher($this->table_name.' Barcode Key', '', 'no_history');


                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }
                }


                if ($this->table_name == 'Part') {

                    $sql = sprintf(
                        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number`=%s AND `Part SKU`!=%d  AND (`Part Barcode Key` IS NULL  OR `Part Barcode Key`=0 )   ', prepare_mysql($_old_value), $this->id
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $part = get_object('Part', $row['Part SKU']);


                            $sql = sprintf(
                                "SELECT `Barcode Key` ,`Barcode Status` ,`Barcode Sticky Note` FROM `Barcode Dimension` WHERE `Barcode Number`=%s   ", prepare_mysql($part->get('Part Barcode Number'))
                            );

                            if ($result2 = $this->db->query($sql)) {
                                if ($row2 = $result2->fetch()) {


                                    $barcode = new Barcode($row2['Barcode Key']);

                                    if ($barcode->get('Barcode Status') == 'Available') {


                                        $asset_data = array(
                                            'Barcode Asset Type'          => 'Part',
                                            'Barcode Asset Key'           => $part->id,
                                            'Barcode Asset Assigned Date' => gmdate('Y-m-d H:i:s')
                                        );


                                        $barcode->assign_asset_to_barcode($asset_data);
                                        $part->fast_update(array('Part Barcode Key' => $barcode->id));

                                    }


                                }
                            }


                        }
                    }
                }


                $this->other_fields_updated = array(
                    $this->table_name.'_Barcode_Number' => array(
                        'field'           => $this->table_name.'_Barcode_Number',
                        'render'          => true,
                        'value'           => $this->get($this->table_name.' Barcode Number'),
                        'formatted_value' => $this->get('Barcode Number'),
                        'barcode_key'     => $this->get($this->table_name.' Barcode Key')


                    )
                );


                return true;

            case 'History Note':

                $this->add_note($value, '', '', $metadata['deletable']);
                break;


            default:


                if (preg_match('/^History Note (\d+)/i', $field, $matches)) {
                    $history_key = $matches[1];
                    $this->edit_note($history_key, $value);

                    return true;
                }

                if (preg_match(
                    '/^History Note Strikethrough (\d+)/i', $field, $matches
                )) {
                    $history_key = $matches[1];
                    $this->edit_note_strikethrough($history_key, $value);

                    return true;
                }


                return false;
        }

        return false;
    }


    function get_asset_common($key, $arg1 = '') {


        if (!$this->id) {
            return;
        }

        switch ($key) {

            case 'Family':
                include_once 'class.Category.php';
                if ($this->get($this->table_name.' Family Category Key') > 0) {
                    $family = new Category(
                        $this->get($this->table_name.' Family Category Key')
                    );
                    if ($family->id) {
                        return array(
                            true,
                            $family
                        );
                    }
                }

                return array(
                    true,
                    false
                );


            case 'Family Category Key':
            case 'Family Category Code':

                include_once 'class.Category.php';
                if ($this->get($this->table_name.' Family Category Key') > 0) {
                    $family = new Category($this->get($this->table_name.' Family Category Key'));
                    if ($family->id) {
                        return array(
                            true,
                            $family->get('Code')
                        );
                    }
                }

                return array(
                    true,
                    ''
                );

                break;


            case  'Tariff Code':
                $tariff_code = $this->data[$this->table_name.' Tariff Code'];
                // if ($tariff_code != '' and $this->data[$this->table_name.' Tariff Code Valid'] == 'No') {
                //     $tariff_code .= ' <span class="error invalid_value"><i class="fa fa-exclamation-circle"></i><span> '._('Invalid').'</span></span>';
                // }

                return array(
                    true,
                    $tariff_code
                );

            case $this->table_name.' Materials':

                if ($this->data[$this->table_name.' Materials'] != '') {
                    $materials = '';

                    $materials_data = json_decode(
                        $this->data[$this->table_name.' Materials'], true
                    );



                    foreach ($materials_data as $material_data) {

                        if ($material_data['may_contain'] == 'Yes') {
                            $may_contain_tag = '±';
                        } else {
                            $may_contain_tag = '';
                        }

                        $materials .= sprintf(
                            ', %s%s', $may_contain_tag, $material_data['name']
                        );

                        if ($material_data['ratio'] > 0) {
                            $materials .= sprintf(
                                ' (%s)', percentage($material_data['ratio'], 1)
                            );
                        }
                    }

                    $materials = preg_replace('/^, /', '', $materials);


                    return array(
                        true,
                        $materials
                    );

                } else {
                    return array(
                        true,
                        ''
                    );
                }

            case 'Materials':

                if ($this->data[$this->table_name.' Materials'] != '') {


                    $materials_data  = json_decode($this->data[$this->table_name.' Materials'], true);
                    $xhtml_materials = '';


                    foreach ($materials_data as $material_data) {
                        if (!array_key_exists('id', $material_data)) {
                            continue;
                        }

                        if ($material_data['may_contain'] == 'Yes') {
                            $may_contain_tag = '±';
                        } else {
                            $may_contain_tag = '';
                        }

                        if ($material_data['id'] > 0) {
                            $xhtml_materials .= sprintf(
                                ', %s<span onCLick="change_view(\'material/%d\')" class="link" >%s</span>', $may_contain_tag, $material_data['id'], $material_data['name']
                            );
                        } else {
                            $xhtml_materials .= sprintf(
                                ', %s%s', $may_contain_tag, $material_data['name']
                            );

                        }


                        if ($material_data['ratio'] > 0) {
                            $xhtml_materials .= sprintf(
                                ' (%s)', percentage($material_data['ratio'], 1)
                            );
                        }
                    }

                    $xhtml_materials = ucfirst(
                        preg_replace('/^, /', '', $xhtml_materials)
                    );

                    return array(
                        true,
                        $xhtml_materials
                    );


                } else {
                    return array(
                        true,
                        ''
                    );
                }


            case 'Package Dimensions':
            case 'Unit Dimensions':


                $key = $this->table_name.' '.$key;


                $dimensions = '';


                if ($this->data[$key] != '') {
                    $data = json_decode($this->data[$key], true);
                    if ($data) {
                        include_once 'utils/units_functions.php';
                        switch ($data['type']) {
                            case 'Rectangular':
                                $dimensions = number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['w'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['h'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';
                                break;
                            case 'Sheet':
                                $dimensions = number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['w'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';
                                break;
                            case 'Cilinder':
                                $dimensions = number(
                                        convert_units(
                                            $data['h'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['w'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';
                                break;
                            case 'Sphere':
                                $dimensions = 'D:'.number(
                                        convert_units(
                                            $data['h'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';

                                break;

                            case 'String':
                                $dimensions = 'L.'.number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';

                                break;


                            default:
                                $dimensions = '';
                        }
                    }
                }


                return array(
                    true,
                    $dimensions
                );

            case 'Package Weight':
            case 'Unit Weight':
                include_once 'utils/natural_language.php';


                return array(
                    true,
                    weight($this->data[$this->table_name.' '.$key])
                );

            case 'Package Smart Weight':
            case 'Unit Smart Weight':
                include_once 'utils/natural_language.php';
                $key = preg_replace('/Smart /', '', $key);


                return array(
                    true,
                    smart_weight($this->data[$this->table_name.' '.$key])

                );


            case 'Package Dimensions':
            case 'Unit Dimensions':

                include_once 'utils/natural_language.php';


                $dimensions = '';


                $tag = preg_replace('/ Dimensions$/', '', $key);

                if ($this->data[$this->table_name.' '.$key] != '') {
                    $data = json_decode($this->data[$this->table_name.' '.$key], true);
                    if ($data) {
                        include_once 'utils/units_functions.php';
                        switch ($data['type']) {
                            case 'Rectangular':

                                $dimensions = number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['w'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['h'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';
                                $dimensions .= '<span class="discreet volume">, '.volume($data['vol']).'</span>';
                                if ($this->data[$this->table_name." $tag Weight"] > 0 and $data['vol'] > 0) {

                                    $dimensions .= '<span class="discreet density">, '.number(
                                            $this->data[$this->table_name." $tag Weight"] / $data['vol'], 3
                                        ).'Kg/L</span>';
                                }

                                break;
                            case 'Sheet':
                                $dimensions = number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['w'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';

                                break;

                            case 'Cilinder':
                                $dimensions = number(
                                        convert_units(
                                            $data['h'], 'm', $data['units']
                                        )
                                    ).'x'.number(
                                        convert_units(
                                            $data['w'], 'm', $data['units']
                                        )
                                    ).' ('.$data['units'].')';
                                $dimensions .= '<span class="discreet volume">, '.volume($data['vol']).'</span>';
                                if ($this->data[$this->table_name." $tag Weight"] > 0 and $data['vol'] > 0) {
                                    $dimensions .= '<span class="discreet density">, '.number(
                                            $this->data[$this->table_name." $tag Weight"] / $data['vol']
                                        ).'Kg/L</span>';
                                }

                                break;

                            case 'Sphere':


                                $dimensions = _('Diameter').' '.number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).$data['units'];
                                $dimensions .= ', <span class="discreet">'.volume(
                                        $data['vol']
                                    ).'</span>';
                                if ($this->data[$this->table_name." $tag Weight"] > 0 and $data['vol'] > 0) {
                                    $dimensions .= '<span class="discreet">, '.number(
                                            $this->data[$this->table_name." $tag Weight"] / $data['vol']
                                        ).'Kg/L</span>';
                                }

                                break;

                            case 'String':
                                $dimensions = number(
                                        convert_units(
                                            $data['l'], 'm', $data['units']
                                        )
                                    ).$data['units'];
                                break;


                            default:
                                $dimensions = '';
                        }
                    }
                }


                return array(
                    true,
                    $dimensions
                );

            case 'Valid From':

                return array(
                    true,
                    strftime(
                        "%a %e %b %Y", strtotime(
                                         $this->data[$this->table_name.' '.$key].' +0:00'
                                     )
                    )
                );


            case 'Valid To':

                if ($this->data[$this->table_name.' '.$key] == '') {
                    return array(
                        true,
                        ''
                    );
                } else {

                    return array(
                        true,
                        strftime(
                            "%a %e %b %Y", strtotime(
                                             $this->data[$this->table_name.' '.$key].' +0:00'
                                         )
                        )
                    );

                }

            default:

                if (preg_match(
                    '/'.$this->table_name.' History Note (\d+)/i', $key, $matches
                )) {


                    return array(
                        true,
                        $this->get_note($matches[1])
                    );

                }
                if (preg_match(
                    '/History Note (Strikethrough )?(\d+)/i', $key, $matches
                )) {


                    return array(
                        true,
                        nl2br($this->get_note($matches[2]))
                    );

                }


                return array(
                    false,
                    false
                );

        }

    }


    function get_image_key($index = 1) {

        $sql = sprintf(
            "SELECT `Image Subject Image Key` FROM  `Image Subject Bridge` WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d ORDER BY `Image Subject Order`  LIMIT %d,1 ", prepare_mysql($this->table_name), $this->id, ($index - 1)
        );

        $image_key = 0;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $image_key = $row['Image Subject Image Key'];
            }
        }

        return $image_key;

    }


}



