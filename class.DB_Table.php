<?php

abstract class DB_Table extends stdClass {

    /**
     * @var $db PDO
     */
    public $db;
    /**
     * @var array
     */
    public $data = array();

    /**
     * @var string
     */
    public $id = 0;
    /**
     * @var bool
     */
    public $fork = false;

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


    /**
     * @param        $user \User
     * @param string $field
     *
     * @return bool
     */
    public function can_edit_field($user,$field=''){
        return true;
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

        $this->last_history_key = false;
        $this->update_table_field($field, $value, $options, $this->table_name, $this->table_name.' Dimension', $this->id);

    }

    protected function update_table_field($field, $value, $options = '', $table_name, $table_full_name, $table_key) {


        $this->updated = false;

        $null_if_empty = true;

        if ($options == 'no_null') {
            $null_if_empty = false;

        }

        if (is_array($value)) {
            return;
        }
        $value = _trim($value);




        $formatted_field = preg_replace('/^'.$this->table_name.' /', '', $field);




        //$old_value=_('Unknown');
        $key_field = $table_name." Key";

        if ($table_name == 'Page' or $table_name == 'Page Store') {
            $key_field = "Page Key";
        }

        if ($table_name == 'Page' ) {
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


        $old_formatted_value = $this->get($formatted_field);


        $sql = sprintf(
            "UPDATE `%s` SET `%s`=? WHERE `%s`=?", addslashes($table_full_name), addslashes($field), addslashes($key_field)
        );


        //prepare_mysql($value, $null_if_empty)
        // $table_key
        $stmt = $this->db->prepare($sql);

        if ($value == '' and $null_if_empty) {
            $value = null;
        }

        $stmt->bindParam(1, $value);
        $stmt->bindParam(2, $table_key);


        $stmt->execute();
        $affected = $stmt->rowCount();

        //print "$affected\n";

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
                    '/product category|invoice|prospect|deal|charge|deal campaign|attachment bridge|location|site|page|part|barcode|agent|customer|contact|company|order|staff|supplier|address|user|store|product|company area|company department|position|category|customer poll query|customer poll query option|api key|email campaign|waehouse|warehouse area|email template|list|sales representative|order basket purge|shipping zone|shipping zone schema|customer client|clocking machine|clocking machine nfc tag/i',
                    $table_name
                ) and !$this->new and $save_history) {




                if($formatted_field=='Tax Number'){
                    $formatted_field='Tax Number Formatted';
                }


                $new_formatted_value = $this->get($formatted_field);



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

        $history_key            = $this->add_history($history_data, false, false, $options);
        $this->last_history_key = $history_key;


        if (in_array($table_name, array('Product Category'))) {


            $this->post_add_history($history_key);
        } else {
            $sql = sprintf(
                "INSERT INTO `%s History Bridge` VALUES (%d,%d,'No','No','Changes')", $table_name, $table_key, $history_key
            );


            $this->db->exec($sql);

            $this->update_history_records_data();
        }


        // }


    }

    function add_history($raw_data, $force = false, $post_arg1 = false, $options = '') {


        return $this->add_table_history($raw_data, $force, $post_arg1, $options, $this->table_name, $this->id);
    }

