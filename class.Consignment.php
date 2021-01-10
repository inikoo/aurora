<?php
/*

  About:
  Author: Raul Perusquia <rulovico@gmail.com>
  Created: 3:08 pm Tuesday, 5 January 2021 (MYT) Kuala Lumpur, Malaysia

  Copyright (c) 2021,Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';



class Consignment extends DB_Table {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Consignment';
        $this->ignore_fields = array('Consignment Key');

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No data provided';

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'create') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);
    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Consignment Dimension` WHERE  `Consignment Key`=%d", $tag
            );
        } elseif ($tipo == 'public_id') {
            $sql = sprintf(
                "SELECT * FROM `Consignment Dimension` WHERE  `Consignment Public ID`=%s", prepare_mysql($tag)
            );

        } else {

            // print
            return;
        }
        //   print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['Consignment Key'];
            $this->metadata = json_decode($this->data['Consignment Metadata'], true);


        }


    }

    protected function create($consignment_data, $order) {


        $base_data = $this->base_data();

        $this->editor = $consignment_data['editor'];
        unset($consignment_data['editor']);


        foreach ($consignment_data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $base_data['Consignment Metadata']    = '{}';

        $sql = sprintf(
            "INSERT INTO `Consignment Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id = $this->db->query("SELECT LAST_INSERT_ID()")->fetchColumn();
            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }

            $this->get_data('id', $this->id);

            if (!is_array($this->data)) {
                throw new Exception('Error data not '.$this->id.' loaded '.$this->table_name);
                Sentry\captureMessage('Error  '.$this->id.'  '.$base_data['Consignment Public ID'].'   '.$this->table_name);


            }





            $history_data = array(
                'History Abstract' => _('Consignment created'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);



            //$this->fork_index_elastic_search();


        } else {
            exit ("$sql \n Error can not create dn header");
        }

        return $this;


    }


    function get($key) {


        if (!$this->id) {
            return '';
        }

        switch ($key) {

            case 'Creation Date':

            case 'Order Date Placed':
            case 'Date Created':

                if($this->data['Consignment '.$key]!=''){
                    return strftime("%e %b %y", strtotime($this->data['Consignment '.$key].' +0:00'));

                }else{
                    return '';
                }


        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (array_key_exists('Consignment '.$key, $this->data)) {
            return $this->data[$this->table_name.' '.$key];
        }


        return false;
    }

    function update_totals() {




    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {


            case 'Consignment State':

                $this->update_state($value, $options, $metadata);
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

    function update_state($value) {

        include_once 'utils/new_fork.php';

        $date = gmdate('Y-m-d H:i:s');




        $operations = array();

        $old_state = $this->get('Consignment State');

        switch ($value) {



            default:
                exit('unknown state '.$value);
                break;
        }




        $this->update_totals();


        $this->update_metadata = array(
            'class_html'  => array(


            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index')
        );

        if ($old_state != $this->get('Consignment State')) {
            //$this->fork_index_elastic_search();
            return true;
        }

        return false;

    }





    function get_field_label($field) {

        switch ($field) {




            default:
                $label = $field;

        }

        return $label;

    }



    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }





}


