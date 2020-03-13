<?php
/*
 
 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 13 February 2018 at 12:30:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

include_once 'class.DB_Table.php';


class Customer_Poll_Query extends DB_Table {


    function __construct($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Customer Poll Query';
        $this->ignore_fields = array('Customer Poll Query Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2);
        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Poll Query Dimension` WHERE `Customer Poll Query Key`=%d", $tag
            );
        } elseif ($key == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Customer Poll Query Key'];
        }


    }


    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Customer Poll Query Deleted Dimension` WHERE `Customer Poll Query Deleted Key`=%d", $tag
        );

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id     = $this->data['Customer Poll Query Deleted Key'];
            $deleted_data = json_decode(
                gzuncompress($this->data['Customer Poll Query Deleted Metadata']), true
            );
            foreach ($deleted_data['data'] as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }


    function create($data) {


        $this->new = false;

        $this->editor = $data['editor'];

        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Customer Poll Query Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Customer poll query created");
            $this->get_data('id', $this->id);
            $this->new = true;


            $number_of_poll_queries = 0;

            $sql = sprintf('SELECT count(*) AS num FROM  `Customer Poll Query Dimension` WHERE  `Customer Poll Query Store Key`=%d ', $this->get('Customer Poll Query Store Key'));
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_of_poll_queries = $row['num'];
                }
            }

            $this->fast_update(array('Customer Poll Query Position' => $number_of_poll_queries));

            $this->redo_positions();
            $history_data = array(
                'History Abstract' => _('Customer poll query created'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->update_website();


            return;
        } else {
            $this->msg = "Error can not create poll query";
            print $sql;
            exit;
        }
    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Customers':
                return number($this->data['Customer Poll Query Customers']);

                break;
            case 'Customers Share':
                $store = get_object('Store', $this->data['Customer Poll Query Store Key']);

                return percentage($this->data['Customer Poll Query Customers'], $store->get('Store Contacts'));

                break;
            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer Poll Query '.$key, $this->data)) {
                    return $this->data['Customer Poll Query '.$key];
                }


        }

        return '';
    }

    function redo_positions() {

        $position = 1;
        $sql      = sprintf('SELECT `Customer Poll Query Key` FROM `Customer Poll Query Dimension` WHERE `Customer Poll Query Store Key`=%d  ORDER BY `Customer Poll Query Position`   ', $this->get('Customer Poll Query Store Key'));
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $sql = sprintf(
                    'UPDATE `Customer Poll Query Dimension` SET `Customer Poll Query Position`=%d WHERE `Customer Poll Query Key`=%d  ', $position, $row['Customer Poll Query Key']
                );

                $this->db->exec($sql);
                $position++;

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function create_option($data) {

        $this->new_option = false;

        $data['editor'] = $this->editor;


        //print_r($data);

        if (empty($data['Customer Poll Query Option Name'])) {
            $this->error      = true;
            $this->msg        = _("Answer code missing");
            $this->error_code = 'option_name_missing';
            $this->metadata   = '';

            return;
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Query Key`=%d AND `Customer Poll Query Option Name`=%s ', $this->id, prepare_mysql($data['Customer Poll Query Option Name'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {


                    $this->error      = true;
                    $this->msg        = sprintf(_('Duplicated code (%s)'), $data['Customer Poll Query Option Name']);
                    $this->error_code = 'duplicate_poll_option_name';
                    $this->metadata   = $data['Customer Poll Query Option Name'];


                    return;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if (empty($data['Customer Poll Query Option Label'])) {
            $this->error      = true;
            $this->msg        = _("Answer missing");
            $this->error_code = 'option_label_missing';
            $this->metadata   = '';

            return;
        }


        $data['Customer Poll Query Option Query Key'] = $this->id;
        $data['Customer Poll Query Option Store Key'] = $this->get('Customer Poll Query Store Key');


        $option = new Customer_Poll_Query_Option('new', $data);


        if ($option->id) {
            $this->new_object_msg = $option->msg;

            if ($option->new) {
                $this->new_object = true;
                $this->new_option = true;


                $this->update_answers();


            } else {

                $this->error = true;
                if ($option->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($option->duplicated_field)
                    );

                    if ($option->duplicated_field == 'Customer Poll Query Option Name') {
                        $this->msg = _("Duplicated code");
                    } else {
                        $this->msg = 'Duplicated '.$option->duplicated_field;
                    }


                } else {
                    $this->msg = $option->msg;
                }
            }

            return $option;
        } else {

            $this->error = true;
            $this->msg   = $option->msg;

        }

    }

    function update_answers() {


        $number_options   = 0;
        $number_customers = 0;
        $last_answered    = '';

        if ($this->data['Customer Poll Query Type'] == 'Options') {
            $sql = sprintf(
                'SELECT count(*) AS options ,sum(`Customer Poll Query Option Customers`) AS customers ,max(`Customer Poll Query Option Last Answered`) AS last_answered FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Query Key`=%d',
                $this->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {


                    $number_options   = $row['options'];
                    $number_customers = $row['customers'];
                    $last_answered    = $row['last_answered'];

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
        } else {


            $sql = sprintf('SELECT count(DISTINCT `Customer Poll Customer Key`) AS number ,max(`Customer Poll Date`) AS last_answered  FROM `Customer Poll Fact` WHERE `Customer Poll Query Key`=%d', $this->id);


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_customers = $row['number'];

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

        }


        $this->fast_update(
            array(
                'Customer Poll Query Options'       => $number_options,
                'Customer Poll Query Customers'     => $number_customers,
                'Customer Poll Query Last Answered' => $last_answered


            )
        );


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

        $this->update_website();
    }

    function get_field_label($field) {

        switch ($field) {


            case 'Customer Poll Query Name':
                $label = _('query code');
                break;
            case 'Customer Poll Query Label':
                $label = _('query');
                break;

            case 'Customer Poll Query Type':
                $label = _('query type');
                break;
            case 'Customer Poll Query In Registration':
                $label = _('show in registration');
                break;
            case 'Customer Poll Query In Profile':
                $label = _('show in customer profile');
                break;
            case 'Customer Poll Query Registration Required':
                $label = _('required for registration');
                break;
            default:

                $label = $field;

        }


        return $label;

    }

    /**
     * @param $customer_key
     *
     * @return array
     */
    public function get_answer($customer_key) {

        if ($this->get('Customer Poll Query Type') == 'Open') {
            $sql  = "SELECT `Customer Poll Reply` FROM `Customer Poll Fact` WHERE `Customer Poll Query Key`=? AND `Customer Poll Customer Key`=?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id,
                    $customer_key
                )
            );
            if ($row = $stmt->fetch()) {
                return array(
                    'open',
                    $row['Customer Poll Reply'],
                    0
                );
            } else {
                return array(
                    '',
                    '',
                    0
                );
            }


        } else {
            $sql =
                "SELECT CPQOD.`Customer Poll Query Option Key`,`Customer Poll Query Option Name`,`Customer Poll Query Option Label` FROM `Customer Poll Fact` CPF  LEFT JOIN `Customer Poll Query Option Dimension` CPQOD ON (CPQOD.`Customer Poll Query Option Key`=CPF.`Customer Poll Query Option Key`)  WHERE `Customer Poll Query Key`=? AND `Customer Poll Customer Key`=?";


            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->id,
                    $customer_key
                )
            );
            if ($row = $stmt->fetch()) {
                return array(
                    $row['Customer Poll Query Option Name'],
                    $row['Customer Poll Query Option Label'],
                    $row['Customer Poll Query Option Key']
                );
            } else {
                return array(
                    '',
                    '',
                    0
                );
            }


        }


    }

    /**
     * @param $customer \Customer
     * @param $value
     */
    public function add_customer($customer, $value) {

        if ($customer->get('Store Key') != $this->get('Store Key')) {

            $this->error = true;
            $this->msg   = 'customer not in poll store';

            return;
        }


        if ($this->get('Customer Poll Query Type') == 'Open') {


            $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d ', $customer->id, $this->id);
            $this->db->exec($sql);

            if ($value != '') {
                $sql = sprintf(
                    'INSERT INTO `Customer Poll Fact` (`Customer Poll Customer Key`,`Customer Poll Query Key`,`Customer Poll Reply`,`Customer Poll Date`) VALUES (%d,%d,%s,%s)', $customer->id, $this->id, prepare_mysql($value), prepare_mysql(gmdate('Y-m-d H:i:s'))
                );
                $this->db->exec($sql);
            }


            $this->update_answers();


        } else {

            $poll_option = get_object('Customer_Poll_Query_Option', $value);

            if ($poll_option->get('Query Key') != $this->id) {

                $this->error = true;
                $this->msg   = 'option not in poll';

                return;
            }


            $sql = sprintf(
                'SELECT `Customer Poll Key` FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d AND `Customer Poll Query Option Key`=%d ', $customer->id, $this->id, $value
            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d  AND `Customer Poll Key`!=%d ', $customer->id, $this->id, $row['Customer Poll Key']);
                    $this->db->exec($sql);

                } else {
                    $sql = sprintf('DELETE FROM `Customer Poll Fact` WHERE `Customer Poll Customer Key`=%d AND `Customer Poll Query Key`=%d ', $customer->id, $this->id);
                    $this->db->exec($sql);

                    $sql = sprintf(
                        'INSERT INTO `Customer Poll Fact` (`Customer Poll Customer Key`,`Customer Poll Query Key`,`Customer Poll Query Option Key`,`Customer Poll Date`) VALUES (%d,%d,%d,%s)', $customer->id, $this->id, $value, prepare_mysql(gmdate('Y-m-d H:i:s'))
                    );
                    $this->db->exec($sql);


                }

                $poll_option->update_poll_query_option_customers();

                $this->update_answers();
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }

    }


    function delete() {

        $replies = array();
        switch ($this->data['Customer Poll Query Type']) {
            case 'Open':

                $sql = sprintf('select `Customer Poll Customer Key`,`Customer Poll Reply` from `Customer Poll Fact` where `Customer Poll Query Key`=%d ', $this->id);

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $replies[$row['Customer Poll Customer Key']] = $row['Customer Poll Reply'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;

            case 'Options':

                $options = array();

                $sql = sprintf('select `Customer Poll Query Option Key`,`Customer Poll Query Option Name`,`Customer Poll Query Option Label` from `Customer Poll Query Option Dimension` where `Customer Poll Query Option Query Key`=%d ', $this->id);

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $options[$row['Customer Poll Query Option Key']] = array(
                            $row['Customer Poll Query Option Name'],
                            $row['Customer Poll Query Option Label']
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf('select `Customer Poll Customer Key`,`Customer Poll Query Option Key` from `Customer Poll Fact` where `Customer Poll Query Key`=%d ', $this->id);

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $replies[$row['Customer Poll Customer Key']] = $row['Customer Poll Query Option Key'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;

            default:

        }


        $data = $this->data;
        unset($data['Customer Poll Query Key']);
        unset($data['Customer Poll Query Name']);
        unset($data['Customer Poll Query Label']);
        $data['Replies'] = $replies;


        $sql = sprintf(
            'INSERT INTO `Customer Poll Query Deleted Dimension`  (`Customer Poll Query Deleted Key`,`Customer Poll Query Deleted Date`,`Customer Poll Query Deleted Name`,`Customer Poll Query Deleted Label`,`Customer Poll Query Deleted Metadata`) VALUES (%d,%s,%s,%s,%s) ',
            $this->id, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($this->get('Customer Poll Query Name')), prepare_mysql($this->get('Customer Poll Query Label')), prepare_mysql(gzcompress(json_encode($data), 9))


        );

        $this->db->exec($sql);


        switch ($this->data['Customer Poll Query Type']) {
            case 'Open':


                $sql = sprintf('delete from `Customer Poll Fact` where `Customer Poll Query Key`=%d ', $this->id);
                $this->db->exec($sql);


                break;

            case 'Options':


                $sql = sprintf('delete from `Customer Poll Query Option Dimension` where `Customer Poll Query Option Query Key`=%d ', $this->id);
                $this->db->exec($sql);

                $sql = sprintf('delete from `Customer Poll Fact` where `Customer Poll Query Key`=%d ', $this->id);
                $this->db->exec($sql);

                break;

            default:

        }


        $sql = sprintf(
            'DELETE FROM `Customer Poll Query Dimension`  WHERE `Customer Poll Query Key`=%d ', $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => sprintf(
                _('Customer poll %s deleted'), $this->data['Customer Poll Query Name']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );

        $this->update_website();

        $this->deleted = true;
    }


    function update_website() {
        $store                = get_object('Public_Store', $this->data['Customer Poll Query Store Key']);
        $website              = get_object('Website', $store->get('Store Website Key'));
        $registration_webpage = $website->get_webpage('register.sys');

        $registration_webpage->reindex_items();
    }


}


?>