    function add_table_history($raw_data, $force, $post_arg1, $options = '', $table_name, $table_key) {




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


        if ($data['Action'] == 'updated') {
            $data['Action'] = 'edited';
        }

        if (array_key_exists('User Key', $raw_data)) {
            $data['User Key'] = $raw_data['User Key'];
        } else {
            $data['User Key'] = $editor_data['User Key'];
        }




        if ($data['Subject'] == '' and isset($this->editor['Subject']) and isset($this->editor['Subject Key']) and isset($this->editor['Author Name'])) {
            $data['Subject']     = $this->editor['Subject'];
            $data['Subject Key'] = $this->editor['Subject Key'];
            $data['Author Name'] = $this->editor['Author Name'];
        }

        if ($data['Subject'] == '') {
            include_once 'class.User.php';
            $user = new User($data['User Key']);
            if ($user->id) {

                $data['Subject']     = ($user->data['User Type'] == 'Contractor' ? 'Staff' : $user->data['User Type']);
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
                $data['Author Name'] = $supplier->data['Supplier Name'];
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

                $data['History Details'] = '
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

                $data['History Details'] = '
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


        if (!empty($this->editor['Upload Key'])) {
            $data['History Abstract'] .= ' <span class="italic">( '.sprintf(
                    '<i class="fa fa-upload" aria-hidden="true"></i> <span class="link" onclick="change_view(\'upload/%d\')">%s</span>)</span>', $this->editor['Upload Key'], $this->editor['Upload Label']
                );
        }



        $sql =
            "INSERT INTO `History Dimension` (`Author Name`,`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`User Key`,`Deep`,`Metadata`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(1, $data['Author Name']);
        $stmt->bindValue(2, $data['Date']);
        $stmt->bindValue(3, $data['Subject']);
        $stmt->bindValue(4, $data['Subject Key'], PDO::PARAM_INT);
        $stmt->bindValue(5, $data['Action']);
        $stmt->bindValue(6, $data['Direct Object']);
        $stmt->bindValue(7, $data['Direct Object Key']);
        $stmt->bindValue(8, $data['Preposition']);
        $stmt->bindValue(9, $data['Indirect Object']);
        $stmt->bindValue(10, $data['Indirect Object Key']);
        $stmt->bindValue(11, $data['History Abstract']);
        $stmt->bindValue(12, $data['History Details']);
        $stmt->bindValue(13, $data['User Key'], PDO::PARAM_INT);
        $stmt->bindValue(14, $data['Deep']);
        $stmt->bindValue(15, $data['Metadata']);


        if ($stmt->execute()) {
            $history_key = $this->db->lastInsertId();

            return $history_key;
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }


    }

    protected function get_editor_data() {

        if (isset($this->editor['Date']) and preg_match(
                '/^\d{4}-\d{2}-\d{2}/', $this->editor['Date']
            )) {
            $date = $this->editor['Date'];
        } else {
            $date = gmdate("Y-m-d H:i:s");
        }


        if (isset($this->editor['User Key']) and is_numeric(
                $this->editor['User Key']
            )) {
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

    function post_add_history($history_key, $type = false) {
        return false;
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

        $this->fast_update(
            array($this->get_object_name().' Number History Records' => $number)
        );


    }

    function get_object_name() {
        return $this->table_name;

    }

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

        }elseif ($table_full_name == 'Part Category Data' or $table_full_name == 'Part Category Dimension') {
            $key_field = 'Part Category Key';

        } elseif ($table_full_name == 'Invoice Category Data' or $table_full_name == 'Invoice Category DC Data' or $table_full_name == 'Invoice Category Dimension') {
            $key_field = 'Invoice Category Key';

        } else {
            $key_field = $this->table_name." Key";

        }



        foreach ($data as $field => $value) {




            $sql = sprintf(
                "UPDATE `%s` SET `%s`=? WHERE `%s`=?", addslashes($table_full_name), addslashes($field), addslashes($key_field)
            );


            //print "$sql\n";

            $stmt = $this->db->prepare($sql);


            if ($value === '' and $null_if_empty) {
                $value = null;
            }


            $stmt->bindParam(1, $value);
            $stmt->bindParam(2, $this->id);


            $stmt->execute();
            //print_r($stmt->errorInfo());


            $this->data[$field] = $value;

        }


    }



    public function fast_update_json_field($field, $key, $value, $table_full_name = '') {


        if ($table_full_name == '') {
            $table_full_name = $this->table_name.' Dimension';
        }

        //todo fix after fix Page Store Dimension
        if($this->table_name=='Page'){
            $table_full_name='Page Store Dimension';
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

        }elseif ($table_full_name == 'Part Category Data' or $table_full_name == 'Part Category Dimension') {
            $key_field = 'Product Category Key';

        } elseif ($table_full_name == 'Invoice Category Data' or $table_full_name == 'Invoice Category DC Data' or $table_full_name == 'Invoice Category Dimension') {
            $key_field = 'Invoice Category Key';

        } else {
            $key_field = $this->table_name." Key";

        }


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


        //todo fix after fix Page Store Dimension
        if($this->table_name=='Page'){
            $table_full_name='Page Store Dimension';
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

        }elseif ($table_full_name == 'Part Category Data' or $table_full_name == 'Part Category Dimension') {
            $key_field = 'Product Category Key';

        } elseif ($table_full_name == 'Invoice Category Data' or $table_full_name == 'Invoice Category DC Data' or $table_full_name == 'Invoice Category Dimension') {
            $key_field = 'Invoice Category Key';

        } else {
            $key_field = $this->table_name." Key";

        }


        $sql = sprintf(
            "UPDATE `%s` SET `%s`= JSON_REMOVE(`%s`,'$.%s') WHERE `%s`=?", addslashes($table_full_name), addslashes($field), addslashes($field), addslashes($key), addslashes($key_field)
        );


        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $this->get_data('id', $this->id);


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

    function add_subject_history($history_data, $force_save = true, $deletable = 'No', $type = 'Changes', $table_name = '', $table_key = '', $update_history_records_data = true) {


        if ($table_name == '') {
            $table_name = $this->get_object_name();
        }
        if ($table_key == '') {
            $table_key = $this->id;
        }

        $history_key = $this->add_table_history($history_data, $force_save, '', '', $table_name, $table_key);


        if ($table_name == 'Page') {
            $table_name = 'Webpage';
        }

        $sql = sprintf(
            "INSERT INTO `%s History Bridge` VALUES (%d,%d,%s,'No',%s)", $table_name, $table_key, $history_key, prepare_mysql($deletable), prepare_mysql($type)
        );

        $this->db->exec($sql);

        $this->update_history_records_data();

        return $history_key;
    }

    function get_formatted_id($prefix = '') {


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

    function index_elastic_search($hosts){
        include_once 'utils/ES_Indexer.class.php';
        $account=get_object('Account',1);
        $indexer=new ES_indexer($hosts,$account->get('Code'),$this,$this->db);
        $indexer->add_object();


    }


}


