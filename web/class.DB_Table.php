<?php

abstract class DB_Table extends StdClass{
    public $errors_while_updating = array();
    public $updated_fields = array();
    public $data = array();
    public $id = 0;
    public $warning = false;
    public $deleted = false;
    public $error = false;
    public $msg = '';
    public $new = false;
    public $updated = false;
    public $new_value = false;
    public $error_updated = false;
    public $msg_updated = '';
    public $found = false;
    public $found_key = false;
    public $no_history = false;
    public $candidate = array();
    public $updated_field = array();
    public $editor
        = array(
            'Author Name'  => false,
            'Author Alias' => false,
            'Author Key'   => 0,
            'User Key'     => 0,
            'Date'         => false
        );
    protected $table_name;
    protected $ignore_fields = array();

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

    protected function update_field_switcher($field, $value, $options = '', $metadata = '') {


        $base_data = $this->base_data();


        if (preg_match('/^Address.*Data$/', $field)) {
            $this->update_field($field, $value, $options);

        } elseif (array_key_exists($field, $base_data)) {

            if ($value != $this->data[$field]) {
                $this->update_field($field, $value, $options);

            }
        } elseif (preg_match('/^custom_field_part/i', $field)) {
            $this->update_field($field, $value, $options);
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

    protected function update_field($field, $value, $options = '') {
        $this->update_table_field(
            $field, $value, $options, $this->table_name, $this->table_name.' Dimension', $this->id
        );

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

        if ($table_name == 'Page' or $table_name == 'Page Store') {
            $key_field = "Page Key";
        }

        if ($table_name == 'Page' and $this->type == 'Store') {
            $extra_data = $this->store_base_data();


            if (array_key_exists($field, $extra_data)) {
                $table_full_name = 'Page Store Dimension';
            }


        } else {
            if ($table_name == 'Part' or $table_full_name == 'Part Data') {
                $key_field = 'Part SKU';
            } else {
                if ($table_name == 'Product' or $table_full_name == 'Product Data' or $table_full_name == 'Product DC Data') {
                    $key_field = 'Product ID';
                } else {
                    if ($table_name == 'Supplier Production') {
                        $key_field = 'Supplier Production Supplier Key';
                    }
                }
            }
        }

        if (preg_match('/^custom_field_part/i', $field)) {
            $field1 = preg_replace('/^custom_field_part_/', '', $field);
            //$sql=sprintf("select %s as value from `Part Custom Field Dimension` where `Part SKU`=%d", $field1, $table_key);
        } elseif (preg_match('/^custom_field_customer/i', $field)) {
            $field1 = preg_replace('/^custom_field_customer_/', '', $field);
            $sql    = sprintf(
                "SELECT `Custom Field Key` FROM `Custom Field Dimension` WHERE `Custom Field Name`=%s", prepare_mysql($field1)
            );

            $field_key = 'Error';
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $field_key = $r['Custom Field Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            //$sql=sprintf("select `%s` as value from `Customer Custom Field Dimension` where `Customer Key`=%d", $field_key, $table_key);
        }

        /*
        else {

            $sql=sprintf("select `%s` as value from `%s` where `%s`=%d ",
                addslashes($field),
                addslashes($table_full_name),
                addslashes($key_field),
                $table_key

            );
        }

        if ($result=$this->db->query($sql)) {

            if ($row = $result->fetch()) {
                $old_value=$row['value'];
            }
        }else {
            print_r($error_info=$this->db->errorInfo());
            exit($sql);

        }
*/
        $old_formatted_value = $this->get($formatted_field);


        if (preg_match('/^custom_field_part/i', $field)) {
            if (is_string($value)) {
                $sql = sprintf(
                    "UPDATE `Part Custom Field Dimension` SET `%s`='%s' WHERE `Part SKU`=%d", $field1, $value, $table_key
                );
            } else {
                $sql = sprintf(
                    "UPDATE `Part Custom Field Dimension` SET `%s`='%d' WHERE `Part SKU`=%d", $field1, $value, $table_key
                );
            }
        } elseif (preg_match('/^custom_field_customer/i', $field)) {
            if (is_string($value)) {
                $sql = sprintf(
                    "UPDATE `Customer Custom Field Dimension` SET `%s`='%s' WHERE `Customer Key`=%d", $r['Custom Field Key'], $value, $table_key
                );
            } else {
                $sql = sprintf(
                    "UPDATE `Customer Custom Field Dimension` SET `%s`='%d' WHERE `Customer Key`=%d", $r['Custom Field Key'], $value, $table_key
                );
            }


        } else {
            $sql = sprintf(
                "UPDATE `%s` SET `%s`=%s WHERE `%s`=%d", addslashes($table_full_name), addslashes($field), prepare_mysql($value, $null_if_empty), addslashes($key_field), $table_key
            );

            //print "$sql\n";

        }


        $update_op = $this->db->prepare($sql);
        $update_op->execute();
        $affected = $update_op->rowCount();

        if ($affected == 0) {
            $this->data[$field] = $value;

        } else {


            $this->data[$field] = $value;
            $this->msg .= " $field "._('Record updated').", \n";
            $this->msg_updated .= " $field "._('Record updated').", \n";
            $this->updated   = true;
            $this->new_value = $value;


            if (preg_match('/no( |\_)history|nohistory/i', $options)) {
                $save_history = false;
            } else {
                $save_history = true;
            }


            if (preg_match(
                    '/deal|deal campaign|attachment bridge|location|site|page|part|barcode|agent|customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position|category/i',
                    $table_name
                ) and !$this->new and $save_history
            ) {


                $old_formatted_value = htmlentities($old_formatted_value);
                $new_formatted_value = htmlentities(
                    $this->get($formatted_field)
                );


                $this->add_changelog_record(
                    $field, $old_formatted_value, $new_formatted_value, $options, $table_name, $table_key
                );

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

        $history_key = $this->add_history(
            $history_data, false, false, $options
        );


        if (!in_array($table_name, array())) {


            $sql = sprintf(
                "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $table_name, $table_key, $history_key
            );

            $this->db->exec($sql);
        }

    }

    function add_history($raw_data, $force = false, $post_arg1 = false, $options = '') {


        return $this->add_table_history(
            $raw_data, $force, $post_arg1, $options, $this->table_name, $this->get_main_id()
        );
    }

    function add_table_history($raw_data, $force, $post_arg1, $options = '', $table_name, $table_key) {

        global $account;

        $editor_data = $this->get_editor_data();
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
        if (array_key_exists('User Key', $raw_data)) {
            $data['User Key'] = $raw_data['User Key'];
        } else {
            $data['User Key'] = $editor_data['User Key'];
        }


        if (($data['Subject'] == '' or !$data['Subject Key']) and $data['Subject'] != 'System') {
            include_once 'class.User.php';
            $user = new User($data['User Key']);
            if ($user->id) {

                $data['Subject']     = $user->data['User Type'];
                $data['Subject Key'] = $user->data['User Parent Key'];
                $data['Author Name'] = $user->data['User Alias'];
            } else {
                $data['Subject']     = 'Staff';
                $data['Subject Key'] = 0;
                $data['Author Name'] = _('Unknown');
            }

        }
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


        if (!array_key_exists('Author Name', $data)) {
            $data['Author Name'] = '';
        }


        if ($data['Author Name'] == '') {


            if ($data['Subject'] == 'Customer') {
                include_once 'class.Customer.php';
                $customer            = new Customer($data['Subject Key']);
                $data['Author Name'] = $customer->data['Customer Name'];
            } elseif ($data['Subject'] == 'Staff') {
                include_once 'class.Staff.php';
                $staff               = new Staff($data['Subject Key']);
                $data['Author Name'] = $staff->data['Staff Alias'];
            } elseif ($data['Subject'] == 'Supplier') {
                include_once 'class.Supplier.php';

                $supplier            = new Supplier($data['Subject Key']);
                $data['Author Name'] = $staff->data['Supplier Name'];
            } elseif ($data['Subject'] == 'System') {

                $data['Author Name'] = _('System');
            }


        }


        if ($data['Action'] == 'created') {
            $data['Preposition'] = '';
        }

        if (isset($this->label) and $this->label) {
            $label = $this->label;
        } else {
            $label = $table_name;
        }
        if ($data['History Details'] == '') {
            if (isset($raw_data['old_value']) and isset($raw_data['new_value'])) {

                $data['History Details']
                    = '
				<div class="table">
				<div class="field tr"><div>'._('Time').':</div><div>'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z"
                    ).'</div></div>xx
				<div class="field tr"><div>'._('User').':</div><div>'.$this->editor['Author Alias'].'</div></div>
				<div class="field tr"><div>'._('Action').':</div><div>'._(
                        'Changed'
                    ).'</div></div>
				<div class="field tr"><div>'._('Old value').':</div><div>'.$raw_data['old_value'].'</div></div>
				<div class="field tr"><div>'._('New value').':</div><div>'.$raw_data['new_value'].'</div></div>
				<div class="field tr"><div>'.$label.':</div><div>'.$this->get_name().'</div></div>
                </div>';


            } elseif (isset($raw_data['new_value'])) {

                $data['History Details']
                    = '
				<div class="table">
				<div class="field tr"><div>'._('Time').':</div><div>'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z"
                    ).'</div></div>
				<div class="field tr"><div>'._('User').':</div><div>'.$this->editor['Author Alias'].'</div></div>
				<div class="field tr"><div>'._('Action').':</div><div>'._(
                        'Associated'
                    ).'</div></div>
				<div class="field tr"><div>'._('New value').':</div><div>'.$raw_data['new_value'].'</div></div>
				<div class="field tr"><div>'.$label.':</div><div>'.$this->get_name().'</div></div>
				</div>';

            }
        }


        $sql = sprintf(
            "INSERT INTO `History Dimension` (`Author Name`,`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`User Key`,`Deep`,`Metadata`) VALUES (%s,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s)",
            prepare_mysql($data['Author Name']), prepare_mysql($data['Date']), prepare_mysql($data['Subject']), $data['Subject Key'], prepare_mysql($data['Action']),
            prepare_mysql($data['Direct Object']), $data['Direct Object Key'], prepare_mysql($data['Preposition'], false), prepare_mysql($data['Indirect Object'], false), $data['Indirect Object Key'],
            prepare_mysql($data['History Abstract']), prepare_mysql($data['History Details']), $data['User Key'], prepare_mysql($data['Deep']), prepare_mysql($data['Metadata'])
        );


        $this->db->exec($sql);

        $history_key = $this->db->lastInsertId();

        return $history_key;


    }

    protected function get_editor_data() {

        if (isset($this->editor['Date']) and preg_match(
                '/^\d{4}-\d{2}-\d{2}/', $this->editor['Date']
            )
        ) {
            $date = $this->editor['Date'];
        } else {
            $date = gmdate("Y-m-d H:i:s");
        }

        $user_key = 1;

        if (isset($this->editor['User Key']) and is_numeric(
                $this->editor['User Key']
            )
        ) {
            $user_key = $this->editor['User Key'];
        } else {
            $user_key = 0;
        }

        return array(
            'User Key' => $user_key,
            'Date'     => $date
        );
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

    function get_field_label($field) {
        return $field;
    }

    function get_name() {
        return '';

    }

    function get_main_id() {


        return $this->id;

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

    function reread() {


        $this->get_data('id', $this->id);


    }

    function add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $table_name, $table_key) {

        $history_key = $this->add_table_history(
            $history_data, $force_save, '', '', $table_name, $table_key
        );

        $sql = sprintf(
            "INSERT INTO `%s History Bridge` VALUES (%d,%d,%s,'No',%s)", $table_name, $table_key, $history_key, prepare_mysql($deletable), prepare_mysql($type)
        );

        $this->db->exec($sql);


        return $history_key;
    }

    function get_object_name() {
        return $this->table_name;

    }


    function get_formatted_id($prefix = '') {

        /*
        global $myconf;
        $sql="select count(*) as num from `Company Dimension`";
        $res=mysql_query($sql);
        $min_number_zeros=$myconf['company_min_number_zeros_id'];
        if ($row=mysql_fetch_array($res)) {
            if (strlen($row['num'])-1>$min_number_zeros)
                $min_number_zeros=strlen($row['num'])-01;
        }
        if (!is_numeric($min_number_zeros))
            $min_number_zeros=4;

        return sprintf("%s%0".$min_number_zeros."d",$myconf['company_id_prefix'], $this->id);
*/

        return sprintf("%s%04d", $prefix, $this->id);
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

    protected function translate_data($data, $options = '') {

        $_data = array();
        foreach ($data as $key => $value) {

            if (preg_match('/supplier/i', $options)) {
                $regeprix = '/^Supplier /i';
            } elseif (preg_match('/customer/i', $options)) {
                $regex = '/^Customer /i';
            } elseif (preg_match('/company/i', $options)) {
                $regex = '/^Company /i';
            } elseif (preg_match('/contact/i', $options)) {
                $regex = '/^Contact /i';
            }

            $rpl = $this->table_name.' ';


            $_key         = preg_replace($regex, $rpl, $key);
            $_data[$_key] = $value;
        }


        return $_data;
    }


}


?>
