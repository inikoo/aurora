<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 17:59:10 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
*/

include_once 'class.DB_Table.php';

class Clocking_Machine extends DB_Table{

    /**
     * @var \PDO
     */
    public $db;

    /**
     * @var integer
     */
    public $id;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name = 'Clocking Machine';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'new') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);


    }


    function get_data($key, $id) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Clocking Machine Dimension` WHERE `Clocking Machine Key`=%d", $id
            );

        } else {

            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Clocking Machine Key'];
            $this->metadata = json_decode($this->data['Clocking Machine Metadata'], true);

        }


    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }


    function create($raw_data, $address_raw_data) {


        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        $this->data['Clocking Machine Creation Date'] = gmdate('Y-m-d H:i:s');


        $sql = sprintf(
            "INSERT INTO `Clocking Machine Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);




            $history_data = array(
                'History Abstract' => sprintf(_("Clocking-in machine created (%s)"), $this->get('Code')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'          => 'Clocking Machine',
                'Subject Key'      => $this->id,
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


        } else {
            $this->error = true;
            print_r($stmt->errorInfo());
            $this->msg = 'Error inserting Clocking Machine record';
        }


    }


    function get($key) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case('Creation Date'):
                if ($this->data['Clocking Machine '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Clocking Machine '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Clocking Machine '.$key]." +00:00")
                    ).'</span>';
                break;



            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Clocking Machine '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                return '';

        }

    }





    function update_field_switcher($field, $value, $options = '', $metadata = array()) {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {



            case 'Clocking Machine Timezone':

                $this->update_field($field, $value, $options);

                break;

            default:


        }
    }



    function get_field_label($field) {


        switch ($field) {


            case 'Clocking Machine Code':
                $label = _('name');
                break;
            case 'Clocking Machine Serial Number':
                $label = _('serial number');
                break;
            case 'Clocking Machine Timezone':
                $label = _('timezone');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


}

