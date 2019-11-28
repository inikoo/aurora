<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 23:01:29 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

abstract class DBW_Table extends stdClass {

    public $data = array();
    public $id = 0;
    public $warning = false;
    public $deleted = false;
    public $error = false;
    public $msg = '';
    public $new = false;
    public $updated = false;
    public $new_value = false;
    public $msg_updated = '';
    public $found = false;
    public $found_key = false;
    public $no_history = false;
    public $candidate = array();
    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );
    protected $table_name;
    protected $ignore_fields = array();


    public function fast_update($data, $table_full_name = false, $options = '') {

        if ($options == 'no_null') {
            $null_if_empty = false;

        } else {
            $null_if_empty = true;

        }


        if ($table_full_name == '') {
            $table_full_name = $this->table_name.' Dimension';
        }


        if ($table_full_name == 'Part Dimension' or $table_full_name == 'Part Data') {
            $key_field = 'Part SKU';
        } elseif ($table_full_name == 'Product Dimension' or $table_full_name == 'Product Data' or $table_full_name == 'Product DC Data') {
            $key_field = 'Product ID';
        } elseif ($table_full_name == 'Supplier Production Dimension') {
            $key_field = 'Supplier Production Supplier Key';

        } elseif ($table_full_name == 'Page Store Dimension') {
            $key_field = 'Page Key';

        } elseif ($table_full_name == 'Product Category Data' or $table_full_name == 'Product Category DC Data' or $table_full_name == 'Product Category Dimension') {
            $key_field = 'Product Category Key';

        } else {
            $key_field = $this->table_name." Key";

        }


        foreach ($data as $field => $value) {


            $sql = sprintf(
                "UPDATE `%s` SET `%s`=%s WHERE `%s`=%d", addslashes($table_full_name), addslashes($field), prepare_mysql($value, $null_if_empty), addslashes($key_field), $this->id
            );

            //   print "$sql;\n";

            $this->db->exec($sql);
            $this->data[$field] = $value;

        }


    }


    public function update($data, $options = '', $metadata = '') {


        $this->error = false;
        $this->msg   = '';
        if (!is_array($data)) {

            $this->error = true;

            return;
        }

        if (isset($data['editor'])) {

            foreach ($data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }


        foreach ($data as $key => $value) {


            if (is_string($value)) {
                $value = _trim($value);
            }
            $this->update_field_switcher($key, $value, $options, $metadata);


        }

        if (!$this->updated and $this->msg == '') {
            $this->msg .= _('Nothing to be updated')."\n";
        }
    }


    public function fast_update_json_field($field, $key, $value, $table_full_name = '') {


        if ($table_full_name == '') {
            $table_full_name = $this->table_name.' Dimension';
        }

        $key_field = $this->table_name." Key";

        $sql = sprintf(
            "UPDATE `%s` SET `%s`= JSON_SET(`%s`,'$.%s',?) WHERE `%s`=?", addslashes($table_full_name), addslashes($field), addslashes($field), addslashes($key), addslashes($key_field)
        );


        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $value);
        $stmt->bindParam(2, $this->id);
        $stmt->execute();

        $this->get_data('id', $this->id);


    }

    public function fast_remove_key_from_json_field($field, $key, $table_full_name = '') {


        if ($table_full_name == '') {
            $table_full_name = $this->table_name.' Dimension';
        }

        $key_field = $this->table_name." Key";

        $sql = sprintf(
            "UPDATE `%s` SET `%s`= JSON_REMOVE(`%s`,'$.%s') WHERE `%s`=?", addslashes($table_full_name), addslashes($field), addslashes($field), addslashes($key), addslashes($key_field)
        );


        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $this->get_data('id', $this->id);


    }

    protected function update_field_switcher($field, $value, $options = '', $metadata = '') {


        $base_data = $this->base_data();


        if (array_key_exists($field, $base_data)) {

            if ($value != $this->data[$field]) {
                $this->update_field($field, $value, $options, $metadata);

            }
        }

    }

    function base_data($table_name = '') {


        if ($table_name == '') {
            $table_name = $this->table_name.' Dimension';
        }

        $data = array();


        $sql = sprintf('show columns from `%s`', addslashes($table_name));
        foreach ($this->db->query($sql) as $row) {
            if (!in_array($row['Field'], $this->ignore_fields)) {
                $data[$row['Field']] = $row['Default'];
            }
        }

        return $data;
    }

    function post_add_history($history_key, $type = false) {
        return false;
    }

    function set_editor($raw_data) {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

    }

    function add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $table_name, $table_key, $update_history_records_data = true) {


        $history_key = $this->add_table_history($history_data, $force_save, '', '', $table_name, $table_key);

        $sql = sprintf(
            "INSERT INTO `%s History Bridge` VALUES (%d,%d,%s,'No',%s)", $table_name, $table_key, $history_key, prepare_mysql($deletable), prepare_mysql($type)
        );


        $this->db->exec($sql);

        $this->update_history_records_data();

        return $history_key;
    }

    function get_update_metadata() {

        if (isset($this->update_metadata)) {
            return $this->update_metadata;
        } else {
            return array();
        }

    }

    function get_other_fields_update_info() {

        if (isset($this->other_fields_updated)) {
            return $this->other_fields_updated;
        } else {
            return false;
        }
    }

    function get_new_fields_info() {
        if (isset($this->new_fields_info)) {
            return $this->new_fields_info;
        } else {
            return false;
        }
    }

    function get_deleted_fields_info() {
        if (isset($this->deleted_fields_info)) {
            return $this->deleted_fields_info;
        } else {
            return false;
        }
    }

    protected function update_field($field, $value, $options = '') {
        $this->update_table_field($field, $value, $options, $this->table_name, $this->table_name.' Dimension', $this->id);

    }

    protected function update_table_field($field, $value, $options = '', $table_name, $table_full_name, $table_key) {

        //print "*** $field, $value\n";
        $this->updated = false;

        $null_if_empty = true;

        if ($options == 'no_null') {
            $null_if_empty = false;

        }

        if (is_array($value)) {
            return;
        }
        $value = _trim($value);


        $formatted_field = preg_replace('/'.$this->table_name.' /', '', $field);


        //$old_value=_('Unknown');
        $key_field = $table_name." Key";


        $old_formatted_value = $this->get($formatted_field);


        $sql = sprintf(
            "UPDATE `%s` SET `%s`=%s WHERE `%s`=%d", addslashes($table_full_name), addslashes($field), prepare_mysql($value, $null_if_empty), addslashes($key_field), $table_key
        );


        $update_op = $this->db->prepare($sql);
        $update_op->execute();


        $affected = $update_op->rowCount();

        // print "$sql $affected  \n";


        if ($affected == 0) {


            $this->data[$field] = $value;

        } else {


            $this->data[$field] = $value;
            $this->msg          .= " $field "._('Record updated').", \n";
            $this->msg_updated  .= " $field "._('Record updated').", \n";
            $this->updated      = true;
            $this->new_value    = $value;


            if (preg_match('/no( |\_)history|nohistory/i', $options)) {
                $save_history = false;
            } else {
                $save_history = true;
            }


            if (preg_match(
                    '/deal|deal campaign|attachment bridge|location|site|page|part|barcode|agent|customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position|category/i', $table_name
                ) and !$this->new and $save_history) {


                $old_formatted_value = htmlentities($old_formatted_value);
                $new_formatted_value = htmlentities($this->get($formatted_field));


                $this->add_changelog_record($field, $old_formatted_value, $new_formatted_value, $options, $table_name, $table_key);

            }

        }

    }

    function add_changelog_record($field, $old_value, $value, $options, $table_name, $table_key, $action = 'updated') {


        $history_data = array(
            'Indirect Object' => $field,
            'old_value'       => $old_value,
            'new_value'       => $value,
            'Action'          => $action

        );


        $history_key = $this->add_history($history_data, false, false, $options);


        $sql = sprintf(
            "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $table_name, $table_key, $history_key
        );
        $this->db->exec($sql);


        $this->update_history_records_data();


    }

    function add_history($raw_data, $force = false, $post_arg1 = false, $options = '') {


        return $this->add_table_history($raw_data, $force, $post_arg1, $options, $this->table_name, $this->id);
    }

    function add_table_history($raw_data, $force, $post_arg1, $options = '', $table_name, $table_key) {


        $editor_data = $this->editor;


        if ($this->no_history) {
            return;
        }

        if ($this->new and !$force) {
            return;
        }

        if (!isset($raw_data['Direct Object'])) {
            $raw_data['Direct Object'] = $table_name;
        }

        if (!isset($raw_data['Direct Object Key'])) {
            $raw_data['Direct Object Key'] = $table_key;
        }

        $data = $this->base_history_data();

        foreach ($raw_data as $key => $value) {
            $data[$key] = $value;
        };


        if ($data['Action'] == 'updated') {
            $data['Action'] = 'edited';
        }

        if (array_key_exists('User Key', $raw_data)) {
            $data['User Key'] = $raw_data['User Key'];
        } else {
            $data['User Key'] = $editor_data['User Key'];
        }

        //  print_r($data);


        if (!isset($data['Date']) or $data['Date'] == '') {
            $data['Date'] = $editor_data['Date'];
        }

        if ($data['History Abstract'] == '') {
            if ($data['Indirect Object']) {

                switch ($data['Indirect Object']) {
                    case 'Customer Website':
                        $formatted_indirect_object = _('Customer website');
                        break;
                    case 'Customer Name':
                        $formatted_indirect_object = _('Customer name');
                        break;


                    default:
                        $formatted_indirect_object = $this->get_field_label(
                            $data['Indirect Object']
                        );

                }


                if ($table_name == 'Staff') {
                    $formatted_table = "Employee's";
                } else {
                    $formatted_table = $table_name."'s";
                }


                if ($data['Action'] == 'added') {
                    $data['History Abstract'] = sprintf(
                        _("%s %s %s was added"), $formatted_table, $formatted_indirect_object, $raw_data['new_value']
                    );

                } elseif ($data['Action'] == 'removed') {
                    $data['History Abstract'] = sprintf(
                        _("%s %s %s was removed"), $formatted_table, $formatted_indirect_object, $raw_data['new_value']
                    );

                } elseif ($data['Action'] == 'set_as_main') {
                    $data['History Abstract'] = sprintf(
                        _("%s %s was set as %s"), $formatted_table, $formatted_indirect_object, $raw_data['new_value']
                    );

                } else {


                    if ($raw_data['new_value'] == '') {
                        $data['History Abstract'] = sprintf(
                            _("%s %s %s was deleted"), $formatted_table, $formatted_indirect_object, $raw_data['old_value']
                        );
                    } elseif ($raw_data['old_value'] == '') {
                        $data['History Abstract'] = sprintf(
                            _("%s %s set as %s"), $formatted_table, $formatted_indirect_object, $raw_data['new_value']
                        );
                    } else {
                        $data['History Abstract'] = sprintf(
                            _("%s %s was changed to %s"), $formatted_table, $formatted_indirect_object, $raw_data['new_value']
                        );
                    }
                }


                $formatted_indirect_object.' '._('changed').' ('.$raw_data['new_value'].')';
            } else {
                $data['History Abstract'] = 'Unknown';
            }
        }


        if ($data['Action'] == 'created') {
            $data['Preposition'] = '';
        }


        if ($data['History Details'] == '') {
            if (isset($raw_data['old_value']) and isset($raw_data['new_value'])) {

                $data['History Details'] = '
				<div class="table">
				<div class="field tr"><div>'._('Updated by customer').'</div><div></div></div>
				<div class="field tr"><div>'._('Time').':</div><div>'.strftime("%a %e %b %Y %H:%M:%S %Z").'</div></div>
				<div class="field tr"><div>'._('Action').':</div><div>'._('Changed').'</div></div>
				<div class="field tr"><div>'._('Old value').':</div><div>'.$raw_data['old_value'].'</div></div>
				<div class="field tr"><div>'._('New value').':</div><div>'.$raw_data['new_value'].'</div></div>
                </div>';


            } elseif (isset($raw_data['new_value'])) {

                $data['History Details'] = '
				<div class="table">
								<div class="field tr"><div>'._('Updated by customer').'</div><div></div></div>
				<div class="field tr"><div>'._('Time').':</div><div>'.strftime("%a %e %b %Y %H:%M:%S %Z").'</div></div>
				<div class="field tr"><div>'._('Action').':</div><div>'._('Associated').'</div></div>
				<div class="field tr"><div>'._('New value').':</div><div>'.$raw_data['new_value'].'</div></div>
				</div>';

            }
        }


        $sql = sprintf(
            "INSERT INTO `History Dimension` (`Author Name`,`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`User Key`,`Deep`,`Metadata`) VALUES (%s,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s)",
            prepare_mysql($data['Author Name']), prepare_mysql($data['Date']), prepare_mysql($data['Subject']), $data['Subject Key'], prepare_mysql($data['Action']), prepare_mysql($data['Direct Object']), $data['Direct Object Key'],
            prepare_mysql($data['Preposition'], false), prepare_mysql($data['Indirect Object'], false), $data['Indirect Object Key'], prepare_mysql($data['History Abstract']), prepare_mysql($data['History Details']), $data['User Key'], prepare_mysql($data['Deep']),
            prepare_mysql($data['Metadata'])
        );

        //    print "$sql\n";


        $this->db->exec($sql);

        $history_key = $this->db->lastInsertId();

        return $history_key;


    }

    function base_history_data() {


        $data = array();
        $sql  = 'SHOW COLUMNS FROM `History Dimension`';
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (!in_array($row['Field'], $this->ignore_fields)) {
                    $data[$row['Field']] = $row['Default'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $data;
    }

    function update_history_records_data() {


        $table = $this->get_object_name().' History Bridge';

        // $table=($this->get_object_name() == 'Category' ? $this->subject_table_name : $this->get_object_name()).' History Bridge';

        switch ($this->get_object_name()) {

            case 'Page':

                return;

                $where_field = 'Webpage Key';
                $table       = 'Webpage History Bridge';
                break;

            case 'Part':
                $where_field = 'Part SKU';
                break;
            case 'Product':
                $where_field = 'Product ID';
                break;
            default:
                $where_field = $this->get_object_name().' Key';
                break;
        }


        $sql = sprintf(
            'SELECT count(*) AS num FROM `%s` WHERE  `%s`=%d ', $table, $where_field, $this->id
        );


        $number = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "UPDATE `".$this->get_object_name()." Dimension` SET `".$this->get_object_name()." Number History Records`=%d WHERE `".$this->get_object_name()." Key`=%d", $number, $this->id
        );

        // print $sql;

        $this->db->exec($sql);


    }

    function get_object_name() {
        return $this->table_name;

    }


}


?>
