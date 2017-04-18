<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2016 at 19:07:36 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'class.DB_Table.php';

class Barcode extends DB_Table {


    function Barcode($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Barcode';
        $this->ignore_fields = array('Barcode Key');

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
                "SELECT * FROM `Barcode Dimension` WHERE `Barcode Key`=%d", $tag
            );
        } elseif ($key == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Barcode Key'];

        }


    }


    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Barcode Deleted Dimension` WHERE `Barcode Deleted Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Barcode Deleted Key'];

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


        if (strlen($data['Barcode Number']) == 12) {
            $sql = sprintf(
                "SELECT `Barcode Key` FROM `Barcode Dimension` WHERE  `Barcode Number`=%s  ", prepare_mysql(
                                                                                                $data['Barcode Number'].$this->get_check_digit(
                                                                                                    'EAN', $data['Barcode Number']
                                                                                                )
                                                                                            )
            );
        } else {

            $sql = sprintf(
                "SELECT `Barcode Key` FROM `Barcode Dimension` WHERE  `Barcode Number`=%s  ", prepare_mysql($data['Barcode Number'])
            );
        }

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Barcode Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Barcode Number';

$this->msg=_('Barcode already in the system');

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

    function get_check_digit($type, $digits) {

        switch ($type) {
            case 'EAN':
                $digits         = (string)$digits;
                $even_sum       = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
                $even_sum_three = $even_sum * 3;
                $odd_sum        = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
                $total_sum      = $even_sum_three + $odd_sum;
                $next_ten       = (ceil($total_sum / 10)) * 10;
                $check_digit    = $next_ten - $total_sum;

                return $check_digit;
                break;
            default:
                return '';
                break;
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





        if ($base_data['Barcode Type'] == 'EAN') {


            if (strlen($base_data['Barcode Number']) == 12) {
                $base_data['Barcode Number'] = $base_data['Barcode Number'].$this->get_check_digit(
                        $base_data['Barcode Type'], $base_data['Barcode Number']
                    );
            } elseif (strlen($base_data['Barcode Number']) == 13) {

                if ($this->get_check_digit(
                        $base_data['Barcode Type'], substr($base_data['Barcode Number'], 0, 12)
                    ) != substr($base_data['Barcode Number'], -1)
                ) {

                    $this->error = true;
                    $this->msg   = _('Barcode check digit error');

                    return;
                }

            } else {
                $this->error = true;
                $this->msg   = _(
                    'EAN13 is a barcode format must consist in 12 digits plus 1 check digit'
                );
            }

        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match('/^(Barcode Sticky Note)$/i', $key)) {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);

        $sql = sprintf("INSERT INTO `Barcode Dimension` %s %s", $keys, $values);
        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Barcode added");
            $this->get_data('id', $this->id);
            $this->new = true;


            $history_data = array(
                'History Abstract' => sprintf(
                    _('Barcode record %s created'), $this->data['Barcode Number']
                ),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            //506006011228
            // try to find orphans barcodes


            $sql=sprintf('select `Part SKU` from `Part Dimension` where `Part Barcode Number`=%s and  (`Part Barcode Key`=0 or `Part Barcode Key` is null) ',

                         $this->get('Barcode Number')

                         );
            if ($result=$this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    include_once 'class.Part.php';

                    $part=new Part($row['Part SKU']);

                    $asset_data = array(
                        'Barcode Asset Type'          => 'Part',
                        'Barcode Asset Key'           => $part->id,
                        'Barcode Asset Assigned Date' => gmdate('Y-m-d H:i:s')
                    );

                    $this->assign_asset($asset_data);

                }
            }else {
            	print_r($error_info=$this->db->errorInfo());
            	print "$sql\n";
            	exit;
            }




            return;
        } else {
            $this->msg = _("Error, can not create barcode");
            print $sql;
            exit;
        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

        switch ($field) {
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }


    function assign_asset($asset_data) {

        $this->new_assigned_asset = false;

        $asset_data['Barcode Asset Barcode Key'] = $this->id;

        if (!array_key_exists('Barcode Asset Assigned Date', $asset_data) or $asset_data['Barcode Asset Assigned Date'] == '') {
            $asset_data['Barcode Asset Assigned Date'] = gmdate('Y-m-d H:i:s');
        }


        $keys   = '(';
        $values = 'values(';
        foreach ($asset_data as $key => $value) {
            $keys .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Barcode Asset Bridge` %s %s", $keys, $values
        );



        if ($this->db->exec($sql)) {

            $this->msg                = _("Barcode assigned to asset");
            $this->new_assigned_asset = true;

            $this->update_status();




            if ($asset_data['Barcode Asset Type'] == 'Part') {
                $asset       = get_object($asset_data['Barcode Asset Type'], $asset_data['Barcode Asset Key']);
                $asset_label = sprintf(
                    '<i class="fa fa-square fa-fw"></i> <span class="link" onClick="change_view(\'part/%d\')">%s</span>', $asset->get('Part SKU'), $asset->get('Part Reference')
                );




                $asset->update(
                    array(
                        'Part Barcode Number' => $this->get('Barcode Number'),
                        'Part Barcode Key'    => $this->id
                    ), 'no_history'
                );


            } else {
                $asset_label = 'asset';
            }

            $history_data = array(
                'History Abstract' => sprintf(_('%s associated'), $asset_label),
                'History Details'  => '',
                'Action'           => 'associated'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->assigned=true;
            return;
        } else {
            $this->error=true;
            $this->assigned=false;
            $this->msg = _("Error, can't associate asset");

        }


    }

    function update_status() {


        if ($this->get('Barcode Status') != 'Reserved') {

            $sql = sprintf(
                "SELECT count(*) AS num,min(`Barcode Asset Assigned Date`) AS from_date FROM  `Barcode Asset Bridge` WHERE `Barcode Asset Barcode Key`=%d AND `Barcode Asset Status`='Assigned' ",
                $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    if ($row['num'] > 0) {
                        $status = 'Used';
                    } else {
                        $status = 'Available';
                    }
                    $this->update(
                        array('Barcode Status' => $status), 'no_history'
                    );
                    if ($row['from_date'] != '') {

                        $this->update(
                            array('Barcode Used From' => $row['from_date']), 'no_history'
                        );

                    }

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
        }

    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {

            case 'Parts':
                $parts = '';
                $sql   = sprintf(
                    'SELECT `Part SKU`,`Part Reference` FROM `Barcode Asset Bridge` LEFT JOIN `Part Dimension` ON (`Barcode Asset Type`="Part" AND `Barcode Asset Key`=`Part SKU`) WHERE `Barcode Asset Status`="Assigned" AND `Barcode Asset Barcode Key`=%d',
                    $this->id
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $parts = sprintf(
                            ', <i class="fa fa-square fa-fw"></i> <span class="link" onClick="change_view(\'part/%d\')">%s</span>', $row['Part SKU'], $row['Part Reference']
                        );
                    }
                    $parts = preg_replace('/^, /', '', $parts);
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                return $parts;
                break;
            case 'Status':

                switch ($this->data['Barcode Status']) {
                    case 'Available':
                        $status = sprintf(
                            '<i class="fa fa-barcode" ></i> %s', _('Available')
                        );
                        break;
                    case 'Used':
                        $status = sprintf(
                            '<i class="fa fa-cube disabled" ></i> %s', _('Used')
                        );

                        break;
                    case 'Reserved':
                        $status = sprintf(
                            '<i class="fa fa-shield disabled" ></i> %s', _('Reserved')
                        );

                        break;
                    default:
                        $status = $this->data['Barcode Status'];
                        break;
                }

                return $status;
                break;
            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Barcode '.$key, $this->data)) {
                    return $this->data['Barcode '.$key];
                }
        }

        return '';
    }

    function get_field_label($field) {
        global $account;

        switch ($field) {

            case 'Barcode Number':
                $label = _("code");
                break;
            case 'Barcode Status':
                $label = _('status');
                break;
            case 'Barcode Type':
                $label = _('type');
                break;
            case 'Barcode Used From':
                $label = _('used from');
                break;
            case 'Barcode Sticky Note':
                $label = _('note');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


    function delete($metadata = false) {


        $sql = sprintf(
            "SELECT `Barcode Asset Type`,`Barcode Asset Key` FROM `Barcode Asset Bridge` WHERE `Barcode Asset Status`='Assigned' AND `Barcode Asset Barcode Key`=%d ", $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $asset_data = array(
                    'Barcode Asset Type' => $row['Barcode Asset Type'],
                    'Barcode Asset Key'  => $row['Barcode Asset Key']
                );

                $this->withdrawn_asset($asset_data);

                $asset = get_object(
                    $row['Barcode Asset Type'], $row['Barcode Asset Key']
                );
                $asset->update(
                    array($asset->table_name.' Barcode Key' => ''), 'no_history'
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            'INSERT INTO `Barcode Deleted Dimension`  (`Barcode Deleted Key`,`Barcode Deleted Type`,`Barcode Deleted Number`,`Barcode Deleted Sticky Note`) VALUES (%d,%s,%s,%s) ', $this->id,
            prepare_mysql($this->get('Barcode Type')), prepare_mysql($this->get('Barcode Number')), prepare_mysql($this->get('Barcode Sticky Note'))


        );
        $this->db->exec($sql);


        $sql = sprintf(
            'DELETE FROM `Barcode Dimension`  WHERE `Barcode Key`=%d ', $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => sprintf(
                _('Barcode record %s deleted'), $this->data['Barcode Number']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $this->deleted = true;
    }

    function withdrawn_asset($asset_data) {


        $sql = sprintf(
            "UPDATE  `Barcode Asset Bridge` SET `Barcode Asset Status`='Historic',`Barcode Asset Withdrawn Date`=%s  WHERE `Barcode Asset Status`='Assigned' AND `Barcode Asset Type`=%s AND `Barcode Asset Key`=%d ",
            prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($asset_data['Barcode Asset Type']), $asset_data['Barcode Asset Key']
        );


        $this->db->exec($sql);
        $this->update_status();

        if ($asset_data['Barcode Asset Type'] == 'Part') {
            $asset       = get_object(
                $asset_data['Barcode Asset Type'], $asset_data['Barcode Asset Key']
            );
            $asset_label = sprintf(
                '<i class="fa fa-square fa-fw"></i> <span class="link" onClick="change_view(\'part/%d\')">%s</span>', $asset->get('Part SKU'), $asset->get('Part Reference')
            );
        } else {
            $asset_label = 'asset';
        }
        $history_data = array(
            'History Abstract' => sprintf(_('%s disassociated'), $asset_label),
            'History Details'  => '',
            'Action'           => 'disassociate'
        );
        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );

    }


}


?>
